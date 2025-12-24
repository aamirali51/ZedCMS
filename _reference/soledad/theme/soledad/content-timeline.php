<?php
/**
 * Template loop for gird 2 style
 */
if ( ! isset ( $j ) ) {
	$j = 1;
} else {
	$j = $j;
}
$post_id                 = get_the_ID();
$heading_title_tag       = get_theme_mod( 'penci_grid_title_tag', 'h2' );
$heading_title_tag_class = 'h2' == $heading_title_tag ? 'penci_grid_title_df' : 'penci_grid_title';
$showcat           = ! get_theme_mod( 'penci_grid_cat' );
$excerpt           = ! get_theme_mod( 'penci_grid_remove_excerpt' );
$excerpt_length    = get_theme_mod( 'penci_post_excerpt_length', 30 );
$thumbnail         = penci_featured_images_size();
$mthumb_size       = '';
$disable_lazy      = get_theme_mod( 'penci_disable_lazyload_layout' );
$readmore_icon     = get_theme_mod( 'penci_grid_remove_arrow' );
$readmore_icon_pos = get_theme_mod( 'penci_grid_readmore_align' );
$readmore          = get_theme_mod( 'penci_grid_add_readmore' );
$item_part = ( $j % 2 === 0 ) ? 'right' : 'left';
$align = 'center';
$center_side = '';

if ( $align == 'center' && $center_side ) {
	$item_part = $center_side;
}

if ( ( $j % 2 === 0 && 'center' == $align && ! $center_side ) || 'right' == $center_side ) : ?>
    <div class="penci-tiline-item penci-tiline-item-date-wrap right">
        <div class="penci-tiline-date">
			<span>
				<?php penci_soledad_time_link(); ?>
			</span>
        </div>
    </div>
