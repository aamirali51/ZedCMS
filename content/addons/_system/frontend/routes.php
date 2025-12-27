<?php
/**
 * Frontend Route Handler
 * 
 * The main route_request listener implementing the Frontend Controller Pattern.
 * This is the SINGLE SOURCE OF TRUTH for frontend routing.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

use Core\Event;
use Core\Router;
use Core\Auth;
use Core\Database;

// =============================================================================
// SINGLE SOURCE OF TRUTH - Frontend Controller Pattern
// =============================================================================
// 
// This route_request listener implements a clean "Controller" architecture:
// 1. THE BRAIN    - Identify what the user wants
// 2. THE FETCH    - Get raw data into $zed_query (Single Source of Truth)
// 3. THE PREPARE  - Standardize data into $post, $posts, $is_404, etc.
// 4. THE HANDOFF  - Determine which theme template to load
// 5. THE EXECUTE  - Include the template and exit
//
// Benefits:
// - Themes receive standardized global variables; no direct DB access needed
// - All routing logic is centralized here
// - Template selection follows a consistent hierarchy
// =============================================================================

Event::on('route_request', function (array $request): void {
    $uri = $request['uri'];
    
    // =========================================================================
    // API ROUTES - Handle API endpoints before frontend routing
    // =========================================================================
    
    // Contact Form Submission API
    if ($uri === '/api/submit-contact' && $request['method'] === 'POST') {
        header('Content-Type: application/json');
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? 'New Message');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($message)) {
            echo json_encode(['success' => false, 'error' => 'All fields are required.']);
            Router::setHandled('');
            return;
        }
        
        try {
            $db = Database::getInstance();
            $title = "Message from " . $name;
            $slug = 'msg-' . time() . '-' . mt_rand(1000, 9999);
            
            $data = [
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
            ];
            
            $db->query(
                "INSERT INTO zed_content (title, slug, type, data, author_id, created_at, updated_at) 
                 VALUES (:title, :slug, 'contact_message', :data, 0, NOW(), NOW())",
                [
                    'title' => $title,
                    'slug' => $slug,
                    'data' => json_encode($data)
                ]
            );
            
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            if ($isAjax || isset($_GET['json'])) {
                echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
            } else {
                $referrer = $_SERVER['HTTP_REFERER'] ?? '/';
                $referrer .= str_contains($referrer, '?') ? '&success=1' : '?success=1';
                header("Location: " . $referrer);
            }
            
        } catch (Exception $e) {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            if ($isAjax || isset($_GET['json'])) {
                echo json_encode(['success' => false, 'error' => 'Database error']);
            } else {
                die("Error saving message: " . $e->getMessage());
            }
        }
        
        Router::setHandled('');
        return;
    }
    
    // =========================================================================
    // ROUTE FILTERING - Skip admin routes and already-handled requests
    // =========================================================================
    
    if (str_starts_with($uri, '/admin')) {
        return; // Let admin handle these
    }
    
    if (Router::isHandled()) {
        return;
    }
    

    // =========================================================================
    // 1. THE BRAIN — Identify What User Wants
    // =========================================================================
    
    $slug = trim($uri, '/');
    $isHome = ($slug === '');
    
    // DEBUG: Log routing info
    file_put_contents(__DIR__ . '/../../../../debug_route_log.txt', 
        date('H:i:s') . " ROUTE: uri=$uri, slug=$slug\n", FILE_APPEND);
    
    // Get core settings
    $homepage_mode = zed_get_option('homepage_mode', 'latest_posts');
    $page_on_front = (int)zed_get_option('page_on_front', '0');
    $blog_slug = zed_get_option('blog_slug', 'blog');
    $posts_per_page = zed_get_posts_per_page();
    $page_num = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page_num - 1) * $posts_per_page;
    
    // =========================================================================
    // 2. THE FETCH — Get Raw Data into $zed_query (Single Source of Truth)
    // =========================================================================
    
    global $zed_query;
    $zed_query = [
        'type' => null,       // 'home', 'single', 'page', 'archive', 'preview', '404'
        'object' => null,     // Single post/page data
        'posts' => [],        // Array of posts for archives/home
        'post_type' => null,  // CPT slug if applicable
        'archive_title' => null,
        'pagination' => [
            'current_page' => $page_num,
            'per_page' => $posts_per_page,
            'total_posts' => 0,
            'total_pages' => 1,
        ],
    ];
    
    try {
        $db = Database::getInstance();
        
        // CASE: Homepage (/)
        if ($isHome) {
            if ($homepage_mode === 'static_page' && $page_on_front > 0) {
                $zed_query['object'] = zed_get_page_by_id($page_on_front);
                $zed_query['type'] = $zed_query['object'] ? 'page' : '404';
            } else {
                $zed_query['posts'] = zed_get_latest_posts($posts_per_page, $offset);
                $zed_query['pagination']['total_posts'] = zed_count_published_posts();
                $zed_query['pagination']['total_pages'] = max(1, ceil($zed_query['pagination']['total_posts'] / $posts_per_page));
                $zed_query['type'] = 'home';
                $zed_query['post_type'] = 'post';
            }
        }
        // CASE: Preview Route (/preview/{id})
        elseif (str_starts_with($slug, 'preview/')) {
            $id = (int)substr($slug, 8);
            
            if (!Auth::check()) {
                Router::redirect('/admin/login?redirect=' . urlencode($uri));
                return;
            }
            
            $zed_query['object'] = $db->queryOne(
                "SELECT * FROM zed_content WHERE id = :id LIMIT 1",
                ['id' => $id]
            );
            $zed_query['type'] = $zed_query['object'] ? 'preview' : '404';
        }
        // CASE: Archive or Single by Post Type
        else {
            $segments = array_values(array_filter(explode('/', $slug)));
            $firstSegment = $segments[0] ?? '';
            $secondSegment = $segments[1] ?? null;
            
            $postTypes = zed_get_post_types(true);
            $matchedType = null;
            $matchedTypeConfig = null;
            
            foreach ($postTypes as $typeSlug => $typeConfig) {
                if ($firstSegment === $typeSlug) {
                    $matchedType = $typeSlug;
                    $matchedTypeConfig = $typeConfig;
                    break;
                }
                $pluralSlug = strtolower(str_replace(' ', '-', $typeConfig['label'] ?? ''));
                if ($firstSegment === $pluralSlug) {
                    $matchedType = $typeSlug;
                    $matchedTypeConfig = $typeConfig;
                    break;
                }
            }
            
            // Special case: /blog always maps to 'post' type
            if ($firstSegment === 'blog' || ($homepage_mode === 'static_page' && $firstSegment === $blog_slug)) {
                $matchedType = 'post';
                $matchedTypeConfig = $postTypes['post'] ?? ['label' => 'Posts', 'singular' => 'Post'];
            }
            
            if ($matchedType !== null) {
                $zed_query['post_type'] = $matchedType;
                
                if ($secondSegment !== null) {
                    // Single item: /{type}/{slug}
                    $zed_query['object'] = $db->queryOne(
                        "SELECT * FROM zed_content 
                         WHERE slug = :slug AND type = :type
                           AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
                         LIMIT 1",
                        ['slug' => $secondSegment, 'type' => $matchedType]
                    );
                    $zed_query['type'] = $zed_query['object'] ? 'single' : '404';
                } else {
                    // Archive listing: /{type}
                    $zed_query['posts'] = $db->query(
                        "SELECT * FROM zed_content 
                         WHERE type = :type 
                           AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
                         ORDER BY created_at DESC
                         LIMIT :limit OFFSET :offset",
                        ['type' => $matchedType, 'limit' => $posts_per_page, 'offset' => $offset]
                    ) ?: [];
                    
                    $zed_query['pagination']['total_posts'] = (int)$db->queryValue(
                        "SELECT COUNT(*) FROM zed_content 
                         WHERE type = :type 
                           AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'",
                        ['type' => $matchedType]
                    );
                    $zed_query['pagination']['total_pages'] = max(1, ceil($zed_query['pagination']['total_posts'] / $posts_per_page));
                    $zed_query['archive_title'] = $matchedTypeConfig['label'] ?? ucfirst($matchedType) . 's';
                    $zed_query['type'] = 'archive';
                }
            } else {
                // CASE: Single Content by Slug (/{slug})
                error_log("FRONTEND ROUTE: Looking up slug: $slug");
                $zed_query['object'] = zed_get_post_by_slug($slug);
                error_log("FRONTEND ROUTE: Found object: " . ($zed_query['object'] ? 'YES id='.$zed_query['object']['id'] : 'NO'));
                
                if ($zed_query['object']) {
                    $objData = is_string($zed_query['object']['data'] ?? null) 
                        ? json_decode($zed_query['object']['data'], true) 
                        : ($zed_query['object']['data'] ?? []);
                    
                    $dbg = "Status=" . ($objData['status'] ?? 'MISSING');
                    
                    if (($objData['status'] ?? '') !== 'published') {
                        $zed_query['object'] = null;
                        $zed_query['type'] = '404';
                        $dbg .= " -> 404 (not published)";
                    } else {
                        $zed_query['type'] = ($zed_query['object']['type'] ?? 'post') === 'page' ? 'page' : 'single';
                        $zed_query['post_type'] = $zed_query['object']['type'] ?? 'post';
                        $dbg .= " -> type=" . $zed_query['type'];
                    }
                    file_put_contents(__DIR__ . '/../../../../debug_route_log.txt', 
                        date('H:i:s') . " $dbg\n", FILE_APPEND);
                } else {
                    $zed_query['type'] = '404';
                    file_put_contents(__DIR__ . '/../../../../debug_route_log.txt', 
                        date('H:i:s') . " -> 404 (object not found)\n", FILE_APPEND);
                }
            }
        }
        
    } catch (Exception $e) {
        error_log("Frontend routes error: " . $e->getMessage());
        $zed_query['type'] = '404';
    }
    
    // =========================================================================
    // 3. THE PREPARATION — Standardize Data for Themes
    // =========================================================================
    
    global $post, $posts, $is_404, $is_home, $is_archive, $is_single, $is_page;
    global $htmlContent, $base_url, $page_num, $total_pages, $total_posts;
    global $post_type, $post_type_label, $archive_title;
    global $beforeContent, $afterContent;
    
    $post = $zed_query['object'];
    $posts = $zed_query['posts'];
    $is_404 = ($zed_query['type'] === '404');
    $is_home = ($zed_query['type'] === 'home');
    $is_archive = ($zed_query['type'] === 'archive');
    $is_single = ($zed_query['type'] === 'single' || $zed_query['type'] === 'preview');
    $is_page = ($zed_query['type'] === 'page');
    
    $page_num = $zed_query['pagination']['current_page'];
    $total_pages = $zed_query['pagination']['total_pages'];
    $total_posts = $zed_query['pagination']['total_posts'];
    
    $post_type = $zed_query['post_type'];
    $archive_title = $zed_query['archive_title'];
    $post_type_config = $post_type ? (zed_get_post_type($post_type) ?? []) : [];
    $post_type_label = $post_type_config['label'] ?? ucfirst($post_type ?? 'Posts');
    
    $base_url = Router::getBasePath();
    
    // Process content for single items
    $htmlContent = '';
    $data = [];
    
    if ($post) {
        $data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
        $content = $data['content'] ?? null;
        
        // Detect content format and render appropriately
        if (is_string($content) && !empty($content)) {
            // NEW: TipTap content is already HTML
            $htmlContent = $content;
        } elseif (is_array($content) && !empty($content)) {
            // LEGACY: BlockNote JSON needs conversion
            $htmlContent = render_blocks($content);
        }
        
        if (function_exists('zed_do_shortcodes')) {
            $htmlContent = zed_do_shortcodes($htmlContent);
        }
        
        ob_start();
        Event::trigger('zed_before_content', $post, $data);
        $beforeContent = ob_get_clean();
        
        ob_start();
        Event::trigger('zed_after_content', $post, $data);
        $afterContent = ob_get_clean();
    }
    
    // Populate Context Registry (modern alternative to globals)
    // Themes can use: zed_context()->post('title') instead of global $post
    if (function_exists('zed_context')) {
        zed_context()->setMany([
            'post' => $post,
            'posts' => $posts,
            'htmlContent' => $htmlContent,
            'is_home' => $is_home,
            'is_single' => $is_single,
            'is_page' => $is_page,
            'is_archive' => $is_archive,
            'is_404' => $is_404,
            'is_blog' => ($zed_query['type'] === 'archive' && $post_type === 'post'),
            'post_type' => $post_type,
            'post_type_label' => $post_type_label,
            'archive_title' => $archive_title,
            'current_page' => $page_num,
            'total_pages' => $total_pages,
            'total_posts' => $total_posts,
            'beforeContent' => $beforeContent ?? '',
            'afterContent' => $afterContent ?? '',
        ]);
    }
    
    // =========================================================================
    // 4. THE HANDOFF — Determine Which Template to Load
    // =========================================================================
    
    $theme = zed_get_option('active_theme', 'starter-theme');
    $themePath = dirname(__DIR__, 3) . '/themes/' . $theme;
    
    if (!is_dir($themePath)) {
        $theme = 'starter-theme';
        $themePath = dirname(__DIR__, 3) . '/themes/' . $theme;
    }
    
    if (!defined('ZED_ACTIVE_THEME')) {
        define('ZED_ACTIVE_THEME', $theme);
    }
    
    error_log("FRONTEND ROUTE: Theme=$theme, ThemePath=$themePath, QueryType=" . $zed_query['type']);
    
    $template = 'index.php';
    
    switch ($zed_query['type']) {
        case '404':
            $template = file_exists("$themePath/404.php") ? '404.php' : 'index.php';
            break;
            
        case 'home':
            $template = file_exists("$themePath/home.php") ? 'home.php' : 'index.php';
            break;
            
        case 'page':
            $customTemplate = $data['template'] ?? 'default';
            if ($customTemplate !== 'default' && file_exists("$themePath/templates/{$customTemplate}.php")) {
                $template = "templates/{$customTemplate}.php";
            } elseif (file_exists("$themePath/page.php")) {
                $template = 'page.php';
            } elseif (file_exists("$themePath/single.php")) {
                $template = 'single.php';
            }
            break;
            
        case 'single':
        case 'preview':
            $customTemplate = $data['template'] ?? 'default';
            
            if ($customTemplate !== 'default') {
                $addonTemplate = Event::filter('zed_resolve_template', null, $customTemplate, $post);
                
                if ($addonTemplate && file_exists($addonTemplate)) {
                    $template = $addonTemplate;
                } elseif (file_exists("$themePath/templates/{$customTemplate}.php")) {
                    $template = "templates/{$customTemplate}.php";
                }
            }
            
            if ($template === 'index.php') {
                $template = zed_resolve_template_hierarchy($themePath, 'single', $post_type ?? 'post');
                $template = str_replace($themePath . '/', '', $template);
            }
            break;
            
        case 'archive':
            $resolved = zed_resolve_template_hierarchy($themePath, 'archive', $post_type ?? 'post');
            $template = str_replace($themePath . '/', '', $resolved);
            break;
    }
    
    $templatePath = str_starts_with($template, '/') ? $template : "$themePath/$template";
    
    if (!file_exists($templatePath)) {
        $templatePath = "$themePath/index.php";
    }
    
    // =========================================================================
    // 5. EXECUTE — Load Template and Exit
    // =========================================================================
    
    if (file_exists($templatePath)) {
        ob_start();
        include $templatePath;
        $html = ob_get_clean();
        Router::setHandled($html);
    } else {
        // Absolute last resort: Render basic fallback HTML
        $title = htmlspecialchars($post['title'] ?? 'Zed CMS');
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} — Zed CMS</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; line-height: 1.6; }
        h1 { border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .content img { max-width: 100%; }
        footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; color: #666; }
    </style>
</head>
<body>
    <h1>{$title}</h1>
    <div class="content">{$htmlContent}</div>
    <footer>Powered by Zed CMS</footer>
</body>
</html>
HTML;
        Router::setHandled($html);
    }
    
}, 100); // Priority 100 = runs AFTER admin (priority 10)
