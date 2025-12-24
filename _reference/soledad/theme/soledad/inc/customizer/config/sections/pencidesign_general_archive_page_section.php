<?php
$options   = [];
$options[] = array(
	'label'    => __( 'Category, Tag, Search, Archive Layout', 'soledad' ),
	'id'       => 'penci_archive_layout',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'standard'         => __( 'Standard Posts', 'soledad' ),
		'classic'          => __( 'Classic Posts', 'soledad' ),
		'overlay'          => __( 'Overlay Posts', 'soledad' ),
		'featured'         => __( 'Featured Boxed Posts', 'soledad' ),
		'grid'             => __( 'Grid Posts', 'soledad' ),
		'grid-boxed'       => __( 'Grid Boxed', 'soledad' ),
		'grid-boxed-2'     => __( 'Grid Boxed 2', 'soledad' ),
		'grid-boxed-3'     => __( 'Grid Boxed 3', 'soledad' ),
		'grid-boxed-4'     => __( 'Grid Boxed 4', 'soledad' ),
		'grid-boxed-5'     => __( 'Grid Boxed 5', 'soledad' ),
		'grid-2'           => __( 'Grid 2 Columns Posts', 'soledad' ),
		'masonry'          => __( 'Grid Masonry Posts', 'soledad' ),
		'masonry-2'        => __( 'Grid Masonry 2 Columns Posts', 'soledad' ),
		'list'             => __( 'List Posts', 'soledad' ),
		'list-boxed'       => __( 'List Boxed', 'soledad' ),
		'list-boxed-2'     => __( 'List Boxed 2', 'soledad' ),
		'list-boxed-3'     => __( 'List Boxed 3', 'soledad' ),
		'timeline'         => __( 'Timeline', 'soledad' ),
		'small-list'       => __( 'Small List Posts', 'soledad' ),
		'boxed-1'          => __( 'Boxed Posts Style 1', 'soledad' ),
		'boxed-2'          => __( 'Boxed Posts Style 2', 'soledad' ),
		'mixed'            => __( 'Mixed Posts', 'soledad' ),
		'mixed-2'          => __( 'Mixed Posts Style 2', 'soledad' ),
		'mixed-3'          => __( 'Mixed Posts Style 3', 'soledad' ),
		'mixed-4'          => __( 'Mixed Posts Style 4', 'soledad' ),
		'photography'      => __( 'Photography Posts', 'soledad' ),
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
	),
	'default'  => 'standard',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'id'              => 'penci_archive_grid_col',
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
			'setting'  => 'penci_archive_layout',
			'operator' => 'in',
			'value'    => [ 'grid-boxed', 'grid-boxed-2','grid-boxed-3', 'masonry', 'grid' ],
		]
	],
);
$options[] = array(
	'id'              => 'penci_archive_grid_mcol',
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
			'setting'  => 'penci_archive_layout',
			'operator' => 'in',
			'value'    => [ 'grid-boxed', 'grid-boxed-2', 'grid-boxed-3', 'masonry', 'grid' ],
		]
	],
);
/* Archive Featured Settings */
$options[] = array(
	'label'    => esc_html__( 'Archive Builder Templates', 'soledad' ),
	'id'       => 'penci_heading_archi_template',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field'
);

$archive_layout     = [];
$archive_layout[''] = __( 'Default Template', 'soledad' );
$archive_layouts    = get_posts( [
	'post_type'      => 'archive-template',
	'posts_per_page' => - 1,
	'meta_query'     => array(
		'relation' => 'OR',
		array(
			'key'     => 'penci_desktop_page_id',
			'compare' => 'NOT EXISTS',
		),
		array(
			'key'     => 'penci_desktop_page_id',
			'value'   => '',
			'compare' => '=',
		),
	),
] );
foreach ( $archive_layouts as $alayout ) {
	$archive_layout[ $alayout->post_name ] = $alayout->post_title;
}

$options[] = array(
	'id'          => 'penci_archive_cat_template',
	'label'       => __( 'Select Builder Template for Categories Pages', 'soledad' ),
	'description' => __( 'To select a custom template for each category, go to Dashboard > Posts and edit the category for which you want to change the template. You can add and edit a category template on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=archive-template' ) ) . '">this page</a>', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => $archive_layout,
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field'
);

$options[] = array(
	'id'          => 'penci_archive_tag_template',
	'label'       => __( 'Select Builder Template for Tags Pages', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => $archive_layout,
	'default'     => '',
	'description' => __( 'You can add new and edit a tag template on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=archive-template' ) ) . '">this page</a>', 'soledad' ),
	'sanitize'    => 'penci_sanitize_choices_field'
);

