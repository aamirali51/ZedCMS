<?php 

$options   = [];
$options[] = array(
	'id'              => 'penci_header_pb_button_text_setting',
	'default'         => 'Button Text',
	'transport'       => 'postMessage',
	'sanitize'        => 'penci_sanitize_choices_field',
	'type'            => 'soledad-fw-text',
	'label'           => __( 'Button Text', 'soledad' ),
	'partial_refresh' => [
		'penci_header_pb_button_text_setting' => [
			'selector'        => '.pc-wrapbuilder-header-inner',
			'render_callback' => function () {
				load_template( PENCI_BUILDER_PATH . '/template/desktop-builder.php' );
			},
		],
	],
);
$options[] = array(
	'id'        => 'penci_header_pb_button_link_setting',
	'default'   => 'https://your-link.com',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-text',
	'label'     => __( 'Button Link', 'soledad' ),
	'priority'  => 10,
);
$options[] = array(
	'id'        => 'penci_header_pb_button_link_target',
	'default'   => '_blank',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Link Target', 'soledad' ),
	'choices'   => [
		'_blank'  => __('Blank','soledad' ),
		'_self'   => __('Self','soledad' ),
		'_parent' => __('Parent','soledad' ),
		'_top'    => __('Top','soledad' ),
	]
);
$options[] = array(
	'id'        => 'penci_header_pb_button_link_rel',
	'default'   => 'noreferrer',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Select "rel" Attribute Type for Button Link','soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => array(
		'none'                         => __('None','soledad' ),
		'nofollow'                     => __('nofollow','soledad' ),
		'noreferrer'                   => __('noreferrer','soledad' ),
		'noopener'                     => __('noopener','soledad' ),
		'noreferrer_noopener'          => __('noreferrer noopener','soledad' ),
		'nofollow_noreferrer'          => __('nofollow noreferrer','soledad' ),
		'nofollow_noopener'            => __('nofollow noopener','soledad' ),
		'nofollow_noreferrer_noopener' => __('nofollow noreferrer noopener','soledad' ),
	)
);
$options[] = array(
	'id'        => 'penci_header_pb_button_style',
	'default'   => 'style-4',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Button Pre-define Style', 'soledad' ),
	'choices'   => [
		'customize' => __('Default','soledad' ),
		'style-4'   => __('Filled','soledad' ),
		'style-1'   => __('Bordered','soledad' ),
		'style-2'   => __('Link','soledad' ),
		'style-3'   => __('Creative','soledad' ),
	]
);
$options[] = array(
	'id'        => 'penci_header_pb_button_shape',
	'default'   => 'ratangle',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-select',
	'label'     => __( 'Button Shape', 'soledad' ),
	'choices'   => [
		'ratangle' => __('Retangle','soledad' ),
		'circle'   => __('Circle','soledad' ),
		'round'    => __('Round','soledad' ),
	]
);
$options[] = array(
	'id'        => 'penci_header_pb_button_spacing_setting',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-box-model',
	'label'   => __( 'Button Spacing', 'soledad' ),
	'choices' => array(
		'margin'        => array(
			'margin-top'    => '',
			'margin-right'  => '',
			'margin-bottom' => '',
			'margin-left'   => '',
		),
		'padding'       => array(
			'padding-top'    => '',
			'padding-right'  => '',
			'padding-bottom' => '',
			'padding-left'   => '',
		),
		'border'        => array(
			'border-top'    => '',
			'border-right'  => '',
			'border-bottom' => '',
			'border-left'   => '',
		),
		'border-radius' => array(
			'border-radius-top'    => '',
			'border-radius-right'  => '',
			'border-radius-bottom' => '',
			'border-radius-left'   => '',
		),
	),
);
/* start font */
$options[] = array(
	'id'        => 'penci_header_pb_button_font',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Font For Button Text','soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => penci_all_fonts( 'select' )
);
$options[] = array(
	'id'        => 'penci_header_pb_button_font_w',
	'default'   => 'bold',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Font Weight For Button Text','soledad' ),
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
	'id'        => 'penci_header_pb_button_font_s',
	'default'   => 'normal',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Font Style for Button Text','soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => array(
		'normal' => 'Normal',
		'italic' => 'Italic'
	)
);
/* end font*/
$options[] = array(
	'id'        => 'penci_header_pb_button_border_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Borders Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_button_border_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Borders Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_button_bg_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Background Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_button_bg_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',

	'label' => __( 'Background Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_button_txt_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Text Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_button_txt_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-color',
	'label'     => __( 'Text Hover Color', 'soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_button_txt_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __('Button Font Size','soledad' ),
	'ids'       => array(
		'desktop' => 'penci_header_pb_button_txt_size',
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
	'id'       => 'penci_header_pb_button_txt_css_class',
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'type'     => 'soledad-fw-text',
	'label'    => esc_html__( 'Custom CSS Class', 'soledad' ),
);
return $options;
