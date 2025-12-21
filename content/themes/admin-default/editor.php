<?php
/**
 * Zed CMS Editor (React + Tiptap Version)
 * 
 * Uses the built frontend bundle from _frontend/
 */

use Core\Auth;
use Core\Database;
use Core\Router;

// Check authentication
if (!Auth::check()) {
    Router::redirect('/admin/login');
}

// Get base URL for links
$base_url = Router::getBasePath();

// Initialize variables
// 1. Check if we are editing an existing post
$postId = $_GET['id'] ?? null;
$post = null;
$jsonContent = 'null'; // Default for new post
$featuredImageUrl = ''; // Initialize featured image URL

if ($postId) {
    try {
        $db = Database::getInstance();
        $post = $db->queryOne("SELECT * FROM zed_content WHERE id = :id", ['id' => $postId]);
        
        if ($post) {
            // Load the content for the JS Editor
            $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
            $jsonContent = isset($data['content']) ? json_encode($data['content']) : 'null';
            // Extract featured image URL from data
            $featuredImageUrl = $data['featured_image'] ?? '';
        }
    } catch (Exception $e) {
        $post = null;
    }
}

// Safe JSON for JS
$initialDataSafe = $jsonContent ?: '{}';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $post ? htmlspecialchars($post['title']) : 'New Entry' ?> — Zero CMS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#256af4",
                        success: "#10b981",
                    },
                    fontFamily: {
                        sans: ["Inter", "sans-serif"],
                        serif: ["Lora", "Georgia", "serif"],
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-gray-50 h-screen overflow-hidden">

<div class="flex h-screen overflow-hidden bg-gray-50">
<main class="flex-1 h-full overflow-y-auto bg-white">
        <!-- Editor Container - Full Width WordPress Style -->
        <div id="tiptap-editor" class="w-full min-h-full"></div>
    </main>
    
    <aside class="w-80 h-full bg-white border-l border-gray-200 overflow-y-auto shrink-0 z-20 shadow-sm flex flex-col">
        <div class="p-6 space-y-6">
            <!-- Header / Back Link -->
            <div class="mb-4">
                <a href="<?= $base_url ?>/admin/content" class="text-xs text-gray-500 hover:text-gray-900 flex items-center mb-2">
                     <span class="material-symbols-outlined text-[14px] mr-1">arrow_back</span>
                     Back to Content
                </a>
            </div>

            <!-- Title Input (Adding this back as it was lost in the snippet but essential) -->
             <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Title</label>
                <input 
                    id="post-title" 
                    type="text" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-bold"
                    value="<?= htmlspecialchars($post['title'] ?? '') ?>"
                    placeholder="Enter title here..."
                >
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status</label>
                <select id="post-status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="draft" <?= ($post['data']['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= ($post['data']['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
            
            <!-- Type (Adding back logic) -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Type</label>
                <select id="post-type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="page" <?= ($post['type'] ?? 'page') === 'page' ? 'selected' : '' ?>>Page</option>
                    <option value="post" <?= ($post['type'] ?? '') === 'post' ? 'selected' : '' ?>>Post</option>
                </select>
            </div>

            <!-- Save Button -->
            <button id="save-btn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-colors">
                Save Changes
            </button>
            
            <hr class="border-gray-100">
            
            <!-- Slug -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">URL Slug</label>
                <input 
                    id="post-slug" 
                    type="text" 
                    class="w-full border-gray-300 rounded-md shadow-sm text-sm" 
                    placeholder="my-post-url"
                    value="<?= htmlspecialchars($post['slug'] ?? '') ?>"
                >
            </div>
            
            <div id="save-feedback" class="text-xs text-center text-gray-500 h-4"></div>
            
            <!-- Categories Section -->
            <div class="border-t border-gray-100 pt-4 mt-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Categories</label>
                <div id="category-list" class="space-y-2 max-h-40 overflow-y-auto border border-gray-200 rounded p-2 bg-gray-50">
                    <div class="text-xs text-gray-400 text-center py-2">Loading categories...</div>
                </div>
                <button type="button" class="text-xs text-indigo-600 mt-2 font-medium hover:underline flex items-center gap-1 opacity-50 cursor-not-allowed" title="Coming Soon">
                    <span class="material-symbols-outlined text-[14px]">add</span>
                    Add New Category
                </button>
                
                <script>
                    document.addEventListener('DOMContentLoaded', async () => {
                        const list = document.getElementById('category-list');
                        
                        try {
                            const res = await fetch('<?php echo \Core\Router::url("/admin/api/categories"); ?>');
                            if (!res.ok) throw new Error('Fetch failed');
                            const categories = await res.json();
                            
                            // Get saved selection
                            // Content data is stored JSON string, parsed in JS variable
                            const contentData = window.ZERO_INITIAL_CONTENT?.data || {}; 
                            // Handle if data is string (double encoded) or object
                            const parsedData = typeof contentData === 'string' ? JSON.parse(contentData) : contentData;
                            const savedCats = parsedData.categories || [];
                            
                            if (categories.length === 0) {
                                list.innerHTML = '<div class="text-xs text-gray-500 p-2">No categories found.</div>';
                                return;
                            }
                            
                            list.innerHTML = categories.map(cat => {
                                const isChecked = savedCats.includes(cat.slug) ? 'checked' : '';
                                return `
                                    <label class="flex items-center space-x-2 cursor-pointer hover:bg-white rounded px-1 group">
                                        <input type="checkbox" name="categories[]" value="${cat.slug}" ${isChecked} class="rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="text-sm text-gray-700 group-hover:text-gray-900">${cat.name}</span>
                                    </label>
                                `;
                            }).join('');
                            
                        } catch (e) {
                            console.error('Category load error:', e);
                            list.innerHTML = '<div class="text-xs text-red-400 p-2">Failed to load categories</div>';
                        }
                    });
                </script>
            </div>
            
            <!-- Featured Image Section -->
            <div class="border-t border-gray-100 pt-4 mt-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Featured Image</label>
                <div id="featured-image-drop" class="border-2 border-dashed border-gray-300 rounded-md p-4 flex flex-col items-center justify-center text-center hover:bg-gray-50 hover:border-indigo-400 cursor-pointer transition-colors" onclick="document.getElementById('feat-upload').click()">
                    <div id="featured-preview" class="hidden w-full mb-2">
                        <img id="featured-img" src="" class="w-full h-32 object-cover rounded" alt="Featured">
                    </div>
                    <div id="featured-placeholder">
                        <svg class="h-10 w-10 text-gray-400 mb-2 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-xs text-gray-500">Click to upload or drag & drop</span>
                    </div>
                    <input type="file" id="feat-upload" class="hidden" accept="image/*">
                </div>
                <button type="button" id="remove-featured" class="hidden text-xs text-red-500 mt-2 font-medium hover:underline">Remove Image</button>
            </div>
            
            <!-- Excerpt Section -->
            <div class="border-t border-gray-100 pt-4 mt-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Excerpt</label>
                <textarea id="post-excerpt" rows="3" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Write a short summary..."><?= htmlspecialchars($post['data']['excerpt'] ?? '') ?></textarea>
            </div>
        </div>
    </aside>
</div>

<!-- DATA INJECTION -->
<script>
    window.ZERO_INITIAL_CONTENT = <?= $initialDataSafe ?>;
    const postId = "<?= htmlspecialchars($postId ?? '') ?>";
    const baseUrl = "<?= $base_url ?>";
    // Pre-populate featured image URL from PHP (if editing existing post)
    let featuredImageUrl = "<?= htmlspecialchars($featuredImageUrl) ?>";
</script>

<!-- REACT BUNDLE -->
<script src="<?= $base_url ?>/content/themes/admin-default/assets/js/editor.bundle.js"></script>

<!-- SAVE & UI LOGIC -->
<script>
// Zero CMS Configuration
const ZERO_BASE_URL = '<?= $base_url ?>';

// Sync slugs
const titleInput = document.getElementById('post-title');
const slugInput = document.getElementById('post-slug');

function generateSlug(text) {
    return text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
}

if (titleInput && slugInput) {
    titleInput.addEventListener('input', () => {
        if (!postId) {
            slugInput.value = generateSlug(titleInput.value);
        }
    });

    slugInput.addEventListener('input', () => slugInput.value = generateSlug(slugInput.value));
}

// Save Handler
document.getElementById('save-btn').addEventListener('click', async () => {
    const btn = document.getElementById('save-btn');
    const originalText = btn.innerText;
    
    // Featured Image and Excerpt
    const featuredImage = document.getElementById('featured-img')?.src || '';
    const excerpt = document.querySelector('textarea')?.value || '';
    const categories = Array.from(document.querySelectorAll('input[name="categories[]"]:checked')).map(cb => cb.value);

    btn.innerText = 'Saving...';
    btn.disabled = true;

    // 1. Collect Data
    // Note: If using BlockNote, use editor.document. If Tiptap, use window.zero_editor_content
    const contentData = window.zero_editor_content || []; 
    
    const payload = { 
        id: postId, // From global variable
        title: document.getElementById('post-title').value || 'Untitled', 
        slug: document.getElementById('post-slug').value, 
        status: document.getElementById('post-status').value, // Assuming existing ID
        type: document.getElementById('post-type').value,
        content: JSON.stringify(contentData),
        // Extra data fields
        data: {
            featured_image: featuredImageUrl || featuredImage, // Use global var or fallback to DOM
            excerpt: excerpt,
            categories: categories
        }
    };

    try {
        // 2. Send to Backend
        const response = await fetch(ZERO_BASE_URL + '/admin/save-post', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await response.json();
        
        if (result.success) {
            btn.innerText = 'Saved!';
            
            // If it was a new post, update the URL without refreshing
            if (!payload.id && result.new_id) {
                window.history.pushState({}, '', '?id=' + result.new_id);
                // Update global postId
                postId = result.new_id;
            }
            setTimeout(() => { btn.innerText = originalText; btn.disabled = false; }, 2000);
            
            // Feedback
            const feedback = document.getElementById('save-feedback');
            if(feedback) {
                feedback.textContent = 'Last saved: ' + new Date().toLocaleTimeString();
                feedback.className = 'text-xs text-center text-green-600 h-4';
            }
        } else {
            alert('Error saving: ' + (result.message || result.error));
            btn.innerText = 'Retry';
            btn.disabled = false;
        }
    } catch (e) {
        console.error(e);
        alert('Network Error');
        btn.innerText = originalText;
        btn.disabled = false;
    }
});

// Featured Image Upload Handler
const featUpload = document.getElementById('feat-upload');
const featPreview = document.getElementById('featured-preview');
const featPlaceholder = document.getElementById('featured-placeholder');
const featImg = document.getElementById('featured-img');
const removeBtn = document.getElementById('remove-featured');
const featDropZone = document.getElementById('featured-image-drop');
// featuredImageUrl is already defined in DATA INJECTION block from PHP\r\n// Only set to null if it wasn't pre-populated\r\nfeaturedImageUrl = featuredImageUrl || null;

featUpload.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    await uploadFeaturedImage(file);
});

