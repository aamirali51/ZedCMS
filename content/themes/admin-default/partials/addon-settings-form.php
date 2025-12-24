<?php
/**
 * Addon Settings Content Partial
 * 
 * Variables available:
 * - $addon_settings_id: string Addon identifier
 * - $addon_settings_config: array Configuration with title, description, fields
 */

$title = $addon_settings_config['title'] ?? 'Addon Settings';
$description = $addon_settings_config['description'] ?? '';
$fields = $addon_settings_config['fields'] ?? [];
$base_url = \Core\Router::getBasePath();
?>

<div class="max-w-3xl">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="<?= $base_url ?>/admin/addons" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h1>
        </div>
        <?php if ($description): ?>
            <p class="text-gray-600"><?= htmlspecialchars($description) ?></p>
        <?php endif; ?>
    </div>

    <!-- Settings Form -->
    <form method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-500">tune</span>
                Configuration
            </h2>
        </div>
        
        <div class="p-6 space-y-6">
            <?php if (empty($fields)): ?>
                <p class="text-gray-500 text-center py-8">No settings configured for this addon.</p>
            <?php else: ?>
                <?php foreach ($fields as $field): ?>
                    <?= zed_render_settings_field($field, $addon_settings_id) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($fields)): ?>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl flex items-center justify-between">
                <span class="text-sm text-gray-500">
                    <span class="material-symbols-outlined text-sm align-middle">info</span>
                    Settings are saved to the database.
                </span>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Save Settings
                </button>
            </div>
        <?php endif; ?>
    </form>
</div>
