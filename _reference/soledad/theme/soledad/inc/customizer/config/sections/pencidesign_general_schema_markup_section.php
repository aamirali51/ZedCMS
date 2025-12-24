<?php
$options   = [];
$options[] = array(
	'label'    => __( 'Custom General Logo for Schema Markup', 'soledad' ),
	'type'     => 'soledad-fw-image',
	'id'       => 'penci_logo_schema',
	'sanitize' => 'esc_url_raw'
);
$options[] = array(
	'label'    => __( 'Remove WPHeader Schema Data', 'soledad' ),
	'id'       => 'penci_schema_wphead',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Remove WPFooter Schema Data', 'soledad' ),
	'id'       => 'penci_schema_wpfoot',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Remove Site Navigation Schema Data', 'soledad' ),
	'id'       => 'penci_schema_sitenav',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Remove Hentry Schema Data', 'soledad' ),
	'id'       => 'penci_schema_hentry',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Remove General Organization Schema Data', 'soledad' ),
	'id'       => 'penci_schema_organization',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Remove Website Schema Data', 'soledad' ),
	'id'       => 'penci_schema_website',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Remove Breadcrumbs Schema Data', 'soledad' ),
	'id'       => 'penci_schema_breadcrumbs',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Remove Schema Data for Single Posts/Pages', 'soledad' ),
	'id'       => 'penci_schema_single',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Use NewsArticle Schema for All Posts', 'soledad' ),
	'id'       => 'penci_post_use_newsarticle',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);

return $options;
