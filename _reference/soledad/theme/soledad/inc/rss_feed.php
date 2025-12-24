<?php

class Penci_Rss_Feed {
	public static $shortcode_count = 1;

	public static function get_rss( $atts, $content = '' ) {
		$sc                   = self::get_short_code_attributes( $atts );
		$remove_default_style = isset( $sc['disable_default_style'] ) && in_array( (string) $sc['disable_default_style'], array(
				'1',
				'y',
				'yes'
			), true );
		if ( ! $remove_default_style ) {
			$settings = apply_filters( 'penci_rss_feed_get_settings', array() );
			if ( ! empty( $settings['general']['disable-default-style'] ) ) {
				$remove_default_style = true;
			}
		}


		$feed_url = self::normalize_urls( $sc['feeds'] );
		if ( empty( $feed_url ) ) {
			return $content;
		}
		$cache = $sc['refresh'];

		// Disregard the pseudo-shortcode coming from Gutenberg as a lazy one.
		if ( ( true === $sc['lazy'] || 'yes' === $sc['lazy'] ) && ! isset( $sc['gutenberg'] ) ) {
			$attributes = '';
			foreach ( $sc as $key => $val ) {
				// ignore the feedData, its not required.
				if ( 'feedData' === $key ) {
					continue;
				}
				if ( is_array( $val ) ) {
					$val = implode( ',', $val );
				}
				$attributes .= 'data-' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
			}
			$lazyload_cache_key = md5( sprintf( 'pcrss-lazy-%s', ( is_array( $feed_url ) ? implode( ',', $feed_url ) : $feed_url ) ) );
			$content            = get_transient( $lazyload_cache_key );

			// the first time the shortcode is being called it will not have any content.
			if ( empty( $content ) ) {
				$content = apply_filters( 'penci_rss_feed_lazyload_loading_msg', __( 'Loading', 'soledad' ) . '...', $feed_url );
			} else {
				$attributes .= 'data-has_valid_cache="true"';
			}
			$class = array_filter( apply_filters( 'penci_rss_feed_add_classes_block', array(
				$sc['classname'],
				'pcrss-' . md5( is_array( $feed_url ) ? implode( ',', $feed_url ) : $feed_url )
			), $sc, null, $feed_url ) );
			$html  = "<div class='pcrss-lazy' $attributes>";
			$html  .= "$content</div>";

			return $html;
		}

		$feed = self::fetch_feed( $feed_url, $cache, $sc );
		if ( is_string( $feed ) ) {
			return $feed;
		}
		$sc      = self::sanitize_attr( $sc, $feed_url );
		$content = self::render_content( $sc, $feed, $feed_url, $content );

		return $content;
	}

	public static function get_short_code_attributes( $atts ) {
		// Retrieve & extract shortcode parameters.
		$sc = shortcode_atts(
			array(
				// comma separated feeds url.
				'feeds'                 => '',
				// number of feeds items (0 for unlimited).
				'max'                   => '5',
				// display feed title yes/no.
				'feed_title'            => 'yes',
				// _blank, _self
				'target'                => '_blank',
				// empty or no for nofollow.
				'follow'                => '',
				// strip title after X char. X can be 0 too, which will remove the title.
				'title'                 => '',
				// yes (author, date, time), no (NEITHER), author, date, time, categories
				// tz=local (for date/time in blog time)
				// tz=gmt (for date/time in UTC time, this is the default)
				// tz=no (for date/time in the feed, without conversion).
				'meta'                  => 'yes',
				// yes (all), no (NEITHER)
				// source: show feed title.
				'multiple_meta'         => 'no',
				// strip title.
				'summary'               => 'yes',
				// strip summary after X char.
				'summarylength'         => '',
				// yes, no, auto.
				'thumb'                 => 'auto',
				// default thumb URL if no image found (only if thumb is set to yes or auto).
				'default'               => '',
				// thumbs pixel size.
				'size'                  => '',
				// only display item if title contains specific keywords (Use comma(,) and plus(+) keyword).
				'keywords_title'        => '',
				// only display item if title OR content contains specific keywords (Use comma(,) and plus(+) keyword).
				'keywords_inc'          => '',
				// Keyword filter include in specific field( title, description, author ).
				'keywords_inc_on'       => '',
				// Keyword filter exclude in specific field( title, description, author ).
				'keywords_exc_on'       => '',
				// cache refresh.
				'refresh'               => '12_hours',
				// sorting.
				'sort'                  => '',
				// https = force https
				// default = fall back to default image
				// auto = continue as it is.
				'http'                  => 'auto',
				// message to show when feed is empty.
				'error_empty'           => __( 'Feed has no items.', 'soledad' ),
				// to disable amp support, use 'no'. This is currently not available as part of the shortcode tinymce form.
				'amp'                   => 'yes',
				// paginate.
				'offset'                => 0,
				// class name of this block.
				'className'             => '',
				// lazy loading of feeds?
				'lazy'                  => 'no',
				// From datetime.
				'from_datetime'         => '',
				// To datetime.
				'to_datetime'           => '',
				// Disable default style.
				'disable_default_style' => 'no',
			),
			$atts,
			'penci_rss_feed_default'
		);
		if ( ! isset( $sc['classname'] ) ) {
			$sc['classname'] = $sc['className'];
			unset( $sc['className'] );
		}
		$sc = array_merge( $sc, apply_filters( 'penci_rss_feed_get_short_code_attributes_filter', $atts ) );

		return $sc;
	}

	public static function normalize_urls( $raw ) {
		$feeds    = self::process_feed_source( $raw );
		$feed_url = self::get_feed_url( $feeds );
		if ( is_array( $feed_url ) ) {
			foreach ( $feed_url as $index => $url ) {
				$feed_url[ $index ] = trim( self::smart_convert( $url ) );
			}
		} else {
			$feed_url = trim( self::smart_convert( $feed_url ) );
		}

		return $feed_url;
	}

