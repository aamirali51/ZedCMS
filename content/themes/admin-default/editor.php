<?php
/**
 * Zed CMS Editor v4.2
 * 
 * Matches admin-default theme colors and styling
 * Supports dark mode
 */

use Core\Auth;
use Core\Database;
use Core\Router;
use Core\Event;

if (!Auth::check()) {
    Router::redirect('/admin/login');
}

$base_url = Router::getBasePath();

// Load post data
$postId = $_GET['id'] ?? null;
$post = null;
$data = [];
$postStatus = 'draft';
$postExcerpt = '';
$featuredImageUrl = '';

if ($postId) {
    try {
        $db = Database::getInstance();
        $post = $db->queryOne("SELECT * FROM zed_content WHERE id = :id", ['id' => $postId]);
        
        if ($post) {
            $data = is_string($post['data']) ? json_decode($post['data'], true) : ($post['data'] ?? []);
            if (!is_array($data)) $data = [];
            
            $featuredImageUrl = $data['featured_image'] ?? '';
            $postStatus = $data['status'] ?? 'draft';
            $postExcerpt = $data['excerpt'] ?? '';
        }
    } catch (Exception $e) {
        $post = null;
    }
}

// Editor content
$jsonContent = isset($data['content']) ? json_encode($data['content']) : '[]';
$defaultBlock = [[
    'id' => uniqid('block_'),
    'type' => 'paragraph',
    'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'],
    'content' => [],
    'children' => [],
]];

$decoded = json_decode($jsonContent, true);
$initialDataSafe = (!is_array($decoded) || empty($decoded)) ? json_encode($defaultBlock) : $jsonContent;

$currentType = $post['type'] ?? ($_GET['type'] ?? 'post');

