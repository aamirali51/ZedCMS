<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "pc_single_related_posts",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Post Builder' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pc_single_relatedposts/frontend.php',
	'weight'        => 910,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Post Builder - Related Posts', 'soledad' ),
	'description'   => 'Post Builder - Related Posts',
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Related Heading Text', 'soledad' ),
			'param_name'       => 'penci_post_related_text',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Related Posts Carousel Auto Play', 'soledad' ),
			'param_name'       => 'penci_post_related_autoplay',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Number of Related Posts', 'soledad' ),
			'param_name'       => 'penci_numbers_related_post',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Order Related Posts By', 'soledad' ),
			'param_name'       => 'penci_related_orderby',
			'value'            => array(
				'Random Posts'                   => 'rand',
				'Published Date'                 => 'date',
				'Post ID'                        => 'ID',
				'Modified Date'                  => 'modified',
				'Post Title'                     => 'title',
				'Comment Count'                  => 'comment_count',
				'Most Viewed Posts All Time'     => 'popular',
				'Most Viewed Posts Daily'     	 => 'popular_day',
				'Most Viewed Posts Once Weekly'  => 'popular7',
				'Most Viewed Posts Once a Month' => 'popular_month',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Sort Order Related Posts', 'soledad' ),
			'param_name'       => 'penci_related_sort_order',
			'value'            => array(
				'DESC' => 'Descending',
				'ASC'  => 'Ascending',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_only_number',
			'heading'          => esc_html__( 'Title Length', 'soledad' ),
			'param_name'       => 'penci_related_posts_title_length',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Related Posts By', 'soledad' ),
			'param_name'       => 'penci_related_by',
			'value'            => array(
				'Categories'                                              => 'categories',
				'Tags'                                                    => 'tags',
				'Primary Category from "Yoast SEO" or "Rank Math" plugin' => 'primary_cat',
			),
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Make Related Posts Display in a Grid Layout ( not Slider )', 'soledad' ),
			'param_name'       => 'penci_post_related_grid',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Dots On Carousel Related Posts', 'soledad' ),
			'param_name'       => 'penci_post_related_dots',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Enable Next/Prev Button On Carousel Related Posts', 'soledad' ),
			'param_name'       => 'penci_post_related_arrows',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Enable Posts Format Icons in Related Posts', 'soledad' ),
			'param_name'       => 'penci_post_related_icons',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'penci_switch',
			'heading'          => esc_html__( 'Hide Post Date on Related Posts', 'soledad' ),
			'param_name'       => 'penci_hide_date_related',
			'edit_field_class' => 'vc_col-sm-6'
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Color for Post Title', 'soledad' ),
			'param_name'       => 'post_title_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Hover Color for Post Title', 'soledad' ),
			'param_name'       => 'post_title_hcolor',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'       => 'google_fonts',
			'group'      => $group_color,
			'param_name' => 'post_title_font',
			'value'      => '',
		),
		array(
			'type'       => 'penci_responsive_sizes',
			'param_name' => 'post_title_size',
			'heading'    => __( 'Font Size', 'soledad' ),
			'suffix'     => 'px',
			'min'        => 1,
			'group'      => $group_color,
		),
		array(
			'type'             => 'colorpicker',
			'heading'          => esc_html__( 'Color for Post Date', 'soledad' ),
			'param_name'       => 'post_date_color',
			'group'            => $group_color,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'type'       => 'google_fonts',
			'group'      => $group_color,
			'param_name' => 'post_date_font',
			'value'      => '',
		),
		array(
			'type'       => 'penci_responsive_sizes',
			'param_name' => 'post_date_size',
			'heading'    => __( 'Font Size', 'soledad' ),
			'suffix'     => 'px',
			'min'        => 1,
			'group'      => $group_color,
		),
		array(
			'type'       => 'penci_responsive_sizes',
			'param_name' => 'post_img_ratio',
			'heading'    => __( 'Custom Featured Image Ratio', 'soledad' ),
			'suffix'     => 'px',
			'min'        => 1,
			'group'      => $group_color,
		),
	), Penci_Vc_Params_Helper::extra_params() )
) );
