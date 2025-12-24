<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$penci_is_mobile = penci_is_mobile();
$product_style   = penci_get_single_product_meta( get_the_ID(), 'product_general', 'penci_single_product_style', get_theme_mod( 'penci_single_product_style', 'default' ) );
$product_tabs    = apply_filters( 'woocommerce_product_tabs', array() );
add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );
add_filter( 'woocommerce_product_description_heading', '__return_false' );
if ( ! empty( $product_tabs ) ) : ?>

	<?php if ( 'default' == $product_style ): ?>

        <div class="woocommerce-tabs wc-tabs-wrapper">
            <ul class="tabs wc-tabs" role="tablist">
				<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                    <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>"
                        role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                        <a href="#tab-<?php echo esc_attr( $key ); ?>">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                        </a>
                    </li>
				<?php endforeach; ?>
            </ul>
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel woocommerce-single-tab-content post-entry entry-content wc-tab"
                     id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
                     aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					?>
                </div>
			<?php endforeach; ?>

			<?php do_action( 'woocommerce_product_after_tabs' ); ?>
        </div>
	<?php else:
		?>
        <div class="woocommerce-accordion wc-accordion-wrapper">
			<?php
			$tab_count          = 1;
			foreach ( $product_tabs as $key => $product_tab ) :
				$classes = $tab_count == 1 ? 'active' : 'normal';
				$parent_classes = $tab_count == 1 ? 'parent-active' : 'normal';
				$display        = $tab_count == 1 ? 'block' : 'none';
				?>
                <div class="woocommerce-accordion-item <?php echo esc_attr( $parent_classes ); ?>">
                    <h4 class="woocommerce-accordion-title <?php echo esc_attr( $classes ); ?>"><a
                                data-toggle="collapse"
                                href="#accordion-<?php echo esc_attr( $key ); ?>"
                                aria-expanded="false"
                                aria-controls="#accordion-<?php echo esc_attr( $key ); ?>">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                        </a></h4>
                    <div class="woocommerce-accordion-panel woocommerce-accordion-panel--<?php echo esc_attr( $key ); ?> panel woocommerce-single-tab-content post-entry entry-content wc-accordion"
                         id="accordion-<?php echo esc_attr( $key ); ?>"
                         aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>"
                         style="display:<?php echo esc_attr( $display ); ?>">
						<?php
						if ( isset( $product_tab['callback'] ) ) {
							call_user_func( $product_tab['callback'], $key, $product_tab );
						}
						?>
                    </div>
                </div>
				<?php
				$tab_count ++;
			endforeach;
			do_action( 'woocommerce_product_after_tabs' ); ?>
        </div>
	<?php endif; ?>

<?php endif; ?>
