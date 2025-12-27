<?php
/**
 * Cache Management Page
 * 
 * Shows cache status and provides controls to clear different cache types.
 */

$baseUrl = \Core\Router::getBasePath();

// Get cache stats
$addonCacheFile = dirname(dirname(dirname(__DIR__))) . '/addons/.addon_cache.php';
$addonCacheExists = file_exists($addonCacheFile);
$addonCacheSize = $addonCacheExists ? filesize($addonCacheFile) : 0;
$addonCacheAge = $addonCacheExists ? time() - filemtime($addonCacheFile) : 0;

$contentStats = function_exists('zed_cache_stats') ? zed_cache_stats() : ['files' => 0, 'size' => 0];

$opcacheEnabled = function_exists('opcache_get_status');
$opcacheStatus = $opcacheEnabled ? @opcache_get_status(false) : null;

function formatAge($seconds) {
    if ($seconds < 60) return $seconds . ' seconds';
    if ($seconds < 3600) return floor($seconds / 60) . ' minutes';
    if ($seconds < 86400) return floor($seconds / 3600) . ' hours';
    return floor($seconds / 86400) . ' days';
}

function formatBytes($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
?>

<style>
    .cache-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }
    
    .cache-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }
    
    .cache-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }
    
    .cache-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .cache-card-icon.addon { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
    .cache-card-icon.content { background: linear-gradient(135deg, #10b981, #34d399); }
    .cache-card-icon.opcache { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    
    .cache-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }
    
    .cache-card-subtitle {
        font-size: 13px;
        color: #64748b;
    }
    
    .cache-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }
    
    .cache-stat {
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px;
    }
    
    .cache-stat-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 4px;
    }
    
    .cache-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }
    
    .cache-stat-value.success { color: #10b981; }
    .cache-stat-value.warning { color: #f59e0b; }
    .cache-stat-value.muted { color: #94a3b8; }
    
    .cache-btn {
        width: 100%;
        padding: 12px 20px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }
    
    .cache-btn-clear {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .cache-btn-clear:hover {
        background: #fecaca;
    }
    
    .cache-btn-clear:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .cache-btn-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .cache-btn-primary:hover {
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        transform: translateY(-1px);
    }
    
    .cache-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .cache-status-badge.active {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .cache-status-badge.inactive {
        background: #f1f5f9;
        color: #64748b;
    }
    
    .clear-all-section {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 16px;
        padding: 32px;
        text-align: center;
        color: white;
    }
    
    .clear-all-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .clear-all-desc {
        color: rgba(255,255,255,0.7);
        margin-bottom: 24px;
        font-size: 14px;
    }
    
    .cache-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        padding: 16px 24px;
        background: #1e293b;
        color: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        display: none;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        z-index: 1000;
    }
    
    .cache-toast.success { background: linear-gradient(135deg, #059669, #10b981); }
    .cache-toast.error { background: linear-gradient(135deg, #dc2626, #ef4444); }
    .cache-toast.show { display: flex; animation: slideIn 0.3s ease; }
    
    @keyframes slideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<div class="cache-grid">
    <!-- Addon Cache -->
    <div class="cache-card">
        <div class="cache-card-header">
            <div class="cache-card-icon addon">
                <span class="material-symbols-outlined">extension</span>
            </div>
            <div>
                <div class="cache-card-title">Addon Cache</div>
                <div class="cache-card-subtitle">File discovery optimization</div>
            </div>
        </div>
        
        <div class="cache-stats">
            <div class="cache-stat">
                <div class="cache-stat-label">Status</div>
                <div class="cache-stat-value <?= $addonCacheExists ? 'success' : 'muted' ?>">
                    <?= $addonCacheExists ? 'Cached' : 'Not Cached' ?>
                </div>
            </div>
            <div class="cache-stat">
                <div class="cache-stat-label">Age</div>
                <div class="cache-stat-value"><?= $addonCacheExists ? formatAge($addonCacheAge) : '-' ?></div>
            </div>
        </div>
        
        <button class="cache-btn cache-btn-clear" onclick="clearCache('addon')" <?= !$addonCacheExists ? 'disabled' : '' ?>>
            <span class="material-symbols-outlined">delete</span>
            Clear Addon Cache
        </button>
    </div>
    
    <!-- Content Cache -->
    <div class="cache-card">
        <div class="cache-card-header">
            <div class="cache-card-icon content">
                <span class="material-symbols-outlined">database</span>
            </div>
            <div>
                <div class="cache-card-title">Content Cache</div>
                <div class="cache-card-subtitle">Query results & options</div>
            </div>
        </div>
        
        <div class="cache-stats">
            <div class="cache-stat">
                <div class="cache-stat-label">Files</div>
                <div class="cache-stat-value"><?= $contentStats['files'] ?? 0 ?></div>
            </div>
            <div class="cache-stat">
                <div class="cache-stat-label">Size</div>
                <div class="cache-stat-value"><?= formatBytes($contentStats['size'] ?? 0) ?></div>
            </div>
        </div>
        
        <button class="cache-btn cache-btn-clear" onclick="clearCache('content')" <?= ($contentStats['files'] ?? 0) == 0 ? 'disabled' : '' ?>>
            <span class="material-symbols-outlined">delete</span>
            Clear Content Cache
        </button>
    </div>
    
    <!-- OPcache -->
    <div class="cache-card">
        <div class="cache-card-header">
            <div class="cache-card-icon opcache">
                <span class="material-symbols-outlined">bolt</span>
            </div>
            <div>
                <div class="cache-card-title">PHP OPcache</div>
                <div class="cache-card-subtitle">Bytecode compilation cache</div>
            </div>
        </div>
        
        <div class="cache-stats">
            <div class="cache-stat">
                <div class="cache-stat-label">Status</div>
                <div class="cache-stat-value <?= $opcacheStatus && $opcacheStatus['opcache_enabled'] ? 'success' : 'muted' ?>">
                    <?= $opcacheStatus && $opcacheStatus['opcache_enabled'] ? 'Enabled' : 'Disabled' ?>
                </div>
            </div>
            <div class="cache-stat">
                <div class="cache-stat-label">Memory</div>
                <div class="cache-stat-value">
                    <?= $opcacheStatus ? formatBytes($opcacheStatus['memory_usage']['used_memory'] ?? 0) : '-' ?>
                </div>
            </div>
        </div>
        
        <button class="cache-btn cache-btn-clear" onclick="clearCache('opcache')" <?= !$opcacheEnabled ? 'disabled' : '' ?>>
            <span class="material-symbols-outlined">refresh</span>
            Reset OPcache
        </button>
    </div>
</div>

<!-- Clear All Section -->
<div class="clear-all-section">
    <div class="clear-all-title">🚀 Clear All Caches</div>
    <div class="clear-all-desc">Reset all cached data. This is safe to do anytime.</div>
    <button class="cache-btn cache-btn-primary" onclick="clearCache('all')" style="max-width: 300px; margin: 0 auto;">
        <span class="material-symbols-outlined">cleaning_services</span>
        Clear All Caches
    </button>
</div>

<!-- Toast -->
<div class="cache-toast" id="cacheToast">
    <span class="material-symbols-outlined" id="toastIcon">check_circle</span>
    <span id="toastMessage">Cache cleared!</span>
</div>

<script>
async function clearCache(type) {
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined">sync</span> Clearing...';
    
    try {
        const response = await fetch('<?= $baseUrl ?>/admin/api/cache/clear', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: type })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            // Reload after a short delay to update stats
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(result.error || 'Failed to clear cache', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (err) {
        showToast('Error: ' + err.message, 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('cacheToast');
    const icon = document.getElementById('toastIcon');
    const msg = document.getElementById('toastMessage');
    
    toast.className = 'cache-toast show ' + type;
    icon.textContent = type === 'success' ? 'check_circle' : 'error';
    msg.textContent = message;
    
    setTimeout(() => toast.classList.remove('show'), 3000);
}
</script>
