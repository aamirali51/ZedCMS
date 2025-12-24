<?php
vc_map( array(
	'base'          => 'penci_text_block',
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name('Soledad'),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/text_block/frontend.php',
	'weight'        => 700,
	'name'          => penci_get_theme_name('Penci').' '.esc_html__( 'Text Block', 'soledad' ),
	'description'   => __( 'A block of text with WYSIWYG editor', 'soledad' ),
	'controls'      => 'full',
	'params'        => array_merge(
		array(
			array(
				'type' => 'textarea_html',
				'holder' => 'div',
				'heading' => __( 'Text', 'soledad' ),
				'param_name' => 'content',
				'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'soledad' ),
			),
		),
		Penci_Vc_Params_Helper::heading_block_params(),
		Penci_Vc_Params_Helper::params_heading_typo_color(),
		Penci_Vc_Params_Helper::extra_params()
	)
) );
