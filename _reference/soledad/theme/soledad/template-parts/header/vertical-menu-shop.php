<?php
$current_state = 'close';
$menu_title    = get_theme_mod( 'penci_woo_vertical_menu_title', 'Shop by Department' );
if ( has_nav_menu( 'vertical-shop-menu' ) ):
	?>
    <div class="vertical-shop-menu <?php echo esc_attr( $current_state ); ?>">
        <ul class="main-shop-nav">
            <li class="vertical-shop-menu-title-wrapper">
                <span class="vertical-shop-menu-title">
                    <?php penci_svg_menu_icon();?>
                    <?php esc_html_e( $menu_title ); ?>
                </span>
				<?php
				wp_nav_menu( array(
					'container'       => 'div',
					'container_class' => 'penci-vertical-shop-menu',
					'theme_location'  => 'vertical-shop-menu',
					'menu_class'      => 'menu-shop',
					//'fallback_cb'     => 'penci_menu_fallback',
					'walker'          => new penci_menu_walker_nav_menu()
				) );
				?>
            </li>
        </ul>
    </div>
<?php
endif;
