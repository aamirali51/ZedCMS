<?php
$options   = [];
$options[] = array(
	'label'    => __( 'Footer Section Background Color', 'soledad' ),
	'id'       => 'penci_footer_copyright_bg_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'label'    => __( 'Footer Section Background Image', 'soledad' ),
	'id'       => 'penci_footer_copyright_bg_image',
	'sanitize' => 'esc_url_raw',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'label'    => esc_html__( 'Footer Widgets Area', 'soledad' ),
	'id'       => 'penci_footer_warea_heading',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field',
);
$options[] = array(
	'label'    => __( 'Footer Widget Area Background', 'soledad' ),
	'id'       => 'penci_footer_widget_area_bg',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Widget Area Border Top Color', 'soledad' ),
	'id'       => 'penci_footer_widget_area_border',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Footer Widget Area Background Image', 'soledad' ),
	'id'       => 'penci_footer_widget_bg_image',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Widget Area Text Color', 'soledad' ),
	'id'       => 'penci_footer_widget_area_text_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Widget Area Borders Color for List', 'soledad' ),
	'id'       => 'penci_footer_widget_area_list_border',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Widget Title Text Color', 'soledad' ),
	'id'       => 'penci_footer_widget_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Widget Title Border Color', 'soledad' ),
	'id'       => 'penci_footer_widget_title_border_color',
);
$options[] = array(
	'default'  => '3',
	'sanitize' => 'absint',
	'label'    => __( 'Border Width of Footer Widget Titles', 'soledad' ),
	'id'       => 'penci_footer_widget_title_border_width',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_footer_widget_title_border_width',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => 3,
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Widget Accent Color', 'soledad' ),
	'id'       => 'penci_footer_widget_accent_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Widget Accent Hover Color', 'soledad' ),
	'id'       => 'penci_footer_widget_accent_hover_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Footer Instagram', 'soledad' ),
	'id'       => 'penci_footer_instagram_bheading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Instagram Border Top Color', 'soledad' ),
	'id'       => 'footer_instagram_border_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Instagram Title Color', 'soledad' ),
	'id'       => 'penci_footer_instagram_title_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Footer Social Icons', 'soledad' ),
	'id'       => 'penci_footer_socialicons_bheading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Social Icons Border & Color', 'soledad' ),
	'id'       => 'penci_footer_icon_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Social Icons Hover Icons Color', 'soledad' ),
	'id'       => 'penci_footer_icon_hover_icon_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Social Icons Hover Border & Background Color', 'soledad' ),
	'id'       => 'penci_footer_icon_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Social Text Color', 'soledad' ),
	'id'       => 'penci_footer_social_text_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Social Hover Text Color', 'soledad' ),
	'id'       => 'penci_footer_social_hover_text_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Social Section Border Color', 'soledad' ),
	'id'       => 'penci_footer_social_border_color',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Footer Social Section Background Color', 'soledad' ),
	'id'          => 'penci_footer_social_bgcolor',
	'description' => __( 'This option just applies when you re-order the social icons section to higher than other sections', 'soledad' ),
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Footer Menu', 'soledad' ),
	'id'       => 'penci_footer_menu_bheading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Menu Text Color', 'soledad' ),
	'id'       => 'penci_footer_menu_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Menu Text Color Hover', 'soledad' ),
	'id'       => 'penci_footer_menu_color_hover',
);
$options[] = array(
	'label'    => esc_html__( 'Footer Copyright Text', 'soledad' ),
	'id'       => 'penci_footer_copyright_bheading',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_hex_color'
);
$options[] = array(
	'label'    => __( 'Footer Copyright Text Color', 'soledad' ),
	'id'       => 'penci_footer_copyright_text_color',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Footer Copyright Accent Color', 'soledad' ),
	'id'       => 'penci_footer_copyright_accent_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Go to Top Button', 'soledad' ),
	'id'       => 'penci_footer_gototop_bheading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Go To Top Text & Icon Color', 'soledad' ),
	'id'       => 'penci_footer_go_top_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Go To Top Text & Icon Hover Color', 'soledad' ),
	'id'       => 'penci_footer_go_top_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Go To Top Button Floating Background Color', 'soledad' ),
	'id'       => 'penci_footer_go_top_float_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Go To Top Button Floating Icon Color', 'soledad' ),
	'id'       => 'penci_footer_go_top_float_icon_color',
);

return $options;
