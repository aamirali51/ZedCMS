<?php
/**
 * Media Library - Superior to WordPress
 * Features: Drag & Drop, Real-time Search, Clipboard Copy with Toast, WebP Optimization
 */
$baseUrl = \Core\Router::getBasePath();

// Note: $uploadApiUrl, $deleteUrl, $siteUrl, and formatBytes() are provided by the route
?>

<style>
    /* ========== MEDIA MANAGER STYLES ========== */
    :root {
        --media-primary: #6366f1;
        --media-primary-hover: #4f46e5;
        --media-success: #10b981;
        --media-danger: #ef4444;
        --media-bg: #f8fafc;
        --media-card-bg: #ffffff;
        --media-border: #e2e8f0;
        --media-text: #1e293b;
        --media-text-muted: #64748b;
        --media-shadow: 0 1px 3px rgba(0,0,0,0.1);
        --media-shadow-lg: 0 10px 40px rgba(0,0,0,0.15);
    }

    .media-manager {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Sticky Toolbar */
    .media-toolbar {
        position: sticky;
        top: 0;
        z-index: 40;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.03) 0%, rgba(168, 85, 247, 0.03) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid var(--media-border);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: var(--media-shadow);
    }

    .media-toolbar-inner {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 16px;
    }

    /* Search Bar */
    .media-search-wrapper {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 400px;
    }

    .media-search {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border: 2px solid var(--media-border);
        border-radius: 12px;
        font-size: 14px;
        background: white;
        transition: all 0.2s ease;
        outline: none;
    }

    .media-search:focus {
        border-color: var(--media-primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .media-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--media-text-muted);
        pointer-events: none;
    }

    /* Upload Button */
    .media-upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: linear-gradient(135deg, var(--media-primary) 0%, #8b5cf6 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .media-upload-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .media-upload-btn:active {
        transform: translateY(0);
    }

    /* Stats Badge */
    .media-stats {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-left: auto;
    }

    .media-stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: var(--media-card-bg);
        border: 1px solid var(--media-border);
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        color: var(--media-text-muted);
    }

    .media-stat-badge .count {
        font-weight: 700;
        color: var(--media-primary);
    }

    /* Drop Zone */
    .media-dropzone {
        position: relative;
        min-height: 300px;
    }

    .media-dropzone.dragging::before {
        content: '';
        position: absolute;
        inset: -4px;
        border: 3px dashed var(--media-primary);
        border-radius: 20px;
        background: rgba(99, 102, 241, 0.05);
        z-index: 30;
        pointer-events: none;
    }

    .media-dropzone.dragging::after {
        content: 'Drop files to upload';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 16px 32px;
        background: var(--media-primary);
        color: white;
        font-weight: 600;
        font-size: 16px;
        border-radius: 12px;
        z-index: 31;
        box-shadow: var(--media-shadow-lg);
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.02); }
    }

    /* Grid */
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
    }

    /* Card */
    .media-card {
        position: relative;
        aspect-ratio: 1;
        border-radius: 12px;
        overflow: hidden;
        background: var(--media-card-bg);
        border: 1px solid var(--media-border);
        box-shadow: var(--media-shadow);
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .media-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.12);
        border-color: var(--media-primary);
    }

    .media-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .media-card:hover img {
        transform: scale(1.08);
    }

    /* Card Overlay */
    .media-card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, 
            transparent 0%, 
            transparent 40%, 
            rgba(0,0,0,0.7) 70%, 
            rgba(0,0,0,0.9) 100%);
        opacity: 0;
        transition: opacity 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 12px;
    }

    .media-card:hover .media-card-overlay {
        opacity: 1;
    }

    .media-card-name {
        color: white;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 4px;
    }

    .media-card-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,0.7);
        font-size: 10px;
    }

    .media-card-size {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 2px 6px;
        background: rgba(255,255,255,0.15);
        border-radius: 4px;
    }

    .media-card-webp {
        display: inline-flex;
        align-items: center;
        padding: 2px 6px;
        background: var(--media-success);
        border-radius: 4px;
        font-weight: 600;
        color: white;
    }

    /* Delete Button */
    .media-delete-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        opacity: 0;
        transform: translateY(-4px);
        transition: all 0.2s ease;
        z-index: 10;
    }

    .media-card:hover .media-delete-btn {
        opacity: 1;
        transform: translateY(0);
    }

    .media-delete-btn:hover {
        background: var(--media-danger);
        transform: scale(1.1);
    }

    /* Copy Indicator */
    .media-copy-indicator {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        padding: 8px 16px;
        background: rgba(16, 185, 129, 0.95);
        color: white;
        font-weight: 600;
        font-size: 12px;
        border-radius: 8px;
        opacity: 0;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
        z-index: 20;
    }

    .media-card.copied .media-copy-indicator {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }

    /* Empty State */
    .media-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 40px;
        background: linear-gradient(135deg, var(--media-bg) 0%, #f1f5f9 100%);
        border: 2px dashed var(--media-border);
        border-radius: 20px;
    }

    .media-empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--media-primary);
    }

    .media-empty h3 {
        font-size: 20px;
        font-weight: 700;
        color: var(--media-text);
        margin-bottom: 8px;
    }

    .media-empty p {
        color: var(--media-text-muted);
        font-size: 14px;
        margin-bottom: 20px;
    }

    /* Toast Notification */
    .media-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        background: #1e293b;
        color: white;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        box-shadow: var(--media-shadow-lg);
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
    }

    .media-toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .media-toast.success {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    }

    .media-toast.error {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    }

    /* Upload Progress */
    .media-upload-progress {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(99, 102, 241, 0.1);
        z-index: 1001;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .media-upload-progress.active {
        opacity: 1;
    }

    .media-upload-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--media-primary) 0%, #8b5cf6 100%);
        width: 0%;
        transition: width 0.3s ease;
    }

    /* Hidden file input */
    #mediaFileInput {
        display: none;
    }

    /* Loading skeleton */
    .media-skeleton {
        aspect-ratio: 1;
        border-radius: 12px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Responsive */
    @media (max-width: 640px) {
        .media-toolbar-inner {
            flex-direction: column;
            align-items: stretch;
        }

        .media-search-wrapper {
            max-width: none;
        }

        .media-stats {
            justify-content: center;
        }

        .media-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 12px;
        }
    }

    /* Media Select Checkbox */
    .media-select-checkbox {
        position: absolute;
        top: 8px;
        left: 8px;
        z-index: 25;
        width: 20px;
        height: 20px;
        border-radius: 6px;
        border: 2px solid rgba(255,255,255,0.8);
        background: rgba(0,0,0,0.4);
        appearance: none;
        cursor: pointer;
        transition: all 0.2s;
        opacity: 0;
    }
    .media-card:hover .media-select-checkbox,
    .media-card.selected .media-select-checkbox,
    .media-select-checkbox:checked {
        opacity: 1;
    }
    .media-select-checkbox:checked {
        background: var(--media-primary);
        border-color: var(--media-primary);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z'/%3E%3C/svg%3E");
        background-size: 16px;
        background-position: center;
        background-repeat: no-repeat;
    }
    .media-card.selected {
        box-shadow: 0 0 0 3px var(--media-primary);
         transform: translateY(-4px);
    }
