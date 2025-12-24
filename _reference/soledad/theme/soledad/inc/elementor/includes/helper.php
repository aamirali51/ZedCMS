<?php

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Utils;

require_once PENCI_SOLEDAD_DIR . '/inc/elementor/includes/penci_custom_walker_category.php';

if ( ! function_exists( 'penci_get_posts_by_query' ) ) {
	/**
	 * Get post by search
	 *
	 * @since 1.0.0
	 */
	function penci_get_posts_by_query() {
		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : ''; // phpcs:ignore
		$post_type     = isset( $_POST['post_type'] ) ? $_POST['post_type'] : 'all'; // phpcs:ignore
		$results       = array();

		$default_query = array(
			's'              => $search_string,
			'posts_per_page' => - 1,
		);


		if ( $post_type != 'all' ) {
			$default_query['post_type'] = $post_type;
		}

		$query = new WP_Query( $default_query );

		if ( ! isset( $query->posts ) ) {
			return;
		}

		foreach ( $query->posts as $post ) {
			$results[] = array(
				'id'   => $post->ID,
				'text' => $post->post_title,
			);
		}

		wp_send_json( $results );
	}

	add_action( 'wp_ajax_penci_get_posts_by_query', 'penci_get_posts_by_query' );
	add_action( 'wp_ajax_nopriv_penci_get_posts_by_query', 'penci_get_posts_by_query' );
}

if ( ! function_exists( 'penci_get_user_by_query' ) ) {
	/**
	 * Get post by search
	 *
	 * @since 1.0.0
	 */
	function penci_get_user_by_query() {
		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : ''; // phpcs:ignore
		$results       = array();

		$user_query = new WP_User_Query(
			array(
				'search'         => '*' . $search_string . '*',
				'search_columns' => array( 'user_login', 'user_email', 'user_nicename' ),
				'fields'         => array( 'ID', 'display_name' ),
			)
		);

		if ( empty( $user_query->get_results() ) ) {
			return;
		}

		foreach ( $user_query->get_results() as $user ) {
			$results[] = array(
				'id'   => $user->ID,
				'text' => $user->display_name,
			);
		}

		wp_send_json( $results );
	}

	add_action( 'wp_ajax_penci_get_user_by_query', 'penci_get_user_by_query' );
	add_action( 'wp_ajax_nopriv_penci_get_user_by_query', 'penci_get_user_by_query' );
}

if ( ! function_exists( 'penci_get_user_title_by_id' ) ) {
	/**
	 * Get post title by ID
	 *
	 * @since 1.0.0
	 */
	function penci_get_user_title_by_id() {
		$ids     = isset( $_POST['id'] ) ? $_POST['id'] : array(); // phpcs:ignore
		$results = array();

		$user_query = new WP_User_Query(
			array(
				'include'        => $ids,
				'search_columns' => array( 'user_login', 'user_email', 'user_nicename' ),
				'fields'         => array( 'ID', 'display_name' ),
			)
		);

		if ( empty( $user_query->get_results() ) ) {
			return;
		}

		foreach ( $user_query->get_results() as $user ) {
			$results[ $user->ID ] = $user->display_name;
		}

		wp_send_json( $results );
	}

	add_action( 'wp_ajax_penci_get_user_title_by_id', 'penci_get_user_title_by_id' );
	add_action( 'wp_ajax_nopriv_penci_get_user_title_by_id', 'penci_get_user_title_by_id' );
}

if ( ! function_exists( 'penci_get_posts_title_by_id' ) ) {
	/**
	 * Get post title by ID
	 *
	 * @since 1.0.0
	 */
	function penci_get_posts_title_by_id() {
		$ids     = isset( $_POST['id'] ) ? $_POST['id'] : array(); // phpcs:ignore
		$results = array();

		$args = array(
			'post__in'       => $ids,
			'posts_per_page' => - 1,
			'post_type'      => 'any',
		);

		$query = get_posts( $args );

		if ( empty( $query ) ) {
			return;
		}

		foreach ( $query as $post ) {
			$results[ $post->ID ] = $post->post_title;
		}

		wp_send_json( $results );
	}

	add_action( 'wp_ajax_penci_get_posts_title_by_id', 'penci_get_posts_title_by_id' );
	add_action( 'wp_ajax_nopriv_penci_get_posts_title_by_id', 'penci_get_posts_title_by_id' );
}
if ( ! function_exists( 'penci_get_taxonomies_title_by_id' ) ) {
	/**
	 * Get taxonomies title by id
	 *
	 * @since 1.0.0
	 */
	function penci_get_taxonomies_title_by_id() {
		$ids     = isset( $_POST['id'] ) ? $_POST['id'] : array(); // phpcs:ignore
		$results = array();

		if ( is_array( $ids ) && $ids ) {
			foreach ( $ids as $id ) {
				$term = get_term( $id );
				if ( is_object( $term ) ) {
					$results[ $term->term_id ] = $term->name . ' (' . $term->taxonomy . ')';
				}
			}
		}

		wp_send_json( $results );
	}

	add_action( 'wp_ajax_penci_get_taxonomies_title_by_id', 'penci_get_taxonomies_title_by_id' );
	add_action( 'wp_ajax_nopriv_penci_get_taxonomies_title_by_id', 'penci_get_taxonomies_title_by_id' );
}

