<?php
vc_map( array(
	'base'          => "penci_pintersest",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name('Soledad'),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/pintersest/frontend.php',
	'weight'        => 775,
	'name'          => penci_get_theme_name('Penci').' '.esc_html__( 'Pinterest', 'soledad' ),
	'description'   => 'Pintersest Block',
	'controls'      => 'full',
	'params'        => array_merge(
		array(
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Enter the <strong style="color: #ff0000;">username</strong> or <strong style="color: #ff0000;">username/board_name</strong> for load images:', 'soledad' ),
				'param_name'  => 'pusername',
				'admin_label' => true,
				'std'         => 'thefirstmess/animals-cuteness',
				'default'     => 'thefirstmess/animals-cuteness',
				'description' => 'Example if you want to load a board has url <strong style="color: #ff0000;"><a href="https://www.pinterest.com/thefirstmess/animals-cuteness" target="_blank">https://www.pinterest.com/thefirstmess/animals-cuteness</a></strong> You need to fill <strong style="color: #ff0000;">thefirstmess/animals-cuteness</strong>',
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Numbers image to show:', 'soledad' ),
				'param_name' => 'pnumbers',
				'std'        => 9
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Cache life time ( unit is seconds ):', 'soledad' ),
				'param_name' => 'pcache',
				'std'        => 1200
			),
			array(
				'type'       => 'penci_switch',
				'heading'    => esc_html__( 'Display more link with username text?', 'soledad' ),
				'param_name' => 'pfollow',
				'true_state'       => 'yes',
				'false_state'      => 'no',
				'default'          => 'no',
				'std'              => 'no',
			)
		),
		Penci_Vc_Params_Helper::heading_block_params(),
		Penci_Vc_Params_Helper::params_heading_typo_color(),
		Penci_Vc_Params_Helper::extra_params()
	)
) );
