<?php
/**
 * Header Builder Integration for Aurora Pro
 * 
 * This file replaces the default header with the header builder output
 * if it has been configured.
 *
 * @package ZedCMS\Themes\AuroraPro
 */

// Check if header builder addon is active and has configuration
if (function_exists('zed_has_header_builder') && zed_has_header_builder()) {
    // Render header from builder
    echo zed_render_header_builder();
} else {
    // Fall back to default header
    require __DIR__ . '/header-default.php';
}
