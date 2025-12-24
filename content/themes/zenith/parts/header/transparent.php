<?php
/**
 * Zenith Theme — Header Style 4: Transparent
 * 
 * Overlay header for hero sections with transparent background
 * Inspired by Soledad transparent header
 * 
 * @package Zenith
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();
$logo = zenith_option('logo', '');
$logo_light = zenith_option('logo_light', $logo); // White version for dark backgrounds
$sticky = zenith_option('header_sticky', 'yes') === 'yes';
$show_search = zenith_option('header_search', 'yes') === 'yes';
?>

<header id="zenith-header" class="zenith-header-transparent absolute top-0 left-0 right-0 z-50 transition-all duration-300" data-sticky="<?= $sticky ? 'true' : 'false' ?>">
    <div class="max-w-container-wide mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-20 md:h-24">
            
            <!-- Logo -->
            <div class="flex-shrink-0">
                <?php if ($logo_light): ?>
                <a href="<?= $base_url ?>/">
                    <img src="<?= htmlspecialchars($logo_light) ?>" alt="<?= $site_name ?>" class="h-8 md:h-10 header-logo-transparent">
                    <?php if ($logo !== $logo_light): ?>
                    <img src="<?= htmlspecialchars($logo) ?>" alt="<?= $site_name ?>" class="h-8 md:h-10 header-logo-solid hidden">
                    <?php endif; ?>
                </a>
                <?php else: ?>
                <a href="<?= $base_url ?>/" class="text-2xl font-heading font-bold text-white header-text-transparent">
                    <?= htmlspecialchars($site_name) ?>
                </a>
                <?php endif; ?>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-1">
                <?php 
                $menu_items = zed_menu('primary') ?: [];
                foreach ($menu_items as $item): 
                ?>
                <a href="<?= htmlspecialchars($item['url']) ?>" 
                   class="px-4 py-2 text-sm font-heading font-semibold uppercase tracking-wide text-white/90 hover:text-white transition-colors header-nav-transparent">
                    <?= htmlspecialchars($item['title']) ?>
                </a>
                <?php endforeach; ?>
            </nav>
            
            <!-- Right Icons -->
            <div class="flex items-center gap-2">
                <?php if ($show_search): ?>
                <button id="search-toggle" class="p-2 text-white/90 hover:text-white transition-colors header-icon-transparent" aria-label="Search">
                    <span class="material-symbols-outlined text-xl">search</span>
                </button>
                <?php endif; ?>
                
                <button id="dark-toggle" class="p-2 text-white/90 hover:text-white transition-colors header-icon-transparent" aria-label="Toggle dark mode">
                    <span class="material-symbols-outlined text-xl dark:hidden">dark_mode</span>
                    <span class="material-symbols-outlined text-xl hidden dark:inline">light_mode</span>
                </button>
                
                <!-- Mobile Menu Button -->
                <button id="menu-toggle" class="md:hidden p-2 text-white header-icon-transparent" aria-label="Menu">
                    <span class="material-symbols-outlined text-xl">menu</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-zenith-dark shadow-zenith-lg">
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

<!-- Sticky header JS state management -->
<script>
(function() {
    const header = document.getElementById('zenith-header');
    if (!header || header.dataset.sticky !== 'true') return;
    
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;
        
        if (currentScroll > 100) {
            header.classList.add('is-scrolled', 'bg-white', 'dark:bg-zenith-dark', 'shadow-zenith-md', 'fixed');
            header.classList.remove('absolute');
            
            // Switch to dark text/icons
            header.querySelectorAll('.header-nav-transparent').forEach(el => {
                el.classList.remove('text-white/90', 'hover:text-white');
                el.classList.add('text-zenith-heading', 'dark:text-white', 'hover:text-zenith-accent');
            });
            header.querySelectorAll('.header-icon-transparent').forEach(el => {
                el.classList.remove('text-white/90', 'hover:text-white', 'text-white');
                el.classList.add('text-zenith-text', 'dark:text-white', 'hover:text-zenith-accent');
            });
            header.querySelectorAll('.header-text-transparent').forEach(el => {
                el.classList.remove('text-white');
                el.classList.add('text-zenith-heading', 'dark:text-white');
            });
        } else {
            header.classList.remove('is-scrolled', 'bg-white', 'dark:bg-zenith-dark', 'shadow-zenith-md', 'fixed');
            header.classList.add('absolute');
            
            // Switch back to light text/icons
            header.querySelectorAll('.header-nav-transparent').forEach(el => {
                el.classList.add('text-white/90', 'hover:text-white');
                el.classList.remove('text-zenith-heading', 'dark:text-white', 'hover:text-zenith-accent');
            });
            header.querySelectorAll('.header-icon-transparent').forEach(el => {
                el.classList.add('text-white/90', 'hover:text-white', 'text-white');
                el.classList.remove('text-zenith-text', 'dark:text-white', 'hover:text-zenith-accent');
            });
            header.querySelectorAll('.header-text-transparent').forEach(el => {
                el.classList.add('text-white');
                el.classList.remove('text-zenith-heading', 'dark:text-white');
            });
        }
        
        lastScroll = currentScroll;
    }, { passive: true });
})();
</script>

<!-- Search Overlay -->
<?php include __DIR__ . '/../_search-overlay.php'; ?>
