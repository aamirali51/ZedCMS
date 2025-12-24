<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_single_featured",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_featured/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Featured Image', 'soledad' ),
	'description'   => 'Post Builder - Featured Image',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Image Size', 'soledad' ),
			'param_name'       => 'penci_single_custom_thumbnail_size',
			'value'            => Penci_Vc_Params_Helper::get_list_image_sizes( true ),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Image Size for Mobile', 'soledad' ),
			'param_name'       => 'penci_single_custom_thumbnail_msize',
			'value'            => Penci_Vc_Params_Helper::get_list_image_sizes( true ),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Disable Autoplay on Slider', 'soledad' ),
			'param_name'       => 'penci_disable_autoplay_single_slider',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Show Image Caption', 'soledad' ),
			'param_name'       => 'penci_post_gallery_caption',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Disable Image Lightbox', 'soledad' ),
			'param_name'       => 'penci_disable_lightbox_single',
			'edit_field_class' => 'vc_col-sm-6'
		),
	), array(
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Caption Color', 'soledad' ),
			'param_name'       => 'caption_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Caption Background Color', 'soledad' ),
			'param_name'       => 'caption_bgcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
