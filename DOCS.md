# Zed CMS — Feature Guide

> **A Simple Guide to Every Feature in Zed CMS**  
> Last Updated: 2025-12-22

---

## 🎯 What is Zed CMS?

Zed CMS is a modern, lightweight content management system built with PHP and React. It uses an **event-driven architecture** where features are added through "addons" instead of hardcoding everything into the core.

**Think of it like LEGO blocks** — the core is minimal, and you add what you need.

---

## 🔑 Getting Started

### First-Time Setup
1. Upload files to your server
2. Visit `/install.php` in your browser
3. Enter your database details
4. Login at `/admin/login`

### Default Login
```
Email: admin@zed.local
Password: (set during install)
```

---

## 📋 Admin Panel Features

### 1. Dashboard (`/admin`)
Your command center showing:
- **Stats Cards** — Pages, Posts, Users, Addons count
- **Recent Activity** — Last 5 edited items
- **Quick Draft** — Create posts instantly
- **Health Checks** — System status (uploads, PHP, SEO)

### 2. Content Manager (`/admin/content`)
| Feature | How It Works |
|---------|--------------|
| Create | Click "New Content" or use Quick Draft |
| Edit | Click row or Edit button |
| Delete | Click delete icon (asks confirmation) |
| Filter | Use status tabs (All / Published / Draft) |
| Search | Type in search box |
| Pagination | 10 items per page |

### 3. Block Editor (`/admin/editor`)
A modern block-based editor (like Notion):
- **Paragraph, Heading, List** — Basic text blocks
- **Image** — Upload or pick from media library
- **Quote, Code** — Styled content blocks
- **Drag & Drop** — Reorder blocks easily

### 4. Media Library (`/admin/media`)
- **Upload** — Drag-drop or click to upload
- **Auto-Optimize** — Converts to WebP, creates thumbnails
- **Search** — Instant filtering
- **Copy URL** — One-click clipboard copy

### 5. Categories (`/admin/categories`)
Simple category management:
- Create: Enter name → auto-generates slug
- Delete: Click trash icon
- Protected: "Uncategorized" can't be deleted

### 6. Menu Builder (`/admin/menus`)
Visual menu editor:
- **Create Menu** — Enter name
- **Add Items** — Click pages/posts to add
- **Drag to Reorder** — Move items up/down
- **Nesting** — Create dropdowns
- **Auto-Save** — Saves 2 seconds after changes

### 7. User Management (`/admin/users`)
Full user CRUD with roles:
| Role | Access Level |
|------|--------------|
| Administrator | Everything |
| Editor | All content, no settings |
| Author | Own content only |
| Subscriber | No admin access |

### 8. Settings (`/admin/settings`)
Organized in tabs:
- **General** — Site title, tagline, homepage
- **SEO** — Meta description, search visibility
- **System** — Maintenance mode, debug mode

### 9. Addons (`/admin/addons`)
- Toggle addons on/off with switches
- Upload new addons (`.php` files)
- System addons can't be disabled

### 10. Themes (`/admin/themes`)
- Preview installed themes
- Activate with one click
- Themes can have custom settings

---

## 🎨 Frontend (Public Site)

### How URLs Work
| URL | What Shows |
|-----|------------|
| `/` | Homepage (posts or static page) |
| `/blog` | Blog listing (if static homepage) |
| `/about` | Page with slug "about" |
| `/hello-world` | Post with slug "hello-world" |

### Theme System
Themes are in `content/themes/`. Each theme needs:
```
my-theme/
├── index.php      ← Blog listing template
├── single.php     ← Individual post/page
├── functions.php  ← Theme setup (optional)
└── parts/
    ├── header.php
    └── footer.php
```

### Using Menus in Themes
```php
<?= zed_menu('Main Menu') ?>
<?= zed_primary_menu() ?>
```

---

## 🔌 For Developers: Addon System

### Creating an Addon
Create a file in `content/addons/`:
```php
<?php
// my_addon.php
use Core\Event;

// Run on every request
Event::on('route_request', function($request) {
    if ($request['uri'] === '/hello') {
        echo "Hello World!";
        \Core\Router::setHandled();
    }
});
```

### Available Hooks

| Hook | When It Fires |
|------|---------------|
| `app_init` | After addons load |
| `app_ready` | System fully ready |
| `route_request` | Every HTTP request |
| `zed_head` | In theme `<head>` |
| `zed_admin_menu` | Building sidebar |

### Addon DX APIs (New!)

#### Shortcodes
```php
zed_register_shortcode('youtube', function($attrs, $content) {
    return '<iframe src="https://youtube.com/embed/' . $attrs['id'] . '"></iframe>';
});
// Use: [youtube id="dQw4w9WgXcQ"]
```

