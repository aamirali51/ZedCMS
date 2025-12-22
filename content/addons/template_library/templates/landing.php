<?php
/**
 * Template Library — Landing Page
 * 
 * Full-width hero section with CTA buttons and features.
 * Fully self-contained with robust fallbacks for any theme.
 */
declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$page_title = $post['title'] ?? 'Welcome';
$excerpt = $data['excerpt'] ?? 'The modern way to build amazing websites.';
$site_name = zed_get_site_name();

// ═══════════════════════════════════════════════════════════════════════════
// HEAD SECTION
// ═══════════════════════════════════════════════════════════════════════════
if (!zed_include_theme_part('head', ['page_title' => $page_title, 'post' => $post ?? []])):
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> — <?= htmlspecialchars($site_name) ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#6366f1',
                        'brand-dark': '#4f46e5',
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
    
    <?php Event::trigger('zed_head'); ?>
</head>
<body class="bg-white text-slate-900 font-sans antialiased min-h-full flex flex-col">
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════════════════════
     MAIN CONTENT - Landing Page (Full-width layout)
     ═══════════════════════════════════════════════════════════════════════════ -->

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-brand overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-30">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <div class="relative max-w-6xl mx-auto px-6 py-24 md:py-32">
        <div class="max-w-3xl">
            <span class="inline-flex items-center gap-2 bg-white/10 text-white/90 text-sm font-medium px-4 py-2 rounded-full mb-8">
                <span class="material-symbols-outlined text-lg">auto_awesome</span>
                Now available for everyone
            </span>
            
            <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-6">
                <?= htmlspecialchars($page_title) ?>
            </h1>
            
            <p class="text-xl text-white/80 mb-10 max-w-2xl">
                <?= htmlspecialchars($excerpt) ?>
            </p>
            
            <div class="flex flex-wrap gap-4">
                <a href="<?= $base_url ?>/contact" 
                   class="inline-flex items-center gap-2 bg-white text-slate-900 font-bold px-8 py-4 rounded-xl hover:bg-slate-100 transition-all shadow-lg hover:shadow-xl">
                    Get Started Free
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
                <a href="#features" 
                   class="inline-flex items-center gap-2 bg-white/10 text-white font-bold px-8 py-4 rounded-xl hover:bg-white/20 transition-all border border-white/20">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Editor Content (if any) -->
<?php if (!empty($htmlContent)): ?>
<section class="py-16">
    <div class="max-w-4xl mx-auto px-6">
        <div class="prose prose-lg max-w-none">
            <?= $htmlContent ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section id="features" class="py-20 bg-slate-50">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-brand font-semibold text-sm uppercase tracking-wider">Features</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2">Everything You Need</h2>
            <p class="text-slate-600 mt-4 max-w-2xl mx-auto">
                Powerful tools designed to help you succeed, no matter your skill level.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php 
            $features = [
                ['icon' => 'bolt', 'title' => 'Lightning Fast', 'desc' => 'Optimized for speed and performance. Your site loads in milliseconds.', 'color' => 'yellow'],
                ['icon' => 'security', 'title' => 'Secure by Default', 'desc' => 'Enterprise-grade security protects your data and your users.', 'color' => 'green'],
                ['icon' => 'palette', 'title' => 'Beautiful Design', 'desc' => 'Stunning templates that make your content shine.', 'color' => 'purple'],
                ['icon' => 'code', 'title' => 'Developer Friendly', 'desc' => 'Clean, extensible code that developers love to work with.', 'color' => 'blue'],
                ['icon' => 'devices', 'title' => 'Fully Responsive', 'desc' => 'Looks perfect on every device, from mobile to desktop.', 'color' => 'pink'],
                ['icon' => 'support_agent', 'title' => '24/7 Support', 'desc' => 'Our team is always here to help you succeed.', 'color' => 'orange'],
            ];
            foreach ($features as $f):
                $colorClasses = [
                    'yellow' => 'bg-yellow-100 text-yellow-600',
                    'green' => 'bg-green-100 text-green-600',
                    'purple' => 'bg-purple-100 text-purple-600',
                    'blue' => 'bg-blue-100 text-blue-600',
                    'pink' => 'bg-pink-100 text-pink-600',
                    'orange' => 'bg-orange-100 text-orange-600',
                ];
            ?>
            <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm hover:shadow-lg transition-all">
                <div class="w-14 h-14 rounded-xl <?= $colorClasses[$f['color']] ?> flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-2xl"><?= $f['icon'] ?></span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3"><?= $f['title'] ?></h3>
                <p class="text-slate-600"><?= $f['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-brand">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Get Started?</h2>
        <p class="text-white/80 text-lg mb-10">
            Join thousands of happy users building amazing things.
        </p>
        <a href="<?= $base_url ?>/contact" 
           class="inline-flex items-center gap-2 bg-white text-brand font-bold px-10 py-4 rounded-xl hover:bg-slate-100 transition-all shadow-lg">
            Start Your Journey
            <span class="material-symbols-outlined">arrow_forward</span>
        </a>
    </div>
</section>

<?php
// ═══════════════════════════════════════════════════════════════════════════
// FOOTER SECTION
// ═══════════════════════════════════════════════════════════════════════════
if (!zed_include_theme_part('footer', ['footer_style' => 'dark'])):
?>
    <footer class="bg-slate-900 text-white py-12 mt-auto">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <div class="font-bold text-xl mb-2"><?= htmlspecialchars($site_name) ?></div>
                    <p class="text-slate-400 text-sm">© <?= date('Y') ?> All rights reserved.</p>
                </div>
                <div class="flex gap-6">
                    <a href="<?= $base_url ?>/about" class="text-slate-400 hover:text-white transition-colors">About</a>
                    <a href="<?= $base_url ?>/contact" class="text-slate-400 hover:text-white transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </footer>
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
<?php endif; ?>
