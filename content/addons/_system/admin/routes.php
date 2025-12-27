<?php
/**
 * Zed CMS - Admin Routes
 * 
 * This file loads modular route handlers and dispatches to them.
 * Large routes file has been split into focused modules in routes/ subdirectory.
 * 
 * @package ZedCMS\Admin
 */

use Core\Event;
use Core\Router;
use Core\Auth;
use Core\Database;

// =============================================================================
// LOAD ROUTE MODULES
// =============================================================================

$routesDir = __DIR__ . '/routes';

// Auth & Security (login, logout, nonce verification)
require_once $routesDir . '/auth.php';

// Dashboard 
require_once $routesDir . '/dashboard.php';

// Content management (list, editor, delete)
require_once $routesDir . '/content.php';

// Categories management
require_once $routesDir . '/categories.php';

// Menu builder
require_once $routesDir . '/menus.php';

// Settings panel
require_once $routesDir . '/settings.php';

// User management
require_once $routesDir . '/users.php';

// Media library
require_once $routesDir . '/media.php';

// Addons manager
require_once $routesDir . '/addons.php';

// Theme manager
require_once $routesDir . '/themes.php';

// Comments management (v3.2.0)
require_once $routesDir . '/comments.php';

// Widgets management (v3.2.0)
require_once $routesDir . '/widgets.php';

// Cache management (v3.2.0)
require_once $routesDir . '/cache.php';

// API endpoints (save, upload, etc.)
require_once $routesDir . '/api.php';

// =============================================================================
// MAIN ROUTE DISPATCHER
// =============================================================================

Event::on('route_request', function (array $request): void {
    $uri = $request['uri'];
    $themePath = __DIR__ . '/../../../themes/admin-default';
    
    // Handle auth routes (login, logout) - always accessible
    if (zed_handle_auth_routes($request, $uri, $themePath)) {
        return;
    }
    
    // Handle AJAX system routes
    if (zed_handle_ajax_system($request, $uri)) {
        return;
    }
    
    // Security check for all /admin/* routes
    if (zed_check_admin_access($uri)) {
        return;
    }
    
    // Route dispatch based on URI
    $handlers = [
        // Registered routes (highest priority - addons can override)
        'zed_handle_registered_routes',
        // Page routes
        'zed_handle_dashboard_route',
        'zed_handle_content_routes',
        'zed_handle_categories_routes',
        'zed_handle_menus_routes',
        'zed_handle_settings_routes',
        'zed_handle_users_routes',
        'zed_handle_media_routes',
        'zed_handle_addons_routes',
        'zed_handle_themes_routes',
        'zed_handle_comments_routes',
        'zed_handle_widgets_routes',
        'zed_handle_cache_routes',
        // API routes
        'zed_handle_api_routes',
        'zed_handle_comment_api',
        // Addon-registered menu pages (catch-all for /admin/{addon_menu})
        'zed_handle_registered_menu_routes',
    ];
    
    foreach ($handlers as $handler) {
        if (function_exists($handler) && $handler($request, $uri, $themePath)) {
            return;
        }
    }
    
}, 10); // Priority 10 = runs before frontend
