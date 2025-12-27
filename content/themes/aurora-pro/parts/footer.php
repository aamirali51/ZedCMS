<?php
/**
 * Aurora Pro - Footer Part
 * 
 * Outputs site footer with links, social icons, and closing tags.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;

$base_url = Router::getBasePath();
$site_name = function_exists('zed_get_site_name') ? zed_get_site_name() : 'ZedCMS';
$copyright = aurora_option('footer_copyright', '© ' . date('Y') . ' ' . $site_name);
$tagline = aurora_option('footer_tagline', 'Built with ZedCMS');

// Social links
$twitter = aurora_option('social_twitter', '');
$facebook = aurora_option('social_facebook', '');
$instagram = aurora_option('social_instagram', '');
$linkedin = aurora_option('social_linkedin', '');
$github = aurora_option('social_github', '');
?>
    </main><!-- End main content -->

    <!-- Site Footer -->
    <footer class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
        <div class="container mx-auto px-6">
            
            <!-- Footer Main -->
            <div class="py-12 lg:py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                
                <!-- Brand Column -->
                <div class="lg:col-span-1">
                    <a href="<?= $base_url ?>/" class="flex items-center gap-3 font-bold text-xl text-gray-900 dark:text-white mb-4">
                        <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-extrabold rounded-xl">
                            Z
                        </span>
                        <span><?= htmlspecialchars($site_name) ?></span>
                    </a>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4">
                        <?= htmlspecialchars($tagline) ?>
                    </p>
                    
                    <!-- Social Links -->
                    <div class="flex items-center gap-3">
                        <?php if ($twitter): ?>
                        <a href="<?= htmlspecialchars($twitter) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full transition-colors" aria-label="Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($facebook): ?>
                        <a href="<?= htmlspecialchars($facebook) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full transition-colors" aria-label="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($instagram): ?>
                        <a href="<?= htmlspecialchars($instagram) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full transition-colors" aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($linkedin): ?>
                        <a href="<?= htmlspecialchars($linkedin) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full transition-colors" aria-label="LinkedIn">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($github): ?>
                        <a href="<?= htmlspecialchars($github) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full transition-colors" aria-label="GitHub">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Links -->
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm uppercase tracking-wide mb-4">Navigation</h4>
                    <ul class="space-y-2">
                        <li><a href="<?= $base_url ?>/" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm transition-colors">Home</a></li>
                        <li><a href="<?= $base_url ?>/blog" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm transition-colors">Blog</a></li>
                        <li><a href="<?= $base_url ?>/about" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm transition-colors">About</a></li>
                        <li><a href="<?= $base_url ?>/contact" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm uppercase tracking-wide mb-4">Categories</h4>
                    <ul class="space-y-2">
                        <?php
                        if (function_exists('zed_get_categories')) {
                            $categories = array_slice(zed_get_categories([]), 0, 5);
                            foreach ($categories as $cat) {
                                echo '<li><a href="' . $base_url . '/category/' . htmlspecialchars($cat['slug']) . '" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm transition-colors">' . htmlspecialchars($cat['name']) . '</a></li>';
                            }
                        } else {
                            echo '<li class="text-gray-500 text-sm">No categories</li>';
                        }
                        ?>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm uppercase tracking-wide mb-4">Get in Touch</h4>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li>support@yoursite.com</li>
                        <li>+1 (555) 123-4567</li>
                    </ul>
                </div>
                
            </div>
            
            <!-- Footer Bottom -->
            <div class="py-6 border-t border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <?= $copyright ?>
                </p>
                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <a href="<?= $base_url ?>/privacy" class="hover:text-gray-900 dark:hover:text-white transition-colors">Privacy Policy</a>
                    <a href="<?= $base_url ?>/terms" class="hover:text-gray-900 dark:hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
            
        </div>
    </footer>
    
    <?php Event::trigger('zed_footer'); ?>
    
    <!-- Mobile Menu & Search Scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Search modal
        const searchToggle = document.getElementById('search-toggle');
        const searchModal = document.getElementById('search-modal');
        const searchBackdrop = document.getElementById('search-backdrop');
        
        if (searchToggle && searchModal) {
            searchToggle.addEventListener('click', function() {
                searchModal.classList.remove('hidden');
                searchModal.querySelector('input').focus();
            });
            
            if (searchBackdrop) {
                searchBackdrop.addEventListener('click', function() {
                    searchModal.classList.add('hidden');
                });
            }
            
            // Close on Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                    searchModal.classList.add('hidden');
                }
            });
        }
        
        // Sticky header shadow on scroll
        const header = document.getElementById('site-header');
        if (header) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                    header.classList.add('shadow-md');
                } else {
                    header.classList.remove('shadow-md');
                }
            });
        }
    });
    </script>

</body>
</html>
