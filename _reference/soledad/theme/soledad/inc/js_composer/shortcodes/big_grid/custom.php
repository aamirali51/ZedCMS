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

                    <div <?php echo penci_layout_bg( $image_url, ! $disable_lazy ); ?> class="<?php echo penci_layout_bg_class(! $disable_lazy);?> penci-image-holder"<?php if ( 'style-2' == $biggid_style ) {
						echo ' style="padding-bottom: ' . $image_ratio . '%"';
					} ?>>
						<?php echo penci_layout_img( $image_url, $title, ! $disable_lazy ); ?>
                    </div>

                </div>
            </div>
            <div class="pcbg-content">
                <div class="pcbg-content-flex">
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
									<?php if ( $readmore_icon ):
										vc_icon_element_fonts_enqueue( 'fontawesome' );
										if ( $readmore_icon ) {
											$icon .= '<i class="penci-cup_iconn--i ' . $readmore_icon . '"></i>';
											echo $icon;
										}
									endif; ?>
                                </a>
                            </div>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$child_css = '';

if ( isset( $setting['horizontal_position'] ) ) {
	$setting_child = $setting['horizontal_position'];
	$data_extract  = array(
		'left'   => 'margin-right: auto',
		'center' => 'margin-left: auto; margin-right: auto;',
		'right'  => 'margin-left: auto',
	);
	$child_css     .= $block_id . ' ' . $item_id . ' .pcbg-content-inner{' . $data_extract[ $setting_child ] . '}';
}

if ( isset( $setting['vertical_position'] ) ) {
	$setting_child = $setting['vertical_position'];
	$data_extract  = array(
		'top'    => 'flex-start',
		'middle' => 'center',
		'bottom' => 'flex-end',
	);
	$child_css     .= $block_id . ' ' . $item_id . ' .pcbg-content-flex{align-items:' . $data_extract[ $setting_child ] . '}';
}

if ( isset( $setting['text_align'] ) ) {
	$setting_child = $setting['text_align'];
	$data_extract  = array(
		'top'    => 'flex-start',
		'middle' => 'center',
		'bottom' => 'flex-end',
	);
	$child_css     .= $block_id . ' ' . $item_id . ' .pcbg-content-flex{text-align:' . $data_extract[ $setting_child ] . '}';
}

if ( isset( $setting['subtitle_color'] ) ) {
	$child_css .= $block_id . ' .penci-bgrid-based-custom ' . $item_id . ' .pcbg-content-inner .pcbg-above{color: ' . $setting['subtitle_color'] . '}';
	$child_css .= $block_id . ' .penci-bgrid-based-custom ' . $item_id . ' .pcbg-content-inner .pcbg-above span{color: ' . $setting['subtitle_color'] . '}';
	$child_css .= $block_id . ' .penci-bgrid-based-custom ' . $item_id . ' .pcbg-content-inner .pcbg-above a{color: ' . $setting['subtitle_color'] . '}';
}

if ( isset( $setting['title_color'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-content-inner .pcbg-title{color: ' . $setting['title_color'] . '}';
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-content-inner .pcbg-title a{color: ' . $setting['title_color'] . '}';
}

if ( isset( $setting['title_hcolor'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-content-inner .pcbg-title a:hover{color: ' . $setting['title_hcolor'] . '}';
}

if ( isset( $setting['desc_color'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-content-inner .pcbg-meta{color: ' . $setting['desc_color'] . '}';
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-content-inner .pcbg-meta a{color: ' . $setting['desc_color'] . '}';
}

if ( isset( $setting['button_color'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-readmore-sec .pcbg-readmorebtn{color: ' . $setting['button_color'] . '}';
}

if ( isset( $setting['button_hcolor'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-readmore-sec .pcbg-readmorebtn:hover{color: ' . $setting['button_hcolor'] . '}';
}

if ( isset( $setting['button_border_color'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-readmore-sec .pcbg-readmorebtn{border-color: ' . $setting['button_border_color'] . '}';
}

if ( isset( $setting['button_border_hcolor'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-readmore-sec .pcbg-readmorebtn:hover{border-color: ' . $setting['button_border_hcolor'] . '}';
}

if ( isset( $setting['button_bg_color'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-readmore-sec .pcbg-readmorebtn{background-color: ' . $setting['button_bg_color'] . '}';
}

if ( isset( $setting['button_bg_hcolor'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-readmore-sec .pcbg-readmorebtn:hover{background-color: ' . $setting['button_bg_hcolor'] . '}';
}

if ( isset( $setting['bgoverlay_padding'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-content-inner{padding: ' . $setting['bgoverlay_padding'] . '}';
}

if ( isset( $setting['bgoverlay_margin'] ) ) {
	$child_css .= $block_id . ' ' . $item_id . ' .pcbg-content-inner{margin: ' . $setting['bgoverlay_margin'] . '}';
}

if ( $child_css ) {
	echo '<style>';
	echo $child_css;
	echo '</style>';
}
