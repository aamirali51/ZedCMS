<?php
defined( 'ABSPATH' ) || exit;
if ( is_customize_preview() && strpos( $_SERVER['HTTP_REFERER'], 'customize_amp=1') !== false) {
    return false;
}
$cart_style = get_theme_mod( 'penci_woo_cart_style', 'side-right' );
?>
<nav id="sidebar-sidebar-cart" class="woocommerce penci-sidebar-cart <?php echo esc_attr( $cart_style ); ?>">
    <div class="sidebar-cart-container">
        <div class="sidecart-heading">
            <h3><?php echo penci_woo_translate_text( 'penci_woo_trans_shopppingcart' ); ?></h3>
            <span class="close sidebar-cart-close"><i
                        class="penciicon-close-button"></i><?php echo penci_woo_translate_text( 'penci_woo_trans_close' ); ?></span>
        </div>
        <div class="sidecart-content">
            <div class="widget_shopping_cart_content">
				<?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
</nav>
<div class="penci-sidebar-cart-close sidebar-cart-close"><?php echo penci_woo_translate_text( 'penci_woo_trans_close' ); ?></div>
