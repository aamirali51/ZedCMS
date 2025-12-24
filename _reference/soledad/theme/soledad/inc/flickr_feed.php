<?php

class Penci_Flickr_Feed {
	public static function display_images( $args = null ) {
		$defaults = array(
			'username'         => '',
			'template'         => 'thumbs-no-border',
			'images_link'      => 'image_url',
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

		if ( '' == $args['username'] ) {
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p style="text-align: center;">This message appears for Admin Users only:<br>Please fill the Flickr Username.';
			}

			return;
		}

		$images_data = self::get_flickr_data( $args );

		if ( ! is_array( $images_data ) || ! $images_data ) {
			if ( current_user_can( 'manage_options' ) ) {
				echo '<p style="text-align: center;">This message appears for Admin Users only:<br>No any image found. Please check it again or try with another Flickr account.</p>';
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
				$link_to = 'https://www.flickr.com/photos/' . $args['username'] . '/';
			}

			if ( $args['image_size'] == 'jr_insta_square' ) {
				$image_url = $image_data['url_thumbnail'];
			} elseif ( $args['image_size'] == 'full' ) {
				$image_url = $image_data['url'];
			} else {
				$image_url = isset( $image_data['url_small'] ) ? $image_data['url_small'] : $image_data['url'];
			}

			$short_caption = wp_trim_words( $image_data['caption'], 10, '...' );
			$caption       = wp_trim_words( $image_data['caption'], intval( $args['caption_words'] ), $more = null );
			$nopin         = ( 1 == $args['no_pin'] ) ? 'nopin="nopin"' : '';

			$image_src = '<span '.penci_layout_bg( $image_url ).' class="penci-image-holder instagram-square-lazy ' . penci_layout_bg_class() . '">' . penci_layout_img( $image_url ) . '</span>';

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
						$output .= "<span class='penci-insta-username'>by <a rel='nofollow' href='https://www.flickr.com/photos/{$username}/' target='_blank'>{$username}</a></span>\n";
					}

					if ( $caption != '' && in_array( 'caption', $args['description'] ) ) {
						$caption_words = isset( $args['caption_words'] ) ? $args['caption_words'] : 20;
						$caption       = preg_replace( '/@([a-z0-9_]+)/i', '&nbsp;<a href="https://www.flickr.com/photos/$1/" rel="noopener" target="_blank">@$1</a>&nbsp;', $caption );
						$caption       = preg_replace( '/\#([a-zA-Z0-9_-]+)/i', '&nbsp;<a href="https://www.flickr.com/explore/tags/$1/" rel="noopener" target="_blank">$0</a>&nbsp;', $caption );
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
						$caption       = preg_replace( '/\#([a-zA-Z0-9_-]+)/i', '&nbsp;<a href="https://www.instagram.com/photos/tags/$1/" rel="noopener" target="_blank">$0</a>&nbsp;', $caption );
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

	public static function get_flickr_data( $args ) {

		$id    = $args['username'];
		$count = $args['images_number'];

		if ( empty( $id ) ) {
			return false;
		}

		$transient_key = md5( 'penci_flickr_cache_' . $id . $count );
		$cached        = get_transient( $transient_key );
		if ( ! empty( $cached ) ) {
			return $cached;
		}

		$protocol = is_ssl() ? 'https' : 'http';
		$output   = array();
		$rss      = $protocol . '://api.flickr.com/services/feeds/photos_public.gne?id=' . $id . '&lang=en-us&format=rss_200';
		$rss      = fetch_feed( $rss );

		if ( is_wp_error( $rss ) ) {
			//check for group feed
			$rss = $protocol . '://api.flickr.com/services/feeds/groups_pool.gne?id=' . $id . '&lang=en-us&format=rss_200';
			$rss = fetch_feed( $rss );
		}

		if ( ! is_wp_error( $rss ) ) {
			$maxitems  = $rss->get_item_quantity( $count );
			$rss_items = $rss->get_items( 0, $maxitems );
			$ncount    = 0;
			foreach ( $rss_items as $item ) {
				$temp               = array();
				$temp['link']       = esc_url( $item->get_permalink() );
				$temp['caption']    = esc_html( $item->get_title() );
				$temp['timestamp']  = esc_html( $item->get_date() );
				$temp['popularity'] = $ncount ++;
				$content            = $item->get_content();
				preg_match_all( "/<IMG.+?SRC=[\"']([^\"']+)/si", $content, $sub, PREG_SET_ORDER );
				$photo_url   = str_replace( "_m.jpg", "_b.jpg", $sub[0][1] );
				$photo_thumb   = str_replace( "_m.jpg", "_t.jpg", $sub[0][1] );
				$temp['url'] = esc_url( $photo_url );
				$temp['url_small'] = esc_url( $sub[0][1] );
				$temp['url_thumbnail'] = esc_url( $photo_thumb );
				$output[]    = $temp;
			}

			set_transient( $transient_key, $output, 60 * 60 * 24 );
		}

		return $output;
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