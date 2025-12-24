<?php
$options   = [];
$options[] = array(
	'default'  => 'default',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Vertical Mobile Nav Style', 'soledad' ),
	'id'       => 'penci_moble_vertical_menu_style',
	'type'     => 'soledad-fw-ajax-select',
	'choices'  => [
		'default' 	=> __('Default','soledad'),
		'fullwidth' => __('Full Width','soledad'),
	],
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Use Penci Block Content for Vertical Mobile Nav', 'soledad' ),
	'id'       => 'penci_moble_vertical_block',
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
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Delayed Load Vertical Mobile Content', 'soledad' ),
	'id'       => 'penci_mobile_nav_delayed',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'label'    => __( 'Logo for Vertical Mobile Nav', 'soledad' ),
	'id'       => 'penci_mobile_nav_logo',
	'type'     => 'soledad-fw-image',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_text_field',
	'label'    => __( 'Custom Link for Logo Image on Vertical Mobile Nav', 'soledad' ),
	'id'       => 'penci_custom_url_logo_vertical',
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Logo on Vertical Mobile Nav', 'soledad' ),
	'id'       => 'penci_header_logo_vertical',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove The Line Below Logo Image', 'soledad' ),
	'id'       => 'penci_vernav_remove_line',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Social Icons on Vertical Mobile Nav', 'soledad' ),
	'id'       => 'penci_header_social_vertical',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Use Brand Colors for Social Icons on Vertical Mobile Nav', 'soledad' ),
	'id'       => 'penci_header_social_vertical_brand',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Display Search Form on Vertical Mobile Nav', 'soledad' ),
	'id'       => 'penci_vernav_searchform',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Turn of Uppercase on Menu Items', 'soledad' ),
	'id'       => 'penci_vernav_off_uppercase',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable click on Parent Menu Item to open Child Menu Items', 'soledad' ),
	'description' => __( 'By default, you need to click to the arrow on the right side to open child menu items - this option will help you click on the parent menu items to open child menu items', 'soledad' ),
	'id'          => 'penci_vernav_click_parent',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Select Custom Menu for Vertical Mobile Nav', 'soledad' ),
	'id'       => 'penci_moble_vertical_menu',
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
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Social Icons', 'soledad' ),
	'id'       => 'penci_font_icons_mobile_nav',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_font_icons_mobile_nav',
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
	'default'  => '13',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Vertical Mobile Menu Items', 'soledad' ),
	'id'       => 'penci_font_size_mobile_nav',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_font_size_mobile_nav',
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
	'label'    => __( 'Font Size for text on Search Form', 'soledad' ),
	'id'       => 'penci_vernav_fsearchinput',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_vernav_fsearchinput',
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
	'sanitize' => 'absint',
	'label'    => __( 'Custom Width for Vertical Mobile', 'soledad' ),
	'description'    => __( 'Supports a minimum width of 200px and a maximum width of 330px.', 'soledad' ),
	'id'       => 'penci_vernav_width',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_vernav_width',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 200,
			'max'  => 330,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
return $options;
