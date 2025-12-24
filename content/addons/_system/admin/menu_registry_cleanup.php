/**
 * Clear menus and routes registered by a specific addon
 * Called when an addon is disabled
 * 
 * @param string $addonFile Addon filename (e.g., 'test_menu_api.php')
 * @return int Number of items cleared
 */
function zed_clear_addon_registrations(string $addonFile): int
{
    global $ZED_ADMIN_MENUS, $ZED_ADMIN_SUBMENUS, $ZED_REGISTERED_ROUTES;
    
    $cleared = 0;
    
    // Clear top-level menus
    foreach ($ZED_ADMIN_MENUS as $id => $menu) {
        if (($menu['registered_by'] ?? null) === $addonFile) {
            unset($ZED_ADMIN_MENUS[$id]);
            $cleared++;
        }
    }
    
    // Clear submenus
    foreach ($ZED_ADMIN_SUBMENUS as $parentId => $submenus) {
        foreach ($submenus as $id => $submenu) {
            if (($submenu['registered_by'] ?? null) === $addonFile) {
                unset($ZED_ADMIN_SUBMENUS[$parentId][$id]);
                $cleared++;
            }
        }
    }
    
    // Clear routes (if they track source)
    if (isset($ZED_REGISTERED_ROUTES)) {
        foreach ($ZED_REGISTERED_ROUTES as $path => $route) {
            if (($route['registered_by'] ?? null) === $addonFile) {
                unset($ZED_REGISTERED_ROUTES[$path]);
                $cleared++;
            }
        }
    }
    
    return $cleared;
}
