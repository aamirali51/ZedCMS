<?php
require_once __DIR__ . '/core/Database.php';
$config = require __DIR__ . '/config.php';
Core\Database::setConfig($config['database'] ?? $config['db'] ?? $config);
$db = Core\Database::getInstance();

header('Content-Type: text/plain');

// Simulate what the route does
$uri = '/demo-api-design-best-practices';
$slug = trim($uri, '/');
echo "URI: $uri\n";
echo "Slug: $slug\n\n";

// Check for matching post
$post = $db->queryOne("SELECT id, title, slug, type FROM zed_content WHERE slug = :slug LIMIT 1", ['slug' => $slug]);

if ($post) {
    echo "Found post:\n";
    echo "  ID: {$post['id']}\n";
    echo "  Title: {$post['title']}\n"; 
    echo "  Type: {$post['type']}\n";
    
    // Check if published
    $data = $db->queryOne("SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) as status FROM zed_content WHERE id = :id", ['id' => $post['id']]);
    echo "  Status: {$data['status']}\n";
} else {
    echo "Post NOT found\n";
}

