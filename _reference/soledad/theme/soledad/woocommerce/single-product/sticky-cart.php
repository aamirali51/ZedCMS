<?php
global $product;
// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
$product_type = $product->get_type();
?>
<div class="penci-sticky-cart" id="sticky-cart">
    <div class="container">
        <div class="penci-sticky-cart-wrapper">
            <div class="penci-sticky-cart-title">
                <div class="product-image">
					<?php woocommerce_template_loop_product_thumbnail(); ?>
                </div>
                <div class="product-title">
                    <h3><?php the_title(); ?></h3>
                </div>
            </div>
            <div class="penci-sticky-cart-buttons">
                <div class="product-price">
			        <?php woocommerce_template_loop_price(); ?>
                </div>
                <div class="product-action">
			        <?php
			        if ( 'simple' == $product_type ) {
				        woocommerce_template_single_add_to_cart();
			        } else {
				        woocommerce_template_loop_add_to_cart();
			        }
			        ?>
                </div>
            </div>
        </div>
    </div>
</div>
