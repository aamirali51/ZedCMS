<?php
$pmetas = array(
	'facebook'       => array(
		'label'   => __( 'Facebook', 'soledad' ),
		'default' => 'yes',
	),
	'twitter'        => array(
		'label'   => __( 'Twitter', 'soledad' ),
		'default' => 'yes',
	),
	'youtube'        => array(
		'label'   => __( 'Youtube', 'soledad' ),
		'default' => 'yes',
	),
	'instagram'      => array(
		'label'   => __( 'Instagram', 'soledad' ),
		'default' => 'yes',
	),
	'linkedin'       => array(
		'label'   => __( 'Linkedin', 'soledad' ),
		'default' => '',
	),
	'pinterest'      => array(
		'label'   => __( 'Pinterest', 'soledad' ),
		'default' => '',
	),
	'flickr'         => array(
		'label'   => __( 'Flickr', 'soledad' ),
		'default' => '',
	),
	'dribbble'       => array(
		'label'   => __( 'Dribbble', 'soledad' ),
		'default' => '',
	),
	'vimeo'          => array(
		'label'   => __( 'Vimeo', 'soledad' ),
		'default' => '',
	),
	'delicious'      => array(
		'label'   => __( 'Delicious', 'soledad' ),
		'default' => '',
	),
	'soundcloud'     => array(
		'label'   => __( 'SoundCloud', 'soledad' ),
		'default' => '',
	),
	'github'         => array(
		'label'   => __( 'Github', 'soledad' ),
		'default' => '',
	),
	'behance '       => array(
		'label'   => __( 'Behance', 'soledad' ),
		'default' => '',
	),
	'vk'             => array(
		'label'   => __( 'VK', 'soledad' ),
		'default' => '',
	),
	'tumblr'         => array(
		'label'   => __( 'Tumblr', 'soledad' ),
		'default' => '',
	),
	'vine'           => array(
		'label'   => __( 'Vine', 'soledad' ),
		'default' => '',
	),
	'steam'          => array(
		'label'   => __( 'Steam', 'soledad' ),
		'default' => '',
	),
	'email'          => array(
		'label'   => __( 'Email', 'soledad' ),
		'default' => '',
	),
	'bloglovin'      => array(
		'label'   => __( 'Bloglovin', 'soledad' ),
		'default' => '',
	),
	'rss'            => array(
		'label'   => __( 'Rss', 'soledad' ),
		'default' => '',
	),
	'snapchat'       => array(
		'label'   => __( 'Snapchat', 'soledad' ),
		'default' => '',
	),
	'spotify'        => array(
		'label'   => __( 'Spotify', 'soledad' ),
		'default' => '',
	),
	'stack_overflow' => array(
		'label'   => __( 'Stack overflow', 'soledad' ),
		'default' => '',
	),
	'twitch'         => array(
		'label'   => __( 'Twitch', 'soledad' ),
		'default' => '',
	),
	'line'           => array(
		'label'   => __( 'Line', 'soledad' ),
		'default' => '',
	),
	'xing'           => array(
		'label'   => __( 'Xing', 'soledad' ),
		'default' => '',
	),
	'patreon'        => array(
		'label'   => __( 'patreon', 'soledad' ),
		'default' => '',
	),
);

$meta_params         = array();
$profile_meta_params = array();
foreach ( $pmetas as $key => $pmeta ) {
	$meta_params[] = array(
		'type'       => 'penci_switch',
		'heading'    => $pmeta['label'],
		'param_name' => 'social_' . $key,
		'value'      => array( __( 'Yes', 'soledad' ) => 'yes' ),
		'std'        => $pmeta['default'],
	);
}

$socials_profile     = penci_social_media_array();
$custom_social_icons = get_option( 'penci_custom_socials', array() );
foreach ( $socials_profile as $name => $data ) {
	$std                   = in_array( $name, array( 'facebook', 'twitter', 'youtube', 'instagram' ) ) ? 'yes' : '';
	$profile_meta_params[] = array(
		'type'        => 'penci_switch',
		'heading'     => ucwords( isset( $custom_social_icons[ $name ]['name'] ) ? $custom_social_icons[ $name ]['name'] : $name ),
		'param_name'  => 'social_profile_' . $name,
		'true_state'  => 'yes',
		'false_state' => 'no',
		'std'         => $std,
		'dependency'  => array(
			'element' => 'source',
			'value'   => array( 'customizer' ),
		),
	);
}

