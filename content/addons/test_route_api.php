<?php
/**
 * Route Registration API - Test Addon
 * 
 * Demonstrates both Menu API and Route API usage
 */

// Test 1: Menu with auto-registered route
zed_register_admin_menu([
    'id' => 'test_addon',
    'title' => 'Test Addon',
    'icon' => 'science',
    'capability' => 'manage_options',
    'position' => 55,
    'badge' => '3',
    'callback' => function() {
        echo '<h1>Menu API + Auto Route</h1>';
        echo '<p>This route was automatically registered by the Menu API!</p>';
    }
]);

// Test 2: Direct route registration (no menu)
zed_register_route([
    'path' => '/admin/test-direct-route',
    'capability' => 'manage_options',
    'callback' => function() {
        return '<h1>Direct Route Registration</h1><p>This route was registered directly without a menu!</p>';
    }
]);

// Test 3: Pattern matching route
zed_register_route([
    'path' => '/admin/test-reports/{type}',
    'capability' => 'manage_options',
    'callback' => function($request, $uri, $params) {
        $type = $params['type'] ?? 'unknown';
        return "<h1>Report: {$type}</h1><p>Pattern matching works! Extracted: {$type}</p>";
    }
]);

// Test 4: API endpoint (no layout)
zed_register_route([
    'path' => '/admin/api/test-endpoint',
    'method' => 'POST',
    'capability' => 'manage_options',
    'wrap_layout' => false,
    'callback' => function() {
        header('Content-Type: application/json');
        return json_encode(['success' => true, 'message' => 'Route API works!']);
    }
]);

error_log('[ROUTE_TEST] All test routes registered successfully');
