<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_single_featured_overlay",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_featured_overlay/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Featured Overlay', 'soledad' ),
	'description'   => 'Post Builder - Featured Overlay',
	'controls'      => 'full',
	'params'        => array_merge( array(

		array(
			'type'             => 'textfield',
			'param_name'       => '_desc_typo_heading',
			'heading'          => esc_html__( 'General Settings', 'soledad' ),
			'value'            => '',
			'group'            => $group_color,
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),

		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Enable Parallax on Featured Image', 'soledad' ),
			'param_name'       => 'penci_enable_jarallax_single',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Featured Image on Top', 'soledad' ),
			'param_name'       => 'penci_post_thumb',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Image Size', 'soledad' ),
			'param_name'       => 'img_size',
			'value'            => Penci_Vc_Params_Helper::get_list_image_sizes( true ),
			'edit_field_class' => 'vc_col-sm-6'
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Image Mobile Size', 'soledad' ),
			'param_name'       => 'img_msize',
			'value'            => Penci_Vc_Params_Helper::get_list_image_sizes( true ),
			'edit_field_class' => 'vc_col-sm-6'
		),

		array(
			'type'             => 'penci_slider',
			'value'            => '',
			'std'              => '',
			'suffix'           => 'px',
			'heading'          => esc_html__( 'Image Ratio (in %)', 'soledad' ),
			'param_name'       => 'img_msize',
			'edit_field_class' => 'vc_col-sm-6'
		),

		// Post Meta
		array(
			'type'             => 'textfield',
			'param_name'       => '_overlay_content_heading',
			'heading'          => esc_html__( 'Overlay Content', 'soledad' ),
			'value'            => '',
			'group'            => $group_color,
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Text Align', 'soledad' ),
			'param_name'       => 'content_overlay_align',
			'value'            => array(
				'Left'   => 'left',
				'Center' => 'center',
				'Right'  => 'right',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Content Align', 'soledad' ),
			'param_name'       => 'content_overlay_calign',
			'value'            => array(
				'Left'   => 'left',
				'Center' => 'center',
				'Right'  => 'right',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),

		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Breadcrumb', 'soledad' ),
			'param_name'       => 'hide_breadcrumb',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Title', 'soledad' ),
			'param_name'       => 'hide_title',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Sub Title', 'soledad' ),
			'param_name'       => 'hide_subtitle',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post Categories', 'soledad' ),
			'param_name'       => 'penci_post_cat',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post Meta', 'soledad' ),
			'param_name'       => 'hide_info',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		// Post Meta
		array(
			'type'             => 'textfield',
			'param_name'       => '_desc_typo_heading',
			'heading'          => esc_html__( 'Post Meta', 'soledad' ),
			'value'            => '',
			'group'            => $group_color,
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Post Meta Text Align', 'soledad' ),
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

		// General Style
		array(
			'type'             => 'textfield',
			'param_name'       => '_general_style',
			'heading'          => esc_html__( 'General Style', 'soledad' ),
			'value'            => '',
			'group'            => $group_color,
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Overlay Background Color', 'soledad' ),
			'param_name'       => 'overlay_bgcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Content Background Color', 'soledad' ),
			'param_name'       => 'overlay_ctbgcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'penci_slider',
			'heading'          => esc_html__( 'Content Width', 'soledad' ),
			'param_name'       => 'overlay_ctw',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'penci_slider',
			'heading'          => esc_html__( 'Content Padding', 'soledad' ),
			'param_name'       => 'overlay_ctpd',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'penci_slider',
			'heading'          => esc_html__( 'Content Border Radius', 'soledad' ),
			'param_name'       => 'overlay_ctbdr',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Overlay Align', 'soledad' ),
			'param_name'       => 'overlay_align',
			'value'            => array(
				'Left'   => 'margin-right: auto !important',
				'Center' => 'margin-left: auto !important; margin-right: auto !important;',
				'Right'  => 'margin-left: auto !important',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),

		// Breadcrumb Style
		array(
			'type'             => 'textfield',
			'param_name'       => '_breadcrumb_style',
			'heading'          => esc_html__( 'Breadcrumb Style', 'soledad' ),
			'value'            => '',
			'group'            => $group_color,
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Breadcrumb Text Color', 'soledad' ),
			'param_name'       => 'breadcrumb_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Breadcrumb Text Hover Color', 'soledad' ),
			'param_name'       => 'breadcrumb_lhcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		// Categories Style
		array(
			'type'             => 'textfield',
			'param_name'       => '_categories_style',
			'heading'          => esc_html__( 'Categories Style', 'soledad' ),
			'value'            => '',
			'group'            => $group_color,
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Category Style', 'soledad' ),
			'param_name'       => 'cat_pre_style',
			'value'            => array(
				'Default' => '',
				'Style 1' => 's1',
				'Style 2' => 's2',
				'Style 3' => 's3',
				'Style 4' => 's4',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Category Color', 'soledad' ),
			'param_name'       => 'cat_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Category Link Color', 'soledad' ),
			'param_name'       => 'cat_lcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Category Link Hover Color', 'soledad' ),
			'param_name'       => 'cat_lhcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Category Background Color', 'soledad' ),
			'param_name'       => 'cat_bgcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Category Background Hover Color', 'soledad' ),
			'param_name'       => 'cat_bghcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Category Border Color', 'soledad' ),
			'param_name'       => 'cat_bcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Category Border Hover Color', 'soledad' ),
			'param_name'       => 'cat_bhcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Category Item Border', 'soledad' ),
			'param_name'       => 'cat_border',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Category Border Style', 'soledad' ),
			'param_name'       => 'cat_border_style',
			'group'            => $group_color,
			'value'            => array(
				'Dotted' => 'dotted',
				'Dashed' => 'dashed',
				'Solid'  => 'solid',
				'Double' => 'double',
				'Groove' => 'groove',
				'Ridge'  => 'ridge',
				'Inset'  => 'inset',
				'Outset' => 'outset',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),

		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Category Border Radius', 'soledad' ),
			'param_name'       => 'cat_border_radius',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Remove Category Divider Character', 'soledad' ),
			'param_name'       => 'cat_divider',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		// Title Style
		array(
			'type'             => 'textfield',
			'param_name'       => '_title_style',
			'heading'          => esc_html__( 'Title & Subtitle Style', 'soledad' ),
			'value'            => '',
			'group'            => $group_color,
			'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Post Title Color', 'soledad' ),
			'param_name'       => 'title_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),

		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Post Sub Title Color', 'soledad' ),
			'param_name'       => 'subtitle_color',
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
