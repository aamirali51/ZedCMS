 <?php
/**
 * Template part for Slider Style 29
 */

$feat_query = penci_get_query_featured_slider();
if ( ! $feat_query ) {
	return;
}
$slider_title_length = get_theme_mod( 'penci_slider_title_length' ) ? get_theme_mod( 'penci_slider_title_length' ) : 20;
$image_size          = get_theme_mod( 'featured_slider_imgsize' ) ? get_theme_mod( 'featured_slider_imgsize' ) : 'penci-slider-full-thumb';

	$image_size_m = get_theme_mod( 'featured_slider_imgsize_mobile' ) ? get_theme_mod( 'featured_slider_imgsize_mobile' ) : 'penci-masonry-thumb';

?>
<?php if ( $feat_query->have_posts() ) : while ( $feat_query->have_posts() ) : $feat_query->the_post(); ?>
    <div class="item swiper-slide swiper-mark-item">
	    <?php do_action( 'penci_bookmark_post' ); ?>
		<?php if ( ! get_theme_mod( 'penci_disable_lazyload_slider' ) ) { ?>
            <div class="penci-swiper-mask penci-image-holder <?php echo penci_classes_slider_lazy(); ?>"
               data-bgset="<?php echo penci_image_srcset( get_the_ID(), $image_size,$image_size_m ); ?>"
               href="<?php the_permalink(); ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
		<?php } else { ?>
            <div class="penci-swiper-mask penci-image-holder"
               style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), penci_is_mobile() ? $image_size_m : $image_size ); ?>');"
               href="<?php the_permalink(); ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
		<?php } ?>
        <a href="<?php the_permalink() ?>" class="featured-slider-overlay"></a>
		<?php if ( ! get_theme_mod( 'penci_featured_center_box' ) ): ?>
            <div class="penci-featured-content">
                <div class="feat-text<?php if ( get_theme_mod( 'penci_featured_meta_date' ) ): echo ' slider-hide-date'; endif; ?>">
					<?php if ( ! get_theme_mod( 'penci_featured_hide_categories' ) ): ?>
                        <div class="cat featured-cat"><?php penci_category( '' ); ?></div>
					<?php endif; ?>
                    <h3><a title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                           href="<?php the_permalink() ?>"><?php echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $slider_title_length, '...' ); ?></a>
                    </h3>
                    <div class="penci-featured-slider-button">
                        <a href="<?php the_permalink() ?>"><?php echo penci_get_setting( 'penci_trans_read_more' ); ?></a>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>
    </div>
<?php endwhile;
	wp_reset_postdata(); endif; ?>
