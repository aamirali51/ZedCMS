<?php 

$options   = [];
$options[] = array(
	'id'              => 'penci_header_builder_pb_html_2_name',
	'default'         => '',
	'transport'       => 'postMessage',
	'sanitize'        => 'penci_sanitize_choices_field',
	'type'            => 'soledad-fw-textarea',
	'label'           => esc_html__( 'HTML Code', 'soledad' ),
	'partial_refresh' => [
		'penci_header_builder_pb_html_2_name' => [
			'selector'        => '.pc-wrapbuilder-header-inner',
			'render_callback' => function () {
				load_template( PENCI_BUILDER_PATH . '/template/desktop-builder.php' );
			},
		],
	],
);
$options[] = array(
	'id'        => 'penci_header_builder_pb_html_2_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => esc_html__( 'Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_builder_pb_html_2_link_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => esc_html__( 'Links Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_builder_pb_html_2_fsize',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __('Font Size','soledad' ),
	'ids'  => array(
		'desktop' => 'penci_header_builder_pb_html_2_fsize',
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
	'id'        => 'penci_header_builder_pb_html_2_spacing',
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
	'id'       => 'penci_header_builder_pb_html_2_css_class',
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'type'     => 'soledad-fw-text',
	'label'    => esc_html__( 'Custom CSS Class', 'soledad' ),
);

return $options;
