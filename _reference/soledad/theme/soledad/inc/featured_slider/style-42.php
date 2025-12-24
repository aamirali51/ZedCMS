<?php
/**
 * Template part for Slider Style 42
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
$data_auto    = get_theme_mod( 'penci_featured_autoplay' ) ? 'true' : 'false';
$data_loop    = get_theme_mod( 'penci_featured_loop' ) ? 'false' : 'true';
$auto_time    = is_numeric( $auto_time = get_theme_mod( 'penci_featured_slider_auto_time' ) ) ? $auto_time : '4000';
$auto_speed   = is_numeric( $auto_speed = get_theme_mod( 'penci_featured_slider_auto_speed' ) ) ? $auto_speed : '600';
?>
<div class="penci-slider42-wrapper">
    <div class="penci-slider42-thumb-wrapper">
        <div class="penci-slider42-thumb penci-owl-carousel penci-owl-carousel-slider swiper" data-item="4"
             data-desktop="4" data-tablet="3" data-mobile="2" data-margin="15"
             data-id="<?php echo $thumb_id; ?>"
             data-auto="<?php echo $data_auto;?>" data-autotime="<?php echo $auto_time;?>" data-speed="<?php echo $auto_speed;?>"
             data-thumb="yes" data-height="false" data-loop="true" data-nav="false" data-e="false" data-auto="false">
            <div class="penci-slider42-thumb-inner swiper-wrapper">
				<?php $thumbcount = 0;
				if ( $feat_query->have_posts() ) : while ( $feat_query->have_posts() ) : $feat_query->the_post(); ?>
                    <div class="item swiper-slide">

                    <div class="penci-slider42-t-item">

                       
                        <div class="pcslider-42-ct">
                        <h3><?php echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $slider_title_length, '...' ); ?></h3>
                        </div>

                        </div>

                    </div>
				<?php endwhile;
					wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </div>
    <div class="penci-slider42-main-wrapper">
        <div class="penci-slider42-main penci-owl-carousel penci-owl-carousel-slider swiper" data-nav="true"
             data-loop="true" data-thumbs-id="<?php echo $thumb_id; ?>" data-id="<?php echo $main_id; ?>" data-auto="<?php echo $data_auto;?>" data-autotime="<?php echo $auto_time;?>" data-speed="<?php echo $auto_speed;?>">
            <div class="swiper-wrapper">
				<?php if ( $feat_query->have_posts() ) : while ( $feat_query->have_posts() ) : $feat_query->the_post(); ?>
                    <div class="item swiper-slide">
                        <a class="penci-slider42-overlay"
                           aria-label="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                           href="<?php the_permalink(); ?>"></a>
                        <div class="item-inner-content">
                            <a class="penci-image-holder"
                               style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $image_size ); ?>');"
                               href="<?php the_permalink(); ?>"
                               title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>

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
                                            <div class="feat-meta fade-in penci-fslider-fmeta">
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
                                                <?php do_action( 'penci_extra_meta' ); ?>
												<?php if ( get_the_excerpt() && ! get_theme_mod( 'penci_featured_meta_excerpt' ) ): ?>
                                                    <div class="featured-slider-excerpt">
                                                        <p><?php $excerpt = get_the_excerpt();
															echo wp_trim_words( $excerpt, 20, '...' ); ?></p></div>
												<?php endif; ?>
                                            </div>
										<?php endif; ?>
                                        
                                    </div>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
				<?php endwhile;
					wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </div>
</div>