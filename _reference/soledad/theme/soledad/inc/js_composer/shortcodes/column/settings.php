<?php
vc_map( array(
	'base'                      => "penci_column",
	'icon'                      => PENCI_SOLEDAD_URL . '/images/vc-icon.png',
	'category'                  => 'Soledad',
	'html_template'             => PENCI_SOLEDAD_DIR . '/inc/js_composer/shortcodes/column/frontend.php',
	'weight'                    => 700,
	'name'                      => __( 'Column', 'soledad' ),
	'class'                     => 'vc_main-sortable-element',
	'wrapper_class'             => '',
	'controls'                  => 'full',
	'allowed_container_element' => false,
	'content_element'           => false,
	'is_container'              => true,
	'params'                    => array(
		array(
			'type'       => 'hidden',
			'param_name' => 'width',
		),
		array(
			'type'       => 'hidden',
			'param_name' => 'class_layout',
		),
		array(
			'type'       => 'hidden',
			'param_name' => 'order',
		),
	),
	'js_view'                   => 'VcColumnView',
) );