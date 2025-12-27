<?php
/**
 * Header Builder Addon for ZedCMS
 * 
 * Addon Name: Header Builder
 * Description: Visual drag-and-drop header builder with desktop, mobile, and sticky header support
 * Version: 1.0.0
 * Author: ZedCMS
 * Requires: aurora-pro
 * 
 * @package ZedCMS\Addons\HeaderBuilder
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;
use Core\Database;

// Prevent direct access
if (!defined('ZED_VERSION')) {
    exit('Direct access not allowed.');
}

// Define addon constants
define('ZED_HEADER_BUILDER_PATH', __DIR__ . '/header-builder/');
define('ZED_HEADER_BUILDER_URL', Router::getBasePath() . '/content/addons/header-builder/');

// Include renderer
require_once ZED_HEADER_BUILDER_PATH . 'renderer.php';

/**
 * Header Builder Elements Registry
 */
function zed_header_builder_elements(): array {
    return [
        // Branding
        'logo' => [
            'label' => 'Logo',
            'icon' => 'image',
            'category' => 'branding',
            'devices' => ['desktop', 'mobile'],
        ],
        'logo_sticky' => [
            'label' => 'Sticky Logo',
            'icon' => 'image',
            'category' => 'branding',
            'devices' => ['desktop'],
        ],
        
        // Navigation
        'menu_primary' => [
            'label' => 'Primary Menu',
            'icon' => 'menu',
            'category' => 'navigation',
            'devices' => ['desktop'],
        ],
        'menu_secondary' => [
            'label' => 'Secondary Menu',
            'icon' => 'menu',
            'category' => 'navigation',
            'devices' => ['desktop'],
        ],
        'hamburger' => [
            'label' => 'Hamburger Menu',
            'icon' => 'menu',
            'category' => 'navigation',
            'devices' => ['desktop', 'mobile'],
        ],
        'mobile_menu_toggle' => [
            'label' => 'Mobile Menu Toggle',
            'icon' => 'menu',
            'category' => 'navigation',
            'devices' => ['mobile'],
        ],
        
        // Search
        'search_icon' => [
            'label' => 'Search Icon',
            'icon' => 'search',
            'category' => 'search',
            'devices' => ['desktop', 'mobile'],
        ],
        'search_form' => [
            'label' => 'Search Form',
            'icon' => 'search',
            'category' => 'search',
            'devices' => ['desktop', 'mobile'],
        ],
        
        // Social
        'social_icons' => [
            'label' => 'Social Icons',
            'icon' => 'share',
            'category' => 'social',
            'devices' => ['desktop', 'mobile'],
        ],
        
        // Buttons
        'button_1' => [
            'label' => 'Button 1',
            'icon' => 'smart_button',
            'category' => 'buttons',
            'devices' => ['desktop', 'mobile'],
        ],
        'button_2' => [
            'label' => 'Button 2',
            'icon' => 'smart_button',
            'category' => 'buttons',
            'devices' => ['desktop', 'mobile'],
        ],
        
        // Content
        'html_1' => [
            'label' => 'Custom HTML 1',
            'icon' => 'code',
            'category' => 'content',
            'devices' => ['desktop', 'mobile'],
        ],
        'html_2' => [
            'label' => 'Custom HTML 2',
            'icon' => 'code',
            'category' => 'content',
            'devices' => ['desktop', 'mobile'],
        ],
        
        // Utility
        'datetime' => [
            'label' => 'Date & Time',
            'icon' => 'schedule',
            'category' => 'utility',
            'devices' => ['desktop', 'mobile'],
        ],
        'darkmode' => [
            'label' => 'Dark Mode Toggle',
            'icon' => 'dark_mode',
            'category' => 'utility',
            'devices' => ['desktop', 'mobile'],
        ],
        'login' => [
            'label' => 'Login/Register',
            'icon' => 'person',
            'category' => 'utility',
            'devices' => ['desktop', 'mobile'],
        ],
        
        // Decorative
        'divider_1' => [
            'label' => 'Divider 1',
            'icon' => 'horizontal_rule',
            'category' => 'decorative',
            'devices' => ['desktop', 'mobile'],
        ],
        'divider_2' => [
            'label' => 'Divider 2',
            'icon' => 'horizontal_rule',
            'category' => 'decorative',
            'devices' => ['desktop', 'mobile'],
        ],
    ];
}

