<?php
/**
 * Template loop for list style
 */
$post_id = get_the_ID();
?>
<li class="list-post pclist-layout">
    <article id="post-<?php the_ID(); ?>" class="item hentry">
		<?php if ( penci_get_post_format( 'gallery' ) ) : ?>
			<?php $images = get_post_meta( get_the_ID(), '_format_gallery_images', true ); ?>
			<?php if ( $images ) : ?>
                <div class="thumbnail">
					<?php do_action( 'penci_bookmark_post' ); ?>
                    <div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-nav-visible" data-auto="true">
                        <div class="swiper-wrapper">
							<?php foreach ( $images as $image ) : ?>
                                <div class="swiper-slide swiper-mark-item">

									<?php $the_image = wp_get_attachment_image_src( $image, penci_featured_images_size() ); ?>
									<?php $the_caption = get_post_field( 'post_excerpt', $image ); ?>


                                    <figure <?php echo penci_layout_bg( $the_image[0] ); ?> class="<?php echo penci_layout_bg_class();?> penci-swiper-mask penci-image-holder penci-lazy"
										<?php if ( $the_caption ) : ?> title="<?php echo esc_attr( $the_caption ); ?>"<?php endif; ?>>
										<?php echo penci_layout_img( $the_image[0], $the_caption ); ?>
                                    </figure>

                                </div>

							<?php endforeach; ?>
                        </div>
                    </div>
                </div>
			<?php endif; ?>

		<?php elseif ( has_post_thumbnail() ) : ?>
            <div class="thumbnail">
				<?php
				do_action( 'penci_bookmark_post' );
				/* Display Review Piechart  */
				if ( function_exists( 'penci_display_piechart_review_html' ) ) {
					penci_display_piechart_review_html( get_the_ID() );
				}
				?>


                <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), penci_featured_images_size() ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder<?php echo penci_class_lightbox_enable(); ?>"
                   href="<?php penci_permalink_fix(); ?>"
                   title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
					<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), penci_featured_images_size() ), get_the_title() ); ?>
                </a>


				<?php if ( ! get_theme_mod( 'penci_grid_icon_format' ) ): ?>
					<?php if ( has_post_format( 'video' ) ) : ?>
                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></a>
					<?php endif; ?>
					<?php if ( has_post_format( 'gallery' ) ) : ?>
                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                           aria-label="Icon"><?php penci_fawesome_icon( 'far fa-image' ); ?></a>
					<?php endif; ?>
					<?php if ( has_post_format( 'audio' ) ) : ?>
                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-music' ); ?></a>
					<?php endif; ?>
					<?php if ( has_post_format( 'link' ) ) : ?>
                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-link' ); ?></a>
					<?php endif; ?>
					<?php if ( has_post_format( 'quote' ) ) : ?>
                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-quote-left' ); ?></a>
					<?php endif; ?>
				<?php endif; ?>
            </div>
		<?php endif; ?>

        <div class="content-list-right content-list-center<?php if ( ! has_post_thumbnail() ) : echo ' fullwidth'; endif; ?>">
            <div class="header-list-style">
				<?php if ( ! get_theme_mod( 'penci_grid_cat' ) ) : ?>
                    <span class="cat"><?php penci_category( '' ); ?></span>
				<?php endif; ?>
                <h2 class="grid-title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php do_action( 'penci_after_post_title' ); ?>
				<?php $hide_readtime = get_theme_mod( 'penci_home_cat_readtime' ); ?>
				<?php if ( ! get_theme_mod( 'penci_home_featured_cat_date' ) || ! get_theme_mod( 'penci_home_featured_cat_author' ) || get_theme_mod( 'penci_home_featured_cat_comment' ) || get_theme_mod( 'penci_home_cat_views' ) || penci_isshow_reading_time( $hide_readtime ) ) : ?>
                    <div class="grid-post-box-meta">
						<?php if ( ! get_theme_mod( 'penci_home_featured_cat_author' ) ) : ?>
                            <span class="featc-author author-italic author"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <a
                                        class="url fn n"
                                        href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></span>
						<?php endif; ?>
						<?php if ( ! get_theme_mod( 'penci_home_featured_cat_date' ) ) : ?>
                            <span class="featc-date"><?php penci_soledad_time_link(); ?></span>
						<?php endif; ?>
						<?php if ( get_theme_mod( 'penci_home_featured_cat_comment' ) ) : ?>
                            <span class="featc-comment"><a
                                        href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
						<?php endif; ?>
						<?php if ( get_theme_mod( 'penci_home_cat_views' ) ) {
							echo '<span class="featc-views">';
							echo penci_get_post_views( get_the_ID() );
							echo ' ' . penci_get_setting( 'penci_trans_countviews' );
							echo '</span>';
						} ?>
						<?php if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                            <span class="featc-readtime"><?php penci_reading_time(); ?></span>
						<?php endif; ?>
						<?php do_action( 'penci_extra_meta' ); ?>
                    </div>
				<?php endif; ?>
            </div>

			<?php if ( get_the_excerpt() && ! get_theme_mod( 'penci_home_featured_cat_remove_excerpt' ) ): ?>
                <div <?php echo penci_excerpt_link(); ?>class="item-content entry-content">
					<?php
					do_action( 'penci_before_post_excerpt' );
					if ( get_theme_mod( 'penci_excerptcharac' ) ) {
						the_excerpt();
					} else {
						$excerpt_length = get_theme_mod( 'penci_post_excerpt_length', 30 );
						penci_the_excerpt( $excerpt_length, $post_id );
					}
					?>
                </div>
			<?php endif; ?>

        </div>
		<?php penci_soledad_meta_schema(); ?>
    </article>
</li>
