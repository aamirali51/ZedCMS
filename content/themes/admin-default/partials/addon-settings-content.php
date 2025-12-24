<?php
/**
 * Addon Settings List Partial
 * 
 * Displays a list of addons that have registered settings.
 * 
 * Variables available:
 * - $addonSettings: array List of registered addon settings
 */

$base_url = \Core\Router::getBasePath();
?>

<div class="max-w-5xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Addon Settings</h1>
        <p class="text-gray-600">Configure settings for your installed addons.</p>
    </div>

    <!-- Alert for no settings -->
    <?php if (empty($addonSettings)): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <span class="material-symbols-outlined text-3xl text-gray-400">extension_off</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Configurable Addons</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-6">
                None of your active addons have registered any settings pages.
            </p>
            <a href="<?= $base_url ?>/admin/addons" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <span class="material-symbols-outlined">extension</span>
                Manage Addons
            </a>
        </div>
    <?php else: ?>
        <!-- Settings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($addonSettings as $id => $config): ?>
                <a href="<?= $base_url ?>/admin/addon-settings/<?= htmlspecialchars($id) ?>" 
                   class="group bg-white rounded-xl border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all p-6 block">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-2xl">tune</span>
                        </div>
                        <span class="material-symbols-outlined text-gray-300 group-hover:text-indigo-500 transition-colors">arrow_forward</span>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">
                        <?= htmlspecialchars($config['title']) ?>
                    </h3>
                    
                    <?php if (!empty($config['description'])): ?>
                        <p class="text-sm text-gray-500 line-clamp-2 mb-4">
                            <?= htmlspecialchars($config['description']) ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="flex items-center gap-2 text-xs font-medium text-gray-400">
                        <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">
                            <?= count($config['fields'] ?? []) ?> settings
                        </span>
                        <?php if (!empty($config['capability'])): ?>
                            <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600" title="Required Capability">
                                <?= htmlspecialchars($config['capability']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
