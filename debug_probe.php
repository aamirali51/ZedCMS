<?php
/**
 * Zed CMS Debug Probe v1.0
 * 
 * A comprehensive single-file debugging script for deep system introspection.
 * Place in the root directory and access via browser.
 * 
 * WARNING: Delete this file in production environments!
 * 
 * @package ZedCMS\Debug
 * @version 1.0.0
 */

declare(strict_types=1);

// ============================================================================
// CONFIGURATION & SECURITY
// ============================================================================

// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Error handling - capture everything
error_reporting(E_ALL);
ini_set('display_errors', '0'); // We'll display our own
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Start output buffering for clean error handling
ob_start();

// Track diagnostics
$diagnostics = [
    'environment' => [],
    'database' => [],
    'routing' => [],
    'events' => [],
    'addons' => [],
    'errors' => [],
    'started_at' => microtime(true),
];

// ============================================================================
// 1. BOOTSTRAPPING (Copy from index.php)
// ============================================================================

$basePath = __DIR__;

try {
    // Load configuration
    if (!file_exists($basePath . '/config.php')) {
        throw new Exception('config.php not found. Is Zed CMS installed?');
    }
    
    $config = require $basePath . '/config.php';
    $diagnostics['environment']['config_loaded'] = true;
    
    // Core autoloader (PSR-4 style for Core namespace)
    spl_autoload_register(function (string $class): void {
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
    
    // Addons autoloader (PSR-4 style for Addons namespace)
    spl_autoload_register(function (string $class): void {
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
        
        $addonName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $parts[0]));
        $className = implode('/', array_slice($parts, 1));
        
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
    
    // Admin namespace autoloader
    spl_autoload_register(function (string $class): void {
        $prefix = 'Admin\\';
        $baseDir = __DIR__ . '/content/addons/_system/admin/';
        
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
    
    $diagnostics['environment']['autoloader_registered'] = true;
    
} catch (Throwable $e) {
    $diagnostics['errors'][] = ['phase' => 'Bootstrap', 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
}

// ============================================================================
// 2. ENVIRONMENT DIAGNOSTICS
// ============================================================================

try {
    $diagnostics['environment']['php_version'] = PHP_VERSION;
    $diagnostics['environment']['php_version_ok'] = version_compare(PHP_VERSION, '8.2.0', '>=');
    
    // Required extensions
    $requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'openssl'];
    $diagnostics['environment']['extensions'] = [];
    foreach ($requiredExtensions as $ext) {
        $diagnostics['environment']['extensions'][$ext] = extension_loaded($ext);
    }
    
    // Optional extensions
    $optionalExtensions = ['gd', 'imagick', 'curl', 'zip'];
    $diagnostics['environment']['optional_extensions'] = [];
    foreach ($optionalExtensions as $ext) {
        $diagnostics['environment']['optional_extensions'][$ext] = extension_loaded($ext);
    }
    
    // Directory permissions
    $checkDirs = [
        'uploads' => $basePath . '/content/uploads',
        'cache' => $basePath . '/content/cache',
        'themes' => $basePath . '/content/themes',
        'addons' => $basePath . '/content/addons',
    ];
    
    $diagnostics['environment']['directories'] = [];
    foreach ($checkDirs as $name => $path) {
        $exists = is_dir($path);
        $writable = $exists && is_writable($path);
        $diagnostics['environment']['directories'][$name] = [
            'path' => $path,
            'exists' => $exists,
            'writable' => $writable,
        ];
    }
    
    // Memory & limits
    $diagnostics['environment']['memory_limit'] = ini_get('memory_limit');
    $diagnostics['environment']['max_execution_time'] = ini_get('max_execution_time');
    $diagnostics['environment']['upload_max_filesize'] = ini_get('upload_max_filesize');
    $diagnostics['environment']['post_max_size'] = ini_get('post_max_size');
    
} catch (Throwable $e) {
    $diagnostics['errors'][] = ['phase' => 'Environment', 'message' => $e->getMessage()];
}

// ============================================================================
// 3. DATABASE DIAGNOSTICS
// ============================================================================

$db = null;
try {
    if (class_exists('Core\\Database')) {
        // Initialize database with config if available
        if (isset($config['db']) && is_array($config['db'])) {
            Core\Database::setConfig($config['db']);
        } elseif (isset($config['database']) && is_array($config['database'])) {
            Core\Database::setConfig($config['database']);
        }
        
        $db = Core\Database::getInstance();
        $diagnostics['database']['connected'] = true;
        
        // Get database version
        $version = $db->queryValue("SELECT VERSION()");
        $diagnostics['database']['version'] = $version;
        
        // List all tables
        $tables = $db->query("SHOW TABLES");
        $tableNames = array_map(fn($t) => array_values($t)[0], $tables);
        $diagnostics['database']['tables'] = $tableNames;
        
        // Row counts for key tables
        $keyTables = ['zed_content', 'zed_options', 'users', 'zed_media', 'zed_categories'];
        $diagnostics['database']['row_counts'] = [];
        
        foreach ($keyTables as $table) {
            if (in_array($table, $tableNames)) {
                $count = $db->queryValue("SELECT COUNT(*) FROM `{$table}`");
                $diagnostics['database']['row_counts'][$table] = (int)$count;
            } else {
                $diagnostics['database']['row_counts'][$table] = 'TABLE NOT FOUND';
            }
        }
        
        // Check database encoding
        $charsetInfo = $db->queryOne("SELECT @@character_set_database AS charset, @@collation_database AS collation");
        $diagnostics['database']['charset'] = $charsetInfo['charset'] ?? 'unknown';
        $diagnostics['database']['collation'] = $charsetInfo['collation'] ?? 'unknown';
        
    } else {
        $diagnostics['database']['connected'] = false;
        $diagnostics['database']['error'] = 'Core\\Database class not found';
    }
} catch (Throwable $e) {
    $diagnostics['database']['connected'] = false;
    $diagnostics['database']['error'] = $e->getMessage();
}

// ============================================================================
// 4. ROUTING DIAGNOSTICS
// ============================================================================

try {
    // Detect current request
    $diagnostics['routing']['detected_uri'] = $_SERVER['REQUEST_URI'] ?? '/';
    $diagnostics['routing']['detected_method'] = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $diagnostics['routing']['script_name'] = $_SERVER['SCRIPT_NAME'] ?? '';
    
    if (class_exists('Core\\Router')) {
        $diagnostics['routing']['router_loaded'] = true;
        $diagnostics['routing']['base_path'] = Core\Router::getBasePath();
        $diagnostics['routing']['normalized_uri'] = Core\Router::normalizeUri($_SERVER['REQUEST_URI'] ?? '/');
        
        // The Router is event-driven with no stored routes - explain this
        $diagnostics['routing']['architecture'] = 'Event-Driven (no static route table)';
        $diagnostics['routing']['explanation'] = 'Routes are claimed via route_request event listeners';
        
    } else {
        $diagnostics['routing']['router_loaded'] = false;
    }
    
} catch (Throwable $e) {
    $diagnostics['errors'][] = ['phase' => 'Routing', 'message' => $e->getMessage()];
}

// ============================================================================
// 5. EVENT SYSTEM DIAGNOSTICS (Using Reflection)
// ============================================================================

try {
    if (class_exists('Core\\Event')) {
        $diagnostics['events']['event_loaded'] = true;
        
        // Use Reflection to access private static $listeners
        $reflection = new ReflectionClass('Core\\Event');
        
        // Get $listeners
        $listenersProperty = $reflection->getProperty('listeners');
        $listenersProperty->setAccessible(true);
        $listeners = $listenersProperty->getValue();
        
        // Get $scopedListeners
        $scopedProperty = $reflection->getProperty('scopedListeners');
        $scopedProperty->setAccessible(true);
        $scopedListeners = $scopedProperty->getValue();
        
        $diagnostics['events']['total_events'] = count($listeners);
        $diagnostics['events']['total_scoped_events'] = count($scopedListeners);
        
        // Build detailed event map
        $diagnostics['events']['registered'] = [];
        
        foreach ($listeners as $eventName => $priorities) {
            $eventEntry = [
                'name' => $eventName,
                'listeners' => [],
            ];
            
            foreach ($priorities as $priority => $callbacks) {
                foreach ($callbacks as $callback) {
                    $callbackName = 'unknown';
                    
                    if (is_string($callback)) {
                        $callbackName = $callback;
                    } elseif (is_array($callback)) {
                        if (is_object($callback[0])) {
                            $callbackName = get_class($callback[0]) . '::' . $callback[1];
                        } else {
                            $callbackName = $callback[0] . '::' . $callback[1];
                        }
                    } elseif ($callback instanceof Closure) {
                        $ref = new ReflectionFunction($callback);
                        $file = basename($ref->getFileName());
                        $line = $ref->getStartLine();
                        $callbackName = "Closure@{$file}:{$line}";
                    }
                    
                    $eventEntry['listeners'][] = [
                        'priority' => $priority,
                        'callback' => $callbackName,
                    ];
                }
            }
            
            $diagnostics['events']['registered'][] = $eventEntry;
        }
        
        // Sort by event name
        usort($diagnostics['events']['registered'], fn($a, $b) => strcmp($a['name'], $b['name']));
        
    } else {
        $diagnostics['events']['event_loaded'] = false;
    }
    
} catch (Throwable $e) {
    $diagnostics['errors'][] = ['phase' => 'Events', 'message' => $e->getMessage()];
}

// ============================================================================
// 6. ADDONS DIAGNOSTICS
// ============================================================================

try {
    $addonsPath = $basePath . '/content/addons';
    $diagnostics['addons']['path'] = $addonsPath;
    
    // Get active addons from database
    $activeAddons = [];
    if ($db) {
        $activeAddonsJson = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_addons'");
        if ($activeAddonsJson) {
            $activeAddons = json_decode($activeAddonsJson, true) ?? [];
        }
    }
    $diagnostics['addons']['active_from_db'] = $activeAddons;
    
    // Scan filesystem for addons
    $foundAddons = [];
    if (is_dir($addonsPath)) {
        $items = scandir($addonsPath);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            
            $itemPath = $addonsPath . '/' . $item;
            
            // Skip _system (core modules)
            if ($item === '_system') {
                $foundAddons['_system'] = [
                    'type' => 'system',
                    'path' => $itemPath,
                    'active' => true,
                ];
                continue;
            }
            
            // Single file addon
            if (is_file($itemPath) && pathinfo($item, PATHINFO_EXTENSION) === 'php') {
                $addonName = pathinfo($item, PATHINFO_FILENAME);
                $foundAddons[$addonName] = [
                    'type' => 'single_file',
                    'path' => $itemPath,
                    'active' => in_array($addonName, $activeAddons),
                ];
            }
            
            // Folder addon with addon.php
            if (is_dir($itemPath) && file_exists($itemPath . '/addon.php')) {
                $foundAddons[$item] = [
                    'type' => 'folder',
                    'path' => $itemPath,
                    'active' => in_array($item, $activeAddons),
                    'has_config' => file_exists($itemPath . '/config.php'),
                    'has_src' => is_dir($itemPath . '/src'),
                ];
            }
        }
    }
    
    $diagnostics['addons']['found'] = $foundAddons;
    $diagnostics['addons']['total_found'] = count($foundAddons);
    $diagnostics['addons']['total_active'] = count(array_filter($foundAddons, fn($a) => $a['active']));
    
} catch (Throwable $e) {
    $diagnostics['errors'][] = ['phase' => 'Addons', 'message' => $e->getMessage()];
}

// ============================================================================
// 7. API/SECURITY HELPERS CHECK
// ============================================================================

try {
    $diagnostics['security']['nonce_function_exists'] = function_exists('zed_verify_nonce');
    $diagnostics['security']['create_nonce_exists'] = function_exists('zed_create_nonce');
    $diagnostics['security']['current_user_can_exists'] = function_exists('zed_current_user_can');
    $diagnostics['security']['auth_check_exists'] = class_exists('Core\\Auth') && method_exists('Core\\Auth', 'check');
    
    // Check if user is logged in
    if (class_exists('Core\\Auth')) {
        $diagnostics['security']['is_logged_in'] = Core\Auth::check();
        if (Core\Auth::check()) {
            $user = Core\Auth::user();
            $diagnostics['security']['logged_in_user'] = $user['email'] ?? $user['username'] ?? 'unknown';
        }
    }
    
} catch (Throwable $e) {
    $diagnostics['errors'][] = ['phase' => 'Security', 'message' => $e->getMessage()];
}

// ============================================================================
// 8. SIMULATE REQUEST FORM HANDLER
// ============================================================================

$simulationResult = null;
if (isset($_POST['simulate_uri'])) {
    try {
        $testUri = $_POST['simulate_uri'];
        $testMethod = $_POST['simulate_method'] ?? 'GET';
        
        if (class_exists('Core\\Router') && class_exists('Core\\Event')) {
            // Check which events would be triggered
            $listeners = Core\Event::getListeners('route_request');
            $listenerCount = 0;
            foreach ($listeners as $priority => $callbacks) {
                $listenerCount += count($callbacks);
            }
            
            $simulationResult = [
                'uri' => $testUri,
                'method' => $testMethod,
                'normalized_uri' => Core\Router::normalizeUri($testUri),
                'segments' => Core\Router::getSegments($testUri),
                'route_request_listeners' => $listenerCount,
                'base_path' => Core\Router::getBasePath(),
                'full_url' => Core\Router::url($testUri),
            ];
        }
    } catch (Throwable $e) {
        $simulationResult = ['error' => $e->getMessage()];
    }
}

// ============================================================================
// FINAL TIMING
// ============================================================================

$diagnostics['execution_time_ms'] = round((microtime(true) - $diagnostics['started_at']) * 1000, 2);

// ============================================================================
// HTML OUTPUT
// ============================================================================

ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zed CMS Debug Probe</title>
    <style>
        :root {
            --bg: #0f172a;
            --bg-card: #1e293b;
            --bg-card-alt: #334155;
            --text: #e2e8f0;
            --text-muted: #94a3b8;
            --success: #22c55e;
            --error: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --border: #475569;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'SF Mono', 'Consolas', 'Monaco', monospace;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            padding: 20px;
            font-size: 14px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }
        
        h1 {
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        h1 span {
            font-size: 12px;
            background: var(--info);
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
        }
        
        .meta {
            color: var(--text-muted);
            font-size: 12px;
        }
        
        details {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-bottom: 16px;
            overflow: hidden;
        }
        
        summary {
            padding: 16px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 16px;
            user-select: none;
        }
        
        summary:hover {
            background: var(--bg-card-alt);
        }
        
        summary::marker {
            color: var(--info);
        }
        
        .content {
            padding: 20px;
            border-top: 1px solid var(--border);
            background: var(--bg);
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-ok { background: rgba(34, 197, 94, 0.2); color: var(--success); }
        .badge-error { background: rgba(239, 68, 68, 0.2); color: var(--error); }
        .badge-warning { background: rgba(245, 158, 11, 0.2); color: var(--warning); }
        .badge-info { background: rgba(59, 130, 246, 0.2); color: var(--info); }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        th, td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        th {
            background: var(--bg-card);
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        
        tr:hover td {
            background: var(--bg-card);
        }
        
        .status-ok { color: var(--success); }
        .status-error { color: var(--error); }
        .status-warning { color: var(--warning); }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .stat-card {
            background: var(--bg-card);
            padding: 16px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }
        
        .stat-card .label {
            color: var(--text-muted);
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        
        .stat-card .value {
            font-size: 18px;
            font-weight: 600;
        }
        
        form {
            background: var(--bg-card);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }
        
        .form-row {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        input, select {
            flex: 1;
            padding: 10px 14px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text);
            font-family: inherit;
            font-size: 14px;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--info);
        }
        
        button {
            padding: 10px 20px;
            background: var(--info);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-family: inherit;
        }
        
        button:hover {
            background: #2563eb;
        }
        
        .result-box {
            background: var(--bg);
            padding: 16px;
            border-radius: 6px;
            margin-top: 16px;
            border: 1px solid var(--border);
        }
        
        pre {
            overflow-x: auto;
            font-size: 12px;
            line-height: 1.5;
        }
        
        .event-group {
            margin-bottom: 16px;
        }
        
        .event-name {
            font-weight: 600;
            color: var(--info);
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .listener-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding-left: 16px;
        }
        
        .listener-item {
            font-size: 12px;
            color: var(--text-muted);
        }
        
        .listener-item .priority {
            color: var(--warning);
            margin-right: 8px;
        }
        
        .warning-banner {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--error);
            color: var(--error);
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>
                🔬 Zed CMS Debug Probe
                <span>v1.0</span>
            </h1>
            <div class="meta">
                Execution time: <?= $diagnostics['execution_time_ms'] ?>ms | 
                <?= date('Y-m-d H:i:s') ?>
            </div>
        </header>
        
        <div class="warning-banner">
            ⚠️ DELETE THIS FILE IN PRODUCTION! It exposes sensitive system information.
        </div>
        
        <?php if (!empty($diagnostics['errors'])): ?>
        <details open>
            <summary>
                <span class="badge badge-error">ERRORS</span>
                <?= count($diagnostics['errors']) ?> Error(s) Detected
            </summary>
            <div class="content">
                <table>
                    <tr><th>Phase</th><th>Message</th><th>Location</th></tr>
                    <?php foreach ($diagnostics['errors'] as $error): ?>
                    <tr>
                        <td class="status-error"><?= htmlspecialchars($error['phase']) ?></td>
                        <td><?= htmlspecialchars($error['message']) ?></td>
                        <td><?= isset($error['file']) ? htmlspecialchars(basename($error['file']) . ':' . $error['line']) : '-' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </details>
        <?php endif; ?>
        
        <!-- Environment -->
        <details open>
            <summary>
                <span class="badge <?= ($diagnostics['environment']['php_version_ok'] ?? false) ? 'badge-ok' : 'badge-error' ?>">
                    <?= ($diagnostics['environment']['php_version_ok'] ?? false) ? 'OK' : 'ISSUE' ?>
                </span>
                Environment
            </summary>
            <div class="content">
                <div class="grid">
                    <div class="stat-card">
                        <div class="label">PHP Version</div>
                        <div class="value <?= ($diagnostics['environment']['php_version_ok'] ?? false) ? 'status-ok' : 'status-error' ?>">
                            <?= $diagnostics['environment']['php_version'] ?? 'Unknown' ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Memory Limit</div>
                        <div class="value"><?= $diagnostics['environment']['memory_limit'] ?? '-' ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Upload Max</div>
                        <div class="value"><?= $diagnostics['environment']['upload_max_filesize'] ?? '-' ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Max Exec Time</div>
                        <div class="value"><?= $diagnostics['environment']['max_execution_time'] ?? '-' ?>s</div>
                    </div>
                </div>
                
                <h4 style="margin: 20px 0 10px; color: var(--text-muted);">Required Extensions</h4>
                <table>
                    <tr><th>Extension</th><th>Status</th></tr>
                    <?php foreach ($diagnostics['environment']['extensions'] ?? [] as $ext => $loaded): ?>
                    <tr>
                        <td><?= $ext ?></td>
                        <td class="<?= $loaded ? 'status-ok' : 'status-error' ?>"><?= $loaded ? '✓ Loaded' : '✗ Missing' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                
                <h4 style="margin: 20px 0 10px; color: var(--text-muted);">Directory Permissions</h4>
                <table>
                    <tr><th>Directory</th><th>Exists</th><th>Writable</th></tr>
                    <?php foreach ($diagnostics['environment']['directories'] ?? [] as $name => $info): ?>
                    <tr>
                        <td><?= $name ?></td>
                        <td class="<?= $info['exists'] ? 'status-ok' : 'status-error' ?>"><?= $info['exists'] ? '✓' : '✗' ?></td>
                        <td class="<?= $info['writable'] ? 'status-ok' : 'status-error' ?>"><?= $info['writable'] ? '✓' : '✗' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </details>
        
        <!-- Database -->
        <details>
            <summary>
                <span class="badge <?= ($diagnostics['database']['connected'] ?? false) ? 'badge-ok' : 'badge-error' ?>">
                    <?= ($diagnostics['database']['connected'] ?? false) ? 'CONNECTED' : 'ERROR' ?>
                </span>
                Database
            </summary>
            <div class="content">
                <?php if ($diagnostics['database']['connected'] ?? false): ?>
                <div class="grid">
                    <div class="stat-card">
                        <div class="label">Version</div>
                        <div class="value"><?= $diagnostics['database']['version'] ?? '-' ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Charset</div>
                        <div class="value"><?= $diagnostics['database']['charset'] ?? '-' ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Total Tables</div>
                        <div class="value"><?= count($diagnostics['database']['tables'] ?? []) ?></div>
                    </div>
                </div>
                
                <h4 style="margin: 20px 0 10px; color: var(--text-muted);">Key Tables Row Counts</h4>
                <table>
                    <tr><th>Table</th><th>Rows</th></tr>
                    <?php foreach ($diagnostics['database']['row_counts'] ?? [] as $table => $count): ?>
                    <tr>
                        <td><?= $table ?></td>
                        <td class="<?= is_int($count) ? '' : 'status-error' ?>"><?= $count ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                
                <h4 style="margin: 20px 0 10px; color: var(--text-muted);">All Tables</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <?php foreach ($diagnostics['database']['tables'] ?? [] as $table): ?>
                    <span class="badge badge-info"><?= $table ?></span>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="status-error">
                    Database connection failed: <?= htmlspecialchars($diagnostics['database']['error'] ?? 'Unknown error') ?>
                </div>
                <?php endif; ?>
            </div>
        </details>
        
        <!-- Routing -->
        <details>
            <summary>
                <span class="badge <?= ($diagnostics['routing']['router_loaded'] ?? false) ? 'badge-ok' : 'badge-error' ?>">
                    <?= ($diagnostics['routing']['router_loaded'] ?? false) ? 'LOADED' : 'ERROR' ?>
                </span>
                Routing
            </summary>
            <div class="content">
                <div class="grid">
                    <div class="stat-card">
                        <div class="label">Detected URI</div>
                        <div class="value" style="font-size: 14px; word-break: break-all;"><?= htmlspecialchars($diagnostics['routing']['detected_uri'] ?? '-') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Method</div>
                        <div class="value"><?= $diagnostics['routing']['detected_method'] ?? '-' ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Base Path</div>
                        <div class="value"><?= htmlspecialchars($diagnostics['routing']['base_path'] ?? '-') ?: '/' ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Normalized URI</div>
                        <div class="value" style="font-size: 14px;"><?= htmlspecialchars($diagnostics['routing']['normalized_uri'] ?? '-') ?></div>
                    </div>
                </div>
                
                <h4 style="margin: 20px 0 10px; color: var(--text-muted);">Architecture</h4>
                <p style="color: var(--text-muted); margin-bottom: 10px;">
                    <strong><?= $diagnostics['routing']['architecture'] ?? 'Unknown' ?></strong><br/>
                    <?= $diagnostics['routing']['explanation'] ?? '' ?>
                </p>
            </div>
        </details>
        
        <!-- Events -->
        <details>
            <summary>
                <span class="badge badge-info"><?= $diagnostics['events']['total_events'] ?? 0 ?></span>
                Event System (<?= $diagnostics['events']['total_events'] ?? 0 ?> hooks registered)
            </summary>
            <div class="content">
                <?php if (!empty($diagnostics['events']['registered'])): ?>
                    <?php foreach ($diagnostics['events']['registered'] as $event): ?>
                    <div class="event-group">
                        <div class="event-name"><?= htmlspecialchars($event['name']) ?> <span style="color: var(--text-muted);">(<?= count($event['listeners']) ?> listeners)</span></div>
                        <div class="listener-list">
                            <?php foreach ($event['listeners'] as $listener): ?>
                            <div class="listener-item">
                                <span class="priority">P:<?= $listener['priority'] ?></span>
                                <?= htmlspecialchars($listener['callback']) ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: var(--text-muted);">No events registered. This usually means addons haven't been loaded yet.</p>
                <?php endif; ?>
            </div>
        </details>
        
        <!-- Addons -->
        <details>
            <summary>
                <span class="badge badge-info"><?= $diagnostics['addons']['total_active'] ?? 0 ?>/<?= $diagnostics['addons']['total_found'] ?? 0 ?></span>
                Addons (<?= $diagnostics['addons']['total_active'] ?? 0 ?> active of <?= $diagnostics['addons']['total_found'] ?? 0 ?> found)
            </summary>
            <div class="content">
                <table>
                    <tr><th>Addon</th><th>Type</th><th>Active</th><th>Features</th></tr>
                    <?php foreach ($diagnostics['addons']['found'] ?? [] as $name => $info): ?>
                    <tr>
                        <td><?= htmlspecialchars($name) ?></td>
                        <td><span class="badge badge-info"><?= $info['type'] ?></span></td>
                        <td class="<?= $info['active'] ? 'status-ok' : 'status-warning' ?>"><?= $info['active'] ? '✓ Active' : '○ Inactive' ?></td>
                        <td>
                            <?php if ($info['has_config'] ?? false): ?><span class="badge badge-info">config</span><?php endif; ?>
                            <?php if ($info['has_src'] ?? false): ?><span class="badge badge-info">src/</span><?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </details>
        
        <!-- Security -->
        <details>
            <summary>
                <span class="badge <?= ($diagnostics['security']['nonce_function_exists'] ?? false) ? 'badge-ok' : 'badge-warning' ?>">
                    <?= ($diagnostics['security']['nonce_function_exists'] ?? false) ? 'LOADED' : 'PARTIAL' ?>
                </span>
                Security Helpers
            </summary>
            <div class="content">
                <table>
                    <tr><th>Function/Feature</th><th>Status</th></tr>
                    <tr>
                        <td>zed_verify_nonce()</td>
                        <td class="<?= ($diagnostics['security']['nonce_function_exists'] ?? false) ? 'status-ok' : 'status-warning' ?>"><?= ($diagnostics['security']['nonce_function_exists'] ?? false) ? '✓ Available' : '✗ Not loaded' ?></td>
                    </tr>
                    <tr>
                        <td>zed_create_nonce()</td>
                        <td class="<?= ($diagnostics['security']['create_nonce_exists'] ?? false) ? 'status-ok' : 'status-warning' ?>"><?= ($diagnostics['security']['create_nonce_exists'] ?? false) ? '✓ Available' : '✗ Not loaded' ?></td>
                    </tr>
                    <tr>
                        <td>zed_current_user_can()</td>
                        <td class="<?= ($diagnostics['security']['current_user_can_exists'] ?? false) ? 'status-ok' : 'status-warning' ?>"><?= ($diagnostics['security']['current_user_can_exists'] ?? false) ? '✓ Available' : '✗ Not loaded' ?></td>
                    </tr>
                    <tr>
                        <td>Core\Auth::check()</td>
                        <td class="<?= ($diagnostics['security']['auth_check_exists'] ?? false) ? 'status-ok' : 'status-error' ?>"><?= ($diagnostics['security']['auth_check_exists'] ?? false) ? '✓ Available' : '✗ Not loaded' ?></td>
                    </tr>
                    <tr>
                        <td>Logged In User</td>
                        <td><?= ($diagnostics['security']['is_logged_in'] ?? false) ? '<span class="status-ok">✓ ' . htmlspecialchars($diagnostics['security']['logged_in_user'] ?? '') . '</span>' : '<span class="status-warning">Not logged in</span>' ?></td>
                    </tr>
                </table>
            </div>
        </details>
        
        <!-- Simulate Request -->
        <details>
            <summary>
                <span class="badge badge-info">TOOL</span>
                Simulate Request
            </summary>
            <div class="content">
                <form method="POST">
                    <div class="form-row">
                        <input type="text" name="simulate_uri" placeholder="Enter URI (e.g., /admin/content)" value="<?= htmlspecialchars($_POST['simulate_uri'] ?? '') ?>">
                        <select name="simulate_method">
                            <option value="GET" <?= ($_POST['simulate_method'] ?? '') === 'GET' ? 'selected' : '' ?>>GET</option>
                            <option value="POST" <?= ($_POST['simulate_method'] ?? '') === 'POST' ? 'selected' : '' ?>>POST</option>
                        </select>
                        <button type="submit">Test URI</button>
                    </div>
                </form>
                
                <?php if ($simulationResult): ?>
                <div class="result-box">
                    <h4 style="margin-bottom: 10px;">Simulation Result</h4>
                    <pre><?= htmlspecialchars(json_encode($simulationResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) ?></pre>
                </div>
                <?php endif; ?>
            </div>
        </details>
        
        <!-- Raw Data -->
        <details>
            <summary>
                <span class="badge badge-warning">RAW</span>
                Raw Diagnostics Data (JSON)
            </summary>
            <div class="content">
                <pre><?= htmlspecialchars(json_encode($diagnostics, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) ?></pre>
            </div>
        </details>
        
        <footer style="margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border); color: var(--text-muted); text-align: center; font-size: 12px;">
            Zed CMS Debug Probe | PHP <?= PHP_VERSION ?> | <?= php_uname('s') ?> <?= php_uname('r') ?>
        </footer>
    </div>
</body>
</html>
