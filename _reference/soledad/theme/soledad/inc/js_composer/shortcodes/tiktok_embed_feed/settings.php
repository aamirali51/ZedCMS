<?php
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => 'penci_tiktok_embed_feed',
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name( 'Soledad' ),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/tiktok_embed_feed/frontend.php',
	'weight'        => 700,
	'name'          => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'TikTok Embed Feed', 'soledad' ),
	'description'   => __( 'Show recent feed of your TikTok', 'soledad' ),
	'controls'      => 'full',
	'params'        => array_merge( array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Username', 'soledad' ),
			'param_name'  => 'username',
			'value'       => '',
			'admin_label' => true,
		),
		array(
			'type'       => 'penci_responsive_sizes',
			'heading'    => __( 'Width', 'soledad' ),
			'param_name' => 'width',
			'value'      => '',
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Content Text Horizontal Position', 'soledad' ),
			'param_name' => 'content_horizontal_position',
			'value'      => array(
				'Left'   => 'margin-right: auto',
				'Center' => 'margin-left: auto; margin-right: auto;',
				'Right'  => 'margin-left: auto',
			),
		),
	), Penci_Vc_Params_Helper::heading_block_params(), Penci_Vc_Params_Helper::params_heading_typo_color(), Penci_Vc_Params_Helper::extra_params() )
) );
