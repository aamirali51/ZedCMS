<?php
$builder_settings            = 'penci_builder_mods';
$builder_section             = 'penci_header_builder_block';
$builder_rows_desktop        = [ 'topblock', 'top', 'mid', 'bottom', 'bottomblock' ];
$builder_rows_desktop_sticky = [
	'top'    => 'Sticky Top',
	'mid'    => 'Sticky Middle',
	'bottom' => 'Sticky Bottom'
];
$builder_columns_desktop     = [ 'left', 'center', 'right' ];
$builder_rows_mobile         = [ 'top', 'mid', 'bottom' ];
$builder_columns_mobile      = [ 'left', 'center', 'right' ];
$builder_row_sidebar         = [ 'top', 'bottom' ];
$builder_columns_sidebar     = [ 'center' ];
$partial_refresh_id          = [
	'topblock'    => '.penci_topblock.penci-desktop-topblock',
	'top'         => '.penci-desktop-topbar',
	'mid'         => '.penci-desktop-midbar',
	'bottom'      => '.penci-desktop-bottombar',
	'bottomblock' => '.penci-desktop-bottomblock',
];
$partial_mobile_refresh_id   = [
	'top'    => '.penci-mobile-topbar',
	'mid'    => '.penci-mobile-midbar',
	'bottom' => '.penci-mobile-bottombar',
];
$partial_sticky_refresh_id   = [
	'top'    => '.penci-desktop-sticky-top',
	'mid'    => '.penci-desktop-sticky-mid',
	'bottom' => '.penci-desktop-sticky-bottom',
];

/* General Set up*/
$wp_customize->add_panel( 'header_builder_config', array(
	'priority'    => 1,
	'title'       => esc_html__( 'Header Builder', 'soledad' ),
	'description' => esc_html__( 'Build your site header with Customizer', 'soledad' ),
) );

$wp_customize->add_section( $builder_section, array(
	'title'       => esc_html__( 'Builder Settings', 'soledad' ),
	'description' => esc_html__( 'General Builder', 'soledad' ),
	'panel'       => 'header_builder_config',
	'priority'    => 900,
) );

$wp_customize->add_section( 'penci_header_desktop_sticky_section', array(
	'title'    => esc_html__( 'Sticky Header', 'soledad' ),
	'panel'    => 'header_builder_config',
	'priority' => 20,
) );

$wp_customize->add_section( 'penci_header_mobile_option_section', array(
	'title'    => esc_html__( 'Mobile Header', 'soledad' ),
	'panel'    => 'header_builder_config',
	'priority' => 20,
) );

$wp_customize->add_section( 'penci_header_drawer_container_section', array(
	'title'    => esc_html__( 'Mobile Sidebar', 'soledad' ),
	'panel'    => 'header_builder_config',
	'priority' => 20,
) );

/* Main Builder Section */
$wp_customize->add_setting( 'penci_hb_arrange_bar', array(
	'default'           => 'topblock,top,mid,bottom,bottomblock',
	'sanitize_callback' => 'penci_text_sanitization',
) );
$wp_customize->add_control( new Penci_Dropdown_Select2_Custom_Control( $wp_customize, 'penci_hb_arrange_bar', [
	'label'       => esc_html__( 'Desktop Element Sort', 'soledad' ),
	'section'     => $builder_section,
	'input_attrs' => array(
		'multiselect' => true,
	),
	'choices'     => [
		'topblock'    => esc_attr__( 'Top Ads', 'soledad' ),
		'top'         => esc_attr__( 'Top', 'soledad' ),
		'mid'         => esc_attr__( 'Mid', 'soledad' ),
		'bottom'      => esc_attr__( 'Bottom', 'soledad' ),
		'bottomblock' => esc_attr__( 'Bottom Ads', 'soledad' ),
	],
] ) );

