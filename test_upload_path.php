<?php
// Test upload directory writing
$baseDir = __DIR__;
$uploadDir = $baseDir . '/uploads/' . date('Y') . '/' . date('m');

echo "Base Dir: $baseDir\n";
echo "Target Upload Dir: $uploadDir\n";

if (!is_dir($uploadDir)) {
    echo "Creating directory...\n";
    if (mkdir($uploadDir, 0755, true)) {
        echo "✓ Directory created successfully\n";
    } else {
        echo "✗ Failed to create directory. Error: " . error_get_last()['message'] . "\n";
    }
} else {
    echo "✓ Directory exists\n";
}

// Try writing a test file
$testFile = $uploadDir . '/test.txt';
if (file_put_contents($testFile, 'Test content')) {
    echo "✓ File write successful to $testFile\n";
} else {
    echo "✗ File write failed\n";
}
