<?php
/**
 * Menu System
 * 
 * Handles navigation menu retrieval and rendering.
 * 
 * @package ZedCMS\System\Frontend
 */

declare(strict_types=1);

use Core\Database;
use Core\Router;

// =============================================================================
// MENU SYSTEM
// =============================================================================

/**
 * Get all menus from the database
 * 
 * @return array Array of all menus
 */
function zed_get_all_menus(): array
{
    try {
        $db = Database::getInstance();
        return $db->query("SELECT * FROM zed_menus ORDER BY name ASC") ?: [];
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get a menu by ID
 * 
 * @param int $id Menu ID
 * @return array|null Menu data or null if not found
 */
function zed_get_menu_by_id(int $id): ?array
{
    try {
        $db = Database::getInstance();
        $menu = $db->queryOne("SELECT * FROM zed_menus WHERE id = :id", ['id' => $id]);
        if ($menu && !empty($menu['items'])) {
            $menu['items'] = json_decode($menu['items'], true) ?: [];
        }
        return $menu ?: null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get a menu by name
 * 
 * @param string $name Menu name (case-insensitive)
 * @return array|null Menu data or null if not found
 */
function zed_get_menu_by_name(string $name): ?array
{
    try {
        $db = Database::getInstance();
        $menu = $db->queryOne("SELECT * FROM zed_menus WHERE LOWER(name) = LOWER(:name)", ['name' => $name]);
        if ($menu && !empty($menu['items'])) {
            $menu['items'] = json_decode($menu['items'], true) ?: [];
        }
        return $menu ?: null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Render a navigation menu by location slug (via nav_menu_locations option)
 * 
 * Usage in themes:
 *   echo render_menu('header');       // By location
 * 
 * @param string $locationSlug The menu location identifier (e.g. 'header')
 * @return string HTML unordered list or empty string if not found
 */
function render_menu(string $locationSlug): string
{
    try {
        $db = Database::getInstance();
        
        $option = $db->queryOne("SELECT option_value FROM zed_options WHERE option_name = 'nav_menu_locations'");
        
        if (!$option || empty($option['option_value'])) {
             return '';
        }
        
        $locations = json_decode($option['option_value'], true);
        if (!isset($locations[$locationSlug])) {
            return '';
        }
        
        $menuId = $locations[$locationSlug];
        
        return zed_menu($menuId);
        
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Render a navigation menu by ID or name - THE MAIN HELPER FOR THEMES
 * 
 * Usage in any theme:
 *   <?= zed_menu('Main Menu') ?>       // By name
 *   <?= zed_menu(1) ?>                 // By ID
 *   <?= zed_menu('Main Menu', ['class' => 'nav-menu']) ?>  // With options
 * 
 * @param int|string $menuIdOrName Menu ID (int) or menu name (string)
 * @param array $options Optional: ['class' => 'custom-class', 'id' => 'nav-id']
 * @return string HTML unordered list or empty string if not found
 */
function zed_menu(int|string $menuIdOrName, array $options = []): string
{
    try {
        // Fetch menu by ID or name
        if (is_int($menuIdOrName)) {
            $menu = zed_get_menu_by_id($menuIdOrName);
        } else {
            $menu = zed_get_menu_by_name($menuIdOrName);
        }
        
        if (!$menu || empty($menu['items'])) {
            return '';
        }
        
        $items = is_array($menu['items']) ? $menu['items'] : [];
        
        if (empty($items)) {
            return '';
        }
        
        // Build attributes
        $menuSlug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $menu['name']));
        $classes = ['zed-menu', "zed-menu-{$menuSlug}"];
        if (!empty($options['class'])) {
            $classes[] = $options['class'];
        }
        $classAttr = implode(' ', $classes);
        $idAttr = !empty($options['id']) ? ' id="' . htmlspecialchars($options['id']) . '"' : '';
        
        // Recursive render function
        $renderItems = function(array $items, int $depth = 0) use (&$renderItems) {
            $html = '';
            $base_url = Router::getBasePath();
            
            foreach ($items as $item) {
                $label = htmlspecialchars($item['label'] ?? '');
                $url = $item['url'] ?? '#';
                $target = htmlspecialchars($item['target'] ?? '_self');
                $children = $item['children'] ?? [];
                
                // Make relative URLs use base path
                if (!str_starts_with($url, 'http') && !str_starts_with($url, '#')) {
                    if (!str_starts_with($url, '/')) {
                        $url = '/' . $url;
                    }
                    if (!str_starts_with($url, $base_url)) {
                        $url = $base_url . $url;
                    }
                }
                $url = htmlspecialchars($url);
                
                $hasChildren = !empty($children);
                $liClass = $hasChildren ? ' class="has-children"' : '';
                
                $html .= "<li{$liClass}>";
                $html .= "<a href=\"{$url}\"" . ($target !== '_self' ? " target=\"{$target}\"" : "") . ">{$label}</a>";
                
                if ($hasChildren) {
                    $html .= '<ul class="sub-menu">';
                    $html .= $renderItems($children, $depth + 1);
                    $html .= '</ul>';
                }
                
                $html .= "</li>\n";
            }
            return $html;
        };
        
        return "<ul class=\"{$classAttr}\"{$idAttr}>\n" . $renderItems($items) . "</ul>";
        
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Get the first available menu (useful for themes that just need "a" menu)
 * 
 * @return array|null First menu or null
 */
function zed_get_primary_menu(): ?array
{
    $menus = zed_get_all_menus();
    return $menus[0] ?? null;
}

/**
 * Render the first available menu (auto-detect)
 * 
 * @param array $options Optional styling options
 * @return string HTML menu
 */
function zed_primary_menu(array $options = []): string
{
    $menu = zed_get_primary_menu();
    if ($menu) {
        return zed_menu((int)$menu['id'], $options);
    }
    return '';
}
