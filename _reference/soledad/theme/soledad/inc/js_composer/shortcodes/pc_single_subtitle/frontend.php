<?php
$settings    = vc_map_get_attributes( $this->getShortcode(), $atts );
$block_id    = Penci_Vc_Helper::get_unique_id_block( 'pc_single_subtitle' );
$css_custom  = '';
$heading_tag = $settings['heading_markup'];
$sub_title   = get_post_meta( get_the_ID(), 'penci_post_sub_title', true );
$css_class   = vc_shortcode_custom_css_class( $settings['css'] );
if ( $sub_title ) {
	echo '<' . $heading_tag . ' class="penci-psub-title ' . $css_class . '" id="' . $block_id . '"><span>' . $sub_title . '</span></' . $heading_tag . '>';
}
$css      = [
	'heading_align'   => [ '{{WRAPPER}} .penci-psub-title' => 'text-align:{{VALUE}}' ],
	'main-text-color' => [ '{{WRAPPER}} .penci-psub-title' => 'color:{{VALUE}} !important' ],
];
$typo_css = Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $settings['main_text_size'],
	'font_style' => $settings['main_text_font'],
	'template'   => '#' . $block_id . '  .penci-psub-title{ %s }',
) );
penci_wpbakery_el_extract_css( $css, $settings, '#' . $block_id, $typo_css );
