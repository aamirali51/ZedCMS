<?php
$config = require 'config.php';

$pdo = new PDO(
    'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['name'],
    $config['database']['user'],
    $config['database']['password']
);

$stmt = $pdo->query("SELECT option_value FROM zed_options WHERE option_name = 'active_addons'");
$result = $stmt->fetchColumn();

echo "Active addons:\n";
echo $result . "\n\n";

$addons = json_decode($result, true);
echo "Parsed:\n";
print_r($addons);

echo "\n\ntest_menu_api.php in list? " . (in_array('test_menu_api.php', $addons ?? []) ? 'YES' : 'NO') . "\n";

