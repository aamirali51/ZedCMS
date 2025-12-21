<?php
/**
 * Zed CMS - Starter Theme: Single Post/Page Template
 * 
 * This template is used to display individual content items.
 * 
 * Variables available:
 * - $post: Array containing the post data (id, title, slug, type, data, created_at, updated_at)
 * - $htmlContent: The rendered HTML content from BlockNote JSON
 * - $base_url: The base URL of the site
 * 
 * Usage by frontend_addon.php:
 *   include 'content/themes/starter-theme/single.php';
 */

use Core\Router;

// Get base URL for links
$base_url = Router::getBasePath();

// Extract data from post
$data = is_string($post['data']) ? json_decode($post['data'], true) : ($post['data'] ?? []);
$featuredImage = $data['featured_image'] ?? '';
$excerpt = $data['excerpt'] ?? '';
$status = $data['status'] ?? 'draft';
$publishDate = $post['created_at'] ? date('F j, Y', strtotime($post['created_at'])) : '';
$type = ucfirst($post['type'] ?? 'page');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> — Zed CMS</title>
    <meta name="description" content="<?php echo htmlspecialchars($excerpt); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
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
        /* Article Content Styles */
        .article-content {
            font-family: 'Merriweather', Georgia, serif;
            font-size: 1.125rem;
            line-height: 1.9;
            color: #374151;
        }
        .article-content p {
            margin-bottom: 1.75rem;
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
        .article-content h1 { font-size: 2.25rem; }
        .article-content h2 { font-size: 1.75rem; }
        .article-content h3 { font-size: 1.5rem; }
        .article-content h4 { font-size: 1.25rem; }
        .article-content ul, .article-content ol {
            margin-bottom: 1.75rem;
            padding-left: 1.5rem;
        }
        .article-content li {
            margin-bottom: 0.5rem;
        }
        .article-content ul li { list-style-type: disc; }
        .article-content ol li { list-style-type: decimal; }
        .article-content a {
            color: #4f46e5;
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .article-content a:hover {
            color: #4338ca;
        }
        .article-content blockquote {
            border-left: 4px solid #4f46e5;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #6b7280;
        }
        .article-content pre {
            background: #1f2937;
            color: #f9fafb;
            padding: 1.25rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1.75rem 0;
            font-size: 0.9rem;
        }
        .article-content code {
            font-family: 'Fira Code', 'Consolas', monospace;
        }
        .article-content :not(pre) > code {
            background: #f3f4f6;
            padding: 0.2em 0.4em;
            border-radius: 0.25rem;
            color: #dc2626;
            font-size: 0.9em;
        }
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 2rem auto;
        }
        .article-content figure {
            margin: 2rem 0;
        }
        .article-content figcaption {
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.75rem;
            font-style: italic;
        }
        .article-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.75rem 0;
        }
        .article-content th, .article-content td {
            border: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            text-align: left;
        }
        .article-content th {
            background: #f9fafb;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-white min-h-screen">

    <!-- Header -->
    <header class="border-b border-gray-100">
        <div class="max-w-3xl mx-auto px-6 py-5 flex items-center justify-between">
            <a href="<?php echo $base_url; ?>/" class="flex items-center gap-2 font-sans font-bold text-xl text-gray-900 hover:text-indigo-600 transition-colors">
                <span class="w-8 h-8 bg-black text-white rounded flex items-center justify-center text-sm">Z</span>
                <span>Zero</span>
            </a>
            <nav class="flex items-center gap-6 text-sm font-medium">
                <a href="<?php echo $base_url; ?>/" class="text-gray-600 hover:text-gray-900 transition-colors">Home</a>
                <a href="<?php echo $base_url; ?>/admin" class="text-indigo-600 hover:text-indigo-700 transition-colors">Admin</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-6 py-12">
        <article>
            <!-- Article Header -->
            <header class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full uppercase tracking-wide">
                        <?php echo htmlspecialchars($type); ?>
                    </span>
                    <?php if ($publishDate): ?>
                    <span class="text-gray-400 text-sm">•</span>
                    <time class="text-gray-500 text-sm"><?php echo $publishDate; ?></time>
                    <?php endif; ?>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight font-sans">
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>
                <?php if ($excerpt): ?>
                <p class="mt-4 text-xl text-gray-600 leading-relaxed">
                    <?php echo htmlspecialchars($excerpt); ?>
                </p>
                <?php endif; ?>
            </header>

            <!-- Featured Image -->
            <?php if ($featuredImage): ?>
            <figure class="mb-10 -mx-6 md:mx-0">
                <img 
                    src="<?php echo htmlspecialchars($featuredImage); ?>" 
                    alt="<?php echo htmlspecialchars($post['title']); ?>"
                    class="w-full rounded-lg shadow-lg"
                >
            </figure>
            <?php endif; ?>

            <!-- Article Content -->
            <div class="article-content">
                <?php echo $htmlContent; ?>
            </div>
        </article>
    </main>

    <!-- Divider -->
    <hr class="max-w-3xl mx-auto border-gray-200">

    <!-- Footer -->
    <footer class="max-w-3xl mx-auto px-6 py-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
            <p>Powered by <strong class="text-gray-700">Zed CMS</strong></p>
            <div class="flex items-center gap-4">
                <a href="<?php echo $base_url; ?>/" class="hover:text-gray-700 transition-colors">Home</a>
                <a href="<?php echo $base_url; ?>/admin" class="hover:text-gray-700 transition-colors">Admin Panel</a>
            </div>
        </div>
    </footer>

</body>
</html>
