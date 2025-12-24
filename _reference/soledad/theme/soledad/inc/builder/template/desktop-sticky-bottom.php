<?php
$classes        = '';
$header_data    = $args['header_data'];
$middle_content = penci_builder_validate_mod( $header_data, 'penci_header_sticky_bottom_middle_column', 'enable' );
$classes        .= 'enable' == $middle_content ? 'pcmiddle-center' : 'pcmiddle-normal';
$classes        .= penci_can_render_header( 'desktop_sticky', 'bottom' ) ? ' pc-hasel' : ' pc-noel';
$content_width  = penci_builder_validate_mod( $header_data, 'penci_header_sticky_bottom_content_width', 'container' );
?>
<div class="penci-desktop-sticky-bottom penci_container penci-sticky-bottom <?php echo esc_attr( $classes ); ?>">
    <div class="container <?php echo esc_attr( $content_width ); ?>">
        <div class="penci_nav_row">
			<?php
			$columns = array( 'left', 'center', 'right' );

			foreach ( $columns as $column ) {
				$setting_align = "penci_hb_align_desktop_sticky_bottom_{$column}";
				$align         = penci_builder_validate_mod( $header_data, $setting_align, $column );


				$setting_element = "penci_hb_element_desktop_sticky_bottom_{$column}";
				$elements        = penci_builder_validate_mod( $header_data, $setting_element, penci_header_default( "sticky_element_bottom_{$column}" ) );
				$elements        = $elements ? explode( ',', $elements ) : '';
				?>

                <div class="penci_nav_col penci_nav_<?php echo esc_attr( $column ); ?> penci_nav_align<?php echo esc_attr( $align ); ?>">

					<?php
					if ( ! empty( $elements ) && is_array( $elements ) ) {
						foreach ( $elements as $element ) {
							if ( ! empty( $element ) && file_exists( PENCI_BUILDER_PATH . 'elements/' . $element . '/front-end.php' ) ) {
								load_template( PENCI_BUILDER_PATH . 'elements/' . $element . '/front-end.php', false, [ 'class_type'  => 'sticky-header',
								                                                                                        'header_data' => $header_data
								] );
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
