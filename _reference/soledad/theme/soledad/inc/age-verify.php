<?php

class PenciAgeVerify {
	function __construct() {

		if ( ! get_theme_mod( 'penci_agepopup_enable' ) || current_user_can( 'manage_options' ) ) {
			return;
		}

		add_action( 'wp_footer', [ $this, 'content' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'soledad_theme/custom_css', [ $this, 'popop_style' ] );
		add_filter( 'body_class', [ $this, 'body_class' ] );
	}

	public function scripts() {
		wp_enqueue_script( 'penci-age-verify', PENCI_SOLEDAD_URL . '/js/age-verify.js', [ 'js-cookies' ], PENCI_SOLEDAD_VERSION, true );
		wp_localize_script( 'penci-age-verify', 'penci_age_settings', [
			'age_verify'         => (bool) get_theme_mod( 'penci_agepopup_enable' ),
			'age_verify_expires' => 30,
		] );
	}

	public function body_class( $class ) {
		$class[] = 'pc-age-verify';

		return $class;
	}

	public function content() {
		$verify_content = get_theme_mod( 'penci_agepopup_message' );
		$error_content  = get_theme_mod( 'penci_agepopup_error_message' );
		$animation      = get_theme_mod( 'penci_agepopup_animation', 'move-to-top' );
		?>

        <div class="mfp-with-anim <?php echo esc_attr( $animation ); ?> penci-popup penci-age-verify">
            <div class="penci-age-verify-text">
				<?php echo do_shortcode( $verify_content ); ?>
            </div>

            <div class="penci-age-verify-text-error">
				<?php echo do_shortcode( $error_content ); ?>
            </div>

            <div class="penci-age-verify-buttons">
                <a href="#" aria-label="Agree" rel="nofollow noopener" class="button penci-age-verify-allowed">
					<?php echo penci_get_setting( 'penci_agepopup_agree_text' ); ?>
                </a>

                <a href="#" aria-label="Cancel" rel="nofollow noopener" class="button penci-age-verify-forbidden">
					<?php echo penci_get_setting( 'penci_agepopup_cancel_text' ); ?>
                </a>
            </div>
        </div>

		<?php
	}

	public function popop_style() {
		$popup_bgimg       = get_theme_mod( 'penci_agepopup_bgimg' );
		$popup_bgcl        = get_theme_mod( 'penci_agepopup_bgcolor' );
		$popup_bgrepeat    = get_theme_mod( 'penci_agepopup_bgrepeat' );
		$popup_bgposition  = get_theme_mod( 'penci_agepopup_bgposition' );
		$popup_bgsize      = get_theme_mod( 'penci_agepopup_bgsize' );
		$popup_bgscroll    = get_theme_mod( 'penci_agepopup_bgscroll' );
		$popup_mw          = get_theme_mod( 'penci_agepopup_width_mobile' );
		$popup_w           = get_theme_mod( 'penci_agepopup_width_desktop' );
		$popup_cl          = get_theme_mod( 'penci_agepopup_txtcolor' );
		$popup_tsize       = get_theme_mod( 'penci_agepopup_txt_size' );
		$popup_tmsize      = get_theme_mod( 'penci_agepopup_txt_msize' );
		$popup_spacing     = get_theme_mod( 'penci_agepopup_spacing' );
		$popup_bordercolor = get_theme_mod( 'penci_agepopup_bordercolor' );

		echo '.penci-age-verify{';
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
		if ( $popup_bordercolor ) {
			echo 'border-color:' . esc_attr( $popup_bordercolor ) . ';';
			echo 'border-style: solid;';
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
		if ( $popup_spacing ) {
			echo $this->penci_spacing_extract_data( $popup_spacing );
		}
		echo '}';

		echo '@media only screen and (max-width:767px){.penci-age-verify{';
		if ( $popup_tmsize ) {
			echo 'font-size:' . esc_attr( $popup_tmsize ) . 'px;';
		}
		if ( $popup_mw ) {
			echo 'max-width:' . esc_attr( $popup_mw ) . 'px;';
		}
		echo '}}';

		$btn_1_cl    = get_theme_mod( 'penci_agepopup_btn1_color' );
		$btn_1_hcl   = get_theme_mod( 'penci_agepopup_btn1_hcolor' );
		$btn_1_bgcl  = get_theme_mod( 'penci_agepopup_btn1_bgcolor' );
		$btn_1_hbgcl = get_theme_mod( 'penci_agepopup_btn1_hbgcolor' );

		echo '.penci-age-verify-buttons .button.penci-age-verify-allowed{';
		if ( $btn_1_cl ) {
			echo 'color:' . $btn_1_cl . ';';
		}
		if ( $btn_1_cl ) {
			echo 'background-color:' . $btn_1_bgcl . ';';
		}
		echo '}';

		echo '.penci-age-verify-buttons .button.penci-age-verify-allowed:hover{';
		if ( $btn_1_hcl ) {
			echo 'color:' . $btn_1_hcl . ';';
		}
		if ( $btn_1_hbgcl ) {
			echo 'background-color:' . $btn_1_hbgcl . ';';
		}
		echo '}';

		$btn_2_cl    = get_theme_mod( 'penci_agepopup_btn2_color' );
		$btn_2_hcl   = get_theme_mod( 'penci_agepopup_btn2_hcolor' );
		$btn_2_bgcl  = get_theme_mod( 'penci_agepopup_btn2_bgcolor' );
		$btn_2_hbgcl = get_theme_mod( 'penci_agepopup_btn2_hbgcolor' );

		echo '.penci-age-verify-buttons .button.penci-age-verify-forbidden{';
		if ( $btn_2_cl ) {
			echo 'color:' . $btn_2_cl . ';';
		}
		if ( $btn_2_cl ) {
			echo 'background-color:' . $btn_2_bgcl . ';';
		}
		echo '}';

		echo '.penci-age-verify-buttons .button.penci-age-verify-forbidden:hover{';
		if ( $btn_2_hcl ) {
			echo 'color:' . $btn_2_hcl . ';';
		}
		if ( $btn_2_hbgcl ) {
			echo 'background-color:' . $btn_2_hbgcl . ';';
		}
		echo '}';

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

new PenciAgeVerify;
