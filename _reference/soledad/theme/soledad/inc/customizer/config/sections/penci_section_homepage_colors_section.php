<?php
$options   = [];
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Featured Boxes', 'soledad' ),
	'id'       => 'penci_section_featured_boxes_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Featured Boxes Border & Background Color', 'soledad' ),
	'id'       => 'penci_home_boxes_overlay',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Featured Boxes Title Color', 'soledad' ),
	'id'       => 'penci_home_boxes_title_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Featured Boxes Accent Hover Color', 'soledad' ),
	'id'       => 'penci_home_boxes_accent_hover_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Popular Posts', 'soledad' ),
	'id'       => 'penci_section_popular_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Home Popular Posts Heading Color', 'soledad' ),
	'id'       => 'penci_home_popular_label_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Home Popular Posts Border Color', 'soledad' ),
	'id'       => 'penci_home_popular_border_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Home Popular Post Titles Color', 'soledad' ),
	'id'       => 'penci_home_popular_post_title_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Home Popular Post Titles Post Hover Color', 'soledad' ),
	'id'       => 'penci_home_popular_post_title_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Home Popular Post Date Color', 'soledad' ),
	'id'       => 'penci_home_popular_post_date_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Home Title Box', 'soledad' ),
	'id'       => 'penci_section_home_titlebox_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Home Title Box Background Color', 'soledad' ),
	'id'       => 'penci_home_title_box_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Home Title Box Background Outer Color', 'soledad' ),
	'id'       => 'penci_home_title_box_outer_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Home Title Box Border Color', 'soledad' ),
	'id'       => 'penci_home_title_box_border_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Home Title Box Border Outer Color', 'soledad' ),
	'id'       => 'penci_home_title_box_border_inner_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Border Bottom on Home Title Box Style 5, 10, 11, 12', 'soledad' ),
	'id'       => 'penci_home_title_box_border_bottom5',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Small Border Bottom on Home Title Box Style 7 & Style 8', 'soledad' ),
	'id'       => 'penci_home_title_box_border_bottom7',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Border Top on Home Title Box Style 10', 'soledad' ),
	'id'       => 'penci_home_title_box_border_top10',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Background Shapes Home Title Box Style 11, 12, 13', 'soledad' ),
	'id'       => 'penci_home_title_box_shapes_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color for Icon on Style 15', 'soledad' ),
	'id'       => 'penci_home_bgstyle15',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Icon Color on Style 15', 'soledad' ),
	'id'       => 'penci_home_iconstyle15',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Lines on Styles 18, 19, 20', 'soledad' ),
	'id'       => 'penci_home_cllines',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Home Title Box Text Color', 'soledad' ),
	'id'       => 'penci_home_title_box_text_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Featured Categories', 'soledad' ),
	'id'       => 'penci_section_featured_cat_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Titles Color', 'soledad' ),
	'id'       => 'penci_home_featured_title_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Titles Hover Color', 'soledad' ),
	'id'       => 'penci_home_featured_title_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Titles Color For Style 3, Style 4, Style 11, Style 14', 'soledad' ),
	'id'       => 'penci_home_featured3_title_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Titles Hover Color For Style 3, Style 4, Style 11, Style 14', 'soledad' ),
	'id'       => 'penci_home_featured3_title_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Meta Color', 'soledad' ),
	'id'       => 'penci_home_featured_meta_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Posts Meta Color For Style 3, Style 4, Style 11, Style 14', 'soledad' ),
	'id'       => 'penci_home_featured3_meta_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Links on Post Meta', 'soledad' ),
	'id'       => 'penci_home_featured_metalink_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Text Color for "View All" Button', 'soledad' ),
	'id'       => 'penci_home_featured_viewall_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color for "View All" Button', 'soledad' ),
	'id'       => 'penci_home_featured_viewall_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Accent Color', 'soledad' ),
	'id'       => 'penci_home_featured_accent_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Posts Overlay Background Color For Style 3 & Style 11', 'soledad' ),
	'id'       => 'penci_home_featured3_overlay_color',
);
$options[] = array(
	'default'  => '0.15',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Posts Overlay Opacity For Style 3 & Style 11', 'soledad' ),
	'id'       => 'penci_home_featured3_overlay_opacity',
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
	'default'  => '0.7',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Posts Overlay Opacity on Hover For Style 3 & Style 11', 'soledad' ),
	'id'       => 'penci_home_featured3_overlay_hover_opacity',
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

return $options;
