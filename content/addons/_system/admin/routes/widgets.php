<?php
/**
 * Zed CMS - Widgets Admin Routes
 * 
 * Handles /admin/widgets route for widget management.
 * 
 * @package ZedCMS\Admin\Routes
 * @since 3.2.0
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;

/**
 * Handle widgets admin routes
 * 
 * @param array $request Request data
 * @param string $uri Current URI
 * @param string $themePath Admin theme path
 * @return bool True if handled
 */
function zed_handle_widgets_routes(array $request, string $uri, string $themePath): bool
{
    // Match /admin/widgets (with or without leading slash)
    $cleanUri = ltrim($uri, '/');
    if ($cleanUri !== 'admin/widgets' && !str_starts_with($cleanUri, 'admin/widgets?')) {
        return false;
    }
    
    // Check permission
    if (!zed_current_user_can('manage_themes')) {
        Router::setHandled(zed_render_forbidden());
        return true;
    }
    
    // Set variables for admin-layout.php
    $current_user = Auth::user();
    $current_page = 'widgets';
    $page_title = 'Widgets';
    $content_partial = $themePath . '/partials/widgets-content.php';
    
    // Render with layout
    ob_start();
    require $themePath . '/admin-layout.php';
    $content = ob_get_clean();
    
    Router::setHandled($content);
    return true;
}
