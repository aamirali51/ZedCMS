<?php
/**
 * Addons Management Partial
 * 
 * Shows installed addons with System/Active status
 */

use Core\Router;
$base_url = Router::getBasePath();
$addons_dir = realpath(__DIR__ . '/../../../addons');
$addon_files = glob($addons_dir . '/*.php') ?: [];

// Get system addons list (defined in index.php)
$system_addons = defined('ZERO_SYSTEM_ADDONS') ? ZERO_SYSTEM_ADDONS : ['admin_addon.php', 'frontend_addon.php'];
?>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-gray-900">Installed Addons</h3>
            <p class="text-sm text-gray-500 mt-1">System addons cannot be disabled</p>
        </div>
        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
            <?= count($addon_files) ?> Total
        </span>
    </div>
    
    <div class="divide-y divide-gray-100">
        <?php foreach ($addon_files as $file): 
            $basename = basename($file);
            $isSystem = in_array($basename, $system_addons, true);
            
            // Default Metadata
            $meta = [
                'name' => ucwords(str_replace('_', ' ', basename($file, '.php'))),
                'description' => $basename,
                'version' => '1.0.0',
                'license' => 'Unknown'
            ];
            
            // Parse Header
            $content = file_get_contents($file, false, null, 0, 4096); // Read first 4KB
            if (preg_match('|/\*\*(.*?)\*/|s', $content, $matches)) {
                $header = $matches[1];
                if (preg_match('/Addon Name:\s*(.*)$/mi', $header, $m)) $meta['name'] = trim($m[1]);
                elseif (preg_match('/Plugin Name:\s*(.*)$/mi', $header, $m)) $meta['name'] = trim($m[1]);
                
                if (preg_match('/Description:\s*(.*)$/mi', $header, $m)) $meta['description'] = trim($m[1]);
                if (preg_match('/Version:\s*(.*)$/mi', $header, $m)) $meta['version'] = trim($m[1]);
                if (preg_match('/License:\s*(.*)$/mi', $header, $m)) $meta['license'] = trim($m[1]);
            }
        ?>
        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg <?= $isSystem ? 'bg-purple-100 text-purple-600' : 'bg-indigo-100 text-indigo-600' ?> flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]"><?= $isSystem ? 'lock' : 'extension' ?></span>
                </div>
                <div class="max-w-xl">
                    <div class="flex items-center gap-2">
                        <h4 class="font-medium text-gray-900"><?= htmlspecialchars($meta['name']) ?></h4>
                        <span class="text-[10px] text-gray-400 border border-gray-200 rounded px-1.5"><?= htmlspecialchars($meta['version']) ?></span>
                        <?php if ($meta['license'] === 'MIT'): ?>
                            <span class="text-[10px] text-green-600 bg-green-50 border border-green-100 rounded px-1.5" title="Open Source">MIT</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5"><?= htmlspecialchars($meta['description']) ?></p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <?php if ($isSystem): ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">
                        <span class="material-symbols-outlined text-[12px]">lock</span>
                        System
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Active
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($addon_files)): ?>
        <div class="py-12 text-center text-gray-500">
            <span class="material-symbols-outlined text-[48px] text-gray-300 mb-2">extension_off</span>
            <p>No addons installed</p>
        </div>
        <?php endif; ?>
    </div>
</div>
