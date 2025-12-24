<?php

vc_map(
	array(
		'name'            => esc_html__( 'Extra menu list item', 'soledad' ),
		'base'            => 'penci_advanced_list_item',
		'as_child'        => array( 'only' => 'penci_advanced_list' ),
		'content_element' => true,
		'category'        => esc_html__( 'Soledad', 'soledad' ),
		'description'     => esc_html__( 'A link for your extra menu list', 'soledad' ),
		'html_template'   => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/advanced_list_item/frontend.php',
		'icon'            => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
		'params'          => array(
			/**
			 * Link
			 */
			array(
				'type'             => 'textfield',
				'param_name'       => 'link_divider',
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
				'hint'             => esc_html__( 'Enter URL if you want this parent menu item to have a link.', 'soledad' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			/**
			 * Label
			 */
			array(
				'type'             => 'textfield',
				'param_name'       => 'label_divider',
				'heading'          => esc_html__( 'Label', 'soledad' ),
				'value'            => '',
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Label text (optional)', 'soledad' ),
				'param_name'       => 'label_text',
				'hint'             => esc_html__( 'Write a label for this menu item badge like “Sale”, “Hot”, “New” etc. Leave empty to not add any badges.', 'soledad' ),
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
			 */
			array(
				'type'             => 'textfield',
				'param_name'       => 'image_divider',
				'heading'          => esc_html__( 'Image', 'soledad' ),
				'value'            => '',
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'             => 'attach_image',
				'heading'          => esc_html__( 'Image', 'soledad' ),
				'param_name'       => 'image',
				'value'            => '',
				'hint'             => esc_html__( 'Select image from media library.', 'soledad' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Image size', 'soledad' ),
				'param_name'       => 'image_size',
				'hint'             => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'soledad' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'description'      => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'soledad' ),
			),
			/**
			 * Extra
			 */
			array(
				'type'             => 'textfield',
				'param_name'       => 'extra_divider',
				'heading'          => esc_html__( 'Extra options', 'soledad' ),
				'value'            => '',
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Extra class name', 'soledad' ),
				'param_name' => 'el_class',
				'hint'       => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'soledad' ),
			),
		),
	)
);
