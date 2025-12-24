<?php
if ( ! class_exists( 'WooCommerce' ) || get_theme_mod( 'penci_woo_shop_hide_cart_icon', false ) ) {
	return;
}
if ( function_exists( 'WC' ) && WC()->cart ) {
    $cart_count = WC()->cart->get_cart_contents_count();
    $cart_count_html = sprintf( _n( '%d', '%d', $cart_count ), $cart_count );
} else {
    $cart_count_html = '0'; // Display a fallback value if the cart isn't available
}
?>
<div id="top-header-cart"
     class="top-search-classes pcheader-icon shoping-cart-icon<?php if ( get_theme_mod( 'penci_topbar_search_check' ) ): echo ' clear-right'; endif; ?>">
    <ul>
        <li><a class="cart-contents"
               href="<?php echo wc_get_cart_url(); ?>"
               title="<?php _e( 'View your shopping cart' ); ?>">
                <i class="penciicon-shopping-cart"></i>
                <span><?php echo $cart_count_html; ?></span></a>
			<?php if ( 'dropdown' == get_theme_mod( 'penci_woo_cart_style' ) ): ?>
                <div class="penci-header-cart-detail woocommerce">
                    <div class="widget_shopping_cart_content">
						<?php woocommerce_mini_cart(); ?>
                    </div>
                </div>
			<?php endif; ?>
        </li>
    </ul>
</div>
