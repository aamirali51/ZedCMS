<?php

declare(strict_types=1);

/**
 * Frontend Addon - Public Content Viewer
 * 
 * This addon handles public-facing routes for viewing content.
 * It matches slugs from the database and renders BlockNote JSON as HTML.
 * 
 * Routes:
 * - /{slug} -> View published content by slug
 * - /preview/{id} -> Preview content by ID (requires auth)
 */

use Core\Event;
use Core\Router;
use Core\Auth;
use Core\Database;

/**
 * Normalize a block to ensure it has all required keys with default values
 * Prevents errors when accessing missing properties
 *
 * @param mixed $block Raw block data
 * @return array Normalized block
 */
function normalize_block(mixed $block): array
{
    if (!is_array($block)) {
        return [
            'id' => uniqid(),
            'type' => 'paragraph',
            'props' => [],
            'content' => [],
            'children' => []
        ];
    }
    
    return [
        'id' => $block['id'] ?? uniqid(),
        'type' => $block['type'] ?? 'paragraph',
        'props' => is_array($block['props'] ?? null) ? $block['props'] : [],
        // Content default depends on usage, but empty array is safest generic default
        'content' => $block['content'] ?? [], 
        'children' => is_array($block['children'] ?? null) ? $block['children'] : []
    ];
}

/**
 * BlockNote JSON to HTML Renderer
 * 
 * Converts BlockNote block format to valid HTML.
 * Supports: paragraph, heading, bulletListItem, numberedListItem, image, code
 *
 * @param array|string $blocks BlockNote blocks array or JSON string
 * @return string Rendered HTML
 */
