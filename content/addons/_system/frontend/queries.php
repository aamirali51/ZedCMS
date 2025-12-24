<?php
/**
 * Content Query Functions
 * 
 * Functions for fetching posts and pages from the database.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

use Core\Database;

// =============================================================================
// CONTENT QUERY FUNCTIONS
// =============================================================================

/**
 * Fetch latest published posts for blog listing
 *
 * @param int $limit Number of posts to fetch
 * @param int $offset Offset for pagination
 * @return array Posts array
 */
function zed_get_latest_posts(int $limit = 10, int $offset = 0): array
{
    try {
        $db = Database::getInstance();
        return $db->query(
            "SELECT * FROM zed_content 
             WHERE type = 'post' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get total count of published posts
 */
function zed_count_published_posts(): int
{
    try {
        $db = Database::getInstance();
        return (int)$db->queryValue(
            "SELECT COUNT(*) FROM zed_content 
             WHERE type = 'post' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'"
        );
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Get a single page by ID
 */
function zed_get_page_by_id(int $id): ?array
{
    try {
        $db = Database::getInstance();
        return $db->queryOne(
            "SELECT * FROM zed_content WHERE id = :id AND type = 'page' LIMIT 1",
            ['id' => $id]
        );
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Resolve template using WordPress-style Template Hierarchy
 * 
 * For 'archive' prefix with 'portfolio' type:
 *   1. archive-portfolio.php
 *   2. archive.php
 *   3. index.php
 * 
 * @param string $themePath Path to active theme directory
 * @param string $prefix Template prefix (archive, single, page)
 * @param string $type Post type slug
 * @return string Full path to selected template
 */
function zed_resolve_template_hierarchy(string $themePath, string $prefix, string $type): string
{
    // Template hierarchy (most specific to least specific)
    $hierarchy = [
        "{$prefix}-{$type}.php",  // e.g., archive-portfolio.php
        "{$prefix}.php",          // e.g., archive.php
        'index.php'               // Ultimate fallback
    ];
    
    foreach ($hierarchy as $template) {
        $path = $themePath . '/' . $template;
        if (file_exists($path)) {
            return $path;
        }
    }
    
    // If nothing found, return index.php path
    return $themePath . '/index.php';
}
