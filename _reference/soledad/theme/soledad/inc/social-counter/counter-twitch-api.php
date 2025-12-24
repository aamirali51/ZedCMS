<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Twitch_API' ) ):
	class Penci_Social_Counter_Twitch_API {
		public static function get_count( $data, $cache_period ) {

			$page_id       = preg_replace( '/\s+/', '', $data['name'] );
			$data['url']   = 'https://www.twitch.tv/' . $page_id;
			$data['icon']  = penci_icon_by_ver( 'fab fa-twitch' );
			$default_count = penci_get_social_counter_option( 'twitch_default' );
			$twitch_count  = $default_count ? $default_count : get_transient( 'penci_counter_twitch' . $page_id );
			$twitch_api    = penci_get_social_counter_option( 'twitch_client_id' );

			if ( ! $twitch_count && $twitch_api ) {
				try {
					$result   = array();
					$response = wp_remote_get( 'https://api.twitch.tv/kraken/channels/' . $page_id . '?client_id=' . $twitch_api, array( 'timeout' => 20 ) );
					if ( is_array( $response ) && ! is_wp_error( $response ) ) {
						$result = json_decode( $response['body'] );
					}
					if ( ! empty( $result ) && ! empty( $result->followers ) ) {
						$count = $result->followers;
					}
				} catch ( Exception $e ) {
					$count = 0;
				}

				set_transient( 'penci_counter_twitch' . $page_id, $count, $cache_period );
			} else {
				$count = $twitch_count;
			}

			if ( $count ) {
				$data['count'] = $count;
			}

			return $data;
		}
	}

endif;
