<?php
/**
 * Addons Management - App Store Style Grid
 * 
 * Professional addon manager with toggle switches and upload.
 * Supports both single-file addons and folder-based addons.
 * 
 * Single file: addons/my_addon.php
 * Folder: addons/my_addon/addon.php
 */

use Core\Router;
use Core\Database;

$base_url = Router::getBasePath();
$addons_dir = realpath(__DIR__ . '/../../../addons');

// Discover all addons (single files + folders with addon.php)
$addon_files = [];

// 1. Single-file addons: addons/*.php
foreach (glob($addons_dir . '/*.php') ?: [] as $file) {
    $addon_files[] = [
        'path' => $file,
        'identifier' => basename($file),
        'type' => 'file',
    ];
}

// 2. Folder-based addons: addons/*/addon.php
foreach (glob($addons_dir . '/*/addon.php') ?: [] as $file) {
    $folderName = basename(dirname($file));
    $addon_files[] = [
        'path' => $file,
        'identifier' => $folderName, // Use folder name as identifier
        'type' => 'folder',
    ];
}

// Get system addons list (defined in index.php)
$system_addons = defined('ZERO_SYSTEM_ADDONS') ? ZERO_SYSTEM_ADDONS : ['admin_addon.php', 'frontend_addon.php'];

// Get active addons from database
$active_addons = null;
try {
    $db = Database::getInstance();
    $result = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_addons'");
    if ($result) {
        $active_addons = json_decode($result, true);
    }
} catch (Exception $e) {
    $active_addons = null;
}

// If no active_addons option exists, all non-system addons are active by default
if ($active_addons === null) {
    $active_addons = [];
    foreach ($addon_files as $addon) {
        $id = $addon['identifier'];
        if (!in_array($id, $system_addons, true)) {
            $active_addons[] = $id;
        }
    }
}

// Parse all addons
$addons = [];
foreach ($addon_files as $addonInfo) {
    $file = $addonInfo['path'];
    $identifier = $addonInfo['identifier'];
    $type = $addonInfo['type'];
    
    $isSystem = in_array($identifier, $system_addons, true);
    $isActive = $isSystem || in_array($identifier, $active_addons, true);
    
    // Generate display name from identifier
    $displayName = ucwords(str_replace(['_', '-', '.php'], [' ', ' ', ''], $identifier));
    
    // Default Metadata
    $meta = [
        'filename' => $identifier,
        'path' => $file,
        'type' => $type,
        'name' => $displayName,
        'description' => 'No description available',
        'version' => '1.0.0',
        'author' => 'Unknown',
        'license' => 'Unknown',
        'is_system' => $isSystem,
        'is_active' => $isActive
    ];
    
    // Parse Header from addon file
    $content = file_get_contents($file, false, null, 0, 4096);
    if (preg_match('|/\*\*(.*?)\*/|s', $content, $matches)) {
        $header = $matches[1];
        if (preg_match('/Addon Name:\s*(.*)$/mi', $header, $m)) $meta['name'] = trim($m[1]);
        elseif (preg_match('/Plugin Name:\s*(.*)$/mi', $header, $m)) $meta['name'] = trim($m[1]);
        
        if (preg_match('/Description:\s*(.*)$/mi', $header, $m)) $meta['description'] = trim($m[1]);
        if (preg_match('/Version:\s*(.*)$/mi', $header, $m)) $meta['version'] = trim($m[1]);
        if (preg_match('/Author:\s*(.*)$/mi', $header, $m)) $meta['author'] = trim($m[1]);
        if (preg_match('/License:\s*(.*)$/mi', $header, $m)) $meta['license'] = trim($m[1]);
    }
    
    $addons[] = $meta;
}

