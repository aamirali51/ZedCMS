<?php
/**
 * Aurora Pro Theme - functions.php
 * 
 * This file is automatically loaded by Zed CMS when this theme is active.
 * It registers theme settings, custom post types, and hooks into the CMS.
 * 
 * @package AuroraPro
 * @version 1.0.0
 */

declare(strict_types=1);

use Core\Event;
use Core\Database;
use Core\Router;

// =============================================================================
// THEME CONSTANTS
// =============================================================================

define('AURORA_PRO_VERSION', '1.0.0');
define('AURORA_PRO_PATH', __DIR__);
define('AURORA_PRO_URL', Router::getBasePath() . '/content/themes/aurora-pro');

// Load theme config
$AURORA_CONFIG = require __DIR__ . '/config.php';

// =============================================================================
// ADMIN PANEL ROUTE
// =============================================================================

// Register theme settings page route
// Note: functions.php loads on app_init which is after admin modules, so zed_register_route exists
if (function_exists('zed_register_route')) {
    // Handle both GET and POST for display and form submission
    zed_register_route([
        'path' => '/admin/aurora-settings',
        'method' => ['GET', 'POST'],
        'capability' => 'manage_themes',
        'wrap_layout' => false,
        'priority' => 10,
        'callback' => function($request, $uri, $params) {
            require AURORA_PRO_PATH . '/panel/theme-panel.php';
        },
    ]);
}

// Add admin menu item for theme settings
Event::on('zed_admin_menu', function($menu) {
    $menu[] = [
        'id' => 'aurora-settings',
        'label' => 'Aurora Pro',
        'icon' => 'palette',
        'url' => Router::getBasePath() . '/admin/aurora-settings',
        'position' => 85,
        'capability' => 'manage_themes',
    ];
    return $menu;
});

// =============================================================================
// LAYOUT SYSTEM - Theme Settings Registration
// =============================================================================

// Layout Selection (CORE FEATURE)
zed_add_theme_setting('site_layout', 'Site Layout', 'select', 'blog', [
    'blog' => 'Classic Blog - Clean minimal layout with sidebar',
    'magazine' => 'Magazine - News-style with featured posts',
    'portfolio' => 'Portfolio - Grid layout for creative work',
]);

// =============================================================================
// APPEARANCE SETTINGS
// =============================================================================

// Colors
zed_add_theme_setting('primary_color', 'Primary Color', 'color', '#4f46e5');
zed_add_theme_setting('secondary_color', 'Secondary Color', 'color', '#7c3aed');
zed_add_theme_setting('accent_color', 'Accent Color', 'color', '#ec4899');

// Typography
zed_add_theme_setting('heading_font', 'Heading Font', 'select', 'inter', [
    'inter' => 'Inter',
    'playfair' => 'Playfair Display',
    'poppins' => 'Poppins',
    'roboto' => 'Roboto',
    'merriweather' => 'Merriweather',
]);

zed_add_theme_setting('body_font', 'Body Font', 'select', 'inter', [
    'inter' => 'Inter',
    'open-sans' => 'Open Sans',
    'roboto' => 'Roboto',
    'lato' => 'Lato',
    'source-sans' => 'Source Sans Pro',
]);

// =============================================================================
// HEADER SETTINGS
// =============================================================================

zed_add_theme_setting('sticky_header', 'Sticky Navigation', 'checkbox', true);
zed_add_theme_setting('show_search', 'Show Search in Header', 'checkbox', true);
zed_add_theme_setting('dark_mode', 'Enable Dark Mode Toggle', 'checkbox', true);

// =============================================================================
// HOMEPAGE SECTIONS (Blog/Magazine)
// =============================================================================

zed_add_theme_setting('show_hero', 'Show Hero Section', 'checkbox', true);
zed_add_theme_setting('hero_title', 'Hero Title', 'text', 'Welcome to Our Blog');
zed_add_theme_setting('hero_subtitle', 'Hero Subtitle', 'text', 'Discover stories, insights, and inspiration');

zed_add_theme_setting('show_featured', 'Show Featured Posts', 'checkbox', true);
zed_add_theme_setting('featured_count', 'Featured Posts Count', 'select', '3', [
    '2' => '2 posts',
    '3' => '3 posts',
    '4' => '4 posts',
    '5' => '5 posts',
]);

zed_add_theme_setting('show_categories', 'Show Category Grid', 'checkbox', true);
zed_add_theme_setting('show_newsletter', 'Show Newsletter Section', 'checkbox', true);
zed_add_theme_setting('newsletter_title', 'Newsletter Title', 'text', 'Subscribe to our newsletter');
zed_add_theme_setting('newsletter_text', 'Newsletter Description', 'text', 'Get the latest posts delivered straight to your inbox.');

