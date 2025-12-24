<?php
/**
 * Admin Routes - Users
 * 
 * Handles user management routes.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * Handle users routes: /admin/users
 */
function zed_handle_users_routes(array $request, string $uri, string $themePath): bool
{
    if ($uri !== '/admin/users') {
        return false;
    }
    
    if (!zed_user_can_access_admin()) {
        Router::redirect('/admin/login');
    }
    
    if (!zed_current_user_can('manage_users')) {
        Router::setHandled(zed_render_forbidden());
        return true;
    }
    
    try {
        $db = Database::getInstance();
        $users = $db->query("SELECT id, email, role, last_login, created_at FROM users ORDER BY id ASC");
    } catch (Exception $e) {
        $users = [];
    }
    
    $current_user = Auth::user();
    $current_page = 'users';
    $page_title = 'User Management';
    $content_partial = $themePath . '/partials/users-content.php';
    
    ob_start();
    require $themePath . '/admin-layout.php';
    $content = ob_get_clean();
    Router::setHandled($content);
    return true;
}
