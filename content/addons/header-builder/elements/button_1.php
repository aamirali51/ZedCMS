<?php
/**
 * Header Element: Button
 * 
 * @var array $settings Element settings
 * @var string $elementId The element ID (button_1, button_2, etc)
 */

$label = $settings['label'] ?? 'Get Started';
$url = $settings['url'] ?? '#';
$style = $settings['style'] ?? 'primary'; // primary, secondary, outline

$classes = match($style) {
    'secondary' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600',
    'outline' => 'border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white',
    default => 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white hover:from-indigo-600 hover:to-purple-700 shadow-md hover:shadow-lg',
};
?>
<a href="<?= htmlspecialchars($url) ?>" 
   class="hb-el-button inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg transition-all <?= $classes ?>">
    <?= htmlspecialchars($label) ?>
</a>