function render_blocks(array|string $blocks): string
{
    // Parse if JSON string
    if (is_string($blocks)) {
        $blocks = json_decode($blocks, true);
        if (!is_array($blocks)) {
            return '';
        }
    }

    $html = '';
    $listStack = []; // Track open list types
    
    foreach ($blocks as $rawBlock) {
        // Normalize block data to prevent crashes
        $block = normalize_block($rawBlock);
        
        $type = $block['type'];
        $content = $block['content'];
        $props = $block['props'];
        
        // Close list if type changes
        if (!empty($listStack) && !in_array($type, ['bulletListItem', 'numberedListItem'])) {
            while (!empty($listStack)) {
                $closingTag = array_pop($listStack);
                $html .= "</{$closingTag}>\n";
            }
        }
        
        // Render inline content (text, links, etc.)
        // Note: Table content is structured differently, handled in render_table
        $innerHtml = $type !== 'table' ? render_inline_content($content) : '';
        
        switch ($type) {
            case 'paragraph':
                if (!empty($innerHtml)) {
                    $textAlign = $props['textAlignment'] ?? 'left';
                    $alignStyle = $textAlign !== 'left' ? " style=\"text-align: {$textAlign};\"" : '';
                    $html .= "<p{$alignStyle}>{$innerHtml}</p>\n";
                }
                break;
                
            case 'heading':
                $level = min(6, max(1, (int)($props['level'] ?? 2)));
                $textAlign = $props['textAlignment'] ?? 'left';
                $alignStyle = $textAlign !== 'left' ? " style=\"text-align: {$textAlign};\"" : '';
                $html .= "<h{$level}{$alignStyle}>{$innerHtml}</h{$level}>\n";
                break;
                
            case 'bulletListItem':
                // Open <ul> if not already in one
                if (empty($listStack) || end($listStack) !== 'ul') {
                    if (!empty($listStack) && end($listStack) === 'ol') {
                        $html .= "</ol>\n";
                        array_pop($listStack);
                    }
                    $html .= "<ul>\n";
                    $listStack[] = 'ul';
                }
                $html .= "<li>{$innerHtml}</li>\n";
                break;
                
            case 'numberedListItem':
                // Open <ol> if not already in one
                if (empty($listStack) || end($listStack) !== 'ol') {
                    if (!empty($listStack) && end($listStack) === 'ul') {
                        $html .= "</ul>\n";
                        array_pop($listStack);
                    }
                    $html .= "<ol>\n";
                    $listStack[] = 'ol';
                }
                $html .= "<li>{$innerHtml}</li>\n";
                break;
                
            case 'image':
                // Safe accessor for URL (check 'url' then 'src')
                $url = htmlspecialchars($props['url'] ?? $props['src'] ?? '');
                $alt = htmlspecialchars($props['caption'] ?? $props['name'] ?? 'Image');
                $width = $props['width'] ?? 'auto';
                if ($url) {
                    $html .= "<figure class=\"content-image\">\n";
                    $html .= "  <img src=\"{$url}\" alt=\"{$alt}\" style=\"max-width: 100%; width: {$width};\" loading=\"lazy\">\n";
                    if (!empty($props['caption'])) {
                        $html .= "  <figcaption>" . htmlspecialchars($props['caption']) . "</figcaption>\n";
                    }
                    $html .= "</figure>\n";
                }
                break;
                
            case 'codeBlock':
                $language = htmlspecialchars($props['language'] ?? 'plaintext');
                $code = htmlspecialchars(render_inline_content($content, true));
                $html .= "<pre><code class=\"language-{$language}\">{$code}</code></pre>\n";
                break;
                
            case 'table':
                $html .= render_table($block);
                break;
                
            case 'video':
                $url = htmlspecialchars($props['url'] ?? '');
                if ($url) {
                    $html .= "<div class=\"video-wrapper\">\n";
                    $html .= "  <video src=\"{$url}\" controls style=\"max-width: 100%;\"></video>\n";
                    $html .= "</div>\n";
                }
                break;
                
            case 'audio':
                $url = htmlspecialchars($props['url'] ?? '');
                if ($url) {
                    $html .= "<audio src=\"{$url}\" controls></audio>\n";
                }
                break;
                
            case 'file':
                $url = htmlspecialchars($props['url'] ?? '');
                $name = htmlspecialchars($props['name'] ?? 'Download File');
                if ($url) {
                    $html .= "<a href=\"{$url}\" class=\"file-download\" download>{$name}</a>\n";
                }
                break;
                
            default:
                // SAFETY: Handle unknown block types gracefully
                // Output hidden HTML comment for debugging (won't crash the site)
                $safeType = htmlspecialchars($type);
                $html .= "<!-- Zed CMS: Unknown block type '{$safeType}' -->\n";
                
                // Still try to render any text content it might have
                if (!empty($innerHtml)) {
                    $html .= "<div class=\"unknown-block\">{$innerHtml}</div>\n";
                }
                break;
        }
    }
    
    // Close any remaining open lists
    while (!empty($listStack)) {
        $closingTag = array_pop($listStack);
        $html .= "</{$closingTag}>\n";
    }
    
    return $html;
}

/**
 * Render inline content (text with formatting)
 *
 * @param array $content Array of inline content nodes
 * @param bool $plainText If true, strip all formatting
 * @return string Rendered HTML or plain text
 */
function render_inline_content(array $content, bool $plainText = false): string
{
    $result = '';
    
    foreach ($content as $node) {
        $text = $node['text'] ?? '';
        $styles = $node['styles'] ?? [];
        
        if ($plainText) {
            $result .= $text;
            continue;
        }
        
        // Escape HTML in text
        $html = htmlspecialchars($text);
        
        // Apply styles (order matters for proper nesting)
        if (!empty($styles['code'])) {
            $html = "<code>{$html}</code>";
        }
        if (!empty($styles['bold'])) {
            $html = "<strong>{$html}</strong>";
        }
        if (!empty($styles['italic'])) {
            $html = "<em>{$html}</em>";
        }
        if (!empty($styles['underline'])) {
            $html = "<u>{$html}</u>";
        }
        if (!empty($styles['strike'])) {
            $html = "<s>{$html}</s>";
        }
        if (!empty($styles['textColor'])) {
            $color = htmlspecialchars($styles['textColor']);
            $html = "<span style=\"color: {$color};\">{$html}</span>";
        }
        if (!empty($styles['backgroundColor'])) {
            $bg = htmlspecialchars($styles['backgroundColor']);
            $html = "<span style=\"background-color: {$bg};\">{$html}</span>";
        }
        
        // Handle links
        if ($node['type'] === 'link' && !empty($node['href'])) {
            $href = htmlspecialchars($node['href']);
            $linkContent = render_inline_content($node['content'] ?? [], $plainText);
            $html = "<a href=\"{$href}\">{$linkContent}</a>";
        }
        
        $result .= $html;
    }
    
    return $result;
}

