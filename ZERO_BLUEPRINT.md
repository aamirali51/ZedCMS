# Zed CMS — Master Architecture Blueprint

> **Version:** 2.2.0  
> **Generated:** 2025-12-22 (Updated)  
> **Last Update:** 2025-12-22 — Theme API v2 (Scoped Hooks, CPT, Asset Injection, Dependencies)  
> **Purpose:** Source of Truth for all development activities.

---

## 1. System Architecture

### 1.1 Stack Overview

Zed CMS is a **Hybrid PHP + React** content management system built on an **event-driven micro-kernel architecture**.

| Layer | Technology | Purpose |
|-------|------------|---------|
| **Backend Core** | PHP 8.2+ | Micro-kernel, Routing, Database, Auth (enhanced security) |
| **Frontend Editor** | React 18 + BlockNote | Rich block-based content editor |
| **Build System** | Vite 5.x | Compiles React → `editor.bundle.js` |
| **Styling** | Tailwind CSS (CDN) | Rapid UI prototyping |
| **Database** | MySQL (PDO) | Content storage with JSON columns |

### 1.2 Core Architectural Principles

```
┌─────────────────────────────────────────────────────────────────────┐
│                           REQUEST FLOW                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│   Browser Request                                                   │
│        │                                                            │
│        ▼                                                            │
│   ┌─────────────┐                                                   │
│   │  index.php  │  Entry Point                                      │
│   │  - Load config                                                  │
│   │  - Autoload Core\*                                              │
│   │  - Load addons                                                  │
│   │  - Run App                                                      │
│   └──────┬──────┘                                                   │
│          │                                                          │
│          ▼                                                          │
│   ┌─────────────┐                                                   │
│   │  Core\App   │  Micro-Kernel                                     │
│   │  - Trigger 'app_init'                                           │
│   │  - Set DB config (lazy)                                         │
│   │  - Trigger 'app_ready'                                          │
│   │  - Dispatch route                                               │
│   └──────┬──────┘                                                   │
│          │                                                          │
│          ▼                                                          │
│   ┌──────────────┐                                                  │
│   │ Core\Router  │  Event-Driven Router                             │
│   │  - Normalize URI                                                │
│   │  - Fire 'route_request' event ◄─── Addons listen here!         │
│   │  - Check if handled                                             │
│   │  - Return 404 if not                                            │
│   └──────┬───────┘                                                  │
│          │                                                          │
│          ▼                                                          │
│   ┌──────────────────────────────────────────────────────────┐      │
│   │                  ADDON LAYER                              │      │
│   │  ┌──────────────────────────────────────────────────────┐│      │
│   │  │ admin_addon.php                                      ││      │
│   │  │  - Listens to 'route_request'                        ││      │
│   │  │  - Claims /admin/* URIs                              ││      │
│   │  │  - Handles auth, dashboard, editor, API              ││      │
│   │  └──────────────────────────────────────────────────────┘│      │
│   └──────────────────────────────────────────────────────────┘      │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.3 Directory Structure

```
F:\laragon\www\Zero\
├── index.php                    # ✅ Entry point (protected system addons)
├── config.php                   # ✅ Database & app config
├── install.php                  # ✅ Database installer
├── .htaccess                    # URL rewriting
│
├── core/                        # ✅ CORE ENGINE (6 classes)
│   ├── App.php                  # Micro-kernel bootstrap + migration trigger
│   ├── Router.php               # Event-driven routing (NO hardcoded routes)
│   ├── Event.php                # WordPress-style hook system
│   ├── Database.php             # PDO wrapper with JSON support
│   ├── Auth.php                 # Session-based authentication
│   └── Migrations.php           # ✅ NEW: Version tracking & safe upgrades
│
├── content/                     # USER CONTENT & EXTENSIONS
│   ├── addons/                  # ✅ Plugin system
│   │   ├── admin_addon.php      # All admin routes & API
│   │   ├── frontend_addon.php   # Public content viewing & BlockNote renderer
│   │   └── test_addon.php       # Sample addon
│   │
│   ├── themes/
│   │   └── admin-default/       # ADMIN THEME
│   │       ├── admin-layout.php  # ✅ Master layout with sidebar
│   │       ├── dashboard.php     # (Legacy) Admin home
│   │       ├── content-list.php  # (Legacy) Content grid
│   │       ├── editor.php        # React editor host page
│   │       ├── login.php         # Auth form
│   │       ├── partials/         # ✅ Content partials for layout
│   │       │   ├── dashboard-content.php
│   │       │   ├── content-list-content.php
│   │       │   ├── media-content.php    # ✅ v2: WebP, Drag & Drop, Toast UI
│   │       │   ├── users-content.php
│   │       │   ├── addons-content.php   # ✅ v2: Card Grid, Toggle Switches
│   │       │   ├── themes-content.php   # ✅ NEW: Gallery Grid, Color Preview
│   │       │   └── settings-content.php
│   │       └── assets/js/
│   │           └── editor.bundle.js  # Compiled React bundle
│   │   └── starter-theme/       # ✅ FE THEME (New)
│   │       ├── index.php        # Homepage template
│   │       └── single.php       # Single post template
│   │
│   └── uploads/                 # User uploads (WebP optimized + originals)
│
└── _frontend/                   # ✅ REACT SOURCE (Vite project)
    ├── package.json             # Dependencies (BlockNote, Tiptap, React)
    ├── vite.config.js           # Build config → outputs to themes/assets/js
    └── src/
        ├── main.jsx             # React entry point
        ├── index.css            # Global styles
        └── components/
            ├── blocknote-editor.jsx  # Main editor component
            ├── drag-handle.jsx       # Block drag functionality
            ├── simple-editor.jsx     # Alternate Tiptap editor
            └── slash-dropdown-menu.jsx  # Slash commands UI
