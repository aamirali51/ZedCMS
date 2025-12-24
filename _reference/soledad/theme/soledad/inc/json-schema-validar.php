<?php
if ( ! class_exists( 'Penci_JSON_Schema_Validator' ) ) {

	/**
	 * JSON Schema object
	 *
	 * Class Penci_JSON_Schema_Validator
	 */
	class Penci_JSON_Schema_Validator {

		function __construct() {
			add_action( 'wp_head', array( $this, 'output_schema' ) );
		}

		public static function pre_data_schema() {
			$data = array();
			if ( ! get_theme_mod( 'penci_schema_organization' ) ) {
				$data['organization'] = self::generate_data();
			}
			if ( ! get_theme_mod( 'penci_schema_website' ) ) {
				$data['website'] = self::website_data();
			}

			if ( is_singular() && ! is_front_page() && ! get_theme_mod( 'penci_schema_single' ) ) {

				if ( is_page() ) {
					$data['page'] = self::page_data();
				} elseif ( function_exists( 'is_product' ) && is_product() ) {
					$data['product'] = self::product_data();
				} else {
					$data['single'] = self::single_data();
				}
			}

			$yoast_bread_enabled = current_theme_supports( 'yoast-seo-breadcrumbs' );
			$breadcrumbs_enabled = false;
			if ( ! $yoast_bread_enabled && class_exists( 'WPSEO_Options' ) ) {
				if ( defined( 'WPSEO_PREMIUM_PLUGIN_FILE' ) ) {
					$options = get_option( 'wpseo_internallinks' );
					if ( isset( $options['breadcrumbs-enable'] ) && $options['breadcrumbs-enable'] == true ) {
						$breadcrumbs_enabled = true;
					}
				} elseif ( method_exists( 'WPSEO_Options', 'get' ) && is_callable( array( 'WPSEO_Options', 'get' ) ) ) {
					$breadcrumbs_enabled = WPSEO_Options::get( 'breadcrumbs-enable', false );
				}
			}

			if ( ! is_front_page() && ! $breadcrumbs_enabled && ! get_theme_mod( 'penci_schema_breadcrumbs' ) ) {
				$data['BreadcrumbList'] = self::BreadcrumbList_data();
			}

			if ( is_singular( 'post' ) && get_theme_mod( 'penci_post_use_newsarticle' ) ) {
				$data['NewsArticle'] = self::newsarticle_data();
			}

			return $data;
		}

		/**
		 * Outut schema with data each element
		 */
		public static function output_schema() {

			$pre_data_schema = self::pre_data_schema();

			if ( ! empty( $pre_data_schema ) ) {
				foreach ( $pre_data_schema as $json ) {
					echo '<script type="application/ld+json">' . wp_json_encode( $json, JSON_PRETTY_PRINT ) . '</script>';
				}
			}
		}

		/**
		 * Organization Schema
		 *
		 * @return array
		 */
		public static function generate_data() {

			$data_logo = '';
			$url_logo  = self::get_url_logo();
			if ( $url_logo ) {
				$data_logo = array(
					'@type' => 'ImageObject',
					'url'   => $url_logo,
				);
			}

			return array(
				'@context'    => 'https://schema.org/',
				'@type'       => 'organization',
				'@id'         => '#organization',
				'logo'        => $data_logo,
				'url'         => home_url( '/' ),
				'name'        => get_bloginfo( 'name' ),
				'description' => esc_attr( get_bloginfo( 'description' ) ),
			);
		}

		/**
		 * WebSite schema
		 *
		 * @return array
		 */
		public static function website_data() {
			$data = array(
				'@context'      => 'https://schema.org/',
				'@type'         => 'WebSite',
				'name'          => esc_attr( get_bloginfo( 'name' ) ),
				'alternateName' => esc_attr( get_bloginfo( 'description' ) ),
				'url'           => home_url( '/' ),
			);

			if ( is_home() || is_front_page() ) {
				$data['potentialAction'] = array(
					'@type'       => 'SearchAction',
					'target'      => get_search_link() . '{search_term}',
					'query-input' => 'required name=search_term',
				);
			}

			return $data;
		}

		public static function single_data() {
			return self::singular_data();
		}

		public static function page_data() {
			return self::singular_data( 'WebPage' );
		}

		public static function product_data() {
			$product       = wc_get_product();
			$schema_markup = self::singular_data( 'Product' );

			$schema_markup['name']           = isset( $schema_markup['headline'] ) ? $schema_markup['headline'] : '';
			$schema_markup['brand']          = isset( $schema_markup['publisher'] ) ? $schema_markup['publisher'] : '';
			$schema_markup['productionDate'] = isset( $schema_markup['datePublished'] ) ? $schema_markup['datePublished'] : '';

			$rating = $product->get_rating_count();

			if ( $rating ) {
				$schema_markup['aggregateRating'] = array(
					'@type'       => 'AggregateRating',
					'ratingValue' => wc_format_decimal( $product->get_average_rating(), 2 ),
					'reviewCount' => intval( $rating ),
				);
			}

			$schema_markup['offers'] = array(
				'@type'         => 'Offer',
				'url'           => get_the_permalink(),
				'priceCurrency' => get_woocommerce_currency(),
				'price'         => $product->get_price(),
				'availability'  => 'https://schema.org/' . ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ),
			);

			unset( $schema_markup['headline'] );
			unset( $schema_markup['datePublished'] );
			unset( $schema_markup['datemodified'] );
			unset( $schema_markup['publisher'] );
			unset( $schema_markup['author'] );

			return $schema_markup;
		}

		public static function newsarticle_data() {
			global $post;

			$post_id   = isset( $post->ID ) ? $post->ID : '';
			$permalink = get_permalink( $post_id );

			$post_title     = isset( $post->post_title ) ? $post->post_title : '';
			$excerpt_length = get_theme_mod( 'penci_post_excerpt_length', 30 );
			$post_excerpt   = ! get_theme_mod( 'penci_excerptcharac' ) ? get_the_excerpt() : penci_get_the_excerpt( null, $excerpt_length );

			$featured_image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			$featured_image_0 = isset( $featured_image[0] ) ? $featured_image[0] : penci_get_default_thumbnail_url();

			$schema_markup = array(
				'@context'         => 'https://schema.org',
				'@type'            => 'NewsArticle',
				'headline'         => $post_title,
				'image'            => $featured_image_0,
				'datePublished'    => get_the_date(DATE_W3C),
				'datemodified'     => get_the_modified_date(DATE_W3C),
				'description'      => $post_excerpt,
				'mainEntityOfPage' => $permalink,
			);

			// publisher
			$schema_markup['publisher'] = array(
				'@type' => 'Organization',
				'name'  => esc_attr( get_bloginfo( 'name' ) ),
			);

			$url_logo = self::get_url_logo();
			if ( $url_logo ) {
				$schema_markup['publisher']['logo'] = array(
					'@type' => 'ImageObject',
					'url'   => $url_logo,
				);
			}

			// Author
			$post_author = isset( $post->post_author ) ? $post->post_author : '';
			$author      = get_the_author_meta( 'display_name', $post_author );
			$author_url  = get_author_posts_url( get_the_author_meta( 'ID', $post_author ) );
			if ( $author ) {
				$schema_markup['author'] = array(
					'@type' => 'Person',
					'@id'   => '#person-' . sanitize_html_class( $author ),
					'name'  => $author,
					'url'   => $author_url,
				);
			}

			$schema_return = apply_filters( 'soledad_schema', $schema_markup );

			return $schema_return;
		}

		public static function singular_data( $type = '', $args = array() ) {
			global $post;

			if ( ! $type ) {
				$type = 'BlogPosting';
			}

			$post_id   = isset( $post->ID ) ? $post->ID : '';
			$permalink = get_permalink( $post_id );

			$post_title     = isset( $post->post_title ) ? $post->post_title : '';
			$excerpt_length = get_theme_mod( 'penci_post_excerpt_length', 30 );
			$post_excerpt   = ! get_theme_mod( 'penci_excerptcharac' ) ? get_the_excerpt() : penci_get_the_excerpt( null, $excerpt_length );
			$post_excerpt   = $post_excerpt ? $post_excerpt : $post_title;
			$post_type      = isset( $post->post_type ) ? $post->post_type : '';

			$schema_markup = array(
				'@context'         => 'https://schema.org/',
				'@type'            => $type,
				'headline'         => $post_title,
				'description'      => $post_excerpt,
				'datePublished'    => get_the_date(DATE_W3C),
				'datemodified'     => get_the_modified_date(DATE_W3C),
				'mainEntityOfPage' => $permalink,
			);

			// Featured img
			$featured_image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			$featured_image_0 = isset( $featured_image[0] ) ? $featured_image[0] : penci_get_default_thumbnail_url();
			if ( $featured_image_0 ) {
				$schema_markup['image'] = array(
					'@type' => 'ImageObject',
					'url'   => $featured_image_0,
				);

				$featured_image_width  = isset( $featured_image[1] ) ? $featured_image[1] : '';
				$featured_image_height = isset( $featured_image[2] ) ? $featured_image[2] : '';

				if ( $featured_image_width && $featured_image_height ) {
					$schema_markup['image']['width']  = $featured_image_width;
					$schema_markup['image']['height'] = $featured_image_height;
				}
			}

			// publisher
			$schema_markup['publisher'] = array(
				'@type' => 'Organization',
				'name'  => esc_attr( get_bloginfo( 'name' ) ),
			);

			$url_logo = self::get_url_logo();
			if ( $url_logo ) {
				$schema_markup['publisher']['logo'] = array(
					'@type' => 'ImageObject',
					'url'   => $url_logo,
				);
			}

			// Author
			$post_author = isset( $post->post_author ) ? $post->post_author : '';
			$author      = get_the_author_meta( 'display_name', $post_author );
			$author_url  = get_author_posts_url( get_the_author_meta( 'ID', $post_author ) );
			if ( $author ) {
				$schema_markup['author'] = array(
					'@type' => 'Person',
					'@id'   => '#person-' . sanitize_html_class( $author ),
					'name'  => $author,
					'url'   => $author_url,
				);
			}

			// Post format
			if ( 'post' == $post_type ) {
				$format = get_post_format();

				if ( 'video' == $format ) {
					$schema_markup['@type'] = 'VideoObject';

					$video = get_post_meta( $post_id, '_format_video_embed', true );

					if ( ! wp_oembed_get( $video ) ) {
						$schema_markup['contentUrl'] = $video;
					}

					$schema_markup['name']         = isset( $schema_markup['headline'] ) ? $schema_markup['headline'] : '';
					$schema_markup['thumbnailUrl'] = isset( $schema_markup['image']['url'] ) ? $schema_markup['image']['url'] : '';
					$schema_markup['uploadDate']   = isset( $schema_markup['datePublished'] ) ? $schema_markup['datePublished'] : '';

					unset( $schema_markup['headline'] );
					unset( $schema_markup['datePublished'] );
					unset( $schema_markup['dateModified'] );
					unset( $schema_markup['image'] );

				} elseif ( 'audio' == $format ) {
					$schema_markup['@type'] = 'AudioObject';
					$audio                  = get_post_meta( $post_id, '_format_audio_embed', true );
					$audio_str              = substr( $audio, - 4 );

					if ( wp_oembed_get( $audio ) ) {

					} elseif ( $audio_str == '.mp3' ) {
						$schema_markup['contentUrl'] = esc_url( $audio );
					} else {
						$schema_markup['contentUrl'] = sanitize_text_field( $audio );
					}
				} elseif ( 'image' == $format ) {
					$schema_markup['@type'] = 'ImageObject';
				} elseif ( 'gallery' == $format ) {
					$schema_markup['@type'] = 'ImageObject';
				}
			}

			return $schema_markup;
		}

		public static function get_url_logo() {
			$logo = PENCI_SOLEDAD_URL . '/images/logo.png';
			if ( get_theme_mod( 'penci_logo' ) ) {
				$logo = get_theme_mod( 'penci_logo' );
			}
			if ( get_theme_mod( 'penci_logo_schema' ) ) {
				$logo = get_theme_mod( 'penci_logo_schema' );
			}

			return $logo;
		}

		public static function BreadcrumbList_data() {
			global $wp;

			$itemListElement = array();
			$items           = array();

			$items[] = array(
				'id'   => home_url(),
				'name' => penci_get_setting( 'penci_trans_home' ),
			);

			if ( is_home() && ! is_front_page() ) {
				$page    = get_option( 'page_for_posts' );
				$items[] = array(
					'id'   => get_permalink( $page ),
					'name' => get_the_title( $page ),
				);
			} elseif ( is_post_type_archive( 'portfolio' ) || is_tax( 'portfolio-category' ) ) {

				$current_term     = get_queried_object();
				$current_term_tax = isset( $current_term->taxonomy ) ? $current_term->taxonomy : '';
				$terms            = penci_get_term_parents( get_queried_object_id(), $current_term_tax );

				foreach ( (array) $terms as $term_id ) {
					$term    = get_term( $term_id, $current_term->taxonomy );
					$items[] = array(
						'id'   => get_category_link( $term_id ),
						'name' => $term->name,
					);
				}

				$current_term_label = isset( $current_term->label ) ? $current_term->label : $current_term->name;
				$current_term_id    = isset( $current_term->term_id ) ? $current_term->term_id : '';

				$items[] = array(
					'id'   => get_category_link( $current_term_id ),
					'name' => $current_term_label,
				);
			} elseif ( is_single() ) {

				// Terms
				$terms = get_the_terms( get_the_ID(), 'category' );

				if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
					$term    = penci_bread_primary_term( current( $terms ), 'category' );
					$terms   = penci_get_term_parents( $term->term_id, 'category' );
					$terms[] = $term->term_id;
					foreach ( $terms as $term_id ) {
						$term = get_term( $term_id, 'category' );
						if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
							$items[] = array(
								'id'   => get_term_link( $term, 'category' ),
								'name' => $term->name,
							);
						}
					}
					$items[] = array(
						'id'   => get_permalink(),
						'name' => get_the_title(),
					);
				}
			} elseif ( is_page() ) {
				$pages = penci_get_post_parents( get_queried_object_id() );
				foreach ( $pages as $page ) {
					$items[] = array(
						'id'   => get_permalink( $page ),
						'name' => get_the_title( $page ),
					);
				}

				$items[] = array(
					'id'   => '',
					'name' => get_the_title(),
				);
			} elseif ( is_tax() || is_category() || is_tag() ) {
				$current_term = get_queried_object();
				$terms        = penci_get_term_parents( get_queried_object_id(), $current_term->taxonomy );

				foreach ( $terms as $term_id ) {
					$term = get_term( $term_id, $current_term->taxonomy );

					$items[] = array(
						'id'   => get_category_link( $term_id ),
						'name' => $term->name,
					);
				}

				$current_term_id = isset( $current_term->term_id ) ? $current_term->term_id : '';

				$items[] = array(
					'id'   => get_category_link( $current_term_id ),
					'name' => $current_term->name,
				);
			} elseif ( is_search() ) {
				$items[] = array(
					'id'   => get_search_link( get_search_query() ),
					'name' => sprintf( esc_html__( '%1$s &quot;%2$s&quot;', 'soledad' ), penci_get_setting( 'penci_trans_search' ), get_search_query() ),
				);
			} elseif ( is_404() ) {
				$items[] = array(
					'id'   => '',
					'name' => esc_html__( 'Not Found', 'soledad' ),
				);
			} elseif ( is_author() ) {
				$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );

				$items[] = array(
					'id'   => $author_url,
					'name' => sprintf(
						esc_html__( '%1$s %2$s', 'soledad' ),
						esc_html__( 'Author', 'soledad' ),
						'<span class="vcard"><a class="url fn n" href="' . $author_url . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>'
					),
				);
			} elseif ( is_day() ) {
				$items[] = array(
					'id'   => home_url( add_query_arg( array(), $wp->request ) ),
					'name' => sprintf( esc_html__( '%1$s %2$s', 'soledad' ), esc_html__( 'Day Archives:', 'soledad' ), get_the_date() ),
				);
			} elseif ( is_month() ) {
				$items[] = array(
					'id'   => home_url( add_query_arg( array(), $wp->request ) ),
					'name' => sprintf( esc_html__( '%1$s %2$s', 'soledad' ), esc_html__( 'Monthly Archives:', 'soledad' ), get_the_date( 'F Y' ) ),
				);
			} elseif ( is_year() ) {
				$items[] = array(
					'id'   => home_url( add_query_arg( array(), $wp->request ) ),
					'name' => sprintf( esc_html__( '%1$s %2$s', 'soledad' ), esc_html__( 'Yearly Archives:', 'soledad' ), get_the_date( 'Y' ) ),
				);
			} else {
				$items[] = array(
					'id'   => home_url( add_query_arg( array(), $wp->request ) ),
					'',
					'name' => penci_get_setting( 'penci_trans_archives' ),
				);
			}

			$pos_item = 1;
			foreach ( $items as $item ) {
				$itemListElement[] = array(
					'@type'    => 'ListItem',
					'position' => absint( $pos_item ),
					'item'     => array(
						'@id'  => $item['id'],
						'name' => $item['name'],
					),
				);

				// $itemListElement .= '{"@type": "ListItem","position": ' . absint( $pos_item ) . ',"item":{"@id": "' . $item['id'] . '","name": "' . $item['name'] . '"}}';
				++ $pos_item;
			}

			$output = array(
				'@context'        => 'https://schema.org/',
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $itemListElement,
			);

			return $output;
		}
	}
}

