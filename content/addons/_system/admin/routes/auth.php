<?php
/**
 * Admin Routes - Auth & Security
 * 
 * Handles authentication routes: login, logout, security checks.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * SECURITY: Verify CSRF nonce for API requests.
 * Returns true if valid, otherwise sends 403 and returns false.
 * 
 * @param array|null $jsonData Pre-parsed JSON body (optional)
 * @return bool True if nonce is valid
 */
if (!function_exists('zed_require_ajax_nonce')) {
    function zed_require_ajax_nonce(?array $jsonData = null): bool
    {
        if (!function_exists('zed_verify_ajax_nonce')) {
            return true; // Skip if security helpers not loaded
        }
        
        if (!zed_verify_ajax_nonce('zed_admin_action', $jsonData)) {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode([
                'success' => false, 
                'error' => 'Security verification failed. Please refresh the page and try again.'
            ]);
            Router::setHandled('');
            return false;
        }
        return true;
    }
}

/**
 * Helper to return permission denied JSON response.
 */
if (!function_exists('zed_json_permission_denied')) {
    function zed_json_permission_denied(): void
    {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Permission denied. You do not have access to this action.']);
    }
}

/**
 * Handle auth routes: /admin/login, /admin/logout
 * 
 * @param array $request The request data
 * @param string $uri The request URI
 * @param string $themePath Path to admin theme
 * @return bool True if request was handled
 */
function zed_handle_auth_routes(array $request, string $uri, string $themePath): bool
{
    // /admin/logout - Logout and redirect (always accessible)
    if ($uri === '/admin/logout') {
        Auth::logout();
        Router::redirect('/admin/login');
        return true;
    }

    // Legacy logout via query param (/?logout=true)
    if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
        Auth::logout();
        Router::redirect('/admin/login');
        return true;
    }

    // /admin/login - Always accessible (public page)
    if ($uri === '/admin/login') {
        // If already logged in, redirect to dashboard
        if (Auth::check()) {
            Router::redirect('/admin');
        }
        
        ob_start();
        require $themePath . '/login.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return true;
    }
    
    return false;
}

/**
 * Handle AJAX system routes: /api/ajax/{action}
 * 
 * @param array $request The request data
 * @param string $uri The request URI
 * @return bool True if request was handled
 */
function zed_handle_ajax_system(array $request, string $uri): bool
{
    if (!preg_match('#^/api/ajax/(\w+)$#', $uri, $matches)) {
        return false;
    }
    
    $action = $matches[1];
    $handlers = zed_get_ajax_handlers();
    
    header('Content-Type: application/json');
    
    if (!isset($handlers[$action])) {
        http_response_code(404);
        echo json_encode(['error' => 'Unknown action', 'action' => $action]);
        Router::setHandled();
        return true;
    }
    
    $handler = $handlers[$action];
    
    // Check method
    $method = $_SERVER['REQUEST_METHOD'];
    if ($handler['method'] !== 'ANY' && $handler['method'] !== $method) {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        Router::setHandled();
        return true;
    }
    
    // Check authentication
    if ($handler['require_auth'] && !Auth::check()) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        Router::setHandled();
        return true;
    }
    
    // Check capability
    if ($handler['capability'] && !zed_current_user_can($handler['capability'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Permission denied']);
        Router::setHandled();
        return true;
    }
    
    // Get request data
    $data = [];
    if ($method === 'POST') {
        $input = file_get_contents('php://input');
        $jsonData = json_decode($input, true);
        $data = $jsonData ?: $_POST;
    } else {
        $data = $_GET;
    }
    
    // Execute handler
    try {
        $result = call_user_func($handler['callback'], $data);
        echo json_encode($result);
    } catch (\Throwable $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    
    Router::setHandled();
    return true;
}

/**
 * Check if user has admin access for protected routes.
 * 
 * @param string $uri The request URI
 * @return bool True if access denied (handled), false if allowed
 */
function zed_check_admin_access(string $uri): bool
{
    if (!str_starts_with($uri, '/admin')) {
        return false;
    }
    
    // Step 1: Check if user is logged in
    if (!Auth::check()) {
        Router::redirect('/admin/login');
        return true;
    }
    
    // Step 2: Check if user has admin/editor role
    if (!zed_user_can_access_admin()) {
        // User is logged in but doesn't have admin privileges
        http_response_code(403);
        $content = zed_render_forbidden();
        echo $content;
        Router::setHandled($content);
        return true;
    }
    
    return false;
}
