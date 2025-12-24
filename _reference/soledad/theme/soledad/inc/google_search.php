<?php
if ( ! class_exists( 'Penci_Google_Search' ) ) {

	class Penci_Google_Search {
		function __construct() {

			if ( ! get_theme_mod( 'penci_gsr_enable' ) ) {
				return false;
			}

			global $wp;

			$wp->add_query_var( 'q' );
			$wp->remove_query_var( 's' );

			add_action( 'init', array( $this, 'init' ), 0 );
			add_filter( 'template_include', [ $this, 'search_result_page' ], 99 );
			add_filter( 'request', [ $this, 'request' ] );
			add_filter( 'template_redirect', [ $this, 'fix_search_url' ] );
		}

		public function fix_search_url() {
			if ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] ) {
				wp_redirect( home_url( '/' ) . '?q=' . esc_attr( $_REQUEST['s'] ) );
				die;
			}
		}

		public function request( $request ) {
			if ( isset( $_REQUEST['q'] ) ) {
				$request['s'] = $_REQUEST['q'];
			}

			return $request;
		}

		public function init() {

			wp_enqueue_script(
				'google_cse_v2',
				PENCI_SOLEDAD_URL . '/js/google_cse_v2.js',
				array( // dependencies
				),
				1.0,
				true
			);

			$script_params = array(
				'google_search_engine_id' => get_theme_mod( 'penci_gsr_id' )
			);

			wp_localize_script( 'google_cse_v2', 'scriptParams', $script_params );

		}

		public function search_result_page( $template ) {
			if ( is_search() && ! is_admin() ) {
				$template = locate_template( array( 'inc/templates/google_search_result.php' ) );
			}

			return $template;
		}
	}
}

new Penci_Google_Search;