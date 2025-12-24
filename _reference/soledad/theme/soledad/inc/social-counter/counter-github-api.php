<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Github_API' ) ):
	class Penci_Social_Counter_Github_API {
		public static function get_count( $data, $cache_period ) {

			$user_id      = preg_replace( '/\s+/', '', $data['name'] );
			$data['url']  = "https://github.com/$user_id";
			$data['icon'] = penci_icon_by_ver( 'fab fa-github' );

			$cache_key     = 'penci_counter_github' . $user_id;
			$default_count = penci_get_social_counter_option( 'github_default' );
			$github_count  = $default_count ? $default_count : get_transient( $cache_key );
			if ( ! $github_count ) {
				$penci_data = self::get_json( "https://api.github.com/users/$user_id" );
				$count      = isset( $penci_data['followers'] ) ? intval( $penci_data['followers'] ) : 0;

				set_transient( $cache_key, $count, $cache_period );
			} else {
				$count = $github_count;
			}

			if ( $count ) {
				$data['count'] = $count;
			}

			return $data;
		}

		public static function get_url_wordpress( $url ) {

			$response = wp_remote_get( $url, array(
				'timeout'    => 10,
				'sslverify'  => false,
				'user-agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0'
			) );

			if ( is_wp_error( $response ) ) {
				return false;
			}

			$penci_request_result = wp_remote_retrieve_body( $response );

			if ( empty( $penci_request_result ) ) {
				return false;
			}

			return $penci_request_result;
		}

		private static function get_url( $url ) {
			return self::get_url_wordpress( $url );
		}

		private static function get_json( $url ) {
			return json_decode( self::get_url( $url ), true );
		}

	}

endif;
