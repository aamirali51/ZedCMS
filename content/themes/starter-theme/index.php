<?php
/**
 * Zed CMS - Starter Theme: Homepage Template
 * 
 * This template displays the site homepage with a list of recent posts.
 * 
 * Variables available:
 * - $posts: Array of recent posts (if provided)
 * - $base_url: The base URL of the site
 */

use Core\Router;

$base_url = Router::getBasePath();

// Fetch recent published posts using helper function (NO direct SQL in themes!)
$posts = function_exists('zed_get_latest_posts') ? zed_get_latest_posts(10) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zed CMS — A Modern Content Management System</title>
    <meta name="description" content="Welcome to Zed CMS, a lightweight and modern content management system.">
    
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
                },
            },
        }
    </script>
    <style>
        /* Zero Menu Styles */
        .zero-menu { display: flex; align-items: center; gap: 1.5rem; list-style: none; margin: 0; padding: 0; }
        .zero-menu li { position: relative; }
        .zero-menu a { color: #111827; text-decoration: none; font-weight: 500; transition: color 0.2s; }
        .zero-menu a:hover { color: #4f46e5; }
        
        /* Dropdown support */
        .zero-menu .sub-menu { display: none; position: absolute; top: 100%; left: 0; background: white; min-width: 200px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-radius: 0.5rem; padding: 0.5rem 0; border: 1px solid #e5e7eb; z-index: 50; }
        .zero-menu .has-children:hover .sub-menu { display: block; }
        .zero-menu .sub-menu li { display: block; }
        .zero-menu .sub-menu a { display: block; padding: 0.5rem 1rem; }
        .zero-menu .sub-menu a:hover { background-color: #f3f4f6; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-6 py-5 flex items-center justify-between">
            <a href="<?php echo $base_url; ?>/" class="flex items-center gap-3 font-bold text-xl text-gray-900">
                <span class="w-10 h-10 bg-black text-white rounded-lg flex items-center justify-center">Z</span>
                <span>Zed CMS</span>
            </a>
            <nav class="flex items-center gap-6 text-sm font-medium">
                <?php 
                if (function_exists('render_menu')) {
                    $menuHtml = render_menu('header');
                    if ($menuHtml) {
                        echo $menuHtml;
                    } else {
                        // Fallback
                        ?>
                        <a href="<?php echo $base_url; ?>/" class="text-gray-900">Home</a>
                        <a href="<?php echo $base_url; ?>/admin" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Admin Panel</a>
                        <?php
                    }
                }
                ?>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white py-20">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
                Welcome to Zed CMS
            </h1>
            <p class="text-xl md:text-2xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                A lightweight, event-driven content management system built with modern PHP and React.
            </p>
            <div class="flex items-center justify-center gap-4">
                <a href="<?php echo $base_url; ?>/admin" class="px-6 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Get Started
                </a>
                <a href="#posts" class="px-6 py-3 border-2 border-white/30 text-white rounded-lg font-semibold hover:bg-white/10 transition-colors">
                    View Content
                </a>
            </div>
        </div>
    </section>

    <!-- Posts Section -->
    <section id="posts" class="py-16">
        <div class="max-w-5xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Recent Content</h2>
            
            <?php if (!empty($posts)): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($posts as $post): 
                    $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
                    $excerpt = $data['excerpt'] ?? '';
                    $featuredImage = $data['featured_image'] ?? '';
                    $type = ucfirst($post['type'] ?? 'post');
                ?>
                <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                    <?php if ($featuredImage): ?>
                    <div class="aspect-video bg-gray-100 overflow-hidden">
                        <img src="<?php echo htmlspecialchars($featuredImage); ?>" 
                             alt="<?php echo htmlspecialchars($post['title']); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <?php else: ?>
                    <div class="aspect-video bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        <span class="text-4xl font-bold text-indigo-300"><?php echo strtoupper(substr($post['title'], 0, 1)); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-medium text-indigo-600 uppercase tracking-wide"><?php echo $type; ?></span>
                            <span class="text-gray-300">•</span>
                            <time class="text-xs text-gray-500"><?php echo date('M j, Y', strtotime($post['created_at'])); ?></time>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">
                            <a href="<?php echo $base_url . '/' . htmlspecialchars($post['slug']); ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <?php if ($excerpt): ?>
                        <p class="text-sm text-gray-600 line-clamp-2"><?php echo htmlspecialchars($excerpt); ?></p>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No content yet</h3>
                <p class="text-gray-600 mb-4">Create your first page or post to see it here.</p>
                <a href="<?php echo $base_url; ?>/admin/content/edit?new=true" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Content
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-2 mb-4">
                <span class="w-8 h-8 bg-white text-gray-900 rounded flex items-center justify-center font-bold text-sm">Z</span>
                <span class="text-white font-semibold">Zed CMS</span>
            </div>
            <p class="text-sm">A modern, lightweight content management system.</p>
            <p class="text-sm mt-2">Built with PHP, React, and ❤️</p>
        </div>
    </footer>

</body>
</html>
