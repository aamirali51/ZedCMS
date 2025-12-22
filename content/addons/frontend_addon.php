<?php

declare(strict_types=1);

/**
 * Frontend Addon - Public Content Viewer & Theme API
 * 
 * This addon handles public-facing routes for viewing content.
 * It matches slugs from the database and renders BlockNote JSON as HTML.
 * 
 * Routes:
 * - /{slug} -> View published content by slug
 * - /preview/{id} -> Preview content by ID (requires auth)
 * 
 * Theme API:
 * - Custom Post Types (CPT) registration
 * - Theme functions.php auto-loading
 * - Theme-driven hooks (zed_before_content, zed_after_content)
 * - Theme Options API (zed_add_theme_setting)
 * 
 * Helper System (organized in frontend/ directory):
 * - helpers_content.php — Content retrieval (zed_get_post, zed_get_posts)
 * - helpers_data.php — Data extraction (zed_get_title, zed_get_excerpt)
 * - helpers_media.php — Images (zed_get_featured_image, zed_get_thumbnail)
 * - helpers_author.php — Authors (zed_get_author, zed_get_post_author)
 * - helpers_taxonomy.php — Categories (zed_get_categories, zed_get_post_categories)
 * - helpers_pagination.php — Pagination (zed_pagination, zed_get_adjacent_post)
 * - helpers_utils.php — Utilities (zed_reading_time, zed_time_ago, zed_truncate)
 * - helpers_seo.php — SEO (zed_meta_tags, zed_og_tags, zed_schema_markup)
 * - helpers_conditionals.php — Conditionals (zed_is_home, zed_is_single)
 * - helpers_urls.php — URLs (zed_theme_url, zed_base_url, zed_admin_url)
 * - helpers_related.php — Related content (zed_get_related_posts, zed_get_featured_posts)
 */

use Core\Event;
use Core\Router;
use Core\Auth;
use Core\Database;

// =============================================================================
// HELPER SYSTEM — Organized in frontend/ directory
// =============================================================================

$helperDir = __DIR__ . '/frontend';

// Load all helper files in dependency order
$helpers = [
    'helpers_urls.php',         // URLs (no dependencies)
    'helpers_utils.php',        // Utilities (no dependencies)
    'helpers_security.php',     // Nonces/CSRF (no dependencies)
    'helpers_conditionals.php', // Conditionals (needs Router)
    'helpers_shortcodes.php',   // Shortcodes (no dependencies)
    'helpers_content.php',      // Content queries (needs Database)
    'helpers_data.php',         // Data extraction (needs helpers_utils, helpers_content)
    'helpers_media.php',        // Images (needs helpers_data)
    'helpers_author.php',       // Authors (needs Database, helpers_content)
    'helpers_taxonomy.php',     // Categories (needs Database, helpers_data)
    'helpers_pagination.php',   // Pagination (needs Router)
    'helpers_seo.php',          // SEO (needs helpers_data, helpers_urls)
    'helpers_related.php',      // Related (needs helpers_content, helpers_taxonomy)
    'helpers_cache.php',        // Transients (needs Database)
    'helpers_email.php',        // Email (no dependencies)
];

foreach ($helpers as $helper) {
    $helperPath = $helperDir . '/' . $helper;
    if (file_exists($helperPath)) {
        require_once $helperPath;
    }
}

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

// =============================================================================
// THEME OPTIONS API
// =============================================================================

/**
 * Global registry for theme settings
 * Stores: ['setting_id' => ['label' => '...', 'type' => '...', 'default' => '...']]
 */
global $ZED_THEME_SETTINGS;
$ZED_THEME_SETTINGS = [];

/**
 * Register a theme setting field
 * 
 * Usage in theme's functions.php:
 *   zed_add_theme_setting('accent_color', 'Accent Color', 'color', '#4f46e5');
 *   zed_add_theme_setting('show_author', 'Show Author Bio', 'checkbox', true);
 * 
 * @param string $id Setting ID (will be prefixed with theme name)
 * @param string $label Display label
 * @param string $type Field type: text, textarea, color, checkbox, select
 * @param mixed $default Default value
 * @param array $options For 'select' type: ['value' => 'Label', ...]
 * @return bool Success
 */
function zed_add_theme_setting(
    string $id, 
    string $label, 
    string $type = 'text', 
    mixed $default = '', 
    array $options = []
): bool {
    global $ZED_THEME_SETTINGS;
    
    $ZED_THEME_SETTINGS[$id] = [
        'id' => $id,
        'label' => $label,
        'type' => $type,
        'default' => $default,
        'options' => $options,
    ];
    
    return true;
}

/**
 * Get all registered theme settings
 * 
 * @return array Settings array
 */
function zed_get_theme_settings(): array
{
    global $ZED_THEME_SETTINGS;
    return $ZED_THEME_SETTINGS;
}

/**
 * Get a theme setting value from database
 * Stored with prefix: theme_{active_theme}_{setting_id}
 * 
 * @param string $id Setting ID 
 * @param mixed $default Fallback value
 * @return mixed Setting value
 */
function zed_theme_option(string $id, mixed $default = ''): mixed
{
    global $ZED_THEME_SETTINGS;
    
    // Get active theme
    $activeTheme = zed_get_option('active_theme', 'starter-theme');
    $optionName = "theme_{$activeTheme}_{$id}";
    
    // Get from database
    $value = zed_get_option($optionName, null);
    
    if ($value !== null) {
        return $value;
    }
    
    // Fall back to registered default
    if (isset($ZED_THEME_SETTINGS[$id])) {
        return $ZED_THEME_SETTINGS[$id]['default'];
    }
    
    return $default;
}

/**
 * Save a theme setting value
 * 
 * @param string $id Setting ID
 * @param mixed $value Value to save
 * @return bool Success
 */
