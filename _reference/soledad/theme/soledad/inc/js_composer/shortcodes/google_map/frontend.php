<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $map_width
 * @var $map_height
 * @var $map_zoom
 * @var $marker_img
 * @var $css_animation
 * @var $el_class
 * @var $css
 */

$map_width = $map_height = $map_zoom = $marker_img = '';
$el_class  = $css_animation = $css = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_class = 'penci-block-vc penci-google-map';
$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$width  = intval( $map_width ) ? $map_width : '100%';
$height = intval( $map_height ) ? $map_height : '500px';

$atts['map_zoom']   = intval( $map_zoom ? $map_zoom : 8 );
$atts['marker_img'] = wp_get_attachment_url( $marker_img );

$block_id = Penci_Vc_Helper::get_unique_id_block( 'map' );
$option   = htmlentities( json_encode( $atts ), ENT_QUOTES, "UTF-8" );

printf( '<div style="width:%s;height:%s" id="%s" class="%s" data-map_options="%s"></div>', $width, $height, $block_id, $css_class, $option );
