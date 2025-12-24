<?php
$options   = [];
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Icon Post Format', 'soledad' ),
	'id'       => 'penci_grid_icon_format',
	'type'     => 'soledad-fw-toggle',
);
$options[]     =  array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Move Categories into the Post Meta', 'soledad' ),
	'description' => __( 'This feature is only supported on category, tag, and archive pages.', 'soledad' ),
	'id'          => 'penci_grid_move_cat_meta',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Display Post Meta Overlay Featured Image', 'soledad' ),
	'id'          => 'penci_grid_meta_overlay',
	'description'=>__('This option just apply for Grid Posts & Masonry Posts','soledad'),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Disable Autoplay for Slider on Posts Format Gallery', 'soledad' ),
	'id'          => 'penci_grid_slider_auto',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Uppercase on Post Categories', 'soledad' ),
	'id'       => 'penci_grid_uppercase_cat',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Turn Off Uppercase on Post Title', 'soledad' ),
	'id'       => 'penci_grid_off_title_uppercase',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Post Title HTML Heading Tag', 'soledad' ),
	'id'       => 'penci_grid_title_tag',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''   => esc_html__( 'Default', 'soledad' ),
		'h1' => esc_html__( 'H1', 'soledad' ),
		'h2' => esc_html__( 'H2', 'soledad' ),
		'h3' => esc_html__( 'H3', 'soledad' ),
		'h4' => esc_html__( 'H4', 'soledad' ),
		'h5' => esc_html__( 'H5', 'soledad' ),
		'h6' => esc_html__( 'H6', 'soledad' ),
	)
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Click on Posts Thumbnail to Play Video', 'soledad' ),
	'description'=>__('This option only apply for video posts format - supports only for Youtube & Vimeo','soledad'),
	'id'          => 'penci_grid_lightbox_video',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Letter Spacing on Post Titles', 'soledad' ),
	'id'       => 'penci_grid_off_letter_spacing',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Do Not Crop Images on List Layouts', 'soledad' ),
	'id'          => 'penci_grid_nocrop_list',
	'description'=>__('This option does not apply for gallery posts format','soledad'),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Share Box', 'soledad' ),
	'id'       => 'penci_grid_share_box',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Borders Left & Right on Share Box', 'soledad' ),
	'id'       => 'penci_grid_share_rmbd',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Post Sub Title', 'soledad' ),
	'id'       => 'penci_grid_show_sub_title',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Category', 'soledad' ),
	'id'       => 'penci_grid_cat',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Post Author', 'soledad' ),
	'id'       => 'penci_grid_author',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Post Date', 'soledad' ),
	'id'       => 'penci_grid_date',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Views Count', 'soledad' ),
	'id'       => 'penci_grid_countviews',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Comment Count on Mixed & Overlay Posts', 'soledad' ),
	'id'       => 'penci_grid_comment',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Comment Count on Grid, Masonry, List, Boxed, Photography Posts', 'soledad' ),
	'id'       => 'penci_grid_comment_other',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Reading Time', 'soledad' ),
	'id'       => 'penci_grid_readingtime',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Line Above Post Excerpt', 'soledad' ),
	'id'       => 'penci_grid_remove_line',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Post Excerpt', 'soledad' ),
	'id'       => 'penci_grid_remove_excerpt',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Add "Read more" button link', 'soledad' ),
	'id'       => 'penci_grid_add_readmore',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove arrow on "Read more"', 'soledad' ),
	'id'       => 'penci_grid_remove_arrow',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Make "Read more" is A Button', 'soledad' ),
	'id'       => 'penci_grid_readmore_button',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Border Bottom on List Posts', 'soledad' ),
	'id'       => 'penci_grid_rmbd_bottom',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Header Alignment', 'soledad' ),
	'description' => __( 'This option does not apply for Overlay, Photography, Boxed 2 Styles', 'soledad' ),
	'id'          => 'penci_grihead_align',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''       => esc_html__( 'Default', 'soledad' ),
		'left'   => esc_html__( 'Left', 'soledad' ),
		'center' => esc_html__( 'Center', 'soledad' ),
		'right'  => esc_html__( 'Right', 'soledad' ),
	)
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Image Alignment for List Layout', 'soledad' ),
	'id'          => 'penci_imglist_align',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''       => esc_html__( 'Left', 'soledad' ),
		'right'  => esc_html__( 'Right', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Post Excerpt Alignment', 'soledad' ),
	'id'       => 'penci_griexc_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''        => esc_html__( 'Default', 'soledad' ),
		'left'    => esc_html__( 'Left', 'soledad' ),
		'center'  => esc_html__( 'Center', 'soledad' ),
		'right'   => esc_html__( 'Right', 'soledad' ),
		'justify' => esc_html__( 'Justify', 'soledad' ),
	)
);
$options[] = array(
	'default'  => 'left',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Align "Read more" Button', 'soledad' ),
	'id'       => 'penci_grid_readmore_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'left'   => esc_html__( 'Left', 'soledad' ),
		'center' => esc_html__( 'Center', 'soledad' ),
		'right'  => esc_html__( 'Right', 'soledad' )
	)
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Share Box Alignment', 'soledad' ),
	'description' => __( 'This option does apply for some Post Styles, not all', 'soledad' ),
	'id'          => 'penci_grishare_align',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''       => esc_html__( 'Default', 'soledad' ),
		'left'   => esc_html__( 'Left', 'soledad' ),
		'center' => esc_html__( 'Center', 'soledad' ),
		'right'  => esc_html__( 'Right', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '30',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Excerpt Length', 'soledad' ),
	'id'       => 'penci_post_excerpt_length',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_post_excerpt_length',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '30',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Maxium Excerpt Lines', 'soledad' ),
	'id'       => 'penci_post_excerpt_line',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_post_excerpt_line',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Image Width on List Posts', 'soledad' ),
	'id'       => 'penci_img_listwidth',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_img_listwidth',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => '%',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Image Width on Small List Posts', 'soledad' ),
	'id'       => 'penci_img_slistwidth',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_img_slistwidth',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => '%',
		),
	),
);

return $options;
