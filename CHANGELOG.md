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

## [3.1.0] - 2024-12-25

### Added
- **API Contracts & Deprecation System**
  - `Core\Deprecation` class for formal API deprecation tracking
  - `zed_deprecated_function()` helper for marking deprecated functions
  - `zed_deprecated_hook()` helper for marking deprecated events
  - `zed_deprecated_argument()` helper for marking deprecated arguments
  - Admin notice integration for deprecation warnings in debug mode
  - `API_STABILITY.md` - Comprehensive API stability guide
  - `VERSIONING.md` - Semantic versioning policy
  - `CHANGELOG.md` - This file!

- **Admin Menu Registration API**
  - `zed_register_admin_menu()` for registering top-level menus
  - `zed_register_admin_submenu()` for registering submenus
  - `zed_register_capabilities()` for custom capabilities
  - Automatic route registration for menu items
  - Permission checks and layout wrapping
  - Badge support for menu items
  - Auto-hide menus when addon disabled

- **Route Registration API**
  - `zed_register_route()` for self-registering addon routes
  - Pattern matching support (`{param}` syntax)
  - Multiple HTTP method support
  - Automatic permission checks
  - Layout wrapping control
  - Priority-based routing

- **Test Addons**
  - `test_menu_api.php` - Demonstrates Admin Menu API
  - `test_route_api.php` - Demonstrates Route Registration API
  - `test_deprecation.php` - Demonstrates Deprecation System

### Changed
- Updated `ARCHITECTURE.md` with new APIs (v3.0.1)
- Updated wiki documentation (`DOCS.md`, addon development guides)
- Created comprehensive API reference guide (`admin-menu-api.md`)
- Improved addon menu filtering to respect activation status

### Fixed
- Content status field now persists correctly in menu manager
- Disabled addon menus no longer appear in admin sidebar
- API endpoint routing for `/admin/api/save-menu` and `/admin/save-post`

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
