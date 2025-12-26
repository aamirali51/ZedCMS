<?php
/**
 * Zed CMS - Comments Admin Routes
 * 
 * Handles /admin/comments route and comment moderation API.
 * 
 * @package ZedCMS\Admin\Routes
 * @since 3.2.0
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;

/**
 * Handle comments admin routes
 * 
 * @param array $request Request data
 * @param string $uri Current URI
 * @param string $themePath Admin theme path
 * @return bool True if handled
 */
function zed_handle_comments_routes(array $request, string $uri, string $themePath): bool
{
    // Match /admin/comments (with or without leading slash, with or without query string)
    $cleanUri = ltrim($uri, '/');
    if ($cleanUri !== 'admin/comments' && !str_starts_with($cleanUri, 'admin/comments?')) {
        return false;
    }
    
    // Check permission
    if (!zed_current_user_can('moderate_comments')) {
        Router::setHandled(zed_render_forbidden());
        return true;
    }
    
    // Set variables for admin-layout.php
    $current_user = Auth::user();
    $current_page = 'comments';
    $page_title = 'Comments';
    $content_partial = $themePath . '/partials/comments-content.php';
    
    // Render with layout
    ob_start();
    require $themePath . '/admin-layout.php';
    $content = ob_get_clean();
    
    Router::setHandled($content);
    return true;
}

/**
 * Handle comment moderation API
 * Routes: /admin/api?action=moderate_comment
 *         /api?action=submit_comment
 * 
 * @param array $request Request data
 * @param string $uri Current URI
 * @return bool True if handled
 */
function zed_handle_comment_api(array $request, string $uri): bool
{
    $action = $_GET['action'] ?? '';
    
    // Handle moderation (admin API)
    if (($uri === 'admin/api' || $uri === '/admin/api') && $action === 'moderate_comment') {
        header('Content-Type: application/json');
        
        // Check auth
        if (!Auth::check() || !zed_current_user_can('moderate_comments')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            Router::setHandled('');
            return true;
        }
        
        // Get data
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $id = (int)($input['id'] ?? 0);
        $status = $input['status'] ?? '';
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Comment ID required']);
            Router::setHandled('');
            return true;
        }
        
        // Handle delete vs status change
        if ($status === 'delete') {
            $result = zed_delete_comment($id);
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Comment deleted' : 'Failed to delete comment'
            ]);
        } else {
            $result = zed_moderate_comment($id, $status);
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Comment status updated' : 'Failed to update comment'
            ]);
        }
        
        Router::setHandled('');
        return true;
    }
    
    // Handle public comment submission
    if (($uri === 'api' || $uri === '/api') && $action === 'submit_comment') {
        header('Content-Type: application/json');
        
        // Get data
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        
        // Submit comment
        $result = zed_submit_comment($input);
        echo json_encode($result);
        
        Router::setHandled('');
        return true;
    }
    
    return false;
}
