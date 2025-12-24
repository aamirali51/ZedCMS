<?php
$classes        = '';
$header_data    = $args['header_data'];
$stick_class    = penci_builder_validate_mod( $header_data, 'penci_header_mobile_midbar_sticky_setting', '' );
$middle_content = penci_builder_validate_mod( $header_data, 'penci_header_mobile_midbar_middle_column', 'enable' );
$classes        .= 'enable' == $stick_class ? 'sticky-enable' : 'sticky-disable';
$classes        .= 'enable' == $middle_content ? ' pcmiddle-center' : ' pcmiddle-normal';
$classes        .= penci_can_render_header( 'mobile', 'mid' ) ? ' pc-hasel' : ' pc-noel';
$bgtrans        = penci_builder_validate_mod( $header_data, 'penci_header_mobile_midbar_background_trans', 'enable' );
$classes        .= 'enable' == $bgtrans ? ' bgtrans-enable' : ' bgnormal';
?>
<div class="penci_mobile_midbar penci-mobile-midbar penci_container <?php echo esc_attr( $classes ); ?>">
    <div class="container">
        <div class="penci_nav_row">
			<?php
			$columns = array( 'left', 'center', 'right' );

			foreach ( $columns as $column ) {
				$setting_align = "penci_hb_align_mobile_mid_{$column}";
				$align         = penci_builder_validate_mod( $header_data, $setting_align, $column );

				$setting_element = "penci_hb_element_mobile_mid_{$column}";
				$elements        = penci_builder_validate_mod( $header_data, $setting_element, penci_header_default( "mobile_element_mid_{$column}" ) );
				$elements        = $elements ? explode( ',', $elements ) : '';
				?>

                <div class="penci_nav_col penci_nav_<?php echo esc_attr( $column ); ?> penci_nav_align<?php echo esc_attr( $align ); ?>">

					<?php
					if ( ! empty( $elements ) ) {
						foreach ( $elements as $element ) {
							if ( ! empty( $element ) && file_exists( PENCI_BUILDER_PATH . 'elements/' . $element . '/front-end.php' ) ) {
								load_template( PENCI_BUILDER_PATH . 'elements/' . $element . '/front-end.php', false, [ 'header_data' => $header_data ] );
							}
						}
					}
					?>

                </div>

				<?php
			}
			?>
        </div>
    </div>
</div>
