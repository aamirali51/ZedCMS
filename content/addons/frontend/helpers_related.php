<?php
/**
 * Zed CMS — Related Content Helpers
 * 
 * Functions for related posts, popular content, and featured items.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Database;

/**
 * Get related posts based on shared categories
 * 
 * @param array $post Current post
 * @param int $limit Number of related posts
 * @return array Related posts
 */
function zed_get_related_posts(array $post, int $limit = 4): array
{
    $postId = $post['id'] ?? 0;
    $data = zed_parse_post_data($post);
    $categories = $data['categories'] ?? [];
    
    if (empty($categories)) {
        // Fallback to recent posts of same type
        return zed_get_posts([
            'type' => $post['type'] ?? 'post',
            'limit' => $limit,
            'status' => 'published',
        ]);
    }
    
    try {
        $db = Database::getInstance();
        
        // Build category match condition
        $categoryConditions = [];
        $params = [
            'post_id' => $postId,
            'type' => $post['type'] ?? 'post',
        ];
        
        foreach ($categories as $i => $cat) {
            $key = "cat{$i}";
            $categoryConditions[] = "JSON_CONTAINS(JSON_EXTRACT(data, '$.categories'), :{$key})";
            $params[$key] = json_encode($cat);
        }
        
        $categoryWhere = '(' . implode(' OR ', $categoryConditions) . ')';
        
        $sql = "SELECT * FROM zed_content 
                WHERE id != :post_id 
                AND type = :type
                AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
                AND {$categoryWhere}
                ORDER BY created_at DESC
                LIMIT {$limit}";
        
        return $db->query($sql, $params);
    } catch (\Exception $e) {
        // Fallback
        return zed_get_posts([
            'type' => $post['type'] ?? 'post',
            'limit' => $limit,
            'status' => 'published',
        ]);
    }
}

/**
 * Get featured posts (marked with is_featured in data)
 * 
 * @param int $limit Number of posts
 * @param string|null $type Content type filter
 * @return array Featured posts
 */
function zed_get_featured_posts(int $limit = 3, ?string $type = null): array
{
    try {
        $db = Database::getInstance();
        
        $params = [];
        $where = [
            "JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'",
            "JSON_UNQUOTE(JSON_EXTRACT(data, '$.is_featured')) = 'true' OR JSON_UNQUOTE(JSON_EXTRACT(data, '$.is_featured')) = '1'",
        ];
        
        if ($type) {
            $where[] = "type = :type";
            $params['type'] = $type;
        }
        
        $sql = "SELECT * FROM zed_content WHERE " . implode(' AND ', $where) 
             . " ORDER BY created_at DESC LIMIT {$limit}";
        
        $featured = $db->query($sql, $params);
        
        // If not enough featured, fill with recent
        if (count($featured) < $limit) {
            $existingIds = array_column($featured, 'id');
            $additionalNeeded = $limit - count($featured);
            
            $additional = zed_get_posts([
                'type' => $type ?? 'post',
                'limit' => $additionalNeeded,
                'status' => 'published',
            ]);
            
            foreach ($additional as $add) {
                if (!in_array($add['id'], $existingIds)) {
                    $featured[] = $add;
                    if (count($featured) >= $limit) break;
                }
            }
        }
        
        return $featured;
    } catch (\Exception $e) {
        return zed_get_posts([
            'type' => $type ?? 'post',
            'limit' => $limit,
            'status' => 'published',
        ]);
    }
}

/**
 * Get posts from same author
 * 
 * @param array $post Current post
 * @param int $limit Number of posts
 * @return array Author's other posts
 */
function zed_get_author_posts(array $post, int $limit = 4): array
{
    $authorId = $post['author_id'] ?? 0;
    $postId = $post['id'] ?? 0;
    
    if (!$authorId) {
        return [];
    }
    
    try {
        $db = Database::getInstance();
        
        return $db->query(
            "SELECT * FROM zed_content 
             WHERE author_id = :author_id 
             AND id != :post_id
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY created_at DESC
             LIMIT {$limit}",
            ['author_id' => $authorId, 'post_id' => $postId]
        );
    } catch (\Exception $e) {
        return [];
    }
}

/**
 * Get popular posts (stub - requires analytics addon for real view counts)
 * 
 * For now, returns recent posts. When analytics addon is enabled,
 * it will hook into zed_popular_posts filter to provide real data.
 * 
 * @param int $limit Number of posts
 * @param string|null $type Content type filter
 * @return array Popular posts
 */
function zed_get_popular_posts(int $limit = 5, ?string $type = null): array
{
    // This is a placeholder - analytics addon can filter this
    $posts = zed_get_posts([
        'type' => $type ?? 'post',
        'limit' => $limit,
        'status' => 'published',
        'orderby' => 'created_at',
        'order' => 'DESC',
    ]);
    
    return \Core\Event::filter('zed_popular_posts', $posts, $limit, $type);
}

/**
 * Get recent posts from multiple categories
 * 
 * @param array $categorySlugs Category slugs
 * @param int $limit Posts per category
 * @return array Posts grouped by category
 */
function zed_get_posts_by_categories(array $categorySlugs, int $limit = 4): array
{
    $result = [];
    
    foreach ($categorySlugs as $slug) {
        $category = zed_get_category($slug);
        if ($category) {
            $result[$slug] = [
                'category' => $category,
                'posts' => zed_get_posts([
                    'category' => $slug,
                    'limit' => $limit,
                    'status' => 'published',
                ]),
            ];
        }
    }
    
    return $result;
}
