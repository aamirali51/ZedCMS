<?php 

$options   = [];
$options[] = array(
	'id'        => 'penci_header_pb_logo_sidebar_logo_display',
	'default'   => 'image',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-radio',
	
	'label'     => esc_html__( 'Display Logo as', 'soledad' ),
	'priority'  => 10,
	'choices'   => [
		'image' => esc_html__( 'Logo Image', 'soledad' ),
		'text'  => esc_html__( 'Text', 'soledad' ),
	],
	'partial_refresh' => [
		'penci_header_pb_logo_sidebar_logo_display' => [
			'selector'        => '.pc-wrapbuilder-header-inner',
			'render_callback' => function () {
				load_template( PENCI_BUILDER_PATH . '/template/desktop-builder.php' );
			},
		],
	],
);
$options[] = array(
	'id'          => 'penci_header_pb_logo_sidebar_image_setting_url',
	'default'     => '',
	'transport'   => 'postMessage',
	'sanitize'    => 'penci_sanitize_choices_field',
	'type'        => 'soledad-fw-image',
	
	'label'       => esc_html__( 'Logo Image', 'soledad' ),
	'description' => esc_html__( 'Upload your image logo here', 'soledad' ),
	'partial_refresh' => [
		'penci_header_pb_logo_sidebar_image_setting_url' => [
			'selector'        => '.pc-wrapbuilder-header-inner',
			'render_callback' => function () {
				load_template( PENCI_BUILDER_PATH . '/template/desktop-builder.php' );
			},
		],
	],
);
$options[] = array(
	'id'          => 'penci_header_pb_logo_sidebar_size_logo_mw',
	'default'     => '',
	'transport'   => 'postMessage',
	'sanitize'    => 'absint',
	'type'      => 'soledad-fw-size',
	'label' => __( 'Maxium Width for Logo Image', 'soledad' ),
	'ids'    => array(
		'desktop' => 'penci_header_pb_logo_sidebar_size_logo_mw',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 500,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'          => 'penci_header_pb_logo_sidebar_size_logo_mh',
	'default'     => '60',
	'transport'   => 'postMessage',
	'sanitize'    => 'absint',
	'type'      => 'soledad-fw-size',
	'label' => __( 'Maxium Height for Logo Image', 'soledad' ),
	'ids'    => array(
		'desktop' => 'penci_header_pb_logo_sidebar_size_logo_mh',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 500,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_pb_logo_sidebar_image_setting_href',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-text',
	
	'label'     => __( 'Custom Logo Link', 'soledad' ),
	'priority'  => 10,
);
$options[] = array(
	'id'        => 'penci_header_pb_logo_sidebar_site_title',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-text',
	
	'label'     => esc_html__( 'Text Logo', 'soledad' ),
	'partial_refresh' => [
		'penci_header_pb_logo_sidebar_site_title' => [
			'selector'        => '.pc-wrapbuilder-header-inner',
			'render_callback' => function () {
				load_template( PENCI_BUILDER_PATH . '/template/desktop-builder.php' );
			},
		],
	],
);
$options[] = array(
	'id'        => 'penci_header_pb_logo_sidebar_site_description',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-textarea',
	
	'label'     => esc_html__( 'Slogan Text', 'soledad' ),
	'partial_refresh' => [
		'penci_header_pb_logo_sidebar_site_description' => [
			'selector'        => '.pc-wrapbuilder-header-inner',
			'render_callback' => function () {
				load_template( PENCI_BUILDER_PATH . '/template/desktop-builder.php' );
			},
		],
	],
);
$options[] = array(
	'id'          => 'penci_header_pb_logo_sidebar_font_size_logo',
	'default'     => '',
	'transport'   => 'postMessage',
	'sanitize'    => 'absint',
	'type'      => 'soledad-fw-size',
	'label' => __( 'Font Size for Text Logo', 'soledad' ),
	'ids'    => array(
		'desktop' => 'penci_header_pb_logo_sidebar_font_size_logo',
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
/* Text Logo */
$options[] = array(
	'id'        => 'penci_header_pb_logo_sidebar_color_logo',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Color for Text Logo','soledad' ),
);
$options[] = array(
	'id'          => 'penci_header_pb_logo_sidebar_penci_font_for_title',
	'default'     => '',
	'transport'   => 'postMessage',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __('Font for Text Logo','soledad' ),
	
	'description' => __('Default font is "PT Serif"','soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => penci_all_fonts( 'select' )
);
$options[] = array(
	'id'        => 'penci_header_pb_logo_sidebar_penci_font_weight_title',
	'default'   => 'bold',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Font Weight for Text Logo','soledad' ),
	
	'type'      => 'soledad-fw-select',
	'choices'   => array(
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
	'id'        => 'penci_header_pb_logo_sidebar_penci_font_style_title',
	'default'   => 'normal',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Font Style for Text Logo','soledad' ),
	
	'type'      => 'soledad-fw-select',
	'choices'   => array(
		'normal' => __('Normal','soledad' ),
		'italic' => __('Italic','soledad' ),
	)
);
/* Slogan*/
$options[] = array(
	'id'          => 'penci_header_pb_logo_sidebar_font_size_slogan',
	'default'     => '',
	'transport'   => 'postMessage',
	'type'      => 'soledad-fw-size',
	'sanitize'    => 'absint',
	'label' => __( 'Font Size for Slogan', 'soledad' ),
	'ids'    => array(
		'desktop' => 'penci_header_pb_logo_sidebar_font_size_slogan',
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
	'id'        => 'penci_header_pb_logo_sidebar_color_slogan',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Color Header Slogan','soledad' ),
);
$options[] = array(
	'id'          => 'penci_header_pb_logo_sidebar_penci_font_for_slogan',
	'default'     => '',
	'transport'   => 'postMessage',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __('Font for Slogan Text','soledad' ),
	
	'description' => __('Default font is "PT Serif"','soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => penci_all_fonts( 'select' )
);
$options[] = array(
	'id'        => 'penci_header_pb_logo_sidebar_penci_font_weight_slogan',
	'default'   => 'bold',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Font Weight for Slogan Text','soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => array(
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
	'id'        => 'penci_header_pb_logo_sidebar_penci_font_style_slogan',
	'default'   => 'normal',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Font Style for Slogan Text','soledad' ),
	
	'type'      => 'soledad-fw-select',
	'choices'   => array(
		'normal' => __('Normal','soledad' ),
		'italic' => __('Italic','soledad' ),
	)
);
$options[] = array(
	'id'        => 'penci_header_pb_logo_sidebar_spacing',
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
	'id'        => 'penci_header_pb_logo_sidebarclass',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Custom CSS Class', 'soledad' ),
);

return $options;
