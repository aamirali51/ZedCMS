<?php
/**
 * Template loop for overlay style
 */
if ( ! isset ( $n ) ) {
	$n = 1;
} else {
	$n = $n;
}
$thumbbsize         = 'penci-full-thumb';
$penci_featimg_size = isset( $penci_featimg_size ) ? $penci_featimg_size : '';
if ( 'custom' == $penci_featimg_size ) {
	$thumb_bigsize = isset( $thumb_bigsize ) ? $thumb_bigsize : '';
	if ( $thumb_bigsize ) {
		$thumbbsize = $thumb_bigsize;
	}
}
$heading_title_tag = $grid_title_tag ? $grid_title_tag : 'h2';
$heading_title_tag_class = 'h2' == $heading_title_tag ? 'penci_grid_title_df' : 'penci_grid_title';
?>
<section class="grid-style grid-overlay">
    <article id="post-<?php the_ID(); ?>" class="item overlay-layout hentry">
        <div class="penci-overlay-over">
            <div class="thumbnail">
				<?php
				do_action( 'penci_bookmark_post' );
				/* Display Review Piechart  */
				if ( function_exists( 'penci_display_piechart_review_html' ) ) {
					penci_display_piechart_review_html( get_the_ID() );
				}
				?>
				<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                    <a class="penci-image-holder penci-lazy"
                       data-bgset="<?php echo penci_image_srcset( get_the_ID(), $thumbbsize ); ?>"
                       href="<?php the_permalink(); ?>" aria-label="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                       title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
                    </a>
				<?php } else { ?>
                    <a class="penci-image-holder"
                       style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $thumbbsize ); ?>');"
                       href="<?php the_permalink(); ?>" aria-label="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                       title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
                    </a>
				<?php } ?>
            </div>

            <a aria-label="<?php echo wp_strip_all_tags( get_the_title() ); ?>" class="overlay-border"
               href="<?php the_permalink() ?>"></a>

            <div class="overlay-header-box">
				<?php if ( 'yes' != $grid_cat ) : ?>
                    <span class="cat"><?php penci_category( '' ); ?></span>
				<?php endif; ?>

                <<?php echo $heading_title_tag;?> class="penci-entry-title entry-title overlay-title <?php echo $heading_title_tag_class;?>"><a
                            href="<?php the_permalink(); ?>"><?php penci_trim_post_title( get_the_ID(), $grid_title_length ); ?></a>
                </<?php echo $heading_title_tag;?>>
				<?php do_action( 'penci_after_post_title' ); ?>
				<?php penci_soledad_meta_schema(); ?>
				<?php if ( 'yes' != $grid_author ) : ?>
                    <div class="penci-meta-author overlay-author byline"><span
                                class="author-italic author vcard"><?php echo penci_get_setting( 'penci_trans_written_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
								penci_coauthors_posts_links();
							else: ?>
                                <a class="author-url url fn n"
                                   href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
							<?php endif; ?></span>
                    </div>
				<?php endif; ?>
            </div>
        </div>

		<?php if ( ( isset( $custom_meta_key ) && $custom_meta_key['validator'] ) || 'yes' != $grid_date || 'yes' != $grid_comment || 'yes' != $grid_share_box || 'yes' == $grid_viewscount || penci_isshow_reading_time( $grid_readtime ) ) : ?>
            <div class="penci-post-box-meta grid-post-box-meta overlay-post-box-meta">
				<?php if ( 'yes' != $grid_date ) : ?>
                    <div class="overlay-share overlay-style-date"><?php penci_fawesome_icon( 'far fa-clock' ); ?><?php penci_soledad_time_link(); ?></div>
				<?php endif; ?>
				<?php if ( 'yes' != $grid_comment ) : ?>
                    <div class="overlay-share overlay-style-comment"><a
                                href="<?php comments_link(); ?> "><?php penci_fawesome_icon( 'far fa-comment' ); ?><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a>
                    </div>
				<?php endif; ?>
				<?php
				if ( 'yes' == $grid_viewscount ) {
					echo '<span>';
					echo penci_get_post_views( get_the_ID() );
					echo ' ' . penci_get_setting( 'penci_trans_countviews' );
					echo '</span>';
				}
				?>
				<?php if ( isset( $custom_meta_key ) ) {
					echo penci_show_custom_meta_fields( $custom_meta_key );
				} ?>
				<?php if ( penci_isshow_reading_time( $grid_readtime ) ): ?>
                    <div class="overlay-share overlay-style-readtime"><?php penci_reading_time(); ?></div>
				<?php endif; ?>
				<?php if ( 'yes' != $grid_share_box ) : ?>
                    <div class="penci-post-share-box">
						<?php echo penci_getPostLikeLink( get_the_ID() ); ?>
						<?php penci_soledad_social_share(); ?>
                    </div>
				<?php endif; ?>
            </div>
		<?php endif; ?>

    </article>
</section>
<?php
if ( isset( $infeed_ads ) && $infeed_ads ) {
	penci_get_markup_infeed_ad(
		array(
			'wrapper'    => 'section',
			'classes'    => 'grid-style grid-overlay penci-infeed-data penci-infeed-vcele',
			'fullwidth'  => $infeed_full,
			'order_ad'   => $infeed_num,
			'order_post' => $n,
			'code'       => $infeed_ads,
			'echo'       => true
		)
	);
}
?>
<?php $n ++; ?>
