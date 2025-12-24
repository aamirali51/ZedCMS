<?php
/**
 * Caching API
 * 
 * Simple file-based caching for Zed CMS.
 * Provides a WordPress-like transients API.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

// =============================================================================
// CACHE CONFIGURATION
// =============================================================================

if (!defined('ZED_CACHE_DIR')) {
    define('ZED_CACHE_DIR', dirname(__DIR__, 3) . '/cache');
}

if (!defined('ZED_CACHE_ENABLED')) {
    define('ZED_CACHE_ENABLED', true);
}

// =============================================================================
// CACHE API FUNCTIONS
// =============================================================================

/**
 * Store a value in the cache.
 * 
 * @param string $key Cache key
 * @param mixed $value Value to cache (will be serialized)
 * @param int $expiration Time to live in seconds (0 = never expires)
 * @return bool True on success
 */
function zed_cache_set(string $key, mixed $value, int $expiration = 3600): bool
{
    if (!ZED_CACHE_ENABLED) {
        return false;
    }
    
    $cacheDir = ZED_CACHE_DIR;
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
        file_put_contents($cacheDir . '/.htaccess', "Deny from all\n");
    }
    
    $data = [
        'value' => $value,
        'expires' => $expiration > 0 ? time() + $expiration : 0,
        'created' => time(),
    ];
    
    $file = $cacheDir . '/' . md5($key) . '.cache';
    return file_put_contents($file, serialize($data)) !== false;
}

/**
 * Retrieve a value from the cache.
 * 
 * @param string $key Cache key
 * @param mixed $default Default value if not found or expired
 * @return mixed Cached value or default
 */
function zed_cache_get(string $key, mixed $default = null): mixed
{
    if (!ZED_CACHE_ENABLED) {
        return $default;
    }
    
    $file = ZED_CACHE_DIR . '/' . md5($key) . '.cache';
    
    if (!file_exists($file)) {
        return $default;
    }
    
    $data = unserialize(file_get_contents($file));
    
    if ($data === false) {
        return $default;
    }
    
    // Check expiration
    if ($data['expires'] > 0 && $data['expires'] < time()) {
        @unlink($file);
        return $default;
    }
    
    return $data['value'];
}

/**
 * Delete a cached value.
 * 
 * @param string $key Cache key
 * @return bool True if deleted
 */
function zed_cache_delete(string $key): bool
{
    $file = ZED_CACHE_DIR . '/' . md5($key) . '.cache';
    
    if (file_exists($file)) {
        return @unlink($file);
    }
    
    return true;
}

/**
 * Clear all cached values.
 * 
 * @param string|null $pattern Optional pattern to match keys (not implemented for file cache)
 * @return int Number of items cleared
 */
function zed_cache_flush(?string $pattern = null): int
{
    $cacheDir = ZED_CACHE_DIR;
    
    if (!is_dir($cacheDir)) {
        return 0;
    }
    
    $count = 0;
    foreach (glob($cacheDir . '/*.cache') as $file) {
        if (@unlink($file)) {
            $count++;
        }
    }
    
    return $count;
}

/**
 * Get or set a cached value using a callback.
 * "Remember" pattern - if cache exists, return it; otherwise compute and cache.
 * 
 * @param string $key Cache key
 * @param callable $callback Function to compute value if not cached
 * @param int $expiration TTL in seconds
 * @return mixed Cached or computed value
 */
function zed_cache_remember(string $key, callable $callback, int $expiration = 3600): mixed
{
    $cached = zed_cache_get($key);
    
    if ($cached !== null) {
        return $cached;
    }
    
    $value = $callback();
    zed_cache_set($key, $value, $expiration);
    
    return $value;
}

/**
 * Check if a cache key exists and is not expired.
 * 
 * @param string $key Cache key
 * @return bool True if exists and valid
 */
function zed_cache_has(string $key): bool
{
    return zed_cache_get($key) !== null;
}

/**
 * Get cache statistics.
 * 
 * @return array Cache stats
 */
function zed_cache_stats(): array
{
    $cacheDir = ZED_CACHE_DIR;
    
    if (!is_dir($cacheDir)) {
        return ['files' => 0, 'size' => 0, 'enabled' => ZED_CACHE_ENABLED];
    }
    
    $files = glob($cacheDir . '/*.cache');
    $size = 0;
    
    foreach ($files as $file) {
        $size += filesize($file);
    }
    
    return [
        'files' => count($files),
        'size' => $size,
        'size_human' => zed_format_bytes($size),
        'enabled' => ZED_CACHE_ENABLED,
        'directory' => $cacheDir,
    ];
}

/**
 * Format bytes to human-readable size.
 */
function zed_format_bytes(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