vc_map(
	array(
		'base'          => 'penci_social_counter',
		'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
		'category'      => penci_get_theme_name( 'Soledad' ),
		'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/social_counter/frontend.php',
		'weight'        => 700,
		'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Social Counter', 'soledad' ),
		'description'   => __( 'Social Counter Block', 'soledad' ),
		'controls'      => 'full',
		'params'        => array_merge(
			Penci_Vc_Params_Helper::params_container_width(),
			array(
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Source', 'soledad' ),
					'param_name' => 'hide_count',
					'std'        => 'counter',
					'value'      => array(
						esc_html__( 'Social Counter', 'soledad' ) => 'counter',
						esc_html__( 'Customizer Social Media URL (No Counter)', 'soledad' ) => 'customizer',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Hide Counter Data & Show Social Name?', 'soledad' ),
					'param_name' => 'hide_count',
					'std'        => 'no',
					'value'      => array(
						esc_html__( 'No', 'soledad' )  => 'no',
						esc_html__( 'Yes', 'soledad' ) => 'yes',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Choose Style', 'soledad' ),
					'param_name' => 'social_style',
					'std'        => 's1',
					'value'      => array(
						esc_html__( 'Style 1', 'soledad' ) => 's1',
						esc_html__( 'Style 2', 'soledad' ) => 's2',
						esc_html__( 'Style 3', 'soledad' ) => 's3',
						esc_html__( 'Style 4', 'soledad' ) => 's4',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Filled or Bordered Style?', 'soledad' ),
					'param_name' => 'fill',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Filled', 'soledad' ) => 'fill',
						esc_html__( 'Bordered', 'soledad' ) => 'border',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Shape', 'soledad' ),
					'param_name' => 'shape',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Rectangle', 'soledad' ) => 'rectangle',
						esc_html__( 'Round', 'soledad' )  => 'round',
						esc_html__( 'Circle', 'soledad' ) => 'circle',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Select Columns', 'soledad' ),
					'param_name' => 'column',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Default', 'soledad' ) => '',
						esc_html__( '1 Column', 'soledad' ) => '1',
						esc_html__( '2 Columns', 'soledad' ) => '2',
						esc_html__( '3 Columns', 'soledad' ) => '3',
						esc_html__( '4 Columns', 'soledad' ) => '4',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Select Columns for Tablet', 'soledad' ),
					'param_name' => 'tab_column',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Default', 'soledad' ) => '',
						esc_html__( '1 Column', 'soledad' ) => '1',
						esc_html__( '2 Columns', 'soledad' ) => '2',
						esc_html__( '3 Columns', 'soledad' ) => '3',
						esc_html__( '4 Columns', 'soledad' ) => '4',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Select Columns for Mobile', 'soledad' ),
					'param_name' => 'mobile_column',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Default', 'soledad' ) => '',
						esc_html__( '1 Column', 'soledad' ) => '1',
						esc_html__( '2 Columns', 'soledad' ) => '2',
						esc_html__( '3 Columns', 'soledad' ) => '3',
						esc_html__( '4 Columns', 'soledad' ) => '4',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Colors Style', 'soledad' ),
					'param_name' => 'color_style',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Custom Color', 'soledad' ) => 'custom',
						esc_html__( 'Brands Background Color', 'soledad' ) => 'brandbg',
						esc_html__( 'Brands Icons Color', 'soledad' ) => 'brandtext',
					),
				),
				array(
					'type'       => 'penci_responsive_sizes',
					'heading'    => esc_html__( 'Horizontal Spacing Between Social Icons', 'soledad' ),
					'param_name' => 'hospace',
					'std'        => '',
					'value'      => '',
				),
				array(
					'type'       => 'penci_responsive_sizes',
					'heading'    => esc_html__( 'Vertical Spacing Between Social Icons', 'soledad' ),
					'param_name' => 'verspace',
					'std'        => '',
					'value'      => '',
				),
			),
			$meta_params,
			$profile_meta_params,
			array(
				array(
					'type'       => 'penci_responsive_sizes',
					'param_name' => 'social_name_size',
					'heading'    => __( 'Icon Size', 'soledad' ),
					'value'      => '',
					'std'        => '',
					'suffix'     => 'px',
					'min'        => 1,
				),
				array(
					'type'       => 'penci_responsive_sizes',
					'param_name' => 'countersize',
					'heading'    => __( 'Font Size for Counter Number', 'soledad' ),
					'value'      => '',
					'std'        => '',
					'suffix'     => 'px',
					'min'        => 1,
				),
				array(
					'type'       => 'penci_responsive_sizes',
					'param_name' => 'fansize',
					'heading'    => __( 'Font Size for Fans/Like text', 'soledad' ),
					'value'      => '',
					'std'        => '',
					'suffix'     => 'px',
					'min'        => 1,
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'bgcl',
					'heading'    => __( 'Background Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'hbgcl',
					'heading'    => __( 'Background Hover Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'bordercl',
					'heading'    => __( 'Border Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'borderhcl',
					'heading'    => __( 'Border Hover Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'textcl',
					'heading'    => __( 'Icon Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'texthcl',
					'heading'    => __( 'Icon Hover Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'countcl',
					'heading'    => __( 'Counter Text Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'counthcl',
					'heading'    => __( 'Counter Text Hover Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'fanscl',
					'heading'    => __( '"Fans" Text Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'fanshcl',
					'heading'    => __( '"Fans" Text Hover Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'followcl',
					'heading'    => __( '"Follow" Text Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'followhcl',
					'heading'    => __( '"Follow" Text Hover Color', 'soledad' ),
					'value'      => '',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Use Shadow?', 'soledad' ),
					'param_name' => 'use_shadow',
					'std'        => '',
					'value'      => array(
						esc_html__( 'Yes', 'soledad' ) => 'yes',
						esc_html__( 'No', 'soledad' )  => 'no',
					),
				),
			),
			Penci_Vc_Params_Helper::heading_block_params(),
			Penci_Vc_Params_Helper::params_heading_typo_color(),
			Penci_Vc_Params_Helper::extra_params()
		),
	)
);
