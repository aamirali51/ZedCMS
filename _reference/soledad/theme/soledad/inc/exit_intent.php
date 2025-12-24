<?php
class PenciExitIntentPopup {
    function __construct() {

        if ( ! get_theme_mod( 'penci_epopup_enable' ) ) {
            return;
        }

        add_action( 'wp_footer', [ $this, 'popup_content' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'popup_assets' ] );
        add_action( 'soledad_theme/custom_css', [ $this, 'popup_style' ] );
    }

    public function popup_assets() {
        wp_enqueue_script('penci-epopup', PENCI_SOLEDAD_URL . '/js/exit.js', array('jquery'), PENCI_SOLEDAD_VERSION, true);
    }

    public function popup_content() {

		$popup_render_content = $class = '';

		$popup_content = get_theme_mod( 'penci_epopup_html_content' );
		$popup_block   = get_theme_mod( 'penci_epopup_block' );

		if ( $popup_block == '' && $popup_content ) {
			
			$popup_render_content = do_shortcode( $popup_content );
			$class                = 'normal-content';
			
		} elseif ( $popup_block ) {
			$popup_block_id = get_page_by_path( $popup_block, OBJECT, 'penci-block' )->ID;
			$class          = 'block-content';
			if ( $popup_block_id && did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->documents->get( $popup_block_id )->is_built_with_elementor() ) {
				$popup_render_content .= penci_get_elementor_content( $popup_block_id );
			} else {
				$popup_render_content .= do_shortcode( get_post( $popup_block_id )->post_content );

				$shortcodes_custom_css = get_post_meta( $popup_block_id, '_wpb_shortcodes_custom_css', true );

				$popup_render_content .= '<style data-type="vc_shortcodes-custom-css">';
				if ( ! empty( $shortcodes_custom_css ) ) {
					$popup_render_content .= $shortcodes_custom_css;
				}
				$popup_render_content .= '</style>';
			}
		}

		if ( $popup_render_content ) {
			$animation = get_theme_mod( 'penci_epopup_animation', 'move-to-top' );
			echo '<div class="mfp-with-anim penci-epopup-content ' . $class . ' ' . $animation . '">' . $popup_render_content . '</div>';
			wp_reset_postdata();
		}
	}

    public function popup_style() {

		$popup_bgimg       = get_theme_mod( 'penci_epopup_bgimg' );
		$popup_bgcl        = get_theme_mod( 'penci_epopup_bgcolor' );
		$popup_bgrepeat    = get_theme_mod( 'penci_epopup_bgrepeat' );
		$popup_bgposition  = get_theme_mod( 'penci_epopup_bgposition' );
		$popup_bgsize      = get_theme_mod( 'penci_epopup_bgsize' );
		$popup_bgscroll    = get_theme_mod( 'penci_epopup_bgscroll' );
		$popup_mw          = get_theme_mod( 'penci_epopup_width_mobile' );
		$popup_w           = get_theme_mod( 'penci_epopup_width_desktop' );
		$popup_cl          = get_theme_mod( 'penci_epopup_txtcolor' );
		$popup_tsize       = get_theme_mod( 'penci_epopup_txt_size' );
		$popup_tmsize      = get_theme_mod( 'penci_epopup_txt_msize' );
		$popup_spacing     = get_theme_mod( 'penci_epopup_spacing' );
		$popup_bordercolor = get_theme_mod( 'penci_epopup_bordercolor' );

		echo '.penci-epopup-content{';
		if ( $popup_bgimg ) {
			echo 'background-image:url("' . esc_url( $popup_bgimg ) . '");';
		}
		if ( $popup_bgcl ) {
			echo 'background-color:' . esc_attr( $popup_bgcl ) . ';';
		}
		if ( $popup_bgrepeat ) {
			echo 'background-repeat:' . esc_attr( $popup_bgrepeat ) . ';';
		}
		if ( $popup_bgposition ) {
			echo 'background-position:' . esc_attr( $popup_bgposition ) . ';';
		}
		if ( $popup_bgsize ) {
			echo 'background-size:' . esc_attr( $popup_bgsize ) . ';';
		}
		if ( $popup_bgscroll ) {
			echo 'background-scroll:' . esc_attr( $popup_bgscroll ) . ';';
		}
		if ( $popup_w ) {
			echo 'max-width:' . esc_attr( $popup_w ) . 'px;';
		}
		if ( $popup_cl ) {
			echo 'color:' . esc_attr( $popup_cl ) . ';';
		}
		if ( $popup_tsize ) {
			echo 'font-size:' . esc_attr( $popup_tsize ) . 'px;';
		}
		if ( $popup_bordercolor ) {
			echo 'border-color:' . esc_attr( $popup_bordercolor ) . ';';
			echo 'border-style: solid;';
		}
		if ( $popup_spacing ) {
			echo $this->penci_spacing_extract_data( $popup_spacing );
		}
		echo '}';

		$popup_close_cl = get_theme_mod( 'penci_epopup_closecolor' );

		if ( $popup_close_cl ) {
			echo '.mfp-close-btn-in .penci-popup-content .mfp-close{';
			echo 'color:' . esc_attr( $popup_close_cl ) . ';';
			echo '}';
		}


		echo '@media only screen and (max-width:767px){.penci-popup-content{';
		if ( $popup_tmsize ) {
			echo 'font-size:' . esc_attr( $popup_tmsize ) . 'px;';
		}
		if ( $popup_mw ) {
			echo 'max-width:' . esc_attr( $popup_mw ) . 'px;';
		}
		echo '}}';

	}

