<?php
$options   = [];
$options[] = array(
	'id'       => 'penci_woo_post_per_page',
	'default'  => '24',
	'sanitize' => 'penci_sanitize_number_field',
	'label'    => __( 'Total Products Display Per Page', 'soledad' ),
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_woo_post_per_page',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '24',
		),
	),
);
$options[] = array(
	'id'       => 'penci_catalog_heading_1',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Catalog Tools Settings', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '9,24,36',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Products Per Page Variations', 'soledad' ),
	'id'       => 'penci_woo_post_per_page_variations',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'id'       => 'penci_woo_per_row_columns_selector',
	'default'  => true,
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Show Columns Selector on Shop page', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => 'list-grid',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Shop products view', 'soledad' ),
	'description' => __( 'You can set different view mode for the shop page', 'soledad' ),
	'id'          => 'penci_shop_product_view',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'grid'      => __('Grid','soledad' ),
		'list'      => __('List','soledad' ),
		'grid-list' => __('Grid/List','soledad' ),
		'list-grid' => __('List/Grid','soledad' ),
	)
);
$options[] = array(
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'AJAX shop', 'soledad' ),
	'description' => __( 'Enable AJAX functionality for filter widgets, categories navigation, and pagination on the shop page.', 'soledad' ),
	'id'          => 'penci_woocommerce_ajax_shop',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Scroll to top after AJAX', 'soledad' ),
	'description' => __( 'Disable - Enable scroll to top after AJAX.', 'soledad' ),
	'id'          => 'penci_woocommerce_ajax_shop_auto_top',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_calalog_heading_1',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Product Item Settings', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'     => 'style-1',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Product Category Style', 'soledad' ),
	'description' => __( 'Select the style of the category showing on archive/categories/tags/search', 'soledad' ),
	'id'          => 'penci_woocommerce_product_cat_style',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'style-1' => __('Style 1','soledad' ),
		'style-2' => __('Style 2','soledad' ),
		'style-3' => __('Style 3','soledad' ),
		'style-4' => __('Style 4','soledad' ),
		'style-5' => __('Style 5','soledad' ),
	)
);
$options[] = array(
	'default'     => 'style-1',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Product Item Style', 'soledad' ),
	'description' => __( 'Select the style of the product showing on shop/archive/categories/tags/search<br/>.', 'soledad' ),
	'id'          => 'penci_woocommerce_product_style',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'standard' => __('Default','soledad' ),
		'style-1'  => __('Style 1','soledad' ),
		'style-2'  => __('Style 2','soledad' ),
		'style-3'  => __('Style 3','soledad' ),
		'style-4'  => __('Style 4','soledad' ),
		'style-5'  => __('Style 5','soledad' ),
		'style-6'  => __('Style 6','soledad' ),
		'style-7'  => __('Style 7','soledad' ),
	)
);
$options[] = array(
	'id'          => 'penci_woocommerce_product_icon_hover_style',
	'default'     => 'round',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Icon Hover Style', 'soledad' ),
	'description' => __( 'Select icon hover style on Product Item', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'round' => __('Separate Round','soledad' ),
		'group' => __('Group in Rectangle','soledad' ),
	)
);
$options[] = array(
	'id'          => 'penci_woocommerce_product_icon_hover_position',
	'default'     => 'top-left',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Icon Hover Position', 'soledad' ),
	'description' => __( 'Select icon hover position on Product Item', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'top-left'      => __('Top left','soledad' ),
		'top-right'     => __('Top Right','soledad' ),
		'bottom-left'   => __('Bottom Left','soledad' ),
		'bottom-right'  => __('Bottom Right','soledad' ),
		'center-top'    => __('Center Top','soledad' ),
		'center-center' => __('Center Center','soledad' ),
		'center-bottom' => __('Center Bottom','soledad' ),
	)
);
$options[] = array(
	'id'          => 'penci_woocommerce_product_icon_hover_animation',
	'default'     => 'move-right',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Icon Hover Animation', 'soledad' ),
	'description' => __( 'Select icon hover animation on Product Item', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'move-left'   => __('Move to left','soledad' ),
		'move-right'  => __('Move to Right','soledad' ),
		'move-top'    => __('Move to Top','soledad' ),
		'move-bottom' => __('Move to Bottom','soledad' ),
		'fade'        => __('Fade In','soledad' ),
		'zoom'        => __('Zoom In','soledad' ),
	)
);
$options[] = array(
	'id'       => 'penci_woocommerce_product_hover_img',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Hover Image on Product Catalog ?', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show product category ?', 'soledad' ),
	'description' => __( 'Display product category link below the product title.', 'soledad' ),
	'id'          => 'penci_woocommerce_loop_category',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_woocommerce_loop_rating',
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show product star rating ?', 'soledad' ),
	'description' => __( 'Display product loop rating below the product title.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Login to see add to cart and prices', 'soledad' ),
	'description' => __( 'You can restrict shopping functions only for logged in customers.', 'soledad' ),
	'id'          => 'penci_woocommerce_restrict_cart_price',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Quick Select Options on Product', 'soledad' ),
	'description' => __( 'Allow customers purchase product on hover content.', 'soledad' ),
	'id'          => 'penci_woocommerce_product_quick_shop',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_number_field',
	'label'    => __( 'Limit Product Title Length', 'soledad' ),
	'desc'     => 'Enter the custom length of the product title you want to display',
	'id'       => 'penci_woo_limit_product_title',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_woo_limit_product_title',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => '',
		),
	),
);
$options[] = array(
	'id'       => 'penci_woo_limit_product_excerpt',
	'default'  => '',
	'sanitize' => 'penci_sanitize_number_field',
	'label'    => __( 'Limit Product Excerpt Length', 'soledad' ),
	'desc'     => 'Enter the custom length of the product summary you want to display',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_woo_limit_product_excerpt',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => '',
		),
	),
);
if ( function_exists( 'penci_product_attributes_array' ) ) {
	$options[] = array(
		'id'          => 'penci_woocommerce_grid_swatch',
		'default'     => false,
		'sanitize'    => 'penci_sanitize_choices_field',
		'label'       => __( 'Grid swatch attribute to display', 'soledad' ),
		'description' => __( 'Choose the attribute that will be shown on the product grid.', 'soledad' ),
		'type'        => 'soledad-fw-ajax-select',
		'choices'     => penci_product_attributes_array(),
	);
}
$options[] = array(
	'id'       => 'penci_woocommerce_grid_swatch_limit',
	'default'  => '5',
	'sanitize' => 'absint',
	'label'    => __( 'Limit swatches on grid ', 'soledad' ),
);
$options[] = array(
	'id'          => 'penci_woocommerce_grid_swatch_cache',
	'default'     => true,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Product Swatches Shop Cache', 'soledad' ),
	'description' => __( 'By default, Soledad using cache to speed up Query for swatch image. Uncheck this option to disable/debug.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_catalog_heading_4',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Catalog Columns Settings', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'          => 'penci_shop_cat_columns',
	'default'     => 4,
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Categories Columns', 'soledad' ),
	'description' => __( 'How many category should be shown per row on section ?', 'soledad' ),
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
	'id'          => 'penci_shop_cat_display_type',
	'default'     => 'grid',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Categories Display Style', 'soledad' ),
	'description' => __( 'Select the category displays style on shop/category page', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'grid'     => __('Grid','soledad' ),
		'carousel' => __('Carousel','soledad' ),
	)
);
$options[] = array(
	'id'          => 'penci_shop_product_columns',
	'default'     => 3,
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Products per row on Desktop', 'soledad' ),
	'description' => __( 'How many products should be shown per row on desktop ?', 'soledad' ),
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
	'id'          => 'penci_shop_product_mobile_columns',
	'default'     => 2,
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Products per row on Mobile', 'soledad' ),
	'description' => __( 'How many products should be shown per row on mobile ?', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		1 => __('1 Column','soledad' ),
		2 => __('2 Columns','soledad' ),
	)
);

return $options;
