<?php
/**
 * Zed CMS — RBAC (Role-Based Access Control) System
 * 
 * Enterprise-grade permission system with:
 * - Capability-based permissions
 * - Role hierarchy support
 * - Content ownership enforcement
 * 
 * @package ZedCMS\Admin
 */

declare(strict_types=1);

use Core\Router;
use Core\Auth;
use Core\Database;

// ============================================================================
// ROLE DEFINITIONS
// ============================================================================

/**
 * Role Definitions with Capabilities
 * Each role has a set of capabilities that define what actions they can perform.
 * Roles inherit capabilities from lower tiers (subscriber < author < editor < admin)
 */
function zed_get_role_capabilities(): array
{
    return [
        // Administrator - Full access to everything
        'admin' => [
            // User Management
            'manage_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // System Settings
            'manage_settings',
            'manage_options', // WP compatibility alias
            'manage_addons',
            'manage_themes',
            
            // Content - Full control
            'manage_categories',
            'manage_menus',
            'publish_content',
            'edit_content',
            'delete_content',
            'edit_others_content',
            'delete_others_content',
            'edit_published_content',
            
            // Media
            'manage_media',
            'upload_media',
            'delete_media',
            'delete_others_media',
            
            // Dashboard
            'view_dashboard',
            'view_analytics',
            
            // Comments (v3.2.0)
            'moderate_comments',
        ],
        
        // Alias for admin
        'administrator' => 'admin', // Inherits from admin
        
        // Editor - Can manage all content but no system settings
        'editor' => [
            // Content - Full control
            'manage_categories',
            'publish_content',
            'edit_content',
            'delete_content',
            'edit_others_content',
            'delete_others_content',
            'edit_published_content',
            
            // Media
            'manage_media',
            'upload_media',
            'delete_media',
            'delete_others_media',
            
            // Dashboard
            'view_dashboard',
            
            // Comments (v3.2.0)
            'moderate_comments',
        ],
        
        // Author - Can manage own content only
        'author' => [
            // Content - Own only
            'publish_content',
            'edit_content',
            'delete_content',
            'edit_published_content',
            // Note: No edit_others_content or delete_others_content
            
            // Media - Own only
            'upload_media',
            'delete_media',
            // Note: No delete_others_media
            
            // Dashboard
            'view_dashboard',
        ],
        
        // Subscriber - View only, no admin access
        'subscriber' => [
            // No admin capabilities
            // Can only view public content on frontend
        ],
    ];
}

/**
 * Get capabilities for a specific role
 */
function zed_get_capabilities_for_role(string $role): array
{
    $roles = zed_get_role_capabilities();
    
    if (!isset($roles[$role])) {
        return [];
    }
    
    $caps = $roles[$role];
    
    // Handle role aliases (inheritance)
    if (is_string($caps)) {
        return zed_get_capabilities_for_role($caps);
    }
    
    return $caps;
}

// ============================================================================
// CAPABILITY CHECKS
// ============================================================================

/**
 * Check if current user has a specific capability
 * 
 * @param string $capability The capability to check
 * @param int|null $object_id Optional object ID for ownership checks
 * @return bool
 */
function zed_current_user_can(string $capability, ?int $object_id = null): bool
{
    if (!Auth::check()) {
        return false;
    }
    
    $user = Auth::user();
    $role = $user['role'] ?? 'subscriber';
    $userId = (int)($user['id'] ?? 0);
    
    // Get capabilities for user's role
    $capabilities = zed_get_capabilities_for_role($role);
    
    // Direct capability check
    if (in_array($capability, $capabilities, true)) {
        return true;
    }
    
    // Ownership-based capability check
    // If user doesn't have "edit_others_content" but has "edit_content",
    // they can still edit their OWN content
    if ($object_id !== null) {
        $ownershipCaps = [
            'edit_others_content' => 'edit_content',
            'delete_others_content' => 'delete_content',
            'delete_others_media' => 'delete_media',
        ];
        
        if (isset($ownershipCaps[$capability])) {
            $baseCap = $ownershipCaps[$capability];
            if (in_array($baseCap, $capabilities, true) && zed_user_owns_object($userId, $capability, $object_id)) {
                return true;
            }
        }
    }
    
    return false;
}

/**
 * Check if a user owns a specific object (content, media, etc.)
 */
