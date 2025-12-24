<?php
$options   = [];
$options[] = array(
	'id'       => 'penci_cursor_enable',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Cursor Effect', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'label'       => __( 'Cursor Size', 'soledad' ),
	'id'          => 'penci_cursor_size',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_cursor_size',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '',
		),
	),
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'label'       => __( 'Cursor Circle Inner Size', 'soledad' ),
	'id'          => 'penci_cursor_circle_inner_size',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_cursor_circle_inner_size',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '',
		),
	),
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'label'       => __( 'Hover Cursor Circle Inner Size', 'soledad' ),
	'id'          => 'penci_cursor_exsmall_size',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_cursor_exsmall_size',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '',
		),
	),
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'label'       => __( 'Cursor Border Width', 'soledad' ),
	'id'          => 'penci_cursor_bdw',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_cursor_bdw',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '',
		),
	),
);
$options[] = array(
	'default'     => 'solid',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Cursor Border Style', 'soledad' ),
	'id'          => 'penci_cursor_bds',
	'type'        => 'soledad-fw-select',
	'choices'         => [
		'solid' 	=> 'Solid',
		'dotted'  	=> 'Dotted',
		'dashed'  	=> 'Dashed',
		'double'  	=> 'Double',
	],
);
$options[] = array(
	'label'    => __( 'Cursor Color', 'soledad' ),
	'id'       => 'penci_cursor_main_inv_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
);
return $options;
