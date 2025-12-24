<?php
vc_map( array(
	'base'                    => 'penci_container_inner',
	'icon'                    => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'                => penci_get_theme_name('Soledad'),
	'html_template'           => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/penci_container_inner/frontend.php',
	'name'                    => penci_get_theme_name('Penci').' '.__( 'Container', 'soledad' ),
	'description'             => esc_html__( 'Place content elements inside the container', 'js_composer' ),
	'weight'                  => 1000,
	'is_container'            => true,
	'show_settings_on_create' => false,
	'as_child'                => array( 'only' => 'penci_column_inner' ),
	'params'                  => array(
		array(
			'type'       => 'hidden',
			'param_name' => 'el_width',
			'std'        => 'w1080',
			'value'      => 'w1080',
		),
		array(
			'type'       => 'hidden',
			'param_name' => 'container_layout',
		),
		array(
			'type'       => 'penci_switch',
			'heading'    => __( 'Disable sticky content & sidebar.', 'soledad' ),
			'param_name' => 'el_disable_sticky',
			'value'      => array( __( 'Yes', 'soledad' ) => 'yes' ),
		),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Row ID', 'soledad' ),
			'param_name'  => 'el_id',
			'description' => sprintf( __( 'Enter optional row ID. Make sure it is unique, and it is valid as w3c specification: %s (Must not have spaces)', 'soledad' ), '<a target="_blank" href="http://www.w3schools.com/tags/att_global_id.asp">' . __( 'link', 'soledad' ) . '</a>' ),
		),
	),
	'js_view'          => 'VcPenciContainerView',
	'default_content'  => '[penci_column_inner width="1/3"][/penci_column_inner][penci_column_inner width="2/3"][/penci_column_inner]',
) );
