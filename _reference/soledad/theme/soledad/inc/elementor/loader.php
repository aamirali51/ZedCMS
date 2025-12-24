<?php

namespace PenciSoledadElementor;
use Elementor\Plugin;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Loader {
	private static $_instance;
	public $modules_manager;
	protected $font_types = [];
	private $classes_aliases = array(
		'PenciSoledadElementor\Modules\PanelPostsControl\Module'                             => 'PenciSoledadElementor\Modules\QueryControl\Module',
		'PenciSoledadElementor\Modules\PanelPostsControl\Controls\Penci_Group_Control_Posts' => 'PenciSoledadElementor\Modules\QueryControl\Controls\Penci_Group_Control_Posts',
		'PenciSoledadElementor\Modules\PanelPostsControl\Controls\Query'                     => 'PenciSoledadElementor\Modules\QueryControl\Controls\Query',
	);

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );

		$this->includes();
		$this->setup_hooks();
	}

	private function includes() {
		require PENCI_ELEMENTOR_PATH . 'includes/modules-manager.php';
		require PENCI_ELEMENTOR_PATH . 'includes/helper.php';
		require PENCI_ELEMENTOR_PATH . 'includes/utils.php';
	}

	private function setup_hooks() {
		add_action( 'elementor/init', array( $this, 'on_elementor_init' ) );
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
		add_action( 'elementor/elements/categories_registered', array( $this, 'widget_categories' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_editor_styles' ) );
		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );

		//handle select2 ajax search
		add_action( 'wp_ajax_penci_select2_search_post', [ $this, 'select2_ajax_posts_filter_autocomplete' ] );
		add_action( 'wp_ajax_nopriv_penci_select2_search_post', [ $this, 'select2_ajax_posts_filter_autocomplete' ] );

		add_action( 'wp_ajax_penci_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );
		add_action( 'wp_ajax_nopriv_penci_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );

		//custom font
		add_filter( 'elementor/fonts/groups', [ $this, 'register_fonts_groups' ] );
		add_filter( 'elementor/fonts/additional_fonts', [ $this, 'register_fonts_in_control' ] );

		add_action( 'wp_body_open', [ $this, 'elementor_pro_header' ] );
		add_action( 'elementor/init', [ $this, 'penci_clear_cache' ] );
	}

	public static function penci_clear_cache() {
		$cleared = get_option( 'penci_clear_cache' );
		if ( ! $cleared ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
			update_option( 'penci_clear_cache', true );
		}
	}

	/**
	 * @return \Elementor\Plugin
	 */

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}


	public static function elementor_pro_header() {

		if ( ! function_exists( 'elementor_location_exits' ) ) {
			return;
		}

		$show_header = false;

		if ( elementor_location_exits( 'footer', true ) ) {
			$show_header = true;
		}

		if ( elementor_location_exits( 'header', true ) ) {
			$show_header = false;
		}

		if ( elementor_location_exits( 'header', true ) ) {
			$header_layout       = 'penci-elementor-pro-header';
			$penci_hide_header   = $show_page_title = false;
			$header_search_style = get_theme_mod( 'penci_topbar_search_style', 'default' );
			if ( is_page() ) {
				$penci_hide_header = get_post_meta( get_the_ID(), 'penci_page_hide_header', true );

				$show_page_title  = get_theme_mod( 'penci_pheader_show' );
				$penci_page_title = get_post_meta( get_the_ID(), 'penci_pmeta_page_title', true );

				$pheader_show = isset( $penci_page_title['pheader_show'] ) ? $penci_page_title['pheader_show'] : '';
				if ( 'enable' == $pheader_show ) {
					$show_page_title = true;
				} elseif ( 'disable' == $pheader_show ) {
					$show_page_title = false;
				}
			} else if ( is_single() ) {
				$penci_hide_header = penci_is_hide_header();
			}
			$class_wrapper_boxed = 'elementor-custom-header-template wrapper-boxed header-style-' . esc_attr( $header_layout );
			if ( get_theme_mod( 'penci_body_boxed_layout' ) && ! get_theme_mod( 'penci_vertical_nav_show' ) ) {
				$class_wrapper_boxed .= ' enable-boxed';
			}
			if ( get_theme_mod( 'penci_enable_dark_layout' ) ) {
				$class_wrapper_boxed .= ' dark-layout-enabled';
			}
			if ( $penci_hide_header ) {
				$class_wrapper_boxed .= ' penci-page-hide-header';
			}
			if ( get_theme_mod( 'penci_header_logo_mobile_center' ) ) {
				$class_wrapper_boxed .= ' penci-hlogo-center';
			}

			$class_wrapper_boxed .= ' header-search-style-' . esc_attr( $header_search_style );

			if ( $show_page_title && ! is_home() && ! is_front_page() ) {
				get_template_part( 'template-parts/page-header' );
			}

			echo '<div id="soledad_wrapper" class="' . $class_wrapper_boxed . '">';
		} else if ( $show_header ) {

			if ( get_theme_mod( 'penci_custom_code_after_body_tag' ) ):
				echo do_shortcode( get_theme_mod( 'penci_custom_code_after_body_tag' ) );
			endif;

			$penci_hide_header = $show_page_title = false;
			if ( is_page() ) {
				$penci_hide_header = get_post_meta( get_the_ID(), 'penci_page_hide_header', true );

				$show_page_title  = get_theme_mod( 'penci_pheader_show' );
				$penci_page_title = get_post_meta( get_the_ID(), 'penci_pmeta_page_title', true );

				$pheader_show = isset( $penci_page_title['pheader_show'] ) ? $penci_page_title['pheader_show'] : '';
				if ( 'enable' == $pheader_show ) {
					$show_page_title = true;
				} elseif ( 'disable' == $pheader_show ) {
					$show_page_title = false;
				}
			} else if ( is_single() ) {
				$penci_hide_header = penci_is_hide_header();
			}

			/**
			 * Get header layout in your customizer to change header layout
			 *
			 * @author PenciDesign
			 */
			$header_layout = penci_soledad_get_header_layout();
			$menu_style    = get_theme_mod( 'penci_header_menu_style' ) ? get_theme_mod( 'penci_header_menu_style' ) : 'menu-style-1';

			$header_class = $header_layout;
			if ( $header_layout == 'header-9' ) {
				$header_class = 'header-6 header-9';
			}

			if ( get_theme_mod( 'penci_vertical_nav_show' ) ) {
				get_template_part( 'template-parts/menu-hamburger' );
			}

			$class_wrapper_boxed = 'wrapper-boxed header-style-' . esc_attr( $header_layout );
			if ( get_theme_mod( 'penci_body_boxed_layout' ) && ! get_theme_mod( 'penci_vertical_nav_show' ) ) {
				$class_wrapper_boxed .= ' enable-boxed';
			}
			if ( get_theme_mod( 'penci_enable_dark_layout' ) ) {
				$class_wrapper_boxed .= ' dark-layout-enabled';
			}
			if ( $penci_hide_header ) {
				$class_wrapper_boxed .= ' penci-page-hide-header';
			}
			if ( get_theme_mod( 'penci_header_logo_mobile_center' ) ) {
				$class_wrapper_boxed .= ' penci-hlogo-center';
			}

			$header_builder      = function_exists( 'penci_check_theme_mod' ) && penci_check_theme_mod() ? penci_check_theme_mod() : '';
			$header_search_style = ! empty( $header_builder ) ? penci_get_builder_mod( 'penci_header_search_style', 'showup' ) : get_theme_mod( 'penci_topbar_search_style', 'default' );
			$class_wrapper_boxed .= ' header-search-style-' . esc_attr( $header_search_style );
			$custom_header_class = $header_builder ? ' pc-wrapbuilder-header' : '';
			?>
        <div id="soledad_wrapper" class="<?php echo esc_attr( $class_wrapper_boxed ); ?>">
			<?php
			if ( ! $penci_hide_header ) {

				do_action( 'penci_above_header_wrap' );

				echo '<div class="penci-header-wrap' . $custom_header_class . '">';

				get_template_part( 'template-parts/header/top-instagram' );

				if ( ! empty( $header_builder ) ) {

					if ( is_singular( 'penci-block' ) ) {
						return;
					}

					load_template( PENCI_SOLEDAD_DIR . '/inc/builder/template/desktop-builder.php' );

				} else {

					if ( get_theme_mod( 'penci_top_bar_show' ) ) {
						get_template_part( 'inc/modules/topbar' );
					}

					get_template_part( 'template-parts/header/' . $header_layout );
				}
				echo '</div>';

				if ( ! is_customize_preview() || ! isset( $_GET['layout_id'] ) ) {

					get_template_part( 'template-parts/header/mailchimp-below-header' );

					if ( is_home() || get_theme_mod( 'penci_featured_slider_all_page' ) ) {
						get_template_part( 'template-parts/header/feature-slider' );
					}

					if ( ( ( is_home() || is_front_page() ) && get_theme_mod( 'penci_signup_display_homepage' ) ) || ! get_theme_mod( 'penci_signup_display_homepage' ) ) {
						get_template_part( 'template-parts/header/mailchimp-below-header2' );
					}
				}
				do_action( 'penci_below_header_wrap' );
			}
			if ( $show_page_title && ! is_home() && ! is_front_page() ) {
				get_template_part( 'template-parts/page-header' );
			}

		}
	}

	/**
	 * @return Loader
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function register_fonts_groups( $font_groups ) {
		$new_groups = [];

		$new_groups['penci_custom_fonts'] = __( 'Penci Custom Fonts', 'soledad' );

		return array_replace( $new_groups, $font_groups );
	}

	public function register_fonts_in_control( $fonts ) {

		$custom_fonts = penci_get_custom_fonts();

		if ( ! empty( $custom_fonts ) ) {
			foreach ( $custom_fonts as $font_name => $font_title ) {
				$fonts[$font_name] = 'penci_custom_fonts';
			}
		}

		return $fonts;
	}

	/**
	 * @Register Control Select2
	 */
	public function register_controls( $controls_manager ) {
		require_once( __DIR__ . '/includes/select2.php' );
		require_once( __DIR__ . '/includes/autocomplete.php' );
		$controls_manager->register( new \PenciSoledadElementor\Controls\Select2() );
		$controls_manager->register( new \PenciSoledadElementor\Controls\Autocomplete() );
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load    = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower( preg_replace( array(
				'/^' . __NAMESPACE__ . '\\\/',
				'/([a-z])([A-Z])/',
				'/_/',
				'/\\\/'
			), array( '', '$1-$2', '-', DIRECTORY_SEPARATOR ), $class_to_load ) );
			$filename = PENCI_ELEMENTOR_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	public function widget_categories( $elements_manager ) {
		// Add our categories
		$category_prefix = 'penci-';

		$elements_manager->add_category( $category_prefix . 'archive-builder', [
			'title' => '[PenciDesign] Archive Builder',
			'icon'  => 'fa fa-plug',
		] );

		// Hack into the private $categories member, and reorder it so our stuff is at the top
		$reorder_cats = function () use ( $category_prefix ) {
			uksort( $this->categories, function ( $keyOne, $keyTwo ) use ( $category_prefix ) {
				if ( substr( $keyOne, 0, 6 ) == $category_prefix ) {
					return - 1;
				}
				if ( substr( $keyTwo, 0, 6 ) == $category_prefix ) {
					return 1;
				}

				return 0;
			} );

		};

		$reorder_cats->call( $elements_manager );
	}

	/**
	 *  Editor enqueue styles.
	 */
	public function enqueue_editor_styles() {
		wp_enqueue_style( 'penci-elementor-editor', PENCI_ELEMENTOR_URL . 'assets/css/editor.css', array( 'elementor-editor' ), PENCI_SOLEDAD_VERSION );
	}

	public function enqueue_editor_scripts() {
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			return;
		}

		if ( version_compare( ELEMENTOR_VERSION, '3.0.0', '<' ) ) {
			wp_enqueue_script( 'penci-elementor', PENCI_ELEMENTOR_URL . 'assets/js/editor.bak.js', array( 'backbone-marionette' ), PENCI_SOLEDAD_VERSION, true );
		} else {
			wp_enqueue_script( 'penci-elementor', PENCI_ELEMENTOR_URL . 'assets/js/editor.min.js', array(
				'backbone-marionette',
				'elementor-common',
				'elementor-editor-modules',
				'elementor-editor-document',
			), PENCI_SOLEDAD_VERSION, true );
		}

		wp_localize_script( 'penci-elementor', 'PenciElementorConfig', array(
			'i18n'     => array(),
			'isActive' => true,
		) );
	}

	/**
	 * Register scripts
	 */
	public function register_frontend_scripts() {
		$api = get_theme_mod( 'penci_map_api_key', '' );
		if ( $api ) {
			$map_url = 'https://maps.google.com/maps/api/js?key=' . esc_attr( $api );
		} else {
			$map_url = 'https://cdnjs.cloudflare.com/ajax/libs/googlemaps-js-api-loader/1.16.8/index.min.js';
		}
		wp_register_script( 'google-map', esc_url( $map_url ), array(), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'penci-amap', PENCI_SOLEDAD_URL . '/js/advance-gmaps.js', array(), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'jquery.plugin', PENCI_SOLEDAD_URL . '/js/jquery.plugin.min.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'countdown', PENCI_SOLEDAD_URL . '/js/jquery.countdown.min.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'waypoints', PENCI_SOLEDAD_URL . '/js/waypoints.min.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'penci-content-accordion', PENCI_SOLEDAD_URL . '/js/content-accordion.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'penci-image-compare', PENCI_SOLEDAD_URL . '/js/image-compare.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'jquery-counterup', PENCI_SOLEDAD_URL . '/js/jquery.counterup.min.js', array(
			'jquery',
			'waypoints'
		), '1.0', true );
		wp_register_script( 'penci-button-popup', PENCI_SOLEDAD_URL . '/inc/elementor/assets/js/penci-button-popup.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'penci-custom-carousel', PENCI_SOLEDAD_URL . '/js/custom-carousel-front.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'penci-el-toc', PENCI_SOLEDAD_URL . '/js/penci-el-toc.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'penci-sticky-container', PENCI_SOLEDAD_URL . '/inc/elementor/assets/js/sticky.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'penci-header-search', PENCI_SOLEDAD_URL . '/inc/elementor/assets/js/search.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		
		wp_register_script( 'penci-formula', PENCI_SOLEDAD_URL . '/js/formula.min.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'penci-jstat', PENCI_SOLEDAD_URL . '/js/jstat.min.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		wp_register_script( 'penci-advanced-calculator', PENCI_SOLEDAD_URL . '/js/advanced-calculator.js', array( 'jquery', 'penci-formula', 'penci-jstat' ), PENCI_SOLEDAD_VERSION, true );
		
		wp_register_style( 'penci-hover-box', PENCI_SOLEDAD_URL . '/inc/elementor/assets/css/hoverbox.css', array(), PENCI_SOLEDAD_VERSION );
		wp_register_script( 'penci-hover-box', PENCI_SOLEDAD_URL . '/inc/elementor/assets/js/hoverbox.js', array('jquery'), PENCI_SOLEDAD_VERSION, true );
		
		wp_register_style( 'penci-circle-menu', PENCI_SOLEDAD_URL . '/inc/elementor/assets/css/circlemenu.css', array(), PENCI_SOLEDAD_VERSION );
		wp_register_script( 'penci-circle-menu', PENCI_SOLEDAD_URL . '/inc/elementor/assets/js/circlemenu.js', array('jquery'), PENCI_SOLEDAD_VERSION, true );
		
		wp_register_style( 'penci-circle-info', PENCI_SOLEDAD_URL . '/inc/elementor/assets/css/circleinfo.css', array(), PENCI_SOLEDAD_VERSION );
		wp_register_script( 'penci-circle-info', PENCI_SOLEDAD_URL . '/inc/elementor/assets/js/circleinfo.js', array('jquery'), PENCI_SOLEDAD_VERSION, true );
				
		wp_register_script( 'penci-marquee-el', PENCI_SOLEDAD_URL . '/inc/elementor/assets/js/marquee.js', array('jquery','gsap'), PENCI_SOLEDAD_VERSION, true );
		
		wp_register_script( 'penci-folding', PENCI_SOLEDAD_URL . '/inc/elementor/assets/js/folding.js', array('jquery'), PENCI_SOLEDAD_VERSION, true );
		wp_register_style( 'penci-folding', PENCI_SOLEDAD_URL . '/inc/elementor/assets/css/fold.css', array(), PENCI_SOLEDAD_VERSION );
		
		if ( did_action( 'elementor/loaded' ) ) {
			$kit = Plugin::$instance->kits_manager->get_active_kit();
			$is_global_image_lightbox_enabled = 'yes' === $kit->get_settings( 'global_image_lightbox' );

			if ( $is_global_image_lightbox_enabled ) {
				wp_enqueue_script( 'penci-el-lightbox', PENCI_SOLEDAD_URL . '/inc/elementor/assets/js/lightbox.js', array('jquery'), PENCI_SOLEDAD_VERSION, true );
			}
		}
	}

	public function on_elementor_init() {
		$this->modules_manager = new Manager();
	}

	public function select2_ajax_posts_filter_autocomplete() {
		$post_type   = 'any';
		$source_name = 'post_type';

		if ( ! empty( $_GET['post_type'] ) && 'by_id' != $_GET['post_type'] && 'current_query' != $_GET['post_type'] && 'related_posts' != $_GET['post_type'] ) {
			$post_type = sanitize_text_field( $_GET['post_type'] );
		}

		if ( ! empty( $_GET['source_name'] ) ) {
			$source_name = sanitize_text_field( $_GET['source_name'] );
		}

		$search  = ! empty( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : '';
		$results = $post_list = [];
		switch ( $source_name ) {
			case 'taxonomy':
				$post_list = wp_list_pluck( get_terms( $post_type, [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'search'     => $search,
					'number'     => '10',
				] ), 'name', 'term_id' );
				break;
			default:
				$post_list = $this->get_query_post_list( $post_type, 10, $search );
		}

		if ( ! empty( $post_list ) ) {
			foreach ( $post_list as $key => $item ) {
				$results[] = [ 'text' => $item, 'id' => $key ];
			}
		}
		wp_send_json( [ 'results' => $results ] );
	}

	public function get_query_post_list( $post_type = 'any', $limit = - 1, $search = '' ) {
		global $wpdb;
		$where = '';
		$data  = [];

		if ( - 1 == $limit ) {
			$limit = '';
		} elseif ( 0 == $limit ) {
			$limit = "limit 0,1";
		} else {
			$limit = $wpdb->prepare( " limit 0,%d", esc_sql( $limit ) );
		}

		if ( 'any' === $post_type ) {
			$in_search_post_types = get_post_types( [ 'exclude_from_search' => false ] );
			if ( empty( $in_search_post_types ) ) {
				$where .= ' AND 1=0 ';
			} else {
				$where .= " AND {$wpdb->posts}.post_type IN ('" . join( "', '", array_map( 'esc_sql', $in_search_post_types ) ) . "')";
			}
		} elseif ( ! empty( $post_type ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_type = %s", esc_sql( $post_type ) );
		}

		if ( ! empty( $search ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", '%' . esc_sql( $search ) . '%' );
		}

		$query   = "select post_title,ID  from $wpdb->posts where post_status = 'publish' $where $limit";
		$results = $wpdb->get_results( $query );
		if ( ! empty( $results ) ) {
			foreach ( $results as $row ) {
				$data[ $row->ID ] = $row->post_title;
			}
		}

		return $data;
	}

	public function select2_ajax_get_posts_value_titles() {
		if ( empty( array_filter( $_POST['id'] ) ) ) {
			wp_send_json_error( [] );
		}

		$ids         = array_map( 'intval', $_POST['id'] );
		$source_name = ! empty( $_POST['source_name'] ) ? sanitize_text_field( $_POST['source_name'] ) : '';

		switch ( $source_name ) {
			case 'taxonomy':
				$response = wp_list_pluck( get_terms( sanitize_text_field( $_POST['post_type'] ), [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'include'    => implode( ',', $ids ),
				] ), 'name', 'term_id' );
				break;
			default:
				$post_info = get_posts( [
					'post_type' => sanitize_text_field( $_POST['post_type'] ),
					'include'   => implode( ',', $ids )
				] );
				$response  = wp_list_pluck( $post_info, 'post_title', 'ID' );
		}

		if ( ! empty( $response ) ) {
			wp_send_json_success( [ 'results' => $response ] );
		} else {
			wp_send_json_error( [] );
		}
	}
}

Loader::instance();

