<?php 
$header_data    = $args['header_data'];
$menu_id      = penci_builder_validate_mod( $header_data, 'penci_header_pb_dropdown_menu_name', '' );
$parent_click = penci_builder_validate_mod( $header_data, 'penci_header_pb_dropdown_menu_penci_vernav_click_parent' );
$classes      = [];
$classes[]    = penci_builder_validate_mod( $header_data, 'penci_header_pb_dropdown_menu_class', 'no-class' );
$classes[]    = 'enable' == $parent_click ? 'penci-vernav-cparent' : 'normal-click';
if ( penci_builder_validate_mod( $header_data, 'penci_header_pb_dropdown_menu_penci_vernav_click_parent' ) == 'enable' ) {
	$classes[] = 'pchb-cparent';
}
add_filter( 'theme_mod_penci_vernav_click_parent', function ( $value ) {
	if ( penci_builder_validate_mod( $header_data, 'penci_header_pb_dropdown_menu_penci_vernav_click_parent' ) == 'enable' ) {
		$value = true;
	}

	return $value;
} );
?>
    <div class="pc-builder-element pc-builder-menu pc-dropdown-menu">
        <nav class="<?php echo implode( ' ', $classes ); ?>" role="navigation"
		     <?php if ( ! penci_builder_validate_mod( $header_data, 'penci_schema_sitenav' ) ): ?>itemscope
             itemtype="https://schema.org/SiteNavigationElement"<?php endif; ?>>
			<?php
			$args = array(
				'container'   => false,
				'menu_class'  => 'menu menu-hgb-main',
				'fallback_cb' => 'penci_menu_fallback',
				'walker'      => new penci_menu_builder_walker_nav_menu()
			);
			if ( $menu_id ) {
				$args['menu'] = $menu_id;
			} else {
				$args['location'] = 'primary-menu';
			}
			wp_nav_menu( $args );
			?>
        </nav>
    </div>
<?php 


