<?php
/**
 * Deprecation Admin Notice Integration
 * 
 * Displays deprecation warnings in the admin panel when debug mode is enabled.
 * 
 * @package ZedCMS\System\Admin
 * @since 3.1.0
 */

declare(strict_types=1);

use Core\Event;
use Core\Deprecation;

/**
 * Display deprecation warnings in admin notices
 */
Event::on('deprecation_warning', function(array $data): void {
    // Only show in admin
    if (!function_exists('zed_add_notice')) {
        return;
    }
    
    $message = sprintf(
        '<strong>Deprecation Warning:</strong> %s<br><small>Called from: %s</small>',
        $data['message'],
        $data['caller']
    );
    
    zed_add_notice($message, 'warning');
});

/**
 * Show deprecation summary in admin footer (debug mode only)
 */
Event::on('admin_footer', function(): void {
    $count = Deprecation::getWarningCount();
    
    if ($count === 0) {
        return;
    }
    
    // Only show if debug mode
    if (!defined('ZED_DEBUG') || ZED_DEBUG !== true) {
        return;
    }
    
    echo sprintf(
        '<div style="padding: 10px; background: #fff3cd; border-left: 4px solid #ffc107; margin: 20px 0;">
            <strong>⚠️ Deprecation Warnings:</strong> %d deprecated API%s used on this page.
            <a href="#" onclick="console.log(%s); return false;" style="margin-left: 10px;">View Details</a>
        </div>',
        $count,
        $count === 1 ? '' : 's',
        json_encode(Deprecation::getWarnings())
    );
});
