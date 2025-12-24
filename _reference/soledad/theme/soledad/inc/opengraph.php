<?php

class Penci_Soledad_OpenGraph {
	private $debug = false;
	private $locale = null;

	private $schema_org_mapping = array(
		'name'          => array( 'og', 'title' ),
		'headline'      => array( 'og', 'blogdescription' ),
		'description'   => array( 'og', 'description' ),
		'datePublished' => array( 'article', 'published_time' ),
		'dateModified'  => array( 'article', 'modified_time' ),
		'author'        => array( 'profile', 'username' ),
	);

	private $is_schema_org_enabled = true;
	private $image_size = 'full';
	private $og = array();


	public function __construct() {

		if ( get_theme_mod( 'penci_disable_og_tag' ) ) {
			return;
		}

		if ( function_exists( 'aioseo' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' ) || class_exists( 'app\SimpleSEO' ) || class_exists( 'RankMath' ) || defined( 'SLIM_SEO_DIR' ) || defined( 'SQ_VERSION' ) || defined( 'WPSEO_FILE' ) || defined( 'WPMETASEO_PLUGIN_URL' ) ) {
			return;
		}

		$this->debug      = apply_filters( 'pcogtg_debug', defined( 'WP_DEBUG' ) && WP_DEBUG );
		$this->image_size = apply_filters( 'pcogtg_image_size', get_theme_mod( 'penci_og_tag_image_size', 'full' ) );

		add_action( 'wp_head', array( $this, 'wp_head' ), 0 );
		add_filter( 'language_attributes', array( $this, 'filter_add_html_itemscope_itemtype' ), 10, 2 );
	}

	private function strip_white_chars( $content ) {
		if ( $content ) {
			$content = wp_strip_all_tags( $content );
			$content = preg_replace( '/\s+/', ' ', $content );
			$content = trim( $content );
		}

		return $content;
	}

	public function wp_head() {
		if ( is_404() ) {
			return;
		}

		echo PHP_EOL;
		printf( __( '<!-- Soledad OG: %s -->', 'og' ), PENCI_SOLEDAD_VERSION );
		if ( $this->debug ) {
			echo PHP_EOL;
		}
		do_action( 'iworks_pcogtg_before' );

		$og = $this->get_pcogtg_array();

		$this->echo_array( $og );
		do_action( 'iworks_pcogtg_after', $og );
		echo PHP_EOL;
		echo '<!-- /Soledad OG -->';
		echo PHP_EOL;
		echo PHP_EOL;

		remove_filter( 'orphan_skip_replacement', '__return_true' );
	}

	private function get_pcogtg_array() {
		if ( ! empty( $this->og ) ) {
			return $this->og;
		}
		$og = array(
			'og'      => array(
				'image'       => apply_filters( 'pcogtg_image_init', array() ),
				'video'       => apply_filters( 'pcogtg_video_init', array() ),
				'description' => '',
				'type'        => 'website',
				'locale'      => $this->get_locale(),
				'site_name'   => get_bloginfo( 'name' ),
				'logo'        => $this->get_site_logo(),
			),
			'article' => array(
				'tag' => array(),
			),
			'twitter' => array(
				'partner' => 'ogwp',
				'site'    => apply_filters( 'pcogtg_twitter_site', '' ),
				'creator' => apply_filters( 'pcogtg_twitter_creator', '' ),
				'widgets' => apply_filters( 'pcogtg_twitter_widgets', array() ),
				'player'  => apply_filters( 'pcogtg_video_init', array() ),
			),
			'schema'  => array(),
		);

		remove_action( 'wp_head', 'fpp_head_action' );

		add_filter( 'orphan_skip_replacement', '__return_true' );

		if ( is_singular() ) {
			global $post, $yarpp;

			$og['og']['type'] = 'article';

			$cache     = false;
			$cache_key = $this->get_transient_key( $post->ID );
			if ( ! $this->debug ) {
				$cache = get_transient( $cache_key );
			}
			if ( false === $cache ) {
				$src = false;

				if (

					apply_filters( 'pcogtg_allow_to_use_thumbnail', true )
					&& empty( $src )
					&& function_exists( 'has_post_thumbnail' )
				) {
					if ( has_post_thumbnail( $post->ID ) ) {
						$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
						$thumbnail_src     = wp_get_attachment_image_src( $post_thumbnail_id, $this->image_size );

						if ( false !== $thumbnail_src ) {
							$src = esc_url( $thumbnail_src[0] );

							array_unshift( $og['og']['image'], $this->get_image_dimensions( $thumbnail_src, $post_thumbnail_id ) );
						}
					}
				}
				/**
				 * attachment image page
				 */
				if ( is_attachment() ) {
					if ( wp_attachment_is_image( $post->ID ) ) {
						$post_thumbnail_id   = $post->ID;
						$thumbnail_src       = wp_get_attachment_image_src( $post_thumbnail_id, $this->image_size );
						$og['og']['image'][] = $this->get_image_dimensions( $thumbnail_src, $post_thumbnail_id );
						$src                 = esc_url( wp_get_attachment_url( $post->ID ) );
					} elseif ( wp_attachment_is( 'video', $post->ID ) ) {
						$og['og']['type']   = 'video';
						$src                = esc_url( wp_get_attachment_url( $post->ID ) );
						$og['video']['url'] = $src;
						if ( is_ssl() ) {
							$og['video']['secure_url'] = $src;
						}
						$meta = get_post_meta( $post->ID, '_wp_attachment_metadata', true );
						if ( isset( $meta['mime_type'] ) ) {
							$og['video']['type'] = $meta['mime_type'];
						}
						if ( isset( $meta['width'] ) ) {
							$og['video']['width'] = $meta['width'];
						}
						if ( isset( $meta['height'] ) ) {
							$og['video']['height'] = $meta['height'];
						}
					} elseif ( wp_attachment_is( 'audio', $post->ID ) ) {
						$og['og']['type']   = 'audio';
						$src                = esc_url( wp_get_attachment_url( $post->ID ) );
						$og['audio']['url'] = $src;
						if ( is_ssl() ) {
							$og['audio']['secure_url'] = $src;
						}
						$meta = get_post_meta( $post->ID, '_wp_attachment_metadata', true );
						if ( isset( $meta['mime_type'] ) ) {
							$og['audio']['type'] = $meta['mime_type'];
						}
					}
				}

				if (
					apply_filters( 'pcogtg_allow_to_use_content_image', true )
					&& empty( $src )
				) {
					$src      = array();
					$home_url = get_home_url();
					$content  = $post->post_content;
					if ( preg_match_all( '/<img[^>]+>/', $content, $matches ) ) {
						$matches = array_unique( $matches[0] );
						foreach ( $matches as $img ) {
							if ( preg_match( '/class="([^"]+)"/', $img, $matches_image_class ) ) {
								$classes = $matches_image_class[1];
								if ( preg_match( '/wp\-image\-(\d+)/', $classes, $matches_image_id ) ) {
									$attachment_id = $matches_image_id[1];
									$thumbnail_src = wp_get_attachment_image_src( $attachment_id, $this->image_size );
									if ( is_array( $thumbnail_src ) ) {
										$src[]               = esc_url( $thumbnail_src[0] );
										$og['og']['image'][] = $this->get_image_dimensions( $thumbnail_src, $attachment_id );
										continue;
									}
								}
							} elseif ( preg_match( '/src=([\'"])?([^"^\'^ ^>]+)([\'" >])?/', $img, $matches_image_src ) ) {
								$temp_src = $matches_image_src[2];
								$pos      = strpos( $temp_src, $home_url );
								if ( false === $pos ) {
									continue;
								}
								if ( 0 !== $pos ) {
									continue;
								}
								$attachment_id = $this->get_attachment_id( $temp_src );
								if ( 0 < $attachment_id ) {
									$thumbnail_src = wp_get_attachment_image_src( $attachment_id, $this->image_size );
									if ( is_array( $thumbnail_src ) ) {
										$src[]               = esc_url( $thumbnail_src[0] );
										$og['og']['image'][] = $this->get_image_dimensions( $thumbnail_src, $attachment_id );
									}
								} else {
									$og['og']['image'][] = $this->get_image_dimensions( array( $temp_src ) );
								}
							}
						}
					}
				}

				$og['og']['title'] = $this->strip_white_chars( get_the_title() );

				$og['og']['url'] = get_permalink();

				if ( ! post_password_required( $post->ID ) ) {
					if ( has_excerpt( $post->ID ) ) {
						$og['og']['description'] = get_the_excerpt();
					} else {

						$number_of_words         = apply_filters( 'pcogtg_description_words', 55 );
						$og['og']['description'] = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), $number_of_words, '...' );
					}
					$og['og']['description'] = $this->strip_white_chars( $og['og']['description'] );
					if ( empty( $og['og']['description'] ) ) {
						$og['og']['description'] = $og['og']['title'];
					}

					$tags = get_the_tags();
					if ( is_array( $tags ) && count( $tags ) > 0 ) {
						foreach ( $tags as $tag ) {
							$og['article']['tag'][] = esc_attr( $tag->name );
						}
					}
					$og['article']['published_time'] = gmdate( 'c', strtotime( $post->post_date_gmt ) );
					$og['article']['modified_time']  = gmdate( 'c', strtotime( $post->post_modified_gmt ) );

					$og['og']['updated_time'] = get_the_modified_date( 'c' );

					$og['article']['section'] = array();
					$post_categories          = wp_get_post_categories( $post->ID );
					if ( ! empty( $post_categories ) ) {
						foreach ( $post_categories as $category_id ) {
							$category                   = get_category( $category_id );
							$og['article']['section'][] = $category->name;
						}
					}

					$og['article']['tag'] = array();
					$post_tags            = wp_get_post_tags( $post->ID );
					if ( ! empty( $post_tags ) ) {
						foreach ( $post_tags as $tag ) {
							$og['article']['tag'][] = $tag->name;
						}
					}

					$og['article']['author'] = $this->get_the_author_meta_array( $post->post_author );
					$og['profile']           = $this->get_the_author_meta_array( $post->post_author );
				}

				$post_format = get_post_format( $post->ID );
				switch ( $post_format ) {
					case 'audio':
						$og['og']['type'] = 'music';
						break;
					case 'video':
						$og['og']['type'] = 'video';
						break;
				}

				$media = get_attached_media( 'video' );
				foreach ( $media as $one ) {

					if ( preg_match( '/^video/', $one->post_mime_type ) ) {
						$og['og']['rich_attachment'] = true;
						$video                       = array(
							'url'  => wp_get_attachment_url( $one->ID ),
							'type' => $one->post_mime_type,
						);
						if ( ! isset( $og['og']['video'] ) ) {
							$og['og']['video'] = array();
						}
						$og['og']['video'][] = $video;
					}
				}

				$media = get_attached_media( 'audio' );
				foreach ( $media as $one ) {
					if ( preg_match( '/^audio/', $one->post_mime_type ) ) {
						$og['og']['rich_attachment'] = true;
						$audio                       = array(
							'url'  => wp_get_attachment_url( $one->ID ),
							'type' => $one->post_mime_type,
						);
						if ( ! isset( $og['og']['audio'] ) ) {
							$og['og']['audio'] = array();
						}
						$og['og']['audio'][] = $audio;
					}
				}

				$og['twitter']['card'] = 'summary';
				if (
					isset( $og['og']['image'] )
					&& is_array( $og['og']['image'] )
					&& ! empty( $og['og']['image'] )
				) {
					$og = $this->set_twitter_image( $og );
				}

				if (
					! empty( $og )
					&& ! $this->debug
				) {
					set_transient( $cache_key, $og, apply_filters( 'pcogtg_set_transient_expiration', DAY_IN_SECONDS ) );
				}
			} else {
				$og = $cache;
			}
		} elseif ( is_author() ) {
			$author_id         = get_the_author_meta( 'ID' );
			$og['og']['url']   = get_author_posts_url( $author_id );
			$og['og']['type']  = 'profile';
			$og['profile']     = $this->get_the_author_meta_array( $author_id );
			$og['og']['image'] = get_avatar_url(
				$author_id,
				array(
					'size'    => 512,
					'default' => 404,
				)
			);

			$og['og']['description'] = $this->strip_white_chars( wp_strip_all_tags( get_the_author_meta( 'description' ) ) );
		} elseif ( is_search() ) {
			$og['og']['url'] = get_search_link();
		} elseif ( is_archive() ) {
			$obj = get_queried_object();
			if ( is_a( $obj, 'WP_Term' ) ) {
				$og['og']['url']         = get_term_link( $obj->term_id );
				$og['og']['description'] = $this->strip_white_chars( term_description( $obj->term_id, $obj->taxonomy ) );

				$term_meta_name = apply_filters(
					'og/term/meta/thumbnail_id_name',
					'image'
				);
				$image_id       = intval( get_term_meta( $obj->term_id, $term_meta_name, true ) );
				if ( 0 < $image_id ) {
					$thumbnail_src     = wp_get_attachment_image_src( $image_id, $this->image_size );
					$src               = $thumbnail_src[0];
					$og['og']['image'] = $this->get_image_dimensions( $thumbnail_src, $image_id );
				} else {

					$term_meta_name = apply_filters(
						'og/term/meta/thumbnail_url',
						'image_url'
					);
					$image_url      = get_term_meta( $obj->term_id, $term_meta_name, true );
					if ( wp_http_validate_url( $image_url ) ) {
						$og['og']['image'] = $image_url;
					}
				}
			} elseif ( is_a( $obj, 'WP_Post_Type' ) ) {
				$og['og']['url'] = get_post_type_archive_link( $obj->name );
			} elseif ( is_date() ) {
				$year  = get_query_var( 'year' );
				$month = get_query_var( 'monthnum' );
				$day   = get_query_var( 'day' );
				if ( is_day() ) {
					$og['og']['url'] = get_day_link( $year, $month, $day );
				} elseif ( is_month() ) {
					$og['og']['url'] = get_month_link( $year, $month );
				} else {
					$og['og']['url'] = get_year_link( $year );
				}
			}
		} else {
			if ( is_home() || is_front_page() ) {
				$og['og']['type'] = 'website';
			}
			$og['og']['description'] = $this->strip_white_chars( get_bloginfo( 'description' ) );
			$og['og']['title']       = get_bloginfo( 'title' );
			$og['og']['url']         = home_url();
			if ( ! is_front_page() && is_home() ) {
				$og['og']['url'] = get_permalink( get_option( 'page_for_posts' ) );
			}
		}
		if ( ! isset( $og['og']['title'] ) || empty( $og['og']['title'] ) ) {
			$og['og']['title'] = wp_get_document_title();
		}

