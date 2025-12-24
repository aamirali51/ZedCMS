<?php
/**
 * Zenith Theme — Header Style 1: Classic Center
 * 
 * Logo centered above, navigation centered below
 * Inspired by Soledad header-1.php
 * 
 * @package Zenith
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();
$logo = zenith_option('logo', '');
$sticky = zenith_option('header_sticky', 'yes') === 'yes';
$show_search = zenith_option('header_search', 'yes') === 'yes';
?>

<header id="zenith-header" class="zenith-header-classic bg-white dark:bg-zenith-dark border-b border-zenith-border dark:border-zenith-border-dark <?= $sticky ? 'sticky top-0 z-50' : '' ?>">
    
    <!-- Top Bar (Optional Social Links) -->
    <div class="border-b border-zenith-border dark:border-zenith-border-dark">
        <div class="max-w-container mx-auto px-4 sm:px-6 py-3 flex items-center justify-between text-sm">
            <div class="flex items-center gap-4 text-zenith-meta">
                <span><?= date('l, F j, Y') ?></span>
            </div>
            <div class="flex items-center gap-3">
                <?php if ($twitter = zenith_option('social_twitter', '')): ?>
                <a href="<?= htmlspecialchars($twitter) ?>" class="text-zenith-meta hover:text-zenith-accent transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <?php endif; ?>
                <?php if ($facebook = zenith_option('social_facebook', '')): ?>
                <a href="<?= htmlspecialchars($facebook) ?>" class="text-zenith-meta hover:text-zenith-accent transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <?php endif; ?>
                <?php if ($instagram = zenith_option('social_instagram', '')): ?>
                <a href="<?= htmlspecialchars($instagram) ?>" class="text-zenith-meta hover:text-zenith-accent transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Logo Area (Centered) -->
    <div class="py-8 text-center">
        <div class="max-w-container mx-auto px-4 sm:px-6">
            <?php if ($logo): ?>
            <a href="<?= $base_url ?>/" class="inline-block">
                <img src="<?= htmlspecialchars($logo) ?>" alt="<?= $site_name ?>" class="h-12 md:h-16">
            </a>
            <?php else: ?>
            <a href="<?= $base_url ?>/" class="inline-block">
                <span class="text-3xl md:text-4xl font-heading font-bold text-zenith-heading dark:text-white tracking-tight">
                    <?= htmlspecialchars($site_name) ?>
                </span>
            </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Navigation (Centered Below Logo) -->
    <nav class="border-t border-zenith-border dark:border-zenith-border-dark">
        <div class="max-w-container mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-center h-14">
                
                <!-- Desktop Menu -->
                <ul class="hidden md:flex items-center gap-1">
                    <?php 
                    $menu_items = zed_menu('primary') ?: [];
                    foreach ($menu_items as $item): 
                    ?>
                    <li>
                        <a href="<?= htmlspecialchars($item['url']) ?>" 
                           class="px-4 py-2 text-sm font-heading font-semibold uppercase tracking-wide text-zenith-heading dark:text-white hover:text-zenith-accent transition-colors">
                            <?= htmlspecialchars($item['title']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                
                <!-- Search & Dark Mode -->
                <div class="flex items-center gap-3 ml-6">
                    <?php if ($show_search): ?>
                    <button id="search-toggle" class="p-2 text-zenith-text dark:text-white hover:text-zenith-accent transition-colors" aria-label="Search">
                        <span class="material-symbols-outlined text-xl">search</span>
                    </button>
                    <?php endif; ?>
                    
                    <button id="dark-toggle" class="p-2 text-zenith-text dark:text-white hover:text-zenith-accent transition-colors" aria-label="Toggle dark mode">
                        <span class="material-symbols-outlined text-xl dark:hidden">dark_mode</span>
                        <span class="material-symbols-outlined text-xl hidden dark:inline">light_mode</span>
                    </button>
                    
                    <!-- Mobile Menu Button -->
                    <button id="menu-toggle" class="md:hidden p-2 text-zenith-text dark:text-white" aria-label="Menu">
                        <span class="material-symbols-outlined text-xl">menu</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-zenith-border dark:border-zenith-border-dark bg-white dark:bg-zenith-dark">
        <nav class="max-w-container mx-auto px-4 py-4">
            <ul class="space-y-2">
                <?php foreach ($menu_items as $item): ?>
                <li>
                    <a href="<?= htmlspecialchars($item['url']) ?>" 
                       class="block py-2 text-zenith-heading dark:text-white font-heading font-medium">
                        <?= htmlspecialchars($item['title']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</header>

<!-- Search Overlay -->
<?php include __DIR__ . '/../_search-overlay.php'; ?>
