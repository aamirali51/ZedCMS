<?php
$options   = [];
$options[] = array(
	'id'        => 'penci_epopup_bgimg',
	'default'   => '',
	'transport' => 'refresh',
	'type'      => 'soledad-fw-image',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __( 'Popup Background Image', 'soledad' ),
);
$options[] = array(
	'id'          => 'penci_epopup_bgcolor',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Popup Background Color', 'soledad' ),
	'description' => __( 'Set background image or color for promo popup', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_epopup_bgrepeat',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Background Repeat', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'repeat'    => __('repeat','soledad' ),
		'repeat-x'  => __('repeat-x','soledad' ),
		'repeat-y'  => __('repeat-y','soledad' ),
		'no-repeat' => __('no-repeat','soledad' ),
		'initial'   => __('initial','soledad' ),
		'inherit'   => __('inherit','soledad' ),
	]
);
$options[] = array(
	'id'       => 'penci_epopup_bgposition',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Background Position', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'left top'      => __('left top','soledad' ),
		'left center'   => __('left center','soledad' ),
		'left bottom'   => __('left bottom','soledad' ),
		'right top'     => __('right top','soledad' ),
		'right center'  => __('right center','soledad' ),
		'right bottom'  => __('right bottom','soledad' ),
		'center top'    => __('center top','soledad' ),
		'center center' => __('center center','soledad' ),
		'center bottom' => __('center bottom','soledad' ),
	]
);
$options[] = array(
	'id'       => 'penci_epopup_bgsize',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Background Size', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'auto'    => 'auto',
		'length'  => 'length',
		'cover'   => 'cover',
		'contain' => 'contain',
		'initial' => 'initial',
		'inherit' => 'inherit'
	]
);
$options[] = array(
	'id'       => 'penci_epopup_bgscroll',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Background Scroll', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'scroll'  => __('scroll','soledad' ),
		'fixed'   => __('fixed','soledad' ),
		'local'   => __('local','soledad' ),
		'initial' => __('initial','soledad' ),
		'inherit' => __('inherit','soledad' ),
	]
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'penci_epopup_width_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'       => __( 'Popup Width', 'soledad' ),
	'description' => __( 'Set width of the promo popup in pixels.', 'soledad' ),
	'id'          => 'penci_epopup_width_desktop',
	'type'        => 'soledad-fw-size',
	'sanitize'    => 'absint',
	'ids'         => array(
		'desktop' => 'penci_epopup_width_desktop',
		'mobile'  => 'penci_epopup_width_mobile',
	),
	'choices'     => array(
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
	'id'       => 'penci_epopup_txtcolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Popup Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_epopup_bordercolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Popup Border Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_epopup_closecolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Popup Close Button Color', 'soledad' ),
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'penci_epopup_txt_msize',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Popup Text Size', 'soledad' ),
	'id'       => 'penci_epopup_txt_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_epopup_txt_size',
		'mobile'  => 'penci_epopup_txt_msize',
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
	'id'       => 'penci_epopup_spacing',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-box-model',
	'label'    => __( 'Popup Spacing', 'soledad' ),
	'choices'  => array(
		'margin'       => array(
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

return $options;