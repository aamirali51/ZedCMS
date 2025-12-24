<?php
/**
 * Zenith Theme — Header Router
 * 
 * Loads the appropriate header style based on theme settings
 * 
 * Available styles:
 * - classic: Logo centered, nav below (Soledad style 1)
 * - standard: Logo left, nav right (Soledad style 4)
 * - boxed: Contained with rounded background
 * - transparent: Overlay for hero sections
 * 
 * @package Zenith
 */

declare(strict_types=1);

// Get selected header style
$header_style = zenith_option('header_style', 'standard');

// Map to file
$header_file = ZENITH_PARTS . '/header/' . $header_style . '.php';

// Fallback to standard if style doesn't exist
if (!file_exists($header_file)) {
    $header_file = ZENITH_PARTS . '/header/standard.php';
}
?>

<body class="font-body bg-zenith-bg text-zenith-text antialiased">
    
<?php
// Include the selected header
include $header_file;
?>
    
    <main>

