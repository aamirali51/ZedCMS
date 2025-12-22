<?php
/**
 * Aurora Theme — Homepage Template
 * 
 * @package Aurora
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();
$site_tagline = zed_get_site_tagline();

// Get Aurora options
$hero_title = zed_theme_option('hero_title', 'Welcome to Aurora');
$hero_subtitle = zed_theme_option('hero_subtitle', 'The modern way to build with ZedCMS');
$hero_image = zed_theme_option('hero_image', '');
$brand_color = zed_theme_option('brand_color', '#6366f1');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= zed_page_title() ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '<?= $brand_color ?>',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400" rel="stylesheet">
    
    <?= aurora_css_variables() ?>
    <?= zed_render_theme_styles() ?>
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
                
                <div class="flex items-center gap-8">
                    <?= zed_menu('Main Menu', ['class' => 'flex items-center gap-6 text-sm font-medium text-slate-600']) ?>
                    <a href="<?= $base_url ?>/admin" class="px-4 py-2 bg-brand text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">
                        Dashboard
                    </a>
                </div>
            </nav>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-brand/90 text-white">
        <?php if ($hero_image): ?>
        <div class="absolute inset-0 opacity-20">
            <img src="<?= htmlspecialchars($hero_image) ?>" alt="" class="w-full h-full object-cover">
        </div>
        <?php endif; ?>
        
        <div class="relative max-w-7xl mx-auto px-6 py-32 text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6">
                <?= htmlspecialchars($hero_title) ?>
            </h1>
            <p class="text-xl md:text-2xl text-slate-300 max-w-3xl mx-auto mb-10">
                <?= htmlspecialchars($hero_subtitle) ?>
            </p>
            <div class="flex items-center justify-center gap-4">
                <a href="<?= $base_url ?>/admin/editor?new=true" class="px-8 py-4 bg-white text-slate-900 font-semibold rounded-xl hover:bg-slate-100 transition-colors shadow-lg">
                    Start Creating
                </a>
                <a href="#features" class="px-8 py-4 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition-colors border border-white/20">
                    Learn More
                </a>
            </div>
        </div>
        
        <!-- Decorative gradient -->
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-slate-50 to-transparent"></div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Built for Developers</h2>
                <p class="text-xl text-slate-600 max-w-2xl mx-auto">Aurora showcases the full power of ZedCMS Theme API v2</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 bg-brand/10 text-brand rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-3xl">palette</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Custom Post Types</h3>
                    <p class="text-slate-600">Portfolio & Testimonials CPTs demonstrate the registry API with full admin integration.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 bg-brand/10 text-brand rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-3xl">tune</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Theme Settings</h3>
                    <p class="text-slate-600">Aurora Options panel with color pickers, media uploads, and layout controls.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 bg-brand/10 text-brand rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-3xl">code</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Scoped Hooks</h3>
                    <p class="text-slate-600">Context-aware hooks that only fire for specific post types or templates.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Latest Posts -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Latest Posts</h2>
                <a href="<?= $base_url ?>/blog" class="text-brand font-medium hover:underline">View all →</a>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                $latest_posts = zed_get_latest_posts(3);
                foreach ($latest_posts as $post):
                    $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
                    $featured = $data['featured_image'] ?? '';
                    $excerpt = $data['excerpt'] ?? '';
                ?>
                <article class="group">
                    <div class="aspect-video rounded-xl overflow-hidden bg-slate-100 mb-4">
                        <?php if ($featured): ?>
                            <img src="<?= htmlspecialchars($featured) ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <span class="material-symbols-outlined text-5xl">image</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 group-hover:text-brand transition-colors mb-2">
                        <a href="<?= $base_url ?>/<?= htmlspecialchars($post['slug']) ?>">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>
                    <?php if ($excerpt): ?>
                    <p class="text-slate-600 text-sm line-clamp-2"><?= htmlspecialchars($excerpt) ?></p>
                    <?php endif; ?>
                </article>
                <?php endforeach; ?>
                
                <?php if (empty($latest_posts)): ?>
                <div class="col-span-3 text-center py-12 text-slate-500">
                    <p>No posts yet. <a href="<?= $base_url ?>/admin/editor?new=true" class="text-brand hover:underline">Create your first post</a></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div>
                    <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">auto_awesome</span>
                        Aurora
                    </h4>
                    <p class="text-slate-400 text-sm">The ultimate starter framework for ZedCMS.</p>
                </div>
                
                <div>
                    <h5 class="font-semibold mb-4 text-slate-300">Resources</h5>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="<?= $base_url ?>/admin/wiki?doc=aurora-guide" class="hover:text-white">Documentation</a></li>
                        <li><a href="<?= $base_url ?>/admin/wiki" class="hover:text-white">Knowledge Base</a></li>
                        <li><a href="#" class="hover:text-white">API Reference</a></li>
                    </ul>
                </div>
                
                <div>
                    <h5 class="font-semibold mb-4 text-slate-300">Company</h5>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-white">About</a></li>
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h5 class="font-semibold mb-4 text-slate-300">Legal</h5>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white">MIT License</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 flex items-center justify-between">
                <p class="text-sm text-slate-500">© <?= date('Y') ?> Aurora Framework. Powered by ZedCMS.</p>
                <div class="flex items-center gap-4">
                    <?php if ($github = zed_theme_option('social_github')): ?>
                    <a href="<?= htmlspecialchars($github) ?>" class="text-slate-400 hover:text-white" target="_blank">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($twitter = zed_theme_option('social_twitter')): ?>
                    <a href="<?= htmlspecialchars($twitter) ?>" class="text-slate-400 hover:text-white" target="_blank">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>
    
    <?= zed_render_theme_scripts() ?>
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
