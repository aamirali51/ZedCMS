<?php
/**
 * Zed CMS — Security Helpers
 * 
 * Nonce/CSRF token generation and verification.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

/**
 * Generate a nonce (number-used-once) for CSRF protection
 * 
 * @param string $action Action name for the nonce
 * @return string Nonce token
 */
function zed_create_nonce(string $action): string
{
    $secret = defined('ZED_NONCE_SECRET') ? ZED_NONCE_SECRET : 'zed_default_secret_change_me';
    $userId = \Core\Auth::check() ? (\Core\Auth::user()['id'] ?? 0) : 0;
    $tick = ceil(time() / (12 * 3600)); // 12-hour validity
    
    $data = $tick . '|' . $action . '|' . $userId;
    return substr(hash_hmac('sha256', $data, $secret), 0, 32);
}

/**
 * Verify a nonce token
 * 
 * @param string $nonce Nonce to verify
 * @param string $action Action the nonce was created for
 * @return bool True if valid
 */
function zed_verify_nonce(string $nonce, string $action): bool
{
    $secret = defined('ZED_NONCE_SECRET') ? ZED_NONCE_SECRET : 'zed_default_secret_change_me';
    $userId = \Core\Auth::check() ? (\Core\Auth::user()['id'] ?? 0) : 0;
    
    // Check current tick and previous tick (allows crossing tick boundary)
    $tick = ceil(time() / (12 * 3600));
    
    for ($i = 0; $i <= 1; $i++) {
        $data = ($tick - $i) . '|' . $action . '|' . $userId;
        $expected = substr(hash_hmac('sha256', $data, $secret), 0, 32);
        
        if (hash_equals($expected, $nonce)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Generate a hidden nonce field for forms
 * 
 * @param string $action Action name
 * @param string $name Field name
 * @return string HTML hidden input
 */
function zed_nonce_field(string $action, string $name = '_zed_nonce'): string
{
    $nonce = zed_create_nonce($action);
    return '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($nonce) . '">';
}

/**
 * Check nonce from request and die if invalid
 * 
 * @param string $action Action to verify
 * @param string $name Field name (default: _zed_nonce)
 * @return bool True if valid (dies if invalid)
 */
function zed_check_nonce(string $action, string $name = '_zed_nonce'): bool
{
    $nonce = $_POST[$name] ?? $_GET[$name] ?? $_SERVER['HTTP_X_ZED_NONCE'] ?? '';
    
    if (!zed_verify_nonce($nonce, $action)) {
        if (zed_is_ajax_request()) {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode(['error' => 'Security check failed']);
            exit;
        }
        die('Security check failed. Please refresh and try again.');
    }
    
    return true;
}

/**
 * Verify AJAX nonce without dying - returns boolean
 * Checks: POST _zed_nonce, JSON body nonce, or X-ZED-NONCE header
 * 
 * @param string $action Action to verify
 * @param array|null $data Optional JSON data already parsed
 * @return bool True if nonce is valid
 */
function zed_verify_ajax_nonce(string $action, ?array $data = null): bool
{
    // Check HTTP header first (preferred for AJAX)
    $nonce = $_SERVER['HTTP_X_ZED_NONCE'] ?? '';
    
    // Fallback to POST/GET
    if (empty($nonce)) {
        $nonce = $_POST['_zed_nonce'] ?? $_POST['nonce'] ?? $_GET['_zed_nonce'] ?? '';
    }
    
    // Fallback to JSON body
    if (empty($nonce) && $data !== null) {
        $nonce = $data['_zed_nonce'] ?? $data['nonce'] ?? '';
    }
    
    return zed_verify_nonce($nonce, $action);
}

/**
 * Check if current request is AJAX
 * 
 * @return bool True if AJAX request
 */
function zed_is_ajax_request(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Generate a unique token (for one-time links, etc.)
 * 
 * @param int $length Token length
 * @return string Random token
 */
function zed_generate_token(int $length = 32): string
{
    return bin2hex(random_bytes($length / 2));
}