if ( ! function_exists( 'penci_get_taxonomies_by_query' ) ) {
	/**
	 * Get taxonomies by search
	 *
	 * @since 1.0.0
	 */
	function penci_get_taxonomies_by_query() {
		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : ''; // phpcs:ignore
		$taxonomy      = isset( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : ''; // phpcs:ignore
		$results       = array();

		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'search'     => $search_string,
		);

		$terms = get_terms( $args );

		if ( is_array( $terms ) && $terms ) {
			foreach ( $terms as $term ) {
				if ( is_object( $term ) ) {
					$results[] = array(
						'id'   => $term->term_id,
						'text' => $term->name . ' (' . $term->taxonomy . ')',
					);
				}
			}
		}

		wp_send_json( $results );
	}

	add_action( 'wp_ajax_penci_get_taxonomies_by_query', 'penci_get_taxonomies_by_query' );
	add_action( 'wp_ajax_nopriv_penci_get_taxonomies_by_query', 'penci_get_taxonomies_by_query' );
}

if ( ! function_exists( 'penci_get_image_html' ) ) {
	/**
	 * Get image url
	 *
	 * @param array $settings Control settings.
	 * @param string $image_size_key Settings key for image size.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function penci_get_image_html( $settings, $image_size_key = '' ) {

		return Group_Control_Image_Size::get_attachment_image_html( $settings, $image_size_key );
	}
}

if ( ! function_exists( 'penci_get_image_url' ) ) {
	/**
	 * Get image url
	 *
	 * @param integer $id Image id.
	 * @param string $image_size_key Settings key for image size.
	 * @param array $settings Control settings.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function penci_get_image_url( $id, $image_size_key, $settings ) {

		return Group_Control_Image_Size::get_attachment_image_src( $id, $image_size_key, $settings );
	}
}

if ( ! function_exists( 'penci_el_get_link_attrs' ) ) {
	/**
	 * Get image url
	 *
	 * @param array $link Link data array.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function penci_el_get_link_attrs( $link ) {
		$link_attrs = '';

		if ( isset( $link['url'] ) && $link['url'] ) {
			$link_attrs = ' href="' . esc_url( $link['url'] ) . '"';

			if ( isset( $link['is_external'] ) && 'on' === $link['is_external'] ) {
				$link_attrs .= ' target="_blank"';
			}

			if ( isset( $link['nofollow'] ) && 'on' === $link['nofollow'] ) {
				$link_attrs .= ' rel="nofollow noopener"';
			}
		}

		if ( isset( $link['class'] ) ) {
			$link_attrs .= ' class="' . esc_attr( $link['class'] ) . '"';
		}

		if ( isset( $link['data'] ) ) {
			$link_attrs .= $link['data'];
		}

		if ( isset( $link['custom_attributes'] ) ) {
			$custom_attributes = Utils::parse_custom_attributes( $link['custom_attributes'] );
			foreach ( $custom_attributes as $key => $value ) {
				$link_attrs .= ' ' . $key . '="' . $value . '"';
			}
		}

		return $link_attrs;
	}
}

if ( ! function_exists( 'penci_elementor_get_render_icon' ) ) {
	/**
	 * Render Icon
	 *
	 * @param array $icon Icon Type, Icon value.
	 * @param array $attributes Icon HTML Attributes.
	 * @param string $tag Icon HTML tag, defaults to <i>.
	 *
	 * @return mixed|string
	 * @since 1.0.0
	 */
	function penci_elementor_get_render_icon( $icon, $attributes = array(), $tag = 'i' ) {
		ob_start();
		Icons_Manager::render_icon( $icon, $attributes, $tag );

		return ob_get_clean();
	}
}


