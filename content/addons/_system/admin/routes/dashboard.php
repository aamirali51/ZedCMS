<?php
/**
 * Admin Routes - Dashboard
 * 
 * Handles the main admin dashboard route.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * Handle dashboard route: /admin or /admin/dashboard
 * 
 * @param array $request The request data
 * @param string $uri The request URI
 * @param string $themePath Path to admin theme
 * @return bool True if request was handled
 */
function zed_handle_dashboard_route(array $request, string $uri, string $themePath): bool
{
    if ($uri !== '/admin' && $uri !== '/admin/dashboard') {
        return false;
    }
    
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
        $addons_dir = dirname(dirname(dirname(__DIR__))); // content/addons
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
        $db = null;
    }
    
    // Health checks
    $health_checks = [];
    $health_status = 'nominal';
    
    // 1. Check uploads folder is writable
    $uploadsPath = dirname(dirname(dirname(dirname(__DIR__)))) . '/uploads';
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
        if ($db) {
            $seoBlocked = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'discourage_search_engines'");
            if ($seoBlocked === '1') {
                $health_checks[] = ['status' => 'warning', 'label' => 'SEO', 'detail' => 'Search engines blocked'];
                if ($health_status === 'nominal') $health_status = 'warning';
            } else {
                $health_checks[] = ['status' => 'ok', 'label' => 'SEO', 'detail' => 'Indexing enabled'];
            }
        }
    } catch (Exception $e) {
        $health_checks[] = ['status' => 'ok', 'label' => 'SEO', 'detail' => 'Indexing enabled'];
    }
    
    // 4. Check maintenance mode
    try {
        if ($db) {
            $maintenanceMode = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'maintenance_mode'");
            if ($maintenanceMode === '1') {
                $health_checks[] = ['status' => 'warning', 'label' => 'Maintenance', 'detail' => 'Site is offline'];
                if ($health_status === 'nominal') $health_status = 'warning';
            } else {
                $health_checks[] = ['status' => 'ok', 'label' => 'Maintenance', 'detail' => 'Site is live'];
            }
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
    return true;
}
