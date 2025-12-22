# Theme API Reference

Complete reference for all Zed CMS Theme API functions (v2.1.0+).

---

## Custom Post Types

### zed_register_post_type()

Register a custom post type.

```php
zed_register_post_type(
    string $type,              // Unique slug (lowercase, alphanumeric)
    string|array $labelOrArgs, // Label string OR config array
    string $icon = 'folder'    // Material icon name
): bool
```

**Config Array Options:**
| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `label` | string | `ucfirst($type).'s'` | Plural display name |
| `singular` | string | `ucfirst($type)` | Singular name |
| `icon` | string | `'folder'` | Material Symbols icon |
| `supports` | array | `['title', 'editor']` | Features to enable |
| `public` | bool | `true` | Show on frontend |
| `show_in_menu` | bool | `true` | Show in admin sidebar |
| `menu_position` | int | `50` | Position in sidebar |

**Example:**
```php
zed_register_post_type('product', [
    'label' => 'Products',
    'singular' => 'Product',
    'icon' => 'inventory_2',
    'supports' => ['title', 'editor', 'featured_image', 'excerpt'],
    'menu_position' => 25,
]);
```

---

### zed_get_post_types()

Get all registered post types.

```php
zed_get_post_types(bool $includeBuiltin = true): array
```

**Returns:** Associative array of all post types with their configs.

---

### zed_get_post_type()

Get configuration for a specific post type.

```php
zed_get_post_type(string $type): ?array
```

**Returns:** Config array or `null` if not found.

---

## Theme Settings

### zed_add_theme_setting()

Register a customizable theme setting.

```php
zed_add_theme_setting(
    string $id,           // Setting ID (no spaces)
    string $label,        // Display label
    string $type = 'text', // Input type
    mixed $default = '',  // Default value
    array $options = []   // Options for 'select' type
): bool
```

**Field Types:**
| Type | Description |
|------|-------------|
| `text` | Single-line text input |
| `textarea` | Multi-line text |
| `color` | Color picker |
| `checkbox` | Toggle (true/false) |
| `select` | Dropdown with options |

**Example:**
```php
zed_add_theme_setting('layout', 'Page Layout', 'select', 'wide', [
    'wide' => 'Full Width',
    'boxed' => 'Boxed Content',
    'sidebar' => 'With Sidebar',
]);
```

---

### zed_theme_option()

Get a theme setting value.

```php
zed_theme_option(string $id, mixed $default = ''): mixed
```

Settings are stored in `zed_options` with prefix `theme_{active_theme}_{id}`.

**Example:**
```php
$color = zed_theme_option('accent_color', '#4f46e5');
$showBio = zed_theme_option('show_author_bio', true);
```

---

### zed_set_theme_option()

Save a theme setting value.

```php
zed_set_theme_option(string $id, mixed $value): bool
```

---

### zed_get_theme_settings()

Get all registered theme settings.

```php
zed_get_theme_settings(): array
```

---

## Theme Requirements

### zed_register_theme_requirements()

Declare theme dependencies.

```php
zed_register_theme_requirements(array $requirements): void
```

**Requirements Array:**
| Key | Type | Description |
|-----|------|-------------|
| `required_addons` | array | Addon filenames (without .php is fine) |
| `min_php_version` | string | Minimum PHP version |

**Example:**
```php
zed_register_theme_requirements([
    'required_addons' => ['seo_addon', 'social_sharing_addon'],
    'min_php_version' => '8.2',
]);
```

---

### zed_get_missing_theme_addons()

Check which required addons are not enabled.

```php
zed_get_missing_theme_addons(): array
```

**Returns:** Array of missing addon filenames.

---

## Asset Injection

### zed_enqueue_theme_asset()

Queue a CSS or JS file from the theme.

```php
zed_enqueue_theme_asset(
    string $file,           // Relative path in assets/
    array $deps = [],       // Dependencies (JS only)
    string $version = '1.0.0' // Cache busting
): void
```

**Example:**
```php
zed_enqueue_theme_asset('css/custom.css');
zed_enqueue_theme_asset('js/theme.js', ['jquery'], '2.0.0');
```

---

### zed_render_theme_styles()

Output enqueued CSS (call in `<head>`).

```php
zed_render_theme_styles(): string
```

---

### zed_render_theme_scripts()

Output enqueued JS (call before `</body>`).

```php
zed_render_theme_scripts(): string
```

---

## Template Data

### zed_add_template_data()

Add data available in all templates.

```php
zed_add_template_data(string|array $keyOrData, mixed $value = null): void
```

**Examples:**
```php
// Single value
zed_add_template_data('site_name', 'My Website');

// Multiple values
zed_add_template_data([
    'featured_posts' => $posts,
    'sidebar_config' => $config,
]);
```

---

### zed_get_template_data()

Get all template data (applies `zed_template_data` filter).

```php
zed_get_template_data(array $contextData = []): array
```

---

### zed_extract_template_data()

Extract template data into local scope.

```php
zed_extract_template_data(array $contextData = []): void
```

**In template:**
```php
<?php zed_extract_template_data(); ?>
<h1><?= $site_name ?></h1>
```

---

## Scoped Hooks

### Event::onScoped()

Register a listener that only fires when context matches.

```php
Event::onScoped(
    string $name,       // Hook name
    callable $callback, // Function to run
    array $context,     // Context conditions to match
    int $priority = 10  // Execution order
): void
```

**Example:**
```php
// Only on product pages
Event::onScoped('zed_head', function() {
    echo '<link href="product.css" rel="stylesheet">';
}, ['post_type' => 'product']);
```

---

### Event::triggerScoped()

Fire a hook with context matching.

```php
Event::triggerScoped(
    string $name,    // Hook name
    array $context,  // Current context
    mixed $payload = null,
    mixed ...$args
): void
```

Both regular AND scoped listeners with matching context will fire.

---

## Content Hooks

| Hook | Parameters | Use Case |
|------|------------|----------|
| `zed_before_content` | `$post`, `$data` | Breadcrumbs, sharing |
| `zed_after_content` | `$post`, `$data` | Author bio, related posts |
| `zed_head` | none | Meta tags, styles |
| `zed_footer` | none | Scripts, analytics |
| `zed_template_data` | `$data` (filter) | Inject template variables |

---

## Filters

### zed_template_data

Modify template data before it's available.

```php
Event::on('zed_template_data', function(array $data): array {
    $data['user'] = Auth::user();
    $data['is_admin'] = Auth::hasCapability('manage_settings');
    return $data;
});
```

---

## Helper Functions

### zed_get_option()

Get any option from `zed_options` table.

```php
zed_get_option(string $name, mixed $default = ''): mixed
```

---

### zed_get_site_name()

Get the site title.

```php
zed_get_site_name(): string
```

---

### zed_get_site_tagline()

Get the site tagline.

```php
zed_get_site_tagline(): string
```

---

### zed_page_title()

Generate a page title with site name.

```php
zed_page_title(string $pageTitle = ''): string
```

**Example:**
```php
<title><?= zed_page_title('About Us') ?></title>
// Output: "About Us — My Site"
```

---

### zed_menu()

Render a navigation menu.

```php
zed_menu(int|string $menuIdOrName, array $options = []): string
```

**Options:**
| Key | Description |
|-----|-------------|
| `class` | Additional CSS classes |
| `id` | HTML ID attribute |

**Example:**
```php
<?= zed_menu('Main Menu', ['class' => 'nav-primary']) ?>
<?= zed_menu(1) ?>
```
