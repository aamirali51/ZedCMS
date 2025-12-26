<?php
/**
 * Zed CMS — Utility Helpers
 * 
 * General purpose utility functions.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Event;

// NOTE: zed_reading_time() moved to theme-helpers.php with enhanced features (v3.2.0)

/**
 * Get word count
 * 
 * @param string|array $content Content string or blocks array
 * @return int Word count
 */
function zed_word_count(string|array $content): int
{
    if (is_array($content)) {
        $content = zed_strip_blocks($content);
    }
    
    return str_word_count(strip_tags($content));
}

/**
 * Format datetime as relative "time ago"
 * 
 * @param string $datetime MySQL datetime string
 * @return string Relative time (e.g., "2 hours ago")
 */
function zed_time_ago(string $datetime): string
{
    try {
        $now = new DateTime();
        $date = new DateTime($datetime);
        $diff = $now->diff($date);
        
        if ($diff->y > 0) {
            return $diff->y === 1 ? '1 year ago' : "{$diff->y} years ago";
        }
        if ($diff->m > 0) {
            return $diff->m === 1 ? '1 month ago' : "{$diff->m} months ago";
        }
        if ($diff->d > 0) {
            if ($diff->d === 1) return 'yesterday';
            if ($diff->d < 7) return "{$diff->d} days ago";
            $weeks = floor($diff->d / 7);
            return $weeks === 1 ? '1 week ago' : "{$weeks} weeks ago";
        }
        if ($diff->h > 0) {
            return $diff->h === 1 ? '1 hour ago' : "{$diff->h} hours ago";
        }
        if ($diff->i > 0) {
            return $diff->i === 1 ? '1 minute ago' : "{$diff->i} minutes ago";
        }
        
        return 'just now';
    } catch (\Exception $e) {
        return $datetime;
    }
}

/**
 * Truncate text to specified length
 * 
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $suffix Suffix to append
 * @return string Truncated text
 */
function zed_truncate(string $text, int $length = 160, string $suffix = '...'): string
{
    $text = trim(strip_tags($text));
    
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    
    // Cut at word boundary
    $text = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($text, ' ');
    
    if ($lastSpace !== false && $lastSpace > $length * 0.8) {
        $text = mb_substr($text, 0, $lastSpace);
    }
    
    return trim($text) . $suffix;
}

/**
 * Strip blocks to plain text
 * 
 * @param array|string $content BlockNote blocks or JSON string
 * @return string Plain text
 */
function zed_strip_blocks(array|string $content): string
{
    if (is_string($content)) {
        $decoded = json_decode($content, true);
        $content = is_array($decoded) ? $decoded : [];
    }
    
    $text = '';
    
    foreach ($content as $block) {
        if (!is_array($block)) continue;
        
        // Extract text from block content
        if (isset($block['content']) && is_array($block['content'])) {
            foreach ($block['content'] as $item) {
                if (isset($item['text'])) {
                    $text .= $item['text'] . ' ';
                }
            }
        }
        
        // Handle table rows
        if (isset($block['content']['rows']) && is_array($block['content']['rows'])) {
            foreach ($block['content']['rows'] as $row) {
                foreach ($row['cells'] ?? [] as $cell) {
                    if (is_array($cell)) {
                        foreach ($cell as $item) {
                            if (isset($item['text'])) {
                                $text .= $item['text'] . ' ';
                            }
                        }
                    }
                }
            }
        }
        
        // Recurse into children
        if (isset($block['children']) && is_array($block['children'])) {
            $text .= zed_strip_blocks($block['children']) . ' ';
        }
    }
    
    return trim(preg_replace('/\s+/', ' ', $text));
}

/**
 * Get social share URLs for a post
 * 
 * @param array $post Post data
 * @return array Share URLs keyed by platform
 */
function zed_share_urls(array $post): array
{
    $url = urlencode(zed_get_permalink($post));
    $title = urlencode(zed_get_title($post));
    $excerpt = urlencode(zed_truncate(zed_get_excerpt($post), 100, ''));
    
    $urls = [
        'twitter' => "https://twitter.com/intent/tweet?url={$url}&text={$title}",
        'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$url}",
        'linkedin' => "https://www.linkedin.com/shareArticle?mini=true&url={$url}&title={$title}&summary={$excerpt}",
        'whatsapp' => "https://wa.me/?text={$title}%20{$url}",
        'telegram' => "https://t.me/share/url?url={$url}&text={$title}",
        'email' => "mailto:?subject={$title}&body={$excerpt}%0A%0A{$url}",
        'copy' => zed_get_permalink($post),
    ];
    
    return Event::filter('zed_share_urls', $urls, $post);
}

/**
 * Generate a slug from text
 * 
 * @param string $text Text to slugify
 * @return string URL-safe slug
 */
function zed_slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s_]+/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

/**
 * Format number in human readable format
 * 
 * @param int $number Number to format
 * @return string Formatted number (e.g., "1.2K")
 */
function zed_format_number(int $number): string
{
    if ($number >= 1000000) {
        return round($number / 1000000, 1) . 'M';
    }
    if ($number >= 1000) {
        return round($number / 1000, 1) . 'K';
    }
    return (string)$number;
}
