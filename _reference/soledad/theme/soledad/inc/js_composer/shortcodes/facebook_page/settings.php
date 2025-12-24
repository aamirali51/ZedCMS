<?php
vc_map( array(
	'base'          => 'penci_facebook_page',
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name('Soledad'),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/facebook_page/frontend.php',
	'weight'        => 700,
	'name'          => penci_get_theme_name('Penci').' '.esc_html__( 'Facebook Page', 'soledad' ),
	'description'   => __( 'Facebook Page Block', 'soledad' ),
	'controls'      => 'full',
	'params'        => array_merge(
		array(
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Facebook Page Title:', 'soledad' ),
				'param_name' => 'title_page',
				'std'        => esc_html__( 'Facebook', 'soledad' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Facebook Page URL:', 'soledad' ),
				'param_name'  => 'page_url',
				'admin_label' => true,
				'std'         => 'https://www.facebook.com/PenciDesign',
				'value'       => 'https://www.facebook.com/PenciDesign',
				'description' => esc_html__( 'EG. https://www.facebook.com/your-page/', 'soledad' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Facebook Page Height::', 'soledad' ),
				'param_name'  => 'page_height',
				'std'         => 290,
				'description' => esc_html__( 'This option is only applied when "Show Stream" option is checked', 'soledad' ),
			),
			array(
				'type'       => 'penci_switch',
				'heading'    => esc_html__( 'Hide Cover Image?', 'soledad' ),
				'true_state'       => 'yes',
				'false_state'      => 'no',
				'default'          => 'no',
				'std'              => 'no',
				'param_name' => 'hide_cover',
			),
			array(
				'type'       => 'penci_switch',
				'heading'    => esc_html__( 'Hide Faces?', 'soledad' ),
				'true_state'       => 'yes',
				'false_state'      => 'no',
				'default'          => 'no',
				'std'              => 'no',
				'param_name' => 'hide_faces',
			),
			array(
				'type'       => 'penci_switch',
				'heading'    => esc_html__( 'Hide Stream?', 'soledad' ),
				'true_state'       => 'yes',
				'false_state'      => 'no',
				'default'          => 'no',
				'std'              => 'no',
				'param_name' => 'hide_stream',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Custom Language', 'soledad' ),
				'param_name'  => 'language',
				'admin_label' => true,
				'std'         => '',
				'value'       => '',
				'description' => __( 'Fill the language code to use on Facebook Page Box here( E.g: de_DE ). By default, the language will follow the site language. See more <a href="https://developers.facebook.com/docs/internationalization/" target="_blank">here</a>', 'soledad' ),
			)
		),
		Penci_Vc_Params_Helper::heading_block_params(),
		Penci_Vc_Params_Helper::params_heading_typo_color(),
		Penci_Vc_Params_Helper::extra_params()
	)
) );
