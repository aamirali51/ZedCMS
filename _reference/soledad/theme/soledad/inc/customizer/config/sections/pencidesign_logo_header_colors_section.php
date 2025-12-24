<?php
$options   = [];
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Header Background Color', 'soledad' ),
	'id'       => 'penci_header_background_color',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'esc_url_raw',
	'type'        => 'soledad-fw-image',
	'label'       => __( 'Header Background Image', 'soledad' ),
	'description' => __( 'You should use image with minimum width 1920px and minimum height 300px', 'soledad' ),
	'id'          => 'penci_header_background_image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Header Social Icons Color', 'soledad' ),
	'id'       => 'penci_header_social_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Header Social Icons Color Hover', 'soledad' ),
	'id'       => 'penci_header_social_color_hover',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Slogan Text', 'soledad' ),
	'id'       => 'penci_section_slogan_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Header Slogan Text Color', 'soledad' ),
	'id'       => 'penci_header_slogan_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Header Slogan Line Color', 'soledad' ),
	'id'       => 'penci_header_slogan_line_color',
);
$options[] = array(
	'sanitize'    => 'sanitize_text_field',
	'label'       => esc_html__( 'Main Bar', 'soledad' ),
	'id'          => 'penci_section_mainbar_heading',
	'description' => __( 'Check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/main-bar.png">this image</a> to know what is Main Bar', 'soledad' ),
	'type'        => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Main Bar Background', 'soledad' ),
	'id'       => 'penci_main_bar_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Main Bar Border Color', 'soledad' ),
	'id'       => 'penci_main_bar_border_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search, Shopping Cart & Mobile Bars Icons Color', 'soledad' ),
	'id'       => 'penci_main_bar_search_magnify',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Icon Close Search Color', 'soledad' ),
	'id'       => 'penci_main_bar_close_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Overlay Background Color', 'soledad' ),
	'id'       => 'penci_search_obg_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Overlay Input Color', 'soledad' ),
	'id'       => 'penci_search_oinput_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Overlay Border Color', 'soledad' ),
	'id'       => 'penci_search_obd_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Primary Menu', 'soledad' ),
	'id'       => 'penci_section_primary_menu_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Menu Text Color', 'soledad' ),
	'id'       => 'penci_main_bar_nav_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Menu Text Hover & Active Color', 'soledad' ),
	'id'       => 'penci_main_bar_color_active',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_hex_color',
	'type'        => 'soledad-fw-color',
	'label'       => __( 'Padding Menu Items Background Color', 'soledad' ),
	'description' => __( 'Use when you enable padding for menu items', 'soledad' ),
	'id'          => 'penci_main_bar_padding_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Dropdown Background Color', 'soledad' ),
	'id'       => 'penci_drop_bg_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Dropdown Menu Items Border Color', 'soledad' ),
	'id'       => 'penci_drop_items_border',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Dropdown Text Color', 'soledad' ),
	'id'       => 'penci_drop_text_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Dropdown Text Hover & Active Color', 'soledad' ),
	'id'       => 'penci_drop_text_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Dropdown Border When Hover for Menu Style2', 'soledad' ),
	'id'       => 'penci_drop_border_style2',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Category Mega Menu', 'soledad' ),
	'id'       => 'penci_section_category_megamenu_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Category Mega Menu Background Color', 'soledad' ),
	'id'       => 'penci_mega_bg_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Category Mega Menu List Child Categories Background Color', 'soledad' ),
	'id'       => 'penci_mega_child_cat_bg_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Title Color', 'soledad' ),
	'id'       => 'penci_mega_title_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Title Color for Category Mega Menu Style 2 & Featured Post on Style 5', 'soledad' ),
	'id'       => 'penci_mega_title_color_25',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Category Mega Menu Post Date Color', 'soledad' ),
	'id'       => 'penci_mega_post_date_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Date Color for Category Mega Menu Style 2 & Featured Post on Style 5', 'soledad' ),
	'id'       => 'penci_mega_post_date_color_25',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Mega Menu Post Category Color', 'soledad' ),
	'id'       => 'penci_mega_post_category_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Category Mega Menu Accent Color', 'soledad' ),
	'id'       => 'penci_mega_accent_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Border Color for Category Mega on Menu Style2', 'soledad' ),
	'id'       => 'penci_mega_border_style2',
);
$options[] = array(
	'sanitize'    => 'sanitize_text_field',
	'label'       => esc_html__( 'Vertical Mobile Navigation', 'soledad' ),
	'id'          => 'penci_section_mobilevernav_color_heading',
	'description' => __( 'Check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/vertical-mobile-navigation.png">this image</a> to know what is Vertical Mobile Navigation.', 'soledad' ),
	'type'        => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Mobile Nav Close Overlay Color', 'soledad' ),
	'id'       => 'penci_ver_nav_overlay_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Mobile Nav Close Button Background', 'soledad' ),
	'id'       => 'penci_ver_nav_close_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Mobile Nav Close Button Icon Color', 'soledad' ),
	'id'       => 'penci_ver_nav_close_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Mobile Nav Background', 'soledad' ),
	'id'       => 'penci_ver_nav_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Form Borders Color', 'soledad' ),
	'id'       => 'penci_ver_nav_searchborder',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Form Text Color', 'soledad' ),
	'id'       => 'penci_ver_nav_textcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search Form Search Icon Color', 'soledad' ),
	'id'       => 'penci_ver_nav_iconcolor',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Mobile Nav Accent Color', 'soledad' ),
	'id'       => 'penci_ver_nav_accent_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Mobile Nav Accent Hover Color', 'soledad' ),
	'id'       => 'penci_ver_nav_accent_hover_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Mobile Nav Menu Items Border Color', 'soledad' ),
	'id'       => 'penci_ver_nav_items_border',
);

return $options;
