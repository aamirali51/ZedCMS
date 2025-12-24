<?php
// No direct access, please
function penci_is_new_update( $version = null ) {
	if ( ! is_admin() || get_theme_mod( 'penci_disable_notice_updates' ) ) {
		return false;
	}

	$url = 'https://penci-api.s3.amazonaws.com/datas.json';
	// Namespace in case of collision, since transients don't support groups like object caching.
	$cache_key = md5( 'remote_apis|' . $url );
	$request   = get_transient( $cache_key );

	if ( false === $request ) {
		$request = wp_remote_get( $url );

		if ( is_wp_error( $request ) ) {
			// Cache failures for a short time, will speed up page rendering in the event of remote failure. Cache for 15 mins = 60 * 15 = 900
			set_transient( $cache_key, $request, 900 );

			return false;
		}
		// Success, cache for a longer time - 24 hours = 60 * 60 * 24 = 86400
		set_transient( $cache_key, $request, 86400 );
	}

	if ( is_wp_error( $request ) ) {
		return false;
	}

	$body = wp_remote_retrieve_body( $request );
	$data = json_decode( $body );

	if ( ! empty( $data ) ) {
		$current_version = $data->soledad->version;
		if ( isset( $current_version ) ) {
			if ( 'version' == $version ) {
				return $current_version;
			}
			if ( ! defined( 'PENCI_SOLEDAD_VERSION' ) || empty( PENCI_SOLEDAD_VERSION ) || version_compare( $current_version, PENCI_SOLEDAD_VERSION, '<=' ) ) {
				return false;
			} else {
				return true;
			}
		}
	}
}

function penci_is_new_promotion( $data_check = false ) {
	if ( ! is_admin() ) {
		return false;
	}

	$url = 'https://penci-api.s3.amazonaws.com/datas.json';
	
	// Namespace in case of collision, since transients don't support groups like object caching.
	$cache_key = md5( 'remote_apis_promo|' . $url );
	$request = get_transient( $cache_key );

	if ( false === $request ) {
		$request = wp_remote_get( $url );

		if ( is_wp_error( $request ) ) {
			// Cache failures for a short time, will speed up page rendering in the event of remote failure. Cache for 15 mins = 60 * 15 = 900
			set_transient( $cache_key, $request, 900 );
			return false;
		}
		// Success, cache for a longer time - 24 hours = 60 * 60 * 24 = 86400
		set_transient( $cache_key, $request, 86400 );
	}

	if ( is_wp_error( $request ) ) {
		return false;
	}


	$body = wp_remote_retrieve_body( $request );
	$data = json_decode( $body );

	if ( ! empty( $data ) ) {
		$banner_url  = isset( $data->promo->img ) && $data->promo->img ? $data->promo->img : '';
		$banner_link = isset( $data->promo->url ) && $data->promo->url ? $data->promo->url : '';

		if ( $data_check == false ) {
			if( $banner_link && $banner_url ){
				return true;
			}
		} else {
			if( $banner_link && $banner_url ){
				return [ 'url' => $banner_url, 'link' => $banner_link ];
			}
		}

		return false;
	}
}