	public static function process_feed_source( $src ) {
		$regex  = '((https?|ftp)\:\/\/)?';                                      // Contains Protocol.
		$regex .= '([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?';  // Uses User and Pass.
		$regex .= '([a-z0-9-.]*)\.([a-z]{2,3})';                                // Has Host or IP.
		$regex .= '(\:[0-9]{2,5})?';                                            // Uses Port.
		$regex .= '(\/([a-z0-9+\$_-]\.?)+)*\/?';                                // Has Path.
		$regex .= '(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?';                   // Has GET Query.
		$regex .= '(#[a-z_.-][a-z0-9+\$_.-]*)?';                                // Uses Anchor.
		if ( preg_match( "/^$regex$/", $src ) ) {
			// If it matches Regex ( it's not a slug ) so return the sources.
			return $src;
		} else {
			return trim( $src );
		}
	}

	public static function smart_convert( $url ) {

		$url = htmlspecialchars_decode( $url );

		// Automatically fix deprecated google news feeds.
		if ( false !== strpos( $url, 'news.google.' ) ) {

			$parts = wp_parse_url( $url );
			parse_str( $parts['query'], $query );

			if ( isset( $query['q'] ) ) {
				$search_query = $query['q'];
				unset( $query['q'] );
				$url = sprintf( 'https://news.google.com/news/rss/search/section/q/%s/%s?%s', $search_query, $search_query, http_build_query( $query ) );

			}
		}

		return apply_filters( 'penci_rss_feed_alter_feed_url', $url );
	}

	public static function fetch_feed( $feed_url, $cache = '12_hours', $sc = '' ) {
		// Load SimplePie if not already.
		do_action( 'penci_rss_feed_pre_http_setup', $feed_url );
		// Load SimplePie Instance.
		$feed = self::init_feed( $feed_url, $cache, $sc ); // Not used as log as #41304 is Opened.

		// Report error when is an error loading the feed.
		if ( is_wp_error( $feed ) ) {
			// Fallback for different edge cases.
			if ( is_array( $feed_url ) ) {
				$feed_url = array_map( 'html_entity_decode', $feed_url );
			} else {
				$feed_url = html_entity_decode( $feed_url );
			}

			$feed_url = self::get_valid_source_urls( $feed_url, $cache );

			$feed = self::init_feed( $feed_url, $cache, $sc ); // Not used as log as #41304 is Opened.

		}

		do_action( 'penci_rss_feed_post_http_teardown', $feed_url );

		return $feed;
	}

	public static function filter_custom_pattern( $keyword = '' ) {
		$pattern = '';
		$regex   = array();
		if ( ! empty( $keyword ) && strlen( preg_replace( '/[^a-zA-Z]/', '', $keyword ) ) <= 500 ) {
			$keywords = explode( ',', $keyword );
			$keywords = array_filter( $keywords );
			$keywords = array_map( 'trim', $keywords );
			if ( ! empty( $keywords ) ) {
				foreach ( $keywords as $keyword ) {
					$keyword = explode( '+', $keyword );
					$keyword = array_map(
						function( $k ) {
							$k = trim( $k );
							return "(?=.*$k)";
						},
						$keyword
					);
					$regex[] = implode( '', $keyword );
				}
				$pattern .= implode( '|', $regex );
			}
		}
		return $pattern;
	}

	public static function sanitize_attr( $sc, $feed_url ) {
		// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		if ( '0' == $sc['max'] ) {
			$sc['max'] = '999';
		} elseif ( empty( $sc['max'] ) || ! is_numeric( $sc['max'] ) ) {
			$sc['max'] = '5';
		}

		if ( empty( $sc['offset'] ) || ! is_numeric( $sc['offset'] ) ) {
			$sc['offset'] = '0';
		}

		if ( empty( $sc['size'] ) || ! ctype_digit( (string) $sc['size'] ) ) {
			$sc['size'] = '150';
		}
		if ( ! empty( $sc['keywords_title'] ) ) {
			if ( is_array( $sc['keywords_title'] ) ) {
				$sc['keywords_title'] = implode( ',', $sc['keywords_title'] );
			}
			$sc['keywords_title'] = self::filter_custom_pattern( $sc['keywords_title'] );
		}
		if ( ! empty( $sc['keywords_inc'] ) ) {
			if ( is_array( $sc['keywords_inc'] ) ) {
				$sc['keywords_inc'] = implode( ',', $sc['keywords_inc'] );
			}
			$sc['keywords_inc'] = self::filter_custom_pattern( $sc['keywords_inc'] );
		}
		if ( ! empty( $sc['keywords_ban'] ) ) {
			if ( is_array( $sc['keywords_ban'] ) ) {
				$sc['keywords_ban'] = implode( ',', $sc['keywords_ban'] );
			}
			$sc['keywords_ban'] = self::filter_custom_pattern( $sc['keywords_ban'] );
		}
		if ( ! empty( $sc['keywords_exc'] ) ) {
			if ( is_array( $sc['keywords_exc'] ) ) {
				$sc['keywords_exc'] = implode( ',', $sc['keywords_exc'] );
			}
			$sc['keywords_exc'] = self::filter_custom_pattern( $sc['keywords_exc'] );
		}
		if ( empty( $sc['summarylength'] ) || ! is_numeric( $sc['summarylength'] ) ) {
			$sc['summarylength'] = '';
		}
		if ( empty( $sc['default'] ) ) {
			$sc['default'] = apply_filters( 'penci_rss_feed_default_image', $sc['default'], $feed_url );
		}