		if (
			(
				! isset( $og['og']['image'] )
				|| empty( $og['og']['image'] )
			)
			&& function_exists( 'get_site_icon_url' )
		) {
			$og['og']['image'] = get_site_icon_url();
		}

		if ( isset( $og['og']['image'] ) ) {
			$tmp_src = null;
			if ( is_string( $og['og']['image'] ) ) {
				$tmp_src = $og['og']['image'];
			} elseif (
				is_array( $og['og']['image'] )
				&& ! empty( $og['og']['image'] )
			) {
				$img = reset( $og['og']['image'] );
				if ( isset( $img['url'] ) ) {
					$tmp_src = $img['url'];
				}
			}

			if (
				! isset( $og['twitter']['image'] )
			) {
				$og = $this->set_twitter_image( $og );
			}

			if ( apply_filters( 'pcogtg_is_schema_org_enabled', $this->is_schema_org_enabled ) ) {
				if ( ! isset( $og['schema']['image'] ) ) {
					$og['schema']['image'] = $tmp_src;
				}
			}
		}

		foreach ( array( 'title', 'description', 'url' ) as $key ) {
			if ( isset( $og['og'][ $key ] ) ) {
				$og['twitter'][ $key ] = $og['og'][ $key ];
			}
		}

		if ( apply_filters( 'pcogtg_is_schema_org_enabled', $this->is_schema_org_enabled ) ) {
			foreach ( $this->schema_org_mapping as $itemprop => $pcogtg_keys ) {
				if ( isset( $og[ $pcogtg_keys[0] ] ) ) {
					if ( isset( $og[ $pcogtg_keys[0] ][ $pcogtg_keys[1] ] ) ) {
						$og['schema'][ $itemprop ] = apply_filters(
							'pcogtg_schema_' . $itemprop,
							$og[ $pcogtg_keys[0] ][ $pcogtg_keys[1] ]
						);
					}
				}
			}

			if ( apply_filters( 'pcogtg_allow_to_use_schema_tagline', false ) ) {
				$og['schema']['tagline'] = apply_filters(
					'pcogtg_schema_tagline',
					get_option( 'blogdescription' )
				);
			}
		}

