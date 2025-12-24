# Addon Development Guide

## Addon Structure Options

Zed CMS supports two addon formats:

### 1. Single-File Addons (Simple)
For basic addons with no assets:
```
content/addons/
└── my_addon.php
```

### 2. Folder-Based Addons (Recommended)
For complex addons with assets:
```
content/addons/
└── my_addon/
    ├── addon.php       ← Entry point (required)
    ├── README.md       ← Documentation
    ├── src/            ← PHP classes (auto-loaded via Addons\ namespace)
    │   └── MyClass.php
    ├── assets/
    │   ├── script.js
    │   ├── style.css
    │   └── icon.png
    └── templates/      ← Optional
        └── custom.php
```

## System Modules vs Addons

Zed CMS separates **system modules** from user addons:

```
content/addons/
├── _system/              ← SYSTEM MODULES (protected, always loaded)
│   ├── admin.php         ← Entry point for admin system
│   │   └── admin/        ← Admin sub-modules
│   └── frontend.php      ← Entry point for frontend system
│       └── frontend/     ← Frontend sub-modules
│
├── zed_seo/              ← User addon (folder-based)
├── zed_contact/          ← User addon (folder-based)
└── custom_addon.php      ← User addon (single-file)
```

**Key Difference:**
- System modules (`_system/*`) cannot be disabled and are always loaded first
- User addons can be enabled/disabled via Admin → Addons

## Class Autoloading for Addons

Addons can define classes in the `Addons\` namespace. They are auto-loaded:

```php
// In: content/addons/zed_seo/src/SitemapGenerator.php
<?php
namespace Addons\ZedSEO;

class SitemapGenerator {
    public function generate(): string { ... }
}
```

Usage anywhere:
```php
$generator = new \Addons\ZedSEO\SitemapGenerator();
```

**Naming Convention:**
- Folder: `zed_seo` (snake_case)
- Namespace: `Addons\ZedSEO` (PascalCase)
- Class: `SitemapGenerator`
- File: `src/SitemapGenerator.php`

## Folder-Based Addon Template

```php
<?php
/**
 * Addon Name: My Awesome Addon
 * Description: Does something amazing
 * Version: 1.0.0
 * Author: Your Name
 */

use Core\Event;
use Core\Router;

// Get addon directory URL
$addonUrl = Router::getBasePath() . '/content/addons/my_addon';

// Register settings
zed_register_addon_settings('my_addon', [
    'title' => 'My Addon Settings',
    'fields' => [
        ['id' => 'api_key', 'type' => 'text', 'label' => 'API Key'],
    ]
]);

// Register shortcode
zed_register_shortcode('my_shortcode', function($attrs) use ($addonUrl) {
    return <<<HTML
    <div class="my-widget">
        <script src="{$addonUrl}/assets/script.js"></script>
        <link rel="stylesheet" href="{$addonUrl}/assets/style.css">
    </div>
HTML;
});

// Register AJAX handler
zed_register_ajax('my_action', function($data) {
    return ['success' => true, 'data' => $data];
}, require_auth: false);
```

## Asset Organization

### JavaScript
```
assets/
├── admin.js        ← Admin-only scripts
├── frontend.js     ← Public-facing scripts
└── shared.js       ← Used in both
```

### CSS
```
assets/
├── admin.css
├── frontend.css
└── icons/
    └── logo.svg
```

### Images
```
assets/
├── images/
│   ├── banner.jpg
│   └── icon.png
└── screenshots/
    └── demo.png
```

## Best Practices

1. **Always use folder structure** for addons with assets or classes
2. **Use `__DIR__`** to reference addon files
3. **Prefix CSS/JS** to avoid conflicts
4. **Document settings** in README.md
5. **Version your addon** in the header comment
6. **Use the `Addons\` namespace** for classes to get auto-loading

## Loading Assets

### In Shortcodes
```php
$addonUrl = Router::getBasePath() . '/content/addons/my_addon';
return '<script src="' . $addonUrl . '/assets/script.js"></script>';
```

### Using Enqueue System
```php
zed_enqueue_script('my-addon-js', '/content/addons/my_addon/assets/script.js', [
    'version' => '1.0.0',
    'in_footer' => true,
]);
```

## Example: Complete Addon

See `zed_contact/` for a complete example demonstrating:
- Folder structure
- Asset loading
- Settings API
- Shortcodes
- AJAX handlers
- Email integration
