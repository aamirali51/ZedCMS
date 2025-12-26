<?php
/**
 * Zenith Theme — functions.php
 * 
 * Premium Magazine/Blog Theme for Zed CMS
 * 
 * @package Zenith
 * @version 1.0.0
 * @license MIT
 */

declare(strict_types=1);

use Core\Event;
use Core\Database;

// =============================================================================
// THEME CONSTANTS
// =============================================================================

define('ZENITH_VERSION', '1.0.0');
define('ZENITH_DIR', __DIR__);
define('ZENITH_PARTS', __DIR__ . '/parts');

// =============================================================================
// THEME SETTINGS
// =============================================================================

// Colors (Soledad-inspired palette)
zed_add_theme_setting('accent_color', 'Accent Color', 'color', '#6eb48c');
zed_add_theme_setting('dark_mode_default', 'Default to Dark Mode', 'select', 'no', [
    'no' => 'No (Light)',
    'yes' => 'Yes (Dark)',
]);

// Header
zed_add_theme_setting('header_style', 'Header Style', 'select', 'standard', [
    'standard' => 'Standard (Logo Left)',
    'classic' => 'Classic (Logo Center)',
    'boxed' => 'Boxed (Rounded Container)',
    'transparent' => 'Transparent (Overlay)',
]);
zed_add_theme_setting('header_sticky', 'Sticky Header', 'select', 'yes', [
    'yes' => 'Enabled',
    'no' => 'Disabled',
]);
zed_add_theme_setting('header_search', 'Show Search Icon', 'select', 'yes', [
    'yes' => 'Show',
    'no' => 'Hide',
]);
zed_add_theme_setting('logo', 'Logo URL', 'text', '');
zed_add_theme_setting('logo_light', 'Logo (Light Version)', 'text', '');

// Homepage
zed_add_theme_setting('featured_count', 'Featured Slider Posts', 'select', '5', [
    '3' => '3 Posts',
    '5' => '5 Posts',
    '7' => '7 Posts',
]);
zed_add_theme_setting('posts_per_page', 'Posts Per Page', 'text', '10');

// Single Post
zed_add_theme_setting('show_reading_progress', 'Reading Progress Bar', 'select', 'yes', [
    'yes' => 'Show',
    'no' => 'Hide',
]);
zed_add_theme_setting('show_author_box', 'Author Box', 'select', 'yes', [
    'yes' => 'Show',
    'no' => 'Hide',
]);
zed_add_theme_setting('show_related_posts', 'Related Posts', 'select', 'yes', [
    'yes' => 'Show',
    'no' => 'Hide',
]);
zed_add_theme_setting('show_share_buttons', 'Share Buttons', 'select', 'yes', [
    'yes' => 'Show',
    'no' => 'Hide',
]);

// Footer
zed_add_theme_setting('footer_about', 'Footer About Text', 'text', 'A premium magazine theme for Zed CMS.');
zed_add_theme_setting('social_twitter', 'Twitter URL', 'text', '');
zed_add_theme_setting('social_facebook', 'Facebook URL', 'text', '');
zed_add_theme_setting('social_instagram', 'Instagram URL', 'text', '');
zed_add_theme_setting('social_linkedin', 'LinkedIn URL', 'text', '');

// =============================================================================
// SIDEBARS (v3.2.0)
// =============================================================================

// Register sidebars for widgets
if (function_exists('zed_register_sidebar')) {
    // Main Blog Sidebar
    zed_register_sidebar('main-sidebar', [
        'name' => 'Main Sidebar',
        'description' => 'Appears on blog archive and single posts',
        'before_widget' => '<div id="%1$s" class="widget mb-8 p-6 bg-white dark:bg-zenith-dark-alt rounded-xl shadow-zenith-sm %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title text-lg font-heading font-bold text-zenith-heading dark:text-white mb-4 pb-3 border-b border-zenith-border dark:border-zenith-border-dark">',
        'after_title' => '</h4>',
    ]);
    
    // Footer Widget Area 1
    zed_register_sidebar('footer-1', [
        'name' => 'Footer Column 1',
        'description' => 'First footer widget area',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="widget-title text-white font-heading font-bold text-sm uppercase tracking-wider mb-4">',
        'after_title' => '</h5>',
    ]);
    
    // Footer Widget Area 2
    zed_register_sidebar('footer-2', [
        'name' => 'Footer Column 2',
        'description' => 'Second footer widget area',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="widget-title text-white font-heading font-bold text-sm uppercase tracking-wider mb-4">',
        'after_title' => '</h5>',
    ]);
    
    // Footer Widget Area 3
    zed_register_sidebar('footer-3', [
        'name' => 'Footer Column 3',
        'description' => 'Third footer widget area',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="widget-title text-white font-heading font-bold text-sm uppercase tracking-wider mb-4">',
        'after_title' => '</h5>',
    ]);
}

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================

