<?php
/**
 * Button set param.
 *
 * @package Soledad
 */

if ( ! function_exists( 'penci_get_button_set_param' ) ) {
	/**
	 * Button set param.
	 *
	 * @param array  $settings Settings.
	 * @param string $value    Value.
	 *
	 * @return string
	 */
	function penci_get_button_set_param( $settings, $value ) {
		if ( ! $value && isset( $settings['default'] ) ) {
			$current_value = $settings['default'];
		} else {
			$current_value = $value;
		}

		$wrapper_classes = '';

		if ( isset( $settings['tabs'] ) ) {
			$current_value    = array_values( $settings['value'] )[0];
			$wrapper_classes .= ' penci-tabs';
		}

		ob_start();
		?>
		<div class="penci-select-fields penci-style-buttons penci-vc-button-set<?php echo esc_attr( $wrapper_classes ); ?>">
			<input type="hidden" class="penci-vc-button-set-value wpb_vc_param_value" name="<?php echo esc_attr( $settings['param_name'] ); ?>" value="<?php echo esc_attr( $current_value ); ?>">

			<ul class="penci-select-buttons penci-vc-button-set-list">
				<?php foreach ( $settings['value'] as $title => $value ) : ?>
					<?php
					$classes = '';

					if ( $current_value === $value ) {
						$classes .= ' penci-active';
					}
					?>

					<li class="penci-buttons-item vc-button-set-item<?php echo esc_attr( $classes ); ?>" data-value="<?php echo esc_html( $value ); ?>">
						<span>
							<?php echo esc_html( $title ); ?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		return ob_get_clean();
	}
}


