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
    ├── assets/
    │   ├── script.js
    │   ├── style.css
    │   └── icon.png
    └── templates/      ← Optional
        └── custom.php
```

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

1. **Always use folder structure** for addons with assets
2. **Use `__DIR__`** to reference addon files
3. **Prefix CSS/JS** to avoid conflicts
4. **Document settings** in README.md
5. **Version your addon** in the header comment

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
