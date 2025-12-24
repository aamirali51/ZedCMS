<?php
$options   = [];
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Move Header Sign-Up Form To Below Featured Slider', 'soledad' ),
	'id'          => 'penci_move_signup_below',
	'description' => __( 'If you using Sign-Up form on the header, this option will help you move Sign-Up form to below the featured slider', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Make Header Sign-Up Form Below Featured Slider Full Width', 'soledad' ),
	'id'       => 'penci_move_signup_full_width',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Display Header Sign-Up Form only on Homepage', 'soledad' ),
	'id'       => 'penci_signup_display_homepage',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '20',
	'sanitize' => 'absint',
	'label'    => __( 'Header Sign-Up Form Padding Top & Bottom', 'soledad' ),
	'id'       => 'penci_header_signup_padding',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_header_signup_padding',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '20',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Description on Sign-Up Form', 'soledad' ),
	'id'       => 'penci_header_signup_fdesc',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_header_signup_fdesc',
	),
	'choices'  => array(
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
	'default'  => '13',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Text on "Name" & "Email" fields', 'soledad' ),
	'id'       => 'penci_header_signup_finput',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_header_signup_finput',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '13',
		),
	),
);
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for "Subscrible" button', 'soledad' ),
	'id'       => 'penci_header_signup_fsubmit',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_header_signup_fsubmit',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Header Sign-Up Form Area Background Color', 'soledad' ),
	'id'       => 'penci_header_signup_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Text Color', 'soledad' ),
	'id'       => 'penci_header_signup_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Input Border Color', 'soledad' ),
	'id'       => 'penci_header_signup_input_border',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Input Text Color', 'soledad' ),
	'id'       => 'penci_header_signup_input_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Submit Button Background Color', 'soledad' ),
	'id'       => 'penci_header_signup_submit_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Submit Button Text Color', 'soledad' ),
	'id'       => 'penci_header_signup_submit_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Submit Button Hover Background Color', 'soledad' ),
	'id'       => 'penci_header_signup_submit_bg_hover',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Submit Button Hover Text Color', 'soledad' ),
	'id'       => 'penci_header_signup_submit_color_hover',
);

return $options;
