<?php
/**
 * Aurora Pro - Archive Template
 * 
 * Displays category, tag, or date archives.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();

// Archive info (set by frontend controller)
$archive_title = $archive_title ?? 'Archive';
$archive_description = $archive_description ?? '';
$layout = function_exists('aurora_get_layout') ? aurora_get_layout() : 'blog';
?>
<?php include __DIR__ . '/parts/head.php'; ?>
<?php include __DIR__ . '/parts/header.php'; ?>

<!-- Archive Header -->
<section class="py-12 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto text-center">
            <span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-semibold uppercase tracking-wider rounded-full mb-4">
                Archive
            </span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                <?= htmlspecialchars($archive_title) ?>
            </h1>
            <?php if ($archive_description): ?>
            <p class="text-lg text-gray-600 dark:text-gray-400">
                <?= htmlspecialchars($archive_description) ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Archive Posts -->
<section class="py-12 lg:py-16">
    <div class="container mx-auto px-6">
        
        <?php if (!empty($posts)): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <?php foreach ($posts as $post): 
                $data = is_string($post['data'] ?? '') 
                    ? json_decode($post['data'], true) 
                    : ($post['data'] ?? []);
                $excerpt = $data['excerpt'] ?? '';
                $featuredImage = $data['featured_image'] ?? '';
            ?>
            <article class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Image -->
                <div class="aspect-[16/10] overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600">
                    <?php if ($featuredImage): ?>
                    <img src="<?= htmlspecialchars($featuredImage) ?>" 
                         alt="<?= htmlspecialchars($post['title']) ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="text-5xl font-extrabold text-gray-300 dark:text-gray-500">
                            <?= strtoupper(substr($post['title'], 0, 1)) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <div class="flex items-center gap-2 text-sm mb-3">
                        <span class="font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wide text-xs">
                            <?= ucfirst($post['type'] ?? 'post') ?>
                        </span>
                        <span class="text-gray-300 dark:text-gray-600">•</span>
                        <time class="text-gray-500 dark:text-gray-400">
                            <?= date('M j, Y', strtotime($post['created_at'])) ?>
                        </time>
                    </div>
                    
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                        <a href="<?= $base_url . '/' . htmlspecialchars($post['slug']) ?>">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h2>
                    
                    <?php if ($excerpt): ?>
                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">
                        <?= htmlspecialchars($excerpt) ?>
                    </p>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (($total_pages ?? 1) > 1): ?>
        <nav class="mt-12 flex justify-center gap-2" aria-label="Pagination">
            <?php for ($i = 1; $i <= min($total_pages, 5); $i++): ?>
            <a href="?page=<?= $i ?>" 
               class="w-10 h-10 flex items-center justify-center rounded-lg <?= ($page_num ?? 1) === $i ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' ?> transition-colors">
                <?= $i ?>
            </a>
            <?php endfor; ?>
        </nav>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="max-w-md mx-auto text-center py-16">
            <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No posts found</h3>
            <p class="text-gray-600 dark:text-gray-400">There are no posts in this archive yet.</p>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<?php include __DIR__ . '/parts/footer.php'; ?>
