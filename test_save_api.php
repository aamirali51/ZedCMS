<?php
/**
 * Test save API directly - simulates what the admin JS does
 */
session_start();

// Bootstrap Zed
require_once __DIR__ . '/index.php';

use Core\Database;
use Core\Auth;

echo "<pre>\n=== Save API Test ===\n\n";

// Get CSRF nonce from session
$nonce = $_SESSION['zed_nonce'] ?? 'none';
echo "Session Nonce: {$nonce}\n";
echo "Logged in: " . (Auth::check() ? 'YES (user: ' . Auth::user()['username'] . ')' : 'NO') . "\n\n";

// Get active theme
$db = Database::getInstance();
$pdo = $db->getPdo();
$stmt = $pdo->query("SELECT option_value FROM zed_options WHERE option_name = 'active_theme'");
$activeTheme = $stmt->fetchColumn() ?: 'aurora';
echo "Active Theme: {$activeTheme}\n\n";

// Simulate the save payload
$testData = [
    'nonce' => $nonce,
    'theme_header_style' => 'boxed',  // Test changing to boxed
    'theme_accent_color' => '#6eb48c'
];

echo "Payload to send:\n";
print_r($testData);
echo "\n";

// Make internal API call
$url = 'http://localhost/ZedCMS/admin/api/save-settings';

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($testData),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'X-ZED-NONCE: ' . $nonce,
        'Cookie: ' . session_name() . '=' . session_id()
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: {$httpCode}\n";
echo "Response: {$response}\n\n";

// Check database directly
echo "=== Database Check ===\n";
$stmt = $pdo->query("SELECT option_name, option_value FROM zed_options WHERE option_name LIKE 'theme_%'");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo sprintf("  %-40s = %s\n", $row['option_name'], $row['option_value']);
}

echo "\n</pre>";

// Link to reload settings
echo '<p><a href="/ZedCMS/admin/settings">Go to Admin Settings</a></p>';
echo '<p><a href="/ZedCMS/">Go to Frontend</a></p>';
