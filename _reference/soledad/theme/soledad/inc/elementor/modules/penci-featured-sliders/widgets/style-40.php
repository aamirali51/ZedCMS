<?php
/**
 * Template part for Slider Style 40
 */

$image_size      = ! empty( $post_thumb_size ) ? $post_thumb_size : 'penci-slider-full-thumb';
$penci_is_mobile = penci_is_mobile();
if ( penci_is_mobile() ) {
	$image_size = ! empty( $post_thumb_size_mobile ) ? $post_thumb_size_mobile : 'penci-masonry-thumb';
}
?>
<div class="slider-40-wrapper">
    <div class="img-blur-container">
        <div class="container-bg-slider-40">
			<?php
			$count = 0;
			while ( $feat_query->have_posts() ) : $feat_query->the_post();
				$ft_class = $count ++ == 0 ? ' current' : '';
				?>
                <div data-index="<?php echo $count; ?>" class="item-bg-slider-40 <?php echo $ft_class; ?>">
                    <div class="item-bg-slider-40-content">
                    	<?php if ( ! $disable_lazyload ) { ?>
                    	<div data-bgset="<?php echo penci_image_srcset( get_the_ID(), $image_size ); ?>" class="<?php echo penci_classes_slider_lazy(); ?> picturefill-background fill clip bg-img-slider-40-behind"></div>
                    	<?php } else { ?>
                        <div style="background-image: url(<?php echo penci_get_featured_image_size( get_the_ID(), $image_size ); ?>);" class="picturefill-background fill clip bg-img-slider-40-behind"></div>
                        <?php } ?>
                    </div>
                </div>
			<?php endwhile; ?>
        </div>
    </div>
    <div class="container-slider-40">
        <div class="galery-slider-40">
            <div class="slider-creative">
                <div class="nav-slider-slider-40 nav-slider hidden-xs">
                    <button id="prev-hl" class="btn-reset nav-slider-button prev-button waves-effect"><i
                                class="penciicon-left-chevron"></i></button>
                    <button id="next-hl" class="btn-reset nav-slider-button next-button waves-effect"><i
                                class="penciicon-right-chevron"></i></button>
                </div>
                <div class="list-slider-creative">

					<?php while ( $feat_query->have_posts() ) : $feat_query->the_post(); ?>
                        <div class="item-slider-creative">
                            <div class="content-item-creative">
                                <div class="main-slide penci-43-slider-item content-item-creative">
                                	<?php if ( ! $disable_lazyload ) { ?>
                                	<div data-bgset="<?php echo penci_image_srcset( get_the_ID(), $image_size ); ?>" class="<?php echo penci_classes_slider_lazy(); ?> picturefill-background fill clip img-container"></div>
                                	<?php } else { ?>
                                    <div style="background-image: url(<?php echo penci_get_featured_image_size( get_the_ID(), $image_size ); ?>);" class="picturefill-background fill clip img-container"></div>
                                    <?php } ?>
                                    <div>
										<?php if ( ! $hide_categories ): ?>
                                            <div class="cat featured-cat number-tv small-title-opa"><?php penci_category( '' ); ?></div>
										<?php endif; ?>
                                        <h3 class="title-part white-title"><a
                                                    title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                                    href="<?php the_permalink() ?>"><?php echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $slider_title_length, '...' ); ?></a>
                                        </h3>

										<?php if ( $settings['cspost_enable'] || ! $hide_meta_comment || ! $meta_date_hide || $show_viewscount || $show_meta_author ): ?>
                                            <div class="feat-meta penci-fslider-fmeta">
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
                                            </div>
										<?php endif; ?>

                                        <?php if ( get_the_excerpt() && ! $hide_meta_excerpt ): ?>
                                            <div class="featured-slider-excerpt">
                                                <p><?php $excerpt = get_the_excerpt();
                                                    echo wp_trim_words( $excerpt, 20, '...' ); ?></p></div>
                                        <?php endif; ?>

                                        <div class="penci-featured-slider-button">
                                            <a href="<?php the_permalink() ?>"><?php echo penci_get_setting( 'penci_trans_read_more' ); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php endwhile; ?>
                </div>
            </div>
            
        </div>

    </div>
    <div class="container-thumb-creative">
		<div class="swiper nav-thumb-creative penci-owl-carousel penci-owl-carousel-slider" data-item="4"
			 data-desktop="4" data-loop="false" data-nav="false" data-thumb="yes" data-auto="false">
			<div class="swiper-wrapper">
				<?php
				$countz = 0;
				while ( $feat_query->have_posts() ) : $feat_query->the_post();
					$ftz_class = $countz ++ == 0 ? ' current' : '';
					?>
					<div class="swiper-slide">
						<div class="swiper-slide-inner">
							<a data-index="<?php echo $countz; ?>" href="<?php the_permalink() ?>"
							   class="thumb-container<?php echo $ftz_class; ?>">
							   <?php if ( ! $disable_lazyload ) { ?>
							   	<span class="<?php echo penci_classes_slider_lazy(); ?> penci-image-holder" data-bgset="<?php echo penci_image_srcset( get_the_ID(), 'penci-masonry-thumb' ); ?>"></span>
							   <?php } else { ?>
							   <span class="penci-image-holder" style="background-image: url(<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-masonry-thumb' ); ?>);"></span>
								<?php } ?>
							</a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>