function zed_set_theme_option(string $id, mixed $value): bool
{
    $activeTheme = zed_get_option('active_theme', 'starter-theme');
    $optionName = "theme_{$activeTheme}_{$id}";
    
    try {
        $db = Database::getInstance();
        
        // Check if exists
        $exists = $db->queryValue(
            "SELECT COUNT(*) FROM zed_options WHERE option_name = :name",
            ['name' => $optionName]
        );
        
        if ($exists > 0) {
            $db->query(
                "UPDATE zed_options SET option_value = :value WHERE option_name = :name",
                ['name' => $optionName, 'value' => is_bool($value) ? ($value ? '1' : '0') : (string)$value]
            );
        } else {
            $db->query(
                "INSERT INTO zed_options (option_name, option_value, autoload) VALUES (:name, :value, 1)",
                ['name' => $optionName, 'value' => is_bool($value) ? ($value ? '1' : '0') : (string)$value]
            );
        }
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// =============================================================================
// THEME FUNCTIONS.PHP AUTO-LOADING
// =============================================================================

/**
 * Global registry for theme metadata (required addons, etc.)
 * Stores: ['theme_slug' => ['required_addons' => [...], 'theme_uri' => '...']]
 */
global $ZED_THEME_REGISTRY;
$ZED_THEME_REGISTRY = [];

/**
 * Register theme requirements (called from theme's functions.php)
 * 
 * Usage:
 *   zed_register_theme_requirements([
 *       'required_addons' => ['seo_addon', 'cache_addon'],
 *       'min_php_version' => '8.2',
 *   ]);
 * 
 * @param array<string, mixed> $requirements Theme requirements
 * @return void
 */
function zed_register_theme_requirements(array $requirements): void
{
    global $ZED_THEME_REGISTRY;
    $activeTheme = zed_get_option('active_theme', 'starter-theme');
    $ZED_THEME_REGISTRY[$activeTheme] = $requirements;
}

/**
 * Get theme requirements for dependency checking
 * 
 * @param string|null $themeName Theme slug (null = active theme)
 * @return array<string, mixed> Requirements array
 */
function zed_get_theme_requirements(?string $themeName = null): array
{
    global $ZED_THEME_REGISTRY;
    $themeName = $themeName ?? zed_get_option('active_theme', 'starter-theme');
    return $ZED_THEME_REGISTRY[$themeName] ?? [];
}

/**
 * Check if theme has unmet addon dependencies
 * Returns array of missing addon names
 * 
 * @return array<string> Missing addon filenames
 */
function zed_get_missing_theme_addons(): array
{
    $requirements = zed_get_theme_requirements();
    $requiredAddons = $requirements['required_addons'] ?? [];
    
    if (empty($requiredAddons)) {
        return [];
    }
    
    // Get active addons from database
    $activeAddonsJson = zed_get_option('active_addons', '[]');
    $activeAddons = json_decode($activeAddonsJson, true) ?: [];
    
    // System addons are always active
    $systemAddons = ['admin_addon.php', 'frontend_addon.php'];
    $allActive = array_merge($systemAddons, $activeAddons);
    
    // Check which required addons are missing
    $missing = [];
    foreach ($requiredAddons as $addon) {
        // Normalize addon name (add .php if missing)
        $addonFile = str_ends_with($addon, '.php') ? $addon : $addon . '.php';
        
        if (!in_array($addonFile, $allActive, true)) {
            $missing[] = $addonFile;
        }
    }
    
    return $missing;
}

// =============================================================================
// ASSET INJECTION HELPER
// =============================================================================

/**
 * Global registry for enqueued theme assets
 * @var array<string, array{type: string, url: string, deps: array, ver: string}>
 */
global $ZED_ENQUEUED_ASSETS;
$ZED_ENQUEUED_ASSETS = ['css' => [], 'js' => []];

/**
 * Enqueue a theme asset (CSS or JS file)
 * 
 * Automatically resolves paths to active theme's assets/ folder.
 * Supports both development (Vite dev server) and production modes.
 *
 * Usage in theme's functions.php:
 *   zed_enqueue_theme_asset('css/custom.css');
 *   zed_enqueue_theme_asset('js/theme.js', ['jquery']);
 *
 * @param string $file Relative path within theme's assets folder
 * @param array<string> $deps Dependencies (for JS)
 * @param string $version Version string for cache busting
 * @return void
 */
function zed_enqueue_theme_asset(string $file, array $deps = [], string $version = '1.0.0'): void
{
    global $ZED_ENQUEUED_ASSETS;
    
    $activeTheme = zed_get_option('active_theme', 'starter-theme');
    $baseUrl = Router::getBasePath();
    
    // Determine file type
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $type = ($extension === 'css') ? 'css' : 'js';
    
    // Build URL to theme asset
    $url = "{$baseUrl}/content/themes/{$activeTheme}/assets/{$file}?v={$version}";
    
    // Check for Vite dev mode
    $viteManifest = dirname(__DIR__) . "/themes/{$activeTheme}/assets/dist/.vite/manifest.json";
    if (file_exists($viteManifest)) {
        // Production: Use manifest for hashed filenames
        $manifest = json_decode(file_get_contents($viteManifest), true);
        if (isset($manifest[$file])) {
            $url = "{$baseUrl}/content/themes/{$activeTheme}/assets/dist/{$manifest[$file]['file']}";
        }
    }
    
    // Add to queue
    $ZED_ENQUEUED_ASSETS[$type][$file] = [
        'type' => $type,
        'url' => $url,
        'deps' => $deps,
        'ver' => $version,
    ];
}

/**
 * Output enqueued CSS assets (call in theme's <head>)
 * 
 * @return string HTML link tags
 */
function zed_render_theme_styles(): string
{
    global $ZED_ENQUEUED_ASSETS;
    
    $html = '';
    foreach ($ZED_ENQUEUED_ASSETS['css'] as $asset) {
        $url = htmlspecialchars($asset['url']);
        $html .= "<link rel=\"stylesheet\" href=\"{$url}\">\n";
    }
    
    return $html;
}

/**
 * Output enqueued JS assets (call before </body>)
 * 
 * @return string HTML script tags
 */
function zed_render_theme_scripts(): string
{
    global $ZED_ENQUEUED_ASSETS;
    
    $html = '';
    foreach ($ZED_ENQUEUED_ASSETS['js'] as $asset) {
        $url = htmlspecialchars($asset['url']);
        $html .= "<script src=\"{$url}\"></script>\n";
    }
    
    return $html;
}

// =============================================================================
// DATA-DRIVEN TEMPLATES
// =============================================================================

/**
 * Global template data that gets injected into all templates
 * @var array<string, mixed>
 */
global $ZED_TEMPLATE_DATA;
$ZED_TEMPLATE_DATA = [];

/**
 * Add data to be available in templates
 * 
 * Usage:
 *   zed_add_template_data('author_name', 'John Doe');
 *   zed_add_template_data(['site_stats' => [...], 'user_prefs' => [...]]);
 *
 * @param string|array<string, mixed> $keyOrData Key name or associative array
 * @param mixed $value Value (if $keyOrData is string)
 * @return void
 */
function zed_add_template_data(string|array $keyOrData, mixed $value = null): void
{
    global $ZED_TEMPLATE_DATA;
    
    if (is_array($keyOrData)) {
        $ZED_TEMPLATE_DATA = array_merge($ZED_TEMPLATE_DATA, $keyOrData);
    } else {
        $ZED_TEMPLATE_DATA[$keyOrData] = $value;
    }
}

/**
 * Get all template data (filtered)
 * Applies the zed_template_data filter for dynamic injection
 *
 * @param array<string, mixed> $contextData Additional context-specific data
 * @return array<string, mixed> Merged template data
 */
function zed_get_template_data(array $contextData = []): array
{
    global $ZED_TEMPLATE_DATA;
    
    // Merge global and context data
    $data = array_merge($ZED_TEMPLATE_DATA, $contextData);
    
    // Apply filter for dynamic injection
    return Event::filter('zed_template_data', $data);
}

/**
 * Extract template data into local scope (call at top of template)
 * 
 * Usage in template:
 *   <?php zed_extract_template_data(); ?>
 *   <h1><?= $page_title ?></h1>
 *
 * @param array<string, mixed> $contextData Additional data
 * @return void
 */
function zed_extract_template_data(array $contextData = []): void
{
    $data = zed_get_template_data($contextData);
    extract($data, EXTR_SKIP);
}

/**
 * Load active theme's functions.php during app_ready
 * This allows themes to register hooks, post types, and settings
 */
Event::on('app_ready', function(): void {
    // Get active theme from database
    $activeTheme = zed_get_option('active_theme', 'aurora');
    
    // Build path to theme's directory
    $themesDir = dirname(__DIR__) . '/themes';
    $themePath = $themesDir . '/' . $activeTheme;
    $functionsPath = $themePath . '/functions.php';
    
    // Define constants for theme parts system
    if (!defined('ZED_ACTIVE_THEME')) {
        define('ZED_ACTIVE_THEME', $activeTheme);
    }
    if (!defined('ZED_ACTIVE_THEME_PATH')) {
        define('ZED_ACTIVE_THEME_PATH', $themePath);
    }
    
    // Load if exists
    if (file_exists($functionsPath)) {
        require_once $functionsPath;
    }
}, 5); // Priority 5 = runs early, before route dispatch


/**
 * Normalize a block to ensure it has all required keys with default values
 * Prevents errors when accessing missing properties
 *
 * @param mixed $block Raw block data
 * @return array Normalized block
 */
function normalize_block(mixed $block): array
{
    if (!is_array($block)) {
        return [
            'id' => uniqid(),
            'type' => 'paragraph',
            'props' => [],
            'content' => [],
            'children' => []
        ];
    }
    
    return [
        'id' => $block['id'] ?? uniqid(),
        'type' => $block['type'] ?? 'paragraph',
        'props' => is_array($block['props'] ?? null) ? $block['props'] : [],
        // Content default depends on usage, but empty array is safest generic default
        'content' => $block['content'] ?? [], 
        'children' => is_array($block['children'] ?? null) ? $block['children'] : []
    ];
}

/**
 * BlockNote JSON to HTML Renderer
 * 
 * Converts BlockNote block format to valid HTML.
 * Supports: paragraph, heading, bulletListItem, numberedListItem, image, code
 *
 * @param array|string $blocks BlockNote blocks array or JSON string
 * @return string Rendered HTML
 */
function render_blocks(array|string $blocks): string
{
    // Parse if JSON string
    if (is_string($blocks)) {
        $blocks = json_decode($blocks, true);
        if (!is_array($blocks)) {
            return '';
        }
    }

    $html = '';
    $listStack = []; // Track open list types
    
    foreach ($blocks as $rawBlock) {
        // Normalize block data to prevent crashes
        $block = normalize_block($rawBlock);
        
        $type = $block['type'];
        $content = $block['content'];
        $props = $block['props'];
        
        // Close list if type changes
        if (!empty($listStack) && !in_array($type, ['bulletListItem', 'numberedListItem'])) {
            while (!empty($listStack)) {
                $closingTag = array_pop($listStack);
                $html .= "</{$closingTag}>\n";
            }
        }
        
        // Render inline content (text, links, etc.)
        // Note: Table content is structured differently, handled in render_table
        $innerHtml = $type !== 'table' ? render_inline_content($content) : '';
        
        switch ($type) {
            case 'paragraph':
                if (!empty($innerHtml)) {
                    $textAlign = $props['textAlignment'] ?? 'left';
                    $alignStyle = $textAlign !== 'left' ? " style=\"text-align: {$textAlign};\"" : '';
                    $html .= "<p{$alignStyle}>{$innerHtml}</p>\n";
                }
                break;
                
            case 'heading':
                $level = min(6, max(1, (int)($props['level'] ?? 2)));
                $textAlign = $props['textAlignment'] ?? 'left';
                $alignStyle = $textAlign !== 'left' ? " style=\"text-align: {$textAlign};\"" : '';
                $html .= "<h{$level}{$alignStyle}>{$innerHtml}</h{$level}>\n";
                break;
                
            case 'bulletListItem':
                // Open <ul> if not already in one
                if (empty($listStack) || end($listStack) !== 'ul') {
                    if (!empty($listStack) && end($listStack) === 'ol') {
                        $html .= "</ol>\n";
                        array_pop($listStack);
                    }
                    $html .= "<ul>\n";
                    $listStack[] = 'ul';
                }
                $html .= "<li>{$innerHtml}</li>\n";
                break;
                
            case 'numberedListItem':
                // Open <ol> if not already in one
                if (empty($listStack) || end($listStack) !== 'ol') {
                    if (!empty($listStack) && end($listStack) === 'ul') {
                        $html .= "</ul>\n";
                        array_pop($listStack);
                    }
                    $html .= "<ol>\n";
                    $listStack[] = 'ol';
                }
                $html .= "<li>{$innerHtml}</li>\n";
                break;
                
            case 'image':
                // Safe accessor for URL (check 'url' then 'src')
                $url = htmlspecialchars($props['url'] ?? $props['src'] ?? '');
                $alt = htmlspecialchars($props['caption'] ?? $props['name'] ?? 'Image');
                $width = $props['width'] ?? 'auto';
                if ($url) {
                    $html .= "<figure class=\"content-image\">\n";
                    $html .= "  <img src=\"{$url}\" alt=\"{$alt}\" style=\"max-width: 100%; width: {$width};\" loading=\"lazy\">\n";
                    if (!empty($props['caption'])) {
                        $html .= "  <figcaption>" . htmlspecialchars($props['caption']) . "</figcaption>\n";
                    }
                    $html .= "</figure>\n";
                }
                break;
                
            case 'codeBlock':
                $language = htmlspecialchars($props['language'] ?? 'plaintext');
                $code = htmlspecialchars(render_inline_content($content, true));
                $html .= "<pre><code class=\"language-{$language}\">{$code}</code></pre>\n";
                break;
                
            case 'table':
                $html .= render_table($block);
                break;
                
            case 'video':
                $url = htmlspecialchars($props['url'] ?? '');
                if ($url) {
                    $html .= "<div class=\"video-wrapper\">\n";
                    $html .= "  <video src=\"{$url}\" controls style=\"max-width: 100%;\"></video>\n";
                    $html .= "</div>\n";
                }
                break;
                
            case 'audio':
                $url = htmlspecialchars($props['url'] ?? '');
                if ($url) {
                    $html .= "<audio src=\"{$url}\" controls></audio>\n";
                }
                break;
                
            case 'file':
                $url = htmlspecialchars($props['url'] ?? '');
                $name = htmlspecialchars($props['name'] ?? 'Download File');
                if ($url) {
                    $html .= "<a href=\"{$url}\" class=\"file-download\" download>{$name}</a>\n";
                }
                break;
                
            default:
                // SAFETY: Handle unknown block types gracefully
                // Output hidden HTML comment for debugging (won't crash the site)
                $safeType = htmlspecialchars($type);
                $html .= "<!-- Zed CMS: Unknown block type '{$safeType}' -->\n";
                
                // Still try to render any text content it might have
                if (!empty($innerHtml)) {
                    $html .= "<div class=\"unknown-block\">{$innerHtml}</div>\n";
                }
                break;
        }
    }
    
    // Close any remaining open lists
    while (!empty($listStack)) {
        $closingTag = array_pop($listStack);
        $html .= "</{$closingTag}>\n";
    }
    
    return $html;
}

/**
 * Render inline content (text with formatting)
 *
 * @param array $content Array of inline content nodes
 * @param bool $plainText If true, strip all formatting
 * @return string Rendered HTML or plain text
 */
function render_inline_content(array $content, bool $plainText = false): string
{
    $result = '';
    
    foreach ($content as $node) {
        $text = $node['text'] ?? '';
        $styles = $node['styles'] ?? [];
        
        if ($plainText) {
            $result .= $text;
            continue;
        }
        
        // Escape HTML in text
        $html = htmlspecialchars($text);
        
        // Apply styles (order matters for proper nesting)
        if (!empty($styles['code'])) {
            $html = "<code>{$html}</code>";
        }
        if (!empty($styles['bold'])) {
            $html = "<strong>{$html}</strong>";
        }
        if (!empty($styles['italic'])) {
            $html = "<em>{$html}</em>";
        }
        if (!empty($styles['underline'])) {
            $html = "<u>{$html}</u>";
        }
        if (!empty($styles['strike'])) {
            $html = "<s>{$html}</s>";
        }
        if (!empty($styles['textColor'])) {
            $color = htmlspecialchars($styles['textColor']);
            $html = "<span style=\"color: {$color};\">{$html}</span>";
        }
        if (!empty($styles['backgroundColor'])) {
            $bg = htmlspecialchars($styles['backgroundColor']);
            $html = "<span style=\"background-color: {$bg};\">{$html}</span>";
        }
        
        // Handle links
        if ($node['type'] === 'link' && !empty($node['href'])) {
            $href = htmlspecialchars($node['href']);
            $linkContent = render_inline_content($node['content'] ?? [], $plainText);
            $html = "<a href=\"{$href}\">{$linkContent}</a>";
        }
        
        $result .= $html;
    }
    
    return $result;
}

/**
 * Render a table block
 *
 * @param array $block Table block data
 * @return string HTML table
 */
function render_table(array $block): string
{
    $content = $block['content'] ?? [];
    if (empty($content) || !isset($content['rows'])) {
        return '';
    }
    
    $html = "<table class=\"content-table\">\n";
    
    foreach ($content['rows'] as $rowIndex => $row) {
        $html .= "<tr>\n";
        $cells = $row['cells'] ?? [];
        foreach ($cells as $cell) {
            $tag = $rowIndex === 0 ? 'th' : 'td';
            $cellContent = render_inline_content($cell ?? []);
            $html .= "  <{$tag}>{$cellContent}</{$tag}>\n";
        }
        $html .= "</tr>\n";
    }
    
    $html .= "</table>\n";
    return $html;
}

/**
 * Get all menus from the database
 * 
 * @return array Array of all menus
 */
function zed_get_all_menus(): array
{
    try {
        $db = Database::getInstance();
        return $db->query("SELECT * FROM zed_menus ORDER BY name ASC") ?: [];
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get a menu by ID
 * 
 * @param int $id Menu ID
 * @return array|null Menu data or null if not found
 */
function zed_get_menu_by_id(int $id): ?array
{
    try {
        $db = Database::getInstance();
        $menu = $db->queryOne("SELECT * FROM zed_menus WHERE id = :id", ['id' => $id]);
        if ($menu && !empty($menu['items'])) {
            $menu['items'] = json_decode($menu['items'], true) ?: [];
        }
        return $menu ?: null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get a menu by name
 * 
 * @param string $name Menu name (case-insensitive)
 * @return array|null Menu data or null if not found
 */
function zed_get_menu_by_name(string $name): ?array
{
    try {
        $db = Database::getInstance();
        $menu = $db->queryOne("SELECT * FROM zed_menus WHERE LOWER(name) = LOWER(:name)", ['name' => $name]);
        if ($menu && !empty($menu['items'])) {
            $menu['items'] = json_decode($menu['items'], true) ?: [];
        }
        return $menu ?: null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Render a navigation menu by location slug (via nav_menu_locations option)
 * OR by menu name directly.
 * 
 * Usage in themes:
 *   echo render_menu('header');       // By location (requires nav_menu_locations option)
 *   echo zed_menu('Main Menu');       // By menu name (direct)
 *   echo zed_menu(1);                 // By menu ID (direct)
 * 
 * @param string $locationSlug The menu location identifier (e.g. 'header')
 * @return string HTML unordered list or empty string if not found
 */
function render_menu(string $locationSlug): string
{
    try {
        $db = Database::getInstance();
        
        // 1. Get the Menu Location Mapping
        $option = $db->queryOne("SELECT option_value FROM zed_options WHERE option_name = 'nav_menu_locations'");
        
        if (!$option || empty($option['option_value'])) {
             return '';
        }
        
        $locations = json_decode($option['option_value'], true);
        if (!isset($locations[$locationSlug])) {
            return '';
        }
        
        $menuId = $locations[$locationSlug];
        
        return zed_menu($menuId);
        
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Render a navigation menu by ID or name - THE MAIN HELPER FOR THEMES
 * 
 * Usage in any theme:
 *   <?= zed_menu('Main Menu') ?>       // By name
 *   <?= zed_menu(1) ?>                 // By ID
 *   <?= zed_menu('Main Menu', ['class' => 'nav-menu']) ?>  // With options
 * 
 * @param int|string $menuIdOrName Menu ID (int) or menu name (string)
 * @param array $options Optional: ['class' => 'custom-class', 'id' => 'nav-id']
 * @return string HTML unordered list or empty string if not found
 */
function zed_menu(int|string $menuIdOrName, array $options = []): string
{
    try {
        // Fetch menu by ID or name
        if (is_int($menuIdOrName)) {
            $menu = zed_get_menu_by_id($menuIdOrName);
        } else {
            $menu = zed_get_menu_by_name($menuIdOrName);
        }
        
        if (!$menu || empty($menu['items'])) {
            return '';
        }
        
        $items = is_array($menu['items']) ? $menu['items'] : [];
        
        if (empty($items)) {
            return '';
        }
        
        // Build attributes
        $menuSlug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $menu['name']));
        $classes = ['zed-menu', "zed-menu-{$menuSlug}"];
        if (!empty($options['class'])) {
            $classes[] = $options['class'];
        }
        $classAttr = implode(' ', $classes);
        $idAttr = !empty($options['id']) ? ' id="' . htmlspecialchars($options['id']) . '"' : '';
        
        // Recursive render function
        $renderItems = function(array $items, int $depth = 0) use (&$renderItems) {
            $html = '';
            $base_url = Router::getBasePath();
            
            foreach ($items as $item) {
                $label = htmlspecialchars($item['label'] ?? '');
                $url = $item['url'] ?? '#';
                $target = htmlspecialchars($item['target'] ?? '_self');
                $children = $item['children'] ?? [];
                
                // Make relative URLs use base path
                if (!str_starts_with($url, 'http') && !str_starts_with($url, '#')) {
                    if (!str_starts_with($url, '/')) {
                        $url = '/' . $url;
                    }
                    if (!str_starts_with($url, $base_url)) {
                        $url = $base_url . $url;
                    }
                }
                $url = htmlspecialchars($url);
                
                $hasChildren = !empty($children);
                $liClass = $hasChildren ? ' class="has-children"' : '';
                
                $html .= "<li{$liClass}>";
                $html .= "<a href=\"{$url}\"" . ($target !== '_self' ? " target=\"{$target}\"" : "") . ">{$label}</a>";
                
                if ($hasChildren) {
                    $html .= '<ul class="sub-menu">';
                    $html .= $renderItems($children, $depth + 1);
                    $html .= '</ul>';
                }
                
                $html .= "</li>\n";
            }
            return $html;
        };
        
        return "<ul class=\"{$classAttr}\"{$idAttr}>\n" . $renderItems($items) . "</ul>";
        
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Get the first available menu (useful for themes that just need "a" menu)
 * 
 * @return array|null First menu or null
 */
function zed_get_primary_menu(): ?array
{
    $menus = zed_get_all_menus();
    return $menus[0] ?? null;
}

/**
 * Render the first available menu (auto-detect)
 * 
 * @param array $options Optional styling options
 * @return string HTML menu
 */
function zed_primary_menu(array $options = []): string
{
    $menu = zed_get_primary_menu();
    if ($menu) {
        return zed_menu((int)$menu['id'], $options);
    }
    return '';
}

// =============================================================================
// OPTIONS HELPER - Cached database lookups for settings
// =============================================================================

/**
 * Get a site option from zed_options table
 * Results are cached in a static variable to prevent repeated DB queries.
 *
 * @param string $name Option name
 * @param mixed $default Default value if option not found
 * @return mixed Option value or default
 */
function zed_get_option(string $name, mixed $default = ''): mixed
{
    static $optionsCache = null;
    
    // Load all options on first call (single query)
    if ($optionsCache === null) {
        $optionsCache = [];
        try {
            $db = Database::getInstance();
            $rows = $db->query("SELECT option_name, option_value FROM zed_options WHERE autoload = 1");
            foreach ($rows as $row) {
                $optionsCache[$row['option_name']] = $row['option_value'];
            }
        } catch (Exception $e) {
            // Silently fail - use defaults
        }
    }
    
    // Return cached value or fetch individually if not autoloaded
    if (isset($optionsCache[$name])) {
        return $optionsCache[$name];
    }
    
    // Not in cache - try individual lookup (for non-autoload options)
    try {
        $db = Database::getInstance();
        $result = $db->queryOne(
            "SELECT option_value FROM zed_options WHERE option_name = :name",
            ['name' => $name]
        );
        if ($result) {
            $optionsCache[$name] = $result['option_value'];
            return $result['option_value'];
        }
    } catch (Exception $e) {
        // Silently fail
    }
    
    return $default;
}

/**
 * Get site name from settings
 */
function zed_get_site_name(): string
{
    return zed_get_option('site_title', 'Zed CMS');
}

/**
 * Get site tagline from settings
 */
function zed_get_site_tagline(): string
{
    return zed_get_option('site_tagline', '');
}

/**
 * Get meta description from settings
 */
function zed_get_meta_description(): string
{
    return zed_get_option('meta_description', '');
}

/**
 * Check if search engines should be discouraged
 */
function zed_is_noindex(): bool
{
    return zed_get_option('discourage_search_engines', '0') === '1';
}

/**
 * Get posts per page setting
 */
function zed_get_posts_per_page(): int
{
    return max(1, (int)zed_get_option('posts_per_page', '10'));
}

/**
 * Fetch latest published posts for blog listing
 *
 * @param int $limit Number of posts to fetch
 * @param int $offset Offset for pagination
 * @return array Posts array
 */
function zed_get_latest_posts(int $limit = 10, int $offset = 0): array
{
    try {
        $db = Database::getInstance();
        return $db->query(
            "SELECT * FROM zed_content 
             WHERE type = 'post' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get total count of published posts
 */
function zed_count_published_posts(): int
{
    try {
        $db = Database::getInstance();
        return (int)$db->queryValue(
            "SELECT COUNT(*) FROM zed_content 
             WHERE type = 'post' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'"
        );
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Resolve template using WordPress-style Template Hierarchy
 * 
 * For 'archive' prefix with 'portfolio' type:
 *   1. archive-portfolio.php
 *   2. archive.php
 *   3. index.php
 * 
 * For 'single' prefix with 'portfolio' type:
 *   1. single-portfolio.php
 *   2. single.php
 *   3. index.php
 * 
 * @param string $themePath Path to active theme directory
 * @param string $prefix Template prefix (archive, single, page)
 * @param string $type Post type slug
 * @return string Full path to selected template
 */
function zed_resolve_template_hierarchy(string $themePath, string $prefix, string $type): string
{
    // Template hierarchy (most specific to least specific)
    $hierarchy = [
        "{$prefix}-{$type}.php",  // e.g., archive-portfolio.php
        "{$prefix}.php",          // e.g., archive.php
        'index.php'               // Ultimate fallback
    ];
    
    foreach ($hierarchy as $template) {
        $path = $themePath . '/' . $template;
        if (file_exists($path)) {
            return $path;
        }
    }
    
    // If nothing found, return index.php path (even if it doesn't exist)
    return $themePath . '/index.php';
}

/**
 * Get a single page by ID
 */
function zed_get_page_by_id(int $id): ?array
{
    try {
        $db = Database::getInstance();
        return $db->queryOne(
            "SELECT * FROM zed_content WHERE id = :id AND type = 'page' LIMIT 1",
            ['id' => $id]
        );
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Render a complete HTML page for public viewing
 *
 * @param array $post Post data from database
 * @param string $renderedContent HTML content
 * @return string Complete HTML page
 */
function render_page(array $post, string $renderedContent): string
{
    $title = htmlspecialchars($post['title'] ?? 'Untitled');
    $type = ucfirst($post['type'] ?? 'page');
    $data = is_string($post['data']) ? json_decode($post['data'], true) : ($post['data'] ?? []);
    $excerpt = htmlspecialchars($data['excerpt'] ?? '');
    $featuredImage = $data['featured_image'] ?? '';
    $createdAt = $post['created_at'] ?? '';
    $updatedAt = $post['updated_at'] ?? '';
    
    // Format dates
    $publishDate = $createdAt ? date('F j, Y', strtotime($createdAt)) : '';
    
    // Base URL
    $baseUrl = Router::getBasePath();
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} — Zed CMS</title>
    <meta name="description" content="{$excerpt}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Lora', 'Georgia', 'serif'],
                    },
                },
            },
        }
    </script>
    
    <style>
        /* Content Styles */
        .content-area {
            font-family: 'Lora', Georgia, serif;
            font-size: 1.125rem;
            line-height: 1.8;
            color: #1f2937;
        }
        .content-area p {
            margin-bottom: 1.5rem;
        }
        .content-area h1, .content-area h2, .content-area h3, 
        .content-area h4, .content-area h5, .content-area h6 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .content-area h1 { font-size: 2.25rem; }
        .content-area h2 { font-size: 1.875rem; }
        .content-area h3 { font-size: 1.5rem; }
        .content-area ul, .content-area ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }
        .content-area li {
            margin-bottom: 0.5rem;
        }
        .content-area ul li {
            list-style-type: disc;
        }
        .content-area ol li {
            list-style-type: decimal;
        }
        .content-area a {
            color: #4f46e5;
            text-decoration: underline;
        }
        .content-area a:hover {
            color: #4338ca;
        }
        .content-area pre {
            background: #1f2937;
            color: #f3f4f6;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }
        .content-area code {
            font-family: ui-monospace, SFMono-Regular, monospace;
            font-size: 0.9em;
        }
        .content-area :not(pre) > code {
            background: #f3f4f6;
            padding: 0.2em 0.4em;
            border-radius: 0.25rem;
            color: #dc2626;
        }
        .content-area figure {
            margin: 2rem 0;
        }
        .content-area figcaption {
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }
        .content-area blockquote {
            border-left: 4px solid #4f46e5;
            padding-left: 1rem;
            font-style: italic;
            color: #4b5563;
            margin: 1.5rem 0;
        }
        .content-area table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        .content-area th, .content-area td {
            border: 1px solid #e5e7eb;
            padding: 0.75rem;
            text-align: left;
        }
        .content-area th {
            background: #f9fafb;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{$baseUrl}/" class="font-bold text-xl flex items-center gap-2">
                <span class="w-8 h-8 bg-black text-white rounded flex items-center justify-center text-sm">Z</span>
                <span>Zero</span>
            </a>
            <nav class="flex items-center gap-6 text-sm">
                <a href="{$baseUrl}/" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="{$baseUrl}/admin" class="text-indigo-600 hover:text-indigo-700 font-medium">Admin</a>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-6 py-12">
        <!-- Article Header -->
        <article>
            <header class="mb-8">
                <div class="text-sm text-indigo-600 font-medium mb-2">{$type}</div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4 font-sans">{$title}</h1>
                " . ($publishDate ? "<p class=\"text-gray-500 text-sm\">Published on {$publishDate}</p>" : "") . "
            </header>
            
            <!-- Featured Image -->
            " . ($featuredImage ? "<img src=\"{$featuredImage}\" alt=\"{$title}\" class=\"w-full rounded-lg mb-8 shadow-lg\">" : "") . "
            
            <!-- Content -->
            <div class="content-area">
                {$renderedContent}
            </div>
        </article>
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-4xl mx-auto px-6 py-8 text-center text-gray-500 text-sm">
            <p>Powered by <strong>Zed CMS</strong></p>
        </div>
    </footer>
</body>
</html>
HTML;
}

// =============================================================================
// Route Listener - Runs with LOW priority (100) so it acts as a fallback
// =============================================================================

Event::on('route_request', function (array $request): void {
    $uri = $request['uri'];
    
    // =========================================================================
    // API: Contact Form Submission
    // =========================================================================
    if ($uri === '/api/submit-contact' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? 'New Message');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($message)) {
            echo json_encode(['success' => false, 'error' => 'All fields are required.']);
            Router::setHandled('');
            return;
        }
        
        try {
            $db = Database::getInstance();
            $title = "Message from " . $name;
            $slug = 'msg-' . time() . '-' . mt_rand(1000, 9999);
            
            $data = [
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
            ];
            
            $db->query(
                "INSERT INTO zed_content (title, slug, type, data, author_id, created_at, updated_at) 
                 VALUES (:title, :slug, 'contact_message', :data, 0, NOW(), NOW())",
                [
                    'title' => $title,
                    'slug' => $slug,
                    'data' => json_encode($data)
                ]
            );
            
            // Redirect back with success (since it's a standard FORM POST, not AJAX in the template)
            // Wait, the template uses standard <form>. So we should Redirect.
            // But user might want JSON if they used JS.
            // The template I wrote uses standard POST.
            
            // Detect if AJAX
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            if ($isAjax || isset($_GET['json'])) {
                echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
            } else {
                // Redirect to referrer with success param
                $referrer = $_SERVER['HTTP_REFERER'] ?? '/';
                if (str_contains($referrer, '?')) {
                    $referrer .= '&success=1';
                } else {
                    $referrer .= '?success=1';
                }
                header("Location: " . $referrer);
            }
            
        } catch (Exception $e) {
             if ($isAjax || isset($_GET['json'])) {
                echo json_encode(['success' => false, 'error' => 'Database error']);
            } else {
                die("Error saving message: " . $e->getMessage());
            }
        }
        
        Router::setHandled('');
        return;
    }
    
    // =========================================================================
    // THEME CONFIGURATION
    // =========================================================================
    // =========================================================================
    // ACTIVE THEME RESOLUTION
    // Reads from database (set via Admin > Themes)
    // =========================================================================
    $theme = zed_get_option('active_theme', 'starter-theme');
    
    // Define theme path
    $themesDir = __DIR__ . '/../themes';
    $themePath = $themesDir . '/' . $theme;
    
    // Fallback: if theme directory doesn't exist, use starter-theme
    if (!is_dir($themePath)) {
        $theme = 'starter-theme';
        $themePath = $themesDir . '/' . $theme;
    }
    
    // Make theme name globally accessible for other addons
    if (!defined('ZED_ACTIVE_THEME')) {
        define('ZED_ACTIVE_THEME', $theme);
    }
    
    // =========================================================================
    // ROUTE FILTERING
    // =========================================================================
    
    // Skip admin routes - let admin_addon handle those
    if (str_starts_with($uri, '/admin')) {
        return;
    }
    
    // Skip if already handled
    if (Router::isHandled()) {
        return;
    }
    
    // Extract slug from URI (remove leading slash)
    $slug = ltrim($uri, '/');
    
    // =========================================================================
    // SMART ROUTING - Respects Unified Settings
    // =========================================================================
    
    // Get homepage configuration from settings
    $homepage_mode = zed_get_option('homepage_mode', 'latest_posts');
    $page_on_front = (int)zed_get_option('page_on_front', '0');
    $blog_slug = zed_get_option('blog_slug', 'blog');
    $posts_per_page = zed_get_posts_per_page();
    
    // =========================================================================
    // HOMEPAGE HANDLER (empty slug = /)
    // =========================================================================
    if (empty($slug)) {
        $base_url = Router::getBasePath();
        
        // Case A: Static Page Homepage
        if ($homepage_mode === 'static_page' && $page_on_front > 0) {
            $post = zed_get_page_by_id($page_on_front);
            
            if ($post) {
                // Parse content
                $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
                $blocks = $data['content'] ?? [];
                $htmlContent = render_blocks($blocks);
                
                // Try page.php first, then single.php, then fallback
                $pageTemplate = $themePath . '/page.php';
                $singleTemplate = $themePath . '/single.php';
                
                if (file_exists($pageTemplate)) {
                    ob_start();
                    include $pageTemplate;
                    $html = ob_get_clean();
                } elseif (file_exists($singleTemplate)) {
                    ob_start();
                    include $singleTemplate;
                    $html = ob_get_clean();
                } else {
                    // Fallback render
                    $html = render_page($post, $htmlContent);
                }
                
                Router::setHandled($html);
                return;
            }
        }
        
        // Case B: Latest Posts (Default)
        // Fetch latest posts for the homepage
        $page_num = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page_num - 1) * $posts_per_page;
        $posts = zed_get_latest_posts($posts_per_page, $offset);
        $total_posts = zed_count_published_posts();
        $total_pages = max(1, ceil($total_posts / $posts_per_page));
        
        // Theme variables for index.php
        $is_home = true;
        $is_blog = true;
        
        $homepageTemplate = $themePath . '/index.php';
        
        if (file_exists($homepageTemplate)) {
            ob_start();
            include $homepageTemplate;
            $html = ob_get_clean();
            Router::setHandled($html);
            return;
        }
        
        // Fallback: Let it fall through
        return;
    }
    
    // =========================================================================
    // SMART ARCHIVE HANDLER
    // Automatically handles archive pages for ANY registered post type
    // Checks $ZED_POST_TYPES registry to detect CPT slugs
    // =========================================================================
    
    // Parse URL segments for context-aware routing
    $segments = array_filter(explode('/', $slug));
    $firstSegment = $segments[0] ?? '';
    $secondSegment = $segments[1] ?? null;
    
    // Get all registered post types
    $postTypes = zed_get_post_types(true);
    
    // Check if first segment matches a post type (or its plural slug)
    $matchedType = null;
    $matchedTypeConfig = null;
    
    foreach ($postTypes as $typeSlug => $typeConfig) {
        // Match by type slug directly (e.g., /portfolio)
        if ($firstSegment === $typeSlug) {
            $matchedType = $typeSlug;
            $matchedTypeConfig = $typeConfig;
            break;
        }
        
        // Also match by plural label slug (e.g., /portfolios → portfolio)
        $pluralSlug = strtolower(str_replace(' ', '-', $typeConfig['label'] ?? ''));
        if ($firstSegment === $pluralSlug) {
            $matchedType = $typeSlug;
            $matchedTypeConfig = $typeConfig;
            break;
        }
    }
    
    // Special case: /blog always maps to 'post' type
    if ($firstSegment === 'blog' || ($homepage_mode === 'static_page' && $firstSegment === $blog_slug)) {
        $matchedType = 'post';
        $matchedTypeConfig = $postTypes['post'] ?? ['label' => 'Posts', 'singular' => 'Post'];
    }
    
    // If matched a CPT...
    if ($matchedType !== null) {
        $base_url = Router::getBasePath();
        
        // ─────────────────────────────────────────────────────────────────────
        // CASE 1: Nested slug like /portfolio/my-project → Single item
        // ─────────────────────────────────────────────────────────────────────
        if ($secondSegment !== null) {
            try {
                $db = Database::getInstance();
                $post = $db->queryOne(
                    "SELECT * FROM zed_content 
                     WHERE slug = :slug 
                       AND type = :type
                       AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
                     LIMIT 1",
                    ['slug' => $secondSegment, 'type' => $matchedType]
                );
                
                if ($post) {
                    // Parse content
                    $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
                    $blocks = $data['content'] ?? [];
                    $htmlContent = render_blocks($blocks);
                    
                    // Process shortcodes
                    if (function_exists('zed_do_shortcodes')) {
                        $htmlContent = zed_do_shortcodes($htmlContent);
                    }
                    
                    // Template data
                    $post_type = $matchedType;
                    $post_type_label = $matchedTypeConfig['singular'] ?? ucfirst($matchedType);
                    
                    // Single Template Hierarchy: single-{type}.php → single.php → index.php
                    $template = zed_resolve_template_hierarchy($themePath, 'single', $matchedType);
                    
                    ob_start();
                    include $template;
                    $html = ob_get_clean();
                    
                    Router::setHandled($html);
                    return;
                }
            } catch (Exception $e) {
                // Fall through
            }
            
            // Not found - will 404
            return;
        }
        
        // ─────────────────────────────────────────────────────────────────────
        // CASE 2: Archive listing like /portfolio → List all portfolio items
        // ─────────────────────────────────────────────────────────────────────
        
        // Pagination
        $page_num = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page_num - 1) * $posts_per_page;
        
        try {
            $db = Database::getInstance();
            
            // Fetch items of this type
            $posts = $db->query(
                "SELECT * FROM zed_content 
                 WHERE type = :type 
                   AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
                 ORDER BY created_at DESC
                 LIMIT :limit OFFSET :offset",
                ['type' => $matchedType, 'limit' => $posts_per_page, 'offset' => $offset]
            );
            
            // Count total
            $total_results = (int)$db->queryValue(
                "SELECT COUNT(*) FROM zed_content 
                 WHERE type = :type 
                   AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'",
                ['type' => $matchedType]
            );
            
            $total_pages = max(1, ceil($total_results / $posts_per_page));
            
        } catch (Exception $e) {
            $posts = [];
            $total_results = 0;
            $total_pages = 1;
        }
        
        // Template data injection
        $post_type = $matchedType;
        $post_type_label = $matchedTypeConfig['label'] ?? ucfirst($matchedType) . 's';
        $post_type_singular = $matchedTypeConfig['singular'] ?? ucfirst($matchedType);
        $archive_title = $post_type_label;
        $is_archive = true;
        $is_blog = ($matchedType === 'post');
        
        // Archive Template Hierarchy: archive-{type}.php → archive.php → index.php
        $template = zed_resolve_template_hierarchy($themePath, 'archive', $matchedType);
        
        ob_start();
        include $template;
        $html = ob_get_clean();
        
        Router::setHandled($html);
        return;
    }
    
    // Handle preview route: /preview/{id}
    if (str_starts_with($slug, 'preview/')) {
        $id = substr($slug, 8); // Remove 'preview/' prefix
        if (!is_numeric($id)) {
            return;
        }
        
        // Preview requires authentication
        if (!Auth::check()) {
            Router::redirect('/admin/login?redirect=' . urlencode($uri));
        }
        
        try {
            $db = Database::getInstance();
            $post = $db->queryOne(
                "SELECT * FROM zed_content WHERE id = :id LIMIT 1",
                ['id' => (int)$id]
            );
            
            if ($post) {
                // Decode content
                $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
                $blocks = $data['content'] ?? [];
                
                // Render
                $renderedContent = render_blocks($blocks);
                
                // Process shortcodes
                if (function_exists('zed_do_shortcodes')) {
                    $renderedContent = zed_do_shortcodes($renderedContent);
                }
                
                $html = render_page($post, $renderedContent);
                
                Router::setHandled($html);
                return;
            }
        } catch (Exception $e) {
            // Fall through to 404
        }
        
        return;
    }
    
    // =========================================================================
    // SINGLE CONTENT HANDLER (/{slug})
    // =========================================================================
    
    // Try to find content by slug
    try {
        $db = Database::getInstance();
        
        // Only show published content on frontend
        $post = $db->queryOne(
            "SELECT * FROM zed_content 
             WHERE slug = :slug 
               AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             LIMIT 1",
            ['slug' => $slug]
        );
        
        if ($post) {
            // ─────────────────────────────────────────────────────────────────
            // STEP 1: Parse the post data and convert JSON blocks to HTML
            // ─────────────────────────────────────────────────────────────────
            $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
            $blocks = $data['content'] ?? [];
            
            // Render BlockNote JSON to HTML
            $htmlContent = render_blocks($blocks);
            
            // Process shortcodes in the rendered content
            if (function_exists('zed_do_shortcodes')) {
                $htmlContent = zed_do_shortcodes($htmlContent);
            }
            
            // Make base_url available to templates
            $base_url = Router::getBasePath();
            
            // ─────────────────────────────────────────────────────────────────
            // THEME HOOKS: Before/After Content
            // Allow themes to inject dynamic elements (author bios, related posts)
            // ─────────────────────────────────────────────────────────────────
            ob_start();
            Event::trigger('zed_before_content', $post, $data);
            $beforeContent = ob_get_clean();
            
            ob_start();
            Event::trigger('zed_after_content', $post, $data);
            $afterContent = ob_get_clean();
            
            // ─────────────────────────────────────────────────────────────────
            // STEP 2: Resolve Template
            // ─────────────────────────────────────────────────────────────────
            // Check for custom template
            $templateName = $data['template'] ?? 'default';
            $templateFile = null;
            
            if ($templateName !== 'default') {
                // First: Let addons provide the template (Template Library, etc.)
                // The filter receives: template path (or null), template name, post data
                $addonTemplate = Event::filter('zed_resolve_template', null, $templateName, $post);
                
                if ($addonTemplate && file_exists($addonTemplate)) {
                    $templateFile = $addonTemplate;
                }
                // Second: Check theme's templates folder
                elseif (file_exists($themePath . '/templates/' . $templateName . '.php')) {
                    $templateFile = $themePath . '/templates/' . $templateName . '.php';
                }
            }
            
            // Fallback to single.php if no custom template found
            if (!$templateFile) {
                $templateFile = $themePath . '/single.php';
            }
            
            if (file_exists($templateFile)) {
                // Template found - include it
                // Variables available: $post, $htmlContent, $base_url, $beforeContent, $afterContent, $data
                ob_start();
                include $templateFile;
                $html = ob_get_clean();
            } else {
                // ─────────────────────────────────────────────────────────────
                // FALLBACK: No theme template - echo raw content safely
                // ─────────────────────────────────────────────────────────────
                $title = htmlspecialchars($post['title'] ?? 'Untitled');
                $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} — Zed CMS</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; line-height: 1.6; }
        h1 { border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .content img { max-width: 100%; }
        footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; color: #666; }
    </style>
</head>
<body>
    <h1>{$title}</h1>
    <div class="content">{$htmlContent}</div>
    <footer>Powered by Zed CMS</footer>
</body>
</html>
HTML;
            }
            
            // Output and mark as handled
            // Output and mark as handled
            Router::setHandled($html);
            return;
        }
        
    } catch (Exception $e) {
        // Database error - let it fall through to 404
        error_log("Frontend addon error: " . $e->getMessage());
    }
    
    // If we get here, slug wasn't found - let Router handle 404
    
}, 100); // Priority 100 = runs AFTER admin_addon (priority 10)

// =============================================================================
// SEO Head Event - Inject metadata from settings
// =============================================================================

/**
 * Output <head> metadata for frontend pages
 * Called by themes via: Event::trigger('zed_head');
 */
Event::on('zed_head', function(): void {
    $siteName = htmlspecialchars(zed_get_site_name());
    $tagline = htmlspecialchars(zed_get_site_tagline());
    $description = htmlspecialchars(zed_get_meta_description());
    $noindex = zed_is_noindex();
    
    // Output meta tags
    echo "\n    <!-- Zed CMS SEO -->\n";
    
    // Site metadata
    echo "    <meta name=\"generator\" content=\"Zed CMS 1.5.0\">\n";
    
    // Description
    if (!empty($description)) {
        echo "    <meta name=\"description\" content=\"{$description}\">\n";
    }
    
    // Noindex if discourage search engines is enabled
    if ($noindex) {
        echo "    <meta name=\"robots\" content=\"noindex, nofollow\">\n";
    }
    
    // Open Graph basics
    echo "    <meta property=\"og:site_name\" content=\"{$siteName}\">\n";
    if (!empty($tagline)) {
        echo "    <meta property=\"og:description\" content=\"{$tagline}\">\n";
    }
    
    // Social sharing image
    $socialImage = zed_get_option('social_sharing_image', '');
    if (!empty($socialImage)) {
        $socialImage = htmlspecialchars($socialImage);
        echo "    <meta property=\"og:image\" content=\"{$socialImage}\">\n";
        echo "    <meta name=\"twitter:image\" content=\"{$socialImage}\">\n";
    }
    
    echo "    <!-- /Zed CMS SEO -->\n";
}, 10);

/**
 * Helper to generate page title with site name
 * Usage in theme: echo zed_page_title('My Page');
 */
function zed_page_title(string $pageTitle = ''): string
{
    $siteName = zed_get_site_name();
    
    if (empty($pageTitle)) {
        $tagline = zed_get_site_tagline();
        return $siteName . ($tagline ? ' — ' . $tagline : '');
    }
    
    return $pageTitle . ' — ' . $siteName;
}

// =============================================================================
// THEME PARTS SYSTEM
// =============================================================================

/**
 * Get the path to a theme part file
 * 
 * Looks for files in:
 * 1. Active theme's /parts/ directory
 * 2. Falls back to default parts if not found
 * 
 * @param string $part Part name (e.g., 'head', 'header', 'footer', 'sidebar')
 * @return string|null Full path to part file or null if not found
 */
function zed_get_theme_part(string $part): ?string
{
    $themePath = ZED_ACTIVE_THEME_PATH ?? '';
    
    if (empty($themePath)) {
        return null;
    }
    
    // Try parts directory first (preferred)
    $partFile = $themePath . '/parts/' . $part . '.php';
    if (file_exists($partFile)) {
        return $partFile;
    }
    
    // Try root directory for backwards compatibility
    $rootFile = $themePath . '/' . $part . '.php';
    if (file_exists($rootFile)) {
        return $rootFile;
    }
    
    return null;
}

/**
 * Include a theme part file
 * 
 * Safely includes a theme part with optional variable extraction.
 * This is the recommended way to include theme partials in templates.
 * 
 * @param string $part Part name (e.g., 'head', 'header', 'footer')
 * @param array $vars Optional variables to make available in the part
 * @return bool True if part was included, false if not found
 * 
 * @example
 *   // Include header with custom nav style
 *   zed_include_theme_part('header', ['header_style' => 'transparent']);
 *   
 *   // Include footer
 *   zed_include_theme_part('footer', ['footer_style' => 'dark']);
 */
function zed_include_theme_part(string $part, array $vars = []): bool
{
    $partFile = zed_get_theme_part($part);
    
    if ($partFile === null) {
        return false;
    }
    
    // Extract variables to make them available in the part
    if (!empty($vars)) {
        extract($vars, EXTR_SKIP);
    }
    
    // Make common variables available
    $base_url = Router::getBasePath();
    $site_name = zed_get_site_name();
    
    include $partFile;
    return true;
}

/**
 * Get the active theme's directory path
 * 
 * @return string Theme directory path
 */
function zed_get_theme_path(): string
{
    return ZED_ACTIVE_THEME_PATH ?? '';
}

/**
 * Check if a theme part exists
 * 
 * @param string $part Part name
 * @return bool True if part exists
 */
function zed_theme_part_exists(string $part): bool
{
    return zed_get_theme_part($part) !== null;
}

/**
 * Get the Tailwind CSS CDN script tag
 * 
 * Returns a properly configured Tailwind CDN include with the theme's colors.
 * Use this in templates to ensure consistent Tailwind styling.
 * 
 * @param array $extraColors Additional colors to add to config
 * @return string HTML script tags for Tailwind
 */
function zed_tailwind_cdn(array $extraColors = []): string
{
    $brand = zed_theme_option('brand_color', '#6366f1');
    $brandDark = zed_theme_option('brand_color_dark', '#4f46e5');
    
    $colors = array_merge([
        'brand' => $brand,
        'brand-dark' => $brandDark,
    ], $extraColors);
    
    $colorsJson = json_encode($colors);
    
    return <<<HTML
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {$colorsJson},
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        }
    </script>
HTML;
}

/**
 * Get Google Fonts link tags for the theme
 * 
 * @param array $fonts Font families to load (default: Inter and Material Symbols)
 * @return string HTML link tags
 */
function zed_google_fonts(array $fonts = []): string
{
    if (empty($fonts)) {
        $fonts = [
            'Inter:wght@400;500;600;700;800',
        ];
    }
    
    $fontParam = implode('&family=', $fonts);
    
    return <<<HTML
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={$fontParam}&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400" rel="stylesheet">
HTML;
}

