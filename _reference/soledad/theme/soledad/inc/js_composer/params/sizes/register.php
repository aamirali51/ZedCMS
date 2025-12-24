<?php
if ( ! function_exists( 'penci_get_responsive_size_param' ) ) {
	function penci_get_responsive_size_param( $settings, $value ) {

		$settings['css_args'] = isset( $settings['css_args'] ) && $settings['css_args'] ? $settings['css_args'] : '';

		$output = '<div class="penci-rs-wrapper ' . esc_attr( $settings['param_name'] ) . '">';
		$output .= '<div class="penci-rs-item desktop">';
		$output .= '<span class="penci-rs-icon penci-css-tooltip" data-text="Desktop"><i class="dashicons dashicons-desktop"></i></span>';
		$output .= '<input type="number" min="1" class="penci-rs-input" data-id="desktop">';
		$output .= '</div>';

		$output .= '<div class="penci-rs-item tablet">';
		$output .= '<span class="penci-rs-icon penci-css-tooltip" data-text="Tablet"><i class="dashicons dashicons-tablet"></i></span>';
		$output .= '<input type="number" min="1" class="penci-rs-input" data-id="tablet">';
		$output .= '</div>';

		$output .= '<div class="penci-rs-item mobile">';
		$output .= '<span class="penci-rs-icon penci-css-tooltip" data-text="Mobile"><i class="dashicons dashicons-smartphone"></i></span>';
		$output .= '<input type="number" min="1" class="penci-rs-input" data-id="mobile">';
		$output .= '</div>';

		$output .= '<div class="penci-rs-unit">px</div>';

		$output .= '<input type="hidden" data-css_args="' . esc_attr( json_encode( $settings['css_args'] ) ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value penci-rs-value" value="' . esc_attr( $value ) . '">';
		$output .= '</div>';

		return $output;
	}

}
