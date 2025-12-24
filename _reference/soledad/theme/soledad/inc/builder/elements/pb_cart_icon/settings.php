<?php 

$options   = [];
$options[] = array(
	'id'        => 'penci_header_pb_cart_icon_section_btn_style',
	'default'   => 'customize',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Cart Icon Button Pre-define Style', 'soledad' ),
	'choices'   => [
		'customize' => __('Default','soledad' ),
		'style-4'   => __('Filled','soledad' ),
		'style-1'   => __('Bordered','soledad' ),
		'style-2'   => __('Link','soledad' ),
	]
);
$options[] = array(
	'id'        => 'penci_header_pb_cart_icon_section_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => esc_html__( 'Cart Icon Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_cart_icon_section_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => esc_html__( 'Cart Icon Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_cart_icon_section_btnbstyle',
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
	'id'        => 'penci_header_pb_cart_icon_section_bcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Cart Icon Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_cart_icon_section_bhcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Cart Icon Hover Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_cart_icon_section_bgcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Cart Icon Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_cart_icon_section_bghcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Cart Icon Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_cart_icon_section_item_count_txt',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Number Count Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_cart_icon_section_item_count_bg',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Number Count Text Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_cart_icon_section_btnspacing',
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
	'id'        => 'penci_header_pb_cart_icon_section_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __('Icon Size','soledad'),
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_pb_cart_icon_section_size',
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
	'id'        => 'penci_header_pb_cart_icon_section_spacing',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-box-model',
	'label'     => __( 'Item Spacing', 'soledad' ),
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

return $options;
