<?php
/**
 * Admin Routes - API Endpoints
 * 
 * Handles all /admin/api/* and /api/* routes.
 * This is a large file that could be further split if needed.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;
use Core\Event;

/**
 * Handle API routes
 * 
 * @param array $request The request data
 * @param string $uri The request URI
 * @param string $themePath Path to admin theme
 * @return bool True if request was handled
 */
function zed_handle_api_routes(array $request, string $uri, string $themePath): bool
{
    // Only handle API routes and legacy endpoints
    if (!str_starts_with($uri, '/admin/api/') && !str_starts_with($uri, '/api/') && $uri !== '/admin/save-post') {
        return false;
    }
    
    // =========================================================================
    // /admin/api/save-settings - Save Settings (POST)
    // =========================================================================
    if ($uri === '/admin/api/save-settings' && $request['method'] === 'POST') {
        return zed_api_save_settings();
    }
    
    // =========================================================================
    // /admin/api/save - Save Content (POST)
    // =========================================================================
    if ($uri === '/admin/api/save' && $request['method'] === 'POST') {
        return zed_api_save_content();
    }
    
    // =========================================================================
    // /admin/api/upload - Upload Media (POST)
    // =========================================================================
    if ($uri === '/admin/api/upload' && $request['method'] === 'POST') {
        return zed_api_upload_media();
    }
    
    // =========================================================================
    // /admin/api/media/delete - Delete Media (GET/POST)
    // =========================================================================
    if ($uri === '/admin/api/media/delete') {
        return zed_api_delete_media();
    }
    
    // =========================================================================
    // /admin/api/categories - Get Categories (GET)
    // =========================================================================
    if ($uri === '/admin/api/categories' && $request['method'] === 'GET') {
        return zed_api_get_categories();
    }
    
    // =========================================================================
    // /admin/api/save-user - Save User (POST)
    // =========================================================================
    if ($uri === '/admin/api/save-user' && $request['method'] === 'POST') {
        return zed_api_save_user();
    }
    
    // =========================================================================
    // /admin/api/delete-user - Delete User (POST)
    // =========================================================================
    if ($uri === '/admin/api/delete-user' && $request['method'] === 'POST') {
        return zed_api_delete_user();
    }
    
    
    // =========================================================================
    // /admin/api/menu/save - Save Menu (POST)
    // =========================================================================
    if ($uri === '/admin/api/menu/save' && $request['method'] === 'POST') {
        return zed_api_save_menu();
    }
    
    // =========================================================================
    // /admin/api/save-menu - Save Menu Alias (POST)
    // =========================================================================
    if ($uri === '/admin/api/save-menu' && $request['method'] === 'POST') {
        return zed_api_save_menu();
    }
    
    // =========================================================================
    // /admin/api/menu/delete - Delete Menu (POST)
    // =========================================================================
    if ($uri === '/admin/api/menu/delete' && $request['method'] === 'POST') {
        return zed_api_delete_menu();
    }
    
    // =========================================================================
    // /admin/save-post - Save Post/Page (POST) - Legacy endpoint
    // =========================================================================
    if ($uri === '/admin/save-post' && $request['method'] === 'POST') {
        return zed_api_save_content();
    }
    
    // =========================================================================
    // /admin/api/toggle-addon - Toggle Addon (POST)
    // =========================================================================
    if ($uri === '/admin/api/toggle-addon' && $request['method'] === 'POST') {
        return zed_api_toggle_addon();
    }
    
    // =========================================================================
    // /admin/api/upload-addon - Upload Addon (POST)
    // =========================================================================
    if ($uri === '/admin/api/upload-addon' && $request['method'] === 'POST') {
        return zed_api_upload_addon();
    }
    
    
    // =========================================================================
    // /admin/api/batch-delete-media - Batch Delete Media (POST)
    // =========================================================================
    if ($uri === '/admin/api/batch-delete-media' && $request['method'] === 'POST') {
        return zed_api_batch_delete_media();
    }
    
    // =========================================================================
    // /admin/api/activate-theme - Activate Theme (POST)
    // =========================================================================
    if ($uri === '/admin/api/activate-theme' && $request['method'] === 'POST') {
        return zed_api_activate_theme();
    }
    
    // =========================================================================
    // /admin/api/cache/clear - Clear Cache (POST)
    // =========================================================================
    if ($uri === '/admin/api/cache/clear' && $request['method'] === 'POST') {
        return zed_api_clear_cache();
    }
    
    return false;
}

