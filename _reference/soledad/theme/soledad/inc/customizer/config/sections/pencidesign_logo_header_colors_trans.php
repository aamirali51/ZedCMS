<?php
$options   = [];
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Header Social Icons Color', 'soledad' ),
	'id'       => 'penci_header_tran_social_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Header Social Icons Color Hover', 'soledad' ),
	'id'       => 'penci_header_tran_social_color_hover',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Search, Shopping Cart & Mobile Bars Icons Color', 'soledad' ),
	'id'       => 'penci_tran_main_bar_search_magnify',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Icon Close Search Color', 'soledad' ),
	'id'       => 'penci_tran_main_bar_close_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Slogan Text', 'soledad' ),
	'id'       => 'penci_section_trslogan_text_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Header Slogan Text Color', 'soledad' ),
	'id'       => 'penci_header_tran_slogan_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Header Slogan Line Color', 'soledad' ),
	'id'       => 'penci_header_tran_slogan_line_color',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Primary Menu', 'soledad' ),
	'id'       => 'penci_section_traprimary_menu_heading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Menu Text Color', 'soledad' ),
	'id'       => 'penci_tran_main_bar_nav_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Menu Text Hover & Active Color', 'soledad' ),
	'id'       => 'penci_tran_main_bar_color_active',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Padding Menu Items Background Color', 'soledad' ),
	'id'       => 'penci_tran_main_bar_padding_color',
);

return $options;
