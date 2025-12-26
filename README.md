# Zed CMS

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-8892BF?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Architecture-Micro--Kernel-6366f1?style=for-the-badge" alt="Micro-Kernel">
  <img src="https://img.shields.io/badge/License-MIT-22c55e?style=for-the-badge" alt="MIT License">
  <img src="https://img.shields.io/badge/Version-3.2.0-f59e0b?style=for-the-badge" alt="v3.2.0">
</p>

<p align="center">
  <strong>The Event-Driven Micro-Kernel CMS for Modern PHP</strong><br>
  <sub>Zero frameworks. Zero bloat. Pure PHP 8.2+</sub>
</p>

<p align="center">
  <a href="#-features">Features</a> •
  <a href="#-quick-start">Quick Start</a> •
  <a href="#-architecture">Architecture</a> •
  <a href="#-api-reference">API</a> •
  <a href="#-theme-development">Themes</a> •
  <a href="#-contributing">Contributing</a>
</p>

---

## ⚡ Why Zed?

**20 years of CMS bloat ends here.**

| Aspect | Zed CMS 🚀 | Legacy CMS 🐢 |
|--------|-----------|---------------|
| **Core Size** | < 500 lines | 200,000+ lines |
| **Architecture** | Event-Driven Micro-Kernel | Monolithic |
| **Boot Time** | < 20ms | 200ms+ |
| **Database** | PDO + JSON columns | Heavy ORM / EAV |
| **Editor** | BlockNote (Notion-style) | WYSIWYG clutter |
| **Widgets** | Drag-and-drop | Plugin required |
| **Comments** | Built-in moderation | Plugin required |
| **Dark Mode** | Native (Mantine) | Plugin required |

---

## 🎯 Features

### Core Platform
- ✅ **Micro-Kernel Architecture** — Core is just event dispatch
- ✅ **Event-Driven** — All features are addons listening to events
- ✅ **PHP 8.2+** — Strict types, named arguments, readonly properties
- ✅ **Zero Dependencies** — No Composer, no CLI tools needed
- ✅ **Shared Hosting Ready** — Works on $5/month hosting

### Content Management
- ✅ **Block Editor** — BlockNote Notion-style editing
- ✅ **Custom Post Types** — Posts, Pages, Portfolio, Testimonials
- ✅ **Categories & Tags** — Full taxonomy system
- ✅ **Media Library** — WebP conversion, thumbnails, folders

### Theme System (v3.2.0)
- ✅ **Comments System** — Full moderation with approval workflow
- ✅ **Widgets/Sidebars** — Drag-and-drop widget management
- ✅ **AJAX Loading** — Infinite scroll, live search, filters
- ✅ **Theme Helpers** — Reading progress, social share, author box
- ✅ **Post Formats** — Standard, Video, Gallery, Audio, Quote, Link

### Admin Panel
- ✅ **Visual Menu Builder** — Drag-and-drop navigation
- ✅ **RBAC Permissions** — Admin, Editor, Author, Contributor
- ✅ **Dark Mode** — Toggle with persistence
- ✅ **Integrated Wiki** — Documentation inside admin

---

## 🚀 Quick Start

### Requirements
- PHP 8.2+ (`pdo`, `gd`, `json` extensions)
- MySQL 5.7+ or MariaDB 10.3+

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/aamirali51/ZedCMS.git
cd ZedCMS

# 2. Configure database
cp config.sample.php config.php
# Edit config.php with your database credentials

# 3. Run installation
# Visit http://your-site.com/install.php in browser

# 4. Secure your installation
rm install.php
```

### Default Login
- **URL:** `/admin`
- **Email:** `admin@example.com`
- **Password:** Set during installation

---

## 🏗 Architecture

```
┌────────────────────────────────────────────────────────┐
│                      index.php                          │
│                   (Entry Point)                         │
└────────────────────────┬───────────────────────────────┘
                         ▼
