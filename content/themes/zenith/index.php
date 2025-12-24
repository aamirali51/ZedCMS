<?php
/**
 * Zenith Theme — Homepage Template
 * 
 * Magazine-style homepage with featured slider, posts grid, and sidebar
 * 
 * @package Zenith
 * @version 1.0.0
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();

// Get posts for the grid (excluding featured ones)
$featured_count = (int) zenith_option('featured_count', 5);
$posts_per_page = (int) zenith_option('posts_per_page', 10);

$posts = zed_get_posts([
    'type' => 'post',
    'status' => 'published',
    'limit' => $posts_per_page,
    'offset' => $featured_count, // Skip featured posts
    'orderby' => 'created_at',
    'order' => 'DESC'
]);

// Include head
include ZENITH_PARTS . '/head.php';

// Include header (which includes opening <body> and <main>)
include ZENITH_PARTS . '/header.php';
?>

<!-- Featured Slider -->
<?php include ZENITH_PARTS . '/slider.php'; ?>

<!-- Main Content -->
<section class="py-12 md:py-16 bg-slate-50 dark:bg-slate-900 dark-transition">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        
        <!-- Section Header -->
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 dark:text-white">
                Latest Articles
            </h2>
            <a href="<?= $base_url ?>/archive" class="text-sm font-medium text-accent hover:underline flex items-center gap-1">
                View All
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Posts Grid -->
            <div class="lg:col-span-2">
                <?php if (!empty($posts)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($posts as $post): ?>
                        <?php include ZENITH_PARTS . '/post-card.php'; ?>
                    <?php endforeach; ?>
                </div>
                
                <!-- Load More Button -->
                <div class="mt-10 text-center">
                    <a href="<?= $base_url ?>/archive" 
                       class="inline-flex items-center gap-2 px-8 py-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-300 font-medium hover:border-accent hover:text-accent transition-colors">
                        <span class="material-symbols-outlined">refresh</span>
                        Load More Articles
                    </a>
                </div>
                <?php else: ?>
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-slate-400 mb-4">article</span>
                    <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">No Posts Yet</h3>
                    <p class="text-slate-500 dark:text-slate-400">Start creating content in the admin panel.</p>
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

<!-- Trending Section (Optional) -->
<?php 
$trending_posts = zed_get_posts([
    'type' => 'post',
    'status' => 'published',
    'limit' => 4,
    'orderby' => 'created_at',
    'order' => 'DESC'
]);
?>
<?php if (!empty($trending_posts) && count($trending_posts) >= 4): ?>
<section class="py-12 md:py-16 bg-white dark:bg-slate-800 dark-transition">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 dark:text-white mb-8 flex items-center gap-3">
            <span class="material-symbols-outlined text-accent">trending_up</span>
            Trending Now
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($trending_posts as $i => $post): ?>
            <article class="group">
                <?php 
                $data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
                $image = $data['featured_image'] ?? '';
                ?>
                <a href="<?= $base_url ?>/<?= $post['slug'] ?>" class="block">
                    <div class="relative rounded-xl overflow-hidden mb-4 zenith-image-zoom">
                        <?php if ($image): ?>
                        <img src="<?= htmlspecialchars($image) ?>" alt="" class="w-full h-48 object-cover">
                        <?php else: ?>
                        <div class="w-full h-48 bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center">
                            <span class="text-5xl font-bold text-slate-400/50"><?= $i + 1 ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Ranking Badge -->
                        <div class="absolute top-3 left-3 w-8 h-8 rounded-full bg-accent text-white flex items-center justify-center font-bold text-sm">
                            <?= $i + 1 ?>
                        </div>
                    </div>
                    
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-accent transition-colors leading-snug line-clamp-2">
                        <?= htmlspecialchars($post['title']) ?>
                    </h3>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Newsletter CTA -->
<section class="py-16 md:py-24 bg-gradient-to-br from-slate-900 via-slate-800 to-accent/30 text-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
        <span class="material-symbols-outlined text-5xl text-accent mb-4">mail</span>
        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4">Stay Updated</h2>
        <p class="text-lg text-slate-300 mb-8">Subscribe to our newsletter and never miss a story.</p>
        
        <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
            <input type="email" placeholder="Enter your email" 
                   class="flex-1 px-5 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/60 focus:outline-none focus:border-accent">
            <button type="submit" class="px-8 py-4 bg-accent hover:bg-accent/90 text-white font-semibold rounded-xl transition-colors">
                Subscribe
            </button>
        </form>
        
        <p class="text-sm text-slate-400 mt-4">No spam, unsubscribe anytime.</p>
    </div>
</section>

<?php 
// Include footer
include ZENITH_PARTS . '/footer.php';
?>
