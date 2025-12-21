<?php
/**
 * Zed CMS - Auth Security Migration
 * 
 * Adds security columns to the users table for:
 * - Brute-force protection (failed_attempts, locked_until)
 * - Remember me functionality (remember_token)
 * - Activity tracking (last_login)
 * 
 * Run this script once to upgrade an existing installation.
 * The Auth class also self-heals, so this is optional.
 * 
 * Usage: php migrate_auth_security.php
 */

declare(strict_types=1);

// Load config
$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    die("Error: config.php not found. Please run install.php first.\n");
}

$config = require $configPath;

try {
    // Connect to database
    $dbConfig = $config['database'];
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "Connected to database: {$dbConfig['name']}\n";
    echo "Checking users table...\n\n";
    
    // Get current columns
    $stmt = $pdo->query("DESCRIBE users");
    $existingColumns = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');
    
    $migrations = [
        'remember_token' => "ALTER TABLE users ADD COLUMN remember_token VARCHAR(64) NULL COMMENT 'Hashed token for persistent login'",
        'last_login' => "ALTER TABLE users ADD COLUMN last_login DATETIME NULL COMMENT 'Last successful login timestamp'",
        'failed_attempts' => "ALTER TABLE users ADD COLUMN failed_attempts INT NOT NULL DEFAULT 0 COMMENT 'Failed login attempts counter'",
        'locked_until' => "ALTER TABLE users ADD COLUMN locked_until DATETIME NULL COMMENT 'Account lockout expiry time'",
    ];
    
    $migratedCount = 0;
    
    foreach ($migrations as $column => $sql) {
        if (in_array($column, $existingColumns)) {
            echo "✓ Column '{$column}' already exists.\n";
        } else {
            echo "→ Adding column '{$column}'... ";
            $pdo->exec($sql);
            echo "✓ Done\n";
            $migratedCount++;
        }
    }
    
    // Add index for remember_token if not exists
    echo "\nChecking indexes...\n";
    $stmt = $pdo->query("SHOW INDEX FROM users WHERE Key_name = 'idx_remember_token'");
    if ($stmt->rowCount() === 0) {
        echo "→ Adding index 'idx_remember_token'... ";
        $pdo->exec("ALTER TABLE users ADD INDEX idx_remember_token (remember_token)");
        echo "✓ Done\n";
        $migratedCount++;
    } else {
        echo "✓ Index 'idx_remember_token' already exists.\n";
    }
    
    echo "\n========================================\n";
    if ($migratedCount > 0) {
        echo "Migration complete! {$migratedCount} change(s) applied.\n";
    } else {
        echo "Database is already up to date.\n";
    }
    echo "========================================\n\n";
    
    echo "Security Features Enabled:\n";
    echo "• Brute-force protection (5 attempts, 10 min lockout)\n";
    echo "• Remember me (30 day persistent login)\n";
    echo "• Session fixation prevention\n";
    echo "• Last login tracking\n";
    
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage() . "\n");
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
