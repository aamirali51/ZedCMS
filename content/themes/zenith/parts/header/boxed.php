<?php
/**
 * Zenith Theme — Header Style 3: Boxed
 * 
 * Contained width with centered layout and subtle background
 * Inspired by Soledad boxed layouts
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

<header id="zenith-header" class="zenith-header-boxed py-4 <?= $sticky ? 'sticky top-0 z-50' : '' ?> bg-zenith-alt dark:bg-zenith-dark-alt">
    <div class="max-w-container mx-auto px-4 sm:px-6">
        <div class="bg-white dark:bg-zenith-dark rounded-lg shadow-zenith-sm px-6 py-4">
            <div class="flex items-center justify-between">
                
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <?php if ($logo): ?>
                    <a href="<?= $base_url ?>/">
                        <img src="<?= htmlspecialchars($logo) ?>" alt="<?= $site_name ?>" class="h-8 md:h-10">
                    </a>
                    <?php else: ?>
                    <a href="<?= $base_url ?>/" class="text-xl md:text-2xl font-heading font-bold text-zenith-heading dark:text-white">
                        <?= htmlspecialchars($site_name) ?>
                    </a>
                    <?php endif; ?>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-1">
                    <?php 
                    $menu_items = zed_menu('primary') ?: [];
                    foreach ($menu_items as $item): 
                    ?>
                    <a href="<?= htmlspecialchars($item['url']) ?>" 
                       class="px-3 py-2 text-sm font-heading font-medium text-zenith-heading dark:text-white hover:text-zenith-accent transition-colors">
                        <?= htmlspecialchars($item['title']) ?>
                    </a>
                    <?php endforeach; ?>
                </nav>
                
                <!-- Right Section -->
                <div class="flex items-center gap-2">
                    <?php if ($show_search): ?>
                    <button id="search-toggle" class="p-2 rounded-full bg-zenith-alt dark:bg-zenith-dark-alt text-zenith-text dark:text-white hover:text-zenith-accent transition-colors" aria-label="Search">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </button>
                    <?php endif; ?>
                    
                    <button id="dark-toggle" class="p-2 rounded-full bg-zenith-alt dark:bg-zenith-dark-alt text-zenith-text dark:text-white hover:text-zenith-accent transition-colors" aria-label="Toggle dark mode">
                        <span class="material-symbols-outlined text-lg dark:hidden">dark_mode</span>
                        <span class="material-symbols-outlined text-lg hidden dark:inline">light_mode</span>
                    </button>
                    
                    <!-- Subscribe Button (Optional) -->
                    <a href="<?= $base_url ?>/subscribe" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-zenith-accent hover:bg-zenith-accent-dark text-white text-sm font-medium rounded transition-colors">
                        <span class="material-symbols-outlined text-lg">mail</span>
                        Subscribe
                    </a>
                    
                    <!-- Mobile Menu Button -->
                    <button id="menu-toggle" class="lg:hidden p-2 text-zenith-text dark:text-white" aria-label="Menu">
                        <span class="material-symbols-outlined text-xl">menu</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden mt-2">
        <div class="max-w-container mx-auto px-4 sm:px-6">
            <div class="bg-white dark:bg-zenith-dark rounded-lg shadow-zenith-sm p-4">
                <ul class="space-y-2">
                    <?php foreach ($menu_items as $item): ?>
                    <li>
                        <a href="<?= htmlspecialchars($item['url']) ?>" 
                           class="block py-2 px-3 rounded text-zenith-heading dark:text-white hover:bg-zenith-alt dark:hover:bg-zenith-dark-alt font-heading font-medium transition-colors">
                            <?= htmlspecialchars($item['title']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</header>

<!-- Search Overlay -->
<?php include __DIR__ . '/../_search-overlay.php'; ?>
