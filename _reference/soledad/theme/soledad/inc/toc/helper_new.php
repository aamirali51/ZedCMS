<?php
if ( ! function_exists( 'penci_toc_wp_strip_all_tags' ) ) {
	function penci_toc_wp_strip_all_tags( $text, $remove_breaks = false ) {

		if ( is_null( $text ) ) {
			return '';
		}

		if ( ! is_scalar( $text ) ) {
			/*
			 * To maintain consistency with pre-PHP 8 error levels,
			 * wp_trigger_error() is used to trigger an E_USER_WARNING,
			 * rather than _doing_it_wrong(), which triggers an E_USER_NOTICE.
			 */
			wp_trigger_error(
				'',
				sprintf(
				/* translators: 1: The function name, 2: The argument number, 3: The argument name, 4: The expected type, 5: The provided type. */
					__( 'Warning: %1$s expects parameter %2$s (%3$s) to be a %4$s, %5$s given.' ),
					__FUNCTION__,
					'#1',
					'$text',
					'string',
					gettype( $text )
				),
				E_USER_WARNING
			);

			return '';
		}

		$text = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $text );
		$text = strip_tags( $text, apply_filters( 'penci_toc_allowable_tags', '' ) );

		if ( $remove_breaks ) {
			$text = preg_replace( '/[\r\n\t ]+/', ' ', $text );
		}

		return trim( $text );
	}
}
if ( ! function_exists( 'insertElementByImgTag' ) ) {
	function insertElementByImgTag( $content, $toc ) {
		$find    = array( '</figure>' );
		$replace = array( '</figure>' . $toc );

		return mb_find_replace( $find, $replace, $content );
	}
}
if ( ! function_exists( 'insertElementByPTag' ) ) {
	function insertElementByPTag( $content, $toc ) {
		$find    = array( '</p>' );
		$replace = array( '</p>' . $toc );

		return mb_find_replace( $find, $replace, $content );
	}
}
if ( ! function_exists( 'penci_toc_auto_device_target_status' ) ) {
	function penci_toc_auto_device_target_status() {
		$status = true;
		if ( get_theme_mod( 'penci_toc_device_target' ) == 'mobile' ) {
			if ( function_exists( 'wp_is_mobile' ) && wp_is_mobile() ) {
				$status = true;
			} else {
				$status = false;
			}
		}
		if ( get_theme_mod( 'penci_toc_device_target' ) == 'desktop' ) {
			if ( function_exists( 'wp_is_mobile' ) && wp_is_mobile() ) {
				$status = false;
			} else {
				$status = true;
			}
		}

		return $status;
	}
}
if ( ! function_exists( 'penci_toc_para_blockquote_replace' ) ) {
	function penci_toc_para_blockquote_replace( $blockquotes, $content, $step ) {
		$bId = 0;
		if ( $step == 1 ) {
			foreach ( $blockquotes[0] as $blockquote ) {
				$replace = '#pencitocbq' . $bId . '#';
				$content = str_replace( trim( $blockquote ), $replace, $content );
				$bId ++;
			}
		} elseif ( $step == 2 ) {
			foreach ( $blockquotes[0] as $blockquote ) {
				$search  = '#pencitocbq' . $bId . '#';
				$content = str_replace( $search, trim( $blockquote ), $content );
				$bId ++;
			}
		}

		return $content;
	}
}
if ( ! function_exists( 'mb_find_replace' ) ) {
	function mb_find_replace( &$find = false, &$replace = false, &$string = '' ) {

		if ( is_array( $find ) && is_array( $replace ) && $string ) {

			// check if multibyte strings are supported
			if ( function_exists( 'mb_strpos' ) ) {


				for ( $i = 0; $i < count( $find ); $i ++ ) {

					$needle = $find[ $i ];
					$start  = mb_strpos( $string, $needle );

					// If heading can not be found, let try decoding entities to see if it can be found.
					if ( false === $start ) {

						$needle = html_entity_decode(
							$needle,
							ENT_QUOTES,
							get_option( 'blog_charset' )
						);

						$umlauts = false;
						$umlauts = apply_filters( 'pencitoc_modify_umlauts', $umlauts );
						if ( $umlauts ) {
							$string = html_entity_decode(
								$string,
								ENT_QUOTES,
								get_option( 'blog_charset' )
							);
						}

						$needle = str_replace( array( '’', '“', '”' ), array( '\'', '"', '"' ), $needle );

						$start = mb_strpos( $string, $needle );
					}

					/*
					 * `mb_strpos()` can return `false`. Only process `mb_substr_replace()` if position in string is found.
					 */
					if ( is_int( $start ) ) {

						$length             = mb_strlen( $needle );
						$apply_new_function = apply_filters( 'pencitoc_mb_subtr_replace', false, $string, $replace[ $i ], $start, $length );
						$string             = $apply_new_function ? $apply_new_function : mb_substr_replace( $string, $replace[ $i ], $start, $length );
					}

				}

			} else {

				for ( $i = 0; $i < count( $find ); $i ++ ) {

					$start  = strpos( $string, $find[ $i ] );
					$length = strlen( $find[ $i ] );

					/*
					 * `strpos()` can return `false`. Only process `substr_replace()` if position in string is found.
					 */
					if ( is_int( $start ) ) {

						$string = substr_replace( $string, $replace[ $i ], $start, $length );
					}
				}
			}
		}

		return $string;
	}
}
if ( ! function_exists( 'mb_substr_replace' ) ) {
	function mb_substr_replace( $string, $replacement, $start, $length = null ) {

		if ( is_array( $string ) ) {

			$num = count( $string );

			// $replacement
			$replacement = is_array( $replacement ) ? array_slice( $replacement, 0, $num ) : array_pad( array( $replacement ), $num, $replacement );

			// $start
			if ( is_array( $start ) ) {
				$start = array_slice( $start, 0, $num );
				foreach ( $start as $key => $value ) {
					$start[ $key ] = is_int( $value ) ? $value : 0;
				}
			} else {
				$start = array_pad( array( $start ), $num, $start );
			}

			// $length
			if ( ! isset( $length ) ) {
				$length = array_fill( 0, $num, 0 );
			} elseif ( is_array( $length ) ) {
				$length = array_slice( $length, 0, $num );
				foreach ( $length as $key => $value ) {
					$length[ $key ] = isset( $value ) ? ( is_int( $value ) ? $value : $num ) : 0;
				}
			} else {
				$length = array_pad( array( $length ), $num, $length );
			}

			// Recursive call
			return array_map( __FUNCTION__, $string, $replacement, $start, $length );
		}

		preg_match_all( '/./us', (string) $string, $smatches );
		preg_match_all( '/./us', (string) $replacement, $rmatches );

		if ( $length === null ) {

			$length = mb_strlen( $string );
		}

		array_splice( $smatches[0], $start, $length, $rmatches[0] );

		return join( $smatches[0] );
	}
}