<?php
/**
 * Aurora Pro - Advanced Theme Panel
 * 
 * Soledad-style theme options panel with tabbed interface,
 * live preview, and comprehensive customization options.
 * 
 * @package AuroraPro
 */

declare(strict_types=1);

use Core\Auth;
use Core\Router;
use Core\Database;

// Security check
if (!Auth::check() || !function_exists('zed_current_user_can') || !zed_current_user_can('manage_themes')) {
    Router::redirect('/admin/login');
    return;
}

$base_url = Router::getBasePath();
$theme_url = $base_url . '/content/themes/aurora-pro';
$panel_url = $theme_url . '/panel/assets';

// Get all current settings
$settings = aurora_get_all_settings();

// Handle save
$saved = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aurora_save'])) {
    if (aurora_save_settings($_POST)) {
        $saved = true;
        $settings = aurora_get_all_settings(); // Refresh
    } else {
        $error = 'Failed to save settings.';
    }
}

// Handle reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aurora_reset'])) {
    aurora_reset_settings();
    $settings = aurora_get_all_settings();
    $saved = true;
}

// Panel sections configuration
$sections = [
    'general' => ['icon' => 'settings', 'label' => 'General'],
    'typography' => ['icon' => 'text_fields', 'label' => 'Typography'],
    'colors' => ['icon' => 'palette', 'label' => 'Colors'],
    'header' => ['icon' => 'web_asset', 'label' => 'Header'],
    'homepage' => ['icon' => 'home', 'label' => 'Homepage'],
    'blog' => ['icon' => 'article', 'label' => 'Blog & Archive'],
    'single' => ['icon' => 'description', 'label' => 'Single Post'],
    'footer' => ['icon' => 'dock', 'label' => 'Footer'],
    'social' => ['icon' => 'share', 'label' => 'Social Media'],
    'advanced' => ['icon' => 'code', 'label' => 'Advanced'],
];
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurora Pro Settings — Zed CMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        'primary-hover': '#4f46e5',
                        aurora: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?= $panel_url ?>/panel.css?v=<?= time() ?>">
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    <!-- Top Bar -->
    <header class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-200 z-50 flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <a href="<?= $base_url ?>/admin" class="text-gray-400 hover:text-gray-600 transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <span class="text-white font-bold text-lg">A</span>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">Aurora Pro</h1>
                    <p class="text-xs text-gray-500">Theme Settings</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <?php if ($saved): ?>
            <span class="text-sm text-green-600 flex items-center gap-1">
                <span class="material-symbols-outlined text-lg">check_circle</span>
                Settings saved!
            </span>
            <?php endif; ?>
            
            <button type="button" onclick="document.getElementById('settings-form').requestSubmit()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-all shadow-sm hover:shadow-md">
                <span class="material-symbols-outlined text-lg">save</span>
                Save Changes
            </button>
        </div>
    </header>

    <div class="flex pt-16" style="height: 100vh;">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white border-r border-gray-200 overflow-y-auto flex-shrink-0">
            <nav class="p-4 space-y-1">
                <?php foreach ($sections as $id => $section): ?>
                <button type="button" 
                        class="panel-tab w-full flex items-center gap-3 px-4 py-3 text-left rounded-xl transition-all <?= $id === 'general' ? 'active' : '' ?>"
                        data-section="<?= $id ?>">
                    <span class="material-symbols-outlined text-xl"><?= $section['icon'] ?></span>
                    <span class="font-medium"><?= $section['label'] ?></span>
                </button>
                <?php endforeach; ?>
            </nav>
            
            <!-- Bottom Actions -->
            <div class="p-4 border-t border-gray-100 mt-4">
                <button type="button" onclick="document.getElementById('import-file').click()" class="w-full flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors text-sm">
                    <span class="material-symbols-outlined text-lg">upload</span>
                    Import Settings
                </button>
                <button type="button" onclick="exportSettings()" class="w-full flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors text-sm">
                    <span class="material-symbols-outlined text-lg">download</span>
                    Export Settings
                </button>
                <form method="post" class="mt-2" onsubmit="return confirm('Reset all settings to defaults?')">
                    <button type="submit" name="aurora_reset" value="1" class="w-full flex items-center gap-3 px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors text-sm">
                        <span class="material-symbols-outlined text-lg">restart_alt</span>
                        Reset to Defaults
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-8">
            <form id="settings-form" method="post">
                <input type="hidden" name="aurora_save" value="1">
                
                <!-- General Section -->
                <section class="panel-section" data-section="general">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">General Settings</h2>
                        <p class="text-gray-500 mt-1">Configure basic site settings and appearance</p>
                    </div>
                    
                    <div class="grid gap-6">
                        <!-- Site Layout -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <label class="setting-label">Site Layout</label>
                                <p class="setting-desc">Choose the overall layout style for your site</p>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <?php 
                                $layouts = [
                                    'blog' => ['icon' => 'view_agenda', 'label' => 'Blog', 'desc' => 'Classic blog layout'],
                                    'magazine' => ['icon' => 'grid_view', 'label' => 'Magazine', 'desc' => 'News-style grid'],
                                    'portfolio' => ['icon' => 'dashboard', 'label' => 'Portfolio', 'desc' => 'Showcase layout'],
                                ];
                                foreach ($layouts as $key => $layout): 
                                ?>
                                <label class="layout-option <?= ($settings['site_layout'] ?? 'blog') === $key ? 'active' : '' ?>">
                                    <input type="radio" name="site_layout" value="<?= $key ?>" class="sr-only" <?= ($settings['site_layout'] ?? 'blog') === $key ? 'checked' : '' ?>>
                                    <span class="material-symbols-outlined text-3xl mb-2"><?= $layout['icon'] ?></span>
                                    <span class="font-medium"><?= $layout['label'] ?></span>
                                    <span class="text-xs text-gray-500"><?= $layout['desc'] ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Preloader -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Page Preloader</label>
                                    <p class="setting-desc">Show a loading animation while page loads</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_preloader" value="1" <?= !empty($settings['show_preloader']) ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Back to Top -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Back to Top Button</label>
                                    <p class="setting-desc">Show floating button to scroll back to top</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_back_to_top" value="1" <?= ($settings['show_back_to_top'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Typography Section -->
                <section class="panel-section hidden" data-section="typography">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Typography</h2>
                        <p class="text-gray-500 mt-1">Customize fonts and text styles</p>
                    </div>
                    
                    <div class="grid gap-6">
                        <!-- Body Font -->
                        <div class="setting-card">
                            <label class="setting-label">Body Font</label>
                            <p class="setting-desc">Main font for body text</p>
                            <select name="body_font" class="aurora-select mt-3">
                                <?php
                                $fonts = [
                                    'inter' => 'Inter',
                                    'roboto' => 'Roboto',
                                    'open-sans' => 'Open Sans',
                                    'lato' => 'Lato',
                                    'poppins' => 'Poppins',
                                    'source-sans' => 'Source Sans Pro',
                                    'nunito' => 'Nunito',
                                    'montserrat' => 'Montserrat',
                                ];
                                foreach ($fonts as $key => $font): ?>
                                <option value="<?= $key ?>" <?= ($settings['body_font'] ?? 'inter') === $key ? 'selected' : '' ?>><?= $font ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Heading Font -->
                        <div class="setting-card">
                            <label class="setting-label">Heading Font</label>
                            <p class="setting-desc">Font for headings (H1-H6)</p>
                            <select name="heading_font" class="aurora-select mt-3">
                                <?php
                                $headingFonts = [
                                    'inter' => 'Inter',
                                    'playfair' => 'Playfair Display',
                                    'merriweather' => 'Merriweather',
                                    'poppins' => 'Poppins',
                                    'roboto' => 'Roboto',
                                    'oswald' => 'Oswald',
                                    'raleway' => 'Raleway',
                                    'montserrat' => 'Montserrat',
                                ];
                                foreach ($headingFonts as $key => $font): ?>
                                <option value="<?= $key ?>" <?= ($settings['heading_font'] ?? 'inter') === $key ? 'selected' : '' ?>><?= $font ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Font Size -->
                        <div class="setting-card">
                            <label class="setting-label">Base Font Size</label>
                            <p class="setting-desc">Default body text size</p>
                            <div class="flex items-center gap-4 mt-3">
                                <input type="range" name="base_font_size" min="14" max="20" value="<?= $settings['base_font_size'] ?? 16 ?>" class="aurora-range flex-1">
                                <span class="font-mono text-sm w-12 text-center"><?= $settings['base_font_size'] ?? 16 ?>px</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Colors Section -->
                <section class="panel-section hidden" data-section="colors">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Colors</h2>
                        <p class="text-gray-500 mt-1">Customize the color scheme</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Primary Color -->
                        <div class="setting-card">
                            <label class="setting-label">Primary Color</label>
                            <p class="setting-desc">Main brand color</p>
                            <div class="flex items-center gap-3 mt-3">
                                <input type="color" name="primary_color" value="<?= $settings['primary_color'] ?? '#4f46e5' ?>" class="aurora-color">
                                <input type="text" value="<?= $settings['primary_color'] ?? '#4f46e5' ?>" class="aurora-input flex-1 font-mono text-sm" readonly>
                            </div>
                        </div>
                        
                        <!-- Secondary Color -->
                        <div class="setting-card">
                            <label class="setting-label">Secondary Color</label>
                            <p class="setting-desc">Accent and highlights</p>
                            <div class="flex items-center gap-3 mt-3">
                                <input type="color" name="secondary_color" value="<?= $settings['secondary_color'] ?? '#7c3aed' ?>" class="aurora-color">
                                <input type="text" value="<?= $settings['secondary_color'] ?? '#7c3aed' ?>" class="aurora-input flex-1 font-mono text-sm" readonly>
                            </div>
                        </div>
                        
                        <!-- Accent Color -->
                        <div class="setting-card">
                            <label class="setting-label">Accent Color</label>
                            <p class="setting-desc">Buttons and CTAs</p>
                            <div class="flex items-center gap-3 mt-3">
                                <input type="color" name="accent_color" value="<?= $settings['accent_color'] ?? '#ec4899' ?>" class="aurora-color">
                                <input type="text" value="<?= $settings['accent_color'] ?? '#ec4899' ?>" class="aurora-input flex-1 font-mono text-sm" readonly>
                            </div>
                        </div>
                        
                        <!-- Dark Mode -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Dark Mode Support</label>
                                    <p class="setting-desc">Enable dark mode toggle</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="dark_mode" value="1" <?= ($settings['dark_mode'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Header Section -->
                <section class="panel-section hidden" data-section="header">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Header Settings</h2>
                        <p class="text-gray-500 mt-1">Customize the site header and navigation</p>
                    </div>
                    
                    <div class="grid gap-6">
                        <!-- Sticky Header -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Sticky Header</label>
                                    <p class="setting-desc">Keep header fixed when scrolling</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="sticky_header" value="1" <?= ($settings['sticky_header'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Search -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Show Search</label>
                                    <p class="setting-desc">Display search icon in header</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_search" value="1" <?= ($settings['show_search'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Header Builder Link -->
                        <div class="setting-card bg-gradient-to-r from-indigo-50 to-purple-50 border-indigo-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label text-indigo-900">Header Builder</label>
                                    <p class="setting-desc text-indigo-600">Use drag & drop builder for custom header</p>
                                </div>
                                <a href="<?= $base_url ?>/admin/header-builder" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                    <span class="material-symbols-outlined text-lg">build</span>
                                    Open Builder
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Homepage Section -->
                <section class="panel-section hidden" data-section="homepage">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Homepage Settings</h2>
                        <p class="text-gray-500 mt-1">Configure homepage sections and layout</p>
                    </div>
                    
                    <div class="grid gap-6">
                        <!-- Hero Section -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <label class="setting-label">Hero Section</label>
                                    <p class="setting-desc">Large banner at top of homepage</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_hero" value="1" <?= ($settings['show_hero'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="space-y-4 pt-4 border-t border-gray-100">
                                <input type="text" name="hero_title" value="<?= htmlspecialchars($settings['hero_title'] ?? 'Welcome to Our Blog') ?>" placeholder="Hero Title" class="aurora-input">
                                <input type="text" name="hero_subtitle" value="<?= htmlspecialchars($settings['hero_subtitle'] ?? 'Discover stories and insights') ?>" placeholder="Hero Subtitle" class="aurora-input">
                            </div>
                        </div>
                        
                        <!-- Featured Posts -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <label class="setting-label">Featured Posts</label>
                                    <p class="setting-desc">Highlight top posts on homepage</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_featured" value="1" <?= ($settings['show_featured'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <label class="text-sm text-gray-600">Number of featured posts</label>
                                <select name="featured_count" class="aurora-select mt-2">
                                    <option value="2" <?= ($settings['featured_count'] ?? '3') == '2' ? 'selected' : '' ?>>2 posts</option>
                                    <option value="3" <?= ($settings['featured_count'] ?? '3') == '3' ? 'selected' : '' ?>>3 posts</option>
                                    <option value="4" <?= ($settings['featured_count'] ?? '3') == '4' ? 'selected' : '' ?>>4 posts</option>
                                    <option value="5" <?= ($settings['featured_count'] ?? '3') == '5' ? 'selected' : '' ?>>5 posts</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Categories -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Category Grid</label>
                                    <p class="setting-desc">Show category cards on homepage</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_categories" value="1" <?= ($settings['show_categories'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Newsletter -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <label class="setting-label">Newsletter Section</label>
                                    <p class="setting-desc">Email subscription form</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_newsletter" value="1" <?= ($settings['show_newsletter'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="space-y-4 pt-4 border-t border-gray-100">
                                <input type="text" name="newsletter_title" value="<?= htmlspecialchars($settings['newsletter_title'] ?? 'Subscribe to our newsletter') ?>" placeholder="Newsletter Title" class="aurora-input">
                                <textarea name="newsletter_text" placeholder="Newsletter description" class="aurora-input" rows="2"><?= htmlspecialchars($settings['newsletter_text'] ?? 'Get the latest posts delivered to your inbox.') ?></textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Blog Section -->
                <section class="panel-section hidden" data-section="blog">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Blog & Archive</h2>
                        <p class="text-gray-500 mt-1">Settings for blog listing and archive pages</p>
                    </div>
                    
                    <div class="grid gap-6">
                        <!-- Posts Per Page -->
                        <div class="setting-card">
                            <label class="setting-label">Posts Per Page</label>
                            <p class="setting-desc">Number of posts to show per page</p>
                            <select name="posts_per_page" class="aurora-select mt-3">
                                <option value="6" <?= ($settings['posts_per_page'] ?? '10') == '6' ? 'selected' : '' ?>>6 posts</option>
                                <option value="9" <?= ($settings['posts_per_page'] ?? '10') == '9' ? 'selected' : '' ?>>9 posts</option>
                                <option value="10" <?= ($settings['posts_per_page'] ?? '10') == '10' ? 'selected' : '' ?>>10 posts</option>
                                <option value="12" <?= ($settings['posts_per_page'] ?? '10') == '12' ? 'selected' : '' ?>>12 posts</option>
                            </select>
                        </div>
                        
                        <!-- Grid Columns -->
                        <div class="setting-card">
                            <label class="setting-label">Grid Columns</label>
                            <p class="setting-desc">Number of columns in post grid</p>
                            <div class="flex gap-4 mt-3">
                                <?php foreach ([2, 3, 4] as $cols): ?>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="blog_columns" value="<?= $cols ?>" class="sr-only peer" <?= ($settings['blog_columns'] ?? '3') == $cols ? 'checked' : '' ?>>
                                    <div class="p-4 text-center border-2 border-gray-200 rounded-xl hover:border-gray-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                        <span class="font-bold text-lg"><?= $cols ?></span>
                                        <span class="block text-xs text-gray-500">columns</span>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Sidebar -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <label class="setting-label">Show Sidebar</label>
                                    <p class="setting-desc">Display sidebar on blog pages</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_sidebar" value="1" <?= ($settings['show_sidebar'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <label class="text-sm text-gray-600">Sidebar Position</label>
                                <select name="sidebar_position" class="aurora-select mt-2">
                                    <option value="right" <?= ($settings['sidebar_position'] ?? 'right') === 'right' ? 'selected' : '' ?>>Right</option>
                                    <option value="left" <?= ($settings['sidebar_position'] ?? 'right') === 'left' ? 'selected' : '' ?>>Left</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Single Post Section -->
                <section class="panel-section hidden" data-section="single">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Single Post</h2>
                        <p class="text-gray-500 mt-1">Settings for individual post pages</p>
                    </div>
                    
                    <div class="grid gap-6">
                        <!-- Author Bio -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Author Bio</label>
                                    <p class="setting-desc">Show author info below post</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_author_bio" value="1" <?= ($settings['show_author_bio'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Share Buttons -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Share Buttons</label>
                                    <p class="setting-desc">Social share buttons on posts</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_share_buttons" value="1" <?= ($settings['show_share_buttons'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Related Posts -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Related Posts</label>
                                    <p class="setting-desc">Show related posts section</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_related_posts" value="1" <?= ($settings['show_related_posts'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Reading Time -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Reading Time</label>
                                    <p class="setting-desc">Show estimated reading time</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_reading_time" value="1" <?= ($settings['show_reading_time'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Post Navigation -->
                        <div class="setting-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="setting-label">Post Navigation</label>
                                    <p class="setting-desc">Next/Previous post links</p>
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="show_post_navigation" value="1" <?= ($settings['show_post_navigation'] ?? '1') ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Footer Section -->
                <section class="panel-section hidden" data-section="footer">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Footer Settings</h2>
                        <p class="text-gray-500 mt-1">Customize the site footer</p>
                    </div>
                    
                    <div class="grid gap-6">
                        <!-- Footer Columns -->
                        <div class="setting-card">
                            <label class="setting-label">Footer Layout</label>
                            <p class="setting-desc">Number of footer widget columns</p>
                            <div class="flex gap-4 mt-3">
                                <?php foreach ([2, 3, 4] as $cols): ?>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="footer_columns" value="<?= $cols ?>" class="sr-only peer" <?= ($settings['footer_columns'] ?? '4') == $cols ? 'checked' : '' ?>>
                                    <div class="p-4 text-center border-2 border-gray-200 rounded-xl hover:border-gray-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                        <span class="font-bold text-lg"><?= $cols ?></span>
                                        <span class="block text-xs text-gray-500">columns</span>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Copyright -->
                        <div class="setting-card">
                            <label class="setting-label">Copyright Text</label>
                            <p class="setting-desc">Displayed at bottom of footer</p>
                            <input type="text" name="footer_copyright" value="<?= htmlspecialchars($settings['footer_copyright'] ?? '© ' . date('Y') . ' Your Site. All rights reserved.') ?>" class="aurora-input mt-3">
                        </div>
                        
                        <!-- Tagline -->
                        <div class="setting-card">
                            <label class="setting-label">Footer Tagline</label>
                            <p class="setting-desc">Small text above copyright</p>
                            <input type="text" name="footer_tagline" value="<?= htmlspecialchars($settings['footer_tagline'] ?? 'Built with ZedCMS') ?>" class="aurora-input mt-3">
                        </div>
                    </div>
                </section>

                <!-- Social Section -->
                <section class="panel-section hidden" data-section="social">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Social Media</h2>
                        <p class="text-gray-500 mt-1">Add your social media profile URLs</p>
                    </div>
                    
                    <div class="grid gap-4">
                        <?php
                        $socials = [
                            'twitter' => ['icon' => 'https://cdn.simpleicons.org/x/gray', 'label' => 'X (Twitter)'],
                            'facebook' => ['icon' => 'https://cdn.simpleicons.org/facebook/gray', 'label' => 'Facebook'],
                            'instagram' => ['icon' => 'https://cdn.simpleicons.org/instagram/gray', 'label' => 'Instagram'],
                            'linkedin' => ['icon' => 'https://cdn.simpleicons.org/linkedin/gray', 'label' => 'LinkedIn'],
                            'github' => ['icon' => 'https://cdn.simpleicons.org/github/gray', 'label' => 'GitHub'],
                            'youtube' => ['icon' => 'https://cdn.simpleicons.org/youtube/gray', 'label' => 'YouTube'],
                            'tiktok' => ['icon' => 'https://cdn.simpleicons.org/tiktok/gray', 'label' => 'TikTok'],
                            'pinterest' => ['icon' => 'https://cdn.simpleicons.org/pinterest/gray', 'label' => 'Pinterest'],
                        ];
                        foreach ($socials as $key => $social): ?>
                        <div class="setting-card flex items-center gap-4">
                            <img src="<?= $social['icon'] ?>" alt="<?= $social['label'] ?>" class="w-6 h-6">
                            <div class="flex-1">
                                <input type="url" name="social_<?= $key ?>" value="<?= htmlspecialchars($settings['social_' . $key] ?? '') ?>" placeholder="<?= $social['label'] ?> URL" class="aurora-input">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Advanced Section -->
                <section class="panel-section hidden" data-section="advanced">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Advanced</h2>
                        <p class="text-gray-500 mt-1">Custom code and advanced options</p>
                    </div>
                    
                    <div class="grid gap-6">
                        <!-- Custom CSS -->
                        <div class="setting-card">
                            <label class="setting-label">Custom CSS</label>
                            <p class="setting-desc">Add custom styles to your site</p>
                            <textarea name="custom_css" rows="8" class="aurora-input mt-3 font-mono text-sm" placeholder="/* Your custom CSS here */"><?= htmlspecialchars($settings['custom_css'] ?? '') ?></textarea>
                        </div>
                        
                        <!-- Header Scripts -->
                        <div class="setting-card">
                            <label class="setting-label">Header Scripts</label>
                            <p class="setting-desc">Scripts to add before &lt;/head&gt; (analytics, etc.)</p>
                            <textarea name="header_scripts" rows="5" class="aurora-input mt-3 font-mono text-sm" placeholder="<!-- Your scripts here -->"><?= htmlspecialchars($settings['header_scripts'] ?? '') ?></textarea>
                        </div>
                        
                        <!-- Footer Scripts -->
                        <div class="setting-card">
                            <label class="setting-label">Footer Scripts</label>
                            <p class="setting-desc">Scripts to add before &lt;/body&gt;</p>
                            <textarea name="footer_scripts" rows="5" class="aurora-input mt-3 font-mono text-sm" placeholder="<!-- Your scripts here -->"><?= htmlspecialchars($settings['footer_scripts'] ?? '') ?></textarea>
                        </div>
                    </div>
                </section>

            </form>
            
            <!-- Hidden import input -->
            <input type="file" id="import-file" accept=".json" class="hidden" onchange="importSettings(this)">
        </main>
    </div>

    <script src="<?= $panel_url ?>/panel.js?v=<?= time() ?>"></script>
</body>
</html>

<?php
// =========================================================================
// HELPER FUNCTIONS
// =========================================================================

/**
 * Get all Aurora settings
 */
function aurora_get_all_settings(): array {
    $settings = [];
    $keys = [
        'site_layout', 'show_preloader', 'show_back_to_top',
        'body_font', 'heading_font', 'base_font_size',
        'primary_color', 'secondary_color', 'accent_color', 'dark_mode',
        'sticky_header', 'show_search',
        'show_hero', 'hero_title', 'hero_subtitle',
        'show_featured', 'featured_count',
        'show_categories', 'show_newsletter', 'newsletter_title', 'newsletter_text',
        'posts_per_page', 'blog_columns', 'show_sidebar', 'sidebar_position',
        'show_author_bio', 'show_share_buttons', 'show_related_posts', 'show_reading_time', 'show_post_navigation',
        'footer_columns', 'footer_copyright', 'footer_tagline',
        'social_twitter', 'social_facebook', 'social_instagram', 'social_linkedin', 'social_github', 'social_youtube', 'social_tiktok', 'social_pinterest',
        'custom_css', 'header_scripts', 'footer_scripts',
    ];
    
    foreach ($keys as $key) {
        $settings[$key] = zed_get_option('aurora_' . $key, '');
    }
    
    return $settings;
}

/**
 * Save Aurora settings
 */
function aurora_save_settings(array $data): bool {
    $keys = [
        'site_layout', 'show_preloader', 'show_back_to_top',
        'body_font', 'heading_font', 'base_font_size',
        'primary_color', 'secondary_color', 'accent_color', 'dark_mode',
        'sticky_header', 'show_search',
        'show_hero', 'hero_title', 'hero_subtitle',
        'show_featured', 'featured_count',
        'show_categories', 'show_newsletter', 'newsletter_title', 'newsletter_text',
        'posts_per_page', 'blog_columns', 'show_sidebar', 'sidebar_position',
        'show_author_bio', 'show_share_buttons', 'show_related_posts', 'show_reading_time', 'show_post_navigation',
        'footer_columns', 'footer_copyright', 'footer_tagline',
        'social_twitter', 'social_facebook', 'social_instagram', 'social_linkedin', 'social_github', 'social_youtube', 'social_tiktok', 'social_pinterest',
        'custom_css', 'header_scripts', 'footer_scripts',
    ];
    
    foreach ($keys as $key) {
        $value = $data[$key] ?? '';
        zed_set_option('aurora_' . $key, $value);
    }
    
    return true;
}

/**
 * Reset Aurora settings to defaults
 */
function aurora_reset_settings(): void {
    $defaults = [
        'site_layout' => 'blog',
        'show_preloader' => '',
        'show_back_to_top' => '1',
        'body_font' => 'inter',
        'heading_font' => 'inter',
        'base_font_size' => '16',
        'primary_color' => '#4f46e5',
        'secondary_color' => '#7c3aed',
        'accent_color' => '#ec4899',
        'dark_mode' => '1',
        'sticky_header' => '1',
        'show_search' => '1',
        'show_hero' => '1',
        'hero_title' => 'Welcome to Our Blog',
        'hero_subtitle' => 'Discover stories and insights',
        'show_featured' => '1',
        'featured_count' => '3',
        'show_categories' => '1',
        'show_newsletter' => '1',
        'newsletter_title' => 'Subscribe to our newsletter',
        'newsletter_text' => 'Get the latest posts delivered to your inbox.',
        'posts_per_page' => '10',
        'blog_columns' => '3',
        'show_sidebar' => '1',
        'sidebar_position' => 'right',
        'show_author_bio' => '1',
        'show_share_buttons' => '1',
        'show_related_posts' => '1',
        'show_reading_time' => '1',
        'show_post_navigation' => '1',
        'footer_columns' => '4',
        'footer_copyright' => '© ' . date('Y') . ' Your Site. All rights reserved.',
        'footer_tagline' => 'Built with ZedCMS',
    ];
    
    foreach ($defaults as $key => $value) {
        zed_set_option('aurora_' . $key, $value);
    }
}
