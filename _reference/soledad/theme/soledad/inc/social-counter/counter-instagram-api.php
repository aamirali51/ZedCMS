<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Instagram_API' ) ):
	class Penci_Social_Counter_Instagram_API {
		public static function get_count( $data, $cache_period ) {

			$user_id      = preg_replace( '/\s+/', '', $data['name'] );
			$data['url']  = "https://www.instagram.com/$user_id";
			$data['icon'] = penci_icon_by_ver( 'fab fa-instagram' );

			$cache_key       = 'penci_counter__instagram' . $user_id;
			$default_count   = penci_get_social_counter_option( 'instagram_default' );
			$instagram_count = $default_count ? $default_count : get_transient( $cache_key );
			if ( ! $instagram_count ) {

				$count = self::penci_get_instagram_data( $user_id );

				set_transient( $cache_key, $count, $cache_period );

			} else {
				$count = $instagram_count;
			}

			if ( $count ) {
				$data['count'] = $count;
			}

			return $data;
		}

		public static function penci_get_instagram_data( $username ) {
			$url      = 'https://webstagram.org/api?api_key=0&username=' . esc_attr( $username ) . '&source=instagram';
			$response = wp_remote_get( $url, array(
				'timeout' => 10,
			) );

			if ( wp_remote_retrieve_response_message( $response ) ) {
				$response = json_decode( $response['body'], true );

				return isset( $response['followers'] ) && $response['followers'] ? $response['followers'] : 0;
			} else {
				return 0;
			}
		}
	}

endif;
