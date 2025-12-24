<?php
vc_map( array(
	'base'                      => 'penci_column_inner',
	'icon'                      => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'                  => 'Soledad',
	'html_template'             => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/penci_column_inner/frontend.php',
	'name'                      => __( 'Column Inner', 'soledad' ),
	'class'                     => '',
	'wrapper_class'             => '',
	'controls'                  => 'full',
	'allowed_container_element' => false,
	'content_element'           => false,
	'is_container'              => true,
	'as_child'                  => array( 'only' => 'penci_container_inner' ),
	'params'                    => array(
		array(
			'type'       => 'hidden',
			'param_name' => 'width',
		),
		array(
			'type'       => 'hidden',
			'param_name' => 'class_layout',
		),
	),
	'js_view'                   => 'VcColumnView',
) );