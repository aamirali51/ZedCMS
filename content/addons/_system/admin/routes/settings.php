<?php
/**
 * Admin Routes - Settings
 * 
 * Handles settings panel routes.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * Handle settings routes: /admin/settings
 */
function zed_handle_settings_routes(array $request, string $uri, string $themePath): bool
{
    if ($uri !== '/admin/settings') {
        return false;
    }
    
    if (!zed_user_can_access_admin()) {
        Router::redirect('/admin/login');
    }
    
    // Only admins can access settings
    if (!zed_current_user_can('manage_settings')) {
        Router::setHandled(zed_render_forbidden());
        return true;
    }
    
    // Fetch all options from zed_options
    $options = [];
    try {
        $db = Database::getInstance();
        $rows = $db->query("SELECT option_name, option_value FROM zed_options");
        foreach ($rows as $row) {
            $options[$row['option_name']] = $row['option_value'];
        }
    } catch (Exception $e) {
        $options = [];
    }
    
    // Fetch published pages for Homepage dropdown
    $pages = [];
    try {
        $db = Database::getInstance();
        $pages = $db->query(
            "SELECT id, title FROM zed_content 
             WHERE type = 'page' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY title ASC"
        );
    } catch (Exception $e) {
        $pages = [];
    }
    
    $current_user = Auth::user();
    $current_page = 'settings';
    $page_title = 'Settings';
    $content_partial = $themePath . '/partials/settings-content.php';
    
    ob_start();
    require $themePath . '/admin-layout.php';
    $content = ob_get_clean();
    Router::setHandled($content);
    return true;
}
