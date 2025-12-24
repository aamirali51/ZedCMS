<?php
$settings   = vc_map_get_attributes( $this->getShortcode(), $atts );
$block_id   = Penci_Vc_Helper::get_unique_id_block( 'pc_archive_taxonomy' );
$css_custom = '';
$css_class  = vc_shortcode_custom_css_class( $settings['css'] );
?>
    <div id="<?php echo $block_id; ?>" class="pcsb-brcrb <?php echo $css_class; ?>">
		<?php
		$yoast_breadcrumb = '';
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb single-breadcrumb">', '</div>', false );
		}

		if ( $yoast_breadcrumb ) {
			echo $yoast_breadcrumb;
		} else {
			$yoast_breadcrumb = '';
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				$yoast_breadcrumb = yoast_breadcrumb( '<div class="container penci-breadcrumb single-breadcrumb">', '</div>', false );
			}

			if ( $yoast_breadcrumb ) {
				echo $yoast_breadcrumb;
			} else { ?>
                <div class="container penci-breadcrumb single-breadcrumb">
                    <span><a class="crumb"
                             href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon( 'fas fa-angle-right' ); ?>
					<?php
					if ( get_theme_mod( 'enable_pri_cat_yoast_seo' ) ) {
						$primary_term = penci_get_wpseo_primary_term();

						if ( $primary_term ) {
							echo $primary_term;
						} else {
							$penci_cats = get_the_category( get_the_ID() );
							$penci_cat  = array_shift( $penci_cats );
							echo penci_get_category_parents( $penci_cat );
						}
					} else {
						$penci_cats = get_the_category( get_the_ID() );
						$penci_cat  = array_shift( $penci_cats );
						echo penci_get_category_parents( $penci_cat );
					}
					?>
                    <span><?php the_title(); ?></span>
                </div>
			<?php } ?>
		<?php } ?>
    </div>
<?php
$block_id = '#' . $block_id;
if ( $settings['breadcrumb_align'] ) {
	$css_custom .= $block_id . ' .penci-breadcrumb{text-align:' . $settings['breadcrumb_align'] . ';}';
}
if ( $settings['breadcrumb-l-hcolor'] ) {
	$css_custom .= $block_id . ' .penci-breadcrumb a:hover{color:' . $settings['breadcrumb-t-color'] . ';}';
}
if ( $settings['breadcrumb-spacing'] ) {
	$css_custom .= penci_extract_responsive_fsize( $block_id . ' .container.penci-breadcrumb i', 'margin-left', $settings['breadcrumb-spacing'] );
	$css_custom .= penci_extract_responsive_fsize( $block_id . ' .container.penci-breadcrumb i', 'margin-right', $settings['breadcrumb-spacing'] - 4 );
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
