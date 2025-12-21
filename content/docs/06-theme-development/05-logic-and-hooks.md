# Logic & Hooks in Themes

While Zed themes are simple, they can be powerful. You don't have a `functions.php` file enforced by the system, but you can create one.

## 1. Using Standard Hooks

Hooks are essential for injecting scripts, styles, and SEO metadata.

### `zed_head` (The <head> tag)
Place this **immediately before** `</head>` in all your templates (`index.php`, `single.php`, `page.php`).

```php
<head>
    <title>My Site</title>
    <!-- Your Styles -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Zed CMS Injection -->
    <?php \Core\Event::trigger('zed_head'); ?>
</head>
```

**What it does:**
*   Injects `<meta name="description">`.
*   Injects Open Graph (Facebook/Twitter) tags.
*   Injects `noindex` if Search Engines are discouraged in Settings.

### `zed_footer` (The </body> tag)
Place this **immediately before** `</body>`.

```php
    <?php \Core\Event::trigger('zed_footer'); ?>
</body>
```

**What it does:**
*   Injects admin toolbar scripts (if logged in).
*   Injects analytics scripts (from addons).

## 2. Organizing Custom Logic

If your theme has complex logic (e.g., custom formatters, fetchers), don't clutter your `index.php`.

### The "Includes" Pattern

Create an `inc/` folder in your theme.

```text
themes/my-theme/
├── index.php
└── inc/
    ├── helpers.php
    └── custom-hooks.php
```

Then, at the top of your `index.php`:

```php
<?php
// Load theme logic
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/custom-hooks.php';
?>
<!DOCTYPE html>...
```

## 3. Creating Custom Hooks

You can define your own hooks to make your theme extensible by other developers or addons.

**In your theme (index.php):**
```php
<main>
    <?php \Core\Event::trigger('theme_before_content'); ?>
    
    <h1><?= $post['title'] ?></h1>
    
    <?php \Core\Event::trigger('theme_after_content'); ?>
</main>
```

**In an Addon (content/addons/my-addon.php):**
```php
\Core\Event::on('theme_after_content', function() {
    echo "<div class='ad-banner'>Buy our Stuff!</div>";
});
```
