<?php
/**
 * Custom Post Type (CPT) Engine
 * 
 * Provides post type registration and management for themes.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

// =============================================================================
// CUSTOM POST TYPE (CPT) ENGINE
// =============================================================================

/**
 * Global registry for custom post types
 * Stores: ['type_slug' => ['label' => '...', 'icon' => '...', 'supports' => [...]]]
 */
global $ZED_POST_TYPES;
$ZED_POST_TYPES = [
    // Built-in types (always available)
    'page' => [
        'label' => 'Pages',
        'singular' => 'Page',
        'icon' => 'description',
        'supports' => ['title', 'editor', 'featured_image', 'excerpt'],
        'public' => true,
        'show_in_menu' => true,
        'menu_position' => 10,
        'builtin' => true,
    ],
    'post' => [
        'label' => 'Posts',
        'singular' => 'Post',
        'icon' => 'article',
        'supports' => ['title', 'editor', 'featured_image', 'excerpt', 'categories'],
        'public' => true,
        'show_in_menu' => true,
        'menu_position' => 20,
        'builtin' => true,
    ],
];

/**
 * Register a Custom Post Type
 * 
 * Usage in theme's functions.php:
 *   zed_register_post_type('product', 'Products', 'inventory_2');
 *   zed_register_post_type('event', [
 *       'label' => 'Events',
 *       'singular' => 'Event',
 *       'icon' => 'event',
 *       'supports' => ['title', 'editor', 'featured_image'],
 *       'menu_position' => 30,
 *   ]);
 * 
 * @param string $type Unique type slug (lowercase, no spaces)
 * @param string|array $labelOrArgs Display label string OR full configuration array
 * @param string $icon Material icon name (optional if using array)
 * @return bool Success
 */
function zed_register_post_type(string $type, string|array $labelOrArgs, string $icon = 'folder'): bool
{
    global $ZED_POST_TYPES;
    
    // Sanitize type slug
    $type = strtolower(preg_replace('/[^a-z0-9_]/', '', $type));
    
    if (empty($type) || $type === 'page' || $type === 'post') {
        return false; // Can't override built-ins
    }
    
    // Parse arguments
    if (is_string($labelOrArgs)) {
        $args = [
            'label' => $labelOrArgs,
            'singular' => rtrim($labelOrArgs, 's'),
            'icon' => $icon,
        ];
    } else {
        $args = $labelOrArgs;
    }
    
    // Merge with defaults
    $defaults = [
        'label' => ucfirst($type) . 's',
        'singular' => ucfirst($type),
        'icon' => 'folder',
        'supports' => ['title', 'editor'],
        'public' => true,
        'show_in_menu' => true,
        'menu_position' => 50,
        'builtin' => false,
    ];
    
    $ZED_POST_TYPES[$type] = array_merge($defaults, $args);
    
    return true;
}

/**
 * Get all registered post types
 * 
 * @param bool $includeBuiltin Include page/post types
 * @return array Post types array
 */
function zed_get_post_types(bool $includeBuiltin = true): array
{
    global $ZED_POST_TYPES;
    
    if ($includeBuiltin) {
        return $ZED_POST_TYPES;
    }
    
    return array_filter($ZED_POST_TYPES, fn($pt) => !($pt['builtin'] ?? false));
}

/**
 * Get a single post type's configuration
 * 
 * @param string $type Type slug
 * @return array|null Configuration or null if not found
 */
function zed_get_post_type(string $type): ?array
{
    global $ZED_POST_TYPES;
    return $ZED_POST_TYPES[$type] ?? null;
}