// =============================================================================
// API HANDLER FUNCTIONS
// =============================================================================

/**
 * API: Save Settings
 */
function zed_api_save_settings(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('manage_settings')) {
        zed_json_permission_denied();
        Router::setHandled('');
        return true;
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!zed_require_ajax_nonce($input)) {
            return true;
        }
        
        if (!$input || !is_array($input)) {
            throw new Exception('Invalid request data.');
        }
        
        $db = Database::getInstance();
        $pdo = $db->getPdo();
        
        $allowedKeys = [
            'site_title', 'site_tagline', 'homepage_mode', 'page_on_front',
            'blog_slug', 'posts_per_page', 'discourage_search_engines',
            'meta_description', 'social_sharing_image', 'maintenance_mode', 'debug_mode',
        ];
        
        $savedCount = 0;
        $activeTheme = zed_get_option('active_theme', 'aurora');
        
        foreach ($input as $key => $value) {
            // Handle theme settings
            if (str_starts_with($key, 'theme_')) {
                $settingId = substr($key, 6);
                $optionName = "theme_{$activeTheme}_{$settingId}";
                
                $value = is_string($value) ? trim($value) : $value;
                if (is_bool($value)) $value = $value ? '1' : '0';
                
                $stmt = $pdo->prepare(
                    "INSERT INTO zed_options (option_name, option_value, autoload) 
                     VALUES (:key, :value, 1)
                     ON DUPLICATE KEY UPDATE option_value = :value2"
                );
                $stmt->execute(['key' => $optionName, 'value' => $value, 'value2' => $value]);
                $savedCount++;
                continue;
            }
            
            if (!in_array($key, $allowedKeys)) continue;
            
            $value = is_string($value) ? trim($value) : $value;
            if (is_bool($value)) $value = $value ? '1' : '0';
            if (is_array($value)) $value = json_encode($value);
            
            $stmt = $pdo->prepare(
                "INSERT INTO zed_options (option_name, option_value, autoload) 
                 VALUES (:key, :value, 1)
                 ON DUPLICATE KEY UPDATE option_value = :value2"
            );
            $stmt->execute(['key' => $key, 'value' => $value, 'value2' => $value]);
            $savedCount++;
        }
        
        echo json_encode(['success' => true, 'message' => 'Settings saved successfully.', 'saved' => $savedCount]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Save Content
 */
function zed_api_save_content(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('edit_content')) {
        zed_json_permission_denied();
        Router::setHandled('');
        return true;
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !is_array($input)) {
            throw new Exception('Invalid request data.');
        }
        
        $db = Database::getInstance();
        
        $id = $input['id'] ?? null;
        $title = trim($input['title'] ?? 'Untitled');
        $slug = trim($input['slug'] ?? '');
        $type = $input['type'] ?? 'post';
        $data = $input['data'] ?? [];
        
        // Merge top-level fields into data object for backward compatibility
        // The frontend may send status, featured_image, content, etc. at top level
        $topLevelFields = ['status', 'featured_image', 'excerpt', 'category', 'content'];
        foreach ($topLevelFields as $field) {
            if (isset($input[$field]) && !isset($data[$field])) {
                $data[$field] = $input[$field];
            }
        }
        
        // Ensure status defaults to draft if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }
        
        // Generate slug from title if not provided
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        }
        
        // Ensure content is stored as array, not string
        // If content is a JSON string (from old frontend), decode it
        if (isset($data['content']) && is_string($data['content'])) {
            $decoded = json_decode($data['content'], true);
            if ($decoded !== null && is_array($decoded)) {
                $data['content'] = $decoded; // Store as array
            }
        }
        
        // Extract plain text for search
        $plainText = '';
        if (isset($data['content']) && is_array($data['content'])) {
            $plainText = zed_extract_plain_text_from_blocks($data['content']);
        }
        
        $dataJson = json_encode($data, JSON_UNESCAPED_UNICODE);
        $userId = Auth::id();
        
        if ($id) {
            // Update existing
            $db->query(
                "UPDATE zed_content SET title = :title, slug = :slug, type = :type, data = :data, plain_text = :plain_text, updated_at = NOW() WHERE id = :id",
                ['id' => $id, 'title' => $title, 'slug' => $slug, 'type' => $type, 'data' => $dataJson, 'plain_text' => $plainText]
            );
            Event::trigger('zed_post_saved', $id, $input);
            echo json_encode(['success' => true, 'id' => $id, 'action' => 'update', 'message' => 'Content updated']);
        } else {
            // Insert new
            $newId = $db->query(
                "INSERT INTO zed_content (title, slug, type, data, plain_text, author_id, created_at, updated_at) VALUES (:title, :slug, :type, :data, :plain_text, :author, NOW(), NOW())",
                ['title' => $title, 'slug' => $slug, 'type' => $type, 'data' => $dataJson, 'plain_text' => $plainText, 'author' => $userId]
            );
            Event::trigger('zed_post_saved', $newId, $input);
            echo json_encode(['success' => true, 'id' => $newId, 'new_id' => $newId, 'action' => 'create', 'message' => 'Content created']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Upload Media
 */
function zed_api_upload_media(): bool
{
    require_once dirname(__DIR__) . '/api/media_upload.php';
    zed_handle_media_upload();
    
    // The handler exits, but in case it doesn't:
    Router::setHandled('');
    return true;
}

/**
 * API: Delete Media
 */
function zed_api_delete_media(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        Router::setHandled('');
        return true;
    }
    
    $mediaId = $_GET['id'] ?? $_POST['id'] ?? null;
    $fileName = $_GET['file'] ?? $_POST['file'] ?? null;
    
    if (!$mediaId && !$fileName) {
        echo json_encode(['success' => false, 'error' => 'No media ID or filename specified']);
        Router::setHandled('');
        return true;
    }
    
    try {
        $db = Database::getInstance();
        $uploadBaseDir = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/uploads';
        
        // Get media record
        if ($mediaId) {
            $media = $db->queryOne("SELECT * FROM zed_media WHERE id = :id", ['id' => $mediaId]);
        } else {
            $media = $db->queryOne("SELECT * FROM zed_media WHERE filename = :filename", ['filename' => $fileName]);
        }
        
        if (!$media) {
            echo json_encode(['success' => false, 'error' => 'Media not found']);
            Router::setHandled('');
            return true;
        }
        
        $filePath = $media['file_path'] ?? '';
        
        // Delete main file
        if ($filePath) {
            $fullPath = $uploadBaseDir . '/' . $filePath;
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
            
            // Delete thumbnails (same folder)
            $dirPath = dirname($fullPath);
            $baseName = pathinfo($media['filename'], PATHINFO_FILENAME);
            $extension = pathinfo($media['filename'], PATHINFO_EXTENSION);
            
            // Delete sized versions
            $sizes = ['150x150', '300x300', '1024x1024'];
            foreach ($sizes as $size) {
                $thumbPath = $dirPath . '/' . $baseName . '-' . $size . '.' . $extension;
                if (file_exists($thumbPath)) {
                    @unlink($thumbPath);
                }
            }
        }
        
        // Delete database record
        $db->delete('zed_media', 'id = :id', ['id' => $media['id']]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Media deleted successfully',
            'deleted_id' => $media['id']
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Get Categories
 */
function zed_api_get_categories(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    try {
        $db = Database::getInstance();
        $categories = $db->query("SELECT * FROM zed_categories ORDER BY name ASC");
        echo json_encode($categories);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Save User
 */
function zed_api_save_user(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('manage_users')) {
        zed_json_permission_denied();
        Router::setHandled('');
        return true;
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!zed_require_ajax_nonce($input)) {
            return true;
        }
        
        $db = Database::getInstance();
        
        $id = $input['id'] ?? null;
        $email = trim($input['email'] ?? '');
        $role = $input['role'] ?? 'author';
        $password = $input['password'] ?? '';
        
        if (empty($email)) {
            throw new Exception('Email is required');
        }
        
        if ($id) {
            // Update existing user
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $db->query(
                    "UPDATE users SET email = :email, role = :role, password_hash = :password_hash WHERE id = :id",
                    ['id' => $id, 'email' => $email, 'role' => $role, 'password_hash' => $hash]
                );
            } else {
                $db->query(
                    "UPDATE users SET email = :email, role = :role WHERE id = :id",
                    ['id' => $id, 'email' => $email, 'role' => $role]
                );
            }
            echo json_encode(['success' => true, 'message' => 'User updated']);
        } else {
            // Create new user
            if (empty($password)) {
                throw new Exception('Password is required for new users');
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $db->query(
                "INSERT INTO users (email, password_hash, role, created_at) VALUES (:email, :password_hash, :role, NOW())",
                ['email' => $email, 'password_hash' => $hash, 'role' => $role]
            );
            echo json_encode(['success' => true, 'message' => 'User created']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Delete User
 */
function zed_api_delete_user(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('delete_users')) {
        zed_json_permission_denied();
        Router::setHandled('');
        return true;
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!zed_require_ajax_nonce($input)) {
            return true;
        }
        
        $id = $input['id'] ?? null;
        
        if (!$id || $id == 1) {
            throw new Exception('Cannot delete admin user');
        }
        
        $db = Database::getInstance();
        $db->query("DELETE FROM users WHERE id = :id AND id != 1", ['id' => $id]);
        
        echo json_encode(['success' => true, 'message' => 'User deleted']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Save Menu
 */
function zed_api_save_menu(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('manage_menus')) {
        zed_json_permission_denied();
        Router::setHandled('');
        return true;
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $id = $input['id'] ?? null;
        $name = trim($input['name'] ?? '');
        $items = $input['items'] ?? [];
        
        if (!$id || !is_numeric($id)) {
            throw new Exception('Invalid menu ID');
        }
        
        $db = Database::getInstance();
        
        $params = ['id' => $id, 'items' => json_encode($items)];
        if (!empty($name)) {
            $db->query(
                "UPDATE zed_menus SET name = :name, items = :items, updated_at = NOW() WHERE id = :id",
                array_merge($params, ['name' => $name])
            );
        } else {
            $db->query("UPDATE zed_menus SET items = :items, updated_at = NOW() WHERE id = :id", $params);
        }
        
        echo json_encode(['success' => true, 'message' => 'Menu saved']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Delete Menu
 */
function zed_api_delete_menu(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('manage_menus')) {
        zed_json_permission_denied();
        Router::setHandled('');
        return true;
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $id = $input['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            throw new Exception('Invalid menu ID');
        }
        
        $db = Database::getInstance();
        $db->query("DELETE FROM zed_menus WHERE id = :id", ['id' => $id]);
        
        echo json_encode(['success' => true, 'message' => 'Menu deleted']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Toggle Addon
 */
function zed_api_toggle_addon(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('manage_addons')) {
        zed_json_permission_denied();
        Router::setHandled('');
        return true;
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!zed_require_ajax_nonce($input)) {
            Router::setHandled('');
            return true;
        }
        
        $filename = $input['filename'] ?? null;
        
        if (!$filename) {
            throw new Exception('Addon filename required');
        }
        
        $db = Database::getInstance();
        $current = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_addons'");
        $activeAddons = $current ? json_decode($current, true) : [];
        if (!is_array($activeAddons)) $activeAddons = [];
        
        // Toggle: if active, deactivate; if inactive, activate
        $isActive = in_array($filename, $activeAddons, true);
        
        if ($isActive) {
            // Deactivate
            $activeAddons = array_values(array_filter($activeAddons, fn($a) => $a !== $filename));
            $message = 'Addon deactivated successfully';
            $newState = false;
        } else {
            // Activate
            if (!in_array($filename, $activeAddons, true)) {
                $activeAddons[] = $filename;
            }
            $message = 'Addon activated successfully';
            $newState = true;
        }
        
        // Save to database
        $db->query(
            "INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('active_addons', :value, 1) ON DUPLICATE KEY UPDATE option_value = :value2",
            ['value' => json_encode($activeAddons), 'value2' => json_encode($activeAddons)]
        );
        
        // Invalidate addon cache (forces rebuild on next request)
        $addonCacheFile = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/content/addons/.addon_cache.php';
        if (file_exists($addonCacheFile)) {
            @unlink($addonCacheFile);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => $message,
            'active' => $newState
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Upload Addon
 */
function zed_api_upload_addon(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('manage_addons')) {
        zed_json_permission_denied();
        Router::setHandled('');
        return true;
    }
    
    try {
        if (!isset($_FILES['addon']) || $_FILES['addon']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('No addon file uploaded');
        }
        
        $file = $_FILES['addon'];
        $filename = basename($file['name']);
        
        if (!str_ends_with(strtolower($filename), '.php')) {
            throw new Exception('Only .php files are allowed');
        }
        
        $safeFilename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
        $addonsDir = dirname(dirname(dirname(__DIR__)));
        $destPath = $addonsDir . '/' . $safeFilename;
        
        if (file_exists($destPath)) {
            throw new Exception('An addon with this name already exists');
        }
        
        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new Exception('Failed to save addon file');
        }
        
        // Auto-activate
        $db = Database::getInstance();
        $current = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_addons'");
        $activeAddons = $current ? json_decode($current, true) : [];
        if (!is_array($activeAddons)) $activeAddons = [];
        
        $activeAddons[] = $safeFilename;
        $db->query(
            "INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('active_addons', :value, 1) ON DUPLICATE KEY UPDATE option_value = :value2",
            ['value' => json_encode($activeAddons), 'value2' => json_encode($activeAddons)]
        );
        
        echo json_encode(['success' => true, 'message' => 'Addon uploaded and activated', 'addon' => $safeFilename]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Batch Delete Media
 */
function zed_api_batch_delete_media(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('delete_files')) {
        zed_json_permission_denied();
        Router::setHandled('');
        return true;
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $files = $input['files'] ?? [];
        if (empty($files) || !is_array($files)) {
            throw new Exception('No files specified');
        }
        
        $uploadDir = dirname(dirname(dirname(dirname(__DIR__)))) . '/uploads';
        $deletedCount = 0;
        
        foreach ($files as $file) {
            $safeFile = basename($file);
            $fullPath = $uploadDir . '/' . $safeFile;
            
            if (file_exists($fullPath) && unlink($fullPath)) {
                $deletedCount++;
                
                // Delete thumbnails
                $baseName = pathinfo($safeFile, PATHINFO_FILENAME);
                foreach (glob($uploadDir . '/' . $baseName . '-*') as $thumbFile) {
                    unlink($thumbFile);
                }
            }
        }
        
        echo json_encode(['success' => true, 'count' => $deletedCount, 'message' => "$deletedCount files deleted"]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * Helper: Extract plain text from BlockNote blocks
 */
function zed_extract_plain_text_from_blocks(array $blocks): string
{
    $text = '';
    foreach ($blocks as $block) {
        if (isset($block['content'])) {
            if (is_string($block['content'])) {
                $text .= strip_tags($block['content']) . ' ';
            } elseif (is_array($block['content'])) {
                foreach ($block['content'] as $inline) {
                    if (isset($inline['text'])) {
                        $text .= $inline['text'] . ' ';
                    }
                }
            }
        }
        if (isset($block['children']) && is_array($block['children'])) {
            $text .= zed_extract_plain_text_from_blocks($block['children']);
        }
    }
    return trim($text);
}

/**
 * API: Activate Theme
 */
function zed_api_activate_theme(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('manage_themes')) {
        zed_json_permission_denied();
        Router::setHandled('');
        return true;
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $theme = $input['theme'] ?? null;
        
        if (!$theme) {
            throw new Exception('Theme name required');
        }
        
        // Validate theme exists
        $themesDir = dirname(dirname(dirname(dirname(__DIR__)))) . '/themes';
        $themePath = $themesDir . '/' . $theme;
        
        if (!is_dir($themePath) || $theme === 'admin-default') {
            throw new Exception('Invalid theme');
        }
        
        // Save to database
        $db = Database::getInstance();
        $db->query(
            "INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('active_theme', :theme, 1) ON DUPLICATE KEY UPDATE option_value = :theme2",
            ['theme' => $theme, 'theme2' => $theme]
        );
        
        echo json_encode([
            'success' => true,
            'message' => 'Theme activated successfully'
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}
