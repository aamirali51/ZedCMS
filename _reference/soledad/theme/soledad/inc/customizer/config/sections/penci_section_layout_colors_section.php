<?php
$options   = [];
$options[] = array(
	'sanitize'    => 'sanitize_text_field',
	'label'       => esc_html__( 'Standard & Classic', 'soledad' ),
	'description'=>__('All options here apply for Standard Layout, Classic Layout and 1st Posts of 1st Standard & 1st Classic Layout.','soledad'),
	'id'          => 'penci_standar_classic_heading_color',
	'type'        => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Categories Color', 'soledad' ),
	'id'       => 'penci_standard_categories_action_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Post Titles Color', 'soledad' ),
	'type'     => 'soledad-fw-color',
	'id'       => 'penci_standard_title_post_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Post Titles Hover Color', 'soledad' ),
	'type'     => 'soledad-fw-color',
	'id'       => 'penci_standard_title_post_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Post Meta Color', 'soledad' ),
	'type'     => 'soledad-fw-color',
	'id'       => 'penci_standard_meta_post_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Author Name Color', 'soledad' ),
	'type'     => 'soledad-fw-color',
	'id'       => 'penci_standard_author_post_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( '"Continue Reading" Color', 'soledad' ),
	'type'     => 'soledad-fw-color',
	'id'       => 'penci_standard_readmore_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( '"Continue Reading" Background Color', 'soledad' ),
	'id'       => 'penci_standard_readmore_bg',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Share Box Icons Color', 'soledad' ),
	'id'       => 'penci_standard_share_icon_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Share Box Icons Hover Color', 'soledad' ),
	'id'       => 'penci_standard_share_icon_hover_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Accent Color', 'soledad' ),
	'id'       => 'penci_standard_accent_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Other Layouts', 'soledad' ),
	'id'       => 'penci_other_layouts_heading_color',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Categories Accent Color', 'soledad' ),
	'id'       => 'penci_masonry_categories_accent_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Post Titles Color', 'soledad' ),
	'id'       => 'penci_masonry_title_post_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Post Titles Hover Color', 'soledad' ),
	'id'       => 'penci_masonry_title_post_hover_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Post Meta Color', 'soledad' ),
	'id'       => 'penci_masonry_meta_color',
	'type'     => 'soledad-fw-color',

);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Author Name & Comment Count Color', 'soledad' ),
	'id'       => 'penci_masonry_author_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( '"Read More" Link/Button Color', 'soledad' ),
	'id'       => 'penci_masonry_readmore_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( '"Read More" Button Background Color', 'soledad' ),
	'id'       => 'penci_masonry_readmore_bg',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Share Box Icons Color', 'soledad' ),
	'id'       => 'penci_masonry_box_icon',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Share Box Icons Hover Color', 'soledad' ),
	'id'       => 'penci_masonry_box_icon_hover',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Share Box Icons Color on Featured Boxed Layout', 'soledad' ),
	'id'       => 'penci_masonry_box_ficon',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Share Box Icons Hover Color on Featured Boxed Layout', 'soledad' ),
	'id'       => 'penci_masonry_box_ficon_hover',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Share Box Background Color on Featured Boxed Layout', 'soledad' ),
	'id'       => 'penci_masonry_box_icon_bg',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Accent Color', 'soledad' ),
	'id'       => 'penci_masonry_accent_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Overlay Background Color on Photography Layout', 'soledad' ),
	'id'       => 'penci_photography_overlay_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '0.3',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Overlay Background Opacity on Photography Layout', 'soledad' ),
	'id'       => 'penci_photography_overlay_opacity',
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
	'label'    => __( 'Overlay Background Hover Opacity on Photography Layout', 'soledad' ),
	'id'       => 'penci_photography_overlay_hover_opacity',
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
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Categories Color on Photography Layout', 'soledad' ),
	'id'       => 'penci_photography_categories_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Post Titles Color on Photography Layout', 'soledad' ),
	'id'       => 'penci_photography_title_post_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Post Titles Hover Color on Photography Layout', 'soledad' ),
	'id'       => 'penci_photography_title_post_hover_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Meta Color on Photography Layout', 'soledad' ),
	'id'       => 'penci_photography_meta_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Accent Color on Photography Layout', 'soledad' ),
	'id'       => 'penci_photography_accent_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Post Titles Color on Overlay Layout', 'soledad' ),
	'id'       => 'penci_overlay_title_post_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Post Titles Hover Color on Overlay Layout', 'soledad' ),
	'id'       => 'penci_overlay_title_post_hover_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Categories Post Color on Overlay Layout', 'soledad' ),
	'id'       => 'penci_overlay_cat_post_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Categories Post Hover Color on Overlay Layout', 'soledad' ),
	'id'       => 'penci_overlay_cat_hover_post_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Author Color on Overlay Layout', 'soledad' ),
	'id'       => 'penci_overlay_author_post_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Grid Boxed 3 - Background Color', 'soledad' ),
	'id'       => 'penci_grid3_bg_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Grid Boxed 4 - Background Color', 'soledad' ),
	'id'       => 'penci_grid4_bg_color',
	'type'     => 'soledad-fw-color',
);

return $options;
