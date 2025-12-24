<?php
/**
 * Zenith Theme — Reading Progress Part
 * 
 * Fixed progress bar that fills as user scrolls
 * 
 * @package Zenith
 */

declare(strict_types=1);

$show = zenith_option('show_reading_progress', 'yes') === 'yes';
if (!$show) return;
?>

<div id="reading-progress" class="reading-progress" style="width: 0%"></div>
