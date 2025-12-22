<?php
/**
 * Zed CMS Admin Layout
 * 
 * Master layout template for all admin pages.
 * Receives:
 * - $current_page: Active page identifier (dashboard, content, media, users, addons, settings)
 * - $page_title: Title for breadcrumb
 * - $content_partial: Path to the content partial to include
 * - All other variables passed through from admin_addon.php
 */

use Core\Router;
use Core\Auth;

// Defaults
$current_page = $current_page ?? 'dashboard';
$page_title = $page_title ?? 'Dashboard';
$base_url = Router::getBasePath();
$current_user = $current_user ?? Auth::user() ?? ['email' => 'admin@ZED.local', 'role' => 'admin'];

// User info with role badge
$user_email = $current_user['email'] ?? 'admin@ZED.local';
$user_role = $current_user['role'] ?? 'admin';
$user_role_info = zed_get_role_info($user_role);
$user_initials = strtoupper(substr($user_email, 0, 2));

// Dynamic nav items based on user capabilities (RBAC)
$nav_items = zed_get_admin_menu_items();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= htmlspecialchars($page_title) ?> — Zed Panel</title>
    
    <!-- Dark Mode Script (prevent flash) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS with Dark Mode -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        'primary-hover': '#4f46e5',
                    },
                    fontFamily: {
                        body: ['Inter', 'sans-serif'],
                        display: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Local Admin Styles -->
    <link rel="stylesheet" href="<?= $base_url ?>/content/themes/admin-default/assets/js/assets/main.css">

    <style>
        /* Smooth transitions for content loading */
        .content-fade-in {
            animation: fadeIn 0.2s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <?php \Core\Event::trigger('zed_admin_head'); ?>
</head>
<body class="font-body bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 h-screen flex overflow-hidden transition-colors duration-200">

<!-- Sidebar -->
<aside class="w-[250px] bg-white dark:bg-slate-900 border-r border-gray-200 dark:border-slate-800 flex flex-col flex-shrink-0 z-30 transition-colors">
    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-slate-800">
        <a href="<?= $base_url ?>/admin" class="flex items-center gap-3">
            <div class="w-8 h-8 bg-black dark:bg-white text-white dark:text-black rounded flex items-center justify-center font-display font-bold text-lg transition-colors">
                Z
            </div>
            <span class="font-display font-bold text-xl tracking-tight text-gray-900 dark:text-white">ZED</span>
        </a>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 py-6 space-y-1 overflow-y-auto">
        <?php foreach ($nav_items as $item): 
            $is_active = $current_page === $item['id'];
        ?>
        <?php if ($is_active): ?>
        <a class="flex items-center gap-3 px-6 py-3 border-l-4 border-indigo-500 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 font-semibold transition-all" 
           href="<?= htmlspecialchars($item['url']) ?>">
            <span class="material-symbols-outlined text-[20px] text-indigo-600 dark:text-indigo-400">
                <?= $item['icon'] ?>
            </span>
            <span><?= $item['label'] ?></span>
        </a>
        <?php else: ?>
        <a class="flex items-center gap-3 px-6 py-3 border-l-4 border-transparent text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800 font-medium transition-all group" 
           href="<?= htmlspecialchars($item['url']) ?>">
            <span class="material-symbols-outlined text-[20px] text-gray-400 dark:text-slate-500 group-hover:text-gray-600 dark:group-hover:text-slate-300">
                <?= $item['icon'] ?>
            </span>
            <span><?= $item['label'] ?></span>
        </a>
        <?php endif; ?>
        <?php endforeach; ?>
    </nav>
    
    <!-- Bottom Section -->
    <div class="p-4 border-t border-gray-100 dark:border-slate-800 space-y-2">
        <!-- User Info with Role Badge -->
        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800">
            <div class="w-8 h-8 rounded-full bg-<?= $user_role_info['color'] ?>-600 text-white flex items-center justify-center text-xs font-bold">
                <?= $user_initials ?>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-900 dark:text-white truncate"><?= htmlspecialchars($user_email) ?></div>
                <div class="flex items-center gap-1 text-xs text-<?= $user_role_info['color'] ?>-600 dark:text-<?= $user_role_info['color'] ?>-400">
                    <span class="material-symbols-outlined text-[12px]"><?= $user_role_info['icon'] ?></span>
                    <?= $user_role_info['label'] ?>
                </div>
            </div>
        </div>
        
        <!-- Logout -->
        <a href="<?= $base_url ?>/admin/logout" 
           class="flex items-center justify-center gap-2 p-2.5 rounded-lg bg-red-50 border border-red-100 text-red-600 hover:bg-red-100 transition-colors text-sm font-medium">
            <span class="material-symbols-outlined text-[18px]">logout</span>
            <span>Logout</span>
        </a>
    </div>
</aside>

<!-- Main Content Area -->
<main class="flex-1 flex flex-col min-w-0 overflow-hidden">
    <!-- Top Header Bar -->
    <header class="h-14 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800 flex items-center justify-between px-6 flex-shrink-0 z-20 transition-colors">
        <!-- Breadcrumb -->
        <div class="flex items-center text-sm">
            <a href="<?= $base_url ?>/admin" class="text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">Admin</a>
            <span class="material-symbols-outlined text-[16px] mx-2 text-gray-300 dark:text-slate-600">chevron_right</span>
            <span class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($page_title) ?></span>
        </div>
        
        <!-- Right Actions -->
        <div class="flex items-center gap-3">
            <?php if ($current_page === 'content' || str_starts_with($current_page, 'cpt_')): ?>
            <a href="<?= $base_url ?>/admin/editor?new=true<?= !empty($type) ? '&type=' . htmlspecialchars($type) : '' ?>" 
               class="flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                New <?= !empty($type) && $type !== 'post' ? ucfirst($type) : 'Content' ?>
            </a>
            <?php endif; ?>
            
            <!-- Dark Mode Toggle -->
            <button id="theme-toggle" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-lg transition-colors" title="Toggle dark mode">
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
            
            <!-- Quick Actions -->
            <button class="p-2 text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[20px]">notifications</span>
            </button>
        </div>
    </header>
    
    <!-- Dynamic Content Area -->
    <div class="flex-1 overflow-y-auto p-6 lg:p-8 bg-gray-50 dark:bg-slate-950 content-fade-in transition-colors">
        <?php 
        // Render admin notices (flash messages)
        if (function_exists('zed_render_notices')) {
            echo zed_render_notices();
        }
        
        // Include the appropriate content partial
        if (isset($content_partial) && file_exists($content_partial)) {
            include $content_partial;
        } else {
            echo '<div class="text-center py-20 text-gray-500">Content not found</div>';
        }
        ?>
    </div>
</main>

<?php \Core\Event::trigger('zed_admin_footer'); ?>

<script>
    // Dark mode toggle functionality
    document.getElementById('theme-toggle').addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    });
</script>
</body>
</html>
