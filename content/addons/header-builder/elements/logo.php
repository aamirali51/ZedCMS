<?php
/**
 * Header Element: Logo
 * 
 * @var array $settings Element settings
 */

$logoUrl = zed_get_option('site_logo', '');
$siteName = zed_get_option('site_title', 'Zed CMS');
$base_url = \Core\Router::getBasePath();
?>
<a href="<?= $base_url ?>/" class="hb-el-logo flex items-center gap-2">
    <?php if ($logoUrl): ?>
        <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars($siteName) ?>" class="h-8 md:h-10">
    <?php else: ?>
        <span class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-extrabold rounded-xl shadow-lg">
            <?= strtoupper(substr($siteName, 0, 1)) ?>
        </span>
        <span class="font-bold text-xl text-gray-900 dark:text-white hidden sm:inline"><?= htmlspecialchars($siteName) ?></span>
    <?php endif; ?>
</a>
