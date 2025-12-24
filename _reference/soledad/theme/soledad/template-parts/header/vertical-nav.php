<?php
$header_layout = penci_soledad_get_header_layout();
$logo_url_nav  = esc_url( home_url( '/' ) );
if ( get_theme_mod( 'penci_custom_url_logo' ) ) {
	$logo_url = get_theme_mod( 'penci_custom_url_logo' );
}
if ( get_theme_mod( 'penci_custom_url_logo_vertical' ) ) {
	$logo_url_nav = get_theme_mod( 'penci_custom_url_logo_vertical' );
}
$style = 'mstyle-' . get_theme_mod( 'penci_moble_vertical_menu_style', 'default' );

if ( ! get_theme_mod( 'penci_vertical_nav_show' ) ) { ?>
    <a href="#" id="close-sidebar-nav"
       class="<?php echo esc_attr( $header_layout . ' ' . $style ); ?>"><?php penci_fawesome_icon( 'fas fa-times' ); ?></a>
    <nav id="sidebar-nav" class="<?php echo esc_attr( $header_layout . ' ' . $style ); ?>" role="navigation"
	     <?php if ( ! get_theme_mod( 'penci_schema_sitenav' ) ): ?>itemscope
         itemtype="https://schema.org/SiteNavigationElement"<?php endif; ?>>

		<?php if ( penci_vernav_builder_content() ) {

			echo penci_vernav_builder_content();

		} else {

			if ( ! get_theme_mod( 'penci_header_logo_vertical' ) ) :
				$dark_logo = get_theme_mod( 'penci_menu_logo_dark' );
				$dark_logo = get_theme_mod( 'penci_menu_logosidebar_dark' ) ? get_theme_mod( 'penci_menu_logosidebar_dark' ) : $dark_logo;

				$data_dark_logo = '';
				if ( $dark_logo ) {
					$data_dark_logo .= 'data-darklogo="' . esc_url( $dark_logo ) . '"';
				}

				?>
                <div id="sidebar-nav-logo">
					<?php if ( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
						<?php if ( get_theme_mod( 'penci_mobile_nav_logo' ) ) {
							$logo_img = penci_holder_image_base( penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_mobile_nav_logo' ) ), 'w' ), penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_mobile_nav_logo' ) ), 'h' ) );
							?>
                            <a href="<?php echo $logo_url_nav; ?>"><img
                                        class="penci-lazy sidebar-nav-logo penci-limg" <?php echo $data_dark_logo; ?>
                                        src="<?php echo $logo_img; ?>"
                                        width="<?php echo penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_mobile_nav_logo' ) ), 'w' ); ?>"
                                        height="<?php echo penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_mobile_nav_logo' ) ), 'h' ); ?>"
                                        data-src="<?php echo esc_url( get_theme_mod( 'penci_mobile_nav_logo' ) ); ?>"
                                        data-lightlogo="<?php echo esc_url( get_theme_mod( 'penci_mobile_nav_logo' ) ); ?>"
                                        alt="<?php bloginfo( 'name' ); ?>"/></a>
						<?php } elseif ( get_theme_mod( 'penci_logo' ) ) {
							$logo_img = penci_holder_image_base( penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_logo' ) ), 'w' ), penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_logo' ) ), 'h' ) );
							?>
                            <a href="<?php echo $logo_url_nav; ?>"><img
                                        class="penci-lazy penci-limg" <?php echo $data_dark_logo; ?>
                                        src="<?php echo $logo_img; ?>"
                                        width="<?php echo penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_logo' ) ), 'w' ); ?>"
                                        height="<?php echo penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_logo' ) ), 'h' ); ?>"
                                        data-src="<?php echo esc_url( get_theme_mod( 'penci_logo' ) ); ?>"
                                        data-lightlogo="<?php echo esc_url( get_theme_mod( 'penci_logo' ) ); ?>"
                                        alt="<?php bloginfo( 'name' ); ?>"/></a>
						<?php } else {
							$logo_img = penci_holder_image_base( 12, 36 );
							?>
                            <a href="<?php echo $logo_url_nav; ?>"><img
                                        class="penci-lazy penci-limg" <?php echo $data_dark_logo; ?>
                                        src="<?php echo $logo_img; ?>"
                                        width="125" height="36"
                                        data-src="<?php echo PENCI_SOLEDAD_URL; ?>/images/mobile-logo.png"
                                        data-lightlogo="<?php echo PENCI_SOLEDAD_URL; ?>/images/mobile-logo.png"
                                        alt="<?php bloginfo( 'name' ); ?>"/></a>
						<?php } ?>
					<?php } else { ?>
						<?php if ( get_theme_mod( 'penci_mobile_nav_logo' ) ) { ?>
                            <a href="<?php echo $logo_url_nav; ?>"><img
                                        src="<?php echo esc_url( get_theme_mod( 'penci_mobile_nav_logo' ) ); ?>"
                                        width="<?php echo penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_mobile_nav_logo' ) ), 'w' ); ?>"
                                        height="<?php echo penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_mobile_nav_logo' ) ), 'h' ); ?>"
                                        alt="<?php bloginfo( 'name' ); ?>"/></a>
						<?php } elseif ( get_theme_mod( 'penci_logo' ) ) { ?>
                            <a href="<?php echo $logo_url_nav; ?>"><img
                                        src="<?php echo esc_url( get_theme_mod( 'penci_logo' ) ); ?>"
                                        width="<?php echo penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_logo' ) ), 'w' ); ?>"
                                        height="<?php echo penci_get_image_data_basedurl( esc_url( get_theme_mod( 'penci_logo' ) ), 'h' ); ?>"
                                        alt="<?php bloginfo( 'name' ); ?>"/></a>
						<?php } else { ?>
                            <a href="<?php echo $logo_url_nav; ?>"><img
                                        src="<?php echo PENCI_SOLEDAD_URL; ?>/images/mobile-logo.png"
                                        width="125"
                                        height="36" alt="<?php bloginfo( 'name' ); ?>"/></a>
						<?php } ?>
					<?php } ?>
                </div>
			<?php endif; ?>

			<?php if ( ! get_theme_mod( 'penci_header_social_vertical' ) ) : ?>
                <div class="header-social sidebar-nav-social<?php if ( get_theme_mod( 'penci_header_social_vertical_brand' ) ): echo ' penci-social-textcolored'; endif; ?>">
					<?php get_template_part( 'inc/modules/socials' ); ?>
                </div>
			<?php endif; ?>

			<?php if ( get_theme_mod( 'penci_vernav_searchform' ) ): ?>
                <div class="penci-hbg-search-box penci-vernav-search-box">
                    <form role="search" method="get" class="pc-searchform penci-hbg-search-form"
                          action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="inner-hbg-search-form">
                            <input type="text" class="search-input"
                                   placeholder="<?php echo penci_get_setting( 'penci_trans_type_and_hit' ); ?>"
                                   name="s"/>
                            <i class="penciicon-magnifiying-glass"></i>
                        </div>
                    </form>
                </div>
			<?php endif; ?>

			<?php
			/**
			 * Display main navigation
			 */
			$menu_id            = '';
			$custom_menu_vernav = get_theme_mod( 'penci_moble_vertical_menu' );
			if ( $custom_menu_vernav ) {
				$menu_id = $custom_menu_vernav;
			}
			if ( is_page() ) {
				$pmeta_page_header = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );
				if ( isset( $pmeta_page_header['main_nav_menu'] ) && $pmeta_page_header['main_nav_menu'] ) {
					$menu_id = $pmeta_page_header['main_nav_menu'];
				}
			}
			wp_nav_menu( array(
				'menu'           => $menu_id,
				'container'      => false,
				'theme_location' => 'main-menu',
				'menu_class'     => 'menu',
				'fallback_cb'    => 'penci_menu_fallback',
				'walker'         => new penci_menu_walker_nav_menu()
			) );
		}
		?>
    </nav>
<?php } ?>
