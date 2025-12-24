<?php 
$header_data    = $args['header_data'];
$logo_settings = 'penci_header_pb_logo_sticky';
$logo_url      = get_theme_mod( 'penci_custom_url_logo' ) ? get_theme_mod( 'penci_custom_url_logo' ) : esc_url( home_url( '/' ) );
$logo_display  = penci_builder_validate_mod( $header_data, $logo_settings . '_logo_display', 'image' );
$logo_title    = penci_builder_validate_mod( $header_data, $logo_settings . '_site_title' );
$logo_slogan   = penci_builder_validate_mod( $header_data, $logo_settings . '_site_description' );
$logo_class    = penci_builder_validate_mod( $header_data, $logo_settings . '_class' );
if ( penci_builder_validate_mod( $header_data, $logo_settings . '_image_setting_href' ) ) {
	$logo_url = penci_builder_validate_mod( $header_data, $logo_settings . '_image_setting_href' );
}
$logo_src = PENCI_SOLEDAD_URL . '/images/logo.png';
if ( get_theme_mod( 'penci_logo' ) ) {
	$logo_src = get_theme_mod( 'penci_logo' );
}
if ( penci_builder_validate_mod( $header_data, $logo_settings . '_image_setting_url' ) ) {
	$logo_src = penci_builder_validate_mod( $header_data, $logo_settings . '_image_setting_url' );
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
$extra_logo_class = ' pclogo-cls';
if ( 'image' == $logo_display ):
	$data_dark_logo = '';
	$dark_logo      = get_theme_mod( 'penci_menu_logo_dark' );
	if ( $dark_logo && get_theme_mod( 'penci_dms_enable' ) ) {
		$data_dark_logo .= 'data-lightlogo="' . esc_url( $logo_src ) . '"';
		$data_dark_logo .= ' data-darklogo="' . esc_url( $dark_logo ) . '"';
	}
	?>
    <div class="pc-builder-element pc-logo-sticky pc-logo penci-header-image-logo <?php echo esc_attr( $logo_class ); ?>">
        <a href="<?php echo $logo_url; ?>">
            <img class="penci-mainlogo penci-limg<?php echo $extra_logo_class; ?>" <?php echo $data_dark_logo;?>
                 src="<?php echo esc_url( $logo_src ); ?>"
                 alt="<?php bloginfo( 'name' ); ?>"
                 width="<?php echo penci_get_image_data_basedurl( $logo_src, 'w' ); ?>"
                 height="<?php echo penci_get_image_data_basedurl( $logo_src, 'h' ); ?>">
			<?php if ( ! empty( $logo_slogan ) ): ?>
                <div class="site-slogan"><span><?php echo esc_attr( $logo_slogan ); ?></span></div>
			<?php endif; ?>
        </a>
    </div>
<?php 
else: ?>
    <div class="pc-builder-element pc-logo pc-logo-sticky penci-header-text-logo <?php echo esc_attr( $logo_class ); ?>">
        <a href="<?php echo $logo_url; ?>">
			<?php if ( ! empty( $logo_title ) ): ?>
                <div class="site-name"><?php echo esc_attr( $logo_title ); ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $logo_slogan ) ): ?>
                <div class="site-slogan"><span><?php echo esc_attr( $logo_slogan ); ?></span></div>
			<?php endif; ?>
        </a>
    </div>
<?php 
endif;
