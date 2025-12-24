<?php
$options   = [];
$options[] = array(
	'id'          => 'penci_shop_product_cross_sell_columns',
	'default'     => 4,
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Cross Sell Product Columns', 'soledad' ),
	'description' => __( 'How many products should be shown per row on cross sell section ?', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		2 => __('2 Columns','soledad' ),
		3 => __('3 Columns','soledad' ),
		4 => __('4 Columns','soledad' ),
		5 => __('5 Columns','soledad' ),
		6 => __('6 Columns','soledad' ),
	)
);
$options[] = array(
	'id'        => 'penci_woo_cart_breadcrumb_active_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Breadcrumb Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_breadcrumb_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Breadcrumb Active Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_tablehead_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Table Head Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_table_border_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Table General Border Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_table_txt_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Table General Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_table_product_title_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Product Title Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_table_product_title_hover_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Product Title Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_table_product_price_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Product Price Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_btn_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Button Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_btn_txt_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Button Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_btn_hover_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Button Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_btn_hover_txt_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Button Hover Text Color', 'soledad' ),
);
// secondary button
$options[] = array(
	'id'        => 'penci_woo_cart_sbtn_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Alt Button Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_sbtn_txt_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Alt Button Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_sbtn_hover_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Alt Button Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_sbtn_hover_txt_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Alt Button Hover Text Color', 'soledad' ),
);
// delete button
$options[] = array(
	'id'        => 'penci_woo_cart_del_btn_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Delete Item Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_del_btn_hv_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Delete Item Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_cart_empty_title',
	'default'   => 'Your cart is currently empty',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_text_field',
	'label'     => __( 'Empty Cart Title', 'soledad' ),
	'type'      => 'soledad-fw-text'
);
$options[] = array(
	'id'        => 'penci_woo_cart_empty_textarea',
	'default'   => 'You don\'t have any products in the shop yet. <br> You will find a lot of interesting products on our "Shop" page.',
	'transport' => 'refresh',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'label'     => __( 'Empty Cart Text', 'soledad' ),
	'type'      => 'soledad-fw-textarea'
);
$options[] = array(
	'id'        => 'penci_woo_cart_before_content',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'label'     => __( 'Custom Content Before Cart Table', 'soledad' ),
	'type'      => 'soledad-fw-textarea'
);
$options[] = array(
	'id'        => 'penci_woo_cart_after_content',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'label'     => __( 'Custom Content After Cart Table', 'soledad' ),
	'type'      => 'soledad-fw-textarea',
);

return $options;
