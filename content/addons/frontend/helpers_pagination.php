<?php
/**
 * Zed CMS — Pagination Helpers
 * 
 * Pure functions for pagination calculations and rendering.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Router;
use Core\Event;

/**
 * Calculate pagination data
 * 
 * @param int $currentPage Current page (1-indexed)
 * @param int $totalItems Total items count
 * @param int $perPage Items per page
 * @return array Pagination data
 */
function zed_get_pagination(int $currentPage, int $totalItems, int $perPage = 10): array
{
    $totalPages = max(1, (int)ceil($totalItems / $perPage));
    $currentPage = max(1, min($currentPage, $totalPages));
    
    $baseUrl = $_SERVER['REQUEST_URI'] ?? '/';
    // Remove existing page param
    $baseUrl = preg_replace('/[?&]page=\d+/', '', $baseUrl);
    $separator = str_contains($baseUrl, '?') ? '&' : '?';
    
    return [
        'current' => $currentPage,
        'total_pages' => $totalPages,
        'total_items' => $totalItems,
        'per_page' => $perPage,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'prev_url' => $currentPage > 1 ? $baseUrl . $separator . 'page=' . ($currentPage - 1) : null,
        'next_url' => $currentPage < $totalPages ? $baseUrl . $separator . 'page=' . ($currentPage + 1) : null,
        'first_url' => $baseUrl . $separator . 'page=1',
        'last_url' => $baseUrl . $separator . 'page=' . $totalPages,
        'offset' => ($currentPage - 1) * $perPage,
    ];
}

/**
 * Render pagination HTML
 * 
 * @param int $currentPage Current page
 * @param int $totalItems Total items
 * @param int $perPage Items per page
 * @param array $options Rendering options
 *   - class: string (container class)
 *   - prev_text: string (default: '← Previous')
 *   - next_text: string (default: 'Next →')
 *   - show_numbers: bool (default: true)
 *   - max_links: int (default: 5)
 * @return string HTML
 */
function zed_pagination(int $currentPage, int $totalItems, int $perPage = 10, array $options = []): string
{
    $defaults = [
        'class' => 'pagination',
        'prev_text' => '← Previous',
        'next_text' => 'Next →',
        'show_numbers' => true,
        'max_links' => 5,
    ];
    
    $options = array_merge($defaults, $options);
    $data = zed_get_pagination($currentPage, $totalItems, $perPage);
    
    if ($data['total_pages'] <= 1) {
        return '';
    }
    
    $html = '<nav class="' . htmlspecialchars($options['class']) . '">';
    $html .= '<ul class="flex items-center gap-2">';
    
    // Previous
    if ($data['has_prev']) {
        $html .= '<li><a href="' . htmlspecialchars($data['prev_url']) . '" class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 transition-colors">' 
               . htmlspecialchars($options['prev_text']) . '</a></li>';
    } else {
        $html .= '<li><span class="px-4 py-2 rounded-lg bg-slate-50 text-slate-400 cursor-not-allowed">' 
               . htmlspecialchars($options['prev_text']) . '</span></li>';
    }
    
    // Page numbers
    if ($options['show_numbers']) {
        $start = max(1, $data['current'] - floor($options['max_links'] / 2));
        $end = min($data['total_pages'], $start + $options['max_links'] - 1);
        $start = max(1, $end - $options['max_links'] + 1);
        
        $baseUrl = $_SERVER['REQUEST_URI'] ?? '/';
        $baseUrl = preg_replace('/[?&]page=\d+/', '', $baseUrl);
        $separator = str_contains($baseUrl, '?') ? '&' : '?';
        
        // First page + ellipsis
        if ($start > 1) {
            $html .= '<li><a href="' . $baseUrl . $separator . 'page=1" class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200">1</a></li>';
            if ($start > 2) {
                $html .= '<li><span class="px-2">...</span></li>';
            }
        }
        
        // Page numbers
        for ($i = $start; $i <= $end; $i++) {
            if ($i === $data['current']) {
                $html .= '<li><span class="w-10 h-10 flex items-center justify-center rounded-lg bg-brand text-white font-bold">' . $i . '</span></li>';
            } else {
                $html .= '<li><a href="' . $baseUrl . $separator . 'page=' . $i . '" class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200">' . $i . '</a></li>';
            }
        }
        
        // Last page + ellipsis
        if ($end < $data['total_pages']) {
            if ($end < $data['total_pages'] - 1) {
                $html .= '<li><span class="px-2">...</span></li>';
            }
            $html .= '<li><a href="' . $baseUrl . $separator . 'page=' . $data['total_pages'] . '" class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200">' . $data['total_pages'] . '</a></li>';
        }
    }
    
    // Next
    if ($data['has_next']) {
        $html .= '<li><a href="' . htmlspecialchars($data['next_url']) . '" class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 transition-colors">' 
               . htmlspecialchars($options['next_text']) . '</a></li>';
    } else {
        $html .= '<li><span class="px-4 py-2 rounded-lg bg-slate-50 text-slate-400 cursor-not-allowed">' 
               . htmlspecialchars($options['next_text']) . '</span></li>';
    }
    
    $html .= '</ul>';
    $html .= '</nav>';
    
    return Event::filter('zed_pagination_html', $html, $data);
}

/**
 * Get adjacent (previous/next) post
 * 
 * @param array $post Current post
 * @param bool $previous True for previous, false for next
 * @return array|null Adjacent post or null
 */
function zed_get_adjacent_post(array $post, bool $previous = true): ?array
{
    try {
        $db = \Core\Database::getInstance();
        
        $createdAt = $post['created_at'] ?? null;
        if (!$createdAt) return null;
        
        $type = $post['type'] ?? 'post';
        $operator = $previous ? '<' : '>';
        $order = $previous ? 'DESC' : 'ASC';
        
        return $db->queryOne(
            "SELECT * FROM zed_content 
             WHERE type = :type 
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'
             AND created_at {$operator} :created_at
             ORDER BY created_at {$order}
             LIMIT 1",
            ['type' => $type, 'created_at' => $createdAt]
        );
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * Get previous post
 * 
 * @param array $post Current post
 * @return array|null Previous post or null
 */
function zed_get_previous_post(array $post): ?array
{
    return zed_get_adjacent_post($post, true);
}

/**
 * Get next post
 * 
 * @param array $post Current post
 * @return array|null Next post or null
 */
function zed_get_next_post(array $post): ?array
{
    return zed_get_adjacent_post($post, false);
}
