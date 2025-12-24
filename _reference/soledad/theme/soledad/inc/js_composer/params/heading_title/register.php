<?php
/**
 * Callback for new param 'penci_heading_title'.
 *
 * @param array $settings
 * @param string $value
 *
 * @return string
 */
function penci_vc_param_heading_title( $settings, $value ) {
	$heading     = isset( $settings['heading'] ) ? esc_attr( $settings['heading'] ) : '';
	$description = isset( $settings['description'] ) ? esc_attr( $settings['description'] ) : '';
	$type        = isset( $settings['type'] ) ? esc_attr( $settings['type'] ) : '';


	$out = '';

	$out .= '<div class="penci-vc-heading-title ' . esc_attr( $type ) . '">';

	if ( $heading ) {
		$out .= '<h4 class="heading">' . esc_attr( $heading ) . '</h4>';
	}

	if ( $description ) {
		$out .= '<span class="description">' . esc_attr( $description ) . '</span>';
	}

	$out .= '</div>';

	return $out;
}

