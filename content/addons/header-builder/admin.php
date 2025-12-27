<?php
/**
 * Header Builder - Admin Page
 * 
 * Visual drag-and-drop header builder interface
 * 
 * @package ZedCMS\Addons\HeaderBuilder
 */

use Core\Auth;
use Core\Router;

if (!Auth::check() || !zed_current_user_can('manage_settings')) {
    Router::redirect('/admin/login');
    return;
}

$base_url = Router::getBasePath();
$elements = zed_header_builder_elements();
$rows = zed_header_builder_rows();

// Group elements by category
$elementsByCategory = [];
foreach ($elements as $id => $element) {
    $cat = $element['category'] ?? 'other';
    $elementsByCategory[$cat][$id] = $element;
}

$categories = [
    'branding' => 'Branding',
    'navigation' => 'Navigation',
    'search' => 'Search',
    'social' => 'Social',
    'buttons' => 'Buttons',
    'content' => 'Content',
    'utility' => 'Utility',
    'decorative' => 'Decorative',
];
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Builder — Zed CMS</title>
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
                        primary: '#4f46e5',
                        'primary-hover': '#4338ca',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        /* Builder Styles */
        .hb-row {
            background: white;
            border: 2px dashed #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            min-height: 60px;
            transition: all 0.2s;
        }
        .hb-row:hover {
            border-color: #4f46e5;
        }
        .hb-row.sortable-ghost {
            opacity: 0.4;
        }
        
        .hb-column {
            flex: 1;
            min-height: 50px;
            background: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 6px;
            padding: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
            transition: all 0.2s;
        }
        .hb-column.sortable-ghost {
            background: #eef2ff;
            border-color: #4f46e5;
        }
        .hb-column-left { justify-content: flex-start; }
        .hb-column-center { justify-content: center; }
        .hb-column-right { justify-content: flex-end; }
        
        .hb-element {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 500;
            cursor: grab;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
            user-select: none;
        }
        .hb-element:hover {
            border-color: #4f46e5;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.15);
        }
        .hb-element:active {
            cursor: grabbing;
        }
        .hb-element .material-symbols-outlined {
            font-size: 18px;
            color: #6b7280;
        }
        .hb-element-close {
            font-size: 14px !important;
            color: #9ca3af;
            cursor: pointer;
            margin-left: 4px;
        }
        .hb-element-close:hover {
            color: #ef4444;
        }
        
        .hb-element.sortable-chosen {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: scale(1.02);
        }
        
        .hb-elements-pool .hb-element {
            margin-bottom: 4px;
        }
        
        .hb-row-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .hb-row-label .drag-handle {
            cursor: grab;
            color: #d1d5db;
        }
        .hb-row-label .drag-handle:hover {
            color: #6b7280;
        }

        /* Device Tabs */
        .device-tab {
            padding: 10px 20px;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
        }
        .device-tab:hover {
            color: #374151;
        }
        .device-tab.active {
            color: #4f46e5;
            border-bottom-color: #4f46e5;
        }
        
        /* Sub Tabs */
        .mode-tab {
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
            background: #f3f4f6;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .mode-tab:hover {
            background: #e5e7eb;
        }
        .mode-tab.active {
            background: #4f46e5;
            color: white;
        }
        
        /* Category accordion */
        .category-header {
            cursor: pointer;
            user-select: none;
        }
        .category-header:hover {
            background: #f9fafb;
        }
        .category-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .category-content.open {
            max-height: 500px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <!-- Top Bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="flex items-center justify-between px-6 py-3">
            <div class="flex items-center gap-4">
                <a href="<?= $base_url ?>/admin" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-lg font-semibold text-gray-900">Header Builder</h1>
            </div>
            <div class="flex items-center gap-3">
                <span id="save-status" class="text-sm text-gray-400"></span>
                <button id="save-btn" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors">
                    <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                    Save Header
                </button>
            </div>
        </div>
        
        <!-- Device Tabs -->
        <div class="flex border-b border-gray-100 px-6">
            <div class="device-tab active" data-device="desktop">
                <span class="material-symbols-outlined align-middle mr-1" style="font-size: 18px;">computer</span>
                Desktop
            </div>
            <div class="device-tab" data-device="mobile">
                <span class="material-symbols-outlined align-middle mr-1" style="font-size: 18px;">smartphone</span>
                Mobile
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex" style="height: calc(100vh - 110px);">
        <!-- Builder Canvas -->
        <div class="flex-1 overflow-y-auto p-6">
            <!-- Mode Tabs (Desktop Only) -->
            <div id="desktop-modes" class="flex gap-2 mb-4">
                <div class="mode-tab active" data-mode="desktop">Normal Header</div>
                <div class="mode-tab" data-mode="desktop_sticky">Sticky Header</div>
            </div>
            
            <div id="mobile-modes" class="hidden flex gap-2 mb-4">
                <div class="mode-tab active" data-mode="mobile">Mobile Header</div>
                <div class="mode-tab" data-mode="mobile_drawer">Mobile Drawer</div>
            </div>
            
            <!-- Builder Areas -->
            <?php foreach ($rows as $device => $deviceRows): ?>
            <div id="builder-<?= $device ?>" class="builder-area <?= $device === 'desktop' ? '' : 'hidden' ?>" data-device="<?= $device ?>">
                <div class="hb-rows-container" data-device="<?= $device ?>">
                    <?php foreach ($deviceRows as $rowId => $rowConfig): ?>
                    <div class="hb-row" data-row="<?= $rowId ?>">
                        <div class="hb-row-label">
                            <span class="material-symbols-outlined drag-handle" style="font-size: 16px;">drag_indicator</span>
                            <?= $rowConfig['label'] ?>
                        </div>
                        <div class="flex gap-2">
                            <?php foreach ($rowConfig['columns'] as $col): ?>
                            <div class="hb-column hb-column-<?= $col ?>" data-column="<?= $col ?>" data-row="<?= $rowId ?>" data-device="<?= $device ?>">
                                <!-- Elements will be dropped here -->
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Elements Sidebar -->
        <aside class="w-80 bg-white border-l border-gray-200 overflow-y-auto">
            <div class="p-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Elements</h2>
                <p class="text-xs text-gray-500 mt-1">Drag elements to the header rows</p>
            </div>
            
            <div class="hb-elements-pool p-4">
                <?php foreach ($categories as $catId => $catLabel): ?>
                <?php if (isset($elementsByCategory[$catId])): ?>
                <div class="category mb-3">
                    <div class="category-header flex items-center justify-between py-2 px-2 rounded" onclick="this.nextElementSibling.classList.toggle('open'); this.querySelector('.chevron').classList.toggle('rotate-90')">
                        <span class="text-sm font-medium text-gray-700"><?= $catLabel ?></span>
                        <span class="material-symbols-outlined chevron text-gray-400 transition-transform" style="font-size: 18px;">chevron_right</span>
                    </div>
                    <div class="category-content open pl-2">
                        <?php foreach ($elementsByCategory[$catId] as $id => $element): ?>
                        <div class="hb-element" data-element="<?= $id ?>" draggable="true">
                            <span class="material-symbols-outlined"><?= $element['icon'] ?></span>
                            <span><?= $element['label'] ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </aside>
    </div>

    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <script>
    const baseUrl = '<?= $base_url ?>';
    let currentDevice = 'desktop';
    let currentMode = 'desktop';
    let headerConfig = {
        desktop: {},
        desktop_sticky: {},
        mobile: {},
        mobile_drawer: {}
    };
    let elementSettings = {};
    
    // Initialize
    document.addEventListener('DOMContentLoaded', async () => {
        await loadConfig();
        initSortable();
        initTabs();
    });
    
    // Load saved configuration
    async function loadConfig() {
        try {
            const res = await fetch(`${baseUrl}/admin/api/header-builder/load`);
            const data = await res.json();
            if (data.success && data.data) {
                headerConfig = {
                    desktop: data.data.desktop || {},
                    desktop_sticky: data.data.desktop_sticky || {},
                    mobile: data.data.mobile || {},
                    mobile_drawer: data.data.mobile_drawer || {}
                };
                elementSettings = data.data.element_settings || {};
                renderSavedElements();
            }
        } catch (e) {
            console.error('Failed to load config:', e);
        }
    }
    
    // Render saved elements to their positions
    function renderSavedElements() {
        Object.keys(headerConfig).forEach(device => {
            const deviceConfig = headerConfig[device];
            Object.keys(deviceConfig).forEach(row => {
                const rowConfig = deviceConfig[row];
                Object.keys(rowConfig).forEach(col => {
                    const elements = rowConfig[col] || [];
                    const column = document.querySelector(
                        `[data-device="${device}"] [data-row="${row}"] [data-column="${col}"]`
                    );
                    if (column) {
                        elements.forEach(elementId => {
                            const el = createElementNode(elementId);
                            column.appendChild(el);
                        });
                    }
                });
            });
        });
    }
    
    // Create element node
    function createElementNode(elementId) {
        const elements = <?= json_encode($elements) ?>;
        const elementType = elementId.replace(/_\d+$/, '');
        const elementData = elements[elementType] || elements[elementId] || { label: elementId, icon: 'widgets' };
        
        const div = document.createElement('div');
        div.className = 'hb-element';
        div.dataset.element = elementId;
        div.innerHTML = `
            <span class="material-symbols-outlined">${elementData.icon}</span>
            <span>${elementData.label}</span>
            <span class="material-symbols-outlined hb-element-close" onclick="removeElement(this)">close</span>
        `;
        return div;
    }
    
    // Remove element
    function removeElement(btn) {
        const element = btn.closest('.hb-element');
        const column = element.closest('.hb-column');
        element.remove();
        updateConfig(column);
    }
    
    // Initialize sortable
    function initSortable() {
        // Make rows sortable
        document.querySelectorAll('.hb-rows-container').forEach(container => {
            new Sortable(container, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
            });
        });
        
        // Make columns sortable (for elements)
        document.querySelectorAll('.hb-column').forEach(column => {
            new Sortable(column, {
                group: 'elements',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onAdd: function(evt) {
                    const item = evt.item;
                    if (!item.querySelector('.hb-element-close')) {
                        // Clone from pool - add close button
                        const clone = item.cloneNode(true);
                        clone.innerHTML += '<span class="material-symbols-outlined hb-element-close" onclick="removeElement(this)">close</span>';
                        item.replaceWith(clone);
                    }
                    updateConfig(evt.to);
                },
                onUpdate: function(evt) {
                    updateConfig(evt.to);
                },
                onRemove: function(evt) {
                    updateConfig(evt.from);
                }
            });
        });
        
        // Make element pool items draggable
        document.querySelectorAll('.hb-elements-pool .hb-element').forEach(el => {
            new Sortable(el.parentElement, {
                group: {
                    name: 'elements',
                    pull: 'clone',
                    put: false
                },
                sort: false,
                animation: 150,
            });
        });
    }
    
    // Update configuration when elements change
    function updateConfig(column) {
        const device = column.dataset.device;
        const row = column.dataset.row;
        const col = column.dataset.column;
        
        if (!headerConfig[device]) headerConfig[device] = {};
        if (!headerConfig[device][row]) headerConfig[device][row] = {};
        
        const elements = [];
        column.querySelectorAll('.hb-element').forEach(el => {
            elements.push(el.dataset.element);
        });
        
        headerConfig[device][row][col] = elements;
    }
    
    // Tab switching
    function initTabs() {
        // Device tabs
        document.querySelectorAll('.device-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.device-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                currentDevice = tab.dataset.device;
                
                // Show/hide mode tabs
                document.getElementById('desktop-modes').classList.toggle('hidden', currentDevice !== 'desktop');
                document.getElementById('mobile-modes').classList.toggle('hidden', currentDevice !== 'mobile');
                
                // Update current mode
                if (currentDevice === 'desktop') {
                    currentMode = 'desktop';
                } else {
                    currentMode = 'mobile';
                }
                
                showBuilder(currentMode);
            });
        });
        
        // Mode tabs
        document.querySelectorAll('.mode-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                const parent = tab.parentElement;
                parent.querySelectorAll('.mode-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                currentMode = tab.dataset.mode;
                showBuilder(currentMode);
            });
        });
    }
    
    // Show specific builder
    function showBuilder(mode) {
        document.querySelectorAll('.builder-area').forEach(area => {
            area.classList.add('hidden');
        });
        document.getElementById(`builder-${mode}`).classList.remove('hidden');
    }
    
    // Save configuration
    document.getElementById('save-btn').addEventListener('click', async () => {
        const btn = document.getElementById('save-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin" style="font-size: 18px;">sync</span> Saving...';
        
        try {
            // Save each device config
            for (const device of Object.keys(headerConfig)) {
                await fetch(`${baseUrl}/admin/api/header-builder/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ device, config: headerConfig[device] })
                });
            }
            
            // Save element settings
            await fetch(`${baseUrl}/admin/api/header-builder/save-elements`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ settings: elementSettings })
            });
            
            document.getElementById('save-status').textContent = 'Saved ' + new Date().toLocaleTimeString();
            btn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">check</span> Saved!';
            
            setTimeout(() => {
                btn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">save</span> Save Header';
                btn.disabled = false;
            }, 1500);
            
        } catch (e) {
            console.error('Save failed:', e);
            btn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">error</span> Error';
            btn.disabled = false;
        }
    });
    </script>
</body>
</html>
