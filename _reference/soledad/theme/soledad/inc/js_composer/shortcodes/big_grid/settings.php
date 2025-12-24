<?php
$group_color  = 'Typo & Color';
$group_custom = 'Custom Grid Items';
vc_map( array(
	'base'          => 'penci_big_grid',
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Soledad' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/big_grid/frontend.php',
	'weight'        => 700,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Big Grid', 'soledad' ),
	'description'   => __( 'Posts Big Grid', 'soledad' ),
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Query Type:', 'soledad' ),
			'value'      => array(
				esc_html__( 'Base Post', 'soledad' ) => 'post',
				esc_html__( 'Custom', 'soledad' )    => 'custom',
			),
			'std'        => 'post',
			'param_name' => 'bgquery_type',
		),
		array(
			'type'        => 'loop',
			'heading'     => '',
			'param_name'  => 'build_query',
			'value'       => 'post_type:post|size:10',
			'settings'    => array(
				'size'      => array( 'value' => 10, 'hidden' => false ),
				'post_type' => array( 'value' => 'post', 'hidden' => false )
			),
			'description' => __( 'Create WordPress loop, to populate content from your site.', 'soledad' ),
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Select Style for This Slider', 'soledad' ),
			'value'      => array(
				esc_html__( 'Grid ( Default )', 'soledad' ) => 'style-1',
				esc_html__( 'Masonry', 'soledad' )          => 'style-2',
				esc_html__( 'Style 3', 'soledad' )          => 'style-3',
				esc_html__( 'Style 4', 'soledad' )          => 'style-4',
				esc_html__( 'Style 5', 'soledad' )          => 'style-5',
				esc_html__( 'Style 6', 'soledad' )          => 'style-6',
				esc_html__( 'Style 7', 'soledad' )          => 'style-7',
				esc_html__( 'Style 8', 'soledad' )          => 'style-8',
				esc_html__( 'Style 9', 'soledad' )          => 'style-9',
				esc_html__( 'Style 10', 'soledad' )         => 'style-10',
				esc_html__( 'Style 11', 'soledad' )         => 'style-11',
				esc_html__( 'Style 12', 'soledad' )         => 'style-12',
				esc_html__( 'Style 13', 'soledad' )         => 'style-13',
				esc_html__( 'Style 14', 'soledad' )         => 'style-14',
				esc_html__( 'Style 15', 'soledad' )         => 'style-15',
				esc_html__( 'Style 16', 'soledad' )         => 'style-16',
				esc_html__( 'Style 17', 'soledad' )         => 'style-17',
				esc_html__( 'Style 18', 'soledad' )         => 'style-18',
				esc_html__( 'Style 19', 'soledad' )         => 'style-19',
				esc_html__( 'Style 20', 'soledad' )         => 'style-20',
				esc_html__( 'Style 21', 'soledad' )         => 'style-21',
				esc_html__( 'Style 22', 'soledad' )         => 'style-22',
			),
			'std'        => 'style-1',
			'param_name' => 'style',
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Grid/Masonry Style Columns', 'soledad' ),
			'value'      => array(
				esc_html__( '1 Column', 'soledad' )  => '1',
				esc_html__( '2 Columns', 'soledad' ) => '2',
				esc_html__( '3 Columns', 'soledad' ) => '3',
				esc_html__( '4 Columns', 'soledad' ) => '4',
				esc_html__( '5 Columns', 'soledad' ) => '5',
				esc_html__( '6 Columns', 'soledad' ) => '6'
			),
			'std'        => '3',
			'param_name' => 'bg_columns',
			'dependency' => array( 'element' => 'style', 'value' => array( 'style-1', 'style-2' ) ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Grid/Masonry Style Columns on Tablet', 'soledad' ),
			'value'      => array(
				esc_html__( 'Default', 'soledad' )   => '',
				esc_html__( '1 Column', 'soledad' )  => '1',
				esc_html__( '2 Columns', 'soledad' ) => '2',
				esc_html__( '3 Columns', 'soledad' ) => '3',
				esc_html__( '4 Columns', 'soledad' ) => '4',
			),
			'std'        => '',
			'param_name' => 'bg_columns_tablet',
			'dependency' => array( 'element' => 'style', 'value' => array( 'style-1', 'style-2' ) ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Grid/Masonry Style Columns on Mobile', 'soledad' ),
			'value'      => array(
				esc_html__( '1 Column', 'soledad' )  => '1',
				esc_html__( '2 Columns', 'soledad' ) => '2',
				esc_html__( '3 Columns', 'soledad' ) => '3',
			),
			'std'        => '1',
			'param_name' => 'bg_columns_mobile',
			'dependency' => array( 'element' => 'style', 'value' => array( 'style-1', 'style-2' ) ),
		),
		array(
			'type'       => 'checkbox',
			'heading'    => __( 'Showing Post Data', 'soledad' ),
			'value'      => array(
				esc_html__( 'Categories', 'soledad' )   => 'cat',
				esc_html__( 'Title', 'soledad' )        => 'title',
				esc_html__( 'Author', 'soledad' )       => 'author',
				esc_html__( 'Date', 'soledad' )         => 'date',
				esc_html__( 'Comments', 'soledad' )     => 'comment',
				esc_html__( 'Views', 'soledad' )        => 'views',
				esc_html__( 'Reading Time', 'soledad' ) => 'reading',
			),
			'std'        => 'cat,title,author,date',
			'param_name' => 'bg_postmeta',
			'dependency' => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Custom Title Words Length', 'soledad' ),
			'std'        => 10,
			'param_name' => 'title_length',
			'dependency' => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Show Primary Category Only', 'soledad' ),
			'param_name'  => 'primary_cat',
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Hide Post Categories on Small Grid Items', 'soledad' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'param_name'  => 'hide_cat_small',
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Hide Post Meta( Author, Date.. ) on Small Grid Items', 'soledad' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'param_name'  => 'hide_meta_small',
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Hide Post Categories/Sub Title on Mobile', 'soledad' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'param_name'  => 'hide_subtitle_mobile',
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Hide Post Meta/Description on Mobile', 'soledad' ),
			'value'       => array( __( 'Yes', 'soledad' ) => 'yes' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'param_name'  => 'hide_desc_mobile',
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Show Read More Button', 'soledad' ),
			'value'       => array( __( 'Yes', 'soledad' ) => 'yes' ),
			'param_name'  => 'show_readmore',
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Hide Read More Button on Small Grid Items', 'soledad' ),
			'value'       => array( __( 'Yes', 'soledad' ) => 'yes' ),
			'param_name'  => 'hide_rm_small',
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Hide Read More Button on Mobile', 'soledad' ),
			'value'       => array( __( 'Yes', 'soledad' ) => 'yes' ),
			'param_name'  => 'hide_readmore_mobile',
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Show Post Format Icons', 'soledad' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'param_name'  => 'show_formaticon',
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'       => 'penci_only_number',
			'heading'    => __( 'Excerpt Length', 'soledad' ),
			'param_name' => 'excerpt_length',
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Hide Only Post Excerpt on Small Grid Items', 'soledad' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'param_name'  => 'hide_excerpt_small',
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Post Format Icon Position', 'soledad' ),
			'value'      => array(
				esc_html__( 'Top Right', 'soledad' )    => 'top-right',
				esc_html__( 'Top Left', 'soledad' )     => 'top-left',
				esc_html__( 'Bottom Right', 'soledad' ) => 'bottom-right',
				esc_html__( 'Bottom Left', 'soledad' )  => 'bottom-left',
				esc_html__( 'Center', 'soledad' )       => 'center',
			),
			'std'        => 'top-right',
			'param_name' => 'formaticon_pos',
			'dependency' => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Show Review Scores from Penci Review plugin', 'soledad' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'param_name'  => 'show_reviewpie',
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Review Scores Position', 'soledad' ),
			'value'      => array(
				esc_html__( 'Top Right', 'soledad' )    => 'top-right',
				esc_html__( 'Top Left', 'soledad' )     => 'top-left',
				esc_html__( 'Bottom Right', 'soledad' ) => 'bottom-right',
				esc_html__( 'Bottom Left', 'soledad' )  => 'bottom-left',
				esc_html__( 'Center', 'soledad' )       => 'center',
			),
			'std'        => 'top-left',
			'param_name' => 'reviewpie_pos',
			'dependency' => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Display One Column on Mobile?', 'soledad' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'param_name'  => 'onecol_mobile',
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Display Grid Items Same Height on Mobile?', 'soledad' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'no',
			'std'         => 'no',
			'param_name'  => 'sameh_mobile',
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Big Grid Content Position', 'soledad' ),
			'value'      => array(
				esc_html__( 'On Image', 'soledad' )    => 'on',
				esc_html__( 'Below Image', 'soledad' ) => 'below',
				esc_html__( 'Above Image', 'soledad' ) => 'above',
			),
			'std'        => 'on',
			'param_name' => 'bgcontent_pos',
		),
		array(
			'type'       => 'penci_only_number',
			'heading'    => __( 'Gap Between Grid & Masonry Items', 'soledad' ),
			'std'        => '',
			'param_name' => 'bg_gap',
			'dependency' => array( 'element' => 'style', 'value' => array( 'style-1', 'style-2' ) ),
		),
		array(
			'type'       => 'penci_only_number',
			'heading'    => __( 'Gap Between Items', 'soledad' ),
			'std'        => '',
			'param_name' => 'bg_othergap',
			'dependency' => array( 'element' => 'style', 'value_not_equal_to' => array( 'style-1', 'style-2' ) ),
		),
		array(
			'type'       => 'penci_only_number',
			'heading'    => __( 'Adjust Ratio of Images( Unit % )', 'soledad' ),
			'std'        => '',
			'param_name' => 'penci_img_ratio',
		),
		array(
			'type'       => 'penci_only_number',
			'heading'    => __( 'Custom Big Grid Height (Unit is px)', 'soledad' ),
			'std'        => '',
			'param_name' => 'bg_height',
		),
		array(
			'type'        => 'penci_switch',
			'heading'     => __( 'Disable Lazyload Images?', 'soledad' ),
			'true_state'  => 'yes',
			'false_state' => 'no',
			'default'     => 'yes',
			'std'         => 'yes',
			'param_name'  => 'disable_lazy',
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Page Navigation Style', 'soledad' ),
			'description' => __( 'Load More Posts Button & Infinite Scroll just works on frontend only.', 'soledad' ),
			'value'       => array(
				esc_html__( 'None', 'soledad' )                    => 'none',
				esc_html__( 'Page Navigation Numbers', 'soledad' ) => 'numbers',
				esc_html__( 'Load More Posts Button', 'soledad' )  => 'loadmore',
				esc_html__( 'Infinite Scroll', 'soledad' )         => 'scroll',
				esc_html__( 'Next/Previous', 'soledad' )           => 'nextprev',
			),
			'std'         => 'none',
			'param_name'  => 'paging',
			'dependency'  => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Page Navigation Align', 'soledad' ),
			'value'      => array(
				esc_html__( 'Center', 'soledad' ) => 'align-center',
				esc_html__( 'Left', 'soledad' )   => 'align-left',
				esc_html__( 'Right', 'soledad' )  => 'align-right',
			),
			'std'        => 'align-center',
			'param_name' => 'paging_align',
			'dependency' => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
		array(
			'type'       => 'penci_only_number',
			'heading'    => __( 'Margin Top for Page Navigation', 'soledad' ),
			'std'        => '',
			'param_name' => 'paging_matop',
			'dependency' => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
		),
	), Penci_Vc_Params_Helper::params_heading(), Penci_Vc_Params_Helper::params_heading_typo_color(),

		/* Custom */

		array(
			array(
				'type'             => 'textfield',
				'param_name'       => 'penci_separator_cs_items',
				'heading'          => __( 'Custom Grid Items', 'soledad' ),
				'description'      => __( 'Add your custom grid item here', 'soledad' ),
				'group'            => $group_custom,
				'dependency'       => array( 'element' => 'bgquery_type', 'value' => array( 'post' ) ),
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'       => 'param_group',
				'param_name' => 'biggrid_items',
				'heading'    => esc_html__( 'Custom Grid Items', 'soledad' ),
				'group'      => $group_custom,
				'dependency' => array( 'element' => 'bgquery_type', 'value' => array( 'custom' ) ),
				'value'      => urlencode( json_encode( array(
					array(
						'image'        => vc_asset_url( 'vc/vc_gitem_image.png' ),
						'sub_title'    => __( 'Sub Title', 'soledad' ),
						'title'        => __( 'Big Grid Item #1', 'soledad' ),
						'desc'         => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
						'button_text'  => __( 'Click Here', 'soledad' ),
						'custom_style' => false,
					),
					array(
						'image'        => vc_asset_url( 'vc/vc_gitem_image.png' ),
						'sub_title'    => __( 'Sub Title', 'soledad' ),
						'title'        => __( 'Big Grid Item #2', 'soledad' ),
						'desc'         => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
						'button_text'  => __( 'Click Here', 'soledad' ),
						'custom_style' => false,
					),
					array(
						'image'        => vc_asset_url( 'vc/vc_gitem_image.png' ),
						'sub_title'    => __( 'Sub Title', 'soledad' ),
						'title'        => __( 'Big Grid Item #3', 'soledad' ),
						'desc'         => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
						'button_text'  => __( 'Click Here', 'soledad' ),
						'custom_style' => false,
					),
					array(
						'image'        => vc_asset_url( 'vc/vc_gitem_image.png' ),
						'sub_title'    => __( 'Sub Title', 'soledad' ),
						'title'        => __( 'Big Grid Item #4', 'soledad' ),
						'desc'         => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
						'button_text'  => __( 'Click Here', 'soledad' ),
						'custom_style' => false,
					),
					array(
						'image'        => vc_asset_url( 'vc/vc_gitem_image.png' ),
						'sub_title'    => __( 'Sub Title', 'soledad' ),
						'title'        => __( 'Big Grid Item #5', 'soledad' ),
						'desc'         => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
						'button_text'  => __( 'Click Here', 'soledad' ),
						'custom_style' => false,
					),
					array(
						'image'        => vc_asset_url( 'vc/vc_gitem_image.png' ),
						'sub_title'    => __( 'Sub Title', 'soledad' ),
						'title'        => __( 'Big Grid Item #6', 'soledad' ),
						'desc'         => __( 'I am demo text. Edit grid items to edit me', 'soledad' ),
						'button_text'  => __( 'Click Here', 'soledad' ),
						'custom_style' => false,
					),
				) ) ),
				'params'     => array(
					array(
						'type'       => 'attach_image',
						'holder'     => 'img',
						'class'      => '',
						'heading'    => __( 'Select Image', 'soledad' ),
						'param_name' => 'image',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Sub title', 'soledad' ),
						'param_name' => 'sub_title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title', 'soledad' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'vc_link',
						'heading'    => __( 'Add Link for Title & Image', 'soledad' ),
						'param_name' => 'title_link',
					),
					array(
						'type'       => 'textarea',
						'heading'    => __( 'Description', 'soledad' ),
						'param_name' => 'desc',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Button Text', 'soledad' ),
						'param_name' => 'button_text',
					),
					array(
						'type'       => 'vc_link',
						'heading'    => __( 'Button Link', 'soledad' ),
						'param_name' => 'button_link',
					),

					/* Style */ array(
						'type'        => 'penci_switch',
						'heading'     => __( 'Custom Style ?', 'soledad' ),
						'param_name'  => 'custom_style',
						'true_state'  => 'yes',
						'false_state' => 'no',
						'default'     => 'yes',
						'std'         => 'yes',
					),

					array(
						'type'             => 'dropdown',
						'heading'          => __( 'Horizontal Position', 'soledad' ),
						'param_name'       => 'horizontal_position',
						'value'            => array(
							esc_html__( 'Center', 'soledad' ) => 'align-center',
							esc_html__( 'Left', 'soledad' )   => 'align-left',
							esc_html__( 'Right', 'soledad' )  => 'align-right',
						),
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-4',
					),

					array(
						'type'             => 'dropdown',
						'heading'          => __( 'Verical Position', 'soledad' ),
						'param_name'       => 'vertical_position',
						'value'            => array(
							esc_html__( 'Top', 'soledad' )    => 'top',
							esc_html__( 'Middle', 'soledad' ) => 'middle',
							esc_html__( 'Bottom', 'soledad' ) => 'bottom',
						),
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-4',
					),

					array(
						'type'             => 'dropdown',
						'heading'          => __( 'Text Align', 'soledad' ),
						'param_name'       => 'text_align',
						'std'              => 'align-left',
						'value'            => array(
							esc_html__( 'Center', 'soledad' ) => 'align-center',
							esc_html__( 'Left', 'soledad' )   => 'align-left',
							esc_html__( 'Right', 'soledad' )  => 'align-right',
						),
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-4',
					),

					array(
						'type'             => 'colorpicker',
						'heading'          => __( 'Sub Title Color', 'soledad' ),
						'param_name'       => 'subtitle_color',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'colorpicker',
						'heading'          => __( 'Title Color', 'soledad' ),
						'param_name'       => 'title_color',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'colorpicker',
						'heading'          => __( 'Title Hover Color', 'soledad' ),
						'param_name'       => 'title_hcolor',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'colorpicker',
						'heading'          => __( 'Description Color', 'soledad' ),
						'param_name'       => 'desc_color',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'colorpicker',
						'heading'          => __( 'Button Text Color', 'soledad' ),
						'param_name'       => 'button_color',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'colorpicker',
						'heading'          => __( 'Button Text Hover Color', 'soledad' ),
						'param_name'       => 'button_hcolor',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'colorpicker',
						'heading'          => __( 'Button Border Color', 'soledad' ),
						'param_name'       => 'button_border_color',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'colorpicker',
						'heading'          => __( 'Button Border Hover Color', 'soledad' ),
						'param_name'       => 'button_border_hcolor',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'colorpicker',
						'heading'          => __( 'Button Background Color', 'soledad' ),
						'param_name'       => 'button_bg_color',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'colorpicker',
						'heading'          => __( 'Button Background Hover Color', 'soledad' ),
						'param_name'       => 'button_bg_hcolor',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'textfield',
						'heading'          => __( 'Content Text Padding', 'soledad' ),
						'param_name'       => 'bgoverlay_padding',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),

					array(
						'type'             => 'textfield',
						'heading'          => __( 'Content Text Margin', 'soledad' ),
						'param_name'       => 'bgoverlay_margin',
						'dependency'       => array( 'element' => 'custom_style', 'value' => 'yes' ),
						'edit_field_class' => 'vc_col-sm-6',
					),
				),
			),
		),

		/* end custom */

		array(
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Custom Image Size', 'soledad' ),
				'param_name' => 'thumb_size',
				'std'        => 'penci-masonry-thumb',
				'group'      => 'Other',
				'value'      => Penci_Vc_Params_Helper::get_list_image_sizes( true ),
			),

			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Image Size for Big Items', 'soledad' ),
				'param_name' => 'bthumb_size',
				'std'        => 'penci-masonry-thumb',
				'group'      => 'Other',
				'value'      => Penci_Vc_Params_Helper::get_list_image_sizes( true ),
			),

			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Custom Image Size for Mobile', 'soledad' ),
				'param_name' => 'mthumb_size',
				'std'        => 'penci-masonry-thumb',
				'group'      => 'Other',
				'value'      => Penci_Vc_Params_Helper::get_list_image_sizes( true ),
			),
		),

		/* Color */ array(
			array(
				'type'             => 'textfield',
				'param_name'       => 'penci_separator_bgstyle_',
				'heading'          => esc_html__( 'Big Grid Style', 'soledad' ),
				'value'            => '',
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Content Text Horizontal Position', 'soledad' ),
				'param_name' => 'content_horizontal_position',
				'std'        => 'left',
				'value'      => array(
					esc_html__( 'Center', 'soledad' ) => 'center',
					esc_html__( 'Left', 'soledad' )   => 'left',
					esc_html__( 'Right', 'soledad' )  => 'right',
				),
				'group'      => $group_color,
			),

			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Content Text Vertical Position', 'soledad' ),
				'param_name' => 'content_vertical_position',
				'value'      => array(
					esc_html__( 'Top', 'soledad' )    => 'top',
					esc_html__( 'Middle', 'soledad' ) => 'middle',
					esc_html__( 'Bottom', 'soledad' ) => 'bottom',
				),
				'group'      => $group_color,
			),

			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Content Text Align', 'soledad' ),
				'param_name' => 'content_text_align',
				'std'        => 'left',
				'value'      => array(
					esc_html__( 'Center', 'soledad' ) => 'center',
					esc_html__( 'Left', 'soledad' )   => 'left',
					esc_html__( 'Right', 'soledad' )  => 'right',
				),
				'group'      => $group_color,
			),

			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Content Text Display', 'soledad' ),
				'param_name' => 'content_display',
				'value'      => array(
					esc_html__( 'Block', 'soledad' )        => 'block',
					esc_html__( 'Inline Block', 'soledad' ) => 'inline-block',
				),
				'group'      => $group_color,
			),

			array(
				'type'       => 'penci_only_number',
				'heading'    => __( 'Content Text Max-Width', 'soledad' ),
				'param_name' => 'content_width',
				'group'      => $group_color,
			),

			array(
				'type'       => 'penci_only_number',
				'heading'    => __( 'Content Text Padding', 'soledad' ),
				'param_name' => 'content_padding',
				'group'      => $group_color,
			),

			array(
				'type'       => 'penci_only_number',
				'heading'    => __( 'Content Text Margin', 'soledad' ),
				'param_name' => 'content_margin',
				'group'      => $group_color,
			),

			array(
				'type'             => 'textfield',
				'param_name'       => 'penci_separator_bgoverlay',
				'heading'          => __( 'Big Grid Overlay', 'soledad' ),
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),

			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Apply Overlay On:', 'soledad' ),
				'param_name' => 'overlay_type',
				'value'      => array(
					esc_html__( 'Whole', 'soledad' )              => 'whole',
					esc_html__( 'Whole Content Text', 'soledad' ) => 'text',
					esc_html__( 'None', 'soledad' )               => 'none',
				),
				'group'      => $group_color,
			),

			array(
				'type'       => 'penci_only_number',
				'heading'    => __( 'Overlay Opacity(%)', 'soledad' ),
				'param_name' => 'overlay_opacity',
				'group'      => $group_color,
			),

			array(
				'type'       => 'penci_only_number',
				'heading'    => __( 'Overlay Hover Opacity(%)', 'soledad' ),
				'param_name' => 'overlay_hopacity',
				'group'      => $group_color,
			),

			array(
				'type'        => 'penci_switch',
				'heading'     => __( 'Apply Separate Background for Categories/Sub Title', 'soledad' ),
				'param_name'  => 'apply_spe_bg_subtitle',
				'true_state'  => 'yes',
				'false_state' => 'no',
				'default'     => 'no',
				'std'         => 'no',
				'group'       => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Background for Categories/Sub Title', 'soledad' ),
				'param_name' => 'spe_bg_subtitle',
				'group'      => $group_color,
				'dependency' => array( 'element' => 'apply_spe_bg_subtitle', 'value' => 'yes' ),
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Background for Categories/Sub Title on Hover', 'soledad' ),
				'param_name' => 'spe_bg_hsubtitle',
				'group'      => $group_color,
				'dependency' => array( 'element' => 'apply_spe_bg_subtitle', 'value' => 'yes' ),
			),

			array(
				'type'        => 'penci_switch',
				'heading'     => __( 'Apply Separate Background for Title', 'soledad' ),
				'param_name'  => 'apply_spe_bg_title',
				'true_state'  => 'yes',
				'false_state' => 'no',
				'default'     => 'no',
				'std'         => 'no',
				'group'       => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Background for Title', 'soledad' ),
				'param_name' => 'spe_bg_title',
				'group'      => $group_color,
				'dependency' => array( 'element' => 'apply_spe_bg_title', 'value' => 'yes' ),
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Background for Title on Hover', 'soledad' ),
				'param_name' => 'spe_bg_htitle',
				'group'      => $group_color,
				'dependency' => array( 'element' => 'apply_spe_bg_title', 'value' => 'yes' ),
			),

			array(
				'type'        => 'penci_switch',
				'heading'     => __( 'Apply Separate Background for Post Meta/Description', 'soledad' ),
				'param_name'  => 'apply_spe_bg_meta',
				'true_state'  => 'yes',
				'false_state' => 'no',
				'default'     => 'no',
				'std'         => 'no',
				'group'       => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Background for Post Meta/Description', 'soledad' ),
				'param_name' => 'spe_bg_meta',
				'group'      => $group_color,
				'dependency' => array( 'element' => 'apply_spe_bg_meta', 'value' => 'yes' ),
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Background for Post Meta/Description on Hover', 'soledad' ),
				'param_name' => 'spe_bg_hmeta',
				'group'      => $group_color,
				'dependency' => array( 'element' => 'apply_spe_bg_meta', 'value' => 'yes' ),
			),

			array(
				'type'             => 'textfield',
				'param_name'       => 'penci_separator_hover_effect',
				'heading'          => __( 'Big Grid Hover Effects', 'soledad' ),
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),

			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Image Hover Effect', 'soledad' ),
				'param_name' => 'image_hover',
				'value'      => array(
					'Zoom-In'        => 'zoom-in',
					'Zoom-out'       => 'zoom-out',
					'Move to Left'   => 'move-left',
					'Move to Right'  => 'move-right',
					'Move to Bottom' => 'move-bottom',
					'Move to Top'    => 'move-top',
					'None'           => 'none',
				),
				'group'      => $group_color,
			),

			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Content Text Hover Type', 'soledad' ),
				'param_name' => 'text_overlay',
				'value'      => array(
					'None'          => 'none',
					'Show on Hover' => 'show-in',
					'Hide on Hover' => 'hide-in',
				),
				'group'      => $group_color,
			),

			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Content Text Hover Animation', 'soledad' ),
				'param_name' => 'text_overlay_ani',
				'value'      => array(
					'Move to Top'    => 'movetop',
					'Move to Bottom' => 'movebottom',
					'Move to Left'   => 'moveleft',
					'Move to Right'  => 'moveright',
					'Zoom In'        => 'zoomin',
					'Zoom Out'       => 'zoomout',
					'Fade'           => 'fade',
				),
				'group'      => $group_color,
			),

			array(
				'type'        => 'penci_switch',
				'heading'     => __( 'Makes Titles Always Visible?', 'soledad' ),
				'param_name'  => 'title_anivisi',
				'group'       => $group_color,
				'true_state'  => 'yes',
				'false_state' => 'no',
				'default'     => 'yes',
				'std'         => 'yes',
				'dependency'  => array( 'element' => 'text_overlay', 'value_not_equal_to' => array( 'none' ) ),
			),

			array(
				'type'             => 'textfield',
				'param_name'       => 'penci_separator_typo_color',
				'heading'          => __( 'Big Grid Typo & Color', 'soledad' ),
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Background Color', 'soledad' ),
				'param_name' => 'bgitem_bg',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Border Color', 'soledad' ),
				'param_name' => 'bgitem_borders',
				'group'      => $group_color,
			),

			array(
				'type'       => 'penci_responsive_sizes',
				'heading'    => __( 'Borders Width', 'soledad' ),
				'param_name' => 'bgitem_borderwidth',
				'group'      => $group_color,
			),

			array(
				'type'       => 'penci_only_number',
				'heading'    => __( 'Padding', 'soledad' ),
				'param_name' => 'bgitem_padding',
				'group'      => $group_color,
			),

			array(
				'type'             => 'textfield',
				'param_name'       => 'penci_separator_bgtitle_design',
				'heading'          => __( 'Big Grid Title Design', 'soledad' ),
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Text & Link Color', 'soledad' ),
				'param_name' => 'bgsub_title',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Link Hover Color', 'soledad' ),
				'param_name' => 'bgsub_title_hover',
				'group'      => $group_color,
			),

			array(
				'type'       => 'font_container',
				'heading'    => __( 'Typography', 'soledad' ),
				'param_name' => 'bgsub_title_typo',
				'value'      => '',
				'group'      => $group_color,
				'settings'   => array(
					'fields' => array(
						//'tag'                     => 'h2',
						'text_align',
						'font_size',
						'line_height',
						'color',
						'tag_description'         => esc_html__( 'Select element tag.', 'js_composer' ),
						'text_align_description'  => esc_html__( 'Select text alignment.', 'js_composer' ),
						'font_size_description'   => esc_html__( 'Enter font size.', 'js_composer' ),
						'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
						'color_description'       => esc_html__( 'Select color for your element.', 'js_composer' ),
					),
				),
			),

			array(
				'type'             => 'textfield',
				'param_name'       => 'penci_separator_bgtitle',
				'heading'          => __( 'Big Grid Title', 'soledad' ),
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Title Color', 'soledad' ),
				'param_name' => 'bgtitle_color',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Title Hover Color', 'soledad' ),
				'param_name' => 'bgtitle_color_hover',
				'group'      => $group_color,
			),

			array(
				'type'       => 'font_container',
				'heading'    => __( 'Title Typography for Big Items', 'soledad' ),
				'param_name' => 'bgtitle_typo_big',
				'value'      => '',
				'group'      => $group_color,
				'settings'   => array(
					'fields' => array(
						//'tag'                     => 'h2',
						'text_align',
						'font_size',
						'line_height',
						'color',
						'tag_description'         => esc_html__( 'Select element tag.', 'js_composer' ),
						'text_align_description'  => esc_html__( 'Select text alignment.', 'js_composer' ),
						'font_size_description'   => esc_html__( 'Enter font size.', 'js_composer' ),
						'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
						'color_description'       => esc_html__( 'Select color for your element.', 'js_composer' ),
					),
				),
			),

			array(
				'type'       => 'font_container',
				'heading'    => __( 'Title Typography', 'soledad' ),
				'param_name' => 'bgtitle_typo',
				'value'      => '',
				'group'      => $group_color,
				'settings'   => array(
					'fields' => array(
						//'tag'                     => 'h2',
						'text_align',
						'font_size',
						'line_height',
						'color',
						'tag_description'         => esc_html__( 'Select element tag.', 'js_composer' ),
						'text_align_description'  => esc_html__( 'Select text alignment.', 'js_composer' ),
						'font_size_description'   => esc_html__( 'Enter font size.', 'js_composer' ),
						'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
						'color_description'       => esc_html__( 'Select color for your element.', 'js_composer' ),
					),
				),
			),

			array(
				'type'             => 'textfield',
				'param_name'       => 'penci_separator_post_meta',
				'heading'          => __( 'Post Meta & Description Text', 'soledad' ),
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Text Color', 'soledad' ),
				'param_name' => 'bgdesc_color',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Links Color', 'soledad' ),
				'param_name' => 'bgdesc_link_color',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Links Hover Color', 'soledad' ),
				'param_name' => 'bgdesc_link_hcolor',
				'group'      => $group_color,
			),

			array(
				'type'       => 'font_container',
				'heading'    => __( 'Typography', 'soledad' ),
				'param_name' => 'title_typo',
				'value'      => '',
				'group'      => $group_color,
				'settings'   => array(
					'fields' => array(
						//'tag'                     => 'h2',
						'text_align',
						'font_size',
						'line_height',
						'color',
						'tag_description'         => esc_html__( 'Select element tag.', 'js_composer' ),
						'text_align_description'  => esc_html__( 'Select text alignment.', 'js_composer' ),
						'font_size_description'   => esc_html__( 'Enter font size.', 'js_composer' ),
						'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
						'color_description'       => esc_html__( 'Select color for your element.', 'js_composer' ),
					),
				),
			),

			array(
				'type'             => 'textfield',
				'param_name'       => 'penci_separator_readmore',
				'heading'          => __( 'Read More Button', 'soledad' ),
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Text Color', 'soledad' ),
				'param_name' => 'readmore_color',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Text Hover Color', 'soledad' ),
				'param_name' => 'readmore_hcolor',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Border Color', 'soledad' ),
				'param_name' => 'bgreadmore_color',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Border Hover Color', 'soledad' ),
				'param_name' => 'bgreadmore_hcolor',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Background Color', 'soledad' ),
				'param_name' => 'bgreadmore_bgcolor',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Background Hover Color', 'soledad' ),
				'param_name' => 'bgreadmore_hbgcolor',
				'group'      => $group_color,
			),

			array(
				'type'       => 'font_container',
				'heading'    => __( 'Typography', 'soledad' ),
				'param_name' => 'bgreadm_typo',
				'value'      => '',
				'group'      => $group_color,
				'settings'   => array(
					'fields' => array(
						//'tag'                     => 'h2',
						'text_align',
						'font_size',
						'line_height',
						'color',
						'tag_description'         => esc_html__( 'Select element tag.', 'js_composer' ),
						'text_align_description'  => esc_html__( 'Select text alignment.', 'js_composer' ),
						'font_size_description'   => esc_html__( 'Enter font size.', 'js_composer' ),
						'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
						'color_description'       => esc_html__( 'Select color for your element.', 'js_composer' ),
					),
				),
			),

			array(
				'type'             => 'penci_only_number',
				'heading'          => __( 'Borders Width', 'soledad' ),
				'param_name'       => 'bgreadmore_borderwidth',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-4',
			),

			array(
				'type'             => 'penci_only_number',
				'heading'          => __( 'Borders Radius', 'soledad' ),
				'param_name'       => 'bgreadmore_borderradius',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-4',
			),

			array(
				'type'             => 'penci_only_number',
				'heading'          => __( 'Padding', 'soledad' ),
				'param_name'       => 'bgreadmore_padding',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-4',
			),

			array(
				'type'       => 'penci_switch',
				'heading'    => __( 'Add Icon to "Read More" Button', 'soledad' ),
				'param_name' => 'add_icon_readmore',
				'group'      => $group_color,
			),

			array(
				'type'             => 'iconpicker',
				'heading'          => __( 'Add Icon to "Read More" Button', 'soledad' ),
				'param_name'       => 'readmore_icon',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),

			array(
				'type'             => 'dropdown',
				'heading'          => __( 'Icon position', 'soledad' ),
				'param_name'       => 'readmore_icon_pos',
				'group'            => $group_color,
				'value'            => array(
					'Right' => 'right',
					'Left'  => 'left',
				),
				'edit_field_class' => 'vc_col-sm-6',
			),

			array(
				'type'             => 'textfield',
				'param_name'       => 'penci_separator_pagenavi',
				'heading'          => __( 'Page Navigation', 'soledad' ),
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),

			array(
				'type'       => 'penci_only_number',
				'heading'    => __( 'Load More Posts Button Max Width', 'soledad' ),
				'param_name' => 'pagi_mwidth',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Text Color', 'soledad' ),
				'param_name' => 'pagi_color',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Text Hove & Active Color', 'soledad' ),
				'param_name' => 'pagi_hcolor',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Border Color', 'soledad' ),
				'param_name' => 'bgpagi_color',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Border Hove & Active Color', 'soledad' ),
				'param_name' => 'bgpagi_hcolor',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Background Color', 'soledad' ),
				'param_name' => 'bgpagi_bgcolor',
				'group'      => $group_color,
			),

			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Hove & Active Background Color', 'soledad' ),
				'param_name' => 'bgpagi_hbgcolor',
				'group'      => $group_color,
			),

			array(
				'type'       => 'font_container',
				'heading'    => __( 'Typography', 'soledad' ),
				'param_name' => 'bgpagi_typo',
				'value'      => '',
				'group'      => $group_color,
				'settings'   => array(
					'fields' => array(
						//'tag'                     => 'h2',
						'text_align',
						'font_size',
						'line_height',
						'color',
						'tag_description'         => esc_html__( 'Select element tag.', 'js_composer' ),
						'text_align_description'  => esc_html__( 'Select text alignment.', 'js_composer' ),
						'font_size_description'   => esc_html__( 'Enter font size.', 'js_composer' ),
						'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
						'color_description'       => esc_html__( 'Select color for your element.', 'js_composer' ),
					),
				),
			),

			array(
				'type'             => 'penci_only_number',
				'heading'          => __( 'Borders Width', 'soledad' ),
				'param_name'       => 'bgreadmore_borderwidth',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-4',
			),

			array(
				'type'             => 'penci_only_number',
				'heading'          => __( 'Borders Radius', 'soledad' ),
				'param_name'       => 'bgreadmore_borderradius',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-4',
			),

			array(
				'type'             => 'penci_only_number',
				'heading'          => __( 'Padding', 'soledad' ),
				'param_name'       => 'bgpagi_padding',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-4',
			),

		), Penci_Vc_Params_Helper::params_heading_filter(), Penci_Vc_Params_Helper::params_custom_meta_fields(), Penci_Vc_Params_Helper::params_heading_filter_style(), Penci_Vc_Params_Helper::extra_params(),Penci_Vc_Params_Helper::params_bookmark_icon() )
) );
