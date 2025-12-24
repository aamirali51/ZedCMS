<?php

/**
 * Product Quick View
 */
class penci_product_quickview {

	function __construct() {
		add_action( 'penci_loop_product_buttons', array( $this, 'quickview_button' ) );
		add_action( 'wp_ajax_nopriv_penci_quickview', array( $this, 'quickview_content' ) );
		add_action( 'wp_ajax_penci_quickview', array( $this, 'quickview_content' ) );
		add_action( 'admin_notices', array( $this, 'compare_admin_notice' ) );
	}

	public function quickview_button() {
		echo '<a title="' . penci_woo_translate_text( 'penci_woo_trans_quickview' ) . '" href="#" data-pID="' . get_the_ID() . '" class="penci-quickview-button button">' . __( 'Quick View', 'soledad' ) . '</a>';
	}

	public function quickview_content() {
		global $post, $product;
		$nonce = isset( $_GET['nonce'] ) ? $_GET['nonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die ( 'Nope!' );
		}

		$productID = ( isset( $_GET['pid'] ) ) ? $_GET['pid'] : '';
		if ( $productID ) {
			$productView = get_posts( array( 'post_type' => 'product', 'post__in' => array( $productID ) ) );
			if ( $productView ) {
				foreach ( $productView as $post ) {
					setup_postdata( $post );
					$product = wc_get_product( $post );
					wc_set_loop_prop( 'quickview', true );
					wc_get_template_part( 'content', 'quickview' );
				}
				wp_reset_postdata();
				wc_reset_loop();
			}
		}
		die();
	}

	public function compare_admin_notice() {
		$query['autofocus[section]'] = 'pencidesign_woo_quickview_settings';
		$section_link                = add_query_arg( $query, admin_url( 'customize.php' ) );
		if ( get_theme_mod( 'penci_woocommerce_quickview', true ) && class_exists( 'YITH_WCQV_Frontend' ) ) {
			?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'You\'ve activated the YITH WooCommerce Quick View plugin, please go to <a href="' . esc_url( $section_link ) . '">this page</a> to disable default theme quick view.', 'soledad' ); ?></p>
            </div>
			<?php

		}
	}
}

$productQuickView = new penci_product_quickview();
