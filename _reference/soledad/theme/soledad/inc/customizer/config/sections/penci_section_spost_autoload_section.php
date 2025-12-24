<?php
$options   = [];
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Infinity Scrolling Load Posts', 'soledad' ),
	'id'       => 'penci_loadnp_posts',
	'type'     => 'soledad-fw-toggle',
);

$options[] = array(
	'default'     => 'prev',
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Load Posts Type', 'soledad' ),
	'id'          => 'penci_loadnp_type',
	'description'=>'',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		'prev'     => __('Previous Posts','soledad' ),
		'next'     => __('Next Posts','soledad' ),
		'prev_cat' => __('Previous Posts has Same Categories','soledad' ),
		'next_cat' => __('Next Posts has Same Categories','soledad' ),
		'prev_tag' => __('Previous Posts has Same Tags','soledad' ),
		'next_tag' => __('Next Posts has Same Tags','soledad' ),
	)
);

$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Exclude Specific Posts from Loads', 'soledad' ),
	'id'          => 'penci_loadnp_exclude',
	'description' => __( 'Exclude Posts by ID Separated by the comma. E.g: 12, 22, 335. You can check <a class="wp-customizer-link" href="https://pagely.com/blog/find-post-id-wordpress/" target="_blank">this guide</a> to know how to find the ID of a post', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
);

$options[] = array(
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Add Google Adsense/Custom HTML code Between Posts When Load Posts', 'soledad' ),
	'description' => __( 'If you use Google Ads here, please use normal Google Ads here - don\'t use Google Auto Ads to get it appears in the correct place.', 'soledad' ),
	'id'          => 'penci_loadnp_ads',
	'type'        => 'soledad-fw-code',
	'code_type'   => 'text/html',
);

$options[] = array(
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
	'label'    => __( 'Custom Color for Loading Icon', 'soledad' ),
	'id'       => 'penci_loadnp_ldscolor',
);

return $options;
