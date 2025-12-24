<?php
/**
 * Template part for Slider Style 9
 */

$post_thumb_size = ! empty( $post_thumb_size ) ? $post_thumb_size : 'penci-thumb-square';
$penci_is_mobile = penci_is_mobile();

	$post_thumb_msize = ! empty( $post_thumb_size_mobile ) ? $post_thumb_size_mobile : 'penci-masonry-thumb';

?>
<?php if ( $feat_query->have_posts() ) : ?>
	<?php $i   = 1;
	$num_posts = $feat_query->post_count;
	while ( $feat_query->have_posts() ) : $feat_query->the_post();
		?>
        <div class="item swiper-slide">
            <div class="wrapper-item wrapper-item-classess">
                <div class="penci-item-mag penci-item-<?php echo( $i % 3 ); ?>">
	                <?php do_action( 'penci_bookmark_post' ); ?>
					<?php if ( ! $disable_lazyload ) { ?>
                        <a class="penci-image-holder <?php echo penci_classes_slider_lazy(); ?>"
                           data-bgset="<?php echo penci_image_srcset( get_the_ID(),$post_thumb_size,$post_thumb_msize ); ?>"
                           href="<?php the_permalink(); ?>"
                           title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>
					<?php } else { ?>
                        <a class="penci-image-holder"
                           style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $penci_is_mobile ? $post_thumb_msize : $post_thumb_size ); ?>');"
                           href="<?php the_permalink(); ?>"
                           title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>
					<?php } ?>
                    <div class="penci-slide-overlay penci-slider7-overlay">
                        <a class="overlay-link"
                           aria-label="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                           href="<?php the_permalink(); ?>"></a>
						<?php if ( ! $hide_format_icons && ( has_post_format( 'video' ) || has_post_format( 'audio' ) || has_post_format( 'link' ) || has_post_format( 'quote' ) || has_post_format( 'gallery' ) ) ): ?>
                            <a href="<?php the_permalink(); ?>" class="overlay-icon-format lager-size-icon">
								<?php if ( has_post_format( 'video' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-play' ); ?>
								<?php endif; ?>
								<?php if ( has_post_format( 'audio' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-music' ); ?>
								<?php endif; ?>
								<?php if ( has_post_format( 'link' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-link' ); ?>
								<?php endif; ?>
								<?php if ( has_post_format( 'quote' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-quote-left' ); ?>
								<?php endif; ?>
								<?php if ( has_post_format( 'gallery' ) ) : ?>
									<?php penci_fawesome_icon( 'far fa-image' ); ?>
								<?php endif; ?>
                            </a>
						<?php endif; ?>
                        <div class="penci-mag-featured-content">
                            <div class="feat-text<?php if ( $meta_date_hide ): echo ' slider-hide-date'; endif; ?>">
								<?php if ( ! $hide_categories && $show_cat ): ?>
                                    <div class="cat featured-cat"><?php penci_category( '' ); ?></div>
								<?php endif; ?>
                                <h3><a title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                       href="<?php the_permalink() ?>"><?php echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $slider_title_length, '...' ); ?></a>
                                </h3>
								<?php if ( $cspost_enable || ! $hide_meta_comment || ! $meta_date_hide || $show_meta_author ): ?>
                                    <div class="feat-meta">
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
	                                    <?php echo penci_show_custom_meta_fields( [
		                                    'validator' => $cspost_enable,
		                                    'keys'      => $cspost_cpost_meta,
		                                    'acf'       => $cspost_cpost_acf_meta,
		                                    'label' 	=> $cspost_cpost_meta_label,
		                                    'divider' 	=> $cspost_cpost_meta_divider,
	                                    ] ); ?>
										<?php do_action( 'penci_extra_meta' ); ?>
                                    </div>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php $i ++; endwhile;
	wp_reset_postdata(); ?>
<?php endif; ?>