	public function penci_spacing_extract_data( $number = '', $out = '' ) {
		$mpb = explode( ',', $number );
		if ( isset( $mpb[0] ) && is_numeric( $mpb[0] ) ) {
			$out .= 'padding-top:' . esc_attr( $mpb[0] ) . 'px;';
		}
		if ( isset( $mpb[1] ) && is_numeric( $mpb[1] ) ) {
			$out .= 'padding-right:' . esc_attr( $mpb[1] ) . 'px;';
		}
		if ( isset( $mpb[2] ) && is_numeric( $mpb[2] ) ) {
			$out .= 'padding-bottom:' . esc_attr( $mpb[2] ) . 'px;';
		}
		if ( isset( $mpb[3] ) && is_numeric( $mpb[3] ) ) {
			$out .= 'padding-left:' . esc_attr( $mpb[3] ) . 'px;';
		}

		if ( isset( $mpb[4] ) && is_numeric( $mpb[4] ) ) {
			$out .= 'border-top-width:' . esc_attr( $mpb[4] ) . 'px;';
		}
		if ( isset( $mpb[5] ) && is_numeric( $mpb[5] ) ) {
			$out .= 'border-right-width:' . esc_attr( $mpb[5] ) . 'px;';
		}
		if ( isset( $mpb[6] ) && is_numeric( $mpb[6] ) ) {
			$out .= 'border-bottom-width:' . esc_attr( $mpb[6] ) . 'px;';
		}
		if ( isset( $mpb[7] ) && is_numeric( $mpb[7] ) ) {
			$out .= 'border-left-width:' . esc_attr( $mpb[7] ) . 'px;';
		}

		if ( isset( $mpb[8] ) && is_numeric( $mpb[8] ) ) {
			$out .= 'border-top-left-radius:' . esc_attr( $mpb[8] ) . 'px;';
		}
		if ( isset( $mpb[9] ) && is_numeric( $mpb[9] ) ) {
			$out .= 'border-top-right-radius:' . esc_attr( $mpb[9] ) . 'px;';
		}
		if ( isset( $mpb[10] ) && is_numeric( $mpb[10] ) ) {
			$out .= 'border-bottom-right-radius:' . esc_attr( $mpb[10] ) . 'px;';
		}
		if ( isset( $mpb[11] ) && is_numeric( $mpb[11] ) ) {
			$out .= 'border-bottom-left-radius:' . esc_attr( $mpb[11] ) . 'px;';
		}

		return $out;
	}
}

$exit_intent_popup = new PenciExitIntentPopup();