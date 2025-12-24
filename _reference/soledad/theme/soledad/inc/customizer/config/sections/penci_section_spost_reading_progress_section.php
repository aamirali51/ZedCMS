<?php
$options = [];
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Reading Progress Bar', 'soledad' ),
	'id'       => 'penci_enable_reading_bar',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'label'    => __( 'Reading Progress Bar Position', 'soledad' ),
	'id'       => 'penci_reading_bar_pos',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'default'  => 'footer',
	'choices'  => [
		'header' => __('Top','soledad' ),
		'footer' => __('Bottom','soledad' ),
		'side' 	 => __('Side - Circle','soledad' ),
	]
);
$options[] = array(
	'default'  => '5',
	'sanitize' => 'absint',
	'label'    => __( 'Reading Progress Bar Height', 'soledad' ),
	'id'       => 'penci_reading_bar_h',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_reading_bar_h',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 200,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[]       = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Progress Bar Color', 'soledad' ),
	'id'       => 'penci_single_progress_color',
);
return $options;