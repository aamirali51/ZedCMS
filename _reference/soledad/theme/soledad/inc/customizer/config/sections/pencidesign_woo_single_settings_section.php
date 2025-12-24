<?php
$options   = [];
$options[] = array(
	'default'     => 'top',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Breadcrumb Position', 'soledad' ),
	'description' => __( 'Select the breadcrumb position on single product', 'soledad' ),
	'id'          => 'penci_single_product_breadcrumb_position',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'top'     => __( 'Top of page', 'soledad' ),
		'summary' => __( 'Top of product summary', 'soledad' ),
		'hidden'  => __( 'Hidden', 'soledad' ),
	)
);
$options[] = array(
	'default'     => 'standard',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Product image width', 'soledad' ),
	'description' => __( 'You can choose different page layout depending on the product image size you need', 'soledad' ),
	'id'          => 'penci_single_product_img_width',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'standard'            => __( 'Standard', 'soledad' ),
		'medium'              => __( 'Medium', 'soledad' ),
		'large'               => __( 'Large', 'soledad' ),
		'fullwidth-container' => __( 'Full Width (Container)', 'soledad' ),
		'fullwidth'           => __( 'Full Width', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Custom Max Width for Product Detail on Full Width Images Style', 'soledad' ),
	'id'       => 'penci_single_product_summary_width',
	'ids'      => array(
		'desktop' => 'penci_single_product_summary_width',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'     => 'thumbnail-left',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Thumbnails position', 'soledad' ),
	'description' => __( 'Use vertical or horizontal position for thumbnails.', 'soledad' ),
	'id'          => 'penci_single_product_thumbnail_position',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'thumbnail-left'         => __('Left','soledad' ),
		'thumbnail-right'        => __('Right','soledad' ),
		'thumbnail-bottom'       => __('Bottom','soledad' ),
		'thumbnail-bottom-1-col' => __('Bottom 1 Column','soledad' ),
		'thumbnail-bottom-2-col' => __('Bottom 2 Column','soledad' ),
		'thumbnail-grid'         => __('Grid','soledad' ),
		'thumbnail-without'      => __('Without','soledad' ),
	)
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Zoom on Gallery Product', 'soledad' ),
	'id'       => 'penci_woo_disable_zoom',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Sticky Product Image & Content', 'soledad' ),
	'description' => __( 'Check to enable sticky content & product images', 'soledad' ),
	'id'          => 'penci_single_product_sticky_thumbnail_content',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Disable Single Product Ajax Add to Cart', 'soledad' ),
	'description' => __( 'Turn on this option if the addon product doesn\'t add to cart.', 'soledad' ),
	'id'          => 'penci_single_product_disable_ajax_atc',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_single_product_top_related_product',
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Top Next/Previous Products', 'soledad' ),
	'description' => __( 'Check to show the next/prvious post on the top of product.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => 'default',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Tabs Content Display Style', 'soledad' ),
	'id'       => 'penci_single_product_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'default'           => __('Standard','soledad' ),
		'accordion-tab'     => __('Accordion Toggle','soledad' ),
		'accordion-content' => __('Accordion Content','soledad' ),
	)
);
$options[] = array(
	'default'     => 'standard',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Product Summary Align', 'soledad' ),
	'description' => __( 'Select default product summary align style', 'soledad' ),
	'id'          => 'penci_single_product_summary_align',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'standard' => __('Standard','soledad' ),
		'center'   => __('Center','soledad' ),
	)
);
$options[] = array(
	'default'  => 'disable',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Enable sticky add to cart ?', 'soledad' ),
	'id'       => 'pencidesign_woo_single_sticky_add_to_cart',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'enable'  => __('Enable','soledad' ),
		'disable' => __('Disable','soledad' ),
	)
);
$options[] = array(
	'default'     => 'disable',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Add shadow to product summary block', 'soledad' ),
	'description' => __( 'Useful when you set background color for the single product page to gray for example.', 'soledad' ),
	'id'          => 'woo_single_add_shadow_to_summary',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'enable'  => __('Enable','soledad' ),
		'disable' => __('Disable','soledad' ),
	)
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Custom Tab', 'soledad' ),
	'id'       => 'penci_woo_custom_tab',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Custom Tab Title', 'soledad' ),
	'id'       => 'penci_woo_custom_tab_title',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => __( 'Custom Tab Content', 'soledad' ),
	'id'       => 'penci_woo_custom_tab_content',
	'type'     => 'soledad-fw-textarea',
);
$options[] = array(
	'default'  => 50,
	'sanitize' => 'penci_sanitize_number_field',
	'label'    => __( 'Custom Tab Priority', 'soledad' ),
	'id'       => 'penci_woo_custom_tab_priority',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_woo_custom_tab_priority',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '50',
		),
	),
);
$options[] = array(
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Convert select into the button', 'soledad' ),
	'description' => __( 'Convert the select into button on the no-swatches field.', 'soledad' ),
	'id'          => 'pencidesign_woo_single_select2button',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_woo_social_share_style',
	'default'     => 'style-1',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Product Social Sharing Style', 'soledad' ),
	'description' => __( 'Select the social sharing style.', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'style-1' => __('Style 1','soledad' ),
		'style-2' => __('Style 2','soledad' ),
		'style-3' => __('Style 3','soledad' ),
		'style-4' => __('Style 4','soledad' ),
	)
);
$options[] = array(
	'id'          => 'penci_woo_social_icon_style',
	'default'     => 'circle',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Product Social Icon Style', 'soledad' ),
	'description' => __( 'Select the social sharing style.', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'circle' => __('Circle','soledad' ),
		'square' => __('Square','soledad' ),
	)
);
$options[] = array(
	'id'          => 'penci_shop_product_related_columns',
	'default'     => 4,
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Related Product Columns', 'soledad' ),
	'description' => __( 'How many products should be shown per row on related section ?', 'soledad' ),
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
	'default'  => '4',
	'sanitize' => 'penci_sanitize_number_field',
	'label'    => __( 'Custom Amount of Related Products', 'soledad' ),
	'id'       => 'penci_woo_number_related_products',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_woo_number_related_products',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '4',
		),
	),
);
$options[] = array(
	'id'          => 'penci_shop_product_up_sell_columns',
	'default'     => 4,
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Up Sell Product Columns', 'soledad' ),
	'description' => __( 'How many products should be shown per row on up sell section ?', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		2 => __('2 Columns','soledad' ),
		3 => __('3 Columns','soledad' ),
		4 => __('4 Columns','soledad' ),
		5 => __('5 Columns','soledad' ),
		6 => __('6 Columns','soledad' ),
	)
);

return $options;
