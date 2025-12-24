<?php
/**
 * ------------------------------------------------------------------------------------------------
 * Load extra WooCommerce Library
 * ------------------------------------------------------------------------------------------------
 */

$penci_modules = array(
	'quick_view'   => ! penci_is_mobile() ? get_theme_mod( 'penci_woocommerce_quickview', true ) : false,
	'wishlist'     => get_theme_mod( 'penci_woocommerce_wishlist', false ),
	'compare'      => get_theme_mod( 'penci_woocommerce_compare', false ),
	'metaboxes'    => true,
	'progress-bar' => true,
	'quickshop'    => get_theme_mod( 'penci_woocommerce_product_quick_shop', false ),
	'swatches'     => true,
	'brand'        => true,
	'misc'         => true,
);

foreach ( $penci_modules as $module => $enable ) {
	if ( $enable ) {
		require PENCI_SOLEDAD_DIR . '/inc/woocommerce/modules/' . $module . '.php';
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * WooCommerce Widget
 * ------------------------------------------------------------------------------------------------
 */

require PENCI_SOLEDAD_DIR . '/inc/widgets/wph-widget-class.php';
require PENCI_SOLEDAD_DIR . '/inc/widgets/product_filter.php';
require PENCI_SOLEDAD_DIR . '/inc/widgets/price_filter.php';
require PENCI_SOLEDAD_DIR . '/inc/widgets/product_sorting.php';
require PENCI_SOLEDAD_DIR . '/inc/widgets/product_stock_status.php';
require PENCI_SOLEDAD_DIR . '/inc/widgets/product_lists.php';

/**
 * Declare WooCommerce support
 *
 * @since 2.2
 */
add_filter( 'woocommerce_enqueue_styles', 'penci_woo_dequeue_styles' );
function penci_woo_dequeue_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );    // Remove the gloss.
	unset( $enqueue_styles['woocommerce-layout'] );        // Remove the layout.

	return $enqueue_styles;
}

if ( ! function_exists( 'penci_declare_woocommerce_support' ) ) {
	add_action( 'after_setup_theme', 'penci_declare_woocommerce_support' );
	function penci_declare_woocommerce_support() {

		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		remove_theme_support( 'wc-product-gallery-slider' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Register navigation menu.
		register_nav_menus( array(
			'vertical-shop-menu' => 'eCommerce Vertical Menu',
			'sidebar-filter'     => 'Sidebar Filter',
		) );

		// Register sidebar.
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar Filter', 'soledad' ),
			'id'            => 'sidebar-filter',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title penci-border-arrow"><span class="inner-arrow">',
			'after_title'   => '</span></h3>',
		) );
	}
}
add_action( 'wp', 'penci_woo_remove_zoom_images', 100 );
function penci_woo_remove_zoom_images() {
	if ( get_theme_mod( 'penci_woo_disable_zoom' ) ) {
		remove_theme_support( 'wc-product-gallery-zoom' );
	}
}

function penci_woo_get_translate_text() {
	return array(
		'penci_woo_trans_about_brand'           => __( 'About brand', 'soledad' ),
		'penci_woo_trans_about_variable'        => __( 'About %s', 'soledad' ),
		'penci_woo_trans_addtocompare'          => __( 'Add to Compare', 'soledad' ),
		'penci_woo_trans_viewallcompare'        => __( 'View all Compare', 'soledad' ),
		'penci_woo_trans_returnshop'            => __( 'Return to shop', 'soledad' ),
		'penci_woo_trans_desc'                  => __( 'Description', 'soledad' ),
		'penci_woo_trans_demensions'            => __( 'Dimensions', 'soledad' ),
		'penci_woo_trans_weight'                => __( 'Weight', 'soledad' ),
		'penci_woo_trans_availability'          => __( 'Availability', 'soledad' ),
		'penci_woo_trans_sku'                   => __( 'Sku', 'soledad' ),
		'penci_woo_trans_instock'               => __( 'In stock', 'soledad' ),
		'penci_woo_trans_remove_product'        => __( 'Remove product', 'soledad' ),
		'penci_woo_trans_remove'                => __( 'Remove', 'soledad' ),
		'penci_woo_trans_sale'                  => __( 'Sale', 'soledad' ),
		'penci_woo_trans_sold_out'              => __( 'Sold out', 'soledad' ),
		'penci_woo_trans_hot'                   => __( 'Hot', 'soledad' ),
		'penci_woo_trans_new'                   => __( 'New', 'soledad' ),
		'penci_woo_trans_show'                  => __( 'Show', 'soledad' ),
		'penci_woo_trans_all'                   => __( 'All', 'soledad' ),
		'penci_woo_trans_lgtspr'                => __( 'Login to see prices', 'soledad' ),
		'penci_woo_trans_fasort'                => __( 'Filter & Sort', 'soledad' ),
		'penci_woo_trans_innumbeistock'         => __( 'Initial number in stock', 'soledad' ),
		'penci_woo_trans_rqinnumbeistock'       => __( 'Required for stock progress bar option', 'soledad' ),
		'penci_woo_trans_quickview'             => __( 'Quick view', 'soledad' ),
		'penci_woo_trans_close'                 => __( 'Close', 'soledad' ),
		'penci_woo_trans_color'                 => __( 'Color', 'soledad' ),
		'penci_woo_trans_image'                 => __( 'Image', 'soledad' ),
		'penci_woo_trans_label'                 => __( 'Label', 'soledad' ),
		'penci_woo_trans_canimage'              => __( 'Choose an image', 'soledad' ),
		'penci_woo_trans_usimage'               => __( 'Use image', 'soledad' ),
		'penci_woo_trans_upaddimg'              => __( 'Upload/Add Image', 'soledad' ),
		'penci_woo_trans_rimg'                  => __( 'Remove image', 'soledad' ),
		'penci_woo_trans_adtwilsh'              => __( 'Add to Wishlist', 'soledad' ),
		'penci_woo_trans_brtwilsh'              => __( 'Browse Wishlist', 'soledad' ),
		'penci_woo_trans_successrmwilish'       => __( 'The product %s has successfully removed.', 'soledad' ),
		'penci_woo_trans_rmproduct'             => __( 'Remove Product', 'soledad' ),
		'penci_woo_trans_ffallpfuv'             => __( 'Feed for all posts filed under %s', 'soledad' ),
		'penci_woo_trans_vtspcart'              => __( 'View your shopping cart', 'soledad' ),
		'penci_woo_trans_loading'               => __( 'Loading...', 'soledad' ),
		'penci_woo_trans_eoc'                   => __( 'End of content', 'soledad' ),
		'penci_woo_trans_nmptl'                 => __( 'No more pages to load', 'soledad' ),
		'penci_woo_trans_vmproduct'             => __( 'View more products', 'soledad' ),
		'penci_woo_trans_products'              => __( 'products', 'soledad' ),
		'penci_woo_trans_noproductfount'        => __( 'No product found', 'soledad' ),
		'penci_woo_trans_shoppingcart'          => __( 'Shopping cart', 'soledad' ),
		'penci_woo_trans_checkout'              => __( 'Checkout', 'soledad' ),
		'penci_woo_trans_ordrcompleted'         => __( 'Order complete', 'soledad' ),
		'penci_woo_trans_npostfound'            => __( 'No posts found', 'soledad' ),
		'penci_woo_trans_allrs'                 => __( 'All results', 'soledad' ),
		'penci_woo_trans_cpproduct'             => __( 'Compare Products', 'soledad' ),
		'penci_woo_trans_day'                   => __( 'Day', 'soledad' ),
		'penci_woo_trans_hours'                 => __( 'Hours', 'soledad' ),
		'penci_woo_trans_second'                => __( 'Seconds', 'soledad' ),
		'penci_woo_trans_minutes'               => __( 'Minutes', 'soledad' ),
		'penci_woo_trans_vcart'                 => __( 'View Cart', 'soledad' ),
		'penci_woo_trans_checkout_text'         => __( 'Check out', 'soledad' ),
		'penci_woo_trans_addtocart'             => __( 'has been added to your cart', 'soledad' ),
		'penci_woo_trans_addtocompare_notice'   => __( 'has been added to compare', 'soledad' ),
		'penci_woo_trans_removecompare'         => __( 'has been removed from compare', 'soledad' ),
		'penci_woo_trans_removewishlist'        => __( 'has been removed from your wishlist', 'soledad' ),
		'penci_woo_trans_addwishlist'           => __( 'has been added to your wishlist', 'soledad' ),
		'penci_woo_trans_viewcart'              => __( 'View your shopping cart', 'soledad' ),
		'penci_woo_trans_productfilter'         => __( 'Product Filter', 'soledad' ),
		'penci_woo_trans_shopppingcart'         => __( 'Shopping Cart', 'soledad' ),
		'penci_woo_trans_viewcompare'           => __( 'View your compare products', 'soledad' ),
		'penci_woo_trans_viewwishlist'          => __( 'View your wishlist products', 'soledad' ),
		'penci_woo_trans_relatedproduct'        => __( 'Related products', 'soledad' ),
		'penci_woo_trans_upsell_title'          => __( 'You may also like&hellip;', 'soledad' ),
		'penci_woo_trans_compare_empty_title'   => __( 'Compare list is empty.', 'soledad' ),
		'penci_woo_trans_wishlist_empty_title'  => __( 'Wishlist is empty.', 'soledad' ),
		'penci_woocommerce_wishlist_empty_text' => __( 'You don\'t have any products in the wishlist yet. <br> You will find a lot of interesting products on our "Shop" page.', 'soledad' ),
		'penci_woocommerce_compare_empty_text'  => __( 'No products added in the compare list. You must add some products to compare them.<br> You will find a lot of interesting products on our "Shop" page.', 'soledad' ),
		'penci_woo_cart_empty_title'            => __( 'Your cart is currently empty.', 'soledad' ),
		'penci_woo_cart_empty_textarea'         => __( 'You don\'t have any products in the shop yet. <br> You will find a lot of interesting products on our "Shop" page.', 'soledad' ),
		'penci_woo_trans_sidebar_filter'        => __( 'Sidebar Filter', 'soledad' ),
		'penci_woo_trans_compare_product'       => __( 'Product', 'soledad' ),
		'penci_woo_trans_blog_search_result'    => __( 'Search Results from Blog', 'soledad' ),
		'penci_woo_trans_showmore_result'       => __( 'Show More Result', 'soledad' ),
		'penci_woo_trans_ordered'               => __( 'Ordered:', 'soledad' ),
		'penci_woo_trans_items_avail'           => __( 'Items available:', 'soledad' ),
		'penci_woo_trans_sold'                  => __( 'Sold', 'soledad' ),
		'penci_woo_trans_text_about'            => __( 'About', 'soledad' ),
	);
}

function penci_woo_translate_text( $text ) {
	$translate_text = penci_woo_get_translate_text();

	$default_translate = isset( $translate_text[ $text ] ) ? $translate_text[ $text ] : '';

	return do_shortcode( get_theme_mod( $text, $default_translate ) );
}

/**
 * Update cart total when products are added to the cart
 *
 * @since 2.2.4
 */
