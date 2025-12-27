<?php
/**
 * Admin Menu Registry API
 * 
 * WordPress-style admin menu registration, but cleaner.
 * Enables addons to add admin pages without touching core files.
 * 
 * @package ZedCMS\Admin
 */

declare(strict_types=1);

// =============================================================================
// MENU REGISTRY (In-memory storage)
// =============================================================================

/**
 * @var array<string, array> Registered admin menus
 */
global $ZED_ADMIN_MENUS;
$ZED_ADMIN_MENUS = [];

/**
 * @var array<string, array<string, array>> Registered admin submenus
 */
global $ZED_ADMIN_SUBMENUS;
$ZED_ADMIN_SUBMENUS = [];

/**
 * @var array<string, string> Registered custom capabilities
 */
global $ZED_CUSTOM_CAPABILITIES;
$ZED_CUSTOM_CAPABILITIES = [];

// =============================================================================
// MENU REGISTRATION API
// =============================================================================

/**
 * Register a top-level admin menu item
 * 
 * Example:
 * ```php
 * zed_register_admin_menu([
 *     'id' => 'my_addon',
 *     'title' => 'My Addon',
 *     'icon' => 'settings',           // Material Symbols icon name
 *     'capability' => 'manage_options',
 *     'position' => 30,               // Lower = higher in menu
 *     'callback' => function() {
 *         echo '<h1>My Addon Page</h1>';
 *     }
 * ]);
 * ```
 * 
 * @param array $args Menu configuration
 * @return bool True if registered successfully
 */
function zed_register_admin_menu(array $args): bool
{
    global $ZED_ADMIN_MENUS;
    
    // Required fields
    if (empty($args['id']) || empty($args['title'])) {
        return false;
    }
    
    $id = $args['id'];
    
    // Track which addon registered this menu (for filtering when addon is disabled)
    $registeredBy = null;
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
    foreach ($backtrace as $trace) {
        if (isset($trace['file']) && strpos($trace['file'], 'content/addons/') !== false) {
            $registeredBy = basename($trace['file']);
            break;
        }
    }
    
    // Normalize the menu configuration
    $ZED_ADMIN_MENUS[$id] = [
        'id' => $id,
        'title' => $args['title'],
        'icon' => $args['icon'] ?? 'extension',
        'capability' => $args['capability'] ?? 'manage_options',
        'position' => $args['position'] ?? 100,
        'callback' => $args['callback'] ?? null,
        'url' => $args['url'] ?? null,  // External URL (optional)
        'badge' => $args['badge'] ?? null,  // Badge count/text (optional)
        'parent' => null,  // Top-level menu
        'registered_by' => $registeredBy,  // Track source addon
    ];
    
    // Auto-register route if callback is provided
    if ($args['callback'] && function_exists('zed_register_route_from_menu')) {
        zed_register_route_from_menu($id, $ZED_ADMIN_MENUS[$id]);
    }
    
    return true;
}

/**
 * Register a submenu under an existing menu
 * 
 * Example:
 * ```php
 * zed_register_admin_submenu('my_addon', [
 *     'id' => 'my_addon_logs',
 *     'title' => 'Logs',
 *     'capability' => 'manage_options',
 *     'callback' => fn() => echo 'Logs here'
 * ]);
 * ```
 * 
 * @param string $parentId Parent menu ID
 * @param array $args Submenu configuration
 * @return bool True if registered successfully
 */
function zed_register_admin_submenu(string $parentId, array $args): bool
{
    global $ZED_ADMIN_SUBMENUS;
    
    // Required fields
    if (empty($args['id']) || empty($args['title'])) {
        return false;
    }
    
    $id = $args['id'];
    
    // Initialize parent array if needed
    if (!isset($ZED_ADMIN_SUBMENUS[$parentId])) {
        $ZED_ADMIN_SUBMENUS[$parentId] = [];
    }
    
    $ZED_ADMIN_SUBMENUS[$parentId][$id] = [
        'id' => $id,
        'title' => $args['title'],
        'capability' => $args['capability'] ?? 'manage_options',
        'position' => $args['position'] ?? 100,
        'callback' => $args['callback'] ?? null,
        'url' => $args['url'] ?? null,
        'parent' => $parentId,
    ];
    
    // Auto-register route if callback is provided
    if ($args['callback'] && function_exists('zed_register_route')) {
        $path = $args['url'] ?? ("/admin/{$parentId}/{$id}");
        zed_register_route([
            'path' => $path,
            'method' => 'GET',
            'capability' => $args['capability'] ?? 'manage_options',
            'callback' => $args['callback'],
            'wrap_layout' => true,
            'priority' => $args['position'] ?? 100,
        ]);
    }
    
    return true;
}

