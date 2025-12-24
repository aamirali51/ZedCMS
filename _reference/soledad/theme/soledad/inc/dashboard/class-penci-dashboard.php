<?php
/**
 * Add theme dashboard page
 *
 * @package Soledad
 */

/**
 * Dashboard class.
 */
class Penci_Soledad_Dashboard {


	private static $instance;

	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	public function __construct() {
		require_once __DIR__ . '/inc/custom_fonts.php';
		require_once __DIR__ . '/inc/social_order.php';
		require_once __DIR__ . '/inc/require-activation.php';
		require_once __DIR__ . '/inc/white-label.php';
		require_once __DIR__ . '/inc/custom-fonts-type.php';
		require_once __DIR__ . '/inc/patcher.php';

		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
			add_action( 'admin_bar_menu', array( $this, 'add_bar_menu' ), 999 );
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'redirect' ) );
		add_action( 'admin_notices', array( $this, 'promo_notice' ) );
		add_action( 'admin_notices', array( $this, 'penci_update_notice' ) );
		add_filter( 'upload_mimes', array( $this, 'custom_mime_types' ) );
		add_action( 'wp_ajax_admin_dimiss_license_notice', array( $this, 'admin_dimiss_license_notice' ) );
		add_action( 'wp_ajax_penci_update_option', array( $this, 'penci_update_option' ) );
		add_filter( 'admin_body_class', array( $this, 'add_body_classes' ), 100 );
	}

	public function add_body_classes( $classes ) {

		$installations = Soledad_Theme_Installations::get_instance();

		if ( $installations->is_setup() && 'done' !== get_option( 'soledad_setup_status' ) ) {
			$classes .= ' soledad-setup-wizard-page';
		}

		return $classes;
	}

	public function penci_update_option() {
		check_ajax_referer( 'ajax-nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Permission denied.' ) );
		}
		$option_name = isset( $_POST['option_name'] ) ? sanitize_text_field( wp_unslash( $_POST['option_name'] ) ) : '';
		$option_val  = isset( $_POST['option_val'] ) ? wp_unslash( $_POST['option_val'] ) : '';

		if ( $option_name && $option_val ) {
			update_option( $option_name, $option_val );
			wp_send_json_success( array( 'message' => 'Option updated successfully.' ) );
		} else {
			wp_send_json_error( array( 'message' => 'Invalid option name.' ) );
		}
	}

	public function promo_notice() {

		$penci_note   = isset( $_GET['penci-dismis'] ) ? $_GET['penci-dismis'] : '';
		$dismis_promo = get_transient( 'penci_dismis_promo' );

		
		if ( 'penci_dismiss_promonotices' == $penci_note ) {
			set_transient( 'penci_dismis_promo', 'yes', 86400 );
		}

		if ( penci_is_new_promotion() && ! $dismis_promo ) {

			$data_pro = penci_is_new_promotion( true );
			if ( !empty( $data_pro ) ){
			?>
			<div class="notice pc-promo-notice">
				<a target="_blank" href="<?php echo $data_pro['link'];?>"><img src="<?php echo $data_pro['url'];?>" alt=""></a>
				<a class="pc-promo-notice-dismiss" href="<?php echo admin_url( '?penci-dismis=penci_dismiss_promonotices' ); ?>">×</a>
			</div>
			<?php
			}
		}
	}

	public function custom_mime_types( $mime_types ) {
		$mime_types['woff'] = 'application/x-font-woff';
		$mime_types['svg']  = 'image/svg+xml';

		return $mime_types;
	}

	public function admin_dimiss_license_notice() {
		update_option( 'penci_hide_license_notice', true );
		wp_send_json_success();
		wp_die();
	}

	/**
	 * Add theme dashboard page.
	 */
	public function add_menu() {

		$wel_page_title      = $this->get_wel_page_title();
		$wel_page_title_html = $wel_page_title;
		if ( penci_is_new_update() ) {
			$wel_page_title_html = $wel_page_title . ' <span class="update-plugins"><span class="update-count">Update</span></span>';
		}
		add_menu_page(
			$wel_page_title,
			$wel_page_title_html,
			'manage_options',
			'soledad_dashboard_welcome',
			array(
				$this,
				'dashboard_welcome',
			),
			null,
			3
		);
		add_submenu_page(
			'soledad_dashboard_welcome',
			esc_html__( 'Custom Fonts', 'soledad' ),
			esc_html__( 'Custom Fonts', 'soledad' ),
			'manage_options',
			'edit.php?post_type=penci_cfonts'
		);

		$this->replace_text_submenu();
	}

	public function get_wel_page_title() {
		$wel_page_title = get_theme_mod( 'admin_wel_page_title' );

		return $wel_page_title && get_theme_mod( 'activate_white_label' ) ? $wel_page_title : 'Soledad';
	}

	public function replace_text_submenu() {
		global $submenu;
		$submenu['soledad_dashboard_welcome'][0][0] = esc_html__( 'Welcome', 'soledad' );
	}

	function penci_get_option( $key = null, $default = false ) {
		static $data;

		$data = get_option( 'penci_soledad_options' );

		if ( empty( $data ) ) {
			return array();
		}

		if ( $key === null ) {
			return $data;
		}

		if ( isset( $data[ $key ] ) ) {
			return $data[ $key ];
		}

		return get_option( $key, $default );
	}

	public function add_bar_menu() {
		global $wp_admin_bar;
		if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
			return;
		}
		$wp_admin_bar->add_menu(
			array(
				'parent' => 'site-name',
				'id'     => 'soledad-dashboard',
				'title'  => $this->get_wel_page_title(),
				'href'   => admin_url( 'admin.php?page=soledad_dashboard_welcome' ),
			)
		);
	}

	/**
	 * Show dashboard page.
	 */
	public function dashboard_welcome() {
		$wizard       = Soledad_Theme_Installations::get_instance();
		if ( $wizard->is_setup() && 'done' !== get_option( 'soledad_setup_status' ) ) {
			$wizard->setup_wizard_template();
		} else {
		?>
		<div class="wrap about-wrap penci-about-wrap">
			<?php include PENCI_SOLEDAD_DIR . '/inc/dashboard/sections/welcome.php'; ?>
			<?php include PENCI_SOLEDAD_DIR . '/inc/dashboard/sections/getting-started.php'; ?>
		</div>
		<?php
		}
	}

	/**
	 * Enqueue scripts for dashboard page.
	 *
	 * @param string $hook Page hook.
	 */
	public function enqueue_scripts( $hook ) {

		$ver = current_time( 'timestamp' );

		wp_enqueue_media();
		wp_enqueue_style( 'dashboard-style', PENCI_SOLEDAD_URL . '/inc/dashboard/css/dashboard-style.css', array(), PENCI_SOLEDAD_VERSION );
		wp_enqueue_script( 'soledad-button-script', PENCI_SOLEDAD_URL . '/inc/woocommerce/js/jquery-grid-picker.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION );
		wp_enqueue_script( 'soledad-dashboard-script', PENCI_SOLEDAD_URL . '/inc/dashboard/js/script.js', array( 'jquery' ), $ver );

		$localize_script = array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'ajax-nonce' ),
			'patcher_nonce'   => wp_create_nonce( 'patcher_nonce' ),
			'domain'  => get_home_url( '/' ),
		);
		wp_localize_script( 'soledad-dashboard-script', 'PENCIDASHBOARD', $localize_script );
	}

	/**
	 * Add notice message when a new version released
	 *
	 * @param none.
	 */
	public function penci_update_notice() {
		if ( penci_is_new_update() ) {
			$penci_note     = isset( $_GET['penci-dismis'] ) ? $_GET['penci-dismis'] : '';
			$latest_version = penci_is_new_update( 'version' );
			$dismis_version = get_transient( 'penci_dismis_update_version' );
			if ( ! $dismis_version || version_compare( $latest_version, $dismis_version, '>' ) ) {
				update_option( 'penci_dismiss_update_notices', '' );
			}
			if ( 'penci_dismiss_updatenotices' == $penci_note ) {
				update_option( 'penci_dismiss_update_notices', 'yes' );
				set_transient( 'penci_dismis_update_version', $latest_version, 2592000 );
			}
			$penci_dismis_notes = get_option( 'penci_dismiss_update_notices', '' );

			if ( 'yes' != $penci_dismis_notes ) {
				?>
				<div class="notice pc-update-notice">
					<p class="pc-updaten-title">New Update Available!</p>
					<p>There is a new version of the theme available! Update your theme to get new features and bug
						fixes.</p>
					<p>You can check <a href="https://pencidesign.ticksy.com/article/15633/" target="_blank">this
							guide</a> to know how to enable updates with one click.</p>
					<p>You can click <a
								href="https://themeforest.net/item/soledad-multiconcept-blogmagazine-wp-theme/12945398#item-description__update-changelog"
								target="_blank">here</a> to check what's new in the newest version also.</p>
					<p><a style="text-decoration: none; opacity: 0.8;"
							href="<?php echo admin_url( '?penci-dismis=penci_dismiss_updatenotices' ); ?>">Dismiss this
							update.</a></p>
				</div>
				<?php
			}
		}
	}

	/**
	 * Redirect to dashboard page after theme activation.
	 */
	public function redirect() {
		global $pagenow;

		$install_wizzard = true;
		$url = admin_url( 'admin.php?page=soledad_dashboard_welcome' );

		if ( 'done' === get_option( 'soledad_setup_status' ) ) {
			$install_wizzard = false;
		}

		$args = array(
			'page' => 'soledad_dashboard_welcome',
			'tab'  => 'wizard',
		);

		if ( $install_wizzard ) {
			$url = esc_url_raw( add_query_arg( $args, admin_url( 'admin.php' ) ) );
		}

		if ( is_admin() && isset( $_GET['activated'] ) && 'themes.php' === $pagenow ) {
			wp_safe_redirect( $url );
			exit;
		}
	}
}

