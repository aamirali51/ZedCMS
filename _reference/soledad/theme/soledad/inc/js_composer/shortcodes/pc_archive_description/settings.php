<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_archive_description",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Archive Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_archive_description/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Archive Builder - Description', 'soledad' ),
	'description'   => 'Archive Builder - Description',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Text Align', 'soledad' ),
			'param_name'       => 'text_align',
			'value'            => array(
				'Left'   => 'left',
				'Center' => 'center',
				'Right'  => 'right',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
	), array(
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Text Color', 'soledad' ),
			'param_name'       => 'text_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Link Color', 'soledad' ),
			'param_name'       => 'text_lcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Link Hover Color', 'soledad' ),
			'param_name'       => 'text_lhcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'       => 'penci_switch',
			'heading'    => __( 'Custom Font Family', 'soledad' ),
			'param_name' => 'use_custom_typo',
			'value'      => 'no',
			'group'      => $group_color,
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
			'group'      => $group_color,
			'min'        => 1,
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
