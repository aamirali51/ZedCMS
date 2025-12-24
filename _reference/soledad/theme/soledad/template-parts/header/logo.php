<?php
$logo_url = esc_url( home_url( '/' ) );
if ( get_theme_mod( 'penci_custom_url_logo' ) ) {
	$logo_url = get_theme_mod( 'penci_custom_url_logo' );
}
$logo_src = PENCI_SOLEDAD_URL . '/images/logo.png';
if ( get_theme_mod( 'penci_logo' ) ) {
	$logo_src = get_theme_mod( 'penci_logo' );
}

if ( is_page() ) {
	$pmeta_page_header = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );
	if ( isset( $pmeta_page_header['custom_logo'] ) && $pmeta_page_header['custom_logo'] ) {

		$logo_src_meta = wp_get_attachment_url( intval( $pmeta_page_header['custom_logo'] ) );
		if ( $logo_src_meta ) {
			$logo_src = $logo_src_meta;
		}
	}
}
$extra_logo_class = '';
$header_layout    = penci_soledad_get_header_layout();
if ( in_array( $header_layout, array( 'header-1', 'header-2', 'header-4', 'header-5', 'header-7', 'header-8' ) ) ) {
	$extra_logo_class = ' pclogo-cls';
}
$data_dark_logo = '';
$dark_logo      = get_theme_mod( 'penci_menu_logo_dark' );
if ( $dark_logo && get_theme_mod( 'penci_dms_enable' ) ) {
	$data_dark_logo .= 'data-lightlogo="' . esc_url( $logo_src ) . '"';
	$data_dark_logo .= ' data-darklogo="' . esc_url( $dark_logo ) . '"';
}
?>
<a href="<?php echo $logo_url; ?>"><img
            class="penci-mainlogo penci-limg<?php echo $extra_logo_class; ?>" <?php echo $data_dark_logo; ?>
            src="<?php echo esc_url( $logo_src ); ?>" alt="<?php bloginfo( 'name' ); ?>"
            width="<?php echo penci_get_image_data_basedurl( $logo_src, 'w' ); ?>"
            height="<?php echo penci_get_image_data_basedurl( $logo_src, 'h' ); ?>"></a>
