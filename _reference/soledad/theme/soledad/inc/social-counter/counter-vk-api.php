<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Vk_API' ) ):
	class Penci_Social_Counter_Vk_API {
		public static function get_count( $data, $cache_period ) {

			$page_id       = preg_replace( '/\s+/', '', $data['name'] );
			$data['url']   = $page_id;
			$data['icon']  = penci_icon_by_ver( 'fab fa-vk' );
			$vk_api        = penci_get_social_counter_option( 'vk_api' );
			$default_count = penci_get_social_counter_option( 'vk_default' );
			$vk_count      = $default_count ? $default_count : get_transient( 'penci_counter_vk' . $page_id );

			if ( ! $vk_count ) {
				$count = self::get_vk_count( $page_id, $vk_api );
				set_transient( 'penci_counter_vk' . $page_id, $count, $cache_period );
			} else {
				$count = $vk_count;
			}

			if ( $count ) {
				$data['count'] = $count;
			}

			return $data;
		}

		public static function get_vk_count( $id, $api ) {
			$url         = 'https://api.vk.com/method/users.getFollowers?user_id=' . $id . '&v=5.74&access_token=' . $api;
			$status_code = @get_headers( $url, 1 );
			$response    = array();
			if ( strpos( $status_code[0], '200' ) ) {
				$response = wp_remote_get( $url, array(
					'timeout' => 10,
				) );
			}
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$result = json_decode( $response['body'] );
				if ( ! empty( $result->response->count ) ) {
					return $result->response->count;
				}
			}

			return 0;
		}
	}

endif;
