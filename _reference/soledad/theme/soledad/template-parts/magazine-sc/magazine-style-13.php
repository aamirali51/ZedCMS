<?php
/**
 * Template loop for gird 3 columns style
 */
$thumbsize = penci_featured_images_size();
if ( 'horizontal' == $penci_featimg_size ) {
	$thumbsize = 'penci-thumb';
} else if ( 'square' == $penci_featimg_size ) {
	$thumbsize = 'penci-thumb-square';
} else if ( 'vertical' == $penci_featimg_size ) {
	$thumbsize = 'penci-thumb-vertical';
} else if ( 'custom' == $penci_featimg_size ) {
	if ( $thumb_size ) {
		$thumbsize = $thumb_size;
	}
}
?>
<li class="grid-style">
    <article id="post-<?php the_ID(); ?>" class="item hentry">
		<?php if ( has_post_thumbnail() ) : ?>
            <div class="thumbnail">
				<?php
				do_action( 'penci_bookmark_post','small' );
				/* Display Review Piechart  */
				if ( function_exists( 'penci_display_piechart_review_html' ) ) {
					penci_display_piechart_review_html( get_the_ID(), 'small' );
				}
				?>

				<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                    <a class="penci-image-holder penci-lazy<?php echo penci_class_lightbox_enable(); ?>"
                       data-bgset="<?php echo penci_image_srcset( get_the_ID(), $thumbsize); ?>"
                       href="<?php penci_permalink_fix(); ?>"
                       title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
                    </a>
				<?php } else { ?>
                    <a class="penci-image-holder<?php echo penci_class_lightbox_enable(); ?>"
                       style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $thumbsize ); ?>');"
                       href="<?php penci_permalink_fix(); ?>"
                       title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
                    </a>
				<?php } ?>

				<?php if ( 'yes' != $hide_icon_format ): ?>
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
				<?php endif; ?>
            </div>
		<?php endif; ?>

        <div class="grid-header-box">
            <h2 class="grid-title entry-title"><a
                        href="<?php the_permalink(); ?>"><?php penci_trim_post_title( get_the_ID(), $_title_length ); ?></a>
            </h2>
			<?php do_action( 'penci_after_post_title' ); ?>
			<?php if ( ( isset( $custom_meta_key ) && $custom_meta_key['validator'] ) || 'yes' != $hide_date || 'yes' != $hide_author || 'yes' == $show_viewscount || 'yes' == $show_commentcount || penci_isshow_reading_time( $hide_readtime ) ) : ?>
                <div class="grid-post-box-meta">
					<?php if ( 'yes' != $hide_author ) : ?>
                        <span class="featc-author author-italic author vcard"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
		                        penci_coauthors_posts_links();
	                        else: ?>
                                <a class="author-url url fn n"
                                   href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
	                        <?php endif; ?></span>
					<?php endif; ?>
					<?php if ( 'yes' != $hide_date ) : ?>
                        <span class="featc-date"><?php penci_soledad_time_link(); ?></span>
					<?php endif; ?>
					<?php if ( 'yes' == $show_commentcount ) : ?>
                        <span class="featc-comment"><a
                                    href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
					<?php endif; ?>
					<?php
					if ( 'yes' == $show_viewscount ) {
						echo '<span>';
						echo penci_get_post_views( get_the_ID() );
						echo ' ' . penci_get_setting( 'penci_trans_countviews' );
						echo '</span>';
					}
					?>
					<?php if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                        <span class="featc-readtime"><?php penci_reading_time(); ?></span>
					<?php endif; ?>
	                <?php if ( isset( $custom_meta_key ) ) {
		                echo penci_show_custom_meta_fields( $custom_meta_key );
	                } ?>
					<?php do_action( 'penci_extra_meta' ); ?>
                </div>
			<?php endif; ?>
			<?php penci_soledad_meta_schema(); ?>
        </div>

    </article>
</li>
