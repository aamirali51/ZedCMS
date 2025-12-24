<?php
/**
 * Template loop for Featured Boxed style
 */
if ( ! isset ( $j ) ) {
	$j = 1;
} else {
	$j = $j;
}
$post_id = get_the_ID();
$heading_title_tag = get_theme_mod( 'penci_grid_title_tag', 'h2' );
$heading_title_tag_class = 'h2' == $heading_title_tag ? 'penci_grid_title_df' : 'penci_grid_title';
?>
    <section class="grid-featured">
        <article id="post-<?php the_ID(); ?>" class="item featured-layout">
            <div class="thumbnail">
                <?php do_action( 'penci_bookmark_post' ); ?>
				<?php if ( ! get_theme_mod( 'penci_grid_share_box' ) ) : ?>
                    <div class="penci-post-share-box penci-featured-share-box">
                        <span class="penci-shareic"><a
                                    href="#" aria-label="Share"><?php penci_fawesome_icon( 'fas fa-share' ); ?></a></span>
                        <span class="penci-shareso">
					<?php echo penci_getPostLikeLink( get_the_ID() ); ?>
					<?php penci_soledad_social_share(); ?>
					</span>
                    </div>
				<?php endif; ?>
				<?php
				/* Display Review Piechart  */
				if ( function_exists( 'penci_display_piechart_review_html' ) ) {
					penci_display_piechart_review_html( get_the_ID() );
				}
				?>

                    <a <?php echo penci_layout_bg(penci_image_srcset( get_the_ID(), 'penci-full-thumb' ));?> class="<?php echo penci_layout_bg_class();?> penci-image-holder"
                       href="<?php the_permalink(); ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
	                    <?php echo penci_layout_img(penci_image_srcset( get_the_ID(), 'penci-full-thumb' ),get_the_title());?>
                    </a>

            </div>

            <div class="penci-featured-infor">
                <div class="grid-header-box featured-header-box">
					<?php if ( ! get_theme_mod( 'penci_grid_cat' ) ) : ?>
                        <span class="cat"><?php penci_category( '' ); ?></span>
					<?php endif; ?>
                    <<?php echo $heading_title_tag;?> class="penci-entry-title entry-title overlay-title <?php echo $heading_title_tag_class;?>"><a
                                href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo $heading_title_tag;?>>
					<?php do_action( 'penci_after_post_title' ); ?>
					<?php penci_soledad_meta_schema(); ?>
					<?php $hide_readtime = get_theme_mod( 'penci_grid_readingtime' ); ?>
					<?php if ( ! get_theme_mod( 'penci_grid_author' ) || ! get_theme_mod( 'penci_grid_date' ) || ! get_theme_mod( 'penci_grid_comment' ) || ! get_theme_mod( 'penci_grid_share_box' ) || get_theme_mod( 'penci_grid_countviews' ) || penci_isshow_reading_time( $hide_readtime ) ) : ?>
                        <div class="grid-post-box-meta">
							<?php do_action( 'penci_grid_meta' ); ?>
							<?php if ( ! get_theme_mod( 'penci_grid_author' ) ) : ?>
                                <span class="otherl-date-author author-italic author vcard"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
		                                penci_coauthors_posts_links();
	                                else: ?>
                                        <a class="author-url url fn n"
                                           href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
	                                <?php endif; ?></span>
							<?php endif; ?>
							<?php if ( ! get_theme_mod( 'penci_grid_date' ) ) : ?>
                                <span class="otherl-date"><?php penci_soledad_time_link(); ?></span>
							<?php endif; ?>
							<?php if ( ! get_theme_mod( 'penci_grid_comment' ) ) : ?>
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
							<?php if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                                <span class="otherl-readtime"><?php penci_reading_time(); ?></span>
							<?php endif; ?>
							<?php do_action( 'penci_extra_meta' ); ?>
                        </div>
					<?php endif; ?>
                </div>

				<?php if ( get_the_excerpt() && ! get_theme_mod( 'penci_grid_remove_excerpt' ) ): ?>
                    <div <?php echo penci_excerpt_link(); ?>class="item-content entry-content">
						<?php
						do_action( 'penci_before_post_excerpt' );
						if( get_theme_mod( 'penci_excerptcharac' ) ){
							the_excerpt();
						} else {
							$excerpt_length = get_theme_mod( 'penci_post_excerpt_length', 30 );
							penci_the_excerpt( $excerpt_length, $post_id );
						}
						?>
                    </div>
				<?php endif; ?>

				<?php if ( get_theme_mod( 'penci_grid_add_readmore' ) ):
					$class_button = '';
					if ( get_theme_mod( 'penci_grid_remove_arrow' ) ): $class_button .= ' penci-btn-remove-arrow'; endif;
					if ( get_theme_mod( 'penci_grid_readmore_button' ) ): $class_button .= ' penci-btn-make-button'; endif;
					if ( get_theme_mod( 'penci_grid_readmore_align' ) ): $class_button .= ' penci-btn-align-' . get_theme_mod( 'penci_grid_readmore_align' ); endif;
					?>
                    <div class="penci-readmore-btn<?php echo $class_button; ?>">
                        <a class="penci-btn-readmore"
                           href="<?php the_permalink(); ?>"><?php echo penci_get_setting( 'penci_trans_read_more' ); ?><?php penci_fawesome_icon( 'fas fa-angle-double-right' ); ?></a>
                    </div>
				<?php endif; ?>
            </div>
        </article>
    </section>
<?php
if ( isset( $infeed_ads ) && $infeed_ads ) {
	penci_get_markup_infeed_ad(
		array(
			'wrapper'    => 'section',
			'classes'    => 'grid-featured penci-infeed-data',
			'fullwidth'  => $infeed_full,
			'order_ad'   => $infeed_num,
			'order_post' => $j,
			'code'       => $infeed_ads,
			'echo'       => true
		)
	);
}
?>
<?php $j ++; ?>
