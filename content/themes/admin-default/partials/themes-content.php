<?php
/**
 * Theme Manager - Visual Gallery Style
 * 
 * Instant theme switching with live color preview
 */

use Core\Router;

$base_url = Router::getBasePath();

// $themes and $activeTheme are set by the route handler in admin_addon.php
// Ensure they exist
if (!isset($themes)) $themes = [];
if (!isset($activeTheme)) $activeTheme = 'starter-theme';
?>

<!-- Toast Container -->
<div id="theme-toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Theme Manager</h2>
            <p class="text-gray-500 mt-1">Choose a visual style for your website</p>
        </div>
        <span class="px-3 py-1.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
            <?= count($themes) ?> Available
        </span>
    </div>

    <!-- Theme Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($themes as $theme): 
            $isActive = $theme['slug'] === $activeTheme;
        ?>
        <div class="theme-card relative bg-white rounded-xl border-2 overflow-hidden transition-all duration-200 hover:shadow-lg group <?= $isActive ? 'border-green-500 ring-2 ring-green-500/20' : 'border-gray-200 hover:border-gray-300' ?>"
             data-folder="<?= htmlspecialchars($theme['slug']) ?>">
            
            <!-- Active Banner -->
            <?php if ($isActive): ?>
            <div class="active-banner absolute top-3 right-3 z-10 inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                <span class="material-symbols-outlined text-[14px]">check_circle</span>
                Active
            </div>
            <?php endif; ?>
            
            <!-- Screenshot / Preview -->
            <div class="relative aspect-[16/10] bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                <?php if ($theme['screenshot']): ?>
                    <img src="<?= htmlspecialchars($theme['screenshot']) ?>" 
                         alt="<?= htmlspecialchars($theme['name']) ?>" 
                         class="w-full h-full object-cover">
                <?php else: ?>
                    <!-- Placeholder with color preview -->
                    <div class="w-full h-full flex items-center justify-center" 
                         style="background: linear-gradient(135deg, <?= htmlspecialchars($theme['colors']['background']) ?> 0%, <?= htmlspecialchars($theme['colors']['brand']) ?>22 100%);">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-[48px]" style="color: <?= htmlspecialchars($theme['colors']['brand']) ?>">palette</span>
                            <p class="text-sm font-medium mt-2" style="color: <?= htmlspecialchars($theme['colors']['text']) ?>"><?= htmlspecialchars($theme['name']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Hover overlay with activate button -->
                <?php if (!$isActive): ?>
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button type="button" 
                            class="activate-theme-btn px-5 py-2.5 bg-white text-gray-900 text-sm font-semibold rounded-lg shadow-lg hover:bg-gray-100 transition-colors transform scale-90 group-hover:scale-100 transition-transform">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">check</span>
                            Activate
                        </span>
                    </button>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Theme Info -->
            <div class="p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate"><?= htmlspecialchars($theme['name']) ?></h3>
                        <p class="text-xs text-gray-500 mt-0.5">by <?= htmlspecialchars($theme['author']) ?></p>
                    </div>
                    <span class="text-xs text-gray-400 border border-gray-200 rounded px-1.5 py-0.5 shrink-0"><?= htmlspecialchars($theme['version']) ?></span>
                </div>
                
                <?php if (!empty($theme['description'])): ?>
                <p class="text-sm text-gray-600 mt-2 line-clamp-2"><?= htmlspecialchars($theme['description']) ?></p>
                <?php endif; ?>
                
                <!-- Color Palette Preview -->
                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                    <span class="text-xs text-gray-400">Colors:</span>
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 rounded-full border-2 border-white shadow-sm" 
                             style="background-color: <?= htmlspecialchars($theme['colors']['brand']) ?>" 
                             title="Brand"></div>
                        <div class="w-5 h-5 rounded-full border-2 border-gray-200 shadow-sm" 
                             style="background-color: <?= htmlspecialchars($theme['colors']['background']) ?>" 
                             title="Background"></div>
                        <div class="w-5 h-5 rounded-full border-2 border-white shadow-sm" 
                             style="background-color: <?= htmlspecialchars($theme['colors']['text']) ?>" 
                             title="Text"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($themes)): ?>
        <div class="col-span-full py-16 text-center text-gray-500">
            <span class="material-symbols-outlined text-[64px] text-gray-300 mb-4">style</span>
            <p class="text-lg font-medium">No themes available</p>
            <p class="text-sm mt-1">Add themes to the content/themes directory</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = '<?= $base_url ?>';
    let currentActive = '<?= htmlspecialchars($activeTheme) ?>';
    
    // Toast notification
    function showToast(message, type = 'success') {
        const container = document.getElementById('theme-toast-container');
        const toast = document.createElement('div');
        toast.className = `flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white text-sm font-medium transform translate-x-full transition-transform duration-300 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.innerHTML = `
            <span class="material-symbols-outlined text-[18px]">${type === 'success' ? 'check_circle' : 'error'}</span>
            <span>${message}</span>
        `;
        container.appendChild(toast);
        
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full');
        });
        
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
    
    // Activate theme
    document.querySelectorAll('.activate-theme-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const card = this.closest('.theme-card');
            const folder = card.dataset.folder;
            
            // Disable button while loading
            this.disabled = true;
            this.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>';
            
            try {
                const response = await fetch(`${baseUrl}/admin/api/activate-theme`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-ZED-NONCE': window.ZED_NONCE || ''
                    },
                    body: JSON.stringify({ theme: folder })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update UI - remove active from previous, add to current
                    document.querySelectorAll('.theme-card').forEach(c => {
                        const isNowActive = c.dataset.folder === folder;
                        
                        // Update border styling
                        c.classList.toggle('border-green-500', isNowActive);
                        c.classList.toggle('ring-2', isNowActive);
                        c.classList.toggle('ring-green-500/20', isNowActive);
                        c.classList.toggle('border-gray-200', !isNowActive);
                        
                        // Handle banner
                        let banner = c.querySelector('.active-banner');
                        if (isNowActive && !banner) {
                            // Add banner
                            banner = document.createElement('div');
                            banner.className = 'active-banner absolute top-3 right-3 z-10 inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg';
                            banner.innerHTML = '<span class="material-symbols-outlined text-[14px]">check_circle</span> Active';
                            c.insertBefore(banner, c.firstChild);
                        } else if (!isNowActive && banner) {
                            // Remove banner
                            banner.remove();
                        }
                        
                        // Handle hover overlay
                        const overlay = c.querySelector('.absolute.inset-0.bg-black\\/50');
                        if (overlay) {
                            if (isNowActive) {
                                overlay.remove();
                            }
                        } else if (!isNowActive) {
                            // Add overlay back
                            const preview = c.querySelector('.aspect-\\[16\\/10\\]');
                            if (preview && !preview.querySelector('.activate-theme-btn')) {
                                const newOverlay = document.createElement('div');
                                newOverlay.className = 'absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center';
                                newOverlay.innerHTML = `
                                    <button type="button" class="activate-theme-btn px-5 py-2.5 bg-white text-gray-900 text-sm font-semibold rounded-lg shadow-lg hover:bg-gray-100 transition-colors transform scale-90 group-hover:scale-100 transition-transform">
                                        <span class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">check</span>
                                            Activate
                                        </span>
                                    </button>
                                `;
                                preview.appendChild(newOverlay);
                                // Re-attach event listener
                                newOverlay.querySelector('.activate-theme-btn').addEventListener('click', arguments.callee);
                            }
                        }
                    });
                    
                    currentActive = folder;
                    showToast(data.message, 'success');
                } else {
                    showToast(data.error || 'Failed to activate theme', 'error');
                    // Reset button
                    this.disabled = false;
                    this.innerHTML = '<span class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">check</span>Activate</span>';
                }
            } catch (err) {
                showToast('Network error. Please try again.', 'error');
                this.disabled = false;
                this.innerHTML = '<span class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">check</span>Activate</span>';
            }
        });
    });
});
</script>
