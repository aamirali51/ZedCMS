<?php
/**
 * Aurora Pro - Homepage Template
 * 
 * Routes to the active layout's homepage template.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();

// Get active layout from theme options
$layout = function_exists('aurora_get_layout') ? aurora_get_layout() : 'blog';

// Get posts for homepage
$posts = function_exists('zed_get_latest_posts') ? zed_get_latest_posts(12) : [];

// Include head
include __DIR__ . '/parts/head.php';

// Include header
include __DIR__ . '/parts/header.php';

// Load layout-specific homepage
$layoutHome = __DIR__ . "/layouts/{$layout}/home.php";

if (file_exists($layoutHome)) {
    include $layoutHome;
} else {
    // Fallback to blog layout
    include __DIR__ . '/layouts/blog/home.php';
}

// Include footer
include __DIR__ . '/parts/footer.php';
