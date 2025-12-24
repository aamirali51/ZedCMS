<?php
// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * class Penci_Generate_Customizer_CSS_File
 */
if( ! class_exists( 'Penci_Generate_Customizer_CSS_File' ) ){
	class Penci_Generate_Customizer_CSS_File {
		
		private static $instance;

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/* Hook to __construct function */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_customizer_css' ), 100 );
			add_action( 'customize_save_after', array( $this, 'remove_option_render_time' ) );

			if ( ! empty( $_POST ) ) {
				add_action( 'wp_ajax_penci_render_separate_css_file', array( $this, 'regenerate_css_file' ) );
			}
		}

		/**
		 * Check to get correct the regenerate CSS data
		 */
		public function regenerate_return() {
			$option = get_theme_mod( 'penci_spcss_render' );

			if ( 'separate_file' == $option ) {
				$data_time = get_option( 'penci_regenerate_css_time' );
				$data_version = get_option( 'penci_regenerate_css_time' );
				if ( ! $data_version ) {
					// Create a version of Customizer CSS file
					update_option( 'penci_regenerate_version', time() );
				}

				if ( ! $data_time ) {
					// Created a data saved time - before 5sec to make sure the file can be created
					$data_time = time() - 5;
					update_option( 'penci_regenerate_css_time', $data_time );
				}

				// Create 1 file every 5 seconds.
				$current_time = (int) time();
				$saved_time    = (int) $data_time;

				if ( 5 <= ( $current_time - $saved_time ) ) {
					$option = ( $this->can_write_css() && $this->create_css() ) ? 'separate_file' : 'inline';
					
					if ( 'separate_file' == $option ) {
						$option = ( file_exists( $this->generate_file( 'path' ) ) ) ? 'separate_file' : 'inline';
					}
				}
			}

			return $option;
		}

		/**
		 * Enqueue the Customizer CSS file
		 */
		public function enqueue_customizer_css() {
			if ( 'separate_file' == $this->regenerate_return() ) {
				wp_enqueue_style( 'penci-soledad-customizer', esc_url( $this->generate_file( 'uri' ) ), array(), null );
			}
		}

		/**
		 * Create Customizer CSS file
		 */
		public function create_css() {
			$content = '';

			if( function_exists( 'pencidesign_return_css' ) ){
				$string_css = pencidesign_return_css();
				$string_render = trim(preg_replace('/\s+/', ' ', $string_css));
				$content .= $string_render;
			}
			
			/* Add a filter to hook to this content data */
			$content = apply_filters( 'penci_regenerate_separate_file_hook', $content );

			if ( ! $content ) {
				return false; /* Return if the $content is empty */
			}

			global $wp_filesystem;
			
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}

			// Check if WP Multisite domain mapping is activated - see more: https://wordpress.org/support/article/wordpress-multisite-domain-mapping/
			if ( defined( 'DOMAIN_MAPPING' ) && DOMAIN_MAPPING ) {
				if ( function_exists( 'domain_mapping_siteurl' ) && function_exists( 'get_original_url' ) ) {
					$domain_mapping = domain_mapping_siteurl( false );
					$domain_mapping = str_replace( 'https://', '//', $domain_mapping );
					$domain_mapping = str_replace( 'http://', '//', $domain_mapping );

					$original_url = get_original_url( 'siteurl' );
					$original_url = str_replace( 'https://', '//', $original_url );
					$original_url = str_replace( 'http://', '//', $original_url );

					$content = str_replace( $original_url, $domain_mapping, $content );
				}
			}

			// Strip protocols.
			$content = str_replace( 'https://', '//', $content );
			$content = str_replace( 'http://', '//', $content );

			if ( is_writable( $this->generate_file( 'path' ) ) || ( ! file_exists( $this->generate_file( 'path' ) ) && is_writable( dirname( $this->generate_file( 'path' ) ) ) ) ) {
				if ( ! $wp_filesystem->put_contents( $this->generate_file( 'path' ), wp_strip_all_tags( $content ), 0644 ) ) {
					return false;
				} else {
					$this->update_option_render_time();
					
					return true;
				}
			}
		}

		/**
		 * Detect if we can write a new CSS file
		 */
		public function can_write_css() {
			global $blog_id;
			
			$upload_folder_dir = wp_upload_dir();

			// If WP Multisite activated, add more $blog_id to the URL
			$css_file_id = ( is_multisite() && $blog_id > 1 ) ? '_blog-' . $blog_id : null;

			$css_file_name   = '/customizer-style' . $css_file_id . '.min.css';
			$folder_path = $upload_folder_dir['basedir'] . DIRECTORY_SEPARATOR . 'pencidesign';

			// Check folder is exists or not
			if ( file_exists( $folder_path ) ) {
				// Forder can be write or not?
				if ( ! is_writable( $folder_path ) ) {
					// Check CSS file is exists or not
					if ( ! file_exists( $folder_path . $css_file_name ) ) {
						// Done because it can't be write
						return false;
					} else {
						if ( ! is_writable( $folder_path . $css_file_name ) ) {
							// Done because it can't be write
							return false;
						}
					}
				} else {
					if ( file_exists( $folder_path . $css_file_name ) ) {
						if ( ! is_writable( $folder_path . $css_file_name ) ) {
							// Done because it can't be write
							return false;
						}
					}
				}
			} else {
				// Create folder path
				return wp_mkdir_p( $folder_path );
			}

			return true;
		}

		/**
		 * Gets the css path or url of customizer CSS
		 *
		 */
		public function generate_file( $return = 'path' ) {
			global $blog_id;
			
			$upload_folder_dir = wp_upload_dir();

			// If WP Multisite activated, add more $blog_id to the URL
			$css_file_id = ( is_multisite() && $blog_id > 1 ) ? '_blog-' . $blog_id : null;

			$css_file_name   = 'customizer-style' . $css_file_id . '.min.css';
			$folder_path = $upload_folder_dir['basedir'] . DIRECTORY_SEPARATOR . 'pencidesign';

			// File path
			$css_path = $folder_path . DIRECTORY_SEPARATOR . $css_file_name;

			// URL directory of customizer CSS
			$css_folder_uri = $upload_folder_dir['baseurl'];

			$css_uri = trailingslashit( $css_folder_uri ) . 'pencidesign/' . $css_file_name;

			// Check if WP Multisite domain mapping is activated - see more: https://wordpress.org/support/article/wordpress-multisite-domain-mapping/
			if ( defined( 'DOMAIN_MAPPING' ) && DOMAIN_MAPPING ) {
				if ( function_exists( 'domain_mapping_siteurl' ) && function_exists( 'get_original_url' ) ) {
					$domain_mapping   = domain_mapping_siteurl( false );
					$original_url = get_original_url( 'siteurl' );
					$css_uri         = str_replace( $original_url, $domain_mapping, $css_uri );
				}
			}

			// Protocols
			$css_uri = str_replace( 'https://', '//', $css_uri );
			$css_uri = str_replace( 'http://', '//', $css_uri );

			if ( 'path' === $return ) {
				return $css_path;
			} elseif ( 'uri' === $return || 'url' === $return ) {
				$version = get_option( 'penci_regenerate_version' );
				$savetime = ( file_exists( $css_path ) ) ? '?version=' . $version : '';
				return $css_uri . $savetime;
			}
		}

		/**
		 * Update the option saved time
		 */
		public function update_option_render_time() {
			$data_time = time();

			update_option( 'penci_regenerate_css_time', $data_time );
		}

		/**
		 * Delete the option saved time
		 */
		public function remove_option_render_time() {
			$data_time = get_option( 'penci_regenerate_css_time' );

			if ( $data_time ) {
				delete_option( 'penci_regenerate_css_time' );
			}
			
			update_option( 'penci_regenerate_version', time() );
		}

		/**
		 * Regenerate the CSS file.
		 */
		public function regenerate_css_file() {
			check_ajax_referer( 'penci_render_separate_css_file', '_nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'User Permission Error', 'soledad' ) );
			}

			$this->remove_option_render_time();

			wp_send_json_success();
			
			die();
		}
	}

	Penci_Generate_Customizer_CSS_File::get_instance();

}