// Sort: system addons first, then alphabetically
usort($addons, function($a, $b) {
    if ($a['is_system'] !== $b['is_system']) {
        return $b['is_system'] ? 1 : -1;
    }
    return strcasecmp($a['name'], $b['name']);
});
?>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Addon Manager</h2>
            <p class="text-gray-500 mt-1">Manage your installed addons and extend functionality</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
                <?= count($addon_files) ?> Installed
            </span>
            <label class="relative cursor-pointer">
                <input type="file" id="addon-upload" accept=".php" class="sr-only">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">upload</span>
                    Upload Addon
                </span>
            </label>
        </div>
    </div>

    <?php
    // Check for theme addon dependencies
    $missing_addons = function_exists('zed_get_missing_theme_addons') ? zed_get_missing_theme_addons() : [];
    if (!empty($missing_addons)):
    ?>
    <!-- Theme Dependency Warning -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
        <div class="p-2 bg-amber-100 rounded-lg text-amber-600">
            <span class="material-symbols-outlined">warning</span>
        </div>
        <div class="flex-1">
            <h3 class="font-semibold text-amber-900">Theme Requires Additional Addons</h3>
            <p class="text-amber-700 text-sm mt-1">
                Your active theme requires the following addons to be enabled:
            </p>
            <div class="flex flex-wrap gap-2 mt-2">
                <?php foreach ($missing_addons as $addon): ?>
                    <span class="px-2 py-1 bg-amber-200 text-amber-800 text-xs font-medium rounded">
                        <?= htmlspecialchars($addon) ?>
                    </span>
                <?php endforeach; ?>
            </div>
            <p class="text-amber-600 text-xs mt-2">Enable these addons below to ensure full theme functionality.</p>
        </div>
    </div>
    <?php endif; ?>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php foreach ($addons as $addon): ?>
        <div class="addon-card bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow group"
             data-filename="<?= htmlspecialchars($addon['filename']) ?>"
             data-active="<?= $addon['is_active'] ? 'true' : 'false' ?>">
            
            <!-- Card Header -->
            <div class="px-5 py-4 border-b border-gray-100 flex items-start justify-between gap-4">
                <div class="flex items-start gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-lg shrink-0 flex items-center justify-center <?= $addon['is_system'] ? 'bg-purple-100 text-purple-600' : ($addon['is_active'] ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400') ?>">
                        <span class="material-symbols-outlined text-[20px]"><?= $addon['is_system'] ? 'verified' : 'extension' ?></span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate"><?= htmlspecialchars($addon['name']) ?></h3>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-gray-400 border border-gray-200 rounded px-1.5"><?= htmlspecialchars($addon['version']) ?></span>
                            <?php if ($addon['license'] === 'MIT'): ?>
                                <span class="text-[10px] text-green-600 bg-green-50 border border-green-100 rounded px-1.5">MIT</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Toggle Switch -->
                <?php if ($addon['is_system']): ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700 shrink-0">
                        <span class="material-symbols-outlined text-[12px]">lock</span>
                        System
                    </span>
                <?php else: ?>
                    <button type="button" 
                            class="addon-toggle relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 <?= $addon['is_active'] ? 'bg-green-500' : 'bg-gray-200' ?>"
                            role="switch"
                            aria-checked="<?= $addon['is_active'] ? 'true' : 'false' ?>">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out <?= $addon['is_active'] ? 'translate-x-5' : 'translate-x-0' ?>"></span>
                    </button>
                <?php endif; ?>
            </div>
            
            <!-- Card Body -->
            <div class="px-5 py-4">
                <p class="text-sm text-gray-600 line-clamp-2 min-h-[40px]"><?= htmlspecialchars($addon['description']) ?></p>
            </div>
            
            <!-- Card Footer -->
            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <span class="material-symbols-outlined text-[14px]">person</span>
                    <span><?= htmlspecialchars($addon['author']) ?></span>
                </div>
                <div class="flex items-center gap-1.5 text-xs <?= $addon['is_active'] ? 'text-green-600' : 'text-gray-400' ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?= $addon['is_active'] ? 'bg-green-500' : 'bg-gray-300' ?>"></span>
                    <span class="addon-status"><?= $addon['is_active'] ? 'Active' : 'Inactive' ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($addons)): ?>
        <div class="col-span-full py-16 text-center text-gray-500">
            <span class="material-symbols-outlined text-[64px] text-gray-300 mb-4">extension_off</span>
            <p class="text-lg font-medium">No addons installed</p>
            <p class="text-sm mt-1">Upload your first addon to get started</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = '<?= $base_url ?>';
    
    // Toast notification
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white text-sm font-medium transform translate-x-full transition-transform duration-300 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.innerHTML = `
            <span class="material-symbols-outlined text-[18px]">${type === 'success' ? 'check_circle' : 'error'}</span>
            <span>${message}</span>
        `;
        container.appendChild(toast);
        
        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full');
        });
        
        // Remove after 4s
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
    
    // Toggle addon
    document.querySelectorAll('.addon-toggle').forEach(toggle => {
        toggle.addEventListener('click', async function() {
            const card = this.closest('.addon-card');
            const filename = card.dataset.filename;
            const isActive = card.dataset.active === 'true';
            
            // Optimistic UI update
            const newState = !isActive;
            updateToggleUI(card, newState);
            
            try {
                const response = await fetch(`${baseUrl}/admin/api/toggle-addon`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ filename })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    card.dataset.active = data.active ? 'true' : 'false';
                    showToast(data.message, 'success');
                } else {
                    // Revert on error
                    updateToggleUI(card, isActive);
                    showToast(data.error || 'Failed to toggle addon', 'error');
                }
            } catch (err) {
                // Revert on network error
                updateToggleUI(card, isActive);
                showToast('Network error. Please try again.', 'error');
            }
        });
    });
    
    function updateToggleUI(card, isActive) {
        const toggle = card.querySelector('.addon-toggle');
        const icon = card.querySelector('.w-10.h-10');
        const status = card.querySelector('.addon-status');
        const statusDot = status?.previousElementSibling;
        
        if (toggle) {
            toggle.setAttribute('aria-checked', isActive);
            toggle.classList.toggle('bg-green-500', isActive);
            toggle.classList.toggle('bg-gray-200', !isActive);
            toggle.querySelector('span').classList.toggle('translate-x-5', isActive);
            toggle.querySelector('span').classList.toggle('translate-x-0', !isActive);
        }
        
        if (icon && !icon.classList.contains('bg-purple-100')) {
            icon.classList.toggle('bg-green-100', isActive);
            icon.classList.toggle('text-green-600', isActive);
            icon.classList.toggle('bg-gray-100', !isActive);
            icon.classList.toggle('text-gray-400', !isActive);
        }
        
        if (status) {
            status.textContent = isActive ? 'Active' : 'Inactive';
            status.classList.toggle('text-green-600', isActive);
            status.classList.toggle('text-gray-400', !isActive);
        }
        
        if (statusDot) {
            statusDot.classList.toggle('bg-green-500', isActive);
            statusDot.classList.toggle('bg-gray-300', !isActive);
        }
    }
    
    // Upload addon
    document.getElementById('addon-upload')?.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        if (!file.name.endsWith('.php')) {
            showToast('Only .php files are allowed', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('addon', file);
        
        try {
            const response = await fetch(`${baseUrl}/admin/api/upload-addon`, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast(data.message, 'success');
                // Reload page to show new addon
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.error || 'Upload failed', 'error');
            }
        } catch (err) {
            showToast('Network error. Please try again.', 'error');
        }
        
        // Reset input
        e.target.value = '';
    });
});
</script>
