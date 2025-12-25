<?php
/**
 * Zed CMS Admin Dashboard
 * 
 * Variables available from admin_addon.php:
 * - $total_pages, $total_posts, $total_content, $total_users, $total_addons
 * - $published_count, $draft_count
 * - $system_status, $system_status_detail, $current_user
 * - $recent_content, $content_by_type, $content_by_status
 * - $dashboard_stats (all stats in one array)
 */

use Core\Router;

// Ensure variables are set (fallback for direct access)
$total_pages = $total_pages ?? 0;
$total_posts = $total_posts ?? 0;
$total_content = $total_content ?? 0;
$published_count = $published_count ?? 0;
$draft_count = $draft_count ?? 0;
$total_users = $total_users ?? 0;
$total_addons = $total_addons ?? 0;
$system_status = $system_status ?? 'Unknown';
$system_status_detail = $system_status_detail ?? 'Status unavailable';
$current_user = $current_user ?? ['email' => 'Guest', 'role' => 'guest'];
$recent_content = $recent_content ?? [];
$content_by_type = $content_by_type ?? ['pages' => 0, 'posts' => 0, 'other' => 0];
$content_by_status = $content_by_status ?? ['published' => 0, 'draft' => 0];

// Extract user display info
$user_email = $current_user['email'] ?? 'admin@zero.local';
$user_role = ucfirst($current_user['role'] ?? 'Admin');
$user_initials = strtoupper(substr($user_email, 0, 2));

// Generate URLs with base path
$base_url = Router::getBasePath();

// Prepare chart data as JSON for JavaScript
$chartDataJson = json_encode([
    'byType' => $content_by_type,
    'byStatus' => $content_by_status,
    'totals' => [
        'pages' => $total_pages,
        'posts' => $total_posts,
        'users' => $total_users,
        'addons' => $total_addons
    ]
]);
?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Zed CMS Command Center</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800&amp;family=Noto+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              "primary": "#4f46e5",
              "primary-hover": "#4338ca",
              "text-main": "#111827",
              "text-secondary": "#6b7280",
              "accent-blue": "#2563eb",
              "accent-purple": "#7c3aed",
              "accent-green": "#10b981",
              "accent-orange": "#f97316",
            },
            fontFamily: {
              "display": ["Space Grotesk", "sans-serif"],
              "body": ["Noto Sans", "sans-serif"],
            },
          },
        },
      }
    </script>
