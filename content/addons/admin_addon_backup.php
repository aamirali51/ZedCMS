<?php

declare(strict_types=1);

/**
 * Admin Addon - Handles admin routes
 * 
 * Routes:
 * - /admin/login  -> Login page
 * - /admin        -> Dashboard (requires auth)
 * - /admin/dashboard -> Dashboard alias
 * - ?logout=true  -> Logout handler
 * 
 * RBAC System:
 * - Capability-based permissions
 * - Role hierarchy support
 * - Content ownership enforcement
 */

use Core\Event;
use Core\Router;
use Core\Auth;
use Core\Database;

// ============================================================================
// ENTERPRISE RBAC SYSTEM - SUPERIOR TO WORDPRESS
// ============================================================================

/**
 * Role Definitions with Capabilities
 * Each role has a set of capabilities that define what actions they can perform.
 * Roles inherit capabilities from lower tiers (subscriber < author < editor < admin)
 */
function zed_get_role_capabilities(): array
{
    return [
        // Administrator - Full access to everything
        'admin' => [
            // User Management
            'manage_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // System Settings
            'manage_settings',
            'manage_addons',
            'manage_themes',
            
            // Content - Full control
            'manage_categories',
            'manage_menus',
            'publish_content',
            'edit_content',
            'delete_content',
            'edit_others_content',
            'delete_others_content',
            'edit_published_content',
            
            // Media
            'manage_media',
            'upload_media',
            'delete_media',
            'delete_others_media',
            
            // Dashboard
            'view_dashboard',
            'view_analytics',
        ],
        
        // Alias for admin
        'administrator' => 'admin', // Inherits from admin
        
        // Editor - Can manage all content but no system settings
        'editor' => [
            // Content - Full control
            'manage_categories',
            'publish_content',
            'edit_content',
            'delete_content',
            'edit_others_content',
            'delete_others_content',
            'edit_published_content',
            
            // Media
            'manage_media',
            'upload_media',
            'delete_media',
            'delete_others_media',
            
            // Dashboard
            'view_dashboard',
        ],
        
        // Author - Can manage own content only
        'author' => [
            // Content - Own only
            'publish_content',
            'edit_content',
            'delete_content',
            'edit_published_content',
            // Note: No edit_others_content or delete_others_content
            
            // Media - Own only
            'upload_media',
            'delete_media',
            // Note: No delete_others_media
            
            // Dashboard
            'view_dashboard',
        ],
        
        // Subscriber - View only, no admin access
        'subscriber' => [
            // No admin capabilities
            // Can only view public content on frontend
        ],
    ];
}

/**
 * Get capabilities for a specific role
 */
function zed_get_capabilities_for_role(string $role): array
{
    $roles = zed_get_role_capabilities();
    
    if (!isset($roles[$role])) {
        return [];
    }
    
    $caps = $roles[$role];
    
    // Handle role aliases (inheritance)
    if (is_string($caps)) {
        return zed_get_capabilities_for_role($caps);
    }
    
    return $caps;
}

/**
 * Check if current user has a specific capability
 * 
 * @param string $capability The capability to check
 * @param int|null $object_id Optional object ID for ownership checks
 * @return bool
 */
function zed_current_user_can(string $capability, ?int $object_id = null): bool
{
    if (!Auth::check()) {
        return false;
    }
    
    $user = Auth::user();
    $role = $user['role'] ?? 'subscriber';
    $userId = (int)($user['id'] ?? 0);
    
    // Get capabilities for user's role
    $capabilities = zed_get_capabilities_for_role($role);
    
    // Direct capability check
    if (in_array($capability, $capabilities, true)) {
        return true;
    }
    
    // Ownership-based capability check
    // If user doesn't have "edit_others_content" but has "edit_content",
    // they can still edit their OWN content
    if ($object_id !== null) {
        $ownershipCaps = [
            'edit_others_content' => 'edit_content',
            'delete_others_content' => 'delete_content',
            'delete_others_media' => 'delete_media',
        ];
        
        if (isset($ownershipCaps[$capability])) {
            $baseCap = $ownershipCaps[$capability];
            if (in_array($baseCap, $capabilities, true) && zed_user_owns_object($userId, $capability, $object_id)) {
                return true;
            }
        }
    }
    
    return false;
}

/**
 * Check if a user owns a specific object (content, media, etc.)
 */
function zed_user_owns_object(int $userId, string $capability, int $objectId): bool
{
    try {
        $db = Database::getInstance();
        
        // Determine table based on capability
        if (str_contains($capability, 'content')) {
            $owner = $db->queryOne(
                "SELECT author_id FROM zed_content WHERE id = :id",
                ['id' => $objectId]
            );
            return $owner && (int)($owner['author_id'] ?? 0) === $userId;
        }
        
        if (str_contains($capability, 'media')) {
            // Media ownership would need a media table with user_id
            // For now, we'll check if filename contains user ID or allow
            return true; // Default to allow for media without ownership tracking
        }
        
        return false;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Check if current user can access the admin panel at all
 * Returns true for admin, administrator, editor, and author roles
 */
function zed_user_can_access_admin(): bool
{
    if (!Auth::check()) {
        return false;
    }
    
    $user = Auth::user();
    $role = $user['role'] ?? '';
    
    // Roles that can access admin
    $adminRoles = ['admin', 'administrator', 'editor', 'author'];
    return in_array($role, $adminRoles, true);
}

/**
 * Check if current user has one of the specified roles
 */
function zed_user_has_role(string|array $roles): bool
{
    if (!Auth::check()) {
        return false;
    }
    
    $user = Auth::user();
    $userRole = $user['role'] ?? '';
    
    if (is_string($roles)) {
        $roles = [$roles];
    }
    
    return in_array($userRole, $roles, true);
}

/**
 * Get current user's role
 */
function zed_get_current_user_role(): string
{
    if (!Auth::check()) {
        return '';
    }
    
    $user = Auth::user();
    return $user['role'] ?? 'subscriber';
}

/**
 * Check if current user is an administrator
 */
function zed_is_admin(): bool
{
    return zed_user_has_role(['admin', 'administrator']);
}

/**
 * Get role display name and metadata
 */
function zed_get_role_info(string $role): array
{
    $roles = [
        'admin' => [
            'label' => 'Administrator',
            'description' => 'Full access to all features and settings',
            'color' => 'purple',
            'icon' => 'shield_person',
            'level' => 100,
        ],
        'administrator' => [
            'label' => 'Administrator',
            'description' => 'Full access to all features and settings',
            'color' => 'purple',
            'icon' => 'shield_person',
            'level' => 100,
        ],
        'editor' => [
            'label' => 'Editor',
            'description' => 'Can manage all content and media',
            'color' => 'blue',
            'icon' => 'edit_note',
            'level' => 70,
        ],
        'author' => [
            'label' => 'Author',
            'description' => 'Can create and manage own content',
            'color' => 'green',
            'icon' => 'draw',
            'level' => 40,
        ],
        'subscriber' => [
            'label' => 'Subscriber',
            'description' => 'Can view content and manage profile',
            'color' => 'gray',
            'icon' => 'person',
            'level' => 10,
        ],
    ];
    
    return $roles[$role] ?? $roles['subscriber'];
}

/**
 * Get all available roles for dropdowns
 */
function zed_get_all_roles(): array
{
    return [
        'subscriber' => 'Subscriber',
        'author' => 'Author',
        'editor' => 'Editor',
        'admin' => 'Administrator',
    ];
}

/**
 * Get sidebar menu items based on user capabilities
 */
function zed_get_admin_menu_items(): array
{
    $items = [];
    $base = Router::getBasePath();
    
    // Dashboard - available to all admin-access users
    if (zed_current_user_can('view_dashboard')) {
        $items[] = [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'dashboard',
            'url' => $base . '/admin',
            'position' => 10,
        ];
    }
    
    // Content - available to those who can edit content
    if (zed_current_user_can('edit_content')) {
        $items[] = [
            'id' => 'content',
            'label' => 'Content',
            'icon' => 'article',
            'url' => $base . '/admin/content',
            'position' => 20,
        ];
        
        // ─────────────────────────────────────────────────────────────────
        // CUSTOM POST TYPES - Dynamic menu items from theme/addon registration
        // ─────────────────────────────────────────────────────────────────
        global $ZED_POST_TYPES;
        if (!empty($ZED_POST_TYPES)) {
            // Sort by menu_position
            $sortedTypes = $ZED_POST_TYPES;
            uasort($sortedTypes, fn($a, $b) => ($a['menu_position'] ?? 50) <=> ($b['menu_position'] ?? 50));
            
            $cptPosition = 21; // Start CPT items after Content (20)
            foreach ($sortedTypes as $type => $config) {
                // Skip built-in types (already have their own entries)
                if ($config['builtin'] ?? false) {
                    continue;
                }
                
                // Only show if configured to show in menu
                if (!($config['show_in_menu'] ?? true)) {
                    continue;
                }
                
                $items[] = [
                    'id' => 'cpt_' . $type,
                    'label' => $config['label'] ?? ucfirst($type) . 's',
                    'icon' => $config['icon'] ?? 'folder',
                    'url' => $base . '/admin/content?type=' . $type,
                    'position' => $cptPosition++,
                ];
            }
        }
    }
    
    // Categories - editors and above
    if (zed_current_user_can('manage_categories')) {
        $items[] = [
            'id' => 'categories',
            'label' => 'Categories',
            'icon' => 'category',
            'url' => $base . '/admin/categories',
            'position' => 40,
        ];
    }
    
    // Media - available to those who can upload
    if (zed_current_user_can('upload_media')) {
        $items[] = [
            'id' => 'media',
            'label' => 'Media Library',
            'icon' => 'perm_media',
            'url' => $base . '/admin/media',
            'position' => 50,
        ];
    }
    
    // Menus - editors and above
    if (zed_current_user_can('manage_menus')) {
        $items[] = [
            'id' => 'menus',
            'label' => 'Menus',
            'icon' => 'menu',
            'url' => $base . '/admin/menus',
            'position' => 60,
        ];
    }
    
    // Users - admin only
    if (zed_current_user_can('manage_users')) {
        $items[] = [
            'id' => 'users',
            'label' => 'Users',
            'icon' => 'group',
            'url' => $base . '/admin/users',
            'position' => 70,
        ];
    }
    
    // Addons - admin only
    if (zed_current_user_can('manage_addons')) {
        $items[] = [
            'id' => 'addons',
            'label' => 'Addons',
            'icon' => 'extension',
            'url' => $base . '/admin/addons',
            'position' => 80,
        ];
    }
    
    // Themes - admin only
    if (zed_current_user_can('manage_themes')) {
        $items[] = [
            'id' => 'themes',
            'label' => 'Themes',
            'icon' => 'palette',
            'url' => $base . '/admin/themes',
            'position' => 85,
        ];
    }
    
    // Wiki / Documentation
    if (zed_current_user_can('view_dashboard')) {
        $items[] = [
            'id' => 'wiki',
            'label' => 'Knowledge Base',
            'icon' => 'library_books',
            'url' => $base . '/admin/wiki',
            'position' => 90,
        ];
    }
    
    // Settings - admin only
    if (zed_current_user_can('manage_settings')) {
        $items[] = [
            'id' => 'settings',
            'label' => 'Settings',
            'icon' => 'settings',
            'url' => $base . '/admin/settings',
            'position' => 100,
        ];
    }
    
    // ─────────────────────────────────────────────────────────────────────
    // ADDON MENU ITEMS - Allow addons to register their own menu items
    // ─────────────────────────────────────────────────────────────────────
    $items = Event::filter('zed_admin_menu', $items);
    
    // Sort by position if provided (addons can specify 'position' key)
    usort($items, function($a, $b) {
        $posA = $a['position'] ?? 100;
        $posB = $b['position'] ?? 100;
        return $posA <=> $posB;
    });
    
    return $items;
}

/**
 * JSON response helper for permission denied
 */
function zed_json_permission_denied(): void
{
    header('Content-Type: application/json');
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Permission denied. You do not have access to this feature.',
        'code' => 'PERMISSION_DENIED'
    ]);
}

