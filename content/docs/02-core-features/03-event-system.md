# The Event System

Zed CMS implementation of the Mediator Pattern. It allows different parts of the system to communicate without knowing about each other. Similar to WordPress Hooks/Actions, but with modern PHP 8.2+ features and **scoped context matching**.

## Conceptual Overview

*   **Trigger:** "Hey everyone, I just saved a post!"
*   **Listener:** "Oh, I heard a post was saved. I'll send an email notification."

## The `Core\Event` Class

### Basic Syntax

```php
class Event {
    // Actions (do something)
    public static function on(string $event, callable $listener, int $priority = 10);
    public static function trigger(string $event, mixed $payload = null, mixed ...$args);
    
    // Filters (modify data)
    public static function filter(string $event, mixed $value, mixed ...$args): mixed;
    
    // Scoped (context-aware)
    public static function onScoped(string $event, callable $callback, array $context, int $priority = 10);
    public static function triggerScoped(string $event, array $context, mixed $payload = null, mixed ...$args);
}
```

---

## 1. Actions (Global Hooks)

### Registering a Listener

```php
use Core\Event;

// Basic Listener
Event::on('user_login', function($user) {
    error_log("User logged in: " . $user['email']);
});

// Listener with Priority (Lower runs first)
Event::on('zed_head', function() {
    echo '<meta charset="utf-8">';
}, 1); // Runs before default priority (10)
```

### Triggering an Event

```php
// Trigger with data
$user = ['id' => 1, 'email' => 'admin@example.com'];
Event::trigger('user_login', $user);

// Trigger with multiple arguments
Event::trigger('zed_post_saved', $postId, $postData);

// Trigger without data (just a signal)
Event::trigger('zed_footer');
```

---

## 2. Scoped Hooks (Context-Aware)

This solves WordPress's biggest hook flaw—all hooks fire globally. With scoped hooks, listeners only execute when context matches.

### Registering a Scoped Listener

```php
// Only fires on 'product' post type
Event::onScoped('zed_head', function() {
    echo '<link rel="stylesheet" href="product-styles.css">';
}, ['post_type' => 'product']);

// Only fires on single post template for blog posts
Event::onScoped('zed_after_content', function($post) {
    echo '<div class="author-bio">...</div>';
}, ['post_type' => 'post', 'template' => 'single']);
```

### Triggering Scoped Events

```php
// In frontend rendering
Event::triggerScoped('zed_head', [
    'post_type' => $post['type'],
    'template' => 'single',
    'is_home' => false,
], $post);
```

**Behavior:** Regular listeners (via `on()`) ALWAYS fire. Scoped listeners (via `onScoped()`) only fire if ALL context conditions match.

---

## 3. Filters (Modify Data)

Filters allow you to modify a value through a chain of callbacks.

### Registering a Filter

```php
Event::on('zed_template_data', function(array $data): array {
    $data['current_user'] = Auth::user();
    $data['is_premium'] = check_premium();
    return $data; // Must return modified value
});
```

### Applying a Filter

```php
$templateData = ['title' => 'My Page'];

// Apply filter - each listener can modify and return
$templateData = Event::filter('zed_template_data', $templateData);
```

---

## 4. Standard Hooks Reference

### System Lifecycle

| Hook | Data | Description |
|------|------|-------------|
| `app_init` | `Core\App` | Fired when App is instantiated |
| `app_ready` | `null` | After config loaded, before routing |
| `route_request` | `['uri' => string]` | **CRITICAL.** Route dispatching |
| `shutdown` | `null` | At the very end of execution |

### Frontend Content

| Hook | Data | Description |
|------|------|-------------|
| `zed_head` | `null` | Inside `<head>` tag. SEO, CSS |
| `zed_footer` | `null` | Before `</body>` tag. JS |
| `zed_before_content` | `$post, $data` | Before post body |
| `zed_after_content` | `$post, $data` | After post body |

### Admin Panel

| Hook | Data | Description |
|------|------|-------------|
| `zed_admin_head` | `null` | Inside admin `<head>` |
| `zed_admin_footer`| `null` | Before admin `</body>` |
| `zed_dashboard_widgets` | `null` | Dashboard widget area |

### Content Events

| Hook | Data | Description |
|------|------|-------------|
| `zed_post_saved` | `$postId, $data` | After content saved |
| `zed_post_deleted`| `$postId` | After content deleted |
| `zed_media_uploaded` | `$mediaId` | After media upload |

### Filters

| Filter | Input | Description |
|--------|-------|-------------|
| `zed_template_data` | `array` | Inject template variables |
| `zed_content_rendered` | `string` | Modify rendered HTML |

---

## 5. Priority System

Lower numbers run first. Default is 10.

```php
Event::on('zed_head', $callback, 1);   // Runs first
Event::on('zed_head', $callback, 10);  // Default
Event::on('zed_head', $callback, 100); // Runs last
```

---

## 6. Removing Listeners

```php
$callback = function() { echo 'Hello'; };

Event::on('my_event', $callback, 10);

// Later, remove it
Event::off('my_event', $callback, 10);
```

---

## 7. Check for Listeners

```php
if (Event::hasListeners('zed_footer')) {
    // Trigger only if someone is listening
    Event::trigger('zed_footer');
}
```

---

## 8. Practical Examples

### Injecting CSS Only for Products

```php
Event::onScoped('zed_head', function() {
    echo '<link rel="stylesheet" href="/themes/my-theme/product.css">';
}, ['post_type' => 'product']);
```

### Adding Author Bio After Posts

```php
Event::on('zed_after_content', function($post, $data) {
    if ($post['type'] !== 'post') return;
    
    $authorId = $post['author_id'];
    // Fetch and display author bio
    echo "<div class='author-bio'>Written by Author #{$authorId}</div>";
}, 10);
```

### Modifying Template Data

```php
Event::on('zed_template_data', function($data) {
    $data['site_stats'] = [
        'posts' => count_posts(),
        'users' => count_users(),
    ];
    return $data;
});
```

