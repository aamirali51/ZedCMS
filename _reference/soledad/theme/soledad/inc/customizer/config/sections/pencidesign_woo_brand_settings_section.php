<?php
$options = [];
if ( function_exists( 'penci_product_attributes_array' ) ) {
	$options[] = array(
		'default'     => false,
		'sanitize'    => 'penci_sanitize_choices_field',
		'label'       => __( 'Brand attribute', 'soledad' ),
		'description' => __( 'If you want to show brand image on your product page select desired attribute here', 'soledad' ),
		'id'          => 'penci_woocommerce_brand_attr',
		'type'        => 'soledad-fw-select',
		'choices'     => penci_product_attributes_array(),
	);
}
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show brand on the single product page', 'soledad' ),
	'description' => __( 'You can disable/enable products brand image on the single page .', 'soledad' ),
	'id'          => 'penci_woocommerce_brand',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show tab with brand information', 'soledad' ),
	'description' => __( 'If enabled you will see an additional tab with brand description on the single product page. Text will be taken from the "Description" field for each brand (attribute term).', 'soledad' ),
	'id'          => 'penci_woocommerce_brand_tab',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Use brand name for tab title', 'soledad' ),
	'description' => __( 'If you enable this option, the tab with the brand information will be called "About [Brand name]"..', 'soledad' ),
	'id'          => 'penci_woocommerce_brand_tab_title',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_woocommerce_brand_display',
	'default'     => 'summary',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Brand Display Position', 'soledad' ),
	'description' => __( 'Select the brand logo placement you want to display at single product', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'top'     => esc_attr__( 'Top', 'soledad' ),
		'summary' => esc_attr__( 'Summary', 'soledad' ),
	),
);

return $options;