```

### 1.4 Event System (The Heart)

The `Core\Event` class powers all inter-component communication:

| Method | WordPress Equivalent | Purpose |
|--------|----------------------|---------|
| `Event::on($name, $fn, $priority)` | `add_action()` / `add_filter()` | Register listener |
| `Event::trigger($name, $payload)` | `do_action()` | Fire action (side effects) |
| `Event::filter($name, $value)` | `apply_filters()` | Modify & return data |
| `Event::off($name, $fn, $priority)` | `remove_action()` | Unregister listener |

**Core Events:**
- `app_init` - Fired immediately after addons load
- `app_ready` - Fired when system is fully bootstrapped
- `route_request` - Fired for every HTTP request (addons claim URIs here)
- `route_not_found` - Fired when no addon handles a route
- `app_output` - Filter final output before echo
- `app_shutdown` - Cleanup hook

### 1.5 Enterprise RBAC System

Zed CMS uses a **capability-based** Role-Based Access Control system superior to WordPress.

#### Role Definitions

| Role | Level | Admin Access | Content | Media | Users | Settings |
|------|-------|--------------|---------|-------|-------|----------|
| **Administrator** | 100 | ✅ Full | All | All | ✅ Manage | ✅ Full |
| **Editor** | 70 | ✅ Full | All | All | ❌ | ❌ |
| **Author** | 40 | ✅ Limited | Own only | Own only | ❌ | ❌ |
| **Subscriber** | 10 | ❌ None | ❌ | ❌ | ❌ | ❌ |

#### Capability Matrix

```
Administrator:
├── manage_users, create_users, edit_users, delete_users
├── manage_settings, manage_addons, manage_themes
├── manage_categories, manage_menus
├── publish_content, edit_content, delete_content
├── edit_others_content, delete_others_content ← Can edit ANY content
├── manage_media, upload_media, delete_media, delete_others_media
└── view_dashboard, view_analytics

Editor:
├── manage_categories
├── publish_content, edit_content, delete_content
├── edit_others_content, delete_others_content ← Can edit ANY content
├── manage_media, upload_media, delete_media, delete_others_media
└── view_dashboard

Author:
├── publish_content, edit_content, delete_content ← OWN content only
├── upload_media, delete_media ← OWN media only
└── view_dashboard

