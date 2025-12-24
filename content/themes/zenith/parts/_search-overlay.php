<?php
/**
 * Zenith Theme — Search Overlay
 * 
 * Full-screen search overlay modal
 * 
 * @package Zenith
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();
?>

<!-- Search Overlay -->
<div id="search-overlay" class="hidden fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm flex items-center justify-center">
    <button id="search-close" class="absolute top-6 right-6 p-3 text-white/70 hover:text-white transition-colors" aria-label="Close search">
        <span class="material-symbols-outlined text-3xl">close</span>
    </button>
    
    <div class="w-full max-w-2xl px-6">
        <form action="<?= $base_url ?>/search" method="get" class="relative">
            <input type="text" name="q" placeholder="Type to search..." autocomplete="off" autofocus
                   class="w-full py-5 px-0 bg-transparent border-0 border-b-2 border-white/30 focus:border-zenith-accent text-white text-2xl md:text-4xl font-heading placeholder-white/40 focus:outline-none transition-colors">
            <button type="submit" class="absolute right-0 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-3xl">search</span>
            </button>
        </form>
        
        <p class="mt-4 text-white/50 text-sm text-center">
            Press <kbd class="px-2 py-1 bg-white/10 rounded text-white/70">ESC</kbd> to close
        </p>
    </div>
</div>
