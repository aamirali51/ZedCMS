<?php
$header_data    = $args['header_data'];
$sticky_layout              = penci_builder_validate_mod( $header_data, 'penci_header_desktop_sticky_wrap_all' );
$sticky_layout_custom_width = penci_builder_validate_mod( $header_data, 'penci_header_desktop_sticky_wrap_width' );

$sticky_enable = false;
$sticky_row    = [ 'top', 'mid', 'bottom' ];
$classess      = [];
foreach ( $sticky_row as $row ) {
	if ( penci_can_render_header( 'desktop_sticky', $row ) ) {
		$sticky_enable = true;
	}
}
if ( 'enable' == penci_builder_validate_mod( $header_data, 'penci_header_desktop_sticky_shadow' ) ) {
	$classess[] = 'shadow-enable';
}
if ( 'enable' == penci_builder_validate_mod( $header_data, 'penci_header_desktop_sticky_hide_down' ) ) {
	$classess[] = 'hide-scroll-down';
}
if ( 'enable' == $sticky_layout ) {
	$classess[] = 'container pchb-boxed-layout';
}
if ( 'enable' == $sticky_layout && $sticky_layout_custom_width ) {
	$classess[] = $sticky_layout_custom_width;
}
if ( $sticky_enable || is_customize_preview() ):
	?>
    <div class="penci_header penci-header-builder penci_builder_sticky_header_desktop <?php echo implode( ' ', $classess ); ?>">
        <div class="penci_container">
            <div class="penci_stickybar penci_navbar">
				<?php
				foreach ( $sticky_row as $row ) {
					if ( penci_can_render_header( 'desktop_sticky', $row ) || is_customize_preview() ) {
						load_template( PENCI_BUILDER_PATH . 'template/desktop-sticky-' . $row . '.php', false, [ 'header_data' => $header_data] );
					}
				}
				?>
            </div>
        </div>
    </div>
<?php endif;
