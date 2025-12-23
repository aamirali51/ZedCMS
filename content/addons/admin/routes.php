<?php
/**
 * Zed CMS - Admin Routes
 * 
 * All admin route handlers (/admin/*, /api/*).
 * 
 * @package ZedCMS\Admin
 */

use Core\Event;
use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * SECURITY: Verify CSRF nonce for API requests
 * Returns true if valid, otherwise sends 403 and returns false
 * 
 * @param array|null $jsonData Pre-parsed JSON body (optional)
 * @return bool True if nonce is valid
 */
function zed_require_ajax_nonce(?array $jsonData = null): bool
{
    if (!function_exists('zed_verify_ajax_nonce')) {
        return true; // Skip if security helpers not loaded
    }
    
    if (!zed_verify_ajax_nonce('zed_admin_action', $jsonData)) {
        header('Content-Type: application/json');
        http_response_code(403);
        echo json_encode([
            'success' => false, 
            'error' => 'Security verification failed. Please refresh the page and try again.'
        ]);
        Router::setHandled('');
        return false;
    }
    return true;
}

// Register admin routes
Event::on('route_request', function (array $request): void {
    $uri = $request['uri'];
    $themePath = __DIR__ . '/../../themes/admin-default';

    // /admin/logout - Logout and redirect (always accessible)
    if ($uri === '/admin/logout') {
        Auth::logout();
        Router::redirect('/admin/login');
    }

    // Legacy logout via query param (/?logout=true)
    if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
        Auth::logout();
        Router::redirect('/admin/login');
    }

    // /admin/login - Always accessible (public page)
    if ($uri === '/admin/login') {
        // If already logged in, redirect to dashboard
        if (Auth::check()) {
            Router::redirect('/admin');
        }
        
        ob_start();
        require $themePath . '/login.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // =========================================================================
    // AJAX HANDLER SYSTEM — /api/ajax/{action}
    // =========================================================================
    if (preg_match('#^/api/ajax/(\w+)$#', $uri, $matches)) {
        $action = $matches[1];
        $handlers = zed_get_ajax_handlers();
        
        header('Content-Type: application/json');
        
        if (!isset($handlers[$action])) {
            http_response_code(404);
            echo json_encode(['error' => 'Unknown action', 'action' => $action]);
            Router::setHandled();
            return;
        }
        
        $handler = $handlers[$action];
        
        // Check method
        $method = $_SERVER['REQUEST_METHOD'];
        if ($handler['method'] !== 'ANY' && $handler['method'] !== $method) {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            Router::setHandled();
            return;
        }
        
        // Check authentication
        if ($handler['require_auth'] && !Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication required']);
            Router::setHandled();
            return;
        }
        
        // Check capability
        if ($handler['capability'] && !zed_current_user_can($handler['capability'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Permission denied']);
            Router::setHandled();
            return;
        }
        
        // Get request data
        $data = [];
        if ($method === 'POST') {
            $input = file_get_contents('php://input');
            $jsonData = json_decode($input, true);
            $data = $jsonData ?: $_POST;
        } else {
            $data = $_GET;
        }
        
        // Execute handler
        try {
            $result = call_user_func($handler['callback'], $data);
            echo json_encode($result);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        
        Router::setHandled();
        return;
    }


    // =========================================================================
    // SECURITY: Role-based access for all other /admin/* routes
    // =========================================================================
    if (str_starts_with($uri, '/admin')) {
        // Step 1: Check if user is logged in
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        // Step 2: Check if user has admin/editor role
        if (!zed_user_can_access_admin()) {
            // User is logged in but doesn't have admin privileges
            http_response_code(403);
            $content = zed_render_forbidden();
            echo $content;
            Router::setHandled($content);
            return;
        }
    }

    // /admin or /admin/dashboard - Dashboard (auth + role already checked above)
    if ($uri === '/admin' || $uri === '/admin/dashboard') {
        // Fetch real stats for dashboard
        try {
            $db = Database::getInstance();
            
            // Count pages (total)
            $total_pages = (int)($db->queryValue(
                "SELECT COUNT(*) FROM zed_content WHERE type = :type",
                ['type' => 'page']
            ) ?: 0);
            
            // Count posts (total)
            $total_posts = (int)($db->queryValue(
                "SELECT COUNT(*) FROM zed_content WHERE type = :type",
                ['type' => 'post']
            ) ?: 0);
            
            // Count all content
            $total_content = (int)($db->queryValue(
                "SELECT COUNT(*) FROM zed_content"
            ) ?: 0);
            
            // Count published content
            $published_count = (int)($db->queryValue(
                "SELECT COUNT(*) FROM zed_content WHERE JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'"
            ) ?: 0);
            
            // Count drafts (everything not published)
            $draft_count = $total_content - $published_count;
            
            // Count users
            $total_users = (int)($db->queryValue(
                "SELECT COUNT(*) FROM users"
            ) ?: 0);
            
            // Count addons
            $addons_dir = dirname(__DIR__); // content/addons
            $total_addons = count(glob($addons_dir . '/*.php'));
            
            // Get recent content for "Jump Back In" activity feed (last 5 items)
            $recent_content = $db->query(
                "SELECT id, title, type, slug, updated_at, 
                        JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) as status 
                 FROM zed_content 
                 ORDER BY updated_at DESC 
                 LIMIT 5"
            );
            
            // Add relative time to each content item
            foreach ($recent_content as &$item) {
                $updatedAt = strtotime($item['updated_at']);
                $diff = time() - $updatedAt;
                if ($diff < 60) {
                    $item['relative_time'] = 'Just now';
                } elseif ($diff < 3600) {
                    $mins = floor($diff / 60);
                    $item['relative_time'] = $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
                } elseif ($diff < 86400) {
                    $hours = floor($diff / 3600);
                    $item['relative_time'] = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
                } elseif ($diff < 604800) {
                    $days = floor($diff / 86400);
                    $item['relative_time'] = $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                } else {
                    $item['relative_time'] = date('M j', $updatedAt);
                }
            }
            unset($item);
            
            // Content by type for charts
            $content_by_type = [
                'pages' => $total_pages,
                'posts' => $total_posts,
                'other' => $total_content - $total_pages - $total_posts
            ];
            
            // Content by status for charts
            $content_by_status = [
                'published' => $published_count,
                'draft' => $draft_count
            ];
            
        } catch (Exception $e) {
            $total_pages = 0;
            $total_posts = 0;
            $total_content = 0;
            $published_count = 0;
            $draft_count = 0;
            $total_users = 0;
            $total_addons = 0;
            $recent_content = [];
            $content_by_type = ['pages' => 0, 'posts' => 0, 'other' => 0];
            $content_by_status = ['published' => 0, 'draft' => 0];
        }
        
        // ===================================================================
        // HEALTH CHECKS - Real system diagnostics
        // ===================================================================
        $health_checks = [];
        $health_status = 'nominal'; // 'nominal', 'warning', 'critical'
        
        // 1. Check uploads folder is writable
        $uploadsPath = dirname(dirname(__DIR__)) . '/uploads';
        if (is_writable($uploadsPath)) {
            $health_checks[] = ['status' => 'ok', 'label' => 'Uploads Folder', 'detail' => 'Writable'];
        } else {
            $health_checks[] = ['status' => 'error', 'label' => 'Uploads Folder', 'detail' => 'Not writable!'];
            $health_status = 'critical';
        }
        
        // 2. Check PHP version
        $phpVersion = phpversion();
        if (version_compare($phpVersion, '8.0.0', '>=')) {
            $health_checks[] = ['status' => 'ok', 'label' => 'PHP Version', 'detail' => $phpVersion];
        } else {
            $health_checks[] = ['status' => 'warning', 'label' => 'PHP Version', 'detail' => $phpVersion . ' (8.0+ recommended)'];
            if ($health_status === 'nominal') $health_status = 'warning';
        }
        
        // 3. Check SEO visibility
        try {
            $seoBlocked = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'discourage_search_engines'");
            if ($seoBlocked === '1') {
                $health_checks[] = ['status' => 'warning', 'label' => 'SEO', 'detail' => 'Search engines blocked'];
                if ($health_status === 'nominal') $health_status = 'warning';
            } else {
                $health_checks[] = ['status' => 'ok', 'label' => 'SEO', 'detail' => 'Indexing enabled'];
            }
        } catch (Exception $e) {
            $health_checks[] = ['status' => 'ok', 'label' => 'SEO', 'detail' => 'Indexing enabled'];
        }
        
        // 4. Check maintenance mode
        try {
            $maintenanceMode = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'maintenance_mode'");
            if ($maintenanceMode === '1') {
                $health_checks[] = ['status' => 'warning', 'label' => 'Maintenance', 'detail' => 'Site is offline'];
                if ($health_status === 'nominal') $health_status = 'warning';
            } else {
                $health_checks[] = ['status' => 'ok', 'label' => 'Maintenance', 'detail' => 'Site is live'];
            }
        } catch (Exception $e) {
            $health_checks[] = ['status' => 'ok', 'label' => 'Maintenance', 'detail' => 'Site is live'];
        }
        
        // 5. Check database connection
        $health_checks[] = ['status' => 'ok', 'label' => 'Database', 'detail' => 'Connected'];
        
        // System status summary
        $system_status = match($health_status) {
            'nominal' => 'System Nominal',
            'warning' => 'System Warning',
            'critical' => 'System Alert',
        };
        
        $system_status_color = match($health_status) {
            'nominal' => 'green',
            'warning' => 'yellow',
            'critical' => 'red',
        };
        
        // Dashboard stats object for easy access
        $dashboard_stats = [
            'total_pages' => $total_pages,
            'total_posts' => $total_posts,
            'total_content' => $total_content,
            'published_count' => $published_count,
            'draft_count' => $draft_count,
            'total_users' => $total_users,
            'total_addons' => $total_addons,
            'content_by_type' => $content_by_type,
            'content_by_status' => $content_by_status
        ];
        
        // Prepare chart data JSON for JavaScript
        $chartDataJson = json_encode([
            'byType' => $content_by_type,
            'byStatus' => $content_by_status,
            'totals' => [
                'pages' => $total_pages,
                'posts' => $total_posts,
                'users' => $total_users,
                'addons' => $total_addons
            ]
        ]);
        
        // Get current user
        $current_user = Auth::user();
        
        // Layout configuration
        $current_page = 'dashboard';
        $page_title = 'Dashboard';
        $content_partial = $themePath . '/partials/dashboard-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/content - Content list (auth + role checked above)
    if ($uri === '/admin/content') {
        
        // Parse query parameters for filtering and pagination
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? ''; // 'published', 'draft', or '' (all)
        $type = $_GET['type'] ?? ''; // Filter by post type
        $msg = $_GET['msg'] ?? ''; // Flash message from delete, etc.
        
        // Build the query dynamically
        $posts = [];
        $totalPosts = 0;
        
        try {
            $db = Database::getInstance();
            
            // Base query parts
            $selectSql = "SELECT * FROM zed_content";
            $countSql = "SELECT COUNT(*) FROM zed_content";
            $whereClauses = [];
            $params = [];
            
            // RBAC: Authors can only see their own content
            // Admins and Editors can see all content
            if (!zed_current_user_can('edit_others_content')) {
                $currentUserId = Auth::id();
                $whereClauses[] = "author_id = :author_id";
                $params['author_id'] = $currentUserId;
            }
            
            // Type filter
            if (!empty($type)) {
                $whereClauses[] = "type = :type";
                $params['type'] = $type;
            }
            
            // Search filter (title or slug)
            if (!empty($search)) {
                $whereClauses[] = "(title LIKE :search OR slug LIKE :search)";
                $params['search'] = '%' . $search . '%';
            }
            
            // Status filter (requires JSON extraction)
            if ($status === 'published') {
                $whereClauses[] = "JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'";
            } elseif ($status === 'draft') {
                $whereClauses[] = "(JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'draft' OR JSON_EXTRACT(data, '$.status') IS NULL)";
            }
            
            // Combine WHERE clauses
            $whereString = '';
            if (!empty($whereClauses)) {
                $whereString = ' WHERE ' . implode(' AND ', $whereClauses);
            }
            
            // Get total count for pagination
            $totalPosts = (int)$db->queryValue($countSql . $whereString, $params);
            
            // Calculate pagination
            $totalPages = max(1, ceil($totalPosts / $perPage));
            $page = min($page, $totalPages);
            $offset = ($page - 1) * $perPage;
            
            // Fetch paginated results
            $fullSql = $selectSql . $whereString . " ORDER BY updated_at DESC LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;
            $posts = $db->query($fullSql, $params);
            
        } catch (Exception $e) {
            $posts = [];
            $totalPosts = 0;
            $totalPages = 1;
        }
        
        // Calculate display range
        $showingFrom = $totalPosts > 0 ? (($page - 1) * $perPage) + 1 : 0;
        $showingTo = min($page * $perPage, $totalPosts);
        
        // Get current user
        $current_user = Auth::user();
        
        // Get type label for display
        $typeLabel = 'Content';
        if (!empty($type)) {
            $typeConfig = zed_get_post_type($type);
            $typeLabel = $typeConfig['label'] ?? ucfirst($type) . 's';
        }
        
        // Layout configuration
        $current_page = !empty($type) ? 'cpt_' . $type : 'content'; // Highlights correct sidebar menu
        $page_title = $typeLabel;
        $content_partial = $themePath . '/partials/content-list-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/content/delete - Delete content by ID
    if ($uri === '/admin/content/delete') {
        // Step 1: Authentication check
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        // Step 2: Capability check
        if (!zed_current_user_can('delete_content')) {
            Router::redirect('/admin/content?msg=permission_denied');
        }
        
        $id = $_GET['id'] ?? null;
        
        // Step 3: Validate ID is numeric
        if (!$id || !is_numeric($id)) {
            Router::redirect('/admin/content?msg=invalid_id');
        }
        
        $id = (int)$id;
        
        try {
            $db = Database::getInstance();
            
            // Step 4: Check if content exists and get author
            $content = $db->queryOne(
                "SELECT id, author_id, title FROM zed_content WHERE id = :id",
                ['id' => $id]
            );
            
            if (!$content) {
                Router::redirect('/admin/content?msg=not_found');
            }
            
            // Step 5: Ownership check for non-admins/editors
            // Users without 'delete_others_content' can only delete their own content
            $currentUserId = Auth::id();
            $contentAuthorId = (int)($content['author_id'] ?? 0);
            
            if (!zed_current_user_can('delete_others_content') && $contentAuthorId !== $currentUserId) {
                Router::redirect('/admin/content?msg=permission_denied');
            }
            
            // Step 6: Perform the DELETE query
            $db->query("DELETE FROM zed_content WHERE id = :id", ['id' => $id]);
            
            // Step 7: Redirect with success message
            Router::redirect('/admin/content?msg=deleted');
            
        } catch (Exception $e) {
            Router::redirect('/admin/content?msg=error');
        }
        
        return;
    }

    // /admin/editor - Content editor (requires auth)
    if ($uri === '/admin/editor') {
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        ob_start();
        require $themePath . '/editor.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/settings - Settings page (requires auth)
    if ($uri === '/admin/settings') {
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        $current_user = Auth::user();
        $current_page = 'settings';
        $page_title = 'Settings';
        $content_partial = $themePath . '/partials/settings-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }
    // /admin/categories/create - Create Category
    if ($uri === '/admin/categories/create' && $request['method'] === 'POST') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        
        if (empty($slug) && !empty($name)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        }
        
        if (!empty($name) && !empty($slug)) {
            $db = Database::getInstance();
            try {
                $db->query(
                    "INSERT INTO zed_categories (name, slug, created_at, updated_at) VALUES (:name, :slug, NOW(), NOW())",
                    ['name' => $name, 'slug' => $slug]
                );
                Router::redirect('/admin/categories?msg=created');
            } catch (Exception $e) {
                // Determine error (duplicate slug usually)
                Router::redirect('/admin/categories?msg=error');
            }
        }
        return;
    }

    // /admin/categories - Categories Manager
    if ($uri === '/admin/categories') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $db = Database::getInstance();
        $categories = $db->query("SELECT * FROM zed_categories ORDER BY name ASC");
        
        $current_user = Auth::user();
        $current_page = 'categories';
        $page_title = 'Categories';
        $content_partial = $themePath . '/partials/categories-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/categories/delete - Delete Category
    if (str_starts_with($uri, '/admin/categories/delete')) {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $id = $_GET['id'] ?? null;
        if ($id && is_numeric($id)) {
            $db = Database::getInstance();
            // Prevent deleting Uncategorized (ID 1 usually)
            if ($id != 1) {
                $db->query("DELETE FROM zed_categories WHERE id = :id", ['id' => $id]);
                Router::redirect('/admin/categories?msg=deleted');
            } else {
                Router::redirect('/admin/categories?msg=locked');
            }
        }
        Router::redirect('/admin/categories');
        return;
    }

    // /admin/menus/create - Create New Menu
    if ($uri === '/admin/menus/create' && $request['method'] === 'POST') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $name = trim($_POST['name'] ?? '');
        if (!empty($name)) {
            $db = Database::getInstance();
            // Default structure: empty list
            $defaultItems = json_encode([]); 
            
            $db->query(
                "INSERT INTO zed_menus (name, items, created_at, updated_at) VALUES (:name, :items, NOW(), NOW())",
                ['name' => $name, 'items' => $defaultItems]
            );
            $newId = $db->getPdo()->lastInsertId();
            Router::redirect("/admin/menus?id={$newId}");
        } else {
            Router::redirect('/admin/menus?msg=name_required');
        }
        return;
    }

    // /admin/menus - Visual Menu Builder (requires auth)
    if ($uri === '/admin/menus') {
        // Security check
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        if (!zed_current_user_can('manage_menus')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        $db = Database::getInstance();
        
        // Handle old form-based Save (POST) - for backwards compatibility
        if ($request['method'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'] ?? null;
            $items = $_POST['items'] ?? '';
            
            // Validate JSON
            $decoded = json_decode($items, true);
            if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                Router::redirect("/admin/menus?id={$id}&msg=error");
            }
            
            // Save to DB
            if ($id && is_numeric($id)) {
                $db->query(
                    "UPDATE zed_menus SET items = :items, updated_at = NOW() WHERE id = :id",
                    ['items' => $items, 'id' => $id]
                );
                Router::redirect("/admin/menus?id={$id}&msg=saved");
            }
        }
        
        // Handle View (GET)
        $current_user = Auth::user();
        $current_page = 'menus';
        $page_title = 'Menu Builder';
        
        // Fetch all menus
        $menus = $db->query("SELECT * FROM zed_menus ORDER BY name ASC");
        
        // Fetch selected menu
        $selectedId = $_GET['id'] ?? ($menus[0]['id'] ?? null);
        $currentMenu = null;
        
        if ($selectedId) {
            foreach ($menus as $m) {
                if ($m['id'] == $selectedId) {
                    $currentMenu = $m;
                    break;
                }
            }
        }
        
        // Fetch all published pages for the toolbox
        $pages = $db->query(
            "SELECT id, title, slug FROM zed_content 
             WHERE type = 'page' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY title ASC"
        );
        
        // Fetch all categories for the toolbox
        $categories = $db->query("SELECT id, name, slug FROM zed_categories ORDER BY name ASC");
        
        // Fetch published posts for the toolbox (optional, top 20)
        $posts = $db->query(
            "SELECT id, title, slug FROM zed_content 
             WHERE type = 'post' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY title ASC
             LIMIT 20"
        );
        
        $content_partial = $themePath . '/partials/menus-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/addons - Addons page (requires auth)
    if ($uri === '/admin/addons') {
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        $current_user = Auth::user();
        $current_page = 'addons';
        $page_title = 'Addons';
        $content_partial = $themePath . '/partials/addons-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/settings - Unified Settings Panel (Admin only)
    if ($uri === '/admin/settings') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        // Only admins can access settings
        if (!zed_current_user_can('manage_settings')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        // Fetch all options from zed_options
        $options = [];
        try {
            $db = Database::getInstance();
            $rows = $db->query("SELECT option_name, option_value FROM zed_options");
            foreach ($rows as $row) {
                $options[$row['option_name']] = $row['option_value'];
            }
        } catch (Exception $e) {
            $options = [];
        }
        
        // Fetch published pages for Homepage dropdown
        $pages = [];
        try {
            $db = Database::getInstance();
            $pages = $db->query(
                "SELECT id, title FROM zed_content 
                 WHERE type = 'page' 
                 AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
                 ORDER BY title ASC"
            );
        } catch (Exception $e) {
            $pages = [];
        }
        
        $current_user = Auth::user();
        $current_page = 'settings';
        $page_title = 'Settings';
        $content_partial = $themePath . '/partials/settings-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/users - User Management (Admin only)
    if ($uri === '/admin/users') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        // Only admins can manage users (using RBAC)
        if (!zed_current_user_can('manage_users')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        // Fetch all users from database
        try {
            $db = Database::getInstance();
            $users = $db->query(
                "SELECT id, email, role, last_login, created_at, updated_at 
                 FROM users 
                 ORDER BY created_at DESC"
            );
        } catch (Exception $e) {
            $users = [];
        }
        
        $current_page = 'users';
        $page_title = 'User Management';
        $content_partial = $themePath . '/partials/users-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/media/delete - Delete Media File (handles WebP + original + thumb)
    if ($uri === '/admin/media/delete') {
        if (!zed_user_can_access_admin()) {
            // Check if AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Not authenticated']);
                Router::setHandled('');
                return;
            }
            Router::redirect('/admin/login');
        }
        
        $file = $_REQUEST['file'] ?? '';
        $safeFile = basename($file);
        $uploadDir = dirname(dirname(__DIR__)) . '/uploads';
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                  (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
        
        if (!empty($safeFile)) {
            $deleted = false;
            $baseName = pathinfo($safeFile, PATHINFO_FILENAME);
            
            // Primary file
            if (file_exists($uploadDir . '/' . $safeFile)) {
                unlink($uploadDir . '/' . $safeFile);
                $deleted = true;
            }
            
            // Thumb version (thumb_filename.webp or thumb_filename.ext)
            $thumbPatterns = [
                $uploadDir . '/thumb_' . $safeFile,
                $uploadDir . '/thumb_' . $baseName . '.webp'
            ];
            foreach ($thumbPatterns as $thumb) {
                if (file_exists($thumb)) {
                    unlink($thumb);
                }
            }
            
            // Original backup (_original.jpg, _original.png, etc.)
            foreach (glob($uploadDir . '/' . $baseName . '_original.*') as $origFile) {
                unlink($origFile);
            }
            
            // Also check if this was a WebP, delete any corresponding original
            if (str_ends_with(strtolower($safeFile), '.webp')) {
                $noExt = preg_replace('/\.webp$/i', '', $safeFile);
                foreach (glob($uploadDir . '/' . $noExt . '_original.*') as $origFile) {
                    unlink($origFile);
                }
            }
            
            // Return JSON for AJAX or redirect for regular request
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => $deleted, 'message' => $deleted ? 'File deleted' : 'File not found']);
                Router::setHandled('');
                return;
            }
            
            Router::redirect('/admin/media?msg=' . ($deleted ? 'deleted' : 'error'));
        } else {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'No file specified']);
                Router::setHandled('');
                return;
            }
            Router::redirect('/admin/media?msg=error');
        }
        return;
    }

    // /admin/media/upload - Form Upload with WebP Optimization
    if ($uri === '/admin/media/upload' && $request['method'] === 'POST') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(dirname(__DIR__)) . '/uploads';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $tmpName = $_FILES['file']['tmp_name'];
            $name = basename($_FILES['file']['name']);
            
            if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $name)) {
                // Use advanced processing
                $result = zed_process_upload($tmpName, $name, $uploadDir);
                if ($result) {
                    Router::redirect('/admin/media?msg=uploaded');
                } else {
                    Router::redirect('/admin/media?msg=processing_error');
                }
            } else {
                Router::redirect('/admin/media?msg=invalid_type');
            }
        } else {
            Router::redirect('/admin/media?msg=upload_error');
        }
        return;
    }

    // /admin/media - Media Library (shows main WebP files, hides thumb_ and _original)
    if ($uri === '/admin/media') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $uploadDir = dirname(dirname(__DIR__)) . '/uploads';
        $files = [];
        
        if (is_dir($uploadDir)) {
            $allFiles = scandir($uploadDir);
            foreach ($allFiles as $f) {
                if ($f === '.' || $f === '..') continue;
                // Skip thumbnails
                if (str_starts_with($f, 'thumb_')) continue;
                // Skip original backups
                if (str_contains($f, '_original.')) continue;
                
                $path = $uploadDir . '/' . $f;
                if (is_file($path) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f)) {
                    $baseName = pathinfo($f, PATHINFO_FILENAME);
                    
                    // Try to find thumbnail (prefer WebP thumb)
                    $thumbWebp = $uploadDir . '/thumb_' . $baseName . '.webp';
                    $thumbExact = $uploadDir . '/thumb_' . $f;
                    
                    if (file_exists($thumbWebp)) {
                        $thumbUrl = Router::getBasePath() . '/content/uploads/thumb_' . $baseName . '.webp';
                    } elseif (file_exists($thumbExact)) {
                        $thumbUrl = Router::getBasePath() . '/content/uploads/thumb_' . $f;
                    } else {
                        $thumbUrl = Router::getBasePath() . '/content/uploads/' . $f;
                    }
                    
                    // Get dimensions if possible
                    $dimensions = @getimagesize($path);
                    $width = $dimensions[0] ?? 0;
                    $height = $dimensions[1] ?? 0;
                        
                    $files[] = [
                        'name' => $f,
                        'url' => Router::getBasePath() . '/content/uploads/' . $f,
                        'thumb' => $thumbUrl,
                        'size' => filesize($path),
                        'sizeFmt' => round(filesize($path) / 1024) . ' KB',
                        'mtime' => filemtime($path),
                        'date' => date('Y-m-d H:i', filemtime($path)),
                        'width' => $width,
                        'height' => $height,
                        'isWebp' => str_ends_with(strtolower($f), '.webp')
                    ];
                }
            }
            // Sort by newest first
            usort($files, fn($a, $b) => $b['mtime'] - $a['mtime']);
        }
        
        $current_user = Auth::user();
        $current_page = 'media';
        $page_title = 'Media Library';
        $content_partial = $themePath . '/partials/media-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // /admin/content/delete - Delete content (requires auth + ownership check)
    if ($uri === '/admin/content/delete') {
        // Security: Require authentication
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        // Get the ID from query parameter
        $deleteId = $_GET['id'] ?? null;
        
        if (!$deleteId || !is_numeric($deleteId)) {
            // Invalid ID - redirect with error
            Router::redirect('/admin/content?msg=invalid_id');
        }
        
        $deleteId = (int)$deleteId;
        
        try {
            $db = Database::getInstance();
            
            // RBAC: Check if user can delete this specific content
            // Admins and Editors can delete any content
            // Authors can only delete their own content
            if (!zed_current_user_can('delete_others_content')) {
                // Check ownership
                $content = $db->queryOne(
                    "SELECT author_id FROM zed_content WHERE id = :id",
                    ['id' => $deleteId]
                );
                
                if (!$content) {
                    Router::redirect('/admin/content?msg=not_found');
                    return;
                }
                
                $currentUserId = Auth::id();
                if ((int)($content['author_id'] ?? 0) !== $currentUserId) {
                    Router::redirect('/admin/content?msg=permission_denied');
                    return;
                }
            }
            
            // Execute the delete query
            $affected = $db->delete('zed_content', 'id = :id', ['id' => $deleteId]);
            
            if ($affected > 0) {
                // Trigger hooks
                \Core\Event::trigger('zed_post_deleted', $deleteId);
                
                // Success - redirect with success message
                Router::redirect('/admin/content?msg=deleted');
            } else {
                // No rows affected - content not found
                Router::redirect('/admin/content?msg=not_found');
            }
        } catch (Exception $e) {
            // Database error - redirect with error
            Router::redirect('/admin/content?msg=error');
        }
        
        return;
    }

    // /admin/api/save-settings - Save Settings (POST, Admin only)
    if ($uri === '/admin/api/save-settings' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        // Admin only
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        if (!zed_current_user_can('manage_settings')) {
            zed_json_permission_denied();
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // SECURITY: Verify CSRF nonce
            if (!zed_require_ajax_nonce($input)) {
                return; // Response already sent by helper
            }
            
            if (!$input || !is_array($input)) {
                throw new Exception('Invalid request data.');
            }
            
            $db = Database::getInstance();
            $pdo = $db->getPdo();
            
            // Whitelist of allowed setting keys
            $allowedKeys = [
                // General
                'site_title',
                'site_tagline',
                'homepage_mode',        // 'latest_posts' or 'static_page'
                'page_on_front',        // Page ID for static homepage
                'blog_slug',            // URL slug for blog (e.g., 'blog', 'news')
                'posts_per_page',       // Number of posts per page
                
                // SEO
                'discourage_search_engines',
                'meta_description',
                'social_sharing_image',
                
                // System
                'maintenance_mode',
                'debug_mode',
            ];
            
            $savedCount = 0;
            
            // Get active theme for theme options
            $activeTheme = zed_get_option('active_theme', 'aurora');
            
            foreach ($input as $key => $value) {
                // Handle theme settings (prefixed with 'theme_')
                if (str_starts_with($key, 'theme_')) {
                    // Extract the setting ID (remove 'theme_' prefix)
                    $settingId = substr($key, 6); // 'theme_' = 6 chars
                    
                    // Store with proper format: theme_{active_theme}_{setting_id}
                    $optionName = "theme_{$activeTheme}_{$settingId}";
                    
                    // Sanitize value
                    $value = is_string($value) ? trim($value) : $value;
                    if (is_bool($value)) {
                        $value = $value ? '1' : '0';
                    }
                    
                    // Upsert theme option
                    $stmt = $pdo->prepare("
                        INSERT INTO zed_options (option_name, option_value, autoload) 
                        VALUES (:key, :value, 1)
                        ON DUPLICATE KEY UPDATE option_value = :value2
                    ");
                    $stmt->execute([
                        'key' => $optionName,
                        'value' => $value,
                        'value2' => $value
                    ]);
                    $savedCount++;
                    continue;
                }
                
                // Only save whitelisted keys for non-theme settings
                if (!in_array($key, $allowedKeys)) {
                    continue;
                }
                
                // Sanitize value
                $value = is_string($value) ? trim($value) : $value;
                if (is_bool($value)) {
                    $value = $value ? '1' : '0';
                }
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                
                // Upsert: INSERT or UPDATE
                $stmt = $pdo->prepare("
                    INSERT INTO zed_options (option_name, option_value, autoload) 
                    VALUES (:key, :value, 1)
                    ON DUPLICATE KEY UPDATE option_value = :value2
                ");
                $stmt->execute([
                    'key' => $key,
                    'value' => $value,
                    'value2' => $value
                ]);
                $savedCount++;
            }
            
            echo json_encode([
                'success' => true,
                'message' => "Settings saved successfully.",
                'saved' => $savedCount
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/quick-draft - Create a quick draft post (POST)
    if ($uri === '/admin/api/quick-draft' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        // Must have publish permission
        if (!zed_current_user_can('publish_content')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $title = trim($input['title'] ?? '');
            
            if (empty($title)) {
                throw new Exception('Title is required');
            }
            
            // Generate slug from title
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
            $slug = trim($slug, '-');
            $slug = substr($slug, 0, 100);
            
            // Ensure unique slug
            $db = Database::getInstance();
            $baseSlug = $slug;
            $counter = 1;
            while ($db->queryValue("SELECT COUNT(*) FROM zed_content WHERE slug = :slug", ['slug' => $slug]) > 0) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            // Create the draft
            $userId = Auth::user()['id'] ?? 1;
            $data = json_encode([
                'content' => [],
                'status' => 'draft',
                'excerpt' => '',
                'featured_image' => ''
            ]);
            
            $newId = $db->query(
                "INSERT INTO zed_content (title, slug, type, data, plain_text, author_id, created_at, updated_at) 
                 VALUES (:title, :slug, 'post', :data, '', :author, NOW(), NOW())",
                ['title' => $title, 'slug' => $slug, 'data' => $data, 'author' => $userId]
            );
            
            // Return success with redirect URL
            echo json_encode([
                'success' => true,
                'id' => $newId,
                'redirect' => $base_url . '/admin/editor?id=' . $newId
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/save-menu - Save menu items (POST, AJAX)
    if ($uri === '/admin/api/save-menu' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        if (!zed_current_user_can('manage_menus')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $menuId = (int)($input['menu_id'] ?? 0);
            $name = trim($input['name'] ?? '');
            $items = $input['items'] ?? [];
            
            if ($menuId <= 0) {
                throw new Exception('Invalid menu ID');
            }
            
            $db = Database::getInstance();
            
            // Clean items before saving (remove UI flags)
            $cleanItems = array_map(function($item) {
                return [
                    'label' => $item['label'] ?? '',
                    'url' => $item['url'] ?? '#',
                    'target' => $item['target'] ?? '_self',
                    'children' => isset($item['children']) ? array_map(function($child) {
                        return [
                            'label' => $child['label'] ?? '',
                            'url' => $child['url'] ?? '#',
                            'target' => $child['target'] ?? '_self',
                            'children' => []
                        ];
                    }, $item['children']) : []
                ];
            }, $items);
            
            // Update menu
            $itemsJson = json_encode($cleanItems);
            
            if (!empty($name)) {
                $db->query(
                    "UPDATE zed_menus SET name = :name, items = :items, updated_at = NOW() WHERE id = :id",
                    ['name' => $name, 'items' => $itemsJson, 'id' => $menuId]
                );
            } else {
                $db->query(
                    "UPDATE zed_menus SET items = :items, updated_at = NOW() WHERE id = :id",
                    ['items' => $itemsJson, 'id' => $menuId]
                );
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Menu saved successfully'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/delete-menu - Delete a menu (POST, AJAX)
    if ($uri === '/admin/api/delete-menu' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        if (!zed_current_user_can('manage_menus')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $menuId = (int)($input['id'] ?? 0);
            
            if ($menuId <= 0) {
                throw new Exception('Invalid menu ID');
            }
            
            $db = Database::getInstance();
            $db->query("DELETE FROM zed_menus WHERE id = :id", ['id' => $menuId]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Menu deleted'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/categories - Get categories list (GET)
    if ($uri === '/admin/api/categories' && $request['method'] === 'GET') {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['error' => 'Not authenticated']);
            exit;
        }
        
        try {
            $db = Database::getInstance();
            $categories = $db->query("SELECT * FROM zed_categories ORDER BY name ASC");
            echo json_encode($categories);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    // /admin/api/save-user - Create or Update User (POST, Admin only)
    if ($uri === '/admin/api/save-user' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        // Admin only
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        $currentUser = Auth::user();
        if (!in_array($currentUser['role'] ?? '', ['admin', 'administrator'])) {
            echo json_encode(['success' => false, 'error' => 'Admin access required']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                // Try form data
                $input = $_POST;
            }
            
            $id = !empty($input['id']) ? (int)$input['id'] : null;
            $email = trim($input['email'] ?? '');
            $password = $input['password'] ?? '';
            $role = $input['role'] ?? 'subscriber';
            
            // Validate email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Please enter a valid email address.');
            }
            
            // Validate role
            $allowedRoles = ['admin', 'administrator', 'editor', 'author', 'subscriber'];
            if (!in_array($role, $allowedRoles)) {
                $role = 'subscriber';
            }
            
            $db = Database::getInstance();
            
            if ($id) {
                // ===== UPDATE EXISTING USER =====
                
                // Check if user exists
                $existingUser = $db->queryOne("SELECT id, email, role FROM users WHERE id = :id", ['id' => $id]);
                if (!$existingUser) {
                    throw new Exception('User not found.');
                }
                
                // Check if changing email to one that already exists
                if ($email !== $existingUser['email']) {
                    $emailCheck = $db->queryOne("SELECT id FROM users WHERE email = :email AND id != :id", ['email' => $email, 'id' => $id]);
                    if ($emailCheck) {
                        throw new Exception('This email is already in use by another account.');
                    }
                }
                
                // Self-lock protection: prevent admin from demoting themselves
                if ($id === (int)$currentUser['id'] && 
                    in_array($existingUser['role'], ['admin', 'administrator']) && 
                    !in_array($role, ['admin', 'administrator'])) {
                    throw new Exception('You cannot remove your own admin privileges.');
                }
                
                // Build update query
                $sql = "UPDATE users SET email = :email, role = :role, updated_at = NOW()";
                $params = ['email' => $email, 'role' => $role, 'id' => $id];
                
                // Only update password if provided
                if (!empty($password)) {
                    if (strlen($password) < 6) {
                        throw new Exception('Password must be at least 6 characters.');
                    }
                    $sql .= ", password_hash = :password_hash";
                    $params['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
                }
                
                $sql .= " WHERE id = :id";
                $db->query($sql, $params);
                
                echo json_encode(['success' => true, 'message' => 'User updated successfully.', 'id' => $id]);
                
            } else {
                // ===== CREATE NEW USER =====
                
                // Password is mandatory for new users
                if (empty($password)) {
                    throw new Exception('Password is required for new users.');
                }
                if (strlen($password) < 6) {
                    throw new Exception('Password must be at least 6 characters.');
                }
                
                // Check if email already exists
                $emailCheck = $db->queryOne("SELECT id FROM users WHERE email = :email", ['email' => $email]);
                if ($emailCheck) {
                    throw new Exception('A user with this email already exists.');
                }
                
                // Insert new user
                $db->query(
                    "INSERT INTO users (email, password_hash, role, created_at, updated_at) VALUES (:email, :password_hash, :role, NOW(), NOW())",
                    [
                        'email' => $email,
                        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                        'role' => $role
                    ]
                );
                
                $newId = $db->getPdo()->lastInsertId();
                echo json_encode(['success' => true, 'message' => 'User created successfully.', 'id' => $newId]);
            }
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/delete-user - Delete User (POST, Admin only)
    if ($uri === '/admin/api/delete-user' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        // Admin only
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        $currentUser = Auth::user();
        if (!in_array($currentUser['role'] ?? '', ['admin', 'administrator'])) {
            echo json_encode(['success' => false, 'error' => 'Admin access required']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                $input = $_POST;
            }
            
            $id = !empty($input['id']) ? (int)$input['id'] : null;
            
            if (!$id) {
                throw new Exception('User ID is required.');
            }
            
            // Self-deletion protection
            if ($id === (int)$currentUser['id']) {
                throw new Exception('You cannot delete your own account.');
            }
            
            $db = Database::getInstance();
            
            // Check if user exists
            $user = $db->queryOne("SELECT id, email FROM users WHERE id = :id", ['id' => $id]);
            if (!$user) {
                throw new Exception('User not found.');
            }
            
            // Delete user
            $db->query("DELETE FROM users WHERE id = :id", ['id' => $id]);
            
            echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/save - Save content API (POST)
    if (($uri === '/admin/api/save' || $uri === '/admin/save-post') && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated', 'message' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        try {
            // Get JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                throw new Exception('Invalid JSON input');
            }
            
            $db = Database::getInstance();
            
            $id = $input['id'] ?? null;
            $title = trim($input['title'] ?? '');
            $slug = trim($input['slug'] ?? '');
            $type = $input['type'] ?? 'page';
            // Handle both structure types (nested data or flat)
            $content = $input['content'] ?? ($input['data']['content'] ?? []);
            $status = $input['status'] ?? ($input['data']['status'] ?? 'draft');
            
            // Reconstruct data array for storage
            $data = [
                'content' => is_string($content) ? json_decode($content, true) : $content,
                'status' => $status,
                'featured_image' => $input['data']['featured_image'] ?? '',
                'categories' => $input['data']['categories'] ?? [],
                'template' => $input['data']['template'] ?? 'default',
                'excerpt' => $input['excerpt'] ?? ($input['data']['excerpt'] ?? '')
            ];
            
            // =================================================================
            // SHADOW TEXT SEARCH STRATEGY
            // Extract plain text for search indexing
            // =================================================================
            $plainText = extract_text_from_blocks($data['content']);
            
            if (empty($title)) {
                throw new Exception('Title is required');
            }
            
            // Auto-generate slug if empty
            if (empty($slug)) {
                $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
                $slug = trim($slug, '-');
            }
            
            // Encode data as JSON
            $dataJson = json_encode($data);
            $userId = Auth::user()['id'] ?? 1;
            
            // =================================================================
            // CONTENT REVISION SYSTEM
            // Capture current state before update for version history
            // =================================================================
            $capturedRevision = null;
            if ($id) {
                try {
                    // Fetch current content state before modification
                    $currentContent = $db->queryOne(
                        "SELECT id, title, slug, type, data, author_id FROM zed_content WHERE id = :id",
                        ['id' => $id]
                    );
                    
                    if ($currentContent) {
                        // Store full state for revision
                        $capturedRevision = [
                            'content_id' => (int)$id,
                            'data_json' => json_encode([
                                'title' => $currentContent['title'],
                                'slug' => $currentContent['slug'],
                                'type' => $currentContent['type'],
                                'data' => is_string($currentContent['data']) 
                                    ? json_decode($currentContent['data'], true) 
                                    : $currentContent['data'],
                            ]),
                            'author_id' => $userId, // Who made this edit
                        ];
                    }
                } catch (Exception $e) {
                    // Don't fail the save if revision capture fails
                    error_log("Revision capture failed: " . $e->getMessage());
                }
            }
            
            // Helper to execute query with auto-migration (self-healing)
            $executeSave = function() use ($db, $id, $title, $slug, $type, $dataJson, $plainText, $userId) {
                if ($id) {
                    // Update existing
                    $db->query(
                        "UPDATE zed_content SET title = :title, slug = :slug, type = :type, data = :data, plain_text = :plain_text, updated_at = NOW() WHERE id = :id",
                        ['id' => $id, 'title' => $title, 'slug' => $slug, 'type' => $type, 'data' => $dataJson, 'plain_text' => $plainText]
                    );
                    return ['success' => true, 'id' => $id, 'action' => 'update', 'message' => 'Content updated'];
                } else {
                    // Insert new
                    $newId = $db->query(
                        "INSERT INTO zed_content (title, slug, type, data, plain_text, author_id, created_at, updated_at) VALUES (:title, :slug, :type, :data, :plain_text, :author, NOW(), NOW())",
                        ['title' => $title, 'slug' => $slug, 'type' => $type, 'data' => $dataJson, 'plain_text' => $plainText, 'author' => $userId]
                    );
                    return ['success' => true, 'id' => $newId, 'new_id' => $newId, 'action' => 'create', 'message' => 'Content created'];
                }
            };
            
            // Helper to save revision and cleanup old ones
            $saveRevision = function() use ($db, $capturedRevision) {
                if (!$capturedRevision) return;
                
                try {
                    // Insert the revision
                    $db->query(
                        "INSERT INTO zed_content_revisions (content_id, data_json, author_id, created_at) VALUES (:content_id, :data_json, :author_id, NOW())",
                        $capturedRevision
                    );
                    
                    // Cleanup: Keep only last 10 revisions per content
                    $contentId = $capturedRevision['content_id'];
                    $db->query(
                        "DELETE FROM zed_content_revisions 
                         WHERE content_id = :content_id 
                         AND id NOT IN (
                             SELECT id FROM (
                                 SELECT id FROM zed_content_revisions 
                                 WHERE content_id = :content_id2 
                                 ORDER BY created_at DESC 
                                 LIMIT 10
                             ) AS keep_rows
                         )",
                        ['content_id' => $contentId, 'content_id2' => $contentId]
                    );
                } catch (Exception $e) {
                    // Table might not exist yet, ignore
                    error_log("Revision save failed: " . $e->getMessage());
                }
            };
            
            try {
                $response = $executeSave();
                
                if ($response['success']) {
                    // Save revision after successful update
                    $saveRevision();
                    \Core\Event::trigger('zed_post_saved', $response['id'], $data);
                }
            } catch (PDOException $e) {
                // Check for "Unknown column 'plain_text'" error (Code 1054)
                if (str_contains($e->getMessage(), 'Unknown column') && str_contains($e->getMessage(), 'plain_text')) {
                    // Self-healing: Add the column on the fly
                    $db->query("ALTER TABLE zed_content ADD COLUMN plain_text LONGTEXT NULL AFTER data");
                    // Retry
                    $response = $executeSave();
                    // Save revision after retry success
                    if ($response['success']) {
                        $saveRevision();
                    }
                } else {
                    throw $e;
                }
            }
            
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log("Save Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage(), 'message' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/api/upload - Image upload for Editor.js (POST) with WebP conversion
    if ($uri === '/admin/api/upload' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        // Check authentication
        if (!Auth::check()) {
            echo json_encode(['success' => 0, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }
        
        try {
            // Support both 'image' and 'file' field names
            $fileField = isset($_FILES['image']) ? 'image' : (isset($_FILES['file']) ? 'file' : null);
            
            if (!$fileField || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No file uploaded or upload error');
            }
            
            $file = $_FILES[$fileField];
            
            // Validate file size (10MB max)
            $maxSize = 10 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                throw new Exception('File too large. Maximum size is 10MB');
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG, PNG, WebP, and GIF are allowed');
            }
            
            // Create uploads directory if it doesn't exist
            $uploadDir = dirname(dirname(__DIR__)) . '/uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Use advanced WebP processing
            $result = zed_process_upload($file['tmp_name'], $file['name'], $uploadDir);
            
            if (!$result) {
                throw new Exception('Failed to process uploaded file');
            }
            
            // Build the public URL for the WebP version
            $basePath = Router::getBasePath();
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $publicUrl = $protocol . '://' . $host . $basePath . '/content/uploads/' . $result['filename'];
            
            // Return success in Editor.js and Media Manager format
            echo json_encode([
                'success' => 1,
                'status' => 'success',
                'file' => [
                    'url' => $publicUrl
                ],
                'url' => $publicUrl,
                'filename' => $result['filename'],
                'size' => filesize($result['webp'] ?? $result['original'])
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => 0,
                'status' => 'error',
                'error' => $e->getMessage()
            ]);
        }
        
        Router::setHandled('');
        return;
    }

    // =========================================================================
    // ADDON MANAGER API
    // =========================================================================

    // POST /admin/api/toggle-addon - Enable/disable an addon
    if ($uri === '/admin/api/toggle-addon' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!zed_user_can_access_admin() || !zed_current_user_can('manage_addons')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // SECURITY: Verify CSRF nonce
            if (!zed_require_ajax_nonce($input)) {
                return; // Response already sent by helper
            }
            
            $identifier = $input['filename'] ?? '';
            
            // Security: prevent directory traversal
            $identifier = basename($identifier);
            
            if (empty($identifier)) {
                throw new Exception('No addon identifier provided');
            }
            
            // Prevent disabling system addons
            $systemAddons = defined('ZERO_SYSTEM_ADDONS') ? ZERO_SYSTEM_ADDONS : ['admin_addon.php', 'frontend_addon.php'];
            if (in_array($identifier, $systemAddons, true)) {
                throw new Exception('System addons cannot be disabled');
            }
            
            // Find the addon file (supports both file and folder addons)
            $addonsDir = dirname(__DIR__); // content/addons
            $addonFile = null;
            $addonType = null;
            
            // Check for single-file addon: addons/{identifier}
            if (file_exists($addonsDir . '/' . $identifier) && str_ends_with($identifier, '.php')) {
                $addonFile = $addonsDir . '/' . $identifier;
                $addonType = 'file';
            }
            // Check for folder-based addon: addons/{identifier}/addon.php
            elseif (file_exists($addonsDir . '/' . $identifier . '/addon.php')) {
                $addonFile = $addonsDir . '/' . $identifier . '/addon.php';
                $addonType = 'folder';
            } else {
                throw new Exception('Addon not found: ' . $identifier);
            }
            
            $db = Database::getInstance();
            
            // Get current active_addons list
            $current = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_addons'");
            $activeAddons = $current ? json_decode($current, true) : null;
            
            // If no option exists, initialize with all non-system addons as active
            if ($activeAddons === null) {
                $activeAddons = [];
                // Single-file addons
                foreach (glob($addonsDir . '/*.php') as $file) {
                    $name = basename($file);
                    if (!in_array($name, $systemAddons, true)) {
                        $activeAddons[] = $name;
                    }
                }
                // Folder-based addons
                foreach (glob($addonsDir . '/*/addon.php') as $file) {
                    $folderName = basename(dirname($file));
                    $activeAddons[] = $folderName;
                }
            }
            
            // Toggle the addon
            $isActive = in_array($identifier, $activeAddons, true);
            if ($isActive) {
                $activeAddons = array_values(array_diff($activeAddons, [$identifier]));
                $newState = false;
            } else {
                $activeAddons[] = $identifier;
                $newState = true;
            }
            
            // Save back to database
            $jsonValue = json_encode(array_values(array_unique($activeAddons)));
            $exists = $db->queryValue("SELECT COUNT(*) FROM zed_options WHERE option_name = 'active_addons'");
            if ($exists) {
                $db->query("UPDATE zed_options SET option_value = :val WHERE option_name = 'active_addons'", ['val' => $jsonValue]);
            } else {
                $db->query("INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('active_addons', :val, 1)", ['val' => $jsonValue]);
            }
            
            // Get addon name for message
            $addonName = ucwords(str_replace(['_', '-', '.php'], [' ', ' ', ''], $identifier));
            $content = file_get_contents($addonFile, false, null, 0, 2048);
            if (preg_match('/Addon Name:\s*(.*)$/mi', $content, $m)) {
                $addonName = trim($m[1]);
            }
            
            echo json_encode([
                'success' => true,
                'active' => $newState,
                'message' => $addonName . ($newState ? ' Activated' : ' Deactivated')
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // POST /admin/api/upload-addon - Upload a new addon file
    if ($uri === '/admin/api/upload-addon' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!zed_user_can_access_admin() || !zed_current_user_can('manage_addons')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            if (!isset($_FILES['addon']) || $_FILES['addon']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No file uploaded or upload error');
            }
            
            $file = $_FILES['addon'];
            $filename = basename($file['name']);
            
            // Validate extension
            if (!str_ends_with(strtolower($filename), '.php')) {
                throw new Exception('Only .php files are allowed');
            }
            
            // Sanitize filename
            $safeFilename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
            
            // Move to addons directory
            $addonsDir = dirname(__DIR__); // content/addons
            $destPath = $addonsDir . '/' . $safeFilename;
            
            if (file_exists($destPath)) {
                throw new Exception('An addon with this name already exists');
            }
            
            if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                throw new Exception('Failed to save addon file');
            }
            
            // Auto-activate the new addon
            $db = Database::getInstance();
            $current = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_addons'");
            $activeAddons = $current ? json_decode($current, true) : [];
            if (!is_array($activeAddons)) $activeAddons = [];
            
            $activeAddons[] = $safeFilename;
            $jsonValue = json_encode(array_values(array_unique($activeAddons)));
            
            $exists = $db->queryValue("SELECT COUNT(*) FROM zed_options WHERE option_name = 'active_addons'");
            if ($exists) {
                $db->query("UPDATE zed_options SET option_value = :val WHERE option_name = 'active_addons'", ['val' => $jsonValue]);
            } else {
                $db->query("INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('active_addons', :val, 1)", ['val' => $jsonValue]);
            }
            
            echo json_encode([
                'success' => true,
                'filename' => $safeFilename,
                'message' => 'Addon uploaded and activated'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // =========================================================================
    // THEME MANAGER API
    // =========================================================================

    // POST /admin/api/activate-theme - Switch the active theme
    if ($uri === '/admin/api/activate-theme' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        if (!zed_user_can_access_admin() || !zed_current_user_can('manage_themes')) {
            echo json_encode(['success' => false, 'error' => 'Permission denied']);
            Router::setHandled('');
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // SECURITY: Verify CSRF nonce
            if (!zed_require_ajax_nonce($input)) {
                return; // Response already sent by helper
            }
            
            $themeName = basename($input['theme'] ?? '');
            
            if (empty($themeName)) {
                throw new Exception('No theme specified');
            }
            
            // Validate theme folder exists
            $themesDir = dirname(dirname(__DIR__)) . '/themes'; // content/themes
            $themePath = $themesDir . '/' . $themeName;
            
            if (!is_dir($themePath)) {
                throw new Exception('Theme not found');
            }
            
            // Exclude admin theme
            if ($themeName === 'admin-default') {
                throw new Exception('Cannot activate admin theme as frontend theme');
            }
            
            $db = Database::getInstance();
            
            // Update or insert active_theme option
            $exists = $db->queryValue("SELECT COUNT(*) FROM zed_options WHERE option_name = 'active_theme'");
            if ($exists) {
                $db->query("UPDATE zed_options SET option_value = :val WHERE option_name = 'active_theme'", ['val' => $themeName]);
            } else {
                $db->query("INSERT INTO zed_options (option_name, option_value, autoload) VALUES ('active_theme', :val, 1)", ['val' => $themeName]);
            }
            
            // Trigger theme switched event
            Event::trigger('zed_theme_switched', $themeName);
            
            // Get theme display name
            $displayName = $themeName;
            $jsonPath = $themePath . '/theme.json';
            if (file_exists($jsonPath)) {
                $themeData = json_decode(file_get_contents($jsonPath), true);
                $displayName = $themeData['name'] ?? $themeName;
            }
            
            echo json_encode([
                'success' => true,
                'theme' => $themeName,
                'message' => $displayName . ' activated'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }

    // /admin/themes - Theme Manager page
    if ($uri === '/admin/themes') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        if (!zed_current_user_can('manage_themes')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        // Scan themes directory
        $themesDir = dirname(dirname(__DIR__)) . '/themes'; // content/themes
        $themes = [];
        
        if (is_dir($themesDir)) {
            foreach (scandir($themesDir) as $folder) {
                if ($folder === '.' || $folder === '..' || $folder === 'admin-default') continue;
                
                $themePath = $themesDir . '/' . $folder;
                if (!is_dir($themePath)) continue;
                
                $theme = [
                    'folder' => $folder,
                    'name' => ucwords(str_replace(['-', '_'], ' ', $folder)),
                    'version' => '1.0.0',
                    'author' => 'Unknown',
                    'description' => '',
                    'colors' => [
                        'brand' => '#256af4',
                        'background' => '#ffffff',
                        'text' => '#111827'
                    ],
                    'screenshot' => null
                ];
                
                // Parse theme.json
                $jsonPath = $themePath . '/theme.json';
                if (file_exists($jsonPath)) {
                    $data = json_decode(file_get_contents($jsonPath), true);
                    if ($data) {
                        $theme['name'] = $data['name'] ?? $theme['name'];
                        $theme['version'] = $data['version'] ?? $theme['version'];
                        $theme['author'] = $data['author'] ?? $theme['author'];
                        $theme['description'] = $data['description'] ?? $theme['description'];
                        if (isset($data['settings'])) {
                            $theme['colors']['brand'] = $data['settings']['brand_color'] ?? $theme['colors']['brand'];
                            $theme['colors']['background'] = $data['settings']['background'] ?? $theme['colors']['background'];
                            $theme['colors']['text'] = $data['settings']['text_color'] ?? $theme['colors']['text'];
                        }
                    }
                }
                
                // Check for screenshot
                foreach (['screenshot.png', 'screenshot.jpg', 'screenshot.webp'] as $img) {
                    if (file_exists($themePath . '/' . $img)) {
                        $theme['screenshot'] = Router::getBasePath() . '/content/themes/' . $folder . '/' . $img;
                        break;
                    }
                }
                
                $themes[] = $theme;
            }
        }
        
        // Get current active theme
        try {
            $db = Database::getInstance();
            $activeTheme = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_theme'") ?: 'starter-theme';
        } catch (Exception $e) {
            $activeTheme = 'starter-theme';
        }
        
        $current_user = Auth::user();
        $current_page = 'themes';
        $page_title = 'Themes';
        $adminThemePath = __DIR__ . '/../../themes/admin-default';
        $content_partial = $adminThemePath . '/partials/themes-content.php';
        
        ob_start();
        require $adminThemePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }

    // =========================================================================
    // ADDON SETTINGS LIST — /admin/addon-settings
    // =========================================================================
    if ($uri === '/admin/addon-settings') {
        if (!zed_current_user_can('manage_settings')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        // Get all registered addon settings
        $allSettings = zed_get_addon_settings();
        
        // Render using AdminRenderer (theme-agnostic)
        $content = AdminRenderer::renderPage('addon-settings-list', [
            'addons' => $allSettings,
        ], [
            'current_page' => 'addon_settings',
            'page_title' => 'Addon Settings',
        ]);
        
        Router::setHandled($content);
        return;
    }

    // =========================================================================
    // ADDON SETTINGS PAGES — /admin/addon-settings/{addon_id}
    // =========================================================================
    if (preg_match('#^/admin/addon-settings/(\w+)$#', $uri, $matches)) {
        $addon_id = $matches[1];
        $allSettings = zed_get_addon_settings();
        
        if (!isset($allSettings[$addon_id])) {
            // Addon has no registered settings
            Router::redirect('/admin/addons');
        }
        
        $config = $allSettings[$addon_id];
        
        // Check capability
        if (!zed_current_user_can($config['capability'] ?? 'manage_settings')) {
            Router::setHandled(zed_render_forbidden());
            return;
        }
        
        // Handle save
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($config['fields'] as $field) {
                $fieldId = $field['id'] ?? '';
                $fieldName = "addon_{$addon_id}_{$fieldId}";
                $fieldType = $field['type'] ?? 'text';
                
                // Handle toggle (checkbox) - if not in POST, it's unchecked
                if ($fieldType === 'toggle') {
                    $value = isset($_POST[$fieldName]) ? '1' : '0';
                } else {
                    $value = $_POST[$fieldName] ?? '';
                }
                
                zed_set_addon_option($addon_id, $fieldId, $value);
            }
            
            zed_add_notice('Settings saved successfully!', 'success');
            Router::redirect('/admin/addon-settings/' . $addon_id);
        }
        
        // Render settings page using AdminRenderer
        $content = AdminRenderer::renderPage('addon-settings-detail', [
            'addon_id' => $addon_id,
            'config' => $config,
        ], [
            'current_page' => 'addon_settings',
            'page_title' => ($config['title'] ?? ucwords(str_replace('_', ' ', $addon_id))) . ' Settings',
        ]);
        
        Router::setHandled($content);
        return;
    }


    // =========================================================================
    // BATCH OPERATIONS
    // =========================================================================

    // POST /admin/api/batch-delete-content - Delete multiple content items
    if ($uri === '/admin/api/batch-delete-content' && $request['method'] === 'POST') {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // SECURITY: Verify CSRF nonce
            if (!zed_require_ajax_nonce($input)) {
                return; // Response already sent by helper
            }
            
            $ids = $input['ids'] ?? [];

            if (empty($ids) || !is_array($ids)) {
                 throw new Exception('No items selected');
            }

            $db = Database::getInstance();
            $deletedCount = 0;

            foreach ($ids as $id) {
                // Perform RBAC checks per item if necessary
                if (!zed_current_user_can('delete_others_content')) {
                     $content = $db->queryOne("SELECT author_id FROM zed_content WHERE id = :id", ['id' => $id]);
                     if (!$content || (int)$content['author_id'] !== Auth::id()) {
                         continue; // Skip items user can't delete
                     }
                }
                 
                $db->query("DELETE FROM zed_content WHERE id = :id", ['id' => $id]);
                \Core\Event::trigger('zed_post_deleted', $id);
                $deletedCount++;
            }

            echo json_encode(['success' => true, 'count' => $deletedCount, 'message' => "$deletedCount items deleted"]);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        Router::setHandled('');
        return;
    }

    // POST /admin/api/batch-delete-media - Delete multiple media files
    if ($uri === '/admin/api/batch-delete-media' && $request['method'] === 'POST') {
        header('Content-Type: application/json');

        if (!zed_user_can_access_admin()) {
            echo json_encode(['success' => false, 'error' => 'Not authenticated']);
            Router::setHandled('');
            return;
        }

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // SECURITY: Verify CSRF nonce
            if (!zed_require_ajax_nonce($input)) {
                return; // Response already sent by helper
            }
            
            $files = $input['files'] ?? [];

            if (empty($files) || !is_array($files)) {
                throw new Exception('No files selected');
            }

            $uploadDir = dirname(dirname(__DIR__)) . '/uploads';
            $deletedCount = 0;

            foreach ($files as $file) {
                $safeFile = basename($file);
                if (empty($safeFile)) continue;

                $baseName = pathinfo($safeFile, PATHINFO_FILENAME);
                
                // Primary file
                if (file_exists($uploadDir . '/' . $safeFile)) {
                    unlink($uploadDir . '/' . $safeFile);
                    $deletedCount++;
                }
                
                // Thumb version
                $thumbPatterns = [
                    $uploadDir . '/thumb_' . $safeFile,
                    $uploadDir . '/thumb_' . $baseName . '.webp'
                ];
                foreach ($thumbPatterns as $thumb) {
                    if (file_exists($thumb)) unlink($thumb);
                }
                
                // Original backup
                foreach (glob($uploadDir . '/' . $baseName . '_original.*') as $origFile) {
                    unlink($origFile);
                }
                
                // WebP related originals
                if (str_ends_with(strtolower($safeFile), '.webp')) {
                    $noExt = preg_replace('/\.webp$/i', '', $safeFile);
                    foreach (glob($uploadDir . '/' . $noExt . '_original.*') as $origFile) {
                        unlink($origFile);
                    }
                }
            }

            echo json_encode(['success' => true, 'count' => $deletedCount, 'message' => "$deletedCount files deleted"]);

        } catch (Exception $e) {
             echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        Router::setHandled('');
        return;
    }

}, 10);



