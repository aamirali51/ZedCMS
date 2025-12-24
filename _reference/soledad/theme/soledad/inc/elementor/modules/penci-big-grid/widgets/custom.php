<div class="penci-bgitem<?php if ( 'style-2' == $biggid_style ) {
	echo ' item-masonry';
}
echo $is_big_item . penci_big_grid_count_classes( $bg, $biggid_style ) . $item_id; ?>">
    <div class="penci-bgitin">
        <div class="penci-bgmain">
            <div class="pcbg-thumb">
                <div class="pcbg-thumbin">
                    <a class="pcbg-bgoverlay<?php if ( 'whole' == $overlay_type && 'on' != $bgcontent_pos ): echo ' active-overlay'; endif; ?>"
					   <?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php }
					echo $title_external . $title_nofollow . $title_attr; ?>
                       title="<?php echo wp_strip_all_tags( $title ); ?>"></a>

                        <div <?php echo penci_layout_bg($image_url, $disable_lazy);?> class="<?php echo penci_layout_bg_class($disable_lazy);?> penci-image-holder"<?php if ( 'style-2' == $biggid_style ) {
							echo ' style="padding-bottom: ' . $image_ratio . '%"';
						} ?>>
	                        <?php echo penci_layout_img($image_url,$title, $disable_lazy);?>
                        </div>

                </div>
            </div>
            <div class="pcbg-content">
                <div class="pcbg-content-flex<?php if ( $title_link ) {
					echo ' pcbg-overlap-hover';
				} ?>">
					<?php if ( $title_link ) { ?>
                        <a class="pcbg-cbgoverlap"
                           href="<?php echo esc_url( $title_link ); ?>"<?php echo $title_external . $title_nofollow . $title_attr; ?>
                           title="<?php echo wp_strip_all_tags( $title ); ?>"></a>
					<?php } ?>
                    <a class="pcbg-bgoverlay<?php if ( 'whole' == $overlay_type && 'on' == $bgcontent_pos ): echo ' active-overlay'; endif; ?>"
					   <?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php }
					echo $title_external . $title_nofollow . $title_attr; ?>
                       title="<?php echo wp_strip_all_tags( $title ); ?>"></a>
                    <div class="pcbg-content-inner<?php if ( 'inline-block' == $content_display ) {
						echo ' bgcontent-inline-block';
					} else {
						echo ' bgcontent-block';
					} ?>">
                        <a <?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php }
						echo $title_external . $title_nofollow . $title_attr; ?>
                           title="<?php echo wp_strip_all_tags( $title ); ?>"
                           class="pcbg-bgoverlaytext<?php if ( 'text' == $overlay_type ): echo ' active-overlay'; endif; ?> item-hover"></a>

						<?php if ( $sub_title ) : ?>
                            <div class="pcbg-above item-hover">
                                <div class="pcbg-sub-title"><?php echo $sub_title; ?></div>
                            </div>
						<?php endif; ?>

						<?php if ( $title ) : ?>
                            <div class="pcbg-heading item-hover">
                                <h3 class="pcbg-title"><a
										<?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php }
									echo $title_external . $title_nofollow . $title_attr; ?>><?php if ( ! $title_length ) {
											echo $title;
										} else {
											echo wp_trim_words( wp_strip_all_tags( $title ), $title_length, '...' );
										} ?></a></h3>
                            </div>
						<?php endif; ?>

						<?php if ( $desc ) { ?>
                            <div class="grid-post-box-meta pcbg-meta item-hover">
                                <div class="pcbg-meta-desc"><?php echo $desc; ?></div>
                            </div>
						<?php } ?>

						<?php if ( $button_text ) { ?>
                            <div class="pcbg-readmore-sec item-hover">
                                <a <?php if ( $button_link ){ ?>href="<?php echo esc_url( $button_link ); ?>"<?php }
								echo $button_external . $button_nofollow . $button_attr; ?>
                                   class="pcbg-readmorebtn <?php echo 'pcreadmore-icon-' . $readmore_icon_pos; ?>">
                                    <span class="pcrm-text"><?php echo $button_text; ?></span>
									<?php if ( $readmore_icon ): ?>
										<?php \Elementor\Icons_Manager::render_icon( $readmore_icon ); ?>
									<?php endif; ?>
                                </a>
                            </div>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
