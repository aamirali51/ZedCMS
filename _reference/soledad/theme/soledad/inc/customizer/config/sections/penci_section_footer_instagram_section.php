<?php
$options   = [];
$options[] = array(
	'label'    => __( 'Display Footer Instagram Widget Title Overlay Images', 'soledad' ),
	'id'       => 'penci_footer_insta_title_overlay',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Hide Instagram Icon on Footer Instagram', 'soledad' ),
	'id'       => 'penci_footer_insta_hide_icon',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'penci_footer_insta_title_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
	'default'     => '14',
);
$options[] = array(
	'label'    => __( 'Font Size for Widget Title', 'soledad' ),
	'id'       => 'penci_footer_insta_title',
	'type'     => 'soledad-fw-size',
	'default'  => '16',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_footer_insta_title',
		'mobile'  => 'penci_footer_insta_title_mobile',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '16',
		),
		'mobile'  => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);

return $options;
