<?php
$mobile_row   = 'topbar';
$name         = 'Mobile Top Bar';
$section_name = "penci_header_mobile_{$mobile_row}_setting_section";
$options      = [];
/* Section Settings */
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_middle_column",
	'default'   => 'enable',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Middle Column Position', 'soledad' ),
	'choices'   => [
		'disable' => 'Flexible',
		'enable'  => 'Always Center',
	]
);
$options[] = array(
	'id'       => "penci_header_mobile_{$mobile_row}_sticky_setting",
	'default'  => 'enable',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'enable'  => 'Yes',
		'disable' => 'No',
	],
	'label'    => esc_html__( 'Sticky This Row on Scroll Down?', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_background_img",
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-image',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => $name . esc_html__( ' Background Image', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_background_color",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'choices'   => [
		'alpha' => true,
	],
	'label'     => $name . __( ' Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_sticky_background_color",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'choices'   => [
		'alpha' => true,
	],
	'label'     => $name . __( ' Background Color on Sticky', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_background_repeat",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => $name . __( ' Background Repeat', 'soledad' ),
	'choices'   => [
		'repeat'    => 'repeat',
		'repeat-x'  => 'repeat-x',
		'repeat-y'  => 'repeat-y',
		'no-repeat' => 'no-repeat',
		'initial'   => 'initial',
		'inherit'   => 'inherit'
	]
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_background_position",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => $name . __( ' Background Position', 'soledad' ),
	'choices'   => [
		'left top'      => 'left top',
		'left center'   => 'left center',
		'left bottom'   => 'left bottom',
		'right top'     => 'right top',
		'right center'  => 'right center',
		'right bottom'  => 'right bottom',
		'center top'    => 'center top',
		'center center' => 'center center',
		'center bottom' => 'center bottom',
	]
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_background_size",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => $name . __( ' Background Size', 'soledad' ),
	'choices'   => [
		'auto'    => 'auto',
		'length'  => 'length',
		'cover'   => 'cover',
		'contain' => 'contain',
		'initial' => 'initial',
		'inherit' => 'inherit'
	]
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_background_attachment",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => $name . __( ' Background Attachment', 'soledad' ),
	'choices'   => [
		'scroll'  => 'scroll',
		'fixed'   => 'fixed',
		'local'   => 'local',
		'initial' => 'initial',
		'inherit' => 'inherit'
	]
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_spacing_setting",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_custom_sanitization',
	'type'      => 'soledad-fw-box-model',
	'label'     => esc_html__( ' Spacing & Borders', 'soledad' ),
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
		'border'  => array(
			'border-top'    => '',
			'border-right'  => '',
			'border-bottom' => '',
			'border-left'   => '',
		),
	),
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_border_setting",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'label'     => $name . __( ' Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_border_style_setting",
	'default'   => 'solid',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => $name . __( ' Borders Style', 'soledad' ),
	'choices'   => [
		'dotted' => 'Dotted',
		'dashed' => 'Dashed',
		'solid'  => 'Solid',
		'double' => 'Double',
	],
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_text_color_setting",
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'penci_sanitize_choices_field',
	'settings'  => "penci_header_mobile_{$mobile_row}_text_color_setting",
	'label'     => $name . __( ' Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_mobile_{$mobile_row}_maxheight_setting",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-text',
	'settings'  => "penci_header_mobile_{$mobile_row}_maxheight_setting",
	'label'     => $name . esc_html__( ' Max Height', 'soledad' ),
);

return $options;
