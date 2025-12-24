<?php
$area          = 'bottomblock';
$name          = 'Bottom Block';
$area_settings = $area . 'bar';
$default_width = 'container-fullwidth';
$section_name  = "penci_header_{$area_settings}_setting_section";
/* Section Settings */
$options   = [];
$options[] = array(
	'id'       => "penci_header_{$area}_content_direction",
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => $name . __( ' Content Direction', 'soledad' ),
	'choices'  => [
		'row'     => 'Row',
		'columns' => 'Columns',
	]
);
$options[] = array(
	'id'        => "penci_header_{$area}_content_width",
	'default'   => $default_width,
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => $name . __( ' Container Width', 'soledad' ),
	'choices'   => [
		'container-normal'    => '1170px',
		'container-1400'      => '1400px',
		'container-fullwidth' => 'Fullwidth',
		'container-custom'    => 'Custom Width',
	]
);
$options[] = array(
	'id'        => "penci_header_{$area}_content_custom_width",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => $name . __( ' Custom Container Width', 'soledad' ),
	'ids'       => array(
		'desktop' => "penci_header_{$area}_content_custom_width",
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
	'id'        => "penci_header_{$area}_background_img",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-image',
	'label'     => $name . esc_html__( ' Background Image', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_{$area}_background_color",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'label'     => $name . __( ' Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_{$area}_transparent_background_color",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __( 'Enable Transparent Background Color ?', 'soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => [
		'enable'  => 'Yes',
		'disable' => 'No',
	]
);
$options[] = array(
	'id'        => "penci_header_{$area}_background_repeat",
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
	'id'        => "penci_header_{$area}_background_position",
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
	'id'        => "penci_header_{$area}_background_size",
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
		'inherit' => 'inherit',
	]
);
$options[] = array(
	'id'        => "penci_header_{$area}_background_attachment",
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
	'id'        => "penci_header_{$area}_spacing_setting",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_custom_sanitization',
	'type'      => 'soledad-fw-box-model',
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
	'id'        => "penci_header_{$area}_border_setting",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'label'     => $name . __( ' Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_{$area}_border_style_setting",
	'default'   => 'solid',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => $name . __( ' Borders Style', 'soledad' ),
	'choices'   => [
		'none'   => 'None',
		'dotted' => 'Dotted',
		'dashed' => 'Dashed',
		'solid'  => 'Solid',
		'double' => 'Double',
	],
);
$options[] = array(
	'id'        => "penci_header_{$area}_text_color_setting",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'settings'  => "penci_header_{$area}_text_color_setting",
	'label'     => __( 'General Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => "penci_header_{$area}_maxheight_setting",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-size',
	'label'     => $name . esc_html__( ' Max Height', 'soledad' ),
	'ids'  => array(
		'desktop' => "penci_header_{$area}_maxheight_setting",
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

return $options;