/**
 * Get a Zenith theme option
 */
function zenith_option(string $key, mixed $default = ''): mixed
{
    return zed_theme_option($key, $default);
}

/**
 * Include a theme part
 */
function zenith_part(string $name, array $args = []): void
{
    $file = ZENITH_PARTS . '/' . $name . '.php';
    if (file_exists($file)) {
        extract($args, EXTR_SKIP);
        include $file;
    }
}

/**
 * Get the accent color
 */
function zenith_accent(): string
{
    return zenith_option('accent_color', '#6eb48c');
}

/**
 * Get featured posts for slider
 */
function zenith_get_featured_posts(int $count = 5): array
{
    return zed_get_posts([
        'type' => 'post',
        'status' => 'published',
        'limit' => $count,
        'orderby' => 'created_at',
        'order' => 'DESC',
    ]);
}

/**
 * Generate Tailwind config script with Soledad design tokens
 */
function zenith_tailwind_config(): string
{
    $accent = zenith_accent();
    return <<<HTML
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'zenith-accent': '{$accent}',
                        'zenith-accent-dark': '#5a9d76',
                        'zenith-accent-light': '#8fcaa6',
                        'zenith-bg': 'var(--zenith-bg)',
                        'zenith-alt': '#f9f9f9',
                        'zenith-dark': '#1a1a1a',
                        'zenith-dark-alt': '#222222',
                        'zenith-text': '#313131',
                        'zenith-heading': '#313131',
                        'zenith-meta': '#888888',
                        'zenith-border': '#dedede',
                        'zenith-border-dark': '#333333',
                    },
                    fontFamily: {
                        body: ['PT Serif', 'Georgia', 'serif'],
                        heading: ['Raleway', 'Inter', 'sans-serif'],
                    },
                    maxWidth: {
                        'container': '1170px',
                        'container-wide': '1400px',
                    },
                    boxShadow: {
                        'zenith-sm': '0 2px 8px rgba(0, 0, 0, 0.06)',
                        'zenith-md': '0 4px 20px rgba(0, 0, 0, 0.08)',
                        'zenith-lg': '0 10px 40px rgba(0, 0, 0, 0.12)',
                    },
                },
            },
        }
    </script>
HTML;
}

// =============================================================================
// SEO HOOKS
// =============================================================================

Event::on('zed_head', function(): void {
    global $post;
    
    $siteName = zed_get_site_name();
    $accent = zenith_accent();
    
    echo "\n    <!-- Zenith Theme -->\n";
    echo "    <meta name=\"theme-color\" content=\"{$accent}\">\n";
    
    if (!empty($post)) {
        $data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
        $title = htmlspecialchars($post['title'] ?? '');
        $excerpt = htmlspecialchars($data['excerpt'] ?? '');
        $featuredImage = $data['featured_image'] ?? '';
        
        // Open Graph
        echo "    <meta property=\"og:type\" content=\"article\">\n";
        echo "    <meta property=\"og:title\" content=\"{$title}\">\n";
        if ($excerpt) {
            echo "    <meta property=\"og:description\" content=\"{$excerpt}\">\n";
        }
        if ($featuredImage) {
            echo "    <meta property=\"og:image\" content=\"{$featuredImage}\">\n";
        }
        
        // Twitter Card
        echo "    <meta name=\"twitter:card\" content=\"summary_large_image\">\n";
    }
}, 15);

// =============================================================================
// ASSETS
// =============================================================================

// Enqueue theme assets
zed_enqueue_theme_asset('style.css');
