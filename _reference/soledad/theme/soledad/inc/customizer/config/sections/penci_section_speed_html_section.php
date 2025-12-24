<?php
$options   = [];
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Minify HTML', 'soledad' ),
	'description' => __( "Minify HTML won't apply for admin users logged in, so let check how it works without logged in.", "soledad" ),
	'id'          => 'penci_speed_html_minify',
	'type'        => 'soledad-fw-toggle',
);

return $options;