/**
 * Get header builder rows configuration
 */
function zed_header_builder_rows(): array {
    return [
        'desktop' => [
            'topblock' => ['label' => 'Top Block', 'columns' => ['left', 'center', 'right']],
            'top' => ['label' => 'Top Bar', 'columns' => ['left', 'center', 'right']],
            'mid' => ['label' => 'Middle Bar', 'columns' => ['left', 'center', 'right']],
            'bottom' => ['label' => 'Bottom Bar', 'columns' => ['left', 'center', 'right']],
            'bottomblock' => ['label' => 'Bottom Block', 'columns' => ['left', 'center', 'right']],
        ],
        'desktop_sticky' => [
            'top' => ['label' => 'Sticky Top', 'columns' => ['left', 'center', 'right']],
            'mid' => ['label' => 'Sticky Middle', 'columns' => ['left', 'center', 'right']],
            'bottom' => ['label' => 'Sticky Bottom', 'columns' => ['left', 'center', 'right']],
        ],
        'mobile' => [
            'top' => ['label' => 'Mobile Top', 'columns' => ['left', 'center', 'right']],
            'mid' => ['label' => 'Mobile Middle', 'columns' => ['left', 'center', 'right']],
            'bottom' => ['label' => 'Mobile Bottom', 'columns' => ['left', 'center', 'right']],
        ],
        'mobile_drawer' => [
            'top' => ['label' => 'Drawer Top', 'columns' => ['center']],
            'content' => ['label' => 'Drawer Content', 'columns' => ['center']],
            'bottom' => ['label' => 'Drawer Bottom', 'columns' => ['center']],
        ],
    ];
}

/**
 * Get saved header configuration
 */
function zed_get_header_config(string $device = 'desktop'): array {
    $key = "header_builder_{$device}";
    $config = zed_get_option($key, '[]');
    return is_string($config) ? json_decode($config, true) ?? [] : $config;
}

/**
 * Save header configuration
 */
function zed_save_header_config(string $device, array $config): bool {
    $key = "header_builder_{$device}";
    return zed_set_option($key, json_encode($config));
}

/**
 * Get element settings
 */
function zed_get_header_element_settings(): array {
    $settings = zed_get_option('header_builder_element_settings', '{}');
    return is_string($settings) ? json_decode($settings, true) ?? [] : $settings;
}

/**
 * Register admin menu
 */
Event::on('zed_admin_menu', function($menu) {
    $menu[] = [
        'id' => 'header-builder',
        'label' => 'Header Builder',
        'icon' => 'view_quilt',
        'url' => \Core\Router::getBasePath() . '/admin/header-builder',
        'position' => 87,
        'capability' => 'manage_settings',
    ];
    return $menu;
});

/**
 * Register admin route
 */
zed_register_route([
    'path' => '/admin/header-builder',
    'method' => 'GET',
    'capability' => 'manage_settings',
    'callback' => function($request, $uri, $params) {
        require ZED_HEADER_BUILDER_PATH . 'admin.php';
    },
    'wrap_layout' => false, // admin.php has its own layout
]);

/**
 * Register API endpoints
 */

