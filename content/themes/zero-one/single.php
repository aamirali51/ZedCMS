<?php
/**
 * Zed One Theme - Single Post/Page Template
 * A modern, minimal reading experience
 */

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();

// $post is passed by frontend_addon.php
$data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
$featuredImage = $data['featured_image'] ?? '';
$excerpt = $data['excerpt'] ?? '';
$type = ucfirst($post['type'] ?? 'post');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> — Zed One</title>
    <meta name="description" content="<?php echo htmlspecialchars($excerpt ?: substr($post['plain_text'] ?? '', 0, 160)); ?>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Merriweather', 'Georgia', 'serif'],
                    },
                },
            },
        }
    </script>
    <style>
        /* Article Content Styling */
        .article-content {
            font-family: 'Merriweather', Georgia, serif;
            font-size: 1.125rem;
            line-height: 1.8;
            color: #374151;
        }
        .article-content p {
            margin-bottom: 1.5rem;
        }
        .article-content h1, .article-content h2, .article-content h3, 
        .article-content h4, .article-content h5, .article-content h6 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            color: #111827;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            line-height: 1.3;
        }
        .article-content h2 { font-size: 1.75rem; }
        .article-content h3 { font-size: 1.5rem; }
        .article-content a {
            color: #16a34a;
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .article-content a:hover {
            color: #15803d;
        }
        .article-content blockquote {
            border-left: 4px solid #22c55e;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #6b7280;
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 0 0.5rem 0.5rem 0;
        }
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 1rem;
            margin: 2rem 0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .article-content code {
            background: #f3f4f6;
            padding: 0.2rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.9em;
            color: #be185d;
        }
        .article-content pre {
            background: #1f2937;
            color: #e5e7eb;
            padding: 1.5rem;
            border-radius: 0.75rem;
            overflow-x: auto;
            margin: 2rem 0;
        }
        .article-content pre code {
            background: transparent;
            color: inherit;
            padding: 0;
        }
        .article-content ul, .article-content ol {
            margin: 1.5rem 0;
            padding-left: 1.5rem;
        }
        .article-content li {
            margin-bottom: 0.5rem;
        }
        .article-content ul li::marker {
            color: #22c55e;
        }
        .article-content ol li::marker {
            color: #22c55e;
            font-weight: 600;
        }
        .gradient-text {
            background: linear-gradient(135deg, #22c55e 0%, #10b981 50%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .reading-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #22c55e, #10b981);
            z-index: 100;
            transition: width 0.1s ease;
        }
    </style>
    <?php Event::trigger('zed_head'); ?>
</head>
<body class="bg-white min-h-screen font-sans antialiased">

    <!-- Reading Progress Bar -->
    <div class="reading-progress" id="progressBar" style="width: 0%"></div>

    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="<?php echo $base_url; ?>/" class="flex items-center gap-3 font-bold text-xl text-gray-900 group">
                <span class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-xl flex items-center justify-center font-black shadow-lg shadow-green-500/30">Z</span>
                <span>Zero<span class="text-green-600">One</span></span>
            </a>
            <nav class="flex items-center gap-4">
                <a href="<?php echo $base_url; ?>/" class="text-gray-600 hover:text-gray-900 transition-colors text-sm font-medium">← Back to Home</a>
            </nav>
        </div>
    </header>

    <!-- Hero/Featured Image -->
    <?php if ($featuredImage): ?>
    <div class="relative h-[50vh] min-h-[400px] bg-gray-900 overflow-hidden">
        <img src="<?php echo htmlspecialchars($featuredImage); ?>" 
             alt="<?php echo htmlspecialchars($post['title']); ?>"
             class="absolute inset-0 w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8">
            <div class="max-w-4xl mx-auto">
                <span class="inline-block text-xs font-semibold text-green-400 uppercase tracking-wider bg-green-500/20 px-3 py-1 rounded-full mb-4"><?php echo $type; ?></span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-4">
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>
                <div class="flex items-center gap-4 text-gray-300 text-sm">
                    <span><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                    <span>•</span>
                    <span>5 min read</span>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Title Section (no featured image) -->
    <section class="bg-gradient-to-b from-gray-50 to-white py-16 md:py-24">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <span class="inline-block text-xs font-semibold text-green-600 uppercase tracking-wider bg-green-50 px-3 py-1 rounded-full mb-6"><?php echo $type; ?></span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                <?php echo htmlspecialchars($post['title']); ?>
            </h1>
            <div class="flex items-center justify-center gap-4 text-gray-500 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                        <?php echo strtoupper(substr($post['title'], 0, 1)); ?>
                    </div>
                    <span class="font-medium text-gray-700">Zero Author</span>
                </div>
                <span class="text-gray-300">•</span>
                <time><?php echo date('F j, Y', strtotime($post['created_at'])); ?></time>
                <span class="text-gray-300">•</span>
                <span>5 min read</span>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Article Content -->
    <article class="py-12 md:py-16">
        <div class="max-w-3xl mx-auto px-6">
            <?php if ($excerpt && !$featuredImage): ?>
            <p class="text-xl text-gray-600 mb-8 pb-8 border-b border-gray-100 leading-relaxed">
                <?php echo htmlspecialchars($excerpt); ?>
            </p>
            <?php endif; ?>
            
            <div class="article-content">
                <?php echo render_blocks($data['content'] ?? []); ?>
            </div>
        </div>
    </article>

    <!-- Share & Actions -->
    <section class="border-t border-gray-100 py-8">
        <div class="max-w-3xl mx-auto px-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Share this:</span>
                    <button onclick="navigator.clipboard.writeText(window.location.href); this.textContent='Copied!'" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                        Copy Link
                    </button>
                </div>
                <a href="<?php echo $base_url; ?>/" class="inline-flex items-center gap-2 text-green-600 font-medium hover:text-green-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to all posts
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <span class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-xl flex items-center justify-center font-black">Z</span>
                <span class="text-white font-bold">Zero<span class="text-green-500">One</span></span>
            </div>
            <p class="text-sm">A modern, minimal reading experience.</p>
            <p class="text-xs text-gray-600 mt-2">&copy; <?php echo date('Y'); ?> Zed One Theme</p>
        </div>
    </footer>
    
    <!-- Reading Progress Script -->
    <script>
        window.addEventListener('scroll', () => {
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrolled = (window.scrollY / docHeight) * 100;
            document.getElementById('progressBar').style.width = scrolled + '%';
        });
    </script>
    
    <!-- Theme: Zed One by AntigravityCMS -->
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