foreach ( $builder_rows_desktop as $row ) {
	foreach ( $builder_columns_desktop as $column ) {
		$wp_customize->add_setting( "penci_hb_align_desktop_{$row}_{$column}", array(
			'default'           => $column,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'penci_sanitize_choices_field'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, "penci_hb_align_desktop_{$row}_{$column}", [
			'type'     => 'select',
			'label'    => ucfirst( $row ) . ' ' . ucfirst( $column ) . ' ' . esc_html__( 'Align', 'soledad' ),
			'section'  => $builder_section,
			'settings' => "penci_hb_align_desktop_{$row}_{$column}",
			'choices'  => [
				'left'   => esc_attr__( 'Left', 'soledad' ),
				'center' => esc_attr__( 'Center', 'soledad' ),
				'right'  => esc_attr__( 'Right', 'soledad' ),
			],
		] ) );
		
		$wp_customize->add_setting( "penci_hb_flex_desktop_{$row}_{$column}", array(
			'default'           => 'row',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'penci_sanitize_choices_field'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, "penci_hb_flex_desktop_{$row}_{$column}", [
			'type'     => 'select',
			'label'    => ucfirst( $row ) . ' ' . ucfirst( $column ) . ' ' . esc_html__( 'Flex Direction', 'soledad' ),
			'section'  => $builder_section,
			'settings' => "penci_hb_flex_desktop_{$row}_{$column}",
			'choices'  => [
				'row'   	=> esc_attr__( 'Row', 'soledad' ),
				'column' 	=> esc_attr__( 'Column', 'soledad' ),
			],
		] ) );


		$wp_customize->add_setting( "penci_hb_element_desktop_{$row}_{$column}", array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'penci_text_sanitization'
		) );
		$wp_customize->add_control( new Penci_Dropdown_Select2_Custom_Control( $wp_customize, "penci_hb_element_desktop_{$row}_{$column}", [
			'label'       => ucfirst( $row ) . ' ' . ucfirst( $column ) . ' ' . esc_html__( 'Element', 'soledad' ),
			'section'     => $builder_section,
			'input_attrs' => array(
				'multiselect' => true,
			),
			'choices'     => HeaderBuilder::desktop_header_element(),
		] ) );

		$wp_customize->selective_refresh->add_partial( "penci_hb_element_desktop_{$row}_{$column}", array(
			'selector'            => $partial_refresh_id[ $row ],
			'settings'            => [
				"penci_hb_align_desktop_{$row}_{$column}",
				"penci_hb_flex_desktop_{$row}_{$column}",
				"penci_hb_element_desktop_{$row}_{$column}",
			],
			'container_inclusive' => true,
			'render_callback'     => "penci_hb_element_desktop_{$row}_preview_render",
			'fallback_refresh'    => false,
		) );
	}
}

/* Mobile Elements*/
foreach ( $builder_rows_mobile as $row ) {
	foreach ( $builder_columns_mobile as $column ) {
		$wp_customize->add_setting( "penci_hb_align_mobile_{$row}_{$column}", array(
			'default'           => $column,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'penci_sanitize_choices_field'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, "penci_hb_align_mobile_{$row}_{$column}", [
			'type'    => 'select',
			'label'   => __( 'Mobile ', 'soledad' ) . ucfirst( $row ) . ' ' . ucfirst( $column ) . ' ' . esc_html__( 'Align', 'soledad' ),
			'section' => $builder_section,
			'choices' => [
				'left'   => esc_attr__( 'Left', 'soledad' ),
				'center' => esc_attr__( 'Center', 'soledad' ),
				'right'  => esc_attr__( 'Right', 'soledad' ),
			],
		] ) );

		$wp_customize->add_setting( "penci_hb_element_mobile_{$row}_{$column}", array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'penci_text_sanitization'
		) );
		$wp_customize->add_control( new Penci_Dropdown_Select2_Custom_Control( $wp_customize, "penci_hb_element_mobile_{$row}_{$column}", [
			'label'       => __( 'Mobile ', 'soledad' ) . ucfirst( $row ) . ' ' . ucfirst( $column ) . ' ' . esc_html__( 'Element', 'soledad' ),
			'section'     => $builder_section,
			'input_attrs' => array(
				'multiselect' => true,
			),
			'choices'     => HeaderBuilder::mobile_header_element(),
		] ) );

		$wp_customize->selective_refresh->add_partial( "penci_hb_element_mobile_{$row}_{$column}", array(
			'selector'            => $partial_mobile_refresh_id[ $row ],
			'setttings'           => [
				"penci_hb_align_mobile_{$row}_{$column}",
				"penci_hb_element_mobile_{$row}_{$column}"
			],
			'container_inclusive' => true,
			'render_callback'     => "penci_hb_element_mobile_{$row}_preview_render",
			'fallback_refresh'    => false,
		) );
	}
}

