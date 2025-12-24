<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_single_breadcrumb",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_breadcrumb/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Breadcrumb', 'soledad' ),
	'description'   => 'Post Builder - Breadcrumb',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Breadcrumb Align', 'soledad' ),
			'param_name'       => 'breadcrumb_align',
			'value'            => array(
				'Left'   => 'left',
				'Center' => 'center',
				'Right'  => 'right',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Show Only Primary Category from "Yoast SEO" or "Rank Math" plugin for Breadcrumb', 'soledad' ),
			'param_name'       => 'enable_pri_cat_yoast_seo',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), array(
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'BreadCrumb Text Color', 'soledad' ),
			'param_name'       => 'breadcrumb-t-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'BreadCrumb Text Hover Color', 'soledad' ),
			'param_name'       => 'breadcrumb-l-hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_responsive_sizes',
			'heading'          => esc_html__( 'BreadCrumb Spacing', 'soledad' ),
			'param_name'       => 'breadcrumb-spacing',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'       => 'penci_switch',
			'heading'    => __( 'Custom Font Family for Breadcrumb', 'soledad' ),
			'param_name' => 'use_custom_typo',
			'group'      => $group_color,
			'value'      => 'no',
		),
		array(
			'type'       => 'google_fonts',
			'param_name' => 'main_text_font',
			'value'      => '',
			'group'      => $group_color,
			'dependency' => array( 'element' => 'use_custom_typo', 'value' => 'yes' ),
		),
		array(
			'type'       => 'penci_responsive_sizes',
			'param_name' => 'main_text_size',
			'heading'    => __( 'Font Size', 'soledad' ),
			'suffix'     => 'px',
			'min'        => 1,
			'group'      => $group_color,
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