Subscriber:
└── (No admin capabilities - frontend only)
```

#### RBAC Helper Functions

| Function | Purpose |
|----------|---------|
| `zed_current_user_can($cap, $obj_id)` | Check if user has capability (with ownership) |
| `zed_user_has_role($role)` | Check if user has specific role(s) |
| `zed_is_admin()` | Check if user is admin |
| `zed_get_current_user_role()` | Get current user's role string |
| `zed_get_role_info($role)` | Get role label, icon, color, level |
| `zed_get_admin_menu_items()` | Get dynamic sidebar based on capabilities |
| `zed_user_can_access_admin()` | Check if user can access admin panel |
| `zed_json_permission_denied()` | Return 403 JSON response |

#### Ownership Enforcement

Authors can only see/edit content where `author_id = current_user_id`. This is enforced in:
- Content list query (`WHERE author_id = :author_id`)
- Content delete route (ownership check before delete)
- Content edit API (ownership check before update)

---

## 2. Implementation Status (Audit)

### ✅ Real/Dynamic (Fully Wired to Database)

| Feature | Location | Details |
|---------|----------|---------|
| **User Authentication** | `Core\Auth`, `login.php` | Enhanced: throttling, remember me, session fixation protection |
| **Login/Logout Flow** | `admin_addon.php:26-49` | Working redirect logic, session destroy |
| **Dashboard Stats** | `admin_addon.php:59-94` | Real queries to `zed_content`, `users` tables |
| **Content CRUD** | `admin_addon.php:180-250` | Full JSON API for create/update |
| **Content Delete** | `admin_addon.php:180-214` | DELETE route with auth check and redirect |
| **Content List** | `content-list.php:20-28` | Real query: `SELECT * FROM zed_content` |
| **Editor Load** | `editor.php:22-39` | Fetches by `?id=`, decodes JSON data column |
| **Editor Save** | `editor.php:229-296` | POST to `/admin/save-post` or `/admin/api/save` |
| **Image Upload** | `admin_addon.php:253-334` | Writes to `content/uploads/`, returns URL |
| **Routing** | `Core\Router` | Fully event-driven, no hardcoded routes |
| **Slugs** | `editor.php:214-226` | Auto-generated from title on client-side |
| **Content Filtering** | `content-list.php` | Status tabs (All/Published/Draft) with query params |
| **Content Search** | `content-list.php` | Title/slug search with LIKE query |
| **Content Pagination** | `content-list.php` | LIMIT/OFFSET with page navigation |
| **Dashboard Stats** | `admin_addon.php`, `dashboard.php` | Real counts for pages, posts, published/drafts |
| **Recent Activity Feed** | `dashboard.php` | Shows last 5 updated content items from DB |
| **System Status** | `dashboard.php` | Dynamic status based on content state |
| **Frontend Public View** | `frontend_addon.php` | `/{slug}` route serves published content as HTML |
| **BlockNote Renderer** | `frontend_addon.php` | Converts JSON blocks to semantic HTML |
| **Content Preview** | `frontend_addon.php` | `/preview/{id}` for authenticated preview |
| **Role-Based Access** | `admin_addon.php` | `zero_user_can_access_admin()` checks roles + 403 page |
| **Unified Admin Layout** | `admin-layout.php` | Master layout template with dynamic partials |
| **Shadow Text Search** | `admin_addon.php` | `plain_text` column + `extract_text_from_blocks()` |
| **Robust Renderer** | `frontend_addon.php` | `normalize_block()` prevents crashes on malformed data |
| **Global Menu System** | `zed_menus`, `zed_options` | Database-driven menu storage & retrieval |
| **Menu Renderer** | `frontend_addon.php` | `render_menu()` helper outputs HTML tree |
| **Menu Manager UI** | `/admin/menus` | "Hacker UI" for manual JSON editing of menus |
| **Category Manager UI** | `/admin/categories` | CRUD for categories (Create, List, Delete) |
| **Category API** | `/admin/api/categories` | JSON endpoint for fetching categories |
| **Dynamic Editor Categories** | `editor.php` | JS fetches categories from API + saves selection |
| **Media Library Backend** | `/admin/media` | Scans `content/uploads`, filters thumbs & originals |
| **WebP Image Processing** | `zed_process_upload()` | Auto-converts to WebP (80%), max 1920px, thumb 300px |
| **WebP Thumbnail Generator** | `zed_generate_thumbnail()` | 300px WebP thumbnails for grid display |
| **Media Upload (Form)** | `/admin/media/upload` | POST handler with WebP conversion pipeline |
| **Media Upload (API)** | `/admin/api/upload` | JSON API with full WebP processing |
| **Media Delete (Smart)** | `/admin/media/delete` | Deletes WebP + original backup + thumbnail |
| **Drag & Drop Upload** | `media-content.php` | Drop zone triggers fetch() to API |
| **Real-time Search** | `media-content.php` | Client-side instant filtering |
| **Copy to Clipboard** | `media-content.php` | Full URL copy with toast notification |
| **User Management UI** | `/admin/users` | Full CRUD with modal, Gravatar, role badges |
| **User Create/Update API** | `/admin/api/save-user` | JSON API with validation, self-lock protection |
| **User Delete API** | `/admin/api/delete-user` | JSON API with self-deletion protection |
| **Password Generator** | `users-content.php` | Generates secure 12-char passwords, copies to clipboard |
| **Login CSRF Protection** | `login.php` | Token-based form protection |
| **Password Visibility** | `login.php` | Eye icon toggle for password field |
| **Login Loading State** | `login.php` | Spinner animation on form submit |
| **Lockout Countdown** | `login.php` | Live countdown timer when account locked |
| **Unified Settings Panel** | `/admin/settings` | Tabbed UI: General, SEO, System |
| **Homepage Mode Toggle** | `settings-content.php` | Latest Posts vs Static Page with page dropdown |
| **Save Settings API** | `/admin/api/save-settings` | Upserts to zed_options with whitelist |
| **Sticky Save Bar** | `settings-content.php` | Always visible, Ctrl+S support |
| **System Toggles** | `settings-content.php` | Maintenance Mode, Debug Mode |
| **Smart Routing** | `frontend_addon.php` | Respects homepage_mode setting |
| **Static Page Homepage** | `frontend_addon.php` | Displays selected page on front |
| **Dynamic Blog Slug** | `frontend_addon.php` | Blog listing at configurable URL |
| **Options Cache** | `zed_get_option()` | Single query loads all autoload options |
| **SEO Head Event** | `zed_head` | Injects meta description, noindex, og:tags |
| **Page Title Helper** | `zed_page_title()` | Generates "Page — Site Name" format |
| **Dashboard Command Center** | `dashboard-content.php` | Real-time stats, health checks, activity feed |
| **Health Monitoring** | `admin_addon.php` | Checks uploads, PHP, SEO, maintenance |
| **Jump Back In Feed** | `dashboard-content.php` | Recent content with relative timestamps |
| **Quick Draft** | `/admin/api/quick-draft` | AJAX post creation from dashboard |
| **Visual Menu Builder** | `/admin/menus` | Drag-drop UI with toolbox accordions |
| **Menu Quick Add** | `menus-content.php` | Instant add pages/posts/categories |
| **Menu Auto-Save** | `menus-content.php` | Saves 2s after changes |
| **Menu JSON Preview** | `menus-content.php` | Debug toggle for developers |
| **Menu Save API** | `/admin/api/save-menu` | AJAX menu persistence |
| **Menu Delete API** | `/admin/api/delete-menu` | AJAX menu deletion |
| **Dynamic Menu Helpers** | `frontend_addon.php` | `zed_menu()`, `zed_primary_menu()` |
| **Menu by Name/ID** | `zed_menu('Main Menu')` | Flexible menu rendering |
| **Addon Manager UI** | `/admin/addons` | Card grid with toggle switches |
| **Addon Toggle API** | `/admin/api/toggle-addon` | Enable/disable addons (JSON array in zed_options) |
| **Addon Upload API** | `/admin/api/upload-addon` | Upload .php addons to content/addons/ |
| **Active Addons Loader** | `index.php` | Only loads enabled addons from active_addons option |
| **Theme Manager UI** | `/admin/themes` | Gallery grid with screenshot/color preview |
| **Theme Activate API** | `/admin/api/activate-theme` | Switch active frontend theme |
| **Theme Switched Event** | `zed_theme_switched` | Triggered when theme changes (for cache clearing) |
| **Content Delete Route** | `/admin/content/delete` | Full RBAC with ownership check, numeric ID validation |
| **Editor Data Parsing** | `editor.php` | Properly parses JSON data for status/excerpt/featured image |
| **BlockNote Crash Prevention** | `editor.php` | Ensures empty array initialization for new content |
| **Migration System** | `Core\Migrations` | Incremental version upgrades, runs once per version |
| **Version Tracking** | `zed_options` | Stores `zed_version` and `zed_migrations_log` |
| **Safe Upgrades** | `App::run()` | Auto-runs migrations on every request (idempotent) |
| **Content Revision System** | `save-post` route | Auto-saves previous state before update (max 10) |
| **Revision Cleanup** | `admin_addon.php` | Rolling limit keeps only last 10 revisions per content |
| **Revision Helper** | `zed_get_revisions()` | Returns decoded revision history for content ID |
| **BlockNote Default Block** | `editor.php` | Provides valid paragraph block when content empty |
| **Vite Relative Paths** | `vite.config.js` | Uses `base: './'` for portable asset URLs |
| **Theme Menu CSS** | `zero-one/index.php` | Horizontal nav with dropdowns |
| **Theme functions.php** | `frontend_addon.php` | Auto-loads active theme's functions.php on app_ready |
| **Custom Post Types (CPT)** | `$ZED_POST_TYPES` | Global registry + `zed_register_post_type()` helper |
| **CPT Admin Sidebar** | `admin_addon.php` | Dynamic menu items for registered CPTs |
| **Theme Settings API** | `zed_add_theme_setting()` | Customizable theme options with DB storage |
| **Theme Option Values** | `zed_theme_option()` | Get/set with `theme_{name}_{id}` prefix |
| **Scoped Action Hooks** | `Event::onScoped()` | Context-aware hooks (post_type, template, etc.) |
| **Scoped Triggers** | `Event::triggerScoped()` | Fire hooks with context matching |
| **Asset Injection** | `zed_enqueue_theme_asset()` | Auto-resolve paths with Vite manifest support |
| **Theme Dependencies** | `zed_register_theme_requirements()` | Declare required addons |
| **Dependency Warnings** | `addons-content.php` | Addon Manager shows missing theme addons |
| **Content Hooks** | `zed_before/after_content` | Theme injection points for author bios, etc. |
| **Template Data API** | `zed_add_template_data()` | Inject PHP variables into templates |
| **Template Filter** | `zed_template_data` filter | Dynamic template variable injection |
| **Dynamic Theme Switching** | `frontend_addon.php` | Reads active_theme from database |

### 🚧 Mocked/Static (Visual Only — Not Connected)

| Feature | Location | Issue |
|---------|----------|-------|
| **Traffic Overview Chart** | `dashboard.php:230-254` | Hardcoded SVG paths (data available in `window.ZERO_DASHBOARD_DATA`) |
| **Bulk Checkbox Selection** | `content-list.php:127-128,151` | Checkboxes render but no JS handler |

| **"View" Action Link** | `content-list.php:189` | Links to `#` — should link to `/{slug}` |
| **Media Delete Button** | `media-content.php` | User reports click ineffective (investigate JS/Z-index) |
| **Sidebar Active States** | `dashboard.php` | Dashboard always active, no dynamic highlighting |


