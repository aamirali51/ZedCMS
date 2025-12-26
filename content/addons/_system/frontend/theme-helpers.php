<?php
/**
 * Zed CMS Theme Helpers
 * 
 * Additional helper functions for premium theme development.
 * Includes: Post Formats, Reading Progress, Social Share, Author Bio.
 * 
 * @package Zed CMS
 * @since 3.2.0
 */

declare(strict_types=1);

use Core\Database;
use Core\Router;

// ============================================================================
// POST FORMATS
// ============================================================================

/**
 * Available post formats
 */
function zed_get_post_formats(): array
{
    return [
        'standard' => ['label' => 'Standard', 'icon' => 'article'],
        'video' => ['label' => 'Video', 'icon' => 'play_circle'],
        'gallery' => ['label' => 'Gallery', 'icon' => 'photo_library'],
        'audio' => ['label' => 'Audio', 'icon' => 'audiotrack'],
        'quote' => ['label' => 'Quote', 'icon' => 'format_quote'],
        'link' => ['label' => 'Link', 'icon' => 'link'],
    ];
}

/**
 * Get the format of a post
 * 
 * @param array $post Post data
 * @return string Format slug (standard, video, gallery, audio, quote, link)
 */
function zed_get_post_format(array $post): string
{
    $data = is_string($post['data'] ?? null) 
        ? json_decode($post['data'], true) 
        : ($post['data'] ?? []);
    
    return $data['format'] ?? 'standard';
}

/**
 * Check if post has a specific format
 * 
 * @param array $post Post data
 * @param string $format Format to check
 * @return bool
 */
function zed_has_post_format(array $post, string $format): bool
{
    return zed_get_post_format($post) === $format;
}

/**
 * Get post format label
 * 
 * @param array $post Post data
 * @return string Human-readable format label
 */
function zed_get_post_format_label(array $post): string
{
    $formats = zed_get_post_formats();
    $format = zed_get_post_format($post);
    return $formats[$format]['label'] ?? 'Standard';
}

/**
 * Get post format icon (Material Symbols)
 * 
 * @param array $post Post data
 * @return string Icon name
 */
function zed_get_post_format_icon(array $post): string
{
    $formats = zed_get_post_formats();
    $format = zed_get_post_format($post);
    return $formats[$format]['icon'] ?? 'article';
}

// ============================================================================
// READING PROGRESS BAR
// ============================================================================

/**
 * Render reading progress bar
 * 
 * Outputs CSS and JS for a reading progress indicator.
 * Place in single.php template.
 * 
 * @param array $options Options:
 *   - color: string Progress bar color (default: primary)
 *   - height: string Bar height (default: 3px)
 *   - position: string 'top' or 'bottom' (default: top)
 *   - container: string Content container selector (default: .post-content)
 * @return void
 */
function zed_reading_progress(array $options = []): void
{
    $defaults = [
        'color' => 'var(--primary, #6366f1)',
        'height' => '3px',
        'position' => 'top',
        'container' => '.post-content',
    ];
    $opts = array_merge($defaults, $options);
    
    echo <<<HTML
<div id="reading-progress-bar" style="
    position: fixed;
    {$opts['position']}: 0;
    left: 0;
    width: 0%;
    height: {$opts['height']};
    background: {$opts['color']};
    z-index: 9999;
    transition: width 0.1s ease-out;
"></div>
<script>
(function() {
    const bar = document.getElementById('reading-progress-bar');
    const content = document.querySelector('{$opts['container']}');
    if (!bar || !content) return;
    
    function updateProgress() {
        const rect = content.getBoundingClientRect();
        const contentTop = rect.top + window.scrollY;
        const contentHeight = rect.height;
        const windowHeight = window.innerHeight;
        const scrollY = window.scrollY;
        
        const start = contentTop - windowHeight;
        const end = contentTop + contentHeight - windowHeight;
        const progress = Math.min(100, Math.max(0, ((scrollY - start) / (end - start)) * 100));
        
        bar.style.width = progress + '%';
    }
    
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
})();
</script>
HTML;
}

// ============================================================================
// SOCIAL SHARE BUTTONS
// ============================================================================

/**
 * Render social share buttons
 * 
 * @param array $post Post data
 * @param array $options Options:
 *   - networks: array Networks to show (default: all)
 *   - style: string 'icons', 'buttons', 'minimal' (default: icons)
 *   - class: string Additional CSS class
 * @return void
 */
