<?php 

$options   = [];
$options[] = array(
	'id'              => 'penci_header_pb_data_time_format',
	'default'         => 'l, F j, Y',
	'transport'       => 'postMessage',
	'sanitize'        => 'penci_sanitize_choices_field',
	'type'            => 'soledad-fw-text',
	'label'           => esc_html__( 'Date/Time Format', 'soledad' ),
	'description'     => __( 'Enter the date time format here. For more information, please read the document <a target="_blank" href="https://wordpress.org/support/article/formatting-date-and-time/">here</a>', 'soledad' ),
	'priority'        => 10,
	'partial_refresh' => [
		'penci_header_pb_data_time_format' => [
			'selector'        => '.pc-wrapbuilder-header-inner',
			'render_callback' => function () {
				load_template( PENCI_BUILDER_PATH . '/template/desktop-builder.php' );
			},
		],
	],
);
$options[] = array(
	'id'        => 'penci_header_pb_data_time_format_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __('Font Size','soledad'),
	'ids'  => array(
		'desktop' => 'penci_header_pb_data_time_format_size',
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
	'id'        => 'penci_header_pb_data_time_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'label'     => esc_html__( 'Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_data_time_spacing',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-box-model',
	'label'     => __( 'Item Spacing', 'soledad' ),
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
	'id'        => 'penci_header_pb_data_time_css_class',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Custom CSS Class', 'soledad' ),
);

return $options;
