<?php
/**
 * Aurora Theme — Shared Footer Partial
 * 
 * Standard footer used across templates.
 * Includes copyright and footer links.
 */

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$site_name = zed_get_site_name();

// Footer style options: 'default', 'dark', 'minimal'
$footer_style = $footer_style ?? 'dark';
?>
    
    <!-- Footer -->
    <footer class="<?= $footer_style === 'dark' ? 'bg-slate-900 text-white' : 'bg-slate-50 border-t border-slate-200 text-slate-600' ?> py-12 mt-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined <?= $footer_style === 'dark' ? 'text-brand' : 'text-brand' ?>">auto_awesome</span>
                    <span class="font-bold"><?= htmlspecialchars($site_name) ?></span>
                </div>
                
                <p class="<?= $footer_style === 'dark' ? 'text-slate-400' : 'text-slate-500' ?> text-sm">
                    © <?= date('Y') ?> <?= htmlspecialchars($site_name) ?> • Powered by 
                    <a href="https://github.com/aamirali51/ZedCMS" class="text-brand hover:underline">Zed CMS</a>
                </p>
                
                <div class="flex gap-6 text-sm">
                    <a href="<?= $base_url ?>/privacy" class="<?= $footer_style === 'dark' ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900' ?>">Privacy</a>
                    <a href="<?= $base_url ?>/terms" class="<?= $footer_style === 'dark' ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900' ?>">Terms</a>
                    <a href="<?= $base_url ?>/contact" class="<?= $footer_style === 'dark' ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900' ?>">Contact</a>
                </div>
            </div>
        </div>
    </footer>
    
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
