<?php
/**
 * Zed CMS — Cron Handler
 * 
 * Processes scheduled tasks registered via zed_schedule_event().
 * Call this file via server cron:
 *   * * * * * curl -s http://yoursite.com/cron.php > /dev/null 2>&1
 * 
 * Or via WP-style pseudo-cron (triggered on page loads):
 *   Define ZED_ALTERNATE_CRON in config.php
 * 
 * @package ZedCMS
 */

declare(strict_types=1);

// Prevent direct browser access without proper setup
if (php_sapi_name() !== 'cli' && !defined('DOING_CRON')) {
    // Check for secret key
    $secret = $_GET['key'] ?? '';
    $expectedKey = defined('ZED_CRON_KEY') ? ZED_CRON_KEY : '';
    
    if (!empty($expectedKey) && $secret !== $expectedKey) {
        http_response_code(403);
        die('Access denied');
    }
}

define('DOING_CRON', true);

// Load the CMS
require_once __DIR__ . '/index.php';

use Core\Database;
use Core\Event;

// =============================================================================
// SCHEDULED EVENTS SYSTEM
// =============================================================================

global $ZED_CRON_SCHEDULES, $ZED_CRON_EVENTS;
$ZED_CRON_SCHEDULES = [
    'hourly' => ['interval' => 3600, 'display' => 'Once Hourly'],
    'twicedaily' => ['interval' => 43200, 'display' => 'Twice Daily'],
    'daily' => ['interval' => 86400, 'display' => 'Once Daily'],
    'weekly' => ['interval' => 604800, 'display' => 'Once Weekly'],
];
$ZED_CRON_EVENTS = [];

/**
 * Add a custom cron schedule
 * 
 * @param string $name Schedule name
 * @param int $interval Interval in seconds
 * @param string $display Human-readable display name
 */
function zed_add_cron_schedule(string $name, int $interval, string $display = ''): void
{
    global $ZED_CRON_SCHEDULES;
    $ZED_CRON_SCHEDULES[$name] = [
        'interval' => $interval,
        'display' => $display ?: $name,
    ];
}

/**
 * Schedule a recurring event
 * 
 * @param string $hook Event hook name
 * @param string $recurrence Schedule name (hourly, daily, etc.)
 * @param callable $callback Function to call
 * @param int $timestamp First run time (default: now)
 */
function zed_schedule_event(string $hook, string $recurrence, callable $callback, int $timestamp = 0): void
{
    global $ZED_CRON_EVENTS, $ZED_CRON_SCHEDULES;
    
    if (!isset($ZED_CRON_SCHEDULES[$recurrence])) {
        return; // Invalid schedule
    }
    
    $ZED_CRON_EVENTS[$hook] = [
        'recurrence' => $recurrence,
        'callback' => $callback,
        'next_run' => $timestamp ?: time(),
    ];
    
    // Also register as event listener
    Event::on($hook, $callback);
}

/**
 * Unschedule an event
 */
function zed_unschedule_event(string $hook): void
{
    global $ZED_CRON_EVENTS;
    unset($ZED_CRON_EVENTS[$hook]);
}

/**
 * Get the next scheduled run time for an event
 */
function zed_next_scheduled(string $hook): ?int
{
    try {
        $db = Database::getInstance();
        $row = $db->queryOne(
            "SELECT option_value FROM zed_options WHERE option_key = :key",
            ['key' => '_cron_' . $hook]
        );
        
        if ($row) {
            $data = json_decode($row['option_value'], true);
            return $data['next_run'] ?? null;
        }
    } catch (\Exception $e) {
        // Ignore
    }
    
    return null;
}

/**
 * Run all scheduled cron events
 * 
 * @return array Results with hooks run
 */
function zed_run_cron(): array
{
    global $ZED_CRON_EVENTS, $ZED_CRON_SCHEDULES;
    
    $results = ['run' => [], 'skipped' => [], 'errors' => []];
    $now = time();
    
    try {
        $db = Database::getInstance();
        
        // Get stored cron state
        $cronState = [];
        $rows = $db->query("SELECT option_key, option_value FROM zed_options WHERE option_key LIKE '_cron_%'");
        foreach ($rows as $row) {
            $hook = str_replace('_cron_', '', $row['option_key']);
            $cronState[$hook] = json_decode($row['option_value'], true);
        }
        
        // Check each scheduled event
        foreach ($ZED_CRON_EVENTS as $hook => $event) {
            $schedule = $ZED_CRON_SCHEDULES[$event['recurrence']] ?? null;
            if (!$schedule) continue;
            
            $lastRun = $cronState[$hook]['last_run'] ?? 0;
            $interval = $schedule['interval'];
            
            // Check if it's time to run
            if ($now >= $lastRun + $interval) {
                try {
                    // Run the callback
                    call_user_func($event['callback']);
                    
                    // Also trigger as event for listeners
                    Event::trigger($hook);
                    
                    // Update last run time
                    $newState = json_encode([
                        'last_run' => $now,
                        'next_run' => $now + $interval,
                        'recurrence' => $event['recurrence'],
                    ]);
                    
                    $key = '_cron_' . $hook;
                    $existing = $db->queryOne("SELECT id FROM zed_options WHERE option_key = :key", ['key' => $key]);
                    
                    if ($existing) {
                        $db->query("UPDATE zed_options SET option_value = :val WHERE option_key = :key", ['val' => $newState, 'key' => $key]);
                    } else {
                        $db->query("INSERT INTO zed_options (option_key, option_value, autoload) VALUES (:key, :val, 0)", ['key' => $key, 'val' => $newState]);
                    }
                    
                    $results['run'][] = $hook;
                    
                } catch (\Throwable $e) {
                    $results['errors'][$hook] = $e->getMessage();
                }
            } else {
                $results['skipped'][] = $hook;
            }
        }
        
    } catch (\Exception $e) {
        $results['errors']['_system'] = $e->getMessage();
    }
    
    return $results;
}

// =============================================================================
// EXECUTE CRON
// =============================================================================

// Allow addons to register cron events
Event::trigger('zed_cron_init');

// Run scheduled events
$cronResults = zed_run_cron();

// Also run built-in cleanup tasks
if (function_exists('zed_cleanup_transients')) {
    zed_cleanup_transients();
}

if (function_exists('zed_process_mail_queue')) {
    zed_process_mail_queue(20); // Process up to 20 queued emails
}

// Output results (for debugging when run manually)
if (php_sapi_name() === 'cli' || !empty($_GET['debug'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'ok',
        'timestamp' => date('c'),
        'results' => $cronResults,
    ], JSON_PRETTY_PRINT);
}
