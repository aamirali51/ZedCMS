<?php
/**
 * Header Element: Mobile Menu Toggle
 * 
 * @var array $settings Element settings
 */
?>
<button type="button" id="hb-mobile-menu-toggle" 
        class="hb-el-mobile-toggle lg:hidden p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
        onclick="document.getElementById('hb-mobile-drawer')?.classList.toggle('translate-x-full')">
    <span class="material-symbols-outlined" style="font-size: 26px;">menu</span>
</button>
