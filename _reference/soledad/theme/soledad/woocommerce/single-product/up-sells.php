<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version 	9.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) :
	$classess = array();
	$total = count( $upsells );
	$columns = get_theme_mod( 'penci_woo_upsell_columns', wc_get_loop_prop( 'columns' ) );
	$classess[] = $total > $columns || penci_is_mobile() ? 'slider' : 'normal';
	?>

    <section class="up-sells upsells products <?php echo implode( ' ', $classess ); ?>">
		<?php
		$heading = apply_filters( 'woocommerce_product_upsells_products_heading', penci_woo_translate_text( 'penci_woo_trans_upsell_title' ) );

		if ( $heading ) :
			?>
            <h2><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<?php woocommerce_product_loop_start(); ?>

		<?php foreach ( $upsells as $upsell ) : ?>

			<?php
			$post_object = get_post( $upsell->get_id() );

			setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

			wc_get_template_part( 'content', 'product' );
			?>

		<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

    </section>

<?php
endif;

wp_reset_postdata();
