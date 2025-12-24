<?php
$options = array();
if ( ! function_exists( 'penci_woo_get_translate_text' ) ) {
	return false;
}
$options[]                    = array(
	'default'  => 'Shop by Department',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Shop by Department', 'soledad' ),
	'id'       => 'penci_woo_vertical_menu_title',
	'type'     => 'soledad-fw-text',
);
$penciwoo_translate_texts     = penci_woo_get_translate_text();
$penci_woo_already_translates = array(
	'penci_woocommerce_wishlist_empty_text',
	'penci_woocommerce_compare_empty_text',
	'penci_woo_trans_compare_empty_title',
	'penci_woo_trans_wishlist_empty_title',
	'penci_woo_cart_empty_title',
	'penci_woo_cart_empty_textarea',
);
foreach ( $penci_woo_already_translates as $untranslate ) {
	unset( $penciwoo_translate_texts[ $untranslate ] );
}
foreach ( $penciwoo_translate_texts as $translate_option => $translate_default ) {
	$options[] = array(
		'id'       => $translate_option,
		'default'  => $translate_default,
		'sanitize' => 'sanitize_text_field',
		'label'    => __( 'Text: ', 'soledad' ) . $translate_default,
		'type'     => 'soledad-fw-text',
	);
}

return $options;
