<?php
/**
 * Template loop for mixed style
 */
?>
<?php
$k = 4;
if ( ! isset ( $j ) ) { $j = 1; } else { $j = $j; }

if ( ( $j % $k ) == 1 ) {
	include( locate_template( 'template-parts/latest-posts-sc/content-mixed-post.php' ) );
}
else {
	include( locate_template( 'template-parts/latest-posts-sc/content-grid.php' ) );
}

$j++;
?>