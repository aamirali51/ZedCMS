<?php
/**
 * Slider.
 *
 * @package Slider
 */

if ( ! function_exists( 'penci_get_slider_param' ) ) {
	/**
	 * penci slider param.
	 *
	 * @param array $settings Settings.
	 * @param string $value Value.
	 *
	 * @return string
	 */
	function penci_get_slider_param( $settings, $value ) {
		$value = $value ? $value : ( isset( $settings['default'] ) ? $settings['default'] : '' );

		ob_start();

		$param_name        = $settings['param_name'];
		$css_args          = isset( $settings['css_args'] ) ? wp_json_encode( $settings['css_args'] ) : '';
		$css_params        = isset( $settings['css_params'] ) ? wp_json_encode( $settings['css_params'] ) : '';
		$settings['units'] = isset( $settings['units'] ) ? $settings['units'] : 'px';
		?>
        <div class="penci-vc-slider">
            <div class="pc-slider-field"></div>

            <input type="hidden" class="pc-slider-field-value wpb_vc_param_value"
                   name="<?php echo esc_attr( $param_name ); ?>" id="<?php echo esc_attr( $param_name ); ?>"
                   value="<?php echo esc_attr( $value ); ?>"
                   data-min="<?php echo esc_attr( isset( $settings['min'] ) && $settings['min'] ? $settings['min'] : 0 ); ?>"
                   data-max="<?php echo esc_attr( isset( $settings['max'] ) && $settings['max'] ? $settings['max'] : 2000 ); ?>"
                   data-step="<?php echo esc_attr( isset( $settings['step'] ) && $settings['step'] ? $settings['step'] : 1 ); ?>"
                   data-css_params="<?php echo esc_attr( $css_params ); ?>"
                   data-css_args="<?php echo esc_attr( $css_args ); ?>">

            <span class="pc-slider-field-value-display">
				<span class="pc-slider-value-preview"></span>
			</span>

            <span class="pc-slider-units">
				<span class="pc-slider-unit-control pc-active"><?php echo esc_attr( $settings['units'] ); ?></span>
			</span>
        </div>
		<?php
		return ob_get_clean();
	}
}
