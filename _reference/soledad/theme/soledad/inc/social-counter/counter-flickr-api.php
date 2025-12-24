<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Flickr_API' ) ):
	class Penci_Social_Counter_Flickr_API {
		public static function get_count( $data, $cache_period ) {

			$page_id      = preg_replace( '/\s+/', '', $data['name'] );
			$data['url']  = $page_id;
			$data['icon'] = penci_icon_by_ver( 'fab fa-flickr' );


			$count         = 0;
			$cache_key     = 'penci_counter__flickr' . $page_id;
			$default_count = penci_get_social_counter_option( 'flickr_default' );
			$flickr_count  = $default_count ? $default_count : get_transient( $cache_key );

			if ( ! $flickr_count && filter_var( $page_id, FILTER_VALIDATE_URL ) !== false ) {
				$params = array( 'sslverify' => true, 'timeout' => 60 );

				$connection = wp_remote_get( $page_id, $params );

				if ( ! is_wp_error( $connection ) ) {
					$pattern = "/\"followerCount\":(.*?),\"/";
					preg_match( $pattern, $connection['body'], $matches );

					if ( ! empty( $matches[1] ) ) {
						$count = (int) $matches[1];
					}
				}

				set_transient( $cache_key, $count, $cache_period );
			} else {
				$count = $flickr_count;
			}

			if ( $count ) {
				$data['count'] = $count;
			}

			return $data;
		}
	}

endif;
