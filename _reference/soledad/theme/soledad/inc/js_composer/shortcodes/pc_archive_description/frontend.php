<?php
$settings  = vc_map_get_attributes( $this->getShortcode(), $atts );
$block_id  = Penci_Vc_Helper::get_unique_id_block( 'pc_archive_description' );
$css_class = vc_shortcode_custom_css_class( $settings['css'] );
if ( is_tag() && tag_description() ) {
	echo '<div id="' . $block_id . '" class="' . $css_class . ' penci-archive-description penci-tag-description">' . do_shortcode( tag_description() ) . '</div>';
} elseif ( is_category() && category_description() ) {
	echo '<div id="' . $block_id . '" class="' . $css_class . ' penci-archive-description penci-category-description">' . do_shortcode( category_description() ) . '</div>';
} elseif ( is_archive() ) {
	the_archive_description( '<div id="' . $block_id . '" class="' . $css_class . ' post-entry penci-category-description penci-archive-description penci-acdes-below">', '</div>' );
}
$css_custom = '';
$block_id = '#'.$block_id;
if ( $settings['text_align'] ) {
	$css_custom .= $block_id . ' .penci-category-description.post-entry{text-align:' . $settings['text_align'] . '}';
}
if ( $settings['text_color'] ) {
	$css_custom .= $block_id . ' .penci-category-description.post-entry{color:' . $settings['text_color'] . '}';
}
if ( $settings['text_lcolor'] ) {
	$css_custom .= $block_id . ' .penci-category-description.post-entry a{color:' . $settings['text_lcolor'] . '}';
}
if ( $settings['text_lhcolor'] ) {
	$css_custom .= $block_id . ' .penci-category-description.post-entry a:hover{color:' . $settings['text_lhcolor'] . '}';
}
if ( $settings['responsive_spacing'] ) {
	$css_custom .= penci_extract_spacing_style( $block_id, $settings['responsive_spacing'] );
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $settings['main_text_size'],
	'font_style' => $settings['main_text_font'],
	'template'   => $block_id . '  .penci-category-description.post-entry{ %s }',
) );
if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
