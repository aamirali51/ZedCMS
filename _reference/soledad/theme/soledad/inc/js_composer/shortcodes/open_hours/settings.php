<?php
$group_icon  = 'Icon';
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => "penci_open_hours",
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name('Soledad'),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/open_hours/frontend.php',
	'weight'        => 775,
	'name'          => penci_get_theme_name('Penci').' '.esc_html__( 'Open Hours', 'soledad' ),
	'description'   => 'Open Hours Block',
	'controls'      => 'full',
	'params'        => array_merge(
		array(
			array(
				'type'       => 'param_group',
				'heading'    => 'Content',
				'param_name' => 'working_hours',
				'value'      => urlencode( json_encode( array(
					array(
						'icon'  => 'far fa-clock',
						'title' => 'Monday',
						'hours' => '8:00 AM - 9:00 PM'
					),
					array(
						'icon'  => 'far fa-clock',
						'title' => 'Tuesday',
						'hours' => '8:00 AM - 9:00 PM'
					),
					array(
						'icon'  => 'far fa-clock',
						'title' => 'Wednessday',
						'hours' => '8:00 AM - 9:00 PM'
					)
				) ) ),
				'params'     => array(
					array(
						'type'       => 'iconpicker',
						'heading'    => __( 'Icon', 'soledad' ),
						'param_name' => 'icon',
						'value'      => '',
						'settings'   => array(
							'emptyIcon'    => true,
							'iconsPerPage' => 4000,
						)
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Title', 'soledad' ),
						'param_name'  => 'title',
						'admin_label' => true
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Subtitle', 'soledad' ),
						'param_name' => 'subtitle'
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Hours or Price', 'soledad' ),
						'param_name'  => 'hours',
						'admin_label' => true
					)
				)
			),
			array(
				'type'       => 'penci_only_number',
				'heading'    => esc_html__( 'Space Item', 'soledad' ),
				'param_name' => 'row_gap',
				'value'      => 0,
				'min'        => 0,
				'max'        => 200,
				'suffix'     => 'px',
			),
			array(
				'type'       => 'penci_responsive_sizes',
				'param_name' => 'subtitle_martop',
				'heading'    => __( 'Subtitle margin top', 'soledad' ),
				'value'      => '',
				'std'        => '',
				'suffix'     => 'px',
				'min'        => 1,
			)
		),
		Penci_Vc_Params_Helper::heading_block_params(),
		Penci_Vc_Params_Helper::params_heading_typo_color(),
		array(
			array(
				'type'             => 'textfield',
				'param_name'       => 'heading_icon_settings',
				'heading'          => esc_html__( 'Icon of Open Hour', 'soledad' ),
				'value'            => '',
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Icon color', 'soledad' ),
				'param_name'       => 'ophour_icon_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'penci_responsive_sizes',
				'param_name'       => 'ophour_icon_size',
				'heading'          => __( 'Font size for Icon', 'soledad' ),
				'value'            => '',
				'std'              => '',
				'suffix'           => 'px',
				'min'              => 1,
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),

			array(
				'type'             => 'textfield',
				'param_name'       => 'heading_title_settings',
				'heading'          => esc_html__( 'Title of Open Hour', 'soledad' ),
				'value'            => '',
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Title color', 'soledad' ),
				'param_name'       => 'ophour_title_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'       => 'google_fonts',
				'group'      => $group_color,
				'param_name' => 'ophour_title_typo',
				'value'      => '',
			),
			array(
				'type'       => 'penci_responsive_sizes',
				'param_name' => 'ophour_title_size',
				'heading'    => __( 'Font Size for Title', 'soledad' ),
				'value'      => '',
				'std'        => '',
				'suffix'     => 'px',
				'min'        => 1,

				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'textfield',
				'param_name'       => 'heading_subtitle_settings',
				'heading'          => esc_html__( 'Subtitle of Open Hour', 'soledad' ),
				'value'            => '',
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Subtitle color', 'soledad' ),
				'param_name'       => 'ophour_subtitle_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'       => 'google_fonts',
				'group'      => $group_color,
				'param_name' => 'ophour_subtitle_typo',
				'value'      => '',
			),
			array(
				'type'             => 'penci_responsive_sizes',
				'param_name'       => 'ophour_subtitle_size',
				'heading'          => __( 'Font Size for Subtitle', 'soledad' ),
				'value'            => '',
				'std'              => '',
				'suffix'           => 'px',
				'min'              => 1,
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'textfield',
				'param_name'       => 'heading_hour_settings',
				'heading'          => esc_html__( 'Hours or Price of Open Hour', 'soledad' ),
				'value'            => '',
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Hours or Price Color', 'soledad' ),
				'param_name'       => 'ophour_hour_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'       => 'google_fonts',
				'group'      => $group_color,
				'param_name' => 'ophour_hour_typo',
				'value'      => '',
			),
			array(
				'type'             => 'penci_responsive_sizes',
				'param_name'       => 'ophour_hour_size',
				'heading'          => __( 'Font size for Hours or Price', 'soledad' ),
				'value'            => '',
				'std'              => '',
				'suffix'           => 'px',
				'min'              => 1,
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'dropdown',
				'heading'          => esc_html__( 'Font Weight for Hours or Price', 'soledad' ),
				'param_name'       => 'ophour_hour_weight',
				'value'            => array(
					__( 'Default', 'soledad' ) => '',
					'Normal'                   => 'normal',
					'Bold'                     => 'bold',
					'Bolder'                   => 'bolder',
					'Lighter'                  => 'lighter',
					'100'                      => '100',
					'200'                      => '200',
					'300'                      => '300',
					'400'                      => '400',
					'500'                      => '500',
					'600'                      => '600',
					'700'                      => '700',
					'800'                      => '800',
					'900'                      => '900',
				),
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6'
			),
		),
		Penci_Vc_Params_Helper::extra_params()
	)
) );
