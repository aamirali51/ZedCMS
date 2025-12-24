<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "penci_login_form",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name('Soledad'),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/login_form/frontend.php',
	'weight'        => 775,
	'name'          => penci_get_theme_name('Penci').' '.esc_html__( 'Login/Register Form', 'soledad' ),
	'description'   => 'Login/Register Form Block',
	'controls'      => 'full',
	'params'        => array_merge(
		array(
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Choose Form Type', 'soledad' ),
				'param_name' => 'form_style',
				'description' => esc_html__( 'Please note that when a user is logged in, the registration form will be hidden. And if you select to show "Register" form, you need to go to Dashboard > Settings > General > on "Membership" select "Anyone can register" to make the Register form displays.', 'soledad' ),
				'std'        => '',
				'value'      => array(
					esc_html__( 'Login', 'soledad' )    => 'login',
					esc_html__( 'Register', 'soledad' ) => 'register',
				)
			)
		),
		Penci_Vc_Params_Helper::heading_block_params(),
		Penci_Vc_Params_Helper::params_heading_typo_color(),
		array(
			array(
				'type'             => 'textfield',
				'param_name'       => 'color_genral_css',
				'heading'          => esc_html__( 'Form colors', 'soledad' ),
				'value'            => '',
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Text color', 'soledad' ),
				'param_name'       => 'form_text_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Input Text Color', 'soledad' ),
				'param_name'       => 'form_input_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Input Placeholder Color', 'soledad' ),
				'param_name'       => 'form_place_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Input Border Color', 'soledad' ),
				'param_name'       => 'form_inputborder_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Link Color', 'soledad' ),
				'param_name'       => 'form_link_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Link Hover Color', 'soledad' ),
				'param_name'       => 'form_link_hcolor',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),

			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Button Text Color', 'soledad' ),
				'param_name'       => 'form_button_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Button Background Color', 'soledad' ),
				'param_name'       => 'form_button_bgcolor',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Button Text Hover Color', 'soledad' ),
				'param_name'       => 'form_button_hcolor',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Button Hover Background Color', 'soledad' ),
				'param_name'       => 'form_button_hbgcolor',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Login & Register Links Color', 'soledad' ),
				'param_name'       => 'ploginregis_link',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
		),
		Penci_Vc_Params_Helper::extra_params()
	)
) );
