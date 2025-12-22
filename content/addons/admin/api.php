<?php
/**
 * Zed CMS — Addon APIs
 * 
 * Unified APIs for addon development:
 * - AJAX Handler registration
 * - Admin Notices (flash messages)
 * - Addon Settings (auto-generate UI)
 * - Metabox System (custom fields)
 * - Script/Style Enqueue
 * 
 * @package ZedCMS\Admin
 */

declare(strict_types=1);

use Core\Database;

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

// =============================================================================
// ADMIN MENU HELPERS
// =============================================================================

/**
 * Get sidebar menu items based on user capabilities
 */
function zed_get_admin_menu_items(): array
{
    $items = [];
    $base = \Core\Router::getBasePath();
    
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
        
        // Custom Post Types - Dynamic menu items
        global $ZED_POST_TYPES;
        if (!empty($ZED_POST_TYPES)) {
            $sortedTypes = $ZED_POST_TYPES;
            uasort($sortedTypes, fn($a, $b) => ($a['menu_position'] ?? 50) <=> ($b['menu_position'] ?? 50));
            
            $cptPosition = 21;
            foreach ($sortedTypes as $type => $config) {
                if ($config['builtin'] ?? false) continue;
                if (!($config['show_in_menu'] ?? true)) continue;
                
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
    
    // Categories
    if (zed_current_user_can('manage_categories')) {
        $items[] = ['id' => 'categories', 'label' => 'Categories', 'icon' => 'category', 'url' => $base . '/admin/categories', 'position' => 40];
    }
    
    // Media
    if (zed_current_user_can('upload_media')) {
        $items[] = ['id' => 'media', 'label' => 'Media Library', 'icon' => 'perm_media', 'url' => $base . '/admin/media', 'position' => 50];
    }
    
    // Menus
    if (zed_current_user_can('manage_menus')) {
        $items[] = ['id' => 'menus', 'label' => 'Menus', 'icon' => 'menu', 'url' => $base . '/admin/menus', 'position' => 60];
    }
    
    // Users
    if (zed_current_user_can('manage_users')) {
        $items[] = ['id' => 'users', 'label' => 'Users', 'icon' => 'group', 'url' => $base . '/admin/users', 'position' => 70];
    }
    
    // Addons
    if (zed_current_user_can('manage_addons')) {
        $items[] = ['id' => 'addons', 'label' => 'Addons', 'icon' => 'extension', 'url' => $base . '/admin/addons', 'position' => 80];
        
        // Addon Settings submenu (show if any addons have registered settings)
        global $ZED_ADDON_SETTINGS;
        if (!empty($ZED_ADDON_SETTINGS)) {
            $items[] = ['id' => 'addon_settings', 'label' => 'Addon Settings', 'icon' => 'tune', 'url' => $base . '/admin/addon-settings', 'position' => 81];
        }
    }
    
    // Themes
    if (zed_current_user_can('manage_themes')) {
        $items[] = ['id' => 'themes', 'label' => 'Themes', 'icon' => 'palette', 'url' => $base . '/admin/themes', 'position' => 85];
    }
    
    // Wiki
    if (zed_current_user_can('view_dashboard')) {
        $items[] = ['id' => 'wiki', 'label' => 'Knowledge Base', 'icon' => 'library_books', 'url' => $base . '/admin/wiki', 'position' => 90];
    }
    
    // Settings
    if (zed_current_user_can('manage_settings')) {
        $items[] = ['id' => 'settings', 'label' => 'Settings', 'icon' => 'settings', 'url' => $base . '/admin/settings', 'position' => 100];
    }
    
    // Allow addons to register menu items
    $items = \Core\Event::filter('zed_admin_menu', $items);
    
    // Sort by position
    usort($items, fn($a, $b) => ($a['position'] ?? 100) <=> ($b['position'] ?? 100));
    
    return $items;
}
