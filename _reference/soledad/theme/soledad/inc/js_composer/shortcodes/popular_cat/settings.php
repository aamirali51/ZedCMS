<?php
$group_color   = 'Typo & Color';

$params_heading           = Penci_Vc_Params_Helper::params_heading();
$extra_params             = Penci_Vc_Params_Helper::extra_params();
$param_heading_typo_color = Penci_Vc_Params_Helper::params_heading_typo_color();

$main_params = array(
	array(
		'type'       => 'textfield',
		'heading'    => esc_html__( 'Amount', 'soledad' ),
		'param_name' => 'plimit',
		'std'        => '10',
	),
	array(
		'type'       => 'dropdown',
		'heading'    => __( 'Categories type', 'soledad' ),
		'param_name' => 'pcat_type',
		'value'      => array(
			__( 'Popular categories by number posts', 'soledad' )   => 'default',
			__( 'Popular categories sort by name A->Z', 'soledad' ) => 'alphabetical_order',
		),
		'std'        => 'default',
	),
	array(
		'type'             => 'penci_switch',
		'heading'          => esc_html__( 'Show posts count', 'soledad' ),
		'param_name'       => 'pcount',
		'edit_field_class' => 'vc_col-sm-6',
		'true_state'       => 'yes',
		'false_state'      => 'no',
		'default'          => 'no',
		'std'              => 'no',
	),
	array(
		'type'             => 'penci_switch',
		'heading'          => esc_html__( 'Show hierarchy', 'soledad' ),
		'param_name'       => 'phierarchical',
		'edit_field_class' => 'vc_col-sm-6',
		'true_state'       => 'yes',
		'false_state'      => 'no',
		'default'          => 'no',
		'std'              => 'no',
	),
	array(
		'type'             => 'penci_switch',
		'heading'          => esc_html__( 'Hide Uncategorized category', 'soledad' ),
		'param_name'       => 'phide_uncat',
		'edit_field_class' => 'vc_col-sm-6',
		'true_state'       => 'yes',
		'false_state'      => 'no',
		'default'          => 'no',
		'std'              => 'no',
	)
);
$typo_params = array(
	array(
		'type'             => 'textfield',
		'param_name'       => 'heading_popularcat_settings',
		'heading'          => esc_html__( 'Popular cats', 'soledad' ),
		'value'            => '',
		'group'            => $group_color,
		'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
	),
	array(
		'type'             => 'colorpicker',
		'heading'          => esc_html__( 'Link Color', 'soledad' ),
		'param_name'       => 'plink_color',
		'group'            => $group_color,
		'edit_field_class' => 'vc_col-sm-6',
	),
	array(
		'type'             => 'colorpicker',
		'heading'          => esc_html__( 'Link Hover Color', 'soledad' ),
		'param_name'       => 'plink_hcolor',
		'group'            => $group_color,
		'edit_field_class' => 'vc_col-sm-6',
	),
	array(
		'type'       => 'penci_responsive_sizes',
		'param_name' => 'pcat_item_size',
		'heading'    => __( 'Font size for Link', 'soledad' ),
		'value'      => '',
		'std'        => '',
		'suffix'     => 'px',
		'min'        => 1,
		'group'      => $group_color,
	),
	array(
		'type'             => 'colorpicker',
		'heading'          => esc_html__( 'Post Counts  Text Color', 'soledad' ),
		'param_name'       => 'ppcount_color',
		'group'            => $group_color,
		'edit_field_class' => 'vc_col-sm-6',
	)
);

vc_map( array(
	'base'          => "penci_popular_cat",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name('Soledad'),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/popular_cat/frontend.php',
	'weight'        => 700,
	'name'          => penci_get_theme_name('Penci').' '.__( 'Popular Cat', 'soledad' ),
	'description'   => __( 'Popular Cat Block', 'soledad' ),
	'controls'      => 'full',
	'params'        => array_merge( $main_params, $params_heading, $param_heading_typo_color, $typo_params, $extra_params )
) );