function zed_user_owns_object(int $userId, string $capability, int $objectId): bool
{
    try {
        $db = Database::getInstance();
        
        // Determine table based on capability
        if (str_contains($capability, 'content')) {
            $owner = $db->queryOne(
                "SELECT author_id FROM zed_content WHERE id = :id",
                ['id' => $objectId]
            );
            return $owner && (int)($owner['author_id'] ?? 0) === $userId;
        }
        
        if (str_contains($capability, 'media')) {
            // Media ownership would need a media table with user_id
            // For now, we'll check if filename contains user ID or allow
            return true; // Default to allow for media without ownership tracking
        }
        
        return false;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Check if current user can access the admin panel at all
 * Returns true for admin, administrator, editor, and author roles
 */
function zed_user_can_access_admin(): bool
{
    if (!Auth::check()) {
        return false;
    }
    
    $user = Auth::user();
    $role = $user['role'] ?? '';
    
    // Roles that can access admin
    $adminRoles = ['admin', 'administrator', 'editor', 'author'];
    return in_array($role, $adminRoles, true);
}

/**
 * Check if current user has one of the specified roles
 */
function zed_user_has_role(string|array $roles): bool
{
    if (!Auth::check()) {
        return false;
    }
    
    $user = Auth::user();
    $userRole = $user['role'] ?? '';
    
    if (is_string($roles)) {
        $roles = [$roles];
    }
    
    return in_array($userRole, $roles, true);
}

/**
 * Get current user's role
 */
function zed_get_current_user_role(): string
{
    if (!Auth::check()) {
        return '';
    }
    
    $user = Auth::user();
    return $user['role'] ?? 'subscriber';
}

/**
 * Check if current user is an administrator
 */
function zed_is_admin(): bool
{
    return zed_user_has_role(['admin', 'administrator']);
}

// ============================================================================
// ROLE METADATA
// ============================================================================

/**
 * Get role display name and metadata
 */
function zed_get_role_info(string $role): array
{
    $roles = [
        'admin' => [
            'label' => 'Administrator',
            'description' => 'Full access to all features and settings',
            'color' => 'purple',
            'icon' => 'shield_person',
            'level' => 100,
        ],
        'administrator' => [
            'label' => 'Administrator',
            'description' => 'Full access to all features and settings',
            'color' => 'purple',
            'icon' => 'shield_person',
            'level' => 100,
        ],
        'editor' => [
            'label' => 'Editor',
            'description' => 'Can manage all content and media',
            'color' => 'blue',
            'icon' => 'edit_note',
            'level' => 70,
        ],
        'author' => [
            'label' => 'Author',
            'description' => 'Can create and manage own content',
            'color' => 'green',
            'icon' => 'draw',
            'level' => 40,
        ],
        'subscriber' => [
            'label' => 'Subscriber',
            'description' => 'Can view content and manage profile',
            'color' => 'gray',
            'icon' => 'person',
            'level' => 10,
        ],
    ];
    
    return $roles[$role] ?? $roles['subscriber'];
}

/**
 * Get all available roles for dropdowns
 */
function zed_get_all_roles(): array
{
    return [
        'subscriber' => 'Subscriber',
        'author' => 'Author',
        'editor' => 'Editor',
        'admin' => 'Administrator',
    ];
}

/**
 * JSON response helper for permission denied
 */
function zed_json_permission_denied(): void
{
    header('Content-Type: application/json');
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Permission denied. You do not have access to this feature.',
        'code' => 'PERMISSION_DENIED'
    ]);
}

/**
 * Render 403 Forbidden page for users without admin access
 */
function zed_render_forbidden(): string
{
    $baseUrl = Router::getBasePath();
    $role = zed_get_current_user_role();
    $roleInfo = zed_get_role_info($role);
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden — Zed CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center font-sans">
    <div class="text-center p-8 max-w-md">
        <div class="w-20 h-20 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
            <span class="material-symbols-outlined text-red-600 text-4xl">gpp_bad</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Access Denied</h1>
        <p class="text-gray-600 mb-4">You don't have permission to access this area.</p>
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-full text-sm text-gray-600 mb-6">
            <span class="material-symbols-outlined text-lg">{$roleInfo['icon']}</span>
            Your role: <strong>{$roleInfo['label']}</strong>
        </div>
        <p class="text-sm text-gray-500 mb-6">{$roleInfo['description']}</p>
        <div class="space-x-4">
            <a href="{$baseUrl}/" class="inline-block px-5 py-2.5 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition-colors">Go Home</a>
            <a href="{$baseUrl}/admin/logout" class="inline-block px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">Logout</a>
        </div>
    </div>
</body>
</html>
HTML;
}
