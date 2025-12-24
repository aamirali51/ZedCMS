<?php
/**
 * Zed CMS — Cache/Transient Helpers
 * 
 * Temporary data storage with expiration.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Database;

/**
 * Set a transient value with expiration
 * 
 * @param string $key Transient key
 * @param mixed $value Value to store (will be JSON encoded)
 * @param int $expiration Seconds until expiration (default: 1 hour)
 * @return bool Success
 */
function zed_set_transient(string $key, mixed $value, int $expiration = 3600): bool
{
    try {
        $db = Database::getInstance();
        $optionKey = '_transient_' . $key;
        $data = json_encode([
            'value' => $value,
            'expires' => time() + $expiration,
        ]);
        
        // Upsert the transient
        $existing = $db->queryOne(
            "SELECT id FROM zed_options WHERE option_key = :key",
            ['key' => $optionKey]
        );
        
        if ($existing) {
            $db->query(
                "UPDATE zed_options SET option_value = :value WHERE option_key = :key",
                ['value' => $data, 'key' => $optionKey]
            );
        } else {
            $db->query(
                "INSERT INTO zed_options (option_key, option_value, autoload) VALUES (:key, :value, 0)",
                ['key' => $optionKey, 'value' => $data]
            );
        }
        
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Get a transient value
 * 
 * @param string $key Transient key
 * @param mixed $default Default if not found or expired
 * @return mixed Stored value or default
 */
function zed_get_transient(string $key, mixed $default = null): mixed
{
    try {
        $db = Database::getInstance();
        $optionKey = '_transient_' . $key;
        
        $row = $db->queryOne(
            "SELECT option_value FROM zed_options WHERE option_key = :key",
            ['key' => $optionKey]
        );
        
        if (!$row) {
            return $default;
        }
        
        $data = json_decode($row['option_value'], true);
        
        if (!$data || !isset($data['expires'])) {
            return $default;
        }
        
        // Check expiration
        if ($data['expires'] < time()) {
            zed_delete_transient($key);
            return $default;
        }
        
        return $data['value'] ?? $default;
    } catch (\Exception $e) {
        return $default;
    }
}

/**
 * Delete a transient
 * 
 * @param string $key Transient key
 * @return bool Success
 */
function zed_delete_transient(string $key): bool
{
    try {
        $db = Database::getInstance();
        $optionKey = '_transient_' . $key;
        
        $db->query(
            "DELETE FROM zed_options WHERE option_key = :key",
            ['key' => $optionKey]
        );
        
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Clean up all expired transients
 * 
 * @return int Number of deleted transients
 */
function zed_cleanup_transients(): int
{
    try {
        $db = Database::getInstance();
        
        // Get all transients
        $transients = $db->query(
            "SELECT option_key, option_value FROM zed_options WHERE option_key LIKE '_transient_%'"
        );
        
        $deleted = 0;
        foreach ($transients as $transient) {
            $data = json_decode($transient['option_value'], true);
            
            if (!$data || !isset($data['expires']) || $data['expires'] < time()) {
                $db->query(
                    "DELETE FROM zed_options WHERE option_key = :key",
                    ['key' => $transient['option_key']]
                );
                $deleted++;
            }
        }
        
        return $deleted;
    } catch (\Exception $e) {
        return 0;
    }
}

/**
 * Get or set transient with callback
 * 
 * If transient exists and not expired, returns cached value.
 * Otherwise, calls callback, stores result, and returns it.
 * 
 * @param string $key Transient key
 * @param callable $callback Function to generate value
 * @param int $expiration Seconds until expiration
 * @return mixed Cached or fresh value
 */
function zed_remember(string $key, callable $callback, int $expiration = 3600): mixed
{
    $cached = zed_get_transient($key);
    
    if ($cached !== null) {
        return $cached;
    }
    
    $value = $callback();
    zed_set_transient($key, $value, $expiration);
    
    return $value;
}
