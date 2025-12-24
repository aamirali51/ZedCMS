<?php
/**
 * Template loop for magazine style 2
 */
if ( ! isset( $j ) ) {
	$j = 1;
} else {
	$j = $j;
}
$post_id = get_the_ID();
$heading_title_tag = get_theme_mod( 'penci_grid_title_tag', 'h2' );
$heading_title_tag_class = 'h2' == $heading_title_tag ? 'penci_grid_title_df' : 'penci_grid_title';
?>
<li class="grid-style magazine-layout magazine-2
<?php
if ( get_theme_mod( 'penci_grid_meta_overlay' ) ) :
	echo ' grid-overlay-meta';
endif;
?>
">
    <article id="post-<?php the_ID(); ?>" class="item hentry">
		<?php if ( penci_get_post_format( 'gallery' ) ) : ?>
			<?php $images = get_post_meta( get_the_ID(), '_format_gallery_images', true ); ?>
			<?php if ( $images ) : ?>
                <div class="thumbnail">
                    <div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-nav-visible"<?php if ( ! get_theme_mod('penci_grid_slider_auto') ) {?> data-auto="true"<?php }?>>
                        <div class="swiper-wrapper">
							<?php foreach ( $images as $image ) : ?>
								<?php $the_image = wp_get_attachment_image_src( $image, penci_featured_images_size() ); ?>
								<?php $the_caption = get_post_field( 'post_excerpt', $image ); ?>
                                <div class="swiper-mark-item swiper-slide">


                                    <figure <?php echo penci_layout_bg( $the_image[0] ); ?> class="<?php echo penci_layout_bg_class();?> penci-swiper-mask penci-image-holder"
										<?php
										if ( $the_caption ) :
											?>
                                            title="<?php echo esc_attr( $the_caption ); ?>"<?php endif; ?>>
										<?php echo penci_layout_img( $the_image[0], $the_caption ); ?>
                                    </figure>

                                </div>

							<?php endforeach; ?>
                        </div>
                    </div>
					<?php do_action( 'penci_bookmark_post' ); ?>
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


                <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), penci_featured_images_size() )); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder <?php echo penci_class_lightbox_enable(); ?>"
                   href="<?php penci_permalink_fix(); ?>"
                   title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
					<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), penci_featured_images_size() ), get_the_title() ); ?>
                </a>


				<?php if ( ! get_theme_mod( 'penci_grid_icon_format' ) ) : ?>
					<?php if ( has_post_format( 'video' ) ) : ?>
                        <a href="<?php the_permalink(); ?>" class="icon-post-format"
                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></a>
					<?php endif; ?>
					<?php if ( has_post_format( 'audio' ) ) : ?>
                        <a href="<?php the_permalink(); ?>" class="icon-post-format"
                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-music' ); ?></a>
					<?php endif; ?>
					<?php if ( has_post_format( 'gallery' ) ) : ?>
                        <a href="<?php the_permalink(); ?>" class="icon-post-format"
                           aria-label="Icon"><?php penci_fawesome_icon( 'far fa-image' ); ?></a>
					<?php endif; ?>
					<?php if ( has_post_format( 'link' ) ) : ?>
                        <a href="<?php the_permalink(); ?>" class="icon-post-format"
                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-link' ); ?></a>
					<?php endif; ?>
					<?php if ( has_post_format( 'quote' ) ) : ?>
                        <a href="<?php the_permalink(); ?>" class="icon-post-format"
                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-quote-left' ); ?></a>
					<?php endif; ?>
				<?php endif; ?>
            </div>
		<?php endif; ?>

        <div class="grid-header-box">

			<?php if ( ! get_theme_mod( 'penci_grid_cat' ) ) : ?>
                <span class="cat"><?php penci_category( '' ); ?></span>
			<?php endif; ?>

            <<?php echo $heading_title_tag;?> class="penci-entry-title entry-title grid-title <?php echo $heading_title_tag_class;?>"><a
                        href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo $heading_title_tag;?>>
			<?php do_action( 'penci_after_post_title' ); ?>
			<?php penci_soledad_meta_schema(); ?>
			<?php $hide_readtime = get_theme_mod( 'penci_grid_readingtime' ); ?>
			<?php if ( ! get_theme_mod( 'penci_grid_date' ) || ! get_theme_mod( 'penci_grid_author' ) || get_theme_mod( 'penci_grid_countviews' ) || get_theme_mod( 'penci_grid_comment_other' ) || penci_isshow_reading_time( $hide_readtime ) ) : ?>
                <div class="grid-post-box-meta">
					<?php do_action( 'penci_grid_meta' ); ?>
					<?php if ( ! get_theme_mod( 'penci_grid_author' ) ) : ?>
                        <span class="otherl-date-author author-italic author vcard"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php
							if ( function_exists( 'coauthors_posts_links' ) ) :
								penci_coauthors_posts_links();
							else :
								?>
                                <a class="author-url url fn n"
                                   href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
							<?php endif; ?></span>
					<?php endif; ?>
					<?php if ( ! get_theme_mod( 'penci_grid_date' ) ) : ?>
                        <span class="otherl-date"><?php penci_soledad_time_link(); ?></span>
					<?php endif; ?>
					<?php if ( get_theme_mod( 'penci_grid_comment_other' ) ) : ?>
                        <span class="otherl-comment"><a
                                    href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
					<?php endif; ?>
					<?php
					if ( get_theme_mod( 'penci_grid_countviews' ) ) {
						echo '<span>';
						echo penci_get_post_views( get_the_ID() );
						echo ' ' . penci_get_setting( 'penci_trans_countviews' );
						echo '</span>';
					}
					?>
					<?php if ( penci_isshow_reading_time( $hide_readtime ) ) : ?>
                        <span class="otherl-readtime"><?php penci_reading_time(); ?></span>
					<?php endif; ?>
					<?php do_action( 'penci_extra_meta' ); ?>
                </div>
			<?php endif; ?>
        </div>

		<?php if ( get_the_excerpt() && ! get_theme_mod( 'penci_grid_remove_excerpt' ) ) : ?>
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

		<?php
		if ( get_theme_mod( 'penci_grid_add_readmore' ) ) :
			$class_button = '';
			if ( get_theme_mod( 'penci_grid_remove_arrow' ) ) :
				$class_button .= ' penci-btn-remove-arrow';
			endif;
			if ( get_theme_mod( 'penci_grid_readmore_button' ) ) :
				$class_button .= ' penci-btn-make-button';
			endif;
			if ( get_theme_mod( 'penci_grid_readmore_align' ) ) :
				$class_button .= ' penci-btn-align-' . get_theme_mod( 'penci_grid_readmore_align' );
			endif;
			?>
            <div class="penci-readmore-btn<?php echo $class_button; ?>">
                <a class="penci-btn-readmore"
                   href="<?php the_permalink(); ?>"><?php echo penci_get_setting( 'penci_trans_read_more' ); ?><?php penci_fawesome_icon( 'fas fa-angle-double-right' ); ?></a>
            </div>
		<?php endif; ?>

    </article>
</li>
<?php
if ( isset( $infeed_ads ) && $infeed_ads ) {
	penci_get_markup_infeed_ad(
		array(
			'wrapper'    => 'li',
			'classes'    => 'grid-style magazine-layout magazine-2 penci-infeed-data',
			'fullwidth'  => $infeed_full,
			'order_ad'   => $infeed_num,
			'order_post' => $j,
			'code'       => $infeed_ads,
			'echo'       => true,
		)
	);
}
?>
<?php ++ $j; ?>
