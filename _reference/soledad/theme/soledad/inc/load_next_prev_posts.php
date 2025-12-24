<?php
/*
 * Running autoload next/prev posts based on the settings from Customize > Single Posts
 * 
 */
class PENCI_SINGLE_LOAD_POSTS {
	
	function __construct() {
		/* Main hooks */
		add_action( 'wp', array( $this, 'posts_hook' ) );
		add_action( 'init', array( $this, 'comment_redirect' ), 5 );
	}


	/**
	 * posts_hook
	 */
	function posts_hook(){

		// Make sure on single posts & the option is enabled
		if( get_theme_mod( 'penci_loadnp_posts' ) && is_single() ){
			// Hook for the posts loads after scroll down and the current viewing posts
			if( function_exists( 'penci_is_single_npload' ) && penci_is_single_npload() ){
				$this->hooks_loads_posts();
			} else{
				$this->hooks_current_posts();
			}
		}
	}


	/**
	 * Current viewing post hooks
	 */
	function hooks_current_posts(){

		// Insert the iframe code after the post content
		add_action( 'penci_action_after_post_content', array( $this, 'get_previous_in_iframe' ), 100 );

		// Insert the iframe Js code at the footer after loading jQuery
		add_action( 'wp_footer', array( $this, 'enque_js_current_post' ), 99999 );

		// Custom body class in the Ajax loaded posts
		add_action('body_class', function( $classes ){
			$classes[] = 'penci-current-single-loadmore';
			return $classes;
		});
	}


	/**
	 * Enqueue js for current viewing post
	 */
	function enque_js_current_post(){
		?>
		<script type="text/javascript" src="<?php echo PENCI_SOLEDAD_URL; ?>/js/loadnp_current_posts.js"></script>
		<?php
	}


	/**
	 * Get the Iframe Code
	 */
	function get_previous_in_iframe(){

		$type = get_theme_mod( 'autoload_posts_type' ) ? get_theme_mod( 'autoload_posts_type' ) : 'prev';

		switch ( $type ) {
			case 'next':
				$prev_posts = $this->get_adjacent_post( false );
				break;
			case 'prev_cat':
				$prev_posts = $this->get_adjacent_post( true, true );
				break;
			case 'next_cat':
				$prev_posts = $this->get_adjacent_post( false, true );
				break;
			case 'prev_tag':
				$prev_posts = $this->get_adjacent_post( true, true, '', 'post_tag' );
				break;
			case 'next_tag':
				$prev_posts = $this->get_adjacent_post( false, true, '', 'post_tag' );
				break;
			default:
				$prev_posts = $this->get_adjacent_post();
		}

		if ( ! empty( $prev_posts ) ) {

			echo '
				<script>var PenciLoadNextPrevPosts = '. wp_json_encode( $prev_posts ) .';</script>
				<div id="penci-wrapper-single-loadmore">
					<div id="penci-wrapper-iframe-data">
					</div>
					<div class="penci-single-loadmore-anim"><div class="penci-loadmore-horizontal">Loading...</div></div>
				</div>
			';
		}
	}


	/**
	 * Load more posts iframe
	 */
	function hooks_loads_posts(){

		// Remove admin bar
		add_filter('show_admin_bar', '__return_false');

		// Remove header & footer
		add_filter( 'penci_filter_hide_header', function(){ return true; } );
		add_filter( 'penci_filter_hide_footer', function(){ return true; } );

		// Modify header and footer of next/prev posts
		add_action( 'wp_head', array( $this, 'loads_posts_header' ), 200 );
		add_action( 'wp_footer', array( $this, 'loads_posts_footer' ), 2 );

		// Add custom body class for loaded posts
		add_action( 'body_class', array( $this, 'loads_posts_bodyclass' ) );
		
		// Add custom class for the main post div, same class will be used for the iframe
		add_action('penci_post_class', function( $return ){
			$return .= ' penci-postclass-loadmore';
			echo $return;
		} );

		// Hook for SEO plugins - Yoast & Rank Math
		add_filter( 'wpseo_frontend_presenter_classes', array( $this, 'yoast_seo_remove_tags' ) );
		add_filter( 'rank_math/frontend/robots', array( $this, 'rank_math_remove_tags' ) );

		// Hook to before & after wp_footer and hide all elements inside it excerpt script & link tags
		add_action( 'wp_footer', function(){
			echo '
				<style>
					html body + *:not(script):not(link),
					html body ~ *:not(script):not(link),
					html body div#penci-iframe-loadnp-footer *:not(script):not(link),
					html body div#penci-iframe-loadnp-footer + *:not(script):not(link),
					html body div#penci-iframe-loadnp-footer ~ *:not(script):not(link){
						display: none !important;
						visibility: hidden !important;
						z-index: -1 !important;
						opacity: 0 !important;
						height: 0 !important;
						width: 0!important;
					}
				</style>
			';
			echo '<div id="penci-iframe-loadnp-footer">';
		}, 0 );

