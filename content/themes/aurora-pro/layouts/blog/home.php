<?php
/**
 * Aurora Pro - Blog Layout Homepage
 * 
 * Classic blog layout with hero, featured posts, and sidebar.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();

// Get theme settings
$showHero = aurora_option('show_hero', true);
$showFeatured = aurora_option('show_featured', true);
$showNewsletter = aurora_option('show_newsletter', true);
$showSidebar = aurora_option('show_sidebar', true);
$heroTitle = aurora_option('hero_title', 'Welcome to Our Blog');
$heroSubtitle = aurora_option('hero_subtitle', 'Discover stories, insights, and inspiration');
$featuredCount = (int) aurora_option('featured_count', 3);

// Get featured posts (pinned or latest)
$featuredPosts = array_slice($posts ?? [], 0, $featuredCount);
$regularPosts = array_slice($posts ?? [], $featuredCount);
?>

<?php if ($showHero): ?>
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white py-20 lg:py-28 overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                <?= htmlspecialchars($heroTitle) ?>
            </h1>
            <p class="text-xl md:text-2xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                <?= htmlspecialchars($heroSubtitle) ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#posts" class="px-8 py-4 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors shadow-lg hover:shadow-xl">
                    Explore Articles
                </a>
                <a href="<?= $base_url ?>/about" class="px-8 py-4 border-2 border-white/40 text-white font-semibold rounded-xl hover:bg-white/10 transition-colors">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($showFeatured && !empty($featuredPosts)): ?>
<!-- Featured Posts -->
<section class="py-12 lg:py-16 bg-gray-50 dark:bg-gray-800/50">
    <div class="container mx-auto px-6">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                Featured Posts
            </h2>
            <a href="<?= $base_url ?>/blog" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline flex items-center gap-1">
                View all
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <?php foreach ($featuredPosts as $featuredPost): 
                $data = is_string($featuredPost['data'] ?? '') 
                    ? json_decode($featuredPost['data'], true) 
                    : ($featuredPost['data'] ?? []);
                $excerpt = $data['excerpt'] ?? '';
                $featuredImage = $data['featured_image'] ?? '';
                $categories = $data['categories'] ?? [];
            ?>
            <article class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Image -->
                <div class="aspect-[16/10] overflow-hidden bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-gray-700 dark:to-gray-600">
                    <?php if ($featuredImage): ?>
                    <img src="<?= htmlspecialchars($featuredImage) ?>" 
                         alt="<?= htmlspecialchars($featuredPost['title']) ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="text-5xl font-extrabold text-indigo-200 dark:text-gray-500">
                            <?= strtoupper(substr($featuredPost['title'], 0, 1)) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <!-- Meta -->
                    <div class="flex items-center gap-2 text-sm mb-3">
                        <span class="font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wide text-xs">
                            <?= ucfirst($featuredPost['type'] ?? 'post') ?>
                        </span>
                        <span class="text-gray-300 dark:text-gray-600">•</span>
                        <time class="text-gray-500 dark:text-gray-400">
                            <?= date('M j, Y', strtotime($featuredPost['created_at'])) ?>
                        </time>
                    </div>
                    
                    <!-- Title -->
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                        <a href="<?= $base_url . '/' . htmlspecialchars($featuredPost['slug']) ?>">
                            <?= htmlspecialchars($featuredPost['title']) ?>
                        </a>
                    </h3>
                    
                    <!-- Excerpt -->
                    <?php if ($excerpt): ?>
                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-4">
                        <?= htmlspecialchars($excerpt) ?>
                    </p>
                    <?php endif; ?>
                    
                    <!-- Read More -->
                    <a href="<?= $base_url . '/' . htmlspecialchars($featuredPost['slug']) ?>" 
                       class="inline-flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                        Read article
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Main Content Area -->
<section id="posts" class="py-12 lg:py-16">
    <div class="container mx-auto px-6">
        <div class="<?= $showSidebar ? 'lg:grid lg:grid-cols-3 lg:gap-12' : '' ?>">
            
            <!-- Posts Grid -->
            <div class="<?= $showSidebar ? 'lg:col-span-2' : '' ?>">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-8">
                    Latest Articles
                </h2>
                
                <?php if (!empty($regularPosts)): ?>
                <div class="space-y-8">
                    <?php foreach ($regularPosts as $post): 
                        $data = is_string($post['data'] ?? '') 
                            ? json_decode($post['data'], true) 
                            : ($post['data'] ?? []);
                        $excerpt = $data['excerpt'] ?? '';
                        $featuredImage = $data['featured_image'] ?? '';
                        $readingTime = function_exists('aurora_reading_time') 
                            ? aurora_reading_time($data['content'] ?? '') 
                            : 3;
                    ?>
                    <article class="group flex flex-col md:flex-row gap-6 bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300">
                        <!-- Image -->
                        <div class="md:w-72 flex-shrink-0">
                            <div class="aspect-[16/10] rounded-xl overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600">
                                <?php if ($featuredImage): ?>
                                <img src="<?= htmlspecialchars($featuredImage) ?>" 
                                     alt="<?= htmlspecialchars($post['title']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="text-4xl font-extrabold text-gray-300 dark:text-gray-500">
                                        <?= strtoupper(substr($post['title'], 0, 1)) ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 flex flex-col justify-center">
                            <!-- Meta -->
                            <div class="flex items-center gap-3 text-sm mb-2">
                                <span class="font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wide text-xs">
                                    <?= ucfirst($post['type'] ?? 'post') ?>
                                </span>
                                <span class="text-gray-300 dark:text-gray-600">•</span>
                                <time class="text-gray-500 dark:text-gray-400">
                                    <?= date('M j, Y', strtotime($post['created_at'])) ?>
                                </time>
                                <span class="text-gray-300 dark:text-gray-600">•</span>
                                <span class="text-gray-500 dark:text-gray-400">
                                    <?= $readingTime ?> min read
                                </span>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                <a href="<?= $base_url . '/' . htmlspecialchars($post['slug']) ?>">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h3>
                            
                            <!-- Excerpt -->
                            <?php if ($excerpt): ?>
                            <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-3">
                                <?= htmlspecialchars($excerpt) ?>
                            </p>
                            <?php endif; ?>
                            
                            <!-- Read More -->
                            <a href="<?= $base_url . '/' . htmlspecialchars($post['slug']) ?>" 
                               class="inline-flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition-colors">
                                Continue reading
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    <a href="<?= $base_url ?>/blog/page/2" class="px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        Load More Posts
                    </a>
                </div>
                <?php else: ?>
                <!-- No Posts -->
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No articles yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Create your first post to see it here.</p>
                    <a href="<?= $base_url ?>/admin/editor" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Post
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if ($showSidebar): ?>
            <!-- Sidebar -->
            <aside class="lg:col-span-1 mt-12 lg:mt-0">
                <div class="sticky top-24 space-y-8">
                    
                    <!-- About Widget -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-4">About</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                            Welcome to our blog! We share insights, tutorials, and stories about technology, design, and creativity.
                        </p>
                    </div>
                    
                    <!-- Categories Widget -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-4">Categories</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            if (function_exists('zed_get_categories')) {
                                $categories = array_slice(zed_get_categories([]), 0, 10);
                                foreach ($categories as $cat) {
                                    echo '<a href="' . $base_url . '/category/' . htmlspecialchars($cat['slug']) . '" 
                                          class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">' 
                                          . htmlspecialchars($cat['name']) . '</a>';
                                }
                            } else {
                                echo '<span class="text-gray-500 text-sm">No categories</span>';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <?php if ($showNewsletter): ?>
                    <!-- Newsletter Widget -->
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white">
                        <h3 class="font-bold mb-2"><?= htmlspecialchars(aurora_option('newsletter_title', 'Subscribe')) ?></h3>
                        <p class="text-indigo-100 text-sm mb-4"><?= htmlspecialchars(aurora_option('newsletter_text', 'Get updates delivered to your inbox.')) ?></p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Enter your email" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl placeholder-indigo-200 text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                            <button type="submit" class="w-full px-4 py-3 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                                Subscribe
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </aside>
            <?php endif; ?>
            
        </div>
    </div>
</section>
