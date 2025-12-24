<?php
$options   = [];
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Wishlist', 'soledad' ),
	'description'=>__('Enable wishlist functionality built in with the theme.','soledad'),
	'id'          => 'penci_woocommerce_wishlist',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_woo_shop_hide_wishlist_icon',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Header Wishlist Icon', 'soledad' ),
	'type'     => 'soledad-fw-toggle'
);
if ( function_exists( 'penci_get_pages_option' ) ) {
	$options[] = array(
		'default'     => '',
		'sanitize'    => 'penci_sanitize_choices_field',
		'label'       => __( 'Wishlist page', 'soledad' ),
		'description'=>__('Select a page for the wishlist table. It should contain the shortcode: [penci_wishlist]','soledad'),
		'id'          => 'penci_woocommerce_wishlist_page',
		'type'        => 'soledad-fw-select',
		'choices'     => penci_get_pages_option()
	);
}
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Only for logged in', 'soledad' ),
	'description'=>__('Disable wishlist for guests customers.','soledad'),
	'id'          => 'penci_woocommerce_wishlist_only_logged_in',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show button on product grid', 'soledad' ),
	'description'=>__('Display wishlist product button on all products grids and lists.','soledad'),
	'id'          => 'penci_woocommerce_wishlist_show',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_woocommerce_wishlist_ppp',
	'default'  => '24',
	'sanitize' => 'penci_sanitize_number_field',
	'label'    => __( 'Products Items Per Page', 'soledad' ),
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_woocommerce_wishlist_ppp',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => '',
		),
	),
);
$options[] = array(
	'default'     => 'pagination',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Products Pagination', 'soledad' ),
	'description'=>__('Choose a type for the pagination on your shop page.','soledad'),
	'id'          => 'penci_woocommerce_wishlist_pagination',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'pagination' => __('Pagination','soledad' ),
		'infinit'    => __('Infinit Scrolling','soledad' ),
	)
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Quick Text Translate', 'soledad' ),
	'id'       => 'penci_woo_section_wishlist_heading_01',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'       => 'penci_woo_trans_wishlist_empty_title',
	'default'  => 'Wishlist is empty.',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Empty wishlist heading text', 'soledad' ),
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'default'     => 'You don\'t have any products in the wishlist yet. <br> You will find a lot of interesting products on our "Shop" page.',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Empty wishlist text', 'soledad' ),
	'description'=>__('Text will be displayed if user don\'t add any products to wishlist','soledad'),
	'id'          => 'penci_woocommerce_wishlist_empty_text',
	'type'        => 'soledad-fw-textarea',
);

return $options;
