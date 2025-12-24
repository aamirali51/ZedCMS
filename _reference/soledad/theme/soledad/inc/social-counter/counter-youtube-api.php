<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Youtube_API' ) ):
	class Penci_Social_Counter_Youtube_API {
		public static function get_count( $data, $cache_period ) {
			if ( empty( $data['name'] ) ) {
				return $data;
			}

			$user_id      = preg_replace( '/\s+/', '', $data['name'] );
			$data['icon'] = penci_icon_by_ver( 'fab fa-youtube' );

			$default_count = penci_get_social_counter_option( 'youtube_default' );
			$cache_key     = 'penci_counter_youtube' . $user_id;
			$youtube_count = $default_count ? $default_count : get_transient( $cache_key );
			$youtube_type  = penci_get_social_counter_option( 'youtube_type', 'channel' );
			$youtube_api   = penci_get_social_counter_option( 'youtube_api_key' );

			if ( 'channel' === $youtube_type ) {
				$data['url'] = "https://www.youtube.com/c/$user_id";
			} else {
				$data['url'] = "https://www.youtube.com/$user_id";
			}

			if ( ! $youtube_count && $youtube_api ) {
				$count = 0;
				if ( $youtube_type == 'channel' ) {
					$youtube_data = @penci_remote_get( "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=$user_id&key=$youtube_api" );
				} else {
					$youtube_data = @penci_remote_get( "https://www.googleapis.com/youtube/v3/channels?part=statistics&forUsername=$user_id&key=$youtube_api" );
				}
				if ( isset( $youtube_data['items'][0]['statistics']['subscriberCount'] ) && $youtube_data['items'][0]['statistics']['subscriberCount'] ) {
					$count = (int) $youtube_data['items'][0]['statistics']['subscriberCount'];
				}
				if ( $count ) {
					set_transient( $cache_key, $count, $cache_period );
					$data['count'] = $count;
				}
			}

			return $data;
		}
	}

endif;
