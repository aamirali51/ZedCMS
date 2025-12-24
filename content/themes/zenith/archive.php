<?php
/**
 * Zenith Theme — Archive Template
 * 
 * Category and tag listing pages
 * 
 * @package Zenith
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();

// Get archive info from query
global $zed_query;
$archive_type = $zed_query['archive_type'] ?? 'category';
$archive_term = $zed_query['archive_term'] ?? '';
$page = max(1, (int) ($_GET['page'] ?? 1));
$per_page = (int) zenith_option('posts_per_page', 10);

// Get posts
$posts = zed_get_posts([
    'type' => 'post',
    'status' => 'published',
    'limit' => $per_page,
    'offset' => ($page - 1) * $per_page,
    'category' => $archive_type === 'category' ? $archive_term : null,
    'orderby' => 'created_at',
    'order' => 'DESC'
]);

// Build title
$title = $archive_term ? ucfirst($archive_term) : 'All Articles';

// Include head & header
include ZENITH_PARTS . '/head.php';
include ZENITH_PARTS . '/header.php';
?>

<!-- Archive Header -->
<section class="py-16 md:py-20 bg-gradient-to-br from-slate-900 to-slate-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <p class="text-accent text-sm font-medium uppercase tracking-wide mb-2">
            <?= ucfirst($archive_type) ?>
        </p>
        <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4">
            <?= htmlspecialchars($title) ?>
        </h1>
        <p class="text-slate-400">
            <?= count($posts) ?> articles found
        </p>
    </div>
</section>

<!-- Posts Grid -->
<section class="py-12 md:py-16 bg-slate-50 dark:bg-slate-900 dark-transition">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Posts -->
            <div class="lg:col-span-2">
                <?php if (!empty($posts)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($posts as $post): ?>
                        <?php include ZENITH_PARTS . '/post-card.php'; ?>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <div class="mt-10 flex items-center justify-center gap-2">
                    <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" 
                       class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-300 hover:border-accent hover:text-accent transition-colors">
                        Previous
                    </a>
                    <?php endif; ?>
                    
                    <span class="px-4 py-2 text-slate-500 dark:text-slate-400">
                        Page <?= $page ?>
                    </span>
                    
                    <?php if (count($posts) >= $per_page): ?>
                    <a href="?page=<?= $page + 1 ?>" 
                       class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-300 hover:border-accent hover:text-accent transition-colors">
                        Next
                    </a>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-slate-400 mb-4">search_off</span>
                    <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">No Posts Found</h3>
                    <p class="text-slate-500 dark:text-slate-400">Try browsing other categories.</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <?php include ZENITH_PARTS . '/sidebar.php'; ?>
            </div>
        </div>
    </div>
</section>

<?php include ZENITH_PARTS . '/footer.php'; ?>
