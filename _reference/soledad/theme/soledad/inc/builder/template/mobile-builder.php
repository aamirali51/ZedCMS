<?php
$class       = [];
$header_data = $args['header_data'];
if ( 'enable' == penci_builder_validate_mod( $header_data, 'penci_header_mobile_sticky_shadow' ) ) {
	$class[] = 'shadow-enable';
}
if ( 'enable' == penci_builder_validate_mod( $header_data, 'penci_header_mobile_sticky_hide_down' ) ) {
	$class[] = 'hide-scroll-down';
}
if ( 'enable' == penci_builder_validate_mod( $header_data, 'penci_header_mobile_overlap_setting' ) ) {
	$class[] = 'mobile-overlap';
}
?>
<div class="penci_navbar_mobile <?php echo implode( ' ', $class ); ?>">
	<?php
	$rows = array( 'top', 'mid', 'bottom' );
	foreach ( $rows as $row ) {
		if ( penci_can_render_header( 'mobile', $row ) || is_customize_preview() ) {
			load_template( PENCI_BUILDER_PATH . 'template/mobile-' . $row . '.php', true, [ 'header_data' => $header_data ] );
		}
	}
	?>
</div>
