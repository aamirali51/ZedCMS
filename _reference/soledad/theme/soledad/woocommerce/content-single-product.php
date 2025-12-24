<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
$sticky            = get_theme_mod( 'penci_single_product_sticky_thumbnail_content', false );
$sticky_class      = $sticky ? 'penci-main-sticky-sidebar' : 'penci-main-normal-sidebar';

$sticky_content            = get_theme_mod( 'penci_sidebar_sticky', false );
$sticky_content_class      = $sticky_content ? 'penci-content-sticky-sidebar' : 'penci-content-normal-sidebar';

$sidebar_placement = penci_woo_sidebar_placement();

$container_class = 'both' == $sidebar_placement ? 'container-wrap' : 'container';
$classes         = 'both' == $sidebar_placement ? 'container sidebar-both' : 'container-wrap sidebar-bottom';
?>
<div class="container">
	<?php
	/**
	 * Hook: woocommerce_before_single_product.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
		echo get_the_password_form(); // WPCS: XSS ok.

		return;
	}
	?>
</div>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $classes, $product ); ?>>

	<?php if ( 'both' == $sidebar_placement ): ?>

    <div class="penci-single-product-sidebar-wrap">
        <div class="theiaStickySidebar">
			<?php endif; ?>


            <div class="penci-single-product-top-container">
                <div class="<?php echo esc_attr( $container_class ); ?>">
					<?php
					/**
					 * Hook: woocommerce_before_single_product_summary.
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
					do_action( 'woocommerce_before_single_product_summary' );
					?>

                    <div class="penci-product-summary summary entry-summary <?php echo esc_attr( $sticky_class ); ?>">
                        <div class="theiaStickySidebar">
							<?php
							/**
							 * Hook: woocommerce_single_product_summary.
							 *
							 * @hooked woocommerce_template_single_title - 5
							 * @hooked woocommerce_template_single_rating - 10
							 * @hooked woocommerce_template_single_price - 10
							 * @hooked woocommerce_template_single_excerpt - 20
							 * @hooked woocommerce_template_single_add_to_cart - 30
							 * @hooked woocommerce_template_single_meta - 40
							 * @hooked woocommerce_template_single_sharing - 50
							 * @hooked WC_Structured_Data::generate_product_data() - 60
							 */
							do_action( 'woocommerce_single_product_summary' );
							?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="penci-single-product-bottom-container">
                <div class="<?php echo esc_attr( $container_class . ' ' . $sticky_content_class ); ?>">
                    <div class="bottom-content">
                        <div class="theiaStickySidebar">
							<?php
							/**
							 * Hook: woocommerce_after_single_product_summary.
							 *
							 * @hooked woocommerce_output_product_data_tabs - 10
							 * @hooked woocommerce_upsell_display - 15
							 * @hooked woocommerce_output_related_products - 20
							 */
							do_action( 'woocommerce_after_single_product_summary' );
							?>
                        </div>
                    </div>
					<?php
					if ( 'bottom' == $sidebar_placement ) {
						do_action( 'penci_single_product_sidebar' );
					}
					?>
                </div>
            </div>
			<?php if ( 'both' == $sidebar_placement ): ?>
        </div>
    </div>
<?php
do_action( 'penci_single_product_sidebar' );
endif; ?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
