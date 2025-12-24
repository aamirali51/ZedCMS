<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( ! class_exists( 'PENCI_FW_MetaBox' ) ):
	class PENCI_FW_MetaBox{

		private static $_instance = null;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {

			// Uses priority 20 to support custom port types registered using the default priority.
			add_action( 'init', array( $this, 'register_meta_boxes' ), 20 );

			add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ), 10, 1 );

			$this->load_files();
		}

		public function load_files() {
			// Creating meta boxes
			require_once  PENCI_SOLEDAD_DIR . '/inc/meta-box/inc/add-meta-box.php';
			require_once  PENCI_SOLEDAD_DIR . '/inc/meta-box/inc/fields.php';
			require_once  PENCI_SOLEDAD_DIR . '/inc/meta-box/register/page.php';
		}

		/**
		 * Register meta boxes.
		 */
		public function register_meta_boxes() {
			$configs = apply_filters( 'penci_meta_boxes', array() );

			foreach ( $configs as $config ) {
				new Penci_Add_Meta_Box( $config );
			}
		}

		/**
		 * Enqueue scripts on custom post add/edit pages
		 *
		 * @param $hook
		 */
		function add_admin_scripts( $hook ) {
			if ( in_array( $hook, array( 'post-new.php','post.php', 'edit-tags.php','term.php' ) ) ) {

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_media();

				wp_enqueue_script(  'penci-custom-gallery-options', PENCI_SOLEDAD_URL .'/js/admin-gallery.js', array( 'jquery','media-views', 'wp-color-picker' ), PENCI_SOLEDAD_VERSION, true );
				wp_localize_script( 'penci-custom-gallery-options', 'PenciObject', array(
					'WidgetImageTitle'   => esc_html__( 'Select an image', 'soledad' ),
					'WidgetImageButton'  => esc_html__( 'Insert into widget', 'soledad' ),
					'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
					'nonce'              => wp_create_nonce( 'ajax-nonce' ),
				) );

				wp_enqueue_style( 'penci-admin-post', PENCI_SOLEDAD_URL . '/css/admin.css', '', PENCI_SOLEDAD_VERSION );
			}
		}
	}

	new PENCI_FW_MetaBox;
endif;
