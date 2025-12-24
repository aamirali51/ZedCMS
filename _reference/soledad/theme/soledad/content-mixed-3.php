<?php
/**
 * Template loop for 1st standard then grid
 */


/**
 * Create var $jj to count order posts
 *
 * @since 1.0
 */
if ( ! isset ( $jj ) ) {
	$jj = 1;
} else {
	$jj = $jj;
}

if ( $jj < 5 ) {
	include( locate_template( 'content-grid-2.php' ) );
} else {
	include( locate_template( 'content-small-list.php' ) );
}

$jj ++;
?>

