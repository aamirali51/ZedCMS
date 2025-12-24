<?php
require_once PENCI_SOLEDAD_DIR . '/inc/avatar/class-penci-avatars.php';

if ( ! defined( 'PENCI_IS_NETWORK' ) ) {
	define( 'PENCI_IS_NETWORK', Penci_Avatars::is_network( plugin_basename( __FILE__ ) ) );
}
global $penci_avatars;
add_action( 'init', function() {
	$penci_avatars = new Penci_Avatars();
});

function get_penci_avatar( $id_or_email, $size = 96, $default = '', $alt = '', $args = array() ) {
	return apply_filters( 'penci_avatar', get_avatar( $id_or_email, $size, $default, $alt, $args ) );
}
