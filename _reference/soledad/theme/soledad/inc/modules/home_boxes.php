<?php
/**
 * Home boxes
 * Create 3/4 boxes in homepage
 * @since 1.0
 */
$weight_text = get_theme_mod( 'penci_home_box_weight' ) ? get_theme_mod( 'penci_home_box_weight' ) : 'normal';
$boxes_size  = get_theme_mod( 'penci_home_box_type' ) ? get_theme_mod( 'penci_home_box_type' ) : 'horizontal';
$thumb       = 'penci-thumb';
if ( $boxes_size == 'square' ) {
	$thumb = 'penci-thumb-square';
} elseif ( $boxes_size == 'vertical' ) {
	$thumb = 'penci-thumb-vertical';
}
?>
<div class="container home-featured-boxes boxes-weight-<?php echo $weight_text; ?> boxes-size-<?php echo $boxes_size; ?>">
    <ul class="homepage-featured-boxes<?php if ( '4' == get_theme_mod( 'penci_home_box_column' ) ): echo ' boxes-4-columns'; endif; ?>">
		<?php
		for ( $k = 1; $k < 10; $k ++ ) {
			$homepage_box_image = get_theme_mod( 'penci_home_box_img' . $k );
			$homepage_box_text  = penci_get_setting( 'penci_home_box_text' . $k );
			$homepage_box_url   = penci_get_setting( 'penci_home_box_url' . $k );
			if ( $homepage_box_image ):
				$open_url = '';
				$close_url      = '';
				$target         = '';
				if ( get_theme_mod( 'penci_home_boxes_new_tab' ) ):
					$target = ' target="_blank"';
				endif;
				if ( $homepage_box_url ) {
					$open_url  = '<a href="' . ( $homepage_box_url ) . '"' . $target . '>';
					$close_url = '</a>';
				}
				?>
                <li class="penci-featured-ct">
					<?php echo wp_kses( $open_url, penci_allow_html() ); ?>
                    <div class="penci-fea-in<?php if ( get_theme_mod( 'penci_home_box_style_2' ) && ! get_theme_mod( 'penci_home_box_style_3' ) ): echo ' boxes-style-2'; endif; ?><?php if ( get_theme_mod( 'penci_home_box_style_3' ) ): echo ' boxes-style-3'; endif; ?>">

                        <div <?php echo penci_layout_bg( penci_get_image_size_url( $homepage_box_image, $thumb ), '' ); ?> class="<?php echo penci_layout_bg_class();?> fea-box-img penci-image-holder penci-holder-load">
							<?php echo penci_layout_img( penci_get_image_size_url( $homepage_box_image, $thumb ), '' ); ?>
                        </div>


						<?php if ( $homepage_box_text ): ?>
                            <h4><span class="boxes-text"><span
                                            style="font-weight: <?php echo $weight_text; ?>"><?php echo sanitize_text_field( $homepage_box_text ); ?></span></span>
                            </h4>
						<?php endif; ?>
                    </div>
					<?php echo wp_kses( $close_url, penci_allow_html() ); ?>
                </li>
			<?php
			endif;
		}
		?>
    </ul>
</div>
