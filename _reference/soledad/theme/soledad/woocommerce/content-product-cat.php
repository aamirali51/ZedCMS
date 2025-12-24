<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$elementor_loop = wc_get_loop_prop( 'elementor' );
$style          = wc_get_loop_prop( 'product_categories_style' );
$index          = wc_get_loop_prop( 'loop' );
$loop_by        = wc_get_loop_prop( 'loop_by' );
$total          = wc_get_loop_prop( 'total' );
$before_item    = $after_item = '';

if ( ! in_array( $style, array( 'default', 'carousel' ) ) && $total - $index > 1 && $index && $loop_by ) {
	$after_item = ( $loop_by - 1 == $index % $loop_by ) ? '</ul><ul>' : '';
}

$item_class = $style == 'carousel' ? 'swiper-slide' : 'normal-item';

echo $before_item;
?>
    <li <?php wc_product_cat_class( $item_class, $category ); ?>>
        <div class="penci-product-cat-loop">
		    <?php
			/**
			 * The woocommerce_before_subcategory hook.
			 *
			 * @hooked woocommerce_template_loop_category_link_open - 10
			 */
			do_action( 'woocommerce_before_subcategory', $category );

			/**
			 * The woocommerce_before_subcategory_title hook.
			 *
			 * @hooked woocommerce_subcategory_thumbnail - 10
			 */
			do_action( 'woocommerce_before_subcategory_title', $category );

			/**
			 * The woocommerce_shop_loop_subcategory_title hook.
			 *
			 * @hooked woocommerce_template_loop_category_title - 10
			 */
			do_action( 'woocommerce_shop_loop_subcategory_title', $category );

			/**
			 * The woocommerce_after_subcategory_title hook.
			 */
			do_action( 'woocommerce_after_subcategory_title', $category );

			/**
			 * The woocommerce_after_subcategory hook.
			 *
			 * @hooked woocommerce_template_loop_category_link_close - 10
			 */
			do_action( 'woocommerce_after_subcategory', $category );
			?>
        </div>
    </li>
<?php echo $after_item;
