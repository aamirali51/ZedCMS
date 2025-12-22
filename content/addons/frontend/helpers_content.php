<?php
/**
 * Zed CMS — Content Retrieval Helpers
 * 
 * Pure functions for fetching posts, pages, and custom content types.
 * All functions return data, no side effects.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Database;

/**
 * Get a single post by ID
 * 
 * @param int $id Post ID
 * @return array|null Post data or null if not found
 */
function zed_get_post(int $id): ?array
{
    try {
        $db = Database::getInstance();
        return $db->queryOne(
            "SELECT * FROM zed_content WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * Get a single post by slug
 * 
 * @param string $slug Post slug
 * @param string|null $type Optional type filter
 * @return array|null Post data or null if not found
 */
function zed_get_post_by_slug(string $slug, ?string $type = null): ?array
{
    try {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM zed_content WHERE slug = :slug";
        $params = ['slug' => $slug];
        
        if ($type !== null) {
            $sql .= " AND type = :type";
            $params['type'] = $type;
        }
        
        $sql .= " LIMIT 1";
        
        return $db->queryOne($sql, $params);
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * Get multiple posts with flexible query options
 * 
 * @param array $args Query arguments
 *   - type: string (default: 'post')
 *   - status: string (default: 'published')
 *   - limit: int (default: 10)
 *   - offset: int (default: 0)
 *   - orderby: string (default: 'created_at')
 *   - order: string (default: 'DESC')
 *   - category: string|int|null
 *   - author: int|null
 *   - search: string|null
 * @return array Posts array
 */
function zed_get_posts(array $args = []): array
{
    $defaults = [
        'type' => 'post',
        'status' => 'published',
        'limit' => 10,
        'offset' => 0,
        'orderby' => 'created_at',
        'order' => 'DESC',
        'category' => null,
        'author' => null,
        'search' => null,
    ];
    
    $args = array_merge($defaults, $args);
    
    try {
        $db = Database::getInstance();
        $params = [];
        $where = [];
        
        // Type filter
        if ($args['type'] !== 'all') {
            $where[] = "type = :type";
            $params['type'] = $args['type'];
        }
        
        // Status filter
        if ($args['status'] !== 'all') {
            $where[] = "JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = :status";
            $params['status'] = $args['status'];
        }
        
        // Author filter
        if ($args['author'] !== null) {
            $where[] = "author_id = :author_id";
            $params['author_id'] = (int)$args['author'];
        }
        
        // Category filter
        if ($args['category'] !== null) {
            $where[] = "JSON_CONTAINS(JSON_EXTRACT(data, '$.categories'), :category)";
            $params['category'] = json_encode((string)$args['category']);
        }
        
        // Search filter
        if ($args['search'] !== null && $args['search'] !== '') {
            $where[] = "(title LIKE :search OR plain_text LIKE :search)";
            $params['search'] = '%' . $args['search'] . '%';
        }
        
        // Build query
        $sql = "SELECT * FROM zed_content";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        // Order
        $orderby = in_array($args['orderby'], ['created_at', 'updated_at', 'title', 'id']) 
            ? $args['orderby'] : 'created_at';
        $order = strtoupper($args['order']) === 'ASC' ? 'ASC' : 'DESC';
        $sql .= " ORDER BY {$orderby} {$order}";
        
        // Limit/Offset
        $sql .= " LIMIT " . (int)$args['limit'] . " OFFSET " . (int)$args['offset'];
        
        return $db->query($sql, $params);
    } catch (\Exception $e) {
        return [];
    }
}

/**
 * Get all published pages
 * 
 * @param array $args Query arguments (same as zed_get_posts)
 * @return array Pages array
 */
function zed_get_pages(array $args = []): array
{
    $args['type'] = 'page';
    return zed_get_posts($args);
}

/**
 * Get content by custom post type
 * 
 * @param string $type CPT slug
 * @param array $args Query arguments
 * @return array Content array
 */
function zed_get_content_by_type(string $type, array $args = []): array
{
    $args['type'] = $type;
    return zed_get_posts($args);
}

/**
 * Count posts matching criteria
 * 
 * @param array $args Query arguments (same as zed_get_posts, limit/offset ignored)
 * @return int Count
 */
function zed_count_posts(array $args = []): int
{
    $defaults = [
        'type' => 'post',
        'status' => 'published',
        'category' => null,
        'author' => null,
        'search' => null,
    ];
    
    $args = array_merge($defaults, $args);
    
    try {
        $db = Database::getInstance();
        $params = [];
        $where = [];
        
        if ($args['type'] !== 'all') {
            $where[] = "type = :type";
            $params['type'] = $args['type'];
        }
        
        if ($args['status'] !== 'all') {
            $where[] = "JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = :status";
            $params['status'] = $args['status'];
        }
        
        if ($args['author'] !== null) {
            $where[] = "author_id = :author_id";
            $params['author_id'] = (int)$args['author'];
        }
        
        if ($args['category'] !== null) {
            $where[] = "JSON_CONTAINS(JSON_EXTRACT(data, '$.categories'), :category)";
            $params['category'] = json_encode((string)$args['category']);
        }
        
        if ($args['search'] !== null && $args['search'] !== '') {
            $where[] = "(title LIKE :search OR plain_text LIKE :search)";
            $params['search'] = '%' . $args['search'] . '%';
        }
        
        $sql = "SELECT COUNT(*) as total FROM zed_content";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $result = $db->queryOne($sql, $params);
        return (int)($result['total'] ?? 0);
    } catch (\Exception $e) {
        return 0;
    }
}
