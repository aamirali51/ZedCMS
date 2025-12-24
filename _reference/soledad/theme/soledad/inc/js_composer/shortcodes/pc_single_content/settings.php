<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_single_content",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_content/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Content', 'soledad' ),
	'description'   => 'Post Builder - Content',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Blockquote Style', 'soledad' ),
			'param_name'       => 'block_style',
			'value'            => array(
				'style-1' => 'style-1',
				'Style 2' => 'style-2',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
	), array(
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Text Color', 'soledad' ),
			'param_name'       => 'main-text-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Link Color', 'soledad' ),
			'param_name'       => 'main-link-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Link Hover Color', 'soledad' ),
			'param_name'       => 'main-link-hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'H1 Color', 'soledad' ),
			'param_name'       => 'content-h1-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'H2 Color', 'soledad' ),
			'param_name'       => 'content-h2-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'H3 Color', 'soledad' ),
			'param_name'       => 'content-h3-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'H4 Color', 'soledad' ),
			'param_name'       => 'content-h4-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'H5 Color', 'soledad' ),
			'param_name'       => 'content-h5-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'H6 Color', 'soledad' ),
			'param_name'       => 'content-h6-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
