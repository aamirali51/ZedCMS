<?php
$block_id       = Penci_Vc_Helper::get_unique_id_block( 'button_popup' );
$settings       = vc_map_get_attributes( $this->getShortcode(), $atts );
$text           = $settings['text'] ? $settings['text'] : '';
$link           = '';
$button_id_attr = '';
$button_id      = $settings['button_id'] ? $settings['button_id'] : '';
if ( $button_id ) {
	$button_id      = str_replace( ' ', '', $button_id );
	$button_id_attr = ' id="' . $button_id . '"';
}
$add_icon       = $settings['add_icon'] ? $settings['add_icon'] : '';
$icon_pos       = $settings['icon_pos'] ? $settings['icon_pos'] : 'left';
$align          = $settings['align'] ? $settings['align'] : 'none';
$align_class    = 'pcbtn-align-' . $align;
$add_subtext    = $settings['add_subtext'] ? $settings['add_subtext'] : '';
$subtext        = $settings['subtext'] ? $settings['subtext'] : '';
$button_icon    = $settings['button_icon'] ? $settings['button_icon'] : array();
$icon_html      = '';
$button_classes = 'pc-popup-btn pcbtn pcbtn-icon-' . $icon_pos;
if ( 'yes' == $add_icon && ! empty( $button_icon ) ) {
	$icon_html = '<i class="' . $button_icon . '"></i>';
}
$id             = 'penci-btn-popup-' . $block_id;
$anistyle       = isset( $settings['popup_anityle'] ) && $settings['popup_anityle'] ? $settings['popup_anityle'] : 'move-to-top';
$popup_position = isset( $settings['popup_position'] ) && $settings['popup_position'] ? $settings['popup_position'] : 'middle-center';
$popup_cstyle   = isset( $settings['popup_cstyle'] ) && $settings['popup_cstyle'] ? $settings['popup_cstyle'] : 'text';
?>
    <div id="<?php echo $block_id; ?>" class="pcbtn-wrapper penci-button-popup <?php echo $align_class; ?>">
        <a data-popup="<?php echo $id; ?>"
           data-position="<?php echo esc_attr( $popup_position . ' penci-pps-' . $block_id ); ?>"
           class="<?php echo $button_classes; ?>"
           href="#<?php echo $id; ?>"<?php
		echo $button_id_attr; ?>>
				<span class="pcbtn-wrapperin">
					<span class="pcbtn-content"><?php if ( 'left' == $icon_pos ): echo $icon_html; endif; ?><?php echo do_shortcode( $text ); ?><?php if ( 'right' == $icon_pos ): echo $icon_html; endif; ?></span>
					<?php if ( 'yes' == $add_subtext && $subtext ) { ?>
                        <span class="pcbtn-subtext"><?php echo do_shortcode( $subtext ); ?></span>
					<?php } ?>
				</span>
        </a>
    </div>
    <div id="<?php echo $id; ?>"
         class="<?php echo esc_attr( $anistyle ); ?> <?php echo esc_attr( $popup_position ); ?> mfp-with-anim button-popup-content">
		<?php if ( 'text' == $popup_cstyle ) {
			echo do_shortcode( $settings['popup_content'] );
		} else if ( 'block' == $popup_cstyle && $settings['popup_block'] ) {
			$popup_render_content = '';
			$popup_block_id       = get_page_by_path( $settings['popup_block'], OBJECT, 'penci-block' )->ID;
			if ( $popup_block_id && did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->documents->get( $popup_block_id )->is_built_with_elementor() ) {
				$popup_render_content .= penci_get_elementor_content( $popup_block_id );
			} else {
				$popup_render_content .= do_shortcode( get_post( $popup_block_id )->post_content );

				$shortcodes_custom_css = get_post_meta( $popup_block_id, '_wpb_shortcodes_custom_css', true );

				$popup_render_content .= '<style data-type="vc_shortcodes-custom-css">';
				if ( ! empty( $shortcodes_custom_css ) ) {
					$popup_render_content .= $shortcodes_custom_css;
				}
				$popup_render_content .= '</style>';
			}
			echo $popup_render_content;
		} ?>
    </div>
<?php
$id_block   = 'body:not(.pcdm-enable) #' . $block_id;
$css_custom = Penci_Vc_Helper::get_heading_block_css( $id_block, $atts );
if ( $settings['btn_color'] ) {
	$css_custom .= $id_block . ' a span.pcbtn-content{ color:' . esc_attr( $settings['btn_color'] ) . ';}';
}
if ( $settings['btn_hcolor'] ) {
	$css_custom .= $id_block . ' a:hover span.pcbtn-content{ color:' . esc_attr( $settings['btn_color'] ) . ';}';
}
if ( $settings['popup_height'] ) {
	$css_custom .= '#penci-btn-popup-' . $block_id . '.button-popup-content{ min-height:' . esc_attr( $settings['popup_height'] ) . 'px;}';
}
if ( $settings['popup_width'] ) {
	$css_custom .= '#penci-btn-popup-' . $block_id . '.button-popup-content{ max-width:' . esc_attr( $settings['popup_width'] ) . 'px;}';
}
if ( $settings['overlay_bgcolor'] ) {
	$css_custom .= '.mfp-bg.penci-pps-' . $block_id . '{background-color:' . esc_attr( $settings['overlay_bgcolor'] ) . ';}';
}
if ( $settings['overlay_opacity'] ) {
	$css_custom .= '.mfp-ani-wrap.mfp-ready.mfp-bg.penci-pps-' . $block_id . '{opacity:' . esc_attr( $settings['overlay_opacity'] ) . ';}';
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $settings['btn_size'],
	'font_style' => $settings['btn_typo'],
	'template'   => $id_block . ' span.pcbtn-content{ %s }',
) );
if ( $settings['responsive_spacing'] ) {
	$css_custom .= penci_extract_spacing_style( $id_block, $settings['responsive_spacing'] );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
wp_enqueue_script( 'penci-button-popup', PENCI_SOLEDAD_URL . '/inc/elementor/assets/js/penci-button-popup.js', array( 'jquery' ), '1.0', true );
