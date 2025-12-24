<?php
/* Sidebar Section */
$mobile_sidebar_section = 'penci_header_drawer_container_section';
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_style",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Mobile Sidebar Style', 'soledad' ),
	'choices'   => [
		''          => 'Default',
		'fullwidth' => 'Full Width',
	]
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_position",
	'default'   => 'left',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Mobile Sidebar Position', 'soledad' ),
	'choices'   => [
		'left'  => 'Left',
		'right' => 'Right',
	]
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_background_img",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-image',
	'label'     => esc_html__( 'Mobile Sidebar Background Image', 'soledad' ),
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_background_color",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Mobile Sidebar Background Color', 'soledad' ),
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_background_repeat",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Mobile Sidebar Background Repeat', 'soledad' ),
	'choices'   => [
		'repeat'    => 'repeat',
		'repeat-x'  => 'repeat-x',
		'repeat-y'  => 'repeat-y',
		'no-repeat' => 'no-repeat',
		'initial'   => 'initial',
		'inherit'   => 'inherit'
	]
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_background_position",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Mobile Sidebar Background Position', 'soledad' ),
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
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_background_size",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Mobile Sidebar Background Size', 'soledad' ),
	'choices'   => [
		'auto'    => 'auto',
		'length'  => 'length',
		'cover'   => 'cover',
		'contain' => 'contain',
		'initial' => 'initial',
		'inherit' => 'inherit'
	]
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_background_attachment",
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Mobile Sidebar Background Attachment', 'soledad' ),
	'choices'   => [
		'scroll'  => 'scroll',
		'fixed'   => 'fixed',
		'local'   => 'local',
		'initial' => 'initial',
		'inherit' => 'inherit'
	]
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_spacing_setting",
	'default'   => '',
	'type'      => 'soledad-fw-box-model',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_custom_sanitization',
	'label'     => esc_html__( 'Spacing & Borders', 'soledad' ),
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
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_border_setting",
	'type'      => 'soledad-fw-color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __( ' Mobile Sidebar Borders Color', 'soledad' ),
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_border_style_setting",
	'default'   => 'solid',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( ' Mobile Sidebar Borders Style', 'soledad' ),
	'choices'   => [
		'dotted' => 'Dotted',
		'dashed' => 'Dashed',
		'solid'  => 'Solid',
		'double' => 'Double',
	],
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_text_color_setting",
	'type'      => 'soledad-fw-color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'settings'  => "penci_header_mobile_sidebar_text_color_setting",
	'label'     => __( 'General Text Color', 'soledad' ),
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_link_color_setting",
	'default'   => '',
	'type'      => 'soledad-fw-color',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __( ' Mobile Sidebar Link Color', 'soledad' ),
);
$options[]              = array(
	'id'        => "penci_header_mobile_sidebar_link_hv_color_setting",
	'default'   => '',
	'type'      => 'soledad-fw-color',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __( ' Mobile Sidebar Link Hover Color', 'soledad' ),
);

return $options;