### 🛑 Missing (Critical Gaps)

| Feature | Priority | Description |
|---------|----------|-------------|
| **Forgot Password** | 🟢 LOW | Link exists in login but no handler |

---

## 3. Data Protocols

### 3.1 Database Schema

**Table: `zed_content`**
```sql
CREATE TABLE zed_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    type ENUM('page', 'post', 'product') DEFAULT 'page',
    data JSON,                  -- Stores content + meta as JSON
    plain_text LONGTEXT,        -- ✅ Shadow column for full-text search
    author_id INT,
    created_at DATETIME,
    updated_at DATETIME
);
```

**Table: `users`** (Enhanced with Security Columns)
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    remember_token VARCHAR(64) NULL,     -- ✅ Hashed token for persistent login
    last_login DATETIME NULL,            -- ✅ Last successful login timestamp
    failed_attempts INT DEFAULT 0,       -- ✅ Failed login attempts counter
    locked_until DATETIME NULL,          -- ✅ Account lockout expiry time
    created_at DATETIME,
    updated_at DATETIME
);
```

**Table: `zed_menus`**
```sql
CREATE TABLE zed_menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    items JSON,                 -- Stores menu tree as JSON
    created_at DATETIME,
    updated_at DATETIME
);
```

**Table: `zed_options`**
```sql
CREATE TABLE zed_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    option_name VARCHAR(191) NOT NULL UNIQUE,
    option_value LONGTEXT,      -- Stores settings
    autoload TINYINT DEFAULT 1
);
```

**Table: `zed_categories`**
```sql
CREATE TABLE zed_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    created_at DATETIME,
    updated_at DATETIME
);
```

**Table: `zed_content_revisions`** (✅ NEW - v2.1.0)
```sql
CREATE TABLE zed_content_revisions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_id INT NOT NULL,            -- FK to zed_content
    data_json JSON NOT NULL,            -- Full snapshot {title, slug, type, data}
    author_id INT NOT NULL,             -- Who made the edit
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_content_id (content_id),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (content_id) REFERENCES zed_content(id) ON DELETE CASCADE
);
```

### 3.2 Content JSON Structure

The `data` column in `zed_content` stores:

```json
{
    "content": [
        // BlockNote block array
        {
            "id": "abc123",
            "type": "paragraph",
            "content": [{ "type": "text", "text": "Hello world" }],
            "props": {}
        },
        {
            "id": "def456",
            "type": "heading",
            "content": [{ "type": "text", "text": "My Heading" }],
            "props": { "level": 2 }
        }
    ],
    "status": "draft" | "published",
    "excerpt": "Optional summary text",
    "featured_image": "URL or null",
    "categories": ["tech", "news"]
}
```

### 3.3 API Endpoints

#### POST `/admin/save-post` (or `/admin/api/save`)
**Purpose:** Create or update content

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
    "id": null | 123,           // null = create, number = update
    "title": "My Post Title",   // Required
    "slug": "my-post-title",    // Auto-generated if empty
    "type": "post" | "page",
    "status": "draft" | "published",
    "content": "[BlockNote JSON array as string]",
    "data": {
        "featured_image": "URL",
        "excerpt": "Summary",
        "categories": ["cat1"]
    }
}
```

