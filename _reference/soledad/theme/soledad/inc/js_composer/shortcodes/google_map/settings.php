<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "penci_google_map",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Soledad' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/google_map/frontend.php',
	'weight'        => 775,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Google Map', 'soledad' ),
	'description'   => 'Map Block',
	'controls'      => 'full',
	'params'        => array(
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Insert Map Using', 'soledad' ),
			'param_name' => 'map_using',
			'value'      => array(
				esc_html__( 'Address', 'soledad' )     => 'address',
				esc_html__( 'Coordinates', 'soledad' ) => 'coordinates',
			)
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Address', 'soledad' ),
			'param_name'  => 'address',
			'admin_label' => true,
			'dependency'  => array(
				'element' => 'map_using',
				'value'   => 'address'
			)
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Latitude', 'soledad' ),
			'param_name'  => 'latitude',
			'admin_label' => true,
			'dependency'  => array(
				'element' => 'map_using',
				'value'   => 'coordinates'
			)
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Longtitude', 'soledad' ),
			'param_name'  => 'longtitude',
			'admin_label' => true,
			'dependency'  => array(
				'element' => 'map_using',
				'value'   => 'coordinates'
			)
		),
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Map type', 'soledad' ),
			'param_name' => 'map_type',
			'value'      => array(
				esc_html__( 'Road', 'soledad' )      => 'road',
				esc_html__( 'Satellite', 'soledad' ) => 'satellite',
				esc_html__( 'Hybrid', 'soledad' )    => 'hybrid',
				esc_html__( 'Terrain', 'soledad' )   => 'terrain',
				esc_html__( 'Custom', 'soledad' )    => 'custom',
			)
		),
		array(
			'type'             => 'penci_responsive_sizes',
			'heading'          => esc_html__( 'Width', 'soledad' ),
			'param_name'       => 'map_width',
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_responsive_sizes',
			'heading'          => esc_html__( 'Height', 'soledad' ),
			'param_name'       => 'map_height',
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'       => 'attach_image',
			'heading'    => esc_html__( 'Marker Image', 'soledad' ),
			'param_name' => 'marker_img',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Marker Title', 'soledad' ),
			'param_name'  => 'marker_title',
			'admin_label' => true,
		),
		array(
			'type'        => 'exploded_textarea_safe',
			'heading'     => esc_html__( 'Info Window', 'soledad' ),
			'param_name'  => 'info_window',
			'description' => ''
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Zoom', 'soledad' ),
			'param_name'       => 'map_is_zoom',
			'edit_field_class' => 'vc_col-sm-4',
			'true_state'       => 'yes',
			'false_state'      => 'no',
			'default'          => 'no',
			'std'              => 'no',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Pan', 'soledad' ),
			'param_name'       => 'map_pan',
			'edit_field_class' => 'vc_col-sm-4',
			'true_state'       => 'yes',
			'false_state'      => 'no',
			'default'          => 'no',
			'std'              => 'no',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Map scale', 'soledad' ),
			'param_name'       => 'map_scale',
			'true_state'       => 'yes',
			'false_state'      => 'no',
			'default'          => 'no',
			'std'              => 'no',
			'edit_field_class' => 'vc_col-sm-4',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Street view', 'soledad' ),
			'param_name'       => 'map_street_view',
			'edit_field_class' => 'vc_col-sm-4',
			'true_state'       => 'yes',
			'false_state'      => 'no',
			'default'          => 'no',
			'std'              => 'no',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Rotate', 'soledad' ),
			'param_name'       => 'map_rotate',
			'edit_field_class' => 'vc_col-sm-4',
			'true_state'       => 'yes',
			'false_state'      => 'no',
			'default'          => 'no',
			'std'              => 'no',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Overview map', 'soledad' ),
			'param_name'       => 'map_overview',
			'edit_field_class' => 'vc_col-sm-4',
			'true_state'       => 'yes',
			'false_state'      => 'no',
			'default'          => 'no',
			'std'              => 'no',
		),
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Zoom', 'soledad' ),
			'param_name' => 'map_zoom',
			'value'      => array(
				6  => 6,
				7  => 7,
				8  => 8,
				9  => 9,
				10 => 10,
				11 => 11,
				12 => 12,
				13 => 13,
				14 => 14,
				15 => 15,
				16 => 16,
			),
			'std'        => '8',
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => esc_html__( 'Scrollwheel', 'soledad' ),
			'param_name'  => 'map_scrollwheel',
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'          => 'no',
			'std'              => 'no',
		),

		vc_map_add_css_animation(),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra class name', 'soledad' ),
			'param_name'  => 'el_class',
			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'soledad' ),
		),
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'CSS box', 'soledad' ),
			'param_name' => 'css',
			'group'      => __( 'Design Options', 'soledad' ),
		),
		penci_get_vc_responsive_spacing_map(),
	)
) );
