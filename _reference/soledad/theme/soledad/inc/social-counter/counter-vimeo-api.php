<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Vimeo_API' ) ):
	class Penci_Social_Counter_Vimeo_API {
		public static function get_count( $data, $cache_period ) {

			$page_id       = preg_replace( '/\s+/', '', $data['name'] );
			$data['url']   = "http://vimeo.com/$page_id";
			$data['icon']  = penci_icon_by_ver( 'fab fa-vimeo-v' );
			$default_count = penci_get_social_counter_option( 'vimeo_default' );
			$cache_key     = 'penci_counter__vimeo' . $page_id;
			$vimeo_count   = $default_count ? $default_count : get_transient( $cache_key );
			if ( ! $vimeo_count ) {

				$count = self::get_vimeo_count( $page_id );

				set_transient( $cache_key, $count, $cache_period );
			} else {
				$count = $vimeo_count;
			}

			if ( $count ) {
				$data['count'] = $count;
			}

			return $data;
		}

		private static function get_vimeo_count( $id ) {
			$status_code = @get_headers( 'https://vimeo.com/' . $id . '/following/followers/', 1 );
			$response    = array();
			if ( strpos( $status_code[0], '200' ) ) {
				$response = wp_remote_get( 'https://vimeo.com/' . $id . '/following/followers/', array(
					'timeout' => 10,
				) );
			}
			$result = 0;
			if ( ! empty( $response ) && ! is_wp_error( $response ) ) {
				$pattern = "/data-title=\"(.*?) Follower(s?)\"/";
				preg_match( $pattern, $response['body'], $matches );

				if ( ! empty( $matches[1] ) ) {
					$result = '';
					foreach ( str_split( $matches[1] ) as $char ) {
						if ( is_numeric( $char ) ) {
							$result .= $char;
						}
					}

					return (int) $result;
				}
			}

			return $result;
		}
	}

endif;
