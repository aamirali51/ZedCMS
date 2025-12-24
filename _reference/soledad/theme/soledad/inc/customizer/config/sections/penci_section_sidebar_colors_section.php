<?php
$options   = [];
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color for Boxed Sidebar Styles', 'soledad' ),
	'id'       => 'penci_bxsb_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Borders Color for Boxed Sidebar Styles', 'soledad' ),
	'id'       => 'penci_bxsb_border',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Sidebar Widget Heading Background Color', 'soledad' ),
	'id'       => 'penci_sidebar_heading_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Sidebar Widget Heading Background Outer Color', 'soledad' ),
	'id'       => 'penci_sidebar_heading_outer_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Sidebar Widget Heading Border Color', 'soledad' ),
	'id'       => 'penci_sidebar_heading_border_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Sidebar Widget Heading Border Outer Color', 'soledad' ),
	'id'       => 'penci_sidebar_heading_border_inner_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Border Bottom on Widget Heading Style 5, 10, 11, 12', 'soledad' ),
	'id'       => 'penci_sidebar_heading_border_color5',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Small Border Bottom on Widget Heading Style 7 & Style 8', 'soledad' ),
	'id'       => 'penci_sidebar_heading_border_color7',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Border Top on Widget Heading Style 10', 'soledad' ),
	'id'       => 'sidebar_heading_bordertop_color10',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Background Shapes Widget Heading Styles 11, 12, 13', 'soledad' ),
	'id'       => 'penci_sidebar_heading_shapes_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color for Icon on Style 15', 'soledad' ),
	'id'       => 'penci_sidebar_bgstyle15',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Icon Color on Style 15', 'soledad' ),
	'id'       => 'penci_sidebar_iconstyle15',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for Lines on Styles 18, 19, 20', 'soledad' ),
	'id'       => 'penci_sidebar_cllines',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Sidebar Widget Heading Text Color', 'soledad' ),
	'id'       => 'penci_sidebar_heading_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Accent Color', 'soledad' ),
	'id'       => 'penci_sidebar_accent_color',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Accent Hover Color', 'soledad' ),
	'id'       => 'penci_sidebar_accent_hover_color',
);

return $options;