/* Header Sticky Elements*/
foreach ( $builder_rows_desktop_sticky as $row => $row_name ) {
	foreach ( $builder_columns_desktop as $column ) {
		$wp_customize->add_setting( "penci_hb_align_desktop_sticky_{$row}_{$column}", array(
			'default'           => $column,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'penci_sanitize_choices_field'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, "penci_hb_align_desktop_sticky_{$row}_{$column}", [
			'type'    => 'select',
			'label'   => __( 'Desktop Sticky ', 'soledad' ) . ucfirst( $row ) . ' ' . ucfirst( $column ) . ' ' . esc_html__( 'Align', 'soledad' ),
			'section' => $builder_section,
			'choices' => [
				'left'   => esc_attr__( 'Left', 'soledad' ),
				'center' => esc_attr__( 'Center', 'soledad' ),
				'right'  => esc_attr__( 'Right', 'soledad' ),
			],
		] ) );


		$wp_customize->add_setting( "penci_hb_element_desktop_sticky_{$row}_{$column}", array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'penci_text_sanitization'
		) );
		$wp_customize->add_control( new Penci_Dropdown_Select2_Custom_Control( $wp_customize, "penci_hb_element_desktop_sticky_{$row}_{$column}", [
			'label'       => __( 'Desktop Sticky ', 'soledad' ) . ucfirst( $row ) . ' ' . ucfirst( $column ) . ' ' . esc_html__( 'Element', 'soledad' ),
			'section'     => $builder_section,
			'input_attrs' => array(
				'multiselect' => true,
			),
			'choices'     => HeaderBuilder::desktop_header_element(),
		] ) );

		$wp_customize->selective_refresh->add_partial( "penci_hb_element_desktop_sticky_{$row}_{$column}", array(
			'selector'            => $partial_sticky_refresh_id[ $row ],
			'setttings'           => [
				"penci_hb_align_desktop_sticky_{$row}_{$column}",
				"penci_hb_element_desktop_sticky_{$row}_{$column}"
			],
			'container_inclusive' => true,
			'render_callback'     => "penci_hb_element_desktop_sticky_{$row}_preview_render",
			'fallback_refresh'    => false,
		) );
	}
}

/* Mobile Sidebar Elements*/
foreach ( $builder_row_sidebar as $row ) {
	foreach ( $builder_columns_sidebar as $column ) {
		$wp_customize->add_setting( "penci_hb_align_mobile_drawer_{$row}_{$column}", array(
			'default'           => $column,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'penci_sanitize_choices_field'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, "penci_hb_align_mobile_drawer_{$row}_{$column}", [
			'type'    => 'select',
			'label'   => __( 'Mobile Sidebar ', 'soledad' ) . ucfirst( $row ) . ' ' . ucfirst( $column ) . ' ' . esc_html__( 'Align', 'soledad' ),
			'section' => $builder_section,
			'choices' => [
				'left'   => esc_attr__( 'Left', 'soledad' ),
				'center' => esc_attr__( 'Center', 'soledad' ),
				'right'  => esc_attr__( 'Right', 'soledad' ),
			],
		] ) );

		$wp_customize->add_setting( "penci_hb_element_mobile_drawer_{$row}_{$column}", array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'penci_text_sanitization'
		) );
		$wp_customize->add_control( new Penci_Dropdown_Select2_Custom_Control( $wp_customize, "penci_hb_element_mobile_drawer_{$row}_{$column}", [
			'label'       => __( 'Mobile Sidebar ', 'soledad' ) . ucfirst( $row ) . ' ' . ucfirst( $column ) . ' ' . esc_html__( 'Element', 'soledad' ),
			'section'     => $builder_section,
			'input_attrs' => array(
				'multiselect' => true,
			),
			'choices'     => HeaderBuilder::mobile_header_element(),
		] ) );

		$wp_customize->selective_refresh->add_partial( "penci_hb_element_mobile_drawer_{$row}_{$column}", array(
			'selector'            => '.penci-mobile-sidebar-content-wrapper',
			'settings'            => [
				"penci_hb_align_mobile_drawer_{$row}_{$column}",
				"penci_hb_element_mobile_drawer_{$row}_{$column}"
			],
			'container_inclusive' => true,
			'render_callback'     => function () {
				load_template( PENCI_BUILDER_PATH . '/template/mobile-menu-content.php' );
			},
			'fallback_refresh'    => false,
		) );
	}
}
