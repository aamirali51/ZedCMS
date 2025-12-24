<?php
$options   = [];
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Home Title Box Style', 'soledad' ),
	'id'       => 'penci_featured_cat_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''                  => __( 'Default( follow Sidebar )', 'soledad' ),
		'style-1'           => __( 'Style 1', 'soledad' ),
		'style-2'           => __( 'Style 2', 'soledad' ),
		'style-3'           => __( 'Style 3', 'soledad' ),
		'style-4'           => __( 'Style 4', 'soledad' ),
		'style-5'           => __( 'Style 5', 'soledad' ),
		'style-6'           => __( 'Style 6 - Only Text', 'soledad' ),
		'style-7'           => __( 'Style 7', 'soledad' ),
		'style-9'           => __( 'Style 8', 'soledad' ),
		'style-8'           => __( 'Style 9 - Custom Background Image', 'soledad' ),
		'style-10'          => __( 'Style 10', 'soledad' ),
		'style-11'          => __( 'Style 11', 'soledad' ),
		'style-12'          => __( 'Style 12', 'soledad' ),
		'style-13'          => __( 'Style 13', 'soledad' ),
		'style-14'          => __( 'Style 14', 'soledad' ),
		'style-15'          => __( 'Style 15', 'soledad' ),
		'style-16'          => __( 'Style 16', 'soledad' ),
		'style-2 style-17'  => __( 'Style 17', 'soledad' ),
		'style-18'          => __( 'Style 18', 'soledad' ),
		'style-18 style-19' => __( 'Style 19', 'soledad' ),
		'style-18 style-20' => __( 'Style 20', 'soledad' ),
		'style-21'          => __( 'Style 21', 'soledad' ),
		'style-22'          => __( 'Style 22', 'soledad' ),
		'style-23'          => __( 'Style 23', 'soledad' ),
		'style-24'          => __( 'Style 24', 'soledad' ),
		'style-25'          => __( 'Style 25', 'soledad' ),
		'style-26'          => __( 'Style 26', 'soledad' ),
		'style-27'          => __( 'Style 27', 'soledad' ),
		'style-28'          => __( 'Style 28', 'soledad' ),
	)
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Border Outer on Title Box', 'soledad' ),
	'id'       => 'penci_home_remove_border_outer',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Arrow Down on Title Box', 'soledad' ),
	'id'       => 'penci_home_remove_arrow_down',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'esc_url_raw',
	'type'     => 'soledad-fw-image',
	'label'    => __( 'Custom Background Image for Style 9', 'soledad' ),
	'id'       => 'penci_featured_cat_image_8',
);
$options[] = array(
	'default'  => 'no-repeat',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Background Image Repeat for Style 9', 'soledad' ),
	'id'       => 'penci_featured_cat8_repeat',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'no-repeat' => 'No Repeat',
		'repeat'    => 'Repeat'
	)
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Turn Off Uppercase on Home Title Box', 'soledad' ),
	'id'       => 'penci_home_featured_cat_lowcase',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => 'pcalign-left',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Homepage Featured Categories Title Box Align', 'soledad' ),
	'id'       => 'penci_featured_cat_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'pcalign-left'   => __( 'Left', 'soledad' ),
		'pcalign-center' => __( 'Center', 'soledad' ),
		'pcalign-right'  => __( 'Right', 'soledad' )
	)
);
$options[] = array(
	'default'  => 'pcalign-center',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Homepage Heading Latest Post Titles Align', 'soledad' ),
	'id'       => 'penci_heading_latest_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'pcalign-center' => __( 'Center', 'soledad' ),
		'pcalign-left'   => __( 'Left', 'soledad' ),
		'pcalign-right'  => __( 'Right', 'soledad' )
	)
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Align Icon on Style 15', 'soledad' ),
	'id'       => 'penci_homep_icon_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''              => __( 'Default( follow Sidebar )', 'soledad' ),
		'pciconp-right' => __( 'Right', 'soledad' ),
		'pciconp-left'  => __( 'Left', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom Icon on Style 15', 'soledad' ),
	'id'       => 'penci_homep_icon_design',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''             => __( 'Default( follow Sidebar )', 'soledad' ),
		'pcicon-right' => __( 'Arrow Right', 'soledad' ),
		'pcicon-left'  => __( 'Arrow Left', 'soledad' ),
		'pcicon-down'  => __( 'Arrow Down', 'soledad' ),
		'pcicon-up'    => __( 'Arrow Up', 'soledad' ),
		'pcicon-star'  => __( 'Star', 'soledad' ),
		'pcicon-bars'  => __( 'Bars', 'soledad' ),
		'pcicon-file'  => __( 'File', 'soledad' ),
		'pcicon-fire'  => __( 'Fire', 'soledad' ),
		'pcicon-book'  => __( 'Book', 'soledad' ),
	)
);

return $options;
