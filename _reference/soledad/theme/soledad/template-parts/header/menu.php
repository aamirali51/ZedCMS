<?php
$menu_id = '';
if ( is_page() ) {
	$pmeta_page_header = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );
	if ( isset( $pmeta_page_header['main_nav_menu'] ) && $pmeta_page_header['main_nav_menu'] ) {
		$menu_id = $pmeta_page_header['main_nav_menu'];
	}
}

wp_nav_menu( array(
	'menu'            => $menu_id,
	'container'      => false,
	'theme_location' => 'main-menu',
	'menu_class'     => 'menu',
	'fallback_cb'    => 'penci_menu_fallback',
	'walker'         => new penci_menu_walker_nav_menu()
) );