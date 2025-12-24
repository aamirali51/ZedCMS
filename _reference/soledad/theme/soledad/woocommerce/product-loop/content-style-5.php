<?php
/**
 * Hook: woocommerce_before_shop_loop_item.
 *
 * @hooked woocommerce_template_loop_product_link_open - 10
 */
?>
<div class="penci-product-loop-top">
    <div class="penci-product-loop-image">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item_title.
		 *
		 * @hooked woocommerce_show_product_loop_sale_flash - 10
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );
		do_action( 'woocommerce_before_shop_loop_item_title' );
		do_action( 'penci_loop_product_image' );
		?>
    </div>
    <div class="penci-product-loop-buttons">
        <div class="penci-product-loop-button">
			<?php
			do_action( 'penci_loop_product_buttons' );
			?>
        </div>
    </div>
    <div class="penci-product-loop-extra-buttons">
		<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
    </div>
</div>
<div class="penci-product-loop-title">
	<?php

	do_action( 'penci_swatches_loop' );
	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_after_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );
	do_action( 'penci_after_shop_loop' );
	?>
</div>
