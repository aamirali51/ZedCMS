<?php
/**
 * Theme Options API
 * 
 * Provides theme settings registration and retrieval.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

use Core\Database;
use Core\Router;
use Core\Event;

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
    $activeAddonsJson = zed_get_option('active_addons', null);
    
    if ($activeAddonsJson === null) {
        // If option is missing, all installed addons are active by default (matches loader logic)
        $activeAddons = [];
        $addonsDir = dirname(__DIR__, 2); // content/addons
        
        // Single files
        foreach (glob($addonsDir . '/*.php') as $file) {
            $activeAddons[] = basename($file);
        }
        
        // Folder addons
        foreach (glob($addonsDir . '/*/addon.php') as $file) {
            $activeAddons[] = basename(dirname($file));
        }
    } else {
        $activeAddons = json_decode($activeAddonsJson, true) ?: [];
    }
    
    // System modules are always active
    $systemModules = ['_system/admin.php', '_system/frontend.php'];
    $allActive = array_merge($systemModules, $activeAddons);
    
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
    $viteManifest = dirname(__DIR__, 3) . "/themes/{$activeTheme}/assets/dist/.vite/manifest.json";
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

/**
 * Load active theme's functions.php during app_init
 * This allows themes to register hooks, post types, settings, AND routes.
 * Uses high priority (100) to ensure admin modules are loaded first.
 */
Event::on('app_init', function(): void {
    // Get active theme from database
    $activeTheme = zed_get_option('active_theme', 'aurora-pro');
    
    // Build path to theme's directory
    $themesDir = dirname(__DIR__, 3) . '/themes';
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
}, 100); // Priority 100 = runs late in app_init, after admin modules loaded
