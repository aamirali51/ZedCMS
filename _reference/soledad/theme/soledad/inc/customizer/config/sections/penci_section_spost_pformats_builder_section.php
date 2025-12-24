<?php
$options = [];

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

$post_formats = [
	'aside'   => 'Aside',
	'chat'    => 'Chat',
	'gallery' => 'Gallery',
	'link'    => 'Link',
	'image'   => 'Image',
	'quote'   => 'Quote',
	'status'  => 'Status',
	'video'   => 'Video',
	'audio'   => 'Audio',
];

if ( ! empty( $post_formats ) ) {

	foreach ( $post_formats as $format => $label ) {

		$options[] = array(
			'default'     => '',
			'description' => __( 'This option will override the single post template. You can add new and edit a builder template on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=custom-post-template' ) ) . '">this page</a>.', 'soledad' ),
			'sanitize'    => 'penci_sanitize_choices_field',
			'label'       => __( $label . ' Posts Format Template', 'soledad' ),
			'id'          => 'penci_' . $format . '_custom_template',
			'type'        => 'soledad-fw-select',
			'choices'     => $single_layout
		);
	}
}

return $options;