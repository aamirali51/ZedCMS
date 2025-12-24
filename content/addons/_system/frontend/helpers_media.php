<?php
/**
 * Zed CMS — Media/Image Helpers
 * 
 * Pure functions for featured images and thumbnails.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

/**
 * Get featured image URL
 * 
 * @param array $post Post data
 * @return string|null Image URL or null
 */
function zed_get_featured_image(array $post): ?string
{
    $data = zed_parse_post_data($post);
    $image = $data['featured_image'] ?? null;
    
    if (empty($image)) {
        return null;
    }
    
    return $image;
}

/**
 * Get thumbnail URL for post
 * Attempts to find thumb_ prefixed version
 * 
 * @param array $post Post data
 * @return string|null Thumbnail URL or null
 */
function zed_get_thumbnail(array $post): ?string
{
    $image = zed_get_featured_image($post);
    
    if (!$image) {
        return null;
    }
    
    // Try to get thumbnail version
    $pathInfo = pathinfo($image);
    $thumbUrl = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
    
    // Check if it's a local file and thumb exists
    $uploadsDir = dirname(__DIR__, 3) . '/uploads/';
    $filename = basename($image);
    $thumbPath = $uploadsDir . 'thumb_' . $filename;
    
    if (file_exists($thumbPath)) {
        return $thumbUrl;
    }
    
    // Return original if no thumb
    return $image;
}

/**
 * Check if post has featured image
 * 
 * @param array $post Post data
 * @return bool True if has featured image
 */
function zed_has_featured_image(array $post): bool
{
    return zed_get_featured_image($post) !== null;
}

/**
 * Render featured image as HTML img tag
 * 
 * @param array $post Post data
 * @param array $attrs HTML attributes (class, alt, loading, etc.)
 * @return string HTML img tag or empty string
 */
function zed_featured_image(array $post, array $attrs = []): string
{
    $image = zed_get_featured_image($post);
    
    if (!$image) {
        return '';
    }
    
    // Default attributes
    $defaults = [
        'class' => '',
        'alt' => zed_get_title($post),
        'loading' => 'lazy',
    ];
    
    $attrs = array_merge($defaults, $attrs);
    
    // Build attribute string
    $attrStr = '';
    foreach ($attrs as $key => $value) {
        if ($value !== '') {
            $attrStr .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
        }
    }
    
    return '<img src="' . htmlspecialchars($image) . '"' . $attrStr . '>';
}

/**
 * Get image from media library by filename
 * 
 * @param string $filename Filename to look up
 * @return string|null Full URL or null
 */
function zed_get_media_url(string $filename): ?string
{
    $uploadsDir = dirname(__DIR__, 3) . '/uploads/';
    $uploadsUrl = \Core\Router::getBasePath() . '/content/uploads/';
    
    if (file_exists($uploadsDir . $filename)) {
        return $uploadsUrl . $filename;
    }
    
    return null;
}

/**
 * Get placeholder image URL
 * 
 * @param int $width Width
 * @param int $height Height
 * @param string $text Optional text
 * @return string Placeholder URL
 */
function zed_placeholder_image(int $width = 800, int $height = 400, string $text = ''): string
{
    $text = $text ?: "{$width}x{$height}";
    return "https://via.placeholder.com/{$width}x{$height}/e2e8f0/64748b?text=" . urlencode($text);
}