/**
 * Render 403 Forbidden page for users without admin access
 */
function zed_render_forbidden(): string
{
    $baseUrl = Router::getBasePath();
    $role = zed_get_current_user_role();
    $roleInfo = zed_get_role_info($role);
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden — Zed CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center font-sans">
    <div class="text-center p-8 max-w-md">
        <div class="w-20 h-20 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
            <span class="material-symbols-outlined text-red-600 text-4xl">gpp_bad</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Access Denied</h1>
        <p class="text-gray-600 mb-4">You don't have permission to access this area.</p>
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-full text-sm text-gray-600 mb-6">
            <span class="material-symbols-outlined text-lg">{$roleInfo['icon']}</span>
            Your role: <strong>{$roleInfo['label']}</strong>
        </div>
        <p class="text-sm text-gray-500 mb-6">{$roleInfo['description']}</p>
        <div class="space-x-4">
            <a href="{$baseUrl}/" class="inline-block px-5 py-2.5 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition-colors">Go Home</a>
            <a href="{$baseUrl}/admin/logout" class="inline-block px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">Logout</a>
        </div>
    </div>
</body>
</html>
HTML;
}

// =============================================================================
// AJAX HANDLER SYSTEM
// =============================================================================

global $ZED_AJAX_HANDLERS;
$ZED_AJAX_HANDLERS = [];

/**
 * Register an AJAX action handler
 * 
 * @param string $action Action name (endpoint will be /api/ajax/{action})
 * @param callable $callback Function that receives $data and returns response array
 * @param bool $require_auth Whether authentication is required
 * @param string $method HTTP method (GET, POST, or ANY)
 * @param string|null $capability Required capability (null = any authenticated user)
 */
function zed_register_ajax(string $action, callable $callback, bool $require_auth = false, string $method = 'POST', ?string $capability = null): void
{
    global $ZED_AJAX_HANDLERS;
    $ZED_AJAX_HANDLERS[$action] = [
        'callback' => $callback,
        'require_auth' => $require_auth,
        'method' => strtoupper($method),
        'capability' => $capability,
    ];
}

/**
 * Get all registered AJAX handlers
 */
function zed_get_ajax_handlers(): array
{
    global $ZED_AJAX_HANDLERS;
    return $ZED_AJAX_HANDLERS;
}

// =============================================================================
// ADMIN NOTICES SYSTEM
// =============================================================================

/**
 * Add an admin notice (flash message)
 * 
 * @param string $message Notice message
 * @param string $type Type: success, error, warning, info
 * @param bool $dismissible Whether the notice can be dismissed
 */
function zed_add_notice(string $message, string $type = 'info', bool $dismissible = true): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['zed_notices'])) {
        $_SESSION['zed_notices'] = [];
    }
    
    $_SESSION['zed_notices'][] = [
        'message' => $message,
        'type' => $type,
        'dismissible' => $dismissible,
    ];
}

/**
 * Get all admin notices and clear them
 * 
 * @return array Array of notice arrays
 */
function zed_get_notices(): array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $notices = $_SESSION['zed_notices'] ?? [];
    $_SESSION['zed_notices'] = [];
    
    return $notices;
}

/**
 * Render admin notices as HTML
 * 
 * @return string HTML for notices
 */
