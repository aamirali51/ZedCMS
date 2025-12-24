<?php
define( 'PENCI_ELEMENTOR_PATH', PENCI_SOLEDAD_DIR . '/inc/elementor/' );
define( 'PENCI_ELEMENTOR_URL', PENCI_SOLEDAD_URL . '/inc/elementor/' );

/**
 * Add a custom control to reorder columns
 */

use Elementor\Controls_Manager;

function penci_add_display_order_control( $el, $args ) {
	$el->add_responsive_control( 'sc_rce_display_order', [
		'label'       => esc_html__( 'Display Order', 'soledad' ),
		'type'        => Controls_Manager::NUMBER,
		'min'         => - 10,
		'max'         => 10,
		'step'        => 1,
		'render_type' => 'ui',
		'separator'   => 'before',
		'selectors'   => [
			'{{WRAPPER}}' => 'order:{{VALUE}} !important'
		]
	] );
}

add_action( 'elementor/element/common/_section_style/before_section_end', 'penci_add_display_order_control', PHP_INT_MAX, 2 );
add_action( 'elementor/element/column/layout/before_section_end', 'penci_add_display_order_control', PHP_INT_MAX, 2 );
add_action( 'elementor/element/section/section_layout/before_section_end', 'penci_add_display_order_control', PHP_INT_MAX, 2 );


if ( ! class_exists( 'Penci_Soledad_Elementor_Extension' ) ):
	final class Penci_Soledad_Elementor_Extension {
		private static $_instance = null;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function __construct() {
			require PENCI_ELEMENTOR_PATH . 'loader.php';
		}
	}

	Penci_Soledad_Elementor_Extension::instance();
endif;