if ( ! function_exists( 'penci_woocommerce_header_add_to_cart_fragment' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'penci_woocommerce_header_add_to_cart_fragment' );
	function penci_woocommerce_header_add_to_cart_fragment( $fragments ) {
		ob_start();
		?>
        <a class="cart-contents" href="
		<?php
		$cart_link = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : WC()->cart->get_cart_url();
		echo esc_url( $cart_link );
		?>
		   "
           title="<?php echo penci_woo_translate_text( 'penci_woo_trans_viewcart' ); ?>">
            <i class="penciicon-shopping-cart"></i>
            <span><?php echo sprintf( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?></span></a>
		<?php

		$fragments['.shoping-cart-icon a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}

/**
 * Change breadcrum markup for woocommerce
 *
 * @since 2.2
 */
if ( ! function_exists( 'penci_custom_woocommerce_breadcrumbs' ) ) {
	add_filter( 'woocommerce_breadcrumb_defaults', 'penci_custom_woocommerce_breadcrumbs' );
	function penci_custom_woocommerce_breadcrumbs() {
		$home  = penci_get_setting( 'penci_trans_home' );
		$class = get_theme_mod( 'penci_single_product_breadcrumb_position', 'top' );

		return array(
			'delimiter'   => penci_icon_by_ver( 'fas fa-angle-right' ),
			'wrap_before' => '<div class="container penci-breadcrumb penci-woo-breadcrumb ' . esc_attr( $class ) . '">',
			'wrap_after'  => '</div>',
			'before'      => '<span>',
			'after'       => '</span>',
			'home'        => $home,
		);
	}
}

/**
 * Helper function
 *
 * @since 1.0
 */

if ( ! function_exists( 'penci_is_product_attribute_archive' ) ) {
	function penci_is_product_attribute_archive() {
		$queried_object = get_queried_object();
		if ( $queried_object && property_exists( $queried_object, 'taxonomy' ) ) {
			$taxonomy = $queried_object->taxonomy;

			return substr( $taxonomy, 0, 3 ) === 'pa_';
		}

		return false;
	}
}

if ( ! function_exists( 'penci_is_shop_archive' ) ) {
	function penci_is_shop_archive() {
		return ( is_shop() || is_product_category() || is_product_tag() || is_singular( 'product' ) || penci_is_product_attribute_archive() );
	}
}

if ( ! function_exists( 'penci_is_shop_catelog' ) ) {
	function penci_is_shop_catelog() {
		return ( is_shop() || is_product_category() || is_product_tag() || penci_is_product_attribute_archive() );
	}
}


/**
 * Custom numbers related products for Woocommerce
 *
 * @since 2.2
 */
if ( ! function_exists( 'penci_custom_number_related_products_args' ) ) {
	add_filter( 'woocommerce_output_related_products_args', 'penci_custom_number_related_products_args' );
	function penci_custom_number_related_products_args( $args ) {
		$number = 4;
		if ( get_theme_mod( 'penci_woo_number_related_products' ) ) :
			$number = absint( get_theme_mod( 'penci_woo_number_related_products' ) );
		endif;

		$args['posts_per_page'] = $number; // 4 related products

		return $args;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Filter & action
 * ------------------------------------------------------------------------------------------------
 */

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

add_filter( 'woocommerce_show_page_title', function () {
	return false;
} );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

if ( ! function_exists( 'penci_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function penci_template_loop_product_title() {

		$length = wc_get_loop_prop( 'title-length' );
		$title  = get_the_title();

		if ( $length ) {
			$title = wp_trim_words( $title, $length );
		}

		echo '<h3 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title penci-loop-title' ) ) . '"><a href="' . get_permalink() . '">' . $title . '</a></h3>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	add_action( 'woocommerce_shop_loop_item_title', 'penci_template_loop_product_title', 10 );
}

add_action( 'woocommerce_archive_description', 'penci_woocommerce_before_main_content' );
function penci_woocommerce_before_main_content() {
	?>
    <div class="penci-woo-before-main-content">
        <div class="penci-woo-before-main-inner-content content-left">
			<?php
			do_action( 'penci_woocommerce_before_main_shop_content_left' );
			if ( ! get_theme_mod( 'penci_archive_move_desc' ) ) {
				?>
                <div class="post-entry pc-proterm-description">
					<?php do_action( 'penci_woocommerce_before_main_shop_content_desc' ); ?>
                </div>
			<?php } ?>
        </div>
        <div class="penci-woo-before-main-inner-content content-right">
			<?php do_action( 'penci_woocommerce_before_main_shop_content_right' ); ?>
        </div>
    </div>
	<?php
}

add_action( 'woocommerce_after_shop_loop', function () {
	if ( get_theme_mod( 'penci_archive_move_desc' ) ) {
		?>
        <div class="post-entry pc-proterm-description pc-proterm-description-bottom">
			<?php do_action( 'penci_woocommerce_before_main_shop_content_desc' ); ?>
        </div>
		<?php
	}
}, 20 );

remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description' );
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description' );

add_action( 'penci_woocommerce_before_main_shop_content_desc', 'woocommerce_taxonomy_archive_description', 99 );
add_action( 'penci_woocommerce_before_main_shop_content_desc', 'woocommerce_product_archive_description', 99 );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'penci_woocommerce_before_main_shop_content_right', 'woocommerce_catalog_ordering', 10 );

add_action( 'init', 'penci_woo_page_header_setup' );
add_action( 'woocommerce_before_single_product', 'penci_woo_page_header_setup' );
function penci_woo_page_header_setup() {

	$single_product_breadcrumb = get_theme_mod( 'penci_single_product_breadcrumb_position', 'top' );

	if ( ! is_product() ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		add_action( 'penci_woocommerce_before_main_shop_content_left', 'woocommerce_breadcrumb', 30 );
	}

	if ( is_product() && 'top' === $single_product_breadcrumb ) {
		add_action( 'woocommerce_before_single_product', 'woocommerce_breadcrumb', 20 );
	}

	if ( is_product() && 'summary' === $single_product_breadcrumb ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 0 );
	}

	if ( is_product() && 'hidden' === $single_product_breadcrumb ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	}

	if ( get_theme_mod( 'penci_woo_disable_breadcrumb', false ) ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		remove_action( 'penci_woocommerce_before_main_shop_content_left', 'woocommerce_breadcrumb', 30 );
	}

	if ( penci_is_mobile() || penci_is_tablet() ) {
		add_action( 'penci_woocommerce_before_main_shop_content_left', 'woocommerce_result_count', 50 );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Add custom style
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'penci_woocommerc_custom_style' ) ) {
	function penci_woocommerc_custom_style() {

		$quick_view_width = get_theme_mod( 'penci_woocommerce_quickview_width', 960 );

		$css_elements = '--pc-woo-quick-view-width: ' . $quick_view_width . 'px;';

		return ':root{' . $css_elements . '}';
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Add Style
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'penci_woocommerce_style' ) ) {
	function penci_woocommerce_style() {

		wp_enqueue_style( 'woocommerce', PENCI_SOLEDAD_URL . '/inc/woocommerce/css/build/woocommerce.css', array(), PENCI_SOLEDAD_VERSION );
		wp_enqueue_style( 'woocommerce-layout', PENCI_SOLEDAD_URL . '/inc/woocommerce/css/build/woocommerce-layout.css', array(), PENCI_SOLEDAD_VERSION );
		wp_enqueue_style( 'penci-woocommerce', PENCI_SOLEDAD_URL . '/inc/woocommerce/css/penci-woocomerce.css', array(), PENCI_SOLEDAD_VERSION );

		wp_enqueue_script( 'popper', PENCI_SOLEDAD_URL . '/inc/woocommerce/js/popper.min.js', array(), PENCI_SOLEDAD_VERSION, true );
		wp_enqueue_script( 'tooltip', PENCI_SOLEDAD_URL . '/inc/woocommerce/js/tippy-bundle.umd.min.js', array( 'popper' ), PENCI_SOLEDAD_VERSION, true );
		wp_enqueue_script( 'jquery.autocomplete', PENCI_SOLEDAD_URL . '/inc/woocommerce/js/jquery.autocomplete.min.js', array(), PENCI_SOLEDAD_VERSION, true );
		wp_enqueue_script( 'penci-woocommerce', PENCI_SOLEDAD_URL . '/inc/woocommerce/js/penci-woocommerce.js', array( 'jquery.pjax' ), PENCI_SOLEDAD_VERSION, true );

		wp_register_script( 'infinite-scroll', PENCI_SOLEDAD_URL . '/inc/woocommerce/js/infinite-scroll.pkgd.min.js', array(), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'woo-countdown', PENCI_SOLEDAD_URL . '/inc/woocommerce/js/jquery.countdown-pack.min.js', array(), PENCI_SOLEDAD_VERSION, true );

		if ( get_theme_mod( 'penci_woo_notify', false ) ) {
			wp_enqueue_style( 'jquery.toast' );
			wp_enqueue_script( 'jquery.toast' );
		}

		if ( 'pagination' !== get_theme_mod( 'penci_shop_product_pagination', 'pagination' ) ) {
			wp_enqueue_script( 'infinite-scroll' );
		}

		wp_enqueue_script( 'wc-add-to-cart-variation' );
		wp_deregister_script( 'wc-single-product' );
		wp_register_script( 'wc-single-product', PENCI_SOLEDAD_URL . '/inc/woocommerce/js/single-product.js', array(), PENCI_SOLEDAD_VERSION, true );

		wp_register_script( 'grid-picker', PENCI_SOLEDAD_URL . '/inc/woocommerce/js/jquery-grid-picker.js', array(), PENCI_SOLEDAD_VERSION, true );

		wp_add_inline_style( 'penci-woocommerce', penci_woocommerc_custom_style() );

		if ( get_theme_mod( 'pencidesign_woo_single_select2button', true ) ) {
			wp_enqueue_script( 'grid-picker' );
		}

		if ( is_rtl() ) {
			wp_enqueue_style( 'penci-woocommerce-rtl', PENCI_SOLEDAD_URL . '/inc/woocommerce/css/penci-woocommerce-rtl.css', array(), PENCI_SOLEDAD_VERSION );
		}

		wp_localize_script( 'penci-woocommerce', 'penciwoo', array(
			'ajaxUrl'                       => admin_url( 'admin-ajax.php' ),
			'nonce'                         => wp_create_nonce( 'ajax-nonce' ),
			'shoppage'                      => get_permalink( wc_get_page_id( 'shop' ) ),
			'checkout_url'                  => get_permalink( wc_get_page_id( 'checkout' ) ),
			'checkout_text'                 => penci_woo_translate_text( 'penci_woo_trans_checkout_text' ),
			'addtocart_jax'                 => get_theme_mod( 'penci_single_product_disable_ajax_atc', false ),
			'addtocart'                     => penci_woo_translate_text( 'penci_woo_trans_addtocart' ),
			'addtocompare'                  => penci_woo_translate_text( 'penci_woo_trans_addtocompare_notice' ),
			'removecompare'                 => penci_woo_translate_text( 'penci_woo_trans_removecompare' ),
			'removewishlist'                => penci_woo_translate_text( 'penci_woo_trans_removewishlist' ),
			'addwishlist'                   => penci_woo_translate_text( 'penci_woo_trans_addwishlist' ),
			'browsewishlist'                => penci_woo_translate_text( 'penci_woo_trans_brtwilsh' ),
			'browsecompare'                 => penci_woo_translate_text( 'penci_woo_trans_cpproduct' ),
			'returnshop'                    => penci_woo_translate_text( 'penci_woo_trans_returnshop' ),
			'allresults'                    => penci_woo_translate_text( 'penci_woo_trans_allrs' ),
			'relateproduct'                 => (int) get_theme_mod( 'penci_shop_product_related_columns', 4 ),
			'upsellproduct'                 => (int) get_theme_mod( 'penci_shop_product_up_sell_columns', 4 ),
			'crosssellproduct'              => (int) get_theme_mod( 'penci_shop_product_cross_sell_columns', 4 ),
			'catcolumns'                    => (int) get_theme_mod( 'penci_shop_cat_columns', 4 ),
			'cartstyle'                     => get_theme_mod( 'penci_woo_cart_style', 'side-right' ),
			'quickshop'                     => (int) get_theme_mod( 'penci_woocommerce_product_quick_shop', false ),
			'cartnotify'                    => (int) get_theme_mod( 'penci_woo_add_to_cart_notify', false ),
			'pagination'                    => get_theme_mod( 'penci_shop_product_pagination', 'pagination' ),
			'ajaxshop'                      => (int) get_theme_mod( 'penci_woocommerce_ajax_shop', true ),
			'scrolltotopajax'               => (int) get_theme_mod( 'penci_woocommerce_ajax_shop_auto_top', true ),
			'pagination_ajax_threshold'     => get_theme_mod( 'penci_shop_product_pagination_ajax_threshold', 400 ),
			'pagination_ajax_history'       => (int) get_theme_mod( 'penci_shop_product_pagination_ajax_history', false ),
			'pagination_ajax_title'         => (int) get_theme_mod( 'penci_shop_product_pagination_ajax_title', false ),
			'select2button'                 => (int) get_theme_mod( 'pencidesign_woo_single_select2button', true ),
			'wishlist_empty_heading'        => penci_woo_translate_text( 'penci_woo_trans_wishlist_empty_title' ),
			'wishlist_empty_text'           => penci_woo_translate_text( 'penci_woocommerce_wishlist_empty_text' ),
			'compare_empty_heading'         => penci_woo_translate_text( 'penci_woo_trans_compare_empty_title' ),
			'compare_empty_text'            => penci_woo_translate_text( 'penci_woocommerce_compare_empty_text' ),
			'disable_mobile_autoscroll'     => (int) get_theme_mod( 'penci_woo_mobile_autoscroll', false ),
			'toast_notify'                  => (int) get_theme_mod( 'penci_woo_notify', false ),
			'toast_notify_position'         => get_theme_mod( 'penci_woo_notify_position', 'bottom-right' ),
			'toast_notify_text_align'       => get_theme_mod( 'penci_woo_notify_text_align', 'left' ),
			'toast_notify_transition'       => get_theme_mod( 'penci_woo_notify_transition', 'slide' ),
			'toast_notify_hide_after'       => get_theme_mod( 'penci_woo_notify_hide_after', 5000 ),
			'toast_notify_bg_color'         => get_theme_mod( 'penci_woo_notify_bg_color', '' ),
			'toast_notify_text_color'       => get_theme_mod( 'penci_woo_notify_text_color', '' ),
			'toast_notify_shop_url'         => esc_url( wc_get_cart_url() ),
			'toast_notify_shop_sucess_text' => penci_woo_translate_text( 'penci_woo_trans_succatc' ),
			'toast_notify_shop_text'        => penci_woo_translate_text( 'penci_woo_trans_vcart' ),
			'fullpanelposition'             => get_theme_mod( 'penci_woo_fw_panel_pst', 'side-right' ),
			'search_input_padding'          => get_theme_mod( 'penci_woo_search_input_padding', 'true' ),
			'countdown_days'                => penci_woo_translate_text( 'penci_woo_trans_day' ),
			'countdown_hours'               => penci_woo_translate_text( 'penci_woo_trans_hours' ),
			'countdown_mins'                => penci_woo_translate_text( 'penci_woo_trans_minutes' ),
			'countdown_sec'                 => penci_woo_translate_text( 'penci_woo_trans_second' ),
			'wdgh'                          => (int) get_theme_mod( 'penci_woo_widgets_scroll_height', 275 ),
			'wdgmh'                         => (int) get_theme_mod( 'penci_woo_widgets_scroll_m_height', 275 ),
			'cart_hash_key'                 => apply_filters( 'woocommerce_cart_hash_key', 'wc_cart_hash_' . md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() ) ),
			'fragment_name'                 => apply_filters( 'woocommerce_cart_fragment_name', 'wc_fragments_' . md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() ) ),
			'demo_mods'                     => penci_woo_theme_mods_custom_link(),
		) );
	}

	add_action( 'wp_enqueue_scripts', 'penci_woocommerce_style', 99 );
}

if ( ! function_exists( 'penci_woocommerce_get_second_image' ) ) {
	add_action( 'penci_loop_product_image', 'penci_woocommerce_get_second_image' );
	function penci_woocommerce_get_second_image() {
		global $product;

		$image_id       = (int) $product->get_image_id();
		$attachment_ids = $product->get_gallery_image_ids();
		$hover_id       = ! empty( $attachment_ids[0] ) ? (int) $attachment_ids[0] : '';

		$size        = 'woocommerce_thumbnail';
		$custom_size = wc_get_loop_prop( 'img_size' );
		$image_size  = apply_filters( 'single_product_archive_thumbnail_size', $size );
		$image_size  = $custom_size ? $custom_size : $image_size;

		if ( count( $attachment_ids ) > 1 && $hover_id === $image_id ) {
			$hover_id = (int) $attachment_ids[1];
		}

		$image_id = ! empty( $hover_id ) ? $hover_id : $image_id;

		if ( get_theme_mod( 'penci_woocommerce_product_hover_img' ) ) :
			?>
            <div class="hover-img">
                <a href="<?php echo esc_url( get_permalink() ); ?>">
					<?php echo wp_get_attachment_image( $image_id, $image_size ); ?>
                </a>
            </div>
		<?php
		endif;
	}
}

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'penci_woocommerce_product_thumbnail', 10 );
function penci_woocommerce_product_thumbnail() {
	global $product;
	$size        = 'woocommerce_thumbnail';
	$custom_size = wc_get_loop_prop( 'img_size' );
	$image_size  = apply_filters( 'single_product_archive_thumbnail_size', $size );
	$image_size  = $custom_size ? $custom_size : $image_size;

	$thumb = $product ? $product->get_image( $image_size ) : '';

	if ( $thumb ) {
		echo '<a class="penci-main-loop-image" href="' . esc_url( get_permalink() ) . '">' . $thumb . '</a>';
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Add extra button to product loop
 * ------------------------------------------------------------------------------------------------
 */

add_action( 'woocommerce_single_product_summary', 'penci_woocommerce_extra_single_product_extra_buttons', 35 );
function penci_woocommerce_extra_single_product_extra_buttons() {
	?>
    <div class="penci-extra-buttons">
		<?php
		do_action( 'penci_single_product_extra_buttons' );
		?>
    </div>
	<?php
}

/**
 * ------------------------------------------------------------------------------------------------
 * Return product attr as array
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'penci_product_attributes_array' ) ) {
	function penci_product_attributes_array( $default = false ) {

		if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
			return;
		}
		$attributes = array();

		if ( $default ) {
			$attributes[''] = esc_attr__( 'Default Theme Setting', 'soledad' );
		}

		foreach ( wc_get_attribute_taxonomies() as $attribute ) {
			$attributes[ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
		}

		return $attributes;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Check product show on front page
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'penci_is_shop_on_front' ) ) {
	function penci_is_shop_on_front() {
		return function_exists( 'wc_get_page_id' ) && 'page' === get_option( 'show_on_front' ) && wc_get_page_id( 'shop' ) == get_option( 'page_on_front' );
	}
}

if ( class_exists( 'YITH_WCWL_Frontend' ) ) {
	add_action( 'penci_single_product_extra_buttons', array( YITH_WCWL_Frontend::get_instance(), 'print_button' ) );
	remove_action( 'init', array( YITH_WCWL_Frontend(), 'add_button' ) );
	add_action( 'penci_loop_product_buttons', array( YITH_WCWL_Frontend(), 'print_button' ), 10 );
}

if ( function_exists( 'YITH_WCQV_Frontend' ) ) {
	remove_action( 'init', array( YITH_WCQV_Frontend(), 'add_button' ) );
	add_action( 'penci_loop_product_buttons', array( YITH_WCQV_Frontend(), 'yith_add_quick_view_button' ), 5 );
}

if ( class_exists( 'YITH_Woocompare_Frontend' ) ) {
	$yith_woocompare_frontend = new YITH_Woocompare_Frontend();
	add_action( 'penci_loop_product_buttons', array( $yith_woocompare_frontend, 'add_compare_link' ), 20 );
	add_action( 'penci_single_product_extra_buttons', array( $yith_woocompare_frontend, 'add_compare_link' ), 20 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get gallery image html + link to product
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'penci_get_gallery_image_html' ) ) {
	function penci_get_gallery_image_html( $attachment_id, $main_image = false, $slider = false ) {
		$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array(
			$gallery_thumbnail['width'],
			$gallery_thumbnail['height'],
		) );
		$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
		$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
		$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
		$alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
		$image             = wp_get_attachment_image( $attachment_id, $image_size, false, apply_filters( 'woocommerce_gallery_image_html_attachment_image_params', array(
			'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
			'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
			'data-src'                => isset( $full_src[0] ) ? esc_url( $full_src[0] ) : '',
			'data-large_image'        => isset( $full_src[0] ) ? esc_url( $full_src[0] ) : '',
			'data-large_image_width'  => isset( $full_src[1] ) ? esc_attr( $full_src[1] ) : '',
			'data-large_image_height' => isset( $full_src[2] ) ? esc_attr( $full_src[2] ) : '',
			'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
		), $attachment_id, $image_size, $main_image ) );

		$out = '';

		$extra_class = $main_image ? 'pcw-gallery-main pcw-gallery' : 'pcw-gallery';

		if ( $slider && isset( $thumbnail_src[0] ) ) {
			$image_new = '<div class="penci-image-holder" style="background-image:url(' . $thumbnail_src[0] . ')"></div>';
			$out       = '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image ' . $extra_class . '">' . $image . '</div>';
		} elseif ( ! $slider && isset( $thumbnail_src[0] ) ) {
			$out = '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image ' . $extra_class . '"><a href="' . esc_url( $full_src[0] ) . '">' . $image . '</a></div>';
		}

		return $out;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Add cart icon to header
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'penci_top_cart_icon' ) ) {
	function penci_top_cart_icon() {
		get_template_part( 'template-parts/header/cart-icon' );
	}

	add_action( 'penci_header_extra_icons', 'penci_top_cart_icon', 5 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Default loop args
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'penci_custom_product_query_default_args' ) ) {
	function penci_custom_product_query_default_args() {
		return array(
			// General.
			'element_title'              => '',
			'title'                      => '',

			// Query.
			'post_type'                  => 'product',
			'include'                    => '',
			'taxonomies'                 => '',
			'offset'                     => '',
			'orderby'                    => '',
			'query_type'                 => 'OR',
			'order'                      => '',
			'meta_key'                   => '', // phpcs:ignore
			'exclude'                    => '',
			'header_tools'               => '0',
			'product_type'               => '',

			// Carousel.
			'speed'                      => '5000',
			'slides_per_view'            => '4',
			'wrap'                       => '',
			'autoplay'                   => 'no',
			'center_mode'                => 'no',
			'hide_pagination_control'    => '',
			'hide_prev_next_buttons'     => '',
			'scroll_per_page'            => 'yes',

			// Layout.
			'layout'                     => 'grid',
			'pagination'                 => '',
			'items_per_page'             => 12,
			'columns'                    => array( 'size' => 4 ),
			'product_horizontal_spacing' => array( 'size' => 30 ),
			'product_rating'             => 1,
			'product_categories'         => 1,

			// Design.
			'product_style'              => get_theme_mod( 'penci_woocommerce_product_style', 'style-1' ),
			'sale_countdown'             => 0,
			'stock_progress_bar'         => 0,
			'img_size'                   => 'woocommerce_thumbnail',
			'icon_style'                 => get_theme_mod( 'penci_woocommerce_product_icon_hover_style', 'round' ),
			'icon_position'              => get_theme_mod( 'penci_woocommerce_product_icon_hover_position', 'top-left' ),
			'icon_animation'             => get_theme_mod( 'penci_woocommerce_product_icon_hover_animation', 'move-right' ),
			'product_round_style'        => penci_shop_product_round_style( get_theme_mod( 'penci_woocommerce_product_icon_hover_style', 'round' ), get_theme_mod( 'penci_woocommerce_product_icon_hover_position', 'top-left' ) ),

			// Extra.
			'loop_name'                  => 'custom',
			'elementor'                  => true,
			'class'                      => '',
		);
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * WooCommerce pJax Preloader
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'penci_pjax_preloader' ) ) {
	function penci_pjax_preloader() {
		$html = '<div class="penci-products-preloader"><span class="penci-loading-icon"><span class="bubble"></span><span class="bubble"></span><span class="bubble"></span></span></div>';
		$html = apply_filters( 'penci_pjax_preloader', $html );
		echo $html;
	}

	add_action( 'woocommerce_before_shop_loop', 'penci_pjax_preloader', 35 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * WooCommerce single class
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'penci_woo_single_product_class' ) ) {
	function penci_woo_single_product_class( $classes, $product ) {
		global $product;
		if ( is_singular( 'product' ) ) {
			$id = get_the_ID();

			$img_width          = get_theme_mod( 'penci_single_product_img_width', 'standard' );
			$img_width          = penci_get_single_product_meta( $id, 'product_general', 'penci_single_product_img_width', $img_width );
			$thumbnail_position = get_theme_mod( 'penci_single_product_thumbnail_position', 'standard' );
			$thumbnail_position = penci_get_single_product_meta( $id, 'product_general', 'penci_single_product_thumbnail_position', $thumbnail_position );

			$summary_align = get_theme_mod( 'penci_single_product_summary_align', 'standard' );
			$summary_align = penci_get_single_product_meta( $id, 'product_general', 'product_summary_align', $summary_align );


			$top_related_posts = get_theme_mod( 'penci_single_product_top_related_product', true );

			$classes[] = $top_related_posts ? 'top-related-posts-show' : 'top-related-posts-hidden';
			$classes[] = 'pcw-breadcrumb-' . get_theme_mod( 'penci_single_product_breadcrumb_position', 'default' );


			if ( penci_woo_is_activate_sidebar() ) {
				$classes[] = 'penci-sidebar-enable';
			}

			$product_background = get_post_meta( $id, 'penci_pmeta_product_background', true );

			$classes[] = ( isset( $product_background['product_wrap_bgcolor'] ) && $product_background['product_wrap_bgcolor'] ) || ( isset( $product_background['product_wrap_bgimg'] ) && $product_background['product_wrap_bgimg'] ) ? 'has-background' : 'no-background';

			$img_width = 'fullwidth' == $img_width ? 'fullwidth penci-product-img-fullwidth-container' : $img_width;

			$classes[] = 'penci-product-img-' . $img_width;
			$classes[] = 'penci-product-' . $thumbnail_position;
			$classes[] = 'penci-summary-align-' . $summary_align;

			$attachment_ids     = $product->get_gallery_image_ids();
			$thumbnail_position = get_theme_mod( 'penci_single_product_thumbnail_position', 'thumbnail-left' );
			$thumbnail_position = penci_get_single_product_meta( get_the_ID(), 'product_general', 'penci_single_product_thumbnail_position', $thumbnail_position );
			$thumbnail_position = $attachment_ids && $product->get_image_id() ? $thumbnail_position : 'no-thumbnail';

			$classes[] = $thumbnail_position;
		}

		$social_share_style = get_theme_mod( 'penci_woo_social_share_style', 'style-1' );
		$social_share_icon  = get_theme_mod( 'penci_woo_social_icon_style', 'circle' );

		$attachment_ids = $product->get_gallery_image_ids();

		$classes[] = 'penci-share-' . $social_share_style;
		$classes[] = 'penci-share-icon-style-' . $social_share_icon;
		$classes[] = ! empty( $attachment_ids ) ? 'has-product-gallery' : 'no-product-gallery';

		return $classes;
	}

	add_filter( 'woocommerce_post_class', 'penci_woo_single_product_class', 10, 2 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * WooCommerce get shop link with the current query
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'penci_shop_page_link' ) ) {
	function penci_shop_page_link( $keep_query = false, $taxonomy = '' ) {
		// Base Link decided by current page.
		$link = '';

		if ( class_exists( 'Automattic\Jetpack\Constants' ) && Automattic\Jetpack\Constants::is_defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) || is_shop() ) {
			$link = get_permalink( wc_get_page_id( 'shop' ) );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} elseif ( get_queried_object() ) {
			$queried_object = get_queried_object();

			if ( property_exists( $queried_object, 'taxonomy' ) ) {
				$link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			}
		}

		if ( $keep_query ) {

			$link = apply_filters( 'penci_woo_filter_link', $link );

			/**
			 * Search Arg.
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( wp_specialchars_decode( get_search_query() ) ), $link );
			}

			// Post Type Arg.
			if ( isset( $_GET['post_type'] ) ) {
				$link = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $link );

				// Prevent post type and page id when pretty permalinks are disabled.
				if ( is_shop() ) {
					$link = remove_query_arg( 'page_id', $link );
				}
			}

			// Min Rating Arg.
			if ( isset( $_GET['min_rating'] ) ) {
				$link = add_query_arg( 'min_rating', wc_clean( $_GET['min_rating'] ), $link );
			}

			// All current filters.
			if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
				foreach ( $_chosen_attributes as $name => $data ) {
					if ( $name === $taxonomy ) {
						continue;
					}
					$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );
					if ( ! empty( $data['terms'] ) ) {
						$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
					}
					if ( 'or' === $data['query_type'] ) {
						$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
					}
				}
			}
		}

		$link = apply_filters( 'penci_shop_page_link', $link, $keep_query, $taxonomy );

		if ( is_string( $link ) ) {
			return $link;
		} else {
			return '';
		}
	}
}
if ( ! function_exists( 'penci_woo_theme_mods_custom_link' ) ) {
	function penci_woo_theme_mods_custom_link() {
		$user_attr = array();

		return apply_filters( 'penci_woo_allow_get_url', $user_attr );
	}
}

add_filter( 'penci_woo_filter_link', function ( $link ) {

	$allow_get = array(
		'min_price',
		'max_price',
		'orderby',
		'stock_status',
		'per_row',
		'per_page',
		'shop_view',
		'shortcode',
	);

	$allow_get = apply_filters( 'penci_woo_allow_get_default_url', $allow_get );
	$mods_get  = penci_woo_theme_mods_custom_link();

	$allow_get = array_merge( $allow_get, $mods_get );

	foreach ( $allow_get as $var ) {
		if ( isset( $_GET[ $var ] ) ) {
			$link = add_query_arg( $var, wc_clean( $_GET[ $var ] ), $link );
		}
	}

	return $link;
} );

if ( ! function_exists( 'penci_woo_get_products_per_page' ) ) {
	function penci_woo_get_products_per_page() {
		$products_per_page = get_theme_mod( 'penci_woo_post_per_page', 24 );

		if ( isset( $_REQUEST['per_page'] ) && ! empty( $_REQUEST['per_page'] ) ) {
			$products_per_page = $_REQUEST['per_page'];
		} elseif ( isset( $_COOKIE['shop_per_page'] ) ) {
			$products_per_page = $_COOKIE['shop_per_page'];
		}

		return intval( $products_per_page );
	}
}

if ( ! function_exists( 'penci_products_per_page_action' ) ) {
	add_action( 'init', 'penci_products_per_page_action', 100 );
	function penci_products_per_page_action() {
		if ( isset( $_REQUEST['per_page'] ) && 1 != $_REQUEST['per_page'] && ! isset( $_REQUEST['_locale'] ) && ! isset( $_REQUEST['shortcode'] ) && apply_filters( 'penci_per_page_custom_expression', true ) ) {
			setcookie( 'shop_per_page', intval( $_REQUEST['per_page'] ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
		}
	}
}

if ( ! function_exists( 'penci_shop_view_action' ) ) {
	add_action( 'init', 'penci_shop_view_action', 100 );
	function penci_shop_view_action() {
		if ( isset( $_REQUEST['shop_view'] ) && ! isset( $_REQUEST['shortcode'] ) ) {
			setcookie( 'shop_view', $_REQUEST['shop_view'], 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
		}
		if ( isset( $_REQUEST['per_row'] ) && ! isset( $_REQUEST['shortcode'] ) ) {
			setcookie( 'shop_per_row', $_REQUEST['per_row'], 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
		}
	}
}

if ( ! function_exists( 'penci_get_products_columns_per_row' ) ) {
	function penci_get_products_columns_per_row() {
		if ( isset( $_REQUEST['per_row'] ) ) {
			return intval( $_REQUEST['per_row'] );
		} elseif ( isset( $_COOKIE['shop_per_row'] ) ) {
			return intval( $_COOKIE['shop_per_row'] );
		} else {
			return intval( get_theme_mod( 'penci_shop_product_columns', 3 ) );
		}
	}
}

if ( ! function_exists( 'penci_get_shop_view' ) ) {
	function penci_get_shop_view() {
		if ( isset( $_REQUEST['shop_view'] ) ) {
			$return = $_REQUEST['shop_view'];
		} elseif ( isset( $_COOKIE['shop_view'] ) ) {
			$return = $_COOKIE['shop_view'];
		} else {
			$shop_view = get_theme_mod( 'penci_shop_product_view', 'list-grid' );
			if ( $shop_view == 'grid_list' ) {
				$return = 'grid';
			} elseif ( $shop_view == 'list_grid' ) {
				$return = 'list';
			} else {
				$return = $shop_view;
			}
		}

		return $return;
	}
}

if ( ! function_exists( 'penci_wc_main_loop_prop' ) ) {
	function penci_wc_main_loop_prop() {

		wc_set_loop_prop( 'per_page', (int) penci_woo_get_products_per_page() );
		wc_set_loop_prop( 'columns', (int) penci_get_products_columns_per_row() );
		wc_set_loop_prop( 'product_loop_style', get_theme_mod( 'penci_woocommerce_product_style', 'style-1' ) );
		wc_set_loop_prop( 'products_view', penci_get_shop_view() );
		wc_set_loop_prop( 'mobile-columns', get_theme_mod( 'penci_shop_product_mobile_columns', 2 ) );
		wc_set_loop_prop( 'cat-loop-style', get_theme_mod( 'penci_woocommerce_product_cat_style', 'style-1' ) );
		wc_set_loop_prop( 'title-length', get_theme_mod( 'penci_woo_limit_product_title' ) );
		wc_set_loop_prop( 'loop_rating', get_theme_mod( 'penci_woocommerce_loop_rating', true ) );
		wc_set_loop_prop( 'loop_categories', get_theme_mod( 'penci_woocommerce_loop_category', true ) );
		wc_set_loop_prop( 'stock_progress_bar', get_theme_mod( 'penci_shop_stock_progress_bar', false ) );

		wc_set_loop_prop( 'product_loop_icon_style', get_theme_mod( 'penci_woocommerce_product_icon_hover_style', 'round' ) );
		wc_set_loop_prop( 'product_loop_icon_position', get_theme_mod( 'penci_woocommerce_product_icon_hover_position', 'top-left' ) );
		wc_set_loop_prop( 'product_loop_icon_animation', get_theme_mod( 'penci_woocommerce_product_icon_hover_animation', 'move-right' ) );
		wc_set_loop_prop( 'product_round_style', penci_shop_product_round_style( get_theme_mod( 'penci_woocommerce_product_icon_hover_style', 'round' ), get_theme_mod( 'penci_woocommerce_product_icon_hover_position', 'top-left' ) ) );


		if ( 'list' === penci_get_shop_view() ) {
			wc_set_loop_prop( 'columns', 1 );
		}
	}

	add_action( 'woocommerce_before_shop_loop', 'penci_wc_main_loop_prop', 10 );
	add_action( 'wp', 'penci_wc_main_loop_prop', 10 );
}

if ( ! function_exists( 'penci_woocommerce_pre_get_posts' ) ) {
	function penci_woocommerce_pre_get_posts( $query ) {

		if ( ! is_admin() && is_post_type_archive( 'product' ) && $query->is_main_query() ) {
			$query->set( 'posts_per_page', (int) penci_woo_get_products_per_page() );
		}
	}

	add_action( 'pre_get_posts', 'penci_woocommerce_pre_get_posts', 20 );
}

if ( ! function_exists( 'penci_woocommerce_get_product_loop_class' ) ) {
	function penci_woocommerce_get_product_loop_class() {
		$classes               = array();
		$product_display_style = wc_get_loop_prop( 'products_view', penci_get_shop_view() );
		$product_style         = wc_get_loop_prop( 'product_loop_style', get_theme_mod( 'penci_woocommerce_product_style', 'style-1' ) );
		$product_style         = $product_style ? $product_style : get_theme_mod( 'penci_woocommerce_product_style', 'style-1' );
		$product_loop_name     = wc_get_loop_prop( 'name' );

		$product_loop_icon_style     = wc_get_loop_prop( 'product_loop_icon_style' );
		$product_loop_icon_style     = $product_loop_icon_style ? $product_loop_icon_style : get_theme_mod( 'penci_woocommerce_product_icon_hover_style', 'round' );
		$product_loop_icon_position  = wc_get_loop_prop( 'product_loop_icon_position' );
		$product_loop_icon_position  = $product_loop_icon_position ? $product_loop_icon_position : get_theme_mod( 'penci_woocommerce_product_icon_hover_position', 'top-left' );
		$product_loop_icon_animation = wc_get_loop_prop( 'product_loop_icon_animation' );
		$product_loop_icon_animation = $product_loop_icon_animation ? $product_loop_icon_animation : get_theme_mod( 'penci_woocommerce_product_icon_hover_animation', 'move-right' );
		$product_loop_icon_alignment = penci_shop_product_round_style( $product_loop_icon_style, $product_loop_icon_position );


		$product_loop_name     = penci_is_mobile() ? 'mobile' : $product_loop_name;
		$default_product_style = get_theme_mod( 'penci_woocommerce_product_style', 'style-1' );
		$product_style         = 'list' == $product_display_style ? 'list' : $product_style;
		$product_style         = in_array( $product_loop_name, array(
			'up-sells',
			'cross-sells',
			'related',
			'wishlist',
		) ) ? $default_product_style : $product_style;

		$product_style = 'custom' == $product_loop_name && 'list' == $product_display_style ? $product_display_style : $product_style;

		$classes[] = 'standard' != $product_style ? 'penci-quickshop-support' : '';
		$classes[] = 'product-' . $product_style;


		$classes[] = 'icon-style-' . $product_loop_icon_style;
		$classes[] = 'icon-position-' . $product_loop_icon_position;
		$classes[] = 'icon-animation-' . $product_loop_icon_animation;
		$classes[] = 'icon-align-' . $product_loop_icon_alignment;


		return implode( ' ', $classes );
	}
}

if ( ! function_exists( 'penci_get_pages_option' ) ) {
	function penci_get_pages_option() {
		$pages    = array();
		$op_pages = get_pages();
		if ( $op_pages ) {
			foreach ( $op_pages as $page ) {
				$pages[ $page->ID ] = $page->post_title;
			}
		}

		return $pages;
	}
}

if ( ! function_exists( 'penci_sticky_cart' ) ) {
	add_action( 'woocommerce_after_single_product', 'penci_sticky_cart' );
	function penci_sticky_cart() {

		$custom_product_settings = get_post_meta( get_the_ID(), 'penci_pmeta_product_general', true );
		$penci_single_sticky_atc = isset( $custom_product_settings['penci_single_sticky_atc'] ) && $custom_product_settings['penci_single_sticky_atc'] ? $custom_product_settings['penci_single_sticky_atc'] : get_theme_mod( 'pencidesign_woo_single_sticky_add_to_cart', 'disable' );

		if ( 'disable' != $penci_single_sticky_atc ) {
			wc_get_template_part( 'single-product/sticky-cart' );
		}
	}
}

if ( ! function_exists( 'penci_single_product_tab' ) ) {
	add_filter( 'woocommerce_product_tabs', 'penci_single_product_tab' );
	function penci_single_product_tab( $tabs ) {
		global $product;
		$tab_content = get_post_meta( get_the_ID(), 'penci_pmeta_product_custom_tab', true );
		$title       = isset( $tab_content['tab_title'] ) ? $tab_content['tab_title'] : '';
		$priority    = isset( $tab_content['tab_priority'] ) && $tab_content['tab_priority'] ? $tab_content['tab_priority'] : 50;
		$content     = isset( $tab_content['tab_content'] ) ? $tab_content['tab_content'] : '';
		if ( $title && $content ) {
			$tabs['penci_user_tab'] = array(
				'title'    => $title,
				'priority' => $priority,
				'callback' => 'penci_single_product_tab_content',
			);
		}

		return $tabs;
	}
}

if ( ! function_exists( 'penci_single_product_tab_content' ) ) {
	function penci_single_product_tab_content() {
		global $product;
		$tab_content    = get_post_meta( get_the_ID(), 'penci_pmeta_product_custom_tab', true );
		$hidden_heading = isset( $tab_content['tab_title_visible'] ) ? $tab_content['tab_title_visible'] : '';
		$title          = isset( $tab_content['tab_title'] ) ? $tab_content['tab_title'] : '';
		$content        = isset( $tab_content['tab_content'] ) ? $tab_content['tab_content'] : '';
		?>
        <div class="penci-custom-user-content post-entry">
			<?php if ( ! $hidden_heading ) : ?>
                <h2><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<?php echo do_shortcode( wpautop( $content ) ); ?>
        </div>
		<?php
	}
}

/* Theme Mod Custom Tab */
if ( ! function_exists( 'penci_user_custom_product_tab' ) ) {
	if ( get_theme_mod( 'penci_woo_custom_tab', false ) ) {
		add_filter( 'woocommerce_product_tabs', 'penci_user_custom_product_tab' );
	}

	function penci_user_custom_product_tab( $tabs ) {
		$title    = get_theme_mod( 'penci_woo_custom_tab_title' );
		$priority = get_theme_mod( 'penci_woo_custom_tab_priority' );
		$content  = get_theme_mod( 'penci_woo_custom_tab_content' );
		if ( $title && $content ) {
			$tabs['penci_custom_tab'] = array(
				'title'    => $title,
				'priority' => $priority,
				'callback' => 'penci_user_custom_product_tab_content',
			);
		}

		return $tabs;
	}
}

if ( ! function_exists( 'penci_user_custom_product_tab_content' ) ) {
	function penci_user_custom_product_tab_content() {
		$title   = get_theme_mod( 'penci_woo_custom_tab_title' );
		$content = get_theme_mod( 'penci_woo_custom_tab_content' );
		?>
        <div class="penci-custom-user-content post-entry">
            <h2><?php echo esc_html( $title ); ?></h2>
			<?php echo do_shortcode( wpautop( $content ) ); ?>
        </div>
		<?php
	}
}

if ( ! function_exists( 'penci_single_product_custom_background' ) ) {
	add_action( 'soledad_theme/custom_css', 'penci_single_product_custom_background' );
	function penci_single_product_custom_background() {
		if ( is_singular( 'product' ) ) {
			$product_id         = get_the_ID();
			$product_custom_css = get_post_meta( $product_id, 'penci_pmeta_product_custom_css', true );
			if ( isset( $product_custom_css['product_custom_css'] ) && $product_custom_css['product_custom_css'] ) {
				echo $product_custom_css['product_custom_css'];
			}
			$product_background = get_post_meta( $product_id, 'penci_pmeta_product_background', true );
			$css_product_wapper = '';

			if ( isset( $product_background['product_wrap_bgcolor'] ) && $product_background['product_wrap_bgcolor'] ) {
				$css_product_wapper .= 'background-color:' . esc_attr( $product_background['product_wrap_bgcolor'] ) . ' !important;';
			}
			if ( isset( $product_background['product_wrap_bgimg'] ) && $product_background['product_wrap_bgimg'] ) {
				$bgimg              = wp_get_attachment_url( $product_background['product_wrap_bgimg'] );
				$css_product_wapper .= 'background-image: url(' . esc_url( $bgimg ) . ') !important;';
			}
			if ( isset( $product_background['product_wrap_bg_pos'] ) && $product_background['product_wrap_bg_pos'] ) {
				$css_product_wapper .= 'background-position:' . esc_attr( str_replace( '_', ' ', $product_background['product_wrap_bg_pos'] ) ) . ' !important;';
			}
			if ( isset( $product_background['product_wrap_bg_size'] ) && $product_background['product_wrap_bg_size'] ) {
				$css_product_wapper .= 'background-size:' . esc_attr( $product_background['product_wrap_bg_size'] ) . ' !important;';
			}
			if ( isset( $product_background['product_wrap_bg_repeat'] ) && $product_background['product_wrap_bg_repeat'] ) {
				$css_product_wapper .= 'background-repeat:' . esc_attr( $product_background['product_wrap_bg_repeat'] ) . ' !important;';
			}

			if ( $css_product_wapper ) {
				echo '#product-' . $product_id . ' .penci-single-product-top-container{' . $css_product_wapper . '}';
			}
		}
	}
}

if ( ! function_exists( 'penci_get_single_product_meta' ) ) {
	function penci_get_single_product_meta( $id, $group, $key, $default = null ) {
		$return       = $default;
		$product_id   = $id ? $id : get_the_ID();
		$product_meta = get_post_meta( $product_id, 'penci_pmeta_' . $group, true );
		if ( isset( $product_meta[ $key ] ) && $product_meta[ $key ] ) {
			$return = $product_meta[ $key ];
		}

		return $return;
	}
}

/**
 * Unhook the WooCommerce wrappers and add new Woocommerce wrappers
 *
 * @since 2.2
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'penci_woocommerce_custom_wrapper_start' ) ) {
	add_action( 'woocommerce_before_main_content', 'penci_woocommerce_custom_wrapper_start', 10 );
	function penci_woocommerce_custom_wrapper_start() {
		$sidebar_class      = '';
		$right_sidebar      = '';
		$container_class    = 'no-container';
		$penci_woo_class    = array();
		$sidebar_enable     = get_theme_mod( 'penci_woo_shop_enable_sidebar', false );
		$sidebar_cat_enable = get_theme_mod( 'penci_woo_cat_enable_sidebar', false );
		$left_sidebar       = get_theme_mod( 'penci_woo_left_sidebar', false );
		$right_sidebar      = $left_sidebar ? ' left-sidebar' : ' right-sidebar';
		$single_sidebar     = penci_woo_is_activate_sidebar();


		if ( is_shop() && $sidebar_enable ) {
			$sidebar_class = ' penci_sidebar';
		}

		if ( ( is_product_category() || is_product_tag() || penci_is_product_attribute_archive() ) && $sidebar_cat_enable ) {
			$sidebar_class = ' penci_sidebar';
		}

		if ( is_singular( 'product' ) && $single_sidebar ) {
			$sidebar_class = ' penci_sidebar';
			$right_sidebar = 'left' == $single_sidebar ? ' left-sidebar' : ' right-sidebar';
		}

		if ( ! is_singular( 'product' ) ) {
			$container_class = 'container';
		}

		if ( is_singular( 'product' ) ) {
			$penci_woo_class[] = 'sidebar-placement-' . penci_woo_sidebar_placement();
		}

		$penci_woo_class[] = get_theme_mod( 'penci_shop_product_pagination', 'pagination' );
		$penci_woo_class   = apply_filters( 'penci_woo_shop_class', $penci_woo_class );
		$penci_woo_class   = implode( ' ', $penci_woo_class );

		echo '<div class="' . $penci_woo_class . ' ' . $container_class . ' penci-woo-page-container' . $sidebar_class . $right_sidebar . '"><div id="main"><div class="theiaStickySidebar">';

		if ( is_singular( 'product' ) ) {
			do_action( 'penci_woo_before_single_product' );
		}
	}
}

if ( ! function_exists( 'penci_woocommerce_custom_wrapper_end' ) ) {
	add_action( 'woocommerce_after_main_content', 'penci_woocommerce_custom_wrapper_end', 10 );
	function penci_woocommerce_custom_wrapper_end() {
		echo '</div>';
		if ( penci_is_shop_archive() ) {
			wc_get_template_part( 'global/mobile-filter' );
		}
		echo '</div><!--end main-->';
	}
}

/**
 * Hook to change products per page in shop page & categories page
 *
 * @since 2.2
 */
if ( ! function_exists( 'penci_custom_products_per_page' ) ) {
	function penci_custom_products_per_page( $options = 24 ) {
		if ( get_theme_mod( 'penci_woo_post_per_page' ) ) {
			$options = absint( get_theme_mod( 'penci_woo_post_per_page' ) );
		}

		return $options;
	}

	add_filter( 'loop_shop_per_page', 'penci_custom_products_per_page', 10, 1 );
}

/**
 * WooCommerce Unhook sidebar
 *
 * @since 2.2
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

if ( ! function_exists( 'penci_woocommerce_add_sidebar_custom' ) && function_exists( 'is_shop' ) && function_exists( 'is_product_category' ) && function_exists( 'is_product_tag' ) && function_exists( 'is_product' ) ) {
	function penci_woocommerce_add_sidebar_custom() {
		if ( ( is_shop() && get_theme_mod( 'penci_woo_shop_enable_sidebar', false ) ) || ( ( is_product_category() || is_product_tag() || penci_is_product_attribute_archive() ) && get_theme_mod( 'penci_woo_cat_enable_sidebar', false ) ) || ( is_singular( 'product' ) && get_theme_mod( 'penci_woo_single_enable_sidebar', false ) ) ) :
			add_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
		endif;
	}

	add_action( 'template_redirect', 'penci_woocommerce_add_sidebar_custom' );
}

if ( ! function_exists( 'penci_woo_is_activate_sidebar' ) ) {
	function penci_woo_is_activate_sidebar() {

		$sidebar_positon = 'right';
		if ( get_theme_mod( 'penci_woo_left_sidebar', false ) ) {
			$sidebar_positon = 'left';
		}
		$sidebar_enable         = get_theme_mod( 'penci_woo_single_enable_sidebar', false );
		$sidebar_positon        = $sidebar_enable ? $sidebar_positon : '';
		$single_sidebar_positon = penci_get_single_product_meta( get_the_ID(), 'product_sidebar', 'sidebar_position', $sidebar_positon );

		if ( $sidebar_enable || 'disable' != $single_sidebar_positon ) {
			return $single_sidebar_positon;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'penci_woo_sidebar_placement' ) ) {
	function penci_woo_sidebar_placement() {
		if ( is_singular( 'product' ) ) {
			$sidebar_placement = penci_get_single_product_meta( get_the_ID(), 'product_sidebar', 'sidebar_placement', get_theme_mod( 'penci_woo_single_sidebar_style', 'bottom' ) );
			$sidebar_placement = penci_woo_is_activate_sidebar() ? $sidebar_placement : 'no-active-sidebar';

			return $sidebar_placement;
		}
	}
}

if ( ! function_exists( 'penci_single_product_custom_options' ) ) {
	add_action( 'woocommerce_before_single_product', 'penci_single_product_custom_options' );
	function penci_single_product_custom_options() {

		$quickview = wc_get_loop_prop( 'quickview', false );

		if ( penci_get_single_product_meta( get_the_ID(), 'product_extra_options', 'hide_related_products' ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}

		$product_style = penci_get_single_product_meta( get_the_ID(), 'product_general', 'penci_single_product_style', get_theme_mod( 'penci_single_product_style' ) );

		if ( 'accordion-content' == $product_style && ! $quickview ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 35 );
		}

		if ( penci_woo_is_activate_sidebar() ) {
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
			add_action( 'penci_single_product_sidebar', 'woocommerce_get_sidebar', 10 );
		}

	}
}

if ( ! function_exists( 'penci_woo_sidebar_content' ) ) {
	function penci_woo_sidebar_content() {
		if ( penci_woo_is_activate_sidebar() && function_exists( 'woocommerce_get_sidebar' ) ) {
			woocommerce_get_sidebar();
		}
	}
}

if ( ! function_exists( 'penci_woo_infinit_load_button' ) ) {
	add_action( 'woocommerce_after_shop_loop', 'penci_woo_infinit_load_button', 5 );
	function penci_woo_infinit_load_button( $shortcode = '', $page = null ) {
		global $wp_query;
		$pages       = $shortcode ? $page : $wp_query->max_num_pages;
		$load_option = $shortcode ? $shortcode : get_theme_mod( 'penci_shop_product_pagination', 'pagination' );
		if ( 'pagination' != $load_option ) {
			?>
            <div class="page-load-status">
                <div class="loader-ellips infinite-scroll-request">
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                </div>
				<?php if ( 'infinit' == $load_option ) : ?>
                    <div class="infinite-scroll-request">
                        <div class="page-load-button penci-pagination penci-ajax-more">
                            <a class="penci-ajax-more-button view-more-button button loading-posts" href="#">
                                <span class="ajax-more-text"><?php echo penci_woo_translate_text( 'penci_woo_trans_loading' ); ?></span>
                                <span class="ajaxdot"></span>
                                <i class="penci-faicon fa fa-refresh"></i>
                            </a>
                        </div>
                    </div>
				<?php endif; ?>
            </div>
			<?php
		}
		if ( 'loadmore' == $load_option && $pages > 1 ) {
			?>
            <div class="page-load-button penci-pagination penci-ajax-more">
                <a class="penci-ajax-more-button view-more-button button" href="#">
                    <span class="ajax-more-text"><?php echo penci_woo_translate_text( 'penci_woo_trans_vmproduct' ); ?></span>
                    <span class="ajaxdot"></span>
                    <i class="penci-faicon fa fa-refresh"></i>
                </a>
            </div>
			<?php
		}
	}
}
if ( ! function_exists( 'penci_template_loop_category_title' ) ) {
	remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
	add_action( 'woocommerce_shop_loop_subcategory_title', 'penci_template_loop_category_title', 10 );
	function penci_template_loop_category_title( $category ) {
		?>
        <div class="woocommerce-loop-category__wrapper">
            <h2 class="woocommerce-loop-category__title">
				<?php
				echo esc_html( $category->name );
				?>
            </h2>
			<?php
			if ( $category->count > 0 ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo apply_filters( 'woocommerce_subcategory_count_html', ' <span class="count">' . esc_html( $category->count ) . ' ' . penci_woo_translate_text( 'penci_woo_trans_products' ) . '</span>', $category );
			}
			?>
        </div>
		<?php
	}
}

if ( ! function_exists( 'penci_subcategory_get_first_product_image' ) ) {
	function penci_subcategory_get_first_product_image( $category_id ) {
		$product_img_id    = '';
		$placeholder_image = get_option( 'woocommerce_placeholder_image', 0 );
		$arg               = array(
			'post_type'      => 'product',
			'posts_per_page' => 1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'terms'    => $category_id,
				),
			),
			'meta_query'     => array(
				array(
					'key'     => '_thumbnail_id',
					'value'   => $placeholder_image,
					'compare' => '!=',
				),
			),
		);
		$product_img       = get_posts( $arg );
		if ( $product_img ) {
			$product_img_id = get_post_thumbnail_id( $product_img[0]->ID );
		}

		return $product_img_id;
	}
}

if ( ! function_exists( 'penci_subcategory_thumbnail' ) ) {
	remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
	add_action( 'woocommerce_before_subcategory_title', 'penci_subcategory_thumbnail', 10 );
	function penci_subcategory_thumbnail( $category ) {
		$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
		$dimensions           = wc_get_image_size( $small_thumbnail_size );
		$thumbnail_id         = get_term_meta( $category->term_id, 'thumbnail_id', true );
		$thumbnail_id         = $thumbnail_id ? $thumbnail_id : penci_subcategory_get_first_product_image( $category->term_id );

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size );

			if ( isset( $image[0] ) && $image[0] ) {
				$image        = $image[0];
				$image_srcset = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $thumbnail_id, $small_thumbnail_size ) : false;
				$image_sizes  = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $thumbnail_id, $small_thumbnail_size ) : false;
			} else {
				$image        = wc_placeholder_img_src();
				$image_srcset = false;
				$image_sizes  = false;
			}
		}

		if ( $image ) {
			// Prevent esc_url from breaking spaces in urls for image embeds.
			// Ref: https://core.trac.wordpress.org/ticket/23605.
			$image = str_replace( ' ', '%20', $image );

			// Add responsive image markup if available.
			if ( $image_srcset && $image_sizes ) {
				echo '<div class="product-category-img"><img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" srcset="' . esc_attr( $image_srcset ) . '" sizes="' . esc_attr( $image_sizes ) . '" /></div>';
			} else {
				echo '<div class="product-category-img"><img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" /></div>';
			}
		}
	}
}

if ( ! function_exists( 'penci_subcategory_extract_img' ) ) {
	function penci_subcategory_extract_img( $category ) {
		$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
		$dimensions           = wc_get_image_size( $small_thumbnail_size );
		$thumbnail_id         = get_term_meta( $category->term_id, 'thumbnail_id', true );
		$thumbnail_id         = $thumbnail_id ? $thumbnail_id : penci_subcategory_get_first_product_image( $category->term_id );

		return $thumbnail_id;
	}
}

add_filter( 'product_cat_class', 'penci_custom_product_cat_class' );
function penci_custom_product_cat_class( $classes ) {
	$style   = wc_get_loop_prop( 'cat-loop-style' );
	$index   = wc_get_loop_prop( 'loop' );
	$loop_by = wc_get_loop_prop( 'loop_by' );

	if ( $index && $loop_by ) {
		$loop_class = $index % $loop_by;
		$loop_class = $loop_class ? $loop_class : $loop_by;
		$classes[]  = 'penci-cat-item-' . $loop_class;
	}

	$classes[] = 'penci-product-cat-' . $style;

	return $classes;
}

if ( ! function_exists( 'penci_woocommerce_get_customizer_font' ) ) {
	function penci_woocommerce_get_customizer_font( $setting ) {
		$font_family_menu     = get_theme_mod( $setting );
		$font_family_menu_end = $font_family_menu;
		if ( ! array_key_exists( $font_family_menu, penci_font_browser() ) ) {
			$font_family_menu = str_replace( '"', '', $font_family_menu );
			$font_menu_explo  = explode( ', ', $font_family_menu );
			$font_menu        = isset( $font_menu_explo[0] ) ? $font_menu_explo[0] : '';
			$font_menu_serif  = isset( $font_menu_explo[2] ) ? $font_menu_explo[2] : '';
			$space_menu_end   = ', ';
			if ( empty( $font_menu_serif ) ) :
				$space_menu_end = '';
			endif;
			$font_family_menu_end = "'" . $font_menu . "'" . $space_menu_end . $font_menu_serif;
		}

		return $font_family_menu_end;
	}
}
if ( ! function_exists( 'penci_woo_customizer_custom_css_rules' ) ) {
	function penci_woo_customizer_custom_css_rules( $settings, $woocommerce_default_color = array(), $font_family_settings = array(), $defaut_font_size = array(), $echo = true ) {
		$return = array();
		$out    = '';
		foreach ( $settings as $prop => $value ) {
			$prop_value = get_theme_mod( $value );

			$customize_accent_color = get_theme_mod( 'penci_color_accent' );

			$body_fontsize = get_theme_mod( 'penci_font_for_size_body' );
			if ( in_array( $prop, $defaut_font_size ) && ! $prop_value && '14' != $body_fontsize ) {
				$prop_value = $body_fontsize;
			}

			if ( $font_family_settings && in_array( $value, $font_family_settings ) ) {
				$prop_value = penci_woocommerce_get_customizer_font( $value );
			}

			if ( $woocommerce_default_color && in_array( $value, $woocommerce_default_color ) ) {
				$prop_value = ( empty( $prop_value ) && $customize_accent_color ) ? $customize_accent_color : $prop_value;
			}

			// default int is font size
			$unit       = 'px';
			$unit       = 'penci_sidebar_width' == $value ? '%' : $unit;
			$prop_value = is_numeric( $prop_value ) && $prop_value > 0 ? $prop_value . $unit : $prop_value;

			if ( $prop_value ) {
				$return[] = $prop . ': ' . $prop_value;
			}
		}

		if ( ! empty( $return ) && isset( $return ) ) {
			$out = 'body{' . implode( ';', $return ) . '}';
		}

		if ( $echo ) {
			echo $out;
		} else {
			return $out;
		}
	}
}

if ( ! function_exists( 'penci_woo_customizer_custom_css_prop' ) ) {
	function penci_woo_customizer_custom_css_prop( $settings, $mobile = false ) {
		$out = $before = $after = '';
		foreach ( $settings as $prop => $rules ) {
			$value = get_theme_mod( $prop );
			if ( $value ) {
				foreach ( $rules as $rule => $selector ) {
					$extra = $rule == 'font-size' ? 'px' : '';
					$out   .= $selector . '{' . $rule . ':' . $value . $extra . '}';
				}
			}
		}

		if ( $mobile ) {
			$before = '@media only screen and (max-width:767px){';
			$after  = '}';
		}

		return $before . $out . $after;
	}
}

if ( ! function_exists( 'penci_woo_hex2rgb' ) ) {
	function penci_woo_hex2rgb( $color, $opacity = null ) {

		$out = '';

		list( $r, $g, $b ) = sscanf( $color, "#%02x%02x%02x" );

		if ( $opacity ) {
			$out = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $opacity . ')';
		}

		return $out;
	}
}

if ( ! function_exists( 'penci_woocommerce_custom_style' ) ) {
	add_action( 'soledad_theme/custom_css', 'penci_woocommerce_custom_style' );
	function penci_woocommerce_custom_style() {
		$product_customize_settings = array(
			'--pccat_tt_cl'                 => 'penci_woo_product_loop_title_color',
			'--pccat_price_cl'              => 'penci_woo_product_loop_price_color',
			'--pccat_cat_cl'                => 'penci_woo_product_loop_cat_color',
			'--pccat_cat_hv_cl'             => 'penci_woo_product_loop_cat_hover_color',
			'--pccat_btn_groups_bgcl'       => 'penci_woo_product_loop_button_groups_bgcolor',
			'--pccat_btn_cl'                => 'penci_woo_product_loop_button_color',
			'--pccat_progress_bg_cl'        => 'penci_woo_product_loop_progress_bg_color',
			'--pccat_progress_active_bg_cl' => 'penci_woo_product_loop_progress_active_bg_color',
			'--pccat_progress_height'       => 'penci_woo_product_loop_progress_height',
			'--pcpage_gtext_cl'             => 'penci_product_page_general_text_color',
			'--pcpage_glink_cl'             => 'penci_product_page_general_link_color',
			'--pcpage_glink_hv_cl'          => 'penci_product_page_general_link_hover_color',
			'--pcpage_gbdr_cl'              => 'penci_product_page_general_border_color',
			'--pcpage_btns_bdr_cl'          => 'penci_product_page_button_swatches_border_color',
			'--pcpage_btns_bdr_hv_cl'       => 'penci_product_page_button_swatches_border_hover_color',
			'--pcpage_btns_bg_cl'           => 'penci_product_page_button_swatches_bg_color',
			'--pcpage_btns_bg_hv_cl'        => 'penci_product_page_button_swatches_bg_hover_color',
			'--pcpage_btn_atc_bg_cl'        => 'penci_product_page_button_atc_bg_color',
			'--pcpage_btn_atc_bg_hv_cl'     => 'penci_product_page_button_atc_bg_hover_color',
			'--pcpage_meta_cl'              => 'penci_product_page_meta_color',
			'--pcpage_meta_link_cl'         => 'penci_product_page_meta_link_color',
			'--pcpage_meta_link_hv_cl'      => 'penci_product_page_meta_link_hover_color',
			'--pcpage_tab_tt_cl'            => 'penci_product_page_tab_title_color',
			'--pcpage_tab_tt_active_cl'     => 'penci_product_page_tab_title_active_color',
			'--pcwp_tt_cl'                  => 'penci_woo_page_title_color',
			'--pcwp_btn_bg_cl'              => 'penci_woo_page_button_bg_color',
			'--pcwp_btn_bg_hv_cl'           => 'penci_woo_page_button_bg_hover_color',
			'--pcwp_btn_alt_bg_cl'          => 'penci_woo_page_button_alt_bg_color',
			'--pcwp_btn_alt_bg_hv_cl'       => 'penci_woo_page_button_alt_bg_hover_color',
			'--pcsl_tt_fs'                  => 'pencidesign_woo_product_loop_title_font_size',
			'--pcsl_tt_m_fs'                => 'pencidesign_woo_product_loop_title_m_font_size',
			'--pcsl_tt_l_fs'                => 'pencidesign_woo_product_loop_list_title_font_size',
			'--pcsl_tt_l_m_fs'              => 'pencidesign_woo_product_loop_list_title_m_font_size',
			'--pcsl_meta_fs'                => 'pencidesign_woo_product_loop_meta_font_size',
			'--pcsl_meta_m_fs'              => 'pencidesign_woo_product_loop_meta_m_font_size',
			'--pcsl_price_fs'               => 'pencidesign_woo_product_loop_price_font_size',
			'--pcsl_price_m_fs'             => 'pencidesign_woo_product_loop_price_m_font_size',
			'--pcsl_btn_icon_size'          => 'pencidesign_woo_product_loop_button_icon_size',
			'--pcs_fsp_single_m_tt'         => 'pencidesign_woo_fontsize_product_single_m_title',
			'--pcs_fsp_single_tt'           => 'pencidesign_woo_fontsize_product_single_title',
			'--pcs_fsp_price'               => 'pencidesign_woo_fontsize_product_price',
			'--pcs_fsp_m_price'             => 'pencidesign_woo_fontsize_m_product_price',
			'--pcs_fsp_breadcrumb'          => 'pencidesign_woo_fontsize_product_breadcrumb',
			'--pcs_fsp_m_breadcrumb'        => 'pencidesign_woo_fontsize_m_product_breadcrumb',
			'--pcs_fsp_general'             => 'pencidesign_woo_fontsize_product_general',
			'--pcs_fsp_tab_tt'              => 'pencidesign_woo_fontsize_product_tab_title',
			'--pcs_fsp_tab_m_tt'            => 'pencidesign_woo_fontsize_product_tab_m_title',
			'--pcs_fsp_meta'                => 'pencidesign_woo_fontsize_product_meta',
			'--pcs_fsp_m_meta'              => 'pencidesign_woo_fontsize_m_product_meta',
			'--pcaccent-cl'                 => 'penci_color_accent',
			'--pclabel_hot'                 => 'penci_woo_label_hot_color',
			'--pclabel_new'                 => 'penci_woo_label_new_color',
			'--pclabel_sale'                => 'penci_woo_label_sale_color',
			'--pclabel_outstock'            => 'penci_woo_label_outstock_color',
			'--pclabel_size'                => 'pencidesign_woo_product_loop_label_size',
			'--pclabel_m_size'              => 'pencidesign_woo_product_loop_label_m_size',

			'--pcsl_tt_btn3_size'          => 'pencidesign_woo_product_loop_button_3_size',
			'--pcsl_tt_btn3_m_size'        => 'pencidesign_woo_product_loop_button_3_m_size',
			'--pcsl_tt_btn4_size'          => 'pencidesign_woo_product_loop_button_4_size',
			'--pcsl_tt_btn4_m_size'        => 'pencidesign_woo_product_loop_button_4_m_size',
			'--pcsl_tt_btn5_size'          => 'pencidesign_woo_product_loop_button_5_size',
			'--pcsl_tt_btn5_m_size'        => 'pencidesign_woo_product_loop_button_5_m_size',
			'--pcsl_tt_lb_size'            => 'pencidesign_woo_fontsize_product_label',
			'--pcsl_tt_lb_m_size'          => 'pencidesign_woo_fontsize_m_product_label',

			// overlay settings
			'--pcl_o_bg'                   => 'penci_woo_product_overlay_bg_color',
			'--pcl_o_opacity'              => 'penci_woo_product_overlay_opacity',
			'--pcl_o_tt_cl'                => 'penci_woo_product_overlay_title_color',
			'--pcl_o_l_cl'                 => 'penci_woo_product_overlay_link_color',
			'--pcl_o_lhv_cl'               => 'penci_woo_product_overlay_link_hover_color',
			'--pcl_o_btn_cl'               => 'penci_woo_product_overlay_button_color',

			// styl3
			'--pcl_3_atc_bg_cl'            => 'penci_woo_product_item_style3_atc_bg_color',
			'--pcl_3_atc_bg_hv_cl'         => 'penci_woo_product_item_style3_atc_bg_hover_color',
			'--pcl_3_atc_txt_cl'           => 'penci_woo_product_item_style3_atc_text_color',
			'--pcl_3_atc_txt_hv_cl'        => 'penci_woo_product_item_style3_atc_text_hover_color',

			// style6
			'--pcl_6_price_cl'             => 'penci_woo_product_item_style6_price_color',
			'--pcl_6_l_cl'                 => 'penci_woo_product_item_style6_link_color',
			'--pcl_6_lhv_cl'               => 'penci_woo_product_item_style6_link_hover_color',
			'--pcl_6_tt_cl'                => 'penci_woo_product_item_style6_title_color',
			'--pcl_6_txt_cl'               => 'penci_woo_product_item_style6_text_color',
			'--pcl_6_bg_cl'                => 'penci_woo_product_item_style6_bg',

			// style4
			'--pcl_4_btn_txt_cl'           => 'penci_woo_product_item_style4_atc_txt_color',
			'--pcl_4_btn_txt_hv_cl'        => 'penci_woo_product_item_style4_atc_hv_txt_color',
			'--pcl_4_btn_bg_cl'            => 'penci_woo_product_item_style4_atc_bg_color',
			'--pcl_4_btn_bg_hv_cl'         => 'penci_woo_product_item_style4_atc_hv_bg_color',

			// style5
			'--pcl_5_btn_txt_cl'           => 'penci_woo_product_item_style5_atc_text_color',
			'--pcl_5_btn_txt_hv_cl'        => 'penci_woo_product_item_style5_atc_hv_text_color',
			'--pcl_5_btn_bd_cl'            => 'penci_woo_product_item_style5_atc_border_color',
			'--pcl_5_btn_bd_hv_cl'         => 'penci_woo_product_item_style5_atc_hv_border_color',
			'--pcl_5_btn_bg_cl'            => 'penci_woo_product_item_style5_atc_bg_color',
			'--pcl_5_btn_bg_hv_cl'         => 'penci_woo_product_item_style5_atc_hv_bg_color',


			// button group
			'--pcl_btn_group_bg_color'     => 'penci_woo_product_loop_button_bg_color',
			'--pcl_btn_group_bg_hv_color'  => 'penci_woo_product_loop_button_bg_hover_color',
			'--pcl_btn_group_txt_color'    => 'penci_woo_product_loop_button_color',
			'--pcl_btn_group_txt_hv_color' => 'penci_woo_product_loop_button_hover_color',


			// product category loop
			'--pcl_l_cat_fs'               => 'penci_woo_loop_meta_font_size',
			'--pcl_l_cat_fs_m'             => 'penci_woo_loop_meta_font_size_m',
			'--pcl_l_cat_tt_fs'            => 'penci_woo_loop_cat_font_size',
			'--pcl_l_cat_tt_fs_m'          => 'penci_woo_loop_cat_font_size_m',
			'--pcl_l_cat_cl'               => 'penci_woo_section_product_cat_loop_meta_color',
			'--pcl_l_cat_tt_cl'            => 'penci_woo_section_product_cat_loop_title_color',
			'--pcl_l_cat_o_cl'             => 'penci_woo_section_product_cat_loop_overlay_color',

			// theme settings
			'--pc-sidebar-w'               => 'penci_sidebar_width',
			'--pc-sf-sum-w'                => 'penci_single_product_summary_width',

			// widget extra
			'--pc-w-mh'                    => 'penci_woo_widgets_scroll_height',
			'--pc-w-mhm'                   => 'penci_woo_widgets_scroll_m_height',
		);

		$cart_page_props = array(
			'penci_woo_cart_breadcrumb_color'                => array(
				'color' => '.woocommerce .penci_woo_pages_breadcrumbs ul li.active span, .woocommerce .penci_woo_pages_breadcrumbs ul li.active a',
			),
			'penci_woo_cart_breadcrumb_active_color'         => array(
				'color' => '.woocommerce .penci_woo_pages_breadcrumbs ul li span,.woocommerce .penci_woo_pages_breadcrumbs ul li a',
			),
			'penci_woo_cart_tablehead_color'                 => array(
				'color' => '.woocommerce table.shop_table th',
			),
			'penci_woo_cart_table_border_color'              => array(
				'border-color' => '.woocommerce table.shop_table th,.woocommerce table.shop_table td,.post-entry td, .post-entry th',
			),
			'penci_woo_cart_table_txt_color'                 => array(),
			'penci_woo_cart_table_product_title_color'       => array(
				'color' => '.woocommerce table.shop_table td.product-name a',
			),
			'penci_woo_cart_table_product_title_hover_color' => array(
				'color' => '.woocommerce table.shop_table td.product-name a:hover',
			),
			'penci_woo_cart_table_product_price_color'       => array(
				'color' => '.woocommerce table.shop_table td.product-subtotal span',
			),
			'penci_woo_cart_btn_bg_color'                    => array(
				'background-color' => '.woocommerce-cart .wc-proceed-to-checkout a',
			),
			'penci_woo_cart_btn_txt_color'                   => array(
				'color' => '.woocommerce-cart .wc-proceed-to-checkout a',
			),
			'penci_woo_cart_btn_hover_bg_color'              => array(
				'background-color' => '.woocommerce-cart .wc-proceed-to-checkout a:hover',
			),
			'penci_woo_cart_btn_hover_txt_color'             => array(
				'color' => '.woocommerce-cart .wc-proceed-to-checkout a:hover',
			),
			'penci_woo_cart_sbtn_bg_color'                   => array(
				'background-color' => '.woocommerce .woocommerce-cart-form .cart .button[name="apply_coupon"], .woocommerce .woocommerce-cart-form .cart button.button, .woocommerce .woocommerce-cart-form .cart button.button:disabled, .woocommerce .woocommerce-cart-form .cart button.button:disabled[disabled]',
			),
			'penci_woo_cart_sbtn_txt_color'                  => array(
				'color' => '.woocommerce .woocommerce-cart-form .cart .button[name="apply_coupon"], .woocommerce .woocommerce-cart-form .cart button.button, .woocommerce .woocommerce-cart-form .cart button.button:disabled, .woocommerce .woocommerce-cart-form .cart button.button:disabled[disabled]',
			),
			'penci_woo_cart_sbtn_hover_bg_color'             => array(
				'background-color' => '.woocommerce .woocommerce-cart-form .cart .button[name="apply_coupon"]:hover, .woocommerce .woocommerce-cart-form .cart button.button:hover, .woocommerce .woocommerce-cart-form .cart button.button:disabled:hover, .woocommerce .woocommerce-cart-form .cart button.button:disabled[disabled]:hover',
			),
			'penci_woo_cart_sbtn_hover_txt_color'            => array(
				'color' => '.woocommerce .woocommerce-cart-form .cart .button[name="apply_coupon"]:hover, .woocommerce .woocommerce-cart-form .cart button.button:hover, .woocommerce .woocommerce-cart-form .cart button.button:disabled:hover, .woocommerce .woocommerce-cart-form .cart button.button:disabled[disabled]:hover',
			),
			'penci_woo_cart_del_btn_color'                   => array(
				'color' => '.woocommerce table.shop_table a.remove',
			),
			'penci_woo_cart_del_btn_hv_color'                => array(
				'color' => '.woocommerce table.shop_table a.remove:hover',
			),
		);

		$checkout_page_prop = array(
			'penci_woo_checkout_breadcrumb_color'         => array(
				'color' => '.woocommerce .penci_woo_pages_breadcrumbs ul li span, .woocommerce .penci_woo_pages_breadcrumbs ul li a',
			),
			'penci_woo_checkout_breadcrumb_active_color'  => array(
				'color' => '.woocommerce .penci_woo_pages_breadcrumbs ul li.active span, .woocommerce .penci_woo_pages_breadcrumbs ul li.active a',
			),
			'penci_woo_checkout_form_label_color'         => array(
				'color' => 'body.woocommerce-checkout form.checkout.woocommerce-checkout label',
			),
			'penci_woo_checkout_form_border_color'        => array(
				'border-color' => '.woocommerce form .form-row .input-text, .woocommerce-page form .form-row .input-text,.select2-dropdown, .select2-container--default .select2-selection--single',
			),
			'penci_woo_checkout_form_border_focus_color'  => array(
				'border-color' => '.woocommerce form .form-row .input-text:focus, .woocommerce-page form .form-row .input-text:focus',
			),
			'penci_woo_checkout_form_bg_color'            => array(
				'background-color' => '.woocommerce form .form-row .input-text, .woocommerce-page form .form-row .input-text',
			),
			'penci_woo_checkout_form_bg_focus_color'      => array(
				'background-color' => '.woocommerce form .form-row .input-text:focus, .woocommerce-page form .form-row .input-text:focus',
			),
			'penci_woo_checkout_order_bg_color'           => array(
				'background-color' => 'body.woocommerce-checkout form.checkout.woocommerce-checkout .woocommerce-checkout-review-order-inner',
			),
			'penci_woo_checkout_order_table_bg_color'     => array(
				'background-color' => 'body.woocommerce-checkout form.checkout.woocommerce-checkout .woocommerce-checkout-review-order-inner table, body.woocommerce-checkout form.checkout.woocommerce-checkout .woocommerce-checkout-review-order-inner .woocommerce-checkout-payment',
			),
			'penci_woo_checkout_order_table_color'        => array(
				'color' => 'body.woocommerce-checkout form.checkout.woocommerce-checkout .woocommerce-checkout-review-order-inner table, body.woocommerce-checkout form.checkout.woocommerce-checkout .woocommerce-checkout-review-order-inner .woocommerce-checkout-payment',
			),
			'penci_woo_checkout_order_head_color'         => array(
				'color' => '.woocommerce table.shop_table th',
			),
			'penci_woo_checkout_order_accent_color'       => array(
				'color' => '.woocommerce form.checkout table.shop_table .order-total .amount',
			),
			'penci_woo_checkout_order_table_border_color' => array(
				'border-color' => '.woocommerce-checkout #payment ul.payment_methods,.woocommerce table.shop_table th,.woocommerce table.shop_table tr',
			),
			'penci_woo_checkout_btn_bg_color'             => array(
				'background-color' => '.woocommerce button.button.alt',
			),
			'penci_woo_checkout_btn_txt_color'            => array(
				'color' => '.woocommerce button.button.alt,body.woocommerce-checkout form.checkout.woocommerce-checkout button',
			),
			'penci_woo_checkout_btn_hover_bg_color'       => array(
				'background-color' => '.woocommerce button.button.alt:hover,body.woocommerce-checkout form.checkout.woocommerce-checkout button:hover',
			),
			'penci_woo_checkout_btn_hover_txt_color'      => array(
				'color' => '.woocommerce button.button.alt:hover,body.woocommerce-checkout form.checkout.woocommerce-checkout button:hover',
			),
			'penci_woo_checkout_review_order_bg_color'    => array(
				'background-color' => 'body.woocommerce-checkout form.checkout.woocommerce-checkout .woocommerce-checkout-review-order-inner',
			)
		);

		$completed_page_prop = array(
			'penci_woo_checkout_txt_color'             => array(
				'color' => '.woocommerce-order',
			),
			'penci_woo_checkout_head_color'            => array(
				'color' => '.post-entry h2,.woocommerce ul.order_details li strong,.woocommerce table.shop_table th,.woocommerce table.shop_table td.product-name a',
			),
			'penci_woo_checkout_border_color'          => array(
				'border-color' => '.woocommerce table.shop_table tfoot td,.woocommerce table.shop_table tfoot th, body.woocommerce-order-received .woocommerce-order section,.woocommerce table.shop_table th,.woocommerce table.shop_table td',
			),
			'penci_woo_checkout_success_icon_color'    => array(
				'color' => 'body.woocommerce-order-received .woocommerce-order .woocommerce-notice:before',
			),
			'penci_woo_checkout_success_icon_bg_color' => array(
				'background-color' => 'body.woocommerce-order-received .woocommerce-order .woocommerce-notice:before',
			),
		);

		$sidecar_props = array(
			'penci_woo_section_sidebarcart_color'          => array(
				'color' => '.sidebar-cart-container',
			),
			'penci_woo_sidecart_bg_color'                  => array(
				'background-color' => '.penci-sidebar-cart',
			),
			'penci_woo_sidecart_heading_bg_color'          => array(
				'background-color' => '.penci-sidebar-cart .sidecart-heading',
			),
			'penci_woo_sidecart_heading_txt_color'         => array(
				'color' => '.penci-sidebar-cart .sidecart-heading h3',
			),
			'penci_woo_sidecart_product_title_color'       => array(
				'color' => '.woocommerce .sidebar-cart-container ul.cart_list li a, .woocommerce .sidebar-cart-container ul.product_list_widget li a',
			),
			'penci_woo_sidecart_product_title_hover_color' => array(
				'color' => '.woocommerce .sidebar-cart-container ul.cart_list li a:hover, .woocommerce .sidebar-cart-container ul.product_list_widget li a:hover',
			),
			'penci_woo_sidecart_border_color'              => array(
				'border-color' => '.woocommerce .sidebar-cart-container ul.cart_list li, .woocommerce .sidebar-cart-container ul.product_list_widget li',
			),
			'penci_woo_sidecart_price_color'               => array(
				'color' => '.woocommerce .sidebar-cart-container ul.cart_list li .amount, .woocommerce .sidebar-cart-container ul.product_list_widget li .amount',
			),
			'penci_woo_sidecart_pitem_bg_color'            => array(
				'background-color' => '.penci-sidebar-cart .sidecart-content ul li',
			),
			'penci_woo_sidecart_pitem_bg_hover_color'      => array(
				'background-color' => '.penci-sidebar-cart .sidecart-content ul li:hover',
			),
			'penci_woo_sidecart_pitem_bg_text_color'       => array(
				'color' => '.penci-sidebar-cart .sidecart-content ul li:hover,.woocommerce .sidecart-content ul.cart_list li a, .woocommerce .sidecart-content ul.product_list_widget li a',
			),
			'penci_woo_sidecart_accent_color'              => array(
				'color' => '.penci-sidebar-cart .woocommerce-mini-cart__total.total .amount',
			),
			'penci_woo_sidecart_heading_color'             => array(
				'color' => '.penci-sidebar-cart strong',
			),
			'penci_woo_sidecart_btn_color'                 => array(
				'background-color' => '.penci-sidebar-cart .woocommerce-mini-cart__buttons.buttons .button',
			),
			'penci_woo_sidecart_btn_text_color'            => array(
				'color' => '.penci-sidebar-cart .woocommerce-mini-cart__buttons.buttons .button',
			),
			'penci_woo_sidecart_btn_hover_color'           => array(
				'background-color' => '.penci-sidebar-cart .woocommerce-mini-cart__buttons.buttons .button:hover',
			),
			'penci_woo_sidecart_btn_text_hover_color'      => array(
				'color' => '.penci-sidebar-cart .woocommerce-mini-cart__buttons.buttons .button:hover',
			),
			'penci_woo_sidecart_footer_bgcolor'            => array(
				'background-color' => '.penci-sidebar-cart .woocommerce-mini-cart__total.total,.penci-sidebar-cart .woocommerce-mini-cart__buttons.buttons',
			),
			'penci_woo_notice_bg_color'                    => array(
				'background-color' => 'p.demo_store, .woocommerce-store-notice'
			),
			'penci_woo_notice_txt_color'                   => array(
				'color' => 'p.demo_store, .woocommerce-store-notice,p.demo_store a, .woocommerce-store-notice a'
			),
		);

		$extra_font_size = array(
			'pencidesign_woo_fontsize_pages_nav_font_size'          => array(
				'font-size' => '.woocommerce .penci_woo_pages_breadcrumbs ul li span, .woocommerce .penci_woo_pages_breadcrumbs ul li a',
			),
			'pencidesign_woo_fontsize_pages_table_th'               => array(
				'font-size' => '.woocommerce table.shop_table th',
			),
			'pencidesign_woo_fontsize_pages_table_product_title'    => array(
				'font-size' => '.woocommerce table.shop_table td.product-name a',
			),
			'pencidesign_woo_fontsize_pages_table_product_price'    => array(
				'font-size' => '.woocommerce table.shop_table td.product-price span, .woocommerce table.shop_table td.product-subtotal span',
			),
			'pencidesign_woo_fontsize_pages_table_product_subtotal' => array(
				'font-size' => '.woocommerce table.shop_table td.product-subtotal span',
			),
			'pencidesign_woo_fontsize_pages_table_product_quantity' => array(
				'font-size' => '.woocommerce .woocommerce-cart-form .quantity .qty',
			),
			'pencidesign_woo_fontsize_pages_cart_total_h2'          => array(
				'font-size' => '.woocommerce .cart-collaterals .cart_totals h2, .woocommerce-page .cart-collaterals .cart_totals h2',
			),
			'pencidesign_woo_fontsize_pages_button'                 => array(
				'font-size' => '.woocommerce .woocommerce-cart-form .cart .button[name="apply_coupon"], .woocommerce .woocommerce-cart-form .cart button.button, .woocommerce .woocommerce-cart-form .cart button.button:disabled, .woocommerce .woocommerce-cart-form .cart button.button:disabled[disabled],.woocommerce #respond .wc-proceed-to-checkout input#submit.alt, .woocommerce .wc-proceed-to-checkout a.button.alt, .woocommerce .wc-proceed-to-checkout button.button.alt, .woocommerce .wc-proceed-to-checkout input.button.alt',
			),
			'pencidesign_woo_fontsize_checkout_form_label'          => array(
				'font-size' => 'body.woocommerce-checkout form.checkout.woocommerce-checkout label',
			),
			'pencidesign_woo_fontsize_checkout_form_input'          => array(
				'font-size' => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row select',
			),
			'pencidesign_woo_fontsize_checkout_order_heading'       => array(
				'font-size' => '.woocommerce form.checkout h3',
			),
			'pencidesign_woo_fontsize_checkout_order_button'        => array(
				'font-size' => '.woocommerce button.button.alt, body.woocommerce-checkout form.checkout.woocommerce-checkout button',
			),
		);

		$extra_font_size_m = array(
			'pencidesign_woo_fontsize_pages_nav_font_size_m'          => array(
				'font-size' => '.woocommerce .penci_woo_pages_breadcrumbs ul li span, .woocommerce .penci_woo_pages_breadcrumbs ul li a',
			),
			'pencidesign_woo_fontsize_pages_table_th_m'               => array(
				'font-size' => '.woocommerce table.shop_table th',
			),
			'pencidesign_woo_fontsize_pages_table_product_title_m'    => array(
				'font-size' => '.woocommerce table.shop_table td.product-name a',
			),
			'pencidesign_woo_fontsize_pages_table_product_price_m'    => array(
				'font-size' => '.woocommerce table.shop_table td.product-price span, .woocommerce table.shop_table td.product-subtotal span',
			),
			'pencidesign_woo_fontsize_pages_table_product_subtotal_m' => array(
				'font-size' => '.woocommerce table.shop_table td.product-subtotal span',
			),
			'pencidesign_woo_fontsize_pages_table_product_quantity_m' => array(
				'font-size' => '.woocommerce .woocommerce-cart-form .quantity .qty',
			),
			'pencidesign_woo_fontsize_pages_cart_total_h2_m'          => array(
				'font-size' => '.woocommerce .cart-collaterals .cart_totals h2, .woocommerce-page .cart-collaterals .cart_totals h2',
			),
			'pencidesign_woo_fontsize_pages_button_m'                 => array(
				'font-size' => '.woocommerce .woocommerce-cart-form .cart .button[name="apply_coupon"], .woocommerce .woocommerce-cart-form .cart button.button, .woocommerce .woocommerce-cart-form .cart button.button:disabled, .woocommerce .woocommerce-cart-form .cart button.button:disabled[disabled],.woocommerce #respond .wc-proceed-to-checkout input#submit.alt, .woocommerce .wc-proceed-to-checkout a.button.alt, .woocommerce .wc-proceed-to-checkout button.button.alt, .woocommerce .wc-proceed-to-checkout input.button.alt',
			),
			'pencidesign_woo_fontsize_checkout_form_label_m'          => array(
				'font-size' => 'body.woocommerce-checkout form.checkout.woocommerce-checkout label',
			),
			'pencidesign_woo_fontsize_checkout_form_input_m'          => array(
				'font-size' => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row select',
			),
			'pencidesign_woo_fontsize_checkout_order_heading_m'       => array(
				'font-size' => '.woocommerce form.checkout h3',
			),
			'pencidesign_woo_fontsize_checkout_order_button_m'        => array(
				'font-size' => '.woocommerce button.button.alt, body.woocommerce-checkout form.checkout.woocommerce-checkout button',
			),
		);

		$custom_product_color = array(
			'penci_woo_product_loop_title_color_reup'      => array(
				'color' => '  .upsells.products .woocommerce-loop-product__title.penci-loop-title a,
                              .upsells.products .penci-product-loop-title h3 a,
                              .upsells.products .products.product-style-7 .price,
                              .upsells.products .products.product-style-7 .penci-product-loop-title a,
                              .related.products .woocommerce-loop-product__title.penci-loop-title a,
                              .related.products .penci-product-loop-title h3 a,
                              .related.products .products.product-style-7 .price,
                              .related.products .products.product-style-7 .penci-product-loop-title a',
			),
			'penci_woo_product_loop_price_color_reup'      => array(
				'color' => '  .woocommerce .upsells.products div.product p.price ins,
                              .woocommerce .upsells.products div.product span.price ins,
                              .woocommerce .upsells.products div.product p.price,
                              .woocommerce .upsells.products div.product span.price,
                              .woocommerce .related.products div.product p.price ins,
                              .woocommerce .related.products div.product span.price ins,
                              .woocommerce .related.products div.product p.price,
                              .woocommerce .related.products div.product span.price',
			),
			'penci_woo_product_loop_cat_color_reup'        => array(
				'color' => '  .upsells.products .products .penci-soledad-product .penci-product-loop-title .penci-product-cats a,
                              .related.products .products .penci-soledad-product .penci-product-loop-title .penci-product-cats a',
			),
			'penci_woo_product_loop_cat_hover_color_reup'  => array(
				'color' => '  .upsells.products .products .penci-soledad-product .penci-product-loop-title .penci-product-cats a:hover,
                              .related.products .products .penci-soledad-product .penci-product-loop-title .penci-product-cats a:hover',
			),
			'penci_woo_product_loop_title_color_cross'     => array(
				'color' => ' .woocommerce .cross-sells .woocommerce-loop-product__title.penci-loop-title a,
                             .woocommerce .cross-sells .penci-product-loop-title h3 a,
                             .woocommerce .cross-sells .products.product-style-7 .price,
                             .woocommerce .cross-sells .products.product-style-7 .penci-product-loop-title a',
			),
			'penci_woo_product_loop_price_color_cross'     => array(
				'color' => ' .woocommerce .cross-sells div.product p.price ins,
                             .woocommerce .cross-sells div.product span.price ins,
                             .woocommerce .cross-sells div.product p.price,
                             .woocommerce .cross-sells div.product span.price',
			),
			'penci_woo_product_loop_cat_color_cross'       => array(
				'color' => ' .cross-sells .products .penci-soledad-product .penci-product-loop-title .penci-product-cats a'
			),
			'penci_woo_product_loop_cat_hover_color_cross' => array(
				'color' => ' .cross-sells .products .penci-soledad-product .penci-product-loop-title .penci-product-cats a:hover'
			),
		);

		$font_family_settings = array(
			'penci_font_for_menu',
		);

		$woocommerce_default_color = array(
			'penci_woo_product_loop_progress_active_bg_color',
			'penci_product_page_general_link_hover_color',
			'penci_product_page_button_swatches_border_hover_color',
			'penci_product_page_button_swatches_bg_color',
			'penci_woo_page_button_bg_color',
			'penci_woo_page_button_alt_bg_hover_color',
		);

		$penci_default_body_font_size = array(
			'--pcs_fsp_general',
			'--pcs_fsp_tab_tt',
		);

		penci_woo_customizer_custom_css_rules( $product_customize_settings, $woocommerce_default_color, $font_family_settings, $penci_default_body_font_size );

		$cart_css           = penci_woo_customizer_custom_css_prop( $cart_page_props );
		$checkout_css       = penci_woo_customizer_custom_css_prop( $checkout_page_prop );
		$completed_page_css = penci_woo_customizer_custom_css_prop( $completed_page_prop );

		echo penci_woo_customizer_custom_css_prop( $sidecar_props );
		echo penci_woo_customizer_custom_css_prop( $extra_font_size );
		echo penci_woo_customizer_custom_css_prop( $extra_font_size_m );
		echo penci_woo_customizer_custom_css_prop( $custom_product_color );

		if ( is_cart() && $cart_css ) {
			echo $cart_css;
		}

		if ( is_checkout() && $checkout_css ) {
			echo $checkout_css;
		}

		if ( is_checkout() && ! empty( is_wc_endpoint_url( 'order-received' ) ) && $completed_page_css ) {
			echo $completed_page_css;
		}

		// Sidebar filter style.
		$filterwidget_margin              = get_theme_mod( 'pencidesign_woo_filter_widget_margin' );
		$filterwidget_heading_lowcase     = get_theme_mod( 'pencidesign_woo_filter_widget_heading_lowcase' );
		$filterwidget_heading_size        = get_theme_mod( 'pencidesign_woo_filter_widget_heading_size' );
		$filterwidget_heading_image_9     = get_theme_mod( 'pencidesign_woo_filter_widget_heading_image_9' );
		$filterwidget_heading9_repeat     = get_theme_mod( 'pencidesign_woo_filter_widget_heading9_repeat' );
		$filterwidget_remove_border_outer = get_theme_mod( 'pencidesign_woo_filter_widget_remove_border_outer' );
		$filterwidget_remove_arrow_down   = get_theme_mod( 'pencidesign_woo_filter_widget_remove_arrow_down' );

		if ( $filterwidget_margin ) {
			echo '.penci-sidebar-filter .penci-sidebar-content .widget{ margin-bottom: ' . esc_attr( $filterwidget_margin ) . 'px; }';
			echo '.penci-sidebar-filter-widgets2{ margin-top: ' . esc_attr( $filterwidget_margin ) . 'px; }';
		}

		if ( $filterwidget_heading_lowcase ) {
			echo '.penci-sidebar-filter .penci-sidebar-content .penci-border-arrow .inner-arrow{ text-transform: none; }';
		}

		if ( $filterwidget_heading_size ) {
			echo '.penci-sidebar-filter .penci-sidebar-content .penci-border-arrow .inner-arrow { font-size: ' . $filterwidget_heading_size . 'px; }';
		}
		if ( $filterwidget_heading_image_9 ) {
			echo '.penci-sidebar-filter .penci-sidebar-content.style-8 .penci-border-arrow .inner-arrow { background-image: url(' . $filterwidget_heading_image_9 . '); }';
		}
		if ( $filterwidget_heading9_repeat ) {
			echo '.penci-sidebar-filter .penci-sidebar-content.style-8 .penci-border-arrow .inner-arrow{ background-repeat: ' . $filterwidget_heading9_repeat . '; background-size: auto; }';
		}
		if ( $filterwidget_remove_border_outer ) {
			echo '.penci-sidebar-filter .penci-sidebar-content .penci-border-arrow:after { content: none; display: none; }
		.penci-sidebar-filter .penci-sidebar-content .widget-title{ margin-left: 0; margin-right: 0; margin-top: 0; }
		.penci-sidebar-filter .penci-sidebar-content .penci-border-arrow:before{ bottom: -6px; border-width: 6px; margin-left: -6px; }';
		}

		if ( $filterwidget_remove_arrow_down ) {
			echo '.penci-sidebar-filter .penci-sidebar-content .penci-border-arrow:before, .penci-sidebar-content.style-2 .penci-border-arrow:after { content: none; display: none; }';
		}

		// Loading Icon
		$circle_loading_cl1 = get_theme_mod( 'penci_woo_loading_cl1' );
		$circle_loading_cl2 = get_theme_mod( 'penci_woo_loading_cl2' );

		if ( $circle_loading_cl1 ) {
			echo '.woocommerce a.button.loading:before, .woocommerce button.button.loading:before, .woocommerce input.button.loading:before, .woocommerce #respond input#submit.loading:before, .woocommerce a.button.loading:after, .woocommerce button.button.loading:after, .woocommerce input.button.loading:after, .woocommerce #respond input#submit.loading:after{ border-right-color: ' . $circle_loading_cl1 . '; border-bottom-color: ' . $circle_loading_cl1 . '; }';
		}

		if ( $circle_loading_cl2 ) {
			echo '.woocommerce a.button.loading:before, .woocommerce button.button.loading:before, .woocommerce input.button.loading:before, .woocommerce #respond input#submit.loading:before, .woocommerce a.button.loading:after, .woocommerce button.button.loading:after, .woocommerce input.button.loading:after, .woocommerce #respond input#submit.loading:after{ border-right-color: ' . $circle_loading_cl2 . '; border-bottom-color: ' . $circle_loading_cl2 . '; }';
		}

		$product_cat_overlay = get_theme_mod( 'penci_woo_section_product_cat_loop_overlay_color' );
		if ( $product_cat_overlay ) {
			echo 'body{--pcl_l_cat_o_cl_rgba: linear-gradient(0deg, ' . penci_woo_hex2rgb( $product_cat_overlay, 1 ) . ' 0%, ' . penci_woo_hex2rgb( $product_cat_overlay, 0.5 ) . ' 50%, rgba(255,255,255,0) 100%);}';
		}
	}
}


if ( ! function_exists( 'penci_is_layered_nav_active' ) ) {
	add_filter( 'woocommerce_is_layered_nav_active', 'penci_is_layered_nav_active' );
	function penci_is_layered_nav_active() {
		return is_active_widget( false, false, 'soledad-product-filter', true );
	}
}

if ( ! function_exists( 'penci_is_layered_price_active' ) ) {
	add_filter( 'woocommerce_is_price_filter_active', 'penci_is_layered_price_active' );
	function penci_is_layered_price_active() {
		$result = is_active_widget( false, false, 'soledad-price-filter', true );
		if ( ! $result ) {
			$result = apply_filters( 'penci_use_custom_price_widget', true );
		}

		return $result;
	}
}

if ( ! function_exists( 'penci_get_filtered_price_new' ) ) {
	function penci_get_filtered_price_new() {
		global $wpdb;

		if ( ! is_shop() && ! is_product_taxonomy() ) {
			$sql = "
			SELECT min( FLOOR( min_price ) ) as min_price, MAX( CEILING( max_price ) ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}";

			return $wpdb->get_row( $sql );
		}

		$args       = WC()->query->get_main_query()->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );
		$search     = WC_Query::get_main_search_query_sql();

		$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$search_query_sql = $search ? ' AND ' . $search : '';

		$sql = "
			SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
			)';

		$sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

		return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	}
}

if ( ! function_exists( 'penci_sidecart_nav' ) ) {
	add_action( 'wp_footer', 'penci_sidecart_nav', 10 );
	function penci_sidecart_nav() {
		if ( 'dropdown' != get_theme_mod( 'penci_woo_cart_style' ) ) {
			wc_get_template_part( 'global/side-cart' );
		}
	}
}

if ( ! function_exists( 'penci_get_product_ids_on_sale' ) ) {
	function penci_get_product_ids_on_sale() {
		$data_store          = WC_Data_Store::load( 'product' );
		$on_sale_products    = $data_store->get_on_sale_products();
		$product_ids_on_sale = wp_parse_id_list( array_merge( wp_list_pluck( $on_sale_products, 'id' ), array_diff( wp_list_pluck( $on_sale_products, 'parent_id' ), array( 0 ) ) ) );

		return $product_ids_on_sale;
	}
}

if ( ! function_exists( 'penci_get_product_ids_on_viewed' ) ) {
	function penci_get_product_ids_on_viewed() {
		return isset( $_COOKIE['penci_product_viewed_ids'] ) && $_COOKIE['penci_product_viewed_ids'] ? json_decode( $_COOKIE['penci_product_viewed_ids'], true ) : [];
	}
}

if ( ! function_exists( 'penci_elementor_products_template' ) ) {
	function penci_elementor_products_template( $settings, $preloader = '' ) {
		$classes  = array();
		$settings = wp_parse_args( $settings, penci_custom_product_query_default_args() );

		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		if ( isset( $_GET['product-page'] ) ) { // phpcs:ignore
			$paged = wc_clean( wp_unslash( $_GET['product-page'] ) ); // phpcs:ignore
		}

		// Query settings.
		$ordering_args = WC()->query->get_catalog_ordering_args( $settings['orderby'], $settings['order'] );

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'paged'               => $paged,
			'orderby'             => $ordering_args['orderby'],
			'order'               => $ordering_args['order'],
			'posts_per_page'      => $settings['items_per_page'],
			'meta_query'          => WC()->query->get_meta_query(), // phpcs:ignore
			'tax_query'           => WC()->query->get_tax_query(), // phpcs:ignore
		);

		if ( 'new' === $settings['post_type'] ) {
			$new_label = get_theme_mod( 'penci_woo_label_new_product', true );
			$days      = get_theme_mod( 'penci_woo_label_new_product_period', 7 );
			if ( $new_label && $days ) {
				$query_args['date_query'] = array(
					'after' => date( 'Y-m-d', strtotime( '-' . $days . ' days' ) ),
				);
			} else {
				$query_args['meta_query'][] = array(
					array(
						'key'     => 'penci_pmeta_product_extra_options',
						'value'   => '"permanent_new_label";s:1:"1"',
						'compare' => 'LIKE',
					),
				);
			}
		}

		if ( isset( $settings['search_query'] ) && ! empty( $settings['search_query'] ) ) {
			$query_args['s'] = esc_attr( $settings['search_query'] );
		}

		if ( $ordering_args['meta_key'] ) {
			$query_args['meta_key'] = $ordering_args['meta_key']; // phpcs:ignore
		}
		if ( $settings['meta_key'] ) {
			$query_args['meta_key'] = $settings['meta_key']; // phpcs:ignore
		}
		if ( 'ids' === $settings['post_type'] && $settings['include'] ) {
			$query_args['post__in'] = $settings['include'];
		}
		if ( $settings['exclude'] ) {
			$query_args['post__not_in'] = $settings['exclude'];
		}
		if ( $settings['taxonomies'] ) {
			$taxonomy_names = get_object_taxonomies( 'product' );
			$terms          = get_terms( $taxonomy_names, array(
				'orderby'    => 'name',
				'include'    => $settings['taxonomies'],
				'hide_empty' => false,
			) );

			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				if ( 'featured' === $settings['post_type'] ) {
					$query_args['tax_query'] = [ 'relation' => 'AND' ]; // phpcs:ignore
				}

				$relation = $settings['query_type'] ? $settings['query_type'] : 'OR';
				if ( count( $terms ) > 1 ) {
					$query_args['tax_query']['categories'] = array( 'relation' => $relation );
				}

				foreach ( $terms as $term ) {
					$query_args['tax_query']['categories'][] = array(
						'taxonomy'         => $term->taxonomy,
						'field'            => 'slug',
						'terms'            => array( $term->slug ),
						'include_children' => true,
						'operator'         => 'IN',
					);
				}
			}
		}
		if ( 'featured' === $settings['post_type'] ) {
			$query_args['tax_query'][] = array(
				'taxonomy'         => 'product_visibility',
				'field'            => 'name',
				'terms'            => 'featured',
				'operator'         => 'IN',
				'include_children' => false,
			);
		}
		if ( apply_filters( 'penci_hide_out_of_stock_items', false ) && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$query_args['meta_query'][] = array(
				'key'     => '_stock_status',
				'value'   => 'outofstock',
				'compare' => 'NOT IN',
			);
		}
		if ( $settings['order'] ) {
			$query_args['order'] = $settings['order'];
		}
		if ( $settings['offset'] ) {
			$query_args['offset'] = $settings['offset'];
		}
		if ( 'sale' === $settings['post_type'] ) {

			$query_args['post__in'] = array_merge( array( 0 ), penci_get_product_ids_on_sale() );
		}
		if ( 'bestselling' === $settings['post_type'] ) {
			$query_args['orderby']  = 'meta_value_num';
			$query_args['meta_key'] = 'total_sales'; // phpcs:ignore
			$query_args['order']    = 'DESC';
		}

		WC()->query->remove_ordering_args();

		if ( isset( $_GET['orderby'] ) && $settings['header_tools'] ) { // phpcs:ignore
			$element_orderby = wc_clean( wp_unslash( $_GET['orderby'] ) ); // phpcs:ignore

			if ( 'date' === $element_orderby ) {
				$query_args['orderby'] = 'date';
				$query_args['order']   = 'DESC';
			} elseif ( 'price-desc' === $element_orderby ) {
				$query_args['orderby'] = 'price';
				$query_args['order']   = 'DESC';
			} else {
				$query_args['orderby'] = $element_orderby;
				$query_args['order']   = 'ASC';
			}
		}

		if ( 'price' === $query_args['orderby'] ) {
			$query_args['orderby']  = 'meta_value_num';
			$query_args['meta_key'] = '_price'; // phpcs:ignore
		}

		if ( isset( $_GET['per_page'] ) && $settings['header_tools'] ) { // phpcs:ignore
			$query_args['posts_per_page'] = wc_clean( wp_unslash( $_GET['per_page'] ) ); // phpcs:ignore
		}

		if ( isset( $settings['product_type'] ) && in_array( $settings['product_type'], array(
				'day',
				'week',
				'month',
			) ) ) {
			$date = '+1 day';
			if ( $settings['product_type'] == 'week' ) {
				$date = '+7 day';
			} elseif ( $settings['product_type'] == 'month' ) {
				$date = '+1 month';
			}
			$query_args['meta_query'] = apply_filters( 'penci_product_deals_meta_query', array_merge( WC()->query->get_meta_query(), array(
				array(
					'key'     => '_deal_quantity',
					'value'   => 0,
					'compare' => '>',
				),
				array(
					'key'     => '_sale_price_dates_to',
					'value'   => 0,
					'compare' => '>',
				),
				array(
					'key'     => '_sale_price_dates_to',
					'value'   => strtotime( $date ),
					'compare' => '<=',
				),
			) ) );
		} elseif ( isset( $settings['product_type'] ) && $settings['product_type'] == 'deals' ) {
			$query_args['meta_query'] = apply_filters( 'penci_product_deals_meta_query', array_merge( WC()->query->get_meta_query(), array(
				array(
					'key'     => '_deal_quantity',
					'value'   => 0,
					'compare' => '>',
				),
			) ) );
		}

		if ( 'recent_viewed' === $settings['post_type'] ) {
			$query_args['post__in'] = array_merge( array( 0 ), penci_get_product_ids_on_viewed() );
		}

		if ( 'top_rated_products' === $settings['post_type'] ) {
			add_filter( 'posts_clauses', 'penci_order_by_rating_post_clauses' );
			$products = new WP_Query( apply_filters( 'penci_product_element_query_args', $query_args ) );
			remove_filter( 'posts_clauses', 'penci_order_by_rating_post_clauses' );
		} else {
			$products = new WP_Query( apply_filters( 'penci_product_element_query_args', $query_args ) );
		}

		// Element settings.
		if ( 'inherit' === $settings['product_style'] ) {
			$settings['product_style'] = get_theme_mod( 'penci_woocommerce_product_style', 'style-1' );
		}

		$product_style = 'list' === $settings['layout'] ? 'list' : $settings['product_style'];

		if ( $settings['elementor'] ) {
			$columns = isset( $settings['columns']['size'] ) && $settings['columns']['size'] ? $settings['columns']['size'] : 4;
		} else {
			$columns = isset( $settings['columns'] ) && $settings['columns'] ? $settings['columns'] : 4;
		}

		$columns = 'list' === $settings['layout'] ? 1 : $columns;

		$data_attr = array();

		if ( 'carousel' === $settings['layout'] ) {
			if ( $settings['elementor'] ) {
				$slides_per_view = isset( $settings['slides_per_view']['size'] ) && $settings['slides_per_view']['size'] ? $settings['slides_per_view']['size'] : 4;
				$tablet_items    = isset( $settings['slides_per_view_tablet']['size'] ) && ! empty( $settings['slides_per_view_tablet']['size'] ) ? $settings['slides_per_view_tablet']['size'] : 2;
				$mobile_items    = isset( $settings['slides_per_view_mobile']['size'] ) && ! empty( $settings['slides_per_view_mobile']['size'] ) ? $settings['slides_per_view_mobile']['size'] : 1;
			} else {
				$slides_per_view = isset( $settings['columns'] ) && $settings['columns'] ? $settings['columns'] : 4;
				$tablet_items    = isset( $settings['tablet_columns'] ) && $settings['tablet_columns'] ? $settings['tablet_columns'] : 2;
				$mobile_items    = isset( $settings['mobile_columns'] ) && $settings['mobile_columns'] ? $settings['mobile_columns'] : 1;
			}

			$spacing_item = isset( $settings['product_horizontal_spacing']['size'] ) ? $settings['product_horizontal_spacing']['size'] : 30;
			$lazy         = isset( $settings['scroll_carousel_init'] ) ? $settings['scroll_carousel_init'] : true;

			$speed           = $settings['speed'] ? $settings['speed'] : 500;
			$scroll_per_page = 'true' == $settings['scroll_per_page'] ? $slides_per_view : 1;
			$data_attr[]     = 'data-speed=\'' . $speed . '\'';
			$data_attr[]     = 'data-item=\'' . $slides_per_view . '\'';
			$data_attr[]     = 'data-desktop=\'' . $slides_per_view . '\'';
			$data_attr[]     = 'data-tablet=\'' . $tablet_items . '\'';
			$data_attr[]     = 'data-tabsmall=\'' . $tablet_items . '\'';
			$data_attr[]     = 'data-mobile=\'' . $mobile_items . '\'';
			$data_attr[]     = 'data-loop=\'' . $settings['wrap'] . '\'';
			$data_attr[]     = 'data-auto=\'' . $settings['autoplay'] . '\'';
			$data_attr[]     = 'data-dots=\'' . $settings['hide_pagination_control'] . '\'';
			$data_attr[]     = 'data-nav=\'' . $settings['hide_prev_next_buttons'] . '\'';
			$data_attr[]     = 'data-lazy=\'' . $lazy . '\'';
			$data_attr[]     = 'data-margin=\'' . $spacing_item . '\'';
			$columns         = $slides_per_view;
		}

		wc_set_loop_prop( 'product_loop_style', $product_style );
		wc_set_loop_prop( 'columns', $columns );

		// Loop settings.
		wc_set_loop_prop( 'products_view', $settings['layout'] );
		wc_set_loop_prop( 'total', $products->found_posts );
		wc_set_loop_prop( 'total_pages', $products->max_num_pages );
		wc_set_loop_prop( 'current_page', $products->query['paged'] );
		wc_set_loop_prop( 'is_shortcode', true );
		wc_set_loop_prop( 'name', isset( $settings['loop_name'] ) ? $settings['loop_name'] : 'custom' );

		wc_set_loop_prop( 'product_loop_icon_style', $settings['icon_style'] );
		wc_set_loop_prop( 'product_loop_icon_position', $settings['icon_position'] );
		wc_set_loop_prop( 'product_loop_icon_animation', $settings['icon_animation'] );
		wc_set_loop_prop( 'product_round_style', penci_shop_product_round_style( $settings['icon_style'], $settings['icon_position'] ) );
		wc_set_loop_prop( 'stock_progress_bar', (boolean) $settings['stock_progress_bar'] );
		wc_set_loop_prop( 'img_size', $settings['img_size'] );
		wc_set_loop_prop( 'pagination', $settings['pagination'] );
		wc_set_loop_prop( 'loop_rating', $settings['product_rating'] );
		wc_set_loop_prop( 'loop_categories', $settings['product_categories'] );
		wc_set_loop_prop( 'layout', $settings['layout'] );

		wc_set_loop_prop( 'penci_woo_settings', wp_json_encode( array_intersect_key( $settings, penci_custom_product_query_default_args() ) ) );

		$classes[] = 'product-style-' . $product_style;
		$classes[] = 'product-layout-' . $settings['layout'];


		$classes[]    = 'woocommerce elementor-products';
		$unique_class = $settings['title'] ? md5( $settings['title'] ) : 'default';
		$unique_class = 'products-' . $unique_class . '-section';
		$classes[]    = $unique_class;
		$classes[]    = $settings['class'] ? $settings['class'] : '';
		$classes[]    = $settings['pagination'];

		if ( 'pagination' !== $settings['pagination'] ) {
			wp_enqueue_script( 'infinite-scroll' );
		}

		$section_id = 'penci-products-' . wp_rand( 0, 99999 );
		wc_set_loop_prop( 'loopid', $section_id );


		$loop_wrapper_classes   = array();
		$loop_wrapper_classes[] = penci_woocommerce_get_product_loop_class();
		$loop_wrapper_classes[] = 'products mobile-columns-' . wc_get_loop_prop( 'mobile-columns', 2 );
		$loop_wrapper_classes[] = 'columns-' . esc_attr( $columns );

		if ( 'carousel' === $settings['layout'] ) {
			$loop_wrapper_classes[] = 'penci-owl-carousel swiper penci-owl-carousel-slider display-style-carousel';
		}

		$classes[]              = $section_id;
		$loop_wrapper_classes[] = $section_id . '-container';

		echo '<div data-section="' . esc_attr( $section_id ) . '" class="penci-custom-products penci-woo-page-container ' . implode( ' ', $classes ) . '">';

		if ( $products->have_posts() ) {
			if ( $preloader ) {
				echo '<div class="penci-products-preloader"><span class="penci-loading-icon"><span class="bubble"></span><span class="bubble"></span><span class="bubble"></span></span></div>';
			}
			echo '<ul ' . implode( ' ', $data_attr ) . ' data-item="' . esc_attr( $columns ) . '" data-columns="' . esc_attr( $columns ) . '" class="' . implode( ' ', $loop_wrapper_classes ) . '">';
			if ( 'carousel' === $settings['layout'] ) {
				echo '<div class="swiper-wrapper">';
			}
			while ( $products->have_posts() ) :
				$products->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
			if ( 'carousel' === $settings['layout'] ) {
				echo '</div>';
			}
			woocommerce_product_loop_end();
			if ( 'none' !== $settings['pagination'] && 'carousel' !== $settings['layout'] ) {
				echo '<div id="penci-load-' . esc_attr( $unique_class ) . '" data-part="' . esc_attr( $unique_class ) . '" class="page-unique-part">';
				penci_woo_infinit_load_button( $settings['pagination'], $products->max_num_pages );
				woocommerce_pagination();
				echo '</div>';
			}
		} else {
			echo penci_woo_translate_text( 'penci_woo_trans_noproductfount' );
		}

		echo '</div>';

		wc_reset_loop();
		wp_reset_postdata();
	}
}

if ( ! function_exists( 'penci_order_by_rating_post_clauses' ) ) {
	function penci_order_by_rating_post_clauses( $args ) {
		global $wpdb;

		$args['where']   .= " AND $wpdb->commentmeta.meta_key = 'rating' ";
		$args['join']    .= "LEFT JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID) LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)";
		$args['orderby'] = "$wpdb->commentmeta.meta_value DESC";
		$args['groupby'] = "$wpdb->posts.ID";

		return $args;
	}
}

add_action( 'woocommerce_before_cart_totals', 'penci_woo_before_cart_total_wrap' );
function penci_woo_before_cart_total_wrap() {
	echo '<div class="penci-woo-cart-total-wrap">';
}

add_action( 'woocommerce_after_cart_totals', 'penci_woo_before_cart_end_wrap' );
function penci_woo_before_cart_end_wrap() {
	echo '</div>';
}

add_action( 'woocommerce_share', 'penci_woo_social_share' );
function penci_woo_social_share() {
	echo '<div class="single-product-share">';
	echo '<span class="share-title">' . penci_get_setting( 'penci_trans_share' ) . '</span>';
	penci_soledad_social_share( 'single' );
	echo '</div>';
}

add_action( 'woocommerce_before_cart', 'penci_woo_pages_breadcrumbs' );
add_action( 'woocommerce_before_checkout_form', 'penci_woo_pages_breadcrumbs', 0 );
function penci_woo_pages_breadcrumbs() {
	?>
    <div class="penci_woo_pages_breadcrumbs">
        <ul>
            <li class="step-cart <?php echo ( is_cart() ) ? 'active' : 'inactive'; ?>">
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                    <span><?php echo penci_woo_translate_text( 'penci_woo_trans_shoppingcart' ); ?></span>
                </a>
            </li>
            <li class="step-checkout <?php echo ( is_checkout() && ! is_order_received_page() ) ? 'active' : 'inactive'; ?>">
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
                    <span><?php echo penci_woo_translate_text( 'penci_woo_trans_checkout' ); ?></span>
                </a>
            </li>
            <li class="step-complete <?php echo ( is_order_received_page() ) ? 'active' : 'inactive'; ?>">
                <span><?php echo penci_woo_translate_text( 'penci_woo_trans_ordrcompleted' ); ?></span>
            </li>
        </ul>
    </div>
	<?php
}

if ( ! function_exists( 'penci_woo_get_custom_page_link' ) ) {
	function penci_woo_get_custom_page_link( $page ) {
		$url = '';
		switch ( $page ) {
			case 'home':
				$url = get_home_url();
				break;
			case 'shop':
				$url = wc_get_page_permalink( 'shop' );
				break;
			case 'cart':
				$url = wc_get_cart_url();
				break;
			case 'account':
				$url = wc_get_page_permalink( 'myaccount' );
				break;
			case 'wishlist':
				$url = get_page_link( get_theme_mod( 'penci_woocommerce_wishlist_page' ) );
				break;
			case 'compare':
				$url = get_page_link( get_theme_mod( 'penci_woocommerce_compare_page' ) );
				break;
			case 'filer':
				$url = '#productfilter';
				break;
		}

		return $url;
	}
}

if ( ! function_exists( 'penci_woo_ele_lib_list' ) ) {
	function penci_woo_ele_lib_list() {
		$elementor_library_args = array(
			'post_type'      => 'elementor_library',
			'posts_per_page' => - 1,
		);

		if ( post_type_exists( 'penci-block' ) ) {
			$elementor_library_args['post_type'] = 'penci-block';
		}

		$elementor_library = get_posts( $elementor_library_args );

		$penci_elementor_library = array();

		foreach ( $elementor_library as $library ) {
			$penci_elementor_library[ $library->post_title ] = $library->ID;
		}

		return $penci_elementor_library;
	}
}

if ( ! function_exists( 'penci_woo_cat_list_opt' ) ) {
	function penci_woo_terms_list_opt( $tax ) {

		$terms_list = array();
		$terms      = get_terms( array(
			'taxonomy'   => $tax,
			'hide_empty' => false,
		) );

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$terms_list[ $term->term_id ] = $term->name;
			}
		}

		return $terms_list;
	}
}

if ( ! function_exists( 'penci_woo_cat_list_opt' ) ) {
	add_action( 'init', 'penci_woo_cat_list_opt' );
	function penci_woo_cat_list_opt() {
		// Thank you text
		add_filter( 'woocommerce_thankyou_order_received_text', function ( $text, $order ) {
			$user_text = get_theme_mod( 'penci_woo_checkout_success_thankyou_text' );

			return $user_text ? $user_text : $text;
		}, 10, 2 );

		add_action( 'woocommerce_before_checkout_form', function () {
			echo get_theme_mod( 'penci_woo_checkout_before_content' );
		} );

		add_action( 'woocommerce_before_checkout_form', function () {
			echo get_theme_mod( 'woocommerce_after_checkout_form' );
		} );

		add_action( 'woocommerce_before_cart', function () {
			echo get_theme_mod( 'penci_woo_cart_before_content' );
		} );

		add_action( 'woocommerce_after_cart', function () {
			echo get_theme_mod( 'penci_woo_cart_after_content' );
		} );

		// product_excerpt_length
		if ( ! empty( get_theme_mod( 'penci_woo_limit_product_excerpt' ) ) ) {
			add_filter( 'woocommerce_short_description', function ( $excerpt ) {
				global $post;
				$quicview = wc_get_loop_prop( 'quickview', false );
				$length   = get_theme_mod( 'penci_woo_limit_product_excerpt' );
				if ( isset( $post->post_type ) && 'product' == $post->post_type && ! is_singular( 'product' ) && ! $quicview ) {
					$excerpt = wp_trim_words( $excerpt, $length );
				}

				return $excerpt;
			}, 10, 1 );
		}
	}
}

if ( ! function_exists( 'penci_get_product_loop_class' ) ) {
	/**
	 * Get Product Loop Class
	 *
	 * @return string
	 */
	function penci_get_product_loop_class() {
		$product_display_style = wc_get_loop_prop( 'products_view' );
		$product_style         = wc_get_loop_prop( 'product_loop_style' );
		$product_loop_name     = wc_get_loop_prop( 'name' );
		$product_loop_name     = penci_is_mobile() ? 'mobile' : $product_loop_name;
		$default_product_style = get_theme_mod( 'penci_woocommerce_product_style', 'style-1' );
		$product_style         = 'list' == $product_display_style ? 'list' : $product_style;
		$product_style         = in_array( $product_loop_name, array(
			'up-sells',
			'cross-sells',
			//'mobile',
			//'custom',
			'related',
			'wishlist',
		) ) ? $default_product_style : $product_style;

		$product_style = $product_style ? $product_style : $default_product_style;

		return $product_style;
	}
}

if ( ! function_exists( 'penci_shop_update_cart_item' ) ) {
	/**
	 * Update cart item.
	 *
	 * @return void
	 */
	function penci_shop_update_cart_item() {
		if ( ( isset( $_GET['item_id'] ) && $_GET['item_id'] ) && ( isset( $_GET['qty'] ) ) ) {
			global $woocommerce;
			if ( $_GET['qty'] ) {
				$woocommerce->cart->set_quantity( $_GET['item_id'], $_GET['qty'] );
			} else {
				$woocommerce->cart->remove_cart_item( $_GET['item_id'] );
			}
		}

		WC_AJAX::get_refreshed_fragments();
	}

	add_action( 'wp_ajax_penci_shop_update_cart_item', 'penci_shop_update_cart_item' );
	add_action( 'wp_ajax_nopriv_penci_shop_update_cart_item', 'penci_shop_update_cart_item' );
}

function penci_shop_product_round_style( $icon, $setting ) {
	$direct = 'vertical';

	$horizontal_class = array(
		'center-top',
		'center-center',
		'center-bottom',
	);

	$vertical_class = array(
		'top-left',
		'top-right',
		'bottom-left',
		'bottom-right',
	);

	if ( in_array( $setting, $horizontal_class ) ) {
		$direct = 'horizontal';
	}

	if ( in_array( $setting, $vertical_class ) && 'group' == $icon ) {
		$direct = 'vertical';
	}

	return $direct;
}

if ( ! function_exists( 'penci_product_is_color_name' ) ) {
	function penci_product_is_color_name( $name ) {
		$color = array(
			'aliceblue',
			'antiquewhite',
			'aqua',
			'aquamarine',
			'azure',
			'beige',
			'bisque',
			'black',
			'blanchedalmond',
			'blue',
			'blueviolet',
			'brown',
			'burlywood',
			'cadetblue',
			'chartreuse',
			'chocolate',
			'coral',
			'cornflowerblue',
			'cornsilk',
			'crimson',
			'cyan',
			'darkblue',
			'darkcyan',
			'darkgoldenrod',
			'darkgray',
			'darkgreen',
			'darkgrey',
			'darkkhaki',
			'darkmagenta',
			'darkolivegreen',
			'darkorange',
			'darkorchid',
			'darkred',
			'darksalmon',
			'darkseagreen',
			'darkslateblue',
			'darkslategray',
			'darkslategrey',
			'darkturquoise',
			'darkviolet',
			'deeppink',
			'deepskyblue',
			'dimgray',
			'dimgrey',
			'dodgerblue',
			'firebrick',
			'floralwhite',
			'forestgreen',
			'fuchsia',
			'gainsboro',
			'ghostwhite',
			'gold',
			'goldenrod',
			'gray',
			'green',
			'greenyellow',
			'grey',
			'honeydew',
			'hotpink',
			'indianred',
			'indigo',
			'ivory',
			'khaki',
			'lavender',
			'lavenderblush',
			'lawngreen',
			'lemonchiffon',
			'lightblue',
			'lightcoral',
			'lightcyan',
			'lightgoldenrodyellow',
			'lightgray',
			'lightgreen',
			'lightgrey',
			'lightpink',
			'lightsalmon',
			'lightseagreen',
			'lightskyblue',
			'lightslategray',
			'lightslategrey',
			'lightsteelblue',
			'lightyellow',
			'lime',
			'limegreen',
			'linen',
			'magenta',
			'maroon',
			'mediumaquamarine',
			'mediumblue',
			'mediumorchid',
			'mediumpurple',
			'mediumseagreen',
			'mediumslateblue',
			'mediumspringgreen',
			'mediumturquoise',
			'mediumvioletred',
			'midnightblue',
			'mintcream',
			'mistyrose',
			'moccasin',
			'navajowhite',
			'navy',
			'oldlace',
			'olive',
			'olivedrab',
			'orange',
			'orangered',
			'orchid',
			'palegoldenrod',
			'palegreen',
			'paleturquoise',
			'palevioletred',
			'papayawhip',
			'peachpuff',
			'peru',
			'pink',
			'plum',
			'powderblue',
			'purple',
			'red',
			'rosybrown',
			'royalblue',
			'saddlebrown',
			'salmon',
			'sandybrown',
			'seagreen',
			'seashell',
			'sienna',
			'silver',
			'skyblue',
			'slateblue',
			'slategray',
			'slategrey',
			'snow',
			'springgreen',
			'steelblue',
			'tan',
			'teal',
			'thistle',
			'tomato',
			'turquoise',
			'saddlebrown',
			'violet',
			'wheat',
			'white',
			'whitesmoke',
			'yellow',
			'yellowgreen',
		);

		return in_array( $name, $color );
	}
}

add_filter( 'woocommerce_cross_sells_columns', function () {
	return get_theme_mod( 'penci_shop_product_cross_sell_columns', 4 );
} );

add_filter( 'woocommerce_upsells_columns', function () {
	return get_theme_mod( 'penci_shop_product_up_sell_columns', 4 );
} );

add_filter( 'woocommerce_related_products_columns', function () {
	return get_theme_mod( 'penci_shop_product_related_columns', 4 );
} );


add_filter( 'woocommerce_before_output_product_categories', function () {
	$columns        = get_theme_mod( 'penci_shop_cat_columns', 4 );
	$mobile_columns = get_theme_mod( 'penci_shop_product_mobile_columns', 2 );
	$display_type   = get_theme_mod( 'penci_shop_cat_display_type', 'grid' );

	$classess = 'carousel' == $display_type ? ' penci-owl-carousel' : '';

	return '<ul class="products penci-woo-product-loop-categories mobile-columns-' . esc_attr( $mobile_columns ) . ' columns-' . esc_attr( $columns ) . ' display-style-' . esc_attr( $display_type . $classess ) . '">';
} );

add_filter( 'woocommerce_after_output_product_categories', function () {
	return '</ul>';
} );

if ( ! function_exists( 'penci_woo_ajax_url' ) ) {
	function penci_woo_ajax_url( $settings = null ) {
		$settings  = json_decode( $settings, true );
		$ajax_args = array();
		foreach ( $settings as $prop => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $sub_prop => $sub_value ) {
					if ( ! empty( $sub_value ) ) {
						$ajax_args[] = 'settings[' . $prop . '][' . $sub_prop . ']=' . $sub_value;
					}
				}
			} else {
				if ( ! empty( $value ) ) {
					$ajax_args[] = 'settings[' . $prop . ']=' . $value;
				}
			}
		}

		return implode( '&', $ajax_args );
	}
}

