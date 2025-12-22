<?php
/**
 * Template Library — Services Grid
 * 
 * Showcase services with icon cards in a responsive grid.
 * Automatically adopts active theme styling via Theme Parts API.
 */
declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$page_title = $post['title'] ?? 'Our Services';
$excerpt = $data['excerpt'] ?? '';
$site_name = zed_get_site_name();

// Include theme's head
if (!zed_include_theme_part('head', ['page_title' => $page_title, 'post' => $post ?? []])) {
    echo "<!DOCTYPE html>\n<html lang=\"en\" class=\"h-full\">\n<head>\n";
    echo "<meta charset=\"utf-8\">\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    echo "<title>" . htmlspecialchars($page_title) . " — " . htmlspecialchars($site_name) . "</title>\n";
    echo zed_tailwind_cdn();
    echo zed_google_fonts();
    echo "</head>\n";
}

// Include theme's header
if (!zed_include_theme_part('header')) {
    echo '<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-full flex flex-col">';
    echo '<header class="bg-white border-b border-slate-200 sticky top-0 z-50">';
    echo '<div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">';
    echo '<a href="' . $base_url . '/" class="font-bold text-xl">' . htmlspecialchars($site_name) . '</a>';
    echo '<a href="' . $base_url . '/" class="text-sm text-slate-600 hover:text-slate-900">← Back to Home</a>';
    echo '</div></header>';
}

// Default services data
$services = [
    [
        'icon' => 'design_services',
        'title' => 'Web Design',
        'description' => 'Beautiful, responsive websites that capture your brand and convert visitors.',
        'color' => 'brand',
    ],
    [
        'icon' => 'code',
        'title' => 'Development',
        'description' => 'Custom web applications built with modern technologies for peak performance.',
        'color' => 'purple-600',
    ],
    [
        'icon' => 'trending_up',
        'title' => 'SEO & Marketing',
        'description' => 'Get found online with data-driven SEO and marketing strategies.',
        'color' => 'green-600',
    ],
    [
        'icon' => 'support_agent',
        'title' => 'Support',
        'description' => '24/7 dedicated support to keep your business running smoothly.',
        'color' => 'orange-500',
    ],
    [
        'icon' => 'cloud',
        'title' => 'Cloud Hosting',
        'description' => 'Lightning-fast, secure hosting with 99.9% uptime guarantee.',
        'color' => 'cyan-600',
    ],
    [
        'icon' => 'security',
        'title' => 'Security',
        'description' => 'Enterprise-grade security to protect your data and customers.',
        'color' => 'red-500',
    ],
];
?>

<main class="flex-1 py-16">
    <!-- Hero Section -->
    <section class="max-w-4xl mx-auto px-6 text-center mb-16">
        <span class="text-brand font-semibold text-sm uppercase tracking-wider">What We Offer</span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mt-2 mb-6">
            <?= htmlspecialchars($page_title) ?>
        </h1>
        <?php if (!empty($excerpt)): ?>
        <p class="text-xl text-slate-600 max-w-2xl mx-auto"><?= htmlspecialchars($excerpt) ?></p>
        <?php else: ?>
        <p class="text-xl text-slate-600 max-w-2xl mx-auto">
            Comprehensive solutions to help your business grow and succeed in the digital world.
        </p>
        <?php endif; ?>
    </section>

    <!-- Editor Content -->
    <?php if (!empty($htmlContent)): ?>
    <section class="max-w-4xl mx-auto px-6 mb-16">
        <div class="prose prose-lg max-w-none text-slate-700">
            <?= $htmlContent ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Services Grid -->
    <section class="max-w-6xl mx-auto px-6 mb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $service): ?>
            <div class="bg-white p-8 rounded-2xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="w-14 h-14 bg-<?= $service['color'] ?>/10 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-<?= $service['color'] ?> text-3xl"><?= $service['icon'] ?></span>
                </div>
                <h3 class="font-bold text-slate-900 text-xl mb-3"><?= htmlspecialchars($service['title']) ?></h3>
                <p class="text-slate-600 leading-relaxed"><?= htmlspecialchars($service['description']) ?></p>
                <a href="<?= $base_url ?>/contact" class="inline-flex items-center gap-1 text-brand font-semibold mt-4 hover:gap-2 transition-all">
                    Learn more 
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <!-- Why Choose Us -->
    <section class="bg-slate-900 py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-brand font-semibold text-sm uppercase tracking-wider">Why Choose Us</span>
                    <h2 class="text-3xl font-bold text-white mt-2 mb-6">
                        The partner you can trust
                    </h2>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand mt-0.5">check_circle</span>
                            <span class="text-slate-300">10+ years of industry experience</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand mt-0.5">check_circle</span>
                            <span class="text-slate-300">500+ successful projects delivered</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand mt-0.5">check_circle</span>
                            <span class="text-slate-300">24/7 dedicated customer support</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand mt-0.5">check_circle</span>
                            <span class="text-slate-300">100% satisfaction guarantee</span>
                        </li>
                    </ul>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-slate-800 p-6 rounded-xl text-center">
                        <div class="text-4xl font-bold text-white mb-1">500+</div>
                        <div class="text-slate-400 text-sm">Projects</div>
                    </div>
                    <div class="bg-slate-800 p-6 rounded-xl text-center">
                        <div class="text-4xl font-bold text-white mb-1">98%</div>
                        <div class="text-slate-400 text-sm">Satisfaction</div>
                    </div>
                    <div class="bg-slate-800 p-6 rounded-xl text-center">
                        <div class="text-4xl font-bold text-white mb-1">24/7</div>
                        <div class="text-slate-400 text-sm">Support</div>
                    </div>
                    <div class="bg-slate-800 p-6 rounded-xl text-center">
                        <div class="text-4xl font-bold text-white mb-1">10+</div>
                        <div class="text-slate-400 text-sm">Years</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA -->
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Ready to get started?</h2>
            <p class="text-slate-600 mb-8">Let's discuss how we can help your business grow.</p>
            <a href="<?= $base_url ?>/contact" class="inline-block bg-brand text-white px-8 py-4 rounded-full font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                Get a Free Quote
            </a>
        </div>
    </section>
</main>

<?php
if (!zed_include_theme_part('footer', ['footer_style' => 'dark'])) {
    echo '<footer class="bg-slate-900 text-white py-12 mt-auto">';
    echo '<div class="max-w-6xl mx-auto px-6 text-center">';
    echo '<p class="text-slate-400 text-sm">© ' . date('Y') . ' ' . htmlspecialchars($site_name) . '</p>';
    echo '</div></footer>';
    Event::trigger('zed_footer');
    echo '</body></html>';
}
?>
