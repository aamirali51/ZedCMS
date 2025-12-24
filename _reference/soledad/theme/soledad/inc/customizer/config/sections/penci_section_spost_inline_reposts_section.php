<?php
$options   = [];
$options[] = array(
	'sanitize'    => 'sanitize_text_field',
	'label'       => esc_html__( 'Inline Related Posts Before/After Content', 'soledad' ),
	'description' => __( 'You can check <a class="wp-customizer-link" href="https://imgresources.s3.amazonaws.com/inline_related_posts.png" target="_blank">this image</a> to understand what\'s Inline Related Posts', 'soledad' ),
	'id'          => 'penci_inlinerp_beaf_head',
	'type'        => 'soledad-fw-header',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Add Inline Related Posts Before/After Post Content?', 'soledad' ),
	'description' => __( 'After enabling it, maybe you need to refresh the customize or check on the single post page on the front-end to see how it works.', 'soledad' ),
	'id'          => 'penci_show_inlinerp',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''       => __( 'None', 'soledad' ),
		'before' => __( 'Before Post Content', 'soledad' ),
		'after'  => __( 'After Post Content', 'soledad' ),
		'be_af'  => __( 'Before & After Post Content', 'soledad' ),
	)
);
$options[] = array(
	'default'  => 'categories',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Display Inline Related Posts By:', 'soledad' ),
	'id'       => 'penci_inlinerp_by',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'categories'  => __( 'Same Categories', 'soledad' ),
		'tags'        => __( 'Same Tags', 'soledad' ),
		'primary_cat' => __( 'Same Primary Category from "Yoast SEO" or "Rank Math" plugin', 'soledad' ),
	)
);
$options[] = array(
	'default'  => 'rand',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Order Inline Related Posts By:', 'soledad' ),
	'id'       => 'penci_inlinerp_orderby',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'rand'          => __( 'Random Posts', 'soledad' ),
		'date'          => __( 'Published Date', 'soledad' ),
		'ID'            => __( 'Post ID', 'soledad' ),
		'modified'      => __( 'Modified Date', 'soledad' ),
		'title'         => __( 'Post Title', 'soledad' ),
		'comment_count' => __( 'Comment Count', 'soledad' ),
		'most_liked'    => __( 'Most Liked', 'soledad' ),
		'popular'       => __( 'Most Viewed Posts All Time', 'soledad' ),
		'popular_day'   => __( 'Most Viewed Posts Once Daily', 'soledad' ),
		'popular7'      => __( 'Most Viewed Posts Once Weekly', 'soledad' ),
		'popular_month' => __( 'Most Viewed Posts Once a Month', 'soledad' ),
	)
);
$options[] = array(
	'default'  => 'DESC',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Order Inline Related Posts:', 'soledad' ),
	'id'       => 'penci_inlinerp_order',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'DESC' => __( 'Descending Order', 'soledad' ),
		'ASC'  => __( 'Ascending  Order', 'soledad' ),
	)
);
$options[] = array(
	'default'  => 'list',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Select Style', 'soledad' ),
	'id'       => 'penci_inlinerp_style',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'list' => __( 'List', 'soledad' ),
		'grid' => __( 'Grid', 'soledad' ),
	)
);
$options[] = array(
	'default'  => 'none',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Inline Related Posts Float:', 'soledad' ),
	'id'       => 'penci_inlinerp_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'none'  => __( 'None', 'soledad' ),
		'left'  => __( 'Float To Left', 'soledad' ),
		'right' => __( 'Float To Right', 'soledad' ),
	)
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Inline Related Posts Inside Post Content', 'soledad' ),
	'id'       => 'penci_inlinerp_insert_head',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Add Inline Related Posts Inside Posts Content?', 'soledad' ),
	'description' => __( 'After enabling it, maybe you need to refresh the customize or check on the single post page on the front-end to see how it works.', 'soledad' ),
	'id'          => 'penci_show_inlinerp_inside',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''         => __( 'None', 'soledad' ),
		'repeat'   => __( 'After Each X Paragraphs - Repeat', 'soledad' ),
		'norepeat' => __( 'After X Paragraphs - No Repeat', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '4',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Add Inline Related Posts Inside Posts Content After How Many Paragraphs?', 'soledad' ),
	'id'       => 'penci_show_inlinerp_p',
	'ids'      => array(
		'desktop' => 'penci_show_inlinerp_p',
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
	'default'  => 'categories',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Display Inline Related Posts By:', 'soledad' ),
	'id'       => 'penci_inlinerpis_by',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'categories'  => __( 'Same Categories', 'soledad' ),
		'tags'        => __( 'Same Tags', 'soledad' ),
		'primary_cat' => __( 'Same Primary Category from "Yoast SEO" or "Rank Math" plugin', 'soledad' ),
	)
);
$options[] = array(
	'default'  => 'rand',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Order Inline Related Posts By:', 'soledad' ),
	'id'       => 'penci_inlinerpis_orderby',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'rand'          => __( 'Random Posts', 'soledad' ),
		'date'          => __( 'Published Date', 'soledad' ),
		'ID'            => __( 'Post ID', 'soledad' ),
		'modified'      => __( 'Modified Date', 'soledad' ),
		'title'         => __( 'Post Title', 'soledad' ),
		'comment_count' => __( 'Comment Count', 'soledad' ),
		'most_liked'    => __( 'Most Liked', 'soledad' ),
		'popular'       => __( 'Most Viewed Posts All Time', 'soledad' ),
		'popular7'      => __( 'Most Viewed Posts Once Weekly', 'soledad' ),
		'popular_month' => __( 'Most Viewed Posts Once a Month', 'soledad' ),
	)
);
$options[] = array(
	'default'  => 'DESC',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Order Inline Related Posts:', 'soledad' ),
	'id'       => 'penci_inlinerpis_order',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'DESC' => __( 'Descending Order', 'soledad' ),
		'ASC'  => __( 'Ascending  Order', 'soledad' ),
	)
);
$options[] = array(
	'default'  => 'list',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Select Style for Inline Related Posts Inside Post Content', 'soledad' ),
	'id'       => 'penci_inlinerp_style_insert',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'list' => 'List',
		'grid' => 'Grid',
	)
);
$options[] = array(
	'default'  => 'none',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Select Float for Inline Related Posts Inside Post Content:', 'soledad' ),
	'id'       => 'penci_inlinerp_align_insert',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'none'  => __( 'None', 'soledad' ),
		'left'  => __( 'Float To Left', 'soledad' ),
		'right' => __( 'Float To Right', 'soledad' ),
	)
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'General Settings', 'soledad' ),
	'id'       => 'penci_inlinerp_general_head',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide on Mobile', 'soledad' ),
	'id'       => 'penci_inlinerp_hide_mobile',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => '2',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Inline Related Posts Columns for Grid Style:', 'soledad' ),
	'description' => __( 'This option just applies when you select style is grid and "Inline Related Posts Float" is "None"', 'soledad' ),
	'id'          => 'penci_inlinerp_col',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'1' => __( '1 Column', 'soledad' ),
		'2' => __( '2 Column', 'soledad' ),
		'3' => __( '3 Column', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '6',
	'sanitize' => 'absint',
	'label'    => __( 'How Many Posts You Want to Display?', 'soledad' ),
	'id'       => 'penci_inlinerp_num',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_inlinerp_num',
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
	'default'  => 'left',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Heading Text Align', 'soledad' ),
	'id'       => 'penci_inlinerp_titalign',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'left'   => 'Left',
		'center' => 'Center',
		'right'  => 'Right',
	)
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove the Line Below the Heading Text', 'soledad' ),
	'id'       => 'penci_inlinerp_titleline',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Featured Image on Grid Style?', 'soledad' ),
	'id'       => 'penci_inlinerp_hide_thumb',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Featured Image on the Right Side for Grid Style?', 'soledad' ),
	'id'       => 'penci_inlinerp_thumb_right',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Post Date?', 'soledad' ),
	'id'       => 'penci_inlinerp_date',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Show Post Views?', 'soledad' ),
	'id'       => 'penci_inlinerp_views',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Font Sizes', 'soledad' ),
	'id'       => 'penci_inlinerp_fontsize_head',
	'type'     => 'soledad-fw-header',
);
/* Font Size */
$options[] = array(
	'label'    => '',
	'id'       => 'penci_inlinerp_fsheading_mobile',
	'type'     => 'soledad-fw-hidden',
	'sanitize' => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Heading Text', 'soledad' ),
	'id'       => 'penci_inlinerp_fsheading',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_inlinerp_fsheading',
		'mobile'  => 'penci_inlinerp_fsheading_mobile',
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
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Title', 'soledad' ),
	'id'       => 'penci_inlinerp_fstitle',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_inlinerp_fstitle',
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
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Title', 'soledad' ),
	'id'       => 'penci_inlinerp_fsmeta',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_inlinerp_fsmeta',
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
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Colors', 'soledad' ),
	'id'       => 'penci_inlinerp_colors_head',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Background Color', 'soledad' ),
	'id'       => 'penci_inlinerp_bg',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Borders Color', 'soledad' ),
	'id'       => 'penci_inlinerp_border',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Heading Text Color', 'soledad' ),
	'id'       => 'penci_inlinerp_cheading',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Color for the Line Below Heading Text', 'soledad' ),
	'id'       => 'penci_inlinerp_cline',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Title Color', 'soledad' ),
	'id'       => 'penci_inlinerp_ctitle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Title Hover Color', 'soledad' ),
	'id'       => 'penci_inlinerp_hctitle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Meta Color', 'soledad' ),
	'id'       => 'penci_inlinerp_hcmeta',
);

return $options;
