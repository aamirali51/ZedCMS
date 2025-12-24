<?php

/**
 * Register Fonts
 *
 * @since 4.0
 */
if ( ! function_exists( 'penci_fonts_url' ) ) {
	function penci_fonts_url( $data = 'normal' ) {
		$font_url = '';

		$array_fonts       = $array_fonts_data = array();
		$array_get         = array();
		$array_options     = array();
		$array_earlyaccess = array();
		$exlude_fonts      = array_merge( penci_get_custom_fonts(), penci_font_browser() );
		$exlude_fonts      = apply_filters( 'penci_exlude_fonts_css', $exlude_fonts );


		if ( ! get_theme_mod( 'penci_font_for_title' ) ) {
			$array_fonts = array_merge( $array_fonts, array( 'Raleway' ) );
		} else {
			$array_options[] = get_theme_mod( 'penci_font_for_title' );
		}
		if ( get_theme_mod( 'penci_font_for_body' ) ) {
			$array_options[] = get_theme_mod( 'penci_font_for_body' );
		} else {
			$array_fonts = array_merge( $array_fonts, array( 'PT Serif' ) );
		}
		if ( get_theme_mod( 'penci_font_for_slogan' ) ) {
			$array_options[] = get_theme_mod( 'penci_font_for_slogan' );
		}
		if ( get_theme_mod( 'penci_font_for_menu' ) ) {
			$array_options[] = get_theme_mod( 'penci_font_for_menu' );
		}

		$array_options = array_diff( $array_options, $exlude_fonts );

		if ( ! empty( $array_options ) ) {

			foreach ( $array_options as $font ) {
				if ( strpos( $font, '-apple-system' ) !== false ) {
					continue;
				}
				$font_family       = str_replace( '"', '', $font );
				$font_family_explo = explode( ', ', $font_family );
				$array_get[]       = isset( $font_family_explo[0] ) ? $font_family_explo[0] : '';
			}

			$array_end        = array_unique( array_merge( $array_fonts, $array_get ), SORT_REGULAR );
			$array_fonts_data = $array_end;

			if ( ! empty( $array_end ) ) {
				$string_end = implode( ':300,300italic,400,400italic,500,500italic,700,700italic,800,800italic|', $array_end );

				if ( $string_end == '-apple-system' ) {
					return;
				}

				/*
					Translators: If there are characters in your language that are not supported
					by chosen font(s), translate this to 'off'. Do not translate into your own language.
				*/
				if ( 'off' !== _x( 'on', 'Google font: on or off', 'soledad' ) ) {
					$font_url = add_query_arg(
						array(
							'family'  => urlencode( $string_end . ':300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic&subset=latin,cyrillic,cyrillic-ext,greek,greek-ext,latin-ext' ),
							'display' => 'swap',
						),
						'https://fonts.googleapis.com/css'
					);
				}
			}
		}

		if ( $data == 'normalarray' ) {
			return $array_fonts_data;
		} elseif ( $data == 'earlyaccess' ) {
			return $array_earlyaccess;
		} else {
			return $font_url;
		}
	}
}

if ( ! function_exists( 'penci_mobile_fonts_url' ) ) {
	function penci_mobile_fonts_url( $data = 'normal' ) {

		if ( ! get_theme_mod( 'penci_font_for_title_mobile' ) && ! get_theme_mod( 'penci_font_for_body_mobile' ) && ! get_theme_mod( 'penci_font_for_menu_mobile' ) ) {
			return '';
		}
		$font_url = '';

		$array_fonts       = $array_fonts_data = array();
		$array_get         = array();
		$array_options     = array();
		$array_earlyaccess = array();
		$exlude_fonts      = array_merge( penci_get_custom_fonts(), penci_font_browser() );
		$exlude_fonts      = apply_filters( 'penci_exlude_fonts_css', $exlude_fonts );

		if ( ! get_theme_mod( 'penci_font_for_title_mobile' ) ) {
			$array_fonts = array_merge( $array_fonts, array( 'Raleway' ) );
		} else {
			$array_options[] = get_theme_mod( 'penci_font_for_title_mobile' );
		}
		if ( get_theme_mod( 'penci_font_for_body_mobile' ) ) {
			$array_options[] = get_theme_mod( 'penci_font_for_body_mobile' );
		} else {
			$array_fonts = array_merge( $array_fonts, array( 'PT Serif' ) );
		}
		if ( get_theme_mod( 'penci_font_for_menu_mobile' ) ) {
			$array_options[] = get_theme_mod( 'penci_font_for_menu_mobile' );
		}

		$array_options = array_diff( $array_options, $exlude_fonts );

		if ( ! empty( $array_options ) ) {

			foreach ( $array_options as $font ) {
				if ( strpos( $font, '-apple-system' ) !== false ) {
					continue;
				}
				$font_family       = str_replace( '"', '', $font );
				$font_family_explo = explode( ', ', $font_family );
				$array_get[]       = isset( $font_family_explo[0] ) ? $font_family_explo[0] : '';
			}

			$array_end        = array_unique( array_merge( $array_fonts, $array_get ), SORT_REGULAR );
			$array_fonts_data = $array_end;

			if ( ! empty( $array_end ) ) {
				$string_end = implode( ':300,300italic,400,400italic,500,500italic,700,700italic,800,800italic|', $array_end );

				/*
					Translators: If there are characters in your language that are not supported
					by chosen font(s), translate this to 'off'. Do not translate into your own language.
				*/
				if ( 'off' !== _x( 'on', 'Google font: on or off', 'soledad' ) ) {
					$font_url = add_query_arg(
						array(
							'family'  => urlencode( $string_end . ':300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic&subset=latin,cyrillic,cyrillic-ext,greek,greek-ext,latin-ext' ),
							'display' => 'swap',
						),
						'https://fonts.googleapis.com/css'
					);
				}
			}
		}

		if ( $data == 'normalarray' ) {
			return $array_fonts_data;
		} elseif ( $data == 'earlyaccess' ) {
			return $array_earlyaccess;
		} else {
			return $font_url;
		}
	}
}
