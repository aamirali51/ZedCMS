<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_single_author",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_author/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Author', 'soledad' ),
	'description'   => 'Post Builder - Author',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Author Box Style', 'soledad' ),
			'param_name'       => 'penci_authorbio_style',
			'value'            => array(
				'Default' => 'style-1',
				'Style 2' => 'style-2',
				'Style 3' => 'style-3',
				'Style 4' => 'style-4',
				'Style 5' => 'style-5',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Author Box Image Style', 'soledad' ),
			'param_name'       => 'penci_bioimg_style',
			'value'            => array(
				'Round'         => 'round',
				'Square'        => 'square',
				'Round Borders' => 'sround',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Author Image Size', 'soledad' ),
			'param_name'       => 'penci_author_ava_size',
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 100
		),
	), array(
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Color for Author Name', 'soledad' ),
			'param_name'       => 'color_name',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Color for General Text', 'soledad' ),
			'param_name'       => 'color_text',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Color for Text Link', 'soledad' ),
			'param_name'       => 'color_link',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Hover Color for Text Link', 'soledad' ),
			'param_name'       => 'hcolor_link',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
