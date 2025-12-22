# Zed CMS Theme Parts System

This document explains how to create page templates that dynamically adopt any theme's styling.

## Overview

The Theme Parts system allows templates to include reusable header, footer, and other layout components that automatically adapt to the active theme's design.

## Theme Structure

A theme should have the following structure for template compatibility:

```
content/themes/your-theme/
├── functions.php          # Theme setup and registration
├── style.css              # Theme metadata
├── index.php              # Homepage template
├── single.php             # Single post template
├── parts/                 # Reusable partials
│   ├── head.php           # <head> section with CSS/JS
│   ├── header.php         # Navigation & body opening
│   └── footer.php         # Footer & body closing
└── templates/             # Page templates
    ├── contact.php
    └── landing.php
```

## Creating Theme Parts

### parts/head.php

This partial outputs the `<!DOCTYPE>`, `<html>`, and `<head>` sections:

```php
<?php
use Core\Event;
use Core\Router;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();
$page_title = $page_title ?? $post['title'] ?? $site_name;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($page_title) ?> — <?= htmlspecialchars($site_name) ?></title>
    <?= zed_tailwind_cdn() ?>
    <?= zed_google_fonts() ?>
    <?php Event::triggerScoped('zed_head', ['post_type' => $post['type'] ?? 'page']); ?>
</head>
```

### parts/header.php

This partial outputs the `<body>` opening and navigation:

```php
<?php
use Core\Router;
$base_url = Router::getBasePath();
$site_name = zed_get_site_name();
?>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    <header class="bg-white border-b sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="<?= $base_url ?>/" class="font-bold text-xl"><?= htmlspecialchars($site_name) ?></a>
            <?= zed_menu('Main Menu') ?>
        </nav>
    </header>
```

### parts/footer.php

This partial outputs the footer and closes `</body></html>`:

```php
<?php
use Core\Event;
$site_name = zed_get_site_name();
?>
    <footer class="bg-slate-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-slate-400">© <?= date('Y') ?> <?= htmlspecialchars($site_name) ?></p>
        </div>
    </footer>
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
```

## Creating Page Templates

Page templates should use `zed_include_theme_part()` to include theme partials:

```php
<?php
/**
 * My Custom Page Template
 */
declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$page_title = $post['title'] ?? 'My Page';

// Include theme's head part (with fallback)
if (!zed_include_theme_part('head', [
    'page_title' => $page_title,
    'post' => $post ?? [],
])) {
    // Fallback if theme doesn't have parts/head.php
    echo "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n";
    echo zed_tailwind_cdn();
    echo zed_google_fonts();
    echo "</head>\n";
}

// Include theme's header
if (!zed_include_theme_part('header')) {
    echo '<body class="bg-slate-50"><header>...</header>';
}
?>

<main class="py-16">
    <div class="max-w-4xl mx-auto px-6">
        <h1><?= htmlspecialchars($page_title) ?></h1>
        <?php if (!empty($htmlContent)): ?>
            <?= $htmlContent ?>
        <?php endif; ?>
    </div>
</main>

<?php
// Include theme's footer
if (!zed_include_theme_part('footer')) {
    echo '<footer>...</footer></body></html>';
}
?>
```

## Available Helper Functions

| Function | Description |
|----------|-------------|
| `zed_include_theme_part($part, $vars)` | Include a theme partial file |
| `zed_get_theme_part($part)` | Get the path to a theme partial |
| `zed_theme_part_exists($part)` | Check if a theme part exists |
| `zed_get_theme_path()` | Get the active theme's directory path |
| `zed_tailwind_cdn($extraColors)` | Get Tailwind CDN script tags with theme colors |
| `zed_google_fonts($fonts)` | Get Google Fonts link tags |
| `zed_theme_option($id, $default)` | Get a theme setting value |

## Variables Available in Parts

When using `zed_include_theme_part()`, these variables are automatically available:

- `$base_url` - The site's base URL
- `$site_name` - The site name from settings
- Any variables passed in the `$vars` array

## Best Practices

1. **Always provide fallbacks** - Use the if-else pattern to provide fallback HTML when parts don't exist
2. **Pass required variables** - Include `page_title`, `post`, and `data` in the vars array
3. **Use theme helper functions** - Use `zed_tailwind_cdn()` and `zed_google_fonts()` for consistent styling
4. **Keep templates focused** - Let parts handle the layout, templates handle the content
