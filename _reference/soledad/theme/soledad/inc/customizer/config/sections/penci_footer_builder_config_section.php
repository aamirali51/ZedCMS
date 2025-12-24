<?php
$footer_options     = [];
$footer_options[''] = __( '- Select -', 'soledad' );
$builder_layouts    = get_posts( [
	'post_type'      => 'penci-block',
	'posts_per_page' => - 1,
] );
foreach ( $builder_layouts as $builder_builder ) {
	$footer_options[ $builder_builder->post_name ] = $builder_builder->post_title;
}
$options = [];
/* Saved Layout */
$options[] = array(
	'id'       => 'penci_footer_builder_layout',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'General Footer Builder for All Pages', 'soledad' ),
	'choices'  => $footer_options,
);

$options[] = array(
	'id'       => 'penci_footer_builder_layout_homepage',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Footer Builder for Homepage', 'soledad' ),
	'choices'  => $footer_options,
);

$options[] = array(
	'id'       => 'penci_footer_builder_layout_archive',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Footer Builder for Category,Tag, Search, Archive Pages', 'soledad' ),
	'choices'  => $footer_options,
);

$options[] = array(
	'id'       => 'penci_footer_builder_layout_page',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Footer Builder for Pages', 'soledad' ),
	'choices'  => $footer_options,
);

$options[] = array(
	'id'       => 'penci_footer_builder_layout_post',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'label'    => esc_html__( 'Footer Builder for Single Post Pages', 'soledad' ),
	'choices'  => $footer_options,
);

return $options;
