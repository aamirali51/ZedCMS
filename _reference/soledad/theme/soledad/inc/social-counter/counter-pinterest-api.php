<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Pinterest_API' ) ):
	class Penci_Social_Counter_Pinterest_API {
		public static function get_count( $data, $cache_period ) {
			$count           = 0;
			$user_id         = preg_replace( '/\s+/', '', $data['name'] );
			$data['url']     = "https://www.pinterest.com/$user_id";
			$data['icon']    = penci_icon_by_ver( 'fab fa-pinterest' );
			$default_count   = penci_get_social_counter_option( 'pinterest_default' );
			$cache_key       = 'penci_counter__pinterest' . $user_id;
			$pinterest_count = $default_count ? $default_count : get_transient( $cache_key );
			if ( ! $pinterest_count ) {

				try {

					$url      = "https://www.pinterest.com/$user_id/";
					$response = wp_remote_get( $url, array(
						'timeout' => 10,
					) );

					$pattern = "/name=\"pinterestapp:followers\" content=\"(.*?)\"/";
					preg_match( $pattern, $response['body'], $matches );

					if ( ! empty( $matches[1] ) ) {
						$count = (int) $matches[1];
						set_transient( $cache_key, $count );
					}

				} catch ( Exception $e ) {
					$count = 0;
				}

			} else {
				$count = $pinterest_count;
			}

			$data['count'] = $count;

			return $data;
		}
	}

endif;
