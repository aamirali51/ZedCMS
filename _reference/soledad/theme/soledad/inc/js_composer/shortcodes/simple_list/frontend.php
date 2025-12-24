<?php
$icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypo = $icon_linecons = $icon_monosocial = $icon_material = $icon_library = $icons_color = $icons_bg_color = $image = $img_size = $color_scheme = $size = $align = $list = $list_type = $list_style = $el_class = $css_animation = $css = '';
$atts             = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
	vc_icon_element_fonts_enqueue( $icon_library );
}

$list_items = $img = '';

if ( function_exists( 'vc_param_group_parse_atts' ) ) {
	$list_items = vc_param_group_parse_atts( $list );
}

if ( empty( $list_items ) ) {
	return;
}

$list_id = 'penci-list-' . uniqid();

$icon_class = 'list-icon';
if ( $list_type == 'icon' ) {
	$icon_class .= ' ' . ${'icon_' . $icon_library};
}

$list_class = 'penci-list penci-wpb';
$list_class .= ' color-scheme-' . $color_scheme;
$list_class .= ' size-' . $size;
$list_class .= ' penci-list-type-' . $list_type;
$list_class .= ' penci-list-style-' . $list_style;
$list_class .= ' penci-justify-' . $align;
$list_class .= ( $el_class ) ? ' ' . $el_class : '';

if ( $list_style == 'rounded' || $list_style == 'square' ) {
	$list_class .= ' penci-list-shape-icon';
}
if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$list_class .= ' ' . vc_shortcode_custom_css_class( $css ) . $this->getCSSAnimation( $css_animation );
}

ob_start();

?>

    <ul class="<?php echo esc_attr( $list_class ); ?>" id="<?php echo esc_attr( $list_id ); ?>">
		<?php foreach ( $list_items as $item ) : ?>
			<?php
			if ( ! $item ) {
				continue;
			}

			if ( isset( $item['link'] ) ) {
				$link_attrs = penci_get_link_attributes( vc_build_link( $item['link'] ) );
			}

			?>
            <li>
	            <?php if ( isset( $item['link'] ) ) : ?>
                    <a class="penci-fill" <?php echo $link_attrs; ?>>
	            <?php endif; ?>
				<?php if ( $list_type != 'without' && $list_type != 'image' ) : ?>
                    <span class="<?php echo esc_attr( $icon_class ); ?>"></span>
				<?php elseif ( $list_type == 'image' ) : ?>
                    <span class="<?php echo esc_attr( $icon_class ); ?>">
							<?php if ( function_exists( 'wpb_getImageBySize' ) ) : ?>
								<?php
								echo wpb_getImageBySize(
									array(
										'attach_id'  => $image,
										'thumb_size' => $img_size,
										'class'      => 'list-image',
									)
								)['thumbnail'];
								?>
							<?php endif; ?>
						</span>
				<?php endif ?>
                <span class="list-content"><?php echo do_shortcode( $item['list-content'] ); ?></span>
				<?php if ( isset( $item['link'] ) ) : ?>
                    </a>
				<?php endif; ?>
            </li>
		<?php endforeach ?>
    </ul>
<?php
if ( ( $icons_color && ! penci_is_css_encode( $icons_color ) ) || ( $icons_bg_color && ! penci_is_css_encode( $icons_bg_color ) ) ) {
	$css = '#' . esc_attr( $list_id ) . ' .list-icon {';
	$css .= 'color: ' . esc_attr( $icons_color ) . ';';
	$css .= '}';

	if ( $list_style == 'rounded' || $list_style == 'square' ) {
		$css .= '#' . esc_attr( $list_id ) . ' .list-icon {';
		$css .= 'background-color: ' . esc_attr( $icons_bg_color ) . ';';
		$css .= '}';
	}

	wp_add_inline_style( 'penci-woocommerce', $css );

}
?>
<?php

echo ob_get_clean();
