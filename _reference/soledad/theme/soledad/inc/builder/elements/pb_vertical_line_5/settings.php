<?php 

$general_config = 'penci_builder_mods';
$options        = [];
$options[]      = array(
	'id'        => 'penci_header_pb_vertical_line5_width',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Vertical Line Width', 'soledad' ),
);
$options[]      = array(
	'id'        => 'penci_header_pb_vertical_line5_height',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Vertical Line Height', 'soledad' ),
);
$options[]      = array(
	'id'        => 'penci_header_pb_vertical_line5_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Vertical Line Color', 'soledad' ),
);
$options[]      = array(
	'id'        => 'penci_header_pb_vertical_line5_spacing',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-box-model',
	'label'     => __( 'Element Spacing', 'soledad' ),
	'choices'   => array(
		'margin' => array(
			'margin-top'    => '',
			'margin-right'  => '',
			'margin-bottom' => '',
			'margin-left'   => '',
		),
	),
);
$options[]      = array(
	'id'        => 'penci_header_pb_vertical_line5_class',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Custom CSS Class', 'soledad' ),
);

return $options;