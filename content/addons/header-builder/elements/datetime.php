<?php
/**
 * Header Element: Date & Time
 * 
 * @var array $settings Element settings
 */

$format = $settings['format'] ?? 'l, F j, Y';
?>
<div class="hb-el-datetime text-sm text-gray-500 dark:text-gray-400">
    <?= date($format) ?>
</div>
