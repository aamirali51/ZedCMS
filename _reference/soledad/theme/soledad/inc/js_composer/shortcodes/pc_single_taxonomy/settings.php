<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

function penci_get_post_taxonomies() {
	$post_taxonomies    = get_object_taxonomies( 'post' );
	$post_tax           = [];
	$post_tax['Select'] = '';
	foreach ( $post_taxonomies as $tname ) {
		$labels                     = get_taxonomy( $tname );
		$post_tax[ $labels->label ] = $tname;
	}

	return $post_tax;
}

vc_map( array(
	'base'          => "pc_single_taxonomy",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_taxonomy/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Taxonomy', 'soledad' ),
	'description'   => 'Post Builder - Taxonomy',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Taxonomies', 'soledad' ),
			'param_name'       => 'term_name',
			'value'            => penci_get_post_taxonomies(),
			'std'              => 'category',
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
			'heading'          => esc_html__( 'Label Style', 'soledad' ),
			'param_name'       => 'label_style',
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
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Text Align', 'soledad' ),
			'param_name'       => 'tax_align',
			'value'            => array(
				'Left'   => 'left',
				'Right'  => 'right',
				'Center' => 'center',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Show Taxonomy as Text', 'soledad' ),
			'param_name'       => 'term_link',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Show Label Text', 'soledad' ),
			'param_name'       => 'term_text',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Label Text', 'soledad' ),
			'param_name'       => 'label_text',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), array(
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Color', 'soledad' ),
			'param_name'       => 'term_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Hover Color', 'soledad' ),
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
			'heading'          => esc_html__( 'Term Background Hover Color', 'soledad' ),
			'param_name'       => 'term_bghcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Border Color', 'soledad' ),
			'param_name'       => 'term_bcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Border Hover Color', 'soledad' ),
			'param_name'       => 'term_bhcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Term Item Borders Radius', 'soledad' ),
			'param_name'       => 'term_border_radius',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
