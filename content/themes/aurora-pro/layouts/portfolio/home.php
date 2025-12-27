<?php
/**
 * Aurora Pro - Portfolio Layout Homepage
 * 
 * Masonry grid layout for showcasing creative projects.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();

// Get portfolio projects (uses custom post type if registered)
$projects = [];
if (function_exists('zed_get_posts_by_type')) {
    $projects = zed_get_posts_by_type('portfolio', 12);
}

// Fallback to regular posts if no portfolio items
if (empty($projects)) {
    $projects = $posts ?? [];
}

// Get settings
$showHero = zed_theme_option('show_hero', true);
$heroTitle = zed_theme_option('hero_title', 'My Portfolio');
$heroSubtitle = zed_theme_option('hero_subtitle', 'A collection of my creative work');

// Get categories for filtering
$categories = function_exists('zed_get_categories') ? array_slice(zed_get_categories([]), 0, 10) : [];
?>

<?php if ($showHero): ?>
<!-- Portfolio Hero -->
<section class="py-16 lg:py-24 bg-gray-900 text-white text-center relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'m36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm-6 60v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4">
            <?= htmlspecialchars($heroTitle) ?>
        </h1>
        <p class="text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
            <?= htmlspecialchars($heroSubtitle) ?>
        </p>
        
        <!-- Category Filters -->
        <?php if (!empty($categories)): ?>
        <div class="flex flex-wrap items-center justify-center gap-3" id="portfolio-filters">
            <button class="filter-btn active px-5 py-2 bg-white text-gray-900 font-medium rounded-full transition-all" data-filter="all">
                All Work
            </button>
            <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
            <button class="filter-btn px-5 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 font-medium rounded-full transition-all" data-filter="<?= htmlspecialchars($cat['slug']) ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- Portfolio Grid -->
<section class="py-12 lg:py-16 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-6">
        
        <?php if (!empty($projects)): ?>
        <!-- Masonry Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="portfolio-grid">
            <?php 
            $sizes = ['tall', 'normal', 'normal', 'wide', 'normal', 'tall'];
            $i = 0;
            foreach ($projects as $project): 
                $data = is_string($project['data'] ?? '') 
                    ? json_decode($project['data'], true) 
                    : ($project['data'] ?? []);
                $featuredImage = $data['featured_image'] ?? '';
                $excerpt = $data['excerpt'] ?? '';
                $projectCategories = $data['categories'] ?? [];
                $size = $sizes[$i % count($sizes)];
                $aspectClass = $size === 'tall' ? 'aspect-[3/4]' : ($size === 'wide' ? 'aspect-[16/9] md:col-span-2' : 'aspect-square');
                $i++;
            ?>
            <article class="portfolio-item group relative overflow-hidden rounded-2xl bg-gray-200 dark:bg-gray-800 <?= $aspectClass ?>" 
                     data-category="<?= htmlspecialchars(implode(',', array_map(fn($c) => is_array($c) ? ($c['slug'] ?? '') : $c, $projectCategories))) ?>">
                
                <!-- Image -->
                <?php if ($featuredImage): ?>
                <img src="<?= htmlspecialchars($featuredImage) ?>" 
                     alt="<?= htmlspecialchars($project['title']) ?>"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <?php else: ?>
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 opacity-80"></div>
                <?php endif; ?>
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <!-- Content -->
                <div class="absolute inset-0 flex flex-col justify-end p-6 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                    <!-- Categories -->
                    <?php if (!empty($projectCategories)): ?>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <?php foreach (array_slice($projectCategories, 0, 2) as $cat): ?>
                        <span class="px-2 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                            <?= htmlspecialchars(is_array($cat) ? ($cat['name'] ?? '') : $cat) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Title -->
                    <h3 class="text-xl lg:text-2xl font-bold text-white mb-2">
                        <?= htmlspecialchars($project['title']) ?>
                    </h3>
                    
                    <!-- Excerpt -->
                    <?php if ($excerpt): ?>
                    <p class="text-gray-300 text-sm line-clamp-2 mb-4">
                        <?= htmlspecialchars($excerpt) ?>
                    </p>
                    <?php endif; ?>
                    
                    <!-- View Button -->
                    <a href="<?= $base_url . '/' . htmlspecialchars($project['slug']) ?>" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-900 font-semibold rounded-xl hover:bg-gray-100 transition-colors self-start">
                        View Project
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
                
                <!-- Quick View Icon (visible without hover on mobile) -->
                <div class="absolute top-4 right-4 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="w-10 h-10 flex items-center justify-center bg-white/20 backdrop-blur-sm text-white rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </span>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Load More -->
        <div class="mt-12 text-center">
            <button class="px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-semibold rounded-xl hover:opacity-90 transition-opacity">
                Load More Projects
            </button>
        </div>
        
        <?php else: ?>
        <!-- No Projects -->
        <div class="max-w-md mx-auto text-center py-16">
            <div class="w-20 h-20 mx-auto mb-6 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No projects yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Start adding portfolio projects to showcase your work.</p>
            <a href="<?= $base_url ?>/admin/editor?type=portfolio" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Project
            </a>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<!-- Filter & Animation Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const items = document.querySelectorAll('.portfolio-item');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-white', 'text-gray-900');
                b.classList.add('bg-gray-800', 'text-gray-300');
            });
            this.classList.add('active', 'bg-white', 'text-gray-900');
            this.classList.remove('bg-gray-800', 'text-gray-300');
            
            const filter = this.dataset.filter;
            
            // Filter items
            items.forEach(item => {
                const categories = item.dataset.category.split(',');
                if (filter === 'all' || categories.includes(filter)) {
                    item.style.display = '';
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        if (!item.dataset.category.includes(document.querySelector('.filter-btn.active').dataset.filter)) {
                            item.style.display = 'none';
                        }
                    }, 300);
                }
            });
        });
    });
});
</script>

<style>
.portfolio-item {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
</style>
