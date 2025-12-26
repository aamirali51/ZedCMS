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
- **BlockNote Editor Migration**
  - Replaced TipTap with BlockNote for simpler, Notion-style editing
  - Built-in slash menu with all block types (`/`)
  - Drag handles for block reordering
  - Formatting toolbar on text selection
  - Tables with full CRUD operations
  - Image uploads with drag-and-drop
  - Dark mode support via Mantine theme

### Changed
- Editor packages: `@blocknote/core`, `@blocknote/react`, `@blocknote/mantine`
- Styling: Mantine UI replaces custom CSS (80% less CSS code)
- Updated `_frontend/package.json` to v3.0.0
- Updated `ARCHITECTURE.md` with BlockNote references

### Removed
- TipTap packages (15+ individual extensions)
- Tailwind CSS and PostCSS configuration
- Custom TipTap extensions (`extensions/` folder)
- Custom slash menu, bubble menu, table toolbar components

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
