<?php
/**
 * Header Element: Login/Register
 * 
 * @var array $settings Element settings
 */

$isLoggedIn = \Core\Auth::check();
$base_url = \Core\Router::getBasePath();
?>
<div class="hb-el-login flex items-center gap-2">
    <?php if ($isLoggedIn): ?>
        <a href="<?= $base_url ?>/admin" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
            <span class="material-symbols-outlined" style="font-size: 20px;">dashboard</span>
            Dashboard
        </a>
    <?php else: ?>
        <a href="<?= $base_url ?>/admin/login" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
            <span class="material-symbols-outlined" style="font-size: 20px;">login</span>
            Login
        </a>
    <?php endif; ?>
</div>
