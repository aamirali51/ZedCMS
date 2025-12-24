<?php
/**
 * Callback for new param 'penci_only_number'.
 *
 * @param array $settings
 * @param string $value
 *
 * @return string
 */
function penci_vc_param_only_number( $settings, $value ) {
	ob_start();

	$suffix = isset( $settings['suffix'] ) ? esc_attr( $settings['suffix'] ) : '';

	$settings_max    = isset( $settings['max'] ) ? $settings['max'] : '';
	$settings['min'] = isset( $settings['min'] ) ? $settings['min'] : '';
	?>
    <input name="<?php echo esc_attr( $settings['param_name'] ); ?>"
           class="wpb_vc_param_value penci-only-number-input wpb-textinput <?php echo esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ); ?>_field"
           type="number" min="<?php echo esc_attr( $settings['min'] ); ?>"
           max="<?php echo esc_attr( $settings_max ); ?>"
           value="<?php echo esc_attr( $value ); ?>" /><?php echo $suffix; ?>
	<?php
	return ob_get_clean();
}

