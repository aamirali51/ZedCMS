<?php

class InstgramSettingPages {
	function __construct() {
		if ( ! function_exists( 'sb_instagram_feed_init' ) ) {
			add_action( 'admin_menu', [ $this, 'add_settings_page' ], 90 );
			add_action( 'init', [ $this, 'sb_instagram_feed' ] );
		}
	}

	public function add_settings_page() {
		add_submenu_page(
			'soledad_dashboard_welcome',
			esc_html__( 'Connect Instagram', 'soledad' ),
			esc_html__( 'Connect Instagram', 'soledad' ),
			'manage_options',
			'penci_instgram_token',
			[ $this, 'dashboard_content' ],
			3
		);
	}

	public function sb_instagram_feed() {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! empty( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) === 'sbi-feed-builder' ) {

			if ( ! empty( $_GET['sbi_access_token'] ) && current_user_can( 'manage_options' ) ) {

				$expires_in        = (int) $_GET['sbi_expires_in'];
				$expires_timestamp = time() + $expires_in;

				$account = [
					'id'           => sanitize_text_field( $_GET['sbi_id'] ),
					'username'     => sanitize_text_field( $_GET['sbi_username'] ),
					'access_token' => $this->sanitize_alphanumeric_and_equals( sanitize_text_field( $_GET['sbi_access_token'] ) ),
					'expires_on'   => (int) $expires_timestamp,
				];
				update_option( 'penci_options[penci_instagram]', $account );
			}

			// Redirect
			$redirect = admin_url( 'admin.php?page=penci_instgram_token' );
			wp_redirect( $redirect );

			exit;
		}
	}

	public function clean( $maybe_dirty ) {

		if ( substr_count( $maybe_dirty, '.' ) < 3 ) {
			return str_replace( '634hgdf83hjdj2', '', $maybe_dirty );
		}

		$parts     = explode( '.', trim( $maybe_dirty ) );
		$last_part = $parts[2] . $parts[3];
		$cleaned   = $parts[0] . '.' . base64_decode( $parts[1] ) . '.' . base64_decode( $last_part );

		return $cleaned;
	}

	public function dashboard_content() {
		$current_user          = wp_get_current_user();
		$instagram_api		   = 'https://www.instagram.com/oauth/authorize?client_id=1070624227814319&redirect_uri=https://connect-ig.smashballoon.com/instagram-business-redirect.php&response_type=code&scope=instagram_business_basic&state={%27{url={%27{url='.admin_url( 'admin.php?page=sbi-feed-builder' ).'}%27},user=' . $current_user->user_email . ',opt=in,sbi_con=f9ca19d9ce,type=business_basic,vn=6.6.1,v=free}%27}';
		$instagram_token       = get_option( 'penci_options[penci_instagram]' );
		$instagram_label       = __( 'You\'ve not connected to any Instagram Account.', 'soledad' );
		$instagram_description = sprintf( __( 'You can <a class="%1$s" href="%2$s" target="_blank">click here</a> to connect to your Instagram account.', 'soledad' ), 'penci_instagram_access_token instagram', $instagram_api );
		if ( is_array( $instagram_token ) && ! empty( $instagram_token ) ) {
			$instagram_label       = sprintf( __( 'Connected to account <strong>%s</strong>', 'soledad' ), $instagram_token['username'] );
			$instagram_description = sprintf( __( 'This token is valid until <strong>%1$s</strong> because Instagram just provide an active token in 30 days.<br>Before it expired, you should connect it again to make it won\'t break the connection to Instagram.<br>You can re-connect or connect to another Instagram account by click <a class="%2$s" href="%3$s" target="_blank">HERE</a>.', 'soledad' ), date( 'F d, Y H:i:s', (int) $instagram_token['expires_on'] ), 'penci_instagram_access_token instagram', $instagram_api );
		}
		?>
        <div class="penci-insta-token-wrapper">
            <div class="pc-ins-tk top-icon">
                <span class="dashicons dashicons-instagram"></span>
            </div>
            <div class="pc-ins-tk top-head">
                <h3><?php echo $instagram_label; ?></h3>
                <p>
					<?php echo $instagram_description; ?>
                </p>
            </div>
        </div>
		<?php
	}

	function sanitize_alphanumeric_and_equals( $value ) {
		return preg_replace( '/[^A-Za-z0-9=]/', '', $value );
	}
}

new InstgramSettingPages();
