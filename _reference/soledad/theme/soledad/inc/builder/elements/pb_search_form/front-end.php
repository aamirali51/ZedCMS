<?php 
$header_data    = $args['header_data'];
$class   = [];
$class[] = 'search-style-' . penci_builder_validate_mod( $header_data, 'penci_header_pb_search_form_style' );
$class[] = penci_builder_validate_mod( $header_data, 'penci_header_pb_search_form_menu_class' );
penci_search_form( [
	'wrapper_custom_classes' => 'penci-builder-element pc-search-form-desktop pc-search-form ' . implode( ' ', $class ),
	'el_classes'             => 'pc-searchform',
] );
