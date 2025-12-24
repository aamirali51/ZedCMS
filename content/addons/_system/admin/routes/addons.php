<?php
/**
 * Admin Routes - Addons
 * 
 * Handles addon manager routes.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * Handle addons routes: /admin/addons, /admin/addon-settings
 */
function zed_handle_addons_routes(array $request, string $uri, string $themePath): bool
{
    // /admin/addons - Addons Manager
    if ($uri === '/admin/addons') {
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        $current_user = Auth::user();
        $current_page = 'addons';
        $page_title = 'Addons';
        $content_partial = $themePath . '/partials/addons-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return true;
    }
    
    // /admin/addon-settings - Addon Settings List
    if ($uri === '/admin/addon-settings') {
        if (!zed_current_user_can('manage_settings')) {
            Router::redirect('/admin/login');
        }
        
        $addonSettings = zed_get_addon_settings();
        
        $current_user = Auth::user();
        $current_page = 'addon-settings';
        $page_title = 'Addon Settings';
        $content_partial = $themePath . '/partials/addon-settings-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return true;
    }
    
    // /admin/addon-settings/{addon} - Specific Addon Settings
    if (preg_match('#^/admin/addon-settings/([a-z0-9_-]+)$#i', $uri, $matches)) {
        if (!zed_current_user_can('manage_settings')) {
            Router::redirect('/admin/login');
        }
        
        $addonSlug = $matches[1];
        $allSettings = zed_get_addon_settings();
        $addon_settings_config = $allSettings[$addonSlug] ?? null;
        $addon_settings_id = $addonSlug;
        
        if (!$addon_settings_config) {
            Router::redirect('/admin/addon-settings?msg=not_found');
        }
        
        $current_user = Auth::user();
        $current_page = 'addon-settings';
        $page_title = $addon_settings_config['title'] ?? $addonSlug;
        $content_partial = $themePath . '/partials/addon-settings-form.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return true;
    }
    
    return false;
}
