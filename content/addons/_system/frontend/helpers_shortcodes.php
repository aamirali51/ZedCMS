<?php
/**
 * Zed CMS — Shortcode System
 * 
 * WordPress-style shortcode parsing for content embedding.
 * 
 * Usage:
 *   zed_register_shortcode('contact_form', function($attrs, $content) {
 *       return '<form>...</form>';
 *   });
 * 
 *   In content: [contact_form email="test@example.com"]
 *   Or: [box]Content here[/box]
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Event;

// Global shortcode registry
global $ZED_SHORTCODES;
$ZED_SHORTCODES = [];

/**
 * Register a shortcode handler
 * 
 * @param string $tag Shortcode tag (e.g., 'contact_form')
 * @param callable $callback Function($attrs, $content) => string
 */
function zed_register_shortcode(string $tag, callable $callback): void
{
    global $ZED_SHORTCODES;
    $ZED_SHORTCODES[$tag] = $callback;
}

/**
 * Unregister a shortcode
 * 
 * @param string $tag Shortcode tag to remove
 */
function zed_unregister_shortcode(string $tag): void
{
    global $ZED_SHORTCODES;
    unset($ZED_SHORTCODES[$tag]);
}

/**
 * Check if a shortcode is registered
 * 
 * @param string $tag Shortcode tag
 * @return bool True if registered
 */
function zed_shortcode_exists(string $tag): bool
{
    global $ZED_SHORTCODES;
    return isset($ZED_SHORTCODES[$tag]);
}

/**
 * Parse and execute all shortcodes in content
 * 
 * @param string $content Content with shortcodes
 * @return string Content with shortcodes replaced
 */
function zed_do_shortcodes(string $content): string
{
    global $ZED_SHORTCODES;
    
    if (empty($ZED_SHORTCODES)) {
        return $content;
    }
    
    // Build regex for registered shortcodes
    $tagNames = array_keys($ZED_SHORTCODES);
    $tagPattern = implode('|', array_map('preg_quote', $tagNames));
    
    // Match: [tag attr="val"] or [tag attr="val"]content[/tag]
    $pattern = '/\[(' . $tagPattern . ')([^\]]*)\](?:(.*?)\[\/\1\])?/s';
    
    return preg_replace_callback($pattern, function($matches) use ($ZED_SHORTCODES) {
        $tag = $matches[1];
        $attrString = $matches[2] ?? '';
        $innerContent = $matches[3] ?? '';
        
        // Parse attributes
        $attrs = zed_parse_shortcode_attrs($attrString);
        
        // Call the handler
        if (isset($ZED_SHORTCODES[$tag])) {
            try {
                $output = call_user_func($ZED_SHORTCODES[$tag], $attrs, $innerContent);
                return Event::filter('zed_shortcode_output', $output, $tag, $attrs);
            } catch (\Throwable $e) {
                // Fail silently in production, show error in debug
                if (function_exists('zed_is_debug') && zed_is_debug()) {
                    return '<div class="shortcode-error">[' . $tag . ' error: ' . htmlspecialchars($e->getMessage()) . ']</div>';
                }
                return '';
            }
        }
        
        return $matches[0]; // Return original if no handler
    }, $content);
}

/**
 * Parse shortcode attribute string
 * 
 * @param string $attrString Attribute string (e.g., 'email="test" limit="5"')
 * @return array Parsed attributes
 */
function zed_parse_shortcode_attrs(string $attrString): array
{
    $attrs = [];
    $attrString = trim($attrString);
    
    if (empty($attrString)) {
        return $attrs;
    }
    
    // Match: name="value" or name='value' or name=value or just name
    preg_match_all('/(\w+)(?:=(?:"([^"]*)"|\'([^\']*)\'|(\S+)))?/', $attrString, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $match) {
        $name = $match[1];
        // Value can be in position 2, 3, or 4
        $value = $match[2] ?? $match[3] ?? $match[4] ?? true;
        $attrs[$name] = $value;
    }
    
    return $attrs;
}

/**
 * Get all registered shortcode tags
 * 
 * @return array List of shortcode tags
 */
function zed_get_shortcodes(): array
{
    global $ZED_SHORTCODES;
    return array_keys($ZED_SHORTCODES);
}

/**
 * Strip all shortcodes from content
 * 
 * @param string $content Content with shortcodes
 * @return string Content without shortcodes
 */
function zed_strip_shortcodes(string $content): string
{
    global $ZED_SHORTCODES;
    
    if (empty($ZED_SHORTCODES)) {
        return $content;
    }
    
    $tagNames = array_keys($ZED_SHORTCODES);
    $tagPattern = implode('|', array_map('preg_quote', $tagNames));
    
    // Remove shortcodes but keep inner content for enclosing shortcodes
    $pattern = '/\[(' . $tagPattern . ')([^\]]*)\](?:(.*?)\[\/\1\])?/s';
    
    return preg_replace_callback($pattern, function($matches) {
        // Return inner content if it exists (for [tag]content[/tag])
        return $matches[3] ?? '';
    }, $content);
}
