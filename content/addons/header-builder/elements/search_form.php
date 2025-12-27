<?php
/**
 * Header Element: Search Form
 * 
 * Inline search input form
 * 
 * @var array $settings Element settings
 */

$base_url = \Core\Router::getBasePath();
$placeholder = $settings['placeholder'] ?? 'Search...';
?>
<form action="<?= $base_url ?>/search" method="get" class="hb-el-search-form flex items-center">
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
            <span class="material-symbols-outlined" style="font-size: 20px;">search</span>
        </span>
        <input type="text" name="q" placeholder="<?= htmlspecialchars($placeholder) ?>" 
               class="pl-10 pr-4 py-2 w-48 lg:w-64 bg-gray-100 dark:bg-gray-800 border-0 rounded-full text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-gray-700 transition-all">
    </div>
</form>
