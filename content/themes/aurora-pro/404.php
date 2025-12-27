<?php
/**
 * Aurora Pro - 404 Not Found Template
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();
$page_title = '404 - Page Not Found';
?>
<?php include __DIR__ . '/parts/head.php'; ?>
<?php include __DIR__ . '/parts/header.php'; ?>

<section class="py-20 lg:py-32">
    <div class="container mx-auto px-6">
        <div class="max-w-xl mx-auto text-center">
            
            <!-- Illustration -->
            <div class="relative mb-8">
                <div class="text-[180px] font-extrabold text-gray-100 dark:text-gray-800 leading-none select-none">
                    404
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-xl">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                Page Not Found
            </h1>
            
            <p class="text-gray-600 dark:text-gray-400 mb-8 text-lg">
                Oops! The page you're looking for doesn't exist or has been moved.
            </p>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="<?= $base_url ?>/" class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl">
                    Go to Homepage
                </a>
                <button onclick="history.back()" class="px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    Go Back
                </button>
            </div>
            
            <!-- Search -->
            <div class="mt-12">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Or try searching:</p>
                <form action="<?= $base_url ?>/search" method="get" class="flex gap-2 max-w-md mx-auto">
                    <input type="search" name="q" placeholder="Search..." 
                           class="flex-1 px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                    <button type="submit" class="px-5 py-3 bg-gray-900 dark:bg-gray-700 text-white rounded-xl hover:bg-gray-800 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</section>

<?php include __DIR__ . '/parts/footer.php'; ?>
