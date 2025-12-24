<?php
$options = [];
/*Filter Sidebar*/
$options[] = array(
	'id'       => 'pencidesign_woo_filter_widget_enable',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Slidebar Filter ?', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_woo_fw_panel_pst',
	'default'  => 'side-right',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Slidebar Filter Position', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'side-right' => __('Side Right','soledad' ),
		'side-left'  => __('Side Left','soledad' ),
	)
);
$options[] = array(
	'id'       => 'pencidesign_woo_filter_widget_margin',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Custom Space Between Widgets', 'soledad' ),
	'ids'      => array(
		'desktop' => 'pencidesign_woo_filter_widget_margin',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'       => 'pencidesign_woo_filter_widget_heading_lowcase',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Turn Off Uppercase Widget Heading', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'pencidesign_woo_filter_widget_heading_size',
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Custom Widget Heading Text Size', 'soledad' ),
	'ids'      => array(
		'desktop' => 'pencidesign_woo_filter_widget_heading_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),

);
$options[] = array(
	'id'       => 'pencidesign_woo_filter_widget_heading_style',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Sidebar Widget Heading Style', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''                  => __('Default','soledad' ),
		'style-1'           => __('Style 1','soledad' ),
		'style-2'           => __('Style 2','soledad' ),
		'style-3'           => __('Style 3','soledad' ),
		'style-4'           => __('Style 4','soledad' ),
		'style-5'           => __('Style 5','soledad' ),
		'style-6'           => __('Style 6 - Only Text','soledad' ),
		'style-7'           => __('Style 7','soledad' ),
		'style-9'           => __('Style 8','soledad' ),
		'style-8'           => __('Style 9 - Custom Background Image','soledad' ),
		'style-10'          => __('Style 10','soledad' ),
		'style-11'          => __('Style 11','soledad' ),
		'style-12'          => __('Style 12','soledad' ),
		'style-13'          => __('Style 13','soledad' ),
		'style-14'          => __('Style 14','soledad' ),
		'style-15'          => __('Style 15','soledad' ),
		'style-16'          => __('Style 16','soledad' ),
		'style-2 style-17'  => __('Style 17','soledad' ),
		'style-18'          => __('Style 18','soledad' ),
		'style-18 style-19' => __('Style 19','soledad' ),
		'style-18 style-20' => __('Style 20','soledad' ),
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
	'id'       => 'pencidesign_woo_filter_widget_heading_image_9',
	'default'  => '',
	'sanitize' => 'esc_url_raw',
	'type'     => 'soledad-fw-image',
	'label'    => __( 'Custom Background Image for Style 9', 'soledad' ),
);
$options[] = array(
	'id'       => 'pencidesign_woo_filter_widget_heading9_repeat',
	'default'  => 'no-repeat',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Background Image Repeat for Style 9', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'no-repeat' => __('No Repeat','soledad' ),
		'repeat'    => __('Repeat','soledad' ),
	)
);
$options[] = array(
	'id'       => 'pencidesign_woo_filter_widget_heading_align',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Sidebar Widget Heading Align', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''               => __('Default','soledad' ),
		'pcalign-center' => __('Center','soledad' ),
		'pcalign-left'   => __('Left','soledad' ),
		'pcalign-right'  => __('Right','soledad' ),
	)
);
$options[] = array(
	'id'       => 'pencidesign_woo_filter_widget_remove_border_outer',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Border Outer on Widget Heading', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'pencidesign_woo_filter_widget_remove_arrow_down',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Arrow Down on Widget Heading', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);

return $options;