$category_page = get_option( 'wc_ajax_add_to_cart_variable_category_page' );


if ( isset( $category_page ) && $category_page == "yes" ) {

	if ( ! function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {

		function woocommerce_template_loop_add_to_cart( $args = array() ) {

			global $product;

			$product_type = $product->get_type();

			if ( $product ) {
				$defaults = array(
					'quantity'   => 1,
					'class'      => implode( ' ', array_filter( array(
						'button',
						'product_type_' . $product_type,
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : ''
					) ) ),
					'attributes' => array(
						'data-product_id'  => $product->get_id(),
						'data-product_sku' => $product->get_sku()
					),
				);

				$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

				if ( $product_type == "variable" ) {
					woocommerce_variable_add_to_cart();
				} else {
					wc_get_template( 'loop/add-to-cart.php', $args );
				}
			}
		}
	}
}

add_filter( 'woocommerce_product_get_rating_html', function ( $html, $rating, $count ) {
	if ( ! wc_get_loop_prop( 'loop_rating', true ) ) {
		$html = false;
	}

	return $html;
}, 10, 3 );

add_action( 'wp_footer', function () {
	$post_ids    = [];
	$current_ids = isset( $_COOKIE['penci_product_viewed_ids'] ) && $_COOKIE['penci_product_viewed_ids'] ? json_decode( $_COOKIE['penci_product_viewed_ids'], true ) : [];
	if ( is_singular( 'product' ) ) {
		$post_ids[] = get_the_ID();

		if ( ! empty( $current_ids ) ) {
			$post_ids = array_unique( array_merge( $post_ids, $current_ids ) );
		}

		?>
        <script type="text/javascript" id="penci_post_viewed_ids">
          Cookies.set('penci_product_viewed_ids', <?php echo json_encode( $post_ids );?>)
        </script>
		<?php
	}
}, 99999 );


add_filter( 'the_content', function ( $content ) {
	// Retrieve theme mod and page IDs once to improve efficiency.
	$is_use_default   = get_theme_mod( 'penci_woocommerce_standard_page', true );
	$cart_page_id     = wc_get_page_id( 'cart' );
	$checkout_page_id = wc_get_page_id( 'checkout' );

	// Only proceed if the default content is enabled.
	if ( $is_use_default ) {
		if ( is_page( $cart_page_id ) ) {
			return '[woocommerce_cart]';
		}

		if ( is_page( $checkout_page_id ) ) {
			return '[woocommerce_checkout]';
		}
	}

	return $content;
} );