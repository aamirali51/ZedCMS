<?php
/**
 * Template loop for typography style
 */
if ( ! isset ( $j ) ) {
	$j = 1;
} else {
	$j = $j;
}
$heading_title_tag = get_theme_mod( 'penci_grid_title_tag', 'h2' );
$heading_title_tag_class = 'h2' == $heading_title_tag ? 'penci_grid_title_df' : 'penci_grid_title';
?>
<li class="grid-style grid-2-style typography-style">
    <article id="post-<?php the_ID(); ?>" class="item hentry">

        <div class="thumbnail">
			<?php do_action( 'penci_bookmark_post' ); ?>

            <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), penci_featured_images_size() ) ); ?> class="<?php echo penci_layout_bg_class();?> penci-image-holder"
               href="<?php the_permalink(); ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
				<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), penci_featured_images_size() ), get_the_title() ); ?>
            </a>


            <div class="content-typography">
                <a href="<?php the_permalink(); ?>" class="overlay-typography"></a>
                <div class="main-typography">
					<?php if ( ! get_theme_mod( 'penci_grid_cat' ) ) : ?>
                        <span class="cat"><?php penci_category( '' ); ?></span>
					<?php endif; ?>

                    <<?php echo $heading_title_tag;?> class="entry-title grid-title <?php echo $heading_title_tag_class;?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </<?php echo $heading_title_tag;?>>
					<?php do_action( 'penci_after_post_title' ); ?>
					<?php penci_soledad_meta_schema(); ?>

					<?php $hide_readtime = get_theme_mod( 'penci_grid_readingtime' ); ?>
					<?php if ( ! get_theme_mod( 'penci_grid_date' ) || ! get_theme_mod( 'penci_grid_author' ) || get_theme_mod( 'penci_grid_countviews' ) || get_theme_mod( 'penci_grid_comment_other' ) || penci_isshow_reading_time( $hide_readtime ) ) : ?>
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
							<?php if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                                <span class="otherl-readtime"><?php penci_reading_time(); ?></span>
							<?php endif; ?>
							<?php do_action( 'penci_extra_meta' ); ?>
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </div>

    </article>
</li>
<?php
if ( isset( $infeed_ads ) && $infeed_ads ) {
	penci_get_markup_infeed_ad(
		array(
			'wrapper'    => 'li',
			'classes'    => 'grid-style grid-2-style typography-style penci-infeed-data',
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
