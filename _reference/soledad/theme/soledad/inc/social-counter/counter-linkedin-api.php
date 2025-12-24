<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Linkedin_API' ) ):
	class Penci_Social_Counter_Linkedin_API {
		public static function get_count( $data, $cache_period ) {

			$page_id      = preg_replace( '/\s+/', '', $data['name'] );
			$data['url']  = $page_id;
			$data['icon'] = penci_icon_by_ver( 'fab fa-linkedin-in' );

			$default_count = penci_get_social_counter_option( 'likedin_default' );


			$data['count'] = $default_count ? $default_count : 0;


			return $data;
		}
	}

endif;
