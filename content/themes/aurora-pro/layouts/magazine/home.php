<?php
/**
 * Aurora Pro - Magazine Layout Homepage
 * 
 * News-style layout with featured posts, category sections, and trending sidebar.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Router;

$base_url = Router::getBasePath();

// Get settings
$showFeatured = zed_theme_option('show_featured', true);
$showCategories = zed_theme_option('show_categories', true);
$showNewsletter = zed_theme_option('show_newsletter', true);

// Split posts for different sections
$featuredPost = $posts[0] ?? null;
$secondaryFeatured = array_slice($posts ?? [], 1, 2);
$latestPosts = array_slice($posts ?? [], 3, 6);
$trendingPosts = array_slice($posts ?? [], 0, 5);
?>

<!-- Magazine Hero - Featured Grid -->
<?php if ($showFeatured && $featuredPost): ?>
<section class="py-8 lg:py-12 bg-gray-50 dark:bg-gray-800/50">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-6">
            
            <!-- Main Featured Post -->
            <?php 
            $data = is_string($featuredPost['data'] ?? '') 
                ? json_decode($featuredPost['data'], true) 
                : ($featuredPost['data'] ?? []);
            $featuredImage = $data['featured_image'] ?? '';
            ?>
            <article class="group relative overflow-hidden rounded-2xl bg-gray-900 aspect-[4/3] lg:aspect-auto lg:min-h-[500px]">
                <?php if ($featuredImage): ?>
                <img src="<?= htmlspecialchars($featuredImage) ?>" 
                     alt="<?= htmlspecialchars($featuredPost['title']) ?>"
                     class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:scale-105 transition-transform duration-700">
                <?php else: ?>
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-purple-700"></div>
                <?php endif; ?>
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                
                <div class="absolute bottom-0 left-0 right-0 p-6 lg:p-8">
                    <span class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-bold uppercase tracking-wider rounded mb-4">
                        Featured
                    </span>
                    <h2 class="text-2xl lg:text-4xl font-extrabold text-white mb-3 leading-tight group-hover:text-indigo-300 transition-colors">
                        <a href="<?= $base_url . '/' . htmlspecialchars($featuredPost['slug']) ?>">
                            <?= htmlspecialchars($featuredPost['title']) ?>
                        </a>
                    </h2>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <time><?= date('M j, Y', strtotime($featuredPost['created_at'])) ?></time>
                        <span>•</span>
                        <span><?= ucfirst($featuredPost['type'] ?? 'post') ?></span>
                    </div>
                </div>
            </article>
            
            <!-- Secondary Featured Posts -->
            <div class="grid gap-6">
                <?php foreach ($secondaryFeatured as $secPost): 
                    $secData = is_string($secPost['data'] ?? '') 
                        ? json_decode($secPost['data'], true) 
                        : ($secPost['data'] ?? []);
                    $secImage = $secData['featured_image'] ?? '';
                ?>
                <article class="group relative overflow-hidden rounded-2xl bg-gray-900 aspect-[16/9] lg:aspect-auto lg:min-h-[240px]">
                    <?php if ($secImage): ?>
                    <img src="<?= htmlspecialchars($secImage) ?>" 
                         alt="<?= htmlspecialchars($secPost['title']) ?>"
                         class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:scale-105 transition-transform duration-700">
                    <?php else: ?>
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600 to-pink-600"></div>
                    <?php endif; ?>
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-5">
                        <h3 class="text-lg lg:text-xl font-bold text-white mb-2 group-hover:text-indigo-300 transition-colors line-clamp-2">
                            <a href="<?= $base_url . '/' . htmlspecialchars($secPost['slug']) ?>">
                                <?= htmlspecialchars($secPost['title']) ?>
                            </a>
                        </h3>
                        <time class="text-sm text-gray-300"><?= date('M j, Y', strtotime($secPost['created_at'])) ?></time>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Breaking News Ticker -->
<section class="bg-gray-900 dark:bg-gray-950 py-3 overflow-hidden">
    <div class="container mx-auto px-6">
        <div class="flex items-center gap-4">
            <span class="flex-shrink-0 px-3 py-1 bg-red-500 text-white text-xs font-bold uppercase tracking-wider rounded animate-pulse">
                Latest
            </span>
            <div class="overflow-hidden">
                <div class="flex gap-8 text-white text-sm whitespace-nowrap animate-marquee">
                    <?php foreach (array_slice($posts ?? [], 0, 5) as $tickerPost): ?>
                    <a href="<?= $base_url . '/' . htmlspecialchars($tickerPost['slug']) ?>" class="hover:text-indigo-400 transition-colors">
                        <?= htmlspecialchars($tickerPost['title']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-marquee {
    animation: marquee 30s linear infinite;
}
</style>

<!-- Main Content with Sidebar -->
<section class="py-12 lg:py-16">
    <div class="container mx-auto px-6">
        <div class="lg:grid lg:grid-cols-3 lg:gap-12">
            
            <!-- Main Content -->
            <div class="lg:col-span-2">
                
                <!-- Latest Posts Section -->
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <span class="w-1 h-8 bg-indigo-600 rounded-full"></span>
                            Latest News
                        </h2>
                        <a href="<?= $base_url ?>/blog" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
                            View all →
                        </a>
                    </div>
                    
                    <div class="space-y-6">
                        <?php foreach ($latestPosts as $latestPost): 
                            $latestData = is_string($latestPost['data'] ?? '') 
                                ? json_decode($latestPost['data'], true) 
                                : ($latestPost['data'] ?? []);
                            $latestImage = $latestData['featured_image'] ?? '';
                            $latestExcerpt = $latestData['excerpt'] ?? '';
                        ?>
                        <article class="group flex gap-5 pb-6 border-b border-gray-100 dark:border-gray-800 last:border-0 last:pb-0">
                            <!-- Thumbnail -->
                            <div class="w-28 h-28 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800">
                                <?php if ($latestImage): ?>
                                <img src="<?= htmlspecialchars($latestImage) ?>" 
                                     alt="<?= htmlspecialchars($latestPost['title']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-2xl font-bold text-gray-300 dark:text-gray-600">
                                    <?= strtoupper(substr($latestPost['title'], 0, 1)) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                    <span class="font-semibold text-indigo-600 dark:text-indigo-400 uppercase">
                                        <?= ucfirst($latestPost['type'] ?? 'post') ?>
                                    </span>
                                    <span>•</span>
                                    <time><?= date('M j', strtotime($latestPost['created_at'])) ?></time>
                                </div>
                                
                                <h3 class="font-bold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                                    <a href="<?= $base_url . '/' . htmlspecialchars($latestPost['slug']) ?>">
                                        <?= htmlspecialchars($latestPost['title']) ?>
                                    </a>
                                </h3>
                                
                                <?php if ($latestExcerpt): ?>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-1">
                                    <?= htmlspecialchars($latestExcerpt) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php if ($showCategories && function_exists('zed_get_categories')): ?>
                <!-- Category Grid -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                        <span class="w-1 h-8 bg-purple-600 rounded-full"></span>
                        Explore Topics
                    </h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php 
                        $allCategories = zed_get_categories([]);
                        $categories = array_slice($allCategories, 0, 6);
                        $categoryColors = ['from-indigo-500 to-purple-600', 'from-pink-500 to-rose-600', 'from-emerald-500 to-teal-600', 'from-amber-500 to-orange-600', 'from-cyan-500 to-blue-600', 'from-violet-500 to-purple-600'];
                        $i = 0;
                        foreach ($categories as $cat): 
                            $color = $categoryColors[$i % count($categoryColors)];
                            $i++;
                        ?>
                        <a href="<?= $base_url ?>/category/<?= htmlspecialchars($cat['slug']) ?>" 
                           class="group relative overflow-hidden rounded-xl aspect-[2/1] bg-gradient-to-br <?= $color ?>">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-white font-bold text-lg"><?= htmlspecialchars($cat['name']) ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
            
            <!-- Sidebar -->
            <aside class="lg:col-span-1 mt-12 lg:mt-0">
                <div class="sticky top-24 space-y-8">
                    
                    <!-- Trending Posts -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                            </svg>
                            Trending
                        </h3>
                        
                        <div class="space-y-4">
                            <?php 
                            $num = 1;
                            foreach ($trendingPosts as $trendPost): 
                            ?>
                            <a href="<?= $base_url . '/' . htmlspecialchars($trendPost['slug']) ?>" 
                               class="flex items-start gap-3 group">
                                <span class="w-8 h-8 flex-shrink-0 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold text-sm rounded-lg">
                                    <?= str_pad((string)$num, 2, '0', STR_PAD_LEFT) ?>
                                </span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                                    <?= htmlspecialchars($trendPost['title']) ?>
                                </span>
                            </a>
                            <?php $num++; endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Social Follow -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-4">Follow Us</h3>
                        <div class="grid grid-cols-3 gap-3">
                            <a href="#" class="flex items-center justify-center py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                            </a>
                            <a href="#" class="flex items-center justify-center py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                            </a>
                            <a href="#" class="flex items-center justify-center py-3 bg-gradient-to-br from-pink-500 to-orange-400 text-white rounded-xl hover:opacity-90 transition-opacity">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                            </a>
                        </div>
                    </div>
                    
                    <?php if ($showNewsletter): ?>
                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white">
                        <h3 class="font-bold mb-2"><?= htmlspecialchars(zed_theme_option('newsletter_title', 'Get Daily Updates')) ?></h3>
                        <p class="text-indigo-100 text-sm mb-4"><?= htmlspecialchars(zed_theme_option('newsletter_text', 'Subscribe for the latest news.')) ?></p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Your email" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl placeholder-indigo-200 text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                            <button type="submit" class="w-full px-4 py-3 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                                Subscribe
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </aside>
            
        </div>
    </div>
</section>
