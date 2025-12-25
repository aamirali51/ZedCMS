<?php
/**
 * Frontend System — Entry Point
 * 
 * Core frontend system for Zed CMS. This is a SYSTEM module that cannot be disabled.
 * Handles public-facing routes, theming, content rendering, and the full Theme API.
 * 
 * This file loads all frontend modules from _system/frontend/:
 * - options.php       — Site options and settings retrieval
 * - post_types.php    — Custom Post Type engine
 * - theme_api.php     — Theme settings and asset enqueue
 * - template_data.php — Template data injection
 * - renderer.php      — BlockNote JSON to HTML conversion
 * - menus.php         — Navigation menu system
 * - queries.php       — Content query functions
 * - theme_parts.php   — Theme partials system
 * - seo_head.php      — SEO metadata injection
 * - routes.php        — Frontend route handler (Controller Pattern)
 * 
 * Additionally loads helper files from frontend/ subdirectory for:
 * - URL helpers, utility functions, conditionals
 * - Content retrieval, data extraction, media handling
 * - Author, taxonomy, pagination, related content
 * - Security, shortcodes, caching, email
 * 
 * Routes handled:
 * - /{slug}           → View published content
 * - /preview/{id}     → Preview content (requires auth)
 * - /blog             → Blog archive
 * - /{post_type}      → Custom post type archives
 * 
 * @package ZedCMS\System\Frontend
 * @version 3.0.0
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;
use Core\Auth;
use Core\Database;

// =============================================================================
// LOAD FRONTEND CORE MODULES (in dependency order)
// =============================================================================

$frontendDir = __DIR__ . '/frontend';

// 1. Options API (no dependencies - needed by everything else)
require_once $frontendDir . '/options.php';

// 2. Custom Post Type Engine (needs options)
require_once $frontendDir . '/post_types.php';

// 3. Theme API (needs options)
require_once $frontendDir . '/theme_api.php';

// 4. Template Data System (needs Event)
require_once $frontendDir . '/template_data.php';

// 5. Block Renderer (standalone)
require_once $frontendDir . '/renderer.php';

// 6. Menu System (needs Database, Router)
require_once $frontendDir . '/menus.php';

// 7. Content Queries (needs Database)
require_once $frontendDir . '/queries.php';

// 8. Theme Parts (needs options, Router)
require_once $frontendDir . '/theme_parts.php';

// 9. SEO Head (needs options, Event)
require_once $frontendDir . '/seo_head.php';

// 10. Caching API (standalone - file-based cache)
require_once $frontendDir . '/cache.php';

// 11. Deprecation helpers (API stability system)
require_once __DIR__ . '/helpers_deprecation.php';

// 12. Context Registry (replaces global variables with proper object)
require_once $frontendDir . '/context.php';

// =============================================================================
// LOAD HELPER FILES (legacy helpers from frontend/ subdirectory)
// =============================================================================

// These helpers provide additional theme functions and are loaded in dependency order
$helpers = [
    'helpers_urls.php',         // URLs (no dependencies)
    'helpers_utils.php',        // Utilities (no dependencies)
    'helpers_security.php',     // Nonces/CSRF (no dependencies)
    'helpers_conditionals.php', // Conditionals (needs Router)
    'helpers_shortcodes.php',   // Shortcodes (no dependencies)
    'helpers_content.php',      // Content queries (needs Database)
    'helpers_data.php',         // Data extraction (needs helpers_utils, helpers_content)
    'helpers_media.php',        // Images (needs helpers_data)
    'helpers_author.php',       // Authors (needs Database, helpers_content)
    'helpers_taxonomy.php',     // Categories (needs Database, helpers_data)
    'helpers_pagination.php',   // Pagination (needs Router)
    'helpers_seo.php',          // SEO (needs helpers_data, helpers_urls)
    'helpers_related.php',      // Related (needs helpers_content, helpers_taxonomy)
    'helpers_cache.php',        // Transients (needs Database)
    'helpers_email.php',        // Email (no dependencies)
];

foreach ($helpers as $helper) {
    $helperPath = $frontendDir . '/' . $helper;
    if (file_exists($helperPath)) {
        require_once $helperPath;
    }
}

// =============================================================================
// LOAD ROUTE HANDLER (must be last - depends on all above)
// =============================================================================

require_once $frontendDir . '/routes.php';
