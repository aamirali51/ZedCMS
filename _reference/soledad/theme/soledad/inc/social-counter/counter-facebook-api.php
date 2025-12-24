<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Facebook_API' ) ):
	class Penci_Social_Counter_Facebook_API {
		public static function get_count( $data, $cache_period ) {

			$page_id        = preg_replace( '/\s+/', '', $data['name'] );
			$default_count  = penci_get_social_counter_option( 'facebook_default' );
			$key            = penci_get_social_counter_option( 'facebook_token' );
			$facebook_count = $default_count ? $default_count : get_transient( 'penci_counter_facebook' . $page_id );

			if ( !$facebook_count ) {

				if ( $key ) {
					$counter = self::get_fb_count( $page_id, $key );
				} else {
					$counter = self::get_fb_count_wapi( $page_id );
				}

				if ( $counter ) { //To update the stored data
					$data['count'] = $counter;
					set_transient( 'penci_counter_facebook' . $page_id, $counter, $cache_period );
				}
			} else {
				$data['count'] = $facebook_count;
			}

			$data['url']  = "https://www.facebook.com/$page_id";
			$data['icon'] = penci_icon_by_ver( 'fab fa-facebook-f' );

			return $data;
		}

		public static function get_fb_count( $name, $key ) {
			$counter  = 0;
			$url      = 'https://graph.facebook.com/v11.0/' . $name . '?access_token=' . $key . '&fields=followers_count';
			$response = wp_remote_get( $url, array(
				'timeout' => 20,
			) );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$result = json_decode( $response['body'] );
				if ( ! empty( $result->fan_count ) ) {
					$counter = $result->fan_count;
				}
			}
			if ( $counter ) {
				return (int) $counter;
			} else {
				return 0;
			}
		}

		public static function get_fb_count_wapi( $name ) {
			$counter  = 0;
			$url      = 'https://www.facebook.com/plugins/fan.php?connections=100&id=' . $name;
			$response = wp_remote_get( $url, array(
				'timeout' => 20,
			) );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$pattern = "/<div class=\"_1drq\" style=\"max-width: 220px;\">(.*?)<\/div>/";
				$counter = penci_get_the_number( $pattern, $response['body'] );
			}
			if ( $counter ) {
				return (int) $counter;
			} else {
				return 0;
			}
		}
	}

endif;
