<?php
/**
 * Content List Partial
 * 
 * This is the content area for the content list, included within admin-layout.php
 * Variables available from admin_addon.php:
 * - $posts: Array of content items
 * - $page, $perPage, $search, $status, $msg: Filter/pagination state
 * - $totalPosts, $totalPages, $showingFrom, $showingTo
 */

use Core\Router;

$base_url = Router::getBasePath();

// Helper functions (if not already defined)
if (!function_exists('getTypeBadgeClass')) {
    function getTypeBadgeClass($type) {
        return match($type) {
            'page' => 'bg-blue-50 text-blue-700 border-blue-100',
            'post' => 'bg-purple-50 text-purple-700 border-purple-100',
            'product' => 'bg-orange-50 text-orange-700 border-orange-100',
            default => 'bg-gray-50 text-gray-700 border-gray-100',
        };
    }
}

if (!function_exists('getStatus')) {
    function getStatus($post) {
        if (isset($post['data'])) {
            $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
            return $data['status'] ?? 'draft';
        }
        return 'draft';
    }
}

if (!function_exists('buildContentUrl')) {
    function buildContentUrl($base_url, $params = []) {
        // Merge with existing type param if present in global GET
        if (isset($_GET['type']) && !isset($params['type'])) {
            $params['type'] = $_GET['type'];
        }
        $query = http_build_query(array_filter($params, fn($v) => $v !== '' && $v !== null));
        return $base_url . '/admin/content' . ($query ? '?' . $query : '');
    }
}
?>

<!-- Flash Messages -->
<?php if (!empty($msg)): ?>
    <div class="mb-6 p-4 rounded-lg <?= $msg === 'deleted' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' ?>">
        <?php
        echo match($msg) {
            'deleted' => '✓ Content deleted successfully.',
            'not_found' => '✗ Content not found.',
            'invalid_id' => '✗ Invalid content ID.',
            'error' => '✗ An error occurred.',
            default => ''
        };
        ?>
    </div>
<?php endif; ?>

<!-- Filter Bar -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
    <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
        <!-- Status Tabs -->
        <nav class="flex space-x-1 bg-gray-100 p-1 rounded-lg" aria-label="Tabs">
            <a href="<?= buildContentUrl($base_url, ['search' => $search]) ?>" 
               class="<?= $status === '' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?> px-4 py-2 font-medium text-sm rounded-md transition-colors">
                All
            </a>
            <a href="<?= buildContentUrl($base_url, ['status' => 'published', 'search' => $search]) ?>" 
               class="<?= $status === 'published' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?> px-4 py-2 font-medium text-sm rounded-md transition-colors">
                Published
            </a>
            <a href="<?= buildContentUrl($base_url, ['status' => 'draft', 'search' => $search]) ?>" 
               class="<?= $status === 'draft' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?> px-4 py-2 font-medium text-sm rounded-md transition-colors">
                Drafts
            </a>
        </nav>
        
        <!-- Search -->
        <form method="GET" action="<?= $base_url ?>/admin/content" class="flex items-center gap-2">
            <?php if ($status): ?><input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>"><?php endif; ?>
            <?php if (!empty($type)): ?><input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>"><?php endif; ?>
            <div class="relative">
                <input type="text" name="search" placeholder="Search content..." value="<?= htmlspecialchars($search) ?>" 
                       class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <span class="absolute left-3 top-2.5 text-gray-400">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </span>
            </div>
            <?php if ($search): ?>
                <a href="<?= buildContentUrl($base_url, ['status' => $status]) ?>" 
                   class="text-sm text-gray-500 hover:text-gray-700 px-2">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>


<!-- Bulk Actions Bar -->
<div id="bulkActionsBar" class="hidden bg-indigo-50 border border-indigo-100 rounded-xl mb-6 px-6 py-4 flex items-center justify-between transition-all">
    <div class="flex items-center gap-3">
            <span class="text-indigo-700 text-sm font-medium"><span id="selectedCount">0</span> items selected</span>
    </div>
    <div class="flex items-center gap-2">
        <button id="batchDeleteBtn" class="bg-white text-red-600 hover:text-red-700 border border-red-200 hover:border-red-300 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[18px]">delete</span>
            Delete Selected
        </button>
    </div>
</div>

