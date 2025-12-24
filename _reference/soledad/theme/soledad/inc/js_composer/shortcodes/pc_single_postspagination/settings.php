<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_single_postspagination",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_postspagination/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Posts Pagination', 'soledad' ),
	'description'   => 'Post Builder - Posts Pagination',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Show Post Navigation Thumbnail?', 'soledad' ),
			'param_name'       => 'penci_post_nav_thumbnail',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Thumbnail Size', 'soledad' ),
			'param_name'       => 'thumb_size',
			'value'            => Penci_Vc_Params_Helper::get_list_image_sizes( true ),
			'edit_field_class' => 'vc_col-sm-6'
		),
	), array(
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Color for Small Text Description', 'soledad' ),
			'param_name'       => 'post_desc_typo',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Color for Post Title', 'soledad' ),
			'param_name'       => 'post_title_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Post Title Hover Color', 'soledad' ),
			'param_name'       => 'post_title_hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
