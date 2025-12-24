<?php
/**
 * Zenith Theme — Post Card Part
 * 
 * Reusable blog post card component
 * 
 * @package Zenith
 * 
 * Expected variables:
 * @var array $post - Post data array
 * @var string $size - 'small' | 'medium' | 'large' (default: medium)
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();

// Extract post data
$data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
$title = htmlspecialchars($post['title'] ?? 'Untitled');
$slug = $post['slug'] ?? '';
$excerpt = htmlspecialchars(substr($data['excerpt'] ?? '', 0, 120));
$image = $data['featured_image'] ?? '';
$category = $data['category'] ?? '';
$date = date('M j, Y', strtotime($post['created_at'] ?? 'now'));
$reading_time = zed_reading_time($data['content'] ?? []);
$author = zed_get_author($post['author_id'] ?? 0);
$size = $size ?? 'medium';

// Size-based classes
$imageHeight = match($size) {
    'large' => 'h-72 md:h-96',
    'small' => 'h-40',
    default => 'h-52'
};

$titleSize = match($size) {
    'large' => 'text-2xl md:text-3xl',
    'small' => 'text-lg',
    default => 'text-xl'
};
?>

<article class="zenith-card group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl dark-transition">
    <!-- Featured Image -->
    <a href="<?= $base_url ?>/<?= $slug ?>" class="block zenith-image-zoom">
        <div class="relative <?= $imageHeight ?>">
            <?php if ($image): ?>
            <img src="<?= htmlspecialchars($image) ?>" alt="<?= $title ?>" 
                 class="w-full h-full object-cover" loading="lazy">
            <?php else: ?>
            <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-5xl text-slate-400 dark:text-slate-500">article</span>
            </div>
            <?php endif; ?>
            
            <!-- Category Badge -->
            <?php if ($category): ?>
            <span class="absolute top-4 left-4 zenith-category">
                <?= htmlspecialchars($category) ?>
            </span>
            <?php endif; ?>
        </div>
    </a>
    
    <!-- Content -->
    <div class="p-5 md:p-6">
        <!-- Title -->
        <h3 class="<?= $titleSize ?> font-serif font-bold text-slate-900 dark:text-white mb-3 leading-snug">
            <a href="<?= $base_url ?>/<?= $slug ?>" class="hover:text-accent transition-colors">
                <?= $title ?>
            </a>
        </h3>
        
        <!-- Excerpt -->
        <?php if ($excerpt && $size !== 'small'): ?>
        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
            <?= $excerpt ?>...
        </p>
        <?php endif; ?>
        
        <!-- Meta -->
        <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
            <div class="flex items-center gap-3">
                <?php if ($author): ?>
                <div class="flex items-center gap-2">
                    <?php if ($author['avatar']): ?>
                    <img src="<?= htmlspecialchars($author['avatar']) ?>" alt="" class="w-6 h-6 rounded-full">
                    <?php endif; ?>
                    <span><?= htmlspecialchars($author['display_name'] ?? 'Anonymous') ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center gap-2 text-slate-400">
                <span><?= $date ?></span>
                <span>·</span>
                <span><?= $reading_time ?></span>
            </div>
        </div>
    </div>
</article>
