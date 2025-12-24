<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_archive_taxonomy",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Archive Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_archive_taxonomy/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Archive Builder - Taxonomy', 'soledad' ),
	'description'   => 'Archive Builder - Taxonomy',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Term Listing', 'soledad' ),
			'param_name'       => 'tax_showall',
			'value'            => array(
				'All Items'    => 'all',
				'Child Items'  => 'child',
				'Custom Items' => 'custom',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'autocomplete',
			'heading'          => esc_html__( 'Select the Post Taxonomies Term.', 'soledad' ),
			'param_name'       => 'taxonomies',
			'settings'         => array(
				'multiple'       => true,
				'min_length'     => 1,
				'groups'         => false,
				'unique_values'  => true,
				'display_inline' => true,
				'delay'          => 500,
				'auto_focus'     => true,
				'save_always'    => true,
				'values'         => penci_wpbakery_taxonomies_list(),
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'autocomplete',
			'heading'          => esc_html__( 'Select the Excluded Post Taxonomies Term.', 'soledad' ),
			'param_name'       => 'taxonomies_ex',
			'settings'         => array(
				'multiple'       => true,
				'min_length'     => 1,
				'groups'         => false,
				'unique_values'  => true,
				'display_inline' => true,
				'delay'          => 500,
				'auto_focus'     => true,
				'save_always'    => true,
				'values'         => penci_wpbakery_taxonomies_list(),
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Display Style', 'soledad' ),
			'param_name'       => 'term_style',
			'value'            => array(
				'Style 1' => 's1',
				'Style 2' => 's2',
				'Style 3' => 's3',
				'Style 4' => 's4',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Align', 'soledad' ),
			'param_name'       => 'term_align',
			'value'            => array(
				'Left'   => 'left',
				'Right'  => 'right',
				'Center' => 'center',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Order by', 'soledad' ),
			'param_name'       => 'orderby',
			'value'            => array(
				'Name'       => 'name',
				'Slug'       => 'slug',
				'ID'         => 'term_id',
				'Term Order' => 'term_order',
				'Term Count' => 'count',
				'Random'     => 'rand',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Order by', 'soledad' ),
			'param_name'       => 'order',
			'value'            => array(
				'DESC' => 'desc',
				'ASC'  => 'asc',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Limit Terms to Show', 'soledad' ),
			'param_name'       => 'number',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Activate Current Viewing Term?', 'soledad' ),
			'param_name'       => 'tax_currentitem',
			'value'            => 'no',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Show Posts Count', 'soledad' ),
			'param_name'       => 'tax_showcount',
			'value'            => 'no',
			'edit_field_class' => 'vc_col-sm-6'
		),
	), array(
		array(
			'type'             => 'penci_slider',
			'heading'          => esc_html__( 'Horizontal Space Between Items', 'soledad' ),
			'param_name'       => 'term_spacing',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_slider',
			'heading'          => esc_html__( 'Vertical Space Between Items', 'soledad' ),
			'param_name'       => 'term_vspacing',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Color', 'soledad' ),
			'param_name'       => 'term_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Hover & Active Color', 'soledad' ),
			'param_name'       => 'term_hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Background Color', 'soledad' ),
			'param_name'       => 'term_bgcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Hover & Active Background Color', 'soledad' ),
			'param_name'       => 'term_bghcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Border Color', 'soledad' ),
			'param_name'       => 'term_bdcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Hover & Active Border Color', 'soledad' ),
			'param_name'       => 'term_bdhcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Border Style', 'soledad' ),
			'param_name'       => 'term_border_style',
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
			'heading'          => esc_html__( 'Term Border Radius', 'soledad' ),
			'param_name'       => 'term_border_radius',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
