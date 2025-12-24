<?php if ( ! get_theme_mod( 'penci_vertical_nav_show' ) ) : ?>
	<?php $navigation_class = penci_soledad_sitenavigation_classes(); ?>
	<?php
	$header_trans      = penci_is_header_transparent();
	$dis_sticky_header = get_theme_mod( 'penci_disable_sticky_header' );
	if ( $dis_sticky_header ) {
		echo '<div class="sticky-wrapper">';
	}
	?>
    <nav id="navigation" class="<?php echo esc_attr( $navigation_class ); ?>" role="navigation"
	     <?php if ( ! get_theme_mod( 'penci_schema_sitenav' ) ): ?>itemscope
         itemtype="https://schema.org/SiteNavigationElement"<?php endif; ?>>
        <div class="<?php penci_soledad_get_header_width(); ?>">
            <div class="button-menu-mobile header-1"><?php penci_svg_menu_icon(); ?></div>
			<?php
			get_template_part( 'template-parts/header/logo-mobile' );
			get_template_part( 'template-parts/header/menu' );
			?>
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
			?>
			<?php if ( class_exists( 'WooCommerce' ) ): ?>
				<?php get_template_part( 'template-parts/header/shop-icon' ); ?>
			<?php endif;
			do_action( 'penci_top_search' );
			?>
        </div>
    </nav><!-- End Navigation -->
	<?php
	if ( $dis_sticky_header ) {
		echo '</div>';
	}
	?>
<?php endif; ?>
<?php get_template_part( 'template-parts/header/header-second' ); ?>

