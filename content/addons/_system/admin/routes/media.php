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
    
    // /admin/media/delete - Delete media file
    if ($uri === '/admin/media/delete') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        $file = $_GET['file'] ?? null;
        if ($file) {
            $uploadDir = dirname(dirname(dirname(dirname(__DIR__)))) . '/uploads';
            
            $safeFile = basename($file);
            $fullPath = $uploadDir . '/' . $safeFile;
            
            $deleted = false;
            if (file_exists($fullPath)) {
                $deleted = unlink($fullPath);
            }
            
            // Delete thumbnails and originals
            $baseName = pathinfo($safeFile, PATHINFO_FILENAME);
            foreach (glob($uploadDir . '/' . $baseName . '-*') as $thumbFile) {
                unlink($thumbFile);
            }
            foreach (glob($uploadDir . '/' . $baseName . '_original.*') as $origFile) {
                unlink($origFile);
            }
            
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => $deleted, 'message' => $deleted ? 'File deleted' : 'File not found']);
                Router::setHandled('');
                return true;
            }
            
            Router::redirect('/admin/media?msg=' . ($deleted ? 'deleted' : 'error'));
        } else {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'No file specified']);
                Router::setHandled('');
                return true;
            }
            Router::redirect('/admin/media?msg=error');
        }
        return true;
    }
    
    return false;
}
