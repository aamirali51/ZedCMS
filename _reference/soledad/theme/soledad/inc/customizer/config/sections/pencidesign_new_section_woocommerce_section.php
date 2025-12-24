<?php
$options   = [];
$options[] = array(
	'id'       => 'penci_general_heading_1',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Header Area', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'        => 'penci_woo_shop_hide_cart_icon',
	'default'   => false,
	'transport' => 'refresh',
	'sanitize'  => 'penci_sanitize_checkbox_field',
	'label'     => __( 'Hide Header Shopping Cart Icon', 'soledad' ),
	'type'      => 'soledad-fw-toggle'
);
$options[] = array(
	'id'          => 'penci_woo_cart_style',
	'default'     => 'side-right',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Header Shopping Cart Style', 'soledad' ),
	'description'=>__('Select the shopping cart detail style.','soledad'),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'dropdown'   => __('Dropdown','soledad' ),
		'side-left'  => __('Side Left','soledad' ),
		'side-right' => __('Side Right','soledad' ),
	)
);
$options[] = array(
	'id'       => 'size_header_cart_icon_check',
	'default'  => '17',
	'sanitize' => 'penci_sanitize_number_field',
	'label'    => __( 'Custom Size for Woocommerce Icons at the Header', 'soledad' ),
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'size_header_cart_icon_check',
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
	'id'          => 'penci_woo_disable_breadcrumb',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Disable Breadcrumb', 'soledad' ),
	'description'=>__('This option apply for shop archive page only.<br/>If you want to modify the single product breadcrumb, please navigate to <strong>WooCommerce > Single Product > Breadcrumb Position</strong>.','soledad'),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_general_heading_2',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Sidebar Settings', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'       => 'penci_woo_shop_enable_sidebar',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Sidebar On Shop Page', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_woo_cat_enable_sidebar',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Sidebar On Shop Archive', 'soledad' ),
	'description'=>__('Show sidebar widget on Product Category/Tags/Atribute/Search Result pages','soledad'),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_woo_single_enable_sidebar',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Sidebar On Single Product', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_woo_single_sidebar_style',
	'default'     => 'bottom',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Single Product Sidebar Placement', 'soledad' ),
	'description'=>__('Select the position of the sidebar display on single product.','soledad'),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'bottom' => 'Bottom Content',
		'both'   => 'Top & Bottom',
	)
);
$options[] = array(
	'id'       => 'penci_woo_left_sidebar',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Left Sidebar', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_woo_widgets_scroll',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Scrollable For Filter Widget', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_woo_widgets_scroll_m_height',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Custom Maximium Height For Filter Widget', 'soledad' ),
	'id'       => 'penci_woo_widgets_scroll_height',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_woo_widgets_scroll_height',
		'mobile'  => 'penci_woo_widgets_scroll_m_height',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 300,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 300,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'id'       => 'penci_general_heading_4',
	'label'    => esc_html__( 'Pagination Settings', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'     => 'pagination',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Products pagination', 'soledad' ),
	'description'=>__('Choose a type for the pagination on your shop page.','soledad'),
	'id'          => 'penci_shop_product_pagination',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'pagination' => __('Pagination','soledad' ),
		'loadmore'   => __('Load More Button','soledad' ),
		'infinit'    => __('Infinit Scrolling','soledad' ),
	)
);
$options[] = array(
	'default'  => 'center',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Page Navigation Alignment', 'soledad' ),
	'id'       => 'penci_woo_paging_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'center' => __('Center','soledad' ),
		'left'   => __('Left','soledad' ),
		'right'  => __('Right','soledad' ),
	)
);
$options[] = array(
	'default'     => '400',
	'sanitize'    => 'penci_sanitize_number_field',
	'label'       => __( 'Infinit Ajax Scroll Threshold', 'soledad' ),
	'description'=>__('Sets the distance between the viewport to scroll area for scrollThreshold event to be triggered. <a class="wp-customizer-link" target="_blank" href="https://infinite-scroll.com/options.html#scrollthreshold">Click here</a> for more information.','soledad'),
	'id'          => 'penci_shop_product_pagination_ajax_threshold',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_shop_product_pagination_ajax_threshold',
	),
	'choices'     => array(
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
	'default'     => 'false',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Infinit Scroll History Options', 'soledad' ),
	'description'=>__('Changes page URL and browser history. <a class="wp-customizer-link" target="_blank" href="https://infinite-scroll.com/options.html#history-options">Click here</a> for more information.','soledad'),
	'id'          => 'penci_shop_product_pagination_ajax_history',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'false'   => __('Disable','soledad' ),
		'push'    => __('Push','soledad' ),
		'replace' => __('Replace','soledad' ),
	)
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'History Title', 'soledad' ),
	'description'=>__('Updates the window title. Requires history enabled. <a class="wp-customizer-link" target="_blank" href="https://infinite-scroll.com/options.html#historytitle">Click here</a> for more information.','soledad'),
	'id'          => 'penci_shop_product_pagination_ajax_title',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => true,
	'id'          => 'penci_shop_mini_cart_quantity',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Side Cart Product Quantity Input', 'soledad' ),
	'description'=>__('Show quantity input on side cart product item.','soledad'),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'id'       => 'penci_shop_stock_progress_bar',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Stock Progress Bar on Product', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_general_heading_5',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Other Settings', 'soledad' ),
	'type'     => 'soledad-fw-header',
);

$options[] = array(
	'id'       => 'penci_woocommerce_search_included_posts',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show blog search results below of product search', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_woocommerce_search_included_total',
	'default'  => 5,
	'sanitize' => 'penci_sanitize_number_field',
	'label'    => __( 'Total blog item Display under product serch', 'soledad' ),
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_woocommerce_search_included_total',
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
	'id'       => 'penci_woocommerce_standard_page',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Use standard page content for cart and checkout pages.', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
return $options;
