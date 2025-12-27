<?php
/**
 * Unified Settings Panel
 * 
 * Features:
 * - Tabbed interface (General, SEO, System)
 * - Homepage Mode with Latest Posts / Static Page toggle
 * - Dynamic page dropdown for homepage
 * - Blog slug configuration
 * - SEO settings with search visibility
 * - System toggles (Maintenance, Debug)
 * - Sticky save bar
 * - AJAX save
 */

use Core\Router;

$base_url = Router::getBasePath();

// Get current values with defaults
$site_title = $options['site_title'] ?? 'Zed CMS';
$site_tagline = $options['site_tagline'] ?? 'Just another Zed site';
$homepage_mode = $options['homepage_mode'] ?? 'latest_posts';
$page_on_front = $options['page_on_front'] ?? '';
$blog_slug = $options['blog_slug'] ?? 'blog';
$posts_per_page = $options['posts_per_page'] ?? '10';

// SEO
$discourage_search = $options['discourage_search_engines'] ?? '0';
$meta_description = $options['meta_description'] ?? '';
$social_image = $options['social_sharing_image'] ?? '';

// System
$maintenance_mode = $options['maintenance_mode'] ?? '0';
$debug_mode = $options['debug_mode'] ?? '0';
?>

<style>
    /* Settings Panel Styles */
    .settings-container {
        display: flex;
        gap: 24px;
        min-height: calc(100vh - 200px);
    }
    
    .settings-sidebar {
        width: 220px;
        flex-shrink: 0;
    }
    
    .settings-content {
        flex: 1;
        max-width: 720px;
    }
    
    .tab-button {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
        transition: all 0.15s;
        text-align: left;
    }
    
    .tab-button:hover {
        background: #f3f4f6;
        color: #1f2937;
    }
    
    .tab-button.active {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    
    .tab-button.active .tab-icon {
        color: white;
    }
    
    .settings-panel {
        display: none;
    }
    
    .settings-panel.active {
        display: block;
    }
    
    .settings-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
    }
    
    .settings-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .settings-card-header h3 {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
    }
    
    .settings-card-body {
        padding: 24px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }
    
    .form-hint {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }
    
    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.15s;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
    }
    
    /* Radio Group */
    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .radio-option {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.15s;
    }
    
    .radio-option:hover {
        border-color: #c7d2fe;
        background: #f5f3ff;
    }
    
    .radio-option.selected {
        border-color: #6366f1;
        background: #eef2ff;
    }
    
    .radio-option input[type="radio"] {
        margin-top: 2px;
        accent-color: #6366f1;
    }
    
    .radio-label {
        flex: 1;
    }
    
    .radio-label-title {
        font-weight: 600;
        color: #111827;
        font-size: 14px;
    }
    
    .radio-label-desc {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }
    
    /* Toggle Switch */
    .toggle-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 16px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .toggle-wrapper:last-child {
        border-bottom: none;
    }
    
    .toggle-label {
        flex: 1;
    }
    
    .toggle-label-title {
        font-weight: 600;
        color: #111827;
        font-size: 14px;
    }
    
    .toggle-label-desc {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }
    
    .toggle-switch {
        position: relative;
        width: 48px;
        height: 26px;
        background: #d1d5db;
        border-radius: 13px;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .toggle-switch.active {
        background: #6366f1;
    }
    
    .toggle-switch::after {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        transition: transform 0.2s;
    }
    
    .toggle-switch.active::after {
        transform: translateX(22px);
    }
    
    /* Conditional Section */
    .conditional-section {
        display: none;
        padding: 16px;
        margin-top: 12px;
        background: #f9fafb;
        border-radius: 10px;
        border: 1px dashed #d1d5db;
    }
    
    .conditional-section.visible {
        display: block;
    }
    
    /* Sticky Save Bar */
    .save-bar {
        position: fixed;
        bottom: 24px;
        right: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        z-index: 100;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .save-bar.visible {
        transform: translateY(0);
        opacity: 1;
    }
    
    .save-bar.saving {
        pointer-events: none;
    }
    
    .save-indicator {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #6b7280;
    }
    
    .save-indicator .dot {
        width: 8px;
        height: 8px;
        background: #fbbf24;
        border-radius: 50%;
    }
    
    .save-bar.saved .save-indicator .dot {
        background: #10b981;
    }
    
    /* Toast */
    .toast {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        padding: 14px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        color: white;
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        opacity: 0;
        transition: all 0.3s;
        z-index: 1000;
    }
    
    .toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    
    .toast.error {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    }
</style>

<div class="settings-container">
    <!-- Sidebar Tabs -->
    <div class="settings-sidebar">
        <div class="bg-white rounded-2xl border border-gray-200 p-3 space-y-1">
            <button class="tab-button active" data-tab="general">
                <span class="material-symbols-outlined tab-icon text-[20px]">home</span>
                General
            </button>
            <button class="tab-button" data-tab="seo">
                <span class="material-symbols-outlined tab-icon text-[20px]">search</span>
                SEO
            </button>
            <?php 
            // Hide Theme tab if theme has its own premium settings panel (e.g., Aurora Pro)
            $activeTheme = defined('ZED_ACTIVE_THEME') ? ZED_ACTIVE_THEME : '';
            $themesWithOwnPanel = ['aurora-pro']; // Themes that have their own settings panel
            $hasOwnPanel = in_array($activeTheme, $themesWithOwnPanel);
            
            if (function_exists('zed_get_theme_settings') && !empty(zed_get_theme_settings()) && !$hasOwnPanel): 
            ?>
            <button class="tab-button" data-tab="theme">
                <span class="material-symbols-outlined tab-icon text-[20px]">palette</span>
                Theme
            </button>
            <?php endif; ?>
            <button class="tab-button" data-tab="system">
                <span class="material-symbols-outlined tab-icon text-[20px]">settings</span>
                System
            </button>
        </div>
    </div>

    <!-- Content Panels -->
    <div class="settings-content">
        
        <!-- ========== GENERAL TAB ========== -->
        <div class="settings-panel active" id="panel-general">
            <!-- Site Identity -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="material-symbols-outlined text-indigo-500">badge</span>
                    <h3>Site Identity</h3>
                </div>
                <div class="settings-card-body">
                    <div class="form-group">
                        <label class="form-label" for="site_title">Site Title</label>
                        <input type="text" id="site_title" name="site_title" class="form-input" 
                               value="<?= htmlspecialchars($site_title) ?>" placeholder="My Awesome Site">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="site_tagline">Tagline</label>
                        <input type="text" id="site_tagline" name="site_tagline" class="form-input" 
                               value="<?= htmlspecialchars($site_tagline) ?>" placeholder="A short description of your site">
                        <p class="form-hint">Briefly describe what your site is about.</p>
                    </div>
                </div>
            </div>
            
            <!-- Homepage Mode -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="material-symbols-outlined text-green-500">home</span>
                    <h3>Homepage Display</h3>
                </div>
                <div class="settings-card-body">
                    <div class="radio-group">
                        <label class="radio-option <?= $homepage_mode === 'latest_posts' ? 'selected' : '' ?>">
                            <input type="radio" name="homepage_mode" value="latest_posts" 
                                   <?= $homepage_mode === 'latest_posts' ? 'checked' : '' ?>>
                            <div class="radio-label">
                                <div class="radio-label-title">Latest Posts</div>
                                <div class="radio-label-desc">Display your most recent blog posts on the homepage.</div>
                            </div>
                        </label>
                        <label class="radio-option <?= $homepage_mode === 'static_page' ? 'selected' : '' ?>">
                            <input type="radio" name="homepage_mode" value="static_page"
                                   <?= $homepage_mode === 'static_page' ? 'checked' : '' ?>>
                            <div class="radio-label">
                                <div class="radio-label-title">Static Page</div>
                                <div class="radio-label-desc">Use a specific page as your homepage.</div>
                            </div>
                        </label>
                    </div>
                    
                    <!-- Static Page Settings (Conditional) -->
                    <div id="staticPageSettings" class="conditional-section <?= $homepage_mode === 'static_page' ? 'visible' : '' ?>">
                        <div class="form-group">
                            <label class="form-label" for="page_on_front">Homepage</label>
                            <select id="page_on_front" name="page_on_front" class="form-input form-select">
                                <option value="">— Select a page —</option>
                                <?php foreach ($pages as $page): ?>
                                <option value="<?= $page['id'] ?>" <?= $page_on_front == $page['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($page['title']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="form-hint">Select which page to display as your homepage.</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="blog_slug">Blog URL Slug</label>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400 text-sm"><?= htmlspecialchars($base_url) ?>/</span>
                                <input type="text" id="blog_slug" name="blog_slug" class="form-input" style="width: 150px;"
                                       value="<?= htmlspecialchars($blog_slug) ?>" placeholder="blog">
                            </div>
                            <p class="form-hint">Where your blog posts will be listed (e.g., 'blog', 'news', 'articles').</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="material-symbols-outlined text-purple-500">view_list</span>
                    <h3>Pagination</h3>
                </div>
                <div class="settings-card-body">
                    <div class="form-group">
                        <label class="form-label" for="posts_per_page">Posts Per Page</label>
                        <input type="number" id="posts_per_page" name="posts_per_page" class="form-input" 
                               style="width: 100px;" min="1" max="100"
                               value="<?= htmlspecialchars($posts_per_page) ?>">
                        <p class="form-hint">Number of posts to show on blog listing pages.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ========== SEO TAB ========== -->
        <div class="settings-panel" id="panel-seo">
            <!-- Search Visibility -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="material-symbols-outlined text-amber-500">visibility_off</span>
                    <h3>Search Engine Visibility</h3>
                </div>
                <div class="settings-card-body">
                    <div class="toggle-wrapper" style="border-bottom: none;">
                        <div class="toggle-label">
                            <div class="toggle-label-title">Discourage search engines</div>
                            <div class="toggle-label-desc">Ask search engines not to index this site. It's up to search engines to honor this request.</div>
                        </div>
                        <div class="toggle-switch <?= $discourage_search === '1' ? 'active' : '' ?>" 
                             data-setting="discourage_search_engines" data-value="<?= $discourage_search ?>"></div>
                    </div>
                </div>
            </div>
            
            <!-- Meta Defaults -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="material-symbols-outlined text-blue-500">description</span>
                    <h3>Default Meta Tags</h3>
                </div>
                <div class="settings-card-body">
                    <div class="form-group">
                        <label class="form-label" for="meta_description">Default Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-input" rows="3"
                                  placeholder="A brief description of your website for search engines..."><?= htmlspecialchars($meta_description) ?></textarea>
                        <p class="form-hint">Used when pages don't have their own meta description. Max 160 characters recommended.</p>
                    </div>
                </div>
            </div>
            
            <!-- Social -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="material-symbols-outlined text-pink-500">share</span>
                    <h3>Social Sharing</h3>
                </div>
                <div class="settings-card-body">
                    <div class="form-group">
                        <label class="form-label" for="social_sharing_image">Default Sharing Image</label>
                        <div class="flex gap-3">
                            <input type="text" id="social_sharing_image" name="social_sharing_image" class="form-input flex-1" 
                                   value="<?= htmlspecialchars($social_image) ?>" placeholder="https://example.com/image.jpg">
                            <button type="button" id="selectMediaBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">image</span>
                                Browse
                            </button>
                        </div>
                        <p class="form-hint">Default image used when content is shared on social media. Recommended size: 1200x630px.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (function_exists('zed_get_theme_settings') && !empty(zed_get_theme_settings()) && !$hasOwnPanel): ?>
        <!-- ========== THEME TAB ========== -->
        <div class="settings-panel" id="panel-theme">
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="material-symbols-outlined text-purple-500">palette</span>
                    <h3>Theme Options (<?= htmlspecialchars(defined('ZED_ACTIVE_THEME') ? ZED_ACTIVE_THEME : 'aurora') ?>)</h3>
                </div>
                <div class="settings-card-body">
                    <?php 
                    $themeSettings = zed_get_theme_settings();
                    foreach ($themeSettings as $setting): 
                        $currentValue = zed_theme_option($setting['id'], $setting['default']);
                        $fieldName = 'theme_' . $setting['id'];
                    ?>
                    <div class="form-group">
                        <label class="form-label" for="<?= $fieldName ?>"><?= htmlspecialchars($setting['label']) ?></label>
                        
                        <?php if ($setting['type'] === 'text'): ?>
                        <input type="text" id="<?= $fieldName ?>" name="<?= $fieldName ?>" class="form-input" 
                               value="<?= htmlspecialchars($currentValue) ?>">
                               
                        <?php elseif ($setting['type'] === 'textarea'): ?>
                        <textarea id="<?= $fieldName ?>" name="<?= $fieldName ?>" class="form-input" rows="3"><?= htmlspecialchars($currentValue) ?></textarea>
                        
                        <?php elseif ($setting['type'] === 'color'): ?>
                        <div class="flex items-center gap-3">
                            <input type="color" id="<?= $fieldName ?>" name="<?= $fieldName ?>" 
                                   value="<?= htmlspecialchars($currentValue) ?>" 
                                   class="w-12 h-10 rounded border border-gray-200 cursor-pointer">
                            <input type="text" value="<?= htmlspecialchars($currentValue) ?>" 
                                   class="form-input w-32" 
                                   oninput="document.getElementById('<?= $fieldName ?>').value = this.value"
                                   onchange="document.getElementById('<?= $fieldName ?>').value = this.value">
                        </div>
                        
                        <?php elseif ($setting['type'] === 'checkbox'): ?>
                        <div class="toggle-wrapper" style="border-bottom: none; padding: 0;">
                            <div></div>
                            <div class="toggle-switch <?= $currentValue ? 'active' : '' ?>" 
                                 data-setting="<?= $fieldName ?>" data-value="<?= $currentValue ? '1' : '0' ?>"></div>
                        </div>
                        
                        <?php elseif ($setting['type'] === 'select' && !empty($setting['options'])): ?>
                        <select id="<?= $fieldName ?>" name="<?= $fieldName ?>" class="form-input form-select">
                            <?php foreach ($setting['options'] as $value => $label): ?>
                            <option value="<?= htmlspecialchars($value) ?>" <?= $currentValue == $value ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- ========== SYSTEM TAB ========== -->
        <div class="settings-panel" id="panel-system">
            <!-- System Toggles -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="material-symbols-outlined text-gray-500">tune</span>
                    <h3>System Settings</h3>
                </div>
                <div class="settings-card-body" style="padding: 8px 24px;">
                    <div class="toggle-wrapper">
                        <div class="toggle-label">
                            <div class="toggle-label-title">Maintenance Mode</div>
                            <div class="toggle-label-desc">When enabled, visitors will see a maintenance page instead of the site.</div>
                        </div>
                        <div class="toggle-switch <?= $maintenance_mode === '1' ? 'active' : '' ?>" 
                             data-setting="maintenance_mode" data-value="<?= $maintenance_mode ?>"></div>
                    </div>
                    <div class="toggle-wrapper">
                        <div class="toggle-label">
                            <div class="toggle-label-title">Debug Mode</div>
                            <div class="toggle-label-desc">Display detailed error messages. Only enable this on development sites.</div>
                        </div>
                        <div class="toggle-switch <?= $debug_mode === '1' ? 'active' : '' ?>" 
                             data-setting="debug_mode" data-value="<?= $debug_mode ?>"></div>
                    </div>
                </div>
            </div>
            
            <!-- System Info -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="material-symbols-outlined text-indigo-500">info</span>
                    <h3>System Information</h3>
                </div>
                <div class="settings-card-body">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">PHP Version</span>
                            <div class="font-semibold text-gray-900"><?= phpversion() ?></div>
                        </div>
                        <div>
                            <span class="text-gray-500">Server</span>
                            <div class="font-semibold text-gray-900"><?= php_uname('s') ?></div>
                        </div>
                        <div>
                            <span class="text-gray-500">Zed CMS Version</span>
                            <div class="font-semibold text-gray-900">1.4.0</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Database</span>
                            <div class="font-semibold text-gray-900">MySQL (PDO)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Sticky Save Bar -->
<div id="saveBar" class="save-bar">
    <div class="save-indicator">
        <span class="dot"></span>
        <span id="saveStatus">Unsaved changes</span>
    </div>
    <button id="saveBtn" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
        <span id="saveBtnIcon" class="material-symbols-outlined text-[18px]">save</span>
        <span id="saveBtnText">Save Changes</span>
    </button>
</div>

<!-- Toast -->
<div id="toast" class="toast"></div>

<script>
(function() {
    'use strict';
    
    const API_SAVE = '<?= Router::url('/admin/api/save-settings') ?>';
    
    // Elements
    const tabButtons = document.querySelectorAll('.tab-button');
    const panels = document.querySelectorAll('.settings-panel');
    const saveBar = document.getElementById('saveBar');
    const saveBtn = document.getElementById('saveBtn');
    const saveBtnText = document.getElementById('saveBtnText');
    const saveBtnIcon = document.getElementById('saveBtnIcon');
    const saveStatus = document.getElementById('saveStatus');
    const toast = document.getElementById('toast');
    
    // Track changes
    let hasChanges = false;
    let originalValues = {};
    
    // ========== TABS ==========
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update buttons
            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // Update panels
            const tabId = btn.dataset.tab;
            panels.forEach(p => p.classList.remove('active'));
            document.getElementById('panel-' + tabId).classList.add('active');
        });
    });
    
    // ========== RADIO OPTIONS STYLING ==========
    const radioOptions = document.querySelectorAll('.radio-option');
    radioOptions.forEach(opt => {
        opt.addEventListener('click', function() {
            const name = this.querySelector('input[type="radio"]').name;
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.closest('.radio-option').classList.remove('selected');
            });
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            
            // Handle homepage mode toggle
            if (name === 'homepage_mode') {
                const mode = this.querySelector('input').value;
                const staticSettings = document.getElementById('staticPageSettings');
                if (mode === 'static_page') {
                    staticSettings.classList.add('visible');
                } else {
                    staticSettings.classList.remove('visible');
                }
            }
            
            markChanged();
        });
    });
    
    // ========== TOGGLE SWITCHES ==========
    const toggleSwitches = document.querySelectorAll('.toggle-switch');
    toggleSwitches.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const isActive = this.classList.toggle('active');
            this.dataset.value = isActive ? '1' : '0';
            markChanged();
        });
    });
    
    // ========== TRACK FORM CHANGES ==========
    const allInputs = document.querySelectorAll('input, textarea, select');
    
    // Store original values
    allInputs.forEach(input => {
        if (input.type === 'radio') {
            if (input.checked) {
                originalValues[input.name] = input.value;
            }
        } else {
            originalValues[input.id] = input.value;
        }
    });
    
    // Listen for changes
    allInputs.forEach(input => {
        input.addEventListener('input', markChanged);
        input.addEventListener('change', markChanged);
    });
    
    function markChanged() {
        hasChanges = true;
        saveBar.classList.add('visible');
        saveBar.classList.remove('saved');
        saveStatus.textContent = 'Unsaved changes';
    }
    
    // ========== SAVE ==========
    async function saveSettings() {
        if (!hasChanges) return;
        
        // Show loading state
        saveBtn.disabled = true;
        saveBtnText.textContent = 'Saving...';
        saveBtnIcon.textContent = 'sync';
        saveBtnIcon.classList.add('animate-spin');
        saveBar.classList.add('saving');
        
        // Collect all values
        const data = {};
        
        // Text inputs and textareas from General tab
        document.querySelectorAll('#panel-general input, #panel-general textarea, #panel-general select').forEach(input => {
            if (input.type !== 'radio' && input.name) {
                data[input.name] = input.value;
            }
        });
        
        // SEO tab inputs
        document.querySelectorAll('#panel-seo input, #panel-seo textarea, #panel-seo select').forEach(input => {
            if (input.name) {
                data[input.name] = input.value;
            }
        });
        
        // Theme tab inputs (FIX: This was missing before!)
        document.querySelectorAll('#panel-theme input, #panel-theme textarea, #panel-theme select').forEach(input => {
            if (input.name) {
                data[input.name] = input.value;
            }
        });
        
        // System tab inputs
        document.querySelectorAll('#panel-system input, #panel-system textarea, #panel-system select').forEach(input => {
            if (input.name) {
                data[input.name] = input.value;
            }
        });
        
        // Radio buttons
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            data[radio.name] = radio.value;
        });
        
        // Toggle switches
        toggleSwitches.forEach(toggle => {
            data[toggle.dataset.setting] = toggle.dataset.value;
        });
        
        // DEBUG: Log what we're sending
        console.log('=== SETTINGS SAVE DEBUG ===');
        console.log('Sending data:', JSON.stringify(data, null, 2));
        console.log('Theme settings in payload:', Object.keys(data).filter(k => k.startsWith('theme_')));
        
        try {
            const response = await fetch(API_SAVE, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-ZED-NONCE': window.ZED_NONCE || ''
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            // DEBUG: Log response
            console.log('Save response:', result);
            
            if (result.success) {
                hasChanges = false;
                saveBar.classList.add('saved');
                saveStatus.textContent = 'All changes saved';
                showToast('Settings saved successfully!');
                
                // Hide save bar after delay
                setTimeout(() => {
                    if (!hasChanges) {
                        saveBar.classList.remove('visible');
                    }
                }, 2000);
            } else {
                showToast(result.error || 'Failed to save settings.', 'error');
            }
        } catch (err) {
            showToast('An error occurred. Please try again.', 'error');
        }
        
        // Reset button
        saveBtn.disabled = false;
        saveBtnText.textContent = 'Save Changes';
        saveBtnIcon.textContent = 'save';
        saveBtnIcon.classList.remove('animate-spin');
        saveBar.classList.remove('saving');
    }
    
    saveBtn.addEventListener('click', saveSettings);
    
    // ========== KEYBOARD SHORTCUT (Ctrl+S) ==========
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            saveSettings();
        }
    });
    
    // ========== TOAST ==========
    let toastTimeout;
    function showToast(message, type = 'success') {
        clearTimeout(toastTimeout);
        toast.textContent = message;
        toast.className = 'toast ' + type;
        toast.classList.add('show');
        toastTimeout = setTimeout(() => toast.classList.remove('show'), 4000);
    }
    
    // ========== BEFORE UNLOAD WARNING ==========
    window.addEventListener('beforeunload', (e) => {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
})();
</script>