**Response (Success):**
```json
{
    "success": true,
    "id": 123,
    "new_id": 123,              // Only present on create
    "action": "create" | "update",
    "message": "Content created"
}
```

**Response (Error):**
```json
{
    "success": false,
    "error": "Title is required",
    "message": "Title is required"
}
```

#### POST `/admin/api/upload`
**Purpose:** Upload images with automatic WebP optimization

**Request:**
- `Content-Type: multipart/form-data`
- Field: `image` or `file` (accepts both)
- Max size: 10MB
- Allowed types: JPG, PNG, GIF, WebP

**Processing Pipeline:**
1. Validate file type and size
2. Convert to WebP (quality 80%)
3. Resize to max 1920px width (if larger)
4. Generate 300px WebP thumbnail (`thumb_filename.webp`)
5. Keep original as backup (`filename_original.ext`)

**Response (Success):**
```json
{
    "success": 1,
    "status": "success",
    "file": {
        "url": "http://localhost/Zero/content/uploads/image_abc123.webp"
    },
    "url": "http://localhost/Zero/content/uploads/image_abc123.webp",
    "filename": "image_abc123.webp",
    "size": 45678
}
```

**Response (Error):**
```json
{
    "success": 0,
    "status": "error",
    "error": "File too large. Maximum size is 10MB"
}
```

#### GET `/admin/media/delete?file=X`
**Purpose:** Delete media file and all related versions

**Query Parameters:**
- `file` (required): Filename to delete (e.g., `image_abc123.webp`)

