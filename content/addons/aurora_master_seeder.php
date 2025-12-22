<?php
/**
 * Aurora Master Seeder Addon
 * 
 * Seeds demo content when activated to showcase the Aurora framework.
 * Automatically removes demo content when disabled.
 * 
 * @addon_name Aurora Master Seeder
 * @addon_description Seeds demo content, menus, and users for the Aurora framework
 * @addon_version 1.1.0
 * @addon_author Zed CMS Team
 * @addon_type utility
 */

declare(strict_types=1);

use Core\Event;
use Core\Database;
use Core\Auth;

// =============================================================================
// SEEDER CONFIGURATION
// =============================================================================

define('AURORA_SEEDER_VERSION', '1.1.0');
define('AURORA_SEEDER_OPTION', 'aurora_seeder_completed');
define('AURORA_SEEDER_IDS', 'aurora_seeder_content_ids'); // Track seeded IDs for cleanup

// =============================================================================
// AUTO-RUN ON ADDON LOAD
// =============================================================================

// Try on app_ready
Event::on('app_ready', function(): void {
    aurora_try_auto_seed();
}, 100);

// Also try on admin_init as a fallback
Event::on('admin_init', function(): void {
    aurora_try_auto_seed();
}, 100);

/**
 * Attempt to auto-seed if conditions are met
 */
function aurora_try_auto_seed(): void
{
    static $attempted = false;
    if ($attempted) return;
    $attempted = true;
    
    // Check if already seeded
    if (zed_get_option(AURORA_SEEDER_OPTION, '') === AURORA_SEEDER_VERSION) {
        return;
    }
    
    // Only seed if we're logged into admin
    if (!Auth::check()) {
        return;
    }
    
    // Run the seeder
    aurora_run_seeder(true);
}

// =============================================================================
// MANUAL TRIGGER VIA ADMIN API
// =============================================================================

