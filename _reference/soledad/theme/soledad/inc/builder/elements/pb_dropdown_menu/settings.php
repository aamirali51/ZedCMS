<?php 

$options   = [];
$options[] = array(
	'id'              => 'penci_header_pb_dropdown_menu_name',
	'default'         => '',
	'transport'       => 'postMessage',
	'sanitize'        => 'penci_sanitize_choices_field',
	'type'            => 'soledad-fw-select',
	
	'label'           => esc_html__( 'Select Menu', 'soledad' ),
	'placeholder'     => esc_html__( 'Select an the menu', 'soledad' ),
	'choices'         => penci_builder_menu_list(),
	'description'     => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to add new or edit your menus.', 'soledad' ), admin_url( 'nav-menus.php' ) ),
	'partial_refresh' => [
		'penci_header_pb_dropdown_menu_name' => [
			'selector'        => '.pc-wrapbuilder-header-inner',
			'render_callback' => function () {
				load_template( PENCI_BUILDER_PATH . '/template/desktop-builder.php' );
			},
		],
	],
);
$options[] = array(
	'id'        => 'penci_header_pb_dropdown_menu_penci_font_for_menu',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Custom Font For Menu Items','soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => penci_all_fonts( 'select' )
);
$options[] = array(
	'id'        => 'penci_header_pb_dropdown_menu_penci_font_weight_menu',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Font Weight For Dropdown Menu Items','soledad' ),
	
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
	'id'        => 'penci_header_pb_dropdown_menu_penci_menu_uppercase',
	'default'   => 'disable',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_choices_field',
	'label'     => __('Turn Off Uppercase on Menu items','soledad' ),
	'type'      => 'soledad-fw-select',
	'choices'   => [
		'disable' => 'No',
		'enable'  => 'Yes',
	]
);
$options[] = array(
	'id'          => 'penci_header_pb_dropdown_menu_penci_vernav_click_parent',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __('Enable click on Parent Menu Item to open Child Menu Items','soledad' ),
	'description' => __('By default, you need to click to the arrow on the right side to open child menu items - this option will help you click on the parent menu items to open child menu items','soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => [
		'disable' => __('No','soledad' ),
		'enable'  => __('Yes','soledad' ),
	]
);
$options[] = array(
	'id'        => 'penci_header_pb_dropdown_menu_penci_font_size_lv1',
	'default'   => '12',
	'sanitize'  => 'absint',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-size',
	'label'     => __( 'Font Size for Menu Items Level 1', 'soledad' ),
	'ids'       => array(
		'desktop' => 'penci_header_pb_dropdown_menu_penci_font_size_lv1',
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
	'id'        => 'penci_header_pb_dropdown_menu_penci_font_size_drop',
	'default'   => '12',
	'transport' => 'postMessage',
	'type'      => 'soledad-fw-size',
	'sanitize'  => 'absint',
	'label'     => __( 'Font Size for Dropdown Menu Items', 'soledad' ),
	'ids'  => array(
		'desktop' => 'penci_header_pb_dropdown_menu_penci_font_size_drop',
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
	'id'        => 'penci_header_pb_dropdown_menu_spacing',
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
	'id'        => 'penci_header_pb_dropdown_menu_penci_menu_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Menu Items Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_dropdown_menu_penci_menu_hv_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Menu Items Hover & Active Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_dropdown_menu_penci_menu_border_color',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'sanitize_hex_color',
	'type'      => 'soledad-fw-color',
	'label'     => __('Menu Items Borders Color','soledad' ),
);
$options[] = array(
	'id'        => 'penci_header_pb_dropdown_menu_class',
	'default'   => '',
	'transport' => 'postMessage',
	'sanitize'  => 'penci_sanitize_textarea_field',
	'type'      => 'soledad-fw-text',
	'label'     => esc_html__( 'Custom CSS Class', 'soledad' ),
);

return $options;
