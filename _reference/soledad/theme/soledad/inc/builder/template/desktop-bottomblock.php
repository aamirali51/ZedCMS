<?php
$classes = '';
$header_data    = $args['header_data'];
$content_width = penci_builder_validate_mod( $header_data, 'penci_header_bottomblock_content_width', 'container-fullwidth' );
$transparent   = penci_builder_validate_mod( $header_data, 'penci_header_bottomblock_transparent_background_color' );
$transparent   .= 'enable' == $transparent ? 'bg-transparent' : 'bg-normal';
$classes       .= penci_can_render_header( 'desktop', 'bottomblock' ) ? ' pc-hasel' : ' pc-noel';
?>
<div class="penci_bottomblock penci-desktop-bottomblock penci_navbar pcmiddle-normal penci_container <?php echo esc_attr( $classes ); ?>">
    <div class="container <?php esc_attr_e( $content_width ); ?>">
        <div class="penci_nav_row">
			<?php
			$columns = [ 'center' ];

			foreach ( $columns as $column ) {
				$content_direction = penci_builder_validate_mod( $header_data, 'penci_header_bottomblock_content_direction', 'row' );
				$content_direction = $content_direction ? $content_direction : 'row';

				$setting_align = "penci_hb_align_desktop_bottomblock_{$column}";
				$align         = penci_builder_validate_mod( $header_data, $setting_align, $column );

				$setting_element = "penci_hb_element_desktop_bottomblock_{$column}";
				$elements        = penci_builder_validate_mod( $header_data, $setting_element, penci_header_default( "desktop_element_bottomblock_{$column}" ) );
				$elements        = $elements ? explode( ',', $elements ) : '';
				?>

                <div class="penci_nav_col penci_nav_<?php echo esc_attr( $column ); ?> penci_content_<?php echo esc_attr( $content_direction ); ?> penci_nav_align<?php echo esc_attr( $align ); ?>">

						<?php
						if ( ! empty( $elements ) && is_array( $elements ) ) {
							foreach ( $elements as $element ) {
								if ( ! empty( $element ) && file_exists( PENCI_BUILDER_PATH . 'elements/' . $element . '/front-end.php' ) ) {
									load_template( PENCI_BUILDER_PATH . 'elements/' . $element . '/front-end.php', false, [ 'class_type' => 'normal-header', 'header_data' => $header_data ] );
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
