<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_archive_breadcrumb",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Archive Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_archive_breadcrumb/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Archive Builder - Breadcrumb', 'soledad' ),
	'description'   => 'Archive Builder - Breadcrumb',
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
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'BreadCrumb Text Color', 'soledad' ),
			'param_name'       => 'breadcrumb-t-color',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'BreadCrumb Text Hover Color', 'soledad' ),
			'param_name'       => 'breadcrumb-t-hcolor',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_number',
			'heading'          => esc_html__( 'BreadCrumb Spacing', 'soledad' ),
			'param_name'       => 'breadcrumb-spacing',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'       => 'penci_switch',
			'heading'    => __( 'Custom Font Family for Breadcrumb', 'soledad' ),
			'param_name' => 'use_custom_typo',
			'value'      => 'no',
		),
		array(
			'type'       => 'google_fonts',
			'param_name' => 'main_text_font',
			'value'      => '',
			'dependency' => array( 'element' => 'use_custom_typo', 'value' => 'yes' ),
		),
		array(
			'type'       => 'penci_responsive_sizes',
			'param_name' => 'main_text_size',
			'heading'    => __( 'Font Size', 'soledad' ),
			'suffix'     => 'px',
			'min'        => 1,
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
