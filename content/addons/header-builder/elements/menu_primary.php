<?php
/**
 * Header Element: Primary Menu
 * 
 * @var array $settings Element settings
 */

$menuId = $settings['menu_id'] ?? 1;
$menuItems = [];

try {
    $db = \Core\Database::getInstance();
    $menu = $db->queryOne("SELECT items FROM zed_menus WHERE id = :id", ['id' => $menuId]);
    if ($menu && !empty($menu['items'])) {
        $menuItems = json_decode($menu['items'], true) ?: [];
    }
} catch (Exception $e) {
    $menuItems = [];
}

$base_url = \Core\Router::getBasePath();
?>
<nav class="hb-el-menu hidden lg:flex items-center gap-1">
    <?php if (!empty($menuItems)): ?>
        <?php foreach ($menuItems as $item): ?>
            <a href="<?= htmlspecialchars($item['url'] ?? '#') ?>" 
               class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <?= htmlspecialchars($item['label'] ?? 'Menu Item') ?>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <a href="<?= $base_url ?>/" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 rounded-lg">Home</a>
        <a href="<?= $base_url ?>/about" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 rounded-lg">About</a>
        <a href="<?= $base_url ?>/contact" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 rounded-lg">Contact</a>
    <?php endif; ?>
</nav>