</head>
<body class="font-body text-slate-800 bg-gray-50 h-screen flex overflow-hidden">
<aside class="w-[250px] bg-white border-r border-gray-200 flex flex-col flex-shrink-0 z-30">
<div class="h-20 flex items-center px-8 border-b border-gray-100">
<div class="flex items-center gap-3">
<div class="w-8 h-8 bg-black text-white rounded flex items-center justify-center font-display font-bold text-xl rotate-45">
<span class="-rotate-45">Z</span>
</div>
<span class="font-display font-bold text-2xl tracking-tight text-gray-900">ZERO</span>
</div>
</div>
<nav class="flex-1 py-8 space-y-1">
<a class="flex items-center gap-3 px-6 py-3.5 border-l-4 border-accent-purple bg-purple-50 text-accent-purple font-medium group transition-colors" href="<?= $base_url ?>/admin">
<span class="material-symbols-outlined text-[22px]">dashboard</span>
<span>Dashboard</span>
</a>
<a class="flex items-center gap-3 px-6 py-3.5 border-l-4 border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors group" href="<?= $base_url ?>/admin/content">
<span class="material-symbols-outlined text-[22px] group-hover:text-gray-700">article</span>
<span>Content</span>
</a>
<a class="flex items-center gap-3 px-6 py-3.5 border-l-4 border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors group" href="<?= $base_url ?>/admin/media">
<span class="material-symbols-outlined text-[22px] group-hover:text-gray-700">perm_media</span>
<span>Media</span>
</a>
<a class="flex items-center gap-3 px-6 py-3.5 border-l-4 border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors group" href="<?= $base_url ?>/admin/users">
<span class="material-symbols-outlined text-[22px] group-hover:text-gray-700">group</span>
<span>Users</span>
</a>
<a class="flex items-center gap-3 px-6 py-3.5 border-l-4 border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors group" href="<?= $base_url ?>/admin/addons">
<span class="material-symbols-outlined text-[22px] group-hover:text-gray-700">extension</span>
<span>Addons</span>
</a>
<a class="flex items-center gap-3 px-6 py-3.5 border-l-4 border-transparent text-gray-500 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors group" href="<?= $base_url ?>/admin/settings">
<span class="material-symbols-outlined text-[22px] group-hover:text-gray-700">settings</span>
<span>Settings</span>
</a>
</nav>
<div class="p-6 border-t border-gray-100 space-y-3">
<div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 border border-gray-100 cursor-pointer hover:bg-gray-100 transition-colors">
<span class="material-symbols-outlined text-gray-500">help</span>
<span class="text-sm font-medium text-gray-600">Support Center</span>
</div>
<!-- Logout Button -->
<a href="<?= $base_url ?>/admin/logout" class="flex items-center gap-3 p-3 rounded-lg bg-red-50 border border-red-100 cursor-pointer hover:bg-red-100 transition-colors group">
<span class="material-symbols-outlined text-red-500 group-hover:text-red-600">logout</span>
<span class="text-sm font-medium text-red-600 group-hover:text-red-700">Logout</span>
</a>
</div>
</aside>
<main class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
<header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0 z-20 shadow-sm">
<div class="flex items-center text-sm font-medium text-gray-500">
<span class="hover:text-gray-900 cursor-pointer transition-colors">Home</span>
<span class="material-symbols-outlined text-[16px] mx-2 text-gray-400">chevron_right</span>
<span class="text-gray-900">Dashboard</span>
</div>
<div class="flex items-center gap-6">
<button class="hidden sm:flex items-center gap-2 bg-accent-blue hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition-all shadow-md shadow-blue-200 hover:shadow-blue-300 active:scale-95">
<span class="material-symbols-outlined text-[20px]">add</span>
                New Post
            </button>
<div class="h-8 w-[1px] bg-gray-200 hidden sm:block"></div>
<div class="flex items-center gap-3 cursor-pointer">
<div class="text-right hidden md:block">
<p class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($user_email) ?></p>
<p class="text-xs text-gray-500"><?= htmlspecialchars($user_role) ?></p>
</div>
<div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold ring-4 ring-gray-50">
                    <?= htmlspecialchars($user_initials) ?>
                </div>
