<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'penci_meta_boxes', 'penci_product_meta_box' );
function penci_product_meta_box( $meta_boxes ) {

	$tabs = array(
		'product_general'       => array(
			'label' => esc_html__( 'General', 'soledad' ),
			'icon'  => 'dashicons dashicons-admin-settings',
		),
		'product_background'    => array(
			'label' => esc_html__( 'Background', 'soledad' ),
			'icon'  => 'dashicons dashicons-admin-customizer',
		),
		'product_sidebar'       => array(
			'label' => esc_html__( 'Sidebar', 'soledad' ),
			'icon'  => 'dashicons dashicons-welcome-widgets-menus',
		),
		'product_custom_tab'    => array(
			'label' => esc_html__( 'Custom Tab', 'soledad' ),
			'icon'  => 'dashicons dashicons-table-row-after',
		),
		'product_extra_options' => array(
			'label' => esc_html__( 'Extra Options', 'soledad' ),
			'icon'  => 'dashicons dashicons-cart',
		),
		'product_custom_css'    => array(
			'label' => esc_html__( 'Custom CSS', 'soledad' ),
			'icon'  => 'dashicons dashicons-editor-code',
		),
	);

	$soledad_sidebar_area                      = array();
	$soledad_sidebar_area['']                  = 'Default Value';
	$soledad_sidebar_area['main-sidebar']      = esc_attr__( "Main Sidebar", "soledad" );
	$soledad_sidebar_area['main-sidebar-left'] = esc_attr__( "Main Sidebar Left", "soledad" );
	$soledad_sidebar_area['penci-shop-single'] = esc_attr__( "Sidebar For Single Product", "soledad" );
	for ( $i = 1; $i <= 10; $i ++ ) {
		$soledad_sidebar_area[ 'custom-sidebar-' . $i ] = sprintf( esc_attr__( 'Custom Sidebar %s', 'soledad' ), $i );
	}

	$soledad_sidebar_area = apply_filters( 'penci_woo_settings_sidebars', $soledad_sidebar_area );

	$fields = array(

		array(
			'tab'     => 'product_general',
			'id'      => 'product_summary_align',
			'name'    => esc_html__( 'Product Summary Align', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => array(
				''       => 'Default Value ( on Customize )',
				'left'   => 'Align Left',
				'center' => 'Align Center',
			),
			'desc'    => esc_html__( 'Override main product summary content for this product.', 'soledad' ),
		),

		array(
			'tab'     => 'product_general',
			'id'      => 'penci_single_product_img_width',
			'name'    => esc_html__( 'Product image width', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => array(
				''                    => 'Default Value ( on Customize )',
				'standard'            => 'Standard',
				'medium'              => 'Medium',
				'large'               => 'Large',
				'fullwidth-container' => 'Full Width (Container)',
				'fullwidth'           => 'Full Width',
			),
			'desc'    => esc_html__( 'You can choose different page layout depending on the product image size you need', 'soledad' ),
		),

		array(
			'tab'     => 'product_general',
			'id'      => 'penci_single_product_thumbnail_position',
			'name'    => esc_html__( 'Thumbnails position', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => array(
				''                       => 'Default Value ( on Customize )',
				'thumbnail-left'         => 'Left',
				'thumbnail-right'        => 'Right',
				'thumbnail-bottom'       => 'Bottom',
				'thumbnail-bottom-1-col' => 'Bottom 1 Column',
				'thumbnail-bottom-2-col' => 'Bottom 2 Column',
				'thumbnail-grid'         => 'Grid',
				'thumbnail-without'      => 'Without',
			),
			'desc'    => esc_html__( 'Use vertical or horizontal position for thumbnails.', 'soledad' ),
		),

		array(
			'tab'     => 'product_general',
			'id'      => 'penci_single_product_style',
			'name'    => esc_html__( 'Product Style', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => array(
				''                  => 'Default Value ( on Customize )',
				'default'           => 'Standard',
				'accordion-tab'     => 'Accordion Toggle',
				'accordion-content' => 'Accordion Content',
			),
			'desc'    => esc_html__( 'Select default product style', 'soledad' ),
		),

		array(
			'tab'     => 'product_general',
			'id'      => 'penci_single_sticky_atc',
			'name'    => esc_html__( 'Enable sticky add to cart ?', 'soledad' ),
			'type'    => 'select',
			'options' => array(
				''        => 'Default Value ( on Customize )',
				'enable'  => 'Enable',
				'disable' => 'Disable',
			),
			'desc'    => esc_html__( 'Enable sticky add to cart on the bottom of the page', 'soledad' ),
		),

		// Sidebar
		array(
			'tab'     => 'product_sidebar',
			'id'      => 'sidebar_position',
			'name'    => esc_html__( 'Sidebar position', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => array(
				''        => 'Default Value ( on Customize )',
				'left'    => 'Sidebar Left',
				'right'   => 'Sidebar Right',
				'disable' => 'Disable Sidebar',
			),
			'desc'    => esc_html__( 'Select main content and sidebar alignment.', 'soledad' ),
		),

		array(
			'tab'     => 'product_sidebar',
			'id'      => 'sidebar_placement',
			'name'    => esc_html__( 'Sidebar Placement', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => array(
				''       => 'Default Value ( on Customize )',
				'bottom' => 'Bottom Content',
				'both'   => 'Top & Bottom',
			),
		),

		/*array(
			'tab'     => 'product_sidebar',
			'id'      => 'sidebar_size',
			'name'    => esc_html__( 'Sidebar size', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => array(
				''       => 'Default Value ( on Customize )',
				'small'  => 'Small Sidebar',
				'medium' => 'Medium Sidebar',
				'large'  => 'Large Sidebar',
			),
			'desc'    => esc_html__( 'You can set different sizes for your pages sidebar', 'soledad' ),
		),*/


		array(
			'tab'     => 'product_sidebar',
			'id'      => 'sidebar_area',
			'name'    => esc_html__( 'Custom sidebar for this product', 'soledad' ),
			'type'    => 'select',
			'std'     => '',
			'options' => $soledad_sidebar_area,
		),

		// Background
		array(
			'id'   => 'product_wrap_bgcolor',
			'type' => 'color',
			'name' => esc_html__( 'Background Color', 'soledad' ),
			'tab'  => 'product_background',
		),
		array(
			'id'   => 'product_wrap_bgimg',
			'type' => 'image',
			'name' => esc_html__( 'Background Image', 'soledad' ),
			'tab'  => 'product_background',
		),
		array(
			'name'    => esc_html__( 'Background Position', 'soledad' ),
			'id'      => 'product_wrap_bg_pos',
			'type'    => 'select',
			'options' => array(
				'center'        => esc_html__( 'Center', 'soledad' ),
				'left_top'      => esc_html__( 'Left Top', 'soledad' ),
				'left_center'   => esc_html__( 'Left Center', 'soledad' ),
				'left_bottom'   => esc_html__( 'Left Bottom', 'soledad' ),
				'right_top'     => esc_html__( 'Right Top', 'soledad' ),
				'right_center'  => esc_html__( 'Right Center', 'soledad' ),
				'right_bottom'  => esc_html__( 'Right Bottom', 'soledad' ),
				'center_top'    => esc_html__( 'Center Top', 'soledad' ),
				'center_bottom' => esc_html__( 'Center Bottom', 'soledad' ),
			),
			'std'     => 'center',
			'tab'     => 'product_background',
			'style'   => 'penci-col-6'
		),
		array(
			'name'    => esc_html__( 'Background Size', 'soledad' ),
			'id'      => 'product_wrap_bg_size',
			'type'    => 'select',
			'std'     => 'cover',
			'options' => array(
				'cover'   => esc_html__( 'Cover', 'soledad' ),
				'auto'    => esc_html__( 'Auto', 'soledad' ),
				'contain' => esc_html__( 'Contain', 'soledad' ),
			),
			'tab'     => 'product_background',
			'style'   => 'penci-col-6'
		),
		array(
			'name'    => esc_html__( 'Background Repeat', 'soledad' ),
			'id'      => 'product_wrap_bg_repeat',
			'type'    => 'select',
			'std'     => 'no-repeat',
			'options' => array(
				'repeat'    => esc_html__( 'Repeat', 'soledad' ),
				'no-repeat' => esc_html__( 'No repeat', 'soledad' ),
			),
			'tab'     => 'product_background',
			'style'   => 'penci-col-6'
		),

		// Custom Tab
		array(
			'name' => esc_html__( 'Hidden Tab Title', 'soledad' ),
			'id'   => 'tab_title_visible',
			'type' => 'checkbox',
			'tab'  => 'product_custom_tab',
		),

		array(
			'name' => esc_html__( 'Custom Tab Title', 'soledad' ),
			'id'   => 'tab_title',
			'type' => 'text',
			'tab'  => 'product_custom_tab',
		),

		array(
			'name' => esc_html__( 'Custom Tab Content', 'soledad' ),
			'id'   => 'tab_content',
			'type' => 'wysiwyg',
			'tab'  => 'product_custom_tab',
		),

		array(
			'name' => esc_html__( 'Priority', 'soledad' ),
			'id'   => 'tab_priority',
			'type' => 'number',
			'std'  => 50,
			'tab'  => 'product_custom_tab',
		),

		// Extra Options
		array(
			'name'    => esc_html__( 'Brands Display', 'soledad' ),
			'id'      => 'brand_locate',
			'type'    => 'select',
			'std'     => '',
			'options' => array(
				''        => esc_html__( 'Default Theme Locate', 'soledad' ),
				'top'     => esc_html__( 'Top', 'soledad' ),
				'summary' => esc_html__( 'Summary', 'soledad' ),
			),
			'tab'     => 'product_extra_options',
		),
		array(
			'name' => esc_html__( 'Permanent "New" label', 'soledad' ),
			'id'   => 'permanent_new_label',
			'type' => 'checkbox',
			'tab'  => 'product_extra_options',
			'desc' => __( 'Enable this option to make your product have "New" status forever.', 'soledad' ),
		),

		array(
			'name' => esc_html__( 'Hide related products', 'soledad' ),
			'id'   => 'hide_related_products',
			'type' => 'checkbox',
			'tab'  => 'product_extra_options',
			'desc' => __( 'You can hide related products on this page', 'soledad' ),
		),

		array(
			'name'    => esc_html__( 'Grid swatch attribute to display', 'soledad' ),
			'id'      => 'grid_swatch',
			'type'    => 'select',
			'tab'     => 'product_extra_options',
			'options' => penci_product_attributes_array( true ),
			'desc'    => __( 'Choose attribute that will be shown on products grid for this particular product', 'soledad' ),
		),

		// Custom css

		array(
			'name'        => esc_html__( 'Custom CSS Code', 'soledad' ),
			'id'          => 'product_custom_css',
			'type'        => 'textarea',
			'tab'         => 'product_custom_css',
			'placeholder' => '.class{ color: #fff; }',
			'desc'        => __( 'Enter your CSS code. In some case, the <code>!important</code> tag may be needed', 'soledad' ),
		),
	);

	$meta_boxes[] = array(
		'id'         => 'penci-metabox-product',
		'title'      => esc_html__( 'Product Options', 'soledad' ),
		'post_types' => array( 'product' ),
		'context'    => 'advanced',
		'priority'   => 'default',
		'autosave'   => 'false',
		'tabs'       => apply_filters( 'penci_product_meta_box_tabs', $tabs ),
		'fields'     => apply_filters( 'penci_product_meta_box_fields', $fields ),
	);

	return $meta_boxes;
}
