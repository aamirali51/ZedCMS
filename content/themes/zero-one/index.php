<?php
/**
 * Zed One Theme - Homepage
 * A modern, minimal theme with structural branding
 */

use Core\Router;
use Core\Database;
use Core\Event;

$base_url = Router::getBasePath();

// Fetch recent published posts
$posts = [];
try {
    $db = Database::getInstance();
    $posts = $db->query(
        "SELECT * FROM zero_content 
         WHERE JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
         ORDER BY created_at DESC 
         LIMIT 12"
    );
} catch (Exception $e) {
    $posts = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zed One — Modern Minimalist Theme</title>
    <meta name="description" content="A clean, minimal reading experience powered by Zed CMS.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'zero': {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        }
                    }
                },
            },
            darkMode: 'class',
        }
    </script>
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #22c55e 0%, #10b981 50%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2322c55e' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        /* Dynamic Menu Styles */
        .zed-menu, .nav-horizontal {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .zed-menu li, .nav-horizontal li {
            position: relative;
        }
        .zed-menu a, .nav-horizontal a {
            color: #4b5563;
            text-decoration: none;
            transition: color 0.2s ease;
            font-weight: 500;
        }
        .zed-menu a:hover, .nav-horizontal a:hover {
            color: #111827;
        }
        /* Dropdown styles */
        .zed-menu .sub-menu, .nav-horizontal .sub-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 180px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            z-index: 100;
            flex-direction: column;
            gap: 0;
        }
        .zed-menu .has-children:hover > .sub-menu,
        .nav-horizontal .has-children:hover > .sub-menu {
            display: flex;
        }
        .zed-menu .sub-menu li, .nav-horizontal .sub-menu li {
            width: 100%;
        }
        .zed-menu .sub-menu a, .nav-horizontal .sub-menu a {
            display: block;
            padding: 0.5rem 1rem;
            color: #4b5563;
        }
        .zed-menu .sub-menu a:hover, .nav-horizontal .sub-menu a:hover {
            background: #f3f4f6;
            color: #111827;
        }
    </style>
    <?php Event::trigger('zed_head'); ?>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">

    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="<?php echo $base_url; ?>/" class="flex items-center gap-3 font-bold text-xl text-gray-900 group">
                <span class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-xl flex items-center justify-center font-black shadow-lg shadow-green-500/30 group-hover:shadow-green-500/50 transition-shadow">Z</span>
                <span>Zero<span class="text-green-600">One</span></span>
            </a>
            <nav class="flex items-center gap-6 text-sm font-medium">
                <?php 
                // Try to render a menu dynamically
                $menuHtml = '';
                
                // Option 1: Try "Main Menu" by name
                if (function_exists('zed_menu')) {
                    $menuHtml = zed_menu('Main Menu', ['class' => 'nav-horizontal']);
                }
                
                // Option 2: If no "Main Menu", try first available menu
                if (empty($menuHtml) && function_exists('zed_primary_menu')) {
                    $menuHtml = zed_primary_menu(['class' => 'nav-horizontal']);
                }
                
                // Option 3: Render menu HTML if found
                if (!empty($menuHtml)) {
                    echo $menuHtml;
                } else {
                    // Fallback: Static links if no menus
                    ?>
                    <a href="<?php echo $base_url; ?>/" class="text-gray-600 hover:text-gray-900 transition-colors">Home</a>
                    <a href="<?php echo $base_url; ?>/admin" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">Admin Panel</a>
                    <?php
                }
                ?>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 hero-pattern opacity-30"></div>
        <div class="absolute top-20 left-10 w-72 h-72 bg-green-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative max-w-4xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/20 text-green-400 rounded-full text-sm font-medium mb-6 border border-green-500/30">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                Powered by Zed CMS
            </div>
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight">
                <span class="gradient-text">Zed One</span>
                <br>
                <span class="text-gray-300">Minimal. Fast. Beautiful.</span>
            </h1>
            <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                A clean reading experience with structural branding. Built for speed, designed for elegance.
            </p>
            <div class="flex items-center justify-center gap-4 flex-wrap">
                <a href="#posts" class="px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg shadow-green-500/30 hover:shadow-green-500/50">
                    Explore Content
                </a>
                <a href="<?php echo $base_url; ?>/admin" class="px-8 py-4 border border-gray-600 text-gray-300 rounded-xl font-semibold hover:bg-white/5 hover:border-gray-500 transition-all">
                    Admin Panel →
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-6 py-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl font-bold text-gray-900"><?php echo count($posts); ?></div>
                    <div class="text-sm text-gray-500">Published Posts</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">∞</div>
                    <div class="text-sm text-gray-500">Possibilities</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">0</div>
                    <div class="text-sm text-gray-500">Bloatware</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-600">100%</div>
                    <div class="text-sm text-gray-500">Open Source</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Posts Section -->
    <section id="posts" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Latest Content</h2>
                    <p class="text-gray-600">Fresh posts and pages from your CMS</p>
                </div>
                <a href="<?php echo $base_url; ?>/admin/editor?new=true" class="hidden md:flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Post
                </a>
            </div>
            
            <?php if (!empty($posts)): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($posts as $index => $post): 
                    $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
                    $excerpt = $data['excerpt'] ?? '';
                    if (!$excerpt && isset($post['plain_text'])) {
                        $excerpt = substr($post['plain_text'], 0, 120) . '...';
                    }
                    $featuredImage = $data['featured_image'] ?? '';
                    $type = ucfirst($post['type'] ?? 'post');
                    $isFirst = $index === 0;
                ?>
                <article class="<?php echo $isFirst ? 'md:col-span-2 lg:col-span-2' : ''; ?> bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover group">
                    <?php if ($featuredImage): ?>
                    <div class="<?php echo $isFirst ? 'aspect-[21/9]' : 'aspect-video'; ?> bg-gray-100 overflow-hidden">
                        <img src="<?php echo htmlspecialchars($featuredImage); ?>" 
                             alt="<?php echo htmlspecialchars($post['title']); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <?php else: ?>
                    <div class="<?php echo $isFirst ? 'aspect-[21/9]' : 'aspect-video'; ?> bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 hero-pattern opacity-50"></div>
                        <span class="relative text-6xl font-black text-green-200"><?php echo strtoupper(substr($post['title'], 0, 1)); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="p-6 <?php echo $isFirst ? 'md:p-8' : ''; ?>">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-xs font-semibold text-green-600 uppercase tracking-wider bg-green-50 px-2.5 py-1 rounded-full"><?php echo $type; ?></span>
                            <time class="text-xs text-gray-400"><?php echo date('M j, Y', strtotime($post['created_at'])); ?></time>
                        </div>
                        <h3 class="<?php echo $isFirst ? 'text-2xl' : 'text-lg'; ?> font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors leading-snug">
                            <a href="<?php echo $base_url . '/' . htmlspecialchars($post['slug']); ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <?php if ($excerpt): ?>
                        <p class="text-gray-600 <?php echo $isFirst ? 'text-base' : 'text-sm'; ?> line-clamp-2 mb-4"><?php echo htmlspecialchars($excerpt); ?></p>
                        <?php endif; ?>
                        <a href="<?php echo $base_url . '/' . htmlspecialchars($post['slug']); ?>" class="inline-flex items-center gap-1 text-sm font-medium text-green-600 hover:text-green-700 group/link">
                            Read more 
                            <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">No content yet</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">Create your first page or post from the admin panel to see it displayed here.</p>
                <a href="<?php echo $base_url; ?>/admin/editor?new=true" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg shadow-green-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Your First Post
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-gray-900 to-gray-800 py-16">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to build something amazing?</h2>
            <p class="text-gray-400 mb-8">Zed CMS gives you the power of a full CMS with the simplicity you deserve.</p>
            <a href="<?php echo $base_url; ?>/admin" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-gray-900 rounded-xl font-semibold hover:bg-gray-100 transition-colors">
                Open Admin Panel
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-16">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-xl flex items-center justify-center font-black">Z</span>
                    <div>
                        <div class="text-white font-bold">Zero<span class="text-green-500">One</span></div>
                        <div class="text-xs text-gray-500">Theme by AntigravityCMS</div>
                    </div>
                </div>
                <div class="text-sm text-center md:text-right">
                    <p>&copy; <?php echo date('Y'); ?> Zed One Theme. Built with Zed CMS.</p>
                    <p class="text-gray-600 mt-1">Minimal. Fast. Beautiful.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Theme: Zed One by AntigravityCMS -->
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
