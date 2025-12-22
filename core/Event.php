<?php

declare(strict_types=1);

namespace Core;

/**
 * Zero CMS Event Manager (The Heart)
 * 
 * A WordPress-inspired hook system using modern OOP PHP.
 * Provides the ability to register actions (do things) and filters (modify data).
 * This is the spine of the CMS—all addon communication happens through this.
 */
final class Event
{
    /**
     * Storage for all registered listeners.
     * Structure: ['event_name' => [priority => [callbacks]]]
     *
     * @var array<string, array<int, array<callable>>>
     */
    private static array $listeners = [];

    /**
     * Cache for sorted listeners to avoid re-sorting on every trigger.
     *
     * @var array<string, array<callable>>
     */
    private static array $sortedListeners = [];

    /**
     * Register a listener (hook) for an event.
     * 
     * Works like WordPress add_action() / add_filter().
     * Lower priority numbers run first (e.g., 1 runs before 10).
     *
     * @param string   $name     The event name (hook).
     * @param callable $callback The callback to execute.
     * @param int      $priority Priority of execution. Lower = earlier. Default: 10.
     * @return void
     */
    public static function on(string $name, callable $callback, int $priority = 10): void
    {
        // Initialize the event's listener array if not exists
        if (!isset(self::$listeners[$name])) {
            self::$listeners[$name] = [];
        }

        // Initialize the priority level if not exists
        if (!isset(self::$listeners[$name][$priority])) {
            self::$listeners[$name][$priority] = [];
        }

        // Add the callback to the specified priority level
        self::$listeners[$name][$priority][] = $callback;

        // Invalidate the sorted cache for this event
        unset(self::$sortedListeners[$name]);
    }

    /**
     * Storage for scoped listeners with context conditions.
     * Structure: ['event_name' => [priority => [['callback' => callable, 'context' => array]]]]
     *
     * @var array<string, array<int, array<array{callback: callable, context: array<string, mixed>}>>>
     */
    private static array $scopedListeners = [];

    /**
     * Register a SCOPED listener that only fires when context matches.
     * 
     * This solves WordPress's biggest hook flaw - hooks fire globally.
     * With scoped hooks, you can bind to specific post types, templates, etc.
     *
     * Usage:
     *   Event::onScoped('zed_head', function() {
     *       echo '<link rel="stylesheet" href="product-styles.css">';
     *   }, ['post_type' => 'product']);
     *
     * @param string   $name     The event name (hook).
     * @param callable $callback The callback to execute.
     * @param array<string, mixed> $context Context conditions to match.
     * @param int      $priority Priority of execution. Default: 10.
     * @return void
     */
    public static function onScoped(string $name, callable $callback, array $context, int $priority = 10): void
    {
        if (!isset(self::$scopedListeners[$name])) {
            self::$scopedListeners[$name] = [];
        }

        if (!isset(self::$scopedListeners[$name][$priority])) {
            self::$scopedListeners[$name][$priority] = [];
        }

        self::$scopedListeners[$name][$priority][] = [
            'callback' => $callback,
            'context' => $context,
        ];
    }

    /**
     * Remove a listener from an event.
     *
     * @param string   $name     The event name.
     * @param callable $callback The callback to remove.
     * @param int      $priority The priority it was registered with.
     * @return bool True if removed, false if not found.
     */
    public static function off(string $name, callable $callback, int $priority = 10): bool
    {
        if (!isset(self::$listeners[$name][$priority])) {
            return false;
        }

        foreach (self::$listeners[$name][$priority] as $index => $registered) {
            if ($registered === $callback) {
                unset(self::$listeners[$name][$priority][$index]);
                // Re-index the array
                self::$listeners[$name][$priority] = array_values(self::$listeners[$name][$priority]);
                // Invalidate the sorted cache
                unset(self::$sortedListeners[$name]);
                return true;
            }
        }

        return false;
    }

    /**
     * Trigger an action event (do something).
     * 
     * Like WordPress do_action(). Executes all registered callbacks.
     * Does NOT return a value—used for side effects.
     *
     * @param string $name    The event name (hook).
     * @param mixed  $payload Optional data to pass to listeners.
     * @param mixed  ...$args Additional arguments.
     * @return void
     */
    public static function trigger(string $name, mixed $payload = null, mixed ...$args): void
    {
        $listeners = self::getSortedListeners($name);

        foreach ($listeners as $callback) {
            $callback($payload, ...$args);
        }
    }

