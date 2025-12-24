<?php
$options = [];
/*Product Label Settings*/
$options[] = array(
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show HOT label ?', 'soledad' ),
	'description'=>__('Display HOT label on featured product.','soledad'),
	'id'          => 'penci_woo_label_hot_product',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => 'square',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Select Label Style', 'soledad' ),
	'id'       => 'penci_woo_label_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'square' => __('Rectangle','soledad' ),
		'round'  => __('Round','soledad' ),
	),
);
$options[] = array(
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Percentage on sale label ?', 'soledad' ),
	'id'       => 'penci_woo_label_percentage',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_woo_label_new_product',
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show NEW label ?', 'soledad' ),
	'description'=>__('Display NEW label on product.','soledad'),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_woo_label_new_product_period',
	'default'     => 7,
	'sanitize'    => 'absint',
	'label'       => __( 'Automatic "New" label period', 'soledad' ),
	'description'=>__('Set a number of days to keep your products marked as "New" after creation.','soledad'),
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_woo_label_new_product_period',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '7',
		),
	),
);
$options[] = array(
	'id'        => 'penci_woo_label_new_color',
	'default'   => '#8dd620',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'New Label Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_label_hot_color',
	'default'   => '#fb1919',
	'transport' => 'refresh',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __( 'Hot Label Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_label_hot_color',
	'default'   => '',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Sale Label Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_woo_label_outstock_color',
	'default'   => '#800000',
	'transport' => 'refresh',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Out of Stock Label Background Color', 'soledad' ),
);

return $options;
