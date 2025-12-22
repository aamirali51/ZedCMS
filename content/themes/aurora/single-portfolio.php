<?php
/**
 * Aurora Theme — Interactive Portfolio Template
 * Dynamically switches layouts based on content context
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
$excerpt = $data['excerpt'] ?? '';
$featured_image = $data['featured_image'] ?? '';
$created_at = $post['created_at'] ?? '';
$year = $created_at ? date('Y', strtotime($created_at)) : date('Y');

// Intelligent layout detection
// In production, this would be a dropdown in the CMS editor
$lowerTitle = strtolower($title);
$layout = 'default'; // Case Study (Sidebar)

// "SaaS Dashboard" -> Dark Mode, Product Focus
if (str_contains($lowerTitle, 'dashboard') || str_contains($lowerTitle, 'saas') || str_contains($lowerTitle, 'analytics')) {
    $layout = 'saas';
} 
// "Mobile App" or "Portal" -> Clean, App Focus
elseif (str_contains($lowerTitle, 'app') || str_contains($lowerTitle, 'mobile') || str_contains($lowerTitle, 'portal') || str_contains($lowerTitle, 'health')) {
    $layout = 'app';
}

// Mock metadata customization based on layout
if ($layout === 'saas') {
    $client = 'TechFlow Systems';
    $service = 'Product Design & React Dev';
    $stack = ['React', 'D3.js', 'Node.js', 'AWS'];
    $bg_class = 'bg-slate-900 text-white';
} elseif ($layout === 'app') {
    $client = 'MediCare Plus';
    $service = 'Mobile App Development';
    $stack = ['Flutter', 'Firebase', 'iOS', 'Android'];
    $bg_class = 'bg-slate-50 text-slate-900';
} else {
    $client = 'Fashion Co.';
    $service = 'E-Commerce Platform';
    $stack = ['Vite', 'Stripe', 'Tailwind', 'PHP'];
    $bg_class = 'bg-white text-slate-900';
}
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
            darkMode: 'class',
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
        .prose p { margin-bottom: 1.5rem; line-height: 1.8; }
        .prose h2 { font-size: 1.5rem; font-weight: 700; margin-top: 2rem; margin-bottom: 1rem; }
        .prose ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.5rem; }
        .prose img { border-radius: 0.75rem; width: 100%; margin: 2rem 0; }
        
        /* Layout specific overrides */
        <?php if ($layout === 'saas'): ?>
        .prose p, .prose li { color: #cbd5e1; } /* Slate-300 */
        .prose h1, .prose h2, .prose h3, .prose strong { color: #f8fafc; } /* Slate-50 */
        <?php else: ?>
        .prose p, .prose li { color: #334155; }
        .prose h2, .prose h3 { color: #0f172a; }
        <?php endif; ?>
    </style>
    
    <?php Event::triggerScoped('zed_head', ['post_type' => 'portfolio']); ?>
</head>
<body class="<?= $bg_class ?> font-sans antialiased">
    
    <!-- Navigation (Context Aware) -->
    <header class="<?= $layout === 'saas' ? 'bg-slate-900 border-slate-800' : 'bg-white/80 backdrop-blur-md border-slate-200' ?> border-b sticky top-0 z-50 transition-colors">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <nav class="flex items-center justify-between">
                <a href="<?= $base_url ?>/" class="text-xl font-bold <?= $layout === 'saas' ? 'text-white' : 'text-brand' ?> flex items-center gap-2">
                    <span class="material-symbols-outlined">auto_awesome</span>
                    <?= htmlspecialchars($site_name) ?>
                </a>
                
                <div class="flex items-center gap-6">
                    <a href="<?= $base_url ?>/portfolio" class="text-sm font-medium <?= $layout === 'saas' ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-brand' ?> flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">grid_view</span>
                        All Projects
                    </a>
                </div>
            </nav>
        </div>
    </header>
    
    <!-- ======================================================================= -->
    <!-- LAYOUT 1: SAAS / DASHBOARD (Dark, Centered, Product Focus)              -->
    <!-- ======================================================================= -->
    <?php if ($layout === 'saas'): ?>
        <main class="min-h-screen">
            <!-- Hero -->
            <section class="relative pt-20 pb-32 overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-brand/20 via-slate-900 to-slate-900"></div>
                
                <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-brand/20 text-brand rounded-full text-xs font-bold uppercase tracking-wide mb-8 border border-brand/20">
                        <span class="material-symbols-outlined text-sm">rocket_launch</span> SaaS Product
                    </div>
                    
                    <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400">
                        <?= htmlspecialchars($title) ?>
                    </h1>
                    
                    <?php if ($excerpt): ?>
                    <p class="text-xl md:text-2xl text-slate-300 max-w-3xl mx-auto leading-relaxed mb-12">
                        <?= htmlspecialchars($excerpt) ?>
                    </p>
                    <?php endif; ?>
                    
                    <div class="flex flex-wrap justify-center gap-4 text-sm font-medium text-slate-400 mb-16">
                        <span class="px-4 py-2 bg-white/5 rounded-full border border-white/10"><?= htmlspecialchars($client) ?></span>
                        <span class="px-4 py-2 bg-white/5 rounded-full border border-white/10"><?= $year ?></span>
                        <?php foreach ($stack as $tech): ?>
                        <span class="px-4 py-2 bg-brand/10 text-brand rounded-full border border-brand/20"><?= htmlspecialchars($tech) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if ($featured_image): ?>
                    <div class="relative rounded-xl bg-slate-800 p-2 ring-1 ring-white/10 shadow-2xl">
                        <img src="<?= htmlspecialchars($featured_image) ?>" alt="" class="w-full rounded-lg">
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            
            <!-- Content -->
            <section class="py-24 bg-slate-900">
                <div class="max-w-3xl mx-auto px-6">
                    <div class="prose prose-invert prose-lg max-w-none">
                        <?= $htmlContent ?>
                    </div>
                </div>
            </section>
        </main>
    
    <!-- ======================================================================= -->
    <!-- LAYOUT 2: APP / PORTAL (Clean, Split Layout, Trust Focus)               -->
    <!-- ======================================================================= -->
    <?php elseif ($layout === 'app'): ?>
        <main class="bg-slate-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-6 py-12 lg:py-24">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    
                    <!-- Text Side -->
                    <div class="order-2 lg:order-1">
                        <div class="mb-4 text-brand font-bold uppercase tracking-widest text-xs">
                             <?= htmlspecialchars($service) ?>
                        </div>
                        
                        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">
                            <?= htmlspecialchars($title) ?>
                        </h1>
                        
                        <?php if ($excerpt): ?>
                        <p class="text-xl text-slate-600 leading-relaxed mb-8">
                            <?= htmlspecialchars($excerpt) ?>
                        </p>
                        <?php endif; ?>
                        
                        <!-- Metadata Grid -->
                        <div class="grid grid-cols-2 gap-6 mb-10 py-8 border-y border-slate-200">
                            <div>
                                <div class="text-sm text-slate-400 mb-1">Client</div>
                                <div class="font-semibold text-slate-900"><?= htmlspecialchars($client) ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-slate-400 mb-1">Year</div>
                                <div class="font-semibold text-slate-900"><?= $year ?></div>
                            </div>
                            <div class="col-span-2">
                                <div class="text-sm text-slate-400 mb-2">Technologies</div>
                                <div class="flex gap-2">
                                    <?php foreach ($stack as $tech): ?>
                                    <span class="inline-block px-2 py-1 bg-white border border-slate-200 rounded text-xs font-medium text-slate-600 shadow-sm">
                                        <?= htmlspecialchars($tech) ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="prose prose-slate max-w-none text-slate-600">
                            <?= $htmlContent ?>
                        </div>
                    </div>
                    
                    <!-- Image Side (Sticky) -->
                    <div class="order-1 lg:order-2 lg:sticky lg:top-24">
                        <?php if ($featured_image): ?>
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-400 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                            <img src="<?= htmlspecialchars($featured_image) ?>" alt="" class="relative w-full rounded-2xl shadow-xl bg-white p-2">
                        </div>
                        <?php endif; ?>
                        
                        <!-- Mini Gallery Mockup -->
                        <div class="grid grid-cols-3 gap-4 mt-8">
                            <div class="h-24 bg-slate-200 rounded-lg animate-pulse"></div>
                            <div class="h-24 bg-slate-200 rounded-lg animate-pulse delay-75"></div>
                            <div class="h-24 bg-slate-200 rounded-lg animate-pulse delay-150"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    
    <!-- ======================================================================= -->
    <!-- LAYOUT 3: DEFAULT / E-COMMERCE (Sidebar Layout)                         -->
    <!-- ======================================================================= -->
    <?php else: ?>
        <!-- Hero / Header -->
        <header class="bg-slate-50 border-b border-slate-100 py-20">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-brand/10 text-brand rounded-full text-xs font-bold uppercase tracking-wide mb-6">
                        <span class="material-symbols-outlined text-sm">shopping_bag</span> E-Commerce
                    </div>
                    <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                        <?= htmlspecialchars($title) ?>
                    </h1>
                    <?php if ($excerpt): ?>
                    <p class="text-xl text-slate-600 leading-relaxed max-w-2xl">
                        <?= htmlspecialchars($excerpt) ?>
                    </p>
                    <?php endif; ?>
                </div>
                
                <?php if ($featured_image): ?>
                <div class="relative">
                    <img src="<?= htmlspecialchars($featured_image) ?>" alt="" class="w-full rounded-2xl shadow-2xl">
                </div>
                <?php endif; ?>
            </div>
        </header>
        
        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid lg:grid-cols-12 gap-12">
                <!-- Sidebar (Metadata) -->
                <aside class="lg:col-span-4 order-2 lg:order-1">
                    <div class="sticky top-24 space-y-8">
                        <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6 pb-2 border-b border-slate-200">
                                Project Overview
                            </h3>
                            <div class="space-y-6">
                                <div>
                                    <span class="block text-xs text-slate-500 uppercase font-semibold mb-1">Client</span>
                                    <span class="text-base font-medium text-slate-900"><?= htmlspecialchars($client) ?></span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-500 uppercase font-semibold mb-1">Results</span>
                                    <span class="text-base font-medium text-green-600 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">trending_up</span> +300% Sales
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-500 uppercase font-semibold mb-1">Platform</span>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <?php foreach ($stack as $tech): ?>
                                        <span class="px-2 py-1 bg-white border border-slate-200 rounded text-xs font-medium text-slate-600">
                                            <?= htmlspecialchars($tech) ?>
                                        </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                             <div class="mt-8 pt-6 border-t border-slate-200">
                                <a href="#" class="flex items-center justify-center w-full px-6 py-3 bg-brand text-white font-bold rounded-xl hover:opacity-90 transition-opacity">
                                    Visit Store <span class="material-symbols-outlined ml-2 text-sm">storefront</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
                
                <!-- Main Content Area -->
                <article class="lg:col-span-8 order-1 lg:order-2">
                    <div class="prose prose-lg max-w-none">
                        <?= $htmlContent ?>
                    </div>
                </article>
            </div>
        </main>
    <?php endif; ?>
    
    <!-- Footer Global -->
    <footer class="<?= $layout === 'saas' ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200' ?> border-t py-12 mt-12 transition-colors">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
            <p class="<?= $layout === 'saas' ? 'text-slate-500' : 'text-slate-500' ?> text-sm">
                © <?= date('Y') ?> <?= htmlspecialchars($site_name) ?>
            </p>
        </div>
    </footer>
    
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
