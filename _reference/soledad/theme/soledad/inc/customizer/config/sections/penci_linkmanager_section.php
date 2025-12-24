<?php
$options   = [];
$options[] = array(
	'id'       => 'penci_linkmg_external_link_heading',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'External links', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'       => 'penci_linkmg_external_link_enable',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable for External links', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_linkmg_external_link_target',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Open external links:', 'soledad' ),
	'choices'  => [
		''       => __( 'Keep default', 'soledad' ),
		'_self'  => __( 'in the same window, tab or frame', 'soledad' ),
		'_blank' => __( 'each in a separate new window or tab', 'soledad' ),
		'_new'   => __( 'all in the same new window or tab (NOT recommended)', 'soledad' ),
		'_top'   => __( 'in the topmost frame (NOT recommended)', 'soledad' ),
	],
);
$options[] = array(
	'id'       => 'penci_linkmg_external_link_rel_follow',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Follow Options:', 'soledad' ),
	'choices'  => [
		''         => __( 'Keep default', 'soledad' ),
		'follow'   => __( 'follow', 'soledad' ),
		'nofollow' => __( 'nofollow', 'soledad' ),
	],
);
$options[] = array(
	'id'       => 'penci_linkmg_external_rel_options',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-multi-check',
	'label'    => esc_html__( 'Add to rel attribute:', 'soledad' ),
	'choices'  => [
		'rel_noopener'   => [ 'name' => __( 'Add "noopener"', 'soledad' ), 'value' => 'rel_noopener' ],
		'rel_noreferrer' => [ 'name' => __( 'Add "noreferrer"', 'soledad' ), 'value' => 'rel_noreferrer' ],
		'rel_external'   => [ 'name' => __( 'Add "external"', 'soledad' ), 'value' => 'rel_external' ],
		'rel_sponsored'  => [ 'name' => __( 'Add "sponsored"', 'soledad' ), 'value' => 'rel_sponsored' ],
		'rel_ugc'        => [ 'name' => __( 'Add "ugc"', 'soledad' ), 'value' => 'rel_ugc' ],
	],
);
$options[] = array(
	'id'       => 'penci_linkmg_interal_link_heading',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Interal links', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'       => 'penci_linkmg_interal_link_enable',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable for Internal links', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_linkmg_interal_link_target',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Open external links:', 'soledad' ),
	'choices'  => [
		''       => __( 'Keep default', 'soledad' ),
		'_self'  => __( 'in the same window, tab or frame', 'soledad' ),
		'_blank' => __( 'each in a separate new window or tab', 'soledad' ),
		'_new'   => __( 'all in the same new window or tab (NOT recommended)', 'soledad' ),
		'_top'   => __( 'in the topmost frame (NOT recommended)', 'soledad' ),
	],
);
$options[] = array(
	'id'       => 'penci_linkmg_interal_link_rel_follow',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Follow Options:', 'soledad' ),
	'choices'  => [
		''         => __( 'Keep default', 'soledad' ),
		'follow'   => __( 'follow', 'soledad' ),
		'nofollow' => __( 'nofollow', 'soledad' ),
	],
);
$options[] = array(
	'id'       => 'penci_linkmg_interal_rel_options',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-multi-check',
	'label'    => esc_html__( 'Add to rel attribute:', 'soledad' ),
	'choices'  => [
		'rel_noopener'   => [ 'name' => __( 'Add "noopener"', 'soledad' ), 'value' => 'rel_noopener' ],
		'rel_noreferrer' => [ 'name' => __( 'Add "noreferrer"', 'soledad' ), 'value' => 'rel_noreferrer' ],
	],
);
$options[] = array(
	'id'       => 'penci_linkmg_exceptions_heading',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Exceptions Links', 'soledad' ),
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'          => 'penci_linkmg_exceptions_ids',
	'label'       => __( 'Skip pages or posts (id\'s):', 'soledad' ),
	'description' => __( 'Separate postID/pageID by comma.', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'id'          => 'penci_linkmg_exceptions_class',
	'label'       => __( 'Ignore links by class', 'soledad' ),
	'description' => __( 'Separate classes by comma.', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'id'          => 'penci_linkmg_exceptions_subdomain',
	'label'       => __( 'Make subdomains internal', 'soledad' ),
	'description' => __( 'Treat all links to the site\'s domain and subdomains as internal links', 'soledad' ),
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'id'          => 'penci_linkmg_exceptions_ieurl',
	'label'       => __( 'Include external links by URL', 'soledad' ),
	'description' => __( 'Separate url\'s by comma and/or a line break. Write the url\'s as specific as you want them to match.', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
);
$options[] = array(
	'id'          => 'penci_linkmg_exceptions_eeurl',
	'label'       => __( 'Exclude external links by URL', 'soledad' ),
	'description' => __( 'Separate url\'s by comma and/or a line break. Write the url\'s as specific as you want them to match.', 'soledad' ),
	'type'        => 'soledad-fw-textarea',
);

return $options;