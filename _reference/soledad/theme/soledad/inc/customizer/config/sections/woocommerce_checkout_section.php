<?php
$options   = [];
$options[] = array(
	'id'        => 'penci_woo_checkout_breadcrumb_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Breadcrumb Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_breadcrumb_active_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Breadcrumb Active Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_form_label_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Label Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_form_border_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Form Input Border Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_form_border_focus_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Form Input Focus Border Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_form_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Form Input Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_form_bg_focus_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Form Input Focus Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_order_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Order Form Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_order_table_bg_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Order Form Table Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_order_table_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Order Form Table Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_checkout_order_head_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Order Form Table Head Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_order_accent_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Order Form Table Accent Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_order_table_border_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Order Form Table Border Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_btn_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_btn_txt_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_btn_hover_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_btn_hover_txt_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Button Hover Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_review_order_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Review Order Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_woo_checkout_before_content',
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => __( 'Custom Content Before Checkout Form', 'soledad' ),
	'type'     => 'soledad-fw-textarea',
);
$options[] = array(
	'id'       => 'penci_woo_checkout_after_content',
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => __( 'Custom Content After Checkout Form', 'soledad' ),
	'type'     => 'soledad-fw-textarea',
);

return $options;
