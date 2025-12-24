<?php
$options   = [];
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Featured Boxes', 'soledad' ),
	'id'       => 'penci_section_featured_boxes_cheading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Text on Featured Boxes', 'soledad' ),
	'id'       => 'penci_home_box_text_size',
	'ids'      => array(
		'desktop' => 'penci_home_box_text_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Popular Posts', 'soledad' ),
	'id'       => 'penci_section_popular_cheading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for "Popular Posts" heading', 'soledad' ),
	'id'       => 'penci_home_polular_fsectitle',
	'ids'      => array(
		'desktop' => 'penci_home_polular_fsectitle',
		'mobile'  => 'penci_home_polular_mfsectitle',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '18',
		),
		'mobile'  => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'id'       => 'penci_home_polular_mfsectitle',
	'default'  => '14',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-hidden',
	'label'    => __( 'Font Size for "Popular Posts" heading', 'soledad' ),
);
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Post Titles on Popular Posts', 'soledad' ),
	'id'       => 'penci_home_popular_post_font_size',
	'ids'      => array(
		'desktop' => 'penci_home_popular_post_font_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'default'  => '13',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Post Date on Popular Posts', 'soledad' ),
	'id'       => 'penci_home_popular_post_fdate',
	'ids'      => array(
		'desktop' => 'penci_home_popular_post_fdate',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '13',
		),
	),
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Home Title Box', 'soledad' ),
	'id'       => 'penci_section_home_titlebox_cheading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Home Title Box', 'soledad' ),
	'id'       => 'penci_featured_cat_size',
	'ids'      => array(
		'desktop' => 'penci_featured_cat_size',
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
	'label'    => esc_html__( 'Featured Categories', 'soledad' ),
	'id'       => 'penci_section_featured_cat_cheading',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Categories on Style 8', 'soledad' ),
	'id'       => 'penci_featuredcat_cat_size',
	'ids'      => array(
		'desktop' => 'penci_featuredcat_cat_size',
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
	'default'     => '13',
	'sanitize'    => 'absint',
	'type'        => 'soledad-fw-size',
	'label'       => __( 'Font Size for Post Meta', 'soledad' ),
	'description' => __( 'Include author name, date, comment count', 'soledad' ),
	'id'          => 'penci_featuredcat_meta_size',
	'ids'         => array(
		'desktop' => 'penci_featuredcat_meta_size',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '13',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for Post Excerpt', 'soledad' ),
	'id'       => 'penci_featuredcat_excerpt_size',
	'ids'      => array(
		'desktop' => 'penci_featuredcat_excerpt_size',
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
	'label'       => '',
	'description'=>'',
	'id'          => 'penci_featuredcat_title_size_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'General Font Size for Post Titles', 'soledad' ),
	'id'       => 'penci_featuredcat_title_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_featuredcat_title_size',
		'mobile'  => 'penci_featuredcat_title_size_mobile',
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
	'label'       => '',
	'description'=>'',
	'id'          => 'penci_featuredcat_smtitle_size',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Post Titles on Small Posts', 'soledad' ),
	'id'       => 'penci_featuredcat_smtitle_size_mobile',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_featuredcat_smtitle_size_mobile',
		'mobile'  => 'penci_featuredcat_smtitle_size',
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
	'label'       => '',
	'description'=>'',
	'id'          => 'penci_featuredcat4_title_size_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Post Titles on Style 4', 'soledad' ),
	'id'       => 'penci_featuredcat4_title_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_featuredcat4_title_size',
		'mobile'  => 'penci_featuredcat4_title_size_mobile',
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
	'label'       => '',
	'description'=>'',
	'id'          => 'penci_featuredcat12_title_size_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Post Titles on Style 12 & 13', 'soledad' ),
	'id'       => 'penci_featuredcat12_title_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_featuredcat12_title_size',
		'mobile'  => 'penci_featuredcat12_title_size_mobile',
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
	'label'       => '',
	'description'=>'',
	'id'          => 'penci_featuredcat14_ftitle_size_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Post Titles on First Post on Style 14', 'soledad' ),
	'id'       => 'penci_featuredcat14_ftitle_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_featuredcat14_ftitle_size',
		'mobile'  => 'penci_featuredcat14_ftitle_size_mobile',
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
	'label'       => '',
	'description'=>'',
	'id'          => 'penci_featuredcat14_title_size_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Font Size for Post Titles on Other Posts on Style 14', 'soledad' ),
	'id'       => 'penci_featuredcat14_title_size',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_featuredcat14_title_size',
		'mobile'  => 'penci_featuredcat14_title_size_mobile',
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
	'type'     => 'soledad-fw-size',
	'label'    => __( 'Font Size for "View All" Button', 'soledad' ),
	'id'       => 'penci_featuredcat_viewall_size',
	'ids'      => array(
		'desktop' => 'penci_featuredcat_viewall_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 300,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);

return $options;
