<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_REST_API' ) ) {
	class Penci_REST_API {
		public function __construct() {
			add_action( 'rest_api_init', [ $this, 'register_routes' ] );
		}


		public function register_routes() {
			register_rest_route( 'penci/v1', '/penci_html_mega_menu', [
				'methods'             => 'POST',
				'callback'            => [ $this, 'penci_html_mega_menu' ],
				'permission_callback' => '__return_true',
			] );
			register_rest_route( 'penci/v1', '/penci_archive_more_post', [
				'methods'             => 'POST',
				'callback'            => [ $this, 'penci_archive_more_post' ],
				'permission_callback' => '__return_true',
			] );
			register_rest_route( 'penci/v1', '/penci_ajax_search', [
				'methods'             => 'GET',
				'callback'            => [ $this, 'penci_ajax_search' ],
				'permission_callback' => '__return_true',
			] );
		}

		public function penci_ajax_search( WP_REST_Request $request ) {
			return penci_ajax_search_results( $request->get_params() );
		}

		public function penci_html_mega_menu( WP_REST_Request $request ) {

			$menu     = $request->get_param( 'menu' );
			$item     = $request->get_param( 'item' );
			$catid    = $request->get_param( 'catid' );
			$number   = $request->get_param( 'number' );
			$style    = $request->get_param( 'style' );
			$position = $request->get_param( 'position' );

			$menu         = wp_get_nav_menu_items( $menu );
			$current_item = isset( $menu[ $item ] ) && is_object( $menu[ $item ] ) ? $menu[ $item ] : '';

			$menu_html = penci_return_html_mega_menu( $catid, $number, $style, $position, $menu, $current_item );

			return new WP_REST_Response( [ 'data' => $menu_html ], 200 );
		}

		public function penci_archive_more_post( WP_REST_Request $request ) {

            $requests = $request->get_params();

			$ppp          = ( isset( $requests['ppp'] ) ) ? (int) $requests['ppp'] : 4;
			$order_get    = ( isset( $requests['order'] ) ) ? sanitize_text_field( $requests['order'] ) : '';
			$offset       = ( isset( $requests['offset'] ) ) ? (int) $requests['offset'] : 0;
			$layout       = ( isset( $requests['layout'] ) ) ? sanitize_file_name( $requests['layout'] ) : 'grid';
			$archivetype  = isset( $requests['archivetype'] ) ? $requests['archivetype'] : '';
			$archivevalue = isset( $requests['archivevalue'] ) ? $requests['archivevalue'] : '';
			$from         = ( isset( $requests['from'] ) ) ? $requests['from'] : 'customize';
			$template     = ( isset( $requests['template'] ) ) ? $requests['template'] : 'sidebar';

			$orderby = get_theme_mod( 'penci_general_post_orderby' );

			if ( ! $orderby ):
				$orderby = 'date';
			endif;

			$order = get_theme_mod( 'penci_general_post_order' );
			if ( ! $order ):
				$order = 'DESC';
			endif;

			$order = $order_get ? $order_get : $order;

			$args = array(
				'post_type'      => 'post',
				'posts_per_page' => $ppp,
				'post_status'    => 'publish',
				'offset'         => $offset,
				'orderby'        => $orderby,
				'order'          => $order,
			);

			if ( $order == 'view' ) {
				$args['meta_key'] = penci_get_postviews_key();
				$args['order']    = 'DESC';
				$args['orderby']  = 'meta_value_num';
			}

			if ( $order == 'comment' ) {
				$args['order']   = 'DESC';
				$args['orderby'] = 'comment_count';
			}

			if ( 'cat' == $archivetype ) {
				$args['cat'] = $archivevalue;
			} elseif ( 'tag' == $archivetype ) {
				$args['tag_id'] = $archivevalue;
			} elseif ( 'day' == $archivetype ) {
				$date_arr           = explode( '|', $archivevalue );
				$args['date_query'] = array(
					array(
						'year'  => isset( $date_arr[2] ) ? $date_arr[2] : '',
						'month' => isset( $date_arr[0] ) ? $date_arr[0] : '',
						'day'   => isset( $date_arr[1] ) ? $date_arr[1] : '',
					),
				);
			} elseif ( 'month' == $archivetype ) {
				$date_arr           = explode( '|', $archivevalue );
				$args['date_query'] = array(
					array(
						'year'  => isset( $date_arr[2] ) ? $date_arr[2] : '',
						'month' => isset( $date_arr[0] ) ? $date_arr[0] : '',
					),
				);
			} elseif ( 'year' == $archivetype ) {
				$date_arr           = explode( '|', $archivevalue );
				$args['date_query'] = array(
					array(
						'year' => isset( $date_arr[2] ) ? $date_arr[2] : '',
					),
				);
			} elseif ( 'search' == $archivetype ) {
				$args['s'] = $archivevalue;

				$post_types    = get_post_types(
					array(
						'public'            => true,
						'show_in_nav_menus' => true,
					),
					'names'
				);
				$array_include = array();
				foreach ( $post_types as $key => $val ) {
					$array_include[] = $key;
				}
				$exclude = array(
					'web-story',
					'e-landing-page',
					'penci-block',
					'penci_builder',
					'archive-template',
					'custom-post-template',
				);

				if ( ! get_theme_mod( 'penci_include_search_page' ) ) {
					$exclude[] = 'page';
				}

				$array_include = array_diff( $array_include, $exclude );

				$args['post_type'] = $array_include;


			} elseif ( 'author' == $archivetype ) {
				$args['author'] = $archivevalue;

				if ( isset( $args['post_type'] ) ) {
					unset( $args['post_type'] );
				}
			} elseif ( $archivetype && $archivevalue ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $archivetype,
						'field'    => 'term_id',
						'terms'    => array( $archivevalue ),
					),
				);
			}

			$loop = new WP_Query( $args );

            $html_archive = '';

            ob_start();

			if ( $loop->have_posts() ):
				$infeed_ads  = get_theme_mod( 'penci_infeedads_archi_code' ) ? get_theme_mod( 'penci_infeedads_archi_code' ) : '';
				$infeed_num  = get_theme_mod( 'penci_infeedads_archi_num' ) ? get_theme_mod( 'penci_infeedads_archi_num' ) : 3;
				$infeed_full = get_theme_mod( 'penci_infeedads_archi_layout' ) ? get_theme_mod( 'penci_infeedads_archi_layout' ) : '';
				while ( $loop->have_posts() ):
					$loop->the_post();
					include locate_template( 'content-' . $layout . '.php' );
				endwhile;
			endif;

			wp_reset_postdata();

            $html_archive = ob_get_clean();

            if ( $html_archive ) {
                return new WP_REST_Response( $html_archive, 200 );
            }
		}
	}
}

new Penci_REST_API();