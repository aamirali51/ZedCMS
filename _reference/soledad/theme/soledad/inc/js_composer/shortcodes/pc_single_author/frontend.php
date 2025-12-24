<?php
$settings  = vc_map_get_attributes( $this->getShortcode(), $atts );
$css_class = vc_shortcode_custom_css_class( $settings['css'] );
$mods      = [
	'penci_authorbio_style',
	'penci_author_ava_size',
	'penci_bioimg_style'
];
foreach ( $mods as $mod ) {
	$value = $settings[ $mod ];
	add_filter( 'theme_mod_' . $mod, function () use ( $value ) {
		return $value;
	} );
}
echo '<div class="' . $css_class . '">';
get_template_part( 'inc/templates/about_author' );
echo '</div>';
