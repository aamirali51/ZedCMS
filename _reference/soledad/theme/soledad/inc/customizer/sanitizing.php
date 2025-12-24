<?php
/**
 * Callback function for sanitizing radio settings.
 * Use for PenciDesign
 *
 * @param $input , $setting
 *
 * @return $input
 */
if ( ! function_exists( 'penci_sanitize_choices_field' ) ) {
	function penci_sanitize_choices_field( $input ) {
		return $input;
	}
}

/**
 * Callback function for sanitizing textarea settings
 * Use for PenciDesign
 *
 * @param $input , $setting
 *
 * @return $input
 */
if ( ! function_exists( 'penci_sanitize_textarea_field' ) ) {
	function penci_sanitize_textarea_field( $input ) {
		return $input;
	}
}
/**
 * Callback function for sanitizing checkbox settings.
 * Use for PenciDesign
 *
 * @param $input
 *
 * @return string default value if type is not exists
 */
if ( ! function_exists( 'penci_sanitize_checkbox_field' ) ) {
	function penci_sanitize_checkbox_field( $input ) {
		if ( $input == 1 ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Callback function for sanitizing switch settings.
 * Use for PenciDesign
 *
 * @param $input
 *
 * @return string default value if type is not exists
 */
if ( ! function_exists( 'penci_switch_sanitization' ) ) {
	function penci_switch_sanitization( $input ) {
		if ( true === $input ) {
			return 1;
		} else {
			return 0;
		}
	}
}

/**
 * Callback function for sanitizing checkbox settings.
 * Use for PenciDesign
 *
 * @param $input
 *
 * @return a number
 */
if ( ! function_exists( 'penci_sanitize_number_field' ) ) {
	function penci_sanitize_number_field( $input ) {
		return absint( $input );
	}
}


/**
 * Sanitize integers that can use decimals
 *
 * @return a decimal
 */
if ( ! function_exists( 'penci_sanitize_decimal_field' ) ) {
	function penci_sanitize_decimal_field( $input ) {
		return abs( floatval( $input ) );
	}
}

/**
 * Sanitize integers that can use decimals
 *
 * @return empty if input is empty or a decimal
 */
if ( ! function_exists( 'penci_sanitize_decimal_empty_field' ) ) {
	function penci_sanitize_decimal_empty_field( $input ) {
		if ( '' == $input ) {
			return '';
		}

		return abs( floatval( $input ) );
	}
}

/**
 * Sanitize integers that can use decimals
 *
 * @return empty if input is empty or a decimal
 */
if ( ! function_exists( 'penci_sanitize_multiple_checkbox' ) ) {
	function penci_sanitize_multiple_checkbox( $values ) {

		$multi_values = ! is_array( $values ) ? explode( ',', $values ) : $values;

		return ! empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
	}
}

/**
 * Output CSS for desktop & mobile devices based on selector & option & attr
 *
 * @return string
 */
if ( ! function_exists( 'penci_renders_css' ) ) {
	function penci_renders_css( $selector, $option, $attr = null ) {
		if ( '' == $selector || '' == $option ) {
			return '';
		}
		$return        = '';
		$attr          = is_null( $attr ) ? 'font-size' : $attr;
		$sub_fix       = ( 'font-size' == $attr ) ? 'px' : '';
		$mobile_option = $option . '_mobile';

		if ( get_theme_mod( $option ) ) {
			$return = $selector . '{' . $attr . ':' . get_theme_mod( $option ) . $sub_fix . ';}';
		}
		if ( get_theme_mod( $mobile_option ) ) {
			$return .= '@media only screen and (max-width: 479px){' . $selector . '{' . $attr . ':' . get_theme_mod( $mobile_option ) . $sub_fix . ';}}';
		}

		return $return;

	}
}

/**
 * Call back to showing generate css button when render Separate CSS File is selected
 *
 * @since 1.11.0
 */
if ( ! function_exists( 'penci_activate_separate_css_file_callback' ) ) {
	function penci_activate_separate_css_file_callback() {

		if ( 'separate_file' === get_theme_mod( 'penci_spcss_render' ) ) {
			return true;
		}

		return false;
	}
}

/**
 * Only allow values between a certain minimum & maxmium range
 *
 * @param number    Input to be sanitized
 *
 * @return number    Sanitized input
 */
if ( ! function_exists( 'penci_in_range' ) ) {
	function penci_in_range( $input, $min, $max ) {
		if ( $input < $min ) {
			$input = $min;
		}
		if ( $input > $max ) {
			$input = $max;
		}

		return $input;
	}
}

/**
 * Alpha Color (Hex, RGB & RGBa) sanitization
 *
 * @param string    Input to be sanitized
 *
 * @return string    Sanitized input
 */
if ( ! function_exists( 'penci_hex_rgba_sanitization' ) ) {
	function penci_hex_rgba_sanitization( $input, $setting ) {
		if ( empty( $input ) || is_array( $input ) ) {
			return $setting->default;
		}

		if ( false === strpos( $input, 'rgb' ) ) {
			// If string doesn't start with 'rgb' then santize as hex color
			$input = sanitize_hex_color( $input );
		} else {
			if ( false === strpos( $input, 'rgba' ) ) {
				// Sanitize as RGB color
				$input = str_replace( ' ', '', $input );
				sscanf( $input, 'rgb(%d,%d,%d)', $red, $green, $blue );
				$input = 'rgb(' . penci_in_range( $red, 0, 255 ) . ',' . penci_in_range( $green, 0, 255 ) . ',' . penci_in_range( $blue, 0, 255 ) . ')';
			} else {
				// Sanitize as RGBa color
				$input = str_replace( ' ', '', $input );
				sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
				$input = 'rgba(' . penci_in_range( $red, 0, 255 ) . ',' . penci_in_range( $green, 0, 255 ) . ',' . penci_in_range( $blue, 0, 255 ) . ',' . penci_in_range( $alpha, 0, 1 ) . ')';
			}
		}

		return $input;
	}
}

if ( ! function_exists( 'penci_text_sanitization' ) ) {
	function penci_text_sanitization( $input ) {
		if ( ! is_array( $input ) && strpos( $input, ',' ) !== false ) {
			$input = explode( ',', $input );
		}
		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$input[ $key ] = sanitize_text_field( $value );
			}
			$input = implode( ',', $input );
		} else {
			$input = sanitize_text_field( $input );
		}

		return $input;
	}
}

if ( ! function_exists( 'penci_spacing_sanitization' ) ) {
	function penci_spacing_sanitization( $input ) {
		if ( is_array( $input ) ) {
			$return = [];
			foreach ( $input as $key => $value ) {
				$return[ $key ] = absint( $value );
			}
			$return = implode( ',', $return );
		} else {
			$return = absint( $input );
		}

		return $return;
	}
}

if ( ! function_exists( 'penci_custom_sanitization' ) ) {
	/**
	 * Text sanitization function for Customize API
	 *
	 * @param string $input Input to be sanitized.
	 *
	 * @return string        Sanitized input.
	 * @since 1.0.0
	 */
	function penci_custom_sanitization( $input ) {
		return wp_kses_post( force_balance_tags( $input ) );
	}
}
