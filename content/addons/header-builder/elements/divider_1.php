<?php
/**
 * Header Element: Divider
 * 
 * @var array $settings Element settings
 */

$height = $settings['height'] ?? '24px';
$color = $settings['color'] ?? '#e5e7eb';
?>
<div class="hb-el-divider" style="width: 1px; height: <?= htmlspecialchars($height) ?>; background: <?= htmlspecialchars($color) ?>;"></div>
