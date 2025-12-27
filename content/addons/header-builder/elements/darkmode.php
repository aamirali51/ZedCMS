<?php
/**
 * Header Element: Dark Mode Toggle
 * 
 * @var array $settings Element settings
 */
?>
<button type="button" id="hb-darkmode-toggle" 
        class="hb-el-darkmode p-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
        onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')">
    <span class="material-symbols-outlined dark:hidden" style="font-size: 22px;">dark_mode</span>
    <span class="material-symbols-outlined hidden dark:block" style="font-size: 22px;">light_mode</span>
</button>