// Save header builder config
zed_register_route([
    'path' => '/admin/api/header-builder/save',
    'method' => 'POST',
    'capability' => 'manage_settings',
    'wrap_layout' => false,
    'callback' => function($request, $uri, $params) {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $device = $input['device'] ?? 'desktop';
        $config = $input['config'] ?? [];
        
        if (zed_save_header_config($device, $config)) {
            echo json_encode(['success' => true, 'message' => 'Header saved']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to save']);
        }
    },
]);

// Save element settings
zed_register_route([
    'path' => '/admin/api/header-builder/save-elements',
    'method' => 'POST',
    'capability' => 'manage_settings',
    'wrap_layout' => false,
    'callback' => function($request, $uri, $params) {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $settings = $input['settings'] ?? [];
        
        if (zed_set_option('header_builder_element_settings', json_encode($settings))) {
            echo json_encode(['success' => true, 'message' => 'Element settings saved']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to save']);
        }
    },
]);

// Load header builder config
zed_register_route([
    'path' => '/admin/api/header-builder/load',
    'method' => 'GET',
    'capability' => 'manage_settings',
    'wrap_layout' => false,
    'callback' => function($request, $uri, $params) {
        header('Content-Type: application/json');
        
        $device = $_GET['device'] ?? 'all';
        
        if ($device === 'all') {
            echo json_encode([
                'success' => true,
                'data' => [
                    'desktop' => zed_get_header_config('desktop'),
                    'desktop_sticky' => zed_get_header_config('desktop_sticky'),
                    'mobile' => zed_get_header_config('mobile'),
                    'mobile_drawer' => zed_get_header_config('mobile_drawer'),
                    'element_settings' => zed_get_header_element_settings(),
                ],
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => zed_get_header_config($device),
            ]);
        }
    },
]);

/**
 * Render header from builder config
 */
function zed_render_builder_header(string $device = 'desktop'): string {
    $config = zed_get_header_config($device);
    $rows = zed_header_builder_rows()[$device] ?? [];
    $elementSettings = zed_get_header_element_settings();
    
    if (empty($config)) {
        return '';
    }
    
    ob_start();
    
    foreach ($rows as $rowId => $rowConfig) {
        $rowElements = $config[$rowId] ?? [];
        $hasContent = false;
        
        // Check if row has any content
        foreach ($rowConfig['columns'] as $col) {
            if (!empty($rowElements[$col])) {
                $hasContent = true;
                break;
            }
        }
        
        if (!$hasContent) continue;
        
        echo '<div class="hb-row hb-row-' . esc_attr($rowId) . '" data-row="' . esc_attr($rowId) . '">';
        echo '<div class="hb-row-inner container mx-auto flex items-center">';
        
        foreach ($rowConfig['columns'] as $col) {
            $colElements = $rowElements[$col] ?? [];
            $align = $col === 'left' ? 'justify-start' : ($col === 'right' ? 'justify-end' : 'justify-center');
            
            echo '<div class="hb-column hb-column-' . esc_attr($col) . ' flex items-center gap-4 ' . $align . '" data-column="' . esc_attr($col) . '">';
            
            foreach ($colElements as $elementId) {
                $settings = $elementSettings[$elementId] ?? [];
                zed_render_header_element($elementId, $settings);
            }
            
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    return ob_get_clean();
}

/**
 * Render a single header element
 */
function zed_render_header_element(string $elementId, array $settings = []): void {
    $elements = zed_header_builder_elements();
    $elementType = preg_replace('/_\d+$/', '', $elementId); // Remove numbered suffix
    
    if (!isset($elements[$elementType])) {
        $elementType = $elementId;
    }
    
    $elementFile = ZED_HEADER_BUILDER_PATH . "elements/{$elementType}.php";
    
    if (file_exists($elementFile)) {
        include $elementFile;
    } else {
        // Fallback for missing element
        echo '<div class="hb-element hb-element-' . esc_attr($elementId) . '">';
        echo esc_html($elements[$elementType]['label'] ?? $elementId);
        echo '</div>';
    }
}

// Helper function for escaping
if (!function_exists('esc_attr')) {
    function esc_attr(string $text): string {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}
