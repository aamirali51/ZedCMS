<?php
/**
 * Admin Routes - Media
 * 
 * Handles media library routes.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * Handle media routes: /admin/media, /admin/media/delete
 */
function zed_handle_media_routes(array $request, string $uri, string $themePath): bool
{
    // /admin/media - Media Library
    if ($uri === '/admin/media') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        // Fetch media from database (zed_media table)
        $db = Database::getInstance();
        $files = [];
        
        try {
            // Check if zed_media table exists
            $tableExists = $db->queryValue("SHOW TABLES LIKE 'zed_media'");
            
            if ($tableExists) {
                $mediaItems = $db->query("SELECT * FROM zed_media ORDER BY uploaded_at DESC");
                
                foreach ($mediaItems as $item) {
                    $files[] = [
                        'id' => $item['id'],
                        'name' => $item['filename'] ?? 'unknown',
                        'original_name' => $item['original_filename'] ?? $item['filename'],
                        'url' => $item['url'] ?? '',
                        'thumb' => $item['thumbnail_url'] ?? $item['url'] ?? '',
                        'medium' => $item['medium_url'] ?? $item['url'] ?? '',
                        'large' => $item['large_url'] ?? $item['url'] ?? '',
                        'file_path' => $item['file_path'] ?? '',
                        'size' => (int)($item['file_size'] ?? 0),
                        'width' => (int)($item['width'] ?? 0),
                        'height' => (int)($item['height'] ?? 0),
                        'modified' => strtotime($item['uploaded_at'] ?? 'now'),
                        'type' => $item['mime_type'] ?? 'image/jpeg',
                        'isWebp' => stripos($item['mime_type'] ?? '', 'webp') !== false
                    ];
                }
            }
        } catch (Exception $e) {
            // Table doesn't exist or query failed - show empty state
            $files = [];
        }
        
        // Helper function for formatting file sizes
        if (!function_exists('formatBytes')) {
            function formatBytes($bytes, $precision = 2) {
                $units = ['B', 'KB', 'MB', 'GB'];
                $bytes = max($bytes, 0);
                $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                $pow = min($pow, count($units) - 1);
                $bytes /= (1 << (10 * $pow));
                return round($bytes, $precision) . ' ' . $units[$pow];
            }
        }
        
        // Set variables for the view
        $siteUrl = Router::url('/');
        $uploadApiUrl = Router::url('/admin/api/upload');
        $deleteUrl = Router::url('/admin/api/media/delete');
        
        $current_user = Auth::user();
        $current_page = 'media';
        $page_title = 'Media Library';
        $content_partial = $themePath . '/partials/media-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return true;
    }
    
    return false;
}
