<?php
$group_color = 'Typo & Color';
$group_trans = 'Strings Translation';

vc_map( array(
	'base'          => 'penci_count_down',
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name('Soledad'),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/count_down/frontend.php',
	'weight'        => 700,
	'name'          => penci_get_theme_name('Penci').' '.esc_html__( 'Count Down', 'soledad' ),
	'description'   => __( 'Count Down Block', 'soledad' ),
	'controls'      => 'full',
	'params'        => array_merge(
		Penci_Vc_Params_Helper::params_container_width(),
		array(
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Choose Skin', 'soledad' ),
				'param_name' => 'count_down_style',
				'std'        => 's1',
				'value'      => array(
					__( 'Style 1', 'soledad' ) => 's1',
					__( 'Style 2', 'soledad' ) => 's2',
					__( 'Style 3', 'soledad' ) => 's3',
					__( 'Style 4', 'soledad' ) => 's4',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Posttion', 'soledad' ),
				'param_name' => 'count_down_posttion',
				'std'        => 'center',
				'value'      => array(
					__( 'Left', 'soledad' )   => 'left',
					__( 'Center', 'soledad' ) => 'center',
					__( 'Right', 'soledad' )  => 'right',
				),
			),
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Year', 'soledad' ),
				'param_name'       => 'count_year',
				'admin_label'      => true,
				'edit_field_class' => 'vc_col-sm-2',
			),
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Month', 'soledad' ),
				'param_name'       => 'count_month',
				'admin_label'      => true,
				'edit_field_class' => 'vc_col-sm-2',
			),
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Day', 'soledad' ),
				'param_name'       => 'count_day',
				'admin_label'      => true,
				'edit_field_class' => 'vc_col-sm-2',

			),
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Hour', 'soledad' ),
				'param_name'       => 'count_hour',
				'admin_label'      => true,
				'edit_field_class' => 'vc_col-sm-2',

			),
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Minus', 'soledad' ),
				'param_name'       => 'count_minus',
				'admin_label'      => true,
				'edit_field_class' => 'vc_col-sm-2',

			),
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Sec', 'soledad' ),
				'param_name'       => 'count_sec',
				'admin_label'      => true,
				'edit_field_class' => 'vc_col-sm-2',
			),

			array(
				"type"       => "checkbox",
				"class"      => "",
				"heading"    => esc_html__( "Select time units to display in countdown timer", "penci-framework" ),
				"param_name" => "countdown_opts",
				"value"      => array(
					esc_html__( "Years", "penci-framework" )   => "Y",
					esc_html__( "Months", "penci-framework" )  => "O",
					esc_html__( "Weeks", "penci-framework" )   => "W",
					esc_html__( "Days", "penci-framework" )    => "D",
					esc_html__( "Hours", "penci-framework" )   => "H",
					esc_html__( "Minutes", "penci-framework" ) => "M",
					esc_html__( "Seconds", "penci-framework" ) => "S",
				)
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Timer digit border style', 'soledad' ),
				'param_name' => 'digit_border',
				'value'      => array(
					esc_html__( 'None', 'soledad' )   => '',
					esc_html__( 'Solid', 'soledad' )  => 'solid',
					esc_html__( 'Dashed', 'soledad' ) => 'dashed',
					esc_html__( 'Dotted', 'soledad' ) => 'dotted',
					esc_html__( 'Double', 'soledad' ) => 'double',
				),
				'dependency' => array( 'element' => 'count_down_style', 'value' => array( 's1' ) ),
			),
			array(
				'type'       => 'penci_responsive_sizes',
				'param_name' => 'digit_border_width',
				'heading'    => __( 'Timer digit border width', 'soledad' ),
				'suffix'     => 'px',
				'min'        => 1,
				'edit_field_class' => 'vc_col-sm-6',
				'dependency' => array( 'element' => 'digit_border', 'value' => array( 'solid', 'dashed', 'dotted', 'double' ) ),
			),
			array(
				'type'       => 'penci_responsive_sizes',
				'param_name' => 'digit_border_radius',
				'heading'    => __( 'Timer digit border radius', 'soledad' ),
				'suffix'     => 'px',
				'min'        => 1,
				'edit_field_class' => 'vc_col-sm-6',
				'dependency' => array( 'element' => 'digit_border', 'value' => array( 'solid', 'dashed', 'dotted', 'double' ) ),
			),
			array(
				'type'             => 'penci_responsive_sizes',
				'param_name'       => 'digit_padding',
				'heading'          => __( 'Timer digit padding', 'soledad' ),
				'suffix'           => 'px',
				'min'              => 1,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'penci_responsive_sizes',
				'param_name'       => 'unit_margin_top',
				'heading'          => __( 'Timer unit margin top', 'soledad' ),
				'suffix'           => 'px',
				'min'              => 1,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'penci_responsive_sizes',
				'param_name'       => 'countdown_item_width',
				'heading'          => __( 'Width of Countdown Section', 'soledad' ),
				'suffix'           => 'px',
				'min'              => 1,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'penci_responsive_sizes',
				'param_name'       => 'countdown_item_height',
				'heading'          => __( 'Height of Countdown Section', 'soledad' ),
				'suffix'           => 'px',
				'min'              => 1,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'       => 'penci_switch',
				'heading'    => __( 'Turn on uppearcase for label count down?', 'soledad' ),
				'true_state'       => 'yes',
				'false_state'      => 'no',
				'default'          => 'no',
				'std'              => 'no',
				'param_name' => 'cdtitle_upper'
			),
			// Transition
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Day (Singular)', 'soledad' ),
				'param_name' => 'str_days',
				'value'      => esc_html__( 'Day', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Days (Plural)', 'soledad' ),
				'param_name' => 'str_days2',
				'value'      => esc_html__( 'Days', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Week (Singular)', 'soledad' ),
				'param_name' => 'str_weeks',
				'value'      => esc_html__( 'Week', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Weeks (Plural)', 'soledad' ),
				'param_name' => 'str_weeks2',
				'value'      => esc_html__( 'Weeks', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Month (Singular)', 'soledad' ),
				'param_name' => 'str_months',
				'value'      => esc_html__( 'Month', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Months (Plural)', 'soledad' ),
				'param_name' => 'str_months2',
				'value'      => esc_html__( 'Months', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Year (Singular)', 'soledad' ),
				'param_name' => 'str_years',
				'value'      => esc_html__( 'Year', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Years (Plural)', 'soledad' ),
				'param_name' => 'str_years2',
				'value'      => esc_html__( 'Years', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Hour (Singular)', 'soledad' ),
				'param_name' => 'str_hours',
				'value'      => esc_html__( 'Hour', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Hours (Plural)', 'soledad' ),
				'param_name' => 'str_hours2',
				'value'      => esc_html__( 'Hours', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Minute (Singular)', 'soledad' ),
				'param_name' => 'str_minutes',
				'value'      => esc_html__( 'Minute', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Minutes (Plural)', 'soledad' ),
				'param_name' => 'str_minutes2',
				'value'      => esc_html__( 'Minutes', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Second (Singular)', 'soledad' ),
				'param_name' => 'str_seconds',
				'value'      => esc_html__( 'Second', 'soledad' ),
				'group'      => $group_trans
			),
			array(
				'type'       => 'textfield',
				'class'      => '',
				'heading'    => esc_html__( 'Seconds (Plural)', 'soledad' ),
				'param_name' => 'str_seconds2',
				'value'      => esc_html__( 'Seconds', 'soledad' ),
				'group'      => $group_trans
			),
			// Color
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Timer background color', 'soledad' ),
				'param_name'       => 'time_bgcolor',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
				'dependency'       => array( 'element' => 'count_down_style', 'value' => array( 's2', 's3', 's4' ) ),
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Timer border color', 'soledad' ),
				'param_name'       => 'time_bordercolor',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
				'dependency'       => array( 'element' => 'count_down_style', 'value' => array( 's2', 's3', 's4' ) ),
			),
			array(
				'type'             => 'textfield',
				'param_name'       => 'time_digit_heading',
				'heading'          => esc_html__( 'Timer digit', 'soledad' ),
				'value'            => '',
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Timer digit border color', 'soledad' ),
				'param_name'       => 'time_digit_bordercolor',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Timer digit background color', 'soledad' ),
				'param_name'       => 'time_digit_bgcolor',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
				'dependency'       => array( 'element' => 'count_down_style', 'value' => array( 's1' ) ),
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Timer digit color', 'soledad' ),
				'param_name'       => 'time_digit_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
				'dependency'       => array( 'element' => 'count_down_style', 'value' => array( 's1' ) ),
			),
			array(
				'type'       => 'google_fonts',
				'group'      => $group_color,
				'param_name' => 'time_digit_typo',
				'value'      => '',
			),
			array(
				'type'       => 'penci_responsive_sizes',
				'param_name' => 'time_digit_size',
				'heading'    => __( 'Font Size for Timer Digit', 'soledad' ),
				'suffix'     => 'px',
				'min'        => 1,
				'group'      => $group_color,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Font Weight for Timer Digit', 'soledad' ),
				'param_name' => 'time_weight',
				'std'        => 'center',
				'value'      => array(
					__( 'Default', 'soledad' ) => '',
					'normal'                   => 'Normal',
					'bold'                     => 'Bold',
					'bolder'                   => 'Bolder',
					'lighter'                  => 'Lighter',
					'100'                      => '100',
					'200'                      => '200',
					'300'                      => '300',
					'400'                      => '400',
					'500'                      => '500',
					'600'                      => '600',
					'700'                      => '700',
					'800'                      => '800',
					'900'                      => '900'
				),
				'group'      => $group_color,
			),
			array(
				'type'             => 'textfield',
				'param_name'       => 'unit_heading',
				'heading'          => esc_html__( 'Timer unit', 'soledad' ),
				'value'            => '',
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Timer unit color', 'soledad' ),
				'param_name'       => 'unit_color',
				'group'            => $group_color,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type'       => 'google_fonts',
				'group'      => $group_color,
				'param_name' => 'unit_typo',
				'value'      => '',
			),
			array(
				'type'       => 'penci_responsive_sizes',
				'param_name' => 'unit_size',
				'heading'    => __( 'Font Size for Timer Unit', 'soledad' ),
				'suffix'     => 'px',
				'min'        => 1,
				'group'      => $group_color,
			),
		),
		Penci_Vc_Params_Helper::extra_params()
	)
) );
