<?php

declare(strict_types=1);

namespace Core;

/**
 * Zed CMS Migration System
 * 
 * Handles safe version upgrades with incremental migrations.
 * Works on shared hosting without CLI requirements.
 * 
 * Usage:
 *   - Called automatically during app_init
 *   - Migrations run once per version, tracked in zed_options
 *   - Add new migrations to the $migrations array
 * 
 * @package Core
 */
final class Migrations
{
    /**
     * Current CMS version
     */
    public const VERSION = '3.2.0';

    /**
     * Option names used for tracking
     */
    private const OPTION_VERSION = 'zed_version';
    private const OPTION_MIGRATIONS_LOG = 'zed_migrations_log';

    /**
     * Database instance
     */
    private static ?\PDO $pdo = null;

    /**
     * Define all migrations here.
     * Each key is a version number, value is an array of migration closures.
     * Migrations are executed in version order, then in array order within each version.
     * 
     * @return array<string, array<callable>>
     */
    private static function getMigrations(): array
    {
        return [
            '1.9.0' => [
                // Add active_addons column if not exists (idempotent)
                function(\PDO $pdo) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM zed_options WHERE option_name = 'active_addons'");
                    $stmt->execute();
                },
                
                // Add active_theme option if not exists
                function(\PDO $pdo) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM zed_options WHERE option_name = 'active_theme'");
                    $stmt->execute();
                    if ($stmt->fetchColumn() == 0) {
                        $pdo->prepare("INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('active_theme', 'starter-theme', 1)")
                            ->execute();
                    }
                },
            ],
            