Event::on('route_request', function(array $request): void {
    $uri = $request['uri'] ?? '';
    
    // Seed endpoint
    if ($uri === '/admin/api/aurora-seed') {
        header('Content-Type: application/json');
        
        if (!Auth::check() || !zed_current_user_can('manage_settings')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        // Force reseed by clearing the completion flag
        $force = isset($_GET['force']) || ($request['method'] ?? '') === 'POST';
        
        $result = aurora_run_seeder($force);
        echo json_encode($result);
        exit;
    }
    
    // Cleanup endpoint
    if ($uri === '/admin/api/aurora-cleanup') {
        header('Content-Type: application/json');
        
        if (!Auth::check() || !zed_current_user_can('manage_settings')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $result = aurora_cleanup_content();
        echo json_encode($result);
        exit;
    }
});

// =============================================================================
// MAIN SEEDER FUNCTION
// =============================================================================

function aurora_run_seeder(bool $force = false): array
{
    // Clear flag if forcing
    if ($force) {
        aurora_delete_option(AURORA_SEEDER_OPTION);
    }
    
    // Check if already seeded
    if (zed_get_option(AURORA_SEEDER_OPTION, '') === AURORA_SEEDER_VERSION) {
        return [
            'success' => true,
            'message' => 'Already seeded. Use ?force=1 to reseed.',
            'stats' => [],
        ];
    }
    
    $stats = [
        'posts' => 0,
        'errors' => [],
    ];
    
    try {
        $db = Database::getInstance();
        $pdo = $db->getPdo();
        $seededIds = [];
        
        // Get user ID
        $userId = Auth::user()['id'] ?? 1;
        
        // =====================================================================
        // SEED POSTS
        // =====================================================================
        $posts = aurora_get_demo_posts();
        
        foreach ($posts as $post) {
            try {
                // Check if slug exists
                $exists = $db->queryValue(
                    "SELECT id FROM zed_content WHERE slug = :slug",
                    ['slug' => $post['slug']]
                );
                
                if ($exists) {
                    continue; // Skip existing
                }
                
                // Insert the post
                $stmt = $pdo->prepare(
                    "INSERT INTO zed_content (slug, type, title, data, author_id, created_at, updated_at) 
                     VALUES (:slug, :type, :title, :data, :author_id, NOW(), NOW())"
                );
                $stmt->execute([
                    'slug' => $post['slug'],
                    'type' => $post['type'],
                    'title' => $post['title'],
                    'data' => json_encode($post['data']),
                    'author_id' => $userId,
                ]);
                
                $newId = (int)$pdo->lastInsertId();
                $seededIds[] = $newId;
                $stats['posts']++;
                
            } catch (Exception $e) {
                $stats['errors'][] = "Post '{$post['slug']}': " . $e->getMessage();
            }
        }
        
        // Save seeded IDs for cleanup
        aurora_set_option(AURORA_SEEDER_IDS, json_encode($seededIds));
        
        // Mark as completed
        aurora_set_option(AURORA_SEEDER_OPTION, AURORA_SEEDER_VERSION);
        
        return [
            'success' => true,
            'message' => "Seeded {$stats['posts']} posts successfully!",
            'stats' => $stats,
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Seeder error: ' . $e->getMessage(),
            'stats' => $stats,
        ];
    }
}

// =============================================================================
// CLEANUP FUNCTION (Called when addon is disabled)
// =============================================================================

function aurora_cleanup_content(): array
{
    try {
        $db = Database::getInstance();
        $pdo = $db->getPdo();
        
        // Get seeded content IDs
        $idsJson = zed_get_option(AURORA_SEEDER_IDS, '[]');
        $ids = json_decode($idsJson, true) ?: [];
        
        $deleted = 0;
        
        if (!empty($ids)) {
            // Delete seeded content
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $pdo->prepare("DELETE FROM zed_content WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $deleted = $stmt->rowCount();
        }
        
        // Clear seeder options
        aurora_delete_option(AURORA_SEEDER_OPTION);
        aurora_delete_option(AURORA_SEEDER_IDS);
        
        return [
            'success' => true,
            'message' => "Cleaned up $deleted demo posts.",
            'deleted' => $deleted,
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Cleanup error: ' . $e->getMessage(),
        ];
    }
}

// =============================================================================
// DEMO POSTS DATA
// =============================================================================

function aurora_get_demo_posts(): array
{
    return [
        // =====================================================================
        // BLOG POSTS
        // =====================================================================
        [
            'title' => 'Welcome to Aurora Framework',
            'slug' => 'welcome-to-aurora',
            'type' => 'post',
            'data' => [
                'status' => 'published',
                'excerpt' => 'Discover the ultimate starter framework for ZedCMS with Custom Post Types, Theme Settings, and modern developer experience.',
                'content' => [
                    ['id' => 'p1', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Aurora is more than just a theme — it\'s a complete framework that showcases everything ZedCMS can do. Built with PHP 8.2+ strict types and modern best practices.', 'styles' => []]], 'children' => []],
                    ['id' => 'h1', 'type' => 'heading', 'props' => ['level' => 2, 'textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'What\'s Included', 'styles' => []]], 'children' => []],
                    ['id' => 'bl1', 'type' => 'bulletListItem', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Custom Post Types: Portfolio & Testimonials', 'styles' => []]], 'children' => []],
                    ['id' => 'bl2', 'type' => 'bulletListItem', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Theme Settings API with color pickers and media uploads', 'styles' => []]], 'children' => []],
                    ['id' => 'bl3', 'type' => 'bulletListItem', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Scoped Hooks for context-aware functionality', 'styles' => []]], 'children' => []],
                    ['id' => 'p2', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Get started by exploring the admin panel and customizing your Aurora options.', 'styles' => []]], 'children' => []],
                ],
            ],
        ],
        
        [
            'title' => 'Our Development Services',
            'slug' => 'development-services',
            'type' => 'post',
            'data' => [
                'status' => 'published',
                'excerpt' => 'We offer comprehensive web development services using modern technologies.',
                'content' => [
                    ['id' => 'p1', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'From custom CMS solutions to full-stack applications, we deliver quality software.', 'styles' => []]], 'children' => []],
                    ['id' => 'h1', 'type' => 'heading', 'props' => ['level' => 2, 'textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Service Checklist', 'styles' => []]], 'children' => []],
                    ['id' => 'cl1', 'type' => 'checkListItem', 'props' => ['checked' => true, 'textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Custom CMS Development', 'styles' => []]], 'children' => []],
                    ['id' => 'cl2', 'type' => 'checkListItem', 'props' => ['checked' => true, 'textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Theme & Plugin Development', 'styles' => []]], 'children' => []],
                    ['id' => 'cl3', 'type' => 'checkListItem', 'props' => ['checked' => true, 'textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'API Integration', 'styles' => []]], 'children' => []],
                ],
            ],
        ],
        
        [
            'title' => 'Getting Started with ZedCMS',
            'slug' => 'getting-started-guide',
            'type' => 'post',
            'data' => [
                'status' => 'published',
                'excerpt' => 'Learn how to build amazing websites with ZedCMS in just a few minutes.',
                'content' => [
                    ['id' => 'p1', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Welcome to ZedCMS! This guide will walk you through the basics of creating content.', 'styles' => []]], 'children' => []],
                    ['id' => 'h1', 'type' => 'heading', 'props' => ['level' => 2, 'textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Step 1: Access the Admin Panel', 'styles' => []]], 'children' => []],
                    ['id' => 'p2', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Navigate to /admin and log in with your credentials.', 'styles' => []]], 'children' => []],
                ],
            ],
        ],
        
        // =====================================================================
        // PORTFOLIO ITEMS
        // =====================================================================
        [
            'title' => 'Project Alpha: E-Commerce Platform',
            'slug' => 'project-alpha-ecommerce',
            'type' => 'portfolio',
            'data' => [
                'status' => 'published',
                'excerpt' => 'A complete e-commerce solution with 10,000+ products and real-time inventory management.',
                'content' => [
                    ['id' => 'p1', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Project Alpha showcases our ability to deliver complex e-commerce solutions.', 'styles' => []]], 'children' => []],
                    ['id' => 'bl1', 'type' => 'bulletListItem', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Client: Alpha Corp', 'styles' => []]], 'children' => []],
                    ['id' => 'bl2', 'type' => 'bulletListItem', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Stack: ZedCMS, MySQL, Tailwind CSS', 'styles' => []]], 'children' => []],
                ],
            ],
        ],
        
        [
            'title' => 'Project Beta: SaaS Dashboard',
            'slug' => 'project-beta-saas',
            'type' => 'portfolio',
            'data' => [
                'status' => 'published',
                'excerpt' => 'Real-time analytics dashboard with WebSocket updates.',
                'content' => [
                    ['id' => 'p1', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'A beautiful analytics dashboard with real-time data visualization.', 'styles' => []]], 'children' => []],
                    ['id' => 'bl1', 'type' => 'bulletListItem', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'WebSocket real-time updates', 'styles' => []]], 'children' => []],
                    ['id' => 'bl2', 'type' => 'bulletListItem', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'Dark mode support', 'styles' => []]], 'children' => []],
                ],
            ],
        ],
        
        [
            'title' => 'Project Gamma: Healthcare Portal',
            'slug' => 'project-gamma-healthcare',
            'type' => 'portfolio',
            'data' => [
                'status' => 'published',
                'excerpt' => 'HIPAA-compliant patient portal with telehealth integration.',
                'content' => [
                    ['id' => 'p1', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => 'A comprehensive healthcare portal connecting patients with providers.', 'styles' => []]], 'children' => []],
                ],
            ],
        ],
        
        // =====================================================================
        // TESTIMONIALS
        // =====================================================================
        [
            'title' => 'Outstanding Development Team',
            'slug' => 'testimonial-john-doe',
            'type' => 'testimonial',
            'data' => [
                'status' => 'published',
                'excerpt' => 'John Doe, CEO at TechCorp',
                'content' => [
                    ['id' => 'p1', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => '"Working with this team was an absolute pleasure. The code quality was outstanding."', 'styles' => [['type' => 'italic']]]], 'children' => []],
                    ['id' => 'p2', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => '— John Doe, CEO at TechCorp', 'styles' => [['type' => 'bold']]]], 'children' => []],
                ],
            ],
        ],
        
        [
            'title' => 'Transformed Our Business',
            'slug' => 'testimonial-sarah-johnson',
            'type' => 'testimonial',
            'data' => [
                'status' => 'published',
                'excerpt' => 'Sarah Johnson, Founder of StartupXYZ',
                'content' => [
                    ['id' => 'p1', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => '"Sales increased by 200% in the first quarter after launch!"', 'styles' => [['type' => 'italic']]]], 'children' => []],
                    ['id' => 'p2', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => '— Sarah Johnson, Founder of StartupXYZ', 'styles' => [['type' => 'bold']]]], 'children' => []],
                ],
            ],
        ],
        
        [
            'title' => 'Best CMS Decision Ever',
            'slug' => 'testimonial-michael-chen',
            'type' => 'testimonial',
            'data' => [
                'status' => 'published',
                'excerpt' => 'Michael Chen, CTO at DevAgency',
                'content' => [
                    ['id' => 'p1', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => '"Moving to ZedCMS with Aurora was our best technical decision this year."', 'styles' => [['type' => 'italic']]]], 'children' => []],
                    ['id' => 'p2', 'type' => 'paragraph', 'props' => ['textColor' => 'default', 'backgroundColor' => 'default', 'textAlignment' => 'left'], 'content' => [['type' => 'text', 'text' => '— Michael Chen, CTO at DevAgency', 'styles' => [['type' => 'bold']]]], 'children' => []],
                ],
            ],
        ],
    ];
}

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================

function aurora_set_option(string $name, string $value): void
{
    try {
        $db = Database::getInstance();
        $pdo = $db->getPdo();
        
        $stmt = $pdo->prepare("
            INSERT INTO zed_options (option_name, option_value, autoload) 
            VALUES (:name, :value, 1)
            ON DUPLICATE KEY UPDATE option_value = :value2
        ");
        $stmt->execute(['name' => $name, 'value' => $value, 'value2' => $value]);
    } catch (Exception $e) {
        // Ignore
    }
}

function aurora_delete_option(string $name): void
{
    try {
        $db = Database::getInstance();
        $db->query("DELETE FROM zed_options WHERE option_name = :name", ['name' => $name]);
    } catch (Exception $e) {
        // Ignore
    }
}
