# Zed CMS — Complete Architecture & Developer Reference

> **Version:** 3.1.0  
> **Last Updated:** December 25, 2024  
> **Target Audience:** Backend Developers, Frontend Developers, System Architects

---

## Table of Contents

1. [Philosophy & Design Principles](#1-philosophy--design-principles)
2. [Architecture Overview](#2-architecture-overview)
3. [Directory Structure](#3-directory-structure)
4. [Request Lifecycle](#4-request-lifecycle)
5. [Core Classes](#5-core-classes)
6. [System Modules](#6-system-modules)
7. [Frontend System](#7-frontend-system)
8. [Admin System](#8-admin-system)
9. [Theme System](#9-theme-system)
10. [Addon System](#10-addon-system)
11. [Database Schema](#11-database-schema)
12. [API Reference](#12-api-reference)
13. [Security Model](#13-security-model)
14. [Best Practices](#14-best-practices)
15. [Caching API](#15-caching-api)
16. [Performance Considerations](#16-performance-considerations)
17. [Roadmap & Known Limitations](#17-roadmap--known-limitations)

---

## 1. Philosophy & Design Principles

### 1.1 Core Philosophy

Zed CMS is built on a **Micro-Kernel Architecture** — the core does almost nothing except:
1. Load configuration
2. Dispatch events
3. Let addons handle everything else

This is fundamentally different from WordPress's monolithic approach where core contains thousands of functions.

### 1.2 Design Principles

| Principle | Description |
|-----------|-------------|
| **Tiny Core** | Core is < 500 lines. All features come from system modules and addons. |
| **Event-Driven** | Everything communicates via events. No hard dependencies. |
| **Shared Hosting Compatible** | No Composer, no CLI requirements, works on $5/month hosting. |
| **Modern PHP** | PHP 8.2+, strict types, readonly properties, named arguments. |
| **No Frameworks** | No Laravel, Symfony, or Slim. Pure PHP for maximum control. |
| **Additive Only** | Changes should add, not modify. Existing code stays stable. |

### 1.3 Comparison with WordPress

| Aspect | WordPress | Zed CMS |
|--------|-----------|---------|
| Core size | 200,000+ lines | < 500 lines |
| Architecture | Monolithic | Micro-kernel |
| Event system | Hooks (actions/filters) | `Core\Event` (same concept, cleaner API) |
| Database | Custom abstraction | PDO wrapper |
| Templating | PHP + custom tags | Pure PHP |
| Admin | Hardcoded | Theme-based (swappable) |

---

## 2. Architecture Overview

### 2.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                         index.php                                │
│                    (Entry Point - 200 lines)                     │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                         Core\App                                 │
│                    (Micro-Kernel - 112 lines)                    │
│                                                                  │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐              │
│  │   Event     │  │   Router    │  │  Database   │              │
│  │   System    │  │   System    │  │   Layer     │              │
│  └─────────────┘  └─────────────┘  └─────────────┘              │
│                                                                  │
│  ┌─────────────┐  ┌─────────────┐                               │
│  │    Auth     │  │ Migrations  │                               │
│  │   System    │  │   System    │                               │
│  └─────────────┘  └─────────────┘                               │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                      System Modules                              │
│                   (_system/ directory)                           │
│                                                                  │
│  ┌──────────────────────┐  ┌──────────────────────┐             │
│  │   _system/admin.php   │  │ _system/frontend.php │             │
│  │                       │  │                      │             │
│  │  • RBAC (roles)       │  │  • Post Types        │             │
│  │  • Admin API          │  │  • Theme API         │             │
│  │  • Admin Routes       │  │  • Block Renderer    │             │
│  │  • Helpers            │  │  • Menu System       │             │
│  │  • Renderer           │  │  • Frontend Routes   │             │
│  └──────────────────────┘  └──────────────────────┘             │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                       User Addons                                │
│              (content/addons/*.php or */addon.php)               │
│                                                                  │
│  Examples: zed_seo, zed_contact, wiki_addon, etc.               │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                         Themes                                   │
│                  (content/themes/*)                              │
│                                                                  │
│  Frontend Theme: aurora, starter-theme, etc.                    │
│  Admin Theme: admin-default (special, cannot be frontend theme) │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Event Flow

```
index.php
    │
    ├── Load config.php
    ├── Register Core\ autoloader
    ├── Register Addons\ autoloader (NEW in v3.0)
    ├── Load _system/admin.php
    ├── Load _system/frontend.php
    ├── Load user addons
    │
    └── new Core\App($config)->run()
            │
            ├── Event::trigger('app_init')     ← Addons register hooks
            │
            ├── Database::setConfig()          ← Lazy DB connection
            │
            ├── Migrations::run()              ← Auto-upgrade system
            │
            ├── Auth::checkRememberCookie()    ← Restore session
            │
            ├── Event::trigger('app_ready')    ← Theme functions.php loads
            │
            ├── Router::dispatch($uri)         ← Route handling
            │   │
            │   ├── Event::trigger('route_request', $request)
            │   │       │
            │   │       ├── admin.php (priority 10) → handles /admin/*
            │   │       │
            │   │       └── frontend.php (priority 100) → handles /*
            │   │
            │   └── Return HTML response
            │
            ├── Event::filter('app_output')    ← Modify output
            │
            ├── echo $response                 ← Send to browser
            │
            └── Event::trigger('app_shutdown') ← Cleanup
```

---

## 3. Directory Structure

```
ZedCMS/
│
├── index.php                 # Entry point (see Section 4)
├── config.php                # Database credentials & settings
├── install.php               # One-time installer
├── cron.php                  # Scheduled tasks runner
│
├── core/                     # MICRO-KERNEL (< 500 lines total)
│   ├── App.php               # Main application class
│   ├── Event.php             # Event/hook system
│   ├── Router.php            # URL routing
│   ├── Database.php          # PDO wrapper
│   ├── Auth.php              # Authentication
│   └── Migrations.php        # Auto-upgrade system
│
├── content/
│   │
│   ├── addons/               # ALL FEATURES LIVE HERE
│   │   │
│   │   ├── _system/          # PROTECTED SYSTEM MODULES
│   │   │   │
│   │   │   ├── admin.php     # Admin entry point
│   │   │   ├── admin/        # Admin sub-modules
│   │   │   │   ├── rbac.php      # Role-based access control
│   │   │   │   ├── api.php       # AJAX, notices, settings API
│   │   │   │   ├── helpers.php   # Admin utilities
│   │   │   │   ├── renderer.php  # Theme-agnostic rendering
│   │   │   │   ├── menu_registry.php # Admin menu registration API (NEW)
│   │   │   │   ├── route_registry.php # Route registration API (NEW)
│   │   │   │   ├── routes.php    # Route dispatcher (~100 lines)
│   │   │   │   └── routes/       # Modular route handlers (Legacy)
│   │   │   │       ├── auth.php      # Login, logout, security
│   │   │   │       ├── dashboard.php # Dashboard stats
│   │   │   │       ├── content.php   # Content list, editor
│   │   │   │       ├── categories.php # Categories CRUD
│   │   │   │       ├── menus.php     # Menu builder
│   │   │   │       ├── settings.php  # Settings panel
│   │   │   │       ├── users.php     # User management
│   │   │   │       ├── media.php     # Media library
│   │   │   │       ├── addons.php    # Addon manager
│   │   │   │       └── themes.php    # Theme manager
│   │   │   │
│   │   │   ├── controllers/      # Class-based Controllers (New)
│   │   │   │   ├── BaseController.php # Shared logic
│   │   │   │   ├── ContentController.php # Posts/Pages logic
│   │   │   │   └── register_routes.php # Route registration
│   │   │   │
│   │   │   ├── api/              # API Endpoints
│   │   │   │   └── media_upload.php # Unified media uploader
│   │   │   │
│   │   │   ├── frontend.php  # Frontend entry point
│   │   │   └── frontend/     # Frontend sub-modules
│   │   │       ├── options.php       # Site options API
│   │   │       ├── post_types.php    # Custom Post Types
│   │   │       ├── theme_api.php     # Theme settings & assets
│   │   │       ├── template_data.php # Template data injection
│   │   │       ├── context.php       # Context registry (replaces globals)
│   │   │       ├── renderer.php      # TipTap HTML + legacy block renderer
│   │   │       ├── menus.php         # Navigation menus
│   │   │       ├── queries.php       # Content queries
│   │   │       ├── theme_parts.php   # Template partials
│   │   │       ├── seo_head.php      # SEO metadata
│   │   │       ├── routes.php        # Frontend controller
│   │   │       └── helpers_*.php     # 15 helper files
│   │   │
│   │   ├── zed_seo/          # User addon (folder-based)
│   │   │   ├── addon.php     # Entry point
│   │   │   └── src/          # Auto-loaded classes
│   │   │
│   │   ├── zed_contact/      # User addon
│   │   └── *.php             # Single-file addons
│   │
│   ├── themes/
│   │   ├── admin-default/    # Admin panel theme
│   │   ├── aurora/           # Frontend theme
│   │   └── starter-theme/    # Minimal starter
│   │
│   └── docs/                 # Documentation
│
├── uploads/                  # User-uploaded media (YYYY/MM structure)
│
├── _frontend/                # React/Vite TipTap Editor
│   ├── package.json
│   ├── vite.config.js
│   └── src/
│       ├── main.jsx          # React entry point
│       ├── editor.css        # Editor styles
│       └── components/
│           └── zed-editor.jsx # Main TipTap editor component
│
└── Documentation files...
    ├── ARCHITECTURE.md       # This file
    ├── DOCS.md               # API Reference
    ├── ADDON_DEVELOPMENT.md  # Addon guide
    ├── CONTRIBUTING.md       # Contribution guide
    ├── CHANGELOG.md          # Version history
    └── README.md             # Getting started
```

---

## 4. Request Lifecycle

### 4.1 index.php — The Entry Point

```php
<?php
// 1. Load configuration
$config = require __DIR__ . '/config.php';

// 2. Core namespace autoloader
spl_autoload_register(function (string $class): void {
    // Core\App → core/App.php
    if (str_starts_with($class, 'Core\\')) {
        require __DIR__ . '/core/' . substr($class, 5) . '.php';
    }
});

// 3. Addons namespace autoloader (NEW in v3.0)
spl_autoload_register(function (string $class): void {
    // Addons\ZedSEO\Sitemap → content/addons/zed_seo/src/Sitemap.php
    if (str_starts_with($class, 'Addons\\')) {
        // Convert PascalCase to snake_case for folder
        // Load from src/ subdirectory
    }
});

// 4. Load system modules (always, in order)
require_once 'content/addons/_system/admin.php';
require_once 'content/addons/_system/frontend.php';

// 5. Load user addons (respects active_addons option)
foreach (glob('content/addons/*.php') as $addon) {
    require_once $addon;
}

// 6. Run the application
$app = new \Core\App($config);
$app->run();
```

### 4.2 Core\App::run() — The Heart

```php
public function run(): void
{
    // 1. Addons register their hooks
    Event::trigger('app_init', $this);
    
    // 2. Store DB config (lazy connection)
    Database::setConfig($this->config('database'));
    
    // 3. Run any pending migrations
    Migrations::run();
    
    // 4. Restore session from remember-me cookie
    Auth::checkRememberCookie();
    
    // 5. System is ready — theme functions.php loads here
    Event::trigger('app_ready', $this);
    
    // 6. Handle the request
    $uri = Router::getCurrentUri();
    $method = Router::getCurrentMethod();
    $response = Router::dispatch($uri, $method);
    
    // 7. Send response
    if ($response !== null) {
        $response = Event::filter('app_output', $response);
        echo $response;
    }
    
    // 8. Cleanup
    Event::trigger('app_shutdown', $this);
}
```

### 4.3 Router::dispatch() — Request Handling

The router triggers a `route_request` event. Multiple listeners can respond:

```php
// In Router::dispatch()
$request = ['uri' => $uri, 'method' => $method];
Event::trigger('route_request', $request);

// Listeners set response via Router::setHandled($html)
if (self::$handled) {
    return self::$response;
}
```

**Priority matters:**
- `admin.php` registers at **priority 10** (runs first)
- `frontend.php` registers at **priority 100** (fallback)

---

## 5. Core Classes

### 5.1 Core\Event — The Backbone

The event system enables decoupled communication between components.

```php
// Register a listener
Event::on('event_name', function($arg1, $arg2) {
    // Do something
}, priority: 10);

// Trigger an event
Event::trigger('event_name', $arg1, $arg2);

// Filter a value through multiple listeners
$filtered = Event::filter('filter_name', $originalValue, $context);
```

**Key Events:**

| Event | When | Purpose |
|-------|------|---------|
| `app_init` | Boot | Register hooks, not execute code |
| `app_ready` | After DB | Theme functions.php loads |
| `route_request` | URL handling | Addons claim routes |
| `app_output` | Before echo | Modify final HTML |
| `app_shutdown` | End | Cleanup, logging |
| `zed_head` | In theme | Inject `<head>` content |
| `zed_before_content` | In theme | Before post content |
| `zed_after_content` | In theme | After post content |
| `zed_post_saved` | Admin | After content save |

### 5.2 Core\Router — URL Routing

```php
// Get current request info
$uri = Router::getCurrentUri();     // '/blog/my-post'
$method = Router::getCurrentMethod(); // 'GET'
$base = Router::getBasePath();      // '/ZedCMS' or ''

// Mark request as handled
Router::setHandled($htmlResponse);

// Check if handled
if (Router::isHandled()) { ... }

// Redirect
Router::redirect('/admin/login');
```

### 5.3 Core\Database — PDO Wrapper

```php
$db = Database::getInstance();

// Query returning rows
$posts = $db->query(
    "SELECT * FROM zed_content WHERE type = :type",
    ['type' => 'post']
);

// Query returning single row
$user = $db->queryOne(
    "SELECT * FROM users WHERE id = :id",
    ['id' => 5]
);

// Query returning single value
$count = $db->queryValue(
    "SELECT COUNT(*) FROM zed_content"
);

// Get raw PDO
$pdo = $db->getPdo();
```

### 5.4 Core\Auth — Authentication

```php
// Check if logged in
if (Auth::check()) {
    $user = Auth::user();
    echo $user['email'];
    echo $user['role'];  // 'admin', 'editor', 'author'
}

// Login
Auth::login($userId, $rememberMe = false);

// Logout
Auth::logout();

// Verify password
if (Auth::verifyPassword($password, $hash)) { ... }
```

### 5.5 Core\Migrations — Auto-Upgrade

Migrations run automatically on every request. They're tracked in `zed_options`.

```php
// In core/Migrations.php
private static array $migrations = [
    '2024_12_01_add_plain_text_column' => function() {
        $db = Database::getInstance();
        $db->query("ALTER TABLE zed_content ADD COLUMN plain_text LONGTEXT");
    },
];
```

---

## 6. System Modules

System modules live in `content/addons/_system/` and **cannot be disabled**.

### 6.1 _system/admin.php

Entry point for the admin system. Loads sub-modules:

```php
require_once __DIR__ . '/admin/rbac.php';     // Roles & permissions
require_once __DIR__ . '/admin/api.php';      // AJAX, notices, settings
require_once __DIR__ . '/admin/helpers.php';  // Utilities
require_once __DIR__ . '/admin/renderer.php'; // View rendering
require_once __DIR__ . '/admin/routes.php';   // Route handlers
```

### 6.2 _system/frontend.php

Entry point for the frontend system. Loads sub-modules:

```php
// Core modules (in dependency order)
require_once __DIR__ . '/frontend/options.php';
require_once __DIR__ . '/frontend/post_types.php';
require_once __DIR__ . '/frontend/theme_api.php';
require_once __DIR__ . '/frontend/template_data.php';
require_once __DIR__ . '/frontend/renderer.php';
require_once __DIR__ . '/frontend/menus.php';
require_once __DIR__ . '/frontend/queries.php';
require_once __DIR__ . '/frontend/theme_parts.php';
require_once __DIR__ . '/frontend/seo_head.php';

// Helper files
foreach ($helpers as $helper) {
    require_once __DIR__ . '/frontend/' . $helper;
}

// Route handler (must be last)
require_once __DIR__ . '/frontend/routes.php';
```

---

## 7. Frontend System

### 7.1 Frontend Controller Pattern

The frontend uses a "Single Source of Truth" pattern. All routing flows through one controller.

**File:** `_system/frontend/routes.php`

```php
Event::on('route_request', function (array $request): void {
    // 1. THE BRAIN — Identify what user wants
    $slug = trim($request['uri'], '/');
    $isHome = ($slug === '');
    
    // 2. THE FETCH — Get data into $zed_query
    global $zed_query;
    $zed_query = [
        'type' => null,    // 'home', 'single', 'page', 'archive', '404'
        'object' => null,  // Single post/page
        'posts' => [],     // For archives
        'pagination' => [...],
    ];
    
    // ... fetch from database based on URL ...
    
    // 3. THE PREPARATION — Standardize for themes
    global $post, $posts, $is_404, $is_home, $is_single;
    global $htmlContent, $base_url;
    
    $post = $zed_query['object'];
    $posts = $zed_query['posts'];
    $is_404 = ($zed_query['type'] === '404');
    // ...
    
    // 4. THE HANDOFF — Select template
    $template = match($zed_query['type']) {
        '404' => '404.php',
        'home' => 'home.php',
        'page' => 'page.php',
        'single' => 'single.php',
        'archive' => 'archive.php',
        default => 'index.php',
    };
    
    // 5. EXECUTE — Render template
    ob_start();
    include $themePath . '/' . $template;
    $html = ob_get_clean();
    
    Router::setHandled($html);
    
}, 100); // Priority 100 = runs after admin
```

### 7.2 Global Variables for Themes

These are set by the frontend controller and available in all templates:

| Variable | Type | Description |
|----------|------|-------------|
| `$post` | `array\|null` | Current post/page data |
| `$posts` | `array` | Archive items |
| `$htmlContent` | `string` | Rendered TipTap/ProseMirror content |
| `$is_home` | `bool` | Is homepage? |
| `$is_single` | `bool` | Is single post? |
| `$is_page` | `bool` | Is static page? |
| `$is_archive` | `bool` | Is archive listing? |
| `$is_404` | `bool` | Is 404 page? |
| `$base_url` | `string` | Site base path |
| `$page_num` | `int` | Current page number |
| `$total_pages` | `int` | Total pagination pages |

### 7.3 Content Renderer

**New in v3.1.0:** Content is stored as HTML directly from the TipTap editor. The renderer handles both new HTML content and legacy block-based JSON for backwards compatibility.

**File:** `_system/frontend/renderer.php`

```php
function zed_render_content(array $post): string {
    $data = $post['data'] ?? [];
    if (is_string($data)) {
        $data = json_decode($data, true) ?: [];
    }
    
    $content = $data['content'] ?? '';
    
    // New TipTap content is HTML string
    if (is_string($content) && !empty($content)) {
        return $content;  // Already HTML
    }
    
    // Legacy BlockNote content is array of blocks
    if (is_array($content)) {
        return render_blocks($content);  // Convert to HTML
    }
    
    return '';
}
```

> **Note:** The `render_blocks()` function is kept for backwards compatibility with content created before v3.1.0.

### 7.4 Custom Post Types

**File:** `_system/frontend/post_types.php`

```php
// Register in theme's functions.php
zed_register_post_type('portfolio', [
    'label' => 'Portfolio',
    'singular' => 'Project',
    'icon' => 'work',
    'supports' => ['title', 'editor', 'featured_image'],
]);

// Query
$types = zed_get_post_types();
$config = zed_get_post_type('portfolio');
```

### 7.5 Theme API

**File:** `_system/frontend/theme_api.php`

```php
// Register theme settings (in functions.php)
zed_add_theme_setting('brand_color', 'Brand Color', 'color', '#6366f1');
zed_add_theme_setting('show_author', 'Show Author', 'checkbox', true);

// Get setting value (in templates)
$color = zed_theme_option('brand_color');
```

### 7.6 Menu System

**File:** `_system/frontend/menus.php`

```php
// In theme templates
echo zed_menu('Main Menu');              // By name
echo zed_menu(1);                        // By ID
echo zed_menu('Main Menu', ['class' => 'nav-primary']);

// Get raw menu data
$menu = zed_get_menu_by_name('Main Menu');
$items = $menu['items']; // Array of menu items
```

---

## 8. Admin System

### 8.1 Admin Routes

**File:** `_system/admin/routes.php` (~2400 lines)

Handles all `/admin/*` and `/api/*` routes:

```
/admin/login           → Login page
/admin                 → Dashboard
/admin/content         → Content library
/admin/editor          → Post/page editor
/admin/editor?id=5     → Edit existing
/admin/media           → Media library
/admin/users           → User management
/admin/settings        → Site settings
/admin/addons          → Addon manager
/admin/themes          → Theme manager
/admin/menus           → Menu editor

/admin/api/save        → Save content (POST)
/admin/api/upload      → Upload media (POST)
/admin/api/delete      → Delete content (POST)
/admin/api/toggle-addon → Enable/disable addon (POST)
```

### 8.2 RBAC System

**File:** `_system/admin/rbac.php`

```php
// Roles and their capabilities
$roles = [
    'administrator' => ['*'],  // All capabilities
    'editor' => [
        'edit_posts', 'edit_pages', 'edit_others_posts',
        'publish_posts', 'manage_categories',
    ],
    'author' => [
        'edit_posts', 'publish_posts', 'upload_files',
    ],
    'contributor' => [
        'edit_posts',  // Can only edit own drafts
    ],
];

// Check permission
if (zed_current_user_can('edit_posts')) { ... }
if (zed_current_user_can('manage_addons')) { ... }

// Check admin access
if (zed_user_can_access_admin()) { ... }
```

### 8.3 Admin API

**File:** `_system/admin/api.php`

```php
// Site options
$value = zed_get_option('site_title', 'Default');
zed_set_option('site_title', 'My Site');

// Admin notices
zed_add_notice('Settings saved!', 'success');
zed_add_notice('Error occurred', 'error');

// Addon settings
zed_register_addon_settings('my_addon', [
    'title' => 'My Addon Settings',
    'fields' => [
        ['id' => 'api_key', 'type' => 'text', 'label' => 'API Key'],
        ['id' => 'enabled', 'type' => 'toggle', 'label' => 'Enable'],
    ],
]);

// Get addon option
$key = zed_get_addon_option('my_addon', 'api_key', 'default');

// Register AJAX handler
zed_register_ajax('my_action', function($data) {
    return ['success' => true, 'result' => $data];
}, require_auth: true);
```

### 8.4 Admin Renderer

**File:** `_system/admin/renderer.php`

Theme-agnostic rendering for admin pages:

```php
$content = AdminRenderer::renderPage('content-list', [
    'posts' => $posts,
    'pagination' => $pagination,
], [
    'current_page' => 'content',
    'page_title' => 'Content Library',
]);

Router::setHandled($content);
```

### 8.5 Admin Menu Registration API (NEW in v3.0)

**File:** `_system/admin/menu_registry.php`

Enables addons to register admin menu items without modifying core files.

```php
// Register a top-level menu
zed_register_admin_menu([
    'id' => 'my_addon',
    'title' => 'My Addon',
    'icon' => 'settings',           // Material Symbols icon
    'capability' => 'manage_options',
    'position' => 30,                // Lower = higher in menu
    'badge' => '5',                  // Optional notification badge
    'callback' => function() {
        echo '<h1>My Addon Page</h1>';
    }
]);

// Register a submenu
zed_register_admin_submenu('my_addon', [
    'id' => 'my_addon_logs',
    'title' => 'Logs',
    'capability' => 'view_logs',
    'callback' => fn() => echo '<h1>Logs</h1>'
]);

// Register custom capabilities
zed_register_capabilities([
    'manage_my_addon' => 'Manage My Addon',
    'view_logs' => 'View Logs',
]);
```

**Features:**
- ✅ Automatic route registration
- ✅ Permission checks
- ✅ Admin layout wrapping
- ✅ Badge support
- ✅ Auto-hide when addon disabled
- ✅ Submenu support

### 8.6 Route Registration API (NEW in v3.0)

**File:** `_system/admin/route_registry.php`

Enables addons to register custom routes without modifying core files.

```php
// Basic route
zed_register_route([
    'path' => '/admin/my-page',
    'callback' => function() {
        return '<h1>My Page</h1>';
    }
]);

// Pattern matching
zed_register_route([
    'path' => '/admin/reports/{type}',
    'capability' => 'view_reports',
    'callback' => function($request, $uri, $params) {
        $type = $params['type'];  // Extracted from URL
        return "<h1>Report: {$type}</h1>";
    }
]);

// API endpoint (no layout)
zed_register_route([
    'path' => '/admin/api/my-action',
    'method' => 'POST',
    'wrap_layout' => false,
    'callback' => function() {
        header('Content-Type: application/json');
        return json_encode(['success' => true]);
    }
]);
```

**Features:**
- ✅ Pattern matching (`{param}` syntax)
- ✅ Multiple HTTP methods
- ✅ Automatic permission checks
- ✅ Automatic layout wrapping
- ✅ Priority system
- ✅ Integrates with Menu API

**Route Priority:**
```
1. zed_handle_registered_routes()    ← Highest (addons)
2. zed_handle_dashboard_route()      ← System routes
3. zed_handle_content_routes()
4. ... other handlers
```

---

## 9. Theme System

### 9.1 Theme Structure

```
content/themes/aurora/
├── theme.json          # Theme metadata
├── functions.php       # Theme functions (auto-loaded)
├── screenshot.png      # Preview image
├── style.css           # (optional) styles
│
├── index.php           # Ultimate fallback
├── home.php            # Homepage template
├── single.php          # Single post
├── page.php            # Static page
├── archive.php         # Archive listing
├── 404.php             # Error page
│
├── templates/          # Custom page templates
│   └── full-width.php
│
├── parts/              # Reusable partials
│   ├── head.php
│   ├── header.php
│   └── footer.php
│
└── assets/             # Theme assets
    ├── css/
    ├── js/
    └── images/
```

### 9.2 theme.json

```json
{
    "name": "Aurora",
    "version": "1.0.0",
    "author": "Zed CMS Team",
    "description": "Modern blog theme",
    "settings": {
        "brand_color": "#6366f1",
        "background": "#ffffff"
    },
    "required_addons": ["zed_seo"]
}
```

### 9.3 functions.php

Auto-loaded on `app_ready` event:

```php
<?php
// Register theme requirements
zed_register_theme_requirements([
    'required_addons' => ['zed_seo'],
]);

// Register custom post types
zed_register_post_type('portfolio', 'Portfolio', 'work');

// Register theme settings
zed_add_theme_setting('accent_color', 'Accent Color', 'color', '#4f46e5');

// Add hook
Event::on('zed_head', function() {
    echo '<link rel="stylesheet" href="...">';
});
```

### 9.4 Template Example

```php
<?php
// single.php
global $post, $htmlContent, $base_url, $is_404;

if ($is_404) {
    include __DIR__ . '/404.php';
    return;
}

$data = json_decode($post['data'], true);
$title = htmlspecialchars($post['title']);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= zed_page_title($title) ?></title>
    <?php Event::trigger('zed_head'); ?>
</head>
<body>
    <?php zed_include_theme_part('header'); ?>
    
    <article>
        <h1><?= $title ?></h1>
        
        <?php Event::trigger('zed_before_content', $post); ?>
        
        <div class="zed-content">
            <?= $htmlContent ?>
        </div>
        
        <?php Event::trigger('zed_after_content', $post); ?>
    </article>
    
    <?php zed_include_theme_part('footer'); ?>
</body>
</html>
```

### 9.5 Template Hierarchy

When rendering content, templates are checked in order:

**For Single Post (type: portfolio):**
1. `single-portfolio.php`
2. `single.php`
3. `index.php`

**For Archive (type: portfolio):**
1. `archive-portfolio.php`
2. `archive.php`
3. `index.php`

**For Page with custom template "landing":**
1. `templates/landing.php`
2. `page.php`
3. `single.php`
4. `index.php`

---

## 10. Addon System

### 10.1 Addon Types

**Single-File Addon:**
```
content/addons/my_addon.php
```

**Folder-Based Addon:**
```
content/addons/my_addon/
├── addon.php       # Entry point (required)
├── README.md
├── src/            # Auto-loaded classes
│   └── Helper.php
└── assets/
    └── script.js
```

### 10.2 Addon Header

```php
<?php
/**
 * Addon Name: My Awesome Addon
 * Description: Does something amazing
 * Version: 1.0.0
 * Author: Your Name
 * License: MIT
 */
```

### 10.3 Class Autoloading

Classes in `src/` are auto-loaded:

```php
// File: content/addons/zed_seo/src/Sitemap.php
namespace Addons\ZedSEO;

class Sitemap {
    public function generate(): string { ... }
}

// Usage anywhere:
$sitemap = new \Addons\ZedSEO\Sitemap();
```

**Naming Convention:**
- Folder: `zed_seo` (snake_case)
- Namespace: `Addons\ZedSEO` (PascalCase)

### 10.4 Addon Lifecycle

```php
<?php
use Core\Event;
use Core\Router;

// 1. Register hooks on load (runs during index.php)
Event::on('app_init', function() {
    // Register settings, post types, etc.
});

// 2. Execute logic when app is ready
Event::on('app_ready', function() {
    // Database is available, theme is loaded
});

// 3. Register routes
Event::on('route_request', function($request) {
    if ($request['uri'] === '/my-addon-page') {
        Router::setHandled('<h1>My Addon Page</h1>');
    }
}, 50); // Priority between admin (10) and frontend (100)

// 4. Filter content
Event::on('app_output', function($html) {
    return str_replace('foo', 'bar', $html);
});
```

### 10.5 Addon APIs

```php
// Register shortcode
zed_register_shortcode('my_widget', function($attrs) {
    $title = $attrs['title'] ?? 'Default';
    return "<div class='widget'>{$title}</div>";
});
// Usage in content: [my_widget title="Hello"]

// Register AJAX endpoint
zed_register_ajax('my_action', function($data) {
    return ['result' => $data['input'] * 2];
}, require_auth: false);
// Called via: POST /api/ajax/my_action

// Enqueue scripts
zed_enqueue_script('my-script', '/content/addons/my_addon/assets/script.js');
```

---

## 11. Database Schema

### 11.1 Core Tables

```sql
-- Content storage (posts, pages, custom types)
CREATE TABLE zed_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    type VARCHAR(50) DEFAULT 'post',      -- 'post', 'page', 'portfolio', etc.
    data JSON,                             -- TipTap HTML content, status, etc.
    plain_text LONGTEXT,                   -- Searchable text extraction
    author_id INT,
    created_at DATETIME,
    updated_at DATETIME,
    INDEX idx_type (type),
    INDEX idx_slug (slug),
    FULLTEXT idx_search (title, plain_text)
);

-- Site options
CREATE TABLE zed_options (
    id INT PRIMARY KEY AUTO_INCREMENT,
    option_name VARCHAR(191) NOT NULL UNIQUE,
    option_value LONGTEXT,
    autoload TINYINT(1) DEFAULT 1,
    INDEX idx_autoload (autoload)
);

-- Users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'author',
    remember_token VARCHAR(255) NULL,
    created_at DATETIME,
    updated_at DATETIME
);

-- Navigation menus
CREATE TABLE zed_menus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    items JSON,                            -- Nested menu structure
    created_at DATETIME,
    updated_at DATETIME
);

-- Categories/Tags
CREATE TABLE zed_terms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    taxonomy VARCHAR(50) DEFAULT 'category',
    parent_id INT DEFAULT 0,
    description TEXT
);

-- Content-Term relationships
CREATE TABLE zed_term_relationships (
    content_id INT,
    term_id INT,
    PRIMARY KEY (content_id, term_id)
);

-- Content revisions
CREATE TABLE zed_content_revisions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content_id INT NOT NULL,
    data_json JSON,
    author_id INT,
    created_at DATETIME,
    INDEX idx_content (content_id)
);

-- Media library (WordPress-style)
CREATE TABLE zed_media (
    id INT PRIMARY KEY AUTO_INCREMENT,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,       -- Relative path: YYYY/MM/file.ext
    url VARCHAR(500) NOT NULL,             -- Full URL
    thumbnail_url VARCHAR(500),            -- 150x150
    medium_url VARCHAR(500),               -- 300x300
    large_url VARCHAR(500),                -- 1024x1024
    file_size INT DEFAULT 0,
    mime_type VARCHAR(100),
    width INT,
    height INT,
    alt_text VARCHAR(255),
    caption TEXT,
    uploaded_by INT,
    uploaded_at DATETIME,
    updated_at DATETIME
);
```

### 11.2 Data Column Structure

The `data` column in `zed_content` stores JSON:

```json
{
    "content": [
        {
            "id": "abc123",
            "type": "paragraph",
            "props": {"textAlignment": "left"},
            "content": [{"type": "text", "text": "Hello world"}]
        },
        {
            "id": "def456",
            "type": "heading",
            "props": {"level": 2},
            "content": [{"type": "text", "text": "Section Title"}]
        }
    ],
    "status": "published",
    "featured_image": "/content/uploads/hero.webp",
    "excerpt": "A brief summary...",
    "template": "default",
    "categories": [1, 3]
}
```

---

## 12. API Reference

### 12.1 Content Functions

```php
// Get posts
$posts = zed_get_posts([
    'type' => 'post',
    'status' => 'published',
    'limit' => 10,
    'offset' => 0,
    'orderby' => 'created_at',
    'order' => 'DESC',
]);

// Get single post
$post = zed_get_post($id);
$post = zed_get_post_by_slug('my-post');

// Get latest
$latest = zed_get_latest_posts(5);

// Count
$count = zed_count_published_posts();
```

### 12.2 Taxonomy Functions

```php
// Get categories
$categories = zed_get_categories();

// Get post categories
$cats = zed_get_post_categories($post_id);

// Get posts in category
$posts = zed_get_posts_by_category($category_id);
```

### 12.3 Media Functions

```php
// Get featured image
$url = zed_get_featured_image($post);
$thumb = zed_get_thumbnail($post, 'medium');
```

### 12.4 URL Functions

```php
$base = zed_base_url();           // '/ZedCMS' or ''
$theme = zed_theme_url();         // '/ZedCMS/content/themes/aurora'
$admin = zed_admin_url();         // '/ZedCMS/admin'
$post = zed_post_url($post);      // '/ZedCMS/my-post-slug'
```

### 12.5 Utility Functions

```php
// Time
$ago = zed_time_ago($datetime);   // '2 hours ago'
$mins = zed_reading_time($html);  // 5

// Text
$short = zed_truncate($text, 150);
$clean = zed_strip_blocks($content);
```

### 12.6 Conditional Functions

```php
if (zed_is_home()) { ... }
if (zed_is_single()) { ... }
if (zed_is_page()) { ... }
if (zed_is_archive()) { ... }
if (zed_is_admin()) { ... }
```

---

## 13. Security Model

### 13.1 Authentication

- Passwords hashed with `password_hash()` (bcrypt)
- Sessions stored server-side
- Remember-me uses secure tokens in `remember_token` column
- CSRF protection via nonces

### 13.2 CSRF Protection

```php
// Generate nonce (in admin pages)
$nonce = zed_create_nonce('save_post');

// In JavaScript
headers: { 'X-ZED-NONCE': window.ZED_NONCE }

// Verify in AJAX handler
if (!zed_verify_nonce($_POST['nonce'], 'save_post')) {
    die('Invalid nonce');
}
```

### 13.3 Input Sanitization

```php
// Always escape output
echo htmlspecialchars($title);

// Use prepared statements
$db->query("SELECT * FROM posts WHERE id = :id", ['id' => $id]);

// Never use raw $_GET/$_POST in queries
```

### 13.4 File Upload Security

```php
// Validate MIME type
$allowed = ['image/jpeg', 'image/png', 'image/webp'];
$mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file);
if (!in_array($mime, $allowed)) { die('Invalid file'); }

// Sanitize filename
$safe = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);

// Store outside web root or with random names
```

---

## 14. Best Practices

### 14.1 For Core Development

1. **Never modify core files** — Use events to extend
2. **Keep core tiny** — If it's > 100 lines, it's a module
3. **Lazy loading** — Database connects only when needed
4. **Graceful degradation** — If feature fails, site still works

### 14.2 For Addon Development

1. **Prefix everything** — `myaddon_function()`, `.myaddon-class`
2. **Use Addons namespace** — `Addons\MyAddon\ClassName`
3. **Register, don't execute** — Do work in hooks, not on load
4. **Respect priorities** — Admin is 10, frontend is 100
5. **Document your addon** — README.md with usage examples

### 14.3 For Theme Development

1. **Use global variables** — They're set by the controller
2. **Include parts** — `zed_include_theme_part('header')`
3. **Trigger events** — `Event::trigger('zed_head')`
4. **Use helper functions** — Don't query database directly
5. **Escape output** — `htmlspecialchars()` always

### 14.4 For Frontend (React/Vite Editor)

The `_frontend/` directory contains the optional React-based editor:

1. **Build command:** `npm run build` in `_frontend/`
2. **Output:** `_frontend/dist/` — referenced by admin editor page
3. **Communication:** Uses `/admin/api/save` endpoint
4. **TipTap format:** HTML string stored in `data.content`

---

## 15. Caching API

Zed CMS includes a file-based caching system with a WordPress-like transients API.

### 15.1 Basic Usage

```php
// Store a value (expires in 1 hour)
zed_cache_set('my_data', $data, 3600);

// Retrieve a value
$data = zed_cache_get('my_data', $default);

// Delete a cached value
zed_cache_delete('my_data');

// Clear all cache
zed_cache_flush();
```

### 15.2 Remember Pattern

The most useful pattern for expensive operations:

```php
// Compute once, cache for 1 hour
$posts = zed_cache_remember('homepage_posts', function() {
    return zed_get_latest_posts(10);
}, 3600);
```

### 15.3 Cache in Themes

```php
// In theme templates - cache expensive sidebar data
$sidebar_data = zed_cache_remember('sidebar_' . $post['id'], function() use ($post) {
    return [
        'related' => zed_get_related_posts($post, 5),
        'categories' => zed_get_categories(),
    ];
}, 1800);
```

### 15.4 Cache Management

```php
// Check if cached
if (zed_cache_has('my_key')) { ... }

// Get cache statistics
$stats = zed_cache_stats();
// Returns: ['files' => 42, 'size' => 1048576, 'size_human' => '1 MB']
```

### 15.5 Cache Configuration

In `config.php` or early in `index.php`:

```php
define('ZED_CACHE_ENABLED', true);  // Enable/disable cache
define('ZED_CACHE_DIR', __DIR__ . '/cache');  // Cache directory
```

---

## 16. Performance Considerations

### 16.1 Migrations

Migrations now only run when the version changes:

```php
// In Core\App::run()
if (Migrations::needsMigration()) {
    Migrations::run();
}
```

This is a fast single-DB-query check that prevents migration overhead on every request.

### 16.2 Addon Loading

Active addons are filtered via the `active_addons` option:

```php
// Only active addons are loaded
if ($activeAddonsList !== null && !in_array($addonBasename, $activeAddonsList)) {
    continue;
}
```

### 16.3 Database Query Optimization

- Use `zed_cache_remember()` for expensive queries
- The `plain_text` column enables fast full-text search
- JSON queries on `data` column use indexed extraction

### 16.4 Recommended Caching Strategy

| Data | TTL | Example |
|------|-----|---------|
| Site options | 1 hour | `zed_cache_remember('site_options', ...)` |
| Menu HTML | 1 hour | `zed_cache_remember('menu_main', ...)` |
| Post queries | 15 min | `zed_cache_remember('latest_posts', ...)` |
| User data | 5 min | `zed_cache_remember('user_' . $id, ...)` |

---

## 17. Roadmap & Known Limitations

### 17.1 Current Limitations

| Limitation | Workaround |
|------------|------------|
| No object cache | Use file-based `zed_cache_*()` functions |
| No CLI tools | Use `?run_migrations=1` in admin or web-based tools |
| No i18n/l10n | Planned for v3.1 |

### 17.2 Completed (v3.0)

- ✅ **Modular admin routes** — Split 2400-line routes.php into 11 focused modules
- ✅ **Caching API** — File-based `zed_cache_*()` functions
- ✅ **Migration optimization** — Only runs when version changes
- ✅ **Context Registry** — `zed_context()` replaces globals, IDE-friendly with autocomplete
- ✅ **Admin Menu Registry** — Addons can register admin pages and sidebar menus via `zed_register_admin_menu()`

### 17.3 Planned Features

- **v3.1:** Internationalization (i18n) support
- **v3.1:** Webhook system for integrations
- **v3.2:** Route compilation (cached event listeners)
- **v3.3:** Multi-site support

### 17.4 Performance Benchmarks

Target performance (on shared hosting):
- Homepage load: < 200ms
- Admin dashboard: < 300ms
- API responses: < 100ms

For high-traffic sites, consider:
- Object caching via Redis addon
- Full-page cache via edge CDN
- Database query caching

---

## 18. TipTap Editor System

### 18.1 Overview

Zed CMS v3.1.0 uses **TipTap** (a ProseMirror-based rich text editor) for content editing. The editor is built with React and compiled via Vite.

**File:** `_frontend/src/components/zed-editor.jsx`

### 18.2 Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                      TipTap Editor Stack                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────┐    ┌────────────────┐    ┌───────────────┐  │
│  │   React 18     │───▶│    TipTap      │───▶│  ProseMirror  │  │
│  │   Component    │    │   Extensions   │    │     Core      │  │
│  └────────────────┘    └────────────────┘    └───────────────┘  │
│                                │                                 │
│                                ▼                                 │
│          ┌─────────────────────────────────────────┐            │
│          │           Custom Extensions              │            │
│          │  • Slash Commands (/)                    │            │
│          │  • Callout Blocks                        │            │
│          │  • YouTube Embeds                        │            │
│          │  • Button Blocks                         │            │
│          │  • Image Controls                        │            │
│          └─────────────────────────────────────────┘            │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 18.3 Features

| Feature | Description |
|---------|-------------|
| **Bubble Menu Toolbar** | Inline formatting toolbar on text selection (Bold, Italic, Underline, Strike, Color, Highlight, etc.) |
| **Slash Commands** | Type `/` to insert blocks (headings, lists, quotes, images, etc.) |
| **Keyboard Navigation** | Arrow keys + Enter to navigate and select slash menu items |
| **Image Controls** | Resize (25%, 50%, 75%, 100%) and align (left, center, right) images |
| **Custom Blocks** | Callout, YouTube embed, Button blocks |
| **Dark Mode** | Inherits theme from admin panel |

### 18.4 Extensions

```javascript
// Core Extensions
import StarterKit from '@tiptap/starter-kit'
import Image from '@tiptap/extension-image'
import Link from '@tiptap/extension-link'
import Underline from '@tiptap/extension-underline'
import Subscript from '@tiptap/extension-subscript'
import Superscript from '@tiptap/extension-superscript'
import TextAlign from '@tiptap/extension-text-align'
import TextStyle from '@tiptap/extension-text-style'
import Highlight from '@tiptap/extension-highlight'
import { Color } from '@tiptap/extension-text-style'

// Custom Extensions (built-in)
- Callout Block
- YouTube Embed
- Button Block
- Slash Command Menu
```

### 18.5 Content Storage

Content is stored in the `zed_content.data` JSON column:

```json
{
  "content": "<p>TipTap HTML content...</p>",
  "status": "published",
  "excerpt": "Post excerpt...",
  "featured_image": "/uploads/2024/12/image.webp"
}
```

The `content` field contains rendered HTML from TipTap, making frontend rendering simple.

### 18.6 Building the Editor

```bash
cd _frontend
npm install
npm run build      # Production build → content/themes/admin-default/assets/js/
npm run dev        # Development with HMR
```

---

## Quick Reference Card

### Key Files

| File | Purpose |
|------|---------|
| `index.php` | Entry point, autoloaders, addon loading |
| `core/App.php` | Micro-kernel, event lifecycle |
| `core/Event.php` | Hook system |
| `core/Router.php` | URL handling |
| `core/Database.php` | PDO wrapper |
| `_system/admin.php` | Admin system loader |
| `_system/frontend.php` | Frontend system loader |
| `_system/frontend/routes.php` | Frontend controller |
| `_system/admin/routes.php` | Admin route handlers |
| `_system/frontend/cache.php` | Caching API |

### Key Events

| Event | When |
|-------|------|
| `app_init` | Register hooks |
| `app_ready` | Execute logic |
| `route_request` | Handle URLs |
| `zed_head` | Inject head content |
| `zed_post_saved` | After save |

### Key Functions

| Function | Purpose |
|----------|---------|
| `zed_get_posts()` | Query content |
| `zed_get_option()` | Get site setting |
| `zed_theme_option()` | Get theme setting |
| `zed_current_user_can()` | Check permission |
| `zed_register_shortcode()` | Add shortcode |
| `zed_register_ajax()` | Add AJAX endpoint |
| `zed_cache_set()` | Store in cache |
| `zed_cache_get()` | Retrieve from cache |
| `zed_cache_remember()` | Cache with callback |

---

*Document maintained by the Zed CMS Core Team*  
*For questions, see CONTRIBUTING.md or open an issue*

