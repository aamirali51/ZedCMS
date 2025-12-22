<?php
/**
 * Template Library — About Page
 * 
 * Clean about page with team section and company story.
 * Fully self-contained with robust fallbacks for any theme.
 */
declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$page_title = $post['title'] ?? 'About Us';
$excerpt = $data['excerpt'] ?? 'Learn about our mission, our team, and what drives us forward.';
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
<?php endif; ?>

<?php
// ═══════════════════════════════════════════════════════════════════════════
// HEADER SECTION  
// ═══════════════════════════════════════════════════════════════════════════
if (!zed_include_theme_part('header')):
?>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 text-slate-900 font-sans antialiased min-h-full flex flex-col">
    <!-- Simple Header -->
    <header class="bg-white/80 backdrop-blur-sm border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="<?= $base_url ?>/" class="font-bold text-xl text-slate-900 hover:text-brand transition-colors">
                <?= htmlspecialchars($site_name) ?>
            </a>
            <a href="<?= $base_url ?>/" class="text-sm text-slate-600 hover:text-slate-900 flex items-center gap-1 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Back to Home
            </a>
        </div>
    </header>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════════════════════
     MAIN CONTENT - About Page (Always rendered, theme-independent)
     ═══════════════════════════════════════════════════════════════════════════ -->
<main class="flex-1 py-16">
    <div class="max-w-6xl mx-auto px-6">
        
        <!-- Page Header -->
        <header class="mb-16 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mb-4">
                <?= htmlspecialchars($page_title) ?>
            </h1>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto"><?= htmlspecialchars($excerpt) ?></p>
        </header>

        <!-- Editor Content (if any) -->
        <?php if (!empty($htmlContent)): ?>
        <div class="prose prose-lg max-w-none text-slate-700 mb-16">
            <?= $htmlContent ?>
        </div>
        <?php endif; ?>

        <!-- Our Story Section -->
        <section class="mb-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-brand font-semibold text-sm uppercase tracking-wider">Our Story</span>
                    <h2 class="text-3xl font-bold text-slate-900 mt-2 mb-6">Building Something Meaningful</h2>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        We started with a simple idea: make powerful tools accessible to everyone. 
                        What began as a small project has grown into something we're incredibly proud of.
                    </p>
                    <p class="text-slate-600 leading-relaxed">
                        Every feature we build, every decision we make, is guided by our commitment 
                        to creating exceptional experiences for our users.
                    </p>
                </div>
                <div class="bg-gradient-to-br from-brand/10 to-purple-500/10 rounded-2xl p-8 flex items-center justify-center">
                    <div class="text-center">
                        <span class="material-symbols-outlined text-6xl text-brand mb-4">rocket_launch</span>
                        <h3 class="text-2xl font-bold text-slate-900">Since 2024</h3>
                        <p class="text-slate-600">Empowering creators worldwide</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Values Section -->
        <section class="mb-20">
            <div class="text-center mb-12">
                <span class="text-brand font-semibold text-sm uppercase tracking-wider">What We Believe</span>
                <h2 class="text-3xl font-bold text-slate-900 mt-2">Our Core Values</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-2xl text-blue-600">lightbulb</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Innovation</h3>
                    <p class="text-slate-600">We constantly push boundaries to deliver cutting-edge solutions that make a real difference.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-2xl text-green-600">handshake</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Trust</h3>
                    <p class="text-slate-600">We build lasting relationships through transparency, reliability, and genuine care for our community.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-2xl text-purple-600">star</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Excellence</h3>
                    <p class="text-slate-600">Good enough is never enough. We strive for excellence in everything we create.</p>
                </div>
            </div>
        </section>
        
        <!-- Team Section -->
        <section class="mb-20">
            <div class="text-center mb-12">
                <span class="text-brand font-semibold text-sm uppercase tracking-wider">The Team</span>
                <h2 class="text-3xl font-bold text-slate-900 mt-2">Meet the People Behind the Magic</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php 
                $team = [
                    ['name' => 'Alex Johnson', 'role' => 'Founder & CEO', 'color' => 'brand'],
                    ['name' => 'Sarah Chen', 'role' => 'Head of Design', 'color' => 'pink-500'],
                    ['name' => 'Mike Roberts', 'role' => 'Lead Developer', 'color' => 'green-500'],
                    ['name' => 'Emma Wilson', 'role' => 'Customer Success', 'color' => 'orange-500'],
                ];
                foreach ($team as $member):
                    $initials = implode('', array_map(fn($n) => $n[0], explode(' ', $member['name'])));
                ?>
                <div class="text-center">
                    <div class="w-24 h-24 bg-<?= $member['color'] ?> rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                        <?= $initials ?>
                    </div>
                    <h4 class="font-bold text-slate-900"><?= $member['name'] ?></h4>
                    <p class="text-sm text-slate-500"><?= $member['role'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!-- Stats Section -->
        <section class="bg-slate-900 rounded-2xl p-12 text-white">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">10K+</div>
                    <div class="text-slate-400">Happy Users</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">50+</div>
                    <div class="text-slate-400">Countries</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">99%</div>
                    <div class="text-slate-400">Uptime</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <div class="text-slate-400">Support</div>
                </div>
            </div>
        </section>
        
    </div>
</main>

<?php
// ═══════════════════════════════════════════════════════════════════════════
// FOOTER SECTION
// ═══════════════════════════════════════════════════════════════════════════
if (!zed_include_theme_part('footer', ['footer_style' => 'dark'])):
?>
    <footer class="bg-slate-900 text-white py-12 mt-auto">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p class="text-slate-400 text-sm">
                © <?= date('Y') ?> <?= htmlspecialchars($site_name) ?>. All rights reserved.
            </p>
        </div>
    </footer>
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
<?php endif; ?>