**Behavior:**
- Requires admin authentication
- Deletes:
  - Main file (`filename.webp`)
  - Thumbnail (`thumb_filename.webp`)
  - Original backup (`filename_original.jpg`)
- Supports both AJAX (JSON response) and regular requests (redirect)

**AJAX Response:**
```json
{
    "success": true,
    "message": "File deleted"
}
```

#### POST `/admin/api/save-settings`
**Purpose:** Save site settings to zed_options table

**Request Body:**
```json
{
    "site_title": "My Site",
    "site_tagline": "A great site",
    "homepage_mode": "latest_posts" | "static_page",
    "page_on_front": 123,
    "blog_slug": "blog",
    "posts_per_page": 10,
    "discourage_search_engines": "0" | "1",
    "meta_description": "Site description",
    "social_sharing_image": "https://...",
    "maintenance_mode": "0" | "1",
    "debug_mode": "0" | "1"
}
```

**Whitelisted Keys Only:** Unknown keys are ignored for security.

**Response:**
```json
{
    "success": true,
    "message": "Settings saved successfully.",
    "saved": 5
}
```

#### POST `/admin/api/save-user`
**Purpose:** Create or update a user

**Request Body:**
```json
{
    "id": null | 123,
    "email": "user@example.com",
    "password": "optional-for-edit",
    "role": "admin" | "editor" | "author" | "subscriber"
}
```

