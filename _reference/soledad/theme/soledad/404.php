<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Wordpress
 * @since   1.0
 */
get_header();

/**
 * Set default value if fields is not isset
 *
 */
$image        = ! get_theme_mod( 'penci_not_found_image' ) ? PENCI_SOLEDAD_URL . '/images/404.png' : get_theme_mod( 'penci_not_found_image' );
$image_width  = 212;
$image_height = 87;
if ( get_theme_mod( 'penci_not_found_image' ) ) {
	$image_width  = penci_get_image_data_basedurl( $image, 'w' );
	$image_height = penci_get_image_data_basedurl( $image, 'h' );
}
?>

    <div class="container page-404">
        <div class="error-404">
            <div class="error-image">
                <img src="<?php echo esc_url( $image ); ?>" alt="404" width="<?php echo $image_width; ?>"
                     height="<?php echo $image_height; ?>"/>
            </div>
			<?php if ( penci_get_setting( 'penci_not_found_sub_heading' ) ): ?>
                <p class="sub-heading-text-404"><?php echo penci_get_setting( 'penci_not_found_sub_heading' ); ?></p>
			<?php endif; ?>
			<?php if ( ! get_theme_mod( 'penci_not_found_hide_search' ) ): get_search_form();endif; ?>
			<?php if ( ! get_theme_mod( 'penci_not_found_hide_backhome' ) ): ?>
                <p class="go-back-home"><a
                            href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo penci_get_setting( 'penci_trans_back_to_homepage' ); ?></a>
                </p>
			<?php endif; ?>
        </div>
    </div>
<?php get_footer(); ?>