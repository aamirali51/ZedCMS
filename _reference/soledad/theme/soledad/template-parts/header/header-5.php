<?php $header_class_main = penci_soledad_wpheader_classes(); ?>
<header id="header" class="<?php echo esc_attr( $header_class_main ); ?>"
        <?php if ( ! get_theme_mod( 'penci_schema_wphead' ) ): ?>itemscope="itemscope"
        itemtype="https://schema.org/WPHeader"<?php endif; ?>>
	<?php if ( ! get_theme_mod( 'penci_vertical_nav_remove_header' ) ): ?>
        <div class="inner-header penci-header-second">
            <div class="<?php penci_soledad_get_header_width(); ?>">
                <div id="logo">
					<?php get_template_part( 'template-parts/header/logo' ); ?>
					<?php if ( ( is_home() || is_front_page() ) && get_theme_mod( 'penci_home_h1content' ) ): echo '<h1 class="penci-hide-tagupdated">' . get_theme_mod( 'penci_home_h1content' ) . '</h1>'; endif; ?>
                </div>

				<?php if ( penci_get_setting( 'penci_header_slogan_text' ) ) : ?>
                    <div class="header-slogan">
                        <div class="header-slogan-text"><?php echo penci_get_setting( 'penci_header_slogan_text' ); ?></div>
                    </div>
				<?php endif; ?>

				<?php if ( ! get_theme_mod( 'penci_header_social_check' ) ) : ?>
                    <div class="header-social<?php if ( get_theme_mod( 'penci_header_social_brand' ) ): echo ' penci-social-textcolored'; endif; ?>">
						<?php get_template_part( 'inc/modules/socials' ); ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
	<?php endif; ?>
	<?php if ( ! get_theme_mod( 'penci_vertical_nav_show' ) ) : ?>
		<?php $class_layout_bottom = penci_soledad_sitenavigation_classes(); ?>
		<?php
		$header_trans      = penci_is_header_transparent();
		$dis_sticky_header = get_theme_mod( 'penci_disable_sticky_header' );
		if ( $dis_sticky_header ) {
			echo '<div class="sticky-wrapper">';
		}
		?>
        <nav id="navigation" class="<?php echo esc_attr( $class_layout_bottom ); ?>" role="navigation"
		     <?php if ( ! get_theme_mod( 'penci_schema_sitenav' ) ): ?>itemscope
             itemtype="https://schema.org/SiteNavigationElement"<?php endif; ?>>
            <div class="<?php penci_soledad_get_header_width(); ?>">
                <div class="button-menu-mobile header-5"><?php penci_svg_menu_icon(); ?></div>
				<?php if ( get_theme_mod( 'penci_header_logo_mobile' ) ): ?>
                    <div class="penci-mobile-hlogo">
						<?php get_template_part( 'template-parts/header/logo' ); ?>
                    </div>
				<?php endif; ?>

				<?php get_template_part( 'template-parts/header/menu' ); ?>

				<?php if ( get_theme_mod( 'penci_header_social_nav' ) ) : ?>
                    <div class="main-nav-social<?php if ( get_theme_mod( 'penci_header_social_brand' ) ): echo ' penci-social-textcolored'; endif; ?>">
						<?php get_template_part( 'inc/modules/socials' ); ?>
                    </div>
				<?php endif; ?>

				<?php
				get_template_part( 'template-parts/menu-hamburger-icon' );

				if ( ! get_theme_mod( 'penci_topbar_search_check' ) ) {
					get_template_part( 'template-parts/header/top-search' );
				}
				do_action( 'penci_top_search' );
				?>

				<?php if ( class_exists( 'WooCommerce' ) ): ?>
					<?php get_template_part( 'template-parts/header/shop-icon' ); ?>
				<?php endif; ?>
            </div>
        </nav><!-- End Navigation -->
		<?php
		if ( $dis_sticky_header ) {
			echo '</div>';
		}
		?>
	<?php endif; ?>
</header>
<!-- end #header -->