</div>
</div>
</header>
<div class="flex-1 overflow-y-auto p-6 lg:p-10 bg-gray-50">
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
<!-- Total Pages Card -->
<div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
<div class="flex justify-between items-start mb-2">
<h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Pages</h3>
<div class="p-1.5 bg-purple-50 rounded-md text-purple-600">
<span class="material-symbols-outlined text-[20px]">description</span>
</div>
</div>
<div class="flex items-end justify-between">
<div>
<div class="text-4xl font-display font-bold text-gray-900"><?= number_format($total_pages) ?></div>
<p class="text-xs text-gray-500 font-medium mt-1"><?= htmlspecialchars($system_status_detail) ?></p>
</div>
</div>
<div class="absolute bottom-0 right-0 w-1/2 h-16 opacity-30 group-hover:opacity-50 transition-opacity">
<svg class="w-full h-full text-purple-600 fill-current" preserveAspectRatio="none" viewBox="0 0 100 40">
<path d="M0 30 Q 10 35 20 20 T 40 10 T 60 25 T 80 15 T 100 5 L 100 40 L 0 40 Z"></path>
</svg>
</div>
</div>
<!-- System Health Card -->
<div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
<div class="flex justify-between items-start mb-2">
<h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">System Health</h3>
<div class="p-1.5 bg-green-50 rounded-md text-green-600">
<span class="material-symbols-outlined text-[20px]">monitor_heart</span>
</div>
</div>
<div class="flex items-end justify-between">
<div>
<div class="text-4xl font-display font-bold text-gray-900"><?= htmlspecialchars($system_status) ?></div>
<p class="text-xs text-gray-400 font-medium mt-1"><?= htmlspecialchars($system_status_detail) ?></p>
</div>
</div>
<div class="absolute bottom-0 right-0 w-1/2 h-16 opacity-30 group-hover:opacity-50 transition-opacity">
<svg class="w-full h-full text-green-500 fill-current" preserveAspectRatio="none" viewBox="0 0 100 40">
<path d="M0 35 L 20 35 L 30 10 L 40 35 L 60 35 L 70 20 L 80 35 L 100 35 L 100 40 L 0 40 Z"></path>
</svg>
</div>
</div>
<!-- Active Addons Card -->
<div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
<div class="flex justify-between items-start mb-2">
<h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Active Addons</h3>
<div class="p-1.5 bg-blue-50 rounded-md text-blue-600">
<span class="material-symbols-outlined text-[20px]">extension</span>
</div>
</div>
<div class="flex items-end justify-between">
<div>
<div class="text-4xl font-display font-bold text-gray-900"><?= number_format($total_addons) ?></div>
<p class="text-xs text-gray-500 font-medium mt-1"><?= $published_count ?> published, <?= $draft_count ?> drafts</p>
</div>
</div>
<div class="absolute bottom-0 right-0 w-1/2 h-16 opacity-30 group-hover:opacity-50 transition-opacity">
<svg class="w-full h-full text-blue-500 fill-current" preserveAspectRatio="none" viewBox="0 0 100 40">
<path d="M0 25 C 20 25, 30 15, 50 15 S 80 30, 100 10 L 100 40 L 0 40 Z"></path>
</svg>
</div>
</div>
<!-- Total Users Card -->
<div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
<div class="flex justify-between items-start mb-2">
<h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Users</h3>
<div class="p-1.5 bg-orange-50 rounded-md text-orange-600">
<span class="material-symbols-outlined text-[20px]">group</span>
</div>
</div>
<div class="flex items-end justify-between">
<div>
<div class="text-4xl font-display font-bold text-gray-900"><?= number_format($total_users) ?></div>
<p class="text-xs text-gray-500 font-medium mt-1">Registered users</p>
</div>
</div>
<div class="absolute bottom-0 right-0 w-1/2 h-16 opacity-30 group-hover:opacity-50 transition-opacity">
<svg class="w-full h-full text-orange-400 fill-current" preserveAspectRatio="none" viewBox="0 0 100 40">
<path d="M0 30 L 15 25 L 30 28 L 45 15 L 60 18 L 75 10 L 90 12 L 100 5 L 100 40 L 0 40 Z"></path>
</svg>
</div>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
<div class="flex justify-between items-center mb-6">
<h2 class="text-lg font-bold font-display text-gray-900">Traffic Overview</h2>
<div class="flex items-center gap-2">
<select class="text-xs border-gray-200 rounded-md text-gray-500 focus:ring-accent-purple focus:border-accent-purple">
<option>Last 7 Days</option>
<option>Last 30 Days</option>
</select>
<button class="p-1 hover:bg-gray-100 rounded text-gray-400">
<span class="material-symbols-outlined text-[20px]">more_horiz</span>
</button>
</div>
</div>
<div class="w-full h-[300px] relative">
<div class="absolute inset-0 flex flex-col justify-between text-xs text-gray-400">
<div class="border-b border-gray-100 pb-1 w-full flex items-end"><span>10k</span></div>
<div class="border-b border-gray-100 pb-1 w-full flex items-end"><span>7.5k</span></div>
<div class="border-b border-gray-100 pb-1 w-full flex items-end"><span>5k</span></div>
<div class="border-b border-gray-100 pb-1 w-full flex items-end"><span>2.5k</span></div>
<div class="border-b border-gray-100 pb-1 w-full flex items-end"><span>0</span></div>
</div>
<svg class="absolute inset-0 w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 100 100">
<defs>
<linearGradient id="chartGradient" x1="0" x2="0" y1="0" y2="1">
<stop offset="0%" stop-color="#8b5cf6" stop-opacity="0.2"></stop>
<stop offset="100%" stop-color="#8b5cf6" stop-opacity="0"></stop>
</linearGradient>
</defs>
<path d="M0 80 C 10 75, 15 60, 25 65 S 35 40, 50 45 S 65 20, 75 30 S 90 10, 100 25 L 100 100 L 0 100 Z" fill="url(#chartGradient)"></path>
<path d="M0 80 C 10 75, 15 60, 25 65 S 35 40, 50 45 S 65 20, 75 30 S 90 10, 100 25" fill="none" stroke="#7c3aed" stroke-width="0.8" vector-effect="non-scaling-stroke"></path>
<circle cx="25" cy="65" fill="white" r="1" stroke="#7c3aed" stroke-width="0.5"></circle>
<circle cx="50" cy="45" fill="white" r="1" stroke="#7c3aed" stroke-width="0.5"></circle>
<circle cx="75" cy="30" fill="white" r="1" stroke="#7c3aed" stroke-width="0.5"></circle>
</svg>
</div>
<div class="flex justify-between mt-4 text-xs text-gray-400 px-2">
<span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
</div>
</div>
<div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm flex flex-col">
<div class="flex justify-between items-center mb-6">
<h2 class="text-lg font-bold font-display text-gray-900">System Events</h2>
<a class="text-xs font-semibold text-accent-blue hover:text-blue-700" href="#">View All</a>
</div>
<div class="flex-1 overflow-y-auto pr-2">
<ul class="space-y-6">
<?php if (!empty($recent_content)): ?>
    <?php foreach ($recent_content as $index => $item): 
        $colors = ['green', 'blue', 'purple', 'orange', 'gray'];
        $color = $colors[$index % count($colors)];
        $timeAgo = (new DateTime($item['updated_at']))->diff(new DateTime())->format('%a') > 0 
            ? (new DateTime($item['updated_at']))->diff(new DateTime())->format('%a days ago')
            : 'Today';
    ?>
    <li class="relative pl-6 border-l <?= $index === count($recent_content) - 1 ? 'border-transparent' : 'border-gray-100' ?> pb-1">
        <div class="absolute left-[-5px] top-[6px] w-2.5 h-2.5 rounded-full bg-<?= $color ?>-500 ring-4 ring-white"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-gray-800">
                    <a href="<?= $base_url ?>/admin/content/edit?id=<?= $item['id'] ?>" class="hover:underline">
                        <?= htmlspecialchars($item['title'] ?: 'Untitled') ?>
                    </a>
                </p>
                <p class="text-xs text-gray-500 mt-0.5">
                    <?= ucfirst($item['type']) ?> • <?= ucfirst($item['status'] ?? 'draft') ?>
                </p>
            </div>
            <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap"><?= $timeAgo ?></span>
        </div>
    </li>
    <?php endforeach; ?>
