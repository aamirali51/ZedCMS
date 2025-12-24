<?php
$title       = $category->name;
$catcount    = $category->count;
$count       = isset( $settings['hide_subtitle'] ) && 'yes' == $settings['hide_subtitle'] ? false : $catcount;
$title_link  = get_term_link( $category->term_id, $tax_name );
$image_id    = penci_subcategory_extract_img( $category );
$image_url   = wp_get_attachment_image_url( penci_get_taxonomies_image( $tax_name, $category->term_id, $term_options ), 'full' );
$image_ratio = penci_get_ratio_size_based_url( $image_url );
?>
<div class="penci-bgitem<?php if ( 'style-2' == $biggid_style ) {
	echo ' item-masonry';
}
echo $is_big_item . penci_big_grid_count_classes( $bg, $biggid_style ) . $item_id; ?>">
    <div class="penci-bgitin">
        <div class="penci-bgmain">
            <div class="pcbg-thumb">
                <div class="pcbg-thumbin">
                    <a class="pcbg-bgoverlay<?php if ( 'whole' == $overlay_type && 'on' != $bgcontent_pos ): echo ' active-overlay'; endif; ?>"
					   <?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php }; ?>
                       title="<?php echo wp_strip_all_tags( $title ); ?>"></a>

                    <div <?php echo penci_layout_bg( $image_url, $disable_lazy ); ?> class="<?php echo penci_layout_bg_class($disable_lazy);?> penci-image-holder"<?php if ( 'style-2' == $biggid_style ) {
						echo ' style="padding-bottom: ' . $image_ratio . '%"';
					} ?>>
						<?php echo penci_layout_img( $image_url, $title, $disable_lazy ); ?>
                    </div>

                </div>
            </div>
            <div class="pcbg-content">
                <div class="pcbg-content-flex">
                    <a class="pcbg-bgoverlay<?php if ( 'whole' == $overlay_type && 'on' == $bgcontent_pos ): echo ' active-overlay'; endif; ?>"
					   <?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php }; ?>
                       title="<?php echo esc_attr( $title ); ?>"></a>
                    <div class="pcbg-content-inner<?php if ( 'inline-block' == $content_display ) {
						echo ' bgcontent-inline-block';
					} else {
						echo ' bgcontent-block';
					} ?>">
                        <a <?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php }; ?>
                           title="<?php echo esc_attr( $title ); ?>"
                           class="pcbg-bgoverlaytext<?php if ( 'text' == $overlay_type ): echo ' active-overlay'; endif; ?> item-hover"></a>

						<?php if ( $title ) : ?>
                            <div class="pcbg-heading item-hover">
                                <h3 class="pcbg-title">
                                    <a <?php if ( $title_link ){ ?>href="<?php echo esc_url( $title_link ); ?>"<?php } ?>>
										<?php echo $title; ?>
                                    </a>
                                </h3>
                            </div>
						<?php endif; ?>

						<?php if ( $count ) : ?>
                            <div class="pcbg-below item-hover">
                                <div class="pcbg-sub-title">
                                    <span>
									<?php
									$text_item  = $text_item ? $text_item : '% item';
									$text_items = $text_items ? $text_items : '% items';
									echo ( 1 >= $count ) ? str_replace( '%', $count, $text_item ) : str_replace( '%', $count, $text_items );
									?>
                                        </span>
                                </div>
                            </div>
						<?php endif; ?>

						<?php if ( $desc ) { ?>
                            <div class="grid-post-box-meta pcbg-meta item-hover">
                                <div class="pcbg-meta-desc"><?php echo $desc; ?></div>
                            </div>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
