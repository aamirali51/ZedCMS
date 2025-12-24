# Admin Menu & Route Registration APIs

## Overview

Zed CMS v3.0.1 introduces two powerful APIs that enable addons to register admin pages and custom routes without modifying core files.

## Admin Menu Registration API

### Basic Usage

```php
zed_register_admin_menu([
    'id' => 'my_addon',
    'title' => 'My Addon',
    'icon' => 'settings',
    'capability' => 'manage_options',
    'position' => 55,
    'badge' => '3',
    'callback' => function() {
        echo '<h1>My Addon Page</h1>';
    }
]);
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | string | Yes | Unique identifier for the menu |
| `title` | string | Yes | Display name in sidebar |
| `icon` | string | No | Material Symbols icon name (default: 'extension') |
| `capability` | string | No | Required permission (default: 'manage_options') |
| `position` | int | No | Sort order (lower = higher, default: 100) |
| `badge` | string | No | Notification badge text |
| `callback` | callable | Yes | Function to render page content |
| `url` | string | No | Custom URL (auto-generated if not provided) |

### Submenus

```php
zed_register_admin_submenu('parent_id', [
    'id' => 'my_submenu',
    'title' => 'Submenu',
    'capability' => 'manage_options',
    'callback' => function() {
        echo '<h1>Submenu Page</h1>';
    }
]);
```

### Custom Capabilities

```php
zed_register_capabilities([
    'manage_my_addon' => 'Manage My Addon',
    'view_logs' => 'View Logs',
]);
```

## Route Registration API

### Basic Route

```php
zed_register_route([
    'path' => '/admin/my-page',
    'callback' => function() {
        return '<h1>My Page</h1>';
    }
]);
```

### Pattern Matching

```php
zed_register_route([
    'path' => '/admin/reports/{type}',
    'callback' => function($request, $uri, $params) {
        $type = $params['type'];
        return "<h1>Report: {$type}</h1>";
    }
]);
```

### API Endpoint

```php
zed_register_route([
    'path' => '/admin/api/my-action',
    'method' => 'POST',
    'wrap_layout' => false,
    'callback' => function() {
        header('Content-Type: application/json');
        return json_encode(['success' => true]);
    }
]);
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `path` | string | Yes | Route path (supports `{param}` syntax) |
| `method` | string\|array | No | HTTP method(s) (default: 'GET') |
| `capability` | string | No | Required permission |
| `callback` | callable | Yes | Function to handle request |
| `wrap_layout` | bool | No | Wrap in admin layout (default: true) |
| `priority` | int | No | Route priority (default: 50) |

## Features

### Automatic Route Registration

When you register a menu, a route is automatically created:

```php
zed_register_admin_menu(['id' => 'my_addon', ...]);
// Automatically creates route: /admin/my_addon
```

### Permission Checks

All registered routes automatically check permissions:

```php
// User must have 'manage_options' capability
zed_register_route([
    'path' => '/admin/settings',
    'capability' => 'manage_options',
    'callback' => ...
]);
```

### Layout Wrapping

Admin pages are automatically wrapped in the admin theme:

```php
// Your callback only needs to return content
'callback' => function() {
    return '<h1>Title</h1><p>Content</p>';
}
// Result: Full admin page with sidebar, header, etc.
```

### Auto-Hide on Disable

When an addon is disabled, its menus automatically disappear from the sidebar.

## Complete Example

```php
<?php
/**
 * Analytics Addon
 */

// Register capabilities
zed_register_capabilities([
    'view_analytics' => 'View Analytics',
    'export_analytics' => 'Export Analytics',
]);

// Register main menu
zed_register_admin_menu([
    'id' => 'analytics',
    'title' => 'Analytics',
    'icon' => 'analytics',
    'capability' => 'view_analytics',
    'position' => 50,
    'badge' => '12',
    'callback' => function() {
        ?>
        <div class="max-w-6xl mx-auto">
            <h1 class="text-2xl font-bold mb-4">Analytics Dashboard</h1>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded shadow">
                    <h3>Page Views</h3>
                    <p class="text-3xl">1,234</p>
                </div>
                <!-- More stats -->
            </div>
        </div>
        <?php
    }
]);

// Register submenu
zed_register_admin_submenu('analytics', [
    'id' => 'analytics_reports',
    'title' => 'Reports',
    'capability' => 'view_analytics',
    'callback' => function() {
        echo '<h1>Reports</h1>';
    }
]);

// Register API endpoint
zed_register_route([
    'path' => '/admin/api/analytics/export',
    'method' => 'POST',
    'capability' => 'export_analytics',
    'wrap_layout' => false,
    'callback' => function() {
        // Export logic
        header('Content-Type: application/json');
        return json_encode(['success' => true, 'file' => 'export.csv']);
    }
]);
```

## Migration from Legacy

### Before (Legacy Event Listener)

```php
Event::on('route_request', function($request) {
    if ($request['uri'] === '/admin/my-page') {
        if (!Auth::check()) Router::redirect('/admin/login');
        if (!zed_current_user_can('manage_options')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        $content = '<h1>My Page</h1>';
        
        ob_start();
        $addon_page_content = $content;
        require $themePath . '/admin-layout.php';
        $output = ob_get_clean();
        
        Router::setHandled($output);
    }
}, 10);
```

### After (Menu API)

```php
zed_register_admin_menu([
    'id' => 'my_page',
    'title' => 'My Page',
    'capability' => 'manage_options',
    'callback' => fn() => '<h1>My Page</h1>'
]);
```

**Result:** 15 lines → 4 lines (73% reduction)
