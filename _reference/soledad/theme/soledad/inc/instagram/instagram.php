<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include( trailingslashit( PENCI_SOLEDAD_DIR ) . 'inc/instagram/widget.php' );
include( trailingslashit( PENCI_SOLEDAD_DIR ) . 'inc/instagram/dashpage.php' );

if ( ! class_exists( 'Penci_Instagram_Feed' ) ):
	class Penci_Instagram_Feed {
		public static function display_images( $args = null ) {

			$insta_token = get_option( 'penci_options[penci_instagram]' );

			$defaults = array(
				'access_token'     => isset( $insta_token['access_token'] ) && $insta_token['access_token'] ? $insta_token['access_token'] : '',
				'insta_user_id'    => isset( $insta_token['id'] ) && $insta_token['id'] ? $insta_token['id'] : '',
				'search_for'       => 'username',
				'username'         => isset( $insta_token['username'] ) && $insta_token['username'] ? $insta_token['username'] : '',
				'hashtag'          => '',
				'blocked_users'    => '',
				'template'         => 'thumbs-no-border',
				'images_link'      => 'image_url',
				'custom_url'       => '',
				'orderby'          => 'rand',
				'images_number'    => 5,
				'columns'          => 4,
				'refresh_hour'     => 5,
				'image_size'       => 'jr_insta_square',
				'image_link_rel'   => '',
				'image_link_class' => '',
				'no_pin'           => 0,
				'controls'         => 'prev_next',
				'caption_words'    => 20,
				'slidespeed'       => 7000,
				'description'      => array( 'username', 'time', 'caption' ),
			);

			$args = wp_parse_args( (array) $args, $defaults );

			if ( 'username' == $args['search_for'] && ! $args['access_token'] ) {
				if ( current_user_can( 'manage_options' ) ) {
					echo '<p style="text-align: center;">This message appears for Admin Users only:<br>Please fill the Instagram Access Token. You can get Instagram Access Token by go to <a
                            href="' . esc_url( admin_url( 'admin.php?page=penci_instgram_token' ) ) . '"
                            target="_blank">this page</a></p>';
				}

				return;
			} elseif ( 'hashtag' == $args['search_for'] && ! $args['hashtag'] ) {
				if ( current_user_can( 'manage_options' ) ) {
					echo '<p style="text-align: center;">This message appears for Admin Users only:<br>Please enter hashtag.</p>';
				}
			}

			$images_data = self::get_instagram_data( $args );

			//print_r( $images_data );


			if ( ! is_array( $images_data ) || ! $images_data ) {
				if ( current_user_can( 'manage_options' ) ) {
					echo '<p style="text-align: center;">This message appears for Admin Users only:<br>No any image found. Please check it again or try with another instagram account.</p>';
				}

				return;
			}

			$output = '';

			$i = 0;

			if ( $args['orderby'] != 'rand' ) {

				$orderby = explode( '-', $args['orderby'] );
				$func    = $orderby[0] == 'date' ? 'sort_timestamp_' . $orderby[1] : 'sort_popularity_' . $orderby[1];

				usort( $images_data, array( __CLASS__, $func ) );

			} else {

				shuffle( $images_data );
			}

			foreach ( $images_data as $key => $image_data ) {

				if ( $i >= $args['images_number'] ) {
					continue;
				}


				$image_url = $link_to = '';
				if ( 'image_url' == $args['images_link'] ) {
					$link_to = $image_data['link'];
				} elseif ( 'user_url' == $args['images_link'] ) {
					$link_to = 'https://www.instagram.com/' . $args['username'] . '/';
				}

				if ( $args['image_size'] == 'jr_insta_square' ) {
					$image_url = $image_data['url_thumbnail'];
				} elseif ( $args['image_size'] == 'full' ) {
					$image_url = $image_data['url'];
				} else {
					$image_url = $image_data['url'];
				}

				$short_caption = wp_trim_words( $image_data['caption'], 10, '...' );
				$caption       = wp_trim_words( $image_data['caption'], intval( $args['caption_words'] ), $more = null );
				$nopin         = ( 1 == $args['no_pin'] ) ? 'nopin="nopin"' : '';

				$image_src = '<span style="background-image:url('.$image_url.')" class="penci-image-holder instagram-square-lazy '.penci_layout_bg_class().'">'. penci_layout_img( $image_url ).'</span>';

				$image_output = $image_src;
				if ( $link_to ) {
					$image_output = '<a href="' . $link_to . '" rel="noopener" target="_blank"';

					if ( ! empty( $args['image_link_rel'] ) ) {
						$image_output .= ' rel="' . $args['image_link_rel'] . '"';
					}

					if ( ! empty( $args['image_link_class'] ) ) {
						$image_output .= ' class="' . $args['image_link_class'] . '"';
					}
					$image_output .= ' title="' . $short_caption . '">' . $image_src . '</a>';
				}

				if ( 'slider' == $args['template'] ) {
					$output .= '<div class="penci-insta-info swiper-slide">';
					$output .= '<img class="instagram-square-lazy" src="' . $image_url . '" alt="' . $short_caption . '" ' . $nopin . '/>';;

					if ( is_array( $args['description'] ) && count( $args['description'] ) >= 1 ) {

						$output .= '<div class="penci-insta-datacontainer">';

						if ( $image_data['timestamp'] && in_array( 'time', $args['description'] ) ) {
							$time   = human_time_diff( strtotime( $image_data['timestamp'] ), current_time( 'timestamp', true ) );
							$output .= "<span class='penci-insta-time'>{$time} ago</span>\n";
						}

						$username = $args['username'];
						if ( in_array( 'username', $args['description'] ) && $username ) {
							$output .= "<span class='penci-insta-username'>by <a rel='nofollow' href='https://www.instagram.com/{$username}/' target='_blank'>{$username}</a></span>\n";
						}

						if ( $caption != '' && in_array( 'caption', $args['description'] ) ) {
							$caption_words = isset( $args['caption_words'] ) ? $args['caption_words'] : 20;
							$caption       = preg_replace( '/@([a-z0-9_]+)/i', '&nbsp;<a href="https://www.instagram.com/$1/" rel="noopener" target="_blank">@$1</a>&nbsp;', $caption );
							$caption       = preg_replace( '/\#([a-zA-Z0-9_-]+)/i', '&nbsp;<a href="https://www.instagram.com/explore/tags/$1/" rel="noopener" target="_blank">$0</a>&nbsp;', $caption );
							$output        .= "<span class='penci-insta-caption'>" . wp_trim_words( $caption, $caption_words, '...' ) . "</span>\n";
						}

						$output .= "</div>\n";
					}

					$output .= "</div>";
				} elseif ( 'slider-overlay' == $args['template'] ) {
					$output .= '<div class="penci-insta-info swiper-slide">';
					$output .= '<img class="instagram-square-lazy" src="' . $image_url . '" alt="' . $short_caption . '" ' . $nopin . '/>';;

					if ( is_array( $args['description'] ) && count( $args['description'] ) >= 1 ) {

						$output .= '<div class="penci-insta-wrap"><div class="penci-insta-datacontainer">';

						if ( $image_data['timestamp'] && in_array( 'time', $args['description'] ) ) {
							$time   = human_time_diff( strtotime( $image_data['timestamp'] ), current_time( 'timestamp', true ) );
							$output .= "<span class='penci-insta-time'>{$time} ago</span>\n";
						}

						$username = $args['username'];
						if ( in_array( 'username', $args['description'] ) && $username ) {
							$output .= "<span class='penci-insta-username'>by <a rel='nofollow' href='https://www.instagram.com/{$username}/' target='_blank'>{$username}</a></span>\n";
						}

						if ( $caption != '' && in_array( 'caption', $args['description'] ) ) {
							$caption_words = isset( $args['caption_words'] ) ? $args['caption_words'] : 20;
							$caption       = preg_replace( '/@([a-z0-9_]+)/i', '&nbsp;<a href="https://www.instagram.com/$1/" rel="noopener" target="_blank">@$1</a>&nbsp;', $caption );
							$caption       = preg_replace( '/\#([a-zA-Z0-9_-]+)/i', '&nbsp;<a href="https://www.instagram.com/explore/tags/$1/" rel="noopener" target="_blank">$0</a>&nbsp;', $caption );
							$output        .= "<span class='penci-insta-caption'>" . wp_trim_words( $caption, $caption_words, '...' ) . "</span>\n";
						}

						$output .= "</div></div>";
					}

					$output .= "</div>";
				} else {
					$output .= "<li>";
					$output .= $image_output;
					$output .= "</li>";
				}

				$i ++;
			}


			$data_slider = ' data-auto="false"';
			$data_slider .= ' data-autotime="' . ( $args['slidespeed'] ? $args['slidespeed'] : '5000' ) . '"';
			$data_slider .= ' data-dots="' . ( 'numberless' == $args['controls'] ? 'true' : '' ) . '"';
			$data_slider .= ' data-nav="' . ( 'prev_next' == $args['controls'] ? '' : 'true' ) . '"';

			if ( $output ) {
				if ( 'slider' == $args['template'] ) {
					echo '<div class="penci-instaslider-normal swiper penci-owl-carousel penci-owl-carousel-slider"' . $data_slider . '><div class="swiper-wrapper">' . $output . '</div></div>';
				} elseif ( 'slider-overlay' == $args['template'] ) {
					echo '<div class="penci-instaslider-overlay swiper penci-owl-carousel penci-owl-carousel-slider"' . $data_slider . '><div class="swiper-wrapper">' . $output . '</div></div>';
				} elseif ( 'thumbs-no-border' == $args['template'] ) {
					echo '<div class="penci-insta-thumb"><ul class="thumbnails no-border penci-inscol' . $args['columns'] . '">' . $output . '</ul></div>';
				} else {
					echo '<div class="penci-insta-thumb"><ul class="thumbnails penci-inscol' . $args['columns'] . '">' . $output . '</ul></div>';
				}
			}
		}

		public static function get_instagram_data( $args ) {

			$blocked_users = $args['blocked_users'];
			if ( 'username' == $args['search_for'] ) {
				$search        = 'user';
				$search_string = $args['username'];
			} elseif ( $args['hashtag'] ) {
				$search              = 'hashtag';
				$search_string       = $args['hashtag'];
				$blocked_users_array = $blocked_users ? self::get_ids_from_usernames( $blocked_users ) : array();
			} elseif ( $args['hashtag'] ) {
				$search        = 'hashtag';
				$search_string = $args['hashtag'];
			} else {
				$search        = '';
				$search_string = '';
			}

			$opt_name   = 'penci_insta_' . md5( $search . '_' . $search_string );
			$insta_data = get_transient( $opt_name );
			$old_opts   = (array) get_option( $opt_name );

			$new_opts = array(
				'search'        => $search,
				'search_string' => $search_string,
				'blocked_users' => $blocked_users,
				'cache_hours'   => $args['refresh_hour'],
			);

			//print_r( self::get_images_data_for_token( $args['access_token'], $args['insta_user_id'] ) );

			if ( true === self::trigger_refresh_data( $insta_data, $old_opts, $new_opts ) ) {

				if ( 'username' == $args['search_for'] ) {
					$insta_data = self::get_images_data_for_token( $args['access_token'], $args['insta_user_id'] );
				} elseif ( $args['hashtag'] ) {
					$insta_data = self::get_images_data_for_hashtag( $search_string, $blocked_users_array );
				}

				update_option( $opt_name, $new_opts );

				if ( is_array( $insta_data ) && ! empty( $insta_data ) ) {
					set_transient( $opt_name, $insta_data, $args['refresh_hour'] * 60 * 60 );
				}
			}

			return $insta_data;
		}

		public static function trigger_refresh_data( $insta_data, $old_opts, $new_opts ) {
			$trigger = 0;

			if ( isset( $_GET['penci_remove_cache_ins'] ) ) {
				return true;
			}

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return false;
			}

			if ( false === $insta_data ) {
				$trigger = 1;
			}

			if ( is_array( $old_opts ) && is_array( $old_opts ) && array_diff( $old_opts, $new_opts ) !== array_diff( $new_opts, $old_opts ) ) {
				$trigger = 1;
			}

			if ( $trigger == 1 ) {
				return true;
			}

			return false;
		}

		public static function get_images_data_for_token( $access_token, $user_id ) {
			$data_images = array();

			$access_token = self::clean_token( $access_token );


			$response_url = 'https://graph.instagram.com/' . $user_id . '/media?fields=media_url,thumbnail_url,caption,id,media_type,timestamp,username,permalink,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}&limit=30&access_token=' . $access_token;

			$response = wp_remote_get( $response_url, array( 'timeout' => 60, 'sslverify' => false ) );


			if ( ! is_wp_error( $response ) ) {
				$results      = json_decode( str_replace( '%22', '&rdquo;', $response['body'] ), true );
				$data_results = isset( $results['data'] ) ? (array) $results['data'] : array();

				if ( $data_results ) {
					foreach ( $data_results as $data_item ) {

						$data_item_id    = isset( $data_item['id'] ) ? $data_item['id'] : '';
						$data_media_type = isset( $data_item['media_type'] ) ? $data_item['media_type'] : '';

						$data_item_url_thumbnail = isset( $data_item['media_url'] ) ? $data_item['media_url'] : '';
						if ( 'VIDEO' == $data_media_type || 'video' == $data_media_type ) {
							$data_item_url_thumbnail = isset( $data_item['thumbnail_url'] ) ? $data_item['thumbnail_url'] : '';
						}

						if ( $data_item_id ) {
							$data_images[ $data_item_id ] = array(
								'code'          => '',
								'username'      => '',
								'user_id'       => '',
								'caption'       => isset( $data_item['caption'] ) ? $data_item['caption'] : '',
								'id'            => $data_item_id,
								'link'          => isset( $data_item['permalink'] ) ? $data_item['permalink'] : '',
								'popularity'    => 0,
								'timestamp'     => isset( $data_item['timestamp'] ) ? $data_item['timestamp'] : '',
								'url'           => $data_item_url_thumbnail,
								'url_thumbnail' => $data_item_url_thumbnail,
							);
						}
					}
				}
			}


			return $data_images;
		}

		public static function get_images_data_for_hashtag( $hashtag, $blocked_users_array ) {
			$response = wp_remote_get( 'https://www.instagram.com/explore/tags/' . trim( $hashtag ), array(
				'sslverify' => false,
				'timeout'   => 60
			) );


			$data_images = array();

			if ( is_wp_error( $response ) ) {
				return $data_images;
			}

			if ( $response['response']['code'] == 200 ) {
				$json = self::parse_instagram_html( $response );

				$results = json_decode( $json, true );

				if ( $results && is_array( $results ) ) {
					$entry_data = isset( $results['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ? $results['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] : array();
					if ( $entry_data ) {

						$images_number = 0;
						foreach ( $entry_data as $current => $result ) {
							$owner_id = isset( $result['node']['owner']['id'] ) ? $result['node']['owner']['id'] : '';

							if ( in_array( $owner_id, $blocked_users_array ) ) {
								continue;
							}

							if ( $images_number > 12 ) {
								continue;
							}

							$comment_count = isset( $result['node']['edge_media_to_comment']['count'] ) ? (int) ( $result['node']['edge_media_to_comment']['count'] ) : 0;
							$liked_count   = isset( $result['node']['edge_liked_by']['count'] ) ? (int) ( $result['node']['edge_liked_by']['count'] ) : 0;

							$image_data['code']          = isset( $result['node']['shortcode'] ) ? $result['node']['shortcode'] : '';
							$image_data['username']      = '';
							$image_data['user_id']       = isset( $result['node']['owner']['id'] ) ? $result['node']['owner']['id'] : '';
							$image_data['caption']       = isset( $result['node']['edge_media_to_caption']['edges']['0']['node']['text'] ) ? self::sanitize( $result['node']['edge_media_to_caption']['edges']['0']['node']['text'] ) : '';
							$image_data['id']            = isset( $result['node']['id'] ) ? $result['node']['id'] : '';
							$image_data['link']          = isset( $result['node']['shortcode'] ) ? 'https://www.instagram.com/p/' . $result['node']['shortcode'] . '/' : '';
							$image_data['popularity']    = $comment_count + $liked_count;
							$image_data['timestamp']     = isset( $result['node']['taken_at_timestamp'] ) ? (float) $result['node']['taken_at_timestamp'] : '';
							$image_data['url']           = isset( $result['node']['display_url'] ) ? $result['node']['display_url'] : '';
							$image_data['url_thumbnail'] = isset( $result['node']['thumbnail_src'] ) ? $result['node']['thumbnail_src'] : '';

							$data_images[] = $image_data;

							$images_number ++;
						}
					}
				}
			}

			return $data_images;
		}

		private static function parse_instagram_html( $response ) {
			$json = str_replace( 'window._sharedData = ', '', strstr( $response['body'], 'window._sharedData = ' ) );

			// Compatibility for version of php where strstr() doesnt accept third parameter
			if ( version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
				$json = strstr( $json, '</script>', true );
			} else {
				$json = substr( $json, 0, strpos( $json, '</script>' ) );
			}

			$json = rtrim( $json, ';' );

			return $json;
		}

		private static function get_ids_from_usernames( $usernames ) {

			$users      = explode( ',', trim( $usernames ) );
			$user_ids   = (array) get_transient( 'penci_insta_user_ids' );
			$return_ids = array();

			if ( is_array( $users ) && ! empty( $users ) ) {

				foreach ( $users as $user ) {

					if ( isset( $user_ids[ $user ] ) ) {
						continue;
					}

					$response = wp_remote_get( 'https://www.instagram.com/' . trim( $user ), array(
						'sslverify' => false,
						'timeout'   => 60
					) );

					if ( is_wp_error( $response ) ) {

						return $response->get_error_message();
					}

					if ( $response['response']['code'] == 200 ) {

						$json = str_replace( 'window._sharedData = ', '', strstr( $response['body'], 'window._sharedData = ' ) );
						if ( version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
							$json = strstr( $json, '</script>', true );
						} else {
							$json = substr( $json, 0, strpos( $json, '</script>' ) );
						}

						$json    = rtrim( $json, ';' );
						$results = json_decode( $json, true );

						if ( $results && is_array( $results ) ) {

							$user_id = isset( $results['entry_data']['ProfilePage'][0]['graphql']['user']['id'] ) ? $results['entry_data']['ProfilePage'][0]['graphql']['user']['id'] : false;
							if ( $user_id ) {

								$user_ids[ $user ] = $user_id;

								set_transient( 'penci_insta_user_ids', $user_ids );
							}
						}
					}
				}
			}

			foreach ( $users as $user ) {
				if ( isset( $user_ids[ $user ] ) ) {
					$return_ids[] = $user_ids[ $user ];
				}
			}

			return $return_ids;
		}

		private static function sanitize( $input ) {

			if ( ! empty( $input ) ) {
				$utf8_2byte       = 0xC0 /*1100 0000*/
				;
				$utf8_2byte_bmask = 0xE0 /*1110 0000*/
				;
				$utf8_3byte       = 0xE0 /*1110 0000*/
				;
				$utf8_3byte_bmask = 0XF0 /*1111 0000*/
				;
				$utf8_4byte       = 0xF0 /*1111 0000*/
				;
				$utf8_4byte_bmask = 0xF8 /*1111 1000*/
				;

				$sanitized = "";
				$len       = strlen( $input );
				for ( $i = 0; $i < $len; ++ $i ) {

					$mb_char = $input[ $i ]; // Potentially a multibyte sequence
					$byte    = ord( $mb_char );

					if ( ( $byte & $utf8_2byte_bmask ) == $utf8_2byte ) {
						$mb_char .= $input[ ++ $i ];
					} else if ( ( $byte & $utf8_3byte_bmask ) == $utf8_3byte ) {
						$mb_char .= $input[ ++ $i ];
						$mb_char .= $input[ ++ $i ];
					} else if ( ( $byte & $utf8_4byte_bmask ) == $utf8_4byte ) {
						// Replace with ? to avoid MySQL exception
						$mb_char = '';
						$i       += 3;
					}

					$sanitized .= $mb_char;
				}

				$input = $sanitized;
			}

			return $input;
		}

		public static function clean_token( $maybe_dirty ) {
			if ( substr_count( $maybe_dirty, '.' ) < 3 ) {
				return str_replace( '634hgdf83hjdj2', '', $maybe_dirty );
			}

			$parts     = explode( '.', trim( $maybe_dirty ) );
			$last_part = $parts[2] . $parts[3];
			$cleaned   = $parts[0] . '.' . base64_decode( $parts[1] ) . '.' . base64_decode( $last_part );

			return $cleaned;
		}

		/**
		 * Sort Function for timestamp Ascending
		 */
		public static function sort_timestamp_ASC( $a, $b ) {
			return $a['timestamp'] <=> $b['timestamp'];
		}

		/**
		 * Sort Function for timestamp Descending
		 */
		public static function sort_timestamp_DESC( $a, $b ) {
			return $b['timestamp'] <=> $a['timestamp'];
		}

		/**
		 * Sort Function for popularity Ascending
		 */
		public static function sort_popularity_ASC( $a, $b ) {
			return $a['popularity'] <=> $b['popularity'];
		}

		/**
		 * Sort Function for popularity Descending
		 */
		public static function sort_popularity_DESC( $a, $b ) {
			return $b['popularity'] <=> $a['popularity'];
		}
	}
endif;
