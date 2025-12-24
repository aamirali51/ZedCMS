<?php
$group_color = 'Typo & Color';

vc_map( array(
	'base'          => 'penci_progress_bar',
	'icon'          => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'      => penci_get_theme_name('Soledad'),
	'html_template' => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/progress_bar/frontend.php',
	'weight'        => 700,
	'name'          => penci_get_theme_name('Penci').' '.esc_html__( 'Progress Bar', 'soledad' ),
	'description'   => __( 'Progress Bar Block', 'soledad' ),
	'controls'      => 'full',
	'params'        => array_merge(
		array(
			array(
				'type'        => 'param_group',
				'heading'     => __( 'Values', 'soledad' ),
				'param_name'  => 'values',
				'description' => __( 'Enter values for graph - value, title and color.', 'soledad' ),
				'value'       => urlencode( json_encode( array(
					array(
						'label' => __( 'Development', 'soledad' ),
						'value' => '90',
					),
					array(
						'label' => __( 'Design', 'soledad' ),
						'value' => '80',
					),
					array(
						'label' => __( 'Marketing', 'soledad' ),
						'value' => '70',
					),
				) ) ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Label', 'soledad' ),
						'param_name'  => 'label',
						'description' => __( 'Enter text used as title of bar.', 'soledad' ),
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Value', 'soledad' ),
						'param_name'  => 'value',
						'description' => __( 'Enter value of bar.', 'soledad' ),
						'admin_label' => true,
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => __( 'Custom background color', 'soledad' ),
						'param_name'  => 'bgcolor',
						'description' => __( 'Select custom single bar background color.', 'soledad' )
					),
					array(
						'type'        => 'colorpicker',
						'heading'     => __( 'Custom text color', 'soledad' ),
						'param_name'  => 'textcolor',
						'description' => __( 'Select custom single bar text color.', 'soledad' ),
						'dependency'  => array(
							'element' => 'color',
							'value'   => array( 'custom' ),
						),
					),
				),
			),
			array(
				'type'       => 'penci_only_number',
				'heading'    => esc_html__( 'Custom height for bar', 'soledad' ),
				'param_name' => 'bar_height',
				'value'      => '',
				'min'        => 1,
				'max'        => 100,
				'suffix'     => 'px',
			),
			array(
				'type'       => 'penci_responsive_sizes',
				'heading'    => esc_html__( 'Custom margin top for bar', 'soledad' ),
				'param_name' => 'bar_mar_top',
				'value'      => '',
				'min'        => 1,
				'max'        => 100,
				'suffix'     => 'px',
			),
			array(
				'type'       => 'penci_responsive_sizes',
				'heading'    => esc_html__( 'Custom margin bottom for bar', 'soledad' ),
				'param_name' => 'bar_mar_bottom',
				'value'      => '',
				'min'        => 1,
				'max'        => 100,
				'suffix'     => 'px',
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Units', 'soledad' ),
				'param_name'  => 'units',
				'description' => __( 'Enter measurement units (Example: %, px, points, etc. Note: graph value and units will be appended to graph title).', 'soledad' ),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Options', 'soledad' ),
				'param_name' => 'options',
				'value'      => array(
					__( 'Add stripes', 'soledad' )                                          => 'striped',
					__( 'Add animation (Note: visible only with striped bar).', 'soledad' ) => 'animated',
				),
			)
		),
		array(
			array(
				'type'             => 'textfield',
				'param_name'       => 'progress_typo_heading',
				'heading'          => esc_html__( 'Progress Bar', 'soledad' ),
				'value'            => '',
				'group'            => $group_color,
				'edit_field_class' => 'penci-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Bar custom text color', 'soledad' ),
				'param_name'  => 'bar_textcolor',
				'description' => __( 'Select custom text color for bars.', 'soledad' ),
				'group'       => $group_color,
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Bar process run custom background color', 'soledad' ),
				'param_name'  => 'bar_run_bgcolor',
				'description' => __( 'Select custom background color for bars.', 'soledad' ),
				'group'       => $group_color,
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Bar custom background color', 'soledad' ),
				'param_name'  => 'bar_bgcolor',
				'description' => __( 'Select custom background color for bars.', 'soledad' ),
				'group'       => $group_color,
			),
		),
		Penci_Vc_Params_Helper::extra_params()
	)
) );
