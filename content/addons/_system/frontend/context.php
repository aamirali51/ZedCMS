<?php
/**
 * Context Backward Compatibility Layer
 * 
 * Integrates Core\Context with frontend system.
 * Maintains backward compatibility with themes using globals and old ZedContext.
 * 
 * @package ZedCMS\Frontend
 * @since 3.1.0
 */

declare(strict_types=1);

use Core\Context;

/**
 * Populate global variables from Context
 * 
 * Called by frontend controller after Context::set()
 * Maintains backward compatibility with themes using globals
 * 
 * @return void
 */
function zed_populate_template_globals(): void
{
    if (!Context::exists()) {
        return;
    }
    
    $context = Context::get();
    
    // Legacy globals (maintained for backward compatibility)
    global $post, $posts, $htmlContent;
    global $is_home, $is_single, $is_page, $is_archive, $is_404, $is_blog;
    global $base_url, $page_num, $total_pages;
    global $zed_query, $post_type, $post_type_label, $archive_title, $current_page;
    
    // Content
    $post = $context->post();
    $posts = $context->posts();
    $htmlContent = $context->htmlContent();
    
    // Context flags
    $is_home = $context->isHome();
    $is_single = $context->isSingle();
    $is_page = $context->isPage();
    $is_archive = $context->isArchive();
    $is_404 = $context->is404();
    $is_blog = $context->get('is_blog', false);
    
    // Metadata
    $base_url = $context->baseUrl();
    $page_num = $context->currentPage();
    $total_pages = $context->totalPages();
    $current_page = $context->currentPage();
    
    // Additional data
    $post_type = $context->get('post_type', 'post');
    $post_type_label = $context->get('post_type_label', 'Posts');
    $archive_title = $context->get('archive_title', '');
    
    // Query data (for advanced usage)
    $zed_query = $context->toArray();
}

// =============================================================================
// HELPER FUNCTIONS (New, Recommended API)
// =============================================================================

/**
 * Get the current context instance
 * 
 * @return Context Current context
 * @since 3.1.0
 */
function zed_context(): Context
{
    return Context::get();
}

/**
 * Get the current post/page
 * 
 * @return array|null Post data or null
 * @since 3.1.0
 */
function zed_current_post(): ?array
{
    return Context::get()->post();
}

/**
 * Get array of posts (for archives)
 * 
 * @return array Posts array
 * @since 3.1.0
 */
function zed_get_posts(): array
{
    return Context::get()->posts();
}

/**
 * Get rendered HTML content
 * 
 * @return string HTML content
 * @since 3.1.0
 */
function zed_get_content(): string
{
    return Context::get()->htmlContent();
}

// =============================================================================
// CONTEXT FLAG HELPERS
// =============================================================================

/**
 * Check if this is the homepage
 * 
 * @return bool True if homepage
 * @since 3.1.0
 */
function zed_is_home(): bool
{
    return Context::get()->isHome();
}

/**
 * Check if this is a single post
 * 
 * @return bool True if single post
 * @since 3.1.0
 */
function zed_is_single(): bool
{
    return Context::get()->isSingle();
}

/**
 * Check if this is a static page
 * 
 * @return bool True if page
 * @since 3.1.0
 */
function zed_is_page(): bool
{
    return Context::get()->isPage();
}

/**
 * Check if this is an archive listing
 * 
 * @return bool True if archive
 * @since 3.1.0
 */
function zed_is_archive(): bool
{
    return Context::get()->isArchive();
}

/**
 * Check if this is a 404 error page
 * 
 * @return bool True if 404
 * @since 3.1.0
 */
function zed_is_404(): bool
{
    return Context::get()->is404();
}

// =============================================================================
// PAGINATION HELPERS
// =============================================================================

/**
 * Get current page number
 * 
 * @return int Current page (1-indexed)
 * @since 3.1.0
 */
function zed_current_page(): int
{
    return Context::get()->currentPage();
}

/**
 * Get total number of pages
 * 
 * @return int Total pages
 * @since 3.1.0
 */
function zed_total_pages(): int
{
    return Context::get()->totalPages();
}

/**
 * Check if there are more pages
 * 
 * @return bool True if more pages exist
 * @since 3.1.0
 */
function zed_has_more_pages(): bool
{
    return Context::get()->hasMorePages();
}

/**
 * Check if this is a paginated request
 * 
 * @return bool True if page > 1
 * @since 3.1.0
 */
function zed_is_paginated(): bool
{
    return Context::get()->isPaginated();
}

// =============================================================================
// LEGACY COMPATIBILITY - Old ZedContext class wrapper
// =============================================================================

/**
 * Legacy ZedContext class for backward compatibility
 * Wraps Core\Context to maintain compatibility with old code
 * 
 * @deprecated 3.1.0 Use Core\Context directly
 */
class ZedContext
{
    private static ?ZedContext $instance = null;
    
    public static function getInstance(): ZedContext
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {}
    
    // Delegate all methods to Core\Context
    
    public function set(string $key, mixed $value): self
    {
        // Not supported in new Context (use Context::set() directly)
        return $this;
    }
    
    public function setMany(array $values): self
    {
        // Not supported in new Context
        return $this;
    }
    
    public function get(string $key, mixed $default = null): mixed
    {
        return Context::get()->get($key, $default);
    }
    
    public function has(string $key): bool
    {
        return Context::get()->has($key);
    }
    
    public function all(): array
    {
        return Context::get()->toArray();
    }
    
    public function post(?string $field = null): mixed
    {
        $post = Context::get()->post();
        if ($field !== null && is_array($post)) {
            return $post[$field] ?? null;
        }
        return $post;
    }
    
    public function postData(?string $field = null): mixed
    {
        $post = Context::get()->post();
        if (!is_array($post)) return null;
        
        $data = $post['data'] ?? [];
        if (is_string($data)) {
            $data = json_decode($data, true) ?? [];
        }
        
        if ($field !== null) {
            return $data[$field] ?? null;
        }
        return $data;
    }
    
    public function posts(): array
    {
        return Context::get()->posts();
    }
    
    public function htmlContent(): string
    {
        return Context::get()->htmlContent();
    }
    
    public function archiveTitle(): string
    {
        return Context::get()->get('archive_title', '');
    }
    
    public function postType(): string
    {
        return Context::get()->get('post_type', 'post');
    }
    
    public function postTypeLabel(): string
    {
        return Context::get()->get('post_type_label', 'Posts');
    }
    
    public function isHome(): bool
    {
        return Context::get()->isHome();
    }
    
    public function isSingle(): bool
    {
        return Context::get()->isSingle();
    }
    
    public function isPage(): bool
    {
        return Context::get()->isPage();
    }
    
    public function isArchive(): bool
    {
        return Context::get()->isArchive();
    }
    
    public function is404(): bool
    {
        return Context::get()->is404();
    }
    
    public function isBlog(): bool
    {
        return (bool)Context::get()->get('is_blog', false);
    }
    
    public function currentPage(): int
    {
        return Context::get()->currentPage();
    }
    
    public function totalPages(): int
    {
        return Context::get()->totalPages();
    }
    
    public function hasPagination(): bool
    {
        return Context::get()->totalPages() > 1;
    }
    
    public function reset(): void
    {
        Context::clear();
    }
}

/**
 * Legacy sync functions for backward compatibility
 * These are no-ops now since Context handles everything
 */
function zed_sync_context_from_globals(): void
{
    // No longer needed - Context is set directly by controller
}

function zed_sync_globals_from_context(): void
{
    // Use zed_populate_template_globals() instead
    zed_populate_template_globals();
}
