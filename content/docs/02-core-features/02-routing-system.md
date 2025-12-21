# The Routing System

Routing in Zed is decentralized. There is no central `routes.php` file.

## How to Register a Route

To add a page, you simply listen for the `route_request` event in an Addon.

### 1. Basic Static Route

```php
Event::on('route_request', function($request) {
    if ($request['uri'] === '/my-page') {
        echo "This is my custom page";
        Router::setHandled(); // IMPORTANT!
    }
});
```

### 2. Dynamic Route (Wildcards)

To match URLs like `/user/123`, use Regex or string parsing.

```php
Event::on('route_request', function($request) {
    // Check if URI starts with /user/
    if (str_starts_with($request['uri'], '/user/')) {
        
        // Extract ID
        $parts = explode('/', $request['uri']);
        $userId = $parts[2] ?? 0;
        
        if (is_numeric($userId)) {
            echo "Profile for User ID: " . $userId;
            Router::setHandled();
        }
    }
});
```

### 3. Handling POST Requests

The `$request` array typically only contains `uri` and `method` (in future versions). For now, use global `$_SERVER`.

```php
Event::on('route_request', function($request) {
    if ($request['uri'] === '/api/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle logic
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Return JSON
        header('Content-Type: application/json');
        echo json_encode(['status' => 'saved']);
        
        Router::setHandled();
    }
});
```

## Router helper Methods

### `Router::redirect($url)`
Immediately stops execution and sends a `Location` header.
```php
Router::redirect('/admin/login');
```

### `Router::setHandled($content = null)`
Marks the request as successful. If `$content` is passed, it echoes it.
```php
Router::setHandled('<h1>Page Content</h1>');
```

### `Router::getBasePath()`
Returns the subdirectory path if Zed is installed in a subfolder (e.g., `/my-site`). **Always** prepend this to links.
```php
echo '<a href="' . Router::getBasePath() . '/about">About</a>';
```

## Priority Logic

Since multiple listeners can subscribe to `route_request`, the order matters.

1.  **System Addons** (load first) get first crack.
2.  **Frontend Fallback** (`frontend_addon.php`) usually runs last and acts as a "catch-all" for slugs.

If you want to override a core route (like `/admin`), you must load your addon *before* `admin_addon.php` (which is hard because system addons force-load first). Instead, you would use a higher priority event listener if supported, or modify the core array.

In standard practice, avoid overriding core `/admin` routes to prevent conflicts.

## Smart Routing (Frontend)

The `frontend_addon.php` implements "Smart Routing" logic to handle the Homepage options in Settings.

### How it decides what to show on `/`:

1.  **Check Settings:** It reads `homepage_mode` from `zed_options`.
2.  **Latest Posts (Default):** Fetches the last 10 posts and renders `index.php`.
3.  **Static Page:**
    *   It reads `page_on_front` ID.
    *   Fetches that specific page from DB.
    *   Renders `page.php` (or `single.php`).

### The Blog Page (`/{blog_slug}`)
If you set a Static Homepage, your posts are moved to a new URL (e.g., `/blog`).
The router automatically checks if the current URI matches the `blog_slug` setting and renders `index.php` there instead.
