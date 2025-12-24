<?php
$options   = [];
$options[] = array(
	'id'        => 'penci_woo_checkout_txt_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_head_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Heading Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_border_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'General Border Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_success_icon_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Sucess Icon Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_success_icon_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Sucess Icon Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_success_thankyou_text',
	'default'   => 'Thank you. Your order has been received.',
	'transport' => 'refresh',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'label'     => __( 'Custom Thankyou Text', 'soledad' ),
	'type'      => 'soledad-fw-textarea',
);

return $options;
