<?php
/**
 * Zed CMS Widgets & Sidebars System
 * 
 * WordPress-compatible widget API for theme sidebars.
 * 
 * Functions:
 *   Sidebars:
 *   - zed_register_sidebar($id, $args)    - Register a sidebar/widget area
 *   - zed_get_sidebars()                  - Get all registered sidebars
 *   - zed_dynamic_sidebar($id)            - Render a sidebar with widgets
 *   - zed_is_active_sidebar($id)          - Check if sidebar has widgets
 * 
 *   Widgets:
 *   - zed_register_widget($id, $args)     - Register a widget type
 *   - zed_get_widgets()                   - Get all registered widgets
 *   - zed_get_sidebar_widgets($sidebar)   - Get widgets assigned to sidebar
 *   - zed_save_sidebar_widgets($sidebar, $widgets) - Save widget assignment
 * 
 * @package Zed CMS
 * @since 3.2.0
 */

declare(strict_types=1);

use Core\Database;
use Core\Event;

// ============================================================================
// GLOBAL REGISTRIES
// ============================================================================

global $ZED_SIDEBARS, $ZED_WIDGETS;
$ZED_SIDEBARS = [];
$ZED_WIDGETS = [];

// ============================================================================
// SIDEBAR REGISTRATION
// ============================================================================

/**
 * Register a sidebar (widget area)
 * 
 * @param string $id Unique sidebar identifier
 * @param array $args Options:
 *   - name: string Display name
 *   - description: string Description
 *   - before_widget: string HTML before each widget
 *   - after_widget: string HTML after each widget
 *   - before_title: string HTML before widget title
 *   - after_title: string HTML after widget title
 * @return void
 * 
 * @example
 * zed_register_sidebar('main-sidebar', [
 *     'name' => 'Main Sidebar',
 *     'description' => 'Appears on blog pages',
 *     'before_widget' => '<div class="widget %2$s">',
 *     'after_widget' => '</div>',
 *     'before_title' => '<h4 class="widget-title">',
 *     'after_title' => '</h4>',
 * ]);
 */
function zed_register_sidebar(string $id, array $args = []): void
{
    global $ZED_SIDEBARS;
    
    $defaults = [
        'name' => ucwords(str_replace(['-', '_'], ' ', $id)),
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ];
    
    $ZED_SIDEBARS[$id] = array_merge($defaults, $args);
}

/**
 * Get all registered sidebars
 * 
 * @return array
 */
function zed_get_sidebars(): array
{
    global $ZED_SIDEBARS;
    return $ZED_SIDEBARS;
}

/**
 * Check if a sidebar exists
 * 
 * @param string $id Sidebar ID
 * @return bool
 */
function zed_sidebar_exists(string $id): bool
{
    global $ZED_SIDEBARS;
    return isset($ZED_SIDEBARS[$id]);
}

/**
 * Check if a sidebar has active widgets
 * 
 * @param string $id Sidebar ID
 * @return bool
 */
function zed_is_active_sidebar(string $id): bool
{
    $widgets = zed_get_sidebar_widgets($id);
    return !empty($widgets);
}

/**
 * Render a sidebar with its widgets
 * 
 * @param string $id Sidebar ID
 * @return bool True if sidebar rendered, false if not found or empty
 */