global $ZED_POST_TYPES;
$types = !empty($ZED_POST_TYPES) ? $ZED_POST_TYPES : [
    'post' => ['label' => 'Post'],
    'page' => ['label' => 'Page']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $post ? htmlspecialchars($post['title']) : 'New Entry' ?> — Zed CMS</title>
    
    <!-- Dark Mode (prevent flash) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        'primary-hover': '#4f46e5',
                    },
                    fontFamily: { sans: ["Inter", "system-ui", "sans-serif"] },
                },
            },
        }
    </script>
    
    <script>
        window.ZED_NONCE = "<?= function_exists('zed_create_nonce') ? zed_create_nonce('zed_admin_action') : '' ?>";
        window.ZED_BASE_URL = "<?= $base_url ?>";
        window.ZED_SITE_NAME = "<?= htmlspecialchars(function_exists('zed_get_site_name') ? zed_get_site_name() : 'Zed CMS') ?>";
    </script>
    
    <?php Event::trigger('zed_admin_head'); ?>
    
    <link rel="stylesheet" href="<?= $base_url ?>/content/themes/admin-default/assets/js/assets/main.css">
    
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; margin: 0; }
        
        /* Layout */
        .editor-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            height: 100vh;
            transition: grid-template-columns 0.2s;
        }
        
        .editor-layout.panel-hidden {
            grid-template-columns: 1fr 0;
        }
        
        .editor-layout.panel-hidden .sidebar {
            display: none;
        }
        
        /* Header */
        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 340px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 100;
            transition: right 0.2s;
        }
        
        .editor-layout.panel-hidden .top-bar {
            right: 0;
        }
        
        /* Main */
        .main-area {
            padding-top: 56px;
            height: 100vh;
            overflow-y: auto;
        }
        
        /* ================================================
           TipTap Editor Theme - Clean & Minimal
           ================================================ */
        
        /* Editor Container */
        #tiptap-editor {
            width: 100%;
            max-width: calc(100vw - 340px);
            min-height: calc(100vh - 56px);
            padding: 40px 24px;
            box-sizing: border-box;
        }
        
        .editor-layout.panel-hidden #tiptap-editor {
            max-width: 100vw;
        }
        
        /* ProseMirror Editor Core */
        #tiptap-editor .ProseMirror {
            outline: none;
            max-width: 100%;
            width: 100%;
        }
        
        #tiptap-editor .ProseMirror > * {
            max-width: 720px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Floating Menu / Tippy.js - constrain width */
        [data-tippy-root] {
            width: auto !important;
            max-width: 260px !important;
        }
        
        .floating-menu,
        .slash-menu {
            width: 240px !important;
            max-width: 240px !important;
        }
        
        .bubble-menu {
            z-index: 10000;
        }
        
        /* Sidebar */
        .sidebar {
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .sidebar-tabs {
            display: flex;
            padding: 12px 16px;
            gap: 6px;
        }
        
        .sidebar-tab {
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s;
        }
        
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 16px 20px;
        }
        
        .sidebar-content::-webkit-scrollbar { width: 4px; }
        .sidebar-content::-webkit-scrollbar-thumb { border-radius: 2px; }
        
        /* Form */
        .form-group { margin-bottom: 18px; }
        
        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        
        .form-input {
            width: 100%;
            padding: 10px 12px;
            font-size: 13px;
            border-radius: 8px;
            transition: all 0.15s;
        }
        
        .form-input:focus {
            outline: none;
        }
        
        textarea.form-input { resize: vertical; min-height: 80px; }
        
        .form-hint {
            font-size: 11px;
            margin-top: 5px;
        }
        
        /* Slug */
        .slug-box {
            display: flex;
            align-items: center;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .slug-prefix {
            padding: 10px 0 10px 12px;
            font-size: 12px;
        }
        
        .slug-input {
            flex: 1;
            padding: 10px 12px 10px 2px;
            font-size: 13px;
            border: none;
            outline: none;
            background: transparent;
        }
        
        /* Upload */
        .upload-area {
            border: 2px dashed;
            border-radius: 10px;
            padding: 28px;
            text-align: center;
            cursor: pointer;
            transition: all 0.15s;
        }
        
        .upload-area.has-image {
            padding: 6px;
            border-style: solid;
        }
        
        .upload-area.has-image img {
            width: 100%;
            border-radius: 6px;
        }
        
        /* Toggle */
        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
        }
        
        .toggle-switch {
            width: 44px;
            height: 24px;
            border-radius: 12px;
            position: relative;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .toggle-switch::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: transform 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }
        
        .toggle-switch.on::after { transform: translateX(20px); }
        
        /* Categories */
        .cat-list {
            border-radius: 8px;
            max-height: 160px;
            overflow-y: auto;
        }
        
        .cat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.1s;
        }
        
        /* Collapsible */
        .collapse-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 10px 0;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
        }
        
        .collapse-btn .title { font-size: 13px; font-weight: 600; }
        .collapse-btn .icon { font-size: 20px; transition: transform 0.2s; }
        .collapse-btn.open .icon { transform: rotate(180deg); }
        .collapse-body { display: none; padding-bottom: 12px; }
        .collapse-body.open { display: block; }
        
        /* SEO Preview */
        .seo-box {
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 18px;
        }
        
        .seo-url { font-size: 12px; }
        .seo-title { font-size: 16px; margin: 4px 0; line-height: 1.3; }
        .seo-desc { font-size: 13px; line-height: 1.4; }
        
        /* Animation */
        @keyframes spin { to { transform: rotate(360deg); } }
        .animate-spin { animation: spin 1s linear infinite; }
    </style>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors">