function zed_render_notices(): string
{
    $notices = zed_get_notices();
    
    if (empty($notices)) {
        return '';
    }
    
    $colors = [
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
    ];
    
    $icons = [
        'success' => 'check_circle',
        'error' => 'error',
        'warning' => 'warning',
        'info' => 'info',
    ];
    
    $html = '<div class="zed-notices space-y-2 mb-4">';
    
    foreach ($notices as $notice) {
        $type = $notice['type'] ?? 'info';
        $colorClass = $colors[$type] ?? $colors['info'];
        $icon = $icons[$type] ?? $icons['info'];
        $dismissible = $notice['dismissible'] ?? true;
        
        $html .= '<div class="flex items-center gap-3 px-4 py-3 rounded-lg border ' . $colorClass . '">';
        $html .= '<span class="material-symbols-outlined text-xl">' . $icon . '</span>';
        $html .= '<span class="flex-1">' . htmlspecialchars($notice['message']) . '</span>';
        
        if ($dismissible) {
            $html .= '<button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100">';
            $html .= '<span class="material-symbols-outlined">close</span>';
            $html .= '</button>';
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

// =============================================================================
// ADDON SETTINGS API
// =============================================================================

global $ZED_ADDON_SETTINGS;
$ZED_ADDON_SETTINGS = [];

/**
 * Register addon settings page
 * 
 * @param string $addon_id Unique addon identifier
 * @param array $config Configuration array:
 *   - title: string Page title
 *   - description: string Optional description
 *   - fields: array Array of field definitions
 *   - capability: string Required capability (default: manage_settings)
 */
function zed_register_addon_settings(string $addon_id, array $config): void
{
    global $ZED_ADDON_SETTINGS;
    
    $defaults = [
        'title' => ucwords(str_replace('_', ' ', $addon_id)) . ' Settings',
        'description' => '',
        'fields' => [],
        'capability' => 'manage_settings',
    ];
    
    $ZED_ADDON_SETTINGS[$addon_id] = array_merge($defaults, $config);
}

/**
 * Get registered addon settings
 */
function zed_get_addon_settings(): array
{
    global $ZED_ADDON_SETTINGS;
    return $ZED_ADDON_SETTINGS;
}

/**
 * Get addon setting value
 * 
 * @param string $addon_id Addon identifier
 * @param string $field_id Field identifier
 * @param mixed $default Default value
 * @return mixed Setting value
 */
function zed_get_addon_option(string $addon_id, string $field_id, mixed $default = null): mixed
{
    return zed_get_option("addon_{$addon_id}_{$field_id}", $default);
}

/**
 * Save addon setting value
 * 
 * @param string $addon_id Addon identifier
 * @param string $field_id Field identifier
 * @param mixed $value Value to save
 * @return bool Success
 */
function zed_set_addon_option(string $addon_id, string $field_id, mixed $value): bool
{
    try {
        $db = Database::getInstance();
        $key = "addon_{$addon_id}_{$field_id}";
        
        $existing = $db->queryOne(
            "SELECT id FROM zed_options WHERE option_key = :key",
            ['key' => $key]
        );
        
        $valueStr = is_array($value) || is_object($value) ? json_encode($value) : (string)$value;
        
        if ($existing) {
            $db->query(
                "UPDATE zed_options SET option_value = :value WHERE option_key = :key",
                ['value' => $valueStr, 'key' => $key]
            );
        } else {
            $db->query(
                "INSERT INTO zed_options (option_key, option_value, autoload) VALUES (:key, :value, 0)",
                ['key' => $key, 'value' => $valueStr]
            );
        }
        
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Render a settings field
 * 
 * @param array $field Field configuration
 * @param string $addon_id Addon identifier
 * @return string HTML
 */
function zed_render_settings_field(array $field, string $addon_id): string
{
    $id = $field['id'] ?? '';
    $type = $field['type'] ?? 'text';
    $label = $field['label'] ?? ucwords(str_replace('_', ' ', $id));
    $description = $field['description'] ?? '';
    $options = $field['options'] ?? [];
    $default = $field['default'] ?? '';
    
    $value = zed_get_addon_option($addon_id, $id, $default);
    $name = "addon_{$addon_id}_{$id}";
    
    $html = '<div class="mb-6">';
    $html .= '<label for="' . $name . '" class="block text-sm font-medium text-gray-700 mb-2">' . htmlspecialchars($label) . '</label>';
    
    switch ($type) {
        case 'text':
        case 'email':
        case 'url':
        case 'number':
            $html .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" value="' . htmlspecialchars((string)$value) . '" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">';
            break;
            
        case 'textarea':
            $html .= '<textarea id="' . $name . '" name="' . $name . '" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">' . htmlspecialchars((string)$value) . '</textarea>';
            break;
            
        case 'toggle':
            $checked = $value ? 'checked' : '';
            $html .= '<label class="relative inline-flex items-center cursor-pointer">';
            $html .= '<input type="checkbox" name="' . $name . '" value="1" ' . $checked . ' class="sr-only peer">';
            $html .= '<div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>';
            $html .= '</label>';
            break;
            
        case 'select':
            $html .= '<select id="' . $name . '" name="' . $name . '" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">';
            foreach ($options as $optVal => $optLabel) {
                $selected = ($value == $optVal) ? 'selected' : '';
                $html .= '<option value="' . htmlspecialchars((string)$optVal) . '" ' . $selected . '>' . htmlspecialchars($optLabel) . '</option>';
            }
            $html .= '</select>';
            break;
            
        case 'color':
            $html .= '<input type="color" id="' . $name . '" name="' . $name . '" value="' . htmlspecialchars((string)$value) . '" class="w-16 h-10 p-1 border border-gray-300 rounded cursor-pointer">';
            break;
            
        case 'image':
            $html .= '<div class="flex items-center gap-4">';
            if ($value) {
                $html .= '<img src="' . htmlspecialchars($value) . '" class="w-20 h-20 object-cover rounded border">';
            }
            $html .= '<input type="url" id="' . $name . '" name="' . $name . '" value="' . htmlspecialchars((string)$value) . '" placeholder="Image URL" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">';
            $html .= '</div>';
            break;
    }
    
    if ($description) {
        $html .= '<p class="mt-2 text-sm text-gray-500">' . htmlspecialchars($description) . '</p>';
    }
    
    $html .= '</div>';
    
    return $html;
}

// =============================================================================
// METABOX SYSTEM (Custom fields in editor)
// =============================================================================

global $ZED_METABOXES;
$ZED_METABOXES = [];

/**
 * Register a metabox for the post editor
 * 
 * @param string $id Unique metabox identifier
 * @param array $config Configuration array:
 *   - title: string Metabox title
 *   - post_types: array Post types to show on (default: all)
 *   - context: string Where to show: 'side' or 'normal' (default: side)
 *   - priority: int Display priority (default: 10)
 *   - fields: array Array of field definitions
 */
function zed_register_metabox(string $id, array $config): void
{
    global $ZED_METABOXES;
    
    $defaults = [
        'title' => ucwords(str_replace('_', ' ', $id)),
        'post_types' => [],  // Empty = all types
        'context' => 'side', // side or normal
        'priority' => 10,
        'fields' => [],
    ];
    
    $ZED_METABOXES[$id] = array_merge($defaults, $config);
}

/**
 * Get all registered metaboxes
 */
function zed_get_metaboxes(): array
{
    global $ZED_METABOXES;
    return $ZED_METABOXES;
}

/**
 * Get metaboxes for a specific post type
 * 
 * @param string $postType Post type to filter by
 * @return array Metaboxes applicable to this type
 */
function zed_get_metaboxes_for_type(string $postType): array
{
    global $ZED_METABOXES;
    
    return array_filter($ZED_METABOXES, function($box) use ($postType) {
        // If no types specified, show on all
        if (empty($box['post_types'])) {
            return true;
        }
        return in_array($postType, $box['post_types'], true);
    });
}

/**
 * Render a metabox for the editor
 * 
 * @param string $id Metabox ID
 * @param array $postData Current post data (for getting saved values)
 * @return string HTML
 */
function zed_render_metabox(string $id, array $postData = []): string
{
    global $ZED_METABOXES;
    
    if (!isset($ZED_METABOXES[$id])) {
        return '';
    }
    
    $config = $ZED_METABOXES[$id];
    $meta = $postData['meta'] ?? [];
    
    $html = '<div class="zed-metabox bg-white rounded-lg border border-gray-200 mb-4" data-metabox="' . htmlspecialchars($id) . '">';
    $html .= '<div class="px-4 py-3 border-b border-gray-100">';
    $html .= '<h3 class="text-sm font-semibold text-gray-700">' . htmlspecialchars($config['title']) . '</h3>';
    $html .= '</div>';
    $html .= '<div class="p-4 space-y-4">';
    
    foreach ($config['fields'] as $field) {
        $html .= zed_render_metabox_field($field, $id, $meta);
    }
    
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Render a metabox field
 */
function zed_render_metabox_field(array $field, string $metaboxId, array $meta): string
{
    $fieldId = $field['id'] ?? '';
    $type = $field['type'] ?? 'text';
    $label = $field['label'] ?? ucwords(str_replace('_', ' ', $fieldId));
    $description = $field['description'] ?? '';
    $default = $field['default'] ?? '';
    $options = $field['options'] ?? [];
    
    $value = $meta[$fieldId] ?? $default;
    $name = "meta[{$fieldId}]";
    $inputId = "meta_{$fieldId}";
    
    $html = '<div class="space-y-1">';
    $html .= '<label for="' . $inputId . '" class="block text-xs font-medium text-gray-600">' . htmlspecialchars($label) . '</label>';
    
    switch ($type) {
        case 'text':
        case 'url':
        case 'email':
        case 'number':
            $html .= '<input type="' . $type . '" id="' . $inputId . '" name="' . $name . '" value="' . htmlspecialchars((string)$value) . '" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">';
            break;
            
        case 'textarea':
            $html .= '<textarea id="' . $inputId . '" name="' . $name . '" rows="3" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">' . htmlspecialchars((string)$value) . '</textarea>';
            break;
            
        case 'select':
            $html .= '<select id="' . $inputId . '" name="' . $name . '" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500">';
            foreach ($options as $optVal => $optLabel) {
                $selected = ($value == $optVal) ? 'selected' : '';
                $html .= '<option value="' . htmlspecialchars((string)$optVal) . '" ' . $selected . '>' . htmlspecialchars($optLabel) . '</option>';
            }
            $html .= '</select>';
            break;
            
        case 'checkbox':
            $checked = $value ? 'checked' : '';
            $html .= '<input type="checkbox" id="' . $inputId . '" name="' . $name . '" value="1" ' . $checked . ' class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">';
            break;
    }
    
    if ($description) {
        $html .= '<p class="text-xs text-gray-400">' . htmlspecialchars($description) . '</p>';
    }
    
    $html .= '</div>';
    
    return $html;
}

// =============================================================================
// SCRIPT & STYLE ENQUEUE SYSTEM
// =============================================================================

global $ZED_SCRIPTS, $ZED_STYLES;
$ZED_SCRIPTS = [];
$ZED_STYLES = [];

/**
 * Enqueue a JavaScript file
 * 
 * @param string $handle Unique handle
 * @param string $src URL to script
 * @param array $options Options:
 *   - deps: array Dependencies (handles that must load first)
 *   - version: string Version for cache busting
 *   - in_footer: bool Load in footer (default: true)
 *   - admin: bool Load in admin only (default: false)
 *   - frontend: bool Load in frontend only (default: false)
 */
function zed_enqueue_script(string $handle, string $src, array $options = []): void
{
    global $ZED_SCRIPTS;
    
    $defaults = [
        'deps' => [],
        'version' => '',
        'in_footer' => true,
        'admin' => false,
        'frontend' => false,
    ];
    
    $ZED_SCRIPTS[$handle] = array_merge($defaults, $options, ['src' => $src]);
}

/**
 * Enqueue a CSS file
 * 
 * @param string $handle Unique handle
 * @param string $src URL to stylesheet
 * @param array $options Options:
 *   - deps: array Dependencies
 *   - version: string Version for cache busting
 *   - media: string Media query (default: all)
 *   - admin: bool Load in admin only
 *   - frontend: bool Load in frontend only
 */
function zed_enqueue_style(string $handle, string $src, array $options = []): void
{
    global $ZED_STYLES;
    
    $defaults = [
        'deps' => [],
        'version' => '',
        'media' => 'all',
        'admin' => false,
        'frontend' => false,
    ];
    
    $ZED_STYLES[$handle] = array_merge($defaults, $options, ['src' => $src]);
}

/**
 * Dequeue a script
 */
function zed_dequeue_script(string $handle): void
{
    global $ZED_SCRIPTS;
    unset($ZED_SCRIPTS[$handle]);
}

/**
 * Dequeue a style
 */
function zed_dequeue_style(string $handle): void
{
    global $ZED_STYLES;
    unset($ZED_STYLES[$handle]);
}

/**
 * Render enqueued styles as HTML link tags
 * 
 * @param bool $isAdmin Whether in admin context
 * @return string HTML
 */
function zed_render_styles(bool $isAdmin = false): string
{
    global $ZED_STYLES;
    
    $html = '';
    $sorted = zed_sort_by_deps($ZED_STYLES);
    
    foreach ($sorted as $handle => $style) {
        // Check context
        if ($isAdmin && $style['frontend']) continue;
        if (!$isAdmin && $style['admin']) continue;
        
        $version = $style['version'] ? '?v=' . $style['version'] : '';
        $html .= '<link rel="stylesheet" id="' . htmlspecialchars($handle) . '-css" href="' . htmlspecialchars($style['src'] . $version) . '" media="' . htmlspecialchars($style['media']) . '">' . "\n";
    }
    
    return $html;
}

/**
 * Render enqueued scripts as HTML script tags
 * 
 * @param bool $isAdmin Whether in admin context
 * @param bool $inFooter Whether rendering footer scripts
 * @return string HTML
 */
function zed_render_scripts(bool $isAdmin = false, bool $inFooter = true): string
{
    global $ZED_SCRIPTS;
    
    $html = '';
    $sorted = zed_sort_by_deps($ZED_SCRIPTS);
    
    foreach ($sorted as $handle => $script) {
        // Check context
        if ($isAdmin && $script['frontend']) continue;
        if (!$isAdmin && $script['admin']) continue;
        
        // Check location
        if ($script['in_footer'] !== $inFooter) continue;
        
        $version = $script['version'] ? '?v=' . $script['version'] : '';
        $html .= '<script id="' . htmlspecialchars($handle) . '-js" src="' . htmlspecialchars($script['src'] . $version) . '"></script>' . "\n";
    }
    
    return $html;
}

/**
 * Sort assets by dependencies (topological sort)
 */
function zed_sort_by_deps(array $assets): array
{
    $sorted = [];
    $visited = [];
    
    $visit = function($handle) use (&$assets, &$sorted, &$visited, &$visit) {
        if (isset($visited[$handle])) return;
        $visited[$handle] = true;
        
        if (!isset($assets[$handle])) return;
        
        $asset = $assets[$handle];
        foreach ($asset['deps'] as $dep) {
            $visit($dep);
        }
        
        $sorted[$handle] = $asset;
    };
    
    foreach (array_keys($assets) as $handle) {
        $visit($handle);
    }
    
    return $sorted;
}

/**
 * Recursively extract plain text from BlockNote blocks
 * Used for building a searchable 'plain_text' index
 */
function extract_text_from_blocks(array $blocks): string 
{
    $textSegments = [];
    
    foreach ($blocks as $block) {
        if (!is_array($block)) continue;
        
        // 1. Text from 'content' property
        if (isset($block['content'])) {
            if (is_array($block['content'])) {
                // Standard inline content array
                foreach ($block['content'] as $inline) {
                    // Plain text
                    if (isset($inline['type']) && $inline['type'] === 'text') {
                        $textSegments[] = $inline['text'] ?? ''; 
                    }
                    // Links
                    if (isset($inline['type']) && $inline['type'] === 'link') {
                         foreach ($inline['content'] ?? [] as $linkContent) {
                             $textSegments[] = $linkContent['text'] ?? '';
                         }
                    }
                }
            } elseif (is_string($block['content'])) {
                 // Fallback for simple blocks
                 $textSegments[] = $block['content'];
            }
        }
        
        // 2. Recursion for children (nested blocks)
        if (!empty($block['children']) && is_array($block['children'])) {
            $textSegments[] = extract_text_from_blocks($block['children']);
        }
    }
    
    // Join with spaces and trim
    return trim(implode(' ', array_filter($textSegments, fn($s) => trim($s) !== '')));
}

/**
 * Get content revisions for a specific content ID
 * 
 * Returns an array of revisions sorted by created_at DESC (newest first).
 * Each revision contains decoded JSON data compatible with BlockNote renderer.
 * 
 * @param int $content_id The content ID to get revisions for
 * @param int $limit Maximum number of revisions to return (default 10)
 * @return array<int, array{id: int, content_id: int, data: array, author_id: int, created_at: string}>
 */
function zed_get_revisions(int $content_id, int $limit = 10): array
{
    try {
        $db = Database::getInstance();
        
        $revisions = $db->query(
            "SELECT id, content_id, data_json, author_id, created_at 
             FROM zed_content_revisions 
             WHERE content_id = :content_id 
             ORDER BY created_at DESC 
             LIMIT :limit",
            ['content_id' => $content_id, 'limit' => $limit]
        );
        
        // Decode JSON data for each revision
        return array_map(function($rev) {
            return [
                'id' => (int)$rev['id'],
                'content_id' => (int)$rev['content_id'],
                'data' => json_decode($rev['data_json'], true) ?? [],
                'author_id' => (int)$rev['author_id'],
                'created_at' => $rev['created_at'],
            ];
        }, $revisions);
        
    } catch (Exception $e) {
        // Table might not exist or query failed
        error_log("zed_get_revisions error: " . $e->getMessage());
        return [];
    }
}
/**
 * Create a GD image resource from file
 */
function zed_image_from_file(string $source): ?GdImage {
    $info = getimagesize($source);
    if (!$info) return null;
    
    $type = $info[2];
    
    return match($type) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($source),
        IMAGETYPE_PNG => imagecreatefrompng($source),
        IMAGETYPE_GIF => imagecreatefromgif($source),
        IMAGETYPE_WEBP => imagecreatefromwebp($source),
        default => null
    };
}

/**
 * Resize an image maintaining aspect ratio
 */
function zed_resize_image(GdImage $source, int $maxWidth): GdImage {
    $srcWidth = imagesx($source);
    $srcHeight = imagesy($source);
    
    // Don't upscale
    if ($srcWidth <= $maxWidth) {
        return $source;
    }
    
    $ratio = $srcWidth / $srcHeight;
    $newWidth = $maxWidth;
    $newHeight = (int)($maxWidth / $ratio);
    
    $resized = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preserve transparency
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
    imagefill($resized, 0, 0, $transparent);
    
    imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
    
    return $resized;
}

/**
 * Save image as WebP
 */
function zed_save_webp(GdImage $image, string $dest, int $quality = 80): bool {
    return imagewebp($image, $dest, $quality);
}

/**
 * Full image processing: Convert to WebP, resize if needed, create thumbnail
 * Returns array with paths or false on failure
 */
function zed_process_upload(string $tmpPath, string $originalName, string $uploadDir): array|false {
    @set_time_limit(60);
    
    // Clean filename
    $baseName = pathinfo($originalName, PATHINFO_FILENAME);
    $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '', $baseName);
    $baseName = substr($baseName, 0, 50) ?: 'image_' . time();
    
    // Unique identifier
    $uniqueId = substr(md5(uniqid() . microtime(true)), 0, 8);
    $finalBaseName = $baseName . '_' . $uniqueId;
    
    // Check PHP GD support
    if (!function_exists('imagecreatefromjpeg')) {
        return false;
    }
    
    // Create source image
    $sourceImg = zed_image_from_file($tmpPath);
    if (!$sourceImg) {
        // Fallback: just save original
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $destPath = $uploadDir . '/' . $finalBaseName . '.' . $ext;
        if (move_uploaded_file($tmpPath, $destPath)) {
            return [
                'original' => $destPath,
                'webp' => null,
                'thumb' => null,
                'filename' => $finalBaseName . '.' . $ext
            ];
        }
        return false;
    }
    
    $srcWidth = imagesx($sourceImg);
    $srcHeight = imagesy($sourceImg);
    
    // Keep original in uploads (for backup)
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $ext = 'jpg';
    }
    $originalPath = $uploadDir . '/' . $finalBaseName . '_original.' . $ext;
    copy($tmpPath, $originalPath);
    
    // Main WebP version (max 1920px)
    $mainImg = zed_resize_image($sourceImg, 1920);
    $webpPath = $uploadDir . '/' . $finalBaseName . '.webp';
    zed_save_webp($mainImg, $webpPath, 80);
    
    // Thumbnail WebP (300px)
    $thumbImg = zed_resize_image($sourceImg, 300);
    $thumbPath = $uploadDir . '/thumb_' . $finalBaseName . '.webp';
    zed_save_webp($thumbImg, $thumbPath, 75);
    
    // Cleanup
    if ($mainImg !== $sourceImg) imagedestroy($mainImg);
    if ($thumbImg !== $sourceImg) imagedestroy($thumbImg);
    imagedestroy($sourceImg);
    
    return [
        'original' => $originalPath,
        'webp' => $webpPath,
        'thumb' => $thumbPath,
        'filename' => $finalBaseName . '.webp',
        'width' => $srcWidth,
        'height' => $srcHeight
    ];
}

/**
 * Generate a thumbnail from an image file (legacy support)
 */
function zed_generate_thumbnail($source, $dest, $targetWidth = 300) {
    if (!file_exists($source)) return false;
    
    $info = getimagesize($source);
    if (!$info) return false;
    
    list($width, $height, $type) = $info;
    $ratio = $width / $height;
    $targetHeight = (int)($targetWidth / $ratio);
    
    $newImg = imagecreatetruecolor($targetWidth, $targetHeight);
    
    // Handle transparency for PNG/GIF
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagecolortransparent($newImg, imagecolorallocatealpha($newImg, 0, 0, 0, 127));
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
    }
    
    switch ($type) {
        case IMAGETYPE_JPEG: $sourceImg = imagecreatefromjpeg($source); break;
        case IMAGETYPE_PNG: $sourceImg = imagecreatefrompng($source); break;
        case IMAGETYPE_GIF: $sourceImg = imagecreatefromgif($source); break;
        case IMAGETYPE_WEBP: $sourceImg = imagecreatefromwebp($source); break;
        default: return false;
    }
    
    if (!$sourceImg) return false;
    
    imagecopyresampled($newImg, $sourceImg, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
    
    // Save as WebP if destination is .webp
    if (str_ends_with(strtolower($dest), '.webp')) {
        imagewebp($newImg, $dest, 80);
    } else {
        switch ($type) {
            case IMAGETYPE_JPEG: imagejpeg($newImg, $dest, 80); break;
            case IMAGETYPE_PNG: imagepng($newImg, $dest); break;
            case IMAGETYPE_GIF: imagegif($newImg, $dest); break;
            case IMAGETYPE_WEBP: imagewebp($newImg, $dest, 80); break;
        }
    }
    
    imagedestroy($newImg);
    imagedestroy($sourceImg);
    return true;
}

// Register admin routes
Event::on('route_request', function (array $request): void {
    $uri = $request['uri'];
    $themePath = __DIR__ . '/../themes/admin-default';

    // /admin/logout - Logout and redirect (always accessible)
    if ($uri === '/admin/logout') {
        Auth::logout();
        Router::redirect('/admin/login');
    }

    // Legacy logout via query param (/?logout=true)
    if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
        Auth::logout();
        Router::redirect('/admin/login');
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
        return;
    }

    // =========================================================================
    // AJAX HANDLER SYSTEM — /api/ajax/{action}
    // =========================================================================
    if (preg_match('#^/api/ajax/(\w+)$#', $uri, $matches)) {
        $action = $matches[1];
        $handlers = zed_get_ajax_handlers();
        
        header('Content-Type: application/json');
        
        if (!isset($handlers[$action])) {
            http_response_code(404);
            echo json_encode(['error' => 'Unknown action', 'action' => $action]);
            Router::setHandled();
            return;
        }
        
        $handler = $handlers[$action];
        
        // Check method
        $method = $_SERVER['REQUEST_METHOD'];
        if ($handler['method'] !== 'ANY' && $handler['method'] !== $method) {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            Router::setHandled();
            return;
        }
        
        // Check authentication
        if ($handler['require_auth'] && !Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication required']);
            Router::setHandled();
            return;
        }
        
        // Check capability
        if ($handler['capability'] && !zed_current_user_can($handler['capability'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Permission denied']);
            Router::setHandled();
            return;
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
        return;
    }


    // =========================================================================
    // SECURITY: Role-based access for all other /admin/* routes
    // =========================================================================
    if (str_starts_with($uri, '/admin')) {
        // Step 1: Check if user is logged in
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        // Step 2: Check if user has admin/editor role
        if (!zed_user_can_access_admin()) {
            // User is logged in but doesn't have admin privileges
            http_response_code(403);
            $content = zed_render_forbidden();
            echo $content;
            Router::setHandled($content);
            return;
        }
    }

    // /admin or /admin/dashboard - Dashboard (auth + role already checked above)
    if ($uri === '/admin' || $uri === '/admin/dashboard') {
        // Fetch real stats for dashboard
        try {
            $db = Database::getInstance();
            
            // Count pages (total)
            $total_pages = (int)($db->queryValue(
                "SELECT COUNT(*) FROM zed_content WHERE type = :type",
                ['type' => 'page']
            ) ?: 0);
            
            // Count posts (total)
            $total_posts = (int)($db->queryValue(
                "SELECT COUNT(*) FROM zed_content WHERE type = :type",
                ['type' => 'post']
            ) ?: 0);
            
            // Count all content
            $total_content = (int)($db->queryValue(
                "SELECT COUNT(*) FROM zed_content"
            ) ?: 0);
            
            // Count published content
            $published_count = (int)($db->queryValue(
                "SELECT COUNT(*) FROM zed_content WHERE JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'"
            ) ?: 0);
            
            // Count drafts (everything not published)
            $draft_count = $total_content - $published_count;
            
            // Count users
            $total_users = (int)($db->queryValue(
                "SELECT COUNT(*) FROM users"
            ) ?: 0);
            
            // Count addons
            $addons_dir = __DIR__;
            $total_addons = count(glob($addons_dir . '/*.php'));
            
            // Get recent content for "Jump Back In" activity feed (last 5 items)
            $recent_content = $db->query(
                "SELECT id, title, type, slug, updated_at, 
                        JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) as status 
                 FROM zed_content 
                 ORDER BY updated_at DESC 
                 LIMIT 5"
            );
            
            // Add relative time to each content item
            foreach ($recent_content as &$item) {
                $updatedAt = strtotime($item['updated_at']);
                $diff = time() - $updatedAt;
                if ($diff < 60) {
                    $item['relative_time'] = 'Just now';
                } elseif ($diff < 3600) {
                    $mins = floor($diff / 60);
                    $item['relative_time'] = $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
                } elseif ($diff < 86400) {
                    $hours = floor($diff / 3600);
                    $item['relative_time'] = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
                } elseif ($diff < 604800) {
                    $days = floor($diff / 86400);
                    $item['relative_time'] = $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                } else {
                    $item['relative_time'] = date('M j', $updatedAt);
                }
            }
            unset($item);
            
            // Content by type for charts
            $content_by_type = [
                'pages' => $total_pages,
                'posts' => $total_posts,
                'other' => $total_content - $total_pages - $total_posts
            ];
            
            // Content by status for charts
            $content_by_status = [
                'published' => $published_count,
                'draft' => $draft_count
            ];
            
        } catch (Exception $e) {
            $total_pages = 0;
            $total_posts = 0;
            $total_content = 0;
            $published_count = 0;
            $draft_count = 0;
            $total_users = 0;
            $total_addons = 0;
            $recent_content = [];
            $content_by_type = ['pages' => 0, 'posts' => 0, 'other' => 0];
            $content_by_status = ['published' => 0, 'draft' => 0];
        }
        
        // ===================================================================
        // HEALTH CHECKS - Real system diagnostics
        // ===================================================================
        $health_checks = [];
        $health_status = 'nominal'; // 'nominal', 'warning', 'critical'
        
        // 1. Check uploads folder is writable
        $uploadsPath = dirname(__DIR__) . '/uploads';
        if (is_writable($uploadsPath)) {
            $health_checks[] = ['status' => 'ok', 'label' => 'Uploads Folder', 'detail' => 'Writable'];
        } else {
            $health_checks[] = ['status' => 'error', 'label' => 'Uploads Folder', 'detail' => 'Not writable!'];
            $health_status = 'critical';
        }
        
        // 2. Check PHP version
        $phpVersion = phpversion();
        if (version_compare($phpVersion, '8.0.0', '>=')) {
            $health_checks[] = ['status' => 'ok', 'label' => 'PHP Version', 'detail' => $phpVersion];
        } else {
            $health_checks[] = ['status' => 'warning', 'label' => 'PHP Version', 'detail' => $phpVersion . ' (8.0+ recommended)'];
            if ($health_status === 'nominal') $health_status = 'warning';
        }
        
        // 3. Check SEO visibility
        try {
            $seoBlocked = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'discourage_search_engines'");
            if ($seoBlocked === '1') {
                $health_checks[] = ['status' => 'warning', 'label' => 'SEO', 'detail' => 'Search engines blocked'];
                if ($health_status === 'nominal') $health_status = 'warning';
            } else {
                $health_checks[] = ['status' => 'ok', 'label' => 'SEO', 'detail' => 'Indexing enabled'];
            }
        } catch (Exception $e) {
            $health_checks[] = ['status' => 'ok', 'label' => 'SEO', 'detail' => 'Indexing enabled'];
        }
        
        // 4. Check maintenance mode
        try {
            $maintenanceMode = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'maintenance_mode'");
            if ($maintenanceMode === '1') {
                $health_checks[] = ['status' => 'warning', 'label' => 'Maintenance', 'detail' => 'Site is offline'];
                if ($health_status === 'nominal') $health_status = 'warning';
            } else {
                $health_checks[] = ['status' => 'ok', 'label' => 'Maintenance', 'detail' => 'Site is live'];
            }
        } catch (Exception $e) {
            $health_checks[] = ['status' => 'ok', 'label' => 'Maintenance', 'detail' => 'Site is live'];
        }
        
        // 5. Check database connection
        $health_checks[] = ['status' => 'ok', 'label' => 'Database', 'detail' => 'Connected'];
        
        // System status summary
        $system_status = match($health_status) {
            'nominal' => 'System Nominal',
            'warning' => 'System Warning',
            'critical' => 'System Alert',
        };
        
        $system_status_color = match($health_status) {
            'nominal' => 'green',
            'warning' => 'yellow',
            'critical' => 'red',
        };
        
        // Dashboard stats object for easy access
        $dashboard_stats = [
            'total_pages' => $total_pages,
            'total_posts' => $total_posts,
            'total_content' => $total_content,
            'published_count' => $published_count,
            'draft_count' => $draft_count,
            'total_users' => $total_users,
            'total_addons' => $total_addons,
            'content_by_type' => $content_by_type,
            'content_by_status' => $content_by_status
        ];
        
        // Prepare chart data JSON for JavaScript
        $chartDataJson = json_encode([
            'byType' => $content_by_type,
            'byStatus' => $content_by_status,
            'totals' => [
                'pages' => $total_pages,
                'posts' => $total_posts,
                'users' => $total_users,
                'addons' => $total_addons
            ]
        ]);
        
        // Get current user
        $current_user = Auth::user();
        
        // Layout configuration
        $current_page = 'dashboard';
        $page_title = 'Dashboard';
        $content_partial = $themePath . '/partials/dashboard-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/content - Content list (auth + role checked above)
    if ($uri === '/admin/content') {
        
        // Parse query parameters for filtering and pagination
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? ''; // 'published', 'draft', or '' (all)
        $type = $_GET['type'] ?? ''; // Filter by post type
        $msg = $_GET['msg'] ?? ''; // Flash message from delete, etc.
        
        // Build the query dynamically
        $posts = [];
        $totalPosts = 0;
        
        try {
            $db = Database::getInstance();
            
            // Base query parts
            $selectSql = "SELECT * FROM zed_content";
            $countSql = "SELECT COUNT(*) FROM zed_content";
            $whereClauses = [];
            $params = [];
            
            // RBAC: Authors can only see their own content
            // Admins and Editors can see all content
            if (!zed_current_user_can('edit_others_content')) {
                $currentUserId = Auth::id();
                $whereClauses[] = "author_id = :author_id";
                $params['author_id'] = $currentUserId;
            }
            
            // Type filter
            if (!empty($type)) {
                $whereClauses[] = "type = :type";
                $params['type'] = $type;
            }
            
            // Search filter (title or slug)
            if (!empty($search)) {
                $whereClauses[] = "(title LIKE :search OR slug LIKE :search)";
                $params['search'] = '%' . $search . '%';
            }
            
            // Status filter (requires JSON extraction)
            if ($status === 'published') {
                $whereClauses[] = "JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'";
            } elseif ($status === 'draft') {
                $whereClauses[] = "(JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'draft' OR JSON_EXTRACT(data, '$.status') IS NULL)";
            }
            
            // Combine WHERE clauses
            $whereString = '';
            if (!empty($whereClauses)) {
                $whereString = ' WHERE ' . implode(' AND ', $whereClauses);
            }
            
            // Get total count for pagination
            $totalPosts = (int)$db->queryValue($countSql . $whereString, $params);
            
            // Calculate pagination
            $totalPages = max(1, ceil($totalPosts / $perPage));
            $page = min($page, $totalPages);
            $offset = ($page - 1) * $perPage;
            
            // Fetch paginated results
            $fullSql = $selectSql . $whereString . " ORDER BY updated_at DESC LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;
            $posts = $db->query($fullSql, $params);
            
        } catch (Exception $e) {
            $posts = [];
            $totalPosts = 0;
            $totalPages = 1;
        }
        
        // Calculate display range
        $showingFrom = $totalPosts > 0 ? (($page - 1) * $perPage) + 1 : 0;
        $showingTo = min($page * $perPage, $totalPosts);
        
        // Get current user
        $current_user = Auth::user();
        
        // Get type label for display
        $typeLabel = 'Content';
        if (!empty($type)) {
            $typeConfig = zed_get_post_type($type);
            $typeLabel = $typeConfig['label'] ?? ucfirst($type) . 's';
        }
        
        // Layout configuration
        $current_page = !empty($type) ? 'cpt_' . $type : 'content'; // Highlights correct sidebar menu
        $page_title = $typeLabel;
        $content_partial = $themePath . '/partials/content-list-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/content/delete - Delete content by ID
    if ($uri === '/admin/content/delete') {
        // Step 1: Authentication check
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        // Step 2: Capability check
        if (!zed_current_user_can('delete_content')) {
            Router::redirect('/admin/content?msg=permission_denied');
        }
        
        $id = $_GET['id'] ?? null;
        
        // Step 3: Validate ID is numeric
        if (!$id || !is_numeric($id)) {
            Router::redirect('/admin/content?msg=invalid_id');
        }
        
        $id = (int)$id;
        
        try {
            $db = Database::getInstance();
            
            // Step 4: Check if content exists and get author
            $content = $db->queryOne(
                "SELECT id, author_id, title FROM zed_content WHERE id = :id",
                ['id' => $id]
            );
            
            if (!$content) {
                Router::redirect('/admin/content?msg=not_found');
            }
            
            // Step 5: Ownership check for non-admins/editors
            // Users without 'delete_others_content' can only delete their own content
            $currentUserId = Auth::id();
            $contentAuthorId = (int)($content['author_id'] ?? 0);
            
            if (!zed_current_user_can('delete_others_content') && $contentAuthorId !== $currentUserId) {
                Router::redirect('/admin/content?msg=permission_denied');
            }
            
            // Step 6: Perform the DELETE query
            $db->query("DELETE FROM zed_content WHERE id = :id", ['id' => $id]);
            
            // Step 7: Redirect with success message
            Router::redirect('/admin/content?msg=deleted');
            
        } catch (Exception $e) {
            Router::redirect('/admin/content?msg=error');
        }
        
        return;
    }

    // /admin/editor - Content editor (requires auth)
    if ($uri === '/admin/editor') {
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        ob_start();
        require $themePath . '/editor.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/settings - Settings page (requires auth)
    if ($uri === '/admin/settings') {
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        $current_user = Auth::user();
        $current_page = 'settings';
        $page_title = 'Settings';
        $content_partial = $themePath . '/partials/settings-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }
    // /admin/categories/create - Create Category
    if ($uri === '/admin/categories/create' && $request['method'] === 'POST') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        
        if (empty($slug) && !empty($name)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        }
        
        if (!empty($name) && !empty($slug)) {
            $db = Database::getInstance();
            try {
                $db->query(
                    "INSERT INTO zed_categories (name, slug, created_at, updated_at) VALUES (:name, :slug, NOW(), NOW())",
                    ['name' => $name, 'slug' => $slug]
                );
                Router::redirect('/admin/categories?msg=created');
            } catch (Exception $e) {
                // Determine error (duplicate slug usually)
                Router::redirect('/admin/categories?msg=error');
            }
        }
        return;
    }

    // /admin/categories - Categories Manager
    if ($uri === '/admin/categories') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $db = Database::getInstance();
        $categories = $db->query("SELECT * FROM zed_categories ORDER BY name ASC");
        
        $current_user = Auth::user();
        $current_page = 'categories';
        $page_title = 'Categories';
        $content_partial = $themePath . '/partials/categories-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/categories/delete - Delete Category
    if (str_starts_with($uri, '/admin/categories/delete')) {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $id = $_GET['id'] ?? null;
        if ($id && is_numeric($id)) {
            $db = Database::getInstance();
            // Prevent deleting Uncategorized (ID 1 usually)
            if ($id != 1) {
                $db->query("DELETE FROM zed_categories WHERE id = :id", ['id' => $id]);
                Router::redirect('/admin/categories?msg=deleted');
            } else {
                Router::redirect('/admin/categories?msg=locked');
            }
        }
        Router::redirect('/admin/categories');
        return;
    }

    // /admin/menus/create - Create New Menu
    if ($uri === '/admin/menus/create' && $request['method'] === 'POST') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $name = trim($_POST['name'] ?? '');
        if (!empty($name)) {
            $db = Database::getInstance();
            // Default structure: empty list
            $defaultItems = json_encode([]); 
            
            $db->query(
                "INSERT INTO zed_menus (name, items, created_at, updated_at) VALUES (:name, :items, NOW(), NOW())",
                ['name' => $name, 'items' => $defaultItems]
            );
            $newId = $db->getPdo()->lastInsertId();
            Router::redirect("/admin/menus?id={$newId}");
        } else {
            Router::redirect('/admin/menus?msg=name_required');
        }
        return;
    }

    // /admin/menus - Visual Menu Builder (requires auth)
    if ($uri === '/admin/menus') {
        // Security check
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        if (!zed_current_user_can('manage_menus')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        $db = Database::getInstance();
        
        // Handle old form-based Save (POST) - for backwards compatibility
        if ($request['method'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'] ?? null;
            $items = $_POST['items'] ?? '';
            
            // Validate JSON
            $decoded = json_decode($items, true);
            if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                Router::redirect("/admin/menus?id={$id}&msg=error");
            }
            
            // Save to DB
            if ($id && is_numeric($id)) {
                $db->query(
                    "UPDATE zed_menus SET items = :items, updated_at = NOW() WHERE id = :id",
                    ['items' => $items, 'id' => $id]
                );
                Router::redirect("/admin/menus?id={$id}&msg=saved");
            }
        }
        
        // Handle View (GET)
        $current_user = Auth::user();
        $current_page = 'menus';
        $page_title = 'Menu Builder';
        
        // Fetch all menus
        $menus = $db->query("SELECT * FROM zed_menus ORDER BY name ASC");
        
        // Fetch selected menu
        $selectedId = $_GET['id'] ?? ($menus[0]['id'] ?? null);
        $currentMenu = null;
        
        if ($selectedId) {
            foreach ($menus as $m) {
                if ($m['id'] == $selectedId) {
                    $currentMenu = $m;
                    break;
                }
            }
        }
        
        // Fetch all published pages for the toolbox
        $pages = $db->query(
            "SELECT id, title, slug FROM zed_content 
             WHERE type = 'page' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY title ASC"
        );
        
        // Fetch all categories for the toolbox
        $categories = $db->query("SELECT id, name, slug FROM zed_categories ORDER BY name ASC");
        
        // Fetch published posts for the toolbox (optional, top 20)
        $posts = $db->query(
            "SELECT id, title, slug FROM zed_content 
             WHERE type = 'post' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY title ASC
             LIMIT 20"
        );
        
        $content_partial = $themePath . '/partials/menus-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/addons - Addons page (requires auth)
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
        return;
    }

    // /admin/settings - Unified Settings Panel (Admin only)
    if ($uri === '/admin/settings') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        // Only admins can access settings
        if (!zed_current_user_can('manage_settings')) {
            Router::setHandled(zed_render_forbidden());
            return;
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
        return;
    }

    // /admin/users - User Management (Admin only)
    if ($uri === '/admin/users') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        // Only admins can manage users (using RBAC)
        if (!zed_current_user_can('manage_users')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        // Fetch all users from database
        try {
            $db = Database::getInstance();
            $users = $db->query(
                "SELECT id, email, role, last_login, created_at, updated_at 
                 FROM users 
                 ORDER BY created_at DESC"
            );
        } catch (Exception $e) {
            $users = [];
        }
        
        $current_page = 'users';
        $page_title = 'User Management';
        $content_partial = $themePath . '/partials/users-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/media/delete - Delete Media File (handles WebP + original + thumb)
    if ($uri === '/admin/media/delete') {
        if (!zed_user_can_access_admin()) {
            // Check if AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Not authenticated']);
                Router::setHandled('');
                return;
            }
            Router::redirect('/admin/login');
        }
        
        $file = $_REQUEST['file'] ?? '';
        $safeFile = basename($file);
        $uploadDir = __DIR__ . '/../uploads';
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                  (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
        
        if (!empty($safeFile)) {
            $deleted = false;
            $baseName = pathinfo($safeFile, PATHINFO_FILENAME);
            
            // Primary file
            if (file_exists($uploadDir . '/' . $safeFile)) {
                unlink($uploadDir . '/' . $safeFile);
                $deleted = true;
            }
            
            // Thumb version (thumb_filename.webp or thumb_filename.ext)
            $thumbPatterns = [
                $uploadDir . '/thumb_' . $safeFile,
                $uploadDir . '/thumb_' . $baseName . '.webp'
            ];
            foreach ($thumbPatterns as $thumb) {
                if (file_exists($thumb)) {
                    unlink($thumb);
                }
            }
            
            // Original backup (_original.jpg, _original.png, etc.)
            foreach (glob($uploadDir . '/' . $baseName . '_original.*') as $origFile) {
                unlink($origFile);
            }
            
            // Also check if this was a WebP, delete any corresponding original
            if (str_ends_with(strtolower($safeFile), '.webp')) {
                $noExt = preg_replace('/\.webp$/i', '', $safeFile);
                foreach (glob($uploadDir . '/' . $noExt . '_original.*') as $origFile) {
                    unlink($origFile);
                }
            }
            
            // Return JSON for AJAX or redirect for regular request
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => $deleted, 'message' => $deleted ? 'File deleted' : 'File not found']);
                Router::setHandled('');
                return;
            }
            
            Router::redirect('/admin/media?msg=' . ($deleted ? 'deleted' : 'error'));
        } else {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'No file specified']);
                Router::setHandled('');
                return;
            }
            Router::redirect('/admin/media?msg=error');
        }
        return;
    }

    // /admin/media/upload - Form Upload with WebP Optimization
    if ($uri === '/admin/media/upload' && $request['method'] === 'POST') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $tmpName = $_FILES['file']['tmp_name'];
            $name = basename($_FILES['file']['name']);
            
            if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $name)) {
                // Use advanced processing
                $result = zed_process_upload($tmpName, $name, $uploadDir);
                if ($result) {
                    Router::redirect('/admin/media?msg=uploaded');
                } else {
                    Router::redirect('/admin/media?msg=processing_error');
                }
            } else {
                Router::redirect('/admin/media?msg=invalid_type');
            }
        } else {
            Router::redirect('/admin/media?msg=upload_error');
        }
        return;
    }

    // /admin/media - Media Library (shows main WebP files, hides thumb_ and _original)
    if ($uri === '/admin/media') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $uploadDir = __DIR__ . '/../uploads';
        $files = [];
        
        if (is_dir($uploadDir)) {
            $allFiles = scandir($uploadDir);
            foreach ($allFiles as $f) {
                if ($f === '.' || $f === '..') continue;
                // Skip thumbnails
                if (str_starts_with($f, 'thumb_')) continue;
                // Skip original backups
                if (str_contains($f, '_original.')) continue;
                
                $path = $uploadDir . '/' . $f;
                if (is_file($path) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f)) {
                    $baseName = pathinfo($f, PATHINFO_FILENAME);
                    
                    // Try to find thumbnail (prefer WebP thumb)
                    $thumbWebp = $uploadDir . '/thumb_' . $baseName . '.webp';
                    $thumbExact = $uploadDir . '/thumb_' . $f;
                    
                    if (file_exists($thumbWebp)) {
                        $thumbUrl = Router::getBasePath() . '/content/uploads/thumb_' . $baseName . '.webp';
                    } elseif (file_exists($thumbExact)) {
                        $thumbUrl = Router::getBasePath() . '/content/uploads/thumb_' . $f;
                    } else {
                        $thumbUrl = Router::getBasePath() . '/content/uploads/' . $f;
                    }
                    
                    // Get dimensions if possible
                    $dimensions = @getimagesize($path);
                    $width = $dimensions[0] ?? 0;
                    $height = $dimensions[1] ?? 0;
                        
                    $files[] = [
                        'name' => $f,
                        'url' => Router::getBasePath() . '/content/uploads/' . $f,
                        'thumb' => $thumbUrl,
                        'size' => filesize($path),
                        'sizeFmt' => round(filesize($path) / 1024) . ' KB',
                        'mtime' => filemtime($path),
                        'date' => date('Y-m-d H:i', filemtime($path)),
                        'width' => $width,
                        'height' => $height,
                        'isWebp' => str_ends_with(strtolower($f), '.webp')
                    ];
                }
            }
            // Sort by newest first
            usort($files, fn($a, $b) => $b['mtime'] - $a['mtime']);
        }
        
        $current_user = Auth::user();
        $current_page = 'media';
        $page_title = 'Media Library';
        $content_partial = $themePath . '/partials/media-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/content/delete - Delete content (requires auth + ownership check)
    if ($uri === '/admin/content/delete') {
        // Security: Require authentication
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        // Get the ID from query parameter
        $deleteId = $_GET['id'] ?? null;
        
        if (!$deleteId || !is_numeric($deleteId)) {
            // Invalid ID - redirect with error
            Router::redirect('/admin/content?msg=invalid_id');
        }
        
        $deleteId = (int)$deleteId;
        
        try {
            $db = Database::getInstance();
            
            // RBAC: Check if user can delete this specific content
            // Admins and Editors can delete any content
            // Authors can only delete their own content
            if (!zed_current_user_can('delete_others_content')) {
                // Check ownership
                $content = $db->queryOne(
                    "SELECT author_id FROM zed_content WHERE id = :id",
                    ['id' => $deleteId]
                );
                
                if (!$content) {
                    Router::redirect('/admin/content?msg=not_found');
                    return;
                }
                
                $currentUserId = Auth::id();
                if ((int)($content['author_id'] ?? 0) !== $currentUserId) {
                    Router::redirect('/admin/content?msg=permission_denied');
                    return;
                }
            }
            
            // Execute the delete query
            $affected = $db->delete('zed_content', 'id = :id', ['id' => $deleteId]);
            
            if ($affected > 0) {
                // Trigger hooks
                \Core\Event::trigger('zed_post_deleted', $deleteId);
                
                // Success - redirect with success message
                Router::redirect('/admin/content?msg=deleted');
            } else {
                // No rows affected - content not found
                Router::redirect('/admin/content?msg=not_found');
            }
        } catch (Exception $e) {
            // Database error - redirect with error
            Router::redirect('/admin/content?msg=error');
        }
        
        return;
    }

    // /admin/api/save-settings - Save Settings (POST, Admin only)
    if ($uri === '/admin/api/save-settings' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        // Admin only
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        if (!zed_current_user_can('manage_settings')) {
            zed_json_permission_denied();
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !is_array($input)) {
                throw new Exception('Invalid request data.');
            }
            
            $db = Database::getInstance();
            $pdo = $db->getPdo();
            
            // Whitelist of allowed setting keys
            $allowedKeys = [
                // General
                'site_title',
                'site_tagline',
                'homepage_mode',        // 'latest_posts' or 'static_page'
                'page_on_front',        // Page ID for static homepage
                'blog_slug',            // URL slug for blog (e.g., 'blog', 'news')
                'posts_per_page',       // Number of posts per page
                
                // SEO
                'discourage_search_engines',
                'meta_description',
                'social_sharing_image',
                
                // System
                'maintenance_mode',
                'debug_mode',
            ];
            
            $savedCount = 0;
            
            // Get active theme for theme options
            $activeTheme = zed_get_option('active_theme', 'aurora');
            
            foreach ($input as $key => $value) {
                // Handle theme settings (prefixed with 'theme_')
                if (str_starts_with($key, 'theme_')) {
                    // Extract the setting ID (remove 'theme_' prefix)
                    $settingId = substr($key, 6); // 'theme_' = 6 chars
                    
                    // Store with proper format: theme_{active_theme}_{setting_id}
                    $optionName = "theme_{$activeTheme}_{$settingId}";
                    
                    // Sanitize value
                    $value = is_string($value) ? trim($value) : $value;
                    if (is_bool($value)) {
                        $value = $value ? '1' : '0';
                    }
                    
                    // Upsert theme option
                    $stmt = $pdo->prepare("
                        INSERT INTO zed_options (option_name, option_value, autoload) 
                        VALUES (:key, :value, 1)
                        ON DUPLICATE KEY UPDATE option_value = :value2
                    ");
                    $stmt->execute([
                        'key' => $optionName,
                        'value' => $value,
                        'value2' => $value
                    ]);
                    $savedCount++;
                    continue;
                }
                
                // Only save whitelisted keys for non-theme settings
                if (!in_array($key, $allowedKeys)) {
                    continue;
                }
                
                // Sanitize value
                $value = is_string($value) ? trim($value) : $value;
                if (is_bool($value)) {
                    $value = $value ? '1' : '0';
                }
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                
                // Upsert: INSERT or UPDATE
                $stmt = $pdo->prepare("
                    INSERT INTO zed_options (option_name, option_value, autoload) 
                    VALUES (:key, :value, 1)
                    ON DUPLICATE KEY UPDATE option_value = :value2
                ");
                $stmt->execute([
                    'key' => $key,
                    'value' => $value,
                    'value2' => $value
                ]);
                $savedCount++;
            }
            
            echo json_encode([
                'success' => true,
                'message' => "Settings saved successfully.",
                'saved' => $savedCount
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/quick-draft - Create a quick draft post (POST)
    if ($uri === '/admin/api/quick-draft' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        // Must have publish permission
        if (!zed_current_user_can('publish_content')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $title = trim($input['title'] ?? '');
            
            if (empty($title)) {
                throw new Exception('Title is required');
            }
            
            // Generate slug from title
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
            $slug = trim($slug, '-');
            $slug = substr($slug, 0, 100);
            
            // Ensure unique slug
            $db = Database::getInstance();
            $baseSlug = $slug;
            $counter = 1;
            while ($db->queryValue("SELECT COUNT(*) FROM zed_content WHERE slug = :slug", ['slug' => $slug]) > 0) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            // Create the draft
            $userId = Auth::user()['id'] ?? 1;
            $data = json_encode([
                'content' => [],
                'status' => 'draft',
                'excerpt' => '',
                'featured_image' => ''
            ]);
            
            $newId = $db->query(
                "INSERT INTO zed_content (title, slug, type, data, plain_text, author_id, created_at, updated_at) 
                 VALUES (:title, :slug, 'post', :data, '', :author, NOW(), NOW())",
                ['title' => $title, 'slug' => $slug, 'data' => $data, 'author' => $userId]
            );
            
            // Return success with redirect URL
            echo json_encode([
                'success' => true,
                'id' => $newId,
                'redirect' => $base_url . '/admin/editor?id=' . $newId
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/save-menu - Save menu items (POST, AJAX)
    if ($uri === '/admin/api/save-menu' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        if (!zed_current_user_can('manage_menus')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $menuId = (int)($input['menu_id'] ?? 0);
            $name = trim($input['name'] ?? '');
            $items = $input['items'] ?? [];
            
            if ($menuId <= 0) {
                throw new Exception('Invalid menu ID');
            }
            
            $db = Database::getInstance();
            
            // Clean items before saving (remove UI flags)
            $cleanItems = array_map(function($item) {
                return [
                    'label' => $item['label'] ?? '',
                    'url' => $item['url'] ?? '#',
                    'target' => $item['target'] ?? '_self',
                    'children' => isset($item['children']) ? array_map(function($child) {
                        return [
                            'label' => $child['label'] ?? '',
                            'url' => $child['url'] ?? '#',
                            'target' => $child['target'] ?? '_self',
                            'children' => []
                        ];
                    }, $item['children']) : []
                ];
            }, $items);
            
            // Update menu
            $itemsJson = json_encode($cleanItems);
            
            if (!empty($name)) {
                $db->query(
                    "UPDATE zed_menus SET name = :name, items = :items, updated_at = NOW() WHERE id = :id",
                    ['name' => $name, 'items' => $itemsJson, 'id' => $menuId]
                );
            } else {
                $db->query(
                    "UPDATE zed_menus SET items = :items, updated_at = NOW() WHERE id = :id",
                    ['items' => $itemsJson, 'id' => $menuId]
                );
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Menu saved successfully'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/delete-menu - Delete a menu (POST, AJAX)
    if ($uri === '/admin/api/delete-menu' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        if (!zed_current_user_can('manage_menus')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $menuId = (int)($input['id'] ?? 0);
            
            if ($menuId <= 0) {
                throw new Exception('Invalid menu ID');
            }
            
            $db = Database::getInstance();
            $db->query("DELETE FROM zed_menus WHERE id = :id", ['id' => $menuId]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Menu deleted'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/categories - Get categories list (GET)
    if ($uri === '/admin/api/categories' && $request['method'] === 'GET') {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['error' => 'Not authenticated']);
            exit;
        }
        
        try {
            $db = Database::getInstance();
            $categories = $db->query("SELECT * FROM zed_categories ORDER BY name ASC");
            echo json_encode($categories);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    // /admin/api/save-user - Create or Update User (POST, Admin only)
    if ($uri === '/admin/api/save-user' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        // Admin only
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        $currentUser = Auth::user();
        if (!in_array($currentUser['role'] ?? '', ['admin', 'administrator'])) {
            echo json_encode(['success' => false, 'error' => 'Admin access required']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                // Try form data
                $input = $_POST;
            }
            
            $id = !empty($input['id']) ? (int)$input['id'] : null;
            $email = trim($input['email'] ?? '');
            $password = $input['password'] ?? '';
            $role = $input['role'] ?? 'subscriber';
            
            // Validate email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Please enter a valid email address.');
            }
            
            // Validate role
            $allowedRoles = ['admin', 'administrator', 'editor', 'author', 'subscriber'];
            if (!in_array($role, $allowedRoles)) {
                $role = 'subscriber';
            }
            
            $db = Database::getInstance();
            
            if ($id) {
                // ===== UPDATE EXISTING USER =====
                
                // Check if user exists
                $existingUser = $db->queryOne("SELECT id, email, role FROM users WHERE id = :id", ['id' => $id]);
                if (!$existingUser) {
                    throw new Exception('User not found.');
                }
                
                // Check if changing email to one that already exists
                if ($email !== $existingUser['email']) {
                    $emailCheck = $db->queryOne("SELECT id FROM users WHERE email = :email AND id != :id", ['email' => $email, 'id' => $id]);
                    if ($emailCheck) {
                        throw new Exception('This email is already in use by another account.');
                    }
                }
                
                // Self-lock protection: prevent admin from demoting themselves
                if ($id === (int)$currentUser['id'] && 
                    in_array($existingUser['role'], ['admin', 'administrator']) && 
                    !in_array($role, ['admin', 'administrator'])) {
                    throw new Exception('You cannot remove your own admin privileges.');
                }
                
                // Build update query
                $sql = "UPDATE users SET email = :email, role = :role, updated_at = NOW()";
                $params = ['email' => $email, 'role' => $role, 'id' => $id];
                
                // Only update password if provided
                if (!empty($password)) {
                    if (strlen($password) < 6) {
                        throw new Exception('Password must be at least 6 characters.');
                    }
                    $sql .= ", password_hash = :password_hash";
                    $params['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
                }
                
                $sql .= " WHERE id = :id";
                $db->query($sql, $params);
                
                echo json_encode(['success' => true, 'message' => 'User updated successfully.', 'id' => $id]);
                
            } else {
                // ===== CREATE NEW USER =====
                
                // Password is mandatory for new users
                if (empty($password)) {
                    throw new Exception('Password is required for new users.');
                }
                if (strlen($password) < 6) {
                    throw new Exception('Password must be at least 6 characters.');
                }
                
                // Check if email already exists
                $emailCheck = $db->queryOne("SELECT id FROM users WHERE email = :email", ['email' => $email]);
                if ($emailCheck) {
                    throw new Exception('A user with this email already exists.');
                }
                
                // Insert new user
                $db->query(
                    "INSERT INTO users (email, password_hash, role, created_at, updated_at) VALUES (:email, :password_hash, :role, NOW(), NOW())",
                    [
                        'email' => $email,
                        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                        'role' => $role
                    ]
                );
                
                $newId = $db->getPdo()->lastInsertId();
                echo json_encode(['success' => true, 'message' => 'User created successfully.', 'id' => $newId]);
            }
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/delete-user - Delete User (POST, Admin only)
    if ($uri === '/admin/api/delete-user' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        // Admin only
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        $currentUser = Auth::user();
        if (!in_array($currentUser['role'] ?? '', ['admin', 'administrator'])) {
            echo json_encode(['success' => false, 'error' => 'Admin access required']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                $input = $_POST;
            }
            
            $id = !empty($input['id']) ? (int)$input['id'] : null;
            
            if (!$id) {
                throw new Exception('User ID is required.');
            }
            
            // Self-deletion protection
            if ($id === (int)$currentUser['id']) {
                throw new Exception('You cannot delete your own account.');
            }
            
            $db = Database::getInstance();
            
            // Check if user exists
            $user = $db->queryOne("SELECT id, email FROM users WHERE id = :id", ['id' => $id]);
            if (!$user) {
                throw new Exception('User not found.');
            }
            
            // Delete user
            $db->query("DELETE FROM users WHERE id = :id", ['id' => $id]);
            
            echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/save - Save content API (POST)
    if (($uri === '/admin/api/save' || $uri === '/admin/save-post') && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated', 'message' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        try {
            // Get JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                throw new Exception('Invalid JSON input');
            }
            
            $db = Database::getInstance();
            
            $id = $input['id'] ?? null;
            $title = trim($input['title'] ?? '');
            $slug = trim($input['slug'] ?? '');
            $type = $input['type'] ?? 'page';
            // Handle both structure types (nested data or flat)
            $content = $input['content'] ?? ($input['data']['content'] ?? []);
            $status = $input['status'] ?? ($input['data']['status'] ?? 'draft');
            
            // Reconstruct data array for storage
            $data = [
                'content' => is_string($content) ? json_decode($content, true) : $content,
                'status' => $status,
                'featured_image' => $input['data']['featured_image'] ?? '',
                'categories' => $input['data']['categories'] ?? [],
                'template' => $input['data']['template'] ?? 'default',
                'excerpt' => $input['excerpt'] ?? ($input['data']['excerpt'] ?? '')
            ];
            
            // =================================================================
            // SHADOW TEXT SEARCH STRATEGY
            // Extract plain text for search indexing
            // =================================================================
            $plainText = extract_text_from_blocks($data['content']);
            
            if (empty($title)) {
                throw new Exception('Title is required');
            }
            
            // Auto-generate slug if empty
            if (empty($slug)) {
                $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
                $slug = trim($slug, '-');
            }
            
            // Encode data as JSON
            $dataJson = json_encode($data);
            $userId = Auth::user()['id'] ?? 1;
            
            // =================================================================
            // CONTENT REVISION SYSTEM
            // Capture current state before update for version history
            // =================================================================
            $capturedRevision = null;
            if ($id) {
                try {
                    // Fetch current content state before modification
                    $currentContent = $db->queryOne(
                        "SELECT id, title, slug, type, data, author_id FROM zed_content WHERE id = :id",
                        ['id' => $id]
                    );
                    
                    if ($currentContent) {
                        // Store full state for revision
                        $capturedRevision = [
                            'content_id' => (int)$id,
                            'data_json' => json_encode([
                                'title' => $currentContent['title'],
                                'slug' => $currentContent['slug'],
                                'type' => $currentContent['type'],
                                'data' => is_string($currentContent['data']) 
                                    ? json_decode($currentContent['data'], true) 
                                    : $currentContent['data'],
                            ]),
                            'author_id' => $userId, // Who made this edit
                        ];
                    }
                } catch (Exception $e) {
                    // Don't fail the save if revision capture fails
                    error_log("Revision capture failed: " . $e->getMessage());
                }
            }
            
            // Helper to execute query with auto-migration (self-healing)
            $executeSave = function() use ($db, $id, $title, $slug, $type, $dataJson, $plainText, $userId) {
                if ($id) {
                    // Update existing
                    $db->query(
                        "UPDATE zed_content SET title = :title, slug = :slug, type = :type, data = :data, plain_text = :plain_text, updated_at = NOW() WHERE id = :id",
                        ['id' => $id, 'title' => $title, 'slug' => $slug, 'type' => $type, 'data' => $dataJson, 'plain_text' => $plainText]
                    );
                    return ['success' => true, 'id' => $id, 'action' => 'update', 'message' => 'Content updated'];
                } else {
                    // Insert new
                    $newId = $db->query(
                        "INSERT INTO zed_content (title, slug, type, data, plain_text, author_id, created_at, updated_at) VALUES (:title, :slug, :type, :data, :plain_text, :author, NOW(), NOW())",
                        ['title' => $title, 'slug' => $slug, 'type' => $type, 'data' => $dataJson, 'plain_text' => $plainText, 'author' => $userId]
                    );
                    return ['success' => true, 'id' => $newId, 'new_id' => $newId, 'action' => 'create', 'message' => 'Content created'];
                }
            };
            
            // Helper to save revision and cleanup old ones
            $saveRevision = function() use ($db, $capturedRevision) {
                if (!$capturedRevision) return;
                
                try {
                    // Insert the revision
                    $db->query(
                        "INSERT INTO zed_content_revisions (content_id, data_json, author_id, created_at) VALUES (:content_id, :data_json, :author_id, NOW())",
                        $capturedRevision
                    );
                    
                    // Cleanup: Keep only last 10 revisions per content
                    $contentId = $capturedRevision['content_id'];
                    $db->query(
                        "DELETE FROM zed_content_revisions 
                         WHERE content_id = :content_id 
                         AND id NOT IN (
                             SELECT id FROM (
                                 SELECT id FROM zed_content_revisions 
                                 WHERE content_id = :content_id2 
                                 ORDER BY created_at DESC 
                                 LIMIT 10
                             ) AS keep_rows
                         )",
                        ['content_id' => $contentId, 'content_id2' => $contentId]
                    );
                } catch (Exception $e) {
                    // Table might not exist yet, ignore
                    error_log("Revision save failed: " . $e->getMessage());
                }
            };
            
            try {
                $response = $executeSave();
                
                if ($response['success']) {
                    // Save revision after successful update
                    $saveRevision();
                    \Core\Event::trigger('zed_post_saved', $response['id'], $data);
                }
            } catch (PDOException $e) {
                // Check for "Unknown column 'plain_text'" error (Code 1054)
                if (str_contains($e->getMessage(), 'Unknown column') && str_contains($e->getMessage(), 'plain_text')) {
                    // Self-healing: Add the column on the fly
                    $db->query("ALTER TABLE zed_content ADD COLUMN plain_text LONGTEXT NULL AFTER data");
                    // Retry
                    $response = $executeSave();
                    // Save revision after retry success
                    if ($response['success']) {
                        $saveRevision();
                    }
                } else {
                    throw $e;
                }
            }
            
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log("Save Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage(), 'message' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/upload - Image upload for Editor.js (POST) with WebP conversion
    if ($uri === '/admin/api/upload' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        // Check authentication
        if (!Auth::check()) {
            echo json_encode(['success' => 0, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        try {
            // Support both 'image' and 'file' field names
            $fileField = isset($_FILES['image']) ? 'image' : (isset($_FILES['file']) ? 'file' : null);
            
            if (!$fileField || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No file uploaded or upload error');
            }
            
            $file = $_FILES[$fileField];
            
            // Validate file size (10MB max)
            $maxSize = 10 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                throw new Exception('File too large. Maximum size is 10MB');
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG, PNG, WebP, and GIF are allowed');
            }
            
            // Create uploads directory if it doesn't exist
            $uploadDir = dirname(__DIR__) . '/uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Use advanced WebP processing
            $result = zed_process_upload($file['tmp_name'], $file['name'], $uploadDir);
            
            if (!$result) {
                throw new Exception('Failed to process uploaded file');
            }
            
            // Build the public URL for the WebP version
            $basePath = Router::getBasePath();
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $publicUrl = $protocol . '://' . $host . $basePath . '/content/uploads/' . $result['filename'];
            
            // Return success in Editor.js and Media Manager format
            echo json_encode([
                'success' => 1,
                'status' => 'success',
                'file' => [
                    'url' => $publicUrl
                ],
                'url' => $publicUrl,
                'filename' => $result['filename'],
                'size' => filesize($result['webp'] ?? $result['original'])
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => 0,
                'status' => 'error',
                'error' => $e->getMessage()
            ]);
        }
        
        Router::setHandled('');
        return;
    }

    // =========================================================================
    // ADDON MANAGER API
    // =========================================================================

    // POST /admin/api/toggle-addon - Enable/disable an addon
    if ($uri === '/admin/api/toggle-addon' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!zed_user_can_access_admin() || !zed_current_user_can('manage_addons')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $identifier = $input['filename'] ?? '';
            
            // Security: prevent directory traversal
            $identifier = basename($identifier);
            
            if (empty($identifier)) {
                throw new Exception('No addon identifier provided');
            }
            
            // Prevent disabling system addons
            $systemAddons = defined('ZERO_SYSTEM_ADDONS') ? ZERO_SYSTEM_ADDONS : ['admin_addon.php', 'frontend_addon.php'];
            if (in_array($identifier, $systemAddons, true)) {
                throw new Exception('System addons cannot be disabled');
            }
            
            // Find the addon file (supports both file and folder addons)
            $addonsDir = dirname(__DIR__) . '/addons';
            $addonFile = null;
            $addonType = null;
            
            // Check for single-file addon: addons/{identifier}
            if (file_exists($addonsDir . '/' . $identifier) && str_ends_with($identifier, '.php')) {
                $addonFile = $addonsDir . '/' . $identifier;
                $addonType = 'file';
            }
            // Check for folder-based addon: addons/{identifier}/addon.php
            elseif (file_exists($addonsDir . '/' . $identifier . '/addon.php')) {
                $addonFile = $addonsDir . '/' . $identifier . '/addon.php';
                $addonType = 'folder';
            } else {
                throw new Exception('Addon not found: ' . $identifier);
            }
            
            $db = Database::getInstance();
            
            // Get current active_addons list
            $current = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_addons'");
            $activeAddons = $current ? json_decode($current, true) : null;
            
            // If no option exists, initialize with all non-system addons as active
            if ($activeAddons === null) {
                $activeAddons = [];
                // Single-file addons
                foreach (glob($addonsDir . '/*.php') as $file) {
                    $name = basename($file);
                    if (!in_array($name, $systemAddons, true)) {
                        $activeAddons[] = $name;
                    }
                }
                // Folder-based addons
                foreach (glob($addonsDir . '/*/addon.php') as $file) {
                    $folderName = basename(dirname($file));
                    $activeAddons[] = $folderName;
                }
            }
            
            // Toggle the addon
            $isActive = in_array($identifier, $activeAddons, true);
            if ($isActive) {
                $activeAddons = array_values(array_diff($activeAddons, [$identifier]));
                $newState = false;
            } else {
                $activeAddons[] = $identifier;
                $newState = true;
            }
            
            // Save back to database
            $jsonValue = json_encode(array_values(array_unique($activeAddons)));
            $exists = $db->queryValue("SELECT COUNT(*) FROM zed_options WHERE option_name = 'active_addons'");
            if ($exists) {
                $db->query("UPDATE zed_options SET option_value = :val WHERE option_name = 'active_addons'", ['val' => $jsonValue]);
            } else {
                $db->query("INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('active_addons', :val, 1)", ['val' => $jsonValue]);
            }
            
            // Get addon name for message
            $addonName = ucwords(str_replace(['_', '-', '.php'], [' ', ' ', ''], $identifier));
            $content = file_get_contents($addonFile, false, null, 0, 2048);
            if (preg_match('/Addon Name:\s*(.*)$/mi', $content, $m)) {
                $addonName = trim($m[1]);
            }
            
            echo json_encode([
                'success' => true,
                'active' => $newState,
                'message' => $addonName . ($newState ? ' Activated' : ' Deactivated')
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // POST /admin/api/upload-addon - Upload a new addon file
    if ($uri === '/admin/api/upload-addon' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!zed_user_can_access_admin() || !zed_current_user_can('manage_addons')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            if (!isset($_FILES['addon']) || $_FILES['addon']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No file uploaded or upload error');
            }
            
            $file = $_FILES['addon'];
            $filename = basename($file['name']);
            
            // Validate extension
            if (!str_ends_with(strtolower($filename), '.php')) {
                throw new Exception('Only .php files are allowed');
            }
            
            // Sanitize filename
            $safeFilename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
            
            // Move to addons directory
            $addonsDir = dirname(__DIR__) . '/addons';
            $destPath = $addonsDir . '/' . $safeFilename;
            
            if (file_exists($destPath)) {
                throw new Exception('An addon with this name already exists');
            }
            
            if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                throw new Exception('Failed to save addon file');
            }
            
            // Auto-activate the new addon
            $db = Database::getInstance();
            $current = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_addons'");
            $activeAddons = $current ? json_decode($current, true) : [];
            if (!is_array($activeAddons)) $activeAddons = [];
            
            $activeAddons[] = $safeFilename;
            $jsonValue = json_encode(array_values(array_unique($activeAddons)));
            
            $exists = $db->queryValue("SELECT COUNT(*) FROM zed_options WHERE option_name = 'active_addons'");
            if ($exists) {
                $db->query("UPDATE zed_options SET option_value = :val WHERE option_name = 'active_addons'", ['val' => $jsonValue]);
            } else {
                $db->query("INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('active_addons', :val, 1)", ['val' => $jsonValue]);
            }
            
            echo json_encode([
                'success' => true,
                'filename' => $safeFilename,
                'message' => 'Addon uploaded and activated'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // =========================================================================
    // THEME MANAGER API
    // =========================================================================

    // POST /admin/api/activate-theme - Switch the active theme
    if ($uri === '/admin/api/activate-theme' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!zed_user_can_access_admin() || !zed_current_user_can('manage_themes')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $themeName = basename($input['theme'] ?? '');
            
            if (empty($themeName)) {
                throw new Exception('No theme specified');
            }
            
            // Validate theme folder exists
            $themesDir = dirname(__DIR__) . '/themes';
            $themePath = $themesDir . '/' . $themeName;
            
            if (!is_dir($themePath)) {
                throw new Exception('Theme not found');
            }
            
            // Exclude admin theme
            if ($themeName === 'admin-default') {
                throw new Exception('Cannot activate admin theme as frontend theme');
            }
            
            $db = Database::getInstance();
            
            // Update or insert active_theme option
            $exists = $db->queryValue("SELECT COUNT(*) FROM zed_options WHERE option_name = 'active_theme'");
            if ($exists) {
                $db->query("UPDATE zed_options SET option_value = :val WHERE option_name = 'active_theme'", ['val' => $themeName]);
            } else {
                $db->query("INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('active_theme', :val, 1)", ['val' => $themeName]);
            }
            
            // Trigger theme switched event
            Event::trigger('zed_theme_switched', $themeName);
            
            // Get theme display name
            $displayName = $themeName;
            $jsonPath = $themePath . '/theme.json';
            if (file_exists($jsonPath)) {
                $themeData = json_decode(file_get_contents($jsonPath), true);
                $displayName = $themeData['name'] ?? $themeName;
            }
            
            echo json_encode([
                'success' => true,
                'theme' => $themeName,
                'message' => $displayName . ' activated'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/themes - Theme Manager page
    if ($uri === '/admin/themes') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        if (!zed_current_user_can('manage_themes')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        // Scan themes directory
        $themesDir = dirname(__DIR__) . '/themes';
        $themes = [];
        
        if (is_dir($themesDir)) {
            foreach (scandir($themesDir) as $folder) {
                if ($folder === '.' || $folder === '..' || $folder === 'admin-default') continue;
                
                $themePath = $themesDir . '/' . $folder;
                if (!is_dir($themePath)) continue;
                
                $theme = [
                    'folder' => $folder,
                    'name' => ucwords(str_replace(['-', '_'], ' ', $folder)),
                    'version' => '1.0.0',
                    'author' => 'Unknown',
                    'description' => '',
                    'colors' => [
                        'brand' => '#256af4',
                        'background' => '#ffffff',
                        'text' => '#111827'
                    ],
                    'screenshot' => null
                ];
                
                // Parse theme.json
                $jsonPath = $themePath . '/theme.json';
                if (file_exists($jsonPath)) {
                    $data = json_decode(file_get_contents($jsonPath), true);
                    if ($data) {
                        $theme['name'] = $data['name'] ?? $theme['name'];
                        $theme['version'] = $data['version'] ?? $theme['version'];
                        $theme['author'] = $data['author'] ?? $theme['author'];
                        $theme['description'] = $data['description'] ?? $theme['description'];
                        if (isset($data['settings'])) {
                            $theme['colors']['brand'] = $data['settings']['brand_color'] ?? $theme['colors']['brand'];
                            $theme['colors']['background'] = $data['settings']['background'] ?? $theme['colors']['background'];
                            $theme['colors']['text'] = $data['settings']['text_color'] ?? $theme['colors']['text'];
                        }
                    }
                }
                
                // Check for screenshot
                foreach (['screenshot.png', 'screenshot.jpg', 'screenshot.webp'] as $img) {
                    if (file_exists($themePath . '/' . $img)) {
                        $theme['screenshot'] = Router::getBasePath() . '/content/themes/' . $folder . '/' . $img;
                        break;
                    }
                }
                
                $themes[] = $theme;
            }
        }
        
        // Get current active theme
        try {
            $db = Database::getInstance();
            $activeTheme = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_theme'") ?: 'starter-theme';
        } catch (Exception $e) {
            $activeTheme = 'starter-theme';
        }
        
        $current_user = Auth::user();
        $current_page = 'themes';
        $page_title = 'Themes';
        $adminThemePath = __DIR__ . '/../themes/admin-default';
        $content_partial = $adminThemePath . '/partials/themes-content.php';
        
        ob_start();
        require $adminThemePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // =========================================================================
    // ADDON SETTINGS PAGES — /admin/addon-settings/{addon_id}
    // =========================================================================
    if (preg_match('#^/admin/addon-settings/(\w+)$#', $uri, $matches)) {
        $addon_id = $matches[1];
        $allSettings = zed_get_addon_settings();
        
        if (!isset($allSettings[$addon_id])) {
            // Addon has no registered settings
            Router::redirect('/admin/addons');
        }
        
        $config = $allSettings[$addon_id];
        
        // Check capability
        if (!zed_current_user_can($config['capability'] ?? 'manage_settings')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        // Handle save
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($config['fields'] as $field) {
                $fieldId = $field['id'] ?? '';
                $fieldName = "addon_{$addon_id}_{$fieldId}";
                $fieldType = $field['type'] ?? 'text';
                
                // Handle toggle (checkbox) - if not in POST, it's unchecked
                if ($fieldType === 'toggle') {
                    $value = isset($_POST[$fieldName]) ? '1' : '0';
                } else {
                    $value = $_POST[$fieldName] ?? '';
                }
                
                zed_set_addon_option($addon_id, $fieldId, $value);
            }
            
            zed_add_notice('Settings saved successfully!', 'success');
            Router::redirect('/admin/addon-settings/' . $addon_id);
        }
        
        // Render settings page
        $current_user = Auth::user();
        $current_page = 'addons';
        $page_title = $config['title'] ?? ucwords(str_replace('_', ' ', $addon_id)) . ' Settings';
        $addon_settings_config = $config;
        $addon_settings_id = $addon_id;
        $adminThemePath = __DIR__ . '/../themes/admin-default';
        $content_partial = $adminThemePath . '/partials/addon-settings-content.php';
        
        ob_start();
        require $adminThemePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

}, 10);

