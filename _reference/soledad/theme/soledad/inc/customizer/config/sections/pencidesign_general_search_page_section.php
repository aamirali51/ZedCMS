<?php
$options = [];

$options[] = array(
	'label'    => esc_html__( 'Search Post Types', 'soledad' ),
	'id'       => 'penci_ajaxsearch_heading_01',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field'
);

$options[] = array(
	'label'    => __( 'Include Pages on Search Results', 'soledad' ),
	'id'       => 'penci_include_search_page',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);

$options[] = array(
	'label'    => esc_html__( 'Ajax Live Search', 'soledad' ),
	'id'       => 'penci_ajaxsearch_heading',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field'
);

$options[] = array(
	'label'    => __( 'Enable Ajax Live Search', 'soledad' ),
	'id'       => 'penci_ajxs_enable',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);

$options[] = array(
	'label'    => __( 'Show Post Thumbnail', 'soledad' ),
	'id'       => 'penci_ajxs_thumb',
	'type'     => 'soledad-fw-toggle',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'label'    => __( 'Show Post Format Icon', 'soledad' ),
	'id'       => 'penci_ajxs_fmat_icon',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'label'    => __( 'Show Post Date', 'soledad' ),
	'id'       => 'penci_ajxs_date',
	'type'     => 'soledad-fw-toggle',
	'default'  => true,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'label'    => __( 'Maximum Return Items', 'soledad' ),
	'id'       => 'penci_ajxs_count',
	'type'     => 'soledad-fw-number',
	'default'  => '',
	'sanitize' => 'penci_sanitize_number_field',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'label'    => __( 'Limit Post Title Words', 'soledad' ),
	'id'       => 'penci_ajxs_title_words',
	'type'     => 'soledad-fw-number',
	'default'  => 8,
	'sanitize' => 'penci_sanitize_number_field',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'label'    => esc_html__( 'Ajax Live Search Font Size', 'soledad' ),
	'id'       => 'penci_ajaxsearch_heading_02',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Title', 'soledad' ),
	'id'       => 'penci_ajaxsearch_title_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_ajaxsearch_title_fsize',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '',
		),
	),
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Post Meta', 'soledad' ),
	'id'       => 'penci_ajaxsearch_meta_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_ajaxsearch_meta_fsize',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '',
		),
	),
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Search Notice', 'soledad' ),
	'id'       => 'penci_ajaxsearch_notice_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_ajaxsearch_notice_fsize',
	),
	'choices'  => array(
		'desktop' => array(
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'edit'    => true,
			'unit'    => 'px',
			'default' => '',
		),
	),
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'label'    => esc_html__( 'Ajax Live Search Style', 'soledad' ),
	'id'       => 'penci_ajaxsearch_heading_03',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Title Color', 'soledad' ),
	'id'       => 'penci_ajaxsearch_title_cl',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Title Hover Color', 'soledad' ),
	'id'       => 'penci_ajaxsearch_title_hcl',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Meta Color', 'soledad' ),
	'id'       => 'penci_ajaxsearch_meta_cl',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Post Meta Hover Color', 'soledad' ),
	'id'       => 'penci_ajaxsearch_meta_hcl',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Notice Color', 'soledad' ),
	'id'       => 'penci_ajaxsearch_notice_cl',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Borders Color', 'soledad' ),
	'id'       => 'penci_ajaxsearch_borders_cl',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Result Item Background Color', 'soledad' ),
	'id'       => 'penci_ajaxsearch_bg_cl',
	'active_callback' => [
		[
			'setting'  => 'penci_ajxs_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'label'    => esc_html__( 'Google Search Result', 'soledad' ),
	'id'       => 'penci_gsr_heading',
	'type'     => 'soledad-fw-header',
	'sanitize' => 'sanitize_text_field',
);

$options[] = array(
	'label'    => __( 'Replace WordPress Search Result by Google Search', 'soledad' ),
	'id'       => 'penci_gsr_enable',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);

$options[] = array(
	'label'       => __( 'Google Search Engine ID', 'soledad' ),
	'id'          => 'penci_gsr_id',
	'type'        => 'soledad-fw-text',
	'default'     => '',
	'description' => __( 'Register to Google Custom Search Engine and get your Google Search Engine ID here: <a class="wp-customizer-link" href="https://www.google.com/cse/" target="_blank">https://www.google.com/cse/</a>', 'soledad' ),
	'active_callback' => [
		[
			'setting'  => 'penci_gsr_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'label'    => __( 'Show Google Search Form on Search Result Page', 'soledad' ),
	'id'       => 'penci_gsr_searchform',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'active_callback' => [
		[
			'setting'  => 'penci_gsr_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

$options[] = array(
	'label'    => __( 'Open Search Result In:', 'soledad' ),
	'id'       => 'penci_gsr_target',
	'type'     => 'soledad-fw-select',
	'default'  => '_self',
	'sanitize' => 'penci_sanitize_choices_field',
	'choices'  => [
		'_self'  => 'Same Tab',
		'_blank' => 'New Tab',
	],
	'active_callback' => [
		[
			'setting'  => 'penci_gsr_enable',
			'operator' => '==',
			'value'    => true,
		]
	],
);

return $options;
