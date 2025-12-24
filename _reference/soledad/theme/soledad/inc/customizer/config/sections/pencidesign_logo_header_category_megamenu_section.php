<?php
$options   = array();
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_text_field',
	'label'       => __( 'Custom Border Radius for Images on Category Mega Menu', 'soledad' ),
	'id'          => 'penci_megamenu_border_radius',
	'type'        => 'soledad-fw-text',
	'description' => __( 'You can use pixel or percent. E.g:  <strong>10px</strong>  or  <strong>10%</strong>. If you want to disable border radius for it - fill 0', 'soledad' ),
);
$options[] = array(
	'default'  => 'horizontal',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Featured Images Type for Category Mega Menu', 'soledad' ),
	'id'       => 'penci_mega_featured_image_size',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'horizontal' => esc_html__( 'Horizontal Size', 'soledad' ),
		'square'     => esc_html__( 'Square Size', 'soledad' ),
		'vertical'   => esc_html__( 'Vertical Size', 'soledad' ),
		'custom'     => esc_html__( 'Custom', 'soledad' ),
	),
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_text_field',
	'type'        => 'soledad-fw-text',
	'label'       => __( 'Custom Aspect Ratio for Featured Images Type for Category Mega Menu', 'soledad' ),
	'id'          => 'penci_mega_featured_image_ratio',
	'description' => __( 'The aspect ratio of an element describes the proportional relationship between its width and its height. E.g: <strong>3:2</strong>. Default is 3:2 . This option apply  for <strong>Featured Images Type for Category Mega Menu is Custom</strong>', 'soledad' ),
);
$options[] = array(
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Use AJAX Loading for Category Mega Menus', 'soledad' ),
	'id'       => 'penci_megamenu_ajax_cat',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide "All" tab on Category Mega Menu', 'soledad' ),
	'id'       => 'penci_topbar_mega_hide_alltab',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '8',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Words Length for Post Titles on Category Mega Menu', 'soledad' ),
	'id'       => 'penci_megamenu_title_length',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_megamenu_title_length',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '8',
		),
	),
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Post Format Icons on Category Mega Menu', 'soledad' ),
	'id'       => 'penci_topbar_mega_icons',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Post Category On Category Mega Menu', 'soledad' ),
	'id'       => 'penci_topbar_mega_category',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Post Date On Category Mega Menu', 'soledad' ),
	'id'       => 'penci_topbar_mega_date',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Turn Off Uppercase Post Titles On Category Mega Menu', 'soledad' ),
	'id'       => 'penci_off_uppercase_cat_mega',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Child Categories On Category Mega Menu', 'soledad' ),
	'id'       => 'penci_font_size_child_cat_mega',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_font_size_child_cat_mega',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '12',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Category Name On Category Mega Menu', 'soledad' ),
	'id'       => 'penci_font_size_cat_mega',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_font_size_cat_mega',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '10',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Titles On Category Mega Menu', 'soledad' ),
	'id'       => 'penci_font_size_title_cat_mega',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_font_size_title_cat_mega',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '12',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Date On Category Mega Menu', 'soledad' ),
	'id'       => 'penci_font_size_date_mega',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_font_size_date_mega',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '12',
		),
	),
);

return $options;
