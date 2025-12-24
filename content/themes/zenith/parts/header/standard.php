<?php
/**
 * Zenith Theme — Header Style 2: Standard
 * 
 * Logo left, navigation right (default style)
 * Inspired by Soledad header-4.php
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

<header id="zenith-header" class="zenith-header-standard bg-white dark:bg-zenith-dark border-b border-zenith-border dark:border-zenith-border-dark <?= $sticky ? 'sticky top-0 z-50' : '' ?>">
    <div class="max-w-container-wide mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16 md:h-20">
            
            <!-- Logo (Left) -->
            <div class="flex-shrink-0">
                <?php if ($logo): ?>
                <a href="<?= $base_url ?>/">
                    <img src="<?= htmlspecialchars($logo) ?>" alt="<?= $site_name ?>" class="h-8 md:h-10">
                </a>
                <?php else: ?>
                <a href="<?= $base_url ?>/" class="text-2xl font-heading font-bold text-zenith-heading dark:text-white">
                    <?= htmlspecialchars($site_name) ?>
                </a>
                <?php endif; ?>
            </div>
            
            <!-- Desktop Navigation (Center/Right) -->
            <nav class="hidden md:flex items-center gap-1">
                <?php 
                $menu_items = zed_menu('primary') ?: [];
                foreach ($menu_items as $item): 
                ?>
                <a href="<?= htmlspecialchars($item['url']) ?>" 
                   class="px-4 py-2 text-sm font-heading font-semibold uppercase tracking-wide text-zenith-heading dark:text-white hover:text-zenith-accent transition-colors">
                    <?= htmlspecialchars($item['title']) ?>
                </a>
                <?php endforeach; ?>
            </nav>
            
            <!-- Right Icons -->
            <div class="flex items-center gap-2">
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