// =============================================================================
// SIDEBAR SETTINGS
// =============================================================================

zed_add_theme_setting('show_sidebar', 'Show Sidebar', 'checkbox', true);
zed_add_theme_setting('sidebar_position', 'Sidebar Position', 'select', 'right', [
    'left' => 'Left',
    'right' => 'Right',
]);

// =============================================================================
// POST SETTINGS
// =============================================================================

zed_add_theme_setting('show_author_bio', 'Show Author Bio', 'checkbox', true);
zed_add_theme_setting('show_share_buttons', 'Show Share Buttons', 'checkbox', true);
zed_add_theme_setting('show_related_posts', 'Show Related Posts', 'checkbox', true);
zed_add_theme_setting('show_reading_time', 'Show Reading Time', 'checkbox', true);
zed_add_theme_setting('show_post_navigation', 'Show Next/Prev Navigation', 'checkbox', true);

// =============================================================================
// FOOTER SETTINGS
// =============================================================================

zed_add_theme_setting('footer_copyright', 'Copyright Text', 'text', '© ' . date('Y') . ' Your Site Name. All rights reserved.');
zed_add_theme_setting('footer_tagline', 'Footer Tagline', 'text', 'Built with ZedCMS');

// Social Links
zed_add_theme_setting('social_twitter', 'Twitter URL', 'text', '');
zed_add_theme_setting('social_facebook', 'Facebook URL', 'text', '');
zed_add_theme_setting('social_instagram', 'Instagram URL', 'text', '');
zed_add_theme_setting('social_linkedin', 'LinkedIn URL', 'text', '');
zed_add_theme_setting('social_github', 'GitHub URL', 'text', '');

// =============================================================================
// CUSTOM POST TYPE: Portfolio (for Portfolio layout)
// =============================================================================

if (function_exists('zed_register_post_type')) {
    zed_register_post_type('portfolio', [
        'label' => 'Portfolio',
        'singular' => 'Project',
        'icon' => 'work',
        'description' => 'Showcase your creative projects',
        'supports' => ['title', 'editor', 'featured_image', 'excerpt'],
        'menu_position' => 25,
        'show_in_menu' => true,
    ]);
}

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================

/**
 * Get an Aurora Pro theme option from the database
 * Uses the 'aurora_' prefix to match what the theme panel saves
 * 
 * @param string $key Option key (without prefix)
 * @param mixed $default Default value if not set
 * @return mixed Option value
 */
function aurora_option(string $key, mixed $default = ''): mixed
{
    return zed_get_option('aurora_' . $key, $default);
}

/**
 * Get the active layout for the theme
 */
function aurora_get_layout(): string
{
    return (string) aurora_option('site_layout', 'blog');
}

/**
 * Get the path to a layout file
 */
function aurora_get_layout_path(string $file): string
{
    $layout = aurora_get_layout();
    $layoutPath = AURORA_PRO_PATH . "/layouts/{$layout}/{$file}";
    
    if (file_exists($layoutPath)) {
        return $layoutPath;
    }
    
    // Fallback to blog layout
    return AURORA_PRO_PATH . "/layouts/blog/{$file}";
}

/**
 * Include a theme component
 */
function aurora_component(string $name, array $vars = []): void
{
    $path = AURORA_PRO_PATH . "/components/{$name}.php";
    
    if (file_exists($path)) {
        extract($vars);
        include $path;
    }
}

/**
 * Calculate reading time for content
 * Handles both string content and array content (TipTap/Editor.js JSON)
 */
function aurora_reading_time(mixed $content): int
{
    // If content is an array (Editor.js/TipTap JSON), extract text
    if (is_array($content)) {
        $content = aurora_extract_text_from_blocks($content);
    }
    
    // Ensure string
    $content = (string) $content;
    
    $wordCount = str_word_count(strip_tags($content));
    $readingTime = ceil($wordCount / 200); // 200 words per minute
    return max(1, (int) $readingTime);
}

/**
 * Extract plain text from Editor.js/TipTap block content
 */
function aurora_extract_text_from_blocks(array $blocks): string
{
    $text = '';
    
    foreach ($blocks as $block) {
        if (is_string($block)) {
            $text .= $block . ' ';
            continue;
        }
        
        if (is_array($block)) {
            // Handle Editor.js format
            if (isset($block['data']['text'])) {
                $text .= strip_tags($block['data']['text']) . ' ';
            }
            // Handle TipTap format
            if (isset($block['content'])) {
                $text .= aurora_extract_text_from_blocks($block['content']) . ' ';
            }
            // Handle text nodes
            if (isset($block['text'])) {
                $text .= $block['text'] . ' ';
            }
        }
    }
    
    return trim($text);
}

