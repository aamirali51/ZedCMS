<?php
/**
 * Aurora Theme — Single Post Template
 * 
 * @package Aurora
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();
$brand_color = zed_theme_option('brand_color', '#6366f1');

// Post data (available from frontend_addon.php)
$title = $post['title'] ?? 'Untitled';
$type = $post['type'] ?? 'post';
$data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
$excerpt = $data['excerpt'] ?? '';
$featured_image = $data['featured_image'] ?? '';
$created_at = $post['created_at'] ?? '';
$publish_date = $created_at ? date('F j, Y', strtotime($created_at)) : '';

// Make post globally accessible for hooks
$GLOBALS['post'] = $post;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — <?= htmlspecialchars($site_name) ?></title>
    
    <?php if ($excerpt): ?>
    <meta name="description" content="<?= htmlspecialchars($excerpt) ?>">
    <?php endif; ?>
    
    <?php if ($featured_image): ?>
    <meta property="og:image" content="<?= htmlspecialchars($featured_image) ?>">
    <?php endif; ?>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: '<?= $brand_color ?>' },
                    fontFamily: { sans: ['Inter', 'system-ui'], serif: ['Georgia', 'serif'] },
                },
            },
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400" rel="stylesheet">
    
    <?= aurora_css_variables() ?>
    
    <style>
        .prose { font-family: Georgia, serif; line-height: 1.8; }
        .prose h2, .prose h3 { font-family: 'Inter', sans-serif; font-weight: 700; }
        .prose p { margin-bottom: 1.5rem; }
        .prose img { border-radius: 0.75rem; }
        .prose a { color: var(--aurora-brand); }
        .prose code { background: #f1f5f9; padding: 0.2em 0.4em; border-radius: 0.25rem; }
    </style>
    
    <?php Event::triggerScoped('zed_head', ['post_type' => $type]); ?>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    
    <!-- Navigation -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 py-4">
            <nav class="flex items-center justify-between">
                <a href="<?= $base_url ?>/" class="text-xl font-bold text-brand flex items-center gap-2">
                    <span class="material-symbols-outlined">auto_awesome</span>
                    <?= htmlspecialchars($site_name) ?>
                </a>
                
                <div class="flex items-center gap-6">
                    <a href="<?= $base_url ?>/" class="text-sm text-slate-600 hover:text-slate-900">← Back to Home</a>
                </div>
            </nav>
        </div>
    </header>
    
    <main class="py-16">
        <article class="max-w-4xl mx-auto px-6">
            
            <!-- Header -->
            <header class="mb-12">
                <?php if ($type !== 'page'): ?>
                <div class="flex items-center gap-3 text-sm text-slate-500 mb-4">
                    <span class="px-2 py-1 bg-brand/10 text-brand rounded font-medium uppercase text-xs">
                        <?= htmlspecialchars($type) ?>
                    </span>
                    <?php if ($publish_date): ?>
                    <span>•</span>
                    <time datetime="<?= htmlspecialchars($created_at) ?>"><?= $publish_date ?></time>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mb-6">
                    <?= htmlspecialchars($title) ?>
                </h1>
                
                <?php if ($excerpt): ?>
                <p class="text-xl text-slate-600 leading-relaxed">
                    <?= htmlspecialchars($excerpt) ?>
                </p>
                <?php endif; ?>
            </header>
            
            <!-- Featured Image -->
            <?php 
            $has_valid_image = $featured_image && 
                strlen($featured_image) > 10 && 
                (str_starts_with($featured_image, 'http') || str_starts_with($featured_image, '/')) &&
                (preg_match('/\.(jpg|jpeg|png|gif|webp|svg)($|\?)/i', $featured_image) || str_contains($featured_image, '/uploads/'));
            ?>
            <?php if ($has_valid_image): ?>
            <figure class="mb-12 -mx-6 md:mx-0">
                <img src="<?= htmlspecialchars($featured_image) ?>" 
                     alt="<?= htmlspecialchars($title) ?>" 
                     class="w-full rounded-2xl shadow-lg">
            </figure>
            <?php endif; ?>
            
            <!-- Before Content Hook -->
            <?= $beforeContent ?? '' ?>
            
            <!-- Content -->
            <div class="zed-content max-w-none">
                <?= $htmlContent ?>
            </div>
            
            <!-- After Content Hook -->
            <?= $afterContent ?? '' ?>
            
            <!-- Share Section -->
            <footer class="mt-16 pt-8 border-t border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-500">
                        Thanks for reading!
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-500">Share:</span>
                        <a href="https://twitter.com/intent/tweet?text=<?= urlencode($title) ?>&url=<?= urlencode($_SERVER['REQUEST_URI'] ?? '') ?>" 
                           class="p-2 text-slate-400 hover:text-slate-600 transition-colors" target="_blank">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    </div>
                </div>
            </footer>
            
        </article>
    </main>
    
    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-12">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <p class="text-slate-400 text-sm">
                © <?= date('Y') ?> <?= htmlspecialchars($site_name) ?> • Powered by <a href="#" class="text-brand hover:underline">Aurora Framework</a>
            </p>
        </div>
    </footer>
    
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
