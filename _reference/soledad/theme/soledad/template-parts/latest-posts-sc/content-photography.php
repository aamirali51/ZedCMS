<?php
/**
 * Template loop for typography style
 */
if ( ! isset ( $n ) ) {
	$n = 1;
} else {
	$n = $n;
}
$penci_featimg_size = isset( $penci_featimg_size ) ? $penci_featimg_size : '';
$thumb_size         = isset( $thumb_size ) ? $thumb_size : '';
$heading_title_tag = $grid_title_tag ? $grid_title_tag : 'h2';
$heading_title_tag_class = 'h2' == $heading_title_tag ? 'penci_grid_title_df' : 'penci_grid_title';
$grid_cat_move = 'yes' != $grid_cat ? $grid_cat_move : false;
$grid_cat = $grid_cat_move ? 'yes' : $grid_cat;
?>
    <li class="grid-style grid-2-style typography-style">
        <article id="post-<?php the_ID(); ?>" class="item hentry">

            <div class="thumbnail">
                <?php do_action( 'penci_bookmark_post' ); ?>
				<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                    <a class="penci-image-holder penci-lazy"
                       data-bgset="<?php echo penci_image_srcset( get_the_ID(), penci_featured_images_size_vcel( 'normal', $penci_featimg_size, $thumb_size ) ); ?>"
                       href="<?php the_permalink(); ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
                    </a>
				<?php } else { ?>
                    <a class="penci-image-holder"
                       style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), penci_featured_images_size_vcel( 'normal', $penci_featimg_size, $thumb_size ) ); ?>');"
                       href="<?php the_permalink(); ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
                    </a>
				<?php } ?>

                <div class="content-typography">
                    <a href="<?php the_permalink(); ?>" class="overlay-typography"></a>
                    <div class="main-typography">
						<?php if ( 'yes' != $grid_cat ) : ?>
                            <span class="cat"><?php penci_category( '' ); ?></span>
						<?php endif; ?>

                        <<?php echo $heading_title_tag;?> class="entry-title grid-title <?php echo $heading_title_tag_class;?>"><a
                                    href="<?php the_permalink(); ?>"><?php penci_trim_post_title( get_the_ID(), $grid_title_length ); ?></a>
                        </<?php echo $heading_title_tag;?>>
						<?php do_action( 'penci_after_post_title' ); ?>
						<?php penci_soledad_meta_schema(); ?>

						<?php if ( ( isset( $custom_meta_key ) && $custom_meta_key['validator'] ) || 'yes' != $grid_date || 'yes' != $grid_author || 'yes' == $grid_viewscount || 'yes' == $grid_comment_other || penci_isshow_reading_time( $grid_readtime ) ) : ?>
                            <div class="grid-post-box-meta">
							<?php if ( $grid_cat_move ) : ?>
								<span class="cat"><?php penci_category( '' ); ?></span>
							<?php endif; ?>
								<?php if ( 'yes' != $grid_author ) : ?>
                                    <span class="otherl-date-author author-italic author vcard"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
		                                    penci_coauthors_posts_links();
	                                    else: ?>
                                            <a class="author-url url fn n"
                                               href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
	                                    <?php endif; ?></span>
								<?php endif; ?>
								<?php if ( 'yes' != $grid_date ) : ?>
                                    <span class="otherl-date"><?php penci_soledad_time_link(); ?></span>
								<?php endif; ?>
								<?php if ( 'yes' == $grid_comment_other ) : ?>
                                    <span class="otherl-comment"><a
                                                href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
								<?php endif; ?>
								<?php
								if ( 'yes' == $grid_viewscount ) {
									echo '<span>';
									echo penci_get_post_views( get_the_ID() );
									echo ' ' . penci_get_setting( 'penci_trans_countviews' );
									echo '</span>';
								}
								?>
								<?php if ( penci_isshow_reading_time( $grid_readtime ) ): ?>
                                    <span class="otherl-readtime"><?php penci_reading_time(); ?></span>
								<?php endif; ?>
	                            <?php if ( isset( $custom_meta_key ) ) {
		                            echo penci_show_custom_meta_fields( $custom_meta_key );
	                            } ?>
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
			'classes'    => 'grid-style grid-2-style typography-style penci-infeed-data penci-infeed-vcele',
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
