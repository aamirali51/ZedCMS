<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! function_exists('penci_global_js') ) {
	function penci_global_js(){

		$output = '<script>' . "\n";
		$output .= 'var penciBlocksArray=[];' . "\n";
		$output .= 'var portfolioDataJs = portfolioDataJs || [];';
		$output .= 'var PENCILOCALCACHE = {};
		(function () {
				"use strict";
		
				PENCILOCALCACHE = {
					data: {},
					remove: function ( ajaxFilterItem ) {
						delete PENCILOCALCACHE.data[ajaxFilterItem];
					},
					exist: function ( ajaxFilterItem ) {
						return PENCILOCALCACHE.data.hasOwnProperty( ajaxFilterItem ) && PENCILOCALCACHE.data[ajaxFilterItem] !== null;
					},
					get: function ( ajaxFilterItem ) {
						return PENCILOCALCACHE.data[ajaxFilterItem];
					},
					set: function ( ajaxFilterItem, cachedData ) {
						PENCILOCALCACHE.remove( ajaxFilterItem );
						PENCILOCALCACHE.data[ajaxFilterItem] = cachedData;
					}
				};
			}
		)();';

		$output .= "function penciBlock() {
		    this.atts_json = '';
		    this.content = '';
		}";
		$output .= '</script>' . "\n";

		echo $output;
	}
}

add_action('wp_head', 'penci_global_js', 10);