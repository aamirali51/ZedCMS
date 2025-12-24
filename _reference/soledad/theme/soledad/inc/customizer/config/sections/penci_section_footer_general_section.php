<?php
$options   = [];
$options[] = array(
	'label'       => __( 'Add Google Adsense/Custom HTML Code Above Footer', 'soledad' ),
	'id'          => 'penci_footer_adsense',
	'description' => '',
	'type'        => 'soledad-fw-code',
	'code_type'   => 'text/html',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field'
);
$options[] = array(
	'label'    => __( 'Footer Container Width', 'soledad' ),
	'id'       => 'penci_footer_width',
	'type'     => 'soledad-fw-select',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'choices'  => array(
		''          => esc_html__( 'Width: 1170px', 'soledad' ),
		'1400'      => esc_html__( 'Width: 1400px', 'soledad' ),
		'fullwidth' => esc_html__( 'Full Width', 'soledad' )
	)
);
$options[] = array(
	'label'    => __( 'Re-order Sections on the Footer', 'soledad' ),
	'id'       => 'penci_footer_order_sections',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'widgets-instagram-signupform-footersocial' => __( 'Widgets Area - Instagram - SignUp Form - Social Icons', 'soledad' ),
		'widgets-instagram-footersocial-signupform' => __( 'Widgets Area - Instagram - Social Icons - SignUp Form', 'soledad' ),
		'widgets-footersocial-instagram-signupform' => __( 'Widgets Area - Social Icons - Instagram - SignUp Form', 'soledad' ),
		'widgets-footersocial-signupform-instagram' => __( 'Widgets Area - Social Icons - SignUp Form - Instagram', 'soledad' ),
		'widgets-signupform-footersocial-instagram' => __( 'Widgets Area - SignUp Form - Social Icons - Instagram', 'soledad' ),
		'widgets-signupform-instagram-footersocial' => __( 'Widgets Area - SignUp Form - Instagram - Social Icons', 'soledad' ),
		'instagram-widgets-signupform-footersocial' => __( 'Instagram - Widgets Area - SignUp Form - Social Icons', 'soledad' ),
		'instagram-widgets-footersocial-signupform' => __( 'Instagram - Widgets Area - Social Icons - SignUp Form', 'soledad' ),
		'instagram-footersocial-widgets-signupform' => __( 'Instagram - Social Icons - Widgets Area - SignUp Form', 'soledad' ),
		'instagram-footersocial-signupform-widgets' => __( 'Instagram - Social Icons - SignUp Form - Widgets Area', 'soledad' ),
		'instagram-signupform-footersocial-widgets' => __( 'Instagram - SignUp Form - Social Icons - Widgets Area', 'soledad' ),
		'instagram-signupform-widgets-footersocial' => __( 'Instagram - SignUp Form - Widgets Area - Social Icons', 'soledad' ),
		'signupform-widgets-footersocial-instagram' => __( 'SignUp Form - Widgets Area - Social Icons - Instagram', 'soledad' ),
		'signupform-widgets-instagram-footersocial' => __( 'SignUp Form - Widgets Area - Instagram - Social Icons', 'soledad' ),
		'signupform-footersocial-widgets-instagram' => __( 'SignUp Form - Social Icons - Widgets Area - Instagram', 'soledad' ),
		'signupform-footersocial-instagram-widgets' => __( 'SignUp Form - Social Icons - Instagram - Widgets Area', 'soledad' ),
		'signupform-instagram-widgets-footersocial' => __( 'SignUp Form - Instagram - Widgets Area - Social Icons', 'soledad' ),
		'signupform-instagram-footersocial-widgets' => __( 'SignUp Form - Instagram - Social Icons - Widgets Area', 'soledad' ),
		'footersocial-widgets-instagram-signupform' => __( 'Social Icons - Widgets Area - Instagram - SignUp Form', 'soledad' ),
		'footersocial-widgets-signupform-instagram' => __( 'Social Icons - Widgets Area - SignUp Form - Instagram', 'soledad' ),
		'footersocial-instagram-signupform-widgets' => __( 'Social Icons - Instagram - SignUp Form - Widgets Area', 'soledad' ),
		'footersocial-instagram-widgets-signupform' => __( 'Social Icons - Instagram - Widgets Area - SignUp Form', 'soledad' ),
		'footersocial-signupform-widgets-instagram' => __( 'Social Icons - SignUp Form - Widgets Area - Instagram', 'soledad' ),
		'footersocial-signupform-instagram-widgets' => __( 'Social Icons - SignUp Form - Instagram - Widgets Area', 'soledad' ),
	),
	'default'  => 'widgets-instagram-signupform-footersocial',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'       => __( 'Enable Footer Content Delayed', 'soledad' ),
	'description' => __( 'This option doesn\'t work with the Footer Builder from Elementor itself.', 'soledad' ),
	'id'          => 'penci_footer_delayed',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Disable Footer Social Icons', 'soledad' ),
	'id'       => 'penci_footer_social',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Disable Border Around Footer Social Icons', 'soledad' ),
	'id'       => 'penci_footer_social_around',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Enable Use Brand Colors for Footer Social Icons', 'soledad' ),
	'id'       => 'penci_footer_brand_social',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Disable Border Radius on Border of Social Icons', 'soledad' ),
	'id'       => 'penci_footer_disable_radius_social',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Hide Footer Social Icons Text', 'soledad' ),
	'id'       => 'penci_footer_social_remove_text',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Make Footer Social Text Drop In New Line', 'soledad' ),
	'id'       => 'penci_footer_social_drop_line',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Font Size for Icons on Footer Social Icons', 'soledad' ),
	'id'       => 'penci_footer_social_size',
	'default'  => '14',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_footer_social_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'label'    => __( 'Disable Uppercase on Footer Social Icons Text', 'soledad' ),
	'id'       => 'penci_footer_social_lowercase',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Font Size for Footer Social Icons Text', 'soledad' ),
	'id'       => 'penci_footer_social_text_size',
	'default'  => '14',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_footer_social_text_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'label'    => __( 'Disable Footer Logo', 'soledad' ),
	'section'  => 'penci_section_footer_general',
	'id'       => 'penci_hide_footer_logo',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'label'    => __( 'Footer Logo', 'soledad' ),
	'section'  => 'penci_section_footer_general',
	'id'       => 'penci_footer_logo',
	'default'  => '',
	'sanitize' => 'esc_url_raw',
	'type'     => 'soledad-fw-image'
);
$options[] = array(
	'label'       => '',
	'description' => '',
	'id'          => 'penci_footer_mwlogo_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
);
$options[] = array(
	'label'    => __( 'Set A Max-Width for Footer Logo', 'soledad' ),
	'id'       => 'penci_footer_mwlogo',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'ids'      => array(
		'desktop' => 'penci_footer_mwlogo',
		'mobile'  => 'penci_footer_mwlogo_mobile',
	),
	'choices'  => array(
		'desktop' => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
		'mobile'  => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'label'       => __( 'Custom Link for Footer Logo Image', 'soledad' ),
	'id'          => 'penci_custom_url_logo_footer',
	'description' => __( 'By default, footer logo image will link to homepage url. If you want to link the footer logo for another URL - fill here. Include http:// or https:// on the link', 'soledad' ),
	'type'        => 'soledad-fw-text',
	'default'     => '',
	'sanitize'    => 'sanitize_text_field'
);
$options[] = array(
	'label'    => __( 'Go To Top Style', 'soledad' ),
	'id'       => 'penci_go_to_top_style',
	'type'     => 'soledad-fw-select',
	'default'  => 'style-1',
	'sanitize' => 'penci_sanitize_choices_field',
	'choices'  => array(
		'style-1' => esc_html__( 'Style 1', 'soledad' ),
		'style-2' => esc_html__( 'Style 2', 'soledad' ),
		'style-3' => esc_html__( 'Style 3', 'soledad' ),
	)
);
$options[] = array(
	'label'    => __( 'Disable Go To Top Button on Footer', 'soledad' ),
	'id'       => 'penci_go_to_top',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Enable Go To Top Button Floating on The Bottom Right', 'soledad' ),
	'id'       => 'penci_go_to_top_floating',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Enable Footer Menu', 'soledad' ),
	'id'          => 'penci_footer_menu',
	'description' => __( 'You can setup your footer menu by go to admin > Appearance > Menus > Create/Select your menu > scroll down and check to "Footer Menu" at the bottom.', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Font Size for Footer Menu', 'soledad' ),
	'id'       => 'penci_footer_menu_size',
	'default'  => '14',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_footer_menu_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'label'    => __( 'Footer Copyright Text', 'soledad' ),
	'id'       => 'penci_footer_copyright',
	'type'     => 'soledad-fw-textarea',
	'default'  => __( '@2020 - All Right Reserved. Designed and Developed by <a class="wp-customizer-link" rel="nofollow" href="https://1.envato.market/YYJ4P" target="_blank">PenciDesign</a>', 'soledad' ),
	'sanitize' => 'penci_sanitize_textarea_field'
);
$options[] = array(
	'label'    => __( 'Disable Italic on Footer Copyright Text', 'soledad' ),
	'id'       => 'penci_footer_copyright_remove_italic',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Font Size for Footer Copyright Text', 'soledad' ),
	'id'       => 'penci_footer_copyright_size',
	'default'  => '14',
	'sanitize' => 'absint',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_footer_copyright_size',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '14',
		),
	),
);
$options[] = array(
	'label'     => __( 'Add Custom HTML code before close </body> tag / Google Analytics Code', 'soledad' ),
	'id'        => 'penci_footer_analytics',
	'type'      => 'soledad-fw-code',
	'code_type' => 'text/html',
	'default'   => '',
	'sanitize'  => 'penci_sanitize_textarea_field'
);

return $options;
