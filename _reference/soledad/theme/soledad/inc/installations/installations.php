<?php

class Soledad_Theme_Installations {

	/**
	 * Instance of this static object.
	 *
	 * @var array
	 */
	private static $instances = [];

	/**
	 * Get singleton instance.
	 *
	 * @return object Current object instance.
	 * @since 1.0.0
	 *
	 */
	public static function get_instance() {
		$subclass = static::class;
		if ( ! isset( self::$instances[ $subclass ] ) ) {
			self::$instances[ $subclass ] = new static();
			self::$instances[ $subclass ]->init();
		}

		return self::$instances[ $subclass ];
	}

	/**
	 * Prevent singleton class clone.
	 *
	 * @since 1.0.0
	 */
	private function __clone() {
	}

	/**
	 * Prevent singleton class initialization.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
	}

	/**
	 * Available pages.
	 *
	 * @var array
	 */
	public $available_pages = array();

	/**
	 * Constructor.
	 */
	public function init() {

		add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
		add_action( 'wp_ajax_soledad_deactivate_plugin', array( $this, 'ajax_deactivate_plugin' ) );
		add_action( 'wp_ajax_soledad_check_plugins', array( $this, 'ajax_check_plugin' ) );
		add_action( 'wp_ajax_soledad_install_child_theme', array( $this, 'install_child_theme' ) );

		if ( isset( $_GET['skip_setup'] ) ) {
			update_option( 'soledad_setup_status', 'done', false );
		}

		if ( 'done' !== get_option( 'soledad_setup_status' ) ) { // phpcs:ignore
			add_action( 'admin_init', array( $this, 'prevent_plugins_redirect' ), 1 );
			do_action( 'soledad_setup_wizard' );
		}

		if ( defined( 'DOING_AJAX' ) || isset( $_GET['page'] ) && ( 'soledad_dashboard_welcome' === $_GET['page'] || 'tgmpa-install-plugins' === $_GET['page'] ) ) {
			add_action( 'admin_init', array( $this, 'prevent_plugins_redirect' ), 1 );
		}
		
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	/**
	 * Setup available pages.
	 *
	 * @return void
	 */
	public function set_available_pages() {
		$this->available_pages = array(
			'welcome'     => esc_html__( 'Welcome', 'soledad' ),
			'activation'  => esc_html__( 'Activation', 'soledad' ),
			'child-theme' => esc_html__( 'Child Theme', 'soledad' ),
			'plugins'     => esc_html__( 'Plugins', 'soledad' ),
			'demo'        => esc_html__( 'Install Demo', 'soledad' ),
			'done'        => esc_html__( 'Complete', 'soledad' ),
		);
	}

	public function register_scripts() {
		wp_register_script( 'penci-installations', PENCI_SOLEDAD_URL . '/inc/installations/assets/installations.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_style( 'penci-installations', PENCI_SOLEDAD_URL . '/inc/installations/assets/style.css', array(), PENCI_SOLEDAD_VERSION );

		$localize_script = array(
			'ajaxUrl'                   => admin_url( 'admin-ajax.php' ),
			'nonce'                     => wp_create_nonce( 'soledad_deactivate_plugin_nonce' ),
			'deactivate_plugin_nonce'   => wp_create_nonce( 'soledad_deactivate_plugin_nonce' ),
			'check_plugins_nonce'       => wp_create_nonce( 'soledad_check_plugins_nonce' ),
			'install_child_theme_nonce' => wp_create_nonce( 'soledad_install_child_theme_nonce' ),
			'demononce'                 => wp_create_nonce( 'penci_soledad_demo_import' ),
			'activate'                  => __( 'Activate', 'soledad' ),
			'deactivate'                => __( 'Deactivate', 'soledad' ),
			'done_url'                  => admin_url( 'admin.php?page=soledad_dashboard_welcome&tab=wizard&step=done' ),
		);

		wp_localize_script( 'penci-installations', 'soledadInstallations', $localize_script );
	}

	/**
	 * Prevent plugins redirect.
	 */
	public function prevent_plugins_redirect() {
		delete_transient( '_revslider_welcome_screen_activation_redirect' );
		delete_transient( '_vc_page_welcome_redirect' );
		delete_transient( 'elementor_activation_redirect' );
		add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
		remove_action( 'admin_menu', 'vc_menu_page_build' );
		remove_action( 'network_admin_menu', 'vc_network_menu_page_build' );
		remove_action( 'vc_activation_hook', 'vc_page_welcome_set_redirect' );
		remove_action( 'admin_init', 'vc_page_welcome_redirect' );
	}

	/**
	 * Template.
	 */
	public function setup_wizard_template() {
		if ( 'done' === get_option( 'soledad_setup_status' ) ) {
			return;
		}

		$this->set_available_pages();

		wp_enqueue_script( 'penci-installations' );
		wp_enqueue_style( 'penci-installations' );

		$page = 'welcome';

		if ( isset( $_GET['step'] ) && ! empty( $_GET['step'] ) ) { // phpcs:ignore
			$page = trim( wp_unslash( $_GET['step'] ) ); // phpcs:ignore
		}

		$this->show_page( $page );
	}

	/**
	 * Show page.
	 *
	 * @param string $name Template file name.
	 */
	public function show_page( $name ) {
		?>
        <div class="soledad-setup-wizard-wrap soledad-theme-style">
            <div class="soledad-setup-wizard soledad-<?php echo esc_attr( $name ); ?>">
                <div class="soledad-wizard-nav">
					<?php $this->show_part( 'sidebar' ); ?>
                </div>

                <div class="soledad-wizard-content">
					<?php $this->show_part( $name ); ?>
                </div>
            </div>
        </div>
		<?php
	}

	/**
	 * Get previous page button.
	 *
	 * @param string $page Page slug.
	 */
	public function get_prev_button( $page ) {
		?>
        <a class="soledad-inline-btn soledad-prev" href="<?php echo esc_url( $this->get_page_url( $page ) ); ?>">
			<?php esc_html_e( 'Previous step', 'soledad' ); ?>
        </a>
		<?php
	}

	/**
	 * Get previous page button.
	 *
	 * @param string $page Page slug.
	 * @param string $builder Builder name.
	 * @param boolean $disabled Is button disabled.
	 */
	public function get_next_button( $page, $builder = '', $disabled = false ) {
		$classes = '';
		$url     = $this->get_page_url( $page );

		if ( $builder ) {
			$url     .= '&soledad_builder=' . $builder;
			$classes .= ' soledad-' . $builder;
		}

		if ( 'elementor' === $builder ) {
			$classes .= ' soledad-shown';
		} elseif ( 'gutenberg' === $builder ) {
			$classes .= ' soledad-hidden';
		} elseif ( 'wpb' === $builder ) {
			$classes .= ' soledad-hidden';
		}

		if ( $disabled ) {
			$classes .= ' soledad-disabled';
		}

		?>
        <a class="soledad-btn soledad-color-primary soledad-next<?php echo esc_attr( $classes ); ?>"
           href="<?php echo esc_url( $url ); ?>">
			<?php esc_html_e( 'Next step', 'soledad' ); ?>
        </a>
		<?php
	}

	/**
	 * Get skip page button.
	 *
	 * @param string $page Page slug.
	 */
	public function get_skip_button( $page ) {
		?>
        <a class="soledad-inline-btn soledad-color-primary soledad-skip"
           href="<?php echo esc_url( $this->get_page_url( $page ) ); ?>">
			<?php esc_html_e( 'Skip', 'soledad' ); ?>
        </a>
		<?php
	}

	/**
	 * Show template part.
	 *
	 * @param string $name Template file name.
	 */
	public function show_part( $name ) {
		include_once get_parent_theme_file_path( '/inc/installations/templates/' . $name . '.php' );
	}

	/**
	 * Is active page.
	 *
	 * @param string $name Page name.
	 */
	public function is_active_page( $name ) {
		$page = 'welcome';

		if ( isset( $_GET['step'] ) && ! empty( $_GET['step'] ) ) { // phpcs:ignore
			$page = trim( wp_unslash( $_GET['step'] ) ); // phpcs:ignore
		}

		return $name === $page; // phpcs:ignore
	}

	/**
	 * Get page url.
	 *
	 * @param string $name Page name.
	 */
	public function get_page_url( $name ) {
		return admin_url( 'admin.php?page=soledad_dashboard_welcome&tab=wizard&step=' . $name ); // phpcs:ignore
	}

	/**
	 * Get image url.
	 *
	 * @param string $name Image name.
	 */
	public function get_image_url( $name ) {
		return PENCI_SOLEDAD_DIR . '/inc/installations/images/' . $name;
	}

	/**
	 * Is setup wizard.
	 *
	 * @return bool
	 */
	public function is_setup() {
		return isset( $_GET['tab'] ) && 'wizard' === $_GET['tab']; //phpcs:ignore
	}

	public function ajax_deactivate_plugin() {
		check_ajax_referer( 'soledad_deactivate_plugin_nonce', 'security' );

		$plugins = $this->get_plugins();

		if ( ! $plugins ) {
			wp_send_json(
				array(
					'message' => esc_html__( 'Plugins list is empty.', 'soledad' ),
					'status'  => 'error',
				)
			);
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(
				array(
					'message' => esc_html__( 'You not have access.', 'soledad' ),
					'status'  => 'error',
				)
			);
		}

		if ( is_multisite() && is_plugin_active_for_network( $plugins[ $_POST['penci_plugin'] ]['file_path'] ) ) { // phpcs:ignore
			wp_send_json(
				array(
					'message' => esc_html__( 'You cannot deactivate the plugin on a multisite.', 'soledad' ),
					'status'  => 'error',
				)
			);
		}

		if ( isset( $_POST['penci_plugin'] ) && is_plugin_active( $plugins[ $_POST['penci_plugin'] ]['file_path'] ) ) { // phpcs:ignore
			deactivate_plugins( $plugins[ $_POST['penci_plugin'] ]['file_path'] ); // phpcs:ignore
		}

		wp_send_json(
			array(
				'data'   => $plugins[ $_POST['penci_plugin'] ]['status'], // phpcs:ignore
				'status' => 'success',
			)
		);
	}

	public function get_plugins() {
		$tgmpa             = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$tgmpa_plugins     = $tgmpa->plugins;
		$installed_plugins = get_plugins();

		$plugins = array();

		foreach ( $tgmpa_plugins as $slug => $plugin ) {
			$plugins[ $slug ]                   = $plugin;
			$plugins[ $slug ]['activate_url']   = $this->get_action_url( $slug, 'activate' );
			$plugins[ $slug ]['update_url']     = $this->get_action_url( $slug, 'update' );
			$plugins[ $slug ]['deactivate_url'] = '';

			if ( isset( $installed_plugins[ $plugin['file_path'] ]['Version'] ) ) {
				$plugins[ $slug ]['version'] = $installed_plugins[ $plugin['file_path'] ]['Version'];
			}

			if ( ! $tgmpa->is_plugin_installed( $slug ) ) {
				$plugins[ $slug ]['status'] = 'install';
			} else {
				if ( $tgmpa->does_plugin_have_update( $slug ) ) {
					$plugins[ $slug ]['status'] = 'update';
				} elseif ( $tgmpa->can_plugin_activate( $slug ) ) {
					$plugins[ $slug ]['status'] = 'activate';
				} elseif ( $tgmpa->does_plugin_require_update( $slug ) ) {
					$plugins[ $slug ]['status'] = 'require_update';
				} else {
					$plugins[ $slug ]['status'] = 'deactivate';
				}
			}
		}


		$order = array(
			'penci-shortcodes',
			'elementor',
			'penci-soledad-demo-importer',
			'woocommerce',
			'contact-form-7',
			'mailchimp-for-wp',
		);

		$filtered_plugins = array();
		foreach ( $order as $plugin_slug ) {
			if ( isset( $plugins[ $plugin_slug ] ) ) {
				$filtered_plugins[ $plugin_slug ] = $plugins[ $plugin_slug ];
			}
		}

		$plugins = $filtered_plugins;

		return $plugins;
	}

	/**
	 * Get required plugins to activate.
	 *
	 * @since 1.0.0
	 */
	public function get_required_plugins_to_activate() {
		$plugins = $this->get_plugins();
		$tgmpa   = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$output  = array();

		foreach ( $plugins as $slug => $plugin ) {
			if ( ! $tgmpa->is_plugin_active( $slug ) && $plugin['required'] ) {
				$output[] = $plugin;
			}
		}

		return $output;
	}

	/**
	 * Is all plugins activated.
	 *
	 * @since 1.0.0
	 */
	public function is_all_activated() {
		$plugins = $this->get_plugins();
		$tgmpa   = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$output  = array();

		foreach ( $plugins as $slug => $plugin ) {
			if ( ! $tgmpa->is_plugin_active( $slug ) && $tgmpa->can_plugin_activate( $slug ) ) {
				$output[] = $plugin;
			}
		}

		return count( $output ) === 0;
	}

	/**
	 * Get required plugins to activate.
	 *
	 * @param string $slug Slug.
	 * @param string $status Status.
	 *
	 * @return string
	 * @since 1.0.0
	 *
	 */
	public function get_action_url( $slug, $status ) {
		return wp_nonce_url(
			add_query_arg(
				array(
					'plugin'           => rawurlencode( $slug ),
					'tgmpa-' . $status => $status . '-plugin',
				),
				admin_url( 'themes.php?page=tgmpa-install-plugins' )
			),
			'tgmpa-' . $status,
			'tgmpa-nonce'
		);
	}

	/**
	 * Get required plugins to activate.
	 *
	 * @param string $status Status.
	 *
	 * @return string
	 * @since 1.0.0
	 *
	 */
	public function get_action_text( $status ) {
		$text = esc_html__( 'Deactivate', 'soledad' );

		if ( 'install' === $status ) {
			$text = esc_html__( 'Install', 'soledad' );
		} elseif ( 'update' === $status ) {
			$text = esc_html__( 'Update', 'soledad' );
		} elseif ( 'activate' === $status ) {
			$text = esc_html__( 'Activate', 'soledad' );
		}

		return $text;
	}

	public function ajax_check_plugin() {
		check_ajax_referer( 'soledad_check_plugins_nonce', 'security' );

		$plugins = $this->get_plugins();

		if ( ! $plugins ) {
			wp_send_json(
				array(
					'message' => esc_html__( 'Plugins list is empty.', 'soledad' ),
					'status'  => 'error',
				)
			);
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(
				array(
					'message' => esc_html__( 'You not have access.', 'soledad' ),
					'status'  => 'error',
				)
			);
		}

		wp_send_json(
			array(
				'data'   => array(
					'status'           => $plugins[ $_POST['penci_plugin'] ]['status'], // phpcs:ignore
					'version'          => $plugins[ $_POST['penci_plugin'] ]['version'], // phpcs:ignore
					'required_plugins' => count( $this->get_required_plugins_to_activate() ) > 0 ? 'has_required' : 'no',
					'is_all_activated' => $this->is_all_activated() ? 'yes' : 'no',
				),
				'status' => 'success',
			)
		);
	}

	public function install_child_theme() {
		check_ajax_referer( 'soledad_install_child_theme_nonce', 'security' );
		$parent_theme_name = 'soledad';
		$child_theme_name  = $parent_theme_name . '-child';
		$theme_root        = WP_CONTENT_DIR . '/themes';
		$child_theme_path  = $theme_root . '/' . $child_theme_name;

		$child_theme_zip = 'https://s3.amazonaws.com/soledad-plugins/soledad-child.zip';

		if ( ! file_exists( $child_theme_path ) ) {
			$dir = wp_mkdir_p( $child_theme_path );
			if ( ! $dir ) {
				echo wp_json_encode( array( 'status' => 'dir_not_exists' ) );
				die();
			}

			// Download the child theme zip file
			$response = wp_remote_get( $child_theme_zip, array( 'timeout' => 60 ) );
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				echo wp_json_encode( array( 'status' => 'download_error' ) );
				die();
			}
			$zip_file = $theme_root . '/soledad-child.zip';
			$zip_data = wp_remote_retrieve_body( $response );
			if ( ! file_put_contents( $zip_file, $zip_data ) ) {
				echo wp_json_encode( array( 'status' => 'file_error' ) );
				die();
			}

			// Unzip the child theme
			$unzip_result = unzip_file( $zip_file, $theme_root );
			if ( is_wp_error( $unzip_result ) ) {
				echo wp_json_encode( array( 'status' => 'unzip_error' ) );
				die();
			}

			// Remove the zip file after unzipping
			unlink( $zip_file );
			// Activate the child theme

			$child_theme = wp_get_theme( $child_theme_name );
			if ( ! $child_theme->exists() ) {
				echo wp_json_encode( array( 'status' => 'theme_not_exists' ) );
				die();
			}

			// Switch to the child theme
			$parent_theme = wp_get_theme( $parent_theme_name );
			if ( ! $parent_theme->exists() ) {
				echo wp_json_encode( array( 'status' => 'parent_theme_not_exists' ) );
				die();
			}

			// Check if the child theme is already active
			if ( is_child_theme() && get_template() === $parent_theme_name ) {
				echo wp_json_encode( array( 'status' => 'already_active' ) );
				die();
			}

			// Update allowed themes for multisite
			if ( is_multisite() ) {
				$allowed_themes = get_site_option( 'allowedthemes' );
				if ( ! is_array( $allowed_themes ) ) {
					$allowed_themes = array();
				}
				$allowed_themes[ $child_theme_name ] = true;
				update_site_option( 'allowedthemes', $allowed_themes );
			}
		}

		if ( $parent_theme_name !== $child_theme_name ) {
			switch_theme( $child_theme_name );
			echo wp_json_encode( array( 'status' => 'success' ) );
			die();
		}
	}

	public function tgmpa_load() {
		return is_admin() || current_user_can( 'install_themes' );
	}
}

Soledad_Theme_Installations::get_instance();
