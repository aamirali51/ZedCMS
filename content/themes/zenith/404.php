<?php
/**
 * Zenith Theme — 404 Template
 * 
 * Page not found error page
 * 
 * @package Zenith
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();

include ZENITH_PARTS . '/head.php';
include ZENITH_PARTS . '/header.php';
?>

<!-- 404 Content -->
<section class="py-20 md:py-32 bg-slate-50 dark:bg-slate-900 dark-transition">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">
        
        <!-- 404 Illustration -->
        <div class="mb-8">
            <span class="text-9xl font-serif font-bold text-accent/20">404</span>
        </div>
        
        <h1 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 dark:text-white mb-4">
            Page Not Found
        </h1>
        <p class="text-lg text-slate-600 dark:text-slate-400 mb-8">
            The page you're looking for doesn't exist or has been moved.
        </p>
        
        <!-- Search -->
        <form action="<?= $base_url ?>/search" method="get" class="mb-8">
            <div class="flex gap-3 max-w-md mx-auto">
                <input type="text" name="q" placeholder="Search articles..." 
                       class="flex-1 px-5 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-accent">
                <button type="submit" class="px-6 py-3 bg-accent hover:bg-accent/90 text-white font-medium rounded-xl transition-colors">
                    Search
                </button>
            </div>
        </form>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?= $base_url ?>/" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-300 font-medium hover:border-accent hover:text-accent transition-colors">
                <span class="material-symbols-outlined">home</span>
                Go Home
            </a>
            <a href="<?= $base_url ?>/archive" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-accent hover:bg-accent/90 text-white font-medium rounded-xl transition-colors">
                <span class="material-symbols-outlined">article</span>
                Browse Articles
            </a>
        </div>
    </div>
</section>

<?php include ZENITH_PARTS . '/footer.php'; ?>
