<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$loop_setting = wc_get_loop_prop( 'penci_woo_settings' );
$pagination   = wc_get_loop_prop( 'pagination' );
$shortcode    = wc_get_loop_prop( 'is_shortcode' );

$total         = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current       = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base          = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format        = isset( $format ) ? $format : '';
$data_settings = $data_class = $extra_class = '';

if ( $total <= 1 ) {
	return;
}

if ( penci_is_shop_archive() || penci_is_shop_catelog() ) {
	$data_class = get_theme_mod( 'penci_shop_product_pagination', 'pagination' ) . '-pagination';
}

if ( ! empty( $loop_setting ) && $shortcode ) {
	$data_settings = ' data-settings=\'' . $loop_setting . '\'';
	$data_class    = ' ajax-pagination';
}

if ( 'loadmore' == $pagination && ! empty( $loop_setting ) && $shortcode ) {
	$data_class = ' loadmore-pagination';
	$url_query  = penci_woo_ajax_url( $loop_setting );
	$nonce      = wp_create_nonce( 'ajax-nonce' );
	$base       = esc_url_raw( admin_url( 'admin-ajax.php?action=penci_ajax_load_products&requestid=' . $nonce . '&' . $url_query . '&product-page=%#%' ) );
}

if ( wc_get_loop_prop( 'loopid' ) ) {
	$extra_class = ' ' . wc_get_loop_prop( 'loopid' );
}

?>
<nav class="woocommerce-pagination <?php echo $data_class . $extra_class; ?>"<?php echo $data_settings; ?>>
	<?php
	echo paginate_links(
		apply_filters(
			'woocommerce_pagination_args',
			array( // WPCS: XSS ok.
				'base'      => $base,
				'format'    => $format,
				'add_args'  => false,
				'current'   => max( 1, $current ),
				'total'     => $total,
				'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
				'next_text' => is_rtl() ? '&larr;' : '&rarr;',
				'type'      => 'list',
				'end_size'  => 3,
				'mid_size'  => 3,
			)
		)
	);
	?>
</nav>