/**
 * Render a table block
 *
 * @param array $block Table block data
 * @return string HTML table
 */
function render_table(array $block): string
{
    $content = $block['content'] ?? [];
    if (empty($content) || !isset($content['rows'])) {
        return '';
    }
    
    $html = "<table class=\"content-table\">\n";
    
    foreach ($content['rows'] as $rowIndex => $row) {
        $html .= "<tr>\n";
        $cells = $row['cells'] ?? [];
        foreach ($cells as $cell) {
            $tag = $rowIndex === 0 ? 'th' : 'td';
            $cellContent = render_inline_content($cell ?? []);
            $html .= "  <{$tag}>{$cellContent}</{$tag}>\n";
        }
        $html .= "</tr>\n";
    }
    
    $html .= "</table>\n";
    return $html;
}

/**
 * Get all menus from the database
 * 
 * @return array Array of all menus
 */
function zed_get_all_menus(): array
{
    try {
        $db = Database::getInstance();
        return $db->query("SELECT * FROM zed_menus ORDER BY name ASC") ?: [];
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get a menu by ID
 * 
 * @param int $id Menu ID
 * @return array|null Menu data or null if not found
 */
function zed_get_menu_by_id(int $id): ?array
{
    try {
        $db = Database::getInstance();
        $menu = $db->queryOne("SELECT * FROM zed_menus WHERE id = :id", ['id' => $id]);
        if ($menu && !empty($menu['items'])) {
            $menu['items'] = json_decode($menu['items'], true) ?: [];
        }
        return $menu ?: null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get a menu by name
 * 
 * @param string $name Menu name (case-insensitive)
 * @return array|null Menu data or null if not found
 */
function zed_get_menu_by_name(string $name): ?array
{
    try {
        $db = Database::getInstance();
        $menu = $db->queryOne("SELECT * FROM zed_menus WHERE LOWER(name) = LOWER(:name)", ['name' => $name]);
        if ($menu && !empty($menu['items'])) {
            $menu['items'] = json_decode($menu['items'], true) ?: [];
        }
        return $menu ?: null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Render a navigation menu by location slug (via nav_menu_locations option)
 * OR by menu name directly.
 * 
 * Usage in themes:
 *   echo render_menu('header');       // By location (requires nav_menu_locations option)
 *   echo zed_menu('Main Menu');       // By menu name (direct)
 *   echo zed_menu(1);                 // By menu ID (direct)
 * 
 * @param string $locationSlug The menu location identifier (e.g. 'header')
 * @return string HTML unordered list or empty string if not found
 */
function render_menu(string $locationSlug): string
{
    try {
        $db = Database::getInstance();
        
        // 1. Get the Menu Location Mapping
        $option = $db->queryOne("SELECT option_value FROM zed_options WHERE option_name = 'nav_menu_locations'");
        
        if (!$option || empty($option['option_value'])) {
             return '';
        }
        
        $locations = json_decode($option['option_value'], true);
        if (!isset($locations[$locationSlug])) {
            return '';
        }
        
        $menuId = $locations[$locationSlug];
        
        return zed_menu($menuId);
        
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Render a navigation menu by ID or name - THE MAIN HELPER FOR THEMES
 * 
 * Usage in any theme:
 *   <?= zed_menu('Main Menu') ?>       // By name
 *   <?= zed_menu(1) ?>                 // By ID
 *   <?= zed_menu('Main Menu', ['class' => 'nav-menu']) ?>  // With options
 * 
 * @param int|string $menuIdOrName Menu ID (int) or menu name (string)
 * @param array $options Optional: ['class' => 'custom-class', 'id' => 'nav-id']
 * @return string HTML unordered list or empty string if not found
 */
function zed_menu(int|string $menuIdOrName, array $options = []): string
{
    try {
        // Fetch menu by ID or name
        if (is_int($menuIdOrName)) {
            $menu = zed_get_menu_by_id($menuIdOrName);
        } else {
            $menu = zed_get_menu_by_name($menuIdOrName);
        }
        
        if (!$menu || empty($menu['items'])) {
            return '';
        }
        
        $items = is_array($menu['items']) ? $menu['items'] : [];
        
        if (empty($items)) {
            return '';
        }
        
        // Build attributes
        $menuSlug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $menu['name']));
        $classes = ['zed-menu', "zed-menu-{$menuSlug}"];
        if (!empty($options['class'])) {
            $classes[] = $options['class'];
        }
        $classAttr = implode(' ', $classes);
        $idAttr = !empty($options['id']) ? ' id="' . htmlspecialchars($options['id']) . '"' : '';
        
        // Recursive render function
        $renderItems = function(array $items, int $depth = 0) use (&$renderItems) {
            $html = '';
            $base_url = Router::getBasePath();
            
            foreach ($items as $item) {
                $label = htmlspecialchars($item['label'] ?? '');
                $url = $item['url'] ?? '#';
                $target = htmlspecialchars($item['target'] ?? '_self');
                $children = $item['children'] ?? [];
                
                // Make relative URLs use base path
                if (!str_starts_with($url, 'http') && !str_starts_with($url, '#')) {
                    if (!str_starts_with($url, '/')) {
                        $url = '/' . $url;
                    }
                    if (!str_starts_with($url, $base_url)) {
                        $url = $base_url . $url;
                    }
                }
                $url = htmlspecialchars($url);
                
                $hasChildren = !empty($children);
                $liClass = $hasChildren ? ' class="has-children"' : '';
                
                $html .= "<li{$liClass}>";
                $html .= "<a href=\"{$url}\"" . ($target !== '_self' ? " target=\"{$target}\"" : "") . ">{$label}</a>";
                
                if ($hasChildren) {
                    $html .= '<ul class="sub-menu">';
                    $html .= $renderItems($children, $depth + 1);
                    $html .= '</ul>';
                }
                
                $html .= "</li>\n";
            }
            return $html;
        };
        
        return "<ul class=\"{$classAttr}\"{$idAttr}>\n" . $renderItems($items) . "</ul>";
        
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Get the first available menu (useful for themes that just need "a" menu)
 * 
 * @return array|null First menu or null
 */
function zed_get_primary_menu(): ?array
{
    $menus = zed_get_all_menus();
    return $menus[0] ?? null;
}

/**
 * Render the first available menu (auto-detect)
 * 
 * @param array $options Optional styling options
 * @return string HTML menu
 */
function zed_primary_menu(array $options = []): string
{
    $menu = zed_get_primary_menu();
    if ($menu) {
        return zed_menu((int)$menu['id'], $options);
    }
    return '';
}

// =============================================================================
// OPTIONS HELPER - Cached database lookups for settings
// =============================================================================

/**
 * Get a site option from zed_options table
 * Results are cached in a static variable to prevent repeated DB queries.
 *
 * @param string $name Option name
 * @param mixed $default Default value if option not found
 * @return mixed Option value or default
 */
function zed_get_option(string $name, mixed $default = ''): mixed
{
    static $optionsCache = null;
    
    // Load all options on first call (single query)
    if ($optionsCache === null) {
        $optionsCache = [];
        try {
            $db = Database::getInstance();
            $rows = $db->query("SELECT option_name, option_value FROM zed_options WHERE autoload = 1");
            foreach ($rows as $row) {
                $optionsCache[$row['option_name']] = $row['option_value'];
            }
        } catch (Exception $e) {
            // Silently fail - use defaults
        }
    }
    
    // Return cached value or fetch individually if not autoloaded
    if (isset($optionsCache[$name])) {
        return $optionsCache[$name];
    }
    
    // Not in cache - try individual lookup (for non-autoload options)
    try {
        $db = Database::getInstance();
        $result = $db->queryOne(
            "SELECT option_value FROM zed_options WHERE option_name = :name",
            ['name' => $name]
        );
        if ($result) {
            $optionsCache[$name] = $result['option_value'];
            return $result['option_value'];
        }
    } catch (Exception $e) {
        // Silently fail
    }
    
    return $default;
}

/**
 * Get site name from settings
 */
function zed_get_site_name(): string
{
    return zed_get_option('site_title', 'Zed CMS');
}

/**
 * Get site tagline from settings
 */
function zed_get_site_tagline(): string
{
    return zed_get_option('site_tagline', '');
}

/**
 * Get meta description from settings
 */
function zed_get_meta_description(): string
{
    return zed_get_option('meta_description', '');
}

/**
 * Check if search engines should be discouraged
 */
function zed_is_noindex(): bool
{
    return zed_get_option('discourage_search_engines', '0') === '1';
}

/**
 * Get posts per page setting
 */
function zed_get_posts_per_page(): int
{
    return max(1, (int)zed_get_option('posts_per_page', '10'));
}

/**
 * Fetch latest published posts for blog listing
 *
 * @param int $limit Number of posts to fetch
 * @param int $offset Offset for pagination
 * @return array Posts array
 */
function zed_get_latest_posts(int $limit = 10, int $offset = 0): array
{
    try {
        $db = Database::getInstance();
        return $db->query(
            "SELECT * FROM zed_content 
             WHERE type = 'post' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get total count of published posts
 */
function zed_count_published_posts(): int
{
    try {
        $db = Database::getInstance();
        return (int)$db->queryValue(
            "SELECT COUNT(*) FROM zed_content 
             WHERE type = 'post' 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'"
        );
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Get a single page by ID
 */
function zed_get_page_by_id(int $id): ?array
{
    try {
        $db = Database::getInstance();
        return $db->queryOne(
            "SELECT * FROM zed_content WHERE id = :id AND type = 'page' LIMIT 1",
            ['id' => $id]
        );
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Render a complete HTML page for public viewing
 *
 * @param array $post Post data from database
 * @param string $renderedContent HTML content
 * @return string Complete HTML page
 */
function render_page(array $post, string $renderedContent): string
{
    $title = htmlspecialchars($post['title'] ?? 'Untitled');
    $type = ucfirst($post['type'] ?? 'page');
    $data = is_string($post['data']) ? json_decode($post['data'], true) : ($post['data'] ?? []);
    $excerpt = htmlspecialchars($data['excerpt'] ?? '');
    $featuredImage = $data['featured_image'] ?? '';
    $createdAt = $post['created_at'] ?? '';
    $updatedAt = $post['updated_at'] ?? '';
    
    // Format dates
    $publishDate = $createdAt ? date('F j, Y', strtotime($createdAt)) : '';
    
    // Base URL
    $baseUrl = Router::getBasePath();
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} — Zed CMS</title>
    <meta name="description" content="{$excerpt}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Lora', 'Georgia', 'serif'],
                    },
                },
            },
        }
    </script>
    
    <style>
        /* Content Styles */
        .content-area {
            font-family: 'Lora', Georgia, serif;
            font-size: 1.125rem;
            line-height: 1.8;
            color: #1f2937;
        }
        .content-area p {
            margin-bottom: 1.5rem;
        }
        .content-area h1, .content-area h2, .content-area h3, 
        .content-area h4, .content-area h5, .content-area h6 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .content-area h1 { font-size: 2.25rem; }
        .content-area h2 { font-size: 1.875rem; }
        .content-area h3 { font-size: 1.5rem; }
        .content-area ul, .content-area ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }
        .content-area li {
            margin-bottom: 0.5rem;
        }
        .content-area ul li {
            list-style-type: disc;
        }
        .content-area ol li {
            list-style-type: decimal;
        }
        .content-area a {
            color: #4f46e5;
            text-decoration: underline;
        }
        .content-area a:hover {
            color: #4338ca;
        }
        .content-area pre {
            background: #1f2937;
            color: #f3f4f6;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }
        .content-area code {
            font-family: ui-monospace, SFMono-Regular, monospace;
            font-size: 0.9em;
        }
        .content-area :not(pre) > code {
            background: #f3f4f6;
            padding: 0.2em 0.4em;
            border-radius: 0.25rem;
            color: #dc2626;
        }
        .content-area figure {
            margin: 2rem 0;
        }
        .content-area figcaption {
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }
        .content-area blockquote {
            border-left: 4px solid #4f46e5;
            padding-left: 1rem;
            font-style: italic;
            color: #4b5563;
            margin: 1.5rem 0;
        }
        .content-area table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        .content-area th, .content-area td {
            border: 1px solid #e5e7eb;
            padding: 0.75rem;
            text-align: left;
        }
        .content-area th {
            background: #f9fafb;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{$baseUrl}/" class="font-bold text-xl flex items-center gap-2">
                <span class="w-8 h-8 bg-black text-white rounded flex items-center justify-center text-sm">Z</span>
                <span>Zero</span>
            </a>
            <nav class="flex items-center gap-6 text-sm">
                <a href="{$baseUrl}/" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="{$baseUrl}/admin" class="text-indigo-600 hover:text-indigo-700 font-medium">Admin</a>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-6 py-12">
        <!-- Article Header -->
        <article>
            <header class="mb-8">
                <div class="text-sm text-indigo-600 font-medium mb-2">{$type}</div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4 font-sans">{$title}</h1>
                " . ($publishDate ? "<p class=\"text-gray-500 text-sm\">Published on {$publishDate}</p>" : "") . "
            </header>
            
            <!-- Featured Image -->
            " . ($featuredImage ? "<img src=\"{$featuredImage}\" alt=\"{$title}\" class=\"w-full rounded-lg mb-8 shadow-lg\">" : "") . "
            
            <!-- Content -->
            <div class="content-area">
                {$renderedContent}
            </div>
        </article>
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-4xl mx-auto px-6 py-8 text-center text-gray-500 text-sm">
            <p>Powered by <strong>Zed CMS</strong></p>
        </div>
    </footer>
</body>
</html>
HTML;
}

// =============================================================================
// Route Listener - Runs with LOW priority (100) so it acts as a fallback
// =============================================================================

Event::on('route_request', function (array $request): void {
    $uri = $request['uri'];
    
    // =========================================================================
    // THEME CONFIGURATION
    // =========================================================================
    // Define the active theme. In the future, this will be loaded from:
    // - Database setting (via Theme Manager UI)
    // - Config file
    // For now, we hardcode it for development.
    // =========================================================================
    $theme = 'zero-one';
    
    // Define theme path and template paths
    $themesDir = __DIR__ . '/../themes';
    $themePath = $themesDir . '/' . $theme;
    
    // Make theme name globally accessible for other addons
    if (!defined('ZED_ACTIVE_THEME')) {
        define('ZED_ACTIVE_THEME', $theme);
    }
    
    // =========================================================================
    // ROUTE FILTERING
    // =========================================================================
    
    // Skip admin routes - let admin_addon handle those
    if (str_starts_with($uri, '/admin')) {
        return;
    }
    
    // Skip if already handled
    if (Router::isHandled()) {
        return;
    }
    
    // Extract slug from URI (remove leading slash)
    $slug = ltrim($uri, '/');
    
    // =========================================================================
    // SMART ROUTING - Respects Unified Settings
    // =========================================================================
    
    // Get homepage configuration from settings
    $homepage_mode = zed_get_option('homepage_mode', 'latest_posts');
    $page_on_front = (int)zed_get_option('page_on_front', '0');
    $blog_slug = zed_get_option('blog_slug', 'blog');
    $posts_per_page = zed_get_posts_per_page();
    
    // =========================================================================
    // HOMEPAGE HANDLER (empty slug = /)
    // =========================================================================
    if (empty($slug)) {
        $base_url = Router::getBasePath();
        
        // Case A: Static Page Homepage
        if ($homepage_mode === 'static_page' && $page_on_front > 0) {
            $post = zed_get_page_by_id($page_on_front);
            
            if ($post) {
                // Parse content
                $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
                $blocks = $data['content'] ?? [];
                $htmlContent = render_blocks($blocks);
                
                // Try page.php first, then single.php, then fallback
                $pageTemplate = $themePath . '/page.php';
                $singleTemplate = $themePath . '/single.php';
                
                if (file_exists($pageTemplate)) {
                    ob_start();
                    include $pageTemplate;
                    $html = ob_get_clean();
                } elseif (file_exists($singleTemplate)) {
                    ob_start();
                    include $singleTemplate;
                    $html = ob_get_clean();
                } else {
                    // Fallback render
                    $html = render_page($post, $htmlContent);
                }
                
                Router::setHandled($html);
                return;
            }
        }
        
        // Case B: Latest Posts (Default)
        // Fetch latest posts for the homepage
        $page_num = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page_num - 1) * $posts_per_page;
        $posts = zed_get_latest_posts($posts_per_page, $offset);
        $total_posts = zed_count_published_posts();
        $total_pages = max(1, ceil($total_posts / $posts_per_page));
        
        // Theme variables for index.php
        $is_home = true;
        $is_blog = true;
        
        $homepageTemplate = $themePath . '/index.php';
        
        if (file_exists($homepageTemplate)) {
            ob_start();
            include $homepageTemplate;
            $html = ob_get_clean();
            Router::setHandled($html);
            return;
        }
        
        // Fallback: Let it fall through
        return;
    }
    
    // =========================================================================
    // BLOG LISTING HANDLER (Dynamic slug from settings)
    // =========================================================================
    // Only active when homepage is set to static page
    if ($homepage_mode === 'static_page' && $slug === $blog_slug) {
        $base_url = Router::getBasePath();
        
        // Pagination
        $page_num = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page_num - 1) * $posts_per_page;
        $posts = zed_get_latest_posts($posts_per_page, $offset);
        $total_posts = zed_count_published_posts();
        $total_pages = max(1, ceil($total_posts / $posts_per_page));
        
        // Theme variables
        $is_home = false;
        $is_blog = true;
        $blog_title = 'Blog';
        
        // Use index.php for blog listing
        $blogTemplate = $themePath . '/index.php';
        
        if (file_exists($blogTemplate)) {
            ob_start();
            include $blogTemplate;
            $html = ob_get_clean();
            Router::setHandled($html);
            return;
        }
        
        // Fallback: return simple listing
        return;
    }
    
    // Handle preview route: /preview/{id}
    if (str_starts_with($slug, 'preview/')) {
        $id = substr($slug, 8); // Remove 'preview/' prefix
        if (!is_numeric($id)) {
            return;
        }
        
        // Preview requires authentication
        if (!Auth::check()) {
            Router::redirect('/admin/login?redirect=' . urlencode($uri));
        }
        
        try {
            $db = Database::getInstance();
            $post = $db->queryOne(
                "SELECT * FROM zed_content WHERE id = :id LIMIT 1",
                ['id' => (int)$id]
            );
            
            if ($post) {
                // Decode content
                $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
                $blocks = $data['content'] ?? [];
                
                // Render
                $renderedContent = render_blocks($blocks);
                $html = render_page($post, $renderedContent);
                
                Router::setHandled($html);
                return;
            }
        } catch (Exception $e) {
            // Fall through to 404
        }
        
        return;
    }
    
    // =========================================================================
    // SINGLE CONTENT HANDLER (/{slug})
    // =========================================================================
    
    // Try to find content by slug
    try {
        $db = Database::getInstance();
        
        // Only show published content on frontend
        $post = $db->queryOne(
            "SELECT * FROM zed_content 
             WHERE slug = :slug 
               AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             LIMIT 1",
            ['slug' => $slug]
        );
        
        if ($post) {
            // ─────────────────────────────────────────────────────────────────
            // STEP 1: Parse the post data and convert JSON blocks to HTML
            // ─────────────────────────────────────────────────────────────────
            $data = is_string($post['data']) ? json_decode($post['data'], true) : $post['data'];
            $blocks = $data['content'] ?? [];
            
            // Render BlockNote JSON to HTML
            $htmlContent = render_blocks($blocks);
            
            // Make base_url available to templates
            $base_url = Router::getBasePath();
            
            // ─────────────────────────────────────────────────────────────────
            // STEP 2: Bridge to Theme Template
            // ─────────────────────────────────────────────────────────────────
            $singleTemplate = $themePath . '/single.php';
            
            if (file_exists($singleTemplate)) {
                // Theme template found - include it
                // Variables available to template: $post, $htmlContent, $base_url
                ob_start();
                include $singleTemplate;
                $html = ob_get_clean();
            } else {
                // ─────────────────────────────────────────────────────────────
                // FALLBACK: No theme template - echo raw content safely
                // ─────────────────────────────────────────────────────────────
                $title = htmlspecialchars($post['title'] ?? 'Untitled');
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
            }
            
            // Output and mark as handled
            // Output and mark as handled
            Router::setHandled($html);
            return;
        }
        
    } catch (Exception $e) {
        // Database error - let it fall through to 404
        error_log("Frontend addon error: " . $e->getMessage());
    }
    
    // If we get here, slug wasn't found - let Router handle 404
    
}, 100); // Priority 100 = runs AFTER admin_addon (priority 10)

// =============================================================================
// SEO Head Event - Inject metadata from settings
// =============================================================================

/**
 * Output <head> metadata for frontend pages
 * Called by themes via: Event::trigger('zed_head');
 */
Event::on('zed_head', function(): void {
    $siteName = htmlspecialchars(zed_get_site_name());
    $tagline = htmlspecialchars(zed_get_site_tagline());
    $description = htmlspecialchars(zed_get_meta_description());
    $noindex = zed_is_noindex();
    
    // Output meta tags
    echo "\n    <!-- Zed CMS SEO -->\n";
    
    // Site metadata
    echo "    <meta name=\"generator\" content=\"Zed CMS 1.5.0\">\n";
    
    // Description
    if (!empty($description)) {
        echo "    <meta name=\"description\" content=\"{$description}\">\n";
    }
    
    // Noindex if discourage search engines is enabled
    if ($noindex) {
        echo "    <meta name=\"robots\" content=\"noindex, nofollow\">\n";
    }
    
    // Open Graph basics
    echo "    <meta property=\"og:site_name\" content=\"{$siteName}\">\n";
    if (!empty($tagline)) {
        echo "    <meta property=\"og:description\" content=\"{$tagline}\">\n";
    }
    
    // Social sharing image
    $socialImage = zed_get_option('social_sharing_image', '');
    if (!empty($socialImage)) {
        $socialImage = htmlspecialchars($socialImage);
        echo "    <meta property=\"og:image\" content=\"{$socialImage}\">\n";
        echo "    <meta name=\"twitter:image\" content=\"{$socialImage}\">\n";
    }
    
    echo "    <!-- /Zed CMS SEO -->\n";
}, 10);

/**
 * Helper to generate page title with site name
 * Usage in theme: echo zed_page_title('My Page');
 */
function zed_page_title(string $pageTitle = ''): string
{
    $siteName = zed_get_site_name();
    
    if (empty($pageTitle)) {
        $tagline = zed_get_site_tagline();
        return $siteName . ($tagline ? ' — ' . $tagline : '');
    }
    
    return $pageTitle . ' — ' . $siteName;
}
