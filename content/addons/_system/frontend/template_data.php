<?php
/**
 * Template Data System
 * 
 * Provides data injection for templates.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

use Core\Event;

// =============================================================================
// DATA-DRIVEN TEMPLATES
// =============================================================================

/**
 * Global template data that gets injected into all templates
 * @var array<string, mixed>
 */
global $ZED_TEMPLATE_DATA;
$ZED_TEMPLATE_DATA = [];

/**
 * Add data to be available in templates
 * 
 * Usage:
 *   zed_add_template_data('author_name', 'John Doe');
 *   zed_add_template_data(['site_stats' => [...], 'user_prefs' => [...]]);
 *
 * @param string|array<string, mixed> $keyOrData Key name or associative array
 * @param mixed $value Value (if $keyOrData is string)
 * @return void
 */
function zed_add_template_data(string|array $keyOrData, mixed $value = null): void
{
    global $ZED_TEMPLATE_DATA;
    
    if (is_array($keyOrData)) {
        $ZED_TEMPLATE_DATA = array_merge($ZED_TEMPLATE_DATA, $keyOrData);
    } else {
        $ZED_TEMPLATE_DATA[$keyOrData] = $value;
    }
}

/**
 * Get all template data (filtered)
 * Applies the zed_template_data filter for dynamic injection
 *
 * @param array<string, mixed> $contextData Additional context-specific data
 * @return array<string, mixed> Merged template data
 */
function zed_get_template_data(array $contextData = []): array
{
    global $ZED_TEMPLATE_DATA;
    
    // Merge global and context data
    $data = array_merge($ZED_TEMPLATE_DATA, $contextData);
    
    // Apply filter for dynamic injection
    return Event::filter('zed_template_data', $data);
}

/**
 * Extract template data into local scope (call at top of template)
 * 
 * Usage in template:
 *   <?php zed_extract_template_data(); ?>
 *   <h1><?= $page_title ?></h1>
 *
 * @param array<string, mixed> $contextData Additional data
 * @return void
 */
function zed_extract_template_data(array $contextData = []): void
{
    $data = zed_get_template_data($contextData);
    extract($data, EXTR_SKIP);
}
