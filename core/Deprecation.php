<?php
/**
 * Deprecation System
 * 
 * Provides formal deprecation warnings for functions, hooks, and arguments.
 * Enables safe API evolution with advance warning to developers.
 * 
 * @package ZedCMS\Core
 * @since 3.1.0
 */

declare(strict_types=1);

namespace Core;

/**
 * Deprecation - Track and warn about deprecated APIs
 * 
 * Provides a professional deprecation system similar to WordPress.
 * Warnings only appear in debug mode to avoid production noise.
 */
class Deprecation
{
    /**
     * Track triggered warnings to prevent duplicates
     */
    private static array $warnings = [];
    
    /**
     * Enable/disable deprecation warnings
     */
    private static bool $enabled = true;
    
    /**
     * Mark a function as deprecated
     * 
     * @param string $function Function name
     * @param string $version Version when deprecated
     * @param string $replacement Replacement function name (optional)
     * @return void
     */
    public static function function(
        string $function,
        string $version,
        string $replacement = ''
    ): void {
        $message = sprintf(
            'Function %s is deprecated since version %s.',
            $function,
            $version
        );
        
        if ($replacement) {
            $message .= sprintf(' Use %s instead.', $replacement);
        }
        
        self::trigger($message, $function);
    }
    
    /**
     * Mark a hook/event as deprecated
     * 
     * @param string $hook Hook name
     * @param string $version Version when deprecated
     * @param string $replacement Replacement hook name (optional)
     * @return void
     */
    public static function hook(
        string $hook,
        string $version,
        string $replacement = ''
    ): void {
        $message = sprintf(
            'Hook "%s" is deprecated since version %s.',
            $hook,
            $version
        );
        
        if ($replacement) {
            $message .= sprintf(' Use "%s" instead.', $replacement);
        }
        
        self::trigger($message, $hook);
    }
    
    /**
     * Mark a function argument as deprecated
     * 
     * @param string $function Function name
     * @param string $argument Argument name
     * @param string $version Version when deprecated
     * @param string $message Additional message (optional)
     * @return void
     */
    public static function argument(
        string $function,
        string $argument,
        string $version,
        string $message = ''
    ): void {
        $msg = sprintf(
            'The %s argument of %s is deprecated since version %s.',
            $argument,
            $function,
            $version
        );
        
        if ($message) {
            $msg .= ' ' . $message;
        }
        
        self::trigger($msg, $function . '::' . $argument);
    }
    
    /**
     * Mark a class property as deprecated
     * 
     * @param string $class Class name
     * @param string $property Property name
     * @param string $version Version when deprecated
     * @param string $replacement Replacement property (optional)
     * @return void
     */
    public static function property(
        string $class,
        string $property,
        string $version,
        string $replacement = ''
    ): void {
        $message = sprintf(
            'Property %s::$%s is deprecated since version %s.',
            $class,
            $property,
            $version
        );
        
        if ($replacement) {
            $message .= sprintf(' Use %s instead.', $replacement);
        }
        
        self::trigger($message, $class . '::' . $property);
    }
    
    /**
     * Trigger a deprecation warning
     * 
     * @param string $message Warning message
     * @param string $key Unique key to prevent duplicates
     * @return void
     */
    private static function trigger(string $message, string $key): void
    {
        // Prevent duplicate warnings
        if (isset(self::$warnings[$key])) {
            return;
        }
        
        self::$warnings[$key] = [
            'message' => $message,
            'time' => time(),
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5),
        ];
        
        // Only show warnings if enabled and in debug mode
        if (!self::$enabled || !self::isDebugMode()) {
            return;
        }
        
        // Log to error log
        $caller = self::getCaller();
        error_log(sprintf(
            '[ZED DEPRECATION] %s (called from %s)',
            $message,
            $caller
        ));
        
        // Trigger event for admin notices
        if (class_exists('\Core\Event')) {
            \Core\Event::trigger('deprecation_warning', [
                'message' => $message,
                'caller' => $caller,
                'key' => $key,
            ]);
        }
    }
    
    /**
     * Get the caller information from backtrace
     * 
     * @return string Caller file and line
     */
    private static function getCaller(): string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        
        // Skip internal Deprecation class calls
        foreach ($trace as $item) {
            if (!isset($item['file'])) {
                continue;
            }
            
            if (strpos($item['file'], 'Deprecation.php') !== false) {
                continue;
            }
            
            return sprintf(
                '%s:%d',
                basename($item['file']),
                $item['line'] ?? 0
            );
        }
        
        return 'unknown';
    }
    
    /**
     * Check if debug mode is enabled
     * 
     * @return bool True if debug mode
     */
    private static function isDebugMode(): bool
    {
        // Check for ZED_DEBUG constant
        if (defined('ZED_DEBUG') && ZED_DEBUG === true) {
            return true;
        }
        
        // Check for debug option
        if (function_exists('zed_get_option')) {
            return zed_get_option('debug_mode', '0') === '1';
        }
        
        return false;
    }
    
    /**
     * Enable deprecation warnings
     * 
     * @return void
     */
    public static function enable(): void
    {
        self::$enabled = true;
    }
    
    /**
     * Disable deprecation warnings
     * 
     * @return void
     */
    public static function disable(): void
    {
        self::$enabled = false;
    }
    
    /**
     * Get all triggered warnings
     * 
     * @return array Array of warnings
     */
    public static function getWarnings(): array
    {
        return self::$warnings;
    }
    
    /**
     * Clear all warnings
     * 
     * @return void
     */
    public static function clearWarnings(): void
    {
        self::$warnings = [];
    }
    
    /**
     * Get count of triggered warnings
     * 
     * @return int Number of warnings
     */
    public static function getWarningCount(): int
    {
        return count(self::$warnings);
    }
}