</style>

<div class="media-manager">
    <!-- Progress Bar -->
    <div class="media-upload-progress" id="uploadProgress">
        <div class="media-upload-progress-bar" id="uploadProgressBar"></div>
    </div>

    <!-- Sticky Toolbar -->
    <div class="media-toolbar">
        <div class="media-toolbar-inner">
            <!-- Search -->
            <div class="media-search-wrapper">
                <span class="media-search-icon material-symbols-outlined">search</span>
                <input type="text" 
                       class="media-search" 
                       id="mediaSearch" 
                       placeholder="Search media files..." 
                       autocomplete="off">
            </div>

            <!-- Batch Delete Button -->
            <button type="button" class="media-upload-btn" id="batchDeleteBtn" style="background: linear-gradient(135deg, var(--media-danger) 0%, #dc2626 100%); display: none;">
                <span class="material-symbols-outlined">delete</span>
                Delete Selected (<span id="selectedMediaCount">0</span>)
            </button>

            <!-- Upload Button -->
            <button type="button" class="media-upload-btn" id="uploadBtn">
                <span class="material-symbols-outlined">cloud_upload</span>
                Upload New
            </button>
            <input type="file" 
                   id="mediaFileInput" 
                   accept="image/jpeg,image/png,image/gif,image/webp" 
                   multiple>

            <!-- Stats -->
            <div class="media-stats">
                <span class="media-stat-badge">
                    <span class="material-symbols-outlined" style="font-size: 16px;">perm_media</span>
                    <span class="count" id="mediaCount"><?php echo count($files); ?></span> items
                </span>
                <?php 
                $totalSize = array_sum(array_column($files, 'size'));
                $webpCount = count(array_filter($files, fn($f) => $f['isWebp'] ?? false));
                ?>
                <span class="media-stat-badge">
                    <span class="material-symbols-outlined" style="font-size: 16px; color: #10b981;">bolt</span>
                    <span class="count"><?php echo $webpCount; ?></span> WebP
                </span>
            </div>
        </div>
    </div>

    <!-- Drop Zone + Grid -->
    <div class="media-dropzone" id="mediaDropzone">
        <div class="media-grid" id="mediaGrid">
            <?php if (empty($files)): ?>
                <div class="media-empty" id="emptyState">
                    <div class="media-empty-icon">
                        <span class="material-symbols-outlined" style="font-size: 40px;">add_photo_alternate</span>
                    </div>
                    <h3>No media yet</h3>
                    <p>Drag & drop files here or click Upload to get started</p>
                    <button type="button" class="media-upload-btn" onclick="document.getElementById('mediaFileInput').click()">
                        <span class="material-symbols-outlined">cloud_upload</span>
                        Upload Files
                    </button>
                </div>
            <?php else: ?>
                <?php foreach ($files as $file): ?>
                <div class="media-card" 
                     data-id="<?php echo htmlspecialchars($file['id'] ?? ''); ?>"
                     data-url="<?php echo htmlspecialchars($file['url']); ?>"
                     data-name="<?php echo htmlspecialchars(strtolower($file['name'])); ?>">
                    <img src="<?php echo htmlspecialchars($file['thumb']); ?>" 
                         alt="<?php echo htmlspecialchars($file['name']); ?>"
                         loading="lazy"
                         onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%23f1f5f9%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 font-size=%2212%22 text-anchor=%22middle%22 fill=%22%2394a3b8%22>No Preview</text></svg>'">
                    
                    <!-- Checkbox -->
                    <input type="checkbox" class="media-select-checkbox" value="<?php echo htmlspecialchars($file['id'] ?? $file['name']); ?>">
                    
                    <!-- Delete Button -->
                    <button type="button" 
                            class="media-delete-btn" 
                            data-id="<?php echo htmlspecialchars($file['id'] ?? ''); ?>"
                            data-file="<?php echo htmlspecialchars($file['name']); ?>"
                            title="Delete">
                        <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                    </button>

                    <!-- Overlay -->
                    <div class="media-card-overlay">
                        <div class="media-card-name" title="<?php echo htmlspecialchars($file['name']); ?>">
                            <?php echo htmlspecialchars($file['name']); ?>
                        </div>
                        <div class="media-card-meta">
                            <span class="media-card-size"><?php echo formatBytes($file['size']); ?></span>
                            <?php if (!empty($file['width']) && !empty($file['height'])): ?>
                            <span class="media-card-dims"><?php echo $file['width']; ?>×<?php echo $file['height']; ?></span>
                            <?php endif; ?>
                            <?php if ($file['isWebp'] ?? false): ?>
                            <span class="media-card-webp">WebP</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Copy Indicator -->
                    <div class="media-copy-indicator">
                        <span class="material-symbols-outlined" style="font-size: 14px;">check</span>
                        Copied!
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Toast -->
    <div class="media-toast" id="mediaToast">
        <span class="material-symbols-outlined" id="toastIcon">check_circle</span>
        <span id="toastMessage">URL copied to clipboard!</span>
    </div>