    /**
     * Trigger a SCOPED action event with context matching.
     * 
     * Only executes callbacks whose context conditions match the current context.
     * Also fires regular (non-scoped) listeners.
     *
     * Usage:
     *   Event::triggerScoped('zed_head', ['post_type' => 'product'], $post);
     *
     * @param string $name    The event name (hook).
     * @param array<string, mixed> $context Current context to match against.
     * @param mixed  $payload Optional data to pass to listeners.
     * @param mixed  ...$args Additional arguments.
     * @return void
     */
    public static function triggerScoped(string $name, array $context, mixed $payload = null, mixed ...$args): void
    {
        // First, fire all regular listeners
        self::trigger($name, $payload, ...$args);

        // Then, fire scoped listeners that match context
        if (!isset(self::$scopedListeners[$name])) {
            return;
        }

        // Sort by priority
        $priorities = self::$scopedListeners[$name];
        ksort($priorities, SORT_NUMERIC);

        foreach ($priorities as $listeners) {
            foreach ($listeners as $listener) {
                $callback = $listener['callback'];
                $requiredContext = $listener['context'];

                // Check if all required context keys match
                $matches = true;
                foreach ($requiredContext as $key => $value) {
                    if (!isset($context[$key]) || $context[$key] !== $value) {
                        $matches = false;
                        break;
                    }
                }

                if ($matches) {
                    $callback($payload, ...$args);
                }
            }
        }
    }

    /**
     * Apply a filter to a value (modify data).
     * 
     * Like WordPress apply_filters(). Each callback receives the value
     * and can modify it. The final modified value is returned.
     *
     * @param string $name  The filter name (hook).
     * @param mixed  $value The initial value to filter.
     * @param mixed  ...$args Additional arguments to pass to each callback.
     * @return mixed The filtered/modified value.
     */
    public static function filter(string $name, mixed $value, mixed ...$args): mixed
    {
        $listeners = self::getSortedListeners($name);

        foreach ($listeners as $callback) {
            $value = $callback($value, ...$args);
        }

        return $value;
    }

    /**
     * Check if an event has any registered listeners.
     *
     * @param string $name The event name.
     * @return bool True if listeners exist, false otherwise.
     */
    public static function hasListeners(string $name): bool
    {
        return !empty(self::$listeners[$name]);
    }

    /**
     * Get all registered listeners for an event (for debugging).
     *
     * @param string|null $name Optional event name. If null, returns all listeners.
     * @return array<string, array<int, array<callable>>>|array<int, array<callable>>
     */
    public static function getListeners(?string $name = null): array
    {
        if ($name === null) {
            return self::$listeners;
        }

        return self::$listeners[$name] ?? [];
    }

    /**
     * Clear all listeners for an event, or all events.
     *
     * @param string|null $name Optional event name. If null, clears everything.
     * @return void
     */
    public static function clear(?string $name = null): void
    {
        if ($name === null) {
            self::$listeners = [];
            self::$sortedListeners = [];
        } else {
            unset(self::$listeners[$name], self::$sortedListeners[$name]);
        }
    }

    /**
     * Get listeners sorted by priority (cached for performance).
     *
     * @param string $name The event name.
     * @return array<callable> Flat array of callbacks sorted by priority.
     */
    private static function getSortedListeners(string $name): array
    {
        // Return empty if no listeners registered
        if (!isset(self::$listeners[$name])) {
            return [];
        }

        // Return cached sorted listeners if available
        if (isset(self::$sortedListeners[$name])) {
            return self::$sortedListeners[$name];
        }

        // Sort by priority (lower numbers first)
        $priorities = self::$listeners[$name];
        ksort($priorities, SORT_NUMERIC);

        // Flatten into a single array
        $sorted = [];
        foreach ($priorities as $callbacks) {
            foreach ($callbacks as $callback) {
                $sorted[] = $callback;
            }
        }

        // Cache and return
        self::$sortedListeners[$name] = $sorted;
        return $sorted;
    }
}
