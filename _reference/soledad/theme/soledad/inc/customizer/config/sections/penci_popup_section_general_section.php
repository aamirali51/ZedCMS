<?php
$options   = array();
$options[] = array(
	'id'          => 'penci_popup_enable',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Enable Promo Popup', 'soledad' ),
	'description' => __( 'Show promo popup to users when they enter the site.', 'soledad' ),
	'section'     => 'penci_popup_section_general',
	'type'        => 'soledad-fw-toggle',
);

$options[]  = array(
	'id'    => 'penci_popup_display_condition',
	'label' => __( 'Display Conditions', 'soledad' ),
	'type'  => 'soledad-fw-header',
);
$post_types = array( 'post', 'page' );
$post_types = array_merge( $post_types, penci_get_published_posttypes() );

if ( class_exists( 'WooCommerce' ) ) {
	$post_types[] = 'product';
}

if ( is_array( $post_types ) && ! empty( $post_types ) ) {

	foreach ( $post_types as $type ) {

		$type_data = get_post_type_object( $type );

		if ( ! empty( $type_data->label ) ) {

			$options[] = array(
				'id'       => 'penci_popup_show_' . $type,
				'sanitize' => 'penci_sanitize_text_field',
				'label'    => __( 'Show Popup for ' . $type_data->label, 'soledad' ),
				'type'     => 'soledad-fw-toggle',
			);
		}
	}
}

$options[] = array(
	'id'          => 'penci_popup_ex_singular_ids',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_text_field',
	'label'       => __( 'Exclude Singular Post IDs', 'soledad' ),
	'description' => __( 'You can check <a class="wp-customizer-link" target="_blank" href="https://ostraining.com/blog/wordpress/how-to-find-the-page-id-in-wordpress/">this guide</a> to know how to find ID of a post or page. Fill the list IDs you want to exclude here, separate by comma. E.g: 10, 20, 34', 'soledad' ),
	'type'        => 'soledad-fw-text',
);

$options[] = array(
	'id'          => 'penci_popup_in_singular_ids',
	'default'     => '',
	'sanitize'    => 'penci_sanitize_text_field',
	'label'       => __( 'Include Singular Post IDs', 'soledad' ),
	'description' => __( 'You can check <a class="wp-customizer-link" target="_blank" href="https://ostraining.com/blog/wordpress/how-to-find-the-page-id-in-wordpress/">this guide</a> to know how to find ID of a post or page. Fill the list IDs you want to exclude here, separate by comma. E.g: 10, 20, 34', 'soledad' ),
	'type'        => 'soledad-fw-text',
);

$options[] = array(
	'id'      => 'penci_popup_exclude_homepages',
	'default' => '',
	'label'   => __( 'Hidden Popup on Homepage', 'soledad' ),
	'type'    => 'soledad-fw-toggle',
);

$options[] = array(
	'id'      => 'penci_popup_exclude_blog',
	'default' => '',
	'label'   => __( 'Hidden Popup on Blog Page', 'soledad' ),
	'type'    => 'soledad-fw-toggle',
);

$options[] = array(
	'id'    => 'penci_popup_display_condition_tax',
	'label' => __( 'Taxonomy Display Conditions', 'soledad' ),
	'type'  => 'soledad-fw-header',
);

foreach ( $post_types as $type ) {

	$type_data = get_post_type_object( $type );
	$post_taxs = get_object_taxonomies( $type, 'objects' );

	$igr_tax = array(
		'post_format',
		'product_type',
		'product_visibility',
		'product_shipping_class',
	);

	foreach ( $igr_tax as $igr ) {
		unset( $post_taxs[ $igr ] );
	}

	foreach ( $post_taxs as $tax ) {

		$options[] = array(
			'id'       => 'penci_popup_archive_' . $tax->name,
			'default'  => '',
			'sanitize' => 'penci_sanitize_text_field',
			'label'    => __( 'Show Popup for ' . $tax->label, 'soledad' ),
			'type'     => 'soledad-fw-toggle',
		);
	}
}


$options[] = array(
	'id'    => 'penci_popup_display_condition_other',
	'label' => __( 'Other Display Conditions', 'soledad' ),
	'type'  => 'soledad-fw-header',
);

$options[] = array(
	'id'      => 'penci_popup_exclude_search',
	'default' => '',
	'label'   => __( 'Hide for Search Page', 'soledad' ),
	'type'    => 'soledad-fw-toggle',
);

$options[] = array(
	'id'      => 'penci_popup_exclude_404',
	'default' => '',
	'label'   => __( 'Hide for 404 Page', 'soledad' ),
	'type'    => 'soledad-fw-toggle',
);

$options[] = array(
	'id'          => 'penci_popup_disable_mobile',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Hide for Mobile Devices', 'soledad' ),
	'description' => __( 'You can disable this option for mobile devices completely.', 'soledad' ),
	'section'     => 'penci_popup_section_general',
	'type'        => 'soledad-fw-toggle',
);

return $options;
