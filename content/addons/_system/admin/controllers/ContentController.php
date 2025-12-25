<?php
/**
 * Content Controller
 * 
 * Handles all content-related routes (posts, pages).
 * Provides list, edit, and save functionality.
 * 
 * @package ZedCMS\Admin\Controllers
 * @since 3.2.0
 */

declare(strict_types=1);

namespace Admin\Controllers;

use Core\Database;
use Core\Event;

/**
 * ContentController - Manage posts and pages
 * 
 * Routes:
 * - GET  /admin/content       - List posts/pages
 * - GET  /admin/content/edit  - Edit post/page
 * - POST /admin/api/content/save - Save post/page
 */
final class ContentController extends BaseController
{
    /**
     * List posts/pages
     * 
     * Route: GET /admin/content
     * Capability: edit_posts
     * 
     * @return void
     */
    public function index(): void
    {
        $type = $_GET['type'] ?? 'post';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Fetch content (showing all content, not filtered by type)
        $content = $this->db()->query(
            "SELECT * FROM zed_content 
             ORDER BY created_at DESC 
             LIMIT :limit OFFSET :offset",
            [
                'limit' => $perPage,
                'offset' => $offset
            ]
        );
        
        // Parse JSON data for each item
        foreach ($content as &$item) {
            if (isset($item['data']) && is_string($item['data'])) {
                $item['data'] = json_decode($item['data'], true) ?? [];
            }
        }
        
        // Get total count
        $total = (int)$this->db()->queryValue(
            "SELECT COUNT(*) FROM zed_content"
        );
        
        $totalPages = (int)ceil($total / $perPage);
        
        // Trigger event for extensibility
        Event::trigger('admin_content_list', [
            'type' => $type,
            'content' => &$content
        ]);
        
        // Calculate pagination display values
        $showingFrom = $total > 0 ? $offset + 1 : 0;
        $showingTo = min($offset + $perPage, $total);
        
        // Render using admin layout with content partial
        // The partial expects these specific variable names
        $this->render('content-list-content', [
            'posts' => $content,  // Partial expects $posts
            'type' => $type,
            'page' => $page,
            'perPage' => $perPage,
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? '',
            'msg' => $_GET['msg'] ?? '',
            'totalPosts' => $total,
            'totalPages' => $totalPages,
            'showingFrom' => $showingFrom,
            'showingTo' => $showingTo
        ]);
    }
    
