<?php

declare(strict_types=1);

/**
 * Zed CMS Entry Point
 * 
 * This file does ONLY four things:
 * 1. Loads the configuration
 * 2. Autoloads classes in /core
 * 3. Loads all addons
 * 4. Initializes the App class
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

// 2. Autoload classes in /core (PSR-4 style autoloader)
spl_autoload_register(function (string $class): void {
    // Convert namespace separator to directory separator
    // Core\App -> core/App.php
    $prefix = 'Core\\';
    $baseDir = __DIR__ . '/core/';

    // Check if the class uses the Core namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relativeClass = substr($class, $len);

    // Build the file path
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    // Require the file if it exists
    if (file_exists($file)) {
        require $file;
    }
});

// 3. Load all addons from content/addons
$addonsDir = __DIR__ . '/content/addons';

/**
 * ==========================================================================
 * SYSTEM ADDONS CONFIGURATION
 * ==========================================================================
 * 
 * System addons are core to Zed CMS functionality and cannot be disabled.
 * They are loaded FIRST to ensure proper event priority and are protected
 * from accidental removal.
 * 
 * FUTURE ADDON MANAGER:
 * ---------------------
 * When implementing the Addon Manager UI, these system addons should be:
 * 
 * 1. VISIBLE in the addons list (so users know they exist)
 * 2. Rendered with a LOCKED state:
 *    - Display a "System" badge instead of version
 *    - Disable button should be REMOVED or GREYED OUT
 *    - Delete button should be HIDDEN completely
 *    - Show tooltip: "System addon - required for core functionality"
 * 
 * 3. Load order matters:
 *    - admin_addon.php (priority 10) - handles /admin/* routes
 *    - frontend_addon.php (priority 100) - handles /{slug} routes as fallback
 * 
 * To add a new system addon:
 *    1. Add filename to $system_addons array below
 *    2. Add require_once in the "Force Load" section
 *    3. Update the Addons Manager UI to recognize the new addon
 * 
 * ==========================================================================
 */

// Define protected system addons (cannot be disabled via UI)
$system_addons = [
    'admin_addon.php',      // Core admin panel functionality
    'frontend_addon.php',   // Public content rendering
];

// Make system addons globally accessible for Addon Manager
define('ZERO_SYSTEM_ADDONS', $system_addons);

if (is_dir($addonsDir)) {
    // ─────────────────────────────────────────────────────────────────────
    // PHASE 1: Force load system addons FIRST (in defined order)
    // ─────────────────────────────────────────────────────────────────────
    foreach ($system_addons as $systemAddon) {
        $systemAddonPath = $addonsDir . '/' . $systemAddon;
        if (file_exists($systemAddonPath)) {
            require_once $systemAddonPath;
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
    // Load single-file addons: addons/*.php
    // ─────────────────────────────────────────────────────────────────────
    foreach (glob($addonsDir . '/*.php') as $addonFile) {
        $addonBasename = basename($addonFile);
        
        // Skip system addons - they're already loaded above
        if (in_array($addonBasename, $system_addons, true)) {
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
    foreach (glob($addonsDir . '/*/addon.php') as $addonFile) {
        $folderName = basename(dirname($addonFile));
        
        // Skip if folder name matches a system addon (without .php)
        $folderAsPHP = $folderName . '.php';
        if (in_array($folderAsPHP, $system_addons, true)) {
            continue;
        }
        
        // If we have an active list, only load addons in that list (using folder name as identifier)
        if ($activeAddonsList !== null && !in_array($folderName, $activeAddonsList, true)) {
            continue;
        }
        
        require_once $addonFile;
    }
}

// 4. Initialize and run the App
$app = new \Core\App($config);
$app->run();

