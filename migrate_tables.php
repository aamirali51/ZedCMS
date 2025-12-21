<?php
/**
 * Zed CMS - Table Migration Script
 * Renames zero_ tables to zed_ tables
 * 
 * Run this ONCE, then delete this file.
 */

$config = require __DIR__ . '/config.php';
$db = $config['database'];

try {
    $pdo = new PDO(
        "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset={$db['charset']}",
        $db['user'],
        $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<h2>Zed CMS - Table Migration</h2>";
    echo "<pre style='font-size: 16px; line-height: 1.8;'>";
    
    $tables = [
        'zero_content' => 'zed_content',
        'zero_categories' => 'zed_categories',
        'zero_menus' => 'zed_menus',
        'zero_options' => 'zed_options',
    ];
    
    foreach ($tables as $old => $new) {
        // Check if old table exists
        $stmt = $pdo->query("SHOW TABLES LIKE '$old'");
        if ($stmt->rowCount() > 0) {
            // Check if new table already exists
            $stmt2 = $pdo->query("SHOW TABLES LIKE '$new'");
            if ($stmt2->rowCount() > 0) {
                echo "⚠️  Table '<strong>$new</strong>' already exists. Skipping '$old'.\n";
            } else {
                $pdo->exec("RENAME TABLE `$old` TO `$new`");
                echo "✅ Renamed '<strong>$old</strong>' → '<strong>$new</strong>'\n";
            }
        } else {
            echo "ℹ️  Table '$old' not found (may already be renamed).\n";
        }
    }
    
    echo "\n<strong style='color: green;'>✔ Migration complete!</strong>\n";
    echo "\n⚠️  You can now <strong>delete this file</strong> (migrate_tables.php).\n";
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "<h2 style='color:red'>Error</h2>";
    echo "<pre style='color:red; font-size: 14px;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
