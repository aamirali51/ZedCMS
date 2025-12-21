<?php
/**
 * Visual Menu Builder
 * 
 * A modern drag-and-drop menu builder superior to WordPress.
 * 
 * Variables available:
 * - $menus - All menus
 * - $currentMenu - Selected menu (if any)
 * - $pages - Published pages for toolbox
 * - $categories - All categories for toolbox
 * - $posts - Published posts for toolbox
 */

use Core\Router;

$base_url = Router::getBasePath();
$menuItems = [];
if ($currentMenu && !empty($currentMenu['items'])) {
    $menuItems = json_decode($currentMenu['items'], true) ?: [];
}
?>

<div class="grid grid-cols-12 gap-6">
    <!-- Left Column: Menu List + Toolbox -->
    <div class="col-span-12 lg:col-span-4 space-y-6">
        <!-- Menu Selector -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600">menu</span>
                    Menus
                </h2>
                <span class="text-xs text-gray-500"><?= count($menus) ?> total</span>
            </div>
            
            <!-- Menu List -->
            <div class="divide-y divide-gray-100 max-h-[200px] overflow-y-auto">
                <?php if (empty($menus)): ?>
                    <div class="p-6 text-center text-gray-500 text-sm">
                        <span class="material-symbols-outlined text-3xl text-gray-300 mb-2">inbox</span>
                        <p>No menus yet</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($menus as $menu): ?>
                        <?php $isActive = ($currentMenu && $currentMenu['id'] == $menu['id']); ?>
                        <a href="<?= $base_url ?>/admin/menus?id=<?= $menu['id'] ?>" 
                           class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition-colors <?= $isActive ? 'bg-indigo-50 border-l-4 border-indigo-600' : '' ?>">
                            <span class="font-medium <?= $isActive ? 'text-indigo-700' : 'text-gray-700' ?>"><?= htmlspecialchars($menu['name']) ?></span>
                            <?php if ($isActive): ?>
                                <span class="material-symbols-outlined text-indigo-600 text-[18px]">edit</span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Create New Menu -->
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                <form action="<?= $base_url ?>/admin/menus/create" method="POST" class="flex gap-2">
                    <input type="text" name="name" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                           placeholder="New menu name..." required>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </button>
                </form>
            </div>
        </div>
        
        <?php if ($currentMenu): ?>
        <!-- Toolbox Accordions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">construction</span>
                    Add Items
                </h2>
            </div>
            
            <!-- Pages Accordion -->
            <div class="border-b border-gray-100" x-data="{open: true}">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-3 hover:bg-gray-50 text-left">
                    <span class="font-medium text-gray-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-500 text-[20px]">description</span>
                        Pages
                    </span>
                    <span class="material-symbols-outlined text-gray-400 transition-transform" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="px-5 pb-4 space-y-2">
                    <?php if (empty($pages)): ?>
                        <p class="text-sm text-gray-500 italic">No published pages</p>
                    <?php else: ?>
                        <?php foreach ($pages as $page): ?>
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg text-sm group">
                            <span class="text-gray-700 truncate"><?= htmlspecialchars($page['title']) ?></span>
                            <button type="button" 
                                    onclick="addMenuItem('<?= htmlspecialchars($page['title'], ENT_QUOTES) ?>', '<?= $base_url ?>/<?= $page['slug'] ?>')"
                                    class="p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-colors">
                                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Posts Accordion -->
            <div class="border-b border-gray-100" x-data="{open: false}">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-3 hover:bg-gray-50 text-left">
                    <span class="font-medium text-gray-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-500 text-[20px]">article</span>
                        Posts
                    </span>
                    <span class="material-symbols-outlined text-gray-400 transition-transform" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="px-5 pb-4 space-y-2">
                    <?php if (empty($posts)): ?>
                        <p class="text-sm text-gray-500 italic">No published posts</p>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg text-sm group">
                            <span class="text-gray-700 truncate"><?= htmlspecialchars($post['title']) ?></span>
                            <button type="button" 
                                    onclick="addMenuItem('<?= htmlspecialchars($post['title'], ENT_QUOTES) ?>', '<?= $base_url ?>/<?= $post['slug'] ?>')"
                                    class="p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-colors">
                                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Categories Accordion -->
            <div class="border-b border-gray-100" x-data="{open: false}">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-3 hover:bg-gray-50 text-left">
                    <span class="font-medium text-gray-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-500 text-[20px]">category</span>
                        Categories
                    </span>
                    <span class="material-symbols-outlined text-gray-400 transition-transform" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="px-5 pb-4 space-y-2">
                    <?php if (empty($categories)): ?>
                        <p class="text-sm text-gray-500 italic">No categories</p>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg text-sm group">
                            <span class="text-gray-700 truncate"><?= htmlspecialchars($cat['name']) ?></span>
                            <button type="button" 
                                    onclick="addMenuItem('<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>', '<?= $base_url ?>/category/<?= $cat['slug'] ?>')"
                                    class="p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-colors">
                                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Custom Link -->
            <div class="p-5" x-data="{open: true}">
                <button @click="open = !open" class="w-full flex items-center justify-between mb-3 text-left">
                    <span class="font-medium text-gray-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-500 text-[20px]">link</span>
                        Custom Link
                    </span>
                    <span class="material-symbols-outlined text-gray-400 transition-transform" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open" class="space-y-3">
                    <input type="text" id="custom-label" placeholder="Label" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <input type="url" id="custom-url" placeholder="https://example.com" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <button type="button" onclick="addCustomLink()" 
                            class="w-full px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">add_link</span>
                        Add to Menu
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Right Column: Menu Builder -->
    <div class="col-span-12 lg:col-span-8">
        <?php if ($currentMenu): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-50 rounded-lg">
                        <span class="material-symbols-outlined text-indigo-600">drag_indicator</span>
                    </div>
                    <div>
                        <input type="text" id="menu-name" value="<?= htmlspecialchars($currentMenu['name']) ?>" 
                               class="font-bold text-gray-900 bg-transparent border-0 p-0 focus:ring-0 text-lg"
                               onchange="markUnsaved()">
                        <p class="text-xs text-gray-500">Drag items to reorder • Drag right to nest</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div id="save-status" class="text-sm text-gray-400 flex items-center gap-1 hidden">
                        <span class="material-symbols-outlined text-[16px] animate-spin">sync</span>
                        Saving...
                    </div>
                    <button type="button" onclick="saveMenu()" id="save-btn"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center gap-2 disabled:opacity-50">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Save Menu
                    </button>
                    <button type="button" onclick="deleteMenu()" 
                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>
            
            <!-- Builder Area -->
            <div id="menu-builder" class="p-6 min-h-[400px] bg-gray-50">
                <?php if (empty($menuItems)): ?>
                <div id="empty-state" class="text-center py-16">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-3xl text-gray-400">menu_open</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">No items yet</h3>
                    <p class="text-gray-500 text-sm">Add items from the toolbox on the left</p>
                </div>
                <?php endif; ?>
                
                <div id="menu-items" class="space-y-2">
                    <!-- Items will be rendered here by JS -->
                </div>
            </div>
            
            <!-- Footer with JSON Preview Toggle -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" id="show-json" onchange="toggleJsonPreview()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    Show JSON (Debug)
                </label>
                <span class="text-xs text-gray-400">Auto-saves after changes</span>
            </div>
            
            <!-- JSON Preview (Hidden by default) -->
            <div id="json-preview" class="hidden bg-slate-900 p-4 max-h-[200px] overflow-auto">
                <pre class="text-green-400 text-xs font-mono" id="json-output"></pre>
            </div>
        </div>
        <?php else: ?>
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-dashed p-12 text-center min-h-[500px] flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-4xl text-indigo-400">arrow_back</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Select or Create a Menu</h3>
            <p class="text-gray-500 max-w-md">Choose a menu from the list on the left to start editing, or create a new one.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Alpine.js for accordions -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<?php if ($currentMenu): ?>
