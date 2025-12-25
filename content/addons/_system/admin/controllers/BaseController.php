<?php
/**
 * Base Controller
 * 
 * Abstract base class for all admin controllers.
 * Provides common helper methods for rendering, JSON responses, redirects, etc.
 * 
 * @package ZedCMS\Admin\Controllers
 * @since 3.2.0
 */

declare(strict_types=1);

namespace Admin\Controllers;

use Core\Database;

/**
 * BaseController - Abstract base for admin controllers
 * 
 * Provides common functionality for all admin controllers:
 * - Rendering admin pages
 * - JSON responses
 * - Redirects
 * - Permission checks
 */
abstract class BaseController
{
    /**
     * Render an admin page
     * 
     * @param string $view View name (without .php extension)
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function render(string $view, array $data = []): void
    {
        // Use AdminRenderer for proper layout integration
        if (class_exists('AdminRenderer')) {
            echo \AdminRenderer::renderPage($view, $data, [
                'page_title' => 'Content',
                'current_page' => 'content'
            ]);
        } else {
            // Fallback if AdminRenderer not available
            extract($data);
            $viewPath = __DIR__ . '/../views/' . $view . '.php';
            if (file_exists($viewPath)) {
                require $viewPath;
            } else {
                echo "View not found: $view";
            }
        }
    }
    
    /**
     * Send JSON response
     * 
     * @param mixed $data Data to encode as JSON
     * @param int $status HTTP status code
     * @return void
     */
    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Send success JSON response
     * 
     * @param mixed $data Optional data
     * @param string $message Optional message
     * @return void
     */
    protected function success(mixed $data = null, string $message = ''): void
    {
        $response = ['success' => true];
        
        if ($message) {
            $response['message'] = $message;
        }
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        $this->json($response);
    }
    
    /**
     * Send error JSON response
     * 
     * @param string $message Error message
     * @param int $status HTTP status code
     * @return void
     */
    protected function error(string $message, int $status = 400): void
    {
        $this->json([
            'success' => false,
            'error' => $message
        ], $status);
    }
    
    /**
     * Redirect to URL
     * 
     * @param string $url URL to redirect to
     * @return void
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Render forbidden page
     * 
     * @return void
     */
    protected function forbidden(): void
    {
        if (function_exists('zed_render_forbidden')) {
            zed_render_forbidden();
        } else {
            http_response_code(403);
            echo 'Forbidden';
        }
        exit;
    }
    
    /**
     * Get database instance
     * 
     * @return Database
     */
    protected function db(): Database
    {
        return Database::getInstance();
    }
    
    /**
     * Get current user ID
     * 
     * @return int
     */
    protected function currentUserId(): int
    {
        return (int)($_SESSION['user_id'] ?? 0);
    }
    
    /**
     * Check if user has capability
     * 
     * @param string $capability Capability to check
     * @return bool
     */
    protected function can(string $capability): bool
    {
        if (function_exists('zed_current_user_can')) {
            return zed_current_user_can($capability);
        }
        return false;
    }
    
    /**
     * Get input from JSON body
     * 
     * @return array
     */
    protected function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }
    
    /**
     * Validate required fields
     * 
     * @param array $data Data to validate
     * @param array $required Required field names
     * @return bool
     */
    protected function validateRequired(array $data, array $required): bool
    {
        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                return false;
            }
        }
        return true;
    }
}
