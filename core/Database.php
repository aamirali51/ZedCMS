<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Zed CMS Database Wrapper (The Schemaless Engine)
 * 
 * A singleton PDO wrapper with JSON column support.
 * Optimized for a "schemaless" design where we heavily use JSON columns.
 */
final class Database
{
    /**
     * The singleton instance.
     */
    private static ?Database $instance = null;

    /**
     * Stored configuration for lazy initialization.
     *
     * @var array<string, mixed>|null
     */
    private static ?array $storedConfig = null;

    /**
     * The PDO connection.
     */
    private PDO $pdo;

    /**
     * Query log for debugging.
     *
     * @var array<array{sql: string, params: array, time: float}>
     */
    private array $queryLog = [];

    /**
     * Whether to log queries (debug mode).
     */
    private bool $logging = false;

    /**
     * Private constructor - use getInstance() instead.
     *
     * @param array<string, mixed> $config Database configuration.
     */
    private function __construct(array $config)
    {
        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? 3306;
        $name = $config['name'] ?? 'zed_cms';
        $user = $config['user'] ?? 'root';
        $pass = $config['password'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                // Throw exceptions on errors
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                // Return associative arrays by default
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Don't emulate prepared statements (more secure)
                PDO::ATTR_EMULATE_PREPARES   => false,
                // Use persistent connections for performance
                PDO::ATTR_PERSISTENT         => true,
                PDO::ATTR_STRINGIFY_FETCHES  => false
            ]);
        } catch (PDOException $e) {
            throw new PDOException(
                "Database connection failed: " . $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
    }

    /**
     * Prevent cloning of the singleton.
     */
    private function __clone(): void {}

    /**
     * Prevent unserialization of the singleton.
     */
    public function __wakeup(): void
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * Store database configuration for lazy initialization.
     * The connection will only be established when getInstance() is called.
     *
     * @param array<string, mixed> $config Database configuration.
     * @return void
     */
    public static function setConfig(array $config): void
    {
        self::$storedConfig = $config;
    }

    /**
     * Get the singleton database instance.
     * If config was set via setConfig(), uses that config.
     *
     * @param array<string, mixed>|null $config Database config (only used on first call).
     * @return self The database instance.
     */
    public static function getInstance(?array $config = null): self
    {
        if (self::$instance === null) {
            // Use provided config, or fall back to stored config
            $config = $config ?? self::$storedConfig;
            
            if ($config === null) {
                throw new \RuntimeException(
                    "Database not initialized. Call setConfig() or getInstance() with config first."
                );
            }
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    /**
     * Get the raw PDO instance (for advanced usage).
     *
     * @return PDO The PDO connection.
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Execute a query and return the result.
     * 
     * For SELECT queries, returns an array of rows.
     * For INSERT, returns the last insert ID.
     * For UPDATE/DELETE, returns the number of affected rows.
     *
     * @param string               $sql    The SQL query with placeholders.
     * @param array<string, mixed> $params Parameters for prepared statement.
     * @return array|int|string Query result.
     */
    public function query(string $sql, array $params = []): array|int|string
    {
        $startTime = microtime(true);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // Log query if logging is enabled
        if ($this->logging) {
            $this->queryLog[] = [
                'sql'    => $sql,
                'params' => $params,
                'time'   => microtime(true) - $startTime,
            ];
        }

        // Determine what to return based on query type
        $sqlType = strtoupper(substr(ltrim($sql), 0, 6));

        return match ($sqlType) {
            'SELECT', 'SHOW  ', 'DESCRI' => $stmt->fetchAll(),
            'INSERT' => $this->pdo->lastInsertId(),
            default  => $stmt->rowCount(),
        };
    }

    /**
     * Execute a query and return a single row.
     *
     * @param string               $sql    The SQL query.
     * @param array<string, mixed> $params Parameters for prepared statement.
     * @return array|null Single row or null if not found.
     */
    public function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return $result === false ? null : $result;
    }

    /**
     * Execute a query and return a single value.
     *
     * @param string               $sql    The SQL query.
     * @param array<string, mixed> $params Parameters for prepared statement.
     * @return mixed The single value or null.
     */
    public function queryValue(string $sql, array $params = []): mixed
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn();
    }

    /**
     * Insert a row with JSON column support.
     * 
     * Automatically converts arrays to JSON strings.
     *
     * @param string               $table The table name.
     * @param array<string, mixed> $data  Column => value pairs (arrays become JSON).
     * @return string The last insert ID.
     */
    public function insert(string $table, array $data): string
    {
        $data = $this->prepareJsonData($data);

        $table = $this->escapeIdentifier($table);
        $columns = implode(', ',
         array_map(
            fn (string $column): string => $this->escapeIdentifier($column), 
            array_keys($data))
        );
        
        $placeholders = implode(', ', array_map(fn($k) => ":{$k}", array_keys($data)));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $this->query($sql, $data);

        return $this->pdo->lastInsertId();
    }

    /**
     * Update rows with JSON column support.
     *
     * @param string               $table The table name.
     * @param array<string, mixed> $data  Column => value pairs to update.
     * @param string               $where WHERE clause (without "WHERE").
     * @param array<string, mixed> $whereParams Parameters for WHERE clause.
     * @return int Number of affected rows.
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $data = $this->prepareJsonData($data);

        $table = $this->escapeIdentifier($table);

        $setParts = [];
        foreach ($data as $column => $value) {
            $column = $this->escapeIdentifier($column);

            $setParts[] = "{$column} = :set_{$column}";
        }
        $setClause = implode(', ', $setParts);

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";

        // Prefix data keys to avoid collision with where params
        $params = [];
        foreach ($data as $k => $v) {
            $params["set_{$k}"] = $v;
        }
        $params = array_merge($params, $whereParams);

        return (int) $this->query($sql, $params);
    }

    /**
     * Delete rows from a table.
     *
     * @param string               $table The table name.
     * @param string               $where WHERE clause (without "WHERE").
     * @param array<string, mixed> $params Parameters for WHERE clause.
     * @return int Number of deleted rows.
     */
    public function delete(string $table, string $where, array $params = []): int
    {
        $table = $this->escapeIdentifier($table);

        $sql = "DELETE FROM {$table} WHERE {$where}";
        return (int) $this->query($sql, $params);
    }

    /**
     * Insert a PHP array as a JSON column value.
     * 
     * Convenience method for working with JSON columns.
     *
     * @param string               $table  The table name.
     * @param string               $column The JSON column name.
     * @param array<mixed>         $data   The array to store as JSON.
     * @param string               $where  WHERE clause for existing row.
     * @param array<string, mixed> $whereParams Parameters for WHERE.
     * @return int Affected rows (for update).
     */
    public function json(string $table, string $column, array $data, string $where, array $whereParams = []): int
    {
        return $this->update(
            $table,
            [$column => $data],
            $where,
            $whereParams
        );
    }

    /**
     * Read and decode a JSON column.
     *
     * @param string               $table  The table name.
     * @param string               $column The JSON column name.
     * @param string               $where  WHERE clause.
     * @param array<string, mixed> $params WHERE parameters.
     * @return array<mixed>|null The decoded array or null.
     */
    public function jsonGet(string $table, string $column, string $where, array $params = []): ?array
    {
        $table = $this->escapeIdentifier($table);
        $column = $this->escapeIdentifier($column);
        
        $sql = "SELECT {$column} FROM {$table} WHERE {$where} LIMIT 1";
        $result = $this->queryValue($sql, $params);

        if ($result === null || $result === false) {
            return null;
        }

        $decoded = json_decode($result, true);
        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Merge data into an existing JSON column.
     * 
     * Uses MySQL's JSON_MERGE_PATCH for atomic updates.
     *
     * @param string               $table  The table name.
     * @param string               $column The JSON column name.
     * @param array<mixed>         $data   Data to merge into the JSON.
     * @param string               $where  WHERE clause.
     * @param array<string, mixed> $whereParams WHERE parameters.
     * @return int Affected rows.
     */
    public function jsonMerge(string $table, string $column, array $data, string $where, array $whereParams = []): int
    {
        $table = $this->escapeIdentifier($table);
        $column = $this->escapeIdentifier($column);

        $jsonData = json_encode($data, JSON_THROW_ON_ERROR);

        $sql = "UPDATE {$table} SET {$column} = JSON_MERGE_PATCH(COALESCE({$column}, '{}'), :json_data) WHERE {$where}";

        $params = array_merge(['json_data' => $jsonData], $whereParams);

        return (int) $this->query($sql, $params);
    }

    /**
     * Prepare data for insertion, converting arrays to JSON.
     *
     * @param array<string, mixed> $data The data to prepare.
     * @return array<string, mixed> Prepared data with JSON strings.
     */
    private function prepareJsonData(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = json_encode($value, JSON_THROW_ON_ERROR);
            }
        }
        return $data;
    }

    /**
     * Begin a transaction.
     *
     * @return bool True on success.
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction.
     *
     * @return bool True on success.
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback a transaction.
     *
     * @return bool True on success.
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * Execute a callback within a transaction.
     *
     * @param callable $callback The callback to execute.
     * @return mixed The callback's return value.
     * @throws \Throwable Re-throws any exception after rollback.
     */
    public function transaction(callable $callback): mixed
    {
        $this->beginTransaction();

        try {
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * Enable or disable query logging.
     *
     * @param bool $enable True to enable, false to disable.
     * @return self For chaining.
     */
    public function setLogging(bool $enable): self
    {
        $this->logging = $enable;
        return $this;
    }

    /**
     * Get the query log.
     *
     * @return array<array{sql: string, params: array, time: float}> The log.
     */
    public function getQueryLog(): array
    {
        return $this->queryLog;
    }

    /**
     * Clear the query log.
     *
     * @return self For chaining.
     */
    public function clearQueryLog(): self
    {
        $this->queryLog = [];
        return $this;
    }

    /**
     * Check if a table exists.
     *
     * @param string $table The table name.
     * @return bool True if exists.
     */
    public function tableExists(string $table): bool
    {
        $sql = "SHOW TABLES LIKE :table";
        $result = $this->queryValue($sql, ['table' => $table]);
        return $result !== false && $result !== null;
    }
    
    protected function escapeIdentifier(string $identifier): string
    {
        $escapedIdentifier = strtr($identifier, [
            '\\' => '\\\\',
            "\n" => '\\n',
            "\r" => '\\r',
            "\t" => '\\t',
            "\0" => '\\0',
            "\b" => '\\b',
            "\x1A" => '\\Z',
            "'" => "\\'",
            '"' => '\\"',
            '`' => '\\`',
        ]);

        return "`{$escapedIdentifier}`";
    }

    /**
     * Reset the singleton (useful for testing).
     *
     * @return void
     */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }
}
