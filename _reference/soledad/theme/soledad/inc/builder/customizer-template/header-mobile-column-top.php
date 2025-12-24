<?php
$setting_align = "penci_hb_align_mobile_{$row}_{$column}";
$default_align = penci_get_builder_mod( $setting_align, $column );

$setting_element = "penci_hb_element_mobile_{$row}_{$column}";
$default_element = penci_get_builder_mod( $setting_element );
$default_element = explode( ',', $default_element );
?>
<div class="header-builder-<?php echo esc_html( $column ); ?> header-builder-column <?php echo esc_html( $default_align ); ?>"
     data-column="<?php echo esc_html( $column ); ?>">
    <div class="header-builder-drop-zone">
		<?php
		$elements = \HeaderBuilder::mobile_header_element();
		if ( is_array( $default_element ) ) {
			foreach ( $default_element as $element ) {
				if ( ! empty( $element ) && isset( $elements[ $element ] ) ) {
					$template->render( 'header-element', array(
						'key'   => $element,
						'value' => $elements[ $element ]
					), true );
				}
			}
		}
		?>
    </div>
</div>
