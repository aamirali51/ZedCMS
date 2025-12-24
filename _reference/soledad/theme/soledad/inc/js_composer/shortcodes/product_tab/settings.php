<?php
vc_map(
	array(
		'name'                    => penci_get_theme_name('Penci').' '.esc_html__( 'Products tab', 'soledad' ),
		'base'                    => 'penci_product_tab',
		'as_child'                => array( 'only' => 'penci_products_tabs' ),
		'content_element'         => true,
		'show_settings_on_create' => true,
		'html_template'           => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/product_tab/frontend.php',
		'category'                => penci_get_theme_name('Soledad'),
		'description'             => esc_html__( 'Product tabs for your marketplace', 'soledad' ),
		'icon'                    => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
		'params'                  => array_merge( array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Tab Title', 'soledad' ),
				'param_name'  => 'title',
				'admin_label' => true,
			),
			/**
			 * Icon
			 */
			array(
				'type'             => 'attach_image',
				'heading'          => esc_html__( 'Icon for the tab', 'soledad' ),
				'param_name'       => 'icon',
				'hint'             => esc_html__( 'Select icon from media library.', 'soledad' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Icon size', 'soledad' ),
				'param_name'       => 'icon_size',
				'hint'             => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'soledad' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'description'      => esc_html__( 'Example: \'thumbnail\', \'medium\', \'large\', \'full\' or enter image size in pixels: \'200x100\'.', 'soledad' ),
			)
		), penci_get_products_shortcode_params() )
	)
);