**Security:**
- Admin-only endpoint
- Email uniqueness validation
- Self-lock protection (can't demote own admin role)
- Password hashed with `password_hash()`

#### POST `/admin/api/delete-user`
**Purpose:** Delete a user

**Request Body:**
```json
{
    "id": 123
}
```

**Security:**
- Admin-only endpoint
- Self-deletion prevention

**Response:**
```json
{ "success": true, "message": "User deleted." }
```

#### POST `/admin/api/quick-draft`
**Purpose:** Create a draft post instantly from the dashboard

**Request Body:**
```json
{
    "title": "My Post Idea"
}
```

**Response:**
```json
{
    "success": true,
    "id": 123,
    "redirect": "/Zero/admin/editor?id=123"
}
```

#### POST `/admin/api/save-menu`
**Purpose:** Save menu items via AJAX

**Request Body:**
```json
{
    "menu_id": 1,
    "name": "Main Menu",
    "items": [
        {
            "label": "Home",
            "url": "/",
            "target": "_self",
            "children": []
        }
    ]
}
```

**Response:**
```json
{ "success": true, "message": "Menu saved successfully" }
```

#### POST `/admin/api/delete-menu`
**Purpose:** Delete a menu

**Request Body:**
```json
{ "id": 1 }
```

**Response:**
```json
{ "success": true, "message": "Menu deleted" }
```

#### POST `/admin/api/toggle-addon`
**Purpose:** Enable or disable a non-system addon

**Request Body:**
```json
{
    "filename": "seo_pack.php"
}
```

**Security:**
- Admin-only endpoint (requires `manage_addons` capability)
- System addons (`admin_addon.php`, `frontend_addon.php`) cannot be disabled

**Response (Success):**
```json
{
    "success": true,
    "active": false,
    "message": "SEO Pack Deactivated"
}
```

**Response (Error):**
```json
{
    "success": false,
    "error": "System addons cannot be disabled"
}
```

#### POST `/admin/api/upload-addon`
**Purpose:** Upload a new addon file

**Request:**
- `Content-Type: multipart/form-data`
- Field: `addon` (accepts `.php` files only)

**Behavior:**
- Sanitizes filename
- Moves to `content/addons/`
- Auto-activates the addon
- Fails if file already exists

**Response:**
```json
{
    "success": true,
    "filename": "seo_pack.php",
    "message": "Addon uploaded and activated"
}
```

#### POST `/admin/api/activate-theme`
**Purpose:** Switch the active frontend theme

**Request Body:**
```json
{
    "theme": "zero-one"
}
```

**Security:**
- Admin-only endpoint (requires `manage_themes` capability)
- `admin-default` theme cannot be activated as frontend theme

**Behavior:**
- Updates `active_theme` in zed_options
- Triggers `zed_theme_switched` event

**Response:**
```json
{
    "success": true,
    "theme": "zero-one",
    "message": "Zed One activated"
}
```

#### GET `/admin/content/delete?id=X`
**Purpose:** Delete content by ID (redirect-based, not JSON API)

**Query Parameters:**
- `id` (required): Numeric content ID to delete

**Behavior:**
- Requires authentication (redirects to `/admin/login` if not logged in)
- Validates ID is numeric
- Executes `DELETE FROM zed_content WHERE id = ?`
- Redirects to `/admin/content` with query parameter:
  - `?msg=deleted` - Success
  - `?msg=not_found` - ID not in database
  - `?msg=invalid_id` - Missing or non-numeric ID
  - `?msg=error` - Database error

### 3.4 React-PHP Data Bridge

The PHP page injects data for React:

```php
// editor.php:199-203
<script>
    window.ZERO_INITIAL_CONTENT = <?= $initialDataSafe ?>;  // BlockNote JSON
    const postId = "<?= htmlspecialchars($post_id) ?>";
    const baseUrl = "<?= $base_url ?>";
</script>
```

React reads this on mount:
```jsx
// main.jsx:10
let content = window.ZERO_INITIAL_CONTENT || null
```

React exposes content for save:
```jsx
// blocknote-editor.jsx:37
window.zero_editor_content = editor.document;
```

PHP-side JS reads and sends:
```javascript
// editor.php:243
const contentData = window.zero_editor_content || [];
```

---

## 4. Frontend Template System

### 4.1 Smart Routing

The frontend router respects the Unified Settings Panel configuration:

```
GET / (Homepage)
│
├── homepage_mode = 'static_page' AND page_on_front > 0
│   └── Load page from DB → Render with page.php or single.php
│
└── homepage_mode = 'latest_posts' (Default)
    └── Fetch posts → Pass $posts, $total_pages to index.php

GET /{blog_slug} (e.g., /blog, /news)
└── Only when homepage_mode = 'static_page'
    └── Fetch posts → Pass to index.php

GET /{slug}
└── Fetch content by slug → Render with single.php
```

### 4.2 Frontend Helper Functions

| Function | Purpose |
|----------|---------|
| `zed_get_option($name, $default)` | Fetch option with caching |
| `zed_get_site_name()` | Get site title from settings |
| `zed_get_site_tagline()` | Get site tagline |
| `zed_get_meta_description()` | Get default meta description |
| `zed_is_noindex()` | Check if search engines discouraged |
| `zed_get_posts_per_page()` | Get pagination limit |
| `zed_get_latest_posts($limit, $offset)` | Fetch published posts |
| `zed_count_published_posts()` | Count total published posts |
| `zed_get_page_by_id($id)` | Fetch a single page |
| `zed_page_title($title)` | Generate "Page — Site Name" format |
| `render_blocks($blocks)` | Convert BlockNote JSON to HTML |
| `render_menu($location)` | Render menu by location (legacy) |
| `zed_menu($nameOrId, $options)` | **Render menu by name or ID** |
| `zed_primary_menu($options)` | Render first available menu |
| `zed_get_menu_by_name($name)` | Get menu data by name |
| `zed_get_menu_by_id($id)` | Get menu data by ID |
| `zed_get_all_menus()` | Get all menus |

### 4.3 Dynamic Menu System

**Usage in themes:**
```php
<!-- By menu name (recommended) -->
<?= zed_menu('Main Menu') ?>

<!-- By menu ID -->
<?= zed_menu(1) ?>

<!-- First available menu -->
<?= zed_primary_menu() ?>

<!-- With custom CSS class -->
<?= zed_menu('Footer Menu', ['class' => 'footer-links', 'id' => 'footer-nav']) ?>
```

**HTML Output:**
```html
<ul class="zed-menu zed-menu-main-menu">
    <li><a href="/Zero/about">About</a></li>
    <li class="has-children">
        <a href="/Zero/services">Services</a>
        <ul class="sub-menu">
            <li><a href="/Zero/web-design">Web Design</a></li>
        </ul>
    </li>
</ul>
```

**Features:**
- Auto-prefixes relative URLs with base path
- Supports nested children (dropdowns)
- Generates slugified class names
- Falls back gracefully if menu not found

### 4.4 Template Variables

**Available in `index.php` (Blog Listing):**
```php
$posts        // Array of post objects
$total_posts  // Total count of published posts
$total_pages  // Total pages for pagination
$page_num     // Current page number
$is_home      // True if on homepage
$is_blog      // True if blog listing
$base_url     // Site base URL
```

**Available in `single.php` / `page.php`:**
```php
$post         // Single content object
$htmlContent  // Pre-rendered BlockNote HTML
$base_url     // Site base URL
```

### 4.5 SEO Head Event

Themes can inject SEO metadata by calling:
```php
<?php \Core\Event::trigger('zed_head'); ?>
```

**Output:**
```html
<!-- Zed CMS SEO -->
<meta name="generator" content="Zed CMS 1.7.0">
<meta name="description" content="Your meta description">
<meta name="robots" content="noindex, nofollow"> <!-- If discouraged -->
<meta property="og:site_name" content="Site Name">
<meta property="og:image" content="https://..."> <!-- If set -->
<!-- /Zed CMS SEO -->
```

---

## 5. Current Blockers

### 5.1 Hardcoded Values to Replace Immediately

| File | Line | Current Value | Required Fix |
|------|------|---------------|>--------------|
| ~~`dashboard.php`~~ | ~~166~~ | ~~`"All systems running"`~~ | ✅ **FIXED** - Dynamic `zed_get_system_health()` |
| `dashboard.php` | 230-254 | SVG chart paths | Implement Chart.js with real data |
| ~~`dashboard.php`~~ | ~~267-302~~ | ~~Static event list~~ | ✅ **FIXED** - "Jump Back In" shows real recent content |
| ~~`content-list.php`~~ | ~~104-106~~ | ~~Filter tabs~~ | ✅ **FIXED** - Status tabs with `?status=` query filtering |
| ~~`content-list.php`~~ | ~~203~~ | ~~`min(10, count($posts))`~~ | ✅ **FIXED** - Real LIMIT/OFFSET pagination |
| ~~`editor.php`~~ | ~~152-166~~ | ~~Categories checkboxes~~ | ✅ **FIXED** - Fetches from `/admin/api/categories` |
| ~~`editor.php`~~ | ~~201~~ | ~~`$post_id`~~ | ✅ **FIXED** - Uses `$postId ?? ''` |
| ~~`editor.php`~~ | ~~254~~ | ~~`featuredImageUrl`~~ | ✅ **FIXED** - Extracted from PHP `$data` and injected |
| `login.php` | 102 | `v2.4.0` | Pull from `config.php` app version |

### 5.2 Critical Bugs

1. ~~**Undefined Variables in `editor.php`:**~~ ✅ **FIXED**
   - ~~Line 201: `$post_id` should be `$postId`~~ → Now uses null-safe `$postId ?? ''`
   - ~~Line 254: `featuredImageUrl` referenced before definition~~ → Now extracted from PHP `$data` and injected into JS
   - **NEW FIX:** JSON data properly parsed to `$postStatus`, `$postExcerpt`, `$featuredImageUrl`
   - **NEW FIX:** `window.zero_editor_content` initialized as empty array to prevent BlockNote crash

2. ~~**Delete Route Missing:**~~ ✅ **FIXED**
   - ~~`content-list.php:190` calls `/admin/content/delete?id=X`~~ → Route fully implemented
   - Includes authentication, `delete_content` capability check, ownership enforcement
   - Redirects with `?msg=deleted`, `?msg=not_found`, `?msg=invalid_id`, `?msg=permission_denied`

3. ~~**Frontend Routes Missing:**~~ ✅ **FIXED**
   - ~~No public-facing route handler for content viewing~~ → Implemented in `frontend_addon.php`
   - ~~`/{slug}` returns 404~~ → Now renders published content

4. ~~**Featured Image Not Loading on Edit:**~~ ✅ **FIXED**
   - Featured image now pre-populates in UI when editing existing content
   - `featuredImageUrl` passed from PHP to JS global scope

### 5.3 Build Dependency

The React editor requires building:
```bash
cd _frontend
npm install
npm run build
# Output: content/themes/admin-default/assets/js/editor.bundle.js
```

If `editor.bundle.js` is missing or outdated, the editor will not function.

---

## 6. Quick Reference

### Start Development Server
```bash
# PHP server (from project root)
php -S localhost:8000

# React dev server (hot reload for editor)
cd _frontend && npm run dev
```

### Build for Production
```bash
cd _frontend && npm run build
```

### Database Setup
Navigate to `/install.php` in browser to run migrations.

### Default Login
```
Email: admin@zero.local
Password: (set during install)
```

---

## Appendix: File Responsibilities

| File | Responsibility |
|------|----------------|
| `index.php` | Boot only: config → autoload → addons → App::run() |
| `Core\App` | Trigger lifecycle events, run migrations, dispatch router |
| `Core\Router` | Normalize URI, fire `route_request`, handle 404 |
| `Core\Event` | Hook registration and execution |
| `Core\Database` | PDO wrapper, JSON column helpers, transactions |
| `Core\Auth` | Session management, login/logout, role checks, remember me |
| `Core\Migrations` | Version tracking, incremental migrations, upgrade safety |
| `admin_addon.php` | All `/admin/*` routes, API handlers, RBAC, WebP processing |
| `frontend_addon.php` | Public routes, smart routing, BlockNote renderer, SEO head |
| `admin-layout.php` | Master admin layout with dynamic RBAC sidebar |
| `dashboard-content.php` | Dashboard stats UI |
| `content-list-content.php` | Content grid with pagination and filters |
| `media-content.php` | Media Manager UI (drag-drop, search, clipboard) |
| `users-content.php` | User Management UI (CRUD, Gravatar, password generator) |
| `addons-content.php` | Addon Manager (card grid, toggle switches, upload) |
| `themes-content.php` | Theme Manager (gallery grid, screenshot/color preview) |
| `settings-content.php` | Unified Settings Panel (General, SEO, System) |
| `editor.php` | React mount point, save JS logic |
| `login.php` | Auth form with brute-force protection, remember me |
| `zero-one/` | Default frontend theme templates |
| `blocknote-editor.jsx` | React editor component, title sync |
| `vite.config.js` | Bundle output config |

---

*This document is the single source of truth for Zed CMS architecture. Update it as features are implemented.*
