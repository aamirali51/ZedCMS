<?php
$options   = [];
$options[] = array(
	'label'    => __( 'Enable Featured Video Background', 'soledad' ),
	'id'       => 'penci_enable_featured_video_bg',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);

$options[] = array(
	'label'    => __( 'Background Image Display Replace Video On Tablet & Mobile', 'soledad' ),
	'id'       => 'penci_featured_video_img_mobile',
	'sanitize' => 'esc_url_raw',
	'type'     => 'soledad-fw-image'
);
$options[] = array(
	'label'    => __( 'Video Youtube URL', 'soledad' ),
	'id'       => 'penci_featured_video_url',
	'type'     => 'soledad-fw-text',
	'sanitize' => 'esc_url_raw'
);
$options[] = array(
	'label'    => __( 'Featured Video Background Height', 'soledad' ),
	'id'       => 'penci_featured_video_height',
	'default'  => '600',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_featured_video_height',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '600',
		),
	),
);
$options[] = array(
	'label'       => __( 'Start Video At', 'soledad' ),
	'description' => __( 'Unit is second', 'soledad' ),
	'id'          => 'penci_featured_video_start',
	'default'     => '0',
	'sanitize'    => 'absint',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_featured_video_start',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 's',
			'default' => '0',
		),
	),
);
$options[] = array(
	'label'    => __( 'Add Custom Image on Video Background', 'soledad' ),
	'id'       => 'penci_featured_video_image',
	'sanitize' => 'esc_url_raw',
	'type'     => 'soledad-fw-text'
);
$options[] = array(
	'label'    => __( 'Heading Text On Video Background', 'soledad' ),
	'id'       => 'penci_featured_video_text_heading',
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'type'     => 'soledad-fw-text'
);
$options[] = array(
	'label'    => __( 'Sub Heading Text On Video Background', 'soledad' ),
	'id'       => 'penci_featured_video_sub_heading',
	'type'     => 'soledad-fw-textarea',
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field'
);

return $options;
