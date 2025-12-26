<?php
/**
 * Content Renderer Engine
 * 
 * Handles content rendering for the frontend:
 * - NEW (v3.1.0+): TipTap content is stored as HTML and passed through directly
 * - LEGACY: BlockNote JSON blocks are converted to HTML via render_blocks()
 * 
 * The frontend routes.php detects the format and only calls render_blocks()
 * for legacy BlockNote content.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

use Core\Router;

// =============================================================================
// LEGACY BLOCK RENDERING ENGINE (for BlockNote JSON content)
// =============================================================================

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
                // Handle unknown block types gracefully
                $safeType = htmlspecialchars($type);
                $html .= "<!-- Zed CMS: Unknown block type '{$safeType}' -->\n";
                
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

// =============================================================================
// CONTENT STYLES
// =============================================================================

/**
 * Base Content CSS - Centralized block rendering styles
 * 
 * This CSS is the SOURCE OF TRUTH for content rendering.
 * All themes automatically get these styles.
 * 
 * @return string Inline CSS for content blocks
 */
function zed_content_styles(): string
{
    return <<<'CSS'
<style id="zed-content-styles">
/* ============================================
   ZED CMS - Base Content Styles
   ============================================ */

.zed-content {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 1.125rem;
    line-height: 1.8;
    color: #334155;
}

.zed-content h1, .zed-content h2, .zed-content h3,
.zed-content h4, .zed-content h5, .zed-content h6 {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-weight: 700;
    line-height: 1.3;
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #1e293b;
}
.zed-content h1 { font-size: 2.5rem; }
.zed-content h2 { font-size: 2rem; }
.zed-content h3 { font-size: 1.5rem; }
.zed-content h4 { font-size: 1.25rem; }

.zed-content > h1:first-child,
.zed-content > h2:first-child,
.zed-content > h3:first-child { margin-top: 0; }

.zed-content p { margin-bottom: 1.5rem; }
.zed-content p:last-child { margin-bottom: 0; }

.zed-content a { color: #4f46e5; text-decoration: underline; }
.zed-content a:hover { color: #4338ca; }

.zed-content ul, .zed-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
.zed-content li { margin-bottom: 0.5rem; }
.zed-content ul { list-style-type: disc; }
.zed-content ol { list-style-type: decimal; }

.zed-content blockquote {
    border-left: 4px solid #4f46e5;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #64748b;
}

.zed-content code {
    background: #f1f5f9;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    font-family: 'Fira Code', Consolas, Monaco, monospace;
}
.zed-content pre {
    background: #1e293b;
    color: #e2e8f0;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}
.zed-content pre code { background: transparent; padding: 0; color: inherit; }

.zed-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 1.5rem 0; }
.zed-content figure { margin: 1.5rem 0; }
.zed-content figcaption { text-align: center; font-size: 0.875rem; color: #64748b; margin-top: 0.5rem; }

.zed-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
.zed-content th, .zed-content td { border: 1px solid #e2e8f0; padding: 0.75rem; text-align: left; }
.zed-content th { background: #f8fafc; font-weight: 600; }

.zed-content hr { border: none; border-top: 1px solid #e2e8f0; margin: 2rem 0; }
</style>
CSS;
}
