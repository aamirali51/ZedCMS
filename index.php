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
    // PHASE 2: Load all other addons dynamically (skip system addons)
    // ─────────────────────────────────────────────────────────────────────
    foreach (glob($addonsDir . '/*.php') as $addonFile) {
        $addonBasename = basename($addonFile);
        
        // Skip system addons - they're already loaded above
        if (in_array($addonBasename, $system_addons, true)) {
            continue;
        }
        
        require_once $addonFile;
    }
}

// 4. Initialize and run the App
$app = new \Core\App($config);
$app->run();

