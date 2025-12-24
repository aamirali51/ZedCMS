<?php
/**
 * Template loop for mixed style
 */
if ( ! isset ( $n ) ) {
	$n = 1;
} else {
	$n = $n;
}
$penci_featimg_size = isset( $penci_featimg_size ) ? $penci_featimg_size : '';
$thumb_bigsize      = isset( $thumb_bigsize ) ? $thumb_bigsize : '';
$post_id = get_the_ID();
$share_box_class = ( 'yes' != $grid_share_box || 'yes' != $grid_comment ) ? 'pcmx-show-share' : 'pcmx-hide-share';
$heading_title_tag = $grid_title_tag ? $grid_title_tag : 'h2';
$heading_title_tag_class = 'h2' == $heading_title_tag ? 'penci_grid_title_df' : 'penci_grid_title';
$grid_cat_move = 'yes' != $grid_cat ? $grid_cat_move : false;
$grid_cat = $grid_cat_move ? 'yes' : $grid_cat;
?>
    <div class="grid-style grid-mixed <?php echo $share_box_class;?>">
        <article id="post-<?php the_ID(); ?>" class="item hentry">

			<?php if ( has_post_thumbnail() ) : ?>
                <div class="thumbnail thumb-left">
					<?php
					/* Display Review Piechart  */
					if ( function_exists( 'penci_display_piechart_review_html' ) ) {
						penci_display_piechart_review_html( get_the_ID() );
					}
					?>
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                        <a class="penci-image-holder penci-lazy<?php echo penci_class_lightbox_enable(); ?>"
                           data-bgset="<?php echo penci_image_srcset( get_the_ID(), penci_featured_images_size_vcel( 'large', $penci_featimg_size, $thumb_bigsize ) ); ?>"
                           href="<?php penci_permalink_fix(); ?>" title="<?php echo
						wp_strip_all_tags(
							get_the_title() ); ?>">
                        </a>
					<?php } else { ?>
                        <a class="penci-image-holder<?php echo penci_class_lightbox_enable(); ?>"
                           style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), penci_featured_images_size_vcel( 'large', $penci_featimg_size, $thumb_bigsize ) ); ?>');"
                           href="<?php penci_permalink_fix(); ?>" title="<?php
						echo wp_strip_all_tags
						( get_the_title() ); ?>">
                        </a>
					<?php } ?>
                </div>
			<?php endif; ?>

            <div class="mixed-detail">
                <div class="grid-header-box">
					<?php if ( 'yes' != $grid_cat ) : ?>
                        <span class="cat"><?php penci_category( '' ); ?></span>
					<?php endif; ?>
                    <<?php echo $heading_title_tag;?> class="penci-entry-title entry-title grid-title <?php echo $heading_title_tag_class;?>"><a
                                href="<?php the_permalink(); ?>"><?php penci_trim_post_title( get_the_ID(), $grid_title_length ); ?></a>
                    </<?php echo $heading_title_tag;?>>
					<?php do_action( 'penci_after_post_title' ); ?>
					<?php penci_soledad_meta_schema(); ?>
					<?php if ( ( isset( $custom_meta_key ) && $custom_meta_key['validator'] ) || 'yes' != $grid_date || 'yes' != $grid_author || 'yes' == $grid_viewscount || penci_isshow_reading_time( $grid_readtime ) ) : ?>
                        <div class="grid-post-box-meta">
						<?php if ( $grid_cat_move ) : ?>
							<span class="cat"><?php penci_category( '' ); ?></span>
						<?php endif; ?>
							<?php if ( 'yes' != $grid_author ) : ?>
                                <span class="author-italic author vcard"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
		                                penci_coauthors_posts_links();
	                                else: ?>
                                        <a class="author-url url fn n"
                                           href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
	                                <?php endif; ?></span>
							<?php endif; ?>
							<?php if ( 'yes' != $grid_date ) : ?>
                                <span><?php penci_soledad_time_link(); ?></span>
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

				<?php if ( get_the_excerpt() && 'yes' != $grid_remove_excerpt ): ?>
                    <div <?php echo penci_excerpt_link(); ?>class="item-content entry-content">
						<?php 
						do_action( 'penci_before_post_excerpt' );
						penci_the_excerpt( $grid_excerpt_length, $post_id ); ?>
                    </div>
				<?php endif; ?>

				<?php if ( 'yes' == $grid_add_readmore ):
					$class_button = '';
					if ( 'yes' == $grid_remove_arrow ): $class_button .= ' penci-btn-remove-arrow'; endif;
					if ( 'yes' == $grid_readmore_button ): $class_button .= ' penci-btn-make-button'; endif;
					if ( $grid_readmore_align ): $class_button .= ' penci-btn-align-' . $grid_readmore_align; endif;
					?>
                    <div class="penci-readmore-btn<?php echo $class_button; ?>">
                        <a class="penci-btn-readmore"
                           href="<?php the_permalink(); ?>"><?php echo penci_get_setting( 'penci_trans_read_more' ); ?><?php penci_fawesome_icon( 'fas fa-angle-double-right' ); ?></a>
                    </div>
				<?php endif; ?>

				<?php if ( 'yes' != $grid_share_box || 'yes' != $grid_comment ) : ?>
                    <div class="penci-post-box-meta<?php if ( 'yes' == $grid_share_box || 'yes' == $grid_comment ): echo ' center-inner'; endif; ?>">
						<?php if ( 'yes' != $grid_comment ) : ?>
                            <div class="penci-box-meta">
                                <span><a href="<?php comments_link(); ?> "><?php penci_fawesome_icon( 'far fa-comment' ); ?><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
                            </div>
						<?php endif; ?>
						<?php if ( 'yes' != $grid_share_box ) : ?>
                            <div class="penci-post-share-box">
								<?php echo penci_getPostLikeLink( get_the_ID() ); ?>
								<?php penci_soledad_social_share(); ?>
                            </div>
						<?php endif; ?>
                    </div>
				<?php endif; ?>
            </div>

			<?php if ( has_post_thumbnail() ) : ?>
                <div class="thumbnail thumb-right">
					<?php
					/* Display Review Piechart  */
					if ( function_exists( 'penci_display_piechart_review_html' ) ) {
						penci_display_piechart_review_html( get_the_ID() );
					}
					?>
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
                        <a class="penci-image-holder penci-lazy<?php echo penci_class_lightbox_enable(); ?>"
                           data-bgset="<?php echo penci_image_srcset( get_the_ID(), penci_featured_images_size_vcel( 'large', $penci_featimg_size, $thumb_bigsize ) ); ?>"
                           href="<?php penci_permalink_fix(); ?>" title="<?php echo wp_strip_all_tags(
							get_the_title() ); ?>">
                        </a>
					<?php } else { ?>
                        <a class="penci-image-holder<?php echo penci_class_lightbox_enable(); ?>"
                           style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), penci_featured_images_size_vcel( 'large', $penci_featimg_size, $thumb_bigsize ) ); ?>');"
                           href="<?php penci_permalink_fix(); ?>"
                           title="<?php echo
						   wp_strip_all_tags( get_the_title() ); ?>">
                        </a>
					<?php } ?>
                </div>
			<?php endif; ?>

        </article>
    </div>
<?php
if ( isset( $infeed_ads ) && $infeed_ads ) {
	penci_get_markup_infeed_ad(
		array(
			'wrapper'    => 'div',
			'classes'    => 'grid-style grid-mixed penci-infeed-data penci-infeed-vcele',
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
