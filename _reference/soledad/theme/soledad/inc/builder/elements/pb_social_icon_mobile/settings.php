<?php 

$social_icon_section         = 'penci_header_pb_social_icon_mobile_section';
$general_config              = 'penci_builder_mods';
$query                       = [];
$query['autofocus[section]'] = 'pencidesign_new_section_social';
$options                     = [];
$options[]                   = array(
	'id'        => $social_icon_section . '_icon_size',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'label'     => __('Social Icon Size','soledad' ),
	'type'      => 'soledad-fw-size',
	
	'ids'  => array(
		'desktop' => $social_icon_section . '_icon_size',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 50,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[]                   = array(
	'id'        => $social_icon_section . '_icon_w',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __('Social Icon Width','soledad' ),
	
	'ids'  => array(
		'desktop' => $social_icon_section . '_icon_w',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 50,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[]                   = array(
	'id'        => $social_icon_section . '_item_spacing',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-size',
	'sanitize'  => 'absint',
	'label'     => __('Spacing Between Item','soledad' ),
	
	'ids'  => array(
		'desktop' => $social_icon_section . '_item_spacing',
	),
	'choices'   => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 1000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[]                   = array(
	'id'        => $social_icon_section . '_icon_style',
	'default'   => 'simple',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Social Icon Style','soledad' ),
	
	'type'      => 'soledad-fw-select',
	'choices'   => array(
		'simple' => __('Simple','soledad' ),
		'square' => __('Square','soledad' ),
		'circle' => __('Circle','soledad' ),
	)
);
$options[]                   = array(
	'id'        => $social_icon_section . '_icon_color',
	'default'   => 'normal',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Social Icon Color','soledad' ),
	
	'type'      => 'soledad-fw-select',
	'choices'   => array(
		'textaccent'  => __('Custom Color','soledad' ),
		'textcolored' => __('Brand Text Color','soledad' ),
		'colored'     => __('Brand Background Color','soledad' ),
	)
);
$options[]                   = array(
	'id'        => $social_icon_section . '_spacing',
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
$options[]                   = array(
	'id'        => $social_icon_section . '_penci_rel_type_social',
	'default'   => 'noreferrer',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Select "rel" Attribute Type for Social Media & Social Share Icons','soledad' ),
	
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
$options[]                   = array(
	'id'        => $social_icon_section . '_bg_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Custom Background Color','soledad' ),
	
);
$options[]                   = array(
	'id'        => $social_icon_section . '_bg_hv_color',
	'type'      => 'soledad-fw-color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Custom Background Hover Color','soledad' ),
	
);
$options[]                   = array(
	'id'        => $social_icon_section . '_border_color',
	'type'      => 'soledad-fw-color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Custom Borders Color','soledad' ),
	
);
$options[]                   = array(
	'id'        => $social_icon_section . '_border_hv_color',
	'type'      => 'soledad-fw-color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Custom Borders Hover Color','soledad' ),
	
);
$options[]                   = array(
	'id'        => $social_icon_section . '_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Custom Color','soledad' ),
	
);
$options[]                   = array(
	'id'        => $social_icon_section . '_hv_color',
	'type'      => 'soledad-fw-color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Custom Hover Color','soledad' ),
	
);

return $options;