<?php
$header_data = $args['header_data'];
?>
<div class="nav_wrap penci-mobile-sidebar-content-wrapper">
    <div class="penci-builder-item-wrap item_main">
		<?php
		$elements = penci_builder_validate_mod( $header_data, 'penci_hb_element_mobile_drawer_top_center' );
		$elements = explode( ',', $elements );
		foreach ( $elements as $element ) {
			if ( ! empty( $element ) && file_exists( PENCI_BUILDER_PATH . 'elements/' . $element . '/front-end.php' ) ) {
				load_template( PENCI_BUILDER_PATH . 'elements/' . $element . '/front-end.php', false, [ 'header_data' => $header_data ] );
			}
		}
		?>
    </div>
</div>