if ( ! function_exists( 'penci_is_plugin_active' ) ) {
	function penci_is_plugin_active( $class, $slug ) {
		return $class->is_plugin_active( $slug );
	}
}

function penci_update_toolbar_link( $wp_admin_bar ) {
	$tgm_instance    = TGM_Plugin_Activation::get_instance();
	$default_plugins = array(
		'penci-shortcodes',
		'vafpress-post-formats-ui-develop',
		'penci-soledad-slider',
		'penci-portfolio',
		'penci-recipe',
		'penci-review',
		'penci-soledad-demo-importer',
		'penci-soledad-amp',
	);

	foreach ( $tgm_instance->plugins as $id => $detail ) {
		if ( in_array( $id, $default_plugins ) && penci_is_plugin_active( $tgm_instance, $id ) && $tgm_instance->does_plugin_require_update( $id ) ) {
			$penci_icon = '<svg style="position: relative; top:4px;margin-right: 5px;" version="1.0" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 26.000000 26.000000" preserveAspectRatio="xMidYMid meet">
				<g transform="translate(0.000000,26.000000) scale(0.100000,-0.100000)" fill="#ffffff" stroke="none">
					<path d="M72 202 l-62 -60 0 -66 0 -66 125 0 125 0 0 61 0 61 -63 65 -62 64
				-63 -59z m73 28 c3 -5 -3 -10 -15 -10 -12 0 -18 5 -15 10 3 6 10 10 15 10 5 0
				12 -4 15 -10z m57 -57 c34 -33 36 -38 20 -49 -14 -10 -21 -8 -45 12 -36 31
				-62 30 -93 -1 -21 -21 -28 -23 -44 -13 -19 12 -18 14 17 50 51 52 92 52 145 1z
				m-77 -93 c0 -59 -1 -60 -27 -60 -26 0 -28 3 -28 42 0 24 7 49 17 60 28 32 38
				21 38 -42z m49 44 c10 -9 16 -33 16 -60 0 -40 -2 -44 -25 -44 -24 0 -25 3 -25
				60 0 62 7 71 34 44z m-130 -20 c9 -8 16 -31 16 -50 0 -27 -4 -34 -20 -34 -17
				0 -20 7 -20 50 0 28 2 50 4 50 3 0 12 -7 20 -16z m201 -34 c0 -44 -3 -50 -20
				-50 -18 0 -20 5 -17 38 4 35 17 62 31 62 3 0 6 -22 6 -50z"></path>
					<path d="M90 70 c0 -5 5 -10 10 -10 6 0 10 5 10 10 0 6 -4 10 -10 10 -5 0 -10
				-4 -10 -10z"></path>
				</g>
			</svg>';

			$args = array(
				'id'    => 'penci-update-notice',
				'title' => $penci_icon . ' Soledad - Notifications',
				'href'  => '#',
				'meta'  => array(
					'class' => 'penci-update-notice-button',
					'title' => 'Soledad - Notifications',
				),
			);
			$wp_admin_bar->add_node( $args );

			$args = array(
				'id'     => 'penci-update-notice-link',
				'title'  => 'Some Plugins Need to Update',
				'href'   => admin_url( 'themes.php?page=tgmpa-install-plugins&plugin_status=update' ),
				'parent' => 'penci-update-notice',
				'meta'   => array(
					'class' => 'penci-update-notice-btn',
					'title' => 'Some Plugins Need to Update',
				),
			);
			$wp_admin_bar->add_node( $args );
		}
	}
}

add_action( 'admin_bar_menu', 'penci_update_toolbar_link', 50 );
Penci_Soledad_Dashboard::instance();
