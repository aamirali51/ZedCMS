<?php
/* Mobile Control */
$options   = [];
$options[] = array(
	'id'                => 'penci_header_mobile_sticky_shadow',
	'default'           => 'disable',
	'sanitize_callback' => 'penci_sanitize_choices_field',
	'label'             => esc_attr__( 'Enable Sticky Shadow ?', 'soledad' ),
	'section'           => 'penci_header_mobile_option_section',
	'type'              => 'soledad-fw-select',
	'choices'           => [
		'disable' => 'No',
		'enable'  => 'Yes',
	]
);

$options[] = array(
	'id'                => 'penci_header_mobile_sticky_hide_down',
	'default'           => 'disable',
	'sanitize_callback' => 'penci_sanitize_choices_field',
	'label'             => esc_attr__( 'Hide When Scrolling Down', 'soledad' ),
	'section'           => 'penci_header_mobile_option_section',
	'type'              => 'soledad-fw-select',
	'choices'           => [
		'disable' => 'No',
		'enable'  => 'Yes',
	]
);

$options[] = array(
	'id'       => "penci_header_mobile_overlap_setting",
	'default'  => 'disable',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'disable' => 'No',
		'enable'  => 'Yes',
	],
	'label'    => __( 'Enable Transparent Header( Overlap Mobile Header )', 'soledad' ),
);

return $options;
