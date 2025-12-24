<?php
/**
 * Request Context Container
 * 
 * Replaces global variables with a clean, testable object.
 * Maintains backward compatibility via helper functions.
 * 
 * @package ZedCMS\Core
 * @since 3.1.0
 */

declare(strict_types=1);

namespace Core;

/**
 * Context - Request-scoped data container
 * 
 * Provides a clean alternative to global variables for template data.
 * Enables testing, type safety, and future SSR/API features.
 * 
 * @example
 * ```php
 * // Set context (in controller)
 * Context::set([
 *     'type' => 'single',
 *     'object' => $post,
 *     'posts' => [],
 * ]);
 * 
 * // Use in templates
 * $post = Context::get()->post();
 * if (Context::get()->isHome()) { ... }
 * ```
 */
class Context
{
    private static ?Context $instance = null;
    
    private array $data = [];
    private array $flags = [];
    
    /**
     * Set the current request context
     * 
     * @param array $data Context data
     * @return void
     */
    public static function set(array $data): void
    {
        self::$instance = new self($data);
    }
    
    /**
     * Get current context instance
     * 
     * @return Context|null Current context or null if not set
     */
    public static function current(): ?Context
    {
        return self::$instance;
    }
    
    /**
     * Get context or create empty one
     * 
     * @return Context Current context or empty context
     */
    public static function get(): Context
    {
        return self::$instance ?? new self([]);
    }
    
    /**
     * Check if context has been set
     * 
     * @return bool True if context exists
     */
    public static function exists(): bool
    {
        return self::$instance !== null;
    }
    
    /**
     * Clear the current context (for testing)
     * 
     * @return void
     */
    public static function clear(): void
    {
        self::$instance = null;
    }
    
    /**
     * Private constructor - use Context::set() instead
     * 
     * @param array $data Context data
     */
    private function __construct(array $data)
    {
        $this->data = $data;
        
        // Pre-compute context flags
        $type = $data['type'] ?? 'unknown';
        $this->flags = [
            'is_home' => $type === 'home',
            'is_single' => $type === 'single',
            'is_page' => $type === 'page',
            'is_archive' => $type === 'archive',
            'is_404' => $type === '404',
        ];
    }
    
    // =========================================================================
    // Content Accessors
    // =========================================================================
    
    /**
     * Get the current post/page object
     * 
     * @return array|null Post data or null
     */
    public function post(): ?array
    {
        return $this->data['object'] ?? null;
    }
    
    /**
     * Get array of posts (for archives)
     * 
     * @return array Array of posts
     */
    public function posts(): array
    {
        return $this->data['posts'] ?? [];
    }
    
    /**
     * Get rendered HTML content
     * 
     * @return string Rendered content HTML
     */
    public function htmlContent(): string
    {
        return $this->data['html_content'] ?? '';
    }
    
    // =========================================================================
    // Context Flags
    // =========================================================================
    
    /**
     * Check if this is the homepage
     * 
     * @return bool True if homepage
     */
    public function isHome(): bool
    {
        return $this->flags['is_home'] ?? false;
    }
    
    /**
     * Check if this is a single post
     * 
     * @return bool True if single post
     */
    public function isSingle(): bool
    {
        return $this->flags['is_single'] ?? false;
    }
    
    /**
     * Check if this is a static page
     * 
     * @return bool True if page
     */
    public function isPage(): bool
    {
        return $this->flags['is_page'] ?? false;
    }
    
    /**
     * Check if this is an archive listing
     * 
     * @return bool True if archive
     */
    public function isArchive(): bool
    {
        return $this->flags['is_archive'] ?? false;
    }
    
    /**
     * Check if this is a 404 error page
     * 
     * @return bool True if 404
     */
    public function is404(): bool
    {
        return $this->flags['is_404'] ?? false;
    }
    
    // =========================================================================
    // Pagination
    // =========================================================================
    
    /**
     * Get pagination data
     * 
     * @return array Pagination info
     */
    public function pagination(): array
    {
        return $this->data['pagination'] ?? [
            'current_page' => 1,
            'total_pages' => 1,
            'per_page' => 10,
            'total_items' => 0,
        ];
    }
    
    /**
     * Get current page number
     * 
     * @return int Current page (1-indexed)
     */
    public function currentPage(): int
    {
        return $this->pagination()['current_page'];
    }
    
    /**
     * Get total number of pages
     * 
     * @return int Total pages
     */
    public function totalPages(): int
    {
        return $this->pagination()['total_pages'];
    }
    
    /**
     * Check if there are more pages
     * 
     * @return bool True if more pages exist
     */
    public function hasMorePages(): bool
    {
        return $this->currentPage() < $this->totalPages();
    }
    
    /**
     * Check if this is a paginated request
     * 
     * @return bool True if page > 1
     */
    public function isPaginated(): bool
    {
        return $this->currentPage() > 1;
    }
    
    // =========================================================================
    // Metadata
    // =========================================================================
    
    /**
     * Get base URL
     * 
     * @return string Base URL path
     */
    public function baseUrl(): string
    {
        return $this->data['base_url'] ?? '';
    }
    
    /**
     * Get context type
     * 
     * @return string Type: 'home', 'single', 'page', 'archive', '404'
     */
    public function type(): string
    {
        return $this->data['type'] ?? 'unknown';
    }
    
    /**
     * Get query data (raw)
     * 
     * @return array Raw query data
     */
    public function query(): array
    {
        return $this->data['query'] ?? [];
    }
    
    // =========================================================================
    // Generic Accessors
    // =========================================================================
    
    /**
     * Get a value from context
     * 
     * @param string $key Key to get
     * @param mixed $default Default value if not found
     * @return mixed Value or default
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * Check if context has a key
     * 
     * @param string $key Key to check
     * @return bool True if key exists
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
    
    /**
     * Get all context data as array
     * 
     * @return array All context data
     */
    public function toArray(): array
    {
        return $this->data;
    }
    
    /**
     * Get context data as JSON
     * 
     * @return string JSON representation
     */
    public function toJson(): string
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
