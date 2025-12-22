<?php
/**
 * Aurora Theme — Single Testimonial Template
 * Focused layout for client reviews and quotes
 * 
 * @package Aurora
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();
$brand_color = zed_theme_option('brand_color', '#6366f1');

// Post data
$title = $post['title'] ?? 'Untitled';
$data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
$excerpt = $data['excerpt'] ?? ''; // Currently used for Client Title/Company
$featured_image = $data['featured_image'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — <?= htmlspecialchars($site_name) ?></title>
    
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
    
    <style>
        /* Typography overrides for the quote */
        .prose p { font-size: 1.5rem; line-height: 1.6; color: #1e293b; font-weight: 500; text-align: center; }
        .prose strong { color: var(--aurora-brand); }
    </style>
    
    <?php Event::triggerScoped('zed_head', ['post_type' => 'testimonial']); ?>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased flex flex-col min-h-screen">
    
    <!-- Navigation -->
    <header class="absolute top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
            <a href="<?= $base_url ?>/" class="text-xl font-bold text-slate-900/50 hover:text-brand flex items-center gap-2 transition-colors">
                <span class="material-symbols-outlined">auto_awesome</span>
                <?= htmlspecialchars($site_name) ?>
            </a>
            
            <a href="<?= $base_url ?>/testimonials" class="text-sm font-medium text-slate-500 hover:text-brand transition-colors">
                View All Testimonials
            </a>
        </div>
    </header>
    
    <main class="flex-grow flex items-center justify-center py-20 px-6">
        <div class="max-w-4xl w-full">
            
            <!-- Quote Card -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 md:p-16 relative overflow-hidden">
                
                <!-- Decorative Quote Icon -->
                <div class="absolute top-8 left-8 text-brand/10 select-none pointer-events-none">
                    <span class="material-symbols-outlined text-[120px] leading-none">format_quote</span>
                </div>
                
                <div class="relative z-10 flex flex-col items-center text-center">
                    
                    <!-- Avatar -->
                    <div class="mb-8 relative">
                        <?php if ($featured_image): ?>
                            <img src="<?= htmlspecialchars($featured_image) ?>" alt="<?= htmlspecialchars($title) ?>" class="w-24 h-24 rounded-full object-cover border-4 border-slate-50 shadow-lg">
                        <?php else: ?>
                            <div class="w-24 h-24 rounded-full bg-brand/10 flex items-center justify-center text-brand border-4 border-slate-50 shadow-lg">
                                <span class="material-symbols-outlined text-4xl">person</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="absolute -bottom-2 -right-2 bg-brand text-white p-1.5 rounded-full shadow-sm">
                            <span class="material-symbols-outlined text-sm">verified</span>
                        </div>
                    </div>
                    
                    <!-- Content (The Quote) -->
                    <div class="prose prose-lg max-w-2xl mb-10">
                        <?= $htmlContent ?>
                    </div>
                    
                    <!-- Client Details -->
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 mb-1">
                            <?= htmlspecialchars($title) ?>
                        </h1>
                        <?php if ($excerpt): ?>
                        <p class="text-brand font-medium tracking-wide text-sm uppercase">
                            <?= htmlspecialchars($excerpt) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    
                </div>
                
            </div>
            
            <!-- Back Link -->
            <div class="text-center mt-12">
                <a href="<?= $base_url ?>/contact" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-full font-medium hover:bg-brand transition-colors shadow-lg shadow-brand/20">
                    Start Your Own Success Story
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
            
        </div>
    </main>
    
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