function zed_social_share(array $post, array $options = []): void
{
    $defaults = [
        'networks' => ['twitter', 'facebook', 'linkedin', 'pinterest', 'whatsapp', 'email'],
        'style' => 'icons',
        'class' => '',
    ];
    $opts = array_merge($defaults, $options);
    
    $url = urlencode(zed_get_permalink($post));
    $title = urlencode($post['title'] ?? '');
    $data = is_string($post['data'] ?? null) ? json_decode($post['data'], true) : ($post['data'] ?? []);
    $image = urlencode($data['featured_image'] ?? '');
    
    $networks = [
        'twitter' => [
            'url' => "https://twitter.com/intent/tweet?url={$url}&text={$title}",
            'icon' => 'tag',
            'label' => 'Twitter',
            'color' => '#1DA1F2',
        ],
        'facebook' => [
            'url' => "https://www.facebook.com/sharer/sharer.php?u={$url}",
            'icon' => 'group',
            'label' => 'Facebook',
            'color' => '#1877F2',
        ],
        'linkedin' => [
            'url' => "https://www.linkedin.com/sharing/share-offsite/?url={$url}",
            'icon' => 'work',
            'label' => 'LinkedIn',
            'color' => '#0A66C2',
        ],
        'pinterest' => [
            'url' => "https://pinterest.com/pin/create/button/?url={$url}&media={$image}&description={$title}",
            'icon' => 'push_pin',
            'label' => 'Pinterest',
            'color' => '#E60023',
        ],
        'whatsapp' => [
            'url' => "https://wa.me/?text={$title}%20{$url}",
            'icon' => 'chat',
            'label' => 'WhatsApp',
            'color' => '#25D366',
        ],
        'email' => [
            'url' => "mailto:?subject={$title}&body={$url}",
            'icon' => 'mail',
            'label' => 'Email',
            'color' => '#6b7280',
        ],
    ];
    
    $styleClass = 'share-' . $opts['style'];
    echo '<div class="social-share ' . $styleClass . ' ' . htmlspecialchars($opts['class']) . '">';
    
    foreach ($opts['networks'] as $key) {
        if (!isset($networks[$key])) continue;
        $net = $networks[$key];
        
        echo '<a href="' . $net['url'] . '" target="_blank" rel="noopener" ';
        echo 'class="share-link share-' . $key . '" ';
        echo 'title="Share on ' . $net['label'] . '" ';
        echo 'style="--share-color: ' . $net['color'] . '">';
        echo '<span class="material-symbols-outlined">' . $net['icon'] . '</span>';
        if ($opts['style'] === 'buttons') {
            echo '<span class="share-label">' . $net['label'] . '</span>';
        }
        echo '</a>';
    }
    
    echo '</div>';
    
    // Inline styles for share buttons
    echo <<<'CSS'
<style>
.social-share { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.share-link { display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s; }
.share-icons .share-link { padding: 0.5rem; border-radius: 50%; background: #f3f4f6; color: #374151; }
.share-icons .share-link:hover { background: var(--share-color); color: white; }
.share-buttons .share-link { padding: 0.5rem 1rem; border-radius: 6px; background: var(--share-color); color: white; font-size: 0.875rem; }
.share-buttons .share-link:hover { opacity: 0.9; }
.share-minimal .share-link { color: #6b7280; }
.share-minimal .share-link:hover { color: var(--share-color); }
.share-link .material-symbols-outlined { font-size: 20px; }
</style>
CSS;
}

// ============================================================================
// AUTHOR BIO BOX
// ============================================================================

/**
 * Render author bio box
 * 
 * @param array $post Post data (uses author_id)
 * @param array $options Options:
 *   - show_avatar: bool (default: true)
 *   - show_social: bool (default: true)
 *   - avatar_size: int (default: 80)
 *   - class: string Additional CSS class
 * @return void
 */
function zed_author_box(array $post, array $options = []): void
{
    $defaults = [
        'show_avatar' => true,
        'show_social' => true,
        'avatar_size' => 80,
        'class' => '',
    ];
    $opts = array_merge($defaults, $options);
    
    $authorId = $post['author_id'] ?? null;
    if (!$authorId) return;
    
    // Get author data
    try {
        $db = Database::getInstance();
        $author = $db->queryOne(
            "SELECT id, display_name, email, bio, avatar, social_links FROM zed_users WHERE id = :id",
            ['id' => $authorId]
        );
    } catch (\Exception $e) {
        return;
    }
    
    if (!$author) return;
    
    $name = htmlspecialchars($author['display_name'] ?? 'Anonymous');
    $bio = htmlspecialchars($author['bio'] ?? '');
    $avatar = $author['avatar'] ?? '';
    $email = $author['email'] ?? '';
    $social = json_decode($author['social_links'] ?? '{}', true) ?: [];
    
    // Generate avatar URL
    if (empty($avatar) && !empty($email)) {
        $avatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=' . ($opts['avatar_size'] * 2);
    }
    
    $authorUrl = Router::getBasePath() . '/author/' . ($author['id'] ?? 0);
    
    echo '<div class="author-box ' . htmlspecialchars($opts['class']) . '">';
    
    if ($opts['show_avatar'] && $avatar) {
        echo '<div class="author-avatar">';
        echo '<img src="' . htmlspecialchars($avatar) . '" alt="' . $name . '" width="' . $opts['avatar_size'] . '" height="' . $opts['avatar_size'] . '">';
        echo '</div>';
    }
    
    echo '<div class="author-info">';
    echo '<h4 class="author-name"><a href="' . $authorUrl . '">' . $name . '</a></h4>';
    
    if ($bio) {
        echo '<p class="author-bio">' . $bio . '</p>';
    }
    
    if ($opts['show_social'] && !empty($social)) {
        echo '<div class="author-social">';
        $socialIcons = [
            'twitter' => 'tag',
            'facebook' => 'group',
            'instagram' => 'photo_camera',
            'linkedin' => 'work',
            'youtube' => 'play_circle',
            'website' => 'language',
        ];
        foreach ($social as $key => $url) {
            if (empty($url)) continue;
            $icon = $socialIcons[$key] ?? 'link';
            echo '<a href="' . htmlspecialchars($url) . '" target="_blank" rel="noopener" class="social-link">';
            echo '<span class="material-symbols-outlined">' . $icon . '</span>';
            echo '</a>';
        }
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
    
    // Inline styles
    echo <<<'CSS'
<style>
.author-box { display: flex; gap: 1.25rem; padding: 1.5rem; background: #f9fafb; border-radius: 12px; margin: 2rem 0; }
.dark .author-box { background: #1e293b; }
.author-avatar img { border-radius: 50%; }
.author-info { flex: 1; }
.author-name { margin: 0 0 0.5rem; font-size: 1.1rem; }
.author-name a { color: inherit; text-decoration: none; }
.author-name a:hover { text-decoration: underline; }
.author-bio { color: #6b7280; margin: 0 0 0.75rem; font-size: 0.95rem; line-height: 1.6; }
.author-social { display: flex; gap: 0.5rem; }
.author-social .social-link { color: #9ca3af; transition: color 0.2s; }
.author-social .social-link:hover { color: #6366f1; }
</style>
CSS;
}

// ============================================================================
// ESTIMATED READING TIME
// ============================================================================

/**
 * Get estimated reading time
 * 
 * @param array|string $content Post array or content string
 * @param int $wpm Words per minute (default: 200)
 * @return array ['minutes' => int, 'text' => string]
 */
function zed_reading_time(array|string $content, int $wpm = 200): array
{
    $text = is_array($content) ? ($content['content'] ?? '') : $content;
    $text = strip_tags($text);
    $wordCount = str_word_count($text);
    $minutes = max(1, (int)ceil($wordCount / $wpm));
    
    return [
        'minutes' => $minutes,
        'words' => $wordCount,
        'text' => $minutes . ' min read',
    ];
}

// ============================================================================
// BREADCRUMBS
// ============================================================================

/**
 * Render breadcrumb navigation
 * 
 * @param array $items Array of ['label' => string, 'url' => string|null]
 * @param array $options Options:
 *   - separator: string (default: /)
 *   - class: string Additional CSS class
 * @return void
 */
function zed_breadcrumbs(array $items, array $options = []): void
{
    $defaults = [
        'separator' => '/',
        'class' => '',
    ];
    $opts = array_merge($defaults, $options);
    
    $base = Router::getBasePath();
    
    echo '<nav class="breadcrumbs ' . htmlspecialchars($opts['class']) . '" aria-label="Breadcrumb">';
    echo '<ol>';
    
    // Always start with Home
    echo '<li><a href="' . $base . '/">Home</a></li>';
    
    foreach ($items as $i => $item) {
        $isLast = $i === count($items) - 1;
        $label = htmlspecialchars($item['label']);
        
        echo '<li>';
        echo '<span class="separator">' . htmlspecialchars($opts['separator']) . '</span>';
        
        if ($isLast || empty($item['url'])) {
            echo '<span class="current" aria-current="page">' . $label . '</span>';
        } else {
            echo '<a href="' . htmlspecialchars($item['url']) . '">' . $label . '</a>';
        }
        
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
    
    echo <<<'CSS'
<style>
.breadcrumbs ol { display: flex; flex-wrap: wrap; list-style: none; margin: 0; padding: 0; font-size: 0.875rem; }
.breadcrumbs li { display: flex; align-items: center; }
.breadcrumbs .separator { margin: 0 0.5rem; color: #9ca3af; }
.breadcrumbs a { color: #6b7280; text-decoration: none; }
.breadcrumbs a:hover { color: #374151; text-decoration: underline; }
.breadcrumbs .current { color: #374151; font-weight: 500; }
</style>
CSS;
}

// ============================================================================
// POST NAVIGATION (Previous/Next)
// ============================================================================

/**
 * Get adjacent posts (previous and next)
 * 
 * @param array $post Current post
 * @return array ['prev' => array|null, 'next' => array|null]
 */
function zed_get_adjacent_posts(array $post): array
{
    try {
        $db = Database::getInstance();
        $type = $post['type'] ?? 'post';
        $createdAt = $post['created_at'];
        
        // Previous post (older)
        $prev = $db->queryOne(
            "SELECT id, title, slug, data FROM zed_content 
             WHERE type = :type 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             AND created_at < :created_at 
             ORDER BY created_at DESC LIMIT 1",
            ['type' => $type, 'created_at' => $createdAt]
        );
        
        // Next post (newer)
        $next = $db->queryOne(
            "SELECT id, title, slug, data FROM zed_content 
             WHERE type = :type 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             AND created_at > :created_at 
             ORDER BY created_at ASC LIMIT 1",
            ['type' => $type, 'created_at' => $createdAt]
        );
        
        return ['prev' => $prev, 'next' => $next];
    } catch (\Exception $e) {
        return ['prev' => null, 'next' => null];
    }
}

/**
 * Render post navigation (previous/next links)
 * 
 * @param array $post Current post
 * @param array $options Options
 * @return void
 */
function zed_post_navigation(array $post, array $options = []): void
{
    $adjacent = zed_get_adjacent_posts($post);
    $prev = $adjacent['prev'];
    $next = $adjacent['next'];
    
    if (!$prev && !$next) return;
    
    echo '<nav class="post-navigation">';
    
    if ($prev) {
        $prevUrl = zed_get_permalink($prev);
        echo '<a href="' . $prevUrl . '" class="nav-prev">';
        echo '<span class="nav-label"><span class="material-symbols-outlined">arrow_back</span> Previous</span>';
        echo '<span class="nav-title">' . htmlspecialchars($prev['title']) . '</span>';
        echo '</a>';
    } else {
        echo '<span class="nav-prev nav-empty"></span>';
    }
    
    if ($next) {
        $nextUrl = zed_get_permalink($next);
        echo '<a href="' . $nextUrl . '" class="nav-next">';
        echo '<span class="nav-label">Next <span class="material-symbols-outlined">arrow_forward</span></span>';
        echo '<span class="nav-title">' . htmlspecialchars($next['title']) . '</span>';
        echo '</a>';
    } else {
        echo '<span class="nav-next nav-empty"></span>';
    }
    
    echo '</nav>';
    
    echo <<<'CSS'
<style>
.post-navigation { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 3rem 0; padding-top: 2rem; border-top: 1px solid #e5e7eb; }
.nav-prev, .nav-next { display: flex; flex-direction: column; gap: 0.25rem; padding: 1rem; background: #f9fafb; border-radius: 8px; text-decoration: none; transition: background 0.2s; }
.nav-prev:hover, .nav-next:hover { background: #f3f4f6; }
.nav-next { text-align: right; align-items: flex-end; }
.nav-label { display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
.nav-title { color: #374151; font-weight: 500; }
.nav-empty { visibility: hidden; }
.dark .nav-prev, .dark .nav-next { background: #1e293b; }
.dark .nav-title { color: #e2e8f0; }
</style>
CSS;
}
