<?php
$header_data    = $args['header_data'];
$classes        = '';
$middle_content = penci_builder_validate_mod( $header_data, 'penci_header_bottombar_middle_column', 'enable' );
$content_width  = penci_builder_validate_mod( $header_data, 'penci_header_bottombar_content_width', 'container' );
$transparent    = penci_builder_validate_mod( $header_data, 'penci_header_bottombar_transparent_background_color' );
$classes        .= 'enable' == $transparent ? 'bg-transparent' : 'bg-normal';
$classes        .= 'enable' == $middle_content ? ' pcmiddle-center' : ' pcmiddle-normal';
$classes        .= penci_can_render_header( 'desktop', 'bottom' ) ? ' pc-hasel' : ' pc-noel';
?>
<div class="penci_bottombar penci-desktop-bottombar penci_navbar penci_container <?php echo $classes; ?>">
    <div class="container <?php esc_attr_e( $content_width ); ?>">
        <div class="penci_nav_row">
			<?php
			$columns = array( 'left', 'center', 'right' );

			foreach ( $columns as $column ) {
				$setting_align = "penci_hb_align_desktop_bottom_{$column}";
				$align         = penci_builder_validate_mod( $header_data, $setting_align, $column );
				
				$setting_flex = "penci_hb_flex_desktop_bottom_{$column}";
				$flex         = penci_builder_validate_mod( $header_data, $setting_flex, $column );


				$setting_element = "penci_hb_element_desktop_bottom_{$column}";
				$elements        = penci_builder_validate_mod( $header_data, $setting_element, penci_header_default( "desktop_element_bottom_{$column}" ) );
				$elements        = $elements ? explode( ',', $elements ) : '';
				?>

                <div class="penci_nav_col penci_nav_<?php echo esc_attr( $column ); ?> penci_nav_flex<?php echo esc_attr( $flex ); ?> penci_nav_align<?php echo esc_attr( $align ); ?>">

					<?php
					if ( ! empty( $elements ) && is_array( $elements ) ) {
						foreach ( $elements as $element ) {
							if ( ! empty( $element ) && file_exists( PENCI_BUILDER_PATH . 'elements/' . $element . '/front-end.php' ) ) {
								load_template( PENCI_BUILDER_PATH . 'elements/' . $element . '/front-end.php', false, [ 'class_type'  => 'normal-header',
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
