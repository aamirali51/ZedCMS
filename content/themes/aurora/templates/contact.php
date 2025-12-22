<?php
/**
 * Aurora Theme — Contact Page Template
 * 
 * Uses the Zed CMS Theme Parts API for dynamic styling.
 * This template works with any theme that provides parts/head.php, parts/header.php, etc.
 * Falls back gracefully if parts are missing.
 */
declare(strict_types=1);

use Core\Router;
use Core\Event;

// Get variables from template data
$base_url = Router::getBasePath();
$page_title = $post['title'] ?? 'Contact Us';
$excerpt = $data['excerpt'] ?? '';
$site_name = zed_get_site_name();

// Try to include theme's head part, or output minimal head
if (!zed_include_theme_part('head', [
    'page_title' => $page_title,
    'post' => $post ?? [],
    'data' => $data ?? [],
])) {
    // Fallback: Output minimal head with Tailwind
    echo "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n";
    echo "<meta charset=\"utf-8\">\n";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    echo "<title>" . htmlspecialchars($page_title) . " — " . htmlspecialchars($site_name) . "</title>\n";
    echo zed_tailwind_cdn();
    echo zed_google_fonts();
    echo "</head>\n";
}

// Try to include theme's header part
if (!zed_include_theme_part('header', [
    'max_width' => 'max-w-4xl',
    'post' => $post ?? [],
])) {
    // Fallback: Minimal header
    echo '<body class="bg-slate-50 text-slate-900 font-sans antialiased">';
    echo '<header class="bg-white border-b border-slate-200 sticky top-0 z-50">';
    echo '<div class="max-w-4xl mx-auto px-6 py-4 flex justify-between items-center">';
    echo '<a href="' . $base_url . '/" class="font-bold text-xl">' . htmlspecialchars($site_name) . '</a>';
    echo '<a href="' . $base_url . '/" class="text-sm text-slate-600 hover:text-slate-900">← Back</a>';
    echo '</div></header>';
}
?>
    
    <main class="py-16">
        <div class="max-w-4xl mx-auto px-6">
            
            <header class="mb-12 text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mb-4">
                    <?= htmlspecialchars($page_title) ?>
                </h1>
                <?php if (!empty($excerpt)): ?>
                <p class="text-xl text-slate-600 max-w-2xl mx-auto"><?= htmlspecialchars($excerpt) ?></p>
                <?php endif; ?>
            </header>

            <!-- Editor Content -->
            <?php if (!empty($htmlContent)): ?>
            <div class="prose prose-lg max-w-none text-slate-700 mb-16">
                <?= $htmlContent ?>
            </div>
            <?php endif; ?>

            <!-- Contact Form -->
            <div class="bg-white p-8 md:p-10 rounded-2xl shadow-lg border border-slate-100 max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-brand">mail</span>
                    Send us a message
                </h3>
                
                <form action="<?= $base_url ?>/api/submit-contact" method="POST" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Name</label>
                            <input type="text" name="name" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-brand focus:ring-brand py-2 px-3">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-brand focus:ring-brand py-2 px-3">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Subject</label>
                        <select name="subject" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-brand focus:ring-brand py-2 px-3">
                            <option>General Inquiry</option>
                            <option>Support Request</option>
                            <option>Partnership</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Message</label>
                        <textarea name="message" rows="4" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-brand focus:ring-brand py-2 px-3"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-brand text-white font-bold py-3 px-6 rounded-lg hover:bg-brand-dark transition-colors shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <span>Send Message</span>
                        <span class="material-symbols-outlined text-sm">send</span>
                    </button>
                    
                    <p class="text-xs text-center text-slate-400 mt-4">
                        We usually respond within 24 hours.
                    </p>
                </form>
            </div>
            
        </div>
    </main>

<?php
// Try to include theme's footer part
if (!zed_include_theme_part('footer', ['footer_style' => 'dark'])) {
    // Fallback: Minimal footer
    echo '<footer class="bg-slate-900 text-white py-12 mt-16">';
    echo '<div class="max-w-4xl mx-auto px-6 text-center">';
    echo '<p class="text-slate-400 text-sm">© ' . date('Y') . ' ' . htmlspecialchars($site_name) . '</p>';
    echo '</div></footer>';
    Event::trigger('zed_footer');
    echo '</body></html>';
}
?>
