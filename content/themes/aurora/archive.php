<?php
/**
 * Aurora Theme — Universal Archive Template
 * Handles Blog, Portfolio, Testimonials, and any CPT
 * 
 * @package Aurora
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();
$brand_color = zed_theme_option('brand_color', '#6366f1');

// Get variables injected by Smart Archive Handler
$posts = $posts ?? [];
$page_num = $page_num ?? 1;
$total_pages = $total_pages ?? 1;

// Archive context
$archive_title = $archive_title ?? $blog_title ?? 'Archive';
$post_type = $post_type ?? 'post';
$post_type_label = $post_type_label ?? 'Posts';
$post_type_singular = $post_type_singular ?? 'Post';
$is_blog = $is_blog ?? ($post_type === 'post');

// Link prefix: Blog posts go to root /slug, CPTs go to /cpt/slug
$link_prefix = $is_blog ? '' : ($post_type . '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($archive_title) ?> — <?= htmlspecialchars($site_name) ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: '<?= $brand_color ?>' },
                    fontFamily: { sans: ['Inter', 'system-ui'] },
                },
            },
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400" rel="stylesheet">
    
    <?= aurora_css_variables() ?>
    <?php Event::trigger('zed_head'); ?>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    
    <!-- Navigation -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <nav class="flex items-center justify-between">
                <a href="<?= $base_url ?>/" class="text-2xl font-bold text-brand flex items-center gap-2">
                    <span class="material-symbols-outlined text-3xl">auto_awesome</span>
                    <?= htmlspecialchars($site_name) ?>
                </a>
                
                <div class="flex items-center gap-6">
                    <a href="<?= $base_url ?>/" class="text-sm text-slate-600 hover:text-slate-900">Home</a>
                    <a href="<?= $base_url ?>/blog" class="text-sm <?= $is_blog ? 'text-brand font-medium' : 'text-slate-600 hover:text-slate-900' ?>">Blog</a>
                    <?php if (!$is_blog): ?>
                    <span class="text-sm text-brand font-medium capitalize"><?= htmlspecialchars($post_type_label) ?></span>
                    <?php endif; ?>
                    <a href="<?= $base_url ?>/admin" class="px-4 py-2 bg-brand text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">
                        Dashboard
                    </a>
                </div>
            </nav>
        </div>
    </header>
    
    <!-- Header -->
    <section class="bg-gradient-to-r from-slate-900 to-brand/90 text-white py-16">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 capitalize"><?= htmlspecialchars($archive_title) ?></h1>
            <p class="text-xl text-slate-300">Latest <?= htmlspecialchars(strtolower($post_type_label)) ?></p>
        </div>
    </section>
    
    <!-- Posts Grid -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">
            <?php if (!empty($posts)): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($posts as $post):
                    $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
                    $featured = $data['featured_image'] ?? '';
                    $excerpt = $data['excerpt'] ?? '';
                    $date = isset($post['created_at']) ? date('M j, Y', strtotime($post['created_at'])) : '';
                    $permalink = $base_url . '/' . $link_prefix . $post['slug'];
                ?>
                <article class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                    <div class="aspect-video bg-slate-100 relative overflow-hidden">
                        <?php if ($featured): ?>
                            <img src="<?= htmlspecialchars($featured) ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-50">
                                <span class="material-symbols-outlined text-5xl">
                                    <?= $post_type === 'portfolio' ? 'work' : ($post_type === 'testimonial' ? 'format_quote' : 'image') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <?php if ($date): ?>
                        <p class="text-xs text-slate-400 mb-2"><?= $date ?></p>
                        <?php endif; ?>
                        <h2 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-brand transition-colors">
                            <a href="<?= $permalink ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h2>
                        <?php if ($excerpt): ?>
                        <p class="text-sm text-slate-600 line-clamp-2"><?= htmlspecialchars($excerpt) ?></p>
                        <?php endif; ?>
                        <a href="<?= $permalink ?>" class="inline-flex items-center gap-1 text-sm text-brand font-medium mt-4 hover:underline">
                            View <?= htmlspecialchars($post_type_singular) ?> →
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="flex items-center justify-center gap-2 mt-12">
                <?php if ($page_num > 1): ?>
                <a href="?page=<?= $page_num - 1 ?>" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm hover:bg-slate-50">
                    ← Previous
                </a>
                <?php endif; ?>
                
                <span class="px-4 py-2 text-sm text-slate-500">
                    Page <?= $page_num ?> of <?= $total_pages ?>
                </span>
                
                <?php if ($page_num < $total_pages): ?>
                <a href="?page=<?= $page_num + 1 ?>" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm hover:bg-slate-50">
                    Next →
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="text-center py-16">
                <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">folder_off</span>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">No <?= htmlspecialchars(strtolower($post_type_label)) ?> found</h2>
                <p class="text-slate-600 mb-6">Check back later for updates.</p>
                <a href="<?= $base_url ?>/" class="px-6 py-3 bg-brand text-white rounded-lg font-medium hover:opacity-90 transition-opacity">
                    Return Home
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-slate-400 text-sm">
                © <?= date('Y') ?> <?= htmlspecialchars($site_name) ?> • Powered by Aurora Framework
            </p>
        </div>
    </footer>
    
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