if ( ! function_exists( 'penci_get_all_taxonomies' ) ) {
	function penci_get_all_taxonomies() {

		$out = array(
			'category' => 'Post Categories',
			'post_tag' => 'Post Tags',
		);

		if ( class_exists( 'woocommerce' ) ) {
			$out['product_cat'] = 'Product Categories';
			$out['product_tag'] = 'Product Tags';
		}

		if ( class_exists( 'Penci_Portfolio' ) ) {
			$out['portfolio-category'] = 'Portfolio Category';
		}

		return $out;
	}
}

if ( ! function_exists( 'penci_get_taxonomies_image' ) ) {
	function penci_get_taxonomies_image(
		$tax, $term, $options = array(
		'sort' => '',
		'key'  => '',
	)
	) {

		if ( 'field' == $options['sort'] && $options['key'] ) {
			return get_term_meta( $term, $options['key'], true );

		}

		$custom_arg = array();
		$arg        = array(
			'post_type'      => 'post',
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key' => '_thumbnail_id',
				),
			),
			'tax_query'      => array(
				array(
					'taxonomy' => $tax,
					'terms'    => $term,
				),
			),
		);

		if ( $tax == 'product_cat' || $tax == 'product_tag' ) {
			$custom_arg['post_type'] = 'product';
		}

		if ( $tax == 'portfolio-category' ) {
			$custom_arg['post_type'] = 'portfolio';
		}

		if ( 'random' == $options['sort'] ) {
			$custom_arg['orderby'] = 'rand';
		}

		if ( 'view' == $options['sort'] && $options['key'] ) {
			$custom_arg['orderby']  = 'meta_value_num';
			$custom_arg['meta_key'] = $options['key'];
		}

		$arg = wp_parse_args( $custom_arg, $arg );

		$term_img = get_posts( $arg );
		if ( $term_img ) {
			return get_post_thumbnail_id( $term_img[0]->ID );
		}
	}
}

if ( ! function_exists( 'penci_elementor_is_edit_mode' ) ) {
	/**
	 * Whether the edit mode is active.
	 *
	 * @since 1.0.0
	 */
	function penci_elementor_is_edit_mode() {
		return Plugin::$instance->editor->is_edit_mode();
	}
}


if ( ! function_exists( 'penci_elementor_get_render_icon' ) ) {
	/**
	 * Render Icon
	 *
	 * @param array $icon Icon Type, Icon value.
	 * @param array $attributes Icon HTML Attributes.
	 * @param string $tag Icon HTML tag, defaults to <i>.
	 *
	 * @return mixed|string
	 * @since 1.0.0
	 */
	function penci_elementor_get_render_icon( $icon, $attributes = array(), $tag = 'i' ) {
		ob_start();
		Icons_Manager::render_icon( $icon, $attributes, $tag );

		return ob_get_clean();
	}
}


if ( ! function_exists( 'penci_get_any_svg' ) ) {
	function penci_get_any_svg( $file, $id = false ) {
		$content   = function_exists( 'penci_get_any_svg' ) ? penci_get_any_svg( $file ) : '';
		$start_tag = '<svg';
		if ( $id ) {
			$pattern = '/id="(\w)+"/';
			if ( preg_match( $pattern, $content ) ) {
				$content = preg_replace( $pattern, 'id="' . $id . '"', $content, 1 );
			} else {
				$content = preg_replace( '/<svg/', '<svg id="' . $id . '"', $content );
			}
		}
		// Strip doctype
		$position = strpos( $content, $start_tag );
		$content  = substr( $content, $position );

		return $content;
	}
}
if ( ! function_exists( 'penci_get_terms_list' ) ) {
	function penci_get_terms_list( $tax ) {
		$post_cats_args = get_terms(
			array(
				'taxonomy'   => $tax,
				'hide_empty' => true,
			)
		);
		$post_terms     = array();
		if ( $post_cats_args && ! empty( $post_cats_args ) ) {
			foreach ( $post_cats_args as $post_cat ) {
				$post_terms[ $post_cat->term_id ] = $post_cat->name;
			}
		}

		return $post_terms;
	}
}

if ( ! function_exists( 'penci_add_cpt_elementor_support' ) ) {
	add_action( 'option_elementor_cpt_support', 'penci_add_cpt_elementor_support' );
	function penci_add_cpt_elementor_support( $current_option ) {

		$default_theme_support = array( 'penci-block', 'archive-template', 'custom-post-template' );

		if ( is_array( $current_option ) ) {

			foreach ( $default_theme_support as $post_type ) {
				$current_option[] = $post_type;
			}
		}

		return array_filter( $current_option );
	}
}