    /**
     * Edit post/page
     * 
     * Route: GET /admin/content/edit
     * Capability: edit_posts
     * 
     * @return void
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $type = $_GET['type'] ?? 'post';
        
        $content = null;
        
        if ($id > 0) {
            // Load existing content
            $content = $this->db()->queryOne(
                "SELECT * FROM zed_content WHERE id = :id",
                ['id' => $id]
            );
            
            if (!$content) {
                $this->redirect('/admin/content?error=not_found&type=' . $type);
                return;
            }
            
            // Parse JSON data
            if (isset($content['data']) && is_string($content['data'])) {
                $content['data'] = json_decode($content['data'], true) ?? [];
            }
            
            // Check ownership for non-admins
            if (!$this->can('edit_others_posts')) {
                if ((int)$content['author_id'] !== $this->currentUserId()) {
                    $this->forbidden();
                    return;
                }
            }
        }
        
        // Trigger event for extensibility
        Event::trigger('admin_content_edit', [
            'content' => &$content,
            'type' => $type
        ]);
        
        // Pass variables to the view
        extract([
            'content' => $content,
            'type' => $type,
            'isNew' => $id === 0,
            'data' => isset($content['data']) ? (is_array($content['data']) ? $content['data'] : json_decode($content['data'], true)) : []
        ]);
        
        // Editor is a standalone page in the theme, not a partial
        // We must load it directly without AdminRenderer
        $themePath = function_exists('zed_get_admin_theme_path') 
            ? zed_get_admin_theme_path() 
            : dirname(dirname(dirname(dirname(__DIR__)))) . '/themes/admin-default';
            
        require $themePath . '/editor.php';
    }
    
    /**
     * Save post/page (API)
     * 
     * Route: POST /admin/api/content/save
     * Capability: edit_posts
     * 
     * @return void
     */
    public function save(): void
    {
        $input = $this->getJsonInput();
        
        // Validate required fields
        if (!$this->validateRequired($input, ['title', 'type'])) {
            $this->error('Missing required fields: title, type');
            return;
        }
        
        $id = (int)($input['id'] ?? 0);
        $title = trim($input['title']);
        $slug = $input['slug'] ?? '';
        $type = $input['type'] ?? 'post';
        $data = $input['data'] ?? [];
        
        // Auto-generate slug if empty
        if (empty($slug)) {
            $slug = $this->generateSlug($title);
        }
        
        // Ensure slug is unique
        $slug = $this->ensureUniqueSlug($slug, $id);
        
        // Merge top-level fields into data object
        $topLevelFields = ['status', 'featured_image', 'excerpt', 'category', 'tags'];
        foreach ($topLevelFields as $field) {
            if (isset($input[$field])) {
                $data[$field] = $input[$field];
            }
        }
        
        // Encode data as JSON
        $dataJson = json_encode($data);
        
        // Trigger before save event
        Event::trigger('admin_content_before_save', [
            'id' => $id,
            'title' => &$title,
            'slug' => &$slug,
            'type' => $type,
            'data' => &$data
        ]);
        
        if ($id > 0) {
            // Update existing content
            
            // Check ownership for non-admins
            if (!$this->can('edit_others_posts')) {
                $existing = $this->db()->queryOne(
                    "SELECT author_id FROM zed_content WHERE id = :id",
                    ['id' => $id]
                );
                
                if ($existing && (int)$existing['author_id'] !== $this->currentUserId()) {
                    $this->error('You do not have permission to edit this content', 403);
                    return;
                }
            }
            
            $this->db()->query(
                "UPDATE zed_content 
                 SET title = :title, slug = :slug, data = :data, updated_at = NOW()
                 WHERE id = :id",
                [
                    'id' => $id,
                    'title' => $title,
                    'slug' => $slug,
                    'data' => $dataJson
                ]
            );
            
            $message = ucfirst($type) . ' updated successfully';
        } else {
            // Insert new content
            $this->db()->query(
                "INSERT INTO zed_content (title, slug, type, data, author_id, created_at, updated_at)
                 VALUES (:title, :slug, :type, :data, :author, NOW(), NOW())",
                [
                    'title' => $title,
                    'slug' => $slug,
                    'type' => $type,
                    'data' => $dataJson,
                    'author' => $this->currentUserId()
                ]
            );
            
            $id = (int)$this->db()->lastInsertId();
            $message = ucfirst($type) . ' created successfully';
        }
        
        // Trigger after save event
        Event::trigger('admin_content_after_save', [
            'id' => $id,
            'type' => $type
        ]);
        
        $this->success([
            'id' => $id,
            'slug' => $slug
        ], $message);
    }
    
    /**
     * Generate slug from title
     * 
     * @param string $title Title to convert
     * @return string Generated slug
     */
    private function generateSlug(string $title): string
    {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
    
    /**
     * Ensure slug is unique
     * 
     * @param string $slug Base slug
     * @param int $excludeId ID to exclude from check
     * @return string Unique slug
     */
    private function ensureUniqueSlug(string $slug, int $excludeId = 0): string
    {
        $originalSlug = $slug;
        $counter = 1;
        
        while (true) {
            $exists = $this->db()->queryValue(
                "SELECT COUNT(*) FROM zed_content WHERE slug = :slug AND id != :id",
                ['slug' => $slug, 'id' => $excludeId]
            );
            
            if ((int)$exists === 0) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
