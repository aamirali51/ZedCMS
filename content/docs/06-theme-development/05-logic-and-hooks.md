# Logic & Hooks in Themes

Zed CMS provides a powerful Theme API that transforms themes from static templates into active system extensions.

## 1. The functions.php File

Unlike the old pattern, Zed CMS **automatically loads** a `functions.php` file from your active theme during the `app_ready` lifecycle event. This allows themes to register hooks, custom post types, and settings before routes dispatch.

**Create this file:**
```text
themes/my-theme/
├── index.php
├── single.php
└── functions.php   ← Auto-loaded!
```

**Example functions.php:**
```php
<?php
declare(strict_types=1);

use Core\Event;

// Register a Custom Post Type
zed_register_post_type('portfolio', 'Portfolio', 'work');

// Register Theme Settings  
zed_add_theme_setting('accent_color', 'Accent Color', 'color', '#4f46e5');

// Hook into content rendering
Event::on('zed_after_content', function($post) {
    echo '<div class="share-buttons">Share this!</div>';
});
```

---

## 2. Custom Post Types (CPT)

Register custom content types that appear in the admin sidebar.

### Basic Registration
```php
zed_register_post_type('product', 'Products', 'inventory_2');
```

### Advanced Registration
```php
zed_register_post_type('event', [
    'label' => 'Events',
    'singular' => 'Event',
    'icon' => 'event',
    'supports' => ['title', 'editor', 'featured_image'],
    'menu_position' => 30,
    'public' => true,
    'show_in_menu' => true,
]);
```

### Retrieving Post Types
```php
// Get all registered types
$types = zed_get_post_types();

// Get custom types only (exclude page/post)
$custom = zed_get_post_types(false);

// Get single type config
$product = zed_get_post_type('product');
```

---

## 3. Theme Settings API

Allow users to customize your theme without editing code.

### Registering Settings
```php
// Text input
zed_add_theme_setting('footer_text', 'Footer Text', 'text', '© 2025 My Site');

// Color picker
zed_add_theme_setting('accent_color', 'Accent Color', 'color', '#4f46e5');

// Checkbox
zed_add_theme_setting('show_author', 'Show Author Bio', 'checkbox', true);

// Select dropdown
zed_add_theme_setting('layout', 'Layout Style', 'select', 'wide', [
    'wide' => 'Wide', 
    'boxed' => 'Boxed'
]);
```

### Using Theme Options in Templates
```php
<footer style="background: <?= zed_theme_option('accent_color', '#333') ?>">
    <?= zed_theme_option('footer_text') ?>
</footer>

<?php if (zed_theme_option('show_author', true)): ?>
    <div class="author-bio">...</div>
<?php endif; ?>
```

---

## 4. Scoped Action Hooks

This solves WordPress's biggest hook flaw—hooks fire globally. With scoped hooks, you can bind to specific contexts.

### Registering Scoped Hooks
```php
// Only inject this CSS on product pages
Event::onScoped('zed_head', function() {
    echo '<link rel="stylesheet" href="product-styles.css">';
}, ['post_type' => 'product']);

// Only show author bio on blog posts
Event::onScoped('zed_after_content', function($post) {
    echo '<div class="author-bio">Written by...</div>';
}, ['post_type' => 'post']);
```

### Triggering Scoped Hooks (in core/addons)
```php
Event::triggerScoped('zed_head', [
    'post_type' => $post['type'],
    'template' => 'single',
], $post);
```

---

## 5. Content Rendering Hooks

Inject dynamic elements before/after post content.

### Available Hooks
| Hook | Fires When | Use Case |
|------|------------|----------|
| `zed_before_content` | Before post body | Breadcrumbs, share buttons |
| `zed_after_content` | After post body | Author bio, related posts |
| `zed_head` | In `<head>` | Meta tags, stylesheets |
| `zed_footer` | Before `</body>` | Scripts, analytics |

### Example: Author Bio After Posts
```php
Event::on('zed_after_content', function($post, $data) {
    if ($post['type'] !== 'post') return;
    
    echo '<div class="author-bio">';
    echo '<img src="' . get_avatar($post['author_id']) . '">';
    echo '<p>Written by ' . $post['author_name'] . '</p>';
    echo '</div>';
}, 10);
```

