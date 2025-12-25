<?php
/**
 * Pro SaaS Dashboard with Dark Mode
 * Premium dashboard design with proper grid layouts and dark mode support
 */

use Core\Router;

$base_url = Router::getBasePath();
$user_name = explode('@', $current_user['email'] ?? 'User')[0];
$greeting = match(true) {
    date('H') < 12 => 'Good morning',
    date('H') < 17 => 'Good afternoon',
    default => 'Good evening'
};
?>

<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight"><?= $greeting ?>, <?= htmlspecialchars(ucfirst($user_name)) ?>!</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Here's what's happening with your site today.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-800 px-4 py-2 rounded-full border border-gray-200 dark:border-slate-700 shadow-sm">
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
            System Online
        </div>
    </div>

    <!-- Stats Grid - 4 Columns -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <!-- Posts Card -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all duration-300 group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/40 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-2 py-1 rounded-full">Posts</span>
                </div>
                <div class="text-4xl font-bold text-gray-900 dark:text-white mb-1"><?= number_format($total_posts) ?></div>
                <p class="text-sm text-gray-500 dark:text-gray-400"><?= $published_count ?> published</p>
            </div>
        </div>

        <!-- Pages Card -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all duration-300 group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-full">Pages</span>
                </div>
                <div class="text-4xl font-bold text-gray-900 dark:text-white mb-1"><?= number_format($total_pages) ?></div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Static pages</p>
            </div>
        </div>

        <!-- Users Card -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all duration-300 group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-500/10 to-transparent rounded-bl-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/40 rounded-xl">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/30 px-2 py-1 rounded-full">Users</span>
                </div>
                <div class="text-4xl font-bold text-gray-900 dark:text-white mb-1"><?= number_format($total_users) ?></div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Registered</p>
            </div>
        </div>

        <!-- System Health Card -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all duration-300 group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-bl-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 rounded-full flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        Healthy
                    </span>
                </div>
                <div class="text-4xl font-bold text-gray-900 dark:text-white mb-1"><?= count($health_checks) ?></div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Checks passed</p>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Activity Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Activity Overview</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Content updates this week</p>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                            Published
                        </span>
                        <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            <span class="w-3 h-3 rounded-full bg-gray-300 dark:bg-slate-600"></span>
                            Drafts
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-48 flex items-end justify-between gap-3">
                        <?php 
                        $heights = [35, 55, 45, 80, 65, 70, 50];
                        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                        foreach ($heights as $i => $h): 
                            $isToday = $i === (int)date('N') - 1;
                        ?>
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full flex flex-col items-center gap-1 h-40 justify-end">
                                <div class="w-full max-w-[40px] rounded-t-lg transition-all duration-300 hover:opacity-80 <?= $isToday ? 'bg-gradient-to-t from-indigo-600 to-indigo-400 shadow-lg shadow-indigo-500/30' : 'bg-indigo-100 dark:bg-indigo-900/30 hover:bg-indigo-200 dark:hover:bg-indigo-900/50' ?>" style="height: <?= $h ?>%"></div>
                            </div>
                            <span class="text-xs font-medium <?= $isToday ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-500' ?>"><?= $days[$i] ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Content -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 dark:text-white">Recent Content</h3>
                    <a href="<?= $base_url ?>/admin/content" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">View All →</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-slate-700">
                    <?php if (!empty($recent_content)): ?>
                        <?php foreach (array_slice($recent_content, 0, 5) as $item): 
                            $statusColor = ($item['status'] ?? 'draft') === 'published' ? 'emerald' : 'amber';
                        ?>
                        <a href="<?= $base_url ?>/admin/content/edit?id=<?= $item['id'] ?>" class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center text-gray-400 dark:text-gray-500 group-hover:from-indigo-100 group-hover:to-indigo-50 dark:group-hover:from-indigo-900/40 dark:group-hover:to-indigo-800/40 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors"><?= htmlspecialchars($item['title'] ?: 'Untitled') ?></h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400"><?= $item['relative_time'] ?></p>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-<?= $statusColor ?>-100 dark:bg-<?= $statusColor ?>-900/30 text-<?= $statusColor ?>-700 dark:text-<?= $statusColor ?>-400"><?= ucfirst($item['status'] ?? 'draft') ?></span>
                        </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="px-6 py-12 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">No content yet</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Start creating your first piece of content</p>
                            <a href="<?= $base_url ?>/admin/content/edit?new=true" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Create Content
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column (1/3) -->
        <div class="space-y-6">
            
            <!-- Quick Draft -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 dark:from-black dark:to-slate-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-indigo-500/20 to-transparent rounded-bl-full"></div>
                <div class="relative">
                    <h3 class="font-bold mb-4 flex items-center gap-2 text-lg">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Quick Draft
                    </h3>
                    <input type="text" id="quick-draft-title" placeholder="What's on your mind?" class="w-full bg-white/10 backdrop-blur border border-white/10 rounded-xl px-4 py-3 placeholder-white/50 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent mb-3">
                    <button id="quick-draft-btn" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-indigo-500/30">Create Draft</button>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="font-bold text-gray-900 dark:text-white">Quick Actions</h3>
                </div>
                <div class="p-4 grid grid-cols-2 gap-3">
                    <a href="<?= $base_url ?>/admin/content/edit?new=true&type=page" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:border-indigo-400 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-slate-200">New Page</span>
                    </a>
                    <a href="<?= $base_url ?>/admin/content/edit?new=true&type=post" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:border-purple-400 dark:hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/30 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-slate-200">New Post</span>
                    </a>
                    <a href="<?= $base_url ?>/admin/media" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:border-emerald-400 dark:hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-slate-200">Media</span>
                    </a>
                    <a href="<?= $base_url ?>/admin/settings" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:border-gray-400 dark:hover:border-slate-500 hover:bg-gray-100 dark:hover:bg-slate-700/60 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-slate-600 text-gray-600 dark:text-slate-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-slate-200">Settings</span>
                    </a>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 dark:text-white">System Status</h3>
                    <span class="flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        All Systems Operational
                    </span>
                </div>
                <div class="p-4 space-y-3">
                    <?php foreach ($health_checks as $check): 
                        $checkColor = $check['status'] === 'ok' ? 'emerald' : ($check['status'] === 'warning' ? 'amber' : 'red');
                    ?>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-<?= $checkColor ?>-50 dark:bg-<?= $checkColor ?>-900/20 border border-<?= $checkColor ?>-100 dark:border-<?= $checkColor ?>-800/50">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-<?= $checkColor ?>-500"></span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($check['label']) ?></span>
                        </div>
                        <span class="text-xs text-<?= $checkColor ?>-700 dark:text-<?= $checkColor ?>-400 font-medium"><?= htmlspecialchars($check['detail']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Draft Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('quick-draft-title');
    const btn = document.getElementById('quick-draft-btn');
    
    btn.addEventListener('click', async function() {
        const title = titleInput.value.trim();
        if (!title) {
            titleInput.focus();
            titleInput.classList.add('ring-2', 'ring-red-500');
            setTimeout(() => titleInput.classList.remove('ring-2', 'ring-red-500'), 2000);
            return;
        }
        
        btn.disabled = true;
        btn.textContent = 'Creating...';
        
        try {
            const response = await fetch('<?= $base_url ?>/admin/api/quick-draft', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title: title })
            });
            
            const data = await response.json();
            
            if (data.success && data.redirect) {
                btn.textContent = 'Redirecting...';
                window.location.href = data.redirect;
            } else {
                throw new Error(data.error || 'Failed to create draft');
            }
        } catch (err) {
            alert('Error: ' + err.message);
            btn.disabled = false;
            btn.textContent = 'Create Draft';
        }
    });
    
    titleInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            btn.click();
        }
    });
});
</script>
