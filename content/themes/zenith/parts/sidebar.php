<?php
/**
 * Zenith Theme — Sidebar Part
 * 
 * Widget area with popular posts, categories, and social links
 * 
 * @package Zenith
 */

declare(strict_types=1);

use Core\Router;
use Core\Database;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();

// Get popular posts (most recent for now)
$popular_posts = zed_get_posts([
    'type' => 'post',
    'status' => 'published',
    'limit' => 5,
    'orderby' => 'created_at',
    'order' => 'DESC'
]);

// Get categories
$categories = zed_get_categories();

// Social links
$twitter = zenith_option('social_twitter', '');
$facebook = zenith_option('social_facebook', '');
$instagram = zenith_option('social_instagram', '');
$linkedin = zenith_option('social_linkedin', '');
?>

<aside class="space-y-8">
    
    <!-- About Widget -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm dark-transition">
        <h3 class="text-lg font-serif font-bold text-slate-900 dark:text-white mb-4">
            About <?= htmlspecialchars($site_name) ?>
        </h3>
        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
            <?= htmlspecialchars(zenith_option('footer_about', 'Welcome to our magazine. We share stories, insights, and ideas.')) ?>
        </p>
        
        <!-- Social Links -->
        <div class="flex items-center gap-3">
            <?php if ($twitter): ?>
            <a href="<?= htmlspecialchars($twitter) ?>" target="_blank" rel="noopener" 
               class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-700 hover:bg-accent hover:text-white flex items-center justify-center transition-colors text-slate-600 dark:text-slate-300">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <?php endif; ?>
            <?php if ($facebook): ?>
            <a href="<?= htmlspecialchars($facebook) ?>" target="_blank" rel="noopener" 
               class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-700 hover:bg-accent hover:text-white flex items-center justify-center transition-colors text-slate-600 dark:text-slate-300">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <?php endif; ?>
            <?php if ($instagram): ?>
            <a href="<?= htmlspecialchars($instagram) ?>" target="_blank" rel="noopener" 
               class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-700 hover:bg-accent hover:text-white flex items-center justify-center transition-colors text-slate-600 dark:text-slate-300">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Popular Posts Widget -->
    <?php if (!empty($popular_posts)): ?>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm dark-transition">
        <h3 class="text-lg font-serif font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-accent">local_fire_department</span>
            Popular Posts
        </h3>
        <div class="space-y-4">
            <?php foreach ($popular_posts as $i => $post): 
                $data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
                $image = $data['featured_image'] ?? '';
            ?>
            <a href="<?= $base_url ?>/<?= $post['slug'] ?>" class="flex gap-4 group">
                <!-- Number or Thumbnail -->
                <?php if ($image): ?>
                <div class="w-20 h-16 rounded-lg overflow-hidden flex-shrink-0">
                    <img src="<?= htmlspecialchars($image) ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                </div>
                <?php else: ?>
                <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center flex-shrink-0">
                    <span class="text-accent font-bold"><?= $i + 1 ?></span>
                </div>
                <?php endif; ?>
                
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-slate-900 dark:text-white group-hover:text-accent transition-colors leading-snug line-clamp-2">
                        <?= htmlspecialchars($post['title']) ?>
                    </h4>
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        <?= date('M j, Y', strtotime($post['created_at'])) ?>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Categories Widget -->
    <?php if (!empty($categories)): ?>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm dark-transition">
        <h3 class="text-lg font-serif font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-accent">folder</span>
            Categories
        </h3>
        <div class="flex flex-wrap gap-2">
            <?php foreach ($categories as $category): ?>
            <a href="<?= $base_url ?>/category/<?= urlencode($category['slug'] ?? $category['name']) ?>" 
               class="px-3 py-1.5 text-sm bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-accent hover:text-white transition-colors">
                <?= htmlspecialchars($category['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Newsletter Widget -->
    <div class="bg-gradient-to-br from-accent to-pink-600 rounded-2xl p-6 text-white">
        <h3 class="text-lg font-serif font-bold mb-2">Newsletter</h3>
        <p class="text-sm text-white/80 mb-4">Get the latest posts delivered to your inbox.</p>
        <form class="space-y-3">
            <input type="email" placeholder="Your email" 
                   class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white">
            <button type="submit" class="w-full px-4 py-3 bg-white text-accent font-semibold rounded-lg hover:bg-white/90 transition-colors">
                Subscribe
            </button>
        </form>
    </div>
</aside>
