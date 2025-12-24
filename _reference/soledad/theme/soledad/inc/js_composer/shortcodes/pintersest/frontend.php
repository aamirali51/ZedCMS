<?php
$output = $penci_block_width = $el_class = $css_animation = $css = $responsive_spacing = '';

$pusername = $pnumbers = $pcache = $pfollow = '';
$pfollow   = 'yes' == $pfollow;

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-pintersest';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'pintersest' );
?>
    <div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $css_class ); ?>">
		<?php Penci_Vc_Helper::markup_block_title( $atts ); ?>
        <div class="penci-block_content">
            <div class="penci-pinterest-widget-container">
				<?php
				if ( ! $pusername ) {
					esc_html_e( 'Pinterest data error: pinterest data is not set, please check the ID', 'soledad' );
				}
				$pinboard = new Penci_Pinterest();
				$pinboard->render_html( $pusername, $pnumbers, $pcache, $pfollow );
				?>
            </div>
        </div>
    </div>
<?php

$id_pintersest = '#' . $block_id;
$css_custom    = Penci_Vc_Helper::get_heading_block_css( $id_pintersest, $atts );

if ( $responsive_spacing ) {
	$css_custom .= penci_extract_spacing_style( $id_pintersest, $responsive_spacing );
}

if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
