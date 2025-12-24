<?php
/**
 * SEO Head & Page Title Functions
 * 
 * Injects SEO metadata into page heads.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

use Core\Event;

// =============================================================================
// SEO HEAD EVENT
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
    
    echo "\n    <!-- Zed CMS SEO -->\n";
    echo "    <meta name=\"generator\" content=\"Zed CMS 3.0.0\">\n";
    
    if (!empty($description)) {
        echo "    <meta name=\"description\" content=\"{$description}\">\n";
    }
    
    if ($noindex) {
        echo "    <meta name=\"robots\" content=\"noindex, nofollow\">\n";
    }
    
    echo "    <meta property=\"og:site_name\" content=\"{$siteName}\">\n";
    if (!empty($tagline)) {
        echo "    <meta property=\"og:description\" content=\"{$tagline}\">\n";
    }
    
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
