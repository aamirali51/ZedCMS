<?php
/**
 * Admin System — Entry Point
 * 
 * Core admin system for Zed CMS. This is a SYSTEM module that cannot be disabled.
 * 
 * This file loads all admin modules from _system/admin/:
 * - rbac.php      — Role-Based Access Control
 * - api.php       — AJAX, Notices, Settings, Metabox, Enqueue APIs
 * - helpers.php   — Content processing, image handling
 * - renderer.php  — Theme-agnostic rendering service
 * - routes.php    — All admin route handlers
 * 
 * Routes handled:
 * - /admin/login           → Login page
 * - /admin                  → Dashboard (requires auth)
 * - /admin/dashboard        → Dashboard alias
 * - /api/ajax/{action}      → AJAX handlers
 * - /admin/addon-settings/* → Addon settings pages
 * 
 * @package ZedCMS\System\Admin
 * @version 3.0.0
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;
use Core\Auth;
use Core\Database;

// =============================================================================
// LOAD ADMIN MODULES
// =============================================================================

$adminDir = __DIR__ . '/admin';

// 1. RBAC System (roles, capabilities, access control)
require_once $adminDir . '/rbac.php';

// 2. Deprecation helpers (API stability system)
require_once __DIR__ . '/helpers_deprecation.php';

// 3. Addon APIs (AJAX, notices, settings, metabox, enqueue)
require_once $adminDir . '/api.php';

// 4. Helper functions (content processing, image handling)
require_once $adminDir . '/helpers.php';

// 5. Rendering service (theme-agnostic view rendering)
require_once $adminDir . '/renderer.php';

// 6. Admin Menu Registry (addon menu registration API)
require_once $adminDir . '/menu_registry.php';

// 7. Route Registry (addon route registration API)
require_once $adminDir . '/route_registry.php';

// 8. Deprecation admin notices (debug mode warnings)
require_once $adminDir . '/deprecation_notices.php';

// 9. Controller routes (class-based routing)
require_once $adminDir . '/controllers/register_routes.php';

// 10. Route handlers (all /admin/* and /api/* routes)
require_once $adminDir . '/routes.php';
