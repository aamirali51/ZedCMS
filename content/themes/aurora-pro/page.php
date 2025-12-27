<?php
/**
 * Aurora Pro - Page Template
 * 
 * Displays static pages.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();

// Get page data
$data = is_string($post['data'] ?? '') 
    ? json_decode($post['data'], true) 
    : ($post['data'] ?? []);

$page_title = $post['title'] ?? 'Untitled';
$featuredImage = $data['featured_image'] ?? '';
$content = $htmlContent ?? ($data['content'] ?? '');
$template = $data['template'] ?? 'default';
?>
<?php include __DIR__ . '/parts/head.php'; ?>
<?php include __DIR__ . '/parts/header.php'; ?>

<!-- Page Content -->
<article class="py-12 lg:py-16">
    <div class="container mx-auto px-6">
        
        <!-- Page Header -->
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
                <?= htmlspecialchars($page_title) ?>
            </h1>
            
            <?php if ($post['updated_at'] ?? false): ?>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Last updated: <?= date('F j, Y', strtotime($post['updated_at'])) ?>
            </p>
            <?php endif; ?>
        </header>
        
        <!-- Featured Image -->
        <?php if ($featuredImage): ?>
        <div class="max-w-4xl mx-auto mb-12">
            <div class="aspect-[16/7] rounded-2xl overflow-hidden shadow-xl">
                <img src="<?= htmlspecialchars($featuredImage) ?>" 
                     alt="<?= htmlspecialchars($page_title) ?>"
                     class="w-full h-full object-cover">
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Page Content -->
        <div class="max-w-3xl mx-auto">
            <?php Event::trigger('zed_before_content', $post, $data); ?>
            
            <div class="prose prose-lg dark:prose-invert max-w-none
                        prose-headings:font-bold prose-headings:text-gray-900 dark:prose-headings:text-white
                        prose-p:text-gray-700 dark:prose-p:text-gray-300
                        prose-a:text-indigo-600 dark:prose-a:text-indigo-400
                        prose-img:rounded-xl prose-img:shadow-lg">
                <?= $content ?>
            </div>
            
            <?php Event::trigger('zed_after_content', $post, $data); ?>
        </div>
        
    </div>
</article>

<?php include __DIR__ . '/parts/footer.php'; ?>