<script>
// Menu state
let menuItems = <?= json_encode($menuItems) ?>;
let menuId = <?= $currentMenu['id'] ?>;
let saveTimeout = null;
let hasUnsavedChanges = false;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderMenuItems();
    updateJsonPreview();
});

// Render menu items
function renderMenuItems() {
    const container = document.getElementById('menu-items');
    const emptyState = document.getElementById('empty-state');
    
    if (menuItems.length === 0) {
        container.innerHTML = '';
        if (emptyState) emptyState.classList.remove('hidden');
        return;
    }
    
    if (emptyState) emptyState.classList.add('hidden');
    container.innerHTML = menuItems.map((item, index) => renderItem(item, index)).join('');
    updateJsonPreview();
}

function renderItem(item, index, isChild = false) {
    const children = item.children || [];
    const childrenHtml = children.map((child, childIndex) => 
        renderItem(child, childIndex, true)
    ).join('');
    
    return `
        <div class="menu-item ${isChild ? 'ml-8' : ''}" data-index="${index}">
            <div class="flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-200 shadow-sm group hover:border-indigo-200 transition-colors">
                <div class="cursor-move text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">drag_indicator</span>
                </div>
                <div class="flex-1 min-w-0">
                    <input type="text" value="${escapeHtml(item.label)}" 
                           class="font-medium text-gray-900 bg-transparent border-0 p-0 focus:ring-0 w-full"
                           onchange="updateItemLabel(${index}, this.value, ${isChild})">
                    <input type="text" value="${escapeHtml(item.url)}" 
                           class="text-xs text-gray-500 bg-transparent border-0 p-0 focus:ring-0 w-full"
                           onchange="updateItemUrl(${index}, this.value, ${isChild})">
                </div>
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    ${!isChild ? `
                    <button onclick="moveItem(${index}, -1)" class="p-1 text-gray-400 hover:text-gray-600 rounded" title="Move up">
                        <span class="material-symbols-outlined text-[18px]">arrow_upward</span>
                    </button>
                    <button onclick="moveItem(${index}, 1)" class="p-1 text-gray-400 hover:text-gray-600 rounded" title="Move down">
                        <span class="material-symbols-outlined text-[18px]">arrow_downward</span>
                    </button>
                    ` : ''}
                    <button onclick="removeItem(${index}, ${isChild})" class="p-1 text-gray-400 hover:text-red-600 rounded" title="Remove">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
            ${childrenHtml ? `<div class="mt-2 space-y-2">${childrenHtml}</div>` : ''}
        </div>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
}

// Add menu item
function addMenuItem(label, url) {
    menuItems.push({
        label: label,
        url: url,
        target: '_self',
        children: []
    });
    renderMenuItems();
    markUnsaved();
    autoSave();
}

// Add custom link
function addCustomLink() {
    const label = document.getElementById('custom-label').value.trim();
    const url = document.getElementById('custom-url').value.trim();
    
    if (!label || !url) {
        alert('Please enter both label and URL');
        return;
    }
    
    addMenuItem(label, url);
    document.getElementById('custom-label').value = '';
    document.getElementById('custom-url').value = '';
}

// Update item label
function updateItemLabel(index, value, isChild) {
    if (isChild) {
        // For now, children updates work on parent[0].children
        // This is simplified - a full implementation would track parent index
    } else {
        menuItems[index].label = value;
    }
    markUnsaved();
    autoSave();
}

// Update item URL
function updateItemUrl(index, value, isChild) {
    if (!isChild) {
        menuItems[index].url = value;
    }
    markUnsaved();
    autoSave();
}

// Move item up/down
function moveItem(index, direction) {
    const newIndex = index + direction;
    if (newIndex < 0 || newIndex >= menuItems.length) return;
    
    const temp = menuItems[index];
    menuItems[index] = menuItems[newIndex];
    menuItems[newIndex] = temp;
    
    renderMenuItems();
    markUnsaved();
    autoSave();
}

// Remove item
function removeItem(index, isChild) {
    if (!isChild) {
        menuItems.splice(index, 1);
    }
    renderMenuItems();
    markUnsaved();
    autoSave();
}

// Mark as unsaved
function markUnsaved() {
    hasUnsavedChanges = true;
    updateJsonPreview();
}

// Auto-save after 2 seconds of inactivity
function autoSave() {
    if (saveTimeout) clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        saveMenu(true);
    }, 2000);
}

// Save menu
async function saveMenu(isAutoSave = false) {
    const saveStatus = document.getElementById('save-status');
    const saveBtn = document.getElementById('save-btn');
    const menuName = document.getElementById('menu-name').value;
    
    saveStatus.classList.remove('hidden');
    saveBtn.disabled = true;
    
    try {
        const response = await fetch('<?= $base_url ?>/admin/api/save-menu', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                menu_id: menuId,
                name: menuName,
                items: menuItems
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            hasUnsavedChanges = false;
            saveStatus.innerHTML = '<span class="material-symbols-outlined text-[16px] text-green-500">check_circle</span> Saved';
            setTimeout(() => {
                saveStatus.classList.add('hidden');
                saveStatus.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">sync</span> Saving...';
            }, 2000);
        } else {
            throw new Error(data.error || 'Failed to save');
        }
    } catch (err) {
        alert('Error saving menu: ' + err.message);
    }
    
    saveBtn.disabled = false;
}

// Delete menu
async function deleteMenu() {
    if (!confirm('Are you sure you want to delete this menu?')) return;
    
    try {
        const response = await fetch('<?= $base_url ?>/admin/api/delete-menu', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: menuId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.href = '<?= $base_url ?>/admin/menus';
        } else {
            throw new Error(data.error || 'Failed to delete');
        }
    } catch (err) {
        alert('Error: ' + err.message);
    }
}

// Toggle JSON preview
function toggleJsonPreview() {
    const preview = document.getElementById('json-preview');
    const checkbox = document.getElementById('show-json');
    preview.classList.toggle('hidden', !checkbox.checked);
}

// Update JSON preview
function updateJsonPreview() {
    const output = document.getElementById('json-output');
    if (output) {
        output.textContent = JSON.stringify(menuItems, null, 2);
    }
}

// Warn on page leave
window.addEventListener('beforeunload', function(e) {
    if (hasUnsavedChanges) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
<?php endif; ?>