		if ( ! empty( $src ) ) {
			$tmp_src = $src;
			if ( is_array( $tmp_src ) ) {
				$tmp_src = array_shift( $tmp_src );
			}
			if ( ! empty( $tmp_src ) ) {

				if ( apply_filters( 'pcogtg_head_link_rel_image_src_enabled', true ) ) {
					printf(
						'<link rel="image_src" href="%s">%s',
						esc_url( $tmp_src ),
						$this->debug ? PHP_EOL : ''
					);
				}

				if ( apply_filters( 'pcogtg_head_meta_title_image_enabled', true ) ) {
					printf(
						'<meta name="msapplication-TileImage" content="%s">%s',
						esc_url( $tmp_src ),
						$this->debug ? PHP_EOL : ''
					);
				}

				if ( apply_filters( 'pcogtg_is_schema_org_enabled', $this->is_schema_org_enabled ) ) {
					$og['schema']['image'] = $tmp_src;
				}
			}
		}

		if ( isset( $og['twitter'] ) && isset( $og['twitter']['description'] ) ) {
			$number_of_words = apply_filters( 'pcogtg_description_words', 55 );
			do {
				$og['twitter']['description'] = wp_trim_words( $og['twitter']['description'], $number_of_words, '...' );
				$number_of_words --;
			} while ( 200 < mb_strlen( $og['twitter']['description'] ) );
		}

