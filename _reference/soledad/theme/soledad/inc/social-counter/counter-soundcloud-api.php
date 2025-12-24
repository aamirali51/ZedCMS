<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Soundcloud_API' ) ):
	class Penci_Social_Counter_Soundcloud_API {
		public static function get_count( $data, $cache_period ) {

			$page_id      = preg_replace( '/\s+/', '', $data['name'] );
			$data['url']  = "https://soundcloud.com/$page_id";
			$data['icon'] = penci_icon_by_ver( 'fab fa-soundcloud' );

			$default_count = penci_get_social_counter_option( 'soundcloud_default' );

			$soundcloud_count = $default_count ? $default_count : get_transient( 'penci_counter_soundcloud' . $page_id );

			if ( ! $soundcloud_count ) {
				$count = self::get_soundcloud_count( $page_id );
				set_transient( 'penci_counter_soundcloud' . $page_id, $count, $cache_period );
			} else {
				$count = $soundcloud_count;
			}

			if ( $count ) {
				$data['count'] = $count;
			}

			return $data;
		}

		private static function get_soundcloud_count( $page_id ) {
			$status_code = @get_headers( "https://soundcloud.com/$page_id", 1 );
			$response    = array();
			if ( strpos( $status_code[0], '200' ) ) {
				$response = wp_remote_get( "https://soundcloud.com/$page_id", array(
					'timeout' => 10,
				) );
			}
			if ( ! empty( $response ) && ! is_wp_error( $response ) ) {
				$pattern = "/<meta property=\"soundcloud:follower_count\" content=\"(.*?)\">/";
				preg_match( $pattern, $response['body'], $matches );
				if ( ! empty( $matches[1] ) ) {
					return (int) $matches[1];
				}
			}

			return 0;
		}
	}

endif;
