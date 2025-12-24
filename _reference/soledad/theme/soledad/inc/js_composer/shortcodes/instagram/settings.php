<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "penci_instagram",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name('Soledad'),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/instagram/frontend.php',
	'weight'        => 775,
	'name'          => penci_get_theme_name('Penci').' '.esc_html__( 'Instagram Feed', 'soledad' ),
	'description'   => 'Instagram Block',
	'controls'      => 'full',
	'params'        => array_merge(
		array(
			array(
				'type'             => 'textfield',
				'param_name'       => 'heading_meta_settings',
				'heading'          => esc_html__( 'You need to connect to your Instagram account via "Dashboard > Soledad > Connect Instagram" first.', 'soledad' ),
				'value'            => '',
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Number of images to show', 'soledad' ),
				'param_name' => 'images_number',
				'value'      => array(
					1  => 1,
					2  => 2,
					3  => 3,
					4  => 4,
					5  => 5,
					6  => 6,
					7  => 7,
					8  => 8,
					9  => 9,
					10 => 10,
					11 => 11,
					12 => 12
				),
				'std'        => '9',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Check for new images every', 'soledad' ),
				'param_name'  => 'refresh_hour',
				'std'         => '5',
				'description' => 'Unix is hour(s)',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Template', 'soledad' ),
				'param_name' => 'template',
				'value'      => array(
					'Thumbnails - Without Border' => 'thumbs-no-border',
					'Thumbnails'                  => 'thumbs',
					'Slider - Normal'             => 'slider',
					'Slider - Overlay Text'       => 'slider-overlay'
				),
				'std'        => 'thumbs',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Number of Columns:', 'soledad' ),
				'param_name' => 'columns',
				'value'      => array(
					1  => 1,
					2  => 2,
					3  => 3,
					4  => 4,
					5  => 5,
					6  => 6,
					7  => 7,
					8  => 8,
					9  => 9,
					10 => 10,
				),
				'std'        => '3',
				'dependency' => array( 'element' => 'template', 'value' => array( 'thumbs-no-border', 'thumbs' ) ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Order by', 'soledad' ),
				'param_name' => 'orderby',
				'value'      => array(
					__( 'Date - Ascending', 'soledad' )        => 'date-ASC',
					__( 'Date - Descending', 'soledad' )       => 'date-DESC',
					__( 'Popularity - Ascending', 'soledad' )  => 'popular-ASC',
					__( 'Popularity - Descending', 'soledad' ) => 'popular-DESC',
					__( 'Random', 'soledad' )                  => 'rand',
				),
				'std'        => 'rand',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'On click action', 'soledad' ),
				'param_name' => 'images_link',
				'value'      => array(
					__( 'None', 'soledad' )              => '',
					__( 'Instagram image', 'soledad' )   => 'image_url',
					__( 'Instagram Profile', 'soledad' ) => 'user_url',
					__( 'Attachment Page', 'soledad' )   => 'attachment'
				),
				'std'        => 'rand',
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Slider Speed (at x seconds)', 'soledad' ),
				'param_name' => 'slidespeed',
				'dependency' => array( 'element' => 'template', 'value' => array( 'slider', 'slider-overlay' ) ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Slider Navigation Controls', 'soledad' ),
				'param_name' => 'controls',
				'value'      => array(
					__( 'None', 'soledad' )        => 'none',
					__( 'Prev & Next', 'soledad' ) => 'prev_next',
					__( 'Dotted', 'soledad' )      => 'numberless',
				),
				'std'        => 'prev_next',
				'dependency' => array( 'element' => 'template', 'value' => array( 'slider', 'slider-overlay' ) ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Number of words in caption', 'soledad' ),
				'param_name' => 'caption_words',
				'default'    => 100,
				'dependency' => array( 'element' => 'template', 'value' => array( 'slider', 'slider-overlay' ) ),
			),
		),
		Penci_Vc_Params_Helper::heading_block_params(),
		Penci_Vc_Params_Helper::params_heading_typo_color(),
		array(
			array(
				'type'       => 'css_editor',
				'heading'    => __( 'CSS box', 'soledad' ),
				'param_name' => 'css',
				'group'      => __( 'Design Options', 'soledad' ),
			),
			penci_get_vc_responsive_spacing_map(),
		)
	)
) );