		return $sc;
	}

	private static function render_content( $sc, $feed, $feed_url, $content = '' ) {
		$count                   = 0;
		$sizes                   = array(
			'width'  => $sc['size'],
			'height' => $sc['size'],
		);
		$sizes                   = apply_filters( 'penci_rss_feed_thumb_sizes', $sizes, $feed_url );
		$feed_title              = self::get_feed_title_filter( $feed, $sc, $feed_url );
		
		if ( $feed->error() ) {
			$content .= apply_filters( 'penci_rss_feed_default_error', $feed->error(), $feed, $feed_url );
		}

		$feed_items = self::get_feed_array( array(), $sc, $feed, $feed_url, $sizes );
		$class      = array_filter( apply_filters( 'penci_rss_feed_add_classes_block', array(
			$sc['classname'],
			'pcrss-' . md5( is_array( $feed_url ) ? implode( ',', $feed_url ) : $feed_url )
		), $sc, $feed, $feed_url ) );

		$main_class = 'pcrss-rss';
		if ( isset( $sc['disable_default_style'] ) && 'yes' === $sc['disable_default_style'] ) {
			$main_class = 'pcrss-' . self::$shortcode_count;
			if ( isset( $feed_title['rss_classes'] ) ) {
				$feed_title['rss_classes'][]         = $main_class;
				$feed_title['disable_default_style'] = true;
			}
			self::$shortcode_count ++;
		}
		$class[] = $main_class;

		$type                 = $sc['type'] ? $sc['type'] : '';
		$inner_wrapper_class = 'pcsl-inner penci-clearfix';
		$inner_wrapper_class .= ' pcsl-' . $type;
		$item_class          = 'normal-item';
		if ( 'crs' == $type ) {
			$inner_wrapper_class .= ' penci-owl-carousel swiper penci-owl-carousel-slider';
			$item_class          = 'swiper-slide';
		}

		$inner_wrapper_class .= ' pcsl-imgpos-' . $sc['imgpos'];
		$inner_wrapper_class .= ' pcsl-col-' . $sc['column'];
		$inner_wrapper_class .= ' pcsl-tabcol-' . $sc['tab_column'];
		$inner_wrapper_class .= ' pcsl-mobcol-' . $sc['mb_column'];
		if ( 'yes' == $sc['nocrop'] ) {
			$inner_wrapper_class .= ' pcsl-nocrop';
		}
		if ( 'yes' == $sc['hide_cat_mobile'] ) {
			$inner_wrapper_class .= ' pcsl-cat-mhide';
		}
		if ( 'yes' == $sc['hide_meta_mobile'] ) {
			$inner_wrapper_class .= ' pcsl-meta-mhide';
		}
		if ( 'yes' == $sc['hide_excerpt_mobile'] ) {
			$inner_wrapper_class .= ' pcsl-excerpt-mhide';
		}
		if ( 'yes' == $sc['hide_rm_mobile'] ) {
			$inner_wrapper_class .= ' pcsl-rm-mhide';
		}
		if ( 'yes' == $sc['imgtop_mobile'] && in_array( $sc['imgpos'], array( 'left', 'right' ) ) ) {
			$inner_wrapper_class .= ' pcsl-imgtopmobile';
		}
		if ( 'yes' == $sc['ver_border'] ) {
			$inner_wrapper_class .= ' pcsl-verbd';
		}

		$content .= '<div class="penci-smalllist-wrapper">';
		$content .= '<div class="penci-smalllist pcsl-wrapper pwsl-id-default">';
		$content .= '<div class="' . esc_attr( implode( ' ', $class ) .' ' . $inner_wrapper_class ) . '">';
		

		if ( empty( $feed_items ) ) {
			$content .= esc_html( $sc['error_empty'] );
			$content .= '</div></div></div>';

			return $content;
		}

		$disable_lazy = isset( $sc['disable_lazy'] ) ? $sc['disable_lazy'] : ''; 

		
		foreach ( $feed_items as $item ) {

				$wrap_item_class = empty( $item['item_img_path'] ) || 'no' == $sc['thumb'] ? ' pcsl-nothumb' : '';
				
				$content .= '<div class="pcsl-item'.$wrap_item_class.'"><div class="pcsl-itemin"><div class="pcsl-iteminer">';
				
				if ( ! empty( $item['item_img_path'] ) && 'no' !== $sc['thumb'] ) {
                	$content .= '<div class="pcsl-thumb">';
					$content .= '<a ' . penci_layout_bg($item['item_img_path'], $disable_lazy) . 
					' href="' . esc_url( $item['item_url'] ) . '" title="' . wp_strip_all_tags($item['item_title']) . 
					'" class="' . penci_layout_bg_class($disable_lazy) . ' penci-image-holder"' . '>' . 
					penci_layout_img($item['item_img_path'], $item['item_title'], $disable_lazy) . 
					'</a>';
					$content .= '</div>';
				}

				$content .= '<div class="pcsl-content">';
				
				$content .= '<div class="pcsl-title"><a rel="'.esc_attr( $item['item_url_follow'] ).'" target="'.esc_attr( $item['item_url_target'] ).'" href="'.esc_url( $item['item_url'] ).'">'.$item['item_title'].'</a></div>';
				
				$content .= '<div class="grid-post-box-meta pcsl-meta">';
				$content .= empty( $item['item_meta'] ) ? '' : sprintf( '<span>%s</span>', wp_kses_post( $item['item_meta'] ) );
				$content .= '</div>';

				if ( 'side' == $sc['excerpt_pos'] ) {

					$content .= '<div class="pcbg-pexcerpt pcsl-pexcerpt">';
					$content .= empty( $item['item_description'] ) ? '' : sprintf( '<p>%s</p>', wp_kses_post( $item['item_description'] ) );
					$content .= '</div>';
					$content .= '</div>';

				
		
				} else {
					$content .= '</div>';
					$content .= '<div class="pcsl-flex-full">';
					$content .= '<div class="pcbg-pexcerpt pcsl-pexcerpt">';
					$content .= empty( $item['item_description'] ) ? '' : sprintf( '<p>%s</p>', wp_kses_post( $item['item_description'] ) );
					$content .= '</div>';
					$content .= '</div>';
					
				}

				
				
				
                $content .= '</div></div></div>';
			
		}
		$content .= '</div></div></div>';
		

		return $content;
	}

	public static function init_feed( $feed_url, $cache, $sc, $allow_https = true ) {
		$unit_defaults = array(
			'mins'  => MINUTE_IN_SECONDS,
			'hours' => HOUR_IN_SECONDS,
			'days'  => DAY_IN_SECONDS,
		);
		$cache_time    = 12 * HOUR_IN_SECONDS;
		$cache         = trim( $cache );
		if ( isset( $cache ) && '' !== $cache ) {
			list( $value, $unit ) = explode( '_', $cache );
			if ( isset( $value ) && is_numeric( $value ) && $value >= 1 && $value <= 100 ) {
				if ( isset( $unit ) && in_array( strtolower( $unit ), array( 'mins', 'hours', 'days' ), true ) ) {
					$cache_time = $value * $unit_defaults[ $unit ];
				}
			}
		}

		$feed = new Penci_SimplePie( $sc );
		if ( ! $allow_https && method_exists( $feed, 'set_curl_options' ) ) {
			$feed->set_curl_options(
				array(
					CURLOPT_SSL_VERIFYHOST => false,
					CURLOPT_SSL_VERIFYPEER => false,
				)
			);
		}
		require_once ABSPATH . WPINC . '/class-wp-feed-cache-transient.php';
		require_once ABSPATH . WPINC . '/class-wp-simplepie-file.php';

		$feed->set_file_class( 'WP_SimplePie_File' );
		$default_agent = self::get_default_user_agent( $feed_url );
		$feed->set_useragent( apply_filters( 'http_headers_useragent', $default_agent ) );
		if ( false === apply_filters( 'penci_rss_feed_disable_db_cache', false, $feed_url ) ) {
			SimplePie_Cache::register( 'wp_transient', 'WP_Feed_Cache_Transient' );
			$feed->set_cache_location( 'wp_transient' );
			if ( ! has_filter( 'wp_feed_cache_transient_lifetime' ) ) {
				add_filter(
					'wp_feed_cache_transient_lifetime',
					function ( $time ) use ( $cache_time ) {
						return $cache_time;
					},
					10,
					1
				);
			}
			$feed->set_cache_duration( apply_filters( 'wp_feed_cache_transient_lifetime', $cache_time, $feed_url ) );
		} else {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;

			$dir = $wp_filesystem->wp_content_dir() . 'uploads/simplepie';
			if ( ! $wp_filesystem->exists( $dir ) ) {
				$done = $wp_filesystem->mkdir( $dir );
				
			}
			$feed->set_cache_location( $dir );
		}

		// Do not use force_feed for multiple URLs.
		$feed->force_feed( apply_filters( 'penci_rss_feed_force_feed', ( is_string( $feed_url ) || ( is_array( $feed_url ) && 1 === count( $feed_url ) ) ) ) );

		do_action( 'penci_rss_feed_modify_feed_config', $feed );

		$cloned_feed = clone $feed;

		// set the url as the last step, because we need to be able to clone this feed without the url being set
		// so that we can fall back to raw data in case of an error.
		$feed->set_feed_url( $feed_url );

		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$set_server_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
			$feed->set_useragent( apply_filters( 'http_headers_useragent', $set_server_agent ) );
		}

		global $penci_rss_feed_current_error_reporting;
		$penci_rss_feed_current_error_reporting = error_reporting();

		// to avoid the Warning! Non-numeric value encountered. This can be removed once SimplePie in core is fixed.
		if ( version_compare( phpversion(), '7.1', '>=' ) ) {
			error_reporting( E_ALL & ~E_WARNING & ~E_DEPRECATED );
			// reset the error_reporting back to its original value.
			add_action(
				'shutdown',
				function () {
					global $penci_rss_feed_current_error_reporting;
					error_reporting( $penci_rss_feed_current_error_reporting );
				}
			);
		}

		$feed->init();

		if ( ! $feed->get_type() ) {
			return $feed;
		}

		$error = $feed->error();
		// error could be an array, so let's join the different errors.
		if ( is_array( $error ) ) {
			$error = implode( '|', $error );
		}

		if ( ! empty( $error ) ) {

			// curl: (60) SSL certificate problem: unable to get local issuer certificate
			if ( strpos( $error, 'SSL certificate' ) !== false ) {
				$feed = self::init_feed( $feed_url, $cache, $sc, false );
			} elseif ( is_string( $feed_url ) || ( is_array( $feed_url ) && 1 === count( $feed_url ) ) ) {
				$data = wp_remote_retrieve_body( wp_safe_remote_get( $feed_url, array( 'user-agent' => $default_agent ) ) );
				$cloned_feed->set_raw_data( $data );
				$cloned_feed->init();
				$error_raw = $cloned_feed->error();
				if ( empty( $error_raw ) ) {
					// only if using the raw url produces no errors, will we consider the new feed as good to go.
					// otherwise we will use the old feed.
					$feed = $cloned_feed;
				}
			}
		}

		return $feed;
	}

	private static function get_feed_title_filter( $feed, $sc, $feed_url ) {
		return array(
			'rss_url'               => $feed->get_permalink(),
			'rss_title_class'       => 'rss_title',
			'rss_title'             => html_entity_decode( $feed->get_title() ),
			'rss_description_class' => 'rss_description',
			'rss_description'       => $feed->get_description(),
			'rss_classes'           => array(
				$sc['classname'],
				'pcrss-' . md5( is_array( $feed_url ) ? implode( ', ', $feed_url ) : $feed_url )
			),
		);
	}

	private static function get_default_user_agent( $urls ) {

		$set = array();
		if ( ! is_array( $urls ) ) {
			$set[] = $urls;
		}
		foreach ( $set as $url ) {
			if ( strpos( $url, 'medium.com' ) !== false ) {
				return 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';
			}
		}

		return SIMPLEPIE_USERAGENT;
	}

	protected function get_valid_source_urls( $feed_url, $cache, $echo = true ) {
		$valid_feed_url = array();
		if ( is_array( $feed_url ) ) {
			foreach ( $feed_url as $url ) {
				$source_type = 'xml';
				if ( self::check_valid_source( $url, $cache, $source_type ) ) {
					$valid_feed_url[] = $url;
				} else {
					if ( $echo ) {
						echo wp_kses_post( sprintf( __( 'Feed URL: %s not valid and removed from fetch.', 'soledad' ), '<b>' . esc_url( $url ) . '</b>' ) );
					}
				}
			}
		} else {
			$source_type = 'xml';
			if ( self::check_valid_source( $feed_url, $cache, $source_type ) ) {
				$valid_feed_url[] = $feed_url;
			} else {
				if ( $echo ) {
					echo wp_kses_post( sprintf( __( 'Feed URL: %s not valid and removed from fetch.', 'soledad' ), '<b>' . esc_url( $feed_url ) . '</b>' ) );
				}
			}
		}

		return $valid_feed_url;
	}

	protected function check_valid_source( $url, $cache, $source_type = 'xml' ) {
		global $post;

		// phpcs:disable WordPress.Security.NonceVerification
		if ( null === $post && ! empty( $_POST['id'] ) ) {
			$post_id = (int) $_POST['id'];
		} else {
			$post_id = $post->ID;
		}
		$is_valid = true;

		$feed = self::init_feed( $url, $cache, array() );
		if ( $feed->error() ) {
			$is_valid = false;
		}
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['penci_rss_feed_meta_data']['import_link_author_admin'] ) && 'yes' === $_POST['penci_rss_feed_meta_data']['import_link_author_admin'] ) {
			if ( $feed->get_items() ) {
				$author = $feed->get_items()[0]->get_author();
				if ( empty( $author ) ) {
					update_post_meta( $post_id, '__transient_penci_rss_feed_invalid_dc_namespace', array( $url ) );
					$is_valid = false;
				}
			}
		}

		// Update source type.
		update_post_meta( $post_id, '__penci_rss_feed_source_type', $source_type );

		return $is_valid;
	}

	public static function get_feed_array( $feed_items, $sc, $feed, $feed_url, $sizes ) {
		$count = 0;
		$items = apply_filters( 'penci_rss_feed_feed_items', $feed->get_items( $sc['offset'] ), $feed_url );
		$index = 0;
		foreach ( (array) $items as $item ) {
			$continue = self::item_additional_filter( true, $sc, $item, $feed_url, $index );
			if ( true === $continue ) {
				// Count items. This should be > and not >= because max, when not defined and empty, becomes 0.
				if ( $count >= $sc['max'] ) {
					break;
				}
				$item_attr            = apply_filters( 'penci_rss_feed_item_attributes', $item_attr = '', $sizes, $item, $feed_url, $sc, $index );
				$feed_items[ $count ] = self::get_feed_item_filter( $sc, $sizes, $item, $feed_url, $count, $index );
				if ( isset( $sc['disable_default_style'] ) && 'yes' === $sc['disable_default_style'] ) {
					$item_attr = preg_replace( '/ style=\\"[^\\"]*\\"/', '', $item_attr );
				}
				$feed_items[ $count ]['itemAttr'] = $item_attr;
				$count ++;
			}
			$index ++;
		}

		return $feed_items;
	}

	private static function get_feed_item_filter( $sc, $sizes, $item, $feed_url, $index, $item_index ) {
		$item_link = $item->get_permalink();
		// if the item has no link (possible in some cases), use the feed link.
		if ( empty( $item_link ) ) {
			$item_link = $item->get_id();
			if ( empty( $item_link ) ) {
				$item_link = $item->get_feed()->get_permalink();
			}
		}
		$new_link = apply_filters( 'penci_rss_feed_item_url_filter', $item_link, $sc, $item );
		
		$content_title = html_entity_decode( $item->get_title(), ENT_QUOTES, 'UTF-8' );
		if ( is_numeric( $sc['title'] ) ) {
			$length = intval( $sc['title'] );
			if ( 0 === $length ) {
				$content_title = '';
			}
			if ( $length > 0 && strlen( $content_title ) > $length ) {
				$content_title = preg_replace( '/\s+?(\S+)?$/', '', substr( $content_title, 0, $length ) ) . '...';
			}
		}
		if ( ! is_numeric( $sc['title'] ) && empty( $content_title ) ) {
			$content_title = esc_html__( 'Post Title', 'soledad' );
		}
		$content_title = apply_filters( 'penci_rss_feed_title_output', $content_title, $feed_url, $item );

		// meta=yes is for backward compatibility, otherwise its always better to provide the fields with granularity.
		// if meta=yes, then meta will be placed in default order. Otherwise in the order stated by the user.
		$meta_args = array(
			'author'      => 'yes' === $sc['meta'] || strpos( $sc['meta'], 'author' ) !== false,
			'date'        => 'yes' === $sc['meta'] || strpos( $sc['meta'], 'date' ) !== false,
			'time'        => 'yes' === $sc['meta'] || strpos( $sc['meta'], 'time' ) !== false,
			'source'      => 'yes' === $sc['multiple_meta'] || strpos( $sc['multiple_meta'], 'source' ) !== false,
			'categories'  => strpos( $sc['meta'], 'categories' ) !== false,
			'tz'          => 'gmt',
			'date_format' => get_option( 'date_format' ),
			'time_format' => get_option( 'time_format' ),
		);

		// parse the x=y type setting e.g. tz=local or tz=gmt.
		if ( strpos( $sc['meta'], '=' ) !== false ) {
			$components = array_map( 'trim', explode( ',', $sc['meta'] ) );
			foreach ( $components as $configs ) {
				if ( strpos( $configs, '=' ) === false ) {
					continue;
				}
				$config                  = explode( '=', $configs );
				$meta_args[ $config[0] ] = $config[1];
			}
		}

		// Filter: penci_rss_feed_meta_args.
		$meta_args = apply_filters( 'penci_rss_feed_meta_args', $meta_args, $feed_url, $item );

		// order of the meta tags.
		$meta_order = array( 'author', 'date', 'time', 'categories' );
		if ( 'yes' !== $sc['meta'] ) {
			$meta_order = array_map( 'trim', explode( ',', $sc['meta'] ) );
		}

		$content_meta_values = array();

		// multiple sources?
		$is_multiple = is_array( $feed_url );

		$feed_source = $item->get_feed()->get_title();
		// author.
		if ( $item->get_author() && $meta_args['author'] ) {
			$author      = $item->get_author();
			$author_name = $author->get_name();
			if ( ! $author_name ) {
				$author_name = $author->get_email();
			}

			$author_name = apply_filters( 'penci_rss_feed_author_name', $author_name, $feed_url, $item );

			if ( $is_multiple && $meta_args['source'] && ! empty( $feed_source ) ) {
				$author_name .= sprintf( ' (%s)', $feed_source );
			}

			if ( $author_name ) {
				$domain                        = wp_parse_url( $new_link );
				$author_url                    = isset( $domain['host'] ) ? '//' . $domain['host'] : '';
				$author_url                    = apply_filters( 'penci_rss_feed_author_url', $author_url, $author_name, $feed_url, $item );
				$content_meta_values['author'] = apply_filters( 'penci_rss_feed_meta_author', __( 'by', 'soledad' ) . ' <a href="' . $author_url . '" target="' . $sc['target'] . '" title="' . $domain['host'] . '" >' . $author_name . '</a> ', $author_name, $author_url, $feed_source, $feed_url, $item );
			}
		} elseif ( $is_multiple && $meta_args['source'] && ! empty( $feed_source ) ) {
			$domain                        = wp_parse_url( $new_link );
			$author_url                    = isset( $domain['host'] ) ? '//' . $domain['host'] : '';
			$author_url                    = apply_filters( 'penci_rss_feed_author_url', $author_url, $feed_source, $feed_url, $item );
			$content_meta_values['author'] = apply_filters( 'penci_rss_feed_meta_author', __( 'by', 'soledad' ) . ' <a href="' . $author_url . '" target="' . $sc['target'] . '" title="' . $domain['host'] . '" >' . $feed_source . '</a> ', $feed_source, $author_url, $feed_source, $feed_url, $item );
		}

		// date/time.
		$date_time = $item->get_date( 'U' );
		if ( 'local' === $meta_args['tz'] ) {
			$date_time = get_date_from_gmt( $item->get_date( 'Y-m-d H:i:s' ), 'U' );
			// strings such as Asia/Kolkata need special handling.
			$tz = get_option( 'timezone_string' );
			if ( $tz ) {
				$date_time = gmdate( 'U', $date_time + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
			}
		} elseif ( 'no' === $meta_args['tz'] ) {
			// change the tz component of the date to UTC.
			$raw_date  = preg_replace( '/\++(\d\d\d\d)/', '+0000', $item->get_date( '' ) );
			$date      = DateTime::createFromFormat( DATE_RFC2822, $raw_date );
			$date_time = $date->format( 'U' );
		}

		$date_time = apply_filters( 'penci_rss_feed_feed_timestamp', $date_time, $feed_url, $item );
		if ( $meta_args['date'] && ! empty( $meta_args['date_format'] ) ) {
			$content_meta_values['date'] = apply_filters( 'penci_rss_feed_meta_date', __( 'on', 'soledad' ) . ' ' . date_i18n( $meta_args['date_format'], $date_time ) . ' ', $date_time, $feed_url, $item );
		}

		if ( $meta_args['time'] && ! empty( $meta_args['time_format'] ) ) {
			$content_meta_values['time'] = apply_filters( 'penci_rss_feed_meta_time', __( 'at', 'soledad' ) . ' ' . date_i18n( $meta_args['time_format'], $date_time ) . ' ', $date_time, $feed_url, $item );
		}

		// categories.
		if ( $meta_args['categories'] ) {
			$categories = self::retrieve_categories( null, $item );
			if ( ! empty( $categories ) ) {
				$content_meta_values['categories'] = apply_filters( 'penci_rss_feed_meta_categories', __( 'in', 'soledad' ) . ' ' . $categories . ' ', $categories, $feed_url, $item );
			}
		}

		$content_meta      = '';
		$content_meta_date = '';
		foreach ( $meta_order as $meta ) {
			if ( isset( $content_meta_values[ $meta ] ) ) {
				// collect date/time values separately too.
				if ( in_array( $meta, array( 'date', 'time' ), true ) ) {
					$content_meta_date .= $content_meta_values[ $meta ];
				}
				$content_meta .= $content_meta_values[ $meta ];
			}
		}

		$content_meta    = apply_filters( 'penci_rss_feed_meta_output', $content_meta, $feed_url, $item, $content_meta_values, $meta_order );
		$content_summary = '';
		if ( 'yes' === $sc['summary'] ) {
			$description     = $item->get_description();
			$description     = apply_filters( 'penci_rss_feed_summary_input', $description, $item->get_content(), $feed_url, $item );
			$content_summary = $description;
			if ( is_numeric( $sc['summarylength'] ) && strlen( $description ) > $sc['summarylength'] ) {
				$content_summary = preg_replace( '/\s+?(\S+)?$/', '', substr( $description, 0, $sc['summarylength'] ) ) . ' [&hellip;]';
			}
			$content_summary = apply_filters( 'penci_rss_feed_summary_output', $content_summary, $new_link, $feed_url, $item );
		}
		$item_content = $item->get_content( false );
		if ( empty( $item_content ) ) {
			$item_content = esc_html__( 'Post Content', 'soledad' );
		}
		$item_array = array(
			'feed_url'              => $item->get_feed()->subscribe_url(),
			'item_unique_hash'      => wp_hash( $item->get_permalink() ),
			'item_url'              => $new_link,
			'item_url_target'       => $sc['target'],
			'item_url_follow'       => isset( $sc['follow'] ) && 'yes' === $sc['follow'] ? 'nofollow' : '',
			'item_url_title'        => $item->get_title(),
			'item_img_path'         => self::penci_rss_feed_retrieve_image( $item, $sc ),
			'item_title'            => $content_title,
			'item_content_class'    => 'rss_content',
			'item_content_style'    => '',
			'item_meta'             => $content_meta,
			'item_date'             => $item->get_date( 'U' ),
			'item_date_formatted'   => $content_meta_date,
			'item_author'           => $item->get_author(),
			'item_description'      => $content_summary,
			'item_content'          => apply_filters( 'penci_rss_feed_content', $item_content, $item ),
			'item_source'           => $feed_source,
			'item_full_description' => $item->get_description(),
		);
		$item_array = apply_filters( 'penci_rss_feed_item_filter', $item_array, $item, $sc, $index, $item_index );

		return $item_array;
	}

	public static function retrieve_categories( $dumb, $item ) {
		$cats       = array();
		$categories = $item->get_categories();
		if ( $categories ) {
			foreach ( $categories as $category ) {
				if ( is_string( $category ) ) {
					$cats[] = $category;
				} else {
					$cats[] = $category->get_label();
				}
			}
		}

		return apply_filters( 'penci_rss_feed_categories', implode( ', ', $cats ), $cats, $item );
	}

	public static function penci_rss_feed_retrieve_image( $item, $sc = null ) {
		$image_mime_types = array();
		foreach ( wp_get_mime_types() as $extn => $mime ) {
			if ( strpos( $mime, 'image/' ) !== false ) {
				$image_mime_types[] = $mime;
			}
		}

		$image_mime_types = apply_filters( 'penci_rss_feed_image_mime_types', $image_mime_types );

		$the_thumbnail = '';
		$enclosures    = $item->get_enclosures();
		if ( $enclosures ) {
			foreach ( (array) $enclosures as $enclosure ) {
				// Item thumbnail.
				$thumbnail = $enclosure->get_thumbnail();
				$medium    = $enclosure->get_medium();
				if ( in_array( $medium, array( 'video' ), true ) ) {
					break;
				}
				if ( $thumbnail ) {
					$the_thumbnail = $thumbnail;
				}
				if ( isset( $enclosure->thumbnails ) ) {
					foreach ( (array) $enclosure->thumbnails as $thumbnail ) {
						$the_thumbnail = $thumbnail;
					}
				}
				$thumbnail = $enclosure->embed();
				if ( $thumbnail ) {
					$pattern = '/https?:\/\/.*\.(?:jpg|JPG|jpeg|JPEG|jpe|JPE|gif|GIF|png|PNG)/i';
					if ( preg_match( $pattern, $thumbnail, $matches ) ) {
						$the_thumbnail = $matches[0];
					}
				}
				foreach ( (array) $enclosure->get_link() as $thumbnail ) {
					$pattern = '/https?:\/\/.*\.(?:jpg|JPG|jpeg|JPEG|jpe|JPE|gif|GIF|png|PNG)/i';
					$imgsrc  = $thumbnail;
					if ( preg_match( $pattern, $imgsrc, $matches ) ) {
						$the_thumbnail = $thumbnail;
						break;
					} elseif ( in_array( $enclosure->type, $image_mime_types, true ) ) {
						$the_thumbnail = $thumbnail;
						break;
					}
				}
				// Break loop if thumbnail is found.
				if ( ! empty( $the_thumbnail ) ) {
					break;
				}
			}
		}
		// xmlns:itunes podcast.
		if ( empty( $the_thumbnail ) ) {
			$data = $item->get_item_tags( 'http://www.itunes.com/dtds/podcast-1.0.dtd', 'image' );
			if ( isset( $data['0']['attribs']['']['href'] ) && ! empty( $data['0']['attribs']['']['href'] ) ) {
				$the_thumbnail = $data['0']['attribs']['']['href'];
			}
		}
		// Content image.
		if ( empty( $the_thumbnail ) ) {
			$feed_description = $item->get_content();
			$the_thumbnail    = self::penci_rss_feed_return_image( $feed_description );
		}
		// Description image.
		if ( empty( $the_thumbnail ) ) {
			$feed_description = $item->get_description();
			$the_thumbnail    = self::penci_rss_feed_return_image( $feed_description );
		}

		// handle HTTP images.
		if ( $sc && isset( $sc['http'] ) && 0 === strpos( $the_thumbnail, 'http://' ) ) {
			switch ( $sc['http'] ) {
				case 'https':
					// fall-through.
				case 'force':
					$the_thumbnail = str_replace( 'http://', 'https://', $the_thumbnail );
					break;
				case 'default':
					$the_thumbnail = $sc['default'];
					break;
			}
		}

		$the_thumbnail = html_entity_decode( $the_thumbnail, ENT_QUOTES, 'UTF-8' );
		if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
			$feed_url      = self::normalize_urls( $sc['feeds'] );
			$the_thumbnail = ! empty( $the_thumbnail ) ? $the_thumbnail : apply_filters( 'penci_rss_feed_default_image', $sc['default'], $feed_url );
		}
		$the_thumbnail = apply_filters( 'penci_rss_feed_retrieve_image', $the_thumbnail, $item );

		return $the_thumbnail;
	}

	public static function penci_rss_feed_return_image( $string ) {
		$img     = html_entity_decode( $string, ENT_QUOTES, 'UTF-8' );
		$pattern = '/<img[^>]+\>/i';
		preg_match_all( $pattern, $img, $matches );

		$image = null;
		if ( isset( $matches[0] ) ) {
			foreach ( $matches[0] as $match ) {
				$link         = self::penci_rss_feed_scrape_image( $match );
				$blacklist    = self::penci_rss_feed_blacklist_images();
				$is_blacklist = false;
				foreach ( $blacklist as $string ) {
					if ( strpos( (string) $link, $string ) !== false ) {
						$is_blacklist = true;
						break;
					}
				}
				if ( ! $is_blacklist ) {
					$image = $link;
					break;
				}
			}
		}

		return $image;
	}

	public static function penci_rss_feed_scrape_image( $string, $link = '' ) {
		$pattern = '/< *img[^>]*src *= *["\']?([^"\']*)/';
		$match   = $link;
		preg_match( $pattern, $string, $link );
		if ( ! empty( $link ) && isset( $link[1] ) ) {
			$match = $link[1];
		}

		return $match;
	}

	public static function penci_rss_feed_blacklist_images() {
		$blacklist = array(
			'frownie.png',
			'icon_arrow.gif',
			'icon_biggrin.gif',
			'icon_confused.gif',
			'icon_cool.gif',
			'icon_cry.gif',
			'icon_eek.gif',
			'icon_evil.gif',
			'icon_exclaim.gif',
			'icon_idea.gif',
			'icon_lol.gif',
			'icon_mad.gif',
			'icon_mrgreen.gif',
			'icon_neutral.gif',
			'icon_question.gif',
			'icon_razz.gif',
			'icon_redface.gif',
			'icon_rolleyes.gif',
			'icon_sad.gif',
			'icon_smile.gif',
			'icon_surprised.gif',
			'icon_twisted.gif',
			'icon_wink.gif',
			'mrgreen.png',
			'rolleyes.png',
			'simple-smile.png',
			'//s.w.org/images/core/emoji/',
		);

		return apply_filters( 'penci_rss_feed_feed_blacklist_images', $blacklist );
	}

	public static function penci_rss_feed_image_encode( $string ) {
		// Check if img url is set as an URL parameter.
		$url_tab = wp_parse_url( $string );
		if ( isset( $url_tab['query'] ) ) {
			preg_match_all( '/(http|https):\/\/[^ ]+(\.gif|\.GIF|\.jpg|\.JPG|\.jpeg|\.JPEG|\.png|\.PNG)/', $url_tab['query'], $img_url );
			if ( isset( $img_url[0][0] ) ) {
				$string = $img_url[0][0];
			}
		}

		$return = apply_filters( 'penci_rss_feed_image_encode', esc_url( $string ), $string );

		return $return;
	}
	public static function item_additional_filter( $continue, $sc, $item, $feed_url ) {
		$keywords_ban = $sc['keywords_ban'];
		if ( ! empty( $keywords_ban ) ) {
			foreach ( $keywords_ban as $keyword ) {
				if ( strpos( $item->get_title(), $keyword ) !== false || strpos( $item->get_content(), $keyword ) !== false ) {
					$continue = false;
				}
			}
		}

		if ( ! empty( $sc['keywords_inc'] ) || ! empty( $sc['keywords_title'] ) ) {
			return $continue;
		}

		if ( ! empty( $sc['keywords_exc'] ) || ! empty( $sc['keywords_ban'] ) ) {
			return $continue;
		}

		// Date filter.
		if ( $continue && ! empty( $sc['from_datetime'] ) && ! empty( $sc['to_datetime'] ) ) {
			$from_datetime = strtotime( $sc['from_datetime'] );
			$to_datetime   = strtotime( $sc['to_datetime'] );
			$item_date     = strtotime( $item->get_date() );
			$continue      = ( ( $from_datetime <= $item_date ) && ( $item_date <= $to_datetime ) );
		}

		return $continue;
	}

	public static function get_feed_url( $feeds ) {
		$feed_url = '';
		if ( ! empty( $feeds ) ) {
			$feeds    = rtrim( $feeds, ',' );
			$feeds    = explode( ',', $feeds );
			$feed_url = array();
			// Remove SSL from HTTP request to prevent fetching errors.
			foreach ( $feeds as $feed ) {
				$feed = trim( $feed );
				// scheme-less URLs.
				if ( strpos( $feed, 'http' ) !== 0 ) {
					$feed = 'http://' . $feed;
				}

				if ( is_array( $feed ) ) {
					foreach ( $feed as $f ) {
						$feed_url[] = $f;
					}
				} else {
					$feed_url[] = $feed;
				}
			}
			if ( count( $feed_url ) === 1 ) {
				$feed_url = $feed_url[0];
			}
		}

		return $feed_url;
	}
}