function zed_dynamic_sidebar(string $id): bool
{
    global $ZED_SIDEBARS, $ZED_WIDGETS;
    
    if (!isset($ZED_SIDEBARS[$id])) {
        return false;
    }
    
    $sidebar = $ZED_SIDEBARS[$id];
    $widgets = zed_get_sidebar_widgets($id);
    
    if (empty($widgets)) {
        return false;
    }
    
    foreach ($widgets as $index => $widget) {
        $widget_type = $widget['type'] ?? '';
        $instance = $widget['instance'] ?? [];
        
        if (!isset($ZED_WIDGETS[$widget_type])) {
            continue;
        }
        
        $widget_config = $ZED_WIDGETS[$widget_type];
        $widget_id = $id . '-widget-' . $index;
        $widget_class = 'widget-' . $widget_type;
        
        // Before widget
        $before = sprintf($sidebar['before_widget'], $widget_id, $widget_class);
        echo $before;
        
        // Widget title
        $title = $instance['title'] ?? $widget_config['name'] ?? '';
        if (!empty($title)) {
            echo $sidebar['before_title'] . esc_html($title) . $sidebar['after_title'];
        }
        
        // Widget content via callback
        if (is_callable($widget_config['callback'])) {
            call_user_func($widget_config['callback'], $sidebar, $instance);
        }
        
        // After widget
        echo $sidebar['after_widget'];
    }
    
    return true;
}

// ============================================================================
// WIDGET REGISTRATION
// ============================================================================

/**
 * Register a widget type
 * 
 * @param string $id Unique widget identifier
 * @param array $args Options:
 *   - name: string Display name
 *   - description: string Description
 *   - callback: callable Function to render widget content
 *   - fields: array Form fields for widget settings
 * @return void
 * 
 * @example
 * zed_register_widget('recent-posts', [
 *     'name' => 'Recent Posts',
 *     'description' => 'Shows latest posts',
 *     'callback' => function($sidebar, $instance) {
 *         $posts = zed_get_posts(['limit' => $instance['count'] ?? 5]);
 *         foreach ($posts as $post) {
 *             echo '<li><a href="' . zed_get_permalink($post) . '">' . esc_html($post['title']) . '</a></li>';
 *         }
 *     },
 *     'fields' => [
 *         'title' => ['type' => 'text', 'label' => 'Title', 'default' => 'Recent Posts'],
 *         'count' => ['type' => 'number', 'label' => 'Number of posts', 'default' => 5],
 *     ],
 * ]);
 */
function zed_register_widget(string $id, array $args = []): void
{
    global $ZED_WIDGETS;
    
    $defaults = [
        'name' => ucwords(str_replace(['-', '_'], ' ', $id)),
        'description' => '',
        'callback' => function() {},
        'fields' => [],
        'icon' => 'widgets',
    ];
    
    $ZED_WIDGETS[$id] = array_merge($defaults, $args);
}

/**
 * Get all registered widgets
 * 
 * @return array
 */
function zed_get_widgets(): array
{
    global $ZED_WIDGETS;
    return $ZED_WIDGETS;
}

// ============================================================================
// WIDGET ASSIGNMENT (Database Storage)
// ============================================================================

/**
 * Get widgets assigned to a sidebar
 * 
 * @param string $sidebar_id Sidebar ID
 * @return array Array of widget instances
 */
function zed_get_sidebar_widgets(string $sidebar_id): array
{
    $all_widgets = zed_get_option('sidebar_widgets', []);
    
    if (is_string($all_widgets)) {
        $all_widgets = json_decode($all_widgets, true) ?: [];
    }
    
    return $all_widgets[$sidebar_id] ?? [];
}

/**
 * Save widgets for a sidebar
 * 
 * @param string $sidebar_id Sidebar ID
 * @param array $widgets Array of widget instances
 * @return bool Success
 */
function zed_save_sidebar_widgets(string $sidebar_id, array $widgets): bool
{
    $all_widgets = zed_get_option('sidebar_widgets', []);
    
    if (is_string($all_widgets)) {
        $all_widgets = json_decode($all_widgets, true) ?: [];
    }
    
    $all_widgets[$sidebar_id] = $widgets;
    
    return zed_set_option('sidebar_widgets', json_encode($all_widgets));
}

/**
 * Get all sidebar widget assignments
 * 
 * @return array
 */
function zed_get_all_sidebar_widgets(): array
{
    $all_widgets = zed_get_option('sidebar_widgets', []);
    
    if (is_string($all_widgets)) {
        $all_widgets = json_decode($all_widgets, true) ?: [];
    }
    
    return $all_widgets;
}

