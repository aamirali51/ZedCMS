<?php
require_once __DIR__ . '/index.php';

use Core\Database;

$db = Database::getInstance();

echo "=== Checking for media tables ===\n\n";

$tables = $db->query("SHOW TABLES");
foreach ($tables as $table) {
    $tableName = array_values($table)[0];
    if (stripos($tableName, 'media') !== false) {
        echo "Found: $tableName\n";
        
        // Show structure
        $count = $db->queryValue("SELECT COUNT(*) FROM `$tableName`");
        echo "  Rows: $count\n";
        
        if ($count > 0) {
            echo "  Sample data:\n";
            $sample = $db->query("SELECT * FROM `$tableName` LIMIT 3");
            foreach ($sample as $row) {
                echo "    " . json_encode($row) . "\n";
            }
        }
        echo "\n";
    }
}
