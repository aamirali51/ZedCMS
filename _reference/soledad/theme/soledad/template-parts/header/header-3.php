<?php $header_class_main = penci_soledad_wpheader_classes(); ?>
<header id="header" class="<?php echo esc_attr( $header_class_main ); ?>" <?php if( ! get_theme_mod('penci_schema_wphead') ): ?>itemscope="itemscope" itemtype="https://schema.org/WPHeader"<?php endif;?>>
	<?php if ( ! get_theme_mod( 'penci_vertical_nav_remove_header' ) ): ?>
		<div class="inner-header penci-header-second">
			<div class="<?php penci_soledad_get_header_width(); ?> align-left-logo<?php if ( get_theme_mod( 'penci_header_3_banner' ) || get_theme_mod( 'penci_header_3_adsense' ) ): echo ' has-banner'; endif; ?>">
				<div id="logo">
					<?php get_template_part( 'template-parts/header/logo' ); ?>
					<?php if ( ( is_home() || is_front_page() ) && get_theme_mod( 'penci_home_h1content' ) ): echo '<h1 class="penci-hide-tagupdated">' . get_theme_mod( 'penci_home_h1content' ) . '</h1>'; endif; ?>
				</div>

				<?php if ( ( get_theme_mod( 'penci_header_3_adsense' ) || get_theme_mod( 'penci_header_3_banner' ) ) ): ?>
					<?php
					$banner_img       = get_theme_mod( 'penci_header_3_banner' ) ? get_theme_mod( 'penci_header_3_banner' ) : get_stylesheet_directory_uri() . '/images/banner-770x90.jpg';
					$open_banner_url  = '';
					$close_banner_url = '';
					if ( get_theme_mod( 'penci_header_3_banner_url' ) ):
						$banner_url       = get_theme_mod( 'penci_header_3_banner_url' );
						$open_banner_url  = '<a rel="noopener" href="' . esc_url( $banner_url ) . '" target="_blank">';
						$close_banner_url = '</a>';
					endif;
					?>
					<div class="header-banner header-style-3">
						<?php if ( get_theme_mod( 'penci_header_3_adsense' ) ): echo do_shortcode( get_theme_mod( 'penci_header_3_adsense' ) ); endif; ?>
						<?php if ( get_theme_mod( 'penci_header_3_banner' ) && ! get_theme_mod( 'penci_header_3_adsense' ) ): ?>
							<?php echo $open_banner_url; ?><img class="pc-hdbanner3" src="<?php echo esc_url( $banner_img ); ?>" alt="Banner" width="<?php echo penci_get_image_data_basedurl( $banner_img, 'w' ); ?>" height="<?php echo penci_get_image_data_basedurl( $banner_img, 'h' ); ?>" /><?php echo $close_banner_url; ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( ! get_theme_mod( 'penci_vertical_nav_show' ) ) : ?>
		<?php
		$class_layout_bottom = penci_soledad_sitenavigation_classes();

		$header_trans      = penci_is_header_transparent();
		$dis_sticky_header = get_theme_mod( 'penci_disable_sticky_header' );
		if( $dis_sticky_header ){
			echo '<div class="sticky-wrapper">';
		}
		?>
		<nav id="navigation" class="<?php echo $class_layout_bottom; ?>" role="navigation" <?php if( ! get_theme_mod('penci_schema_sitenav') ): ?>itemscope itemtype="https://schema.org/SiteNavigationElement"<?php endif; ?>>
			<div class="<?php penci_soledad_get_header_width(); ?>">
				<div class="button-menu-mobile header-3"><?php penci_svg_menu_icon(); ?></div>
				<?php
				if ( get_theme_mod( 'penci_header_logo_mobile' ) ) {
					echo '<div class="penci-mobile-hlogo">';
					get_template_part( 'template-parts/header/logo' );
					echo '</div>';
				}

				get_template_part( 'template-parts/header/menu' );
				do_action( 'penci_top_search' );

				if ( ! get_theme_mod( 'penci_topbar_search_check' ) ) {
					get_template_part( 'template-parts/header/top-search' );
				}
				get_template_part( 'template-parts/menu-hamburger-icon' );

				if ( class_exists( 'WooCommerce' ) ) {
					get_template_part( 'template-parts/header/shop-icon' );
				}
				?>
				<?php if ( get_theme_mod( 'penci_header_social_nav' ) ) : ?>
                    <div class="main-nav-social<?php if ( get_theme_mod( 'penci_header_social_brand' ) ): echo ' penci-social-textcolored'; endif; ?>">
						<?php get_template_part( 'inc/modules/socials' ); ?>
                    </div>
				<?php endif; ?>

            </div>
        </nav><!-- End Navigation -->
		<?php
		if( $dis_sticky_header ){
			echo '</div>';
		}
		?>
	<?php endif; ?>

</header>
<!-- end #header -->
