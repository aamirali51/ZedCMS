<?php
/**
 * Admin Routes - Themes
 * 
 * Handles theme manager routes.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * Handle themes routes: /admin/themes
 */
function zed_handle_themes_routes(array $request, string $uri, string $themePath): bool
{
    if ($uri !== '/admin/themes') {
        return false;
    }
    
    // Handle POST - Theme activation
    if ($request['method'] === 'POST') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        if (!zed_current_user_can('manage_themes')) {
            Router::redirect('/admin/themes?msg=permission_denied');
            return true;
        }
        
        $newTheme = $_POST['theme'] ?? null;
        if (!empty($newTheme)) {
            $themesDir = dirname(dirname(dirname(dirname(__DIR__)))) . '/themes';
            $targetPath = $themesDir . '/' . $newTheme;
            
            if (is_dir($targetPath) && $newTheme !== 'admin-default') {
                try {
                    $db = Database::getInstance();
                    $db->query(
                        "INSERT INTO zed_options (option_name, option_value, autoload) 
                         VALUES ('active_theme', :theme, 1) 
                         ON DUPLICATE KEY UPDATE option_value = :theme",
                        ['theme' => $newTheme]
                    );
                    Router::redirect('/admin/themes?msg=activated');
                } catch (Exception $e) {
                    Router::redirect('/admin/themes?msg=error');
                }
            } else {
                Router::redirect('/admin/themes?msg=invalid');
            }
        }
        return true;
    }
    
    // Handle GET - Theme list
    if (!zed_user_can_access_admin()) {
        Router::redirect('/admin/login');
    }
    
    $themesDir = dirname(dirname(dirname(dirname(__DIR__)))) . '/themes';
    $themes = [];
    
    foreach (glob($themesDir . '/*', GLOB_ONLYDIR) as $themeDir) {
        $themeName = basename($themeDir);
        if ($themeName === 'admin-default') continue;
        
        $themeInfo = [
            'slug' => $themeName,
            'name' => $themeName,
            'version' => '1.0.0',
            'author' => 'Unknown',
            'description' => '',
            'screenshot' => null,
            'colors' => [
                'brand' => '#6366f1',
                'background' => '#f8fafc',
                'text' => '#1e293b'
            ]
        ];
        
        $jsonPath = $themeDir . '/theme.json';
        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);
            if ($jsonData) {
                $themeInfo = array_merge($themeInfo, $jsonData);
            }
        }
        
        $screenshots = ['screenshot.png', 'screenshot.jpg', 'screenshot.webp'];
        foreach ($screenshots as $ss) {
            if (file_exists($themeDir . '/' . $ss)) {
                $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
                $themeInfo['screenshot'] = $baseUrl . '/content/themes/' . $themeName . '/' . $ss;
                break;
            }
        }
        
        $themes[] = $themeInfo;
    }
    
    try {
        $db = Database::getInstance();
        $activeTheme = $db->queryValue("SELECT option_value FROM zed_options WHERE option_name = 'active_theme'");
    } catch (Exception $e) {
        $activeTheme = 'starter-theme';
    }
    
    $current_user = Auth::user();
    $current_page = 'themes';
    $page_title = 'Themes';
    $content_partial = $themePath . '/partials/themes-content.php';
    
    ob_start();
    require $themePath . '/admin-layout.php';
    $content = ob_get_clean();
    Router::setHandled($content);
    return true;
}
