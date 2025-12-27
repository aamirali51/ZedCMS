<?php

declare(strict_types=1);

/**
 * Zed CMS Entry Point
 * 
 * This file does ONLY five things:
 * 1. Loads the configuration
 * 2. Autoloads classes in /core (Core namespace)
 * 3. Autoloads addon classes (Addons namespace)
 * 4. Loads system modules and addons
 * 5. Initializes the App class
 * 
 * @package ZedCMS
 * @version 3.0.0
 */

// 1. Load configuration
if (!file_exists(__DIR__ . '/config.php')) {
    if (file_exists(__DIR__ . '/install.php')) {
        header('Location: install.php');
        exit;
    }
    die('Configuration file (config.php) is missing and installer (install.php) could not be found.');
}

$config = require __DIR__ . '/config.php';

// 2. Autoload classes in /core (PSR-4 style autoloader for Core namespace)
spl_autoload_register(function (string $class): void {
    // Core\App -> core/App.php
    $prefix = 'Core\\';
    $baseDir = __DIR__ . '/core/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 3. Autoload addon classes (PSR-4 style autoloader for Addons namespace)
// Allows addons to define classes like: Addons\ZedSEO\SitemapGenerator
spl_autoload_register(function (string $class): void {
    // Addons\ZedSEO\SitemapGenerator -> content/addons/zed_seo/src/SitemapGenerator.php
    $prefix = 'Addons\\';
    $baseDir = __DIR__ . '/content/addons/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relativeClass = substr($class, $len);
    $parts = explode('\\', $relativeClass);
    
    if (count($parts) < 2) {
        return;
    }
    
    // Convert AddonName to addon_name (PascalCase to snake_case)
    $addonName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $parts[0]));
    $className = implode('/', array_slice($parts, 1));
    
    // Try src/ subdirectory first, then root
    $file = $baseDir . $addonName . '/src/' . $className . '.php';
    if (file_exists($file)) {
        require $file;
        return;
    }
    
    $file = $baseDir . $addonName . '/' . $className . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// 4. Autoload admin controllers (PSR-4 style autoloader for Admin\Controllers namespace)
spl_autoload_register(function (string $class): void {
    // Admin\Controllers\ContentController -> content/addons/_system/admin/controllers/ContentController.php
    $prefix = 'Admin\\Controllers\\';
    $baseDir = __DIR__ . '/content/addons/_system/admin/controllers/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// 5. Load system modules and addons from content/addons
$addonsDir = __DIR__ . '/content/addons';

/**
 * ==========================================================================
 * SYSTEM MODULES CONFIGURATION
 * ==========================================================================
 * 
 * System modules are core to Zed CMS functionality and cannot be disabled.
 * They are loaded FIRST to ensure proper event priority and are protected
 * from accidental removal.
 * 
 * STRUCTURE:
 * -----------
 * content/addons/_system/
 * ├── admin.php      — Admin panel, authentication, RBAC
 * │   └── admin/     — Admin sub-modules (rbac, api, helpers, renderer, routes)
 * └── frontend.php   — Public routing, theming, content rendering
 *     └── frontend/  — Frontend sub-modules (options, post_types, theme_api, etc.)
 * 
 * ADDON MANAGER UI:
 * -----------------
 * When implementing the Addon Manager, system modules should be:
 * 1. VISIBLE with a "System" badge
 * 2. Cannot be disabled or deleted
 * 3. Show tooltip: "System module - required for core functionality"
 * 
 * ==========================================================================
 */

// Define protected system modules (cannot be disabled via UI)
$system_modules = [
    '_system/admin.php',      // Core admin panel functionality
    '_system/frontend.php',   // Public content rendering
];

// Make system modules globally accessible
define('ZED_SYSTEM_MODULES', $system_modules);

// Legacy constant for backward compatibility
define('ZED_SYSTEM_ADDONS', ['admin_addon.php', 'frontend_addon.php']);

if (is_dir($addonsDir)) {
    // ─────────────────────────────────────────────────────────────────────
    // PHASE 1: Load system modules FIRST (in defined order)
    // ─────────────────────────────────────────────────────────────────────
    foreach ($system_modules as $systemModule) {
        $systemModulePath = $addonsDir . '/' . $systemModule;
        if (file_exists($systemModulePath)) {
            require_once $systemModulePath;
        }
    }
    
    // ─────────────────────────────────────────────────────────────────────
    // PHASE 2: Load only ACTIVE addons (respects Addon Manager settings)
    // ─────────────────────────────────────────────────────────────────────
    
    // Fetch active_addons list from database (early DB connection)
    $activeAddonsList = null; // null = load all (backward compatible)
    try {
        $dbConfig = $config['database'] ?? [];
        if (!empty($dbConfig['host']) && !empty($dbConfig['name'])) {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $dbConfig['host'],
                $dbConfig['port'] ?? 3306,
                $dbConfig['name'],
                $dbConfig['charset'] ?? 'utf8mb4'
            );
            $pdo = new PDO($dsn, $dbConfig['user'] ?? '', $dbConfig['password'] ?? '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            
            $stmt = $pdo->prepare("SELECT option_value FROM zed_options WHERE option_name = 'active_addons' LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetchColumn();
            
            if ($result !== false) {
                $decoded = json_decode($result, true);
                if (is_array($decoded)) {
                    $activeAddonsList = $decoded;
                }
            }
        }
    } catch (Exception $e) {
        // If DB fails, load all addons (graceful degradation)
        $activeAddonsList = null;
    }
    
    // ─────────────────────────────────────────────────────────────────────
    // ADDON FILE CACHING (Performance optimization for 100+ addons)
    // ─────────────────────────────────────────────────────────────────────
    // Cache the file list to avoid expensive glob() on every request.
    // Cache is stored in a simple file and auto-invalidates when addons
    // are toggled (see addon toggle API).
    
    $addonCacheFile = $addonsDir . '/.addon_cache.php';
    $addonFiles = null;
    
    // Try to load from cache (production mode)
    if (!($config['app']['debug'] ?? false) && file_exists($addonCacheFile)) {
        $addonFiles = @include $addonCacheFile;
        // Validate cache structure
        if (!is_array($addonFiles) || !isset($addonFiles['single']) || !isset($addonFiles['folder'])) {
            $addonFiles = null;
        }
    }
    
    // Build cache if needed (cache miss or debug mode)
    if ($addonFiles === null) {
        $addonFiles = [
            'single' => glob($addonsDir . '/*.php') ?: [],
            'folder' => glob($addonsDir . '/*/addon.php') ?: [],
            'generated' => time(),
        ];
        
        // Save cache file (only in production mode)
        if (!($config['app']['debug'] ?? false)) {
            $cacheContent = "<?php\n// Auto-generated addon cache - delete to rebuild\nreturn " . var_export($addonFiles, true) . ";\n";
            @file_put_contents($addonCacheFile, $cacheContent, LOCK_EX);
        }
    }
    
    // ─────────────────────────────────────────────────────────────────────
    // Load single-file addons: addons/*.php (excluding _system directory)
    // ─────────────────────────────────────────────────────────────────────
    foreach ($addonFiles['single'] as $addonFile) {
        if (!file_exists($addonFile)) continue; // Cache might be stale
        
        $addonBasename = basename($addonFile);
        
        // Skip legacy system addons (if they still exist during migration)
        if (in_array($addonBasename, ['admin_addon.php', 'frontend_addon.php'], true)) {
            continue;
        }
        
        // Skip system modules - they're already loaded above
        if (str_starts_with($addonBasename, '_system')) {
            continue;
        }
        
        // If we have an active list, only load addons in that list
        if ($activeAddonsList !== null && !in_array($addonBasename, $activeAddonsList, true)) {
            continue;
        }
        
        require_once $addonFile;
    }
    
    // ─────────────────────────────────────────────────────────────────────
    // Load folder-based addons: addons/*/addon.php
    // ─────────────────────────────────────────────────────────────────────
    foreach ($addonFiles['folder'] as $addonFile) {
        if (!file_exists($addonFile)) continue; // Cache might be stale
        
        $folderName = basename(dirname($addonFile));
        
        // Skip _system folder and legacy folders
        if ($folderName === '_system' || in_array($folderName, ['admin', 'frontend'], true)) {
            continue;
        }
        
        // If we have an active list, only load addons in that list (using folder name as identifier)
        if ($activeAddonsList !== null && !in_array($folderName, $activeAddonsList, true)) {
            continue;
        }
        
        require_once $addonFile;
    }
}

// 5. Initialize and run the App
$app = new \Core\App($config);
$app->run();

