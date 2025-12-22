<?php
/**
 * Template Library — Admin Showcase Page
 * 
 * Beautiful gallery of available templates with insert functionality.
 * Uses the admin layout for consistent sidebar.
 */

use Core\Router;
use Core\Auth;

if (!Auth::check()) {
    Router::redirect('/admin/login');
}

$base_url = Router::getBasePath();
$templates = zed_get_template_library();

// Group by category
$categories = [];
foreach ($templates as $slug => $template) {
    $cat = $template['category'] ?? 'Other';
    if (!isset($categories[$cat])) {
        $categories[$cat] = [];
    }
    $categories[$cat][$slug] = $template;
}

// Icon colors for visual variety
$iconColors = [
    'mail' => 'text-blue-500 bg-blue-100',
    'rocket_launch' => 'text-purple-500 bg-purple-100',
    'groups' => 'text-green-500 bg-green-100',
    'grid_view' => 'text-orange-500 bg-orange-100',
    'help' => 'text-cyan-500 bg-cyan-100',
    'payments' => 'text-pink-500 bg-pink-100',
];

// Variables for admin layout
$current_user = Auth::user();
$current_page = 'template_library';
$page_title = 'Template Library';

// Correct path to admin theme
$adminThemePath = dirname(__DIR__, 3) . '/themes/admin-default';

// Point to the content partial
$content_partial = __DIR__ . '/showcase-content.php';

// Include admin layout which will include the content partial
require $adminThemePath . '/admin-layout.php';
