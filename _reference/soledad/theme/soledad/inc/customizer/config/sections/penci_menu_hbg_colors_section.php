<?php
$options   = [];
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'General Colors', 'soledad' ),
	'id'       => 'penci_hmhbg_general_color',
	'type'     => 'soledad-fw-header',
);

$options_color_menu_hbg1 = array(
	'penci_mhbg_icon_toggle_color'   => esc_html__( 'Menu Hamburger Icon Color on Horizontal Navigation', 'soledad' ),
	'penci_mhbg_icon_toggle_hcolor'  => esc_html__( 'Menu Hamburger Icon Hover Color on Horizontal Navigation', 'soledad' ),
	'penci_mhbg_mobilebgcl'          => esc_html__( 'Mobile Bars Icon Background Color( for Vertical Navigation )', 'soledad' ),
	'penci_mhbg_mobilecl'            => esc_html__( 'Mobile Bars Icon Color( for Vertical Navigation )', 'soledad' ),
	'penci_mhbg_bgcolor'             => esc_html__( 'Background Color', 'soledad' ),
	'penci_mhbg_textcolor'           => esc_html__( 'General Text Color', 'soledad' ),
	'penci_mhbg_closecolor'          => esc_html__( 'Menu Hamburger Close Icon Color', 'soledad' ),
	'penci_mhbg_closehover'          => esc_html__( 'Menu Hamburger Close Icon Hover Color', 'soledad' ),
	'penci_mhbg_bordercolor'         => esc_html__( 'General Border Color', 'soledad' ),
	'penci_mhbgtitle_color'          => esc_html__( 'Site Title Color', 'soledad' ),
	'penci_mhbgdesc_hcolor'          => esc_html__( 'Site Description Color', 'soledad' ),
	'penci_mhbg_search_border'       => esc_html__( 'Custom Border Colors for Search Box', 'soledad' ),
	'penci_mhbg_search_border_hover' => esc_html__( 'Custom Border Colors for Search Box on Focus', 'soledad' ),
	'penci_mhbg_search_color'        => esc_html__( 'Custom Text Colors for Search Box', 'soledad' ),
	'penci_mhbg_search_icon'         => esc_html__( 'Custom Colors for Search Icon', 'soledad' ),
	'penci_mhbgaccent_color'         => esc_html__( 'Accent Color', 'soledad' ),
	'penci_mhbgaccent_hover_color'   => esc_html__( 'Accent Hover Color', 'soledad' ),
	'penci_mhbgfooter_color'         => esc_html__( 'Footer Text Color', 'soledad' ),
	'penci_mhbgicon_color'           => esc_html__( 'Social Icons Color', 'soledad' ),
	'penci_mhbgicon_hover_color'     => esc_html__( 'Social Icons Hover Color', 'soledad' ),
	'penci_mhbgicon_border'          => esc_html__( 'Social Icons Border Color', 'soledad' ),
	'penci_mhbgicon_border_hover'    => esc_html__( 'Social Icons Border Hover Color', 'soledad' ),
	'penci_mhbgicon_bg'              => esc_html__( 'Social Icons Background Color', 'soledad' ),
	'penci_mhbgicon_bg_hover'        => esc_html__( 'Social Icons Background Hover Color', 'soledad' ),
);

foreach ( $options_color_menu_hbg1 as $key => $label ) {
	$options[] = array(
		'sanitize' => 'sanitize_hex_color',
		'type'     => 'soledad-fw-color',
		'label'    => $label,
		'id'       => $key,
	);
}

$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Widgets Colors', 'soledad' ),
	'id'       => 'penci_hmhbg_widgets_color',
	'type'     => 'soledad-fw-header',
);

$options_color_menu_hbg2 = array(
	'penci_mhwidget_heading_bg'           => esc_html__( 'Widget Heading Background Color', 'soledad' ),
	'penci_mhwidget_heading_outer_bg'     => esc_html__( 'Widget Heading Background Outer Color', 'soledad' ),
	'penci_mhwidget_heading_bcolor'       => esc_html__( 'Sidebar Widget Heading Border Color', 'soledad' ),
	'penci_mhwidget_heading_binner_color' => esc_html__( 'Widget Heading Border Outer Color', 'soledad' ),
	'penci_mhwidget_heading_bcolor5'      => esc_html__( 'Custom Color for Border Bottom on Widget Heading Style 5', 'soledad' ),
	'penci_mhwidget_heading_bcolor7'      => esc_html__( 'Custom Color for Small Border Bottom on Widget Heading Style 7 & Style 8', 'soledad' ),
	'penci_mhwidget_bordertop_color10'    => esc_html__( 'Custom Color for Border Top on Widget Heading Style 10', 'soledad' ),
	'penci_mhwidget_shapes_color'         => esc_html__( 'Custom Shapes Background Color on Widget Heading Styles 11, 12, 13', 'soledad' ),
	'penci_mhwidget_bgstyle15'            => esc_html__( 'Background Color for Icon on Style 15', 'soledad' ),
	'penci_mhwidget_iconstyle15'          => esc_html__( 'Icon Color on Style 15', 'soledad' ),
	'penci_mhwidget_cllines'              => esc_html__( 'Color for Lines on Styles 18, 19, 20', 'soledad' ),
	'penci_mhwidget_heading_color'        => esc_html__( 'Widget Heading Text Color', 'soledad' ),
);

foreach ( $options_color_menu_hbg2 as $key => $label ) {
	$options[] = array(
		'sanitize' => 'sanitize_hex_color',
		'type'     => 'soledad-fw-color',
		'label'    => $label,
		'id'       => $key,
	);
}

return $options;
