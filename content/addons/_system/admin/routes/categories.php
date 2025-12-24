<?php
/**
 * Admin Routes - Categories
 * 
 * Handles category management routes.
 * 
 * @package ZedCMS\Admin\Routes
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * Handle categories routes: /admin/categories, /admin/categories/create, /admin/categories/delete
 */
function zed_handle_categories_routes(array $request, string $uri, string $themePath): bool
{
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
                Router::redirect('/admin/categories?msg=error');
            }
        }
        return true;
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
        return true;
    }

    // /admin/categories/delete - Delete Category
    if (str_starts_with($uri, '/admin/categories/delete')) {
        if (!zed_user_can_access_admin()) {
            Router::redirect('/admin/login');
        }
        
        $id = $_GET['id'] ?? null;
        if ($id && is_numeric($id)) {
            $db = Database::getInstance();
            if ($id != 1) {
                $db->query("DELETE FROM zed_categories WHERE id = :id", ['id' => $id]);
                Router::redirect('/admin/categories?msg=deleted');
            } else {
                Router::redirect('/admin/categories?msg=locked');
            }
        }
        Router::redirect('/admin/categories');
        return true;
    }
    
    return false;
}
