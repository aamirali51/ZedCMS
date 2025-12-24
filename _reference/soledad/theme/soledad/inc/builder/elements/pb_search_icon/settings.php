<?php 

$options   = [];
$options[] = array(
	'id'        => 'penci_header_search_style',
	'default'   => 'showup',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Search Style', 'soledad' ),
	'choices'   => [
		'showup'  => 'Default (Slide Up)',
		'overlay' => 'Overlay',
		'popup'   => 'Popup',
	]
);
$options[] = array(
	'id'        => 'penci_header_search_icon_btn_style',
	'default'   => 'customize',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Search Button Pre-define Style', 'soledad' ),
	'choices'   => [
		'customize' => __('Default','soledad' ),
		'style-4'   => __('Filled','soledad' ),
		'style-1'   => __('Bordered','soledad' ),
		'style-2'   => __('Link','soledad' ),
	]
);
$options[] = array(
	'id'        => 'penci_header_search_icon_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Icon Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_icon_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => esc_html__( 'Search Icon Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_btnborder_style',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Button Borders Style', 'soledad' ),
	'choices'   => [
		''       => __('Default','soledad' ),
		'none'   => __('None','soledad' ),
		'dotted' => __('Dotted','soledad' ),
		'dashed' => __('Dashed','soledad' ),
		'solid'  => __('Solid','soledad' ),
		'double' => __('Double','soledad' ),
	],
);
$options[] = array(
	'id'        => 'penci_header_search_icon_bcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Icon Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_icon_bhcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Icon Hover Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_button_bgcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Icon Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_button_bghcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Icon Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_border_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => esc_html__( 'Search Form Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_bg_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Form Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_input_border_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Form Input Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_input_bg_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Form Input Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_input_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Form Input Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_button_bg_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Form Submit Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_button_bg_hcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Form Submit Background Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_button_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Form Submit Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_button_hcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Search Form Submit Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_o_bgcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Overlay Search Form Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_o_bdcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Overlay Search Input Border Bottom Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_search_o_closecolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Overlay Close Button Color', 'soledad' ),
);

$options[] = array(
	'id'        => 'penci_header_search_icon_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __('Search Icon Size','soledad'),
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_search_icon_size',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_search_input_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __('Search Form Input Text Size','soledad' ),
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_search_input_size',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_search_btn_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __('Search Icon Size','soledad' ),
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_search_btn_size',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_search_btnspacing',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-box-model',
	'label'     => __( 'Button Spacing', 'soledad' ),
	'choices'   => array(
		'margin'        => array(
			'margin-top'    => '',
			'margin-right'  => '',
			'margin-bottom' => '',
			'margin-left'   => '',
		),
		'padding'       => array(
			'padding-top'    => '',
			'padding-right'  => '',
			'padding-bottom' => '',
			'padding-left'   => '',
		),
		'border'        => array(
			'border-top'    => '',
			'border-right'  => '',
			'border-bottom' => '',
			'border-left'   => '',
		),
		'border-radius' => array(
			'border-radius-top'    => '',
			'border-radius-right'  => '',
			'border-radius-bottom' => '',
			'border-radius-left'   => '',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_search_spacing',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-box-model',
	'label'     => __( 'Element Spacing', 'soledad' ),
	'choices'   => array(
		'margin'  => array(
			'margin-top'    => '',
			'margin-right'  => '',
			'margin-bottom' => '',
			'margin-left'   => '',
		),
		'padding' => array(
			'padding-top'    => '',
			'padding-right'  => '',
			'padding-bottom' => '',
			'padding-left'   => '',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_search_css_class',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Custom CSS Class', 'soledad' ),
);

return $options;