new Penci_JSON_Schema_Validator();

/**
 * Gets parent posts' IDs of any post type, include current post
 * Modified from Hybrid Framework
 *
 * @param int|string $post_id ID of the post whose parents we want.
 *
 * @return array Array of parent posts' IDs.
 */
if ( ! function_exists( 'penci_get_post_parents' ) ) {
	function penci_get_post_parents( $post_id = '' ) {
		// Set up some default array.
		$list = array();

		// If no post ID is given, return an empty array.
		if ( empty( $post_id ) ) {
			return $list;
		}

		do {
			$list[] = $post_id;

			// Get next parent post
			$post    = get_post( $post_id );
			$post_id = $post->post_parent;
		} while ( $post_id );

		// Reverse the array to put them in the proper order for the trail.
		$list = array_reverse( $list );
		array_pop( $list );

		return $list;
	}
}

if ( ! function_exists( 'penci_bread_primary_term' ) ) {

	function penci_bread_primary_term( $term, $taxonomy ) {
		if ( class_exists( 'WPSEO_Primary_Term' ) ) {
			$wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy, get_the_id() );
			$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
			$primary_term       = get_term( $wpseo_primary_term );

			if ( ! is_wp_error( $primary_term ) ) {
				$term = $primary_term;
			}
		}

		return $term;
	}
}

/**
 * Searches for term parents' IDs of hierarchical taxonomies, including current term.
 * This function is similar to the WordPress function get_category_parents() but handles any type of taxonomy.
 * Modified from Hybrid Framework
 *
 * @param int|string $term_id The term ID
 * @param object|string $taxonomy The taxonomy of the term whose parents we want.
 *
 * @return array Array of parent terms' IDs.
 */
if ( ! function_exists( 'penci_get_term_parents' ) ) {
	function penci_get_term_parents( $term_id = '', $taxonomy = 'category' ) {
		// Set up some default arrays.
		$list = array();

		// If no term ID or taxonomy is given, return an empty array.
		if ( empty( $term_id ) || empty( $taxonomy ) ) {
			return $list;
		}

		do {
			$list[] = $term_id;

			// Get next parent term
			$term    = get_term( $term_id, $taxonomy );
			$term_id = $term->parent;
		} while ( $term_id );

		// Reverse the array to put them in the proper order for the trail.
		$list = array_reverse( $list );
		array_pop( $list );

		return $list;
	}
}
