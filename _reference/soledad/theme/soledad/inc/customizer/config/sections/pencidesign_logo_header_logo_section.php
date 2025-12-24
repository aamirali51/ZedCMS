<?php
$options   = [];
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Upload Logo', 'soledad' ),
	'id'       => 'penci_logo',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Upload Logo for Mobile', 'soledad' ),
	'id'       => 'penci_mobile_logo',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Set A Max Width for Logo', 'soledad' ),
	'id'       => 'penci_logo_max_width',
	'ids'      => array(
		'desktop' => 'penci_logo_max_width',
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
	'label'    => '',
	'id'       => 'penci_logo_height_mobile',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Set A Height for Logo Image', 'soledad' ),
	'id'       => 'penci_logo_height',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_logo_height',
		'mobile'  => 'penci_logo_height_mobile',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'sanitize'    => 'esc_url_raw',
	'label'       => __( 'Upload Logo for Transparent Header style 6, 9, 10 & 11', 'soledad' ),
	'id'          => 'penci_upload_transparent_logo',
	'type'        => 'soledad-fw-image',
	'description'=>__('Important Note: This option apply when you use transparent header only','soledad'),
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_text_field',
	'label'       => __( 'Custom Link for Logo Image', 'soledad' ),
	'id'          => 'penci_custom_url_logo',
	'description'=>__('By default, logo image will link to homepage url. If you want to link the logo for another URL - fill here. Include http:// or https:// on the link','soledad'),
	'type'        => 'soledad-fw-text',
);
$options[] = array(
	'default'         => '40',
	'sanitize'        => 'absint',
	'type'            => 'soledad-fw-size',
	'label'           => __( 'Logo Padding Top & Bottom', 'soledad' ),
	'sub_description' => __( 'This option does not apply for header layout 6, 9, 10, 11', 'soledad' ),
	'id'              => 'penci_header_padding',
	'ids'             => array(
		'desktop' => 'penci_header_padding',
	),
	'choices'         => array(
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
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Overflow Logo for Header Layout 10 & 11', 'soledad' ),
	'id'       => 'penci_overflow_logo',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'type'        => 'soledad-fw-size',
	'label'       => __( 'Max Width for Sticky Logo on Header 10 & 11', 'soledad' ),
	'description' => __( 'This option just apply when you use overflow logo for header layout 10 & 11', 'soledad' ),
	'id'          => 'penci_logo_max_width_overflow',
	'ids'         => array(
		'desktop' => 'penci_logo_max_width_overflow',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);

return $options;
