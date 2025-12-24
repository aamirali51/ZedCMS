<?php
/**
 * Template loop for mixed style
 */
?>
<?php
/**
* Create var $jj to count order posts
* If $jj = 1, to show mixed post, $jj > 1 to show grid post
*
* @since 1.0
*/
$penci_mixed_style = isset( $penci_mixed_style ) ? $penci_mixed_style : '';

$k = 3;
if ( ! isset ( $jj ) ) { $jj = 1; } else { $jj = $jj; }
if( ( is_home() && ! penci_get_setting( 'penci_sidebar_home' ) ) || ( is_archive() && ! penci_get_setting( 'penci_sidebar_archive' ) ) || is_page_template( 'page-vc.php' ) || is_page_template( 'page-penci-full-width.php' ) ):
	$k = 4;
endif;

if( isset( $template ) && $template == 'no-sidebar' ) {
	$k = 4;
}

if( 's1' == $penci_mixed_style ) {
	$k = 4;
} elseif( 's2' == $penci_mixed_style ) {
	$k = 3;
}

if ( ( $jj % $k ) == 1 ) {
	include( locate_template( 'content-overlay.php' ) );
}
else {
	include( locate_template( 'content-grid.php' ) );
}

$jj++;
?>