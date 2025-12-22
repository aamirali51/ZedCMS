<?php
/**
 * Zed CMS — SEO & Meta Helpers
 * 
 * Functions for generating SEO meta tags, Open Graph, and structured data.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

/**
 * Generate all meta tags for a page
 * 
 * @param array $post Optional post data for single pages
 * @return string HTML meta tags
 */
function zed_meta_tags(array $post = []): string
{
    $tags = [];
    
    // Description
    if (!empty($post)) {
        $description = zed_get_excerpt($post, 160);
    } else {
        $description = zed_get_meta_description();
    }
    
    if ($description) {
        $tags[] = '<meta name="description" content="' . htmlspecialchars($description) . '">';
    }
    
    // Robots
    if (zed_is_noindex()) {
        $tags[] = '<meta name="robots" content="noindex, nofollow">';
    }
    
    // Canonical
    $canonical = zed_canonical_url();
    if ($canonical) {
        $tags[] = '<link rel="canonical" href="' . htmlspecialchars($canonical) . '">';
    }
    
    // Open Graph
    $tags[] = zed_og_tags($post);
    
    // Twitter Card
    $tags[] = zed_twitter_tags($post);
    
    $html = implode("\n    ", array_filter($tags));
    return Event::filter('zed_meta_tags', $html, $post);
}

/**
 * Generate Open Graph meta tags
 * 
 * @param array $post Optional post data
 * @return string HTML OG tags
 */
function zed_og_tags(array $post = []): string
{
    $base = Router::getBasePath();
    $siteName = zed_get_site_name();
    
    $tags = [];
    $tags[] = '<meta property="og:site_name" content="' . htmlspecialchars($siteName) . '">';
    
    if (!empty($post)) {
        // Single post OG
        $tags[] = '<meta property="og:type" content="article">';
        $tags[] = '<meta property="og:title" content="' . htmlspecialchars(zed_get_title($post)) . '">';
        $tags[] = '<meta property="og:description" content="' . htmlspecialchars(zed_get_excerpt($post, 200)) . '">';
        $tags[] = '<meta property="og:url" content="' . htmlspecialchars(zed_get_permalink($post)) . '">';
        
        $image = zed_get_featured_image($post);
        if ($image) {
            $tags[] = '<meta property="og:image" content="' . htmlspecialchars($image) . '">';
        }
        
        // Article specific
        $tags[] = '<meta property="article:published_time" content="' . htmlspecialchars($post['created_at'] ?? '') . '">';
        $tags[] = '<meta property="article:modified_time" content="' . htmlspecialchars($post['updated_at'] ?? '') . '">';
    } else {
        // Homepage/archive OG
        $tags[] = '<meta property="og:type" content="website">';
        $tags[] = '<meta property="og:title" content="' . htmlspecialchars($siteName) . '">';
        $tags[] = '<meta property="og:description" content="' . htmlspecialchars(zed_get_site_tagline()) . '">';
        $tags[] = '<meta property="og:url" content="' . htmlspecialchars($base . '/') . '">';
        
        $socialImage = zed_get_option('social_sharing_image');
        if ($socialImage) {
            $tags[] = '<meta property="og:image" content="' . htmlspecialchars($socialImage) . '">';
        }
    }
    
    return implode("\n    ", $tags);
}

/**
 * Generate Twitter Card meta tags
 * 
 * @param array $post Optional post data
 * @return string HTML Twitter tags
 */
function zed_twitter_tags(array $post = []): string
{
    $tags = [];
    $tags[] = '<meta name="twitter:card" content="summary_large_image">';
    
    if (!empty($post)) {
        $tags[] = '<meta name="twitter:title" content="' . htmlspecialchars(zed_get_title($post)) . '">';
        $tags[] = '<meta name="twitter:description" content="' . htmlspecialchars(zed_get_excerpt($post, 200)) . '">';
        
        $image = zed_get_featured_image($post);
        if ($image) {
            $tags[] = '<meta name="twitter:image" content="' . htmlspecialchars($image) . '">';
        }
    } else {
        $tags[] = '<meta name="twitter:title" content="' . htmlspecialchars(zed_get_site_name()) . '">';
        $tags[] = '<meta name="twitter:description" content="' . htmlspecialchars(zed_get_site_tagline()) . '">';
    }
    
    return implode("\n    ", $tags);
}

/**
 * Generate JSON-LD schema markup
 * 
 * @param array $post Optional post data
 * @return string JSON-LD script tag
 */
function zed_schema_markup(array $post = []): string
{
    $base = Router::getBasePath();
    $siteName = zed_get_site_name();
    
    if (!empty($post)) {
        // Article schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => zed_get_title($post),
            'description' => zed_get_excerpt($post, 200),
            'url' => zed_get_permalink($post),
            'datePublished' => $post['created_at'] ?? '',
            'dateModified' => $post['updated_at'] ?? '',
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
            ],
        ];
        
        $image = zed_get_featured_image($post);
        if ($image) {
            $schema['image'] = $image;
        }
        
        $author = zed_get_post_author($post);
        if ($author) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => $author['display_name'] ?? 'Unknown',
            ];
        }
    } else {
        // Website schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'description' => zed_get_site_tagline(),
            'url' => $base . '/',
        ];
    }
    
    $schema = Event::filter('zed_schema_data', $schema, $post);
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Get canonical URL for current page
 * 
 * @return string Canonical URL
 */
function zed_canonical_url(): string
{
    $base = Router::getBasePath();
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    
    // Remove query string for canonical
    $uri = strtok($uri, '?');
    
    // Remove base path if present
    if (str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base));
    }
    
    return rtrim($base, '/') . '/' . ltrim($uri, '/');
}
