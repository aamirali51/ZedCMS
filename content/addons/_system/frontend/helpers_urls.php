<?php
/**
 * Zed CMS — URL & Asset Helpers
 * 
 * Functions for generating URLs and asset paths.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Router;

/**
 * Get theme URL for assets
 * 
 * @param string $path Optional path within theme
 * @return string Full URL to theme asset
 */
function zed_theme_url(string $path = ''): string
{
    $base = Router::getBasePath();
    $themeName = defined('ZED_ACTIVE_THEME') ? ZED_ACTIVE_THEME : 'starter-theme';
    
    $url = "{$base}/content/themes/{$themeName}";
    
    if ($path) {
        $url .= '/' . ltrim($path, '/');
    }
    
    return $url;
}

/**
 * Get uploads URL
 * 
 * @param string $path Optional path within uploads
 * @return string Full URL to uploads
 */
function zed_uploads_url(string $path = ''): string
{
    $base = Router::getBasePath();
    $url = "{$base}/content/uploads";
    
    if ($path) {
        $url .= '/' . ltrim($path, '/');
    }
    
    return $url;
}

/**
 * Get base URL
 * 
 * @param string $path Optional path to append
 * @return string Full URL
 */
function zed_base_url(string $path = ''): string
{
    $base = Router::getBasePath();
    
    if ($path) {
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
    
    return $base;
}

/**
 * Get admin URL
 * 
 * @param string $path Optional path within admin
 * @return string Admin URL
 */
function zed_admin_url(string $path = ''): string
{
    $base = Router::getBasePath();
    $url = "{$base}/admin";
    
    if ($path) {
        $url .= '/' . ltrim($path, '/');
    }
    
    return $url;
}

/**
 * Get current URL
 * 
 * @param bool $withQuery Include query string
 * @return string Current URL
 */
function zed_current_url(bool $withQuery = true): string
{
    $base = Router::getBasePath();
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    
    if (!$withQuery) {
        $uri = strtok($uri, '?');
    }
    
    // Build full URL
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    return "{$scheme}://{$host}{$uri}";
}

/**
 * Get login URL
 * 
 * @param string|null $redirect Redirect URL after login
 * @return string Login URL
 */
function zed_login_url(?string $redirect = null): string
{
    $url = zed_admin_url('login');
    
    if ($redirect) {
        $url .= '?redirect=' . urlencode($redirect);
    }
    
    return $url;
}

/**
 * Get logout URL
 * 
 * @return string Logout URL
 */
function zed_logout_url(): string
{
    return zed_admin_url('logout');
}

/**
 * Get edit URL for a post
 * 
 * @param array|int $postOrId Post data or ID
 * @return string Editor URL
 */
function zed_edit_url(array|int $postOrId): string
{
    $id = is_array($postOrId) ? ($postOrId['id'] ?? 0) : $postOrId;
    return zed_admin_url("editor?id={$id}");
}

/**
 * Build URL with query parameters
 * 
 * @param string $url Base URL
 * @param array $params Query parameters
 * @return string URL with query string
 */
function zed_url_with_params(string $url, array $params): string
{
    if (empty($params)) {
        return $url;
    }
    
    $separator = str_contains($url, '?') ? '&' : '?';
    return $url . $separator . http_build_query($params);
}
