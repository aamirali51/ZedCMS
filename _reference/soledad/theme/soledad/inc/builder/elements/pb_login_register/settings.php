<?php 

$options   = [];
$options[] = array(
	'id'              => 'penci_header_pb_login_register_penci_tblogin_text',
	'default'         => '',
	'transport'       => 'postMessage',
	'sanitize'        => 'sanitize_text_field',
	'label'           => __('Add Login Text','soledad' ),
	'description'     => __( 'Text beside the icon, leave it empty to disable', 'soledad' ),
	'type'            => 'soledad-fw-text',
	'partial_refresh' => [
		'penci_header_pb_login_register_penci_tblogin_text' => [
			'selector'        => '.pc-wrapbuilder-header-inner',
			'render_callback' => function () {
				load_template( PENCI_BUILDER_PATH . '/template/desktop-builder.php' );
			},
		],
	],
);
$options[] = array(
	'id'        => 'penci_header_pb_login_register_penci_font_login_text',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Custom Font for Login Text','soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => penci_all_fonts( 'select' )
);
$options[] = array(
	'id'        => 'penci_header_pb_login_register_penci_fontw_login_text',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Font Weight for Login Text','soledad' ),

	'type'    => 'soledad-fw-select',
	'choices' => array(
		''        => __('','soledad' ),
		'normal'  => __('Normal','soledad' ),
		'bold'    => __('Bold','soledad' ),
		'bolder'  => __('Bolder','soledad' ),
		'lighter' => __('Lighter','soledad' ),
		'100'     => __('100','soledad' ),
		'200'     => __('200','soledad' ),
		'300'     => __('300','soledad' ),
		'400'     => __('400','soledad' ),
		'500'     => __('500','soledad' ),
		'600'     => __('600','soledad' ),
		'700'     => __('700','soledad' ),
		'800'     => __('800','soledad' ),
		'900'     => __('900','soledad' ),
	)
);
$options[] = array(
	'id'        => 'penci_header_pb_login_register_spacing',
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
	'id'        => 'penci_header_pb_login_register_text_uppercase',
	'default'   => 'disable',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Uppercase Text ?','soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => [
		'enable'  => __('Yes','soledad' ),
		'disable' => __('No','soledad' ),
	]
);
$options[] = array(
	'id'        => 'penci_header_pb_login_register_color',
	'default'   => '',
	'type'      => 'soledad-fw-color',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __( 'Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_login_register_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __( 'Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_login_register_dropdown_color',
	'default'   => '',
	'type'      => 'soledad-fw-color',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __( 'Dropdown Link Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_login_register_dropdown_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __( 'Dropdown Link Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_login_register_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __('Font Size for Icon','soledad' ),
	'ids'       => array(
		'desktop' => 'penci_header_pb_login_register_size',
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
	'id'        => 'penci_header_pb_login_register_txt_size',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-size',
	'sanitize'  => 'absint',
	'label'     => __('Font Size for Text','soledad' ),
	'ids'       => array(
		'desktop' => 'penci_header_pb_login_register_txt_size',
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
	'id'        => 'penci_header_pb_login_register_css_class',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Custom CSS Class', 'soledad' ),
);

return $options;
