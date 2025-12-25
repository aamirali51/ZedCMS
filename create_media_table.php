<?php
/**
 * Create zed_media table
 * WordPress-style media management
 */

require_once __DIR__ . '/index.php';

use Core\Database;

$db = Database::getInstance();

echo "Creating zed_media table...\n\n";

$sql = "
CREATE TABLE IF NOT EXISTS `zed_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL COMMENT 'Relative path: YYYY/MM/filename.ext',
  `url` varchar(500) NOT NULL COMMENT 'Full URL to file',
  `thumbnail_url` varchar(500) DEFAULT NULL COMMENT 'URL to thumbnail (150x150)',
  `medium_url` varchar(500) DEFAULT NULL COMMENT 'URL to medium size (300x300)',
  `large_url` varchar(500) DEFAULT NULL COMMENT 'URL to large size (1024x1024)',
  `file_size` int(11) NOT NULL DEFAULT 0 COMMENT 'File size in bytes',
  `mime_type` varchar(100) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL COMMENT 'User ID who uploaded',
  `uploaded_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uploaded_by` (`uploaded_by`),
  KEY `mime_type` (`mime_type`),
  KEY `uploaded_at` (`uploaded_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

try {
    $db->query($sql);
    echo "✓ Table created successfully!\n\n";
    
    // Show table structure
    echo "Table structure:\n";
    $columns = $db->query("DESCRIBE zed_media");
    foreach ($columns as $col) {
        echo "  - {$col['Field']} ({$col['Type']})\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n✓ Media table ready!\n";