/**
 * Get all registered admin menus (sorted by position)
 * 
 * @return array<string, array> Menus indexed by ID
 */
function zed_get_admin_menus(): array
{
    global $ZED_ADMIN_MENUS;
    
    $menus = $ZED_ADMIN_MENUS;
    
    // Sort by position
    uasort($menus, fn($a, $b) => ($a['position'] ?? 100) <=> ($b['position'] ?? 100));
    
    return $menus;
}

/**
 * Get submenus for a parent menu
 * 
 * @param string $parentId Parent menu ID
 * @return array<string, array> Submenus indexed by ID
 */
function zed_get_admin_submenus(string $parentId): array
{
    global $ZED_ADMIN_SUBMENUS;
    
    $submenus = $ZED_ADMIN_SUBMENUS[$parentId] ?? [];
    
    // Sort by position
    uasort($submenus, fn($a, $b) => ($a['position'] ?? 100) <=> ($b['position'] ?? 100));
    
    return $submenus;
}

/**
 * Get a specific menu by ID
 * 
 * @param string $menuId Menu ID
 * @return array|null Menu configuration or null
 */
function zed_get_admin_menu(string $menuId): ?array
{
    global $ZED_ADMIN_MENUS, $ZED_ADMIN_SUBMENUS;
    
    // Check top-level menus
    if (isset($ZED_ADMIN_MENUS[$menuId])) {
        return $ZED_ADMIN_MENUS[$menuId];
    }
    
    // Check submenus
    foreach ($ZED_ADMIN_SUBMENUS as $parentId => $submenus) {
        if (isset($submenus[$menuId])) {
            return $submenus[$menuId];
        }
    }
    
    return null;
}

// =============================================================================
// CAPABILITY REGISTRATION API
// =============================================================================

/**
 * Register custom capabilities for an addon
 * 
 * Example:
 * ```php
 * zed_register_capabilities([
 *     'manage_my_addon' => 'Manage My Addon',
 *     'view_my_addon_logs' => 'View Addon Logs',
 * ]);
 * ```
 * 
 * @param array<string, string> $capabilities [capability_name => label]
 * @return void
 */
function zed_register_capabilities(array $capabilities): void
{
    global $ZED_CUSTOM_CAPABILITIES;
    
    $ZED_CUSTOM_CAPABILITIES = array_merge($ZED_CUSTOM_CAPABILITIES, $capabilities);
}

/**
 * Get all registered custom capabilities
 * 
 * @return array<string, string> [capability_name => label]
 */
function zed_get_custom_capabilities(): array
{
    global $ZED_CUSTOM_CAPABILITIES;
    return $ZED_CUSTOM_CAPABILITIES;
}

/**
 * Get all capabilities (core + custom)
 * 
 * @return array<string, string> [capability_name => label]
 */
function zed_get_all_capabilities(): array
{
    // Core capabilities
    $core = [
        'manage_settings' => 'Manage Settings',
        'manage_options' => 'Manage Options',
        'manage_users' => 'Manage Users',
        'delete_users' => 'Delete Users',
        'manage_themes' => 'Manage Themes',
        'manage_addons' => 'Manage Addons',
        'manage_menus' => 'Manage Menus',
        'edit_content' => 'Edit Content',
        'delete_content' => 'Delete Content',
        'publish_content' => 'Publish Content',
        'upload_files' => 'Upload Files',
        'delete_files' => 'Delete Files',
    ];
    
    return array_merge($core, zed_get_custom_capabilities());
}

// =============================================================================
// ADMIN PAGE RENDERING
// =============================================================================

/**
 * Render an addon admin page with the standard admin layout
 * 
 * @param string $menuId Menu ID to render
 * @param array $vars Extra variables for the template
 * @return string|null Rendered HTML or null if not found/no permission
 */
