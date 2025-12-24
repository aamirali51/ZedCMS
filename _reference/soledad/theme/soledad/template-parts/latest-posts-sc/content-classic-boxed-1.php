<?php
/**
 * Template loop for 1st classic then boxed 1
 */


/**
 * Create var $j to count order posts
 * If $j = 1, to show standard post, $j > 1 to show grid post
 *
 * @since 1.0
 */
if ( ! isset ( $j ) ) { $j = 1; } else { $j = $j; }

if ( $j == 1 ) {
	//include( locate_template( 'template-parts/latest-posts-sc/content-boxed-1.php' ) );get_template_part( 'content', 'classic' );
	include( locate_template( 'template-parts/latest-posts-sc/content-classic.php' ) );
}
else {
	include( locate_template( 'template-parts/latest-posts-sc/content-boxed-1.php' ) );
}

$j++;
?>

