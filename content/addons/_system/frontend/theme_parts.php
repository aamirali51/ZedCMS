<?php
/**
 * Theme Parts System
 * 
 * Provides template part loading and theme utilities.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

use Core\Router;

// =============================================================================
// THEME PARTS SYSTEM
// =============================================================================

/**
 * Get the path to a theme part file
 * 
 * Looks for files in:
 * 1. Active theme's /parts/ directory
 * 2. Falls back to root directory
 * 
 * @param string $part Part name (e.g., 'head', 'header', 'footer', 'sidebar')
 * @return string|null Full path to part file or null if not found
 */
function zed_get_theme_part(string $part): ?string
{
    $themePath = ZED_ACTIVE_THEME_PATH ?? '';
    
    if (empty($themePath)) {
        return null;
    }
    
    // Try parts directory first (preferred)
    $partFile = $themePath . '/parts/' . $part . '.php';
    if (file_exists($partFile)) {
        return $partFile;
    }
    
    // Try root directory for backwards compatibility
    $rootFile = $themePath . '/' . $part . '.php';
    if (file_exists($rootFile)) {
        return $rootFile;
    }
    
    return null;
}

/**
 * Include a theme part file
 * 
 * Safely includes a theme part with optional variable extraction.
 * 
 * @param string $part Part name (e.g., 'head', 'header', 'footer')
 * @param array $vars Optional variables to make available in the part
 * @return bool True if part was included, false if not found
 * 
 * @example
 *   zed_include_theme_part('header', ['header_style' => 'transparent']);
 *   zed_include_theme_part('footer', ['footer_style' => 'dark']);
 */
function zed_include_theme_part(string $part, array $vars = []): bool
{
    $partFile = zed_get_theme_part($part);
    
    if ($partFile === null) {
        return false;
    }
    
    // Extract variables to make them available in the part
    if (!empty($vars)) {
        extract($vars, EXTR_SKIP);
    }
    
    // Make common variables available
    $base_url = Router::getBasePath();
    $site_name = zed_get_site_name();
    
    include $partFile;
    return true;
}

/**
 * Get the active theme's directory path
 * 
 * @return string Theme directory path
 */
function zed_get_theme_path(): string
{
    return ZED_ACTIVE_THEME_PATH ?? '';
}

/**
 * Check if a theme part exists
 * 
 * @param string $part Part name
 * @return bool True if part exists
 */
function zed_theme_part_exists(string $part): bool
{
    return zed_get_theme_part($part) !== null;
}

/**
 * Get the Tailwind CSS CDN script tag
 * 
 * @param array $extraColors Additional colors to add to config
 * @return string HTML script tags for Tailwind
 */
function zed_tailwind_cdn(array $extraColors = []): string
{
    $brand = zed_theme_option('brand_color', '#6366f1');
    $brandDark = zed_theme_option('brand_color_dark', '#4f46e5');
    
    $colors = array_merge([
        'brand' => $brand,
        'brand-dark' => $brandDark,
    ], $extraColors);
    
    $colorsJson = json_encode($colors);
    
    return <<<HTML
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {$colorsJson},
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        }
    </script>
HTML;
}

/**
 * Get Google Fonts link tags for the theme
 * 
 * @param array $fonts Font families to load
 * @return string HTML link tags
 */
function zed_google_fonts(array $fonts = []): string
{
    if (empty($fonts)) {
        $fonts = [
            'Inter:wght@400;500;600;700;800',
        ];
    }
    
    $fontParam = implode('&family=', $fonts);
    
    return <<<HTML
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={$fontParam}&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400" rel="stylesheet">
HTML;
}
