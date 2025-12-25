# Zed CMS API Stability Guide

**Version:** 3.1.0  
**Last Updated:** 2024-12-25

This document lists all public APIs in Zed CMS and their stability guarantees.

---

## Understanding Stability Levels

### ✅ **Stable** (`@api-stable`)
- **Promise:** Will not change in minor versions (3.x)
- **Safe to use:** Yes, recommended for production
- **Breaking changes:** Only in major versions (4.0.0+)
- **Deprecation:** 6+ month notice before removal

### ⚠️ **Experimental** (`@api-experimental`)
- **Promise:** May change without notice
- **Safe to use:** Use with caution
- **Breaking changes:** Possible in any version
- **Deprecation:** May be removed quickly

### 🔒 **Internal** (`@api-internal`)
- **Promise:** No stability guarantee
- **Safe to use:** No, for core use only
- **Breaking changes:** Can change anytime
- **Deprecation:** No notice required

---

## Stable APIs (Safe to Use)

### Admin Menu System
**Since:** 3.0.0

```php
/**
 * @api-stable
 */
function zed_register_admin_menu(array $args): void
function zed_register_admin_submenu(string $parent_id, array $args): void
function zed_register_capabilities(array $capabilities): void
```

**Guaranteed Parameters:**
- `id` (string, required) - Menu identifier
- `title` (string, required) - Display title
- `capability` (string, optional) - Required capability
- `callback` (callable, required) - Page callback
- `icon` (string, optional) - Icon name
- `position` (int, optional) - Menu position

### Route Registration
**Since:** 3.0.0

```php
/**
 * @api-stable
 */
function zed_register_route(array $args): void
```

**Guaranteed Parameters:**
- `path` (string, required) - Route path
- `method` (string, optional) - HTTP method
- `callback` (callable, required) - Route handler
- `capability` (string, optional) - Required capability
- `wrap_layout` (bool, optional) - Wrap in admin layout

### Core Events (Stable)
**Since:** 1.0.0

```php
/**
 * @api-stable
 */
Event::on('app_init', callable $callback): void
Event::on('app_ready', callable $callback): void
Event::on('route_request', callable $callback): void
Event::trigger(string $event, mixed $data = null): void
```

### Options API
**Since:** 1.0.0

```php
/**
 * @api-stable
 */
function zed_get_option(string $key, mixed $default = null): mixed
function zed_update_option(string $key, mixed $value): bool
function zed_delete_option(string $key): bool
```

### User & Capabilities
**Since:** 2.0.0

```php
/**
 * @api-stable
 */
function zed_current_user_can(string $capability): bool
function zed_user_can_access_admin(): bool
function zed_get_current_user(): ?array
```

### Content Helpers
**Since:** 2.0.0

```php
/**
 * @api-stable
 */
function zed_get_post_by_slug(string $slug): ?array
function zed_get_posts(array $args = []): array
function zed_get_page_by_id(int $id): ?array
```

### Deprecation System
**Since:** 3.1.0

```php
/**
 * @api-stable
 */
function zed_deprecated_function(string $function, string $version, string $replacement = ''): void
function zed_deprecated_hook(string $hook, string $version, string $replacement = ''): void
function zed_deprecated_argument(string $function, string $argument, string $version, string $message = ''): void
```

---

## Experimental APIs (Use with Caution)

### Admin Notices
**Since:** 3.0.0

```php
/**
 * @api-experimental
 * May change in future versions
 */
function zed_add_notice(string $message, string $type = 'info'): void
```

### Custom Post Types
**Since:** 2.5.0

```php
/**
 * @api-experimental
 * API may be refined
 */
function zed_register_post_type(string $slug, array $args): void
function zed_get_post_types(bool $include_builtin = false): array
```

---

## Internal APIs (Do Not Use)

These are for core system use only and may change without notice:

- `Core\Router::*` - Use events instead
- `Core\Database::*` - Use helper functions
- `zed_render_*` internal functions
- `$_GLOBALS['zed_*']` variables

---

## Migration Guides

### Upgrading from 2.x to 3.x
- All 2.x stable APIs remain stable in 3.x
- New Admin Menu API available (recommended)
- Old manual menu registration still works (deprecated in 3.2.0)

### Preparing for 4.0
- Review deprecation warnings in debug mode
- Migrate to new APIs before 4.0 release
- Minimum 6 months notice for all breaking changes

---

## Reporting Issues

If a stable API breaks in a minor version update:
1. This is a **bug** - please report it
2. We will fix it or provide migration path
3. Your code should not need changes

---

## Version History

- **3.1.0** - Added deprecation system, API stability markers
- **3.0.0** - Added Admin Menu & Route Registration APIs
- **2.0.0** - Stabilized core helper functions
- **1.0.0** - Initial stable release
