<?php
/**
 * Debug route issue
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Router.php';

$config = require __DIR__ . '/config.php';
Core\Database::setConfig($config['database'] ?? $config['db'] ?? $config);

$db = Core\Database::getInstance();

header('Content-Type: text/plain');

$slug = 'demo-api-design-best-practices';

echo "=== DEBUGGING ROUTE FOR: $slug ===\n\n";

// 1. Check if post exists
echo "1. CHECKING DATABASE:\n";
$post = $db->queryOne("SELECT id, title, slug, type, data FROM zed_content WHERE slug = :slug LIMIT 1", ['slug' => $slug]);

if ($post) {
    echo "   Post found! ID: {$post['id']}\n";
    echo "   Title: {$post['title']}\n";
    echo "   Type: {$post['type']}\n";
    
    $data = json_decode($post['data'], true);
    echo "   Status in data: " . ($data['status'] ?? 'NOT SET') . "\n";
    
    // Check the exact comparison
    $status = $data['status'] ?? '';
    echo "   Status === 'published': " . ($status === 'published' ? 'YES' : 'NO') . "\n";
    echo "   Status value type: " . gettype($status) . "\n";
    echo "   Status value: '" . $status . "'\n";
} else {
    echo "   POST NOT FOUND!\n";
}

echo "\n2. CHECKING zed_get_post_by_slug FUNCTION:\n";

// Load the helper
$helpersFile = __DIR__ . '/content/addons/_system/frontend/helpers_content.php';
if (file_exists($helpersFile)) {
    require_once $helpersFile;
    
    if (function_exists('zed_get_post_by_slug')) {
        $result = zed_get_post_by_slug($slug);
        echo "   Function returned: " . ($result ? "POST ID {$result['id']}" : "NULL") . "\n";
    } else {
        echo "   ERROR: zed_get_post_by_slug function not defined!\n";
    }
} else {
    echo "   ERROR: helpers_content.php not found!\n";
}

echo "\n3. CHECKING POST TYPES MATCHING:\n";

// Simulate the routing check
$segments = array_values(array_filter(explode('/', $slug)));
$firstSegment = $segments[0] ?? '';
echo "   First segment: '$firstSegment'\n";

// Check if it matches any post type
$postTypes = ['post', 'page', 'blog']; // Basic types
echo "   Is first segment a post type? " . (in_array($firstSegment, $postTypes) ? 'YES' : 'NO') . "\n";

echo "\n4. EXPECTED ROUTING PATH:\n";
echo "   Since '$slug' is not a registered post type slug,\n";
echo "   it should go to 'Single Content by Slug' logic\n";
echo "   and call zed_get_post_by_slug('$slug')\n";
