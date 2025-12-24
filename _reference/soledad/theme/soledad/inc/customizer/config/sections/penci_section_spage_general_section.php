<?php
$options         = [];
$options[]       = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Page Default Template Layout', 'soledad' ),
	'id'          => 'penci_page_default_template_layout',
	'description' => __( 'Check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/images/template.png">this image</a> to know how to change Template of a page.', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''              => __( 'No Sidebar with Container', 'soledad' ),
		'small-width'   => __( 'No Sidebar with Smaller Container Width', 'soledad' ),
		'right-sidebar' => __( 'Page with Right Sidebar', 'soledad' ),
		'left-sidebar'  => __( 'Page with Left Sidebar', 'soledad' ),
		'two-sidebar'   => __( 'Page with Two Sidebars', 'soledad' )
	)
);
$options[]       = array(
	'default'  => '900',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'label'    => __( 'Custom Width for "Page No Sidebar with Smaller Container Width"', 'soledad' ),
	'id'       => 'penci_page_custom_width',
	'ids'      => array(
		'desktop' => 'penci_page_custom_width',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 2000,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '900',
		),
	),
);
$options[]       = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Featured Image Auto Appears', 'soledad' ),
	'id'       => 'penci_page_hide_featured_image',
	'type'     => 'soledad-fw-toggle',
);
$options[]       = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Page Titles', 'soledad' ),
	'id'       => 'penci_page_hide_ptitle',
	'type'     => 'soledad-fw-toggle',
);
$options[]       = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Uppercase on Page Titles', 'soledad' ),
	'id'       => 'penci_page_title_uppercase',
	'type'     => 'soledad-fw-toggle',
);
$options[]       = array(
	'label'       => '',
	'description' => '',
	'id'          => 'penci_page_title_fsize_mobile',
	'type'        => 'soledad-fw-hidden',
	'sanitize'    => 'absint',
	'default'     => '18',
);
$options[]       = array(
	'label'    => __( 'Font Size for Page Titles', 'soledad' ),
	'id'       => 'penci_page_title_fsize',
	'type'     => 'soledad-fw-size',
	'sanitize' => 'absint',
	'default'  => '24',
	'ids'      => array(
		'desktop' => 'penci_page_title_fsize',
		'mobile'  => 'penci_page_title_fsize_mobile',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'default' => '24',
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
		),
		'mobile'  => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '18',
		),
	),
);
$options[]       = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Share Buttons', 'soledad' ),
	'id'       => 'penci_page_share',
	'type'     => 'soledad-fw-toggle',
);
$share_style     = [];
$share_style[''] = 'Inherit from the Single Post Settings';
for ( $i = 1; $i <= 23; $i ++ ) {
	$v                      = $i < 4 ? 's' : 'n';
	$n                      = $i < 4 ? $i : $i - 3;
	$share_style[ $v . $n ] = 'Style ' . $i;
}
$options[] = array(
	'id'       => 'penci_page_style_cscount',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Share Box Style', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => $share_style,
	'active_callback' => [
		[
			'setting'  => 'penci_page_share',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Social Share Plus Button', 'soledad' ),
	'id'       => 'penci_page_share_disbtnplus',
	'type'     => 'soledad-fw-toggle',
	'active_callback' => [
		[
			'setting'  => 'penci_page_share',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'id'       => 'penci_page_align_cscount',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Share Box Alignment', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''       => __('Default Theme Style','soledad' ),
		'left'   => __('Left','soledad' ),
		'right'  => __('Right','soledad' ),
		'center' => __('Center','soledad' ),
	),
	'active_callback' => [
		[
			'setting'  => 'penci_page_share',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for "Share" Text', 'soledad' ),
	'id'       => 'penci_page_sharetext_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_page_sharetext_fsize',
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
	'active_callback' => [
		[
			'setting'  => 'penci_page_share',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Social Share Icons', 'soledad' ),
	'id'       => 'penci_page_shareicon_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_page_shareicon_fsize',
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
	'active_callback' => [
		[
			'setting'  => 'penci_page_share',
			'operator' => '!=',
			'value'    => true,
		]
	],
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Comments', 'soledad' ),
	'id'       => 'penci_page_comments',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Header Transparent', 'soledad' ),
	'id'       => 'penci_header_enable_transparent',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => 'main-sidebar',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Sidebar for Pages', 'soledad' ),
	'id'          => 'penci_sidebar_name_pages',
	'description' => __( 'If sidebar your choice is empty, will display Main Sidebar.', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => get_list_custom_sidebar_option()
);
$options[] = array(
	'default'     => 'main-sidebar-left',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Sidebar Left for Pages', 'soledad' ),
	'id'          => 'penci_sidebar_left_name_pages',
	'description' => __( 'If sidebar your choice is empty, will display Main Sidebar Left. This option just apply when you use 2 sidebars for Pages', 'soledad' ),
	'type'        => 'soledad-fw-select',
	'choices'     => get_list_custom_sidebar_option()
);

return $options;
