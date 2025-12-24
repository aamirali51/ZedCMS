<?php
$options   = [];
$options[] = array(
	'default'  => 'standard',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Homepage Layout', 'soledad' ),
	'id'       => 'penci_home_layout',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'standard'         => __( 'Standard Posts', 'soledad' ),
		'classic'          => __( 'Classic Posts', 'soledad' ),
		'overlay'          => __( 'Overlay Posts', 'soledad' ),
		'featured'         => __( 'Featured Boxed Posts', 'soledad' ),
		'grid'             => __( 'Grid Posts', 'soledad' ),
		'grid-2'           => __( 'Grid 2 Columns Posts', 'soledad' ),
		'grid-boxed'       => __( 'Grid Boxed', 'soledad' ),
		'grid-boxed-2'     => __( 'Grid Boxed 2', 'soledad' ),
		'grid-boxed-3'     => __( 'Grid Boxed 3', 'soledad' ),
		'grid-boxed-4'     => __( 'Grid Boxed 4', 'soledad' ),
		'grid-boxed-5'     => __( 'Grid Boxed 5', 'soledad' ),
		'masonry'          => __( 'Grid Masonry Posts', 'soledad' ),
		'masonry-2'        => __( 'Grid Masonry 2 Columns Posts', 'soledad' ),
		'list'             => __( 'List Posts', 'soledad' ),
		'list-boxed'       => __( 'List Boxed', 'soledad' ),
		'list-boxed-2'     => __( 'List Boxed 2', 'soledad' ),
		'small-list'       => __( 'Small List Posts', 'soledad' ),
		'boxed-1'          => __( 'Boxed Posts Style 1', 'soledad' ),
		'boxed-2'          => __( 'Boxed Posts Style 2', 'soledad' ),
		'mixed'            => __( 'Mixed Posts', 'soledad' ),
		'mixed-2'          => __( 'Mixed Posts Style 2', 'soledad' ),
		'mixed-3'          => __( 'Mixed Posts Style 3', 'soledad' ),
		'mixed-4'          => __( 'Mixed Posts Style 4', 'soledad' ),
		'photography'      => __( 'Photography Posts', 'soledad' ),
		'magazine-1'       => __( 'Magazine Style 1', 'soledad' ),
		'magazine-2'       => __( 'Magazine Style 2', 'soledad' ),
		'standard-grid'    => __( '1st Standard Then Grid', 'soledad' ),
		'standard-grid-2'  => __( '1st Standard Then Grid 2 Columns', 'soledad' ),
		'standard-list'    => __( '1st Standard Then List', 'soledad' ),
		'standard-boxed-1' => __( '1st Standard Then Boxed', 'soledad' ),
		'classic-grid'     => __( '1st Classic Then Grid', 'soledad' ),
		'classic-grid-2'   => __( '1st Classic Then Grid 2 Columns', 'soledad' ),
		'classic-list'     => __( '1st Classic Then List', 'soledad' ),
		'classic-boxed-1'  => __( '1st Classic Then Boxed', 'soledad' ),
		'overlay-grid'     => __( '1st Overlay Then Grid', 'soledad' ),
		'overlay-list'     => __( '1st Overlay Then List', 'soledad' )
	)
);
$options[] = array(
	'id'              => 'penci_home_grid_col',
	'label'           => __( 'Grid Columns', 'soledad' ),
	'type'            => 'soledad-fw-select',
	'choices'         => array(
		''  => __( 'Default', 'soledad' ),
		'2' => __( '2 Columns', 'soledad' ),
		'3' => __( '3 Columns', 'soledad' ),
		'4' => __( '4 Columns', 'soledad' ),
		'5' => __( '5 Columns', 'soledad' ),
	),
	'sanitize'        => 'penci_sanitize_choices_field',
	'default'         => '',
	'active_callback' => [
		[
			'setting'  => 'penci_home_layout',
			'operator' => 'in',
			'value'    => [ 'grid-boxed', 'grid-boxed-2', 'masonry', 'grid' ],
		]
	],
);
$options[] = array(
	'id'              => 'penci_home_grid_mcol',
	'label'           => __( 'Grid Columns on Mobile', 'soledad' ),
	'type'            => 'soledad-fw-select',
	'choices'         => array(
		''  => __( 'Default', 'soledad' ),
		'1' => __( '1 Column', 'soledad' ),
		'2' => __( '2 Columns', 'soledad' ),
	),
	'default'         => '',
	'sanitize'        => 'penci_sanitize_choices_field',
	'active_callback' => [
		[
			'setting'  => 'penci_home_layout',
			'operator' => 'in',
			'value'    => [ 'grid-boxed', 'grid-boxed-2', 'masonry', 'grid' ],
		]
	],
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_text_field',
	'label'       => __( 'Custom Heading Title of Latest Posts Section', 'soledad' ),
	'id'          => 'penci_home_title',
	'description' => __( 'You can check <a class="wp-customizer-link" href="https://imgresources.s3.amazonaws.com/heading-title-latest-posts.png" target="_blank">this image</a> to know what\'s "Heading Title of Latest Posts Section".<br>If you want hide it, let empty this option', 'soledad' ),
	'type'        => 'soledad-fw-text',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Exclude specific categories from latest posts on Homepage', 'soledad' ),
	'id'          => 'penci_home_exclude_cat',
	'description' => __( 'Example: if you do not want all posts in categories: Fashion, Life Style show on your latest posts on homepage. You can fill slug of the categories here: fashion, life-style. You can see <a class="wp-customizer-link" rel="nofollow" href="https://pencidesign.net/soledad/soledad-document/assets/images/magazine-2.png" target="_blank">this image</a> to understand what is slug', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'default'  => '10',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Amount of Posts Shown Per Page on Homepage', 'soledad' ),
	'id'       => 'penci_home_lastest_posts_numbers',
	'ids'      => array(
		'desktop' => 'penci_home_lastest_posts_numbers',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '10',
		),
	),
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Latest Posts On Homepage', 'soledad' ),
	'id'       => 'penci_hide_latest_post_homepage',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Load More Button on Homepage', 'soledad' ),
	'id'       => 'penci_page_navigation_ajax',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Infinite Scroll on Homepage', 'soledad' ),
	'id'       => 'penci_page_navigation_scroll',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '6',
	'sanitize' => 'absint',
	'label'    => __( 'Number of Posts for Each Time Load More Posts', 'soledad' ),
	'id'       => 'penci_number_load_more',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_number_load_more',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '6',
		),
	),
);
$options[] = array(
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Sidebar On Homepage', 'soledad' ),
	'id'       => 'penci_sidebar_home',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Left Sidebar On Homepage', 'soledad' ),
	'id'       => 'penci_left_sidebar_home',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Two Sidebars On Homepage', 'soledad' ),
	'id'       => 'penci_two_sidebar_home',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Add Meta Description for Homepage', 'soledad' ),
	'description' => __( 'If you\'re using a SEO plugin, maybe it already added a meta description. So, you don\'t need to use this option anymore.', 'soledad' ),
	'id'          => 'penci_home_metadesc',
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'default'     => 'main-sidebar',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Sidebar for Homepage', 'soledad' ),
	'id'          => 'penci_sidebar_name_home',
	'description' => __( 'If sidebar your choice is empty, will display Main Sidebar', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => get_list_custom_sidebar_option()
);
$options[] = array(
	'default'     => 'main-sidebar-left',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Sidebar Left for Homepage', 'soledad' ),
	'id'          => 'penci_sidebar_left_name_home',
	'description' => __( 'If sidebar your choice is empty, will display Main Sidebar Left. This option just use when you enable 2 sidebars for Homepage', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => get_list_custom_sidebar_option()
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'sanitize_text_field',
	'label'       => __( 'Add More &lt;H1&gt; Tag for Homepage', 'soledad' ),
	'id'          => 'penci_home_h1content',
	'description' => __( 'Write content for &lt;H1&gt; tag here', 'soledad' ),
	'type'        => 'soledad-fw-text',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'In-Feed Ads on Latest Posts', 'soledad' ),
	'id'       => 'penci_heading_infeed_ads_home',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '3',
	'sanitize' => 'absint',
	'label'    => __( 'Insert In-feed Ads Code After Every How Many Posts?', 'soledad' ),
	'id'       => 'penci_infeedads_home_num',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_infeedads_home_num',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => '',
			'default' => '3',
		),
	),
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'In-feed Ads Code/HTML', 'soledad' ),
	'description' => __( 'Please use normal responsive ads here to get the best results - the in-feed ads can\'t work with auto-ads because auto-ads will randomly place your ads on random places on the pages.', 'soledad' ),
	'id'          => 'penci_infeedads_home_code',
	'type'        => 'soledad-fw-code',
	'code_type'   => 'text/html',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'In-feed Ads Layout Type', 'soledad' ),
	'id'       => 'penci_infeedads_home_layout',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''     => __( 'Follow Current Layout', 'soledad' ),
		'full' => __( 'Full Width', 'soledad' ),
	)
);

return $options;