<?php else: ?>
    <li class="relative pl-6 border-l border-gray-100 pb-1">
        <div class="absolute left-[-5px] top-[6px] w-2.5 h-2.5 rounded-full bg-green-500 ring-4 ring-white"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-gray-800">System Initialized</p>
                <p class="text-xs text-gray-500 mt-0.5">Zed CMS core loaded successfully.</p>
            </div>
            <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">Just now</span>
        </div>
    </li>
    <li class="relative pl-6 border-l border-gray-100 pb-1">
        <div class="absolute left-[-5px] top-[6px] w-2.5 h-2.5 rounded-full bg-blue-500 ring-4 ring-white"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-gray-800">User Login</p>
                <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($user_email) ?> logged in</p>
            </div>
            <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">Just now</span>
        </div>
    </li>
    <li class="relative pl-6 border-l border-transparent pb-1">
        <div class="absolute left-[-5px] top-[6px] w-2.5 h-2.5 rounded-full bg-gray-300 ring-4 ring-white"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-gray-800">No content yet</p>
                <p class="text-xs text-gray-500 mt-0.5">Create your first page or post.</p>
            </div>
            <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">Startup</span>
        </div>
    </li>
<?php endif; ?>
</ul>
</div>
</div>
</div>
</div>
</main>

<!-- Dashboard Data for Charts -->
<script>
    window.ZED_DASHBOARD_DATA = <?= $chartDataJson ?>;
    console.log('Dashboard Stats:', window.ZED_DASHBOARD_DATA);
</script>

</body></html>