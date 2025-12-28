<?php
/**
 * Aurora Pro - Header Part
 * 
 * Outputs <body> opening tag and site header/navigation.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();
$site_name = function_exists('zed_get_site_name') ? zed_get_site_name() : 'ZedCMS';
$sticky = aurora_bool_option('sticky_header', true);
$showSearch = aurora_bool_option('show_search', true);
$darkMode = aurora_bool_option('dark_mode', true);
$layout = aurora_get_layout();
?>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans antialiased min-h-screen flex flex-col">

    <!-- Skip to content for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary text-white px-4 py-2 rounded-lg z-50">
        Skip to content
    </a>

    <!-- Site Header -->
    <header class="site-header bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 <?= $sticky ? 'sticky top-0 z-50' : '' ?>" id="site-header">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between h-16 lg:h-20">
                
                <!-- Logo -->
                <a href="<?= $base_url ?>/" class="flex items-center gap-3 font-bold text-xl text-gray-900 dark:text-white hover:opacity-80 transition-opacity">
                    <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-extrabold rounded-xl shadow-lg">
                        Z
                    </span>
                    <span class="hidden sm:inline"><?= htmlspecialchars($site_name) ?></span>
                </a>
                
                <!-- Main Navigation -->
                <nav class="hidden lg:flex items-center gap-1">
                    <?php
                    if (function_exists('zed_menu')) {
                        echo zed_menu('Main Menu', ['class' => 'flex items-center gap-1']);
                    } else {
                        // Fallback navigation
                        ?>
                        <a href="<?= $base_url ?>/" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">Home</a>
                        <a href="<?= $base_url ?>/blog" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">Blog</a>
                        <a href="<?= $base_url ?>/about" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">About</a>
                        <a href="<?= $base_url ?>/contact" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">Contact</a>
                        <?php
                    }
                    ?>
                </nav>
                
                <!-- Header Actions -->
                <div class="flex items-center gap-3">
                    
                    <?php if ($showSearch): ?>
                    <!-- Search Button -->
                    <button type="button" class="w-10 h-10 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors" aria-label="Search" id="search-toggle">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    <?php endif; ?>
                    
                    <?php if ($darkMode): ?>
                    <!-- Dark Mode Toggle -->
                    <button type="button" class="dark-toggle w-10 h-10 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors" aria-label="Toggle dark mode">
                        <!-- Sun icon (shown in dark mode) -->
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <!-- Moon icon (shown in light mode) -->
                        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                    <?php endif; ?>
                    
                    <!-- CTA Button -->
                    <a href="<?= $base_url ?>/admin" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-semibold rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Admin
                    </a>
                    
                    <!-- Mobile Menu Button -->
                    <button type="button" class="lg:hidden w-10 h-10 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors" id="mobile-menu-toggle" aria-label="Toggle menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation (hidden by default) -->
        <nav class="lg:hidden hidden border-t border-gray-100 dark:border-gray-800 py-4 px-6" id="mobile-menu">
            <div class="flex flex-col gap-2">
                <a href="<?= $base_url ?>/" class="px-4 py-3 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">Home</a>
                <a href="<?= $base_url ?>/blog" class="px-4 py-3 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">Blog</a>
                <a href="<?= $base_url ?>/about" class="px-4 py-3 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">About</a>
                <a href="<?= $base_url ?>/contact" class="px-4 py-3 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">Contact</a>
                <a href="<?= $base_url ?>/admin" class="mt-2 px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-base font-semibold rounded-lg text-center">Admin Panel</a>
            </div>
        </nav>
    </header>
    
    <!-- Search Modal (hidden by default) -->
    <div class="fixed inset-0 z-50 hidden" id="search-modal">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="search-backdrop"></div>
        <div class="relative max-w-2xl mx-auto mt-20 px-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
                <form action="<?= $base_url ?>/search" method="get" class="flex items-center">
                    <input 
                        type="search" 
                        name="q" 
                        placeholder="Search posts, pages..." 
                        class="flex-1 px-6 py-4 text-lg bg-transparent border-0 focus:ring-0 focus:outline-none text-gray-900 dark:text-white placeholder-gray-400"
                        autofocus
                    >
                    <button type="submit" class="px-6 py-4 text-indigo-600 dark:text-indigo-400 hover:text-indigo-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Main Content Wrapper -->
    <main id="main-content" class="flex-1">
