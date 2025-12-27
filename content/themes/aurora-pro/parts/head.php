<?php
/**
 * Aurora Pro - Head Part
 * 
 * Outputs DOCTYPE, <html>, and <head> section with all assets.
 * 
 * Available variables:
 * - $page_title: Page title
 * - $post: Current post data (if applicable)
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;

$base_url = Router::getBasePath();
$site_name = function_exists('zed_get_site_name') ? zed_get_site_name() : 'ZedCMS';
$page_title = $page_title ?? $post['title'] ?? $site_name;
$description = $post['data']['excerpt'] ?? $post['data']['meta_description'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> — <?= htmlspecialchars($site_name) ?></title>
    <?php if ($description): ?>
    <meta name="description" content="<?= htmlspecialchars($description) ?>">
    <?php endif; ?>
    
    <!-- Tailwind CDN for rapid development -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ['class', '[data-theme="dark"]'],
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        heading: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: 'var(--color-primary)',
                            dark: 'var(--color-primary-dark)',
                            light: 'var(--color-primary-light)',
                        },
                        secondary: {
                            DEFAULT: 'var(--color-secondary)',
                        },
                    },
                },
            },
        }
    </script>
    
    <?php 
    // Trigger zed_head event for theme assets and SEO
    Event::trigger('zed_head', $post ?? []); 
    ?>
</head>
