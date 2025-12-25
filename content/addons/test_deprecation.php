<?php
/**
 * Test Addon - Deprecation System Demo
 * 
 * Demonstrates the deprecation system with example deprecated functions.
 * Enable this addon and visit /admin to see deprecation warnings in debug mode.
 * 
 * @package ZedCMS\Addons
 * @since 3.1.0
 */

declare(strict_types=1);

use Core\Event;

/**
 * Example: Deprecated function
 */
function zed_old_test_function(): string
{
    // Mark this function as deprecated
    zed_deprecated_function(
        'zed_old_test_function',
        '3.1.0',
        'zed_new_test_function'
    );
    
    return 'This is the old function (still works)';
}

/**
 * Example: New replacement function
 */
function zed_new_test_function(): string
{
    return 'This is the new function';
}

/**
 * Example: Function with deprecated argument
 */
function zed_test_with_deprecated_arg(string $name, ?string $oldArg = null): string
{
    if ($oldArg !== null) {
        zed_deprecated_argument(
            'zed_test_with_deprecated_arg',
            '$oldArg',
            '3.1.0',
            'This argument is no longer used and will be removed in 4.0.0'
        );
    }
    
    return "Hello, {$name}!";
}

/**
 * Example: Deprecated hook
 */
Event::on('app_ready', function(): void {
    // Trigger old hook (deprecated)
    Event::trigger('old_test_hook', ['data' => 'test']);
});

Event::on('old_test_hook', function(array $data): void {
    // Mark this hook as deprecated
    zed_deprecated_hook(
        'old_test_hook',
        '3.1.0',
        'new_test_hook'
    );
    
    // Then trigger the new hook
    Event::trigger('new_test_hook', $data);
});

/**
 * Test the deprecated functions when addon loads
 */
if (defined('ZED_DEBUG') && ZED_DEBUG === true) {
    // These will trigger deprecation warnings
    $result1 = zed_old_test_function();
    $result2 = zed_test_with_deprecated_arg('World', 'deprecated_value');
}

/**
 * Add test menu to demonstrate in admin
 */
Event::on('admin_menu', function(): void {
    zed_register_admin_menu([
        'id' => 'test_deprecation',
        'title' => 'Deprecation Test',
        'icon' => 'warning',
        'capability' => 'manage_options',
        'callback' => function() {
            echo '<div class="zed-admin-page">';
            echo '<h1>Deprecation System Test</h1>';
            echo '<p>This page demonstrates the deprecation system.</p>';
            
            echo '<h2>Test 1: Deprecated Function</h2>';
            echo '<pre>';
            echo zed_old_test_function();
            echo '</pre>';
            
            echo '<h2>Test 2: Deprecated Argument</h2>';
            echo '<pre>';
            echo zed_test_with_deprecated_arg('Test', 'old_value');
            echo '</pre>';
            
            echo '<h2>Deprecation Warnings</h2>';
            $warnings = zed_get_deprecation_warnings();
            echo '<p>Total warnings: ' . count($warnings) . '</p>';
            echo '<pre>' . print_r($warnings, true) . '</pre>';
            
            echo '</div>';
        },
    ]);
});
