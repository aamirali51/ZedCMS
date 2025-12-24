<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */
defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;
$product_id          = $product->get_id();
$img_width           = get_theme_mod( 'penci_single_product_img_width', 'standard' );
$img_width           = penci_get_single_product_meta( $product_id, 'product_general', 'penci_single_product_img_width', $img_width );
$img_width           = penci_is_mobile() ? 'standard' : $img_width;
$sticky              = get_theme_mod( 'penci_single_product_sticky_thumbnail_content', false );
$sticky_class        = $sticky ? 'penci-sticky-sidebar' : 'penci-normal-sidebar';
$quickview           = wc_get_loop_prop( 'quickview', false );
$quickview_class     = $quickview ? 'quickview-screen' : 'normal-screen';
$columns             = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id   = $product->get_image_id();
$attachment_ids      = $product->get_gallery_image_ids();
$thumbnail_position  = get_theme_mod( 'penci_single_product_thumbnail_position', 'thumbnail-left' );
$thumbnail_position  = penci_get_single_product_meta( $product_id, 'product_general', 'penci_single_product_thumbnail_position', $thumbnail_position );
$thumbnail_position  = $attachment_ids && $product->get_image_id() ? $thumbnail_position : 'thumbnail-left';
$thumbnail_position  = ( 'fullwidth-container' == $img_width || 'fullwidth' == $img_width ) ? 'thumbnail-without' : $thumbnail_position;
$thumbnail_position  = $quickview || penci_is_mobile() ? 'thumbnail-bottom' : $thumbnail_position;
$slider              = $attachment_ids && in_array( $thumbnail_position, array(
	'thumbnail-left',
	'thumbnail-right',
	'thumbnail-bottom',
	'thumbnail-without'
) ) ? true : '';
$wrapper_classes     = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
		'no-js',
		$thumbnail_position,
		$sticky_class,
		$quickview_class
	)
);
$thumbnail_class     = $slider ? 'penci-thumbnail-slider splide' : 'penci-thumbnail-grid';
$thumbnail_fig_class = $slider ? 'splide__slide woocommerce-product-thumbnail' : 'woocommerce-product-thumbnail';
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>"
     data-columns="<?php echo esc_attr( $columns ); ?>">
    <div class="theiaStickySidebar">

		<?php if ( $slider ): ?>

        <div class="penci-product-gallery-slider splide <?php echo esc_attr( $img_width ); ?>">
            <div class="penci-product-gallery-items swiper splide__track">
                <div class="splide__list penci-gallery-image-list swiper-wrapper">
					<?php endif; ?>
                    <figure data-slide_item="0" data-attr_id="<?php echo esc_attr( $post_thumbnail_id ); ?>"
                            class="swiper-slide splide__slide splide__slide-<?php echo esc_attr( $post_thumbnail_id ); ?> woocommerce-product-gallery__wrapper">
						<?php
						if ( $post_thumbnail_id ) {
							$html = penci_get_gallery_image_html( $post_thumbnail_id, true );
						} else {
							$html = '<div class="woocommerce-product-gallery__image--placeholder">';
							$html .= sprintf( '<img data-src="%s" alt="%s" class="penci-lazy wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
							$html .= '</div>';
						}

						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
						?>
                    </figure>
					<?php
					if ( $attachment_ids && $product->get_image_id() && $slider ) {
						$slide = 1;
						foreach ( $attachment_ids as $attachment_id ) {

							echo '<figure data-slide_item="' . $slide ++ . '" data-attr_id="' . esc_attr( $attachment_id ) . '" class="swiper-slide splide__slide splide__slide-' . esc_attr( $attachment_id ) . ' woocommerce-product-gallery__wrapper">';

							if ( $attachment_id ) {
								$html = penci_get_gallery_image_html( $attachment_id, true );
							} else {
								$html = '<div class="woocommerce-product-gallery__image--placeholder">';
								$html .= sprintf( '<img data-src="%s" alt="%s" class="penci-lazy wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
								$html .= '</div>';
							}

							echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

							echo '</figure>';
						}
					}
					?>

					<?php if ( $slider ): ?>
                </div>
            </div>
        </div>
	<?php endif; ?>

		<?php if ( $attachment_ids && $product->get_image_id() && 'thumbnail-without' != $thumbnail_position ) : ?>
            <div class="<?php echo esc_attr( $thumbnail_class ); ?>">
				<?php if ( $slider ):
				array_unshift( $attachment_ids, $product->get_image_id() );
				?>
                <div class="splide__track swiper penci-thumbnail-image-wrapper">
                    <div class="penci-thumbnail-image-list splide__list swiper-wrapper"
                         data-total-slides="<?php echo count( $attachment_ids ); ?>">
						<?php
						endif;
						if ( $attachment_ids && $product->get_image_id() ) {
							$item_count = 0;
							foreach ( $attachment_ids as $attachment_id ) {
								echo '<figure class="swiper-slide item-' . $item_count . ' ' . esc_attr( $thumbnail_fig_class ) . '">';
								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', penci_get_gallery_image_html( $attachment_id, $slider ? false : true, $slider ), $attachment_id );
								echo '</figure>';
								$item_count ++;
							}
						}
						if ( $slider ): ?>
                    </div>
                </div>
                <div class="penci-custom-thumbnail-nav">
                    <button data-slide="-" class="penci-product-slider-prev"><i class="penciicon-up-chevron"></i><span
                                class="screen-reader-text"><?php echo esc_attr__( 'Previous', 'soledad' ); ?></span>
                    </button>
                    <button data-slide="+"
                            class="penci-product-slider-next"><i class="penciicon-down-chevron"></i><span
                                class="screen-reader-text"><?php echo esc_attr__( 'Next', 'soledad' ); ?></span>
                    </button>
                </div>
			<?php endif; ?>
            </div>
		<?php endif; ?>
    </div>
</div>
