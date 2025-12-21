# System Architecture

Zed CMS is built on a **micro-kernel architecture** that prioritizes speed and simplicity.

## Core Components

The system consists of three main layers:

1. **The Kernel (`/core`)**: Handles routing, database, and authentication.
2. **Addons (`/content/addons`)**: Implements actual functionality (Admin, Frontend, Wiki).
3. **Themes (`/content/themes`)**: Controls the visual presentation.

## Event-Driven Design

We use a simple hook system similar to WordPress but faster:

```php
// Register a hook
Event::on('route_request', function($request) {
    // Handle route
});

// Trigger a hook
Event::trigger('zed_head');
```

## Directory Structure

*   `core/` - The engine room.
*   `content/` - User data and extensions.
*   `index.php` - The single entry point.
