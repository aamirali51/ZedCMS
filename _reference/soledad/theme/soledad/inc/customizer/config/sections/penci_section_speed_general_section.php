<?php
$options   = [];
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Activate optimize CSS/JS for logged in users?', 'soledad' ),
	'id'       => 'penci_speed_disable_cssjs',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Emoji and Smilies', 'soledad' ),
	'id'       => 'penci_speed_disable_emoji',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Remove query strings from static resource', 'soledad' ),
	'description' => __( 'Remove query strings for non-login & non-administrator users', 'soledad' ),
	'id'          => 'penci_speed_remove_query_string',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Remove wlwmanifest Link', 'soledad' ),
	'description' => __( 'If you do not use Windows Live Writer, you can disable it.', 'soledad' ),
	'id'          => 'penci_speed_remove_wlwmanifest',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Remove XML-RPC and RSD Link', 'soledad' ),
	'description' => __( 'Check <a class="wp-customizer-link" href="https://codex.wordpress.org/XML-RPC_Support" target="_blank">this post</a> and <a class="wp-customizer-link" target="_blank" href="https://developer.wordpress.org/reference/functions/rsd_link/">this post</a> to understand what is it. In most cases, its not needed, so if you dont need it, you can remove it.', 'soledad' ),
	'id'          => 'penci_speed_remove_xml_rsd',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'sanitize'    => 'sanitize_text_field',
	'label'       => esc_html__( 'Lazyload Images', 'soledad' ),
	'id'          => 'penci_section_speed_lazy_heading',
	'description' => __( "This theme supports lazyload images from itself. But, if you want to use lazyload images from another plugin - let disable the lazyload images below to get it works. But, If you want to use the optimise speed features from our theme, using the lazy load images feature from our theme is required.", "soledad" ),
	'type'        => 'soledad-fw-header',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Delay all images loading in the first screen to optimize LCP value', 'soledad' ),
	'id'       => 'penci_speed_disable_first_screen',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable LazyLoad Images on Category Mega Menu', 'soledad' ),
	'id'       => 'penci_topbar_mega_disable_lazy',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Lazy Load Images on Sliders', 'soledad' ),
	'id'       => 'penci_disable_lazyload_slider',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Lazyload Images on All Posts Layouts & Widgets', 'soledad' ),
	'id'       => 'penci_disable_lazyload_layout',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Lazyload for Featured Image on Single Posts/Pages', 'soledad' ),
	'id'       => 'penci_disable_lazyload_fsingle',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Disable Lazyload Images on Single Posts', 'soledad' ),
	'id'       => 'penci_disable_lazyload_single',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Disable Lazyload for All Iframes', 'soledad' ),
	'id'          => 'penci_disable_lazyload_iframe',
	'description' => __( "Note that: Lazyload iframes just apply when you activate optimize CSS", "soledad" ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => 'maxresdefault',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Youtube Thumbnail Size', 'soledad' ),
	'id'       => 'penci_youtube_thumbnail_size',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'maxresdefault' => __( 'Max Resolution', 'soledad' ),
		'hqdefault'     => __( 'High Quality', 'soledad' ),
		'mqdefault'     => __( 'Medium Quality', 'soledad' ),
		'sddefault'     => __( 'Standard Quality', 'soledad' ),
	),
);
$options[] = array(
	'id'          => 'penci_disable_lazyload_extra',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_textarea_field',
	'label'       => __( 'Exclude Custom CSS Classes from Lazyload', 'soledad' ),
	'description' => __( "Enter one per line to exclude certain CSS class from this optimizations OR a part of image/iframe URL. Examples: <strong>my-css-class</strong>", "soledad" ),
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Lazyload Effects', 'soledad' ),
	'id'       => 'penci_section_speed_lazy_heading_01',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'label'    => __( 'Lazyloaded Effect', 'soledad' ),
	'id'       => 'penci_lazyloaded_effect',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''        => __( 'None', 'soledad' ),
		'style-1' => __( 'Fade In', 'soledad' ),
		'style-2' => __( 'Zoom In & Blur', 'soledad' ),
		'style-3' => __( 'Blur', 'soledad' ),
	),
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
);
$options[] = array(
	'sanitize' => 'sanitize_text_field',
	'label'    => esc_html__( 'Image Library Settings', 'soledad' ),
	'id'       => 'penci_section_speed_lazy_heading_05',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Disable all Unnecessary Image Sizes', 'soledad' ),
	'id'          => 'penci_disable_unnecessary_image_sizes',
	'description' => __( 'Normally WP generates many sizes on upload. You can disable all unnecessary sizes and only keep the ones you actually need.', "soledad" ),
	'type'        => 'soledad-fw-toggle',
);
return $options;
