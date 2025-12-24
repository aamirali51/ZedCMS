<?php
$options   = [];
$options[] = array(
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Quick View', 'soledad' ),
	'description' => __( 'Enable Quick view option. Ability to see the product information with AJAX.', 'soledad' ),
	'id'          => 'penci_woocommerce_quickview',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show variations information on quick view', 'soledad' ),
	'description' => __( 'Enable Quick show all product summary information for variable products.', 'soledad' ),
	'id'          => 'penci_woocommerce_quickview_variations',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'type'        => 'soledad-fw-size',
	'default'     => '960',
	'sanitize'    => 'absint',
	'label'       => __( 'Quick view width', 'soledad' ),
	'description' => __( 'Set width of the quick view in pixels.', 'soledad' ),
	'id'          => 'penci_woocommerce_quickview_width',
	'ids'         => array(
		'desktop' => 'penci_woocommerce_quickview_width',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '960',
		),
	),
);

return $options;
