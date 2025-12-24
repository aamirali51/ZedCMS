# Addon Development

Addons are powerful mechanisms to extend Zed CMS. They can register routes, admin pages, listen to events, or modify content.

## The Basic Addon

Create a PHP file in `content/addons/`. E.g., `my_custom_addon.php`.

This file is **automatically loaded** by `index.php` on every request.

```php
<?php
/**
 * My Custom Addon
 * Description: Adds a "Hello" message to the footer.
 */

use Core\Event;

// Hook into the footer
Event::on('zed_footer', function() {
    echo '<p style="text-align:center">Powered by My Custom Addon</p>';
});
```

## Creating Admin Pages (NEW in v3.0.1)

Zed CMS provides two powerful APIs for adding admin functionality:

### Method 1: Admin Menu Registration API (Recommended)

The easiest way to add admin pages. Automatically handles routing, permissions, and layout.

```php
<?php
/**
 * My Admin Addon
 */

// Register a top-level menu
zed_register_admin_menu([
    'id' => 'my_addon',
    'title' => 'My Addon',
    'icon' => 'settings',           // Material Symbols icon
    'capability' => 'manage_options',
    'position' => 55,                // Lower = higher in menu
    'badge' => '3',                  // Optional notification badge
    'callback' => function() {
        ?>
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-4">My Addon Dashboard</h1>
            <p>This page was created without modifying core files!</p>
        </div>
        <?php
    }
]);

// Register a submenu
zed_register_admin_submenu('my_addon', [
    'id' => 'my_addon_settings',
    'title' => 'Settings',
    'capability' => 'manage_options',
    'callback' => function() {
        echo '<h1>Addon Settings</h1>';
    }
]);
```

**Features:**
- ✅ Automatic route registration (`/admin/my_addon`)
- ✅ Automatic permission checks
- ✅ Automatic admin layout wrapping
- ✅ Menu appears in sidebar automatically
- ✅ Auto-hides when addon is disabled

### Method 2: Route Registration API

For more control or non-menu routes:

```php
// Basic route
zed_register_route([
    'path' => '/admin/my-custom-page',
    'capability' => 'manage_options',
    'callback' => function() {
        return '<h1>My Custom Page</h1>';
    }
]);

// Pattern matching
zed_register_route([
    'path' => '/admin/reports/{type}',
    'capability' => 'view_reports',
    'callback' => function($request, $uri, $params) {
        $type = $params['type'];  // Extracted from URL
        return "<h1>Report: {$type}</h1>";
    }
]);

// API endpoint (no layout)
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

### Method 3: Manual Event Listener (Legacy)

For complex routing logic:

```php
use Core\Event;
use Core\Router;
use Core\Auth;

Event::on('route_request', function($request) {
    if ($request['uri'] === '/admin/my-page') {
        
        // Security Check
        if (!Auth::check()) Router::redirect('/admin/login');
        
        // Render
        echo "<h1>My Admin Page</h1>";
        
        // Stop the router from looking further
        Router::setHandled();
    }
}, priority: 10);
```

## Custom Capabilities

Register custom permissions for your addon:

```php
zed_register_capabilities([
    'manage_my_addon' => 'Manage My Addon',
    'view_my_addon_logs' => 'View Addon Logs',
    'export_my_addon_data' => 'Export Addon Data',
]);

// Use in your code
if (zed_current_user_can('manage_my_addon')) {
    // User has permission
}
```

## Best Practices

1. **Use the Menu API** for admin pages - it's the easiest and most maintainable
2. **Use the Route API** for custom routes or API endpoints
3. **Always check permissions** using capabilities
4. **Never modify core files** - use the registration APIs
5. **Track your addon's state** - menus auto-hide when addon is disabled
