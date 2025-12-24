<?php
/**
 * Template display for featured category style 3
 *
 * @since 1.0
 */
?>

<div class="mag-photo">
    <div class="magcat-thumb hentry">
		<?php
		do_action( 'penci_bookmark_post', get_the_ID() );
		/* Display Review Piechart  */
		if ( function_exists( 'penci_display_piechart_review_html' ) ) {
			penci_display_piechart_review_html( get_the_ID() );
		}
		?>
        <a href="<?php penci_permalink_fix(); ?>"
           class="mag-overlay-photo<?php echo penci_class_lightbox_enable(); ?>"></a>

        <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), penci_featured_images_size() ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder<?php echo penci_class_lightbox_enable(); ?>"
           href="<?php penci_permalink_fix(); ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
			<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), penci_featured_images_size() ), get_the_title() ); ?>
        </a>

		<?php if ( has_post_thumbnail() && get_theme_mod( 'penci_home_featured_cat_icons' ) ): ?>
			<?php if ( has_post_format( 'video' ) ) : ?>
                <a href="<?php the_permalink() ?>" class="icon-post-format"
                   aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></a>
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
			<?php if ( has_post_format( 'gallery' ) ) : ?>
                <a href="<?php the_permalink() ?>" class="icon-post-format"
                   aria-label="Icon"><?php penci_fawesome_icon( 'far fa-image' ); ?></a>
			<?php endif; ?>
		<?php endif; ?>
        <div class="magcat-detail">
            <h3 class="magcat-titlte entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php do_action( 'penci_after_post_title' ); ?>
			<?php $hide_readtime = get_theme_mod( 'penci_home_cat_readtime' ); ?>
			<?php if ( ! get_theme_mod( 'penci_home_featured_cat_author' ) || ! get_theme_mod( 'penci_home_featured_cat_date' ) || get_theme_mod( 'penci_home_featured_cat_comment' ) || get_theme_mod( 'penci_home_cat_views' ) || penci_isshow_reading_time( $hide_readtime ) ): ?>
                <div class="grid-post-box-meta mag-meta">
					<?php if ( ! get_theme_mod( 'penci_home_featured_cat_author' ) ) : ?>
                        <span class="featc-author author vcard"><?php if ( function_exists( 'coauthors_posts_links' ) ) :
								penci_coauthors_posts_links();
							else: ?>
                                <a class="author-url url fn n"
                                   href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
							<?php endif; ?></span>
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
                </div>
			<?php endif; ?>
			<?php penci_soledad_meta_schema(); ?>
        </div>
    </div>
</div>
