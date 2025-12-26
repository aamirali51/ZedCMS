<?php
/**
 * Zed CMS Frontend AJAX API
 * 
 * Provides API endpoints for AJAX content loading.
 * 
 * Endpoints:
 *   - /api?action=get_posts - Paginated posts
 *   - /api?action=search - Live search
 *   - /api?action=filter_posts - Filter by category/tag
 * 
 * @package Zed CMS
 * @since 3.2.0
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;
use Core\Database;

/**
 * Handle AJAX API requests
 */
Event::on('route_request', function(array $request): void {
    $uri = ltrim($request['uri'], '/');
    
    // Only handle /api routes
    if ($uri !== 'api' && !str_starts_with($uri, 'api?')) {
        return;
    }
    
    $action = $_GET['action'] ?? '';
    
    // Route to handler
    $handlers = [
        'get_posts' => 'zed_api_get_posts',
        'search' => 'zed_api_search',
        'filter_posts' => 'zed_api_filter_posts',
        'get_post' => 'zed_api_get_post',
    ];
    
    if (isset($handlers[$action]) && function_exists($handlers[$action])) {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        try {
            $result = call_user_func($handlers[$action]);
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
    }
}, 5); // Priority 5 = before admin routes

/**
 * Get paginated posts
 * 
 * @return array
 */
function zed_api_get_posts(): array
{
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = min(50, max(1, (int)($_GET['per_page'] ?? 10)));
    $type = $_GET['type'] ?? 'post';
    $category = $_GET['category'] ?? null;
    $tag = $_GET['tag'] ?? null;
    $orderby = $_GET['orderby'] ?? 'created_at';
    $order = strtoupper($_GET['order'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
    
    $offset = ($page - 1) * $perPage;
    
    try {
        $db = Database::getInstance();
        
        // Build query - status is stored in JSON data column
        $where = "type = :type AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'";
        $params = ['type' => $type];
        
        // Category filter
        if ($category) {
            $where .= " AND JSON_CONTAINS(data, :category, '$.categories')";
            $params['category'] = json_encode((int)$category);
        }
        
        // Tag filter
        if ($tag) {
            $where .= " AND JSON_CONTAINS(data, :tag, '$.tags')";
            $params['tag'] = json_encode((int)$tag);
        }
        
        // Safe orderby
        $allowedOrderby = ['created_at', 'updated_at', 'title', 'id'];
        if (!in_array($orderby, $allowedOrderby)) {
            $orderby = 'created_at';
        }
        
        // Get total count
        $countResult = $db->queryOne("SELECT COUNT(*) as total FROM zed_content WHERE {$where}", $params);
        $total = (int)($countResult['total'] ?? 0);
        
        // Get posts
        $sql = "SELECT id, title, slug, type, data, created_at, updated_at 
                FROM zed_content 
                WHERE {$where} 
                ORDER BY {$orderby} {$order} 
                LIMIT {$perPage} OFFSET {$offset}";
        
        $posts = $db->query($sql, $params);
        
        // Transform posts
        $items = array_map(function($post) {
            $data = json_decode($post['data'] ?? '{}', true);
            return [
                'id' => (int)$post['id'],
                'title' => $post['title'],
                'slug' => $post['slug'],
                'url' => zed_get_permalink($post),
                'excerpt' => $data['excerpt'] ?? '',
                'featured_image' => $data['featured_image'] ?? null,
                'date' => $post['created_at'],
                'date_formatted' => date('M j, Y', strtotime($post['created_at'])),
            ];
        }, $posts);
        
        return [
            'success' => true,
            'items' => $items,
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => ceil($total / $perPage),
            'has_more' => ($page * $perPage) < $total,
        ];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage(), 'items' => []];
    }
}

/**
 * Search posts
 * 
 * @return array
 */
function zed_api_search(): array
{
    $query = trim($_GET['q'] ?? '');
    $limit = min(20, max(1, (int)($_GET['limit'] ?? 10)));
    $type = $_GET['type'] ?? null;
    
    if (strlen($query) < 2) {
        return ['success' => true, 'results' => [], 'query' => $query];
    }
    
    try {
        $db = Database::getInstance();
        
        // Build query - status is stored in JSON data column
        $where = "JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published' AND (title LIKE :query OR content LIKE :query2)";
        $params = [
            'query' => "%{$query}%",
            'query2' => "%{$query}%",
        ];
        
        if ($type) {
            $where .= " AND type = :type";
            $params['type'] = $type;
        }
        
        $sql = "SELECT id, title, slug, type, data, created_at 
                FROM zed_content 
                WHERE {$where} 
                ORDER BY created_at DESC 
                LIMIT {$limit}";
        
        $posts = $db->query($sql, $params);
        
        // Transform results
        $results = array_map(function($post) {
            $data = json_decode($post['data'] ?? '{}', true);
            return [
                'id' => (int)$post['id'],
                'title' => $post['title'],
                'url' => zed_get_permalink($post),
                'type' => $post['type'],
                'excerpt' => mb_substr($data['excerpt'] ?? '', 0, 100),
                'featured_image' => $data['featured_image'] ?? null,
            ];
        }, $posts);
        
        return [
            'success' => true,
            'results' => $results,
            'query' => $query,
            'count' => count($results),
        ];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage(), 'results' => []];
    }
}

/**
 * Filter posts by category/tag
 * 
 * @return array
 */
function zed_api_filter_posts(): array
{
    // Same as get_posts but with category/tag support
    return zed_api_get_posts();
}

/**
 * Get single post
 * 
 * @return array
 */
function zed_api_get_post(): array
{
    $id = (int)($_GET['id'] ?? 0);
    $slug = $_GET['slug'] ?? null;
    
    if (!$id && !$slug) {
        return ['success' => false, 'error' => 'Post ID or slug required'];
    }
    
    try {
        $db = Database::getInstance();
        
        if ($id) {
            $post = $db->queryOne(
                "SELECT * FROM zed_content WHERE id = :id AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'",
                ['id' => $id]
            );
        } else {
            $post = $db->queryOne(
                "SELECT * FROM zed_content WHERE slug = :slug AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'",
                ['slug' => $slug]
            );
        }
        
        if (!$post) {
            return ['success' => false, 'error' => 'Post not found'];
        }
        
        $data = json_decode($post['data'] ?? '{}', true);
        
        return [
            'success' => true,
            'post' => [
                'id' => (int)$post['id'],
                'title' => $post['title'],
                'slug' => $post['slug'],
                'url' => zed_get_permalink($post),
                'content' => $post['content'],
                'excerpt' => $data['excerpt'] ?? '',
                'featured_image' => $data['featured_image'] ?? null,
                'date' => $post['created_at'],
                'type' => $post['type'],
            ],
        ];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
