<?php
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => 'penci_button_popup',
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Soledad' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/button_popup/frontend.php',
	'weight'        => 700,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Button Popup', 'soledad' ),
	'description'   => __( 'Show popup when click a button', 'soledad' ),
	'controls'      => 'full',
	'params'        => array_merge( array(

		array(
			'type'             => 'textfield',
			'param_name'       => 'heading_popup_gnr',
			'heading'          => esc_html__( 'Popup Settings', 'soledad' ),
			'value'            => '',
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),

		array(
			'type'       => 'textfield',
			'heading'    => __( 'Button Text', 'soledad' ),
			'param_name' => 'text',
			'std'        => '',
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Alignment', 'soledad' ),
			'param_name' => 'align',
			'value'      => array(
				__( 'Align Left', 'soledad' )   => 'left',
				__( 'Align Center', 'soledad' ) => 'center',
				__( 'Align Right', 'soledad' )  => 'right',
			),
			'std'        => 'center',
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Popup Content Style', 'soledad' ),
			'param_name' => 'popup_cstyle',
			'value'      => array(
				__( 'Custom Text/HTML', 'soledad' ) => 'text',
				__( 'Penci Block', 'soledad' )      => 'block',
			),
			'std'        => 'text',
		),

		array(
			'type'             => 'textfield',
			'param_name'       => 'heading_popup_ct',
			'heading'          => esc_html__( 'Popup Content', 'soledad' ),
			'value'            => '',
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),

		array(
			'type'       => 'textarea_html',
			'heading'    => __( 'Popup Content', 'soledad' ),
			'param_name' => 'popup_content',
		),

		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Popup Block', 'soledad' ),
			'param_name' => 'popup_block',
			'value'      => penci_builder_block_list( true ),
		),

		array(
			'type'       => 'penci_only_number',
			'heading'    => __( 'Popup Width', 'soledad' ),
			'param_name' => 'popup_width',
		),

		array(
			'type'       => 'penci_only_number',
			'heading'    => __( 'Popup Height', 'soledad' ),
			'param_name' => 'popup_height',
		),

		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Popup Position', 'soledad' ),
			'param_name' => 'popup_position',
			'value'      => array(
				'Top Left'      => 'top-left',
				'Top Center'    => 'top-center',
				'Top Right'     => 'top-right',
				'Middle Left'   => 'middle-left',
				'Middle Center' => 'middle-center',
				'Middle Right'  => 'middle-right',
				'Bottom Left'   => 'bottom-left',
				'Bottom Center' => 'bottom-center',
				'Bottom Right'  => 'bottom-right',
			),
			'std'        => 'middle-center',
		),

		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Popup Open Animation Style', 'soledad' ),
			'param_name' => 'popup_anityle',
			'value'      => array(
				'Move To Left'   => 'move-to-left',
				'Move To Right'  => 'move-to-right',
				'Move To Top'    => 'move-to-top',
				'Move To Bottom' => 'move-to-bottom',
				'Fade In'        => 'fadein',
				'Zoom In'        => 'zoomin',
			),
			'std'        => 'fadein',
		),

		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Add Sub Text?', 'soledad' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'param_name'  => 'add_subtext'
		),

		array(
			'type'       => 'textfield',
			'heading'    => __( 'Sub Text', 'soledad' ),
			'param_name' => 'subtext',
			'dependency' => array( 'element' => 'add_subtext', 'value' => array( 'yes' ) ),
		),

		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Sub Text Align', 'soledad' ),
			'param_name' => 'subtext_align',
			'value'      => array(
				__( 'Align Left', 'soledad' )   => 'left',
				__( 'Align Center', 'soledad' ) => 'center',
				__( 'Align Right', 'soledad' )  => 'right',
			),
			'std'        => 'center',
			'dependency' => array( 'element' => 'add_subtext', 'value' => array( 'yes' ) ),
		),

		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Add Icon to Button?', 'soledad' ),
			'param_name'  => 'add_icon',
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
		),

		array(
			'type'       => 'iconpicker',
			'heading'    => __( 'Button Icon', 'soledad' ),
			'param_name' => 'button_icon',
			'value'      => 'fa fa-adjust',
			'dependency' => array( 'element' => 'add_icon', 'value' => array( 'yes' ) ),
		),

		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Icon Position', 'soledad' ),
			'param_name' => 'icon_pos',
			'value'      => array(
				__( 'Align Left', 'soledad' )   => 'left',
				__( 'Align Center', 'soledad' ) => 'center',
			),
			'std'        => 'left',
			'dependency' => array( 'element' => 'add_icon', 'value' => array( 'yes' ) ),
		),

		array(
			'type'       => 'textfield',
			'heading'    => __( 'Button ID', 'soledad' ),
			'param_name' => 'button_id',
		),

	), Penci_Vc_Params_Helper::heading_block_params(), Penci_Vc_Params_Helper::params_heading_typo_color(), Penci_Vc_Params_Helper::extra_params(), array(

		array(
			'type'             => 'textfield',
			'param_name'       => 'heading_popup_btn',
			'heading'          => esc_html__( 'Button Settings', 'soledad' ),
			'value'            => '',
			'group'            => $group_color,
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),

		array(
			'type'       => 'google_fonts',
			'group'      => $group_color,
			'param_name' => 'btn_typo',
			'value'      => '',
		),
		array(
			'type'       => 'penci_responsive_sizes',
			'param_name' => 'btn_size',
			'heading'    => __( 'Font Size for Button Text', 'soledad' ),
			'suffix'     => 'px',
			'min'        => 1,
			'group'      => $group_color,
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Button Text Color', 'soledad' ),
			'param_name'       => 'btn_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Button Text Hover Color', 'soledad' ),
			'param_name'       => 'btn_hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Popup Overlay Background Color', 'soledad' ),
			'param_name'       => 'overlay_bgcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Popup Overlay Opacity', 'soledad' ),
			'param_name'       => 'overlay_opacity',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	) )
) );