#### AJAX Handlers
```php
zed_register_ajax('my_action', function($data) {
    return ['success' => true, 'message' => 'Done!'];
}, require_auth: true, method: 'POST');
// Endpoint: POST /api/ajax/my_action
```

#### Addon Settings (Auto UI)
```php
zed_register_addon_settings('my_seo', [
    'title' => 'SEO Settings',
    'fields' => [
        ['id' => 'tracking_id', 'type' => 'text', 'label' => 'GA ID'],
        ['id' => 'enabled', 'type' => 'toggle', 'label' => 'Enable Tracking'],
    ]
]);
// Creates settings page at /admin/addon-settings/my_seo
```

#### Admin Notices (Flash Messages)
```php
zed_add_notice('Settings saved!', 'success');
zed_add_notice('Something went wrong', 'error');
```

#### Security (Nonces)
```php
// In form:
<?= zed_nonce_field('my_action') ?>

// On submit:
zed_check_nonce('my_action'); // Dies if invalid
```

#### Transients (Cache)
```php
// Cache API data for 1 hour
$data = zed_remember('api_data', function() {
    return fetch_from_api();
}, 3600);
```

---

## 🗂️ File Structure

```
ZedCMS/
├── index.php           ← Entry point
├── config.php          ← Database config
├── install.php         ← Installer
├── cron.php            ← Scheduled tasks
│
├── core/               ← Engine (don't modify)
│   ├── App.php         ← Bootstrap
│   ├── Router.php      ← Event-driven routing
│   ├── Event.php       ← Hook system
│   ├── Database.php    ← PDO wrapper
│   └── Auth.php        ← Session & login
│
└── content/            ← Your stuff
    ├── addons/         ← Plugins
    │   ├── admin/      ← Admin modules
    │   └── frontend/   ← Theme helpers
    ├── themes/         ← Site themes
    └── uploads/        ← Media files
```


---

## 🛠️ Admin Development

### Admin Themes
Admin themes are located in `content/themes/`. To create a custom admin theme:
1. Create a folder (e.g., `content/themes/my-admin-theme`)
2. Create `admin-layout.php` (master layout)
3. Create view templates in `views/` folder (optional overrides)
4. Set `admin_theme` option to `my-admin-theme` in database

### Admin Renderer (New!)
Routes are theme-agnostic. Use `AdminRenderer` to render pages:

```php
use AdminRenderer;

// Render a full page with layout
$content = AdminRenderer::renderPage('my-view', [
    'foo' => 'bar'
], [
    'page_title' => 'My Page'
]);

// Render just the view (no layout)
$html = AdminRenderer::render('my-view', ['foo' => 'bar']);
```

### View Resolution
When you request view `my-view`, the renderer looks in:
1. `content/themes/{active_theme}/views/my-view.php`
2. `content/themes/{active_theme}/partials/my-view.php` (legacy)
3. `content/themes/{active_theme}/my-view.php`
4. Falls back to `admin-default` theme if not found

---


## 🤔 Common Questions

### How do I change the homepage?
1. Go to Settings
2. Set "Homepage Mode" to "Static Page"
3. Select a page from dropdown
4. Save

### How do I add custom fields?
Use the Metabox API:
```php
zed_register_metabox('book_details', [
    'title' => 'Book Details',
    'post_types' => ['book'],
    'fields' => [
        ['id' => 'isbn', 'type' => 'text', 'label' => 'ISBN'],
        ['id' => 'author', 'type' => 'text', 'label' => 'Author'],
    ]
]);
```

### How do I create a custom post type?
```php
// In theme's functions.php
zed_register_post_type('product', [
    'label' => 'Products',
    'singular' => 'Product',
    'icon' => 'shopping_cart',
]);
```

### How do I send emails?
```php
zed_mail([
    'to' => 'user@example.com',
    'subject' => 'Welcome!',
    'body' => '<h1>Hello!</h1><p>Welcome aboard.</p>',
]);
```

### How do I schedule tasks?
```php
// In cron.php or addon:
zed_schedule_event('cleanup', 'daily', function() {
    // Runs once per day
    delete_old_records();
});
```

---

## 📊 Database Tables

| Table | Purpose |
|-------|---------|
| `zed_content` | Pages, posts, all content |
| `users` | User accounts |
| `zed_categories` | Categories |
| `zed_menus` | Navigation menus |
| `zed_options` | Site settings |
| `zed_content_revisions` | Version history |

---

## 🚀 Need Help?

1. Check the [Knowledge Base](/admin/wiki) in admin
2. Review `ZERO_BLUEPRINT.md` for technical details
3. Look at existing addons for examples

---

*Zed CMS — Built for developers who want simplicity with power.*
