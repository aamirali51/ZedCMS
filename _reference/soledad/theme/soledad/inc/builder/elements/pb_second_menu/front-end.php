<?php 
$header_data    = $args['header_data'];
$menu_id = penci_builder_validate_mod( $header_data, 'penci_header_pb_second_menu_name' );
if ( is_page() ) {
	$pmeta_page_header = get_post_meta( get_the_ID(), 'penci_pmeta_page_header', true );
	if ( isset( $pmeta_page_header['main_nav_menu'] ) && $pmeta_page_header['main_nav_menu'] ) {
		$menu_id = $pmeta_page_header['main_nav_menu'];
	}
}
$classes = [];

$classes[] = 'navigation';
$classes[] = get_theme_mod( 'penci_header_menu_style','menu-style-1' );
$classes[] = penci_builder_validate_mod( $header_data, 'penci_header_pb_second_menu_class', 'no-class' );
$classes[] = 'enable' == penci_builder_validate_mod( $header_data, 'penci_header_pb_second_menu_penci_header_enable_padding' ) ? 'menu-item-padding' : 'menu-item-normal';
$classes[] = 'enable' == penci_builder_validate_mod( $header_data, 'penci_header_pb_second_menu_penci_header_remove_line_hover' ) ? 'pcremove-lineh' : '';

if ( $menu_id || is_customize_preview() ) {
	?>
    <div class="pc-builder-element pc-builder-menu pc-second-menu">
        <nav class="<?php echo implode( ' ', $classes ); ?>" role="navigation"
		     <?php if ( ! penci_builder_validate_mod( $header_data, 'penci_schema_sitenav' ) ): ?>itemscope
             itemtype="https://schema.org/SiteNavigationElement"<?php endif; ?>>

			<?php
			if( $menu_id ){
				wp_nav_menu( array(
					'menu'        => $menu_id,
					'container'   => false,
					'menu_class'  => 'menu',
					'fallback_cb' => 'penci_menu_fallback',
					'walker'      => new penci_menu_walker_nav_menu()
				) );
			}
			?>
        </nav>
    </div>
	<?php
}


