<?php
$tbmenu_id = '';
if ( is_page() ) {
	$pmeta_page_header = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );
	if ( isset( $pmeta_page_header['topbar_menu'] ) && $pmeta_page_header['topbar_menu'] ) {
		$tbmenu_id = $pmeta_page_header['topbar_menu'];
	}
}
/**
 * Display topbar menu
 */
wp_nav_menu( array(
	'menu'           => $tbmenu_id,
	'container'      => 'div',
	'container_class'=> 'pctopbar-item penci-wtopbar-menu',
	'theme_location' => 'topbar-menu',
	'menu_class'     => 'penci-topbar-menu',
	'fallback_cb'    => 'penci_menu_fallback',
	'walker'         => ''
) );