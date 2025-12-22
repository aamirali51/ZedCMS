<?php
/**
 * Addon Settings List View
 * 
 * Displays a grid of all addons with registered settings.
 * 
 * Available variables:
 * - $addons: array of addon settings configurations
 */

use Core\Router;

$base_url = Router::getBasePath();
?>

<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Addon Settings</h1>
        <p class="mt-2 text-gray-600">Configure settings for installed addons</p>
    </div>
    
    <?php if (empty($addons)): ?>
        <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
            <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">extension_off</span>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No Addon Settings</h3>
            <p class="text-gray-500">No addons have registered settings pages yet.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($addons as $addon_id => $config): ?>
                <a href="<?= $base_url ?>/admin/addon-settings/<?= htmlspecialchars($addon_id) ?>" 
                   class="block bg-white rounded-lg border border-gray-200 p-6 hover:border-indigo-500 hover:shadow-lg transition-all group">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-500 transition-colors">
                            <span class="material-symbols-outlined text-2xl text-indigo-600 group-hover:text-white">tune</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">
                                <?= htmlspecialchars($config['title'] ?? ucwords(str_replace('_', ' ', $addon_id))) ?>
                            </h3>
                            <?php if (!empty($config['description'])): ?>
                                <p class="text-sm text-gray-500 line-clamp-2"><?= htmlspecialchars($config['description']) ?></p>
                            <?php endif; ?>
                            <div class="mt-3 flex items-center text-sm text-indigo-600 font-medium">
                                Configure
                                <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
