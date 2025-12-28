<?php
/**
 * Aurora Pro - Single Post Template
 * 
 * Displays a single post or page.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$site_name = function_exists('zed_get_site_name') ? zed_get_site_name() : 'ZedCMS';

// Get post data (should be set by frontend controller)
$data = is_string($post['data'] ?? '') 
    ? json_decode($post['data'], true) 
    : ($post['data'] ?? []);

$page_title = $post['title'] ?? 'Untitled';
$featuredImage = $data['featured_image'] ?? '';
$excerpt = $data['excerpt'] ?? '';
$content = $htmlContent ?? ($data['content'] ?? '');
$categories = $data['categories'] ?? [];
$readingTime = function_exists('aurora_reading_time') ? aurora_reading_time($content) : 3;

// Theme settings
$showReadingTime = aurora_bool_option('show_reading_time', true);
$showPostNav = aurora_bool_option('show_post_navigation', true);
?>
<?php include __DIR__ . '/parts/head.php'; ?>
<?php include __DIR__ . '/parts/header.php'; ?>

<!-- Single Post -->
<article class="py-12 lg:py-16">
    <div class="container mx-auto px-6">
        
        <!-- Post Header -->
        <header class="max-w-3xl mx-auto text-center mb-10">
            <!-- Categories -->
            <?php if (!empty($categories)): ?>
            <div class="flex items-center justify-center gap-2 mb-4">
                <?php foreach (array_slice($categories, 0, 3) as $cat): ?>
                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-semibold uppercase tracking-wide rounded-full">
                    <?= htmlspecialchars(is_array($cat) ? ($cat['name'] ?? '') : $cat) ?>
                </span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <!-- Title -->
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                <?= htmlspecialchars($page_title) ?>
            </h1>
            
            <!-- Excerpt -->
            <?php if ($excerpt): ?>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-6">
                <?= htmlspecialchars($excerpt) ?>
            </p>
            <?php endif; ?>
            
            <!-- Meta -->
            <div class="flex items-center justify-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                <time datetime="<?= $post['created_at'] ?>">
                    <?= date('F j, Y', strtotime($post['created_at'])) ?>
                </time>
                
                <?php if ($showReadingTime): ?>
                <span>•</span>
                <span><?= $readingTime ?> min read</span>
                <?php endif; ?>
                
                <?php if (!empty($post['author_id'])): ?>
                <span>•</span>
                <span>By Author</span>
                <?php endif; ?>
            </div>
        </header>
        
        <!-- Featured Image -->
        <?php if ($featuredImage): ?>
        <div class="max-w-4xl mx-auto mb-12">
            <div class="aspect-[16/9] rounded-2xl overflow-hidden shadow-xl">
                <img src="<?= htmlspecialchars($featuredImage) ?>" 
                     alt="<?= htmlspecialchars($page_title) ?>"
                     class="w-full h-full object-cover">
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Post Content -->
        <div class="max-w-3xl mx-auto">
            <!-- Before Content Hook -->
            <?php Event::trigger('zed_before_content', $post, $data); ?>
            
            <!-- Content -->
            <div class="prose prose-lg dark:prose-invert max-w-none
                        prose-headings:font-bold prose-headings:text-gray-900 dark:prose-headings:text-white
                        prose-p:text-gray-700 dark:prose-p:text-gray-300
                        prose-a:text-indigo-600 dark:prose-a:text-indigo-400 prose-a:no-underline hover:prose-a:underline
                        prose-img:rounded-xl prose-img:shadow-lg
                        prose-blockquote:border-indigo-500 prose-blockquote:bg-gray-50 dark:prose-blockquote:bg-gray-800
                        prose-code:text-indigo-600 dark:prose-code:text-indigo-400
                        prose-pre:bg-gray-900 dark:prose-pre:bg-gray-950">
                <?= $content ?>
            </div>
            
            <!-- After Content Hook (author bio, share buttons, related posts) -->
            <?php Event::trigger('zed_after_content', $post, $data); ?>
        </div>
        
        <!-- Post Navigation -->
        <?php if ($showPostNav): ?>
        <nav class="max-w-3xl mx-auto mt-16 pt-8 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <a href="<?= $base_url ?>/blog" class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span class="font-medium">Back to Blog</span>
                </a>
            </div>
        </nav>
        <?php endif; ?>
        
    </div>
</article>

<?php include __DIR__ . '/parts/footer.php'; ?>
