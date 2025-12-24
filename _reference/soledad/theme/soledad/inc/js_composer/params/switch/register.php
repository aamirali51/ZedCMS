<?php
/**
 * This file creates html for the penci_switch field in WPBakery.
 *
 * @package Soledad.
 */

/**
 * penci switch param
 */
if ( ! function_exists( 'penci_get_switch_param' ) ) {
	/**
	 * This function creates html for the penci_switch field in WPBakery.
	 *
	 * @param array $settings .
	 * @param array $value .
	 *
	 * @return string
	 */
	function penci_get_switch_param( $settings, $value ) {
		$default = isset( $settings['std'] ) && $settings['std'] ? $settings['std'] : ( isset( $settings['default'] ) && $settings['default'] ? $settings['default'] : 'no' );
		if ( '0' === $value ) {
			$value = 0;
		} elseif ( empty( $value ) ) {
			$value = $default;
		}

		$settings['true_state']  = isset( $settings['true_state'] ) ? $settings['true_state'] : 'yes';
		$settings['false_state'] = isset( $settings['false_state'] ) ? $settings['false_state'] : 'no';

		$settings['true_text']  = isset( $settings['true_text'] ) ? $settings['true_text'] : esc_html__( 'yes', 'soledad' );
		$settings['false_text'] = isset( $settings['false_text'] ) ? $settings['false_text'] : esc_html__( 'no', 'soledad' );

		ob_start();
		?>
        <div class="penci-vc-switch">
            <input type="hidden" class="switch-field-value wpb_vc_param_value"
                   name="<?php echo esc_attr( $settings['param_name'] ); ?>" value="<?php echo esc_attr( $value ); ?>">
            <div class="penci-vc-switch-inner">
                <div class="switch-controls switch-active"
                     data-value="<?php echo esc_attr( $settings['true_state'] ); ?>">
						<span>
							<?php echo esc_html( $settings['true_text'] ); ?>
						</span>
                </div>
                <div class="switch-controls switch-inactive"
                     data-value="<?php echo esc_attr( $settings['false_state'] ); ?>">
						<span>
							<?php echo esc_html( $settings['false_text'] ); ?>
						</span>
                </div>
            </div>
        </div>
		<?php

		return ob_get_clean();
	}
}
