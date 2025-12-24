<?php
/**
 * Template part for Slider Style 41
 */
$image_size          = ! empty( $post_thumb_size ) ? $post_thumb_size : 'penci-slider-full-thumb';
if ( penci_is_mobile() ) {
	$image_size = ! empty( $post_thumb_size_mobile ) ? $post_thumb_size_mobile : 'penci-masonry-thumb';
}
$main_id  = 'fsm_' . rand();
$thumb_id = 'fst_' . rand();

$data_attr = '';
$data_attr .= ' data-auto="' . ( 'yes' == $settings['autoplay'] ? 'true' : 'false' ) . '"';
$data_attr .= ' data-autotime="' . ( $settings['auto_time'] ? intval( $settings['auto_time'] ) : '4000' ) . '"';
$data_attr .= ' data-speed="' . ( $settings['speed'] ? intval( $settings['speed'] ) : '600' ) . '"';
$data_attr .= ' data-loop="' . ( 'yes' == $settings['loop'] ? 'true' : 'false' ) . '"';

?>
<div class="penci-slider41-wrapper">
    <div class="penci-slider41-thumb-wrapper">
        <div class="penci-slider41-thumb penci-owl-carousel penci-owl-carousel-slider swiper" data-item="4"
             data-direction="vertical" data-desktop="4" data-tablet="3" data-mobile="3" data-margin="0"
             data-id="<?php echo $thumb_id; ?>"
             <?php echo $data_attr; ?>
             data-thumb="yes" data-height="false" data-loop="true" data-nav="false">
            <div class="penci-slider41-thumb-inner swiper-wrapper">
				<?php $thumbcount = 0;
				if ( $feat_query->have_posts() ) : while ( $feat_query->have_posts() ) : $feat_query->the_post(); ?>
                    <div class="item swiper-slide">

                    <div class="penci-slider41-t-item">

                        <div class="pcslider-41-img">
                        <div class="penci-image-holder"
                             style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $image_size ); ?>');"
                             href="<?php the_permalink(); ?>"
                             title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></div>
                        </div>
                        <div class="pcslider-41-ct">
                        <h3><?php echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $slider_title_length, '...' ); ?></h3>
                        </div>

                        </div>

                    </div>
				<?php endwhile;
					wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </div>
    <div class="penci-slider41-main-wrapper">
        <div class="penci-slider41-main penci-owl-carousel penci-owl-carousel-slider swiper" data-nav="true"
             data-loop="true" data-thumbs-id="<?php echo $thumb_id; ?>" data-id="<?php echo $main_id; ?>" <?php echo $data_attr; ?>>
            <div class="swiper-wrapper">
				<?php if ( $feat_query->have_posts() ) : while ( $feat_query->have_posts() ) : $feat_query->the_post(); ?>
                    <div class="item swiper-slide">
                        <a class="penci-slider41-overlay"
                           aria-label="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                           href="<?php the_permalink(); ?>"></a>
                        <div class="item-inner-content">
                            <a class="penci-image-holder"
                               style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $image_size ); ?>');"
                               href="<?php the_permalink(); ?>"
                               title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>

							<?php if ( ! $center_box ): ?>
                                <div class="penci-featured-content">
                                    <div class="feat-text<?php if ( $meta_date_hide ): echo ' slider-hide-date'; endif; ?>">
										<?php if ( ! $hide_categories && $show_cat ): ?>
                                            <div class="cat featured-cat"><?php penci_category( '' ); ?></div>
										<?php endif; ?>
                                        <h3><a href="<?php the_permalink() ?>"
                                               title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"><?php echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $slider_title_length, '...' ); ?></a>
                                        </h3>
										<?php if ( $settings['cspost_enable'] || ! $hide_meta_comment || ! $meta_date_hide || $show_viewscount || $show_meta_author ): ?>
                                            <div class="feat-meta fade-in penci-fslider-fmeta">
												<?php if ( $show_meta_author ): ?>
                                                    <span class="feat-author"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <a
                                                                href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></span>
												<?php endif; ?>
												<?php if ( ! $meta_date_hide ): ?>
                                                    <span class="feat-time"><?php penci_soledad_time_link(); ?></span>
												<?php endif; ?>
												<?php if ( ! $hide_meta_comment ): ?>
                                                    <span class="feat-comments"><a
                                                                href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
												<?php endif; ?>
												<?php
												if ( $show_viewscount ) {
													echo '<span class="feat-countviews">';
													echo penci_get_post_views( get_the_ID() );
													echo ' ' . penci_get_setting( 'penci_trans_countviews' );
													echo '</span>';
												}
												?>
												<?php echo penci_show_custom_meta_fields( [
													'validator' => isset( $settings['cspost_enable'] ) ? $settings['cspost_enable'] : '',
													'keys'      => isset( $settings['cspost_cpost_meta'] ) ? $settings['cspost_cpost_meta'] : '',
													'acf'       => isset( $settings['cspost_cpost_acf_meta'] ) ? $settings['cspost_cpost_acf_meta'] : '',
													'label'     => isset( $settings['cspost_cpost_meta_label'] ) ? $settings['cspost_cpost_meta_label'] : '',
													'divider'   => isset( $settings['cspost_cpost_meta_divider'] ) ? $settings['cspost_cpost_meta_divider'] : '',
												] ); ?>
                                                <?php do_action( 'penci_extra_meta' ); ?>
												<?php if ( get_the_excerpt() && ! $hide_meta_excerpt ): ?>
                                                    <div class="featured-slider-excerpt">
														<?php
														if ( get_theme_mod( 'penci_excerptcharac' ) ) {
															the_excerpt();
														} else {
															$excerpt_length = get_theme_mod( 'penci_post_excerpt_length', 30 );
															penci_the_excerpt( $excerpt_length );
														}
														?>
                                                    </div>
												<?php endif; ?>
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
    </div>
</div>