<?php
$options   = [];
$options[] = array(
	'label'    => __( 'Custom Padding to Top', 'soledad' ),
	'id'       => 'penci_footer_signup_ptop',
	'default'  => '50',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_footer_signup_ptop',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '50',
		),
	),
);
$options[] = array(
	'label'    => __( 'Custom Padding to Bottom', 'soledad' ),
	'id'       => 'penci_footer_signup_pbottom',
	'default'  => '40',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_footer_signup_pbottom',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '40',
		),
	),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'penci_footer_signup_fstitle_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
	'default'     => '24',
);
$options[] = array(
	'label'    => __( 'Font Size for Heading', 'soledad' ),
	'id'       => 'penci_footer_signup_fstitle',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'default'  => '32',
	'ids'      => array(
		'desktop' => 'penci_footer_signup_fstitle',
		'mobile'  => 'penci_footer_signup_fstitle_mobile',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '32',
		),
		'mobile'  => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '24',
		),
	),
);
$options[] = array(
	'label'    => __( 'Font Size for Description', 'soledad' ),
	'id'       => 'penci_footer_signup_fsdesc',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'default'  => '18',
	'ids'      => [ 'desktop' => 'penci_footer_signup_fsdesc', 'mobile' => 'penci_footer_signup_fsdesc_mobile' ],
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '18',
		),
		'mobile'  => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '16',
		),
	),
);
$options[] = array(
	'label'    => __( 'Font Size for Description', 'soledad' ),
	'id'       => 'penci_footer_signup_fsdesc_mobile',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-hidden',
	'default'  => '16',
);
$options[] = array(
	'label'    => __( 'Font Size for Inputs', 'soledad' ),
	'id'       => 'penci_footer_signup_fsinputs',
	'sanitize' => 'absint',
	'default'  => '14',
	'ids'      => [ 'desktop' => 'penci_footer_signup_fsinputs' ],
	'type'     => 'soledad-fw-size',
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
	'label'    => __( 'Font Size for Submit Button', 'soledad' ),
	'id'       => 'penci_footer_signup_fsisubmit',
	'sanitize' => 'absint',
	'ids'      => [ 'desktop' => 'penci_footer_signup_fsisubmit' ],
	'type'     => 'soledad-fw-size',
	'default'  => '14',
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
	'label'    => __( 'Display Name Field on Footer SignUp Form', 'soledad' ),
	'id'       => 'penci_footer_signup_showemail',
	'type'     => 'soledad-fw-toggle',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Footer SignUp Form Area Background Color', 'soledad' ),
	'id'       => 'penci_footer_signup_area_bg',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'label'    => __( 'SignUp Form Heading Text Color', 'soledad' ),
	'id'       => 'penci_footer_signup_heading_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'SignUp Form Description Text Color', 'soledad' ),
	'id'       => 'penci_footer_signup_des_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'SignUp Form Email Input Border Color', 'soledad' ),
	'id'       => 'penci_footer_signup_email_border',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'SignUp Form Email Input Hover Border Color', 'soledad' ),
	'id'       => 'footer_signup_email_border_hover',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'SignUp Form Email Text Color', 'soledad' ),
	'id'       => 'penci_footer_signup_email_text_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'SignUp Form Submit Background Color', 'soledad' ),
	'id'       => 'penci_footer_signup_button_bg',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'SignUp Form Submit Hover Background Color', 'soledad' ),
	'id'       => 'penci_footer_signup_button_bg_hover',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'SignUp Form Submit Text Color', 'soledad' ),
	'id'       => 'penci_footer_signup_button_text',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'SignUp Form Submit Hover Text Color', 'soledad' ),
	'id'       => 'penci_footer_signup_button_text_hover',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);

return $options;
