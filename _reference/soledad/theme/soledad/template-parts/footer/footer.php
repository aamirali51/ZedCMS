<?php
/**
 * This is footer template of Soledad theme
 *
 * @package WordPress
 * @since   1.0
 */

if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_category' ) && is_product_category() ) || ( function_exists( 'is_product_tag' ) && is_product_tag() ) || ( function_exists( 'is_product' ) && is_product() ) || ( function_exists( 'penci_is_product_attribute_archive' ) && penci_is_product_attribute_archive() ) ) {
	echo '</div>';
}

$penci_hide_footer  = '';
$hide_fwidget       = get_theme_mod( 'penci_footer_widget_area' );
$footer_layout      = get_theme_mod( 'penci_footer_widget_area_layout' ) ? get_theme_mod( 'penci_footer_widget_area_layout' ) : 'style-1';
$penci_footer_width = get_theme_mod( 'penci_footer_width' );

if ( is_page() ) {
	$penci_hide_footer = get_post_meta( get_the_ID(), 'penci_page_hide_footer', true );

	$pmeta_page_footer = get_post_meta( get_the_ID(), 'penci_pmeta_page_footer', true );
	if ( isset( $pmeta_page_footer['penci_footer_style'] ) && $pmeta_page_footer['penci_footer_style'] ) {
		$footer_layout = $pmeta_page_footer['penci_footer_style'];
	}

	if ( isset( $pmeta_page_footer['penci_hide_fwidget'] ) ) {
		if ( 'yes' == $pmeta_page_footer['penci_hide_fwidget'] ) {
			$hide_fwidget = true;
		} elseif ( 'no' == $pmeta_page_footer['penci_hide_fwidget'] ) {
			$hide_fwidget = false;
		}
	}

	if ( isset( $pmeta_page_footer['penci_footer_width'] ) && $pmeta_page_footer['penci_footer_width'] ) {
		$penci_footer_width = $pmeta_page_footer['penci_footer_width'];
	}
} elseif ( is_single() ) {
	$penci_hide_footer = penci_is_hide_footer();
}

$footer_logo_url = esc_url( home_url( '/' ) );
if ( get_theme_mod( 'penci_custom_url_logo_footer' ) ) {
	$footer_logo_url = get_theme_mod( 'penci_custom_url_logo_footer' );
}
if ( ! $penci_hide_footer ) : ?>
    <div class="clear-footer"></div>

	<?php
	if ( get_theme_mod( 'penci_footer_adsense' ) ) :
		echo '<div class="container penci-google-adsense penci-google-adsense-footer">' . do_shortcode( get_theme_mod( 'penci_footer_adsense' ) ) . '</div>';
	endif;
	?>
	<?php
	// footer builder start here
	if ( function_exists( 'penci_can_render_footer' ) && penci_can_render_footer() ) {
		penci_footer_builder_content();
	} else {
		if ( get_theme_mod( 'penci_footer_delayed' ) ) {
			$footer_settings = [
				'footer_logo_url' => $footer_logo_url,
				'penci_footer_width' => $penci_footer_width,
				'footer_layout' => $footer_layout,
				'hide_fwidget' => $hide_fwidget,
			];
			echo '<div class="pcfb-footer-delayed pc-content-delayed" data-type="template-footer" data-settings=\''.htmlentities( json_encode( $footer_settings ), ENT_QUOTES, "UTF-8" ).'\'></div>';
		} else {
			include PENCI_SOLEDAD_DIR . '/template-parts/footer/footer-main.php';
		}
	}
endif; // Hide footer ?>

</div><!-- End .wrapper-boxed -->