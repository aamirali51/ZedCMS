<?php
$options   = [];
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'List Featured Categories', 'soledad' ),
	'id'          => 'penci_home_featured_cat',
	'description'=>__('By default, this option apply only for Homepage Magazine(style 1 and style 2) layout, copy and paste categories slug you want display above Latest Posts here - check <a class="wp-customizer-link" rel="nofollow" href="https://imgresources.s3.amazonaws.com/magazine-2.png" target="_blank">this image</a> to understand what is categories slug. Example: You want display categories "Life Style, Travel, Music" above Latest Posts, fill categories slug like "life-style, travel, music"','soledad'),
	'type'        => 'soledad-fw-textarea',
	'transport'       => 'postMessage',
	'partial_refresh'    => [
		'penci_home_featured_cat' => [
			'selector'        => '.home-featured-cat',
			'render_callback' => 'penci_home_featured_cat',
		],
	],
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Featured Categories for All Homepage Layouts - Not for Magazine Layouts Only', 'soledad' ),
	'id'       => 'penci_enable_featured_cat_all_layouts',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Move Latest Posts To Above Featured Categories', 'soledad' ),
	'id'       => 'penci_move_latest_posts_above',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Border Bottom Between Post Items', 'soledad' ),
	'id'       => 'penci_feacat_rmborder',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Post Meta Overlay Featured Image for Featured Category Style 7', 'soledad' ),
	'id'       => 'penci_home_featured_cat_overlay7',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Thumbnail for Small Posts on Featured Category Style 15', 'soledad' ),
	'id'       => 'penci_home_thumbnail15',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '60',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Custom Space Between Featured Categories', 'soledad' ),
	'id'       => 'penci_featured_cat_margin',
	'ids'      => array(
		'desktop' => 'penci_featured_cat_margin',
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
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Custom Image Width on Small Posts', 'soledad' ),
	'id'       => 'penci_featured_cat_imgwidth',
	'ids'      => array(
		'desktop' => 'penci_featured_cat_imgwidth',
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
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Post Author on Featured Categories', 'soledad' ),
	'id'       => 'penci_home_featured_cat_author',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Show Post Author on Small Posts', 'soledad' ),
	'description' => __( 'You can check <a class="wp-customizer-link" href="https://imgresources.s3.amazonaws.com/small-posts.png" target="_blank">this image</a> to know where is the "Small Posts"', 'soledad' ),
	'id'          => 'penci_home_cat_author_sposts',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Post Date on Featured Categories', 'soledad' ),
	'id'       => 'penci_home_featured_cat_date',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Comment Count on Featured Categories', 'soledad' ),
	'id'       => 'penci_home_featured_cat_comment',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Views Count on Featured Categories', 'soledad' ),
	'id'       => 'penci_home_cat_views',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Reading Time on Featured Categories', 'soledad' ),
	'id'       => 'penci_home_cat_readtime',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Post Format Icons on Featured Categories', 'soledad' ),
	'id'       => 'penci_home_featured_cat_icons',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable "View All" link for Featured Categories', 'soledad' ),
	'id'       => 'penci_home_featured_cat_seemore',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove arrow on "View All"', 'soledad' ),
	'id'       => 'penci_home_featured_cat_remove_arrow',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Make "View All" is A Button', 'soledad' ),
	'id'       => 'penci_home_featured_cat_readmore_button',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => 'left',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Align "View All" Button', 'soledad' ),
	'id'       => 'penci_home_featured_cat_readmore_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'left'   => esc_html__( 'Left', 'soledad' ),
		'center' => esc_html__( 'Center', 'soledad' ),
		'right'  => esc_html__( 'Right', 'soledad' )
	)
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Posts Excerpt on Featured Categories', 'soledad' ),
	'id'       => 'penci_home_featured_cat_remove_excerpt',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Autoplay for Sliders on Style 4, 5, 12', 'soledad' ),
	'id'       => 'penci_home_featured_cat_autoplay',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '5',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 1', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_1',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_1',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '5',
		),
	),
);
$options[] = array(
	'default'  => '4',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 2', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_2',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_2',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '4',
		),
	),
);
$options[] = array(
	'default'  => '4',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 3', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_3',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_3',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '4',
		),
	),
);
$options[] = array(
	'default'  => '6',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 4', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_4',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_4',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '6',
		),
	),
);
$options[] = array(
	'default'  => '6',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 5', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_5',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_5',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '6',
		),
	),
);
$options[] = array(
	'default'  => '5',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 6', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_6',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_6',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '5',
		),
	),
);
$options[] = array(
	'default'  => '6',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 7', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_7',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_7',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '6',
		),
	),
);
$options[] = array(
	'default'  => '3',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 8', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_8',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_8',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '3',
		),
	),
);
$options[] = array(
	'default'  => '8',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 9', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_9',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_9',
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
	'default'  => '6',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 10', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_10',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_10',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '6',
		),
	),
);
$options[] = array(
	'default'  => '4',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 11', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_11',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_11',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '4',
		),
	),
);
$options[] = array(
	'default'  => '6',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 12', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_12',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_12',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '6',
		),
	),
);
$options[] = array(
	'default'  => '6',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 13', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_13',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_13',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '6',
		),
	),
);
$options[] = array(
	'default'  => '6',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 14', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_14',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_14',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '6',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Display on Style 15', 'soledad' ),
	'id'       => 'penci_home_featured_posts_numbers_15',
	'ids'      => array(
		'desktop' => 'penci_home_featured_posts_numbers_15',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => '',
		),
	),
);

return $options;
