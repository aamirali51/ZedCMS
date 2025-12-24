<?php
$smartlists_enable = get_post_meta( get_the_ID(), 'pcsml_smartlists_enable', true );
$smartlists_style  = get_post_meta( get_the_ID(), 'pcsml_smartlists_style', true );
$smartlists_h      = get_post_meta( get_the_ID(), 'pcsml_smartlists_h', true );
$smartlists_order  = get_post_meta( get_the_ID(), 'pcsml_smartlists_order', true );

if ( 'yes' == $smartlists_enable ) {
	$content = get_the_content();
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	remove_filter( 'the_content', 'penci_insert_post_content_ads' );
	add_filter( 'penci_toc_maybe_apply_the_content_filter', function () {
		return false;
	}, 10 );
	$smartlists_content = penci_smartlists( [
		'style'        => $smartlists_style,
		'content'      => $content,
		'order'        => $smartlists_order,
		'h'            => $smartlists_h,
		'sm_title_tag' => $smartlists_h,
	] );
	if ( $smartlists_content ) {
		echo '<div class="pcsml-el pcsml-customized-ver scmchck">' . $smartlists_content . '</div>';
	}
} else {
	the_content();
}
?>