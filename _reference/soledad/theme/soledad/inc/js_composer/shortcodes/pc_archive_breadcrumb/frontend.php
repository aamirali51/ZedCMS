<?php
$settings          = vc_map_get_attributes( $this->getShortcode(), $atts );
$sidebar_position  = penci_get_sidebar_position_archive();
$two_sidebar_class = $css_custom = '';
if ( 'two-sidebar' == $sidebar_position ): $two_sidebar_class = ' two-sidebar'; endif;
$yoast_breadcrumb = '';
if ( function_exists( 'yoast_breadcrumb' ) ) {
	$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb penci-crumb-inside' . $two_sidebar_class . '">', '</div>', false );
}
$block_id = Penci_Vc_Helper::get_unique_id_block( 'pc_archive_breadcrumb' );
if ( $yoast_breadcrumb ) {
	echo $yoast_breadcrumb;
} else { ?>
    <div id="<?php echo esc_attr( $block_id ); ?>"
         class="<?php echo vc_shortcode_custom_css_class( $settings['css'] ); ?> container penci-breadcrumb penci-crumb-inside">
                            <span><a class="crumb"
                                     href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
		<?php
		echo '<span>';
		echo penci_get_setting( 'penci_trans_archives' );
		echo '</span>';
		?>
    </div>
<?php }
$block_id = '#' . $block_id;
if ( $settings['breadcrumb_align'] ) {
	$css_custom .= $block_id . ' .penci-breadcrumb{text-align:' . $settings['breadcrumb_align'] . '}';
}
if ( $settings['breadcrumb-t-color'] ) {
	$css_custom .= $block_id . ' .penci-breadcrumb *{color:' . $settings['breadcrumb_align'] . '}';
}
if ( $settings['breadcrumb-t-hcolor'] ) {
	$css_custom .= $block_id . ' .penci-breadcrumb a{color:' . $settings['breadcrumb-t-hcolor'] . '}';
}
if ( $settings['breadcrumb-spacing'] ) {
	$css_custom .= $block_id . ' .container.penci-breadcrumb i{margin-left:' . $settings['breadcrumb-spacing'] . 'px;margin-right:calc(' . $settings['breadcrumb-spacing'] . 'px - 4px)}';
}
if ( $settings['responsive_spacing'] ) {
	$css_custom .= penci_extract_spacing_style( $block_id, $settings['responsive_spacing'] );
}
$css_custom .= Penci_Vc_Helper::vc_google_fonts_parse_attributes( array(
	'font_size'  => $settings['main_text_size'],
	'font_style' => $settings['main_text_font'],
	'template'   => $block_id . '  .container.penci-breadcrumb{ %s }',
) );
if ( $css_custom ) {
	echo '<style>';
	echo $css_custom;
	echo '</style>';
}
