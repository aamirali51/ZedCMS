<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_single_meta",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_meta/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Meta', 'soledad' ),
	'description'   => 'Post Builder - Meta',
	'controls'      => 'full',
	'params'        => array_merge( array(
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
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Meta Label Text', 'soledad' ),
			'param_name'       => 'hide_meta_label',
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
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Remove Divider Character', 'soledad' ),
			'param_name'       => 'remove-divider',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Spacing Between Meta', 'soledad' ),
			'param_name'       => 'meta-item-spacing',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Author', 'soledad' ),
			'param_name'       => 'penci_single_meta_author',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Author Avatar', 'soledad' ),
			'param_name'       => 'penci_single_author_avatar',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Author Borders Radius', 'soledad' ),
			'param_name'       => 'penci_single_author_avatar_br',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Author Spacing', 'soledad' ),
			'param_name'       => 'penci_single_author_avatar_sp',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Author Avatar Width', 'soledad' ),
			'param_name'       => 'penci_avatar_w',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post Author Icon?', 'soledad' ),
			'param_name'       => 'penci_single_meta_ava_icon_check',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post Date', 'soledad' ),
			'param_name'       => 'penci_single_meta_date',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post Date Icon', 'soledad' ),
			'param_name'       => 'penci_single_meta_date_icon_check',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post Comment', 'soledad' ),
			'param_name'       => 'penci_single_meta_comment',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post Comment Icon', 'soledad' ),
			'param_name'       => 'penci_single_meta_comment_icon_check',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post View', 'soledad' ),
			'param_name'       => 'penci_single_show_cview',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post View Icon', 'soledad' ),
			'param_name'       => 'penci_single_meta_view_icon_check',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post Reading Time', 'soledad' ),
			'param_name'       => 'penci_single_hreadtime',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post Reading Time Icon', 'soledad' ),
			'param_name'       => 'penci_single_meta_reading_icon_check',
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
	), array(
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Author Text Color', 'soledad' ),
			'param_name'       => 'meta-author-color',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Author Link Color', 'soledad' ),
			'param_name'       => 'meta-author-lcolor',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Author Link Hover Color', 'soledad' ),
			'param_name'       => 'meta-author-hcolor',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Post Date Color', 'soledad' ),
			'param_name'       => 'meta-pdate-color',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Post Comment Color', 'soledad' ),
			'param_name'       => 'meta-pcomment-color',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Post View Color', 'soledad' ),
			'param_name'       => 'meta-pview-color',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Post Reading Color', 'soledad' ),
			'param_name'       => 'meta-preading-color',
			'edit_field_class' => 'vc_col-sm-6'
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
