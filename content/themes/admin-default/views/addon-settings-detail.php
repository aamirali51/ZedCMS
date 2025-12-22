<?php
/**
 * Addon Settings Detail View
 * 
 * Displays the settings form for a specific addon.
 * 
 * Available variables:
 * - $addon_id: string Addon identifier
 * - $config: array Configuration with title, description, fields
 */

use Core\Router;

$title = $config['title'] ?? 'Addon Settings';
$description = $config['description'] ?? '';
$fields = $config['fields'] ?? [];
$base_url = Router::getBasePath();
?>

<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="<?= $base_url ?>/admin/addon-settings" class="text-gray-400 hover:text-gray-600 transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h1>
        </div>
        <?php if ($description): ?>
            <p class="text-gray-600 ml-9"><?= htmlspecialchars($description) ?></p>
        <?php endif; ?>
    </div>

    <!-- Settings Form -->
    <form method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-500">tune</span>
                Configuration
            </h2>
        </div>
        
        <div class="p-6 space-y-6">
            <?php if (empty($fields)): ?>
                <div class="text-center py-12">
                     <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">settings_off</span>
                    <p class="text-gray-500">No settings configured for this addon.</p>
                </div>
            <?php else: ?>
                <?php foreach ($fields as $field): ?>
                    <?= zed_render_settings_field($field, $addon_id) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($fields)): ?>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl flex items-center justify-between">
                <span class="text-sm text-gray-500 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">info</span>
                    Settings are saved immediately.
                </span>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 hover:shadow-md transition-all focus:ring-4 focus:ring-indigo-100">
                    <span class="material-symbols-outlined text-xl">save</span>
                    Save Changes
                </button>
            </div>
        <?php endif; ?>
    </form>
</div>
