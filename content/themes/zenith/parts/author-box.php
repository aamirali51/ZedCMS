<?php
/**
 * Zenith Theme — Author Box Part
 * 
 * Author bio section with avatar and social links
 * 
 * @package Zenith
 * 
 * Expected variables:
 * @var array $author - Author data from zed_get_author()
 */

declare(strict_types=1);

use Core\Router;

if (empty($author)) return;

$base_url = Router::getBasePath();
$name = htmlspecialchars($author['display_name'] ?? 'Anonymous');
$bio = htmlspecialchars($author['bio'] ?? '');
$avatar = $author['avatar'] ?? '';
?>

<div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row gap-6 items-start md:items-center">
    <!-- Avatar -->
    <?php if ($avatar): ?>
    <img src="<?= htmlspecialchars($avatar) ?>" alt="<?= $name ?>" 
         class="w-20 h-20 rounded-full object-cover ring-4 ring-white dark:ring-slate-700 flex-shrink-0">
    <?php else: ?>
    <div class="w-20 h-20 rounded-full bg-accent/10 flex items-center justify-center flex-shrink-0">
        <span class="text-2xl font-bold text-accent"><?= strtoupper(substr($name, 0, 1)) ?></span>
    </div>
    <?php endif; ?>
    
    <!-- Info -->
    <div class="flex-1">
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Written by</p>
        <h4 class="text-xl font-serif font-bold text-slate-900 dark:text-white mb-2"><?= $name ?></h4>
        <?php if ($bio): ?>
        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
            <?= $bio ?>
        </p>
        <?php endif; ?>
    </div>
    
    <!-- Follow Button -->
    <button class="px-5 py-2.5 bg-accent hover:bg-accent/90 text-white text-sm font-medium rounded-lg transition-colors flex-shrink-0">
        Follow
    </button>
</div>