/**
 * Get excerpt from content
 */
function aurora_excerpt(string $content, int $length = 150): string
{
    $text = strip_tags($content);
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Get featured image URL or placeholder
 */
function aurora_featured_image(array $post, string $size = 'large'): string
{
    $data = is_string($post['data'] ?? '') 
        ? json_decode($post['data'], true) 
        : ($post['data'] ?? []);
    
    return $data['featured_image'] ?? '';
}

/**
 * Get Google Fonts URL based on theme settings
 */
function aurora_get_fonts_url(): string
{
    $headingFont = aurora_option('heading_font', 'inter');
    $bodyFont = aurora_option('body_font', 'inter');
    
    $fontMap = [
        'inter' => 'Inter:wght@400;500;600;700;800',
        'playfair' => 'Playfair+Display:wght@400;500;600;700',
        'poppins' => 'Poppins:wght@400;500;600;700',
        'roboto' => 'Roboto:wght@400;500;700',
        'merriweather' => 'Merriweather:wght@400;700',
        'open-sans' => 'Open+Sans:wght@400;500;600;700',
        'lato' => 'Lato:wght@400;700',
        'source-sans' => 'Source+Sans+Pro:wght@400;600;700',
    ];
    
    $fonts = [];
    if (isset($fontMap[$headingFont])) {
        $fonts[] = $fontMap[$headingFont];
    }
    if ($bodyFont !== $headingFont && isset($fontMap[$bodyFont])) {
        $fonts[] = $fontMap[$bodyFont];
    }
    
    if (empty($fonts)) {
        $fonts[] = $fontMap['inter'];
    }
    
    return 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $fonts) . '&display=swap';
}

/**
 * Get CSS custom properties for theme colors
 */
function aurora_get_color_css(): string
{
    $primary = aurora_option('primary_color', '#4f46e5');
    $secondary = aurora_option('secondary_color', '#7c3aed');
    $accent = aurora_option('accent_color', '#ec4899');
    
    return ":root {
        --color-primary: {$primary};
        --color-secondary: {$secondary};
        --color-accent: {$accent};
    }";
}

// =============================================================================
// HOOKS - Inject Theme Assets
// =============================================================================

/**
 * Inject theme styles and fonts into <head>
 */
Event::on('zed_head', function(): void {
    $fontsUrl = aurora_get_fonts_url();
    $colorCss = aurora_get_color_css();
    $themeUrl = AURORA_PRO_URL;
    
    echo <<<HTML
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{$fontsUrl}" rel="stylesheet">
    <link rel="stylesheet" href="{$themeUrl}/style.css?v=1.0.0">
    <style>{$colorCss}</style>
HTML;
}, 5);

/**
 * Add author bio after post content
 */
Event::on('zed_after_content', function(array $post, array $data): void {
    if (($post['type'] ?? '') !== 'post') {
        return;
    }
    
    if (aurora_option('show_author_bio', true) !== '1' && aurora_option('show_author_bio', true) !== true) {
        return;
    }
    
    aurora_component('author-box', ['post' => $post]);
}, 10);

/**
 * Add share buttons after post content
 */
Event::on('zed_after_content', function(array $post, array $data): void {
    if (!in_array($post['type'] ?? '', ['post', 'portfolio'])) {
        return;
    }
    
    if (aurora_option('show_share_buttons', true) !== '1' && aurora_option('show_share_buttons', true) !== true) {
        return;
    }
    
    aurora_component('share-buttons', ['post' => $post]);
}, 15);

/**
 * Add related posts after content
 */
Event::on('zed_after_content', function(array $post, array $data): void {
    if (($post['type'] ?? '') !== 'post') {
        return;
    }
    
    if (aurora_option('show_related_posts', true) !== '1' && aurora_option('show_related_posts', true) !== true) {
        return;
    }
    
    aurora_component('related-posts', ['post' => $post]);
}, 20);

/**
 * Inject dark mode script
 */
Event::on('zed_footer', function(): void {
    if (aurora_option('dark_mode', true) !== '1' && aurora_option('dark_mode', true) !== true) {
        return;
    }
    
    echo <<<'HTML'
    <script>
    (function() {
        const toggle = document.querySelector('.dark-toggle');
        const html = document.documentElement;
        
        // Check saved preference or system preference
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (saved === 'dark' || (!saved && prefersDark)) {
            html.setAttribute('data-theme', 'dark');
        }
        
        if (toggle) {
            toggle.addEventListener('click', function() {
                const isDark = html.getAttribute('data-theme') === 'dark';
                html.setAttribute('data-theme', isDark ? 'light' : 'dark');
                localStorage.setItem('theme', isDark ? 'light' : 'dark');
            });
        }
    })();
    </script>
HTML;
}, 100);
