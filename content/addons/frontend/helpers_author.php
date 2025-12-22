<?php
/**
 * Zed CMS — Author Helpers
 * 
 * Pure functions for author data retrieval.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Database;

/**
 * Get author by user ID
 * 
 * @param int $userId User ID
 * @return array|null Author data or null
 */
function zed_get_author(int $userId): ?array
{
    try {
        $db = Database::getInstance();
        $user = $db->queryOne(
            "SELECT id, email, role, created_at FROM users WHERE id = :id LIMIT 1",
            ['id' => $userId]
        );
        
        if (!$user) return null;
        
        // Add computed fields
        $user['display_name'] = zed_extract_name_from_email($user['email']);
        $user['avatar'] = zed_get_author_avatar($userId);
        $user['posts_url'] = zed_get_author_posts_url($userId);
        
        return $user;
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * Get author of a specific post
 * 
 * @param array $post Post data
 * @return array|null Author data or null
 */
function zed_get_post_author(array $post): ?array
{
    $authorId = $post['author_id'] ?? null;
    
    if (!$authorId) {
        return null;
    }
    
    return zed_get_author((int)$authorId);
}

/**
 * Get author display name
 * 
 * @param int $userId User ID
 * @return string Display name
 */
function zed_get_author_name(int $userId): string
{
    $author = zed_get_author($userId);
    return $author['display_name'] ?? 'Unknown Author';
}

/**
 * Get Gravatar URL for user
 * 
 * @param int $userId User ID
 * @param int $size Avatar size in pixels
 * @return string Gravatar URL
 */
function zed_get_author_avatar(int $userId, int $size = 64): string
{
    try {
        $db = Database::getInstance();
        $user = $db->queryOne(
            "SELECT email FROM users WHERE id = :id LIMIT 1",
            ['id' => $userId]
        );
        
        if (!$user) {
            return zed_default_avatar($size);
        }
        
        $hash = md5(strtolower(trim($user['email'])));
        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mp";
    } catch (\Exception $e) {
        return zed_default_avatar($size);
    }
}

/**
 * Get author posts archive URL
 * 
 * @param int $userId User ID
 * @return string Author archive URL
 */
function zed_get_author_posts_url(int $userId): string
{
    $base = \Core\Router::getBasePath();
    return "{$base}/author/{$userId}";
}

/**
 * Get author post count
 * 
 * @param int $userId User ID
 * @param string $status Status filter
 * @return int Post count
 */
function zed_get_author_post_count(int $userId, string $status = 'published'): int
{
    return zed_count_posts([
        'author' => $userId,
        'status' => $status,
        'type' => 'all',
    ]);
}

/**
 * Extract display name from email
 * 
 * @param string $email Email address
 * @return string Display name
 */
function zed_extract_name_from_email(string $email): string
{
    $parts = explode('@', $email);
    $name = $parts[0] ?? 'User';
    
    // Convert underscores/dots to spaces and capitalize
    $name = str_replace(['.', '_', '-'], ' ', $name);
    return ucwords($name);
}

/**
 * Get default avatar URL
 * 
 * @param int $size Size in pixels
 * @return string Default avatar URL
 */
function zed_default_avatar(int $size = 64): string
{
    return "https://www.gravatar.com/avatar/00000000000000000000000000000000?s={$size}&d=mp";
}
