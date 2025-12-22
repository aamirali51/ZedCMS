<?php
/**
 * Zed Aurora Framework — functions.php
 * 
 * The ultimate starter boilerplate for ZedCMS demonstrating Theme API v2.
 * 
 * @package Aurora
 * @version 1.0.0
 * @license MIT
 */

declare(strict_types=1);

use Core\Event;
use Core\Database;

// =============================================================================
// AUTOLOAD THEME CLASSES
// =============================================================================

$appDir = __DIR__ . '/app';

// Load all app classes
foreach (['Setup', 'PostTypes', 'Settings', 'SEO', 'Assets'] as $class) {
    $file = $appDir . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

// =============================================================================
// CUSTOM POST TYPES
// =============================================================================

// Portfolio - Showcase your work
zed_register_post_type('portfolio', [
    'label' => 'Portfolio',
    'singular' => 'Project',
    'icon' => 'work',
    'supports' => ['title', 'editor', 'featured_image', 'excerpt'],
    'menu_position' => 25,
    'public' => true,
]);

// Testimonials - Client reviews
zed_register_post_type('testimonial', [
    'label' => 'Testimonials',
    'singular' => 'Testimonial',
    'icon' => 'format_quote',
    'supports' => ['title', 'editor'],
    'menu_position' => 26,
    'public' => true,
]);

// =============================================================================
// THEME SETTINGS (Aurora Options)
// =============================================================================

// Hero Section
zed_add_theme_setting('hero_image', 'Hero Background Image', 'text', '');
zed_add_theme_setting('hero_title', 'Hero Title', 'text', 'Welcome to Aurora');
zed_add_theme_setting('hero_subtitle', 'Hero Subtitle', 'text', 'The modern way to build with ZedCMS');

// Branding
zed_add_theme_setting('brand_color', 'Brand Color', 'color', '#6366f1');
zed_add_theme_setting('brand_color_dark', 'Brand Color (Dark)', 'color', '#4f46e5');

// Layout
zed_add_theme_setting('nav_layout', 'Navigation Layout', 'select', 'horizontal', [
    'horizontal' => 'Horizontal',
    'vertical' => 'Vertical Sidebar',
]);

zed_add_theme_setting('footer_columns', 'Footer Columns', 'select', '3', [
    '2' => '2 Columns',
    '3' => '3 Columns',
    '4' => '4 Columns',
]);

// Social Links
zed_add_theme_setting('social_twitter', 'Twitter/X URL', 'text', '');
zed_add_theme_setting('social_github', 'GitHub URL', 'text', '');
zed_add_theme_setting('social_linkedin', 'LinkedIn URL', 'text', '');

// =============================================================================
// SCOPED HOOKS — Portfolio-Specific Meta Tags
// =============================================================================

// Inject portfolio-specific Open Graph tags only on portfolio pages
Event::onScoped('zed_head', function(): void {
    $brandColor = zed_theme_option('brand_color', '#6366f1');
    echo "\n    <!-- Aurora: Portfolio Meta -->\n";
    echo "    <meta name=\"theme-color\" content=\"{$brandColor}\">\n";
    echo "    <meta property=\"og:type\" content=\"website\">\n";
}, ['post_type' => 'portfolio']);

// =============================================================================
// SEO — JSON-LD SCHEMA INJECTION
// =============================================================================

Event::on('zed_head', function(): void {
    // Only on single content pages
    global $post;
    if (empty($post) || !is_array($post)) {
        return;
    }
    
    $type = $post['type'] ?? 'page';
    $title = htmlspecialchars($post['title'] ?? '', ENT_QUOTES);
    $data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
    $excerpt = htmlspecialchars($data['excerpt'] ?? '', ENT_QUOTES);
    $featuredImage = $data['featured_image'] ?? '';
    $datePublished = $post['created_at'] ?? date('Y-m-d');
    $dateModified = $post['updated_at'] ?? $datePublished;
    
    $siteName = zed_get_site_name();
    $siteUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    
    // Article Schema for Posts
    if ($type === 'post') {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title,
            'description' => $excerpt,
            'image' => $featuredImage ?: null,
            'datePublished' => $datePublished,
            'dateModified' => $dateModified,
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
            ],
        ];
        
        echo "\n    <!-- Aurora: Article Schema -->\n";
        echo '    <script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . "</script>\n";
    }
    
    // Portfolio Schema
    if ($type === 'portfolio') {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $title,
            'description' => $excerpt,
            'image' => $featuredImage ?: null,
            'dateCreated' => $datePublished,
        ];
        
        echo "\n    <!-- Aurora: Portfolio Schema -->\n";
        echo '    <script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . "</script>\n";
    }
}, 15);

// =============================================================================
// ASSET ENQUEUING
// =============================================================================

// Enqueue theme assets
zed_enqueue_theme_asset('css/aurora.css');
zed_enqueue_theme_asset('js/aurora.js');

// =============================================================================
// TEMPLATE DATA INJECTION
// =============================================================================

Event::on('zed_template_data', function(array $data): array {
    // Inject Aurora theme options into all templates
    $data['aurora'] = [
        'brand_color' => zed_theme_option('brand_color', '#6366f1'),
        'brand_color_dark' => zed_theme_option('brand_color_dark', '#4f46e5'),
        'hero_image' => zed_theme_option('hero_image', ''),
        'hero_title' => zed_theme_option('hero_title', 'Welcome to Aurora'),
        'hero_subtitle' => zed_theme_option('hero_subtitle', 'The modern way to build with ZedCMS'),
        'nav_layout' => zed_theme_option('nav_layout', 'horizontal'),
        'footer_columns' => zed_theme_option('footer_columns', '3'),
        'social' => [
            'twitter' => zed_theme_option('social_twitter', ''),
            'github' => zed_theme_option('social_github', ''),
            'linkedin' => zed_theme_option('social_linkedin', ''),
        ],
    ];
    
    return $data;
});

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================

/**
 * Get a template part from the Aurora theme
 * 
 * @param string $slug Template slug (e.g., 'partials/header')
 * @param array $args Variables to pass to the template
 * @return void
 */
function aurora_get_template_part(string $slug, array $args = []): void
{
    $file = __DIR__ . '/templates/' . $slug . '.php';
    
    if (!file_exists($file)) {
        // Fallback to root
        $file = __DIR__ . '/' . $slug . '.php';
    }
    
    if (file_exists($file)) {
        // Extract args into local scope
        extract($args, EXTR_SKIP);
        include $file;
    }
}

/**
 * Get Aurora theme option with fallback
 * Shorthand for zed_theme_option
 */
function aurora_option(string $key, mixed $default = ''): mixed
{
    return zed_theme_option($key, $default);
}

/**
 * Output brand color CSS variables
 */
function aurora_css_variables(): string
{
    $brand = zed_theme_option('brand_color', '#6366f1');
    $brandDark = zed_theme_option('brand_color_dark', '#4f46e5');
    
    return <<<CSS
    <style>
        :root {
            --aurora-brand: {$brand};
            --aurora-brand-dark: {$brandDark};
            --aurora-brand-light: {$brand}20;
        }
    </style>
CSS;
}
