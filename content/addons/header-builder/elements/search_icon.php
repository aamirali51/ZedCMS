<?php
/**
 * Header Element: Search Icon
 * 
 * @var array $settings Element settings
 */
?>
<button type="button" class="hb-el-search-icon p-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors" onclick="document.getElementById('hb-search-modal')?.classList.remove('hidden')">
    <span class="material-symbols-outlined" style="font-size: 22px;">search</span>
</button>

<!-- Search Modal (rendered once) -->
<div id="hb-search-modal" class="hidden fixed inset-0 z-50 flex items-start justify-center pt-20 bg-black/50 backdrop-blur-sm" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl mx-4">
        <form action="<?= \Core\Router::getBasePath() ?>/search" method="get" class="flex items-center">
            <span class="material-symbols-outlined text-gray-400 ml-6" style="font-size: 24px;">search</span>
            <input type="text" name="q" placeholder="Search posts, pages..." 
                   class="flex-1 px-4 py-5 text-lg bg-transparent border-0 focus:ring-0 focus:outline-none text-gray-900 dark:text-white" autofocus>
            <button type="button" class="p-4 text-gray-400 hover:text-gray-600" onclick="document.getElementById('hb-search-modal').classList.add('hidden')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </form>
    </div>
</div>
