<?php
/**
 * Rename Database Tables from zero_ to zed_
 * 
 * This script renames all tables from the old "Zero CMS" naming to "Zed CMS"
 * Run this once to migrate your database.
 */

require_once __DIR__ . '/index.php';

use Core\Database;

$db = Database::getInstance();

echo "Starting table rename migration...\n\n";

// List of tables to rename
$tables = [
    'zero_content' => 'zed_content',
    'zero_users' => 'zed_users',
    'zero_options' => 'zed_options',
    'zero_categories' => 'zed_categories',
    'zero_media' => 'zed_media',
    'zero_menus' => 'zed_menus',
];

foreach ($tables as $oldName => $newName) {
    try {
        // Check if old table exists
        $exists = $db->queryValue("SHOW TABLES LIKE '$oldName'");
        
        if ($exists) {
            echo "Renaming $oldName to $newName... ";
            $db->query("RENAME TABLE `$oldName` TO `$newName`");
            echo "✓ Done\n";
        } else {
            echo "Table $oldName doesn't exist, skipping.\n";
        }
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}

echo "\n✓ Migration complete!\n";
echo "You can now delete this file: rename_tables.php\n";
