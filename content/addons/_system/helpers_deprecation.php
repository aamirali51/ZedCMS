<?php
/**
 * Deprecation Helper Functions
 * 
 * Global helper functions for marking APIs as deprecated.
 * Provides a simple interface to the Core\Deprecation class.
 * 
 * @package ZedCMS\Helpers
 * @since 3.1.0
 */

declare(strict_types=1);

use Core\Deprecation;

/**
 * Mark a function as deprecated
 * 
 * @param string $function Function name
 * @param string $version Version when deprecated
 * @param string $replacement Replacement function (optional)
 * @return void
 * 
 * @example
 * ```php
 * function zed_old_function() {
 *     zed_deprecated_function('zed_old_function', '3.2.0', 'zed_new_function');
 *     // Old implementation...
 * }
 * ```
 */
function zed_deprecated_function(
    string $function,
    string $version,
    string $replacement = ''
): void {
    Deprecation::function($function, $version, $replacement);
}

/**
 * Mark a hook/event as deprecated
 * 
 * @param string $hook Hook name
 * @param string $version Version when deprecated
 * @param string $replacement Replacement hook (optional)
 * @return void
 * 
 * @example
 * ```php
 * Event::on('old_hook', function() {
 *     zed_deprecated_hook('old_hook', '3.2.0', 'new_hook');
 * });
 * ```
 */
function zed_deprecated_hook(
    string $hook,
    string $version,
    string $replacement = ''
): void {
    Deprecation::hook($hook, $version, $replacement);
}

/**
 * Mark a function argument as deprecated
 * 
 * @param string $function Function name
 * @param string $argument Argument name
 * @param string $version Version when deprecated
 * @param string $message Additional message (optional)
 * @return void
 * 
 * @example
 * ```php
 * function zed_some_function($arg1, $oldArg = null) {
 *     if ($oldArg !== null) {
 *         zed_deprecated_argument(
 *             'zed_some_function',
 *             '$oldArg',
 *             '3.2.0',
 *             'This argument is no longer used.'
 *         );
 *     }
 * }
 * ```
 */
function zed_deprecated_argument(
    string $function,
    string $argument,
    string $version,
    string $message = ''
): void {
    Deprecation::argument($function, $argument, $version, $message);
}

/**
 * Mark a class property as deprecated
 * 
 * @param string $class Class name
 * @param string $property Property name
 * @param string $version Version when deprecated
 * @param string $replacement Replacement property (optional)
 * @return void
 * 
 * @example
 * ```php
 * class MyClass {
 *     public function __get($name) {
 *         if ($name === 'oldProperty') {
 *             zed_deprecated_property(
 *                 'MyClass',
 *                 'oldProperty',
 *                 '3.2.0',
 *                 'newProperty'
 *             );
 *         }
 *     }
 * }
 * ```
 */
function zed_deprecated_property(
    string $class,
    string $property,
    string $version,
    string $replacement = ''
): void {
    Deprecation::property($class, $property, $version, $replacement);
}

/**
 * Get all deprecation warnings
 * 
 * Useful for displaying warnings in admin panel.
 * 
 * @return array Array of warnings
 */
function zed_get_deprecation_warnings(): array
{
    return Deprecation::getWarnings();
}

/**
 * Get count of deprecation warnings
 * 
 * @return int Number of warnings
 */
function zed_get_deprecation_count(): int
{
    return Deprecation::getWarningCount();
}

/**
 * Clear all deprecation warnings
 * 
 * @return void
 */
function zed_clear_deprecation_warnings(): void
{
    Deprecation::clearWarnings();
}
