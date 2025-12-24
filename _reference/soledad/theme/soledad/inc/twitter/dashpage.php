<?php

class TwitterSettingPages {
	function __construct() {
		if ( ! function_exists( 'sb_twitter_feed_init' ) ) {
			add_action( 'admin_menu', [ $this, 'add_settings_page' ], 90 );
			add_action( 'init', [ $this, 'sb_twitter_feed' ] );
		}
	}

	public function add_settings_page() {
		add_submenu_page( 'soledad_dashboard_welcome', esc_html__( 'Connect Twitter', 'soledad' ), esc_html__( 'Connect Twitter', 'soledad' ), 'manage_options', 'penci_twitter_token', [
			$this,
			'dashboard_content'
		], 3 );
	}

	public function sb_twitter_feed() {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! empty( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) === 'custom-twitter-feeds' && is_array( $_GET ) ) {

			$nonce = isset( $_REQUEST['nonce'] ) && $_REQUEST['nonce'] ? $_REQUEST['nonce'] : '';
			
			if ( wp_verify_nonce( $nonce, 'custom-twitter-feeds' ) ) {

				update_option( 'penci_options[penci_twitter]', $_GET );

				// Redirect
				$redirect = admin_url( 'admin.php?page=penci_twitter_token' );
				wp_redirect( $redirect );

				exit;
			}
		}
	}

	public function dashboard_content() {
		$nonce = wp_create_nonce( 'custom-twitter-feeds' );
		$twitter_api         = 'https://api.smashballoon.com/twitter-login.php?return_uri=' . admin_url( 'admin.php?page=custom-twitter-feeds&nonce='.$nonce );
		$twitter_token       = get_option( 'penci_options[penci_twitter]' );
		$twitter_label       = __( 'You\'ve not connected to any Twitter Account.', 'soledad' );
		$twitter_description = sprintf( __( 'You can <a class="%1$s" href="%2$s" target="_blank">click here</a> to connect to your Twitter account.', 'soledad' ), 'penci_twitter_access_token twitter', $twitter_api );
		if ( ! empty( $twitter_token ) && isset( $twitter_token['screen_name'] ) ) {
			$twitter_label = sprintf( __( 'Connected to account <strong>%s</strong>', 'soledad' ), $twitter_token['screen_name'] );
		} else if ( isset( $twitter_token['error'] ) && $twitter_token['error'] ) {
			$twitter_label = __( 'Error connect to your Twitter account. Please try to connect later.', 'soledad' );
		}
		?>
        <div class="penci-insta-token-wrapper">
            <div class="pc-ins-tk top-icon">
                <span class="dashicons dashicons-twitter"></span>
            </div>
            <div class="pc-ins-tk top-head">
                <h3><?php echo $twitter_label; ?></h3>
                <p>
					<?php echo $twitter_description; ?>
                </p>
            </div>
        </div>
		<?php
	}
}

new TwitterSettingPages();
