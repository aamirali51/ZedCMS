<?php

class penci_product_brand {
	function __construct() {
		add_action( 'woocommerce_before_single_product', array( $this, 'brand_setup' ) );
	}

	public function brand_setup() {

		global $product;
		$brand_locate = get_theme_mod( 'penci_woocommerce_brand_display', 'summary' );
		if ( is_singular( 'product' ) ) {
			$post_locate  = penci_get_single_product_meta( $product->get_id(), 'product_extra_options', 'brand_locate' );
			$brand_locate = $post_locate ? $post_locate : $brand_locate;
		}
		$brand_locate = ( 'top' == $brand_locate ) ? [
			'woocommerce_single_product_summary',
			3
		] : [ 'woocommerce_product_meta_end', 5 ];

		add_filter( 'woocommerce_product_tabs', array( $this, 'product_brand_tab' ) );
		add_action( $brand_locate[0], array( $this, 'product_brand' ), $brand_locate[1] );

	}

	public function product_brand() {
		global $product;
		$attr = get_theme_mod( 'penci_woocommerce_brand_attr' );
		if ( ! $attr || ! get_theme_mod( 'penci_woocommerce_brand' ) ) {
			return;
		}

		$attributes = $product->get_attributes();

		if ( ! isset( $attributes[ $attr ] ) || empty( $attributes[ $attr ] ) ) {
			return;
		}

		$brands   = wc_get_product_terms( $product->get_id(), $attr, array( 'fields' => 'all' ) );
		$taxonomy = get_taxonomy( $attr );

		if ( empty( $brands ) ) {
			return;
		}

		if ( penci_is_shop_on_front() ) {
			$link = home_url();
		} else {
			$link = get_post_type_archive_link( 'product' );
		}


		echo '<div class="penci-product-brands">';

		foreach ( $brands as $brand ) {
			$image       = get_term_meta( $brand->term_id, 'image', true );
			$filter_name = 'filter_' . sanitize_title( str_replace( 'pa_', '', $attr ) );
			$attrs       = '';


			if ( is_object( $taxonomy ) && $taxonomy->public ) {
				$attr_link = get_term_link( $brand->term_id, $brand->taxonomy );
			} else {
				$attr_link = add_query_arg( $filter_name, $brand->slug, $link );
			}

			$content = esc_attr( $brand->name );

			if ( $image ) {
				$img_url = wp_get_attachment_image_url( $image, 'full' );
				$w       = penci_get_image_data_basedurl( $img_url, 'w' );
				$h       = penci_get_image_data_basedurl( $img_url, 'h' );
				$content = '<img width="' . esc_attr( $w ) . '" height="' . esc_attr( $h ) . '" src="' . esc_url( $img_url ) . '" title="' . esc_attr( $brand->name ) . '" alt="' . esc_attr( $brand->name ) . '">';
			}

			echo '<div class="penci-product-brand">';
			echo '<span class="brand-title">' . esc_attr__( 'Brands', 'soledad' ) . ': </span>';
			echo '<a href="' . esc_url( $attr_link ) . '">' . $content . '</a>'; // phpcs:ignore
			echo '</div>';
		}

		echo '</div>';
	}

	function product_brands_links() {
		global $product;
		$brand_option = get_theme_mod( 'penci_woocommerce_brand_attr' );
		$brands       = wc_get_product_terms( $product->get_id(), $brand_option, array( 'fields' => 'all' ) );
		$taxonomy     = get_taxonomy( $brand_option );

		$link = ( penci_is_shop_on_front() ) ? home_url() : get_post_type_archive_link( 'product' );

		echo '<div class="penci-product-brands-links">';

		foreach ( $brands as $brand ) {
			$filter_name = 'filter_' . sanitize_title( str_replace( 'pa_', '', $brand_option ) );

			if ( is_object( $taxonomy ) && $taxonomy->public ) {
				$attr_link = get_term_link( $brand->term_id, $brand->taxonomy );
			} else {
				$attr_link = add_query_arg( $filter_name, $brand->slug, $link );
			}

			$sep = ', ';
			if ( end( $brands ) == $brand ) {
				$sep = '';
			}

			echo '<a href="' . esc_url( $attr_link ) . '">' . $brand->name . '</a>' . $sep;
		}

		echo '</div>';
	}

	function product_brand_tab( $tabs ) {
		global $product;

		$show_tab    = false;
		$brand_title = penci_woo_translate_text( 'penci_woo_trans_about_brand' );
		$brand_info  = wc_get_product_terms( $product->get_id(), get_theme_mod( 'penci_woocommerce_brand_attr' ), array( 'fields' => 'all' ) );
		if ( ! isset( $brand_info[0] ) ) {
			return $tabs;
		}

		if ( $brand_info[0]->description ) {
			$show_tab = true;
		}
		if ( get_theme_mod( 'penci_woocommerce_brand_tab_title', false ) ) {
			$brand_title = penci_woo_translate_text( 'penci_woo_trans_text_about' ) . ' ' . $brand_info[0]->name;
		}

		if ( $show_tab ) {
			$tabs['brand_tab'] = array(
				'title'    => $brand_title,
				'priority' => 50,
				'callback' => array( $this, 'product_brand_tab_content' ),
			);
		}

		return $tabs;
	}

	function product_brand_tab_content() {
		global $product;
		$attr = get_theme_mod( 'penci_woocommerce_brand_attr' );
		if ( ! $attr ) {
			return;
		}

		$attributes = $product->get_attributes();

		if ( ! isset( $attributes[ $attr ] ) || empty( $attributes[ $attr ] ) ) {
			return;
		}

		$brands = wc_get_product_terms( $product->get_id(), $attr, array( 'fields' => 'slugs' ) );

		if ( empty( $brands ) ) {
			return;
		}

		foreach ( $brands as $id => $slug ) {
			echo '<div class="penci-product-brand-description post-entry">';
			$brand = get_term_by( 'slug', $slug, $attr );
			echo do_shortcode( $brand->description );
			echo '</div>';
		}

	}
}

$penci_product_brand = new penci_product_brand();
