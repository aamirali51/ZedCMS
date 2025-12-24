<?php
if ( ! function_exists( 'penci_should_render_archive_template' ) ) {
	function penci_should_render_archive_template() {

		if ( is_feed() ) {
			return false;
		}

		$render   = false;
		$cat_data = get_queried_object();

		$cat_pages     = get_theme_mod( 'penci_archive_cat_template' );
		$tag_pages     = get_theme_mod( 'penci_archive_tag_template' );
		$author_pages  = get_theme_mod( 'penci_archive_author_template' );
		$date_pages    = get_theme_mod( 'penci_archive_date_template' );
		$search_paeges = get_theme_mod( 'penci_archive_search_template' );

		if ( is_category() && isset( $cat_data->term_id ) ) {
			$cat_id            = $cat_data->term_id;
			$custom_cat        = get_option( "category_$cat_id" );
			$alayout_save_slug = isset( $custom_cat['penci_archive_layout'] ) ? $custom_cat['penci_archive_layout'] : '';
			$cat_pages         = $alayout_save_slug ? $alayout_save_slug : $cat_pages;

			if ( $cat_pages ) {
				$render = $cat_pages;
			}
		}

		if ( $tag_pages && is_tag() ) {
			$render = $tag_pages;
		}

		if ( $author_pages && is_author() ) {
			$render = $author_pages;
		}

		if ( $date_pages && ( is_date() || is_day() || is_month() || is_year() ) ) {
			$render = $date_pages;
		}

		if ( $search_paeges && is_search() ) {
			$render = $search_paeges;
		}

		if ( $render ) {
			$recheck = get_page_by_path( $render, OBJECT, 'archive-template' );
			$render  = ! empty( $recheck ) && isset( $recheck->ID ) ? $recheck->ID : false;
		}

		if ( $render && penci_is_mobile() ) {
			$mobile_id_template = get_post_meta( $render, 'penci_mobile_page_id', true );
			$render = $mobile_id_template && 'publish' == get_post_status( $mobile_id_template ) ? $mobile_id_template : $render;
		}

		return $render;
	}
}

if ( ! function_exists( 'penci_get_published_posttypes' ) ) {
	function penci_get_published_posttypes() {
		$post_types = get_post_types( array(
			'public'   => true,
			'_builtin' => false
		) );

		$ex = [
			'e-landing-page',
			'elementor_library',
			'product',
			'archive-template',
			'custom-post-template',
			'penci-block'
		];

		return array_diff( $post_types, $ex );
	}
}

if ( ! function_exists( 'penci_should_render_single_template' ) ) {
	function penci_should_render_single_template() {
		$template = false;

		if ( is_singular( 'post' ) ) {
			$customize_template = get_theme_mod( 'penci_single_custom_template' );

			$post_format = get_post_format( get_the_ID() );

			if ( $post_format && get_theme_mod( 'penci_' . $post_format . '_custom_template' ) ) {
				$customize_template = get_theme_mod( 'penci_' . $post_format . '_custom_template' );
			}

			$post_template      = penci_get_single_key( get_the_ID(), 'penci_single_builder_layout', true );
			$template           = $post_template ? $post_template : $customize_template;
		} else {
			foreach ( penci_get_published_posttypes() as $type ) {
				if ( is_singular( $type ) ) {
					$template = get_theme_mod( 'penci_' . $type . '_custom_template' );

					$post_format = get_post_format( get_the_ID() );

					if ( $post_format && get_theme_mod( 'penci_' . $post_format . '_custom_template' ) ) {
						$template = get_theme_mod( 'penci_' . $post_format . '_custom_template' );
					}
				}
			}
		}

		if ( ! empty( $template ) ) {
			$post_data = get_page_by_path( $template, OBJECT, 'custom-post-template' );
			$template  = ( ! empty( $post_data ) && isset( $post_data->ID ) ) ? $post_data->ID : false;
		}

		if ( $template && penci_is_mobile() && ! is_admin() ) {
			$mobile_id_template = get_post_meta( $template, 'penci_mobile_page_id', true );
			$template = $mobile_id_template && 'publish' == get_post_status( $mobile_id_template ) ? $mobile_id_template : $template;
		}

		return $template;

	}
}
if ( ! function_exists( 'penci_is_builder_template' ) ) {
	function penci_is_builder_template() {
		global $wp_query;
		$post_type = isset( $wp_query->query['p'] ) && $wp_query->query['p'] ? get_post( $wp_query->query['p'] )->post_type : '';

		return ( $post_type == 'custom-post-template' || $post_type == 'archive-template' );
	}
}
