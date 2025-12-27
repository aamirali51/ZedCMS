<?php
/**
 * Options & Site Settings API
 * 
 * Handles site options retrieval and caching.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

use Core\Database;

// =============================================================================
// OPTIONS HELPER - Cached database lookups for settings
// =============================================================================

// Only define if not already defined by admin/api.php
if (!function_exists('zed_get_option')) {
    /**
     * Get a site option from zed_options table
     * Results are cached in a static variable to prevent repeated DB queries.
     *
     * @param string $name Option name
     * @param mixed $default Default value if option not found
     * @return mixed Option value or default
     */
    function zed_get_option(string $name, mixed $default = ''): mixed
    {
        static $optionsCache = null;
        
        // Special: Clear cache (used after zed_set_option)
        if ($name === '__CLEAR_CACHE__') {
            $optionsCache = null;
            return true;
        }
        
        // Load all options on first call (single query)
        if ($optionsCache === null) {
            $optionsCache = [];
            try {
                $db = Database::getInstance();
                $rows = $db->query("SELECT option_name, option_value FROM zed_options WHERE autoload = 1");
                foreach ($rows as $row) {
                    $optionsCache[$row['option_name']] = $row['option_value'];
                }
            } catch (Exception $e) {
                // Silently fail - use defaults
            }
        }
        
        // Return cached value or fetch individually if not autoloaded
        if (isset($optionsCache[$name])) {
            return $optionsCache[$name];
        }
        
        // Not in cache - try individual lookup (for non-autoload options)
        try {
            $db = Database::getInstance();
            $result = $db->queryOne(
                "SELECT option_value FROM zed_options WHERE option_name = :name",
                ['name' => $name]
            );
            if ($result) {
                $optionsCache[$name] = $result['option_value'];
                return $result['option_value'];
            }
        } catch (Exception $e) {
            // Silently fail
        }
        
        return $default;
    }
}

/**
 * Get site name from settings
 */
function zed_get_site_name(): string
{
    return zed_get_option('site_title', 'Zed CMS');
}

/**
 * Get site tagline from settings
 */
function zed_get_site_tagline(): string
{
    return zed_get_option('site_tagline', '');
}

/**
 * Get meta description from settings
 */
function zed_get_meta_description(): string
{
    return zed_get_option('meta_description', '');
}

/**
 * Check if search engines should be discouraged
 */
function zed_is_noindex(): bool
{
    return zed_get_option('discourage_search_engines', '0') === '1';
}

/**
 * Get posts per page setting
 */
function zed_get_posts_per_page(): int
{
    return max(1, (int)zed_get_option('posts_per_page', '10'));
}