// Drag and drop for featured image
featDropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    featDropZone.classList.add('border-indigo-500', 'bg-indigo-50');
});

featDropZone.addEventListener('dragleave', () => {
    featDropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
});

featDropZone.addEventListener('drop', async (e) => {
    e.preventDefault();
    featDropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        await uploadFeaturedImage(file);
    }
});

async function uploadFeaturedImage(file) {
    const formData = new FormData();
    formData.append('image', file);
    
    try {
        const res = await fetch(baseUrl + '/admin/api/upload', {
            method: 'POST',
            body: formData
        });
        const result = await res.json();
        
        if (result.success && result.file && result.file.url) {
            featuredImageUrl = result.file.url;
            featImg.src = featuredImageUrl;
            featPreview.classList.remove('hidden');
            featPlaceholder.classList.add('hidden');
            removeBtn.classList.remove('hidden');
        } else {
            alert('Upload failed: ' + (result.error || 'Unknown error'));
        }
    } catch (err) {
        console.error('Featured image upload error:', err);
        alert('Upload failed');
    }
}

removeBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    featuredImageUrl = null;
    featImg.src = '';
    featPreview.classList.add('hidden');
    featPlaceholder.classList.remove('hidden');
    removeBtn.classList.add('hidden');
});
</script>

</body>
</html>