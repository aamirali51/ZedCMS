<?php
$options          = [];
$page_options     = [];
$page_options[''] = '- Default Theme Template -';
$page_layouts     = get_pages();
foreach ( $page_layouts as $page_builder ) {
	$page_options[ $page_builder->post_name ] = $page_builder->post_title;
}
$options   = [];
$options[] = array(
	'id'          => 'fof_page',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_choices_field',
	'type'        => 'soledad-fw-select',
	'label'       => esc_html__( '404 Page Template', 'soledad' ),
	'description' => esc_html__( 'Select the page you\'ve buit with the Elementor.', 'soledad' ),
	'choices'     => $page_options,
);
$options[] = array(
	'sanitize' => 'esc_url_raw',
	'type'     => 'soledad-fw-image',
	'label'    => __( '404 Custom Main Image', 'soledad' ),
	'id'       => 'penci_not_found_image',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Remove Line Below Main Image', 'soledad' ),
	'id'       => 'penci_not_found_removeline',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => "OOPS! Page you're looking for doesn't exist. Please use search for help",
	'sanitize' => 'penci_sanitize_textarea_field',
	'label'    => __( '404 Custom Message Text', 'soledad' ),
	'id'       => 'penci_not_found_sub_heading',
	'type'     => 'soledad-fw-textarea',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide Search Form', 'soledad' ),
	'id'       => 'penci_not_found_hide_search',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Hide "BACK TO HOME PAGE"', 'soledad' ),
	'id'       => 'penci_not_found_hide_backhome',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Message Text', 'soledad' ),
	'id'       => 'penci_notfound_message_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_notfound_message_fsize',
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
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for Text on Search Form', 'soledad' ),
	'id'       => 'penci_notfound_input_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_notfound_input_fsize',
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
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'absint',
	'label'    => __( 'Font Size for "Back To Homepage"', 'soledad' ),
	'id'       => 'penci_notfound_backhome_fsize',
	'type'     => 'soledad-fw-size',
	'ids'      => array(
		'desktop' => 'penci_notfound_backhome_fsize',
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
);

return $options;
