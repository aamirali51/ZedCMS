<?php

$header_options     = [];
$header_options[''] = __( '- Select -', 'soledad' );
$header_layouts     = get_posts( [
	'post_type'      => 'penci_builder',
	'posts_per_page' => - 1,
] );
foreach ( $header_layouts as $header_builder ) {
	$header_options[ $header_builder->post_name ] = $header_builder->post_title;
}

$header_block_options     = [];
$header_block_options[''] = __( '- Select -', 'soledad' );
$builder_layouts          = get_posts( [
	'post_type'      => 'penci-block',
	'posts_per_page' => - 1,
] );
foreach ( $builder_layouts as $builder_builder ) {
	$header_block_options[ $builder_builder->post_name ] = $builder_builder->post_title;
}

$options = [];

$options[] = array(
	'id'      => 'pchdbd_df_title',
	'default' => '',
	'type'    => 'soledad-fw-header',
	'label'   => esc_html__( 'Soledad Header Builder', 'soledad' ),
);
$options[] = array(
	'id'       => 'pchdbd_all',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'General Header Builder for All Pages', 'soledad' ),
	'choices'  => $header_options,
);

$options[] = array(
	'id'       => 'pchdbd_homepage',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Header Builder for Homepage', 'soledad' ),
	'choices'  => $header_options,
);

$options[] = array(
	'id'       => 'pchdbd_archive',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Header Builder for Category,Tag, Search, Archive Pages', 'soledad' ),
	'choices'  => $header_options,
);

$options[] = array(
	'id'       => 'pchdbd_post',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Header Builder for Single Post Pages', 'soledad' ),
	'choices'  => $header_options,
);

$options[] = array(
	'id'       => 'pchdbd_page',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Header Builder for Pages', 'soledad' ),
	'choices'  => $header_options,
);
if ( class_exists( 'WooCommerce' ) ) {
	$options[] = array(
		'id'       => 'pchdbd_woocommerce',
		'default'  => '',
		'sanitize' => 'penci_sanitize_choices_field',
		'type'     => 'soledad-fw-select',
		'label'    => esc_html__( 'Header Builder for WooCommerce', 'soledad' ),
		'choices'  => $header_options,
	);
}
$options[] = array(
	'id'      => 'pchdbd_el_title',
	'default' => '',
	'type'    => 'soledad-fw-header',
	'label'   => esc_html__( 'Penci Block Header Builder', 'soledad' ),
	'description'   => esc_html__( 'The options below will overwrite all the header layouts you have selected above.', 'soledad' ),
);
$options[] = array(
	'id'       => 'pchdbd_block_all',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'General Header Builder for All Pages', 'soledad' ),
	'choices'  => $header_block_options,
);

$options[] = array(
	'id'       => 'pchdbd_block_homepage',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Header Builder for Homepage', 'soledad' ),
	'choices'  => $header_block_options,
);

$options[] = array(
	'id'       => 'pchdbd_block_archive',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Header Builder for Category,Tag, Search, Archive Pages', 'soledad' ),
	'choices'  => $header_block_options,
);

$options[] = array(
	'id'       => 'pchdbd_block_post',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Header Builder for Single Post Pages', 'soledad' ),
	'choices'  => $header_block_options,
);

$options[] = array(
	'id'       => 'pchdbd_block_page',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Header Builder for Pages', 'soledad' ),
	'choices'  => $header_block_options,
);
if ( class_exists( 'WooCommerce' ) ) {
	$options[] = array(
		'id'       => 'pchdbd_block_woocommerce',
		'default'  => '',
		'sanitize' => 'penci_sanitize_choices_field',
		'type'     => 'soledad-fw-select',
		'label'    => esc_html__( 'Header Builder for WooCommerce', 'soledad' ),
		'choices'  => $header_block_options,
	);
}

return $options;
