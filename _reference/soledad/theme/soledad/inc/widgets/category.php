<div class="penci-bgitem<?php if ( 'style-2' == $biggid_style ) {
	echo ' item-masonry';
}
echo $is_big_item . penci_big_grid_count_classes( $bg, $biggid_style ) . $item_id; ?>">
    <div class="penci-bgitin">
        <div class="penci-bgmain">
            <?php do_action('penci_bl_follow_term',['follow_taxonomy'=> $settings['term_name'],'follow_term_id' => $setting->term_id]); ?>
            <div class="pcbg-thumb">
                <div class="pcbg-thumbin">
                    <a class="pcbg-bgoverlay<?php if ( 'whole' == $overlay_type && 'on' != $bgcontent_pos ): echo ' active-overlay'; endif; ?>"
					   <?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php } ?>
                       title="<?php echo wp_strip_all_tags( $title ); ?>"></a>

                        <div <?php echo penci_layout_bg( $image_url, ! $disable_lazy ); ?> class="<?php echo penci_layout_bg_class(! $disable_lazy);?> penci-image-holder"<?php if ( 'style-2' == $biggid_style ) {
							echo ' style="padding-bottom: ' . $image_ratio . '%"';
						} ?>>
	                        <?php echo penci_layout_img( $image_url, $title, ! $disable_lazy ); ?>
                        </div>

                </div>
            </div>
            <div class="pcbg-content">
                <div class="pcbg-content-flex<?php if ( $title_link ) {
					echo ' pcbg-overlap-hover';
				} ?>">
					<?php if ( $title_link ) { ?>
                        <a class="pcbg-cbgoverlap"
                           href="<?php echo esc_url( $title_link ); ?>"
                           title="<?php echo wp_strip_all_tags( $title ); ?>"></a>
					<?php } ?>
                    <a class="pcbg-bgoverlay<?php if ( 'whole' == $overlay_type && 'on' == $bgcontent_pos ): echo ' active-overlay'; endif; ?>"
					   <?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php } ?>
                       title="<?php echo wp_strip_all_tags( $title ); ?>"></a>
                    <div class="pcbg-content-inner<?php if ( 'inline-block' == $content_display ) {
						echo ' bgcontent-inline-block';
					} else {
						echo ' bgcontent-block';
					} ?>">
                        <a <?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php } ?>
                           title="<?php echo wp_strip_all_tags( $title ); ?>"
                           class="pcbg-bgoverlaytext<?php if ( 'text' == $overlay_type ): echo ' active-overlay'; endif; ?> item-hover"></a>

						<?php if ( $title ) : ?>
                            <div class="pcbg-heading item-hover">
                                <h3 class="pcbg-title"><a
										<?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"><?php }
										if ( ! $title_length ) {
											echo $title;
										} else {
											echo wp_trim_words( wp_strip_all_tags( $title ), $title_length, '...' );
										} ?></a></h3>
                            </div>
						<?php endif; ?>

						<?php if ( in_array( 'count', $post_meta ) && ! $hide_cat_small_flag ) : ?>
                            <div class="grid-post-box-meta pcbg-meta item-hover">
                                <div class="pcbg-meta-desc">
                                    <span class="cat pcbg-sub-title">
                                        <?php
                                        $prefix = $post_count == 1 ? penci_get_setting('penci_trans_post') : penci_get_setting('penci_trans_posts');
                                        echo $post_count . ' ' . $prefix; ?>
                                    </span>
                                </div>
                            </div>
						<?php endif; ?>

						<?php if ( in_array( 'desc', $post_meta ) && $desc && ! $hide_excerpt_small_flag ) { ?>
                            <div class="grid-post-box-meta pcbg-meta item-hover">
                                <div class="pcbg-meta-desc"><?php echo wp_trim_words( wp_strip_all_tags( $desc ), $excerpt_length, '...' ); ?></div>
                            </div>
						<?php } ?>

                        <?php if ( $show_readmore && ! $hide_rm_small_flag ) { ?>
                            <div class="pcbg-readmore-sec item-hover">
                                <a href="<?php echo esc_url( $title_link ); ?>"
                                   class="pcbg-readmorebtn <?php echo 'pcreadmore-icon-' . $readmore_icon_pos; ?>">
                                    <span class="pcrm-text"><?php echo penci_get_setting( 'penci_trans_view_all' ); ?></span>
                                </a>
                            </div>
                        <?php } ?>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>