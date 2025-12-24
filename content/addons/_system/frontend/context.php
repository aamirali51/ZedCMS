<?php
/**
 * Template Context System
 * 
 * Replaces global variables with a proper registry object.
 * Provides IDE-friendly access and prevents addon collisions.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

/**
 * Template Context Registry
 * 
 * Instead of:
 *   global $post, $is_home;
 *   echo $post['title'];
 * 
 * Use:
 *   echo zed_context()->post('title');
 *   if (zed_context()->is_home()) { ... }
 */
class ZedContext
{
    /**
     * Singleton instance
     */
    private static ?ZedContext $instance = null;
    
    /**
     * Context data storage
     * @var array<string, mixed>
     */
    private array $data = [];
    
    /**
     * Get singleton instance
     */
    public static function getInstance(): ZedContext
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Private constructor (singleton)
     */
    private function __construct() {}
    
    // =========================================================================
    // SETTERS (for routes.php to populate)
    // =========================================================================
    
    /**
     * Set a context value
     */
    public function set(string $key, mixed $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }
    
    /**
     * Set multiple context values
     */
    public function setMany(array $values): self
    {
        foreach ($values as $key => $value) {
            $this->data[$key] = $value;
        }
        return $this;
    }
    
    /**
     * Get raw value
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * Check if key exists
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
    
    /**
     * Get all data (for backward compat with extract())
     */
    public function all(): array
    {
        return $this->data;
    }
    
    // =========================================================================
    // TYPED GETTERS (IDE-friendly)
    // =========================================================================
    
    /**
     * Get the current post (single post/page)
     * 
     * @param string|null $field Optional field to get (e.g., 'title', 'slug')
     * @return mixed Post array, field value, or null
     */
    public function post(?string $field = null): mixed
    {
        $post = $this->data['post'] ?? null;
        if ($field !== null && is_array($post)) {
            return $post[$field] ?? null;
        }
        return $post;
    }
    
    /**
     * Get post data (JSON column)
     */
    public function postData(?string $field = null): mixed
    {
        $post = $this->data['post'] ?? null;
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
    
    /**
     * Get posts list (archive, home, blog)
     */
    public function posts(): array
    {
        return $this->data['posts'] ?? [];
    }
    
    /**
     * Get rendered HTML content
     */
    public function htmlContent(): string
    {
        return $this->data['htmlContent'] ?? '';
    }
    
    /**
     * Get archive title
     */
    public function archiveTitle(): string
    {
        return $this->data['archive_title'] ?? '';
    }
    
    /**
     * Get post type
     */
    public function postType(): string
    {
        return $this->data['post_type'] ?? 'post';
    }
    
    /**
     * Get post type label
     */
    public function postTypeLabel(): string
    {
        return $this->data['post_type_label'] ?? 'Posts';
    }
    
    // =========================================================================
    // BOOLEAN CONDITIONALS
    // =========================================================================
    
    public function isHome(): bool
    {
        return (bool)($this->data['is_home'] ?? false);
    }
    
    public function isSingle(): bool
    {
        return (bool)($this->data['is_single'] ?? false);
    }
    
    public function isPage(): bool
    {
        return (bool)($this->data['is_page'] ?? false);
    }
    
    public function isArchive(): bool
    {
        return (bool)($this->data['is_archive'] ?? false);
    }
    
    public function is404(): bool
    {
        return (bool)($this->data['is_404'] ?? false);
    }
    
    public function isBlog(): bool
    {
        return (bool)($this->data['is_blog'] ?? false);
    }
    
    // =========================================================================
    // PAGINATION
    // =========================================================================
    
    public function currentPage(): int
    {
        return (int)($this->data['current_page'] ?? 1);
    }
    
    public function totalPages(): int
    {
        return (int)($this->data['total_pages'] ?? 1);
    }
    
    public function hasPagination(): bool
    {
        return $this->totalPages() > 1;
    }
    
    // =========================================================================
    // RESET (for testing)
    // =========================================================================
    
    public function reset(): void
    {
        $this->data = [];
    }
}

// =============================================================================
// GLOBAL HELPER FUNCTION
// =============================================================================

/**
 * Get the template context instance
 * 
 * Usage:
 *   $title = zed_context()->post('title');
 *   if (zed_context()->isHome()) { ... }
 *   $posts = zed_context()->posts();
 * 
 * @return ZedContext
 */
function zed_context(): ZedContext
{
    return ZedContext::getInstance();
}

// =============================================================================
// BACKWARD COMPATIBILITY - Sync with globals
// =============================================================================

/**
 * Populate context from legacy globals (called before template render)
 * This maintains compatibility with existing themes using globals.
 */
function zed_sync_context_from_globals(): void
{
    global $post, $posts, $htmlContent, $is_home, $is_single, $is_page, $is_archive, $is_404, $is_blog;
    global $post_type, $post_type_label, $archive_title, $current_page, $total_pages;
    
    $ctx = zed_context();
    
    if (isset($post)) $ctx->set('post', $post);
    if (isset($posts)) $ctx->set('posts', $posts);
    if (isset($htmlContent)) $ctx->set('htmlContent', $htmlContent);
    if (isset($is_home)) $ctx->set('is_home', $is_home);
    if (isset($is_single)) $ctx->set('is_single', $is_single);
    if (isset($is_page)) $ctx->set('is_page', $is_page);
    if (isset($is_archive)) $ctx->set('is_archive', $is_archive);
    if (isset($is_404)) $ctx->set('is_404', $is_404);
    if (isset($is_blog)) $ctx->set('is_blog', $is_blog);
    if (isset($post_type)) $ctx->set('post_type', $post_type);
    if (isset($post_type_label)) $ctx->set('post_type_label', $post_type_label);
    if (isset($archive_title)) $ctx->set('archive_title', $archive_title);
    if (isset($current_page)) $ctx->set('current_page', $current_page);
    if (isset($total_pages)) $ctx->set('total_pages', $total_pages);
}

/**
 * Populate globals from context (for legacy theme compatibility)
 * Call this before requiring a theme template.
 */
function zed_sync_globals_from_context(): void
{
    global $post, $posts, $htmlContent, $is_home, $is_single, $is_page, $is_archive, $is_404, $is_blog;
    global $post_type, $post_type_label, $archive_title, $current_page, $total_pages;
    
    $ctx = zed_context();
    
    $post = $ctx->post();
    $posts = $ctx->posts();
    $htmlContent = $ctx->htmlContent();
    $is_home = $ctx->isHome();
    $is_single = $ctx->isSingle();
    $is_page = $ctx->isPage();
    $is_archive = $ctx->isArchive();
    $is_404 = $ctx->is404();
    $is_blog = $ctx->isBlog();
    $post_type = $ctx->postType();
    $post_type_label = $ctx->postTypeLabel();
    $archive_title = $ctx->archiveTitle();
    $current_page = $ctx->currentPage();
    $total_pages = $ctx->totalPages();
}