		foreach ( $og as $key => $data ) {
			$og[ $key ] = apply_filters( 'pcogtg_' . $key . '_array', $data );
		}

		$this->og = apply_filters( 'pcogtg_array', $og );

		return $this->og;
	}


	private function echo_array( $og, $parent = array() ) {
		foreach ( $og as $tag => $data ) {
			if ( empty( $parent ) ) {
				echo PHP_EOL;
				if ( $this->debug ) {
					printf( '<!-- %s -->%s', $tag, PHP_EOL );
				}
			}
			$tags = $parent;
			if ( ! is_integer( $tag ) ) {
				$tags[] = $tag;
			}

			if ( 'labels' === $tag && count( $parent ) && 'twitter' === $parent[0] ) {
				$this->echo_twiter_labels( $data );
			} elseif ( is_array( $data ) ) {

				if ( 'logo' === $tag ) {
					if ( ! empty( $data['content'] ) ) {
						$this->echo_one_with_array_of_params( array( 'og', $tag ), $data );
					}
				} else {
					$this->echo_array( $data, $tags );
				}
			} else {
				if ( 'schema' === $tags[0] ) {
					if ( apply_filters( 'pcogtg_is_schema_org_enabled', $this->is_schema_org_enabled ) ) {
						$this->echo_one( $tags[1], $data, 'itemprop' );
					}
				} elseif ( 'offers' === $tags[0] ) {
					$this->echo_one( $tags, $data, 'itemprop' );
				} elseif ( 2 < sizeof( $tags ) && $tags[1] === $tags[2] ) {
					$this->echo_one( array( $tags[0], $tags[1] ), $data );
				} else {
					$this->echo_one( $tags, $data );
				}
			}
		}
	}


	private function echo_one_with_array_of_params( $property, $params ) {
		$meta_property = $property;
		if ( is_array( $property ) ) {
			$meta_property = implode( ':', $property );
		}
		if ( ! is_array( $params ) ) {
			$this->echo_one( $property, $params );

			return;
		}
		$attrs = array();
		foreach ( $params as $key => $value ) {
			$attrs[] = sprintf(
				'%s="%s"',
				esc_attr( $key ),
				esc_attr( $value )
			);
		}
		if ( empty( $attrs ) ) {
			return;
		}

		$property_filter_string = preg_replace( '/:/', '_', $meta_property );

		$filter_name = sprintf( 'pcogtg_%s_meta', $property_filter_string );
		echo apply_filters(
			$filter_name,
			sprintf(
				'<meta property="%s" %s>%s',
				esc_attr( $meta_property ),
				implode( ' ', $attrs ),
				$this->debug ? PHP_EOL : ''
			)
		);
	}


	private function echo_one( $property, $value, $name = 'property' ) {
		$meta_property = $property;
		if ( is_array( $property ) ) {
			$meta_property = implode( ':', $property );
		}

		$meta_property = preg_replace( '/^og:(image|video):url$/', 'og:$1', $meta_property );

		$property_filter_string = preg_replace( '/:/', '_', $meta_property );

		$filter_name = sprintf( 'pcogtg_%s_value', $property_filter_string );

		$value = apply_filters( $filter_name, $value );
		if ( empty( $value ) ) {
			return;
		}

		$filter_name = sprintf( 'pcogtg_%s_meta', $property_filter_string );
		echo apply_filters(
			$filter_name,
			sprintf(
				'<meta %s="%s" content="%s">%s',
				esc_attr( $name ),
				esc_attr( $meta_property ),
				esc_attr( wp_strip_all_tags( $value ) ),
				$this->debug ? PHP_EOL : ''
			)
		);
	}


	private function get_locale() {
		if ( null !== $this->locale ) {
			return apply_filters( 'pcogtg_get_locale', $this->locale );
		}
		$this->locale = preg_replace( '/-/', '_', get_bloginfo( 'language' ) );

		return apply_filters( 'pcogtg_get_locale', $this->locale );
	}


	private function get_image_dimensions( $image, $image_id = 0 ) {
		if ( empty( $image ) || ! is_array( $image ) ) {
			return null;
		}
		$data = array(
			'url' => $image[0],
		);
		if ( preg_match( '/^https/', $image[0] ) ) {
			$data['secure_url'] = $image[0];
		}
		if ( 2 < count( $image ) ) {
			$data['width']  = intval( $image[1] );
			$data['height'] = intval( $image[2] );
		}
		if ( 0 === $image_id ) {
			$size = @getimagesize( $image[0] );
			if ( ! empty( $size ) ) {
				$data['width']  = $size[0];
				$data['height'] = $size[1];
				$data['type']   = $size['mime'];
			}
		} else {
			$data['alt'] = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			if ( empty( $data['alt'] ) ) {
				$data['alt'] = wp_get_attachment_caption( $image_id );
				if ( empty( $data['alt'] ) ) {
					$data['alt'] = get_the_title( $image_id );
				}
			}
			/**
			 * Set mime type
			 *
			 * @since 2.7.7
			 */
			$data['type'] = get_post_mime_type( $image_id );
		}

		return $data;
	}


	private function get_attachment_id( $url ) {
		if ( ! is_string( $url ) ) {
			return 0;
		}
		global $wpdb;
		$attachment = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT ID FROM $wpdb->posts WHERE guid=%s",
				$url
			)
		);
		if ( empty( $attachment ) ) {
			$url2 = preg_replace( '/\-\d+x\d+(.[egjnp]+)$/', '$1', $url );
			if ( $url != $url2 ) {
				return $this->get_attachment_id( $url2 );
			}
		}
		if ( is_array( $attachment ) && ! empty( $attachment ) ) {
			return $attachment[0];
		}

		return 0;
	}


	private function get_transient_key( $post_id ) {
		$key    = sprintf( 'pcogtg_%d_%s', $post_id, PENCI_SOLEDAD_VERSION );
		$locale = $this->get_locale();
		if ( ! empty( $locale ) ) {
			$key .= '_' . $locale;
		}

		return $key;
	}


	public function echo_twiter_labels( $data ) {
		$counter = 1;
		foreach ( $data as $one ) {
			if ( ! isset( $one['label'] ) ) {
				continue;
			}
			if ( ! isset( $one['data'] ) ) {
				continue;
			}
			$this->echo_one( 'twitter:label' . $counter, $one['label'] );
			$this->echo_one( 'twitter:data' . $counter, $one['data'] );
			$counter ++;
		}
	}


	private function map_itemscope_itemtype( $type ) {
		$map = array(
			'article' => 'https://schema.org/Article',
			'audio'   => 'https://schema.org/AudioObject',
			'blog'    => 'https://schema.org/Blog',
			'contact' => 'https://schema.org/ContactPage',
			'course'  => 'https://schema.org/Course',
			'page'    => 'https://schema.org/WebPage',
			'person'  => 'https://schema.org/Person',
			'place'   => 'https://schema.org/Place',
			'post'    => 'https://schema.org/BlogPosting',
			'product' => 'https://schema.org/Product',
			'search'  => 'https://schema.org/SearchAction',
			'video'   => 'https://schema.org/VideoObject',
		);
		if ( isset( $map[ $type ] ) ) {
			return $map[ $type ];
		}

		return 'https://schema.org/WebSite';
	}


	public function filter_add_html_itemscope_itemtype( $output, $doctype ) {

		if ( is_admin() ) {
			return $output;
		}

		if ( 'html' !== $doctype ) {
			return $output;
		}
		if ( ! apply_filters( 'pcogtg_is_schema_org_enabled', $this->is_schema_org_enabled ) ) {
			return $output;
		}

		global $wp_query;
		if ( isset( $wp_query->query['sitemap-stylesheet'] ) ) {
			return $output;
		}
		$type   = $this->get_type();
		$output .= sprintf(
			' itemscope itemtype="%s"',
			esc_attr( $this->map_itemscope_itemtype( $type ) )
		);

		return $output;
	}


	private function get_type() {

		if ( is_home() ) {
			return 'blog';
		}

		if ( is_singular() ) {
			$post_type = get_post_type();
			switch ( $post_type ) {
				case 'page':
				case 'post':
					global $post;
					if ( preg_match( '/contact-form-7/', $post->post_content ) ) {
						return 'contact';
					}

					return $post_type;
				case 'course':
				case 'event':
				case 'place':
				case 'product':
					return $post_type;
				case 'attachment':
					if ( wp_attachment_is_image() ) {
						return 'image';
					}
					if ( wp_attachment_is( 'video' ) ) {
						return 'video';
					}
					if ( wp_attachment_is( 'video' ) ) {
						return 'video';
					}
			}
		}

		if ( is_author() ) {
			return 'person';
		}

		if ( is_search() ) {
			return 'search';
		}

		return 'website';
	}

	private function get_the_author_meta_array( $author_id ) {
		return apply_filters(
			'pcogtg_profile',
			array(
				'first_name' => get_the_author_meta( 'first_name', $author_id ),
				'last_name'  => get_the_author_meta( 'last_name', $author_id ),
				'username'   => get_the_author_meta( 'display_name', $author_id ),
			),
			$author_id
		);
	}

	private function set_twitter_image( $og ) {
		$img = array();
		if (
			isset( $og['og']['image'] )
			&& is_array( $og['og']['image'] )
			&& ! empty( $og['og']['image'] )
		) {
			$img = reset( $og['og']['image'] );
		} else {
			return $og;
		}
		if ( isset( $img['url'] ) ) {

			if ( isset( $img['width'] ) && 519 < $img['width'] ) {
				$og['twitter']['card'] = 'summary_large_image';
			}
			$og['twitter']['image']['image'] = $img['url'];

			if ( isset( $img['alt'] ) ) {
				$og['twitter']['image']['alt'] = $img['alt'];
			}
		}

		return $og;
	}

	public function get_site_logo() {
		if ( ! apply_filters( 'allow_pcogtg_logo', false ) ) {
			return;
		}
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( empty( $logo_id ) ) {
			return;
		}
		$logo     = wp_get_attachment_metadata( $logo_id );
		$logo_src = wp_get_attachment_image_src( $logo_id, apply_filters( 'pcogtg_logo_size', 'full' ) );
		if ( empty( $logo_src ) ) {
			return;
		}

		return array(
			'content' => $logo_src[0],
			'size'    => sprintf( '%dx%d', $logo['width'], $logo['height'] ),
		);
	}
}

new Penci_Soledad_OpenGraph();