<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Tiktok_API' ) ):
	class Penci_Social_Counter_Tiktok_API {
		public static function get_count( $data, $cache_period ) {
			if ( empty( $data['name'] ) ) {
				return $data;
			}

			$user_id      = preg_replace( '/\s+/', '', $data['name'] );
			$data['icon'] = penci_icon_by_ver( 'penciicon-tik-tok-1' );

			$cache_key     = 'penci_counter_tiktok' . $user_id;
			$default_count = penci_get_social_counter_option( 'tiktok_default' );
			$tiktok_count  = $default_count ? $default_count : get_transient( $cache_key );
			$data['url']   = "https://www.tiktok.com/@$user_id";

			if ( ! $tiktok_count ) {

				$count = self::get_tiktok_count( $user_id );

				set_transient( $cache_key, $count, $cache_period );
			} else {
				$count = $tiktok_count;
			}

			if ( $count ) {
				$data['count'] = $count;
			}

			return $data;
		}

		public static function get_tiktok_count( $username ): string {
			$count    = 0;
			$url      = "https://www.tiktok.com/@{$username}";
			$response = wp_remote_get( $url, array(
				'timeout'    => 20,
				'user-agent' => 'Mozilla/5.0 (iPhone; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.25 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1'
			) );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$pattern = "/data-e2e=\"followers-count\" class=\"(.*?)\">(.*?)<\/strong>/";
				preg_match_all( $pattern, $response['body'], $matches );

				if ( isset( $matches[2][1] ) ) {
					$count = $matches[2][1];
				}
			}

			return $count;
		}
	}

endif;
