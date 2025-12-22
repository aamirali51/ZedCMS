<?php
/**
 * Zero CMS Content List
 * 
 * Displays content from zero_content table with:
 * - Status filtering (All/Published/Draft)
 * - Search by title/slug
 * - Pagination
 */

use Core\Auth;
use Core\Database;
use Core\Router;

// Check authentication
if (!Auth::check()) {
    Router::redirect('/admin/login');
}

// Get base URL for links
$base_url = Router::getBasePath();

// Get filter parameters (passed from admin_addon.php or default)
$page = $contentFilters['page'] ?? max(1, (int)($_GET['page'] ?? 1));
$perPage = $contentFilters['perPage'] ?? 10;
$search = $contentFilters['search'] ?? trim($_GET['search'] ?? '');
$status = $contentFilters['status'] ?? ($_GET['status'] ?? '');
$msg = $contentFilters['msg'] ?? ($_GET['msg'] ?? '');

// Build the query dynamically
$posts = [];
$totalPosts = 0;

try {
    $db = Database::getInstance();
    
    // Base query parts
    $selectSql = "SELECT * FROM zero_content";
    $countSql = "SELECT COUNT(*) FROM zero_content";
    $whereClauses = [];
    $params = [];
    
    // Search filter (title or slug)
    if (!empty($search)) {
        $whereClauses[] = "(title LIKE :search OR slug LIKE :search)";
        $params['search'] = '%' . $search . '%';
    }
    
    // Status filter (requires JSON extraction)
    if ($status === 'published') {
        $whereClauses[] = "JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'";
    } elseif ($status === 'draft') {
        $whereClauses[] = "(JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'draft' OR JSON_EXTRACT(data, '$.status') IS NULL)";
    }
    
    // Combine WHERE clauses
    $whereString = '';
    if (!empty($whereClauses)) {
        $whereString = ' WHERE ' . implode(' AND ', $whereClauses);
    }
    
    // Get total count for pagination
    $totalPosts = (int)$db->queryValue($countSql . $whereString, $params);
    
    // Calculate pagination
    $totalPages = max(1, ceil($totalPosts / $perPage));
    $page = min($page, $totalPages); // Don't exceed total pages
    $offset = ($page - 1) * $perPage;
    
    // Fetch paginated results
    $fullSql = $selectSql . $whereString . " ORDER BY updated_at DESC LIMIT :limit OFFSET :offset";
    
    // PDO doesn't allow named params in LIMIT/OFFSET with emulation off, so use positional
    $stmt = $db->getPdo()->prepare(
        str_replace([':limit', ':offset'], ['?', '?'], 
            str_replace(':search', '?', $fullSql)
        )
    );
    
    // Build positional params
    $positionalParams = [];
    if (!empty($search)) {
        $positionalParams[] = '%' . $search . '%';
    }
    $positionalParams[] = (int)$perPage;
    $positionalParams[] = (int)$offset;
    
    $stmt->execute($positionalParams);
    $posts = $stmt->fetchAll();
    
} catch (Exception $e) {
    $posts = [];
    $totalPosts = 0;
    $totalPages = 1;
}

// Get current user
$current_user = Auth::user();

// Helper function to get type badge color
function getTypeBadgeClass($type) {
    return match($type) {
        'page' => 'bg-blue-50 text-blue-700 border-blue-100',
        'post' => 'bg-purple-50 text-purple-700 border-purple-100',
        'product' => 'bg-orange-50 text-orange-700 border-orange-100',
        default => 'bg-gray-50 text-gray-700 border-gray-100',
    };
}

// Helper to get status from data JSON
function getStatus($post) {
    if (isset($post['data'])) {
        $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
        return $data['status'] ?? 'published';
    }
    return 'published';
}

// Helper to build URL with query params
function buildContentUrl($base_url, $params = []) {
    $query = http_build_query(array_filter($params, fn($v) => $v !== '' && $v !== null));
    return $base_url . '/admin/content' . ($query ? '?' . $query : '');
}

