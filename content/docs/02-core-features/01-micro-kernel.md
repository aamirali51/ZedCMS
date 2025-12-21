# The Micro-Kernel

At the heart of Zed CMS lies the **Micro-Kernel**. It is designed to be as thin as possible, delegating all actual logic to **Addons**.

## The Request Lifecycle

Understanding this flow is crucial for developers.

### 1. The Entry Point (`index.php`)
Every request hits `index.php`. It does **not** contain any HTML. Its job is strictly initialization:
1.  Loads `config.php`.
2.  Registers the PSR-4 Autoloader for the `Core\` namespace.
3.  **Phase 1:** Loads System Addons (`admin_addon.php`, `frontend_addon.php`). These are protected and load first.
4.  **Phase 2:** Loads Component Addons (`wiki_addon.php`, etc.).
5.  Instantiates `Core\App` and calls `run()`.

### 2. The Application (`Core\App`)
The `App` class is a wrapper that sets the stage.
*   **Init:** Connects to the database (Lazy connection pattern).
*   **Hook:** Triggers `app_init`.
*   **Routing:** Calls `Router::dispatch()`.

### 3. The Router (`Core\Router`)
This is where Zed differs from other frameworks. **The router does not have a route map.**

Instead of defining routes like `$router->get('/home', 'HomeController@index')`, we use an **Event-Driven Router**.

1.  Router analyzes the URI (e.g., `/admin/settings`).
2.  It triggers the event `route_request` passing the URI.
3.  **Addons listen to this event.**
4.  If an addon says "I handle /admin/settings!", it executes logic and tells the router to stop.

### Why this architecture?
*   **Decoupling:** The core doesn't know about the Admin Panel or the Frontend. They are just plugins.
*   **Extensibility:** You can replace the entire Admin Panel by simply removing `admin_addon.php` and dropping in your own.
*   **Speed:** No parsing of massive route files (YAML/XML/Annotations). It's just simple string comparison in closures.

## Code Example: How Routing Works

```php
// In Core/Router.php
public static function dispatch() {
    $request = ['uri' => $_SERVER['REQUEST_URI']];
    Event::trigger('route_request', $request);
}

// In content/addons/my_addon.php
Event::on('route_request', function($request) {
    if ($request['uri'] === '/hello') {
        echo "Hello World!";
        Router::setHandled(); // Stop other addons/frontend
    }
});
```
