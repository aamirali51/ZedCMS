<?php
/**
 * Zenith Theme — Single Post Template
 * 
 * Beautiful article layout with reading progress, author box, and related posts
 * 
 * @package Zenith
 * @version 1.0.0
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();

// Get post from global context (set by frontend_addon)
global $post, $htmlContent;

if (empty($post)) {
    // Fallback: 404
    include ZENITH_PARTS . '/head.php';
    include ZENITH_PARTS . '/header.php';
    echo '<div class="py-20 text-center"><h1 class="text-3xl font-bold">Post Not Found</h1></div>';
    include ZENITH_PARTS . '/footer.php';
    exit;
}

// Extract post data
$data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
$title = htmlspecialchars($post['title'] ?? 'Untitled');
$slug = $post['slug'] ?? '';
$excerpt = htmlspecialchars($data['excerpt'] ?? '');
$image = $data['featured_image'] ?? '';
$category = $data['category'] ?? '';
$date = date('F j, Y', strtotime($post['created_at'] ?? 'now'));
$reading_time = zed_reading_time($data['content'] ?? []);
$author = zed_get_author($post['author_id'] ?? 0);

// Full URL for sharing
$full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') 
          . "://{$_SERVER['HTTP_HOST']}{$base_url}/{$slug}";

// Get related posts
$show_related = zenith_option('show_related_posts', 'yes') === 'yes';
$related_posts = $show_related ? zed_get_related_posts($post, 3) : [];

// Include head
include ZENITH_PARTS . '/head.php';

// Reading progress bar
include ZENITH_PARTS . '/reading-progress.php';

// Include header
include ZENITH_PARTS . '/header.php';
?>

<!-- Article -->
<article id="article-content" class="dark-transition">
    
    <!-- Hero Section -->
    <header class="relative">
        <?php if ($image): ?>
        <!-- Featured Image (Full Width) -->
        <div class="relative h-[400px] md:h-[500px] lg:h-[600px]">
            <img src="<?= htmlspecialchars($image) ?>" alt="<?= $title ?>" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
        </div>
        
        <!-- Title Overlay on Image -->
        <div class="absolute bottom-0 inset-x-0 pb-8 md:pb-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center text-white">
                <?php if ($category): ?>
                <span class="zenith-category mb-4 inline-block"><?= htmlspecialchars($category) ?></span>
                <?php endif; ?>
                
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-serif font-bold leading-tight mb-6">
                    <?= $title ?>
                </h1>
                
                <div class="flex items-center justify-center gap-4 text-sm text-white/80">
                    <?php if ($author): ?>
                    <div class="flex items-center gap-2">
                        <?php if ($author['avatar']): ?>
                        <img src="<?= htmlspecialchars($author['avatar']) ?>" 
                             class="w-8 h-8 rounded-full ring-2 ring-white/30">
                        <?php endif; ?>
                        <span><?= htmlspecialchars($author['display_name'] ?? 'Anonymous') ?></span>
                    </div>
                    <span class="w-1 h-1 rounded-full bg-white/50"></span>
                    <?php endif; ?>
                    <span><?= $date ?></span>
                    <span class="w-1 h-1 rounded-full bg-white/50"></span>
                    <span><?= $reading_time ?></span>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- No Image: Simple Header -->
        <div class="py-16 md:py-24 bg-slate-50 dark:bg-slate-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
                <?php if ($category): ?>
                <span class="zenith-category mb-4 inline-block"><?= htmlspecialchars($category) ?></span>
                <?php endif; ?>
                
                <h1 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 dark:text-white leading-tight mb-6">
                    <?= $title ?>
                </h1>
                
                <div class="flex items-center justify-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                    <?php if ($author): ?>
                    <span><?= htmlspecialchars($author['display_name'] ?? 'Anonymous') ?></span>
                    <span class="w-1 h-1 rounded-full bg-slate-400"></span>
                    <?php endif; ?>
                    <span><?= $date ?></span>
                    <span class="w-1 h-1 rounded-full bg-slate-400"></span>
                    <span><?= $reading_time ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </header>
    
    <!-- Content -->
    <div class="py-12 md:py-16 bg-white dark:bg-slate-900">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            
            <!-- Share Buttons (Top) -->
            <?php if (zenith_option('show_share_buttons', 'yes') === 'yes'): ?>
            <div class="mb-8 pb-8 border-b border-slate-200 dark:border-slate-700">
                <?php 
                $url = $full_url;
                include ZENITH_PARTS . '/share-buttons.php'; 
                ?>
            </div>
            <?php endif; ?>
            
            <!-- Article Body -->
            <div class="zenith-prose prose prose-lg dark:prose-invert max-w-none
                        prose-headings:font-serif prose-headings:font-bold
                        prose-p:text-slate-600 dark:prose-p:text-slate-300 prose-p:leading-relaxed
                        prose-a:text-accent prose-a:no-underline hover:prose-a:underline
                        prose-img:rounded-xl prose-img:shadow-lg
                        prose-blockquote:border-accent prose-blockquote:bg-slate-50 dark:prose-blockquote:bg-slate-800 prose-blockquote:rounded-r-lg prose-blockquote:py-1
                        prose-code:bg-slate-100 dark:prose-code:bg-slate-800 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:before:content-none prose-code:after:content-none">
                <?= $htmlContent ?>
            </div>
            
            <!-- Tags (if any) -->
            <?php if (!empty($data['tags'])): ?>
            <div class="mt-10 pt-8 border-t border-slate-200 dark:border-slate-700">
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($data['tags'] as $tag): ?>
                    <a href="<?= $base_url ?>/tag/<?= urlencode($tag) ?>" 
                       class="px-3 py-1.5 text-sm bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-accent hover:text-white transition-colors">
                        #<?= htmlspecialchars($tag) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Share Buttons (Bottom) -->
            <?php if (zenith_option('show_share_buttons', 'yes') === 'yes'): ?>
            <div class="mt-10 pt-8 border-t border-slate-200 dark:border-slate-700">
                <?php include ZENITH_PARTS . '/share-buttons.php'; ?>
            </div>
            <?php endif; ?>
            
            <!-- Author Box -->
            <?php if (zenith_option('show_author_box', 'yes') === 'yes' && $author): ?>
            <div class="mt-10">
                <?php include ZENITH_PARTS . '/author-box.php'; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Related Posts -->
    <?php if (!empty($related_posts)): ?>
    <section class="py-12 md:py-16 bg-slate-50 dark:bg-slate-800 dark-transition">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 dark:text-white mb-8 text-center">
                You May Also Like
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($related_posts as $post): ?>
                    <?php include ZENITH_PARTS . '/post-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Post Navigation (Previous/Next) -->
    <section class="border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark-transition">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex justify-between gap-8">
                <!-- Previous Post Placeholder -->
                <a href="<?= $base_url ?>/" class="group flex items-center gap-3 text-left">
                    <span class="material-symbols-outlined text-slate-400 group-hover:text-accent transition-colors">arrow_back</span>
                    <div>
                        <span class="text-xs text-slate-500 dark:text-slate-400">Previous</span>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-accent transition-colors">
                            Back to Home
                        </p>
                    </div>
                </a>
                
                <!-- Next Post Placeholder -->
                <a href="<?= $base_url ?>/archive" class="group flex items-center gap-3 text-right">
                    <div>
                        <span class="text-xs text-slate-500 dark:text-slate-400">More</span>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-accent transition-colors">
                            View All Posts
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 group-hover:text-accent transition-colors">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>
</article>

<?php 
// Include footer
include ZENITH_PARTS . '/footer.php';
?>
