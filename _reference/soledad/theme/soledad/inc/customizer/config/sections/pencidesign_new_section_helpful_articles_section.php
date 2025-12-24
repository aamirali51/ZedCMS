<?php
$options = [];
$options[] = array(
	'id'       => 'penci_ha_enabled_post_types',
	'default'  => '',
	'sanitize' => 'penci_sanitize_multiple_checkbox',
	'label'    => __( 'Enable Support in Post Types', 'soledad' ),
	'type'     => 'soledad-fw-select',
	'multiple' => 999,
	'choices'  => call_user_func( function () {
		$exclude    = array(
			'attachment',
			'revision',
			'nav_menu_item',
			'safecss',
			'penci-block',
			'penci_builder',
			'custom-post-template',
			'archive-template',
		);
		$registered = get_post_types( [ 'show_in_nav_menus' => true ], 'objects' );
		$types      = array();


		foreach ( $registered as $post ) {

			if ( in_array( $post->name, $exclude ) ) {

				continue;
			}

			$types[ $post->name ] = $post->label;
		}

		return $types;
	} )
);

$texts = [
    'yes' => __( 'Yes', 'soledad' ),
    'no'  => __( 'No', 'soledad' ),
    'question'  => __( 'Was this article helpful?', 'soledad' ),
    'thank'  => __( 'Thanks for your feedback!', 'soledad' ),
];

foreach ( $texts as $key => $value ) {
    $options[] = array(
    'label'    => __( 'Text: ' . $value, 'soledad' ),
    'id'       => 'penci_ha_' . $key . '_text',
    'type'     => 'soledad-fw-text',
    'default'  => $value,
    'sanitize' => 'penci_sanitize_tex_field'
    );
}

return $options;