<!-- Content Table -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <?php if (!empty($posts)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Updated
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php foreach ($posts as $post): 
                    $postStatus = getStatus($post);
                    $statusColor = $postStatus === 'published' ? 'green' : 'yellow';
                    $updatedAt = $post['updated_at'] ? date('M j, Y', strtotime($post['updated_at'])) : '-';
                ?>
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="row-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer" value="<?= $post['id'] ?>">
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                <?= strtoupper(substr($post['title'] ?: 'U', 0, 1)) ?>
                            </div>
                            <div>
                                <a href="<?= $base_url ?>/admin/editor?id=<?= $post['id'] ?>" 
                                   class="font-medium text-gray-900 hover:text-indigo-600">
                                    <?= htmlspecialchars($post['title'] ?: 'Untitled') ?>
                                </a>
                                <p class="text-xs text-gray-500">/<?= htmlspecialchars($post['slug']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-md border <?= getTypeBadgeClass($post['type']) ?>">
                            <?= ucfirst($post['type']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-<?= $statusColor ?>-500"></span>
                            <?= ucfirst($postStatus) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= $updatedAt ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="<?= $base_url ?>/admin/editor?id=<?= $post['id'] ?>" 
                               class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                               title="Edit">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            <a href="<?= $base_url ?>/<?= htmlspecialchars($post['slug']) ?>" 
                               target="_blank"
                               class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                               title="View">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </a>
                            <a href="<?= $base_url ?>/admin/content/delete?id=<?= $post['id'] ?>" 
                               class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                               title="Delete"
                               onclick="return confirm('Are you sure you want to delete this content?')">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            <?php if ($totalPosts > 0): ?>
                Showing <span class="font-medium"><?= $showingFrom ?></span> to <span class="font-medium"><?= $showingTo ?></span> of <span class="font-medium"><?= $totalPosts ?></span>
                <?php if ($search): ?><span class="text-gray-400">for "<?= htmlspecialchars($search) ?>"</span><?php endif; ?>
            <?php else: ?>
                No results
            <?php endif; ?>
        </div>
        <div class="flex items-center gap-2">
            <?php if ($page > 1): ?>
                <a href="<?= buildContentUrl($base_url, ['page' => $page - 1, 'status' => $status, 'search' => $search]) ?>" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Previous
                </a>
            <?php endif; ?>
            
            <span class="px-4 py-2 text-sm text-gray-600">Page <?= $page ?> of <?= $totalPages ?></span>
            
            <?php if ($page < $totalPages): ?>
                <a href="<?= buildContentUrl($base_url, ['page' => $page + 1, 'status' => $status, 'search' => $search]) ?>" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Next
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Empty State -->
    <div class="py-16 text-center">
        <span class="material-symbols-outlined text-[64px] text-gray-300 mb-4">description</span>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No content found</h3>
        <p class="text-gray-500 mb-6">
            <?php if ($search): ?>
                No results match "<?= htmlspecialchars($search) ?>"
            <?php else: ?>
                Get started by creating your first piece of content
            <?php endif; ?>
        </p>
        <a href="<?= $base_url ?>/admin/editor?new=true<?= !empty($type) ? '&type=' . htmlspecialchars($type) : '' ?>" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Create Content
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCountSpan = document.getElementById('selectedCount');
        const batchDeleteBtn = document.getElementById('batchDeleteBtn');
        const baseUrl = '<?= $base_url ?>';

        function updateBulkActions() {
            const selected = document.querySelectorAll('.row-checkbox:checked');
            selectedCountSpan.textContent = selected.length;
            
            if (selected.length > 0) {
                bulkActionsBar.classList.remove('hidden');
            } else {
                bulkActionsBar.classList.add('hidden');
            }
        }

        // Toggle all
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.row-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkActions();
            });
        }

        // Individual toggle
        document.querySelector('table').addEventListener('change', function(e) {
            if (e.target.classList.contains('row-checkbox')) {
                updateBulkActions();
                
                // Update selectAll state
                const all = document.querySelectorAll('.row-checkbox');
                const checked = document.querySelectorAll('.row-checkbox:checked');
                if (selectAll) {
                    selectAll.checked = all.length > 0 && all.length === checked.length;
                    selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
                }
            }
        });

        // Batch Delete
        if (batchDeleteBtn) {
            batchDeleteBtn.addEventListener('click', async function() {
                const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
                if (selected.length === 0) return;

                if (!confirm(`Are you sure you want to delete ${selected.length} items? This cannot be undone.`)) {
                    return;
                }

                try {
                    batchDeleteBtn.disabled = true;
                    batchDeleteBtn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">refresh</span> Deleting...';

                    const response = await fetch(baseUrl + '/admin/api/batch-delete-content', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ ids: selected })
                    });

                    const result = await response.json();

                    if (result.success) {
                        window.location.reload();
                    } else {
                        alert('Error: ' + (result.error || 'Unknown error'));
                        batchDeleteBtn.disabled = false;
                        batchDeleteBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">delete</span> Delete Selected';
                    }
                } catch (e) {
                    alert('Error: ' + e.message);
                    batchDeleteBtn.disabled = false;
                    batchDeleteBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">delete</span> Delete Selected';
                }
            });
        }
    });
</script>