function zed_render_admin_page(string $menuId, array $vars = []): ?string
{
    $menu = zed_get_admin_menu($menuId);
    
    if (!$menu) {
        return null;
    }
    
    // Check capability
    $capability = $menu['capability'] ?? 'manage_options';
    if (!zed_current_user_can($capability)) {
        return zed_render_forbidden();
    }
    
    // Get callback
    $callback = $menu['callback'];
    if (!$callback || !is_callable($callback)) {
        return null;
    }
    
    // Capture callback output
    ob_start();
    $callback($vars);
    $content = ob_get_clean();
    
    return $content;
}

// =============================================================================
// ROUTE HANDLER FOR REGISTERED MENUS
// =============================================================================

/**
 * Handle routes for registered admin menus
 * Called from the main admin routes dispatcher
 * 
 * @param array $request Request data
 * @param string $uri Request URI
 * @param string $themePath Admin theme path
 * @return bool True if handled
 */
function zed_handle_registered_menu_routes(array $request, string $uri, string $themePath): bool
{
    // Match /admin/{menu_id} or /admin/{parent_id}/{submenu_id}
    if (!preg_match('#^/admin/([a-z0-9_-]+)(?:/([a-z0-9_-]+))?$#i', $uri, $matches)) {
        return false;
    }
    
    $menuId = $matches[1];
    $subMenuId = $matches[2] ?? null;
    
    // Determine which menu to render
    $targetId = $subMenuId ?? $menuId;
    $menu = zed_get_admin_menu($targetId);
    
    if (!$menu || !$menu['callback']) {
        return false; // Not a registered menu, let other handlers try
    }
    
    // Check capability
    $capability = $menu['capability'] ?? 'manage_options';
    if (!zed_current_user_can($capability)) {
        \Core\Router::setHandled(zed_render_forbidden());
        return true;
    }
    
    // Render the page with admin layout
    $current_user = \Core\Auth::user();
    $current_page = $targetId;
    $page_title = $menu['title'];
    
    // Capture callback output
    ob_start();
    $callback = $menu['callback'];
    $callback([
        'menu' => $menu,
        'uri' => $uri,
        'request' => $request,
    ]);
    $addon_content = ob_get_clean();
    
    // Wrap in admin layout
    ob_start();
    // Set variables for the layout
    $content_partial = null; // We'll inject content directly
    $addon_page_content = $addon_content;
    require $themePath . '/admin-layout.php';
    $content = ob_get_clean();
    
    \Core\Router::setHandled($content);
    return true;
}

// =============================================================================
// SIDEBAR MENU BUILDER
// =============================================================================

/**
 * Get the complete sidebar menu structure
 * Combines system menus + registered addon menus
 * 
 * @return array Menu items for rendering
 */