		add_action( 'wp_footer', function(){
			echo '</div>';
		}, 10000000000 );

	}


	/**
	 * Actions for <head> tag of next/prev posts
	 */
	function loads_posts_header(){
		// Stop index for loaded iframe
		echo '<meta name="robots" content="noindex, nofollow" />';

		// Enqueue js for loaded posts iframe
		echo '<script type="text/javascript" src="'. PENCI_SOLEDAD_URL . '/js/loadnp_next_posts.js"></script>';

		// CSS
		$css_codes = apply_filters( 'penci_css_loaded_posts', '
			html{
				overflow: hidden !important;
			}
			html, body{
				background: transparent !important;
				padding: 0 !important;
				margin 0 !important;
			}
			.penci-rlt-popup, .penci-wrap-gprd-law{
				display: none !important;
			}
			.fb-comments > span {
				display: block !important;
			}
			.fb-comments > span iframe{
				width: 100% !important;
			}
		');

		if( ! empty( $css_codes ) ){
			echo '<style>'.  $css_codes .'</style>';
		}
	}


	/**
	 * Add custom code before close </body> tag for loaded posts
	 */
	function loads_posts_footer(){
		
		$data = '<script>
			var link = document.getElementsByTagName("a");
			var i;
			for (i = 0; i < link.length; i++) {

				if( link[i].href !== "#" && "undefined" !== typeof link[i].target ){
					link[i].setAttribute("target", "_parent")
				}
			}

			var iFrameResizer = {
				onMessage: function(message) {
					alert(message, parentIFrame.getId())
				},
				onReady: function() {
					parentIFrame.sendMessage( \'{ "id": "readytoLoad" }\' );
				}
			}

			// Listen to parent
			window.addEventListener("message", handleParentMessage, false);
			function handleParentMessage(e) {
				if( e.data && typeof e.data === "object" ){
			    html = document.getElementsByTagName("html")[0].classList;
					if( "undefined" !== typeof e.data.addClass ) {
						var Classes = e.data.addClass.split(" ");
						for (i = 0; i < Classes.length; i++) {
							if( Classes[i] ){
								html.add( Classes[i] );
							}
						}
					}
					if( "undefined" !== typeof e.data.removeClass ) {
						var Classes = e.data.removeClass.split(" ");
						for (i = 0; i < Classes.length; i++) {
							if( Classes[i] ){
								html.remove( Classes[i] );
							}
						}
					}
				}
			}
		</script>';

		echo $data;
	}


	/**
	 * Custom Class in the Ajax Loaded Posts
	 */
	function loads_posts_bodyclass( $classes ){

		$classes[] = 'penci-single-loaded-posts';

		return $classes;
	}


	/**
	 * Disable all meta tags from Yoast SEO for load posts - more information: https://developer.yoast.com/customization/apis/metadata-api/
	 */
	function yoast_seo_remove_tags( $presenters ){

		if( is_array( $presenters ) && ! empty( $presenters ) ){

			$prefix = 'Yoast\WP\SEO\Presenters';

			$removed_tags = array(
				'\Robots_Presenter',
				'\Googlebot_Presenter',
				'\Bingbot_Presenter',
			);

			foreach ( $presenters as $key => $val ) {
				$presenter = str_replace( $prefix, '', $val );
				if( in_array( $presenter, $removed_tags ) ){
					unset( $presenters[ $key ] );
				}
			}
		}

		return $presenters;
	}

	/**
	 * Disable index, follow from Rank Math plugin
	 */
	function rank_math_remove_tags( $tags ) {
		$tags['index']  = 'noindex';
		$tags['follow'] = 'nofollow';

		return $tags;
	}
	
	/**
	 * Modify the get_adjacent_post function to get the data of load next/prev posts - original function: https://developer.wordpress.org/reference/functions/get_adjacent_post/
	 */
	function get_adjacent_post( $previous = true, $in_same_term = false, $excluded_terms = '', $taxonomy = 'category' ) {
		global $wpdb;

		$post = get_post();
		if ( ! $post || ! taxonomy_exists( $taxonomy ) ) {
			return null;
		}

		$current_post_date = $post->post_date;

		$join     = '';
		$where    = '';
		$adjacent = $previous ? 'previous' : 'next';

		if ( ! empty( $excluded_terms ) && ! is_array( $excluded_terms ) ) {
			$excluded_terms = explode( ',', $excluded_terms );
			$excluded_terms = array_map( 'intval', $excluded_terms );
		}

		if ( $in_same_term || ! empty( $excluded_terms ) ) {
			if ( $in_same_term ) {
				$join  .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
				$where .= $wpdb->prepare( 'AND tt.taxonomy = %s', $taxonomy );

				if ( ! is_object_in_taxonomy( $post->post_type, $taxonomy ) ) {
					return '';
				}
				$term_array = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );

				// Remove any exclusions from the term array to include.
				$term_array = array_diff( $term_array, (array) $excluded_terms );
				$term_array = array_map( 'intval', $term_array );

				if ( ! $term_array || is_wp_error( $term_array ) ) {
					return '';
				}

				$where .= ' AND tt.term_id IN (' . implode( ',', $term_array ) . ')';
			}

			if ( ! empty( $excluded_terms ) ) {
				$where .= " AND p.ID NOT IN ( SELECT tr.object_id FROM $wpdb->term_relationships tr LEFT JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) WHERE tt.term_id IN (" . implode( ',', array_map( 'intval', $excluded_terms ) ) . ') )';
			}
		}

		// Only Published posts
		$where .= " AND p.post_status = 'publish'";

		$op    = $previous ? '<' : '>';
		$order = $previous ? 'DESC' : 'ASC';
		$limit = get_theme_mod( 'penci_loadnp_number') ? get_theme_mod( 'penci_loadnp_number') : 4;

		$where = $wpdb->prepare( "WHERE p.post_date $op %s AND p.post_type = %s $where", $current_post_date, $post->post_type );

		$query     = "SELECT DISTINCT ID, post_author, post_date, post_date_gmt, post_title, post_type, post_status, post_name, guid FROM $wpdb->posts AS p $join $where ORDER BY p.post_date $order LIMIT $limit";
		$query_key = 'autoload_posts_' . md5( $query );
		$result    = wp_cache_get( $query_key, 'counts' );

		if ( false === $result ) {

			$result = $wpdb->get_results( $query );

			if ( null === $result ) {
				$result = '';
			}

			wp_cache_set( $query_key, $result, 'counts' );
		}

		$loaddata = array();

		if( ! empty( $result ) || is_array( $result ) ){
			foreach ( $result as $postdata ) {
				
				// Permalinks
				$post_url = get_permalink( $postdata );

				// Change the edit URL, 
				$post_type_object = get_post_type_object( $postdata->post_type );
				$edit_url = admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=edit', $postdata->ID ) );

				$loaddata[] = array(
					'id'       => $postdata->ID,
					'url'      => $post_url,
					'edit_url' => $edit_url,
					'title'    => get_the_title( $postdata ),
					'src'      => add_query_arg( 'penci-sload-ajax', '1', $post_url ),
				);

			}

			return $loaddata;
		}

		return false;
	}
	
	/**
	 * Comment redirect
	 */
	function comment_redirect(){
		if( get_theme_mod( 'penci_loadnp_posts' ) && is_single() ){
			add_filter( 'comment_post_redirect', array( $this, 'comment_redirect_hook' ) );
		}
	}

	/**
	 * Add parameter ?penci-sload-ajax to the comment URL to refresh page after someone commented.
	 */
	function comment_redirect_hook( $url ){

		if ( strpos( $_SERVER["HTTP_REFERER"], 'penci-sload-ajax' ) !== false ) {
			$url = add_query_arg( 'penci-sload-ajax', 'true', $url );
		}

		return $url;
	}

}

new PENCI_SINGLE_LOAD_POSTS();