<?php
/**
 * Zenith Theme — Featured Slider Part
 * 
 * Full-width featured posts carousel
 * 
 * @package Zenith
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();
$count = (int) zenith_option('featured_count', 5);
$posts = zenith_get_featured_posts($count);

if (empty($posts)) return;
?>

<!-- Featured Slider -->
<section class="relative overflow-hidden bg-slate-900">
    <div id="zenith-slider" class="relative">
        <?php foreach ($posts as $index => $post): 
            $data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
            $title = htmlspecialchars($post['title'] ?? 'Untitled');
            $slug = $post['slug'] ?? '';
            $excerpt = htmlspecialchars(substr($data['excerpt'] ?? '', 0, 150));
            $image = $data['featured_image'] ?? '';
            $category = $data['category'] ?? 'General';
            $date = date('M j, Y', strtotime($post['created_at'] ?? 'now'));
            $isActive = $index === 0;
        ?>
        <div class="zenith-slide <?= $isActive ? '' : 'hidden' ?>" data-index="<?= $index ?>">
            <div class="relative h-[500px] md:h-[600px] lg:h-[700px]">
                <!-- Background Image -->
                <?php if ($image): ?>
                <img src="<?= htmlspecialchars($image) ?>" alt="<?= $title ?>" 
                     class="absolute inset-0 w-full h-full object-cover">
                <?php else: ?>
                <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900"></div>
                <?php endif; ?>
                
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
                
                <!-- Content -->
                <div class="relative h-full flex items-end">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-16 md:pb-24 w-full">
                        <div class="max-w-3xl zenith-slider-slide">
                            <!-- Category Badge -->
                            <span class="zenith-category mb-4 inline-block">
                                <?= htmlspecialchars($category) ?>
                            </span>
                            
                            <!-- Title -->
                            <h2 class="text-3xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-4 leading-tight">
                                <a href="<?= $base_url ?>/<?= $slug ?>" class="hover:text-accent transition-colors">
                                    <?= $title ?>
                                </a>
                            </h2>
                            
                            <!-- Excerpt -->
                            <?php if ($excerpt): ?>
                            <p class="text-lg text-slate-300 mb-6 hidden md:block">
                                <?= $excerpt ?>...
                            </p>
                            <?php endif; ?>
                            
                            <!-- Meta -->
                            <div class="flex items-center gap-4 text-sm text-slate-400">
                                <span><?= $date ?></span>
                                <span class="w-1 h-1 rounded-full bg-slate-500"></span>
                                <span><?= zed_reading_time($data['content'] ?? []) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- Slider Navigation -->
        <?php if (count($posts) > 1): ?>
        <div class="absolute bottom-6 right-6 flex items-center gap-2">
            <button id="slider-prev" class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-white">chevron_left</span>
            </button>
            <button id="slider-next" class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-white">chevron_right</span>
            </button>
        </div>
        
        <!-- Slider Dots -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2">
            <?php for ($i = 0; $i < count($posts); $i++): ?>
            <button class="zenith-dot w-2 h-2 rounded-full transition-all <?= $i === 0 ? 'bg-white w-8' : 'bg-white/40' ?>" 
                    data-index="<?= $i ?>"></button>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
