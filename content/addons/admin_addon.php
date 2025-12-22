<?php
/**
 * Admin Addon — Entry Point
 * 
 * Modular admin system for Zed CMS.
 * 
 * This file loads all admin modules:
 * - admin/rbac.php      — Role-Based Access Control
 * - admin/api.php       — AJAX, Notices, Settings, Metabox, Enqueue APIs
 * - admin/helpers.php   — Content processing, image handling
 * - admin/renderer.php  — Theme-agnostic rendering service
 * - admin/routes.php    — All admin route handlers
 * 
 * Routes:
 * - /admin/login  -> Login page
 * - /admin        -> Dashboard (requires auth)
 * - /admin/dashboard -> Dashboard alias
 * - /api/ajax/{action} -> AJAX handlers
 * - /admin/addon-settings/{id} -> Addon settings pages
 * 
 * @package ZedCMS\Admin
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

// 2. Addon APIs (AJAX, notices, settings, metabox, enqueue)
require_once $adminDir . '/api.php';

// 3. Helper functions (content processing, image handling)
require_once $adminDir . '/helpers.php';

// 4. Rendering service (theme-agnostic view rendering)
require_once $adminDir . '/renderer.php';

// 5. Route handlers (all /admin/* and /api/* routes)
require_once $adminDir . '/routes.php';
