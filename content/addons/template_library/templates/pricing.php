<?php
/**
 * Template Library — Pricing Table
 * 
 * Beautiful pricing cards with feature comparison.
 * Automatically adopts active theme styling via Theme Parts API.
 */
declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$page_title = $post['title'] ?? 'Pricing';
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

// Default pricing data
$plans = [
    [
        'name' => 'Starter',
        'price' => 19,
        'period' => 'month',
        'description' => 'Perfect for individuals and small projects.',
        'features' => [
            'Up to 5 projects',
            '10 GB storage',
            'Basic analytics',
            'Email support',
            'API access',
        ],
        'cta' => 'Start Free Trial',
        'popular' => false,
    ],
    [
        'name' => 'Professional',
        'price' => 49,
        'period' => 'month',
        'description' => 'Best for growing businesses and teams.',
        'features' => [
            'Unlimited projects',
            '100 GB storage',
            'Advanced analytics',
            'Priority support',
            'API access',
            'Custom integrations',
            'Team collaboration',
        ],
        'cta' => 'Start Free Trial',
        'popular' => true,
    ],
    [
        'name' => 'Enterprise',
        'price' => 149,
        'period' => 'month',
        'description' => 'For large organizations with custom needs.',
        'features' => [
            'Everything in Pro',
            'Unlimited storage',
            'Custom analytics',
            'Dedicated support',
            'SSO & SAML',
            'SLA guarantee',
            'Custom contracts',
            'On-premise option',
        ],
        'cta' => 'Contact Sales',
        'popular' => false,
    ],
];
?>

<main class="flex-1 py-16">
    <!-- Hero Section -->
    <section class="max-w-4xl mx-auto px-6 text-center mb-16">
        <span class="text-brand font-semibold text-sm uppercase tracking-wider">Pricing</span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mt-2 mb-6">
            <?= htmlspecialchars($page_title) ?>
        </h1>
        <?php if (!empty($excerpt)): ?>
        <p class="text-xl text-slate-600 max-w-2xl mx-auto"><?= htmlspecialchars($excerpt) ?></p>
        <?php else: ?>
        <p class="text-xl text-slate-600 max-w-2xl mx-auto">
            Simple, transparent pricing. No hidden fees. Choose the plan that works for you.
        </p>
        <?php endif; ?>
        
        <!-- Billing Toggle -->
        <div class="flex items-center justify-center gap-4 mt-8">
            <span class="text-slate-600">Monthly</span>
            <button class="relative w-14 h-8 bg-brand rounded-full p-1 transition-colors" id="billing-toggle">
                <span class="block w-6 h-6 bg-white rounded-full shadow-md transform transition-transform" id="toggle-knob"></span>
            </button>
            <span class="text-slate-600">Annual <span class="text-brand font-semibold">(Save 20%)</span></span>
        </div>
    </section>

    <!-- Editor Content -->
    <?php if (!empty($htmlContent)): ?>
    <section class="max-w-4xl mx-auto px-6 mb-16">
        <div class="prose prose-lg max-w-none text-slate-700">
            <?= $htmlContent ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Pricing Cards -->
    <section class="max-w-6xl mx-auto px-6 mb-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($plans as $plan): ?>
            <div class="relative bg-white rounded-2xl border-2 <?= $plan['popular'] ? 'border-brand shadow-xl' : 'border-slate-200' ?> p-8 flex flex-col">
                <?php if ($plan['popular']): ?>
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="bg-brand text-white text-xs font-bold px-4 py-1.5 rounded-full">Most Popular</span>
                </div>
                <?php endif; ?>
                
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2"><?= htmlspecialchars($plan['name']) ?></h3>
                    <p class="text-slate-500 text-sm"><?= htmlspecialchars($plan['description']) ?></p>
                </div>
                
                <div class="mb-6">
                    <span class="text-5xl font-extrabold text-slate-900">$<?= $plan['price'] ?></span>
                    <span class="text-slate-500">/<?= $plan['period'] ?></span>
                </div>
                
                <ul class="space-y-3 mb-8 flex-grow">
                    <?php foreach ($plan['features'] as $feature): ?>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-brand text-lg mt-0.5">check_circle</span>
                        <span class="text-slate-600"><?= htmlspecialchars($feature) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                
                <a href="<?= $base_url ?>/contact" 
                   class="block text-center py-3.5 px-6 rounded-lg font-bold transition-all <?= $plan['popular'] ? 'bg-brand text-white hover:bg-brand-dark' : 'bg-slate-100 text-slate-900 hover:bg-slate-200' ?>">
                    <?= htmlspecialchars($plan['cta']) ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <!-- Trust Badges -->
    <section class="max-w-4xl mx-auto px-6 mb-20">
        <div class="bg-white rounded-2xl border border-slate-200 p-8">
            <div class="flex flex-wrap justify-center items-center gap-8 text-slate-400">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl">verified</span>
                    <span class="font-medium">30-day money back</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl">lock</span>
                    <span class="font-medium">Secure payments</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl">support_agent</span>
                    <span class="font-medium">24/7 support</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl">cancel</span>
                    <span class="font-medium">Cancel anytime</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="max-w-3xl mx-auto px-6">
        <h2 class="text-2xl font-bold text-slate-900 text-center mb-8">Questions about pricing?</h2>
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="font-bold text-slate-900 mb-2">Can I switch plans later?</h3>
                <p class="text-slate-600">Yes! You can upgrade or downgrade at any time. Changes take effect immediately.</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="font-bold text-slate-900 mb-2">What happens after my trial ends?</h3>
                <p class="text-slate-600">You'll be prompted to choose a plan. No automatic charges—we'll never surprise you.</p>
            </div>
        </div>
    </section>
</main>

<script>
document.getElementById('billing-toggle')?.addEventListener('click', function() {
    const knob = document.getElementById('toggle-knob');
    const isAnnual = knob.style.transform === 'translateX(24px)';
    knob.style.transform = isAnnual ? '' : 'translateX(24px)';
    
    // Update prices (20% discount for annual)
    document.querySelectorAll('[data-monthly-price]').forEach(el => {
        const monthly = parseInt(el.dataset.monthlyPrice);
        el.textContent = isAnnual ? `$${monthly}` : `$${Math.round(monthly * 0.8)}`;
    });
});
</script>

<?php
if (!zed_include_theme_part('footer', ['footer_style' => 'dark'])) {
    echo '<footer class="bg-slate-900 text-white py-12 mt-auto">';
    echo '<div class="max-w-4xl mx-auto px-6 text-center">';
    echo '<p class="text-slate-400 text-sm">© ' . date('Y') . ' ' . htmlspecialchars($site_name) . '</p>';
    echo '</div></footer>';
    Event::trigger('zed_footer');
    echo '</body></html>';
}
?>
