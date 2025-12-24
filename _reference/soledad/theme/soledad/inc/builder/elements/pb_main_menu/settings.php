<?php 

$options   = [];
$options[] = array(
	'id'              => 'penci_header_pb_main_menu_name',
	'default'         => '',
	'transport'       => 'postMessage',
	'sanitize'        => 'penci_sanitize_choices_field',
	'type'            => 'soledad-fw-select',
	
	'label'           => esc_html__( 'Select Menu', 'soledad' ),
	'choices'         => penci_builder_menu_list(),
	'description'     => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to add new or edit your menus.', 'soledad' ), admin_url( 'nav-menus.php' ) ),
	'partial_refresh' => [
		'penci_header_pb_main_menu_name' => [
			'selector'        => '.pc-wrapbuilder-header-inner',
			'render_callback' => function () {
				load_template( PENCI_BUILDER_PATH . '/template/desktop-builder.php' );
			},
		],
	],
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_header_enable_padding',
	'default'   => 'disable',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Enable Padding on Menu Item Level 1','soledad' ),
	
	'type'      => 'soledad-fw-select',
	'choices'   => [
		'disable' => __('No','soledad' ),
		'enable'  => __('Yes','soledad' ),
	]
);
$options[] = array(
	'id'          => 'penci_header_pb_main_menu_penci_header_remove_line_hover',
	'default'     => 'disable',
	'transport'   => 'postMessage',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __('Hide Line When Hover on Menu Items Level 1','soledad' ),
	
	'description' => __( 'You can change the sub menu style via Customize > Logo & Header > Main Bar & Primary Menu > Sub Menu Style', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => [
		'disable' => __('No','soledad' ),
		'enable'  => __('Yes','soledad' ),
	]
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_font_for_menu',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Custom Font For Menu Items','soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => penci_all_fonts( 'select' )
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_font_weight_menu',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Font Weight For Primary Menu Items','soledad' ),
	
	'type'      => 'soledad-fw-select',
	'choices'   => array(
		''        => '',
		'normal'  => __('Normal','soledad' ),
		'bold'    => __('Bold','soledad' ),
		'bolder'  => __('Bolder','soledad' ),
		'lighter' => __('Lighter','soledad' ),
		'100'     => __('100','soledad' ),
		'200'     => __('200','soledad' ),
		'300'     => __('300','soledad' ),
		'400'     => __('400','soledad' ),
		'500'     => __('500','soledad' ),
		'600'     => __('600','soledad' ),
		'700'     => __('700','soledad' ),
		'800'     => __('800','soledad' ),
		'900'     => __('900','soledad' ),
	)
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_menu_uppercase',
	'default'   => 'disable',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Turn Off Uppercase on Menu items','soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => [
		'disable' => __('No','soledad' ),
		'enable'  => __('Yes','soledad' ),
	]
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_section_slogan_heading',
	'sanitize'  => 'sanitize_text_field',
	'transport' => 'postMessage',
	'label'     => esc_html__( 'Menu Colors', 'soledad' ),
	
	'type'      => 'soledad-fw-header',
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_menu_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Menu Items Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_menu_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Menu Items Hover Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_menu_active_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Menu Items Active Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_menu_line_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Line Hover Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_menu_bg_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Menu Items Background Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_menu_bg_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Menu Items Hover Background Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_submenu_bgcolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Sub Menu Background Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_submenu_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Sub Menu Items Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_submenu_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Sub Menu Items Hover Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_submenu_activecl',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Sub Menu Items Active Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_submenu_bordercolor',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Sub Menu Borders Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_mega_bg_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Category Mega Menu Background Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_mega_child_cat_bg_color',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-color',
	'sanitize'  => 'sanitize_hex_color',
	'label'     => __('Category Mega Menu List Child Categories Background Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_mega_post_category_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Mega Menu Post Category Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_mega_post_title_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Category Mega Menu Post Title Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_mega_post_date_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Category Mega Menu Post Date Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_mega_accent_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Category Mega Menu Accent Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_mega_border_style2',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Borders Color for Category Mega on Menu Style2','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_drop_border_style2',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Dropdown Borders on Hover for Menu Style2','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_section_slogan_heading_1',
	'sanitize'  => 'sanitize_text_field',
	'transport' => 'postMessage',
	'label'     => esc_html__( 'Menu Font Size', 'soledad' ),
	
	'type'      => 'soledad-fw-header',
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_penci_line_height_lv1',
	'default'   => '',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-size',
	'sanitize'  => 'absint',
	'label'     => __( 'Height for Menu Items Level 1', 'soledad' ),
	'ids'       => array(
		'desktop' => 'penci_header_pb_main_menu_penci_line_height_lv1',
	),
	'choices'   => array(
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
	'id'        => 'penci_header_pb_main_menu_penci_font_size_lv1',
	'default'   => '12',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __( 'Font Size for Menu Items Level 1', 'soledad' ),
	'ids'       => array(
		'desktop' => 'penci_header_pb_main_menu_penci_font_size_lv1',
	),
	'choices'   => array(
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
	'id'        => 'penci_header_pb_main_menu_penci_font_size_drop',
	'default'   => '12',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __( 'Font Size for Dropdown Menu Items', 'soledad' ),
	'ids'       => array(
		'desktop' => 'penci_header_pb_main_menu_penci_font_size_drop',
	),
	'choices'   => array(
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
	'id'        => 'penci_header_pb_main_menu_penci_lv1_item_spacing',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __( 'Space Between Menu Items Level 1', 'soledad' ),
	'ids'       => array(
		'desktop' => 'penci_header_pb_main_menu_penci_lv1_item_spacing',
	),
	'choices'   => array(
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
	'id'        => 'penci_header_pb_main_menu_penci_lv1_item_margin',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'absint',
	'type'      => 'soledad-fw-size',
	'label'     => __( 'Margin Between Menu Items Level 1', 'soledad' ),
	'description'     => __( 'This option only applies when you enable padding for menu items level 1', 'soledad' ),
	'ids'       => array(
		'desktop' => 'penci_header_pb_main_menu_penci_lv1_item_margin',
	),
	'choices'   => array(
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
	'id'        => 'penci_header_pb_main_menu_spacing',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'type'      => 'soledad-fw-box-model',
	'label'     => __( 'Element Spacing', 'soledad' ),
	'choices'   => array(
		'margin'  => array(
			'margin-top'    => '',
			'margin-right'  => '',
			'margin-bottom' => '',
			'margin-left'   => '',
		),
		'padding' => array(
			'padding-top'    => '',
			'padding-right'  => '',
			'padding-bottom' => '',
			'padding-left'   => '',
		),
	),
);
$options[] = array(
	'id'        => 'penci_header_pb_main_menu_class',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Custom CSS Class', 'soledad' ),
);


return $options;