</div>

<script>
(function() {
    'use strict';

    const uploadApiUrl = '<?php echo $uploadApiUrl; ?>';
    const deleteUrl = '<?php echo $deleteUrl; ?>';
    
    // Elements
    const dropzone = document.getElementById('mediaDropzone');
    const grid = document.getElementById('mediaGrid');
    const searchInput = document.getElementById('mediaSearch');
    const uploadBtn = document.getElementById('uploadBtn');
    const fileInput = document.getElementById('mediaFileInput');
    const toast = document.getElementById('mediaToast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');
    const progress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('uploadProgressBar');
    const countEl = document.getElementById('mediaCount');

    // ========== TOAST SYSTEM ==========
    let toastTimeout;
    function showToast(message, type = 'success') {
        clearTimeout(toastTimeout);
        toast.className = 'media-toast ' + type;
        toastIcon.textContent = type === 'success' ? 'check_circle' : 'error';
        toastMessage.textContent = message;
        toast.classList.add('show');
        toastTimeout = setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // ========== COPY TO CLIPBOARD ==========
    grid.addEventListener('click', async (e) => {
        const card = e.target.closest('.media-card');
        if (!card) return;

        // Ignore if clicking delete button
        if (e.target.closest('.media-delete-btn')) return;

        const url = card.dataset.url;
        try {
            await navigator.clipboard.writeText(url);
            card.classList.add('copied');
            showToast('URL copied to clipboard!');
            setTimeout(() => card.classList.remove('copied'), 1500);
        } catch (err) {
            showToast('Failed to copy URL', 'error');
        }
    });

    // ========== REAL-TIME SEARCH ==========
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase().trim();
        const cards = grid.querySelectorAll('.media-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name || '';
            const matches = name.includes(query);
            card.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        // Update count
        if (countEl) countEl.textContent = visibleCount;
    });

    // ========== UPLOAD BUTTON ==========
    uploadBtn.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            uploadFiles(Array.from(e.target.files));
            fileInput.value = '';
        }
    });

    // ========== DRAG & DROP ==========
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('dragging');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('dragging');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const files = Array.from(e.dataTransfer.files).filter(f => 
            f.type.startsWith('image/')
        );
        if (files.length > 0) {
            uploadFiles(files);
        }
    });

    // ========== UPLOAD FILES ==========
    async function uploadFiles(files) {
        progress.classList.add('active');
        let completed = 0;
        const total = files.length;

        for (const file of files) {
            try {
                const formData = new FormData();
                formData.append('file', file);

                const response = await fetch(uploadApiUrl, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success || result.status === 'success') {
                    completed++;
                    progressBar.style.width = ((completed / total) * 100) + '%';
                } else {
                    showToast('Upload failed: ' + (result.error || 'Unknown error'), 'error');
                }
            } catch (err) {
                showToast('Upload error: ' + err.message, 'error');
            }
        }

        // Complete
        progressBar.style.width = '100%';
        
        if (completed > 0) {
            showToast(`${completed} file${completed > 1 ? 's' : ''} uploaded successfully!`);
            // Refresh after a brief delay
            setTimeout(() => location.reload(), 500);
        }

        setTimeout(() => {
            progress.classList.remove('active');
            progressBar.style.width = '0%';
        }, 1000);
    }

    // ========== DELETE ==========
    grid.addEventListener('click', async (e) => {
        const deleteBtn = e.target.closest('.media-delete-btn');
        if (!deleteBtn) return;

        e.preventDefault();
        e.stopPropagation();

        const mediaId = deleteBtn.dataset.id;
        const fileName = deleteBtn.dataset.file;
        if (!confirm('Delete "' + fileName + '" permanently?')) return;

        try {
            // Prefer ID-based deletion for database integrity
            const params = mediaId ? 'id=' + encodeURIComponent(mediaId) : 'file=' + encodeURIComponent(fileName);
            const response = await fetch(deleteUrl + '?' + params);
            const result = await response.json();
            
            if (result.success) {
                // Remove card from DOM with animation
                const card = deleteBtn.closest('.media-card');
                if (card) {
                    card.style.transform = 'scale(0.8)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        // Update count
                        const remaining = grid.querySelectorAll('.media-card').length;
                        if (countEl) countEl.textContent = remaining;
                        
                        // Show empty state if no files
                        if (remaining === 0) {
                            location.reload();
                        }
                    }, 200);
                }
                showToast('File deleted successfully');
            } else {
                showToast('Delete failed: ' + (result.error || 'Unknown error'), 'error');
            }
        } catch (err) {
            showToast('Delete failed: ' + err.message, 'error');
        }
    });

    // ========== BATCH DELETE ==========
    const batchDeleteBtn = document.getElementById('batchDeleteBtn');
    const selectedMediaCountSpan = document.getElementById('selectedMediaCount');

    function updateBatchActions() {
        const checked = grid.querySelectorAll('.media-select-checkbox:checked');
        const count = checked.length;
        if (selectedMediaCountSpan) selectedMediaCountSpan.textContent = count;
        
        if (count > 0) {
            batchDeleteBtn.style.display = 'inline-flex';
        } else {
            batchDeleteBtn.style.display = 'none';
        }
    }

    grid.addEventListener('change', (e) => {
        if(e.target.classList.contains('media-select-checkbox')) {
            const card = e.target.closest('.media-card');
            if(e.target.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
            updateBatchActions();
        }
    });
    
    // Prevent clicking checkbox from triggering card click (which copies URL)
    grid.addEventListener('click', (e) => {
        if(e.target.classList.contains('media-select-checkbox')) {
            e.stopPropagation();
        }
    });

    if (batchDeleteBtn) {
        batchDeleteBtn.addEventListener('click', async () => {
            const checked = Array.from(grid.querySelectorAll('.media-select-checkbox:checked'));
            const files = checked.map(cb => cb.value);
            
            if (files.length === 0) return;
            
            if (!confirm(`Permanently delete ${files.length} selected files?`)) return;
            
            try {
                batchDeleteBtn.disabled = true;
                batchDeleteBtn.textContent = 'Deleting...';
                
                const response = await fetch('<?php echo \Core\Router::url('/admin/api/batch-delete-media'); ?>', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-ZED-NONCE': window.ZED_NONCE || ''
                    },
                    body: JSON.stringify({ files: files })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(`${result.count} files deleted`);
                    setTimeout(() => location.reload(), 500);
                } else {
                    showToast('Error: ' + (result.error || 'Unknown error'), 'error');
                    batchDeleteBtn.disabled = false;
                    batchDeleteBtn.innerHTML = '<span class="material-symbols-outlined">delete</span> Delete Selected (' + files.length + ')';
                }
            } catch (err) {
                showToast('Error: ' + err.message, 'error');
                batchDeleteBtn.disabled = false;
                batchDeleteBtn.innerHTML = '<span class="material-symbols-outlined">delete</span> Delete Selected (' + files.length + ')';
            }
        });
    }

    // ========== KEYBOARD SHORTCUTS ==========
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + U = Upload
        if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
            e.preventDefault();
            fileInput.click();
        }
        // Ctrl/Cmd + F = Focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            searchInput.focus();
        }
        // Escape = Clear search
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        }
    });

})();
</script>
