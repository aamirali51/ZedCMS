<?php
vc_map( array(
	'name'                    => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Advanced Menu', 'soledad' ),
	'base'                    => 'penci_advanced_list',
	'as_parent'               => array( 'only' => 'penci_advanced_list_item' ),
	'content_element'         => true,
	'show_settings_on_create' => true,
	'html_template'           => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/advanced_list/frontend.php',
	'category'                => penci_get_theme_name( 'Soledad' ),
	'description'             => esc_html__( 'Create a menu list for your mega menu dropdown', 'soledad' ),
	'icon'                    => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'weight'                  => 700,
	'params'                  => array(
		array(
			'type'             => 'textfield',
			'param_name'       => '_heading_settings1',
			'heading'          => esc_html__( 'Link', 'soledad' ),
			'value'            => '',
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),
		array(
			'type'             => 'textfield',
			'holder'           => 'div',
			'heading'          => esc_html__( 'Title', 'soledad' ),
			'param_name'       => 'title',
			'edit_field_class' => 'vc_col-sm-6 vc_column',

		),
		array(
			'type'             => 'vc_link',
			'heading'          => esc_html__( 'Link', 'soledad' ),
			'param_name'       => 'link',
			//'description'             => esc_html__( 'Enter URL if you want this parent menu item to have a link.', 'soledad' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		/**
		 * Label
		 */ array(
			'type'             => 'textfield',
			'param_name'       => '_heading_settings2',
			'heading'          => esc_html__( 'Label', 'soledad' ),
			'value'            => '',
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Label text (optional)', 'soledad' ),
			'param_name'       => 'label_text',
			'description'      => esc_html__( 'Write a label for this menu item badge like “Sale”, “Hot”, “New” etc. Leave empty to not add any badges.', 'soledad' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Label color', 'soledad' ),
			'param_name'       => 'label',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		/**
		 * Image
		 */ array(
			'type'             => 'textfield',
			'param_name'       => '_heading_settings3',
			'heading'          => esc_html__( 'Images', 'soledad' ),
			'value'            => '',
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),
		array(
			'type'             => 'attach_image',
			'heading'          => esc_html__( 'Image', 'soledad' ),
			'param_name'       => 'image',
			'value'            => '',
			'description'      => esc_html__( 'Select image from media library.', 'soledad' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Image size', 'soledad' ),
			'param_name'       => 'image_size',
			'description'      => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'soledad' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		/**
		 * Extra
		 */ array(
			'type'             => 'textfield',
			'param_name'       => '_heading_settings4',
			'heading'          => esc_html__( 'Extra Options', 'soledad' ),
			'value'            => '',
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra class name', 'soledad' ),
			'param_name'  => 'el_class',
			'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'soledad' ),
		),
	),
	'js_view'                 => 'VcColumnView',
) );
