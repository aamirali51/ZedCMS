<?php
$settings   = vc_map_get_attributes( $this->getShortcode(), $atts );
$css_class  = vc_shortcode_custom_css_class( $settings['css'] );
$block_id   = Penci_Vc_Helper::get_unique_id_block( 'pc_single_relatedposts' );
$css_custom = '';
$mods       = [
	'penci_post_related_autoplay',
	'penci_numbers_related_post',
	'penci_related_orderby',
	'penci_related_sort_order',
	'penci_related_posts_title_length',
	'penci_related_by',
	'penci_post_related_grid',
	'penci_post_related_dots',
	'penci_post_related_arrows',
	'penci_post_related_icons',
	'penci_hide_date_related',
	'penci_post_related_text'
];
foreach ( $mods as $mod ) {
	$value = $settings[ $mod ];
	add_filter( 'theme_mod_' . $mod, function () use ( $value ) {
		return penci_switch_value( $value );
	} );
}
$settings['heading'] = $settings['penci_post_related_text'];
echo '<div id="' . $block_id . '" class="' . $css_class . '">';
get_template_part( 'inc/templates/related_posts' );
echo '</div>';
$typo_css = '';
$css      = [
	'post_title_color'  => [ '{{WRAPPER}} .item-related h3 a' => 'color:{{VALUE}}' ],
	'post_title_hcolor' => [ '{{WRAPPER}} .item-related h3 a:hover' => 'color:{{VALUE}} !important' ],
	'post_date_color'   => [ '{{WRAPPER}} .item-related span.date' => 'color:{{VALUE}} !important' ],
];
$typo_css .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $settings['post_title_size'],
	'font_style' => $settings['post_title_font'],
	'template'   => '#' . $block_id . '  .item-related h3{ %s }',
) );
$typo_css .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $settings['post_date_size'],
	'font_style' => $settings['post_date_font'],
	'template'   => '#' . $block_id . '  .item-related span.date{ %s }',
) );
if ( $settings['post_img_ratio'] ) {
	$typo_css = penci_extract_md_responsive_fsize( '#' . $block_id . ' .item-related > a:before{padding-top:{{VALUE}}%;}', $settings['post_img_ratio'] );
}
penci_wpbakery_el_extract_css( $css, $settings, '#' . $block_id, $typo_css );
