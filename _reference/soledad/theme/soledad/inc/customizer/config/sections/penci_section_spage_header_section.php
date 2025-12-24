<?php
$options   = [];
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Enable Page Header for Pages', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_pheader_show',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => esc_html__( 'Background Image for Page Header', 'soledad' ),
	'id'       => 'penci_pheader_bgimg',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Hide Line Below Title', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_pheader_hideline',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Hide Breadcrumbs', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_pheader_hidebead',
);
$options[] = array(
	'default'  => 'center',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Text Align', 'soledad' ),
	'id'       => 'penci_pheader_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'left'   => esc_html__( 'Left', 'soledad' ),
		'center' => esc_html__( 'Center', 'soledad' ),
		'right'  => esc_html__( 'Right', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'By default, container width for page header will follow the container width for header', 'soledad' ),
	'id'       => 'penci_pheader_width',
	'type'     => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_pheader_width',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Padding top', 'soledad' ),
	'id'       => 'penci_pheader_ptop',
	'type'     => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_pheader_ptop',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Padding Bottom', 'soledad' ),
	'id'       => 'penci_pheader_pbottom',
	'type'     => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_pheader_pbottom',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);

$options[] = array(
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Turn Off Uppercase for Title', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_pheader_turn_offup',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Padding Bottom for Page Title', 'soledad' ),
	'id'       => 'penci_pheader_title_pbottom',
	'type'     => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_pheader_title_pbottom',
		'mobile' => 'penci_pheader_title_mbottom',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Margin Bottom for Page Title', 'soledad' ),
	'id'       => 'penci_pheader_title_mbottom',
	'type'     => 'soledad-fw-hidden',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Page Title', 'soledad' ),
	'id'       => 'penci_pheader_title_fsize',
	'type'     => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_pheader_title_fsize',
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
$options[] = array(
	'default'  => '600',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Font Weight For Title', 'soledad' ),
	'id'       => 'penci_pheader_fwtitle',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
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
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Breadcrumb on Page Header', 'soledad' ),
	'id'       => 'penci_pheader_bread_fsize',
	'type'     => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_pheader_bread_fsize',
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
