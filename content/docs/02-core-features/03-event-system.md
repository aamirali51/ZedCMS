# The Event System

Zed CMS implementation of the Mediator Pattern. It allows different parts of the system to communicate without knowing about each other. It is similar to WordPress Hooks/Actions, but simplified.

## Conceptual Overview

*   **Trigger:** "Hey everyone, I just saved a post!"
*   **Listener:** "Oh, I heard a post was saved. I'll send an email notification."

## The `Core\Event` Class

### syntax

```php
class Event {
    public static function on(string $event, callable $listener, int $priority = 10);
    public static function trigger(string $event, mixed $data = null);
}
```

### 1. Registering a Listener (`on`)

Use `Event::on()` to subscribe to an event.

```php
use Core\Event;

// Basic Listener
Event::on('user_login', function($user) {
    error_log("User logged in: " . $user['email']);
});

// Listener with Priority (Lower runs first)
Event::on('zed_head', function() {
    echo '<meta charset="utf-8">';
}, 1); // Runs before standard meta tags
```

### 2. Triggering an Event (`trigger`)

Use `Event::trigger()` to broadcast an event.

```php
// Trigger with data
$user = ['id' => 1, 'email' => 'admin@example.com'];
Event::trigger('user_login', $user);

// Trigger without data (just a signal)
Event::trigger('zed_footer');
```

## List of Standard Hooks

### System Lifecycle

| Hook | Data | Description |
|------|------|-------------|
| `app_init` | `Core\App` | Fired when App is instantiated. |
| `route_request` | `['uri' => string]` | **CRITICAL.** Fired to determine who handles the page. |
| `app_ready` | `null` | Fired just before routing begins. |
| `shutdown` | `null` | Fired at the very end of execution. |

### Admin & Content

| Hook | Data | Description |
|------|------|-------------|
| `zed_head` | `null` | Inside `<head>` tag. Used for SEO, CSS. |
| `zed_footer` | `null` | Before `</body>` tag. Used for JS. |
| `zed_admin_head` | `null` | Inside admin panel `<head>`. |
| `zed_admin_footer`| `null` | Before admin panel `</body>`. |
| `zed_post_saved` | `$postId` | Fired after content is updated/created. |
| `zed_post_deleted`| `$postId` | Fired after content is deleted. |

## Modifying Data (Filters)

Currently, Zed's `trigger` does not return modified data (it's an Observer pattern, not a Filter pattern like WP `apply_filters`). If you need to modify data, pass an object by reference.

```php
$data = new stdClass();
$data->title = "Original Title";

Event::trigger('modify_title', $data);

echo $data->title; // "Modified Title"

// Listener
Event::on('modify_title', function($obj) {
    $obj->title = "Modified Title";
});
```
