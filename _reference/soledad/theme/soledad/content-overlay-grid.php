<?php
/**
 * Template loop for 1st standard then grid
 */


/**
 * Create var $jj to count order posts
 * If $jj = 1, to show standard post, $jj > 1 to show grid post
 *
 * @since 1.0
 */
if ( ! isset ( $jj ) ) { $jj = 1; } else { $jj = $jj; }

if ( $jj == 1 ) {
	include( locate_template( 'content-overlay.php' ) );
}
else {
	include( locate_template( 'content-grid.php' ) );
}

$jj++;
?>

