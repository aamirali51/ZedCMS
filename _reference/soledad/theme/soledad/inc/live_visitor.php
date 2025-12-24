<?php

class penci_live_visitor_update {

	public function __construct() {
		// Register REST route early.
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	
		// Skip if the feature is disabled.
		if ( ! get_theme_mod( 'penci_live_viewer_enable' ) ) {
			return;
		}
	
		// Delay other actions until WP query is ready.
		add_action( 'wp', [ $this, 'init_later' ] );
	}
	
	public function init_later() {
		if ( ! is_singular() ) {
			return;
		}
	
		$post_type = get_post_type();
		if ( get_theme_mod( 'penci_live_viewer_disable_' . $post_type ) ) {
			return;
		}
	
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'view_content' ] );
		add_action( 'soledad_theme/custom_css', [ $this, 'popup_style' ] );
	}

	public function view_content() {
		$pos = get_theme_mod( 'penci_live_viewer_position', 'bottom-left' );
		echo '<div id="visitor-count" class="penci-visitor-count ' . esc_attr( $pos ) . '"></div>';
	}

	// Register REST routes
	public function register_routes() {
		register_rest_route( 'livevisitor/v1', '/update/(?P<post_id>\d+)', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'update_visitor' ],
			'permission_callback' => '__return_true',
		] );

		register_rest_route( 'livevisitor/v1', '/count/(?P<post_id>\d+)', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'count_visitor' ],
			'permission_callback' => '__return_true',
		] );
	}

	// Update visitor count
	public function update_visitor( $request ) {
		$post_id = (int) $request['post_id'];

		// Get visitor's IP address
		$visitor_ip = $_SERVER['REMOTE_ADDR'];

		// Fetch the current list of visitor IPs (or initialize an empty array)
		$visitors = get_transient( 'livevisitor_ips_' . $post_id ) ?: [];

		// Add visitor IP if it's not already in the list
		if ( ! in_array( $visitor_ip, $visitors ) ) {
			$visitors[] = $visitor_ip;
		}

		// Save the updated list of visitor IPs back to transient with expiration of 30 seconds
		set_transient( 'livevisitor_ips_' . $post_id, $visitors, 30 );

		return [ 'status' => 'ok' ];
	}

	// Get current visitor count based on IP addresses
	public function count_visitor( $request ) {
		$post_id = (int) $request['post_id'];

		// Get the list of visitor IPs for this post
		$visitors = get_transient( 'livevisitor_ips_' . $post_id ) ?: [];

		// Return the number of unique visitors (IP count)
		return [ 'count' => count( $visitors ) ];
	}

	// Enqueue JS file
	public function enqueue_scripts() {
		if ( is_singular() ) {
			// Enqueue JS file
			wp_enqueue_script(
				'penci-live-visitor-counter',
				PENCI_SOLEDAD_URL . '/js/live-visitor.js',
				[],
				null,
				true
			);

			$post_id = get_the_ID();
        	$post_type_object = get_post_type_object( get_post_type( $post_id ) );
        	$object_label = $post_type_object ? strtolower( $post_type_object->labels->singular_name ) : 'post';


			// Localize the post ID to JavaScript
			wp_localize_script( 'penci-live-visitor-counter', 'penci_live_visitor', [
				'post_id'        => $post_id,
				'object_label'   => $object_label,
				'singular_text'  => get_theme_mod('penci_live_viewer_singular_text', '1 viewer reading this {object}'),
				'plural_text'    => get_theme_mod('penci_live_viewer_plural_text', '{view} viewers are reading this {object}')
			] );
		}
	}

	public function popup_style() {
		$color = get_theme_mod( 'penci_live_viewer_color' );
		$bgcolor = get_theme_mod( 'penci_live_viewer_bgcolor' );
		if ( $color ) {
			echo '.penci-visitor-count{
				color: ' . esc_attr( $color ) . ';
			}';
		}
		if ( $bgcolor ) {
			echo '.penci-visitor-count{
				background: ' . esc_attr( $bgcolor ) . ';
			}';
		}
	}
}

// Initialize the class
new penci_live_visitor_update();