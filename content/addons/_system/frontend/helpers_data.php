<?php
/**
 * Zed CMS — Content Data Extraction Helpers
 * 
 * Pure functions for extracting data from post arrays.
 * All functions take explicit post array, return data.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

/**
 * Get post title
 * 
 * @param array $post Post data
 * @return string Title
 */
function zed_get_title(array $post): string
{
    return $post['title'] ?? '';
}

/**
 * Get post excerpt (from data or auto-generated)
 * 
 * @param array $post Post data
 * @param int $length Max length for auto-excerpt
 * @return string Excerpt
 */
function zed_get_excerpt(array $post, int $length = 160): string
{
    $data = zed_parse_post_data($post);
    
    // Check for explicit excerpt
    if (!empty($data['excerpt'])) {
        $excerpt = $data['excerpt'];
    } else {
        // Auto-generate from content
        $plainText = $post['plain_text'] ?? '';
        if (empty($plainText) && !empty($data['content'])) {
            $plainText = zed_strip_blocks($data['content']);
        }
        $excerpt = $plainText;
    }
    
    // Apply filter and truncate
    $excerpt = Event::filter('zed_excerpt', $excerpt, $post);
    return zed_truncate($excerpt, $length);
}

/**
 * Get rendered HTML content from blocks
 * 
 * @param array $post Post data
 * @return string HTML content
 */
function zed_get_content(array $post): string
{
    $data = zed_parse_post_data($post);
    $blocks = $data['content'] ?? [];
    
    if (!is_array($blocks) || empty($blocks)) {
        return '';
    }
    
    $html = render_blocks($blocks);
    return Event::filter('zed_content_html', $html, $post);
}

/**
 * Get post slug
 * 
 * @param array $post Post data
 * @return string Slug
 */
function zed_get_slug(array $post): string
{
    return $post['slug'] ?? '';
}

/**
 * Get full permalink URL to post
 * 
 * @param array $post Post data
 * @return string Full URL
 */
function zed_get_permalink(array $post): string
{
    $base = Router::getBasePath();
    $slug = zed_get_slug($post);
    $type = zed_get_type($post);
    
    // Check for CPT base slug
    $postTypes = zed_get_post_types();
    if (isset($postTypes[$type]) && !empty($postTypes[$type]['rewrite'])) {
        $rewrite = $postTypes[$type]['rewrite'];
        return "{$base}/{$rewrite}/{$slug}";
    }
    
    // Default: just use slug
    return "{$base}/{$slug}";
}

/**
 * Get post status
 * 
 * @param array $post Post data
 * @return string Status (draft|published)
 */
function zed_get_status(array $post): string
{
    $data = zed_parse_post_data($post);
    return $data['status'] ?? 'draft';
}

/**
 * Get content type
 * 
 * @param array $post Post data
 * @return string Type (post|page|custom)
 */
function zed_get_type(array $post): string
{
    return $post['type'] ?? 'page';
}

/**
 * Get created date formatted
 * 
 * @param array $post Post data
 * @param string $format Date format
 * @return string Formatted date
 */
function zed_get_created_date(array $post, string $format = 'M j, Y'): string
{
    $date = $post['created_at'] ?? null;
    if (!$date) return '';
    
    try {
        $dt = new DateTime($date);
        return $dt->format($format);
    } catch (\Exception $e) {
        return '';
    }
}

/**
 * Get updated date formatted
 * 
 * @param array $post Post data
 * @param string $format Date format
 * @return string Formatted date
 */
function zed_get_updated_date(array $post, string $format = 'M j, Y'): string
{
    $date = $post['updated_at'] ?? null;
    if (!$date) return '';
    
    try {
        $dt = new DateTime($date);
        return $dt->format($format);
    } catch (\Exception $e) {
        return '';
    }
}

/**
 * Parse post data JSON column
 * 
 * @param array $post Post data
 * @return array Parsed data
 */
function zed_parse_post_data(array $post): array
{
    $data = $post['data'] ?? [];
    
    if (is_string($data)) {
        $decoded = json_decode($data, true);
        return is_array($decoded) ? $decoded : [];
    }
    
    return is_array($data) ? $data : [];
}

/**
 * Check if post is published
 * 
 * @param array $post Post data
 * @return bool True if published
 */
function zed_is_published(array $post): bool
{
    return zed_get_status($post) === 'published';
}
