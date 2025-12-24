<?php 

$options   = [];
$options[] = array(
	'id'       => 'penci_header_pb_hamburger_menu_desc',
	'type'     => 'soledad-fw-alert',
	'label'    => __('You can go to "Appearance > Customize > Vertical Navigation & Menu Hamburger" to adjust the sidebar hamburger.','soledad' ),
	'sanitize' => 'penci_sanitize_choices_field',
	'default'  => 'notice-bg'
);
$options[] = array(
	'id'        => 'penci_header_pb_hamburger_menu_btn_style',
	'default'   => 'customize',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Hamburger Button Pre-define Style', 'soledad' ),
	'choices'   => [
		'customize' => __('Default','soledad' ),
		'style-4'   => __('Filled','soledad' ),
		'style-1'   => __('Bordered','soledad' ),
		'style-2'   => __('Link','soledad' ),
	]
);
$options[] = array(
	'id'        => 'penci_header_pb_hamburger_menu_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __('Custom Size','soledad' ),
	'ids'       => array(
		'desktop' => 'penci_header_pb_hamburger_menu_size',
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
	'id'        => 'penci_header_pb_hamburger_menu_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_hamburger_menu_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Text Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_hamburger_menu_btnbstyle',
	'default'   => 'none',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Borders Style', 'soledad' ),
	'choices'   => [
		'none'   => __('None','soledad' ),
		'dotted' => __('Dotted','soledad' ),
		'dashed' => __('Dashed','soledad' ),
		'solid'  => __('Solid','soledad' ),
		'double' => __('Double','soledad' ),
	],
);
$options[] = array(
	'id'        => 'penci_header_pb_hamburger_menu_bcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_hamburger_menu_bhcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Menu Hover Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_hamburger_menu_bgcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_hamburger_menu_bghcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => esc_html__( 'Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_hamburger_menu_btnspacing',
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
	'id'        => 'penci_header_pb_hamburger_menu_spacing',
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
	'id'        => 'penci_header_pb_hamburger_menu_class',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Custom CSS Class', 'soledad' ),
);

return $options;
