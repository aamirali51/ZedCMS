<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_single_meta_field",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_meta_field/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Meta Field', 'soledad' ),
	'description'   => 'Show custom field data',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Meta Source', 'soledad' ),
			'param_name'       => 'meta_source',
			'value'            => array(
				'Custom Field'          => 'custom',
				'Advanced Custom Field' => 'acf',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'       => 'textfield',
			'param_name' => 'cspost_cpost_meta',
			'heading'    => esc_html__( 'Custom Post Meta Keys', 'soledad' ),
		),
		array(
			'type'        => 'autocomplete',
			'param_name'  => 'cspost_cpost_acf_meta',
			'heading'     => esc_html__( 'Custom Post ACF Meta Keys', 'soledad' ),
			'description' => 'You can show your own custom fields easily by using the <a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin.',
			'settings'    => array(
				'multiple'       => true,
				'min_length'     => 1,
				'groups'         => false,
				'unique_values'  => true,
				'display_inline' => true,
				'delay'          => 500,
				'auto_focus'     => true,
				'values'         => Penci_Vc_Params_Helper::penci_get_meta_list()
			),
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Text Align', 'soledad' ),
			'param_name'       => 'meta_align',
			'value'            => array(
				'Left'   => 'left',
				'Center' => 'center',
				'Right'  => 'right',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Meta Label Text', 'soledad' ),
			'param_name'       => 'meta_label',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Justify Align?', 'soledad' ),
			'param_name'       => 'justify_align',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), array(
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Icon Style', 'soledad' ),
			'param_name'       => 'meta_icon_style',
			'value'            => array(
				'Default' => 'default',
				'Style 1' => 's1',
				'Style 2' => 's2',
				'Style 3' => 's3',
				'Style 4' => 's4',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Text Color', 'soledad' ),
			'param_name'       => 'penci_single_meta_gnr_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Link Color', 'soledad' ),
			'param_name'       => 'meta-link-color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Link Hover Color', 'soledad' ),
			'param_name'       => 'meta-link-hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Meta Background Color', 'soledad' ),
			'param_name'       => 'penci_single_meta_mt_bg_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Icon Color', 'soledad' ),
			'param_name'       => 'penci_single_meta_gnr_icon_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Background Color', 'soledad' ),
			'param_name'       => 'penci_single_meta_gnr_bg_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Border Color', 'soledad' ),
			'param_name'       => 'penci_single_meta_gnr_bd_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
