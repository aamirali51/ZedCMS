<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! class_exists( 'Penci_Patcher' ) ) {
	/**
	 * The main patcher class.
	 */
	class Penci_Patcher {
		/**
		 * Instance of this static object.
		 *
		 * @var array
		 */
		protected static $instance = null;

		/**
		 * Return an instance of this class.
		 * @return    object    A single instance of this class.
		 * @since     1.0.0
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * The uri of the remote server.
		 *
		 * @var string
		 */
		public $remote_server = 'https://soledad-plugins.s3.us-east-1.amazonaws.com/patchers/';

		/**
		 * Version site.
		 *
		 * @var string
		 */
		public $theme_version;

		/**
		 * Process notices.
		 *
		 * @var array
		 */
		public $notices = array();

		/**
		 * Transient name.
		 *
		 * @var string
		 */
		public $transient_name = '';

		/**
		 * Patch ID.
		 *
		 * @var $patcher_id
		 */
		public $patch_id;

		/**
		 * Flag successfully write files.
		 *
		 * @var $successful_write_files
		 */
		private $successful_write_files = true;

		protected function __construct() {

			if ( get_theme_mod( 'penci_disable_patch_update' ) ) {
				return;
			}

			$this->init();
		}

		/**
		 * Register hooks.
		 */
		public function init() {
			$this->theme_version  = PENCI_SOLEDAD_VERSION;
			$this->transient_name = 'penci_patches_map_' . $this->theme_version;
			add_action( 'admin_menu', array( $this, 'add_menu' ), 999 );
			add_action( 'wp_ajax_penci_patch_action', array( $this, 'patch_process' ) );
		}

		public function add_menu() {

			$patches_count = $this->get_count_patches_map();

			add_submenu_page(
				'soledad_dashboard_welcome',
				__( 'Patcher', 'soledad' ),
				__( 'Patcher' . $patches_count, 'soledad' ),
				'manage_options',
				'penci_patcher',
				array(
					$this,
					'render',
				),
				3
			);
		}

		/**
		 * Patch File.
		 *
		 * @param string $patch_id Patch id.
		 */
		public function patch_init( $patch_id ) {
			$this->patch_id = $patch_id;

			$this->apply();
		}

		/**
		 * Patch process.
		 */
		public function patch_process() {
			check_ajax_referer( 'patcher_nonce', 'security' );

			if ( empty( $_GET['id'] ) ) {
				wp_send_json(
					array(
						'message' => esc_html__( 'Empty path ID, please, try again.', 'soledad' ),
						'status'  => 'error',
					)
				);
			}

			$patch_id          = sanitize_text_field( $_GET['id'] ); //phpcs:ignore
			$patches_installed = get_option( 'penci_successfully_installed_patches' );

			if ( isset( $patches_installed[ PENCI_SOLEDAD_VERSION ][ $patch_id ] ) ) {
				wp_send_json(
					array(
						'message' => esc_html__( 'The patch is already applied.', 'soledad' ),
						'status'  => 'success',
					)
				);
			}

			$this->patch_init( $patch_id );
		}

		/**
		 * Get count patches map.
		 *
		 * @return string
		 */
		public function get_count_patches_map() {
			global $pagenow;

			$patches_maps = get_transient( $this->transient_name );

			if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) ) { //phpcs:ignore.
				if ( in_array( $_GET['page'], array(
					'penci_dashboard',
					'penci_theme_settings'
				), true ) ) { //phpcs:ignore.
					$patches_maps = $this->get_patches_maps();
				} else if ( 'penci_patcher' === $_GET['page'] ) { //phpcs:ignore.
					$patches_maps = $this->get_patches_maps_from_server();
				}
			}

			if ( ! $patches_maps || ! is_array( $patches_maps ) ) {
				return '';
			}

			$patches_installed = get_option( 'penci_successfully_installed_patches', array() );

			if ( isset( $patches_installed[ $this->theme_version ] ) ) {
				$patches_maps = array_diff_key( $patches_maps, $patches_installed[ $this->theme_version ] );
			}

			$count = count( $patches_maps );

			if ( 0 === $count ) {
				return '';
			}

			ob_start();
			?>
            <span class="penci-patcher-counter update-plugins count-<?php echo esc_attr( $count ); ?>">
				<span class="patcher-count">
					<?php echo esc_html( $count ); ?>
				</span>
			</span>
			<?php

			return ob_get_clean();
		}

		/**
		 * Interface in admin panel.
		 */
		public function render() {

			if ( isset( $_GET['pcpatchcheck'] ) && $_GET['pcpatchcheck'] == 'now' ) {
				delete_transient( $this->transient_name );
			}

			wp_enqueue_script( 'penci-patcher-scripts', PENCI_SOLEDAD_URL . '/inc/dashboard/js/patcher.js', array(), PENCI_SOLEDAD_VERSION, true );
			wp_localize_script( 'penci-patcher-scripts', 'penci_patch_notice', $this->add_localized_settings() );

			$patches               = $this->get_patches_maps();
			$patch_installed       = get_option( 'penci_successfully_installed_patches' );
			$all_patches_installed = empty( array_diff( array_keys( $patches ), isset( $patch_installed[ $this->theme_version ] ) ? array_keys( $patch_installed[ $this->theme_version ] ) : array() ) );
			$classes               = $patches ? 'has-patches' : 'no-patches';
			?>
            <div class="wrap">
                <h1><?php esc_html_e( 'Patcher', 'soledad' ); ?></h1>
                <div class="penci-box">
                    <div class="penci-box-content <?php echo $classes; ?>">
						<?php if ( $patches ) : ?>
                            <div class="penci-notices-wrapper penci-patches-notice"><?php $this->print_notices(); // Must be in one line. ?></div>

                            <div class="penci-table penci-even penci-patches-wrapper">

                                <div class="penci-table-row-heading penci-patch-item penci-patch-title-wrapper">
                                    <div class="penci-patch-id">
										<?php esc_html_e( 'Patch ID', 'soledad' ); ?>
                                    </div>
                                    <div class="penci-patch-description">
										<?php esc_html_e( 'Description', 'soledad' ); ?>
                                    </div>
                                    <div class="penci-patch-date">
										<?php esc_html_e( 'Date', 'soledad' ); ?>
                                    </div>
                                    <div class="penci-patch-button-wrapper"></div>
                                </div>

								<?php foreach ( $patches as $patch => $patcher ) :
									$patch_id = $patcher['version'];
									?>
									<?php $classes = isset( $patch_installed[ $this->theme_version ][ $patch_id ] ) ? ' penci-applied' : ''; ?>
                                    <div class="penci-table-row penci-patch-item<?php echo esc_attr( $classes ); ?>">
                                        <div class="penci-patch-id">
                                            <span><?php echo esc_html( $patch_id ); ?></span>
                                        </div>
                                        <div class="penci-patch-description">
											<?php echo $patcher['description']; ?>
                                        </div>
                                        <div class="penci-patch-date">
											<?php echo esc_html( $patcher['releaseDate'] ); ?>
                                        </div>
                                        <div class="penci-patch-button-wrapper">
											<?php if ( ! $this->check_filesystem_api() ) : ?>
                                                <a href="<?php echo esc_url( $patcher['patch_link'] ); ?>"
                                                   class="penci-btn penci-color-primary">
													<?php esc_html_e( 'Download', 'soledad' ); ?>
                                                </a>
											<?php else : ?>
                                                <a href="#"
                                                   class="penci-btn penci-color-primary penci-patch-apply penci-i-check"
                                                   data-patches-map='<?php echo wp_json_encode( $patcher['files'] ); ?>'
                                                   data-id="<?php echo esc_html( $patch_id ); ?>">
													<?php esc_html_e( 'Apply', 'soledad' ); ?>
                                                </a>
                                                <span class="penci-patch-label-applied penci-i-check">
											<?php esc_html_e( 'Applied', 'soledad' ); ?>
										</span>
											<?php endif; ?>
                                        </div>
                                    </div>
								<?php endforeach; ?>
                            </div>
						<?php else :
							$url_check = esc_url( 'admin.php?page=penci_patcher&pcpatchcheck=now' );
							?>
                            <div class="penci-empty-patches">
                                <div class="pencti-notice-icon">
                                    <svg fill="#000000" width="800px" height="800px" viewBox="0 0 24 24"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M12,2 C17.5228475,2 22,6.4771525 22,12 C22,17.5228475 17.5228475,22 12,22 C6.4771525,22 2,17.5228475 2,12 C2,6.4771525 6.4771525,2 12,2 Z M12,4 C7.581722,4 4,7.581722 4,12 C4,16.418278 7.581722,20 12,20 C16.418278,20 20,16.418278 20,12 C20,7.581722 16.418278,4 12,4 Z M15.2928932,8.29289322 L10,13.5857864 L8.70710678,12.2928932 C8.31658249,11.9023689 7.68341751,11.9023689 7.29289322,12.2928932 C6.90236893,12.6834175 6.90236893,13.3165825 7.29289322,13.7071068 L9.29289322,15.7071068 C9.68341751,16.0976311 10.3165825,16.0976311 10.7071068,15.7071068 L16.7071068,9.70710678 C17.0976311,9.31658249 17.0976311,8.68341751 16.7071068,8.29289322 C16.3165825,7.90236893 15.6834175,7.90236893 15.2928932,8.29289322 Z"/>
                                    </svg>
                                </div>
                                <p>
									<?php esc_html_e( 'There are no patches found for your theme version.', 'soledad' ); ?>
                                </p>
                                <div class="penci-check-patch">
                                    <a href="<?php echo $url_check; ?>"><?php _e( 'Check now', 'soledad' ); ?></a>
                                </div>
                            </div>
						<?php endif; ?>
                    </div>
					<?php if ( $patches && $this->check_filesystem_api() ) : ?>
                        <div class="penci-box-footer">
                            <div class="penci-box-helper">
								<?php _e( 'We added patcher functionality instead of frequent updates to address bugs more efficiently. Minor bugs affecting only a few sites can be patched without requiring all customers to update. Significant bugs impacting many sites will still prompt an update, with patches disappearing in the next release.', 'soledad' ); ?>
                            </div>
                            <div class="penci-patch-button-wrapper <?php echo $all_patches_installed ? 'penci-applied' : ''; ?>">
                                <a href="#" class="penci-btn penci-color-primary penci-patch-apply-all penci-i-check">
									<?php esc_html_e( 'Apply all', 'soledad' ); ?>
                                </a>
                                <span class="penci-patch-label-applied penci-i-check">
								<?php esc_html_e( 'All applied', 'soledad' ); ?>
							</span>
                            </div>
                        </div>
					<?php endif; ?>
                </div>
            </div>
			<?php
		}

		/**
		 * Print notices.
		 */
		public function print_notices() {
			if ( ! $this->check_filesystem_api() ) {
				$this->notices['warning'] = esc_html__( 'Direct access to theme file is not allowed on your server. You need to download and replace the files manually.', 'soledad' );
			}

			if ( ! $this->notices ) {
				return;
			}

			foreach ( $this->notices as $type => $notice ) {
				$this->print_notice( $notice, $type );
			}
		}

		/**
		 * Print notice.
		 *
		 * @param string $message Message.
		 * @param string $type Type.
		 */
		private function print_notice( $message, $type = 'warning' ) {
			?>
            <div class="penci-notice penci-<?php echo esc_attr( $type ); ?>">
				<?php echo wp_kses( $message, array(
					'h1'     => array(),
					'h2'     => array(),
					'h3'     => array(),
					'h4'     => array(),
					'h5'     => array(),
					'h6'     => array(),
					'pre'    => array(),
					'p'      => array(),
					'br'     => array(),
					'i'      => array(),
					'b'      => array(),
					'u'      => array(),
					'em'     => array(),
					'del'    => array(),
					'a'      => array(
						'href'   => true,
						'class'  => true,
						'target' => true,
						'title'  => true,
						'rel'    => true,
					),
					'strong' => array(),
					'span'   => array(
						'style' => true,
						'class' => true,
					),
					'ol'     => array(),
					'ul'     => array(),
					'li'     => array(),
				) ); ?>
            </div>
			<?php
		}

		/**
		 * Get patches maps.
		 *
		 * @return array
		 */
		public function get_patches_maps() {

			$patches_maps = get_transient( $this->transient_name );

			if ( ! $patches_maps ) {
				$patches_maps = $this->get_patches_maps_from_server();
			}

			if ( ! is_array( $patches_maps ) ) {
				return array();
			}

			return $patches_maps;
		}

		/**
		 * Queries the patches server for a list of patches.
		 *
		 * @return array
		 */
		public function get_patches_maps_from_server() {

			$version    = $this->theme_version;
			$url        = $this->remote_server;
			$subversion = 1;

			$updates = [];
			while ( true ) {
				$update_url = $url . $version . '.' . $subversion . '/update.json';

				$response = wp_remote_get( $update_url, [ 'sslverify' => false ] );

				if ( is_wp_error( $response ) || ! isset( $response['body'] ) ) {
					$this->notices['error'] = $response->get_error_message();
					$this->update_set_transient( 'error' );
					break;
				}
				$update_content = json_decode( $response['body'], true );

				if ( isset( $update_content['changes'] ) && is_array( $update_content['changes'] ) ) {
					$update_content['files'] = array_column( $update_content['changes'], 'file' );
					$update_content['path']  = array_column( $update_content['changes'], 'path' );
				}

				$update_content['patch_link'] = $url . $version . '.' . $subversion . '/ ' . $version . '.' . $subversion . '.zip';

				if ( json_last_error() !== JSON_ERROR_NONE ) {
					break;
				}

				$updates[ $version . '.' . $subversion ] = $update_content;
				$subversion                              += 1;
			}

			$updates = array_reverse( $updates );

			$this->update_set_transient( $updates );

			return $updates;
		}

		/**
		 * Sets/updates the value of a transient.
		 *
		 * @param string|array $data Value.
		 *
		 * @return void
		 */
		public function update_set_transient( $data ) {
			set_transient( $this->transient_name, $data, DAY_IN_SECONDS );
		}


		/**
		 * Check filesystem API.
		 *
		 * @return bool
		 */
		public function check_filesystem_api() {
			global $wp_filesystem;

			if ( function_exists( 'WP_Filesystem' ) ) {
				WP_Filesystem();
			}

			return 'direct' === $wp_filesystem->method;
		}

		/**
		 * Add localized settings.
		 *
		 * @return array
		 */
		public function add_localized_settings() {
			return array(
				'single_patch_confirm' => esc_html__( 'These files will be updated:', 'soledad' ),
				'all_patches_confirm'  => esc_html__( 'Are you sure you want to download all patches?', 'soledad' ),
				'all_patches_applied'  => esc_html__( 'All patches are applied.', 'soledad' ),
				'ajax_error'           => esc_html__( 'Something wrong with removing data. Please, try to remove data manually or contact our support center for further assistance.', 'soledad' ),
			);
		}

		/**
		 * Apply patch.
		 */
		public function apply() {
			$patches_map = $this->get_patches_maps();

			if ( ! isset( $patches_map[ $this->patch_id ] ) ) {
				wp_send_json(
					array(
						'message' => esc_html__( 'Patch with this ID does\'t exist.', 'soledad' ),
						'status'  => 'error',
					)
				);
			}

			$patch = $patches_map[ $this->patch_id ];

			$patch_server_file = $patch['path'];

			foreach ( $patch['files'] as $key => $file_dir ) {
				$content = $this->get_patch_file_from_server( $patch_server_file[ $key ] );

				if ( ! $content ) {
					$this->successful_write_files = false;
					continue;
				}

				$this->write_file( $file_dir, $content );
			}

			if ( $this->successful_write_files ) {
				$patch_success = get_option( 'penci_successfully_installed_patches' );

				$patch_success[ PENCI_SOLEDAD_VERSION ][ $this->patch_id ] = true;

				update_option( 'penci_successfully_installed_patches', $patch_success, false );

				wp_send_json(
					array(
						'message' => esc_html__( 'Patch has been successfully applied.', 'soledad' ),
						'status'  => 'success',
					)
				);
			}

			wp_send_json(
				array(
					'message' => esc_html__( 'Something went wrong during patch installation. Patch can\'t be applied. Please, try again later.', 'soledad' ),
					'status'  => 'error',
				)
			);
		}

		/**
		 * Write file.
		 *
		 * @param string $file_dir File directory.
		 * @param string $content File content.
		 */
		public function write_file( $file_dir, $content ) {
			global $wp_filesystem;

			if ( function_exists( 'WP_Filesystem' ) ) {
				WP_Filesystem();
			}

			$target = get_template_directory() . wp_normalize_path( '/' . $file_dir );

			$status_write_file = $wp_filesystem->put_contents( $target, $content );

			if ( ! $status_write_file ) {
				$this->successful_write_files = false;
			}
		}

		/**
		 * Queries the patches server for a list of patches.
		 *
		 * @param int $key Key file.
		 *
		 * @return string
		 */
		private function get_patch_file_from_server( $key ) {

			$url     = $this->remote_server;
			$path_id = $this->patch_id;

			$file_query_url = $url . $path_id . '/' . $key;

			$response = wp_remote_get( $file_query_url, [ 'sslverify' => false ] );

			if ( is_wp_error( $response ) ) {
				wp_send_json(
					array(
						'message' => $response->get_error_message(),
						'status'  => 'error',
					)
				);
			}

			if ( ! isset( $response['body'] ) ) {
				wp_send_json(
					array(
						'message' => $response['response']['code'] . ': ' . $response['response']['message'],
						'status'  => 'error',
					)
				);
			}

			$response = wp_remote_retrieve_body( $response );

			if ( isset( $response_body['code'] ) && isset( $response_body['message'] ) ) {
				wp_send_json(
					array(
						'message' => $response_body['message'],
						'status'  => 'error',
					)
				);
			}

			if ( isset( $response_body['type'] ) && isset( $response_body['message'] ) ) {
				wp_send_json(
					array(
						'message' => $response_body['message'],
						'status'  => $response_body['type'],
					)
				);
			}

			return $response;
		}
	}

	Penci_Patcher::get_instance();
}