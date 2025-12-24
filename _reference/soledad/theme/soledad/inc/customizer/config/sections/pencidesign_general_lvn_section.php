<?php
$options   = [];
$options[] = array(
	'label'    => __( 'General', 'soledad' ),
	'id'       => 'penci_live_viewer_heading_01',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'id'       => 'penci_live_viewer_enable',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Live Viewer Notifications', 'soledad' ),
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'id'       => 'penci_live_viewer_singular_text',
	'default'  => '1 viewer reading this {object}',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Text: 1 Viewer', 'soledad' ),
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'id'       => 'penci_live_viewer_plural_text',
	'default'  => '{view} viewers are reading this {object}',
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Text: {view} Viewers', 'soledad' ),
	'type'     => 'soledad-fw-text',
);
$options[] = array(
	'id'       => 'penci_live_viewer_position',
	'default'  => 'bottom-left',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Popup Position', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'choices'  => [
		'bottom-left'  => __( 'Bottom Left', 'soledad' ),
		'bottom-right' => __( 'Bottom Right', 'soledad' ),
		'top-right'    => __( 'Top Right', 'soledad' ),
		'top-left'     => __( 'Top Left', 'soledad' ),
	]
);
$options[] = array(
	'label'    => __( 'Post Type Manager', 'soledad' ),
	'id'       => 'penci_live_viewer_heading_02',
	'type'     => 'soledad-fw-header',
);
$post_type_exclude    = array(
	'attachment',
	'revision',
	'nav_menu_item',
	'safecss',
	'penci-block',
	'penci_builder',
	'custom-post-template',
	'archive-template',
);
$posts_types = get_post_types( [ 'show_in_nav_menus' => true ], 'objects' );

foreach ( $posts_types as $type ) {

	if ( in_array( $type->name, $post_type_exclude ) ) {

		continue;
	}

	$options[] = array(
		'id'       => 'penci_live_viewer_disable_' . $type->name,
		'default'  => false,
		'sanitize' => 'penci_sanitize_checkbox_field',
		'label'    => __( 'Hidden on', 'soledad' ) .  ' ' . $type->label,
		'type'     => 'soledad-fw-toggle',
	);
}

$options[] = array(
	'label'    => __( 'Styles', 'soledad' ),
	'id'       => 'penci_live_viewer_heading_style',
	'type'     => 'soledad-fw-header',
);
$options[] = array(
	'label'    => __( 'Popup Color', 'soledad' ),
	'id'       => 'penci_live_viewer_color',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
);
$options[] = array(
	'label'    => __( 'Popup Background Color', 'soledad' ),
	'id'       => 'penci_live_viewer_bgcolor',
	'default'  => '',
	'sanitize' => 'sanitize_hex_color',
	'type'     => 'soledad-fw-color',
);
return $options;