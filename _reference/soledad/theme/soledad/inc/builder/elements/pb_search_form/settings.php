<?php 

$options   = [];
$options[] = array(
	'id'        => 'penci_header_pb_search_form_style',
	'default'   => 'default',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Search Style','soledad' ),

	'type'    => 'soledad-fw-select',
	'choices' => array(
		'default'     => __('Default','soledad' ),
		'text-button' => __('Text Button','soledad' ),
		'icon-button' => __('Icon Button','soledad' ),
	)
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_bg_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Background Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_border_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Borders Color','soledad' ),
	'type'      => 'soledad-fw-color',
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_txt_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Text Color','soledad' ),
	'type'      => 'soledad-fw-color',
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_btntxt_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Icon/Button Text Color','soledad' ),
	'type'      => 'soledad-fw-color',
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_btnhtxt_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Button Hover Text Color','soledad' ),
	'type'      => 'soledad-fw-color',
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_btn_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Button Background Color','soledad' ),
	'type'      => 'soledad-fw-color',
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_btn_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Button Background Hover Color','soledad' ),
	'type'      => 'soledad-fw-color',
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_width',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __('Search Form Max Width','soledad' ),
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_pb_search_form_width',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 1200,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_height',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __('Custom Height','soledad' ),
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_pb_search_form_height',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 1200,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_input_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __('Font Size for Input Text','soledad' ),
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_pb_search_form_input_size',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 1200,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_btn_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __('Font Size for Button/Icon','soledad' ),
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_pb_search_form_btn_size',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 1200,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_input_pdl',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_pb_search_form_input_pdl',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 1200,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
	'label'     => __( 'Input Text Padding Left', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_input_pdr',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_pb_search_form_input_pdr',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 1200,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
	'label'     => __( 'Input Text Padding Right', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_btn_pdl',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_pb_search_form_btn_pdl',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 1200,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
	'label'     => __( 'Button Padding Left', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_btn_pdr',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-size',
	'ids'       => array(
		'desktop' => 'penci_header_pb_search_form_btn_pdr',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 1200,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
	'label'     => __( 'Button Padding Right', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_search_form_menu_spacing',
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
	'id'        => 'penci_header_pb_search_form_menu_class',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Custom CSS Class', 'soledad' ),

);

return $options;
