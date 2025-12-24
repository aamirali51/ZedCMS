<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Rss_API' ) ):
	class Penci_Social_Counter_Rss_API {
		public static function get_count( $data, $cache_period ) {

			$user_id      = preg_replace( '/\s+/', '', $data['name'] );
			$data['icon'] = penci_icon_by_ver( 'fab fa-rss' );

			$cache_key = 'penci_counter__rss' . $user_id;
			$rss_count = get_transient( $cache_key );

			$rss_type        = penci_get_social_counter_option( 'rss_type' );
			$rss_feedpress   = penci_get_social_counter_option( 'rss_feedpress' );
			$rss_default     = penci_get_social_counter_option( 'rss_default' );
			$rss_default_url = penci_get_social_counter_option( 'rss_feed_uri' );

			if ( $rss_type == 'feedpress' && $rss_feedpress ) {
				$data['url'] = esc_url( $rss_feedpress );
			} elseif ( $rss_default_url ) {
				$data['url'] = esc_url( $rss_default_url );
			}

			if ( ! $rss_count ) {

				if ( ( $rss_type == 'feedpress' ) && ! empty( $rss_feedpress ) ) {
					try {
						$feedpress_url = esc_url( $rss_feedpress );
						$feedpress_url = str_replace( 'feedpress.it', 'feed.press', $feedpress_url );

						$data   = @penci_remote_get( $feedpress_url );
						$result = (int) $data['subscribers'];
					} catch ( Exception $e ) {
						$result = 0;
					}
				} elseif ( ( $rss_type == 'manual' ) && ! empty( $rss_default ) ) {
					$result = $rss_default;
				} else {
					$result = 0;
				}

				if ( ! empty( $result ) ) {
					$data['count'] = $result;
					set_transient( $cache_key, $result, $cache_period );
				}
			} else {
				$data['count'] = $rss_count;
			}

			return $data;
		}
	}

endif;