<div id="app" class="editor-layout">
    <!-- Main Area -->
    <div class="main-area bg-white dark:bg-slate-900">
        <!-- Top Bar -->
        <header class="top-bar bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
            <div class="flex items-center gap-4">
                <a href="<?= $base_url ?>/admin/content" class="flex items-center gap-2 px-3 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span>
                    Content
                </a>
                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase <?= $postStatus === 'published' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400' ?>">
                    <?= $postStatus ?>
                </span>
            </div>
            <div class="flex items-center gap-3">
                <span id="save-status" class="text-xs text-slate-400 dark:text-slate-500"></span>
                <button class="w-9 h-9 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" onclick="document.getElementById('app').classList.toggle('panel-hidden')">
                    <span class="material-symbols-outlined">right_panel_open</span>
                </button>
                <button id="save-btn" class="flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold transition-colors">
                    <span class="material-symbols-outlined" style="font-size: 18px;">check</span>
                    Save
                </button>
            </div>
        </header>
        
        <!-- Editor -->
        <div id="tiptap-editor"></div>
    </div>
    
    <!-- Sidebar -->
    <aside class="sidebar bg-white dark:bg-slate-900 border-l border-gray-200 dark:border-slate-800">
        <div class="sidebar-tabs border-b border-gray-100 dark:border-slate-800">
            <div class="sidebar-tab active bg-primary text-white" data-tab="doc">Document</div>
            <div class="sidebar-tab text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300" data-tab="seo">SEO</div>
        </div>
        
        <div class="sidebar-content">
            <!-- Document Tab -->
            <div class="tab-pane" data-tab="doc">
                <div class="form-group">
                    <label class="form-label text-slate-500 dark:text-slate-400">Title</label>
                    <input type="text" id="post-title" class="form-input bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20" value="<?= htmlspecialchars($post['title'] ?? '') ?>" placeholder="Post title...">
                </div>
                
                <div class="form-group">
                    <label class="form-label text-slate-500 dark:text-slate-400">URL Slug</label>
                    <div class="slug-box bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                        <span class="slug-prefix text-slate-400 dark:text-slate-500">/</span>
                        <input type="text" id="post-slug" class="slug-input text-slate-700 dark:text-slate-300" value="<?= htmlspecialchars($post['slug'] ?? '') ?>" placeholder="url-slug">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label text-slate-500 dark:text-slate-400">Status</label>
                        <select id="post-status" class="form-input bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white">
                            <option value="draft" <?= $postStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= $postStatus === 'published' ? 'selected' : '' ?>>Published</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-slate-500 dark:text-slate-400">Type</label>
                        <select id="post-type" class="form-input bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white">
                            <?php foreach ($types as $typeKey => $config): ?>
                            <option value="<?= $typeKey ?>" <?= $currentType === $typeKey ? 'selected' : '' ?>><?= htmlspecialchars($config['label'] ?? ucfirst($typeKey)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label text-slate-500 dark:text-slate-400">Featured Image</label>
                    <div id="upload-box" class="upload-area border-slate-200 dark:border-slate-700 hover:border-primary dark:hover:border-primary bg-slate-50 dark:bg-slate-800 <?= $featuredImageUrl ? 'has-image' : '' ?>" onclick="document.getElementById('file-input').click()">
                        <?php if ($featuredImageUrl): ?>
                            <img id="preview-img" src="<?= htmlspecialchars($featuredImageUrl) ?>">
                        <?php else: ?>
                            <span class="material-symbols-outlined text-3xl text-slate-300 dark:text-slate-600">add_photo_alternate</span>
                            <div class="text-xs text-slate-400 dark:text-slate-500 mt-2">Click to upload</div>
                            <img id="preview-img" style="display:none;">
                        <?php endif; ?>
                        <input type="file" id="file-input" accept="image/*" style="display:none;">
                    </div>
                    <button type="button" id="remove-img" class="text-xs text-red-500 mt-2 cursor-pointer bg-transparent border-none" style="display:<?= $featuredImageUrl ? 'inline' : 'none' ?>">Remove</button>
                </div>
                
                <div class="form-group">
                    <button class="collapse-btn text-slate-700 dark:text-slate-300" onclick="this.classList.toggle('open'); this.nextElementSibling.classList.toggle('open');">
                        <span class="title">Excerpt</span>
                        <span class="material-symbols-outlined icon text-slate-400">expand_more</span>
                    </button>
                    <div class="collapse-body">
                        <textarea id="post-excerpt" class="form-input bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white" rows="3" placeholder="Brief summary..."><?= htmlspecialchars($postExcerpt) ?></textarea>
                        <div class="form-hint text-slate-400 dark:text-slate-500">Used in archives and search</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <button class="collapse-btn text-slate-700 dark:text-slate-300" onclick="this.classList.toggle('open'); this.nextElementSibling.classList.toggle('open');">
                        <span class="title">Categories</span>
                        <span class="material-symbols-outlined icon text-slate-400">expand_more</span>
                    </button>
                    <div class="collapse-body">
                        <div id="cat-list" class="cat-list bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                            <div class="cat-item text-slate-400">Loading...</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SEO Tab -->
            <div class="tab-pane" data-tab="seo" style="display:none;">
                <div class="form-group">
                    <label class="form-label text-slate-500 dark:text-slate-400">Search Preview</label>
                    <div class="seo-box bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                        <div id="seo-url" class="seo-url text-green-600 dark:text-green-400"><?= str_replace(['https://', 'http://'], '', $base_url) ?>/...</div>
                        <div id="seo-title" class="seo-title text-blue-600 dark:text-blue-400">Page Title</div>
                        <div id="seo-desc" class="seo-desc text-slate-600 dark:text-slate-400">Add a meta description...</div>
                    </div>
                </div>
                
                <?php 
                if (function_exists('zed_get_metaboxes_for_type')) {
                    $metaboxes = zed_get_metaboxes_for_type($currentType);
                    foreach ($metaboxes as $metaboxId => $metabox) {
                        if (function_exists('zed_render_metabox_field')) {
                            $meta = [];
                            if (!empty($post['data'])) {
                                $postData = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
                                $meta = $postData['meta'] ?? [];
                            }
                            
                            foreach ($metabox['fields'] as $field) {
                                if (($field['type'] ?? '') !== 'html') {
                                    $fieldId = $field['id'] ?? '';
                                    $value = $meta[$fieldId] ?? ($field['default'] ?? '');
                                    $name = "meta[{$fieldId}]";
                                    
                                    echo '<div class="form-group">';
                                    echo '<label class="form-label text-slate-500 dark:text-slate-400">' . htmlspecialchars($field['label'] ?? '') . '</label>';
                                    
                                    if (($field['type'] ?? '') === 'textarea') {
                                        echo '<textarea name="' . $name . '" class="form-input bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white" rows="2" placeholder="' . htmlspecialchars($field['placeholder'] ?? '') . '">' . htmlspecialchars($value) . '</textarea>';
                                    } elseif (($field['type'] ?? '') === 'select') {
                                        echo '<select name="' . $name . '" class="form-input bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white">';
                                        foreach (($field['options'] ?? []) as $optVal => $optLabel) {
                                            echo '<option value="' . htmlspecialchars($optVal) . '"' . ($value == $optVal ? ' selected' : '') . '>' . htmlspecialchars($optLabel) . '</option>';
                                        }
                                        echo '</select>';
                                    } elseif (($field['type'] ?? '') === 'toggle') {
                                        $isOn = ($value === 'true' || $value === '1' || $value === true);
                                        echo '<div class="toggle-row border-b border-slate-100 dark:border-slate-800">';
                                        echo '<span class="text-sm text-slate-600 dark:text-slate-400">' . htmlspecialchars($field['description'] ?? 'Enable') . '</span>';
                                        echo '<div class="toggle-switch ' . ($isOn ? 'on bg-primary' : 'bg-slate-200 dark:bg-slate-700') . '" onclick="this.classList.toggle(\'on\'); this.classList.toggle(\'bg-primary\'); this.classList.toggle(\'bg-slate-200\'); this.classList.toggle(\'dark:bg-slate-700\'); this.nextElementSibling.checked = this.classList.contains(\'on\');"><span class="after:bg-white"></span></div>';
                                        echo '<input type="checkbox" name="' . $name . '" value="true"' . ($isOn ? ' checked' : '') . ' style="display:none;">';
                                        echo '</div>';
                                    } else {
                                        echo '<input type="text" name="' . $name . '" class="form-input bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:border-primary" value="' . htmlspecialchars($value) . '" placeholder="' . htmlspecialchars($field['placeholder'] ?? '') . '">';
                                    }
                                    
                                    if (!empty($field['description']) && ($field['type'] ?? '') !== 'toggle') {
                                        echo '<div class="form-hint text-slate-400 dark:text-slate-500">' . htmlspecialchars($field['description']) . '</div>';
                                    }
                                    echo '</div>';
                                }
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </aside>
</div>

<script>
    window.ZED_INITIAL_CONTENT = <?= $initialDataSafe ?>;
    if (!window.ZED_INITIAL_CONTENT || !Array.isArray(window.ZED_INITIAL_CONTENT) || window.ZED_INITIAL_CONTENT.length === 0) {
        window.ZED_INITIAL_CONTENT = [{ id: 'default_' + Date.now(), type: 'paragraph', props: { textColor: 'default', backgroundColor: 'default', textAlignment: 'left' }, content: [], children: [] }];
    }
    window.zed_editor_content = window.ZED_INITIAL_CONTENT;
    
    let postId = "<?= htmlspecialchars($postId ?? '') ?>";
    const baseUrl = "<?= $base_url ?>";
    let featuredImageUrl = "<?= htmlspecialchars($featuredImageUrl) ?>";
</script>

<script type="module" src="<?= $base_url ?>/content/themes/admin-default/assets/js/editor.bundle.js"></script>

<script>
// Tabs
document.querySelectorAll('.sidebar-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.sidebar-tab').forEach(t => {
            t.classList.remove('active', 'bg-primary', 'text-white');
            t.classList.add('text-slate-500', 'dark:text-slate-400');
        });
        document.querySelectorAll('.tab-pane').forEach(p => p.style.display = 'none');
        tab.classList.add('active', 'bg-primary', 'text-white');
        tab.classList.remove('text-slate-500', 'dark:text-slate-400');
        document.querySelector('.tab-pane[data-tab="' + tab.dataset.tab + '"]').style.display = 'block';
    });
});

// Slug
const titleEl = document.getElementById('post-title');
const slugEl = document.getElementById('post-slug');
const genSlug = t => t.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
titleEl?.addEventListener('input', () => { if (!postId) slugEl.value = genSlug(titleEl.value); updateSEO(); });
slugEl?.addEventListener('input', () => { slugEl.value = genSlug(slugEl.value); updateSEO(); });

// SEO
function updateSEO() {
    const title = document.querySelector('[name="meta[seo_title]"]')?.value || titleEl?.value || 'Page Title';
    const desc = document.querySelector('[name="meta[seo_desc]"]')?.value || 'Add a meta description...';
    const slug = slugEl?.value || 'page';
    document.getElementById('seo-url').textContent = '<?= str_replace(['https://', 'http://'], '', $base_url) ?>/' + slug;
    document.getElementById('seo-title').textContent = title.substring(0, 60);
    document.getElementById('seo-desc').textContent = desc.substring(0, 160);
}
document.querySelector('[name="meta[seo_title]"]')?.addEventListener('input', updateSEO);
document.querySelector('[name="meta[seo_desc]"]')?.addEventListener('input', updateSEO);
setTimeout(updateSEO, 300);

// Categories
(async () => {
    const list = document.getElementById('cat-list');
    try {
        const cats = await (await fetch('<?= Router::url("/admin/api/categories") ?>')).json();
        const saved = <?= json_encode($data['categories'] ?? []) ?>;
        if (cats.length === 0) { list.innerHTML = '<div class="cat-item text-slate-400">No categories</div>'; return; }
        list.innerHTML = cats.map(c => `<label class="cat-item text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border-b border-slate-100 dark:border-slate-700 last:border-0"><input type="checkbox" name="categories[]" value="${c.slug}" class="accent-primary" ${saved.includes(c.slug) ? 'checked' : ''}> ${c.name}</label>`).join('');
    } catch { list.innerHTML = '<div class="cat-item text-red-500">Failed to load</div>'; }
})();

// Image
const fileInput = document.getElementById('file-input');
const uploadBox = document.getElementById('upload-box');
const previewImg = document.getElementById('preview-img');
const removeBtn = document.getElementById('remove-img');

fileInput?.addEventListener('change', async e => {
    const file = e.target.files[0]; if (!file) return;
    const fd = new FormData(); fd.append('image', file);
    try {
        const r = await (await fetch(baseUrl + '/admin/api/upload', { method: 'POST', body: fd })).json();
        if (r.success && r.file?.url) {
            featuredImageUrl = r.file.url;
            previewImg.src = featuredImageUrl;
            previewImg.style.display = 'block';
            uploadBox.classList.add('has-image');
            removeBtn.style.display = 'inline';
            uploadBox.querySelectorAll('.material-symbols-outlined, .text-xs').forEach(el => el.style.display = 'none');
        }
    } catch { alert('Upload failed'); }
});

removeBtn?.addEventListener('click', e => {
    e.stopPropagation();
    featuredImageUrl = '';
    previewImg.src = '';
    previewImg.style.display = 'none';
    uploadBox.classList.remove('has-image');
    removeBtn.style.display = 'none';
    uploadBox.querySelectorAll('.material-symbols-outlined, .text-xs').forEach(el => el.style.display = '');
});

// Save
document.getElementById('save-btn')?.addEventListener('click', async function() {
    const btn = this; btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin" style="font-size: 18px;">sync</span> Saving...';
    
    const payload = {
        id: postId,
        title: titleEl?.value || 'Untitled',
        slug: slugEl?.value || '',
        status: document.getElementById('post-status')?.value || 'draft',
        type: document.getElementById('post-type')?.value || 'post',
        content: JSON.stringify(window.zed_editor_content || []),
        data: {
            featured_image: featuredImageUrl,
            excerpt: document.getElementById('post-excerpt')?.value || '',
            categories: Array.from(document.querySelectorAll('input[name="categories[]"]:checked')).map(c => c.value),
            meta: {}
        }
    };
    
    document.querySelectorAll('[name^="meta["]').forEach(el => {
        const m = el.name.match(/^meta\[([^\]]+)\]$/);
        if (m) payload.data.meta[m[1]] = el.type === 'checkbox' ? (el.checked ? 'true' : 'false') : el.value;
    });
    
    try {
        const res = await fetch(baseUrl + '/admin/save-post', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-ZED-NONCE': window.ZED_NONCE || '' },
            body: JSON.stringify(payload)
        });
        const r = await res.json();
        
        if (r.success) {
            btn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">check</span> Saved!';
            document.getElementById('save-status').textContent = 'Saved ' + new Date().toLocaleTimeString();
            if (!postId && r.new_id) { history.pushState({}, '', '?id=' + r.new_id); postId = r.new_id; }
            setTimeout(() => { btn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">check</span> Save'; btn.disabled = false; }, 1500);
        } else {
            alert('Error: ' + (r.message || r.error));
            btn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">check</span> Save'; btn.disabled = false;
        }
    } catch { alert('Network error'); btn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">check</span> Save'; btn.disabled = false; }
});

document.addEventListener('keydown', e => { if ((e.ctrlKey || e.metaKey) && e.key === 's') { e.preventDefault(); document.getElementById('save-btn')?.click(); } });
</script>

</body>
</html>