<?php
/**
 * Template Library — FAQ Page
 * 
 * Frequently asked questions with expandable accordion.
 * Automatically adopts active theme styling via Theme Parts API.
 */
declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$page_title = $post['title'] ?? 'Frequently Asked Questions';
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
    echo '<div class="max-w-4xl mx-auto px-6 py-4 flex justify-between items-center">';
    echo '<a href="' . $base_url . '/" class="font-bold text-xl">' . htmlspecialchars($site_name) . '</a>';
    echo '<a href="' . $base_url . '/" class="text-sm text-slate-600 hover:text-slate-900">← Back to Home</a>';
    echo '</div></header>';
}

// Default FAQ data
$faqs = [
    [
        'category' => 'General',
        'questions' => [
            [
                'q' => 'What is your return policy?',
                'a' => 'We offer a 30-day money-back guarantee. If you\'re not satisfied with our service, simply contact us within 30 days of your purchase for a full refund.',
            ],
            [
                'q' => 'How do I get started?',
                'a' => 'Getting started is easy! Simply sign up for an account, choose your plan, and follow our step-by-step setup guide. Our support team is always here to help if you need assistance.',
            ],
            [
                'q' => 'Do you offer a free trial?',
                'a' => 'Yes! We offer a 14-day free trial with full access to all features. No credit card required to start.',
            ],
        ],
    ],
    [
        'category' => 'Pricing & Billing',
        'questions' => [
            [
                'q' => 'What payment methods do you accept?',
                'a' => 'We accept all major credit cards (Visa, MasterCard, American Express), PayPal, and bank transfers for annual plans.',
            ],
            [
                'q' => 'Can I change my plan later?',
                'a' => 'Absolutely! You can upgrade or downgrade your plan at any time. Changes take effect immediately, and we\'ll prorate any charges.',
            ],
            [
                'q' => 'Do you offer discounts for annual billing?',
                'a' => 'Yes, you save 20% when you choose annual billing over monthly billing.',
            ],
        ],
    ],
    [
        'category' => 'Technical',
        'questions' => [
            [
                'q' => 'Is my data secure?',
                'a' => 'Security is our top priority. We use 256-bit SSL encryption, regular security audits, and store data in SOC 2 compliant data centers.',
            ],
            [
                'q' => 'Can I export my data?',
                'a' => 'Yes, you can export all your data at any time in various formats including CSV, JSON, and PDF.',
            ],
        ],
    ],
];
?>

<main class="flex-1 py-16">
    <!-- Hero Section -->
    <section class="max-w-4xl mx-auto px-6 text-center mb-16">
        <span class="inline-flex items-center gap-2 text-brand font-semibold text-sm bg-brand/10 px-4 py-2 rounded-full mb-4">
            <span class="material-symbols-outlined">help</span>
            Help Center
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mb-6">
            <?= htmlspecialchars($page_title) ?>
        </h1>
        <?php if (!empty($excerpt)): ?>
        <p class="text-xl text-slate-600 max-w-2xl mx-auto"><?= htmlspecialchars($excerpt) ?></p>
        <?php else: ?>
        <p class="text-xl text-slate-600 max-w-2xl mx-auto">
            Find answers to common questions. Can't find what you're looking for? Contact us!
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
    
    <!-- FAQ Accordion -->
    <section class="max-w-3xl mx-auto px-6 mb-20">
        <?php foreach ($faqs as $category): ?>
        <div class="mb-8">
            <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-0.5 bg-brand rounded-full"></span>
                <?= htmlspecialchars($category['category']) ?>
            </h2>
            
            <div class="space-y-3">
                <?php foreach ($category['questions'] as $i => $faq): ?>
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden faq-item">
                    <button class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-slate-50 transition-colors faq-toggle" onclick="toggleFaq(this)">
                        <span class="font-semibold text-slate-900 pr-8"><?= htmlspecialchars($faq['q']) ?></span>
                        <span class="material-symbols-outlined text-slate-400 faq-icon transition-transform">expand_more</span>
                    </button>
                    <div class="faq-answer hidden px-6 pb-5">
                        <p class="text-slate-600 leading-relaxed"><?= htmlspecialchars($faq['a']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
    
    <!-- Still have questions -->
    <section class="bg-brand py-16">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <span class="material-symbols-outlined text-white/30 text-6xl mb-4">contact_support</span>
            <h2 class="text-3xl font-bold text-white mb-4">Still have questions?</h2>
            <p class="text-white/80 mb-8">Our support team is here to help you.</p>
            <a href="<?= $base_url ?>/contact" class="inline-block bg-white text-brand px-8 py-4 rounded-full font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                Contact Support
            </a>
        </div>
    </section>
</main>

<script>
function toggleFaq(button) {
    const item = button.closest('.faq-item');
    const answer = item.querySelector('.faq-answer');
    const icon = item.querySelector('.faq-icon');
    
    answer.classList.toggle('hidden');
    icon.style.transform = answer.classList.contains('hidden') ? '' : 'rotate(180deg)';
}
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
