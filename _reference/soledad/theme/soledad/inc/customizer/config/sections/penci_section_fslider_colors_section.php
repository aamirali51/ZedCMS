<?php
$options   = [];
$options[] = array(
	'label'       => __( 'Featured Slider Overlay Color', 'soledad' ),
	'description' => __( 'This option just apply for some featured slider styles has overlay color', 'soledad' ),
	'id'          => 'penci_featured_slider_overlay_bg',
	'default'     => '#000000',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Featured Slider Overlay Color Opacity', 'soledad' ),
	'id'       => 'penci_featured_slider_overlay_bg_opacity',
	'default'  => '0.7',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'0'    => '0',
		'0.05' => '0.05',
		'0.1'  => '0.1',
		'0.15' => '0.15',
		'0.2'  => '0.2',
		'0.25' => '0.25',
		'0.3'  => '0.3',
		'0.35' => '0.35',
		'0.4'  => '0.4',
		'0.45' => '0.45',
		'0.5'  => '0.5',
		'0.55' => '0.55',
		'0.6'  => '0.6',
		'0.65' => '0.65',
		'0.7'  => '0.7',
		'0.75' => '0.75',
		'0.8'  => '0.8',
		'0.85' => '0.85',
		'0.9'  => '0.9',
		'0.95' => '0.95',
		'1'    => '1',
	)
);
$options[] = array(
	'label'    => __( 'Featured Slider Hover Overlay Color Opacity', 'soledad' ),
	'id'       => 'penci_featured_slider_overlay_bg_hover_opacity',
	'type'     => 'soledad-fw-select',
	'default'  => '0.9',
	'sanitize' => 'penci_sanitize_choices_field',
	'choices'  => array(
		'0'    => '0',
		'0.05' => '0.05',
		'0.1'  => '0.1',
		'0.15' => '0.15',
		'0.2'  => '0.2',
		'0.25' => '0.25',
		'0.3'  => '0.3',
		'0.35' => '0.35',
		'0.4'  => '0.4',
		'0.45' => '0.45',
		'0.5'  => '0.5',
		'0.55' => '0.55',
		'0.6'  => '0.6',
		'0.65' => '0.65',
		'0.7'  => '0.7',
		'0.75' => '0.75',
		'0.8'  => '0.8',
		'0.85' => '0.85',
		'0.9'  => '0.9',
		'0.95' => '0.95',
		'1'    => '1',
	)
);
$options[] = array(
	'label'       => __( 'Center Box Background Color', 'soledad' ),
	'id'          => 'penci_featured_slider_box_bg_color',
	'description' => __( 'This option just apply for featured slider styles 1, 2, 3, 35, 36', 'soledad' ),
	'default'     => '#000000',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Center Box Opacity', 'soledad' ),
	'id'       => 'penci_featured_slider_box_opacity',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'0'    => '0',
		'0.05' => '0.05',
		'0.1'  => '0.1',
		'0.15' => '0.15',
		'0.2'  => '0.2',
		'0.25' => '0.25',
		'0.3'  => '0.3',
		'0.35' => '0.35',
		'0.4'  => '0.4',
		'0.45' => '0.45',
		'0.5'  => '0.5',
		'0.55' => '0.55',
		'0.6'  => '0.6',
		'0.65' => '0.65',
		'0.7'  => '0.7',
		'0.75' => '0.75',
		'0.8'  => '0.8',
		'0.85' => '0.85',
		'0.9'  => '0.9',
		'0.95' => '0.95',
		'1'    => '1',
	),
	'default'  => '0.7',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Post Category Color', 'soledad' ),
	'id'       => 'penci_featured_slider_cat_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Post Category Hover Color', 'soledad' ),
	'id'       => 'penci_featured_slider_cat_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Title Post Color', 'soledad' ),
	'id'       => 'penci_featured_slider_title_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Title Post Hover Color', 'soledad' ),
	'id'       => 'penci_featured_slider_title_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Post Meta Color', 'soledad' ),
	'id'       => 'penci_featured_slider_meta_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Post Excerpt Color', 'soledad' ),
	'id'       => 'penci_featured_slider_excerpt_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Post Format Icons Color', 'soledad' ),
	'id'       => 'penci_featured_slider_icon_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Overlay Color for Slider Style 29 & 30', 'soledad' ),
	'id'       => 'penci_featured_slider_color_29',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Overlay Opacity for Slider Style 29 & 30', 'soledad' ),
	'id'       => 'penci_featured_slider_overlay_opacity29',
	'type'     => 'soledad-fw-select',
	'default'  => '0.3',
	'sanitize' => 'penci_sanitize_choices_field',
	'choices'  => array(
		'0'    => '0',
		'0.05' => '0.05',
		'0.1'  => '0.1',
		'0.15' => '0.15',
		'0.2'  => '0.2',
		'0.25' => '0.25',
		'0.3'  => '0.3',
		'0.35' => '0.35',
		'0.4'  => '0.4',
		'0.45' => '0.45',
		'0.5'  => '0.5',
		'0.55' => '0.55',
		'0.6'  => '0.6',
		'0.65' => '0.65',
		'0.7'  => '0.7',
		'0.75' => '0.75',
		'0.8'  => '0.8',
		'0.85' => '0.85',
		'0.9'  => '0.9',
		'0.95' => '0.95',
		'1'    => '1',
	)
);
$options[] = array(
	'label'    => __( 'Color of Line on Slider Style 29 & 30', 'soledad' ),
	'id'       => 'penci_featured_slider_lines_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Color Button Text & Button Border on Slider Style 29, 30, 35, 36', 'soledad' ),
	'id'       => 'penci_featured_slider_button_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Background Color Hover of Buttor on Slider Style 29, 30, 35, 36, 38', 'soledad' ),
	'id'       => 'penci_featured_slider_button_hover_bg',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);
$options[] = array(
	'label'    => __( 'Text Color Hover of Buttor on Slider Style 29, 30, 35, 36, 38', 'soledad' ),
	'id'       => 'penci_featured_slider_button_hover_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color'
);

return $options;
