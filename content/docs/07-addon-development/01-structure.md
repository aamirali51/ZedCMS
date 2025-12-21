# Addon Development

Addons are powerful mechanisms to extend Zed CMS. They can Register routes, listen to events, or modify content.

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

## Creating Admin Pages

To add a page to the admin panel, you need two things:
1.  **A Route**: Listen for the URL.
2.  **A Menu Item**: (Currently manual, but hook coming soon).

### Step 1: Handle the Route

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
});
```
