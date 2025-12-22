<?php
/**
 * Template Library — Content Partial
 * 
 * This is included by showcase.php via the admin layout.
 */

use Core\Router;

$base_url = Router::getBasePath();
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-3">
            <span class="material-symbols-outlined text-indigo-500 text-4xl">auto_awesome_mosaic</span>
            Template Library
        </h1>
        <p class="text-slate-600 mt-2">
            Pre-built page templates that automatically adopt your theme's styling. 
            Click "Use Template" to create a new page.
        </p>
    </div>
    
    <!-- Template Categories -->
    <?php foreach ($categories as $categoryName => $categoryTemplates): ?>
    <div class="mb-8">
        <h2 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
            <span class="w-8 h-0.5 bg-indigo-500 rounded-full"></span>
            <?= htmlspecialchars($categoryName) ?>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($categoryTemplates as $slug => $template): 
                $icon = $template['icon'] ?? 'description';
                $colorClass = $iconColors[$icon] ?? 'text-slate-500 bg-slate-100';
            ?>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-200">
                <!-- Icon Preview Area -->
                <div class="h-40 bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center relative">
                    <div class="w-20 h-20 rounded-2xl <?= $colorClass ?> flex items-center justify-center">
                        <span class="material-symbols-outlined text-4xl"><?= $icon ?></span>
                    </div>
                </div>
                
                <!-- Info -->
                <div class="p-5">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-bold text-slate-900 text-lg">
                            <?= htmlspecialchars($template['name']) ?>
                        </h3>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-full font-medium">
                            <?= htmlspecialchars($template['category']) ?>
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 mb-4 leading-relaxed">
                        <?= htmlspecialchars($template['description']) ?>
                    </p>
                    
                    <button onclick="insertTemplate('<?= $slug ?>')" 
                            class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-semibold text-sm hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        Use This Template
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
    
    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8">
        <div class="flex gap-4">
            <span class="material-symbols-outlined text-blue-500 text-2xl flex-shrink-0">info</span>
            <div>
                <h4 class="font-bold text-blue-900 mb-1">How Templates Work</h4>
                <p class="text-blue-800 text-sm">
                    Templates automatically adopt your active theme's styling through the Theme Parts system. 
                    When you insert a template, it creates a new page that you can customize in the editor.
                    The template's layout and functionality remain, but colors, fonts, and navigation 
                    match your theme.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Insert Modal -->
<div id="insert-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
        <div class="text-center">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-indigo-600 text-3xl" id="modal-icon">hourglass_empty</span>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2" id="modal-title">Creating Page...</h3>
            <p class="text-slate-500" id="modal-message">Please wait while we set up your new page.</p>
        </div>
    </div>
</div>

<script>
async function insertTemplate(slug) {
    const modal = document.getElementById('insert-modal');
    const icon = document.getElementById('modal-icon');
    const title = document.getElementById('modal-title');
    const message = document.getElementById('modal-message');
    
    // Reset modal state
    icon.textContent = 'hourglass_empty';
    icon.className = 'material-symbols-outlined text-indigo-600 text-3xl';
    title.textContent = 'Creating Page...';
    message.textContent = 'Please wait while we set up your new page.';
    modal.classList.remove('hidden');
    
    try {
        const response = await fetch('<?= $base_url ?>/admin/template-library/insert', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'template=' + encodeURIComponent(slug)
        });
        
        const text = await response.text();
        let data;
        
        try {
            data = JSON.parse(text);
        } catch (e) {
            throw new Error('Server error: ' + text.substring(0, 200));
        }
        
        if (data.success && data.redirect) {
            icon.textContent = 'check_circle';
            icon.className = 'material-symbols-outlined text-green-500 text-3xl';
            title.textContent = 'Page Created!';
            message.textContent = 'Redirecting to editor...';
            
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 500);
        } else {
            throw new Error(data.error || 'Failed to create page');
        }
    } catch (err) {
        icon.textContent = 'error';
        icon.className = 'material-symbols-outlined text-red-500 text-3xl';
        title.textContent = 'Error';
        message.textContent = err.message;
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 4000);
    }
}
</script>
