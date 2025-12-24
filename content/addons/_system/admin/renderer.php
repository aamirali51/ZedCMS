<?php
/**
 * Admin Renderer Service
 * 
 * Provides theme-agnostic rendering for admin pages.
 * Decouples admin routes from specific theme implementations.
 * 
 * @package ZedCMS\Admin
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;

/**
 * Admin Renderer - Theme-agnostic rendering service
 */
class AdminRenderer
{
    /**
     * Get the active admin theme name
     * 
     * @return string Theme directory name
     */
    public static function getActiveTheme(): string
    {
        return zed_get_option('admin_theme', 'admin-default');
    }
    
    /**
     * Get the absolute path to the active admin theme
     * 
     * @return string Absolute path to theme directory
     */
    public static function getThemePath(): string
    {
        $themeName = self::getActiveTheme();
        $basePath = dirname(dirname(dirname(__DIR__))) . '/themes';
        $themePath = $basePath . '/' . $themeName;
        
        // Fallback to admin-default if theme doesn't exist
        if (!is_dir($themePath)) {
            $themePath = $basePath . '/admin-default';
        }
        
        return $themePath;
    }
    
    /**
     * Resolve a view file path with fallback
     * 
     * @param string $view View name (e.g., 'addon-settings-list')
     * @return string|null Absolute path to view file, or null if not found
     */
    public static function resolveView(string $view): ?string
    {
        $themePath = self::getThemePath();
        
        // Try views/{view}.php
        $viewPath = $themePath . '/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            return $viewPath;
        }
        
        // Try partials/{view}.php (legacy)
        $partialPath = $themePath . '/partials/' . $view . '.php';
        if (file_exists($partialPath)) {
            return $partialPath;
        }
        
        // Try root {view}.php
        $rootPath = $themePath . '/' . $view . '.php';
        if (file_exists($rootPath)) {
            return $rootPath;
        }
        
        return null;
    }
    
    /**
     * Render a view with data
     * 
     * @param string $view View name
     * @param array $data Data to pass to view
     * @return string Rendered HTML
     */
    public static function render(string $view, array $data = []): string
    {
        $viewPath = self::resolveView($view);
        
        if ($viewPath === null) {
            return self::renderError("View not found: {$view}");
        }
        
        // Extract data to make variables available in view
        extract($data, EXTR_SKIP);
        
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
    
    /**
     * Render a complete admin page with layout
     * 
     * @param string $view View name for content
     * @param array $data Data for the view
     * @param array $layoutData Additional data for layout (page_title, current_page, etc.)
     * @return string Complete HTML page
     */
    public static function renderPage(string $view, array $data = [], array $layoutData = []): string
    {
        $themePath = self::getThemePath();
        $layoutPath = $themePath . '/admin-layout.php';
        
        // Prepare layout data
        $current_user = $layoutData['current_user'] ?? Auth::user();
        $current_page = $layoutData['current_page'] ?? 'dashboard';
        $page_title = $layoutData['page_title'] ?? 'Admin';
        
        // Render the view content
        $viewPath = self::resolveView($view);
        
        if ($viewPath) {
            // Extract data for view
            extract($data, EXTR_SKIP);
            
            ob_start();
            include $viewPath;
            $content_html = ob_get_clean();
        } else {
            $content_html = self::renderError("View not found: {$view}");
        }
        
        // Render with layout
        if (file_exists($layoutPath)) {
            ob_start();
            require $layoutPath;
            $output = ob_get_clean();
            
            // Replace "Content not found" placeholder with actual content
            $output = str_replace(
                '<div class="text-center py-20 text-gray-500">Content not found</div>',
                $content_html,
                $output
            );
            
            return $output;
        }
        
        // Fallback: return content without layout
        return $content_html;
    }
    
    /**
     * Render an error message
     * 
     * @param string $message Error message
     * @return string HTML error display
     */
    private static function renderError(string $message): string
    {
        return <<<HTML
<div class="max-w-2xl mx-auto mt-20">
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-center gap-3 mb-2">
            <span class="material-symbols-outlined text-red-600">error</span>
            <h3 class="text-lg font-semibold text-red-900">Rendering Error</h3>
        </div>
        <p class="text-red-700">{$message}</p>
    </div>
</div>
HTML;
    }
}
