<?php
/**
 * Route Registration API
 * 
 * Enables addons to self-register routes without modifying core files.
 * Provides automatic permission checks, layout wrapping, and pattern matching.
 * 
 * @package ZedCMS\Admin
 */

declare(strict_types=1);

// =============================================================================
// ROUTE REGISTRY (In-memory storage)
// =============================================================================

/**
 * @var array<string, array> Registered routes indexed by path
 */
global $ZED_REGISTERED_ROUTES;
$ZED_REGISTERED_ROUTES = [];

// =============================================================================
// ROUTE REGISTRATION API
// =============================================================================

/**
 * Register a custom route
 * 
 * Example:
 * ```php
 * zed_register_route([
 *     'path' => '/admin/my-page',
 *     'method' => 'GET',
 *     'capability' => 'manage_options',
 *     'callback' => function($request, $uri, $params) {
 *         return '<h1>My Page</h1>';
 *     },
 *     'wrap_layout' => true,
 * ]);
 * ```
 * 
 * @param array $args Route configuration
 * @return bool True if registered successfully
 */
function zed_register_route(array $args): bool
{
    global $ZED_REGISTERED_ROUTES;
    
    // Required fields
    if (empty($args['path']) || empty($args['callback'])) {
        return false;
    }
    
    $path = $args['path'];
    
    // Normalize method
    $method = $args['method'] ?? 'GET';
    if (is_string($method)) {
        $method = [$method];
    }
    
    // Store route configuration
    $ZED_REGISTERED_ROUTES[$path] = [
        'path' => $path,
        'method' => $method,
        'capability' => $args['capability'] ?? null,
        'callback' => $args['callback'],
        'wrap_layout' => $args['wrap_layout'] ?? true,
        'priority' => $args['priority'] ?? 50,
        'pattern' => $args['pattern'] ?? null,  // For pattern matching
    ];
    
    return true;
}

/**
 * Get all registered routes
 * 
 * @return array<string, array> Routes indexed by path
 */
function zed_get_registered_routes(): array
{
    global $ZED_REGISTERED_ROUTES;
    
    $routes = $ZED_REGISTERED_ROUTES;
    
    // Sort by priority (lower = higher priority)
    uasort($routes, fn($a, $b) => ($a['priority'] ?? 50) <=> ($b['priority'] ?? 50));
    
    return $routes;
}

/**
 * Match a URI against a route pattern
 * 
 * Supports:
 * - Exact match: /admin/my-page
 * - Pattern match: /admin/reports/{type}
 * - Wildcard: /admin/reports/*
 * 
 * @param string $pattern Route pattern
 * @param string $uri Request URI
 * @return array|false Array of extracted params or false if no match
 */
function zed_match_route_pattern(string $pattern, string $uri): array|false
{
    // Exact match
    if ($pattern === $uri) {
        return [];
    }
    
    // Convert pattern to regex
    // /admin/reports/{type} -> /admin/reports/([^/]+)
    $regex = preg_replace('/\{([a-z_]+)\}/', '([^/]+)', $pattern);
    $regex = str_replace('*', '.*', $regex);
    $regex = '#^' . $regex . '$#i';
    
    if (preg_match($regex, $uri, $matches)) {
        // Extract parameter names from pattern
        preg_match_all('/\{([a-z_]+)\}/', $pattern, $paramNames);
        
        $params = [];
        foreach ($paramNames[1] as $index => $name) {
            $params[$name] = $matches[$index + 1] ?? null;
        }
        
        return $params;
    }
    
    return false;
}

// =============================================================================
// ROUTE HANDLER FOR REGISTERED ROUTES
// =============================================================================

/**
 * Handle routes registered via zed_register_route()
 * Called from the main admin routes dispatcher
 * 
 * @param array $request Request data
 * @param string $uri Request URI
 * @param string $themePath Admin theme path
 * @return bool True if handled
 */
function zed_handle_registered_routes(array $request, string $uri, string $themePath): bool
{
    $routes = zed_get_registered_routes();
    
    foreach ($routes as $route) {
        // Check if method matches
        if (!in_array($request['method'], $route['method'], true)) {
            continue;
        }
        
        // Try to match the route
        $params = false;
        
        // Exact match
        if ($route['path'] === $uri) {
            $params = [];
        }
        // Pattern match
        elseif (strpos($route['path'], '{') !== false || strpos($route['path'], '*') !== false) {
            $params = zed_match_route_pattern($route['path'], $uri);
        }
        
        // No match, try next route
        if ($params === false) {
            continue;
        }
        
        // Route matched! Check capability
        if (!empty($route['capability'])) {
            if (!function_exists('zed_current_user_can') || !zed_current_user_can($route['capability'])) {
                if (function_exists('zed_render_forbidden')) {
                    \Core\Router::setHandled(zed_render_forbidden());
                } else {
                    \Core\Router::setHandled('403 Forbidden');
                }
                return true;
            }
        }
        
        // Execute callback
        $callback = $route['callback'];
        if (!is_callable($callback)) {
            continue;
        }
        
        // Capture callback output
        ob_start();
        $result = $callback($request, $uri, $params);
        $output = ob_get_clean();
        
        // If callback returned a string, use that
        if (is_string($result)) {
            $output = $result;
        }
        
        // Wrap in admin layout if requested
        if ($route['wrap_layout']) {
            $current_user = \Core\Auth::user();
            $current_page = basename($uri);
            $page_title = 'Custom Page';
            
            ob_start();
            $content_partial = null;
            $addon_page_content = $output;
            require $themePath . '/admin-layout.php';
            $output = ob_get_clean();
        }
        
        \Core\Router::setHandled($output);
        return true;
    }
    
    return false;
}

/**
 * Register a route from menu configuration
 * Internal helper for menu_registry.php integration
 * 
 * @param string $menuId Menu ID
 * @param array $menu Menu configuration
 * @return bool True if registered
 */
function zed_register_route_from_menu(string $menuId, array $menu): bool
{
    if (empty($menu['callback'])) {
        return false;
    }
    
    $path = $menu['url'] ?? ('/admin/' . $menuId);
    
    return zed_register_route([
        'path' => $path,
        'method' => 'GET',
        'capability' => $menu['capability'] ?? 'manage_options',
        'callback' => $menu['callback'],
        'wrap_layout' => true,
        'priority' => $menu['position'] ?? 50,
    ]);
}
