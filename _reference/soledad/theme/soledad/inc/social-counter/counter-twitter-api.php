<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Penci_Social_Counter_Twitter_API' ) ):
	class Penci_Social_Counter_Twitter_API {
		public static function get_count( $data, $cache_period ) {
			$user_id       = preg_replace( '/\s+/', '', $data['name'] );
			$data['url']   = "https://x.com/$user_id";
			$data['icon']  = penci_icon_by_ver( 'penciicon-x-twitter' );
			$default_count = penci_get_social_counter_option( 'twitter_default' );
			$cache_key     = 'penci_counter_twitter' . $user_id;
			$twitter_count = $default_count ? $default_count : get_transient( $cache_key );

			$count = 0;

			$api = [];

			$gnr = get_option( 'penci_social_counter_settings' );

			if ( $gnr['twitter_api_key'] ) {
				$key = $gnr['twitter_api_key'];
			}

			if ( $gnr['twitter_api_key_secret'] ) {
				$src = $gnr['twitter_api_key_secret'];
			}


			if ( ! $twitter_count ) {

				$count = self::getTwitterFollowers( $user_id, $key, $src );
				set_transient( $cache_key, $count, $cache_period );

			} else {
				$count = $twitter_count;
			}

			if ( $count ) {
				$data['count'] = $count;
			}

			return $data;
		}

		/**
		 * Extract numbers from string
		 *
		 * @param $penci_string
		 *
		 * @return string
		 */
		private static function extract_numbers_from_string( $penci_string ) {
			$output = '';
			foreach ( str_split( $penci_string ) as $penci_char ) {
				if ( is_numeric( $penci_char ) ) {
					$output .= $penci_char;
				}
			}

			return $output;
		}

		public static function getTwitterFollowers($screenName = 'envato', $key = '', $src = '') {
		    // some variables
		    $consumerKey = $key;
		    $consumerSecret = $src;
		    $token = get_option('cfTwitterToken_'.$screenName);
		  
		    // get follower count from cache
		    $numberOfFollowers = get_transient('cfTwitterFollowers_'.$screenName);
		   
		    // cache version does not exist or expired
		    if (false === $numberOfFollowers) {
		        // getting new auth bearer only if we don't have one
		        if(!$token) {
		            // preparing credentials
		            $credentials = $consumerKey . ':' . $consumerSecret;
		            $toSend = base64_encode($credentials);
		   
		            // http post arguments
		            $args = array(
		                'method' => 'POST',
		                'httpversion' => '1.1',
		                'blocking' => true,
		                'headers' => array(
		                    'Authorization' => 'Basic ' . $toSend,
		                    'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
		                ),
		                'body' => array( 'grant_type' => 'client_credentials' )
		            );
		   
		            add_filter('https_ssl_verify', '__return_false');
		            $response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);
		   
		            $keys = json_decode(wp_remote_retrieve_body($response));
		   
		            if($keys) {
		                // saving token to wp_options table
		                update_option('cfTwitterToken_'.$screenName, $keys->access_token);
		                $token = $keys->access_token;
		            }
		        }
		        // we have bearer token wether we obtained it from API or from options
		        $args = array(
		            'httpversion' => '1.1',
		            'blocking' => true,
		            'headers' => array(
		                'Authorization' => "Bearer $token"
		            )
		        );
		   
		        add_filter('https_ssl_verify', '__return_false');
		        $api_url = "https://api.twitter.com/2/users/by/username/$screenName";
		        $response = wp_remote_get($api_url, $args);
		   
		        if (!is_wp_error($response)) {
		            $followers = json_decode(wp_remote_retrieve_body($response));
		            $numberOfFollowers = isset( $followers->followers_count ) ? $followers->followers_count : '';
		        } else {
		            // get old value and break
		            $numberOfFollowers = get_option('cfNumberOfFollowers_'.$screenName);
		            // uncomment below to debug
		           die($response->get_error_message());
		        }
		   
		        // cache for an hour
		        set_transient('cfTwitterFollowers_'.$screenName, $numberOfFollowers, 1*60*60);
		        update_option('cfNumberOfFollowers_'.$screenName, $numberOfFollowers);
		    }
		   
		    return $numberOfFollowers;
		}
	}

endif;
