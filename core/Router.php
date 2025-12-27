<?php

declare(strict_types=1);

namespace Core;

/**
 * Zed CMS Router (The Auto-API Foundation)
 * 
 * A dynamic, event-driven router that contains NO hardcoded routes.
 * Instead of defining routes here, addons listen to the 'route_request' event
 * and claim URLs they own. This makes the router infinitely extensible.
 */
final class Router
{
    /**
     * Flag to track if any listener handled the request.
     */
    private static bool $handled = false;

    /**
     * The response content from the handler.
     */
    private static mixed $response = null;

    /**
     * Dispatch an incoming request.
     * 
     * This router does NOTHING except fire the 'route_request' event.
     * Addons (like PageManager) will listen and say "I own this URL!"
     * If no listener handles the request, a 404 is returned.
     *
     * @param string $uri    The request URI (e.g., '/blog/my-post').
     * @param string $method The HTTP method (GET, POST, etc.).
     * @return mixed The response from the handler, or triggers 404.
     */
    public static function dispatch(string $uri, string $method = 'GET'): mixed
    {
        // Reset state for this request
        self::$handled = false;
        self::$response = null;

        // Normalize the URI
        $uri = self::normalizeUri($uri);

        // Prepare the request context
        $request = [
            'uri'    => $uri,
            'method' => strtoupper($method),
            'query'  => $_GET,
            'body'   => $_POST,
        ];

        // Fire the route_request event - addons will listen to this!
        // This is the ONLY logic in the router. Pure event-driven design.
        Event::trigger('route_request', $request);

        // Check if any addon handled the request
        /** @phpstan-ignore-next-line Event callbacks modify self::$handled */
        if (self::$handled) {
            return self::$response;
        }
        
        // No handler claimed this URL - fire 404 event
        Event::trigger('route_not_found', $request);
        
        // If still not handled after 404 event, return default 404
        /** @phpstan-ignore-next-line Event callbacks modify self::$handled */
        if (self::$handled) {
            return self::$response;
        }
        
        return self::notFound($uri);
    }

    /**
     * Mark the current request as handled.
     * 
     * Addons should call this when they claim a URL to prevent 404.
     *
     * @param mixed $response The response content to return.
     * @return void
     */
    public static function setHandled(mixed $response = null): void
    {
        self::$handled = true;
        self::$response = $response;
    }

    /**
     * Check if the current request has been handled.
     *
     * @return bool True if handled, false otherwise.
     */
    public static function isHandled(): bool
    {
        return self::$handled;
    }

    /**
     * Get the current response.
     *
     * @return mixed The response content.
     */
    public static function getResponse(): mixed
    {
        return self::$response;
    }

    /**
     * Normalize a URI for consistent matching.
     * - Removes base path (subdirectory)
     * - Removes query string
     * - Ensures leading slash
     * - Removes trailing slash (except for root)
     * - Decodes URL encoding
     *
     * @param string $uri The raw URI.
     * @return string The normalized URI.
     */
    public static function normalizeUri(string $uri): string
    {
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Decode URL encoding
        $uri = urldecode($uri);

        // Auto-detect and strip base path (e.g., /ZedCMS)
        // Use case-insensitive comparison for Windows compatibility
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $basePath = dirname($scriptName);
        if ($basePath !== '/' && $basePath !== '\\') {
            // Case-insensitive check for Windows (URL might be lowercase, folder might be mixed case)
            if (stripos($uri, $basePath) === 0) {
                $uri = substr($uri, strlen($basePath));
            }
        }

        // Ensure leading slash
        if (!str_starts_with($uri, '/')) {
            $uri = '/' . $uri;
        }

        // Remove trailing slash (but keep root as '/')
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        return $uri;
    }

    /**
     * Parse URI segments for easy matching.
     *
     * @param string $uri The URI to parse.
     * @return array<string> Array of segments.
     */
    public static function getSegments(string $uri): array
    {
        $uri = self::normalizeUri($uri);
        $uri = trim($uri, '/');

        if ($uri === '') {
            return [];
        }

        return explode('/', $uri);
    }

    /**
     * Simple pattern matching for dynamic routes.
     * Supports {param} style placeholders.
     *
     * @param string $pattern The route pattern (e.g., '/blog/{slug}').
     * @param string $uri     The actual URI to match.
     * @return array|false Array of matched params, or false if no match.
     */
    public static function matchPattern(string $pattern, string $uri): array|false
    {
        $pattern = self::normalizeUri($pattern);
        $uri = self::normalizeUri($uri);

        // Exact match
        if ($pattern === $uri) {
            return [];
        }

        $patternParts = explode('/', trim($pattern, '/'));
        $uriParts = explode('/', trim($uri, '/'));

        // Must have same number of segments
        if (count($patternParts) !== count($uriParts)) {
            return false;
        }

        $params = [];

        foreach ($patternParts as $i => $part) {
            // Check for {param} placeholder
            if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)\}$/', $part, $matches)) {
                $params[$matches[1]] = $uriParts[$i];
            } elseif ($part !== $uriParts[$i]) {
                // No match
                return false;
            }
        }

        return $params;
    }

    /**
     * Default 404 Not Found response.
     *
     * @param string $uri The requested URI.
     * @return string The 404 message.
     */
    private static function notFound(string $uri): string
    {
        http_response_code(404);

        // Allow filtering the 404 response
        $message = Event::filter('404_message', "404 Not Found: {$uri}");

        return $message;
    }

    /**
     * Get the base path (subdirectory) if running in a subdirectory.
     *
     * @return string The base path (e.g., '/ZedCMS' or '').
     */
    public static function getBasePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $basePath = dirname($scriptName);
        return ($basePath === '/' || $basePath === '\\') ? '' : $basePath;
    }

    /**
     * Generate a full URL with base path.
     *
     * @param string $path The path (e.g., '/admin/login').
     * @return string The full URL with base path.
     */
    public static function url(string $path): string
    {
        $basePath = self::getBasePath();
        
        // Ensure path starts with /
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }
        
        return $basePath . $path;
    }

    /**
     * Redirect to another URL.
     *
     * @param string $url        The URL to redirect to (relative paths will have base path prepended).
     * @param int    $statusCode HTTP status code (301, 302, etc.).
     * @return never
     */
    public static function redirect(string $url, int $statusCode = 302): never
    {
        // If it's a relative path (starts with /), prepend base path
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
            $url = self::url($url);
        }
        
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }

    /**
     * Get the current request URI.
     *
     * @return string The current URI.
     */
    public static function getCurrentUri(): string
    {
        return self::normalizeUri($_SERVER['REQUEST_URI'] ?? '/');
    }

    /**
     * Get the current request method.
     *
     * @return string The HTTP method.
     */
    public static function getCurrentMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }
}

