<?php
/**
 * Aurora Theme — Shared Header Partial
 * 
 * Standard navigation header used across templates.
 * Provides consistent branding and menu.
 */

use Core\Router;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();

// Header style options: 'default', 'transparent', 'minimal'
$header_style = $header_style ?? 'default';
$max_width = $max_width ?? 'max-w-7xl';
?>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    
    <!-- Navigation -->
    <header class="<?= $header_style === 'transparent' ? 'absolute top-0 left-0 w-full z-50 pt-6' : 'bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50' ?>">
        <div class="<?= $max_width ?> mx-auto px-6 <?= $header_style === 'transparent' ? '' : 'py-4' ?>">
            <nav class="flex items-center justify-between">
                <a href="<?= $base_url ?>/" class="text-2xl font-bold text-brand flex items-center gap-2">
                    <span class="material-symbols-outlined text-3xl">auto_awesome</span>
                    <?= htmlspecialchars($site_name) ?>
                </a>
                
                <div class="flex items-center gap-8">
                    <?= zed_menu('Main Menu', ['class' => 'flex items-center gap-6 text-sm font-medium text-slate-600']) ?>
                    <a href="<?= $base_url ?>/admin" class="px-4 py-2 bg-brand text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">
                        Admin
                    </a>
                </div>
            </nav>
        </div>
    </header>
