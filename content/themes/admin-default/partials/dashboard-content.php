<?php
/**
 * Dashboard Command Center
 * 
 * Real-time dashboard with live stats, health monitoring, and quick actions.
 * 
 * Variables available from admin_addon.php:
 * - $total_pages, $total_posts, $total_content, $published_count, $draft_count
 * - $total_users, $total_addons
 * - $recent_content (with relative_time)
 * - $health_checks, $health_status, $system_status, $system_status_color
 * - $content_by_type, $content_by_status
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

<!-- Welcome Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-1"><?= $greeting ?>, <?= htmlspecialchars(ucfirst($user_name)) ?>!</h1>
    <p class="text-gray-500">Here's what's happening with your site today.</p>
</div>

<!-- At a Glance Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Posts Card -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Posts</h3>
            <div class="p-1.5 bg-purple-50 rounded-md text-purple-600">
                <span class="material-symbols-outlined text-[20px]">article</span>
            </div>
        </div>
        <?php if ($total_posts === 0): ?>
            <div class="mt-2">
                <p class="text-gray-500 text-sm mb-3">No posts yet</p>
                <a href="<?= $base_url ?>/admin/editor?new=true&type=post" 
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    Write your first post
                </a>
            </div>
        <?php else: ?>
            <div class="flex items-end justify-between">
                <div>
                    <div class="text-4xl font-display font-bold text-gray-900"><?= number_format($total_posts) ?></div>
                    <p class="text-xs text-gray-500 font-medium mt-1"><?= $published_count ?> published</p>
                </div>
            </div>
        <?php endif; ?>
        <div class="absolute bottom-0 right-0 w-1/2 h-16 opacity-30 group-hover:opacity-50 transition-opacity">
            <svg class="w-full h-full text-purple-600 fill-current" preserveAspectRatio="none" viewBox="0 0 100 40">
                <path d="M0 30 Q 10 35 20 20 T 40 10 T 60 25 T 80 15 T 100 5 L 100 40 L 0 40 Z"></path>
            </svg>
        </div>
    </div>
    
    <!-- Pages Card -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Pages</h3>
            <div class="p-1.5 bg-blue-50 rounded-md text-blue-600">
                <span class="material-symbols-outlined text-[20px]">description</span>
            </div>
        </div>
        <div class="flex items-end justify-between">
            <div>
                <div class="text-4xl font-display font-bold text-gray-900"><?= number_format($total_pages) ?></div>
                <p class="text-xs text-gray-500 font-medium mt-1">Static pages</p>
            </div>
        </div>
        <div class="absolute bottom-0 right-0 w-1/2 h-16 opacity-30 group-hover:opacity-50 transition-opacity">
            <svg class="w-full h-full text-blue-500 fill-current" preserveAspectRatio="none" viewBox="0 0 100 40">
                <path d="M0 25 C 20 25, 30 15, 50 15 S 80 30, 100 10 L 100 40 L 0 40 Z"></path>
            </svg>
        </div>
    </div>
    
    <!-- Users Card -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Users</h3>
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
            <svg class="w-full h-full text-orange-500 fill-current" preserveAspectRatio="none" viewBox="0 0 100 40">
                <path d="M0 30 Q 25 20 50 30 T 100 20 L 100 40 L 0 40 Z"></path>
            </svg>
        </div>
    </div>
    
    <!-- System Health Card -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">System</h3>
            <div class="p-1.5 bg-<?= $system_status_color ?>-50 rounded-md text-<?= $system_status_color ?>-600">
                <span class="material-symbols-outlined text-[20px]">
                    <?= $health_status === 'nominal' ? 'check_circle' : ($health_status === 'warning' ? 'warning' : 'error') ?>
                </span>
            </div>
        </div>
        <div class="flex items-end justify-between">
            <div>
                <div class="text-lg font-bold text-<?= $system_status_color ?>-600"><?= $system_status ?></div>
                <p class="text-xs text-gray-500 font-medium mt-1"><?= count($health_checks) ?> checks passed</p>
            </div>
        </div>
        <div class="absolute bottom-0 right-0 w-1/2 h-16 opacity-30 group-hover:opacity-50 transition-opacity">
            <svg class="w-full h-full text-<?= $system_status_color ?>-500 fill-current" preserveAspectRatio="none" viewBox="0 0 100 40">
                <path d="M0 35 L 20 35 L 30 10 L 40 35 L 60 35 L 70 20 L 80 35 L 100 35 L 100 40 L 0 40 Z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Two Column Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Jump Back In (Left - 2 cols) -->
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">history</span>
                <h3 class="font-semibold text-gray-900">Jump Back In</h3>
            </div>
            <a href="<?= $base_url ?>/admin/content" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All →</a>
        </div>
        <div class="divide-y divide-gray-100">
            <?php if (!empty($recent_content)): ?>
                <?php foreach ($recent_content as $item): 
                    $statusColor = ($item['status'] ?? 'draft') === 'published' ? 'green' : 'yellow';
                    $icon = $item['type'] === 'page' ? 'description' : 'article';
                ?>
                <a href="<?= $base_url ?>/admin/editor?id=<?= $item['id'] ?>" 
                   class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                        <span class="material-symbols-outlined text-[20px]"><?= $icon ?></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-gray-900 truncate"><?= htmlspecialchars($item['title'] ?: 'Untitled') ?></h4>
                        <p class="text-xs text-gray-500">
                            Edited <span class="font-medium"><?= $item['relative_time'] ?></span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-700">
                            <?= ucfirst($item['status'] ?? 'draft') ?>
                        </span>
                        <span class="material-symbols-outlined text-gray-400 group-hover:text-indigo-600 transition-colors">arrow_forward</span>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-[32px] text-gray-400">inbox</span>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-1">No content yet</h4>
                    <p class="text-gray-500 text-sm mb-4">Start creating your first piece of content.</p>
                    <a href="<?= $base_url ?>/admin/editor?new=true" 
                       class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Create Content
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Quick Draft (Right) -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <span class="material-symbols-outlined text-purple-600">edit_note</span>
            <h3 class="font-semibold text-gray-900">Quick Draft</h3>
        </div>
        <div class="p-6">
            <form id="quick-draft-form" class="space-y-4">
                <div>
                    <label for="quick-draft-title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" 
                           id="quick-draft-title" 
                           name="title" 
                           placeholder="What's on your mind?" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                           required>
                </div>
                <button type="submit" 
                        id="quick-draft-btn"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">bolt</span>
                    <span id="quick-draft-btn-text">Create Draft</span>
                </button>
            </form>
            <p class="text-xs text-gray-500 mt-3 text-center">Instantly creates a draft and opens the editor.</p>
        </div>
    </div>
</div>

<!-- Health Widget & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Health Checks -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-<?= $system_status_color ?>-600">monitor_heart</span>
                <h3 class="font-semibold text-gray-900">System Health</h3>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-<?= $system_status_color ?>-500 animate-pulse"></div>
                <span class="text-sm font-medium text-<?= $system_status_color ?>-600"><?= $system_status ?></span>
            </div>
        </div>
        <div class="p-4 space-y-2">
            <?php foreach ($health_checks as $check): 
                $checkColor = $check['status'] === 'ok' ? 'green' : ($check['status'] === 'warning' ? 'yellow' : 'red');
                $checkIcon = $check['status'] === 'ok' ? 'check_circle' : ($check['status'] === 'warning' ? 'warning' : 'error');
            ?>
            <div class="flex items-center justify-between p-3 rounded-lg bg-<?= $checkColor ?>-50 border border-<?= $checkColor ?>-100">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-<?= $checkColor ?>-600 text-[20px]"><?= $checkIcon ?></span>
                    <span class="font-medium text-gray-900"><?= htmlspecialchars($check['label']) ?></span>
                </div>
                <span class="text-sm text-<?= $checkColor ?>-700"><?= htmlspecialchars($check['detail']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600">lightning_stand</span>
            <h3 class="font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-4 grid grid-cols-2 gap-3">
            <a href="<?= $base_url ?>/admin/editor?new=true&type=page" 
               class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-200 hover:border-indigo-200 hover:bg-indigo-50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">note_add</span>
                </div>
                <span class="text-sm font-medium text-gray-700">New Page</span>
            </a>
            <a href="<?= $base_url ?>/admin/editor?new=true&type=post" 
               class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-200 hover:border-purple-200 hover:bg-purple-50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">edit_note</span>
                </div>
                <span class="text-sm font-medium text-gray-700">New Post</span>
            </a>
            <a href="<?= $base_url ?>/admin/media" 
               class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-200 hover:border-green-200 hover:bg-green-50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">upload_file</span>
                </div>
                <span class="text-sm font-medium text-gray-700">Upload Media</span>
            </a>
            <a href="<?= $base_url ?>/admin/settings" 
               class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">settings</span>
                </div>
                <span class="text-sm font-medium text-gray-700">Settings</span>
            </a>
        </div>
    </div>
</div>

<!-- Quick Draft Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('quick-draft-form');
    const btn = document.getElementById('quick-draft-btn');
    const btnText = document.getElementById('quick-draft-btn-text');
    const titleInput = document.getElementById('quick-draft-title');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const title = titleInput.value.trim();
        if (!title) return;
        
        // Disable button and show loading
        btn.disabled = true;
        btnText.textContent = 'Creating...';
        
        try {
            const response = await fetch('<?= $base_url ?>/admin/api/quick-draft', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title: title })
            });
            
            const data = await response.json();
            
            if (data.success && data.redirect) {
                btnText.textContent = 'Redirecting...';
                window.location.href = data.redirect;
            } else {
                throw new Error(data.error || 'Failed to create draft');
            }
        } catch (err) {
            alert('Error: ' + err.message);
            btn.disabled = false;
            btnText.textContent = 'Create Draft';
        }
    });
});
</script>

<!-- Chart Data for future use -->
<script>
    window.ZERO_DASHBOARD_DATA = <?= $chartDataJson ?? '{}' ?>;
</script>
