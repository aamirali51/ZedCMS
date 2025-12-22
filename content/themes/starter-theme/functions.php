<?php
/**
 * Starter Theme - functions.php
 * 
 * This file is automatically loaded by Zed CMS when this theme is active.
 * Use it to:
 * - Register custom post types
 * - Add theme settings
 * - Hook into content rendering
 * 
 * @package StarterTheme
 */

declare(strict_types=1);

use Core\Event;

// =============================================================================
// THEME SETUP
// =============================================================================

/**
 * Example: Register a Custom Post Type
 * Uncomment to enable a "Portfolio" post type
 */
// zed_register_post_type('portfolio', [
//     'label' => 'Portfolio',
//     'singular' => 'Project',
//     'icon' => 'work',
//     'supports' => ['title', 'editor', 'featured_image'],
//     'menu_position' => 25,
// ]);

/**
 * Example: Register Theme Settings
 * These will appear in Admin > Settings > Theme tab
 */
zed_add_theme_setting('accent_color', 'Accent Color', 'color', '#4f46e5');
zed_add_theme_setting('show_author_bio', 'Show Author Bio on Posts', 'checkbox', true);
zed_add_theme_setting('footer_text', 'Footer Copyright Text', 'text', '© ' . date('Y') . ' My Website');

// =============================================================================
// THEME HOOKS - Dynamic Content Injection
// =============================================================================

/**
 * Add author bio after post content
 * Only on posts, when show_author_bio is enabled
 */
Event::on('zed_after_content', function(array $post, array $data): void {
    // Only show on posts
    if (($post['type'] ?? '') !== 'post') {
        return;
    }
    
    // Check theme setting
    if (zed_theme_option('show_author_bio', true) !== '1' && zed_theme_option('show_author_bio', true) !== true) {
        return;
    }
    
    // Get author info
    $authorId = $post['author_id'] ?? null;
    if (!$authorId) {
        return;
    }
    
    try {
        $db = \Core\Database::getInstance();
        $author = $db->queryOne("SELECT email FROM zed_users WHERE id = :id", ['id' => $authorId]);
        
        if ($author) {
            $email = $author['email'];
            $name = ucfirst(explode('@', $email)[0]);
            $avatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=80&d=mp';
            
            echo <<<HTML
<div class="author-bio" style="margin-top: 2rem; padding: 1.5rem; background: #f9fafb; border-radius: 0.5rem; display: flex; align-items: center; gap: 1rem;">
    <img src="{$avatar}" alt="{$name}" style="width: 60px; height: 60px; border-radius: 50%;">
    <div>
        <p style="margin: 0; font-weight: 600;">Written by {$name}</p>
        <p style="margin: 0.25rem 0 0; color: #6b7280; font-size: 0.875rem;">Author at this website</p>
    </div>
</div>
HTML;
        }
    } catch (Exception $e) {
        // Silently fail
    }
}, 10);

/**
 * Example: Add related posts section
 * Uncomment to enable
 */
// Event::on('zed_after_content', function(array $post, array $data): void {
//     if (($post['type'] ?? '') !== 'post') return;
//     
//     echo '<div class="related-posts"><h3>Related Posts</h3><p>Coming soon...</p></div>';
// }, 20);