function zed_get_sidebar_menu(): array
{
    $baseUrl = \Core\Router::getBasePath();
    
    // System menus (hardcoded for now, will migrate later)
    $systemMenus = [
        [
            'id' => 'dashboard',
            'title' => 'Dashboard',
            'icon' => 'dashboard',
            'url' => $baseUrl . '/admin',
            'position' => 0,
            'is_system' => true,
        ],
        [
            'id' => 'content',
            'title' => 'Content',
            'icon' => 'article',
            'url' => $baseUrl . '/admin/content',
            'position' => 10,
            'is_system' => true,
        ],
        [
            'id' => 'media',
            'title' => 'Media',
            'icon' => 'perm_media',
            'url' => $baseUrl . '/admin/media',
            'position' => 15,
            'is_system' => true,
        ],
        [
            'id' => 'categories',
            'title' => 'Categories',
            'icon' => 'category',
            'url' => $baseUrl . '/admin/categories',
            'position' => 20,
            'is_system' => true,
        ],
        [
            'id' => 'menus',
            'title' => 'Menus',
            'icon' => 'menu',
            'url' => $baseUrl . '/admin/menus',
            'position' => 25,
            'is_system' => true,
        ],
        [
            'id' => 'divider_1',
            'type' => 'divider',
            'position' => 49,
        ],
        [
            'id' => 'users',
            'title' => 'Users',
            'icon' => 'group',
            'url' => $baseUrl . '/admin/users',
            'position' => 60,
            'capability' => 'manage_users',
            'is_system' => true,
        ],
        [
            'id' => 'addons',
            'title' => 'Addons',
            'icon' => 'extension',
            'url' => $baseUrl . '/admin/addons',
            'position' => 70,
            'capability' => 'manage_addons',
            'is_system' => true,
        ],
        [
            'id' => 'themes',
            'title' => 'Themes',
            'icon' => 'palette',
            'url' => $baseUrl . '/admin/themes',
            'position' => 75,
            'capability' => 'manage_themes',
            'is_system' => true,
        ],
        [
            'id' => 'divider_2',
            'type' => 'divider',
            'position' => 89,
        ],
        [
            'id' => 'settings',
            'title' => 'Settings',
            'icon' => 'settings',
            'url' => $baseUrl . '/admin/settings',
            'position' => 90,
            'capability' => 'manage_settings',
            'is_system' => true,
        ],
        [
            'id' => 'cache',
            'title' => 'Cache',
            'icon' => 'cached',
            'url' => $baseUrl . '/admin/cache',
            'position' => 91,
            'capability' => 'manage_settings',
            'is_system' => true,
        ],
    ];
    
    // Add registered addon menus
    $addonMenus = [];
    foreach (zed_get_admin_menus() as $id => $menu) {
        // Skip if user doesn't have capability
        $capability = $menu['capability'] ?? 'manage_options';
        if (function_exists('zed_current_user_can') && !zed_current_user_can($capability)) {
            continue;
        }
        
        $addonMenus[] = [
            'id' => $id,
            'title' => $menu['title'],
            'icon' => $menu['icon'] ?? 'extension',
            'url' => $menu['url'] ?? ($baseUrl . '/admin/' . $id),
            'position' => $menu['position'] ?? 50,
            'badge' => $menu['badge'] ?? null,
            'is_addon' => true,
            'submenus' => zed_get_admin_submenus($id),
        ];
    }
    
    // Merge and sort
    $allMenus = array_merge($systemMenus, $addonMenus);
    usort($allMenus, fn($a, $b) => ($a['position'] ?? 100) <=> ($b['position'] ?? 100));
    
    return $allMenus;
}

/**
 * Render the sidebar menu HTML
 * 
 * @param string $currentPage Current page ID (for active state)
 * @return string HTML output
 */
function zed_render_sidebar_menu(string $currentPage = ''): string
{
    $menus = zed_get_sidebar_menu();
    $html = '';
    
    foreach ($menus as $menu) {
        // Handle dividers
        if (($menu['type'] ?? '') === 'divider') {
            $html .= '<div class="sidebar-divider"></div>';
            continue;
        }
        
        // Check capability if set
        if (!empty($menu['capability']) && function_exists('zed_current_user_can')) {
            if (!zed_current_user_can($menu['capability'])) {
                continue;
            }
        }
        
        $isActive = ($menu['id'] === $currentPage) ? 'active' : '';
        $badge = $menu['badge'] ?? null;
        $badgeHtml = $badge ? '<span class="menu-badge">' . htmlspecialchars((string)$badge) . '</span>' : '';
        $addonClass = !empty($menu['is_addon']) ? 'addon-menu' : '';
        
        $html .= sprintf(
            '<a href="%s" class="sidebar-link %s %s">
                <span class="material-symbols-outlined">%s</span>
                <span class="link-text">%s</span>
                %s
            </a>',
            htmlspecialchars($menu['url']),
            $isActive,
            $addonClass,
            htmlspecialchars($menu['icon']),
            htmlspecialchars($menu['title']),
            $badgeHtml
        );
        
        // Render submenus if present
        $submenus = $menu['submenus'] ?? [];
        if (!empty($submenus)) {
            $html .= '<div class="sidebar-submenu">';
            foreach ($submenus as $submenu) {
                $subActive = ($submenu['id'] === $currentPage) ? 'active' : '';
                $html .= sprintf(
                    '<a href="%s" class="sidebar-sublink %s">%s</a>',
                    htmlspecialchars($submenu['url'] ?? '#'),
                    $subActive,
                    htmlspecialchars($submenu['title'])
                );
            }
            $html .= '</div>';
        }
    }
    
    return $html;
}
