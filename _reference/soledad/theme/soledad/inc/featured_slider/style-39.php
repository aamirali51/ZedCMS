<?php
/**
 * Template part for Slider Style 39
 */

$feat_query = penci_get_query_featured_slider();
if ( ! $feat_query ) {
	return;
}
$slider_title_length = get_theme_mod( 'penci_slider_title_length' ) ? get_theme_mod( 'penci_slider_title_length' ) : 12;
$image_size          = get_theme_mod( 'featured_slider_imgsize' ) ? get_theme_mod( 'featured_slider_imgsize' ) : 'penci-full-thumb';
if ( penci_is_mobile() ) {
	$image_size = get_theme_mod( 'featured_slider_imgsize_mobile' ) ? get_theme_mod( 'featured_slider_imgsize_mobile' ) : 'penci-full-thumb';
}
$main_id  = 'fsm_' . rand();
$thumb_id = 'fst_' . rand();
?>
<div class="penci-slider39-thumb penci-owl-carousel penci-owl-carousel-slider swiper" data-item="5"
     data-direction="vertical" data-desktop="5" data-tablet="3" data-margin="30" data-id="<?php echo $thumb_id; ?>"
     data-thumb="yes" data-height="true" data-loop="true">
    <div class="penci-slider39-thumb-inner swiper-wrapper">
		<?php $thumbcount = 0;
		if ( $feat_query->have_posts() ) : while ( $feat_query->have_posts() ) : $feat_query->the_post(); ?>
            <div class="item swiper-slide owl-thumb-item">

                <div class="penci-image-holder"
                     style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $image_size ); ?>');"
                     href="<?php the_permalink(); ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></div>
                <h3><?php echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $slider_title_length, '...' ); ?></h3>

            </div>
		<?php endwhile;
			wp_reset_postdata(); endif; ?>
    </div>
</div>
<div class="penci-slider39-main penci-owl-carousel penci-owl-carousel-slider swiper" data-nav="false" data-loop="true"
     data-thumbs-id="<?php echo $thumb_id; ?>" data-id="<?php echo $main_id; ?>">
    <div class="swiper-wrapper">
		<?php if ( $feat_query->have_posts() ) : while ( $feat_query->have_posts() ) : $feat_query->the_post(); ?>
            <div class="item swiper-slide">
                <a class="penci-slider39-overlay" aria-label="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                   href="<?php the_permalink(); ?>"></a>
                <div class="item-inner-content">
                    <a class="penci-image-holder"
                       style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $image_size ); ?>');"
                       href="<?php the_permalink(); ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>

					<?php if ( ! get_theme_mod( 'penci_featured_center_box' ) ): ?>
                        <div class="penci-featured-content">
                            <div class="feat-text<?php if ( get_theme_mod( 'penci_featured_meta_date' ) ): echo ' slider-hide-date'; endif; ?>">
								<?php if ( ! get_theme_mod( 'penci_featured_hide_categories' ) ): ?>
                                    <div class="cat featured-cat"><?php penci_category( '' ); ?></div>
								<?php endif; ?>
                                <h3><a href="<?php the_permalink() ?>"
                                       title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"><?php echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $slider_title_length, '...' ); ?></a>
                                </h3>
								<?php if ( ( get_the_excerpt() && ! get_theme_mod( 'penci_featured_meta_excerpt' ) ) || ! get_theme_mod( 'penci_featured_meta_comment' ) || ! get_theme_mod( 'penci_featured_meta_date' ) || get_theme_mod( 'penci_featured_meta_author' ) ): ?>
                                    <div class="feat-meta">
										<?php if ( get_theme_mod( 'penci_featured_meta_author' ) ): ?>
                                            <span class="feat-author"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <a
                                                        href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></span>
										<?php endif; ?>
										<?php if ( ! get_theme_mod( 'penci_featured_meta_date' ) ): ?>
                                            <span class="feat-time"><?php penci_soledad_time_link(); ?></span>
										<?php endif; ?>
										<?php if ( ! get_theme_mod( 'penci_featured_meta_comment' ) ): ?>
                                            <span class="feat-comments"><a
                                                        href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
										<?php endif; ?>
										<?php if ( get_the_excerpt() && ! get_theme_mod( 'penci_featured_meta_excerpt' ) ): ?>
                                            <div class="featured-slider-excerpt"><p><?php $excerpt = get_the_excerpt();
													echo wp_trim_words( $excerpt, 20, '...' ); ?></p></div>
										<?php endif; ?>
                                        <?php do_action( 'penci_extra_meta' ); ?>
                                    </div>
								<?php endif; ?>
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
    </div>
</div>