# Changelog

All notable changes to Zed CMS will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added
- Nothing yet

### Changed
- Nothing yet

### Deprecated
- Nothing yet

### Removed
- Nothing yet

### Fixed
- Nothing yet

### Security
- Nothing yet

---

## [3.2.0] - 2024-12-26

### Added
- **Comments System**
  - Full comment moderation in admin panel (`/admin/comments`)
  - Comment submission API: `zed_submit_comment()`, `zed_comment_form()`
  - Comment retrieval: `zed_get_comments()`, `zed_comment_count()`
  - Moderation functions: `zed_moderate_comment()`, `zed_delete_comment()`
  - Status tabs: All, Pending, Approved, Spam, Trash
  - RBAC capability: `moderate_comments`

- **Widgets/Sidebars System**
  - Sidebar registration: `zed_register_sidebar($id, $options)`
  - Widget registration: `zed_register_widget($id, $options)`
  - Sidebar rendering: `zed_dynamic_sidebar($id)`
  - Admin drag-and-drop widget manager (`/admin/widgets`)
  - 6 built-in widgets: Recent Posts, Categories, Tags, Search, Custom HTML, Social Links

- **AJAX Loading Library** (`zed-frontend.js`)
  - `Zed.infiniteScroll()` - Automatic loading on scroll
  - `Zed.loadMore()` - Button-triggered loading
  - `Zed.liveSearch()` - Search with debounce
  - `Zed.ajaxFilter()` - Filter/sort without reload
  - API endpoints: `/api?action=get_posts`, `/api?action=search`

- **Theme Helpers** (`theme-helpers.php`)
  - Post Formats: `zed_get_post_format()`, `zed_has_post_format()`
  - Reading Progress: `zed_reading_progress()`
  - Social Share: `zed_social_share()`
  - Author Bio Box: `zed_author_box()`
  - Reading Time: `zed_reading_time()`
  - Breadcrumbs: `zed_breadcrumbs()`
  - Post Navigation: `zed_post_navigation()`

- **BlockNote Editor Migration**
  - Replaced TipTap with BlockNote for Notion-style editing
  - Built-in slash menu, drag handles, formatting toolbar
  - Tables, image uploads, dark mode support

### Changed
- Editor packages now use `@blocknote/*` and `@mantine/*`
- All admin routes now use consistent pattern with `admin-layout.php`
- Sidebar menu now includes Comments and Widgets items

### Removed
- TipTap packages (15+ individual extensions)
- Custom TipTap extensions folder

---

## [3.1.0] - 2024-12-25

### Added
- **TipTap Rich Text Editor**
  - Replaced BlockNote with TipTap (ProseMirror-based) editor
  - Bubble menu toolbar with text formatting (bold, italic, underline, strikethrough)
  - Text color and highlight with color pickers
  - Text alignment controls (left, center, right, justify)
  - Subscript and superscript support
  - Slash commands (`/`) with keyboard navigation (Arrow keys + Enter)
  - Image controls (resize: 25%, 50%, 75%, 100% and alignment)
  - Custom blocks: Callout, YouTube embed, Button
  - **Table Block** - Full-featured tables with header row, add/delete rows and columns
  - **Toggle Block** - Collapsible accordion sections for FAQs and hidden content

### Changed
- Updated `ARCHITECTURE.md` with new APIs (v3.1.0)
- Updated wiki documentation (`DOCS.md`, addon development guides)
- Created comprehensive API reference guide (`admin-menu-api.md`)
- Improved addon menu filtering to respect activation status
- Editor bubble menu now renders as single horizontal line (sleeker UI)
- Improved slash command keyboard navigation with proper Enter key selection

### Removed
- Legacy BlockNote editor components (`blocknote-editor.jsx`, `simple-editor.jsx`)
- Unused slash menu components (`slash-dropdown-menu.jsx`, `command-list.jsx`, `drag-handle.jsx`)
- Development/debug files (`check_*.php`, `test_*.php`, `migrate_*.php`)
- Outdated documentation (`ZERO_BLUEPRINT.md`, `PROJECT_AUDIT_REPORT.md`, `nots.md`, `documentaton.md`)
- Python script and text dumps (`fl.py`, `full_project_context.txt`, `users_schema.txt`)

### Fixed
- Content status field now persists correctly in menu manager
- Disabled addon menus no longer appear in admin sidebar
- API endpoint routing for `/admin/api/save-menu` and `/admin/save-post`
- Slash menu Enter key now correctly inserts selected block

---

## [3.0.0] - 2024-12-01

### Added
- Initial stable release
- Core event system (`Core\Event`)
- Micro-kernel architecture
- Admin panel with authentication
- Theme system with template hierarchy
- Content management (posts, pages)
- Custom post types
- Menu manager
- Media library
- User roles and capabilities
- Options API
- Database abstraction layer
- Router with event-driven dispatch
- Addon system

### Security
- CSRF protection
- SQL injection prevention via prepared statements
- XSS protection in templates
- Secure password hashing

---

## Version Links

[Unreleased]: https://github.com/user/zedcms/compare/v3.1.0...HEAD
[3.1.0]: https://github.com/user/zedcms/compare/v3.0.0...v3.1.0
[3.0.0]: https://github.com/user/zedcms/releases/tag/v3.0.0

---

## Changelog Guidelines

### Categories

- **Added** - New features
- **Changed** - Changes to existing functionality
- **Deprecated** - Soon-to-be removed features
- **Removed** - Removed features
- **Fixed** - Bug fixes
- **Security** - Security fixes

### Version Format

- **MAJOR.MINOR.PATCH** (e.g., 3.1.0)
- Follow [Semantic Versioning](https://semver.org/)
- See [VERSIONING.md](VERSIONING.md) for our policy
