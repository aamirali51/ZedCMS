<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_archive_title",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Archive Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_archive_title/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Archive Builder - Title', 'soledad' ),
	'description'   => 'Archive Builder - Title',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Heading Markup', 'soledad' ),
			'param_name'       => 'heading_markup',
			'value'            => array(
				'H1' => 'h1',
				'H2' => 'h2',
				'H3' => 'h3',
				'H4' => 'h4',
				'H5' => 'h5',
				'H6' => 'h6',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Heading Align', 'soledad' ),
			'param_name'       => 'heading_align',
			'value'            => array(
				'Left'   => 'left',
				'Right'  => 'right',
				'Center' => 'center',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Remove Heading Line', 'soledad' ),
			'param_name'       => 'heading_line',
			'edit_field_class' => 'vc_col-sm-6'
		),
	), array(
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Prefix Text Color', 'soledad' ),
			'param_name'       => 'text_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Main Text Color', 'soledad' ),
			'param_name'       => 'main_text_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Heading Line Color', 'soledad' ),
			'param_name'       => 'heading-line-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Heading Line Spacing with Title', 'soledad' ),
			'param_name'       => 'heading-line-spacing',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Heading Line Width', 'soledad' ),
			'param_name'       => 'heading-line-w',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Heading Line Height', 'soledad' ),
			'param_name'       => 'heading-line-h',
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
			'group'      => $group_color,
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
			'group'      => $group_color,
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
