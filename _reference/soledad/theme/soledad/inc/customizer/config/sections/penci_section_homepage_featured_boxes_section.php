<?php
$options   = array();
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Home Featured Boxes', 'soledad' ),
	'id'       => 'penci_home_hide_boxes',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Home Featured Boxes Style 2', 'soledad' ),
	'id'       => 'penci_home_box_style_2',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Home Featured Boxes Style 3', 'soledad' ),
	'id'       => 'penci_home_box_style_3',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => 'normal',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom Font Weight for Text on Featured Boxes', 'soledad' ),
	'id'       => 'penci_home_box_weight',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'bold'   => __('Bold','soledad' ),
		'normal' => __('Normal','soledad' ),
	),
);
$options[] = array(
	'default'  => '3',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Home Featured Boxes Columns', 'soledad' ),
	'id'       => 'penci_home_box_column',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'3' => __('3 Columns','soledad' ),
		'4' => __('4 Columns','soledad' ),
	),
);
$options[] = array(
	'default'  => 'horizontal',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Home Featured Boxes Size Type', 'soledad' ),
	'id'       => 'penci_home_box_type',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'horizontal' => esc_html__( 'Horizontal Size', 'soledad' ),
		'square'     => esc_html__( 'Square Size', 'soledad' ),
		'vertical'   => esc_html__( 'Vertical Size', 'soledad' ),
	),
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Open Home Featured Boxes in New Tab', 'soledad' ),
	'id'       => 'penci_home_boxes_new_tab',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Homepage Featured Box 1st Image', 'soledad' ),
	'id'       => 'penci_home_box_img1',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 1st Text', 'soledad' ),
	'id'       => 'penci_home_box_text1',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 1st URL', 'soledad' ),
	'id'       => 'penci_home_box_url1',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Homepage Featured Box 2nd Image', 'soledad' ),
	'id'       => 'penci_home_box_img2',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 2nd Text', 'soledad' ),
	'id'       => 'penci_home_box_text2',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 2nd URL', 'soledad' ),
	'id'       => 'penci_home_box_url2',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Homepage Featured Box 3rd Image', 'soledad' ),
	'id'       => 'penci_home_box_img3',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 3rd Text', 'soledad' ),
	'id'       => 'penci_home_box_text3',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 3rd URL', 'soledad' ),
	'id'       => 'penci_home_box_url3',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Homepage Featured Box 4th Image', 'soledad' ),
	'id'       => 'penci_home_box_img4',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 4th Text', 'soledad' ),
	'id'       => 'penci_home_box_text4',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 4th URL', 'soledad' ),
	'id'       => 'penci_home_box_url4',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Homepage Featured Box 5th Image', 'soledad' ),
	'id'       => 'penci_home_box_img5',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 5th Text', 'soledad' ),
	'id'       => 'penci_home_box_text5',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 5th URL', 'soledad' ),
	'id'       => 'penci_home_box_url5',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Homepage Featured Box 6th Image', 'soledad' ),
	'id'       => 'penci_home_box_img6',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 6th Text', 'soledad' ),
	'id'       => 'penci_home_box_text6',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 6th URL', 'soledad' ),
	'id'       => 'penci_home_box_url6',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Homepage Featured Box 7th Image', 'soledad' ),
	'id'       => 'penci_home_box_img7',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 7th Text', 'soledad' ),
	'id'       => 'penci_home_box_text7',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 7th URL', 'soledad' ),
	'id'       => 'penci_home_box_url7',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Homepage Featured Box 8th Image', 'soledad' ),
	'id'       => 'penci_home_box_img8',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 8th Text', 'soledad' ),
	'id'       => 'penci_home_box_text8',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 8th URL', 'soledad' ),
	'id'       => 'penci_home_box_url8',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Homepage Featured Box 9th Image', 'soledad' ),
	'id'       => 'penci_home_box_img9',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 9th Text', 'soledad' ),
	'id'       => 'penci_home_box_text9',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Homepage Featured Box 9th URL', 'soledad' ),
	'id'       => 'penci_home_box_url9',
	'type'     => 'soledad-fw-text',
);

return $options;
