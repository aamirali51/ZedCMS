<?php
header('Content-Type: text/plain');

echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "Base Path: " . dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$basePath = dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php');

echo "\nCheck: str_starts_with('$uri', '$basePath') = " . (str_starts_with($uri, $basePath) ? 'true' : 'false') . "\n";

// Case insensitive check
echo "Check case-insensitive: " . (stripos($uri, $basePath) === 0 ? 'true' : 'false') . "\n";
