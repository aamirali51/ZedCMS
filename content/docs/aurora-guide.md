# Aurora Framework Guide

The ultimate starter theme for ZedCMS demonstrating the Theme API v2.

---

## Getting Started

### 1. Activate Aurora Theme

1. Go to **Admin → Themes**
2. Find "Aurora" in the list
3. Click **Activate**

### 2. Run the Master Seeder (Optional)

1. Go to **Admin → Addons**
2. Enable "Aurora Master Seeder"
3. Demo content, menus, and users will be created automatically

---

## Theme Structure

```text
aurora/
├── app/                # PHP classes
├── templates/          # Partials
├── assets/
│   ├── css/aurora.css
│   └── js/aurora.js
├── functions.php       # Entry point
├── index.php           # Homepage
├── single.php          # Single post
└── style.css           # Theme metadata
```

---

## Custom Post Types

Aurora registers two CPTs out of the box:

### Portfolio
```php
zed_register_post_type('portfolio', [
    'label' => 'Portfolio',
    'singular' => 'Project',
    'icon' => 'work',
    'supports' => ['title', 'editor', 'featured_image', 'excerpt'],
]);
```

### Testimonials
```php
zed_register_post_type('testimonial', [
    'label' => 'Testimonials',
    'singular' => 'Testimonial',
    'icon' => 'format_quote',
    'supports' => ['title', 'editor'],
]);
```

---

## Theme Settings

Aurora comes with customizable options in the admin panel:

| Setting | Description |
|---------|-------------|
| Hero Image | Background image for hero section |
| Hero Title | Main headline text |
| Brand Color | Primary accent color |
| Navigation Layout | Horizontal or Vertical |
| Social Links | Twitter, GitHub, LinkedIn URLs |

### Using Theme Options

```php
<?php
// In your templates
$color = zed_theme_option('brand_color', '#6366f1');
$title = zed_theme_option('hero_title', 'Welcome');
?>
```

---

## Scoped Hooks

Aurora demonstrates context-aware hooks that only fire for specific post types:

```php
// Only injects on portfolio pages
Event::onScoped('zed_head', function() {
    echo '<meta name="portfolio" content="true">';
}, ['post_type' => 'portfolio']);
```

---

## JSON-LD SEO

Aurora automatically injects Schema.org structured data:

- **Article schema** for blog posts
- **CreativeWork schema** for portfolio items

This happens automatically via the `zed_head` hook.

---

## Extending Aurora

### Add a New Post Type

```php
// In functions.php or a child theme
zed_register_post_type('product', [
    'label' => 'Products',
    'icon' => 'inventory_2',
    'supports' => ['title', 'editor', 'featured_image'],
]);
```

### Add Theme Settings

```php
zed_add_theme_setting('my_option', 'My Label', 'text', 'default');
```

### Hook into Content

```php
Event::on('zed_after_content', function($post) {
    echo '<div class="cta">Subscribe now!</div>';
});
```

---

## Demo Accounts

The Master Seeder creates:

| Email | Password | Role |
|-------|----------|------|
| editor@demo.zed | editor123 | Editor |
| author@demo.zed | author123 | Author |

---

## License

Aurora Framework is released under the **MIT License**.

© 2025 ZedCMS Team
