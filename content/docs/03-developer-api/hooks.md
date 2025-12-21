# Developer Hooks

Zed CMS uses an event-driven hook system.

## Core Hooks

*   `app_init` - Fires when the App initializes.
*   `route_request` - Fires when a request needs routing.
*   `app_ready` - Fires before dispatching routes.

## Frontend Hooks

*   `zed_head` - Helper to inject SEO tags in `<head>`.
*   `zed_footer` - Helper to inject scripts in footer.
*   `content_render` - Filters content before display.

## Admin Hooks

*   `admin_init` - Fires on admin page load.
*   `admin_menu` - Used to register menu items.
*   `save_post` - Fires after content is saved.

## Example Usage

```php
Event::on('save_post', function($data) {
    // Log the update
    error_log("Post saved: " . $data['id']);
});
```
