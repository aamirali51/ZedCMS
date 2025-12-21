# Helper Function Reference

A complete list of global functions available in Themes and Addons.

## Settings & Options

### `zed_get_option(string $key, mixed $default = null): mixed`
Retrieves a value from the `zed_options` table. Uses internal static caching to avoid DB queries on repeated calls.
```php
$title = zed_get_option('site_title', 'My Site');
```

### `zed_get_site_name(): string`
Shortcut for `zed_get_option('site_name')`.

### `zed_is_debug_mode(): bool`
Returns true if `debug_mode` is enabled.

## Content & Template Tags

### `zed_page_title(string $customTitle = ''): string`
Generates a complete `<title>` tag string based on context (Home vs Internal Page).
```html
<title><?= zed_page_title($post['title']) ?></title>
<!-- Output: "My Post — My Site" -->
```

### `zed_get_latest_posts(int $limit = 10, int $offset = 0): array`
Fetches a list of posts with status 'published', sorted by `created_at` DESC.

### `render_blocks(array $blocks): string`
Converts JSON block data into HTML.
*   **Input:** Array of block objects.
*   **Output:** HTML string.
*   **Safe:** Automatically sanitizes HTML attributes.

## Navigation & Menus

### `zed_menu(string|int $id_or_name, array $options = []): string`
Renders a navigation menu.
*   **$id_or_name:** ID (int) or Name (string) of the menu.
*   **$options:**
    *   `class` (string): CSS class for `<ul>`. Default `zed-menu`.
    *   `id` (string): CSS ID for `<ul>`.

```php
<?= zed_menu('Footer Menu', ['class' => 'flex gap-4']) ?>
```

### `zed_primary_menu(array $options = []): string`
Fetches the first available menu in the database. Good fallback for themes.

## Authentication

### `zed_is_admin(): bool`
Returns true if the current user has the `admin` role.

### `zed_currrent_user_can(string $cap): bool`
Checks if the current user has a specific capability using the RBAC matrix.

## Utility

### `zed_safe_slug(string $title): string`
Converts a string to a URL-friendly slug.
*   "Hello World!" -> "hello-world"
