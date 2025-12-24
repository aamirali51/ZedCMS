<?php

class Penci_AdBlocker_Detector {
	public function __construct() {
		$block           = false;
		$disable_adblock = get_theme_mod( 'penci_adblocker_user_group', array() );
		if ( ! empty( $disable_adblock ) && is_array( $disable_adblock ) && is_user_logged_in() ) {

			$user_data  = wp_get_current_user();
			$user_roles = (array) $user_data->roles;

			foreach ( $disable_adblock as $user_group ) {
				foreach ( $user_roles as $role ) {
					$block = $role == $user_group;
				}
			}
		}

		if ( ! get_theme_mod( 'penci_adblocker_popup' ) || $block ) {
			return;
		}

		add_action( 'wp_footer', array( $this, 'ad_blocker' ), 500 );
		add_action( 'soledad_theme/custom_css', array( $this, 'custom_style' ) );
	}


	public function ad_blocker() {

		echo '<ins class="adsbygoogle Ad-Container sidebar-ad"><div style="z-index:-1; height:0; width:1px; visibility: hidden; bottom: -1px; left: 0;"></div></ins>';

		if ( get_theme_mod( 'penci_adblocker_popup_dismissable' ) && get_theme_mod( 'penci_adblocker_popup_onetime' ) && isset( $_COOKIE['AdBlockerDismissed'] ) ) {
			return;
		}

		?>
		<div id="penci-adblocker-popup-adblock"
			class="mfp-hide mfp-with-anim penci-popup penci-adblocker-popup">

			<div class="penci-adblocker-popup-container">
				<div class="container-wrapper">

					<span class="penci-adblocker-adblock-icon fa fa-exclamation-triangle" aria-hidden="true"></span>

					<h2 class="penci-adblocker-head"><?php echo penci_get_setting( 'penci_adblocker_popup_title' ); ?></h2>

					<div class="penci-adblock-message">
						<?php echo penci_get_setting( 'penci_adblocker_popup_message' ); ?>
					</div>

					<div class="penci-adblock-btn-group">

						<?php if ( get_theme_mod( 'penci_adblocker_popup_dismissable' ) ) : ?>
							<button type="button"
									class="mfp-close"><?php echo penci_get_setting( 'penci_adblocker_dissmiss' ); ?></button>
						<?php endif; ?>

					</div>

				</div>
			</div>
		</div>
		<?php
		$script_content = file_get_contents( PENCI_SOLEDAD_DIR . '/js/detector.min.js' );
		$script_options = array(
			'ad_blocker_detector'               => (bool) get_theme_mod( 'penci_adblocker_popup' ),
			'ad_blocker_detector_delay'         => get_theme_mod( 'penci_adblocker_popup_time' ),
			'ad_blocker_detector_dismissable'   => (bool) get_theme_mod( 'penci_adblocker_popup_dismissable' ),
			'ad_blocker_detector_onetime'       => (bool) get_theme_mod( 'penci_adblocker_popup_onetime' ),
			'ad_blocker_detector_block_img'     => (bool) get_theme_mod( 'penci_adblocker_disallow_images' ),
			'ad_blocker_detector_block_img_url' => get_theme_mod( 'penci_adblocker_placeholder_images' ),
			'ad_blocker_detector_post'          => (bool) get_theme_mod( 'penci_adblocker_disallow_images_posts' ),
		);

		if ( $script_content ) {
			echo '<script type="text/javascript">';
			echo 'var penci_options_set = '.json_encode($script_options).';';
			echo $script_content;
			echo '</script>';
		}
	}

	public function custom_style() {
		$out       = '';
		$selectors = array(
			'penci_adblocker_popup_bgcolor'      => '.penci-adblocker-popup-container{background-color:{{VALUE}}}',
			'penci_adblocker_popup_icolor'       => '.penci-adblocker-popup-container .penci-adblocker-adblock-icon{color:{{VALUE}}}',
			'penci_adblocker_popup_headingcolor' => '.penci-adblocker-popup-container .penci-adblocker-head{color:{{VALUE}}}',
			'penci_adblocker_popup_textcolor'    => '.penci-adblocker-popup-container .penci-adblock-message{color:{{VALUE}}}',
			'penci_adblocker_popup_btncolor'     => '.penci-adblocker-popup-container button.mfp-close{color:{{VALUE}}}',
			'penci_adblocker_popup_btnhcolor'    => '.penci-adblocker-popup-container button.mfp-close:hover{color:{{VALUE}}}',
		);
		foreach ( $selectors as $mod => $selector ) {
			$value = get_theme_mod( $mod );
			if ( $value ) {
				$out .= str_replace( '{{VALUE}}', $value, $selector );
			}
		}
		echo $out;
	}
}

new Penci_AdBlocker_Detector();