┌────────────────────────────────────────────────────────┐
│                     Core\App                            │
│              (Micro-Kernel ~100 lines)                  │
│                                                         │
│    ┌─────────────┐  ┌─────────────┐  ┌─────────────┐   │
│    │   Event     │  │   Router    │  │  Database   │   │
│    │   System    │  │   System    │  │   (PDO)     │   │
│    └─────────────┘  └─────────────┘  └─────────────┘   │
└────────────────────────┬───────────────────────────────┘
                         ▼
┌────────────────────────────────────────────────────────┐
│                    System Addons                        │
│                                                         │
│  ┌──────────────────┐    ┌──────────────────┐          │
│  │  _system/admin   │    │ _system/frontend │          │
│  │                  │    │                  │          │
│  │  • RBAC          │    │  • Post Types    │          │
│  │  • Routes        │    │  • Comments      │          │
│  │  • API           │    │  • Widgets       │          │
│  │  • Media         │    │  • AJAX API      │          │
│  └──────────────────┘    └──────────────────┘          │
└────────────────────────────────────────────────────────┘
```

### Request Lifecycle

1. **`index.php`** — Load config, autoload classes
2. **`Core\App::run()`** — Fire `app_init` event
3. **Addons register** — Hook into `route_request`
4. **`Core\Router`** — Parse URI, fire `route_request`
5. **Addon claims route** — Call `Router::setHandled($html)`
6. **Response sent** — HTML output to browser

---

## 📚 API Reference

### Content Functions
```php
// Get posts
$posts = zed_get_posts([
    'type' => 'post',
    'status' => 'published',
    'limit' => 10,
    'category' => 5,
]);

// Get single post
$post = zed_get_post($id);
$post = zed_get_post_by_slug('hello-world');

// Get permalink
$url = zed_get_permalink($post);
```

### Comments System (v3.2.0)
```php
// Display comments
<?php if (zed_comments_open($post)): ?>
    <h3><?= zed_comment_count($post['id']) ?> Comments</h3>
    <?php zed_comments_list($post['id']); ?>
    <?php zed_comment_form($post['id']); ?>
<?php endif; ?>

// Submit comment (API)
$result = zed_submit_comment([
    'post_id' => 123,
    'author_name' => 'John',
    'author_email' => 'john@example.com',
    'content' => 'Great article!',
]);
```

### Widgets/Sidebars (v3.2.0)
```php
// Register sidebar in functions.php
zed_register_sidebar('main-sidebar', [
    'name' => 'Main Sidebar',
    'description' => 'Appears on blog pages',
    'before_widget' => '<div class="widget %2$s">',
    'after_widget' => '</div>',
]);

// Display in template
<?php if (zed_is_active_sidebar('main-sidebar')): ?>
    <aside class="sidebar">
        <?php zed_dynamic_sidebar('main-sidebar'); ?>
    </aside>
<?php endif; ?>

// Register custom widget
zed_register_widget('my-widget', [
    'name' => 'My Widget',
    'callback' => function($sidebar, $instance) {
        echo '<p>Hello from widget!</p>';
    },
]);
```

### Theme Helpers (v3.2.0)
```php
// Reading progress bar
<?php zed_reading_progress(['color' => '#6366f1']); ?>

// Social share buttons
<?php zed_social_share($post, ['style' => 'buttons']); ?>

// Author bio box
<?php zed_author_box($post); ?>

// Reading time
$time = zed_reading_time($post);
echo $time['text']; // "5 min read"

// Post navigation (prev/next)
<?php zed_post_navigation($post); ?>

// Breadcrumbs
<?php zed_breadcrumbs([
    ['label' => 'Blog', 'url' => '/blog'],
    ['label' => $post['title']],
]); ?>

// Post formats
$format = zed_get_post_format($post); // 'standard', 'video', 'gallery', etc.
```

### AJAX Loading (v3.2.0)
```html
<script src="/addons/_system/assets/js/zed-frontend.js"></script>
<script>
// Infinite scroll
Zed.infiniteScroll({
    container: '.posts-grid',
    url: '/api?action=get_posts',
    render: (post) => `
        <article>
            <h2><a href="${post.url}">${post.title}</a></h2>
            <p>${post.excerpt}</p>
        </article>
    `,
});