// Calculate display range
$showingFrom = $totalPosts > 0 ? $offset + 1 : 0;
$showingTo = min($offset + $perPage, $totalPosts);
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Zero CMS Content Manager</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#256af4",
              "background-light": "#f5f6f8",
              "background-dark": "#101622",
            },
            fontFamily: {
              "display": ["Inter", "sans-serif"],
              "mono": ["ui-monospace", "SFMono-Regular", "Menlo", "Monaco", "Consolas", "Liberation Mono", "Courier New", "monospace"]
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
<style>
        /* Custom scrollbar for table if needed */
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#0d121c] dark:text-white font-display antialiased min-h-screen flex flex-col">
<!-- Main Layout -->
<div class="layout-container flex flex-col flex-1 w-full max-w-[1280px] mx-auto px-6 py-8">
    
<!-- Flash Messages -->
    <?php if ($msg): ?>
        <div class="mb-4 p-4 rounded-lg <?= $msg === 'deleted' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' ?>">
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
    
    <!-- 1. The Header (Filter Tabs + Search) -->
    <div class="bg-white border border-gray-200 rounded-t-lg border-b-0 px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
        <nav class="flex space-x-1 bg-gray-100/50 p-1 rounded-lg" aria-label="Tabs">
            <a href="<?= buildContentUrl($base_url, ['search' => $search]) ?>" 
               class="<?= $status === '' ? 'bg-white text-indigo-700 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' ?> px-3 py-1.5 font-medium text-sm rounded-md transition-colors"
               <?= $status === '' ? 'aria-current="page"' : '' ?>>All</a>
            <a href="<?= buildContentUrl($base_url, ['status' => 'published', 'search' => $search]) ?>" 
               class="<?= $status === 'published' ? 'bg-white text-indigo-700 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' ?> px-3 py-1.5 font-medium text-sm rounded-md transition-colors"
               <?= $status === 'published' ? 'aria-current="page"' : '' ?>>Published</a>
            <a href="<?= buildContentUrl($base_url, ['status' => 'draft', 'search' => $search]) ?>" 
               class="<?= $status === 'draft' ? 'bg-white text-indigo-700 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' ?> px-3 py-1.5 font-medium text-sm rounded-md transition-colors"
               <?= $status === 'draft' ? 'aria-current="page"' : '' ?>>Drafts</a>
        </nav>
        <div class="flex items-center gap-2">
            <form method="GET" action="<?= $base_url ?>/admin/content" class="relative">
                <?php if ($status): ?><input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>"><?php endif; ?>
                <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>" 
                       class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                <button type="submit" class="absolute left-3 top-2.5 text-gray-400 hover:text-gray-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>
            <?php if ($search): ?>
                <a href="<?= buildContentUrl($base_url, ['status' => $status]) ?>" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
            <?php endif; ?>
            <a href="<?= $base_url ?>/admin/editor?new=true" class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Entry
            </a>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="hidden bg-indigo-50 border-x border-t border-indigo-100 px-6 py-3 flex items-center justify-between transition-all">
        <div class="flex items-center gap-3">
             <span class="text-indigo-700 text-sm font-medium"><span id="selectedCount">0</span> items selected</span>
        </div>
        <div class="flex items-center gap-2">
            <button id="batchDeleteBtn" class="bg-white text-red-600 hover:text-red-700 border border-red-200 hover:border-red-300 px-3 py-1.5 rounded-md text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete Selected
            </button>
        </div>
    </div>

    <!-- 2. The Data Grid (Rich Table) -->
    <div class="overflow-x-auto border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title & Slug</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Modified</th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No content found. Start by creating a new entry.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $post): 
                        $status = getStatus($post);
                        $date = date('M j, Y', strtotime($post['updated_at'] ?? $post['created_at']));
                    ?>
                    <tr class="hover:bg-gray-50 group transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="row-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="<?= $post['id'] ?>">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold">
                                    <?php echo strtoupper(substr($post['title'], 0, 1) ?: '?'); ?>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="<?= $base_url ?>/admin/editor?id=<?php echo $post['id']; ?>" class="hover:underline">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500">/<?php echo htmlspecialchars($post['slug']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($status === 'published'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-600 font-bold">
                                    <?= strtoupper(substr($current_user['username'] ?? 'Admin', 0, 2)) ?>
                                </div>
                                <span class="ml-2 text-sm text-gray-500"><?= htmlspecialchars($current_user['username'] ?? 'Admin') ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $date; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex justify-end gap-2">
                                <a href="<?= $base_url ?>/admin/editor?id=<?php echo $post['id']; ?>" class="text-indigo-600 hover:text-indigo-900 border border-indigo-200 bg-indigo-50 px-2 py-1 rounded text-xs">Edit</a>
                                <a href="#" class="text-gray-600 hover:text-gray-900 border border-gray-200 bg-white px-2 py-1 rounded text-xs">View</a>
                                <button onclick="if(confirm('Delete this content?')) window.location.href='<?= $base_url ?>/admin/content/delete?id=<?= $post['id'] ?>'" class="text-red-600 hover:text-red-900 border border-red-200 bg-red-50 px-2 py-1 rounded text-xs">Trash</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 3. Pagination Footer -->
    <div class="bg-white px-4 py-3 border border-gray-200 border-t-0 rounded-b-lg flex items-center justify-between sm:px-6">
        <div class="text-sm text-gray-700">
            <?php if ($totalPosts > 0): ?>
                Showing <span class="font-medium"><?= $showingFrom ?></span> to <span class="font-medium"><?= $showingTo ?></span> of <span class="font-medium"><?= $totalPosts ?></span> results
                <?php if ($search): ?><span class="text-gray-500">for "<?= htmlspecialchars($search) ?>"</span><?php endif; ?>
            <?php else: ?>
                No results found
                <?php if ($search): ?><span class="text-gray-500">for "<?= htmlspecialchars($search) ?>"</span><?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="flex-1 flex justify-end gap-2">
            <?php 
            $prevUrl = $page > 1 ? buildContentUrl($base_url, ['page' => $page - 1, 'status' => $status, 'search' => $search]) : null;
            $nextUrl = $page < $totalPages ? buildContentUrl($base_url, ['page' => $page + 1, 'status' => $status, 'search' => $search]) : null;
            ?>
            <?php if ($prevUrl): ?>
                <a href="<?= $prevUrl ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
            <?php else: ?>
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">Previous</span>
            <?php endif; ?>
            
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700">
                Page <?= $page ?> of <?= $totalPages ?>
            </span>
            
            <?php if ($nextUrl): ?>
                <a href="<?= $nextUrl ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
            <?php else: ?>
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">Next</span>
            <?php endif; ?>
        </div>
    </div>

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
        // Use delegation or re-query to ensure we catch them
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
                    batchDeleteBtn.innerHTML = 'Deleting...';

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
                        batchDeleteBtn.innerHTML = 'Delete Selected';
                    }
                } catch (e) {
                    alert('Error: ' + e.message);
                    batchDeleteBtn.disabled = false;
                    batchDeleteBtn.innerHTML = 'Delete Selected';
                }
            });
        }
    });
</script>
</body></html>
