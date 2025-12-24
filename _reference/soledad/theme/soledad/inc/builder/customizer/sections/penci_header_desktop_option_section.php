<?php
$options   = [];
$options[] = array(
	'id'       => "penci_header_overlap_setting",
	'default'  => 'disable',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'disable' => 'No',
		'enable'  => 'Yes',
	],
	'label'    => __( 'Enable Transparent Header( Overlap Header )', 'soledad' ),
);
$options[] = array(
	'id'       => "penci_header_wrap_all",
	'default'  => 'disable',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'disable' => 'No',
		'enable'  => 'Yes',
	],
	'label'    => __( 'Enable Boxed Header ?', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_shadow",
	'default'   => 'disable',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'choices'   => [
		'disable' => 'No',
		'enable'  => 'Yes',
	],
	'label'     => __( 'Enable Header Shadow ?', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_wrap_width",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'choices'   => [
		'container-normal' => '1170px',
		'container-1400'   => '1400px',
		'container-custom' => 'Custom Width',
	],
	'label'     => __( 'Select Width for Boxed Header', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_wrap_custom_width",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __( 'Boxed Header Custom Width', 'soledad' ),
	'ids'       => array(
		'desktop' => "penci_header_wrap_custom_width",
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 9999,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'        => "penci_header_wrap_lr_spc",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __( 'Boxed Header Left/Right Margin', 'soledad' ),
	'ids'       => array(
		'desktop' => "penci_header_wrap_lr_spc",
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 9999,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_spacing_setting',
	'type'      => 'soledad-fw-box-model',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_custom_sanitization',
	'label'     => esc_html__( 'Spacing & Borders', 'soledad' ),
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
	'id'        => 'penci_header_border_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __( 'Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_border_style',
	'default'   => 'solid',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( ' Borders Style', 'soledad' ),
	'choices'   => [
		'none'   => 'None',
		'dotted' => 'Dotted',
		'dashed' => 'Dashed',
		'solid'  => 'Solid',
		'double' => 'Double',
	],
);

return $options;
