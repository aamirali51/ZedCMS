<?php
header("Content-type: text/css; charset: UTF-8");

// Load the WordPress library.

// "../../../../../../wp-load.php"
$root = dirname( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) );

// "../../../../../wp-load.php"
$root2 = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) );

// "../../../../wp-load.php"
$root3 = dirname( dirname( dirname( dirname( __FILE__ ) ) ) );

if ( file_exists( $root . '/wp-load.php' ) ) {
	require_once( $root . '/wp-load.php' );
} elseif ( file_exists( $root2 . '/wp-load.php' ) ) {
	require_once( $root2 . '/wp-load.php' );
} elseif ( file_exists( $root3 . '/wp-load.php' ) ) {
	require_once( $root3 . '/wp-load.php' );
} else {
	return;
}

pencidesign_get_customizer_css_file();
pencidesign_customizer_css_page_header_title();
pencidesign_customizer_css_page_header_transparent();