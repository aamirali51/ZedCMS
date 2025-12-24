<?php
/**
 * Template part for Slider Style 26
 */

$post_thumb_size  = ! empty( $post_thumb_size ) ? $post_thumb_size : 'penci-thumb';
$bpost_thumb_size = ! empty( $bpost_thumb_size ) ? $bpost_thumb_size : 'penci-thumb-square';
$penci_is_mobile  = penci_is_mobile();
?>
<?php if ( $feat_query->have_posts() ) : ?>
    <div class="item swiper-slide">
        <div class="wrapper-item wrapper-item-classess">
			<?php $i   = 1;
			$num_posts = $feat_query->post_count;
			while ( $feat_query->have_posts() ) : $feat_query->the_post();
				$thumb = $post_thumb_size;
				$psize = 'small';
				if ( $i % 5 == 1 ): $thumb = $bpost_thumb_size;
					$psize                 = 'normal'; endif;

				$thumb_mobile = ! empty( $post_thumb_size_mobile ) ? $post_thumb_size_mobile : 'penci-masonry-thumb';

				?>
                <div class="penci-item-mag penci-item-<?php echo( $i % 5 ); ?>  <?php echo( ( $i % 5 == 1 ) ? 'penci-pitem-big' : 'penci-pitem-small' ) ?>">
					<?php do_action( 'penci_bookmark_post', get_the_ID(), $psize ); ?>
					<?php if ( ! $disable_lazyload ) { ?>
                        <a class="penci-image-holder <?php echo penci_classes_slider_lazy(); ?>"
                           data-bgset="<?php echo penci_image_srcset( get_the_ID(), $thumb, $thumb_mobile ); ?>"
                           href="<?php the_permalink(); ?>"
                           title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>
					<?php } else { ?>
                        <a class="penci-image-holder"
                           style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $penci_is_mobile ? $thumb_mobile : $thumb ); ?>');"
                           href="<?php the_permalink(); ?>"
                           title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>
					<?php } ?>
                    <div class="penci-slide-overlay penci-slider6-overlay">
                        <a class="overlay-link"
                           aria-label="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                           href="<?php the_permalink(); ?>"></a>
						<?php if ( ! $hide_format_icons && ( has_post_format( 'video' ) || has_post_format( 'audio' ) || has_post_format( 'link' ) || has_post_format( 'quote' ) || has_post_format( 'gallery' ) ) ): ?>
                            <a href="<?php the_permalink(); ?>"
                               class="overlay-icon-format<?php if ( $i % 5 == 1 ): echo ' lager-size-icon'; endif; ?>">
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
								<?php if ( ( $i % 5 == 1 || $show_cat ) && ! $hide_categories ): ?>
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
											'label'     => $cspost_cpost_meta_label,
											'divider'   => $cspost_cpost_meta_divider,
										] ); ?>
										<?php do_action( 'penci_extra_meta' ); ?>
                                    </div>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

				<?php if ( $i % 5 == 0 && $i > 1 && $i < $num_posts ): echo '</div></div><div class="item swiper-slide"><div class="wrapper-item wrapper-item-classess">'; endif; ?>

				<?php $i ++; endwhile;
			wp_reset_postdata(); ?>
        </div>
    </div>
<?php endif; ?>
