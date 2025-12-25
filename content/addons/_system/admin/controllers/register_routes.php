<?php
/**
 * Controller Route Registration
 * 
 * Registers all controller-based routes using the Route Registry API.
 * This file is loaded by admin.php after the route registry is available.
 * 
 * @package ZedCMS\Admin
 * @since 3.2.0
 */

declare(strict_types=1);

use Admin\Controllers\ContentController;

// =============================================================================
// CONTENT ROUTES (Posts & Pages)
// =============================================================================

/**
 * List posts/pages
 * Route: GET /admin/content
 */
zed_register_route([
    'path' => '/admin/content',
    'method' => 'GET',
    'capability' => 'manage_options',
    'wrap_layout' => false,  // AdminRenderer already wraps the layout
    'callback' => function() {
        $controller = new ContentController();
        $controller->index();
    },
    'priority' => 10
]);

/**
 * Edit post/page
 * Route: GET /admin/content/edit
 */
zed_register_route([
    'path' => '/admin/content/edit',
    'method' => 'GET',
    'capability' => 'manage_options',
    'wrap_layout' => false,
    'callback' => function() {
        $controller = new ContentController();
        $controller->edit();
    },
    'priority' => 10
]);

/**
 * Save post/page (API)
 * Route: POST /admin/api/content/save
 */
zed_register_route([
    'path' => '/admin/api/content/save',
    'method' => 'POST',
    'capability' => 'manage_options',  // Changed for testing
    'wrap_layout' => false,
    'callback' => function() {
        $controller = new ContentController();
        $controller->save();
    },
    'priority' => 10
]);
