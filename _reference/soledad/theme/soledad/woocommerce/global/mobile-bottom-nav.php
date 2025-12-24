<?php
defined( 'ABSPATH' ) || exit;
$mobile_bottom_nav = get_theme_mod( 'penci_woo_mobile_bottom_nav', false );
$menu_id           = get_theme_mod( 'penci_woo_mobile_nav_menu' );
$menu_custom       = get_theme_mod( 'penci_woo_mobile_show_custom_nav' );
$menu_woo_pages    = get_theme_mod( 'penci_woo_mobile_nav_items' );
if ( $menu_custom && $menu_id ) {
	?>
    <div class="penci-mobile-bottom-nav">
        <nav aria-label="Navigation Extra Menus">
			<?php
			wp_nav_menu( array(
				'menu'           => $menu_id,
				'container'      => false,
				'theme_location' => 'mobile-bottom-menu',
				'menu_class'     => 'menu',
				'depth'          => 1,
				'fallback_cb'    => 'penci_menu_fallback',
				'walker'         => new penci_menu_walker_nav_menu()
			) );
			?>
        </nav>
    </div>
	<?php
}
if ( $menu_woo_pages ) {
	?>
    <div class="penci-mobile-bottom-nav">
        <nav aria-label="<?php esc_attr_e('Navigation Extra Menus','soledad');?>">
            <ul>
				<?php
				$pages = $menu_woo_pages;
				foreach ( $pages as $page ) {
					$classes = strtolower( str_replace( ' ', '', $page ) );
					$icon    = '<i class="penci-footer-icon ' . esc_attr( $classes ) . '"></i>';
					?>

                    <li class="<?php echo esc_attr( $classes ); ?>">
                        <a href="<?php echo esc_url( penci_woo_get_custom_page_link( $page ) ); ?>">
							<?php echo $icon . $page; ?>
                        </a>
						<?php do_action( 'penci_footer_nav_' . $classes . '_item' ); ?>
                    </li>

					<?php
				}
				?>
            </ul>
        </nav>
    </div>
    </div>
	<?php
}
