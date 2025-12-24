<?php
/* Header Sticky */
$options   = [];
$options[] = array(
	'id'       => 'penci_header_desktop_sticky_shadow',
	'default'  => 'enable',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => esc_attr__( 'Enable Sticky Shadow ?', 'soledad' ),
	'section'  => 'penci_header_desktop_sticky_section',
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'disable' => 'No',
		'enable'  => 'Yes',
	]
);

$options[] = array(
	'id'       => 'penci_header_desktop_sticky_hide_down',
	'default'  => 'disable',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => esc_attr__( 'Hide When Scrolling Down', 'soledad' ),
	'section'  => 'penci_header_desktop_sticky_section',
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'disable' => 'No',
		'enable'  => 'Yes',
	]
);

$options[] = array(
	'id'       => "penci_header_desktop_sticky_wrap_all",
	'default'  => 'disable',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'disable' => 'No',
		'enable'  => 'Yes',
	],
	'label'    => __( 'Enable Boxed Header Sticky ?', 'soledad' ),
	'section'  => 'penci_header_desktop_sticky_section',
);

$options[] = array(
	'id'        => "penci_header_desktop_sticky_wrap_width",
	'default'   => 'container-fullwidth',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'choices'   => [
		'container-normal' => '1170px',
		'container-1400'   => '1400px',
		'container-custom' => 'Custom Width',
	],
	'label'     => __( 'Select Width for Boxed Header', 'soledad' ),
	'section'   => 'penci_header_desktop_sticky_section',
);

$options[] = array(
	'id'        => "penci_header_desktop_sticky_wrap_custom_width",
	'default'   => '',
	'type'      => 'soledad-fw-size',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __( 'Boxed Header Custom Width', 'soledad' ),
	'section'   => 'penci_header_desktop_sticky_section',
	'ids'       => array(
		'desktop' => "penci_header_desktop_sticky_wrap_custom_width",
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
	'id'        => "penci_header_desktop_sticky_lr_spc",
	'default'   => '',
	'type'      => 'soledad-fw-size',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __( 'Boxed Header Left/Right Margin', 'soledad' ),
	'section'   => 'penci_header_desktop_sticky_section',
	'ids'       => array(
		'desktop' => "penci_header_desktop_sticky_lr_spc",
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
	'id'        => 'penci_header_desktop_sticky_spacing_setting',
	'type'      => 'soledad-fw-box-model',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_custom_sanitization',
	'label'     => esc_html__( 'Spacing & Borders', 'soledad' ),
	'section'   => 'penci_header_desktop_sticky_section',
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
	'id'        => 'penci_header_sticky_border_color',
	'default'   => '',
	'type'      => 'soledad-fw-color',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __( 'Borders Color', 'soledad' ),
	'section'   => 'penci_header_desktop_sticky_section',
);

$options[] = array(
	'id'        => 'penci_header_sticky_border_style',
	'default'   => 'solid',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( ' Borders Style', 'soledad' ),
	'section'   => 'penci_header_desktop_sticky_section',
	'choices'   => [
		'none'   => 'None',
		'dotted' => 'Dotted',
		'dashed' => 'Dashed',
		'solid'  => 'Solid',
		'double' => 'Double',
	],
);

return $options;
