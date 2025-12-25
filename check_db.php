<?php
/**
 * Check Database Tables and Content
 */

require_once __DIR__ . '/index.php';

use Core\Database;

$db = Database::getInstance();

echo "=== Checking Database Tables ===\n\n";

// Show all tables
echo "All tables in database:\n";
$tables = $db->query("SHOW TABLES");
foreach ($tables as $table) {
    $tableName = array_values($table)[0];
    echo "  - $tableName\n";
}

echo "\n=== Checking zed_content table ===\n\n";

try {
    // Check if zed_content exists and has data
    $count = $db->queryValue("SELECT COUNT(*) FROM zed_content");
    echo "Total rows in zed_content: $count\n\n";
    
    if ($count > 0) {
        echo "Sample data:\n";
        $sample = $db->query("SELECT id, title, type, slug FROM zed_content LIMIT 5");
        foreach ($sample as $row) {
            echo "  ID: {$row['id']}, Title: {$row['title']}, Type: {$row['type']}, Slug: {$row['slug']}\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
