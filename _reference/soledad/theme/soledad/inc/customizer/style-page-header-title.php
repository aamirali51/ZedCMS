<?php
if ( ! function_exists( 'pencidesign_customizer_css_page_header_title' ) ):
	function pencidesign_customizer_css_page_header_title() {

		$page_title_df = array(
			'pheader_width'         => '',
			'pheader_ptop'          => '',
			'pheader_pbottom'       => '',
			'pheader_turn_offup'    => '',
			'pheader_title_pbottom' => '',
			'pheader_title_mbottom' => '',
			'pheader_title_fsize'   => '',
			'pheader_fwtitle'       => '',
			'pheader_bread_fsize'   => '',
			'pheader_bgimg'         => '',
			'pheader_bgcolor'       => '',
			'pheader_title_color'   => '',
			'pheader_bread_color'   => '',
			'pheader_bread_hcolor'  => '',
			'pheader_line_color'    => '',
		);
		$page_title    = get_post_meta( get_the_ID(), 'penci_pmeta_page_title', true );
		$page_title    = wp_parse_args( $page_title, $page_title_df );

		$pheader_width         = $page_title['pheader_width'] ? $page_title['pheader_width'] : get_theme_mod( 'penci_pheader_width' );
		$pheader_ptop          = $page_title['pheader_ptop'] ? $page_title['pheader_ptop'] : get_theme_mod( 'penci_pheader_ptop' );
		$pheader_pbottom       = $page_title['pheader_pbottom'] ? $page_title['pheader_pbottom'] : get_theme_mod( 'penci_pheader_pbottom' );
		$pheader_turn_offup    = get_theme_mod( 'penci_pheader_turn_offup' );
		$pheader_title_pbottom = $page_title['pheader_title_pbottom'] ? $page_title['pheader_title_pbottom'] : get_theme_mod( 'penci_pheader_title_pbottom' );
		$pheader_title_mbottom = $page_title['pheader_title_mbottom'] ? $page_title['pheader_title_mbottom'] : get_theme_mod( 'penci_pheader_title_mbottom' );
		$pheader_title_fsize   = $page_title['pheader_title_fsize'] ? $page_title['pheader_title_fsize'] : get_theme_mod( 'penci_pheader_title_fsize' );
		$pheader_fwtitle       = $page_title['pheader_fwtitle'] ? $page_title['pheader_fwtitle'] : get_theme_mod( 'penci_pheader_fwtitle' );
		$pheader_bread_fsize   = $page_title['pheader_bread_fsize'] ? $page_title['pheader_bread_fsize'] : get_theme_mod( 'penci_pheader_bread_fsize' );
		$pheader_bgimg         = $page_title['pheader_bgimg'] ? wp_get_attachment_url( intval( $page_title['pheader_bgimg'] ) ) : get_theme_mod( 'penci_pheader_bgimg' );
		$pheader_bgcolor       = $page_title['pheader_bgcolor'] ? $page_title['pheader_bgcolor'] : get_theme_mod( 'penci_pheader_bgcolor' );
		$pheader_title_color   = $page_title['pheader_title_color'] ? $page_title['pheader_title_color'] : get_theme_mod( 'penci_pheader_title_color' );
		$pheader_bread_color   = $page_title['pheader_bread_color'] ? $page_title['pheader_bread_color'] : get_theme_mod( 'penci_pheader_bread_color' );
		$pheader_bread_hcolor  = $page_title['pheader_bread_hcolor'] ? $page_title['pheader_bread_hcolor'] : get_theme_mod( 'penci_pheader_bread_hcolor' );
		$pheader_line_color    = $page_title['pheader_line_color'] ? $page_title['pheader_line_color'] : get_theme_mod( 'penci_pheader_line_color' );

		if ( 'on' == $page_title['pheader_turn_offup'] ) {
			$pheader_turn_offup = true;
		} elseif ( 'off' == $page_title['pheader_turn_offup'] ) {
			$pheader_turn_offup = false;
		}
		$penci_header_padding = '';
		?>

		<?php if ( $pheader_width ): ?>
			.penci-page-header-wrap .penci-page-header-inner.container {
			max-width: <?php echo esc_attr( $pheader_width ); ?>px;
			width: 100%;
			}
		<?php endif; ?>
		
		<?php if ( $pheader_ptop ): ?>
			.penci-page-header-wrap {
			padding-top: <?php echo esc_attr( $pheader_ptop ); ?>px;
			}
		<?php endif; ?>
		
		<?php if ( $pheader_pbottom ): ?>
			.penci-page-header-wrap {
			padding-bottom: <?php echo esc_attr( $pheader_pbottom ); ?>px;
			}
		<?php endif; ?>
		
		<?php
		$css_title = '';
		if ( $pheader_turn_offup ) {
			$css_title .= 'text-transform: none;';
		}
		if ( $pheader_title_pbottom ) {
			$css_title .= 'padding-bottom:' . esc_attr( $pheader_title_pbottom ) . 'px;';
		}
		if ( $pheader_title_mbottom ) {
			$css_title .= 'margin-bottom:' . esc_attr( $pheader_title_mbottom ) . 'px;';
		}
		if ( $pheader_title_fsize ) {
			$css_title .= 'font-size:' . esc_attr( $pheader_title_fsize ) . 'px;';
		}
		if ( $pheader_fwtitle ) {
			$css_title .= 'font-weight:' . esc_attr( $pheader_fwtitle ) . ';';
		}
		if ( $pheader_title_color ) {
			$css_title .= 'color:' . esc_attr( $pheader_title_color ) . ';';
		}
		if ( $css_title ) {
			echo '.penci-page-header-wrap .penci-page-header-title {' . $css_title . '}';
		}

		if ( $pheader_line_color ) {
			echo '.penci-page-header-wrap .penci-page-header-title:before{ border-color: ' . esc_attr( $pheader_line_color ) . '; opacity: 1; }';
		}

		if ( $pheader_bread_fsize ) {
			echo '.penci-page-header-wrap .container.penci-breadcrumb  span, .penci-page-header-wrap .container.penci-breadcrumb a, .penci-page-header-wrap .container.penci-breadcrumb i{ font-size:' . esc_attr( $pheader_bread_fsize ) . 'px; }';
		}
		if ( $pheader_bread_color ) {
			echo '.penci-page-header-wrap .container.penci-breadcrumb  span, .penci-page-header-wrap .container.penci-breadcrumb a,.penci-page-header-wrap .container.penci-breadcrumb i{ color:' . esc_attr( $pheader_bread_color ) . '; }';
		}
		if ( $pheader_bread_hcolor ) {
			echo '.penci-page-header-wrap .container.penci-breadcrumb  a:hover{ color:' . $pheader_bread_hcolor . '; }';
		}

		if ( $pheader_bgimg ) {
			echo '.penci-page-header-wrap{ background-image: url(' . esc_attr( $pheader_bgimg ) . '); }';
		}
		if ( $pheader_bgcolor ) {
			echo '.penci-page-header-wrap{ background-color:' . esc_attr( $pheader_bgcolor ) . '; }';
		}
	}
endif;
