<?php
/**
 * Admin Routes - Content Management
 * 
 * Handles content list, editor, and delete routes.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * Handle content routes: /admin/content, /admin/editor, /admin/content/delete
 * 
 * @param array $request The request data
 * @param string $uri The request URI
 * @param string $themePath Path to admin theme
 * @return bool True if request was handled
 */
function zed_handle_content_routes(array $request, string $uri, string $themePath): bool
{
    // /admin/content - Content list
    if ($uri === '/admin/content') {
        // Parse query parameters for filtering and pagination
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? '';
        $type = $_GET['type'] ?? '';
        $msg = $_GET['msg'] ?? '';
        
        // Build the query dynamically
        $posts = [];
        $totalPosts = 0;
        
        try {
            $db = Database::getInstance();
            
            $selectSql = "SELECT * FROM zed_content";
            $countSql = "SELECT COUNT(*) FROM zed_content";
            $whereClauses = [];
            $params = [];
            
            // RBAC: Authors can only see their own content
            if (!zed_current_user_can('edit_others_content')) {
                $currentUserId = Auth::id();
                $whereClauses[] = "author_id = :author_id";
                $params['author_id'] = $currentUserId;
            }
            
            // Type filter
            if (!empty($type)) {
                $whereClauses[] = "type = :type";
                $params['type'] = $type;
            }
            
            // Search filter
            if (!empty($search)) {
                $whereClauses[] = "(title LIKE :search OR slug LIKE :search)";
                $params['search'] = '%' . $search . '%';
            }
            
            // Status filter
            if ($status === 'published') {
                $whereClauses[] = "JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'";
            } elseif ($status === 'draft') {
                $whereClauses[] = "(JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'draft' OR JSON_EXTRACT(data, '$.status') IS NULL)";
            }
            
            $whereString = '';
            if (!empty($whereClauses)) {
                $whereString = ' WHERE ' . implode(' AND ', $whereClauses);
            }
            
            $totalPosts = (int)$db->queryValue($countSql . $whereString, $params);
            
            $totalPages = max(1, ceil($totalPosts / $perPage));
            $page = min($page, $totalPages);
            $offset = ($page - 1) * $perPage;
            
            $fullSql = $selectSql . $whereString . " ORDER BY updated_at DESC LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;
            $posts = $db->query($fullSql, $params);
            
        } catch (Exception $e) {
            $posts = [];
            $totalPosts = 0;
            $totalPages = 1;
        }
        
        $showingFrom = $totalPosts > 0 ? (($page - 1) * $perPage) + 1 : 0;
        $showingTo = min($page * $perPage, $totalPosts);
        
        $current_user = Auth::user();
        
        $typeLabel = 'Content';
        if (!empty($type)) {
            $typeConfig = zed_get_post_type($type);
            $typeLabel = $typeConfig['label'] ?? ucfirst($type) . 's';
        }
        
        $current_page = !empty($type) ? 'cpt_' . $type : 'content';
        $page_title = $typeLabel;
        $content_partial = $themePath . '/partials/content-list-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return true;
    }

    // /admin/content/delete - Delete content by ID
    if ($uri === '/admin/content/delete') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        if (!zed_current_user_can('delete_content')) {
            Router::redirect('/admin/content?msg=permission_denied');
        }
        
        $id = $_GET['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            Router::redirect('/admin/content?msg=invalid_id');
        }
        
        $id = (int)$id;
        
        try {
            $db = Database::getInstance();
            
            $content = $db->queryOne(
                "SELECT id, author_id, title FROM zed_content WHERE id = :id",
                ['id' => $id]
            );
            
            if (!$content) {
                Router::redirect('/admin/content?msg=not_found');
            }
            
            $currentUserId = Auth::id();
            $contentAuthorId = (int)($content['author_id'] ?? 0);
            
            if (!zed_current_user_can('delete_others_content') && $contentAuthorId !== $currentUserId) {
                Router::redirect('/admin/content?msg=permission_denied');
            }
            
            $db->query("DELETE FROM zed_content WHERE id = :id", ['id' => $id]);
            
            Router::redirect('/admin/content?msg=deleted');
            
        } catch (Exception $e) {
            Router::redirect('/admin/content?msg=error');
        }
        
        return true;
    }

    // /admin/editor - Content editor
    if ($uri === '/admin/editor') {
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        ob_start();
        require $themePath . '/editor.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return true;
    }
    
    return false;
}
