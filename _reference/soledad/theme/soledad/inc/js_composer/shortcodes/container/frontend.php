<?php
$el_class          = $el_width = $columns_placement = $container_layout = '';
$content_placement = $css = $el_id = $css_animation = '';
$disable_element   = $ctsidebar_mb = $el_enable_sticky = '';
$output          = $after_output = '';
$atts            = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

Penci_Global_Data_Blocks::set_data_row( $container_layout );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_classes = array(
	'penci-vc-container',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

if ( ! empty( $container_layout ) ) {
	$css_classes[] = 'layout-' . $container_layout;

	if( in_array( $container_layout, array( '13_23','23_13','14_12_14','12_14_14','14_14_12' ) ) ) {
		$css_classes[] = 'penci-vc-dis-padding';
	}
}

if ( ! empty( $el_width ) ) {
	$css_classes[] = 'vc-penci-' . $el_width;
}
if ( ! empty( $ctsidebar_mb ) ) {
	$css_classes[] = 'penci-' . $ctsidebar_mb;
}

if ( 'yes' == $el_enable_sticky ) {
	$css_classes[] = 'penci-vc-sticky-sidebar';
}

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$css_class            = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '><div class="penci-vc-row">';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div></div>';
$output .= $after_output;

// @codingStandardsIgnoreLine
echo $output;

Penci_Global_Data_Blocks::reset_data_row();
