<?php
/**
 * Admin Routes - Cache Management
 * 
 * Handles cache management routes.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;

/**
 * Handle cache routes: /admin/cache
 */
function zed_handle_cache_routes(array $request, string $uri, string $themePath): bool
{
    $cleanUri = ltrim($uri, '/');
    
    // /admin/cache - Cache Management Page
    if ($cleanUri === 'admin/cache') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
            return true;
        }
        
        if (!zed_current_user_can('manage_settings')) {
            Router::setHandled(zed_render_forbidden());
            return true;
        }
        
        $current_user = Auth::user();
        $current_page = 'cache';
        $page_title = 'Cache Management';
        $content_partial = $themePath . '/partials/cache-content.php';
        
        ob_start();
        require $themePath . '/admin-layout.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return true;
    }
    
    return false;
}

/**
 * API: Clear specific cache type
 */
function zed_api_clear_cache(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        Router::setHandled('');
        return true;
    }
    
    if (!zed_current_user_can('manage_settings')) {
        echo json_encode(['success' => false, 'error' => 'Permission denied']);
        Router::setHandled('');
        return true;
    }
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $type = $input['type'] ?? 'all';
        
        $stats = ['cleared' => 0, 'type' => $type];
        
        switch ($type) {
            case 'addon':
                // Clear addon file cache
                $addonCacheFile = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/content/addons/.addon_cache.php';
                if (file_exists($addonCacheFile) && @unlink($addonCacheFile)) {
                    $stats['cleared'] = 1;
                }
                $stats['message'] = 'Addon cache cleared';
                break;
                
            case 'content':
                // Clear content/query cache (from cache.php)
                if (function_exists('zed_cache_flush')) {
                    $stats['cleared'] = zed_cache_flush();
                }
                $stats['message'] = 'Content cache cleared';
                break;
                
            case 'opcache':
                // Clear PHP OPcache if available
                if (function_exists('opcache_reset')) {
                    opcache_reset();
                    $stats['cleared'] = 1;
                    $stats['message'] = 'OPcache cleared';
                } else {
                    $stats['message'] = 'OPcache not available';
                }
                break;
                
            case 'all':
            default:
                $cleared = 0;
                
                // Addon cache
                $addonCacheFile = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/content/addons/.addon_cache.php';
                if (file_exists($addonCacheFile) && @unlink($addonCacheFile)) {
                    $cleared++;
                }
                
                // Content cache
                if (function_exists('zed_cache_flush')) {
                    $cleared += zed_cache_flush();
                }
                
                // OPcache
                if (function_exists('opcache_reset')) {
                    opcache_reset();
                    $cleared++;
                }
                
                $stats['cleared'] = $cleared;
                $stats['message'] = 'All caches cleared';
                break;
        }
        
        echo json_encode([
            'success' => true,
            'message' => $stats['message'],
            'cleared' => $stats['cleared']
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    Router::setHandled('');
    return true;
}

/**
 * API: Get cache status
 */
function zed_api_cache_status(): bool
{
    header('Content-Type: application/json');
    
    if (!Auth::check() || !zed_current_user_can('manage_settings')) {
        echo json_encode(['success' => false, 'error' => 'Permission denied']);
        Router::setHandled('');
        return true;
    }
    
    $status = [];
    
    // Addon cache
    $addonCacheFile = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/content/addons/.addon_cache.php';
    $status['addon'] = [
        'exists' => file_exists($addonCacheFile),
        'size' => file_exists($addonCacheFile) ? filesize($addonCacheFile) : 0,
        'age' => file_exists($addonCacheFile) ? time() - filemtime($addonCacheFile) : 0,
    ];
    
    // Content cache
    if (function_exists('zed_cache_stats')) {
        $status['content'] = zed_cache_stats();
    }
    
    // OPcache
    $status['opcache'] = [
        'available' => function_exists('opcache_get_status'),
        'enabled' => function_exists('opcache_get_status') ? (opcache_get_status(false)['opcache_enabled'] ?? false) : false,
    ];
    
    echo json_encode(['success' => true, 'status' => $status]);
    Router::setHandled('');
    return true;
}
