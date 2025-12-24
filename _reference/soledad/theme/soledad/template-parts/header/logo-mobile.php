<?php
if ( get_theme_mod( 'penci_header_logo_mobile' ) ) {
	$logo_url = esc_url( home_url( '/' ) );
	if ( get_theme_mod( 'penci_custom_url_logo' ) ) {
		$logo_url = get_theme_mod( 'penci_custom_url_logo' );
	}

	$logo_src = PENCI_SOLEDAD_URL . '/images/logo.png';
	if ( get_theme_mod( 'penci_logo' ) ) {
		$logo_src = get_theme_mod( 'penci_logo' );
	}
	$data_dark_logo = '';
	$dark_logo      = get_theme_mod( 'penci_menu_logo_dark' );
	if ( $dark_logo && get_theme_mod( 'penci_dms_enable' ) ) {
		$data_dark_logo .= 'data-lightlogo="' . esc_url( $logo_src ) . '"';
		$data_dark_logo .= ' data-darklogo="' . esc_url( $dark_logo ) . '"';
	}
	?>
	<div class="penci-mobile-hlogo">
		<a href="<?php echo esc_url( $logo_url ); ?>"><img class="penci-mainlogo penci-mainlogo-mb penci-limg" <?php echo $data_dark_logo;?> src="<?php echo esc_url( $logo_src ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo penci_get_image_data_basedurl( $logo_src, 'w' ); ?>" height="<?php echo penci_get_image_data_basedurl( $logo_src, 'h' ); ?>" /></a>
	</div>
	<?php
}
