<?php
$options   = [];
$options[] = array(
	'id'       => 'penci_agepopup_enable',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable age verification popup ', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_agepopup_message',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Popup Message', 'soledad' ),
	'description' => __( 'Write a message warning your visitors about age restriction on your website', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'id'          => 'penci_agepopup_error_message',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Error Message', 'soledad' ),
	'description' => __( 'This message will be displayed when the visitor don\'t verify his age.', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'id'       => 'penci_agepopup_agree_text',
	'default'  => __( 'I am 18 or Older', 'soledad' ),
	'sanitize' => 'penci_sanitize_tex_field',
	'label'    => __( 'Agree Text', 'soledad' ),
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'id'       => 'penci_agepopup_cancel_text',
	'default'  => __( 'I am Under 18', 'soledad' ),
	'sanitize' => 'penci_sanitize_tex_field',
	'label'    => __( 'Cancel Text', 'soledad' ),
	'type'     => 'soledad-fw-text',
);
/* Style & Size */
$options[] = array(
	'id'       => 'penci_heading_ageverify',
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Popup Content Styles', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'       => 'penci_agepopup_animation',
	'default'  => 'move-to-top',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Open Animation', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'move-to-left'   => __( 'Move To Left', 'soledad' ),
		'move-to-right'  => __( 'Move To Right', 'soledad' ),
		'move-to-top'    => __( 'Move To Top', 'soledad' ),
		'move-to-bottom' => __( 'Move To Bottom', 'soledad' ),
		'fadein'         => __( 'Fade In', 'soledad' ),
	]
);
$options[] = array(
	'id'       => 'penci_agepopup_bgimg',
	'default'  => '',
	'type'     => 'soledad-fw-image',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Background Image', 'soledad' ),
);
$options[] = array(
	'id'          => 'penci_agepopup_bgcolor',
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'label'       => __( 'Popup Background Color', 'soledad' ),
	'type'        => 'soledad-fw-color',
	'description' => __( 'Set background image or color for age popup', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_bgrepeat',
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
	'id'       => 'penci_agepopup_bgposition',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Background Position', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'left top'      => __( 'left top', 'soledad' ),
		'left center'   => __( 'left center', 'soledad' ),
		'left bottom'   => __( 'left bottom', 'soledad' ),
		'right top'     => __( 'right top', 'soledad' ),
		'right center'  => __( 'right center', 'soledad' ),
		'right bottom'  => __( 'right bottom', 'soledad' ),
		'center top'    => __( 'center top', 'soledad' ),
		'center center' => __( 'center center', 'soledad' ),
		'center bottom' => __( 'center bottom', 'soledad' ),
	]
);
$options[] = array(
	'id'       => 'penci_agepopup_bgsize',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Background Size', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'auto'    => __( 'auto', 'soledad' ),
		'length'  => __( 'length', 'soledad' ),
		'cover'   => __( 'cover', 'soledad' ),
		'contain' => __( 'contain', 'soledad' ),
		'initial' => __( 'initial', 'soledad' ),
		'inherit' => __( 'inherit', 'soledad' ),
	]
);
$options[] = array(
	'id'       => 'penci_agepopup_bgscroll',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Background Scroll', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'scroll'  => __( 'scroll', 'soledad' ),
		'fixed'   => __( 'fixed', 'soledad' ),
		'local'   => __( 'local', 'soledad' ),
		'initial' => __( 'initial', 'soledad' ),
		'inherit' => __( 'inherit', 'soledad' ),
	]
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'penci_agepopup_width_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'       => __( 'Popup Width', 'soledad' ),
	'description' => __( 'Set width of the age popup in pixels.', 'soledad' ),
	'id'          => 'penci_agepopup_width_desktop',
	'type'        => 'soledad-fw-size',
	'sanitize'    => 'absint',
	'ids'         => array(
		'desktop' => 'penci_agepopup_width_desktop',
		'mobile'  => 'penci_agepopup_width_mobile',
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
	'id'       => 'penci_agepopup_txtcolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Popup Text Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_txt_msize',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-hidden',
	'label'    => __( 'Popup Text Size on Mobile', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_txt_size',
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Popup Text Size', 'soledad' ),
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_agepopup_txt_size',
		'mobile'  => 'penci_agepopup_txt_msize',
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
	'id'       => 'penci_agepopup_bordercolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Popup Border Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_btn1_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Agree Button Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_btn1_hcolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Agree Button Hover Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_btn1_bgcolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Agree Button Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_btn1_hbgcolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Agree Button Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_btn2_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Cancel Button Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_btn2_hcolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Cancel Button Hover Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_btn2_bgcolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Cancel Button Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_btn2_hbgcolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Cancel Button Hover Background Color', 'soledad' ),
);
$options[] = array(
	'id'       => 'penci_agepopup_spacing',
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