            '2.0.0' => [
                // Create zed_content_revisions table for content revision system
                function(\PDO $pdo) {
                    $sql = "CREATE TABLE IF NOT EXISTS zed_content_revisions (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        content_id INT NOT NULL,
                        data_json JSON NOT NULL,
                        author_id INT NOT NULL,
                        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                        INDEX idx_content_id (content_id),
                        INDEX idx_created_at (created_at),
                        CONSTRAINT fk_revision_content FOREIGN KEY (content_id) 
                            REFERENCES zed_content(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                    
                    $pdo->exec($sql);
                },
            ],

            '3.2.0' => [
                // Create zed_comments table for comments system
                function(\PDO $pdo) {
                    $sql = "CREATE TABLE IF NOT EXISTS zed_comments (
                        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        post_id INT UNSIGNED NOT NULL,
                        parent_id INT UNSIGNED DEFAULT 0,
                        user_id INT UNSIGNED DEFAULT NULL,
                        author_name VARCHAR(100) NOT NULL,
                        author_email VARCHAR(255) NOT NULL,
                        author_url VARCHAR(255) DEFAULT NULL,
                        content TEXT NOT NULL,
                        status ENUM('pending', 'approved', 'spam', 'trash') DEFAULT 'pending',
                        ip_address VARCHAR(45) DEFAULT NULL,
                        user_agent VARCHAR(255) DEFAULT NULL,
                        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        INDEX idx_post_id (post_id),
                        INDEX idx_parent_id (parent_id),
                        INDEX idx_status (status),
                        INDEX idx_created_at (created_at)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                    
                    $pdo->exec($sql);
                },

                // Add comments_enabled option
                function(\PDO $pdo) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM zed_options WHERE option_name = 'comments_enabled'");
                    $stmt->execute();
                    if ($stmt->fetchColumn() == 0) {
                        $pdo->prepare("INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('comments_enabled', '1', 1)")
                            ->execute();
                    }
                },

                // Add comments_moderation option (1 = require approval)
                function(\PDO $pdo) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM zed_options WHERE option_name = 'comments_moderation'");
                    $stmt->execute();
                    if ($stmt->fetchColumn() == 0) {
                        $pdo->prepare("INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('comments_moderation', '1', 1)")
                            ->execute();
                    }
                },
            ],
        ];
    }

    /**
     * Run pending migrations.
     * This is the main entry point called during app initialization.
     * 
     * @return array{executed: int, skipped: int, errors: array<string>}
     */
    public static function run(): array
    {
        $result = [
            'executed' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        try {
            $pdo = self::getPdo();
            if (!$pdo) {
                return $result;
            }

            // Get current installed version
            $currentVersion = self::getOption(self::OPTION_VERSION);
            
            // Get migration log
            $logJson = self::getOption(self::OPTION_MIGRATIONS_LOG);
            $migrationLog = $logJson ? json_decode($logJson, true) : [];
            if (!is_array($migrationLog)) {
                $migrationLog = [];
            }

            $migrations = self::getMigrations();
            
            // Sort migrations by version
            uksort($migrations, 'version_compare');

            foreach ($migrations as $version => $versionMigrations) {
                // Skip if this version's migrations are already complete
                if ($currentVersion && version_compare($version, $currentVersion, '<=')) {
                    // Check if individual migrations within this version ran
                    foreach ($versionMigrations as $index => $migration) {
                        $migrationKey = $version . '_' . $index;
                        if (!isset($migrationLog[$migrationKey])) {
                            // Migration not logged, run it
                            try {
                                $migration($pdo);
                                $migrationLog[$migrationKey] = [
                                    'executed_at' => date('Y-m-d H:i:s'),
                                    'version' => $version,
                                ];
                                $result['executed']++;
                            } catch (\Exception $e) {
                                $result['errors'][] = "Migration {$migrationKey} failed: " . $e->getMessage();
                            }
                        } else {
                            $result['skipped']++;
                        }
                    }
                    continue;
                }

                // Run migrations for versions newer than current
                foreach ($versionMigrations as $index => $migration) {
                    $migrationKey = $version . '_' . $index;
                    
                    if (isset($migrationLog[$migrationKey])) {
                        $result['skipped']++;
                        continue;
                    }

                    try {
                        $migration($pdo);
                        $migrationLog[$migrationKey] = [
                            'executed_at' => date('Y-m-d H:i:s'),
                            'version' => $version,
                        ];
                        $result['executed']++;
                    } catch (\Exception $e) {
                        $result['errors'][] = "Migration {$migrationKey} failed: " . $e->getMessage();
                    }
                }
            }

            // Update version and log
            self::setOption(self::OPTION_VERSION, self::VERSION);
            self::setOption(self::OPTION_MIGRATIONS_LOG, json_encode($migrationLog));

        } catch (\Exception $e) {
            $result['errors'][] = 'Migration system error: ' . $e->getMessage();
        }

        return $result;
    }

    /**
     * Get the current installed version.
     * 
     * @return string|null
     */
    public static function getInstalledVersion(): ?string
    {
        try {
            return self::getOption(self::OPTION_VERSION);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if migrations are needed.
     * 
     * @return bool
     */
    public static function needsMigration(): bool
    {
        $installed = self::getInstalledVersion();
        if (!$installed) {
            return true;
        }
        return version_compare($installed, self::VERSION, '<');
    }

    /**
     * Get the migration log.
     * 
     * @return array<string, array{executed_at: string, version: string}>
     */
    public static function getLog(): array
    {
        try {
            $logJson = self::getOption(self::OPTION_MIGRATIONS_LOG);
            $log = $logJson ? json_decode($logJson, true) : [];
            return is_array($log) ? $log : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get PDO instance (creates connection if needed).
     * 
     * @return \PDO|null
     */
    private static function getPdo(): ?\PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        try {
            // Try to get from Database singleton first
            $db = Database::getInstance();
            self::$pdo = $db->getPdo();
            return self::$pdo;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get an option value from zed_options.
     * 
     * @param string $name Option name
     * @return string|null
     */
    private static function getOption(string $name): ?string
    {
        $pdo = self::getPdo();
        if (!$pdo) {
            return null;
        }

        try {
            $stmt = $pdo->prepare("SELECT option_value FROM zed_options WHERE option_name = :name LIMIT 1");
            $stmt->execute(['name' => $name]);
            $result = $stmt->fetchColumn();
            return $result !== false ? (string)$result : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Set an option value in zed_options.
     * 
     * @param string $name Option name
     * @param string $value Option value
     * @return bool
     */
    private static function setOption(string $name, string $value): bool
    {
        $pdo = self::getPdo();
        if (!$pdo) {
            return false;
        }

        try {
            // Check if exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM zed_options WHERE option_name = :name");
            $stmt->execute(['name' => $name]);
            $exists = $stmt->fetchColumn() > 0;

            if ($exists) {
                $stmt = $pdo->prepare("UPDATE zed_options SET option_value = :value WHERE option_name = :name");
            } else {
                $stmt = $pdo->prepare("INSERT INTO zed_options (option_name, option_value, autoload) VALUES (:name, :value, 1)");
            }

            return $stmt->execute(['name' => $name, 'value' => $value]);
        } catch (\Exception $e) {
            return false;
        }
    }
}

/**
 * Helper function to run migrations.
 * Can be called manually if needed.
 * 
 * @return array{executed: int, skipped: int, errors: array<string>}
 */
function zed_run_migrations(): array
{
    return Migrations::run();
}
