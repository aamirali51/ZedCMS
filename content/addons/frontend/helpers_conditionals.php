<?php
/**
 * Zed CMS — Conditional Helpers
 * 
 * Boolean helpers for template conditionals.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;

/**
 * Check if current page is homepage
 * 
 * @return bool True if homepage
 */
function zed_is_home(): bool
{
    $uri = Router::getCurrentUri();
    return $uri === '' || $uri === '/';
}

/**
 * Check if current page is a single post/page
 * 
 * @return bool True if single view
 */
function zed_is_single(): bool
{
    // Check if we're viewing a single content item
    global $zed_current_post;
    return isset($zed_current_post) && !empty($zed_current_post);
}

/**
 * Check if current page is a specific page
 * 
 * @param string|null $slug Optional specific page slug
 * @return bool True if page
 */
function zed_is_page(?string $slug = null): bool
{
    global $zed_current_post;
    
    if (!isset($zed_current_post) || empty($zed_current_post)) {
        return false;
    }
    
    if (($zed_current_post['type'] ?? '') !== 'page') {
        return false;
    }
    
    if ($slug !== null) {
        return ($zed_current_post['slug'] ?? '') === $slug;
    }
    
    return true;
}

/**
 * Check if current page is an archive/listing
 * 
 * @return bool True if archive
 */
function zed_is_archive(): bool
{
    $uri = Router::getCurrentUri();
    return str_starts_with($uri, 'category/') 
        || str_starts_with($uri, 'author/')
        || str_starts_with($uri, 'tag/')
        || $uri === zed_get_option('blog_slug', 'blog');
}

/**
 * Check if current page is a category archive
 * 
 * @param string|null $slug Optional specific category slug
 * @return bool True if category page
 */
function zed_is_category(?string $slug = null): bool
{
    $uri = Router::getCurrentUri();
    
    if (!str_starts_with($uri, 'category/')) {
        return false;
    }
    
    if ($slug !== null) {
        return $uri === 'category/' . $slug;
    }
    
    return true;
}

/**
 * Check if current page is author archive
 * 
 * @param int|null $userId Optional specific author ID
 * @return bool True if author page
 */
function zed_is_author(?int $userId = null): bool
{
    $uri = Router::getCurrentUri();
    
    if (!str_starts_with($uri, 'author/')) {
        return false;
    }
    
    if ($userId !== null) {
        return $uri === 'author/' . $userId;
    }
    
    return true;
}

/**
 * Check if current page is blog listing
 * 
 * @return bool True if blog page
 */
function zed_is_blog(): bool
{
    $uri = Router::getCurrentUri();
    $blogSlug = zed_get_option('blog_slug', 'blog');
    return $uri === $blogSlug;
}

/**
 * Check if user is logged in
 * 
 * @return bool True if logged in
 */
function zed_is_logged_in(): bool
{
    return Auth::check();
}

/**
 * Check if current user is admin
 * 
 * @return bool True if admin
 */
function zed_is_admin_user(): bool
{
    if (!Auth::check()) {
        return false;
    }
    
    $user = Auth::user();
    return ($user['role'] ?? '') === 'admin' || ($user['role'] ?? '') === 'administrator';
}

/**
 * Check if maintenance mode is enabled
 * 
 * @return bool True if maintenance mode
 */
function zed_is_maintenance_mode(): bool
{
    return zed_get_option('maintenance_mode', '0') === '1';
}

/**
 * Check if debug mode is enabled
 * 
 * @return bool True if debug mode
 */
function zed_is_debug(): bool
{
    return zed_get_option('debug_mode', '0') === '1';
}

/**
 * Check if current content is of specific type
 * 
 * @param string $type Type to check
 * @return bool True if matching type
 */
function zed_is_type(string $type): bool
{
    global $zed_current_post;
    return ($zed_current_post['type'] ?? '') === $type;
}

/**
 * Check if viewing search results
 * 
 * @return bool True if search
 */
function zed_is_search(): bool
{
    return isset($_GET['s']) && $_GET['s'] !== '';
}

/**
 * Get current search query
 * 
 * @return string Search query or empty
 */
function zed_get_search_query(): string
{
    return trim($_GET['s'] ?? '');
}
