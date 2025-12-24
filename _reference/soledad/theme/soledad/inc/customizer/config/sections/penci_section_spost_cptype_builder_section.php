<?php
$options = [];

$post_types = penci_get_published_posttypes();
if ( is_array( $post_types ) && ! empty( $post_types ) ) {
	$single_layout     = [];
	$single_layout[''] = esc_attr__( 'None' );
	$single_layouts    = get_posts( [
		'post_type'      => 'custom-post-template',
		'posts_per_page' => - 1,
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => 'penci_desktop_page_id',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'penci_desktop_page_id',
				'value'   => '',
				'compare' => '=',
			),
		),
	] );
	foreach ( $single_layouts as $slayout ) {
		$single_layout[ $slayout->post_name ] = $slayout->post_title;
	}

	foreach ( $post_types as $type ) {

		$type_data = get_post_type_object( $type );

		$options[] = array(
			'default'     => '',
			'description' => __( 'This option will override the pre-build Single  ' . $type_data->label . ' Template. You can add new and edit a builder template on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=custom-post-template' ) ) . '">this page</a>.', 'soledad' ),
			'sanitize'    => 'penci_sanitize_choices_field',
			'label'       => __( 'Custom Single ' . $type_data->label . ' Template', 'soledad' ),
			'id'          => 'penci_' . $type . '_custom_template',
			'type'        => 'soledad-fw-select',
			'choices'     => $single_layout
		);
	}

} else {
	$options[] = array(
		'default'     => 'notice',
		'description' => '',
		'label'       => __( 'Your website doesn\'t have any custom post types that need to be built.', 'soledad' ),
		'id'          => 'penci_custom_template_notice',
		'type'        => 'soledad-fw-alert',
	);
}

return $options;
