<?php
$options   = [];
$options[] = array(
	'label'       => __( 'General', 'soledad' ),
	'id'          => 'penci_sidebar_heading_01',
	'type'        => 'soledad-fw-header',
);
$options[] = array(
	'label'    => __( 'Enable Sticky Sidebar', 'soledad' ),
	'id'       => 'penci_sidebar_sticky',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label'    => __( 'Disable Sidebar for BBPress Forums', 'soledad' ),
	'id'       => 'penci_dis_sidebar_bbforums',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label'    => __( 'Disable Sidebar for BBPress Forum', 'soledad' ),
	'id'       => 'penci_dis_sidebar_bbforum',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label'    => __( 'Disable Sidebar for BBPress Topic', 'soledad' ),
	'id'       => 'penci_dis_sidebar_bbtoppic',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'label'       => __( 'Widget Styles', 'soledad' ),
	'id'          => 'penci_sidebar_heading_02',
	'type'        => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Select Sidebar Style', 'soledad' ),
	'id'       => 'penci_sidebar_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''                  => __( 'Default', 'soledad' ),
		'pcsb-boxed-whole'  => __( 'Boxed Whole Sidebar', 'soledad' ),
		'pcsb-boxed-widget' => __( 'Boxed Widgets on Sidebar', 'soledad' ),
	)
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Disable Boxed on "Custom HTML" widget?', 'soledad' ),
	'id'          => 'penci_sidebar_disable_phtml',
	'description' => __( 'This option just applies on "Custom HTML" widget & you\'ve selected sidebar style is "Boxed Widgets"', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Custom Padding Value on Boxed Sidebar Styles', 'soledad' ),
	'id'       => 'penci_sidebar_padding',
	'ids'      => array(
		'desktop' => 'penci_sidebar_padding',
		'mobile'  => 'penci_sidebar_padding_mobile',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-hidden',
	'label'    => __( 'Custom Padding Value on Boxed Sidebar Styles', 'soledad' ),
	'id'       => 'penci_sidebar_padding_mobile',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Select Borders Type on Sidebar Boxed Styles', 'soledad' ),
	'id'          => 'penci_sbbx_bdstyle',
	'description' => __( 'Some types need to adjust the borders width below to a minimum of 4px to see how it works.', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''       => __( 'Default ( Solid )', 'soledad' ),
		'dotted' => __( 'Dotted', 'soledad' ),
		'dashed' => __( 'Dashed', 'soledad' ),
		'double' => __( 'Double', 'soledad' ),
		'groove' => __( 'Groove', 'soledad' ),
		'ridge'  => __( 'Ridge', 'soledad' ),
		'inset'  => __( 'Inset', 'soledad' ),
		'outset' => __( 'Outset', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Custom Borders Width on Sidebar Boxed Styles', 'soledad' ),
	'id'       => 'penci_sidebar_boxed_bdw',
	'ids'      => array(
		'desktop' => 'penci_sidebar_boxed_bdw',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '29.1',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Sidebar Width', 'soledad' ),
	'id'       => 'penci_sidebar_width',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_sidebar_width',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '%',
			'default' => '29.1',
		),
	),
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'description' => __( 'This option will override the sidebar width set by % above. Default is 340px.', 'soledad' ),
	'label'       => __( 'Custom Sidebar Width in Pixel', 'soledad' ),
	'id'          => 'penci_sidebar_width_px',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_sidebar_width_px',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '21.5',
	'sanitize' => 'absint',
	'label'    => __( 'Sidebar Width on Two Sidebars Layout', 'soledad' ),
	'id'       => 'penci_2sidebar_width',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_2sidebar_width',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '%',
			'default' => '21.5',
		),
	),
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'absint',
	'description' => __( 'This option will override the sidebar width set by % above. Default is 300px.', 'soledad' ),
	'label'       => __( 'Sidebar Width on Two Sidebars Layout in Pixel', 'soledad' ),
	'id'          => 'penci_2sidebar_width_px',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_2sidebar_width_px',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '50',
	'sanitize' => 'absint',
	'label'    => __( 'Space Between Sidebar & Content', 'soledad' ),
	'id'       => 'penci_sidebar_space',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_sidebar_space',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '50',
		),
	),

);
$options[] = array(
	'default'  => '60',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Space Between Widgets', 'soledad' ),
	'id'       => 'penci_sidebar_widget_margin',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_sidebar_widget_margin',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '60',
		),
	),
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Turn Off Uppercase Widget Heading', 'soledad' ),
	'id'       => 'penci_sidebar_heading_lowcase',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Remove Border Bottom on the list in Widgets', 'soledad' ),
	'description' => __( 'This option will remove the border-bottom on widgets: Soledad Latest Posts, Soledad Popular Posts, Categories,...', 'soledad' ),
	'id'          => 'penci_sidebar_rm_bdbottom',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => 'style-1',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Sidebar Widget Heading Style', 'soledad' ),
	'id'       => 'penci_sidebar_heading_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'style-1'           => __( 'Default Style', 'soledad' ),
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
	'default'  => '',
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Custom Background Image for Style 9', 'soledad' ),
	'id'       => 'penci_sidebar_heading_image_8',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => 'no-repeat',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Background Image Repeat for Style 9', 'soledad' ),
	'id'       => 'penci_sidebar_heading8_repeat',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'no-repeat' => __( 'No Repeat', 'soledad' ),
		'repeat'    => __( 'Repeat', 'soledad' )
	)
);
$options[] = array(
	'default'  => 'pcalign-center',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Sidebar Widget Heading Align', 'soledad' ),
	'id'       => 'penci_sidebar_heading_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'pcalign-center' => __( 'Center', 'soledad' ),
		'pcalign-left'   => __( 'Left', 'soledad' ),
		'pcalign-right'  => __( 'Right', 'soledad' )
	)
);
$options[] = array(
	'default'  => 'pciconp-right',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Align Icon on Style 15', 'soledad' ),
	'id'       => 'penci_sidebar_icon_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'pciconp-right' => 'Right',
		'pciconp-left'  => 'Left',
	)
);
$options[] = array(
	'default'  => 'pcicon-right',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Custom Icon on Style 15', 'soledad' ),
	'id'       => 'penci_sidebar_icon_design',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
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
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Border Outer on Widget Heading', 'soledad' ),
	'id'       => 'penci_sidebar_remove_border_outer',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Arrow Down on Widget Heading', 'soledad' ),
	'id'       => 'penci_sidebar_remove_arrow_down',
	'type'     => 'soledad-fw-toggle',
);

return $options;
