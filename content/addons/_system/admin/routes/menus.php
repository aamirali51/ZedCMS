<?php
/**
 * Admin Routes - Menus
 * 
 * Handles menu builder routes.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * Handle menus routes: /admin/menus, /admin/menus/create
 */
function zed_handle_menus_routes(array $request, string $uri, string $themePath): bool
{
    // /admin/menus/create - Create New Menu
    if ($uri === '/admin/menus/create' && $request['method'] === 'POST') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $name = trim($_POST['name'] ?? '');
        if (!empty($name)) {
            $db = Database::getInstance();
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
        return true;
    }

    // /admin/menus - Visual Menu Builder
    if ($uri === '/admin/menus') {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        if (!zed_current_user_can('manage_menus')) {
            Router::setHandled(zed_render_forbidden());
            return true;
        }
        
        $db = Database::getInstance();
        
        // Handle form-based Save (POST)
        if ($request['method'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'] ?? null;
            $items = $_POST['items'] ?? '';
            
            $decoded = json_decode($items, true);
            if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                Router::redirect("/admin/menus?id={$id}&msg=error");
            }
            
            if ($id && is_numeric($id)) {
                $db->query(
                    "UPDATE zed_menus SET items = :items, updated_at = NOW() WHERE id = :id",
                    ['items' => $items, 'id' => $id]
                );
                Router::redirect("/admin/menus?id={$id}&msg=saved");
            }
        }
        
        $current_user = Auth::user();
        $current_page = 'menus';
        $page_title = 'Menu Builder';
        
        $menus = $db->query("SELECT * FROM zed_menus ORDER BY name ASC");
        
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
        
        $pages = $db->query(
            "SELECT id, title, slug FROM zed_content 
             WHERE type = 'page' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY title ASC"
        );
        
        $categories = $db->query("SELECT id, name, slug FROM zed_categories ORDER BY name ASC");
        
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
        return true;
    }
    
    return false;
}
