<?php
/**
 * Zed CMS — Taxonomy Helpers
 * 
 * Pure functions for categories and taxonomies.
 * 
 * @package ZedCMS\Helpers
 */

declare(strict_types=1);

use Core\Database;
use Core\Router;

/**
 * Get all categories
 * 
 * @param array $args Query arguments
 *   - orderby: string (default: 'name')
 *   - order: string (default: 'ASC')
 *   - hide_empty: bool (default: false)
 * @return array Categories
 */
function zed_get_categories(array $args = []): array
{
    $defaults = [
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
    ];
    
    $args = array_merge($defaults, $args);
    
    try {
        $db = Database::getInstance();
        
        $orderby = in_array($args['orderby'], ['name', 'slug', 'id', 'created_at']) 
            ? $args['orderby'] : 'name';
        $order = strtoupper($args['order']) === 'DESC' ? 'DESC' : 'ASC';
        
        $categories = $db->query(
            "SELECT * FROM zed_categories ORDER BY {$orderby} {$order}"
        );
        
        // Add post count to each
        foreach ($categories as &$cat) {
            $cat['post_count'] = zed_get_category_post_count($cat['id']);
            $cat['url'] = zed_category_link($cat);
        }
        
        // Filter empty if requested
        if ($args['hide_empty']) {
            $categories = array_filter($categories, fn($c) => $c['post_count'] > 0);
        }
        
        return array_values($categories);
    } catch (\Exception $e) {
        return [];
    }
}

/**
 * Get single category by ID or slug
 * 
 * @param int|string $idOrSlug Category ID or slug
 * @return array|null Category data or null
 */
function zed_get_category(int|string $idOrSlug): ?array
{
    try {
        $db = Database::getInstance();
        
        if (is_numeric($idOrSlug)) {
            $cat = $db->queryOne(
                "SELECT * FROM zed_categories WHERE id = :id LIMIT 1",
                ['id' => (int)$idOrSlug]
            );
        } else {
            $cat = $db->queryOne(
                "SELECT * FROM zed_categories WHERE slug = :slug LIMIT 1",
                ['slug' => $idOrSlug]
            );
        }
        
        if (!$cat) return null;
        
        $cat['post_count'] = zed_get_category_post_count($cat['id']);
        $cat['url'] = zed_category_link($cat);
        
        return $cat;
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * Get categories for a specific post
 * 
 * @param array $post Post data
 * @return array Categories array
 */
function zed_get_post_categories(array $post): array
{
    $data = zed_parse_post_data($post);
    $categorySlugs = $data['categories'] ?? [];
    
    if (empty($categorySlugs) || !is_array($categorySlugs)) {
        return [];
    }
    
    $categories = [];
    foreach ($categorySlugs as $slug) {
        $cat = zed_get_category($slug);
        if ($cat) {
            $categories[] = $cat;
        }
    }
    
    return $categories;
}

/**
 * Get category archive link
 * 
 * @param array $category Category data
 * @return string Category URL
 */
function zed_category_link(array $category): string
{
    $base = Router::getBasePath();
    $slug = $category['slug'] ?? '';
    return "{$base}/category/{$slug}";
}

/**
 * Get post count for category
 * 
 * @param int $categoryId Category ID
 * @return int Post count
 */
function zed_get_category_post_count(int $categoryId): int
{
    try {
        $db = Database::getInstance();
        
        // Get category slug first
        $cat = $db->queryOne(
            "SELECT slug FROM zed_categories WHERE id = :id",
            ['id' => $categoryId]
        );
        
        if (!$cat) return 0;
        
        // Count posts with this category
        $result = $db->queryOne(
            "SELECT COUNT(*) as total FROM zed_content 
             WHERE JSON_CONTAINS(JSON_EXTRACT(data, '$.categories'), :slug)
             AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) = 'published'",
            ['slug' => json_encode($cat['slug'])]
        );
        
        return (int)($result['total'] ?? 0);
    } catch (\Exception $e) {
        return 0;
    }
}

/**
 * Render category list HTML for a post
 * 
 * @param array $post Post data
 * @param array $options Rendering options
 *   - separator: string (default: ', ')
 *   - class: string (default: '')
 *   - link: bool (default: true)
 * @return string HTML
 */
function zed_post_categories_html(array $post, array $options = []): string
{
    $defaults = [
        'separator' => ', ',
        'class' => '',
        'link' => true,
    ];
    
    $options = array_merge($defaults, $options);
    $categories = zed_get_post_categories($post);
    
    if (empty($categories)) {
        return '';
    }
    
    $items = [];
    foreach ($categories as $cat) {
        if ($options['link']) {
            $class = $options['class'] ? ' class="' . htmlspecialchars($options['class']) . '"' : '';
            $items[] = '<a href="' . htmlspecialchars($cat['url']) . '"' . $class . '>' 
                     . htmlspecialchars($cat['name']) . '</a>';
        } else {
            $items[] = htmlspecialchars($cat['name']);
        }
    }
    
    return implode($options['separator'], $items);
}
