<?php
/**
 * Zenith Theme — Head Part
 * 
 * Renders <head> section with meta tags, fonts, and styles
 * 
 * @package Zenith
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();
$accent = zenith_accent();
$dark_default = zenith_option('dark_mode_default', 'no') === 'yes';
?>
<!DOCTYPE html>
<html lang="en" class="<?= $dark_default ? 'dark' : '' ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= zed_page_title() ?></title>
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Fonts: PT Serif + Raleway (Soledad Typography) -->
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400&family=Raleway:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <?= zenith_tailwind_config() ?>
    
    <!-- Zenith Theme Styles -->
    <link rel="stylesheet" href="<?= $base_url ?>/content/themes/zenith/style.css">
    
    <!-- Dark Mode Detection (Prevents Flash) -->
    <script>
        (function() {
            const saved = localStorage.getItem('zenith-dark-mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'true' || (saved === null && prefersDark) || '<?= $dark_default ? 'true' : 'false' ?>' === 'true') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    
    <style>
        /* Critical CSS — Soledad Typography */
        body { 
            font-family: 'PT Serif', Georgia, serif; 
            color: #313131;
        }
        h1, h2, h3, h4, h5, h6, .font-heading { 
            font-family: 'Raleway', 'Inter', sans-serif;
            font-weight: 700; 
        }
        
        /* Accent Color CSS Variable */
        :root {
            --zenith-accent: <?= $accent ?>;
        }
    </style>
    
    <?php Event::trigger('zed_head'); ?>
</head>