$options[] = array(
	'id'          => 'penci_archive_author_template',
	'label'       => __( 'Select Builder Template for Author Pages', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => $archive_layout,
	'default'     => '',
	'description' => __( 'You can add new and edit a author template on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=archive-template' ) ) . '">this page</a>', 'soledad' ),
	'sanitize'    => 'penci_sanitize_choices_field'
);

$options[] = array(
	'id'          => 'penci_archive_date_template',
	'label'       => __( 'Select Builder Template for Day/Times Pages', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => $archive_layout,
	'default'     => '',
	'description' => __( 'You can add new and edit a day/times archive template on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=archive-template' ) ) . '">this page</a>', 'soledad' ),
	'sanitize'    => 'penci_sanitize_choices_field'
);

$options[] = array(
	'id'          => 'penci_archive_search_template',
	'label'       => __( 'Select Builder Template for Search Result Page', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => $archive_layout,
	'default'     => '',
	'description' => __( 'You can add new and edit a search result template on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=archive-template' ) ) . '">this page</a>', 'soledad' ),
	'sanitize'    => 'penci_sanitize_choices_field'
);

$penci_featured_cat_layout = [
	''                         => esc_html__( 'None', 'soledad' ),
	'style-1 penci-grid-col-2' => esc_html__( 'Grid 2 Columns', 'soledad' ),
	'style-1 penci-grid-col-3' => esc_html__( 'Grid 3 Columns', 'soledad' ),
	'style-1 penci-grid-col-4' => esc_html__( 'Grid 4 Columns', 'soledad' ),
	'style-1 penci-grid-col-5' => esc_html__( 'Grid 5 Columns', 'soledad' ),
	'style-3'                  => esc_html__( 'Featured 3', 'soledad' ),
	'style-4'                  => esc_html__( 'Featured 4', 'soledad' ),
	'style-5'                  => esc_html__( 'Featured 5', 'soledad' ),
	'style-6'                  => esc_html__( 'Featured 6', 'soledad' ),
	'style-7'                  => esc_html__( 'Featured 7', 'soledad' ),
	'style-8'                  => esc_html__( 'Featured 8', 'soledad' ),
	'style-9'                  => esc_html__( 'Featured 9', 'soledad' ),
	'style-10'                 => esc_html__( 'Featured 10', 'soledad' ),
	'style-11'                 => esc_html__( 'Featured 11', 'soledad' ),
	'style-12'                 => esc_html__( 'Featured 12', 'soledad' ),
	'style-13'                 => esc_html__( 'Featured 13', 'soledad' ),
	'style-14'                 => esc_html__( 'Featured 14', 'soledad' ),
	'style-15'                 => esc_html__( 'Featured 15', 'soledad' ),
	'style-16'                 => esc_html__( 'Featured 16', 'soledad' ),
	'style-17'                 => esc_html__( 'Featured 17', 'soledad' ),
	'style-18'                 => esc_html__( 'Featured 18', 'soledad' ),
	'style-19'                 => esc_html__( 'Featured 19', 'soledad' ),
	'style-20'                 => esc_html__( 'Featured 20', 'soledad' ),
	'style-21'                 => esc_html__( 'Featured 21', 'soledad' ),
	'style-22'                 => esc_html__( 'Featured 22', 'soledad' ),
];
/* Archive Featured Settings */
$options[] = array(
	'label'    => esc_html__( 'Show Featured Posts', 'soledad' ),
	'id'       => 'penci_heading_featured_archi',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field'
);
/* Category Featured */
$options[] = array(
	'id'       => 'penci_cat_featured_layout',
	'label'    => __( 'Category Pages Featured Style', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => $penci_featured_cat_layout,
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_category_featured_mheight',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'       => __( 'Height for Featured Styles', 'soledad' ),
	'description' => __( 'for Featured Posts on Category Pages', 'soledad' ),
	'id'          => 'penci_category_featured_height',
	'type'        => 'soledad-fw-size',
	'sanitize'    => 'absint',
	'ids'         => array(
		'desktop' => 'penci_category_featured_height',
		'mobile'  => 'penci_category_featured_mheight',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'type'        => 'soledad-fw-size',
	'id'          => 'penci_cat_featured_theight',
	'sanitize'    => 'absint',
	'label'       => __( 'Height for Featured Styles on Tablet', 'soledad' ),
	'description' => __( 'for Featured Posts on Category Pages', 'soledad' ),
	'ids'         => array(
		'desktop' => 'penci_cat_featured_theight',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
/* Tags Featured */
$options[] = array(
	'id'       => 'penci_tag_featured_layout',
	'label'    => __( 'Tag Pages Featured Style', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => $penci_featured_cat_layout,
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_tag_featured_mheight',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Height for Featured Styles', 'soledad' ),
	'id'       => 'penci_tag_featured_height',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_tag_featured_height',
		'mobile'  => 'penci_tag_featured_mheight',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'id'          => 'penci_tag_featured_theight',
	'type'        => 'soledad-fw-size',
	'label'       => __( 'Height for Featured Styles on Tablet', 'soledad' ),
	'description' => __( 'for Featured Posts on Tags Pages', 'soledad' ),
	'ids'         => array(
		'desktop' => 'penci_tag_featured_theight',
	),
	'choices'     => array(
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
	'id'       => 'penci_arf_orderby',
	'label'    => __( 'Featured Posts Sort By', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		''              => __( 'Published Date', 'soledad' ),
		'modified'      => __( 'Modified Date', 'soledad' ),
		'comment_count' => __( 'Comment Count', 'soledad' ),
		'popular'       => __( 'Most Viewed Posts All Time', 'soledad' ),
		'popular_day'   => __( 'Most Viewed Posts Once Daily', 'soledad' ),
		'popular7'      => __( 'Most Viewed Posts Once Weekly', 'soledad' ),
		'popular_month' => __( 'Most Viewed Posts Once a Month', 'soledad' ),
	],
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'id'       => 'penci_arf_sortby',
	'label'    => __( 'Featured Posts Order By', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'desc' => __( 'DESC', 'soledad' ),
		'asc'  => __( 'ASC', 'soledad' ),
	],
	'default'  => 'desc',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'id'       => 'penci_arf_img_ratio',
	'type'     => 'soledad-fw-text',
	'label'    => __( 'Image Ratio for Grid Layout (Unit is %. Eg: 50.5)', 'soledad' ),
	'default'  => '',
	'sanitize' => 'penci_sanitize_text_field',
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_arf_mheight',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Height for Featured Styles', 'soledad' ),
	'id'       => 'penci_arf_height',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_arf_height',
		'mobile'  => 'penci_arf_mheight',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'    => __( 'Height for Featured Styles on Tablet', 'soledad' ),
	'id'       => 'penci_arf_theight',
	'type'     => 'soledad-fw-size',
	'default'  => '',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_arf_theight',
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
	'label'    => __( 'Gap Between Featured Posts', 'soledad' ),
	'id'       => 'penci_arf_gap',
	'type'     => 'soledad-fw-size',
	'default'  => '4',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_arf_gap',
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
$options[] = array(
	'label'    => __( 'Custom Title Words Length', 'soledad' ),
	'id'       => 'penci_arf_titlength',
	'type'     => 'soledad-fw-size',
	'default'  => '',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_arf_titlength',
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
$options[] = array(
	'label'    => __( 'Hide Categories', 'soledad' ),
	'id'       => 'penci_arcf_cat',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Hide Post Date', 'soledad' ),
	'id'       => 'penci_arcf_date',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Show Post Author', 'soledad' ),
	'id'       => 'penci_arcf_author',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Show Comments Count', 'soledad' ),
	'id'       => 'penci_arcf_cm',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Show Views Count', 'soledad' ),
	'id'       => 'penci_arcf_view',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Show Reading Time', 'soledad' ),
	'id'       => 'penci_arcf_reading',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Hide Categories on Mobile', 'soledad' ),
	'id'       => 'penci_arcf_mcat',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Hide Post Meta on Mobile', 'soledad' ),
	'id'       => 'penci_arcf_mmeta',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Google Adsense/Custom HTML Code Display Below Featured Posts', 'soledad' ),
	'id'       => 'penci_arcf_adbelow',
	'type'     => 'soledad-fw-textarea',
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field'
);
$options[] = array(
	'label'       => __( 'Latest Posts Heading Title Below the Featured Area', 'soledad' ),
	'description' => __( 'Replace your category/tag name with {name} string.', 'soledad' ),
	'type'        => 'soledad-fw-text',
	'id'          => 'penci_arf_title',
	'default'     => 'Latest in {name}',
	'sanitize'    => 'penci_sanitize_choices_field'
);
$options[] = array(
	'id'       => 'penci_arf_title_style',
	'label'    => __( 'Select Latest Posts Title Heading Style', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'choices'  => array(
		''                  => __( 'Follow Title Box Style', 'soledad' ),
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
	)
);
/* Font size & color */
$options[] = array(
	'id'       => 'penci_heading_featured_fzc',
	'label'    => esc_html__( 'Featured Posts Font Size & Colors', 'soledad' ),
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field'
);
$options[] = array(
	'id'       => 'penci_arf_catfs',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Post Categories', 'soledad' ),
	'default'  => '',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_arf_catfs',
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
	'label'    => '',
	'id'       => 'penci_arf_t_mfs',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Big Grid Font Size for Post Title', 'soledad' ),
	'id'       => 'penci_arf_t_fs',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_arf_t_fs',
		'mobile'  => 'penci_arf_t_mfs',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'    => '',
	'id'       => 'penci_arf_t_bmfs',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Post Title on Big Items', 'soledad' ),
	'id'       => 'penci_arf_t_bfs',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_arf_t_bfs',
		'mobile'  => 'penci_arf_t_bmfs',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'    => __( 'Big Grid Font Size for Post Meta', 'soledad' ),
	'id'       => 'penci_arf_meta_fs',
	'type'     => 'soledad-fw-size',
	'default'  => '',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_arf_meta_fs',
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
/* Color */
$options[] = array(
	'id'       => 'penci_arf_cat_cl',
	'label'    => __( 'Post Categories Color', 'soledad' ),
	'type'     => 'soledad-fw-color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color'
);
$options[] = array(
	'id'       => 'penci_arf_cat_hcl',
	'label'    => __( 'Post Categories Hover Color', 'soledad' ),
	'type'     => 'soledad-fw-color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color'
);
$options[] = array(
	'id'       => 'penci_arf_t_cl',
	'label'    => __( 'Post Title Color', 'soledad' ),
	'type'     => 'soledad-fw-color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color'
);
$options[] = array(
	'label'    => __( 'Post Title Hover Color', 'soledad' ),
	'id'       => 'penci_arf_t_hcl',
	'type'     => 'soledad-fw-color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color'
);
$options[] = array(
	'label'    => __( 'Post Meta Color', 'soledad' ),
	'id'       => 'penci_arf_meta_cl',
	'type'     => 'soledad-fw-color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color'
);
$options[] = array(
	'label'    => __( 'Post Meta Links Color', 'soledad' ),
	'id'       => 'penci_arf_meta_lcl',
	'type'     => 'soledad-fw-color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color'
);
$options[] = array(
	'label'    => __( 'Post Meta Links Hover Color', 'soledad' ),
	'id'       => 'penci_arf_meta_hcl',
	'type'     => 'soledad-fw-color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color'
);
// End Category Featured
$options[] = array(
	'label'    => esc_html__( 'Other Settings', 'soledad' ),
	'id'       => 'penci_heading_other_archi',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field'
);
$options[] = array(
	'label'    => __( 'Hide Category Description', 'soledad' ),
	'id'       => 'penci_archive_hide_desc',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Select Posts Order on Archive Pages', 'soledad' ),
	'id'       => 'penci_general_show_post_order',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'label'    => __( 'Show Date Filter on Archive Pages', 'soledad' ),
	'id'       => 'penci_archive_show_date_filter',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Enable Animation on Scroll', 'soledad' ),
	'id'       => 'penci_archive_enable_animation',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'label'    => __( 'Enable Underline Color for Post Titles on Hover', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_archive_hover_underline',
);
$options[] = array(
	'label'    => __( 'Show Category Number Count', 'soledad' ),
	'id'       => 'penci_archive_show_cat_count',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Enable Link on Excerpt Content.', 'soledad' ),
	'description' => __( 'Navigate to the post when clicking on the excerpt content.', 'soledad' ),
	'id'          => 'penci_archive_excerpt_link',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Enable Load More Button for Categories, Tags, Search, Archive Pages', 'soledad' ),
	'id'       => 'penci_archive_nav_ajax',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Enable Infinite Scroll for Categories, Tags, Search, Archive Pages', 'soledad' ),
	'id'       => 'penci_archive_nav_scroll',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Enable AJAX Numeric/Next-Prev Pagination', 'soledad' ),
	'description' => __( 'You can enable the numbered pagination feature through General > General Settings > Enable Page Navigation Numbers', 'soledad' ),
	'id'          => 'penci_archive_ajax_navigation',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Number of Posts for Each Time Load More Posts', 'soledad' ),
	'type'     => 'soledad-fw-size',
	'id'       => 'penci_arc_number_load_more',
	'default'  => '6',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_arc_number_load_more',
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
	'label'    => __( 'Move Description of Category, Tag, Archive Pages Below Post Listings', 'soledad' ),
	'id'       => 'penci_archive_move_desc',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Show Child Categories on Single Category Page', 'soledad' ),
	'id'       => 'penci_archive_show_sub',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Child Categories Columns', 'soledad' ),
	'id'       => 'penci_archive_show_sub_col',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'2'    => '2 Columns',
		'3'    => '3 Columns',
		'4'    => '4 Columns',
		'5'    => '5 Columns',
	),
	'default'  => '3',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Show Child Categories Description?', 'soledad' ),
	'id'       => 'penci_archive_show_sub_desc',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Disable Auto Collapse Description Content', 'soledad' ),
	'id'       => 'penci_archive_disable_desc_collapse',
	'type'     => 'soledad-fw-toggle',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Category, Tags, Archive Title Align', 'soledad' ),
	'id'       => 'penci_archive_titlealign',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'default' => 'Default',
		'left'    => 'Left',
		'right'   => 'Right',
		'center'  => 'Center',
	),
	'default'  => 'default',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Category, Tags, Archive Description Align', 'soledad' ),
	'id'       => 'penci_archive_descalign',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''       => 'Default',
		'left'   => 'Left',
		'right'  => 'Right',
		'center' => 'Center',
	),
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Enable Sidebar On Archives', 'soledad' ),
	'id'       => 'penci_sidebar_archive',
	'type'     => 'soledad-fw-toggle',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Hide Sidebar On Mobile', 'soledad' ),
	'id'       => 'penci_sidebar_archive_mobile',
	'type'     => 'soledad-fw-toggle',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Enable Left Sidebar On Archives', 'soledad' ),
	'id'       => 'penci_left_sidebar_archive',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Enable Two Sidebars On Archives', 'soledad' ),
	'id'       => 'penci_two_sidebar_archive',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Remove "Category:" Words on Category Pages', 'soledad' ),
	'id'       => 'penci_remove_cat_words',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Remove "Tag:" Words on Tag Pages', 'soledad' ),
	'id'       => 'penci_remove_tag_words',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Custom Sidebar Display on Category Pages', 'soledad' ),
	'id'          => 'penci_sidebar_name_category',
	'description' => __( 'If sidebar your choice is empty, will display Main Sidebar', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => get_list_custom_sidebar_option(),
	'default'     => 'main-sidebar',
	'sanitize'    => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'       => __( 'Custom Sidebar Left Display on Category Pages', 'soledad' ),
	'id'          => 'penci_sidebar_left_name_category',
	'description' => __( 'If the sidebar you choose is empty, the Main Sidebar Left will be displayed. This option is only used when you enable 2 sidebars for archive pages.', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => get_list_custom_sidebar_option(),
	'default'     => 'main-sidebar-left',
	'sanitize'    => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'       => __( 'Google Adsense/Custom HTML Code to Display Above Posts Layout for Archive Pages', 'soledad' ),
	'id'          => 'penci_archive_ad_above',
	'description' => __( 'You can display Google AdSense or custom HTML code above posts on category, tag, search, and archive pages by using this option.', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field'
);
$options[] = array(
	'label'       => __( 'Google Adsense/Custom HTML Code to Display Below Posts Layout for Archive Pages', 'soledad' ),
	'id'          => 'penci_archive_ad_below',
	'description' => __( 'You can display Google AdSense or custom HTML code below posts on category, tag, search, and archive pages by using this option', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field'
);
$options[] = array(
	'label'    => esc_html__( 'In-Feed Ads', 'soledad' ),
	'id'       => 'penci_heading_infeed_ads_archi',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field'
);
$options[] = array(
	'label'    => __( 'Insert In-feed Ads Code After Every How Many Posts?', 'soledad' ),
	'id'       => 'penci_infeedads_archi_num',
	'type'     => 'soledad-fw-size',
	'default'  => '3',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_infeedads_archi_num',
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
	'label'       => __( 'In-feed Ads Code/HTML', 'soledad' ),
	'description' => __( 'Please use normal responsive ads here to get the best results. In-feed ads cannot work with auto-ads because auto-ads will randomly place your ads in random places on the pages.', 'soledad' ),
	'id'          => 'penci_infeedads_archi_code',
	'type'        => 'soledad-fw-textarea',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field'
);
$options[] = array(
	'label'    => __( 'In-feed Ads Layout Type', 'soledad' ),
	'id'       => 'penci_infeedads_archi_layout',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''     => __( 'Follow Current Layout', 'soledad' ),
		'full' => __( 'Full Width', 'soledad' ),
	),
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field'
);

return $options;
