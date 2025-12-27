<?php
/**
 * Header Element: Custom HTML
 * 
 * @var array $settings Element settings
 */

$html = $settings['html'] ?? '<span class="text-sm text-gray-500">Custom HTML</span>';
?>
<div class="hb-el-html">
    <?= $html ?>
</div>
