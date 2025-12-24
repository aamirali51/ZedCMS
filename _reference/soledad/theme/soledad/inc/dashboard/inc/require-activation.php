<?php
if ( ! function_exists( 'penci_soledad_is_activated' ) ) {
	function penci_soledad_is_activated() {
		return get_option( 'penci_soledad_is_activated' );
	}
}

if ( ! function_exists( 'penci_soledad_is_license' ) ) {
	function penci_soledad_is_license() {
		$license_data = get_option( 'penci_soledad_purchased_data' );
		if ( ! empty( $license_data ) ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! class_exists( 'Penci_Require_Active' ) ) {
	class Penci_Require_Active {
		protected $time_max = 2592000; // 30 days
		protected $theme_info;

		public function __construct() {
			// Not run code require active theme on the admin
			if ( ! is_admin() ) {
				return;
			}

			$this->theme_info = wp_get_theme();
			$this->main();

			add_action( 'wp_ajax_nopriv_penci_check_envato_code', array( $this, 'do_check_envato_code' ) );
			add_action( 'wp_ajax_penci_check_envato_code', array( $this, 'do_check_envato_code' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ), 10, 1 );
		}

		public function main() {

			$curent_time             = time();
			$active_status_last_time = get_option( 'soledad_active_status_last_time' );

			add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 15 );

			if ( empty( $active_status_last_time ) ) {
				update_option( 'soledad_active_status_last_time', $curent_time );
			} else {
				if ( penci_soledad_is_activated() || get_option( 'penci_hide_license_notice' ) ) {
					return;
				}
			}
		}

		public function add_admin_scripts( $hook ) {
			if ( penci_soledad_is_activated() ) {
				return;
			}

			$active_status_last_time = get_option( 'soledad_active_status_last_time' );
			$time_delta              = time() - $active_status_last_time;
			$time_max                = $this->time_max;

			if ( $time_delta < $time_max ) {
				return;
			}

			if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
				wp_enqueue_script( 'soledad-editor-script', PENCI_SOLEDAD_URL . '/inc/dashboard/js/edit-post.js', array( 'jquery' ) );
			}
		}

		function add_submenu_page() {

			if ( ! penci_soledad_is_activated() ) {
				add_submenu_page(
					'soledad_dashboard_welcome',
					esc_html__( 'Active theme', 'soledad' ),
					esc_html__( 'Active theme', 'soledad' ),
					'manage_options',
					'soledad_active_theme',
					array(
						$this,
						'require_active_page',
					)
				);
				add_action( 'admin_notices', array( $this, 'validation_notice' ) );
			} elseif ( penci_soledad_is_activated() && penci_soledad_is_license() ) {
				add_submenu_page(
					'soledad_dashboard_welcome',
					esc_html__( 'Theme License', 'soledad' ),
					esc_html__( 'Theme License', 'soledad' ),
					'manage_options',
					'soledad_theme_license',
					array(
						$this,
						'theme_license_page',
					)
				);
			}

			if ( penci_is_new_update() ) {
				$version    = penci_is_new_update( 'version' );
				$page_title = esc_html__( 'New Update', 'soledad' );
				$menu_title = $page_title . ' <span class="update-plugins"><span class="update-count">' . $version . '</span></span>';
				add_submenu_page(
					'soledad_dashboard_welcome',
					$page_title,
					$menu_title,
					'manage_options',
					'soledad_new_update',
					array(
						$this,
						'new_update_page',
					)
				);
			}
		}


		public function get_server_id() {
			ob_start();
			if ( function_exists( 'phpinfo' ) ) {
				phpinfo( INFO_GENERAL );
			}
			echo $this->theme_info->name;

			return md5( ob_get_clean() );
		}

		/**
		 * Show notice active theme
		 */
		function validation_notice() {
			$link_page_active = admin_url( 'admin.php?page=soledad_active_theme' );
			?>
			<div class="penci-license-notice notice notice-success is-dismissible"
				style="background: #b70303; color: #fff; border-color: #00a32a;padding: 15px 25px 15px 120px;position: relative;">
				<i style="font-size: 100px;position: absolute;left: 10px;top: 50%;transform: translateY(-50%);width: auto;height: auto;"
					class="dashicons dashicons-warning"></i>
				<p style="font-size: 14px">
					<a class="penci-notice-logo" href="<?php echo esc_url( 'https://pencidesign.net/' ); ?>"
						target="_blank"><?php $this->get_icon_penci(); ?></a>
					<?php _e( '<strong>The system can\'t detect your purchase. Please activate Soledad to use full features of the theme</strong>. We\'re sorry about this but we built the activation system to prevent piracy of our themes in the internet, we can do better serve our paying customers.', 'soledad' ); ?>
				</p>
				<p style="font-size: 14px">
					<?php esc_html_e( 'You can', 'soledad' ); ?>
					<a style="color: #0bf948;"
						href="<?php echo( $link_page_active ); ?>"><?php esc_html_e( 'click here to activate the theme', 'soledad' ); ?></a>
					- <?php _e( 'If you have issues with this please contact us via <a rel="nofollow" href="http://pencidesign.ticksy.com/" target="_blank" style="color: #0bf948;">our support forum</a> or <a style="color: #0bf948;" rel="nofollow" href="https://themeforest.net/user/pencidesign#contact" target="_blank">our contact form</a>. This notice will be removed after you activated the theme.', 'soledad' ); ?>
				</p>
				<p style="font-size: 14px"><?php _e( 'You can check the documentation for this theme <a style="color: #0bf948;" href="https://soledad.pencidesign.net/soledad-document/" target="_blank">here</a> to know how to config this theme also.', 'soledad' ); ?></p>
			</div>
			<?php
		}

		/**
		 * Get icon penci
		 */
		function get_icon_penci() {
			?>
			<svg style="position: relative; top:4px;margin-right: 5px;" version="1.0" xmlns="http://www.w3.org/2000/svg"
				width="18px" height="18px" viewBox="0 0 26.000000 26.000000"
				preserveAspectRatio="xMidYMid meet">
				<g transform="translate(0.000000,26.000000) scale(0.100000,-0.100000)"
					fill="#ffffff" stroke="none">
					<path d="M72 202 l-62 -60 0 -66 0 -66 125 0 125 0 0 61 0 61 -63 65 -62 64
				-63 -59z m73 28 c3 -5 -3 -10 -15 -10 -12 0 -18 5 -15 10 3 6 10 10 15 10 5 0
				12 -4 15 -10z m57 -57 c34 -33 36 -38 20 -49 -14 -10 -21 -8 -45 12 -36 31
				-62 30 -93 -1 -21 -21 -28 -23 -44 -13 -19 12 -18 14 17 50 51 52 92 52 145 1z
				m-77 -93 c0 -59 -1 -60 -27 -60 -26 0 -28 3 -28 42 0 24 7 49 17 60 28 32 38
				21 38 -42z m49 44 c10 -9 16 -33 16 -60 0 -40 -2 -44 -25 -44 -24 0 -25 3 -25
				60 0 62 7 71 34 44z m-130 -20 c9 -8 16 -31 16 -50 0 -27 -4 -34 -20 -34 -17
				0 -20 7 -20 50 0 28 2 50 4 50 3 0 12 -7 20 -16z m201 -34 c0 -44 -3 -50 -20
				-50 -18 0 -20 5 -17 38 4 35 17 62 31 62 3 0 6 -22 6 -50z"/>
					<path d="M90 70 c0 -5 5 -10 10 -10 6 0 10 5 10 10 0 6 -4 10 -10 10 -5 0 -10
				-4 -10 -10z"/>
				</g>
			</svg>
			<?php
		}

		public function require_active_page() {
			$soledad_theme = wp_get_theme();
			?>
			<div class="wrap about-wrap penci-about-wrap penci-active-theme penci-dashboard-wapper">
				<?php include PENCI_SOLEDAD_DIR . '/inc/dashboard/sections/welcome.php'; ?>
				<h2 class="nav-tab-wrapper">
					<a href="<?php echo admin_url( 'admin.php?page=soledad_dashboard_welcome' ); ?>"
						class="nav-tab"><?php esc_html_e( 'Getting started', 'soledad' ); ?></a>
					<a href="<?php echo admin_url( 'customize.php' ); ?>"
						class="nav-tab"><?php esc_html_e( 'Customize Style', 'soledad' ); ?></a>
					<a href="<?php echo admin_url( 'admin.php?page=soledad_custom_fonts' ); ?>"
						class="nav-tab"><?php esc_html_e( 'Custom Fonts', 'soledad' ); ?></a>
					<a href="<?php echo admin_url( 'admin.php?page=soledad_active_theme' ); ?>"
						class="nav-tab nav-tab-active"><?php esc_html_e( 'Active theme', 'soledad' ); ?></a>
					<a href="https://pencidesign.ticksy.com/" target="_blank"
						class="nav-tab"><?php esc_html_e( 'Support Forum', 'soledad' ); ?></a>
				</h2>
				<div class="penci-activate-wrap gt-tab-pane gt-is-active">
					<div class="penci-activate-envato-code">
						<div class="penci-activate-code-title"><?php esc_html_e( 'Active Soledad', 'soledad' ); ?></div>
						<p class="penci-activate-desc">
							<?php echo esc_html( sprintf( __( 'Please activate %s to use full features of the theme. We\'re sorry about this but we built the activation system to prevent piracy of our themes in the internet, we can do better serve our paying customers. Also, use the theme without a valid license can make your site get issues with DMCA later.', 'soledad' ), $soledad_theme->name ) ); ?>
							<br>
							<?php _e( 'And please note that: <strong>With each license - you just can use for one website.</strong><br>If you want to use this theme for multiple sites, please buy more licenses for it.<br>Example: <strong>2 licenses can use for 2 websites, 4 licenses can use for 4 websites, 7 licenses can use for 7 websites,.etc..</strong>', 'soledad' ); ?>
							<br>
							<?php _e( 'You can put your purchase code to the field below to activate the theme.<br>Check <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">this guide</a> to know how to get your purchase code.<br>After activating the theme, you can revoke the license for this website if you want.', 'soledad' ); ?>
						</p>
						<form id="penci-check-license"
								action="<?php echo admin_url( 'admin.php?page=soledad_active_theme' ); ?>">
							<div class="penci-activate-inputs">
								<input name="evato-code" class="penci-form-control evato-code" type="text"
										placeholder="<?php esc_html_e( 'Your Purchase Code', 'soledad' ); ?>" value=""
										autocomplete="off">
								<input type="hidden" name="server-id" class="server-id"
										value="<?php echo $this->get_server_id(); ?>" readonly/>
								<span class="penci-form-control-bar"></span>
								<div class="penci-activate-err">
									<span class="penci-err-missing"><?php esc_html_e( 'Code is required', 'soledad' ); ?></span>
									<span class="penci-err-length"><?php esc_html_e( 'Code is too short', 'soledad' ); ?></span>
									<span class="penci-err-invalid"><?php esc_html_e( 'Invalid purchase code. Please check it again.', 'soledad' ); ?></span>
								</div>
							</div>
							<button class="soledad-activate-button"><?php esc_html_e( 'Activate theme', 'soledad' ); ?></button>
							<div class="spinner"></div>
							<div class="soledad-find-code">
								<a href="<?php echo esc_url( 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-' ); ?>"
									target="_blank">
									<?php esc_html_e( 'Find Your Purchase Code', 'soledad' ); ?>
								</a>
							</div>
						</form>
						<div class="penci-activate-extra-notes">
							<p class="penci-activate-desc" style="margin: 30px 0 10px;">
								<?php _e( 'To deliver you a better customer support service, access to features and to prevent piracy when the theme is activated the following data is sent to our servers:', 'soledad' ); ?>
							</p>
							<ul style="list-style: square; padding-left: 40px; font-size: 14px;">
								<li><?php _e( 'The Envato username', 'soledad' ); ?></li>
								<li><?php _e( 'The Envato purchase code for the item', 'soledad' ); ?></li>
								<li><?php _e( 'The website URL you\'ve activated the theme', 'soledad' ); ?></li>
								<li><?php _e( 'The server IP address that has the theme installed', 'soledad' ); ?></li>
							</ul>
							<p class="penci-activate-desc"
								style="margin-bottom: 0;"><?php _e( 'The data is stored in a server located in US, and we do not share any of this information with third-party partners.' ); ?></p>
							</p>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		public function theme_license_page() {
			?>
			<div class="wrap about-wrap penci-about-wrap penci-active-theme penci-dashboard-wapper">
				<?php include PENCI_SOLEDAD_DIR . '/inc/dashboard/sections/welcome.php'; ?>
				<h2 class="nav-tab-wrapper">
					<a href="<?php echo admin_url( 'admin.php?page=soledad_dashboard_welcome' ); ?>"
						class="nav-tab"><?php esc_html_e( 'Getting started', 'soledad' ); ?></a>
					<a href="<?php echo admin_url( 'customize.php' ); ?>"
						class="nav-tab"><?php esc_html_e( 'Customize Style', 'soledad' ); ?></a>
					<a href="<?php echo admin_url( 'admin.php?page=soledad_custom_fonts' ); ?>"
						class="nav-tab"><?php esc_html_e( 'Custom Fonts', 'soledad' ); ?></a>
					<a href="<?php echo admin_url( 'admin.php?page=soledad_theme_license' ); ?>"
						class="nav-tab nav-tab-active"><?php esc_html_e( 'Theme License', 'soledad' ); ?></a>
					<a href="https://pencidesign.ticksy.com/" target="_blank"
						class="nav-tab"><?php esc_html_e( 'Support Forum', 'soledad' ); ?></a>
				</h2>
				<div class="penci-activate-wrap gt-tab-pane gt-is-active">
					<div class="penci-activate-envato-code">
						<div class="penci-activate-code-title"><?php esc_html_e( 'Theme License', 'soledad' ); ?></div>
						<?php
						$license_data = get_option( 'penci_soledad_purchased_data' );
						if ( ! empty( $license_data ) ) {
							?>
							<h3><?php esc_html_e( 'License Detail', 'soledad' ); ?></h3>
							<p class="penci-license-detail-desc" style="font-size: 15px;">
								<?php
								if ( isset( $license_data['buyer'] ) && $license_data['buyer'] ) {
									$buyer         = $license_data['buyer'];
									$buyer_len     = strlen( $buyer );
									$buyer_display = substr( $buyer, 0, 1 ) . str_repeat( '*', $buyer_len - 2 ) . substr( $buyer, $buyer_len - 1, 1 );
									?>
									<strong style="display: inline-block; margin-bottom: 8px; min-width: 140px;"><?php esc_html_e( 'Buyer Username', 'soledad' ); ?></strong> : &nbsp;&nbsp;
									<strong><?php echo $buyer_display; ?></strong><br>
								<?php } ?>
								<?php if ( isset( $license_data['bount_time'] ) && $license_data['bount_time'] ) { ?>
									<strong style="display: inline-block; margin-bottom: 8px; min-width: 140px;"><?php esc_html_e( 'Purchase Date', 'soledad' ); ?></strong> : &nbsp;&nbsp;
									<strong><?php echo $license_data['bount_time']; ?></strong><br>
								<?php } ?>
								<?php
								if ( isset( $license_data['purchase_code'] ) && $license_data['purchase_code'] ) {
									$purchased_code = $license_data['purchase_code'];
									$code_len       = strlen( $purchased_code );
									$code_display   = substr( $purchased_code, 0, 8 ) . str_repeat( '*', $code_len - 16 ) . substr( $purchased_code, $code_len - 8, 8 );
									?>
									<strong style="display: inline-block; margin-bottom: 8px; min-width: 140px;"><?php esc_html_e( 'Item Purchase Code', 'soledad' ); ?></strong> : &nbsp;&nbsp;
									<strong><?php echo $code_display; ?></strong><br>
								<?php } ?>
							</p>
							<h3><?php esc_html_e( 'Revoke License', 'soledad' ); ?></h3>
							<p class="penci-license-detail-desc" style="font-size: 15px;">
								<span style="color: #ff0000; font-weight: bold; font-size: 17px;"><?php _e( 'Note Important: ', 'soledad' ); ?></span><?php _e( 'If you want to use the license on another domain, please revoke the license for this website by clicking the button below:', 'soledad' ); ?>
								<br>
								<a href="
								<?php
								echo admin_url( 'admin.php?page=soledad_dashboard_welcome&penci_revoke_license=confirm&revoke_none=' );
								echo wp_create_nonce( 'revoke_license' );
								?>
								"
									style="padding: 10px 20px; display: inline-block; line-height: 1; margin-top: 15px; background: #fd6f64; color: #fff;"
									onclick="return confirm('Are you sure?');"><?php esc_html_e( 'Revoke License', 'soledad' ); ?></a>
							</p>
							<h3><?php esc_html_e( 'Are you new with Soledad?', 'soledad' ); ?></h3>
							<p class="penci-license-detail-desc" style="font-size: 15px;">
								
								<a href="<?php echo admin_url('?page=soledad_dashboard_welcome'); ?>" style="padding: 10px 20px;margin-right: 8px; display: inline-block; line-height: 1; background: #22b1b1; color: #fff;">
									<?php esc_html_e( 'Getting Started', 'soledad' ); ?>
								</a>
								<a target="_blank" href="https://soledad.pencidesign.net/soledad-document/" style="padding: 10px 20px;margin-right: 8px; display: inline-block; line-height: 1; background: #c20e00; color: #fff;">
									<?php esc_html_e( 'View Documentation', 'soledad' ); ?>
								</a>
								<a href="<?php echo admin_url('themes.php?page=tgmpa-install-plugins'); ?>" style="padding: 10px 20px; display: inline-block; line-height: 1;background: #001fc8; color: #fff;">
									<?php esc_html_e( 'Install Recommend Plugins', 'soledad' ); ?>
								</a>
							</p>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
		}

		public function new_update_page() {
			$new_version = penci_is_new_update( 'version' );
			?>
			<div class="wrap about-wrap penci-about-wrap penci-active-theme penci-dashboard-wapper">
				<?php include PENCI_SOLEDAD_DIR . '/inc/dashboard/sections/welcome.php'; ?>
				<h2 class="nav-tab-wrapper">
					<a href="<?php echo admin_url( 'admin.php?page=soledad_dashboard_welcome' ); ?>"
						class="nav-tab"><?php esc_html_e( 'Getting started', 'soledad' ); ?></a>
					<a href="<?php echo admin_url( 'customize.php' ); ?>"
						class="nav-tab"><?php esc_html_e( 'Customize Style', 'soledad' ); ?></a>
					<a href="<?php echo admin_url( 'admin.php?page=soledad_custom_fonts' ); ?>"
						class="nav-tab"><?php esc_html_e( 'Custom Fonts', 'soledad' ); ?></a>
					<a href="<?php echo admin_url( 'admin.php?page=soledad_theme_license' ); ?>"
						class="nav-tab"><?php esc_html_e( 'Theme License', 'soledad' ); ?></a>
					<a href="<?php echo admin_url( 'admin.php?page=soledad_new_update' ); ?>"
						class="nav-tab nav-tab-active"><?php esc_html_e( 'New Update', 'soledad' ); ?></a>
					<a href="https://pencidesign.ticksy.com/" target="_blank"
						class="nav-tab"><?php esc_html_e( 'Support Forum', 'soledad' ); ?></a>
				</h2>
				<div class="penci-activate-wrap gt-tab-pane gt-is-active">
					<div class="penci-activate-envato-code">
						<div class="penci-activate-code-title"><?php esc_html_e( 'Update Soledad Theme', 'soledad' ); ?></div>
						<p class="penci-license-detail-desc">
							<?php esc_html_e( 'A new version of Soledad theme just released - Soledad Theme version ', 'soledad' ); ?><?php echo $new_version; ?>
							<br>
							<?php esc_html_e( 'Please update the theme to get the latest version.', 'soledad' ); ?>
						</p>
						<p class="penci-license-detail-desc">
							<?php _e( 'To enable Automatic Theme Updates, please check <a style="text-decoration: underline; color: #0043ff;" href="https://pencidesign.ticksy.com/article/15633/" target="_blank">this guide</a>', 'soledad' ); ?>
						</p>
						<p class="penci-license-detail-desc">
							<?php _e( 'You can click <a style="text-decoration: underline; color: #0043ff;" href="https://themeforest.net/item/soledad-multiconcept-blogmagazine-wp-theme/12945398#item-description__update-changelog" target="_blank">here</a> to check what\'s new in the newest version also.', 'soledad' ); ?>
						</p>
					</div>
				</div>
			</div>
			<?php
		}

		public function do_check_envato_code() {
			$code    = isset( $_POST['code'] ) ? $_POST['code'] : '';
			$code    = trim( $code );
			$domain  = get_home_url( '/' );
			$item_id = 12945398;

			$req = wp_remote_post(
				'https://license.pencidesign.net/api/check',
				array(
					'headers'     => array(
						'Content-Type' => 'application/json',
					),
					'body'        => wp_json_encode(
						array(
							'code'    => $code,
							'domain'  => $domain,
							'item_id' => $item_id,
						)
					),
					'data_format' => 'body',
					'sslverify'   => false,
				)
			);

			$body = wp_remote_retrieve_body( $req );
			$res  = json_decode( $body );

			if ( ! empty( $res ) && $res->status === 'success' ) {
				$data                        = $res->data;
				$array_data                  = array();
				$array_data['purchase_code'] = $code;
				$time_now                    = strtotime( '+30 days' );
				if ( $time_now ) {
					$array_data['time_notice'] = $time_now;
				}
				if ( isset( $data->supported_until ) ) {
					$date                     = new DateTime( $data->supported_until );
					$support_to               = $date->format( 'Y-m-d H:i:s' );
					$array_data['support_to'] = $support_to; /* Cover to Unix timestamps use strtotime() */
				}

				if ( isset( $data->sold_at ) ) {
					$boughtdate               = new DateTime( $data->sold_at );
					$bount_time               = $boughtdate->format( 'Y-m-d H:i:s' );
					$array_data['bount_time'] = $bount_time; /* Cover to Unix timestamps use strtotime() */
				}

				if ( isset( $data->buyer ) ) {
					$array_data['buyer'] = $data->buyer;
				}

				if ( isset( $data->purchase_count ) ) {
					$array_data['purchase_count'] = $data->purchase_count;
				}

				if ( isset( $data->amount ) ) {
					$array_data['amount'] = $data->amount;
				}

				update_option( strrev( 'kcehc_etadilav_icnep' ), strtotime( '+30 days' ) );
				update_option( strrev( 'detavitca_si_dadelos_icnep' ), 1 );
				update_option( strrev( 'atad_desahcrup_dadelos_icnep' ), $array_data );
				update_option( strrev( 'mc_sdaol_icnep' ), 'load' );
				wp_send_json_success( array( 'success' => true ) );
			} else {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $res->message,
					)
				);
			}
		}

		public function theme_purchase_exists( $token, $page = '' ) {
			$list_themes = $this->get_list_theme_purchase( $token );

			if ( ! $list_themes ) {
				return false;
			}

			foreach ( $list_themes as $theme ) {

				if ( isset( $theme['id'] ) && '12945398' == $theme['id'] ) {
					return true;
				}
			}

			if ( 100 === count( $list_themes ) ) {

				if ( ! $page ) {
					$page = 2;
				} else {
					$page = $page + 1;
				}

				$page = ( ! $page ) ? 2 : $page + 1;

				return $this->theme_purchase_exists( $token, $page );
			}

			return false;
		}

		public function get_list_theme_purchase( $token, $page = '' ) {
			$themes = array();
			$url    = 'https://api.envato.com/v3/market/buyer/list-purchases?filter_by=wordpress-themes' . ( $page ? '&page=' . $page : '' );

			$response_themes = wp_remote_get(
				$url,
				array(
					'headers'      => array(
						'Authorization' => 'Bearer ' . $token,
						'User-Agent'    => 'Purchase code verification script',
					),
					'timeout'      => 20,
					'headers_data' => false,
					'sslverify'    => false,
				)
			);
			$response_themes = json_decode( wp_remote_retrieve_body( $response_themes ), true );

			if ( ! is_wp_error( $response_themes ) && isset( $response_themes['results'] ) ) {
				foreach ( (array) $response_themes['results'] as $theme ) {
					$themes[] = array(
						'id'         => isset( $theme['item']['id'] ) ? $theme['item']['id'] : '',
						'name'       => isset( $theme['item']['wordpress_theme_metadata']['theme_name'] ) ? $theme['item']['wordpress_theme_metadata']['theme_name'] : '',
						'author'     => isset( $theme['item']['wordpress_theme_metadata']['author_name'] ) ? $theme['item']['wordpress_theme_metadata']['author_name'] : '',
						'url'        => isset( $theme['item']['url'] ) ? $theme['item']['url'] : '',
						'author_url' => isset( $theme['item']['author_url'] ) ? $theme['item']['author_url'] : '',
					);
				}
			}

			return $themes;
		}
	}
}

new Penci_Require_Active();
