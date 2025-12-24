<?php
$options   = [];
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Enable Menu Hamburger', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_menu_hbg_show',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Enable Vertical Navigation', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_vertical_nav_show',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Completely Delete The Header When Enable Vertical Navigation', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_vertical_nav_remove_header',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Hide Menu Hamburger Icon Display on Horizontal Navigation on Mobile', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_menu_hbg_mobile',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Use Penci Block Content', 'soledad' ),
	'id'       => 'penci_vertical_block',
	'type'     => 'soledad-fw-ajax-select',
	'choices'     => call_user_func( function () {
		$builder_layout  = [ '' => __( '- Select -', 'soledad' ) ];
		$builder_layouts = get_posts( [
			'post_type'      => 'penci-block',
			'posts_per_page' => - 1,
		] );

		foreach ( $builder_layouts as $builder_builder ) {
			$builder_layout[ $builder_builder->post_name ] = $builder_builder->post_title;
		}

		return $builder_layout;
	} ),
);
$options[] = array(
	'type'     => 'soledad-fw-image',
	'sanitize' => 'esc_url_raw',
	'label'    => esc_html__( 'Set A Background Image', 'soledad' ),
	'id'       => 'penci_menu_hbg_bgimg',
);
$options[] = array(
	'default'     => '330',
	'sanitize'    => 'absint',
	'label'       => __( 'Custom Width for Vertical Nav & Menu Hamburger', 'soledad' ),
	'description' => __( 'Min is 250px, Max is 500px', 'soledad' ),
	'id'          => 'penci_hbg_width',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_hbg_width',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '330',
		),
	),
);
$options[] = array(
	'default'     => '18',
	'sanitize'    => 'absint',
	'label'       => __( 'Custom Size for Hamburger Menu Icon', 'soledad' ),
	'description' => __( 'Min is 14px, Max is 30px', 'soledad' ),
	'id'          => 'penci_hbg_size_icon',
	'type'        => 'soledad-fw-size',
	'ids'         => array(
		'desktop' => 'penci_hbg_size_icon',
	),
	'choices'     => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '18',
		),
	),
);
$options[] = array(
	'default'  => 'left',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => esc_html__( 'Position in Page', 'soledad' ),
	'id'       => 'penci_menu_hbg_pos',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'left'  => esc_html__( 'Left', 'soledad' ),
		'right' => esc_html__( 'Right', 'soledad' )
	)
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Hide Logo', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_menu_hbg_hide_logo',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => esc_html__( 'Custom Logo Image', 'soledad' ),
	'id'       => 'penci_menu_hbg_logo',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Custom Link for Logo Image', 'soledad' ),
	'id'       => 'penci_custom_logo_hamburger',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Set A Max Width for Logo Image', 'soledad' ),
	'id'       => 'penci_hbg_logo_max_width',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_hbg_logo_max_width',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 2000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => esc_html__( 'Add Site Title', 'soledad' ),
	'id'       => 'penci_menu_hbg_sitetitle',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'default'  => '18',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Site Title', 'soledad' ),
	'id'       => 'penci_hbg_sitetitle_size',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_hbg_sitetitle_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '18',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => esc_html__( 'Add Site Description', 'soledad' ),
	'id'       => 'penci_menu_hbg_desc',
	'type'     => 'soledad-fw-textarea',
);
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Site Description', 'soledad' ),
	'id'       => 'penci_hbg_sitedes_size',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_hbg_sitedes_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Add Search Form Below Site Description', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_menu_hbg_show_search',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Select Custom Menu to Display on Primary Menu', 'soledad' ),
	'id'       => 'penci_menu_hbg_primary',
	'type'     => 'soledad-fw-ajax-select',
	'choices'  => call_user_func( function () {
		$menu_list = [ '' => '' ];
		$menus     = wp_get_nav_menus();
		if ( ! empty( $menus ) ) {
			foreach ( $menus as $menu ) {
				$menu_list[ $menu->slug ] = $menu->name;
			}
		}

		return $menu_list;
	} ),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Hide Primary Menu', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_menu_hbg_hide_menu',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable click on Parent Menu Item to open Child Menu Items', 'soledad' ),
	'description' => __( 'By default, you need to click to the arrow on the right side to open child menu items - this option will help you click on the parent menu items to open child menu items', 'soledad' ),
	'id'          => 'penci_menu_hbg_click_parent',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Disable Uppercase on Primary Menu Items', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_menu_hbg_lowcase',
);
$options[] = array(
	'default'  => '13',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Primary Menu Items', 'soledad' ),
	'id'       => 'penci_font_size_menu_hbg',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_font_size_menu_hbg',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '13',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Sub-menu Items', 'soledad' ),
	'id'       => 'penci_font_size_submenu_hbg',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_font_size_submenu_hbg',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Hide Footer Text', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_menu_hbg_hideftext',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => esc_html__( 'Hide Social Icons', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
	'id'       => 'penci_menu_hbg_hidesocial',
);
$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => esc_html__( 'Select Style for Social Icons', 'soledad' ),
	'id'          => 'penci_menuhbg_social_style',
	'description' => __( 'The options for custom colors, background color, border for social icons will does not apply for Brand Color styles', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''                                                => esc_html__( 'Default', 'soledad' ),
		'style-6 penci-social-textcolored'                => esc_html__( 'Icons with Brand Color', 'soledad' ),
		'style-2'                                         => esc_html__( 'Round with Border', 'soledad' ),
		'style-2 hgb-social-style-3'                      => esc_html__( 'Square with Border', 'soledad' ),
		'style-4 penci-social-colored'                    => esc_html__( 'Round with Brand Color', 'soledad' ),
		'style-4 hgb-social-style-5 penci-social-colored' => esc_html__( 'Square with Brand Color', 'soledad' ),
	)
);
$options[] = array(
	'default'  => '14',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Social Media Icons', 'soledad' ),
	'id'       => 'penci_menuhbg_icon_size',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_menuhbg_icon_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => esc_html__( 'Custom Footer Text', 'soledad' ),
	'id'       => 'penci_menu_hbg_footer_text',
	'type'     => 'soledad-fw-textarea',
);

return $options;