// Live search
Zed.liveSearch({
    input: '#search-input',
    results: '#search-results',
    url: '/api?action=search',
    render: (item) => `<a href="${item.url}">${item.title}</a>`,
});

// Load more button
Zed.loadMore({
    button: '.load-more-btn',
    container: '.posts-grid',
    render: renderPost,
});
</script>
```

---

## 🎨 Theme Development

### Directory Structure
```
themes/my-theme/
├── functions.php      # Theme setup, sidebars, settings
├── index.php          # Homepage template
├── single.php         # Single post template
├── page.php           # Page template
├── archive.php        # Category/tag archive
├── 404.php            # Not found page
├── style.css          # Theme stylesheet
└── parts/
    ├── header.php     # Header partial
    ├── footer.php     # Footer partial
    └── sidebar.php    # Sidebar partial
```

### Theme Settings
```php
// In functions.php
zed_add_theme_setting('accent_color', 'Accent Color', 'color', '#6366f1');
zed_add_theme_setting('show_sidebar', 'Show Sidebar', 'checkbox', true);

// In templates
$accent = zed_theme_option('accent_color');
```

### Built-in Widgets
| Widget | Description |
|--------|-------------|
| Recent Posts | Latest posts with thumbnails |
| Categories | Category list with counts |
| Tags | Tag cloud |
| Search | Search form |
| Custom HTML | Raw HTML content |
| Social Links | Social media icons |

---

## 📦 Directory Structure

```
ZedCMS/
├── core/                    # Micro-kernel (< 500 lines total)
│   ├── App.php              # Main application class
│   ├── Event.php            # Event/hook system
│   ├── Router.php           # URL routing
│   ├── Database.php         # PDO wrapper
│   ├── Auth.php             # Authentication
│   └── Migrations.php       # Schema migrations
│
├── content/
│   ├── addons/
│   │   └── _system/         # Core system addon
│   │       ├── admin/       # Admin panel
│   │       ├── frontend/    # Frontend APIs
│   │       └── assets/      # JS/CSS assets
│   │
│   └── themes/
│       ├── admin-default/   # Admin theme
│       └── zenith/          # Frontend theme
│
├── uploads/                 # Media files (YYYY/MM structure)
├── _frontend/               # BlockNote editor (Vite/React)
│
├── ARCHITECTURE.md          # Complete developer reference
├── CHANGELOG.md             # Version history
├── CONTRIBUTING.md          # Contribution guidelines
└── LICENSE                  # MIT License
```

---

## 🔒 Security

- **RBAC** — Role-based access control with capabilities
- **CSRF Protection** — Nonce tokens on all forms
- **XSS Prevention** — Output escaping helpers
- **SQL Injection** — Prepared statements everywhere
- **Password Hashing** — PHP `password_hash()` with bcrypt

---

## 📋 Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history.

### v3.2.0 (Current)
- Comments System with moderation
- Widgets/Sidebars with drag-and-drop
- AJAX Loading library (`zed-frontend.js`)
- Theme Helpers (reading progress, social share, author box)
- Post Formats (video, gallery, audio, quote, link)

---

## 🤝 Contributing

We welcome contributions! Please read [CONTRIBUTING.md](CONTRIBUTING.md) first.

### Development Setup
```bash
git clone https://github.com/aamirali51/ZedCMS.git
cd ZedCMS
cp config.sample.php config.php
# Configure database and visit /install.php
```

### What We Need
- **Theme Developers** — Build beautiful themes
- **Plugin Authors** — Extend functionality
- **Documentation** — Improve guides and examples
- **Testing** — Find and fix bugs

---

## 📄 License

Zed CMS is open-source software licensed under the [MIT License](LICENSE).

---

<p align="center">
  <strong>Built with zero gravity ⚡</strong><br>
  <sub>Star ⭐ this repo if you find it useful!</sub>
</p>
