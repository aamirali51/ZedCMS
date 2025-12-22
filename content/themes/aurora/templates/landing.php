<?php
/**
 * Aurora Theme — Landing Page Template (Full Width)
 * 
 * Uses the Zed CMS Theme Parts API for dynamic styling.
 * This is a full-width hero-style template for marketing pages.
 * Works with any theme that provides the standard theme parts.
 */
declare(strict_types=1);

use Core\Router;
use Core\Event;

// Get variables from template data
$base_url = Router::getBasePath();
$page_title = $post['title'] ?? 'Welcome';
$excerpt = $data['excerpt'] ?? '';
$site_name = zed_get_site_name();

// Try to include theme's head part
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
?>
<body class="bg-white text-slate-900 font-sans antialiased">
    
    <!-- Navigation (Transparent for Landing) -->
    <header class="absolute top-0 left-0 w-full z-50 pt-6 px-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="<?= $base_url ?>/" class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined">auto_awesome</span>
                <?= htmlspecialchars($site_name) ?>
            </a>
            
            <div class="flex items-center gap-4">
                <?= zed_menu('Main Menu', ['class' => 'hidden md:flex items-center gap-6 text-sm font-medium text-slate-600']) ?>
                <a href="<?= $base_url ?>/contact" class="bg-brand text-white px-5 py-2.5 rounded-full font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                    Get Started
                </a>
            </div>
        </div>
    </header>
    
    <main>
        <!-- Editor Content (Full Width) -->
        <?php if (!empty($htmlContent)): ?>
        <div class="prose prose-xl max-w-none prose-headings:text-slate-900 prose-p:text-slate-600">
            <?= $htmlContent ?>
        </div>
        <?php else: ?>
        <!-- Default Hero (if empty) -->
        <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-blue-50 pt-20">
            <div class="text-center max-w-3xl px-6">
                <h1 class="text-5xl md:text-6xl font-extrabold mb-6 tracking-tight text-slate-900">
                    <?= htmlspecialchars($page_title) ?>
                </h1>
                <?php if (!empty($excerpt)): ?>
                <p class="text-xl md:text-2xl text-slate-600 mb-10 leading-relaxed"><?= htmlspecialchars($excerpt) ?></p>
                <?php endif; ?>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?= $base_url ?>/contact" class="bg-brand text-white px-8 py-4 rounded-full font-bold text-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        Get Started
                    </a>
                    <a href="#features" class="bg-slate-900 text-white px-8 py-4 rounded-full font-bold text-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        Learn More
                    </a>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>

<?php
// Try to include theme's footer part
if (!zed_include_theme_part('footer', ['footer_style' => 'default'])) {
    // Fallback: Minimal footer
    echo '<footer class="bg-slate-50 border-t border-slate-200 py-12 mt-12">';
    echo '<div class="max-w-7xl mx-auto px-6 text-center">';
    echo '<p class="text-slate-500 text-sm">© ' . date('Y') . ' ' . htmlspecialchars($site_name) . ' • All rights reserved.</p>';
    echo '</div></footer>';
    Event::trigger('zed_footer');
    echo '</body></html>';
}
?>
