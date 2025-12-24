<?php
$options   = [];
$options[] = array(
	'default'     => 'keep your memories alive',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Header Slogan Text', 'soledad' ),
	'id'          => 'penci_header_slogan_text',
	'description' => __( 'If you want to hide the slogan text, let make it blank', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
);

$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove the Lines on Left & Right of Header Slogan', 'soledad' ),
	'id'       => 'penci_header_remove_line_slogan',
	'type'     => 'soledad-fw-toggle',
);

$options[] = array(
	'default'  => '14',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Slogan', 'soledad' ),
	'id'       => 'penci_font_size_slogan',
	'ids'      => array(
		'desktop' => 'penci_font_size_slogan',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);

$options[] = array(
	'default'     => '"PT Serif", "regular:italic:700:700italic", serif',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Font For Header Slogan', 'soledad' ),
	'id'          => 'penci_font_for_slogan',
	'description' => __( 'Default font is "PT Serif"', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => penci_all_fonts()
);

$options[] = array(
	'default'  => 'bold',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Font Weight For Slogan', 'soledad' ),
	'id'       => 'penci_font_weight_slogan',
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
	'default'  => 'italic',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Font Style for Slogan', 'soledad' ),
	'id'       => 'penci_font_style_slogan',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'italic' => __('Italic','soledad' ),
		'normal' => __('Normal','soledad' ),
	)
);

return $options;