/**
 * Save all sidebar widget assignments
 * 
 * @param array $data All sidebar widgets
 * @return bool Success
 */
function zed_save_all_sidebar_widgets(array $data): bool
{
    return zed_set_option('sidebar_widgets', json_encode($data));
}

// ============================================================================
// BUILT-IN WIDGETS
// ============================================================================

/**
 * Register built-in widgets
 * Called during system init
 */
function zed_register_builtin_widgets(): void
{
    // Recent Posts Widget
    zed_register_widget('recent-posts', [
        'name' => 'Recent Posts',
        'description' => 'Display a list of recent posts',
        'icon' => 'article',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'Title', 'default' => 'Recent Posts'],
            'count' => ['type' => 'number', 'label' => 'Number of posts', 'default' => 5, 'min' => 1, 'max' => 20],
            'show_date' => ['type' => 'checkbox', 'label' => 'Show date', 'default' => true],
        ],
        'callback' => function($sidebar, $instance) {
            $count = (int)($instance['count'] ?? 5);
            $show_date = $instance['show_date'] ?? true;
            $posts = function_exists('zed_get_posts') 
                ? zed_get_posts(['limit' => $count, 'status' => 'published']) 
                : [];
            
            if (empty($posts)) {
                echo '<p class="no-posts">No posts found.</p>';
                return;
            }
            
            echo '<ul class="widget-recent-posts">';
            foreach ($posts as $post) {
                $url = function_exists('zed_get_permalink') ? zed_get_permalink($post) : '#';
                echo '<li>';
                echo '<a href="' . esc_attr($url) . '">' . esc_html($post['title']) . '</a>';
                if ($show_date) {
                    echo '<span class="post-date">' . date('M j, Y', strtotime($post['published_at'] ?? $post['created_at'])) . '</span>';
                }
                echo '</li>';
            }
            echo '</ul>';
        },
    ]);
    
    // Categories Widget
    zed_register_widget('categories', [
        'name' => 'Categories',
        'description' => 'Display a list of categories',
        'icon' => 'category',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'Title', 'default' => 'Categories'],
            'show_count' => ['type' => 'checkbox', 'label' => 'Show post count', 'default' => true],
            'hide_empty' => ['type' => 'checkbox', 'label' => 'Hide empty categories', 'default' => true],
        ],
        'callback' => function($sidebar, $instance) {
            $show_count = $instance['show_count'] ?? true;
            $hide_empty = $instance['hide_empty'] ?? true;
            
            $categories = function_exists('zed_get_categories') 
                ? zed_get_categories(['hide_empty' => $hide_empty]) 
                : [];
            
            if (empty($categories)) {
                echo '<p class="no-categories">No categories found.</p>';
                return;
            }
            
            echo '<ul class="widget-categories">';
            foreach ($categories as $cat) {
                $url = function_exists('zed_get_category_url') ? zed_get_category_url($cat) : '#';
                echo '<li>';
                echo '<a href="' . esc_attr($url) . '">' . esc_html($cat['name']);
                if ($show_count && isset($cat['post_count'])) {
                    echo ' <span class="count">(' . (int)$cat['post_count'] . ')</span>';
                }
                echo '</a></li>';
            }
            echo '</ul>';
        },
    ]);
    
    // Tags Widget (Tag Cloud)
    zed_register_widget('tags', [
        'name' => 'Tags',
        'description' => 'Display a tag cloud',
        'icon' => 'sell',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'Title', 'default' => 'Tags'],
            'max_tags' => ['type' => 'number', 'label' => 'Maximum tags', 'default' => 20, 'min' => 5, 'max' => 50],
        ],
        'callback' => function($sidebar, $instance) {
            $max = (int)($instance['max_tags'] ?? 20);
            
            $tags = function_exists('zed_get_tags') 
                ? zed_get_tags(['limit' => $max]) 
                : [];
            
            if (empty($tags)) {
                echo '<p class="no-tags">No tags found.</p>';
                return;
            }
            
            echo '<div class="widget-tag-cloud">';
            foreach ($tags as $tag) {
                $url = function_exists('zed_get_tag_url') ? zed_get_tag_url($tag) : '#';
                echo '<a href="' . esc_attr($url) . '" class="tag">' . esc_html($tag['name']) . '</a> ';
            }
            echo '</div>';
        },
    ]);
    
    // Search Widget
    zed_register_widget('search', [
        'name' => 'Search',
        'description' => 'A search form for your site',
        'icon' => 'search',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'Title', 'default' => ''],
            'placeholder' => ['type' => 'text', 'label' => 'Placeholder text', 'default' => 'Search...'],
        ],
        'callback' => function($sidebar, $instance) {
            $placeholder = $instance['placeholder'] ?? 'Search...';
            $base_url = \Core\Router::getBasePath();
            
            echo '<form class="widget-search-form" action="' . $base_url . '/search" method="get">';
            echo '<input type="search" name="q" placeholder="' . esc_attr($placeholder) . '" class="search-input">';
            echo '<button type="submit" class="search-btn"><span class="material-symbols-outlined">search</span></button>';
            echo '</form>';
        },
    ]);
    
    // Custom HTML Widget
    zed_register_widget('custom-html', [
        'name' => 'Custom HTML',
        'description' => 'Add custom HTML code',
        'icon' => 'code',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'Title', 'default' => ''],
            'content' => ['type' => 'textarea', 'label' => 'HTML Content', 'default' => '', 'rows' => 8],
        ],
        'callback' => function($sidebar, $instance) {
            $content = $instance['content'] ?? '';
            // Allow HTML but sanitize dangerous tags
            $allowed = '<p><br><a><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><div><span><img><iframe>';
            echo strip_tags($content, $allowed);
        },
    ]);
    
    // Social Links Widget
    zed_register_widget('social-links', [
        'name' => 'Social Links',
        'description' => 'Display social media links',
        'icon' => 'share',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'Title', 'default' => 'Follow Us'],
            'facebook' => ['type' => 'url', 'label' => 'Facebook URL', 'default' => ''],
            'twitter' => ['type' => 'url', 'label' => 'Twitter/X URL', 'default' => ''],
            'instagram' => ['type' => 'url', 'label' => 'Instagram URL', 'default' => ''],
            'youtube' => ['type' => 'url', 'label' => 'YouTube URL', 'default' => ''],
            'linkedin' => ['type' => 'url', 'label' => 'LinkedIn URL', 'default' => ''],
        ],
        'callback' => function($sidebar, $instance) {
            $links = [
                'facebook' => ['icon' => '&#xf09a;', 'label' => 'Facebook'],
                'twitter' => ['icon' => '&#xf099;', 'label' => 'Twitter'],
                'instagram' => ['icon' => '&#xf16d;', 'label' => 'Instagram'],
                'youtube' => ['icon' => '&#xf167;', 'label' => 'YouTube'],
                'linkedin' => ['icon' => '&#xf0e1;', 'label' => 'LinkedIn'],
            ];
            
            echo '<div class="widget-social-links">';
            foreach ($links as $key => $data) {
                $url = $instance[$key] ?? '';
                if (!empty($url)) {
                    echo '<a href="' . esc_attr($url) . '" target="_blank" rel="noopener" title="' . $data['label'] . '" class="social-link social-' . $key . '">';
                    echo '<span class="material-symbols-outlined">' . ($key === 'facebook' ? 'group' : ($key === 'twitter' ? 'tag' : ($key === 'instagram' ? 'photo_camera' : ($key === 'youtube' ? 'play_circle' : 'work')))) . '</span>';
                    echo '</a>';
                }
            }
            echo '</div>';
        },
    ]);
}

// Register built-in widgets on init
Event::on('app_init', function() {
    zed_register_builtin_widgets();
}, 5);

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

/**
 * Escape HTML attribute
 */
function esc_attr(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