<?php
endif;
?>
<div class="penci-tiline-item <?php echo esc_attr( $item_part ) . '-part'; ?> main-alt-part">

    <div class="penci-tiline-item-main-wrapper">
        <div class="penci-tiline-line">
            <span></span>
        </div>
        <div class="penci-tiline-item-main-container">
			
			<?php if( ! get_theme_mod('penci_grid_icon_format') ): ?>
            <div class="penci-tiline-icon">
				<?php if ( has_post_format( 'gallery' ) ) : ?>
                    <span><?php penci_fawesome_icon( 'far fa-image' ); ?></span>
				<?php elseif ( has_post_format( 'link' ) ) : ?>
                    <span><?php penci_fawesome_icon( 'fas fa-link' ); ?></span>
				<?php elseif ( has_post_format( 'quote' ) ) : ?>
                    <span><?php penci_fawesome_icon( 'fas fa-quote-left' ); ?></span>
				<?php elseif ( has_post_format( 'video' ) ) : ?>
                    <span><?php penci_fawesome_icon( 'fas fa-play' ); ?></span>
				<?php elseif ( has_post_format( 'audio' ) ) : ?>
                    <span><?php penci_fawesome_icon( 'fas fa-music' ); ?></span>
				<?php else : ?>
                    <span><?php penci_fawesome_icon( 'fas fa-file' ); ?></span>
				<?php endif; ?>
            </div>
			<?php endif; ?>

            <div class="penci-tiline-item-main">
                <span class="penci-tiline-arrow"></span>
                <div class="penci-clearfix penci-biggrid-wrapper penci-grid-col-1 penci-grid-mcol-1 penci-bgrid-based-post penci-bgrid-style-1 pcbg-ficonpo-top-right pcbg-reiconpo-top-left penci-bgrid-content-below pencibg-imageh-none pencibg-texth-none pencibg-textani-movetop textop pc-flexmnld">
                    <div class="penci-clearfix penci-biggrid penci-bgstyle-1 penci-bgel">
                        <div class="penci-biggrid-inner default">
                            <div class="penci-clearfix penci-biggrid-data penci-dflex">
                                <div class="penci-bgitem">
                                    <div class="penci-bgitin">
                                        <div class="penci-bgmain">
											
											<div class="pcbg-thumb">
												<?php
												do_action( 'penci_bookmark_post', get_the_ID() );
												?>
												<div class="pcbg-thumbin"><a class="pcbg-bgoverlay"
																				href="<?php the_permalink(); ?>"
																				title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>
													<div <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumbnail, $mthumb_size ), ! $disable_lazy ); ?>
															class="<?php echo penci_layout_bg_class( ! $disable_lazy ); ?> penci-image-holder">
														<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), $thumbnail, $mthumb_size ), get_the_title(), ! $disable_lazy ); ?>
													</div>
												</div>
											</div>
											
                                            <div class="pcbg-content">
                                                <div class="pcbg-content-flex"><a class="pcbg-bgoverlay"
                                                                                  href="<?php the_permalink(); ?>"
                                                                                  title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"></a>
                                                    <div class="pcbg-content-inner bgcontent-block"><a
                                                                href="<?php the_permalink(); ?>"
                                                                title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                                                class="pcbg-bgoverlaytext item-hover"></a>

														<?php if ( $showcat ) : ?>
                                                            <div class="pcbg-above item-hover">
                                                <span class="cat pcbg-sub-title">
													<?php penci_category( '' ); ?>
                                                </span>
                                                            </div>
														<?php endif; ?>

                                                        <div class="pcbg-heading item-hover">
															
															<h3 class="pcbg-title"
																title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
																<a href="<?php the_permalink(); ?>">
																	<?php the_title(); ?>
																</a>
															</h3>
															
                                                        </div>
                                                        <div class="grid-post-box-meta pcbg-meta item-hover">
															
                                                                <div class="pcbg-meta-desc">
                                                <span class="bg-date-author author-italic author vcard">
                                                    <?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
		                                                penci_coauthors_posts_links();
	                                                else: ?>
                                                        <a class="author-url url fn n"
                                                           href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
	                                                <?php endif; ?>
                                                </span>
                                                                    <span class="bg-date"><?php penci_soledad_time_link(); ?></span>
                                                                    <?php do_action( 'penci_extra_meta' ); ?>
                                                                </div>
															
															<?php if ( $excerpt ) : ?>
                                                                <div class="pcbg-pexcerpt item-content entry-content">
																	<?php penci_the_excerpt( $excerpt_length ); ?>
                                                                </div>
															<?php endif; ?>
                                                        </div>
														<?php if ( $readmore ) : ?>
                                                            <div class="pcbg-readmore-sec item-hover">
                                                                <a href="<?php the_permalink(); ?>"
                                                                   class="pcbg-readmorebtn <?php echo 'pcreadmore-icon-' . $readmore_icon_pos; ?>">
                                                                    <span class="pcrm-text"><?php echo penci_get_setting( 'penci_trans_read_more' ); ?></span>
																	<?php if ( $readmore_icon ): ?>
																		<?php \Elementor\Icons_Manager::render_icon( $readmore_icon ); ?>
																	<?php endif; ?>
                                                                </a>
                                                            </div>
														<?php endif; ?>

                                                        <?php if ( ! get_theme_mod( 'penci_grid_share_box' ) ) : ?>
                                                            <div class="penci-post-box-meta penci-post-box-timeline">
                                                                <div class="penci-post-share-box">
                                                                    <?php echo penci_getPostLikeLink( get_the_ID() ); ?>
                                                                    <?php penci_soledad_social_share(); ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php

if ( ( $j % 2 === 1 && ( 'center' == $align ) && ! $center_side ) || 'left' == $center_side ) : ?>
    <div class="penci-tiline-item penci-tiline-item-date-wrap">
        <div class="penci-tiline-date">
			<span>
				<?php penci_soledad_time_link(); ?>
			</span>
        </div>
    </div>
<?php
endif;

if ( isset( $infeed_ads ) && $infeed_ads ) {
	penci_get_markup_infeed_ad(
		array(
			'wrapper'    => 'li',
			'classes'    => 'grid-style grid-2-style penci-infeed-data',
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
