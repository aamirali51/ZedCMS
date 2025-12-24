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
    private static ?ZedContext $instance = null;
    private array $data = [];
    
    public static function getInstance(): ZedContext
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {}
    
    public function set(string $key, mixed $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }
    
    public function setMany(array $values): self
    {
        foreach ($values as $key => $value) {
            $this->data[$key] = $value;
        }
        return $this;
    }
    
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
    
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
    
    public function all(): array
    {
        return $this->data;
    }
    
    public function post(?string $field = null): mixed
    {
        $post = $this->data['post'] ?? null;
        if ($field !== null && is_array($post)) {
            return $post[$field] ?? null;
        }
        return $post;
    }
    
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
    
    public function posts(): array
    {
        return $this->data['posts'] ?? [];
    }
    
    public function htmlContent(): string
    {
        return $this->data['htmlContent'] ?? '';
    }
    
    public function archiveTitle(): string
    {
        return $this->data['archive_title'] ?? '';
    }
    
    public function postType(): string
    {
        return $this->data['post_type'] ?? 'post';
    }
    
    public function postTypeLabel(): string
    {
        return $this->data['post_type_label'] ?? 'Posts';
    }
    
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
    
    public function reset(): void
    {
        $this->data = [];
    }
}

/**
 * Get the template context instance
 */
function zed_context(): ZedContext
{
    return ZedContext::getInstance();
}

/**
 * Populate context from legacy globals (called before template render)
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
