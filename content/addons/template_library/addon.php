<?php
/**
 * Addon Name: Template Library
 * Description: Pre-built page templates (Contact, Landing, FAQ, Pricing, etc.) that work with any theme.
 * Version: 1.0.0
 * Author: Zed CMS
 * License: MIT
 * 
 * Provides a collection of professional page templates that automatically adopt
 * the active theme's styling through the Theme Parts API.
 * 
 * Features:
 * - Adds "Template Library" to admin sidebar
 * - Visual showcase of available templates
 * - One-click template insertion (creates new page)
 * - Dynamic theme styling adoption
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;
use Core\Database;
use Core\Auth;

// =============================================================================
// ADDON REGISTRATION
// =============================================================================

/**
 * Template definitions with metadata
 */
function zed_get_template_library(): array
{
    return [
        'contact' => [
            'name' => 'Contact Form',
            'description' => 'Professional contact page with styled form, subject dropdown, and thank-you message.',
            'category' => 'Forms',
            'icon' => 'mail',
            'preview' => 'contact.png',
        ],
        'landing' => [
            'name' => 'Landing Page',
            'description' => 'Full-width hero section with call-to-action buttons. Perfect for marketing pages.',
            'category' => 'Marketing',
            'icon' => 'rocket_launch',
            'preview' => 'landing.png',
        ],
        'about' => [
            'name' => 'About Page',
            'description' => 'Clean about page with team section and company story layout.',
            'category' => 'Company',
            'icon' => 'groups',
            'preview' => 'about.png',
        ],
        'services' => [
            'name' => 'Services Grid',
            'description' => 'Showcase your services with icon cards in a responsive grid layout.',
            'category' => 'Business',
            'icon' => 'grid_view',
            'preview' => 'services.png',
        ],
        'faq' => [
            'name' => 'FAQ Page',
            'description' => 'Frequently asked questions with expandable accordion sections.',
            'category' => 'Support',
            'icon' => 'help',
            'preview' => 'faq.png',
        ],
        'pricing' => [
            'name' => 'Pricing Table',
            'description' => 'Beautiful pricing cards with feature comparison and CTA buttons.',
            'category' => 'Business',
            'icon' => 'payments',
            'preview' => 'pricing.png',
        ],
    ];
}

/**
 * Get the path to the template library addon
 */
function zed_get_template_library_path(): string
{
    return dirname(__FILE__);
}

/**
 * Register templates with the CMS template system
 * This makes templates available in the editor's template dropdown
 */
Event::on('app_ready', function(): void {
    global $ZED_PAGE_TEMPLATES;
    if (!isset($ZED_PAGE_TEMPLATES)) {
        $ZED_PAGE_TEMPLATES = [];
    }
    
    $templates = zed_get_template_library();
    foreach ($templates as $slug => $data) {
        $ZED_PAGE_TEMPLATES[$slug] = [
            'name' => $data['name'],
            'file' => zed_get_template_library_path() . '/templates/' . $slug . '.php',
            'source' => 'template_library',
        ];
    }
}, 10);

// =============================================================================
// ADMIN SIDEBAR MENU
// =============================================================================

/**
 * Add Template Library to admin sidebar
 */
Event::on('zed_admin_menu', function(array $items): array {
    $base = \Core\Router::getBasePath();
    
    // Add Template Library menu item
    $items[] = [
        'id' => 'template_library',
        'label' => 'Template Library',
        'icon' => 'auto_awesome_mosaic',
        'url' => $base . '/admin/template-library',
        'position' => 35, // After Content, before Categories
    ];
    
    return $items;
}, 20);

// =============================================================================
// ADMIN ROUTES (Event-based routing)
// =============================================================================

Event::on('route_request', function(array $request): void {
    $uri = $request['uri'];
    $method = $request['method'];
    
    // ─────────────────────────────────────────────────────────────────────
    // GET /admin/template-library - Template showcase page
    // ─────────────────────────────────────────────────────────────────────
    if ($uri === '/admin/template-library' && $method === 'GET') {
        if (!Auth::check()) {
            Router::redirect('/admin/login');
        }
        
        ob_start();
        include zed_get_template_library_path() . '/pages/showcase.php';
        $content = ob_get_clean();
        Router::setHandled($content);
        return;
    }
    
    // ─────────────────────────────────────────────────────────────────────
    // POST /admin/template-library/insert - Insert template (create page)
    // ─────────────────────────────────────────────────────────────────────
    if ($uri === '/admin/template-library/insert' && $method === 'POST') {
        // Suppress any PHP warnings/notices from corrupting JSON
        @ob_clean();
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            Router::setHandled('');
            return;
        }
        
        $templateSlug = $_POST['template'] ?? '';
        $templates = zed_get_template_library();
        
        if (!isset($templates[$templateSlug])) {
            echo json_encode(['error' => 'Template not found: ' . $templateSlug]);
            Router::setHandled('');
            return;
        }
        
        $template = $templates[$templateSlug];
        
        try {
            $db = Database::getInstance();
            
            // Create unique slug
            $baseSlug = strtolower(str_replace(' ', '-', $template['name']));
            $slug = $baseSlug;
            $counter = 1;
            
            while ($db->queryOne("SELECT id FROM zed_content WHERE slug = :slug", ['slug' => $slug])) {
                $slug = $baseSlug . '-' . $counter++;
            }
            
            // Get author ID safely
            $authorId = (int)(Auth::id() ?? 1);
            
            // Create new page with template using insert() which returns the ID
            $pageId = $db->insert('zed_content', [
                'title' => $template['name'],
                'slug' => $slug,
                'type' => 'page',
                'data' => [
                    'status' => 'draft',
                    'template' => $templateSlug,
                    'content' => [],
                    'excerpt' => $template['description'],
                ],
                'author_id' => $authorId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            
            echo json_encode([
                'success' => true,
                'page_id' => $pageId,
                'redirect' => Router::getBasePath() . '/admin/editor?id=' . $pageId,
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['error' => 'Failed to create page: ' . $e->getMessage()]);
        }
        
        Router::setHandled('');
        return;
    }
    
}, 50); // Priority 50 = after admin_addon

// =============================================================================
// TEMPLATE LOADER HOOK
// =============================================================================

/**
 * Hook into template resolution to provide Template Library templates
 * 
 * This filter receives the current template path (or null), template slug, and post data.
 * Returns the template file path if we have that template, otherwise returns unchanged.
 */
Event::on('zed_resolve_template', function(?string $templateFile, string $templateSlug, array $post): ?string {
    // Check if this template is from the library
    $templates = zed_get_template_library();
    
    if (isset($templates[$templateSlug])) {
        $libraryFile = zed_get_template_library_path() . '/templates/' . $templateSlug . '.php';
        if (file_exists($libraryFile)) {
            return $libraryFile;
        }
    }
    
    // Return unchanged if not our template
    return $templateFile;
}, 20);
