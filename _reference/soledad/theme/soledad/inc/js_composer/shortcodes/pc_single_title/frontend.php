<?php
$settings      = vc_map_get_attributes( $this->getShortcode(), $atts );
$block_id      = Penci_Vc_Helper::get_unique_id_block( 'pc_single_title' );
$css_custom    = '';
$heading_tag   = $settings['heading_markup'];
$heading_align = $settings['heading_align'];
$css_class     = vc_shortcode_custom_css_class( $settings['css'] );
echo '<div id="' . $block_id . '" class="header-standard align-' . $heading_align . ' ' . $css_class . '"><' . $heading_tag . ' class="post-title single-post-title entry-title"><span>' . get_the_title() . '</span></' . $heading_tag . '></div>';
$css      = [
	'heading_line'       => [
		'{{WRAPPER}} .header-standard:after' => 'display:none',
		'{{WRAPPER}} .header-standard'       => 'padding:0;margin:0;',
	],
	'main-text-color'    => [ '{{WRAPPER}} .post-title' => 'color:{{VALUE}} !important' ],
	'heading-line-color' => [ '{{WRAPPER}} .header-standard:after' => 'background-color:{{VALUE}} !important' ],
	'heading-line-s'     => [ '{{WRAPPER}} .header-standard' => 'padding-bottom:{{VALUE}}px;' ],
	'heading-line-h'     => [
		'{{WRAPPER}} .header-standard:after' => 'height:{{SIZE}}px !important;',
	],
	'heading-line-w'     => [
		'{{WRAPPER}} .header-standard:after'              => 'width:{{SIZE}}px !important;',
		'{{WRAPPER}} .header-standard.align-center:after' => 'margin-left:calc({{SIZE}}px / 2 * -1)',
	],
];
$typo_css = Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $settings['main_text_size'],
	'font_style' => $settings['main_text_font'],
	'template'   => '#' . $block_id . '  .post-title{ %s }',
) );
penci_wpbakery_el_extract_css( $css, $settings, '#' . $block_id, $typo_css );