---

## 6. Asset Injection Helper

Automatically resolve paths to theme assets with dev/production support.

### Enqueueing Assets
```php
// In functions.php
zed_enqueue_theme_asset('css/custom.css');
zed_enqueue_theme_asset('js/theme.js', ['dependency'], '1.2.0');
```

### Rendering in Templates
```html
<head>
    <?= zed_render_theme_styles() ?>
</head>
<body>
    <!-- content -->
    <?= zed_render_theme_scripts() ?>
</body>
```

Supports **Vite manifest.json** for production builds with hashed filenames.

---

## 7. Theme Dependencies

Declare required addons that your theme needs.

### In functions.php
```php
zed_register_theme_requirements([
    'required_addons' => ['seo_addon', 'social_sharing'],
    'min_php_version' => '8.2',
]);
```

### Result
The Addon Manager will show a warning banner prompting users to enable missing addons.

---

## 8. Data-Driven Templates

Inject PHP variables into templates before rendering.

### Adding Template Data
```php
// In functions.php or addon
zed_add_template_data('site_stats', [
    'posts' => 42,
    'users' => 156,
]);

// Or batch add
zed_add_template_data([
    'featured_posts' => $featured,
    'sidebar_widgets' => $widgets,
]);
```

### Using the Filter
```php
Event::on('zed_template_data', function($data) {
    $data['current_user'] = Auth::user();
    $data['is_premium'] = check_premium_status();
    return $data;
});
```

### In Templates
```php
<?php $data = zed_get_template_data(); ?>
<p>Total posts: <?= $data['site_stats']['posts'] ?></p>

<?php if ($data['is_premium']): ?>
    <div class="premium-content">...</div>
<?php endif; ?>
```

---

## 9. Standard Hooks Reference

### `zed_head` (The `<head>` tag)
```php
<head>
    <title>My Site</title>
    <?php \Core\Event::trigger('zed_head'); ?>
</head>
```

**Injects:** Meta description, Open Graph tags, noindex (if enabled), theme styles.

### `zed_footer` (The `</body>` tag)
```php
    <?php \Core\Event::trigger('zed_footer'); ?>
</body>
```

**Injects:** Admin toolbar, analytics scripts, theme scripts.

---

## 10. Complete Theme functions.php Example

```php
<?php
declare(strict_types=1);

use Core\Event;
use Core\Auth;

// =============================================================================
// THEME REQUIREMENTS
// =============================================================================
zed_register_theme_requirements([
    'required_addons' => ['seo_addon'],
    'min_php_version' => '8.2',
]);

// =============================================================================
// CUSTOM POST TYPES
// =============================================================================
zed_register_post_type('portfolio', [
    'label' => 'Portfolio',
    'singular' => 'Project',
    'icon' => 'work',
    'supports' => ['title', 'editor', 'featured_image'],
    'menu_position' => 25,
]);

// =============================================================================
// THEME SETTINGS
// =============================================================================
zed_add_theme_setting('accent_color', 'Accent Color', 'color', '#4f46e5');
zed_add_theme_setting('show_author_bio', 'Show Author Bio', 'checkbox', true);
zed_add_theme_setting('footer_copyright', 'Copyright Text', 'text', '© ' . date('Y'));

// =============================================================================
// THEME ASSETS
// =============================================================================
zed_enqueue_theme_asset('css/theme.css');
zed_enqueue_theme_asset('js/theme.js');

// =============================================================================
// CONTENT HOOKS
// =============================================================================

// Author bio after blog posts
Event::on('zed_after_content', function($post) {
    if ($post['type'] !== 'post') return;
    if (!zed_theme_option('show_author_bio', true)) return;
    
    echo '<div class="author-bio" style="margin-top:2rem;padding:1rem;background:#f9fafb;">';
    echo '<p><strong>About the Author</strong></p>';
    echo '</div>';
}, 10);

// Inject accent color CSS variable
Event::on('zed_head', function() {
    $accent = zed_theme_option('accent_color', '#4f46e5');
    echo "<style>:root { --accent-color: {$accent}; }</style>";
});
```
