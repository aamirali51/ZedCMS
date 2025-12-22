<?php
/**
 * Aurora Theme — Shared Head Partial
 * 
 * This partial outputs all common <head> elements:
 * - Tailwind CSS CDN with theme config
 * - Google Fonts
 * - CSS Variables
 * - Custom styles
 * 
 * Include this in all templates for consistent styling.
 */

use Core\Event;
use Core\Router;

// Get theme settings
$base_url = Router::getBasePath();
$site_name = zed_get_site_name();
$brand_color = zed_theme_option('brand_color', '#6366f1');
$brand_color_dark = zed_theme_option('brand_color_dark', '#4f46e5');

// Page title (passed from template or use default)
$page_title = $page_title ?? $post['title'] ?? $site_name;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> — <?= htmlspecialchars($site_name) ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '<?= $brand_color ?>',
                        'brand-dark': '<?= $brand_color_dark ?>',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400" rel="stylesheet">

    <?= aurora_css_variables() ?>
    <?= zed_render_theme_styles() ?>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .prose { font-family: Georgia, serif; line-height: 1.8; }
        .prose h1, .prose h2, .prose h3 { font-family: 'Inter', sans-serif; font-weight: 700; }
        .prose p { margin-bottom: 1.5rem; }
        .prose a { color: var(--aurora-brand); }
    </style>
    
    <?php 
    // Allow scoped head hooks for specific post types
    $post_type = $post['type'] ?? 'page';
    Event::triggerScoped('zed_head', ['post_type' => $post_type]); 
    ?>
</head>
