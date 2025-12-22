<?php
/**
 * Template Library — Contact Form
 * 
 * Professional contact page with styled form.
 * Automatically adopts active theme styling via Theme Parts API.
 * Fully self-contained with robust fallbacks for any theme.
 */
declare(strict_types=1);

use Core\Router;
use Core\Event;

$base_url = Router::getBasePath();
$page_title = $post['title'] ?? 'Contact Us';
$excerpt = $data['excerpt'] ?? 'We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.';
$site_name = zed_get_site_name();

// Check if theme parts exist
$hasThemeParts = defined('ZED_ACTIVE_THEME_PATH') 
                 && file_exists(ZED_ACTIVE_THEME_PATH . '/parts/head.php');

// ═══════════════════════════════════════════════════════════════════════════
// HEAD SECTION
// ═══════════════════════════════════════════════════════════════════════════
if (!zed_include_theme_part('head', ['page_title' => $page_title, 'post' => $post ?? []])):
?>
<!DOCTYPE html>
<html lang="en">
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400" rel="stylesheet">
    
    <!-- Form Styles -->
    <style>
        input, select, textarea {
            border: 1px solid #e2e8f0;
            transition: all 0.15s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
    
    <?php Event::trigger('zed_head'); ?>
</head>
<?php endif; ?>

<?php
// ═══════════════════════════════════════════════════════════════════════════
// HEADER SECTION  
// ═══════════════════════════════════════════════════════════════════════════
if (!zed_include_theme_part('header', ['max_width' => 'max-w-4xl'])):
?>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 text-slate-900 font-sans antialiased min-h-screen">
    <!-- Simple Header -->
    <header class="bg-white/80 backdrop-blur-sm border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 py-4 flex justify-between items-center">
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
     MAIN CONTENT - Contact Form (Always rendered, theme-independent)
     ═══════════════════════════════════════════════════════════════════════════ -->
<main class="py-16">
    <div class="max-w-4xl mx-auto px-6">
        
        <!-- Page Header -->
        <header class="mb-12 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mb-4">
                <?= htmlspecialchars($page_title) ?>
            </h1>
            <?php if (!empty($excerpt)): ?>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto"><?= htmlspecialchars($excerpt) ?></p>
            <?php endif; ?>
        </header>

        <!-- Editor Content (if any) -->
        <?php if (!empty($htmlContent)): ?>
        <div class="prose prose-lg max-w-none text-slate-700 mb-16">
            <?= $htmlContent ?>
        </div>
        <?php endif; ?>

        <!-- Contact Form Card -->
        <div class="bg-white p-8 md:p-10 rounded-2xl shadow-xl border border-slate-100 max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
                <span class="w-10 h-10 bg-brand/10 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-brand">mail</span>
                </span>
                Send us a message
            </h3>
            
            <form action="<?= $base_url ?>/api/contact" method="POST" class="space-y-5" id="contact-form">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Your Name *</label>
                        <input type="text" name="name" required 
                               class="w-full rounded-xl bg-slate-50 py-3 px-4 text-slate-900 placeholder-slate-400"
                               placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address *</label>
                        <input type="email" name="email" required 
                               class="w-full rounded-xl bg-slate-50 py-3 px-4 text-slate-900 placeholder-slate-400"
                               placeholder="john@example.com">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Subject</label>
                    <select name="subject" class="w-full rounded-xl bg-slate-50 py-3 px-4 text-slate-900">
                        <option value="general">General Inquiry</option>
                        <option value="support">Support Request</option>
                        <option value="partnership">Partnership</option>
                        <option value="feedback">Feedback</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Message *</label>
                    <textarea name="message" rows="5" required 
                              class="w-full rounded-xl bg-slate-50 py-3 px-4 text-slate-900 placeholder-slate-400"
                              placeholder="How can we help you?"></textarea>
                </div>
                
                <button type="submit" 
                        class="w-full bg-brand text-white font-bold py-4 px-6 rounded-xl hover:bg-brand-dark transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <span>Send Message</span>
                    <span class="material-symbols-outlined text-lg">send</span>
                </button>
                
                <p class="text-xs text-center text-slate-400 mt-4">
                    We usually respond within 24 hours.
                </p>
            </form>
            
            <!-- Success Message (hidden by default) -->
            <div id="contact-success" class="hidden text-center py-8">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-4xl text-green-500">check_circle</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Message Sent!</h3>
                <p class="text-slate-600">Thank you for reaching out. We'll get back to you soon.</p>
            </div>
        </div>
        
        <!-- Contact Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 max-w-2xl mx-auto">
            <div class="text-center p-6 bg-white rounded-xl border border-slate-100 shadow-sm">
                <span class="material-symbols-outlined text-3xl text-brand mb-2">location_on</span>
                <h4 class="font-semibold text-slate-900">Address</h4>
                <p class="text-sm text-slate-500 mt-1">123 Main Street<br>City, Country</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl border border-slate-100 shadow-sm">
                <span class="material-symbols-outlined text-3xl text-brand mb-2">phone</span>
                <h4 class="font-semibold text-slate-900">Phone</h4>
                <p class="text-sm text-slate-500 mt-1">+1 (555) 123-4567</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl border border-slate-100 shadow-sm">
                <span class="material-symbols-outlined text-3xl text-brand mb-2">schedule</span>
                <h4 class="font-semibold text-slate-900">Hours</h4>
                <p class="text-sm text-slate-500 mt-1">Mon-Fri: 9am-5pm</p>
            </div>
        </div>
        
    </div>
</main>

<!-- Form Script -->
<script>
document.getElementById('contact-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const button = form.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<span class="animate-spin material-symbols-outlined">progress_activity</span> Sending...';
    button.disabled = true;
    
    const formData = new FormData(form);
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            form.classList.add('hidden');
            document.getElementById('contact-success').classList.remove('hidden');
        } else {
            throw new Error('Failed to send');
        }
    } catch (err) {
        alert('There was an error sending your message. Please try again.');
        button.innerHTML = originalText;
        button.disabled = false;
    }
});
</script>

<?php
// ═══════════════════════════════════════════════════════════════════════════
// FOOTER SECTION
// ═══════════════════════════════════════════════════════════════════════════
if (!zed_include_theme_part('footer', ['footer_style' => 'dark'])):
?>
    <footer class="bg-slate-900 text-white py-12 mt-16">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <p class="text-slate-400 text-sm">
                © <?= date('Y') ?> <?= htmlspecialchars($site_name) ?>. All rights reserved.
            </p>
        </div>
    </footer>
    <?php Event::trigger('zed_footer'); ?>
</body>
</html>
<?php endif; ?>
