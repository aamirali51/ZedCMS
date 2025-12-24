<?php
$el_class = $el_id = $width = $css = $offset = $css_animation = $order = '';
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$width = penci_wpb_translateColumnWidthToSpan( $width, $order );
$css_classes = array(
	$this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation ),
	'penci-vc-column',
	$width
);

$wrapper_attributes = array();
$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$innerColumnClass = 'penci-column-inner ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) );
$output .= '<div class="' . trim( $innerColumnClass ) . '">';
$output .= '<div class="wpb_wrapper">';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;
