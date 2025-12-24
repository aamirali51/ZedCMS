<?php
/**
 * @author : PenciDesign
 */

/**
 * Class Penci Header Builder
 */
class HeaderBuilder {
	/**
	 * @var HeaderBuilder
	 */
	private static $instance = null;

	public function __construct() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		add_action( 'init', array( $this, 'builder_post_type' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'header_builder_html' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'header_builder_js' ), 900 );
		add_action( 'wp_ajax_save_header_builder', array( $this, 'save_header_builder' ) );

		add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
		add_filter( 'customize_register', array( $this, 'clear_settings' ), 99999999 );
		add_action( 'admin_init', array( $this, 'preview_customize' ) );
		add_action( 'edit_form_after_title', array( $this, 'penci_builder_editpost_link' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'penci_builder_admin_css' ) );
		add_filter( 'customize_loaded_components', array( $this, 'customizer_cleaner' ) );
		add_filter( 'display_post_states', array( $this, 'penci_dashboard_title' ), 10, 2 );
		add_filter( 'post_type_link', array( $this, 'change_link' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'add_edit_link' ), 10, 2 );
		add_filter( 'template_include', array( $this, 'header_template' ), 999 );
		add_filter( 'body_class', array( $this, 'preview_body_class' ), 10, 1 );
		add_action( 'customize_preview_init', array( $this, 'live_preview' ) );
		add_action( 'customize_controls_init', array( $this, 'live_preview_url' ) );
	}

	/**
	 * @return HeaderBuilder
	 */
	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function live_preview_url() {
		global $wp_customize;
		if ( isset( $_REQUEST['layout_id'] ) && $_REQUEST['layout_id'] ) {
			$wp_customize->set_preview_url(
				add_query_arg(
					array( 'layout_id' => $_REQUEST['layout_id'] ),
					home_url()
				)
			);
		}
	}

	public static function remain_desktop_header_element() {
		return self::remain_desktop( 'penci_hb_element_desktop' );
	}

	public static function remain_desktop( $setting_prefix ) {
		$all_element = self::desktop_header_element();

		$rows_desktop    = array( 'topblock', 'top', 'mid', 'bottom', 'bottomblock' );
		$columns_desktop = array( 'left', 'center', 'right' );

		if ( $setting_prefix === 'penci_hb_element_desktop_sticky' ) {
			$rows_desktop = array( 'top', 'mid', 'bottom' );
		}

		$mobile_element = array(
			'pb_logo_mobile',
			'pb_mobile_menu',
			'pb_social_icon_mobile',
			'pb_button_mobile',
			'pb_button_mobile_2',
			'pb_html_ad_mobile',
			'pb_html_ad_mobile_2',
			'pb_shortcode_mobile',
			'pb_login_register_mobile',
			'pb_vertical_line_mobile_1',
			'pb_vertical_line_mobile_2',
		);

		foreach ( $rows_desktop as $row ) {
			foreach ( $columns_desktop as $column ) {
				$setting_element = "{$setting_prefix}_{$row}_{$column}";
				$default_element = penci_get_builder_mod( $setting_element, '' );
				$default_element = $default_element ? explode( ',', $default_element ) : $default_element;
				if ( is_array( $default_element ) ) {
					$default_element = array_merge( $default_element, $mobile_element );
					foreach ( $default_element as $element ) {
						unset( $all_element[ $element ] );
					}
				}
			}
		}

		return $all_element;
	}

	public static function desktop_header_element() {
		$elements = array(
			'pb_logo'            => esc_attr__( 'Logo', 'soledad' ),
			'pb_logo_sticky'     => esc_attr__( 'Sticky Logo', 'soledad' ),
			'pb_main_menu'       => esc_attr__( 'Menu 1', 'soledad' ),
			'pb_second_menu'     => esc_attr__( 'Menu 2', 'soledad' ),
			'pb_third_menu'      => esc_attr__( 'Menu 3', 'soledad' ),
			'pb_date_time'       => esc_attr__( 'Date Time', 'soledad' ),
			'pb_search_icon'     => esc_attr__( 'Search Icon', 'soledad' ),
			'pb_search_form'     => esc_attr__( 'Search Form', 'soledad' ),
			'pb_social_icon'     => esc_attr__( 'Social Icon', 'soledad' ),
			'pb_button'          => esc_attr__( 'Button 1', 'soledad' ),
			'pb_button_2'        => esc_attr__( 'Button 2', 'soledad' ),
			'pb_button_3'        => esc_attr__( 'Button 3', 'soledad' ),
			'pb_news_ticker'     => esc_attr__( 'News Ticker', 'soledad' ),
			'pb_hamburger_menu'  => esc_attr__( 'Hamburger Menu', 'soledad' ),
			'pb_login_register'  => esc_attr__( 'Login/Register', 'soledad' ),
			'pb_block'           => esc_attr__( 'Penci Block 1', 'soledad' ),
			'pb_block_2'         => esc_attr__( 'Penci Block 2', 'soledad' ),
			'pb_html_ad'         => esc_attr__( 'Custom HTML 1', 'soledad' ),
			'pb_html_ad_2'       => esc_attr__( 'Custom HTML 2', 'soledad' ),
			'pb_html_ad_3'       => esc_attr__( 'Custom HTML 3', 'soledad' ),
			'pb_shortcode'       => esc_attr__( 'Shortcode 1', 'soledad' ),
			'pb_shortcode_2'     => esc_attr__( 'Shortcode 2', 'soledad' ),
			'pb_shortcode_3'     => esc_attr__( 'Shortcode 3', 'soledad' ),
			'pb_vertical_line_1' => esc_attr__( 'Line Divider 1', 'soledad' ),
			'pb_vertical_line_2' => esc_attr__( 'Line Divider 2', 'soledad' ),
			'pb_vertical_line_3' => esc_attr__( 'Line Divider 3', 'soledad' ),
			'pb_vertical_line_4' => esc_attr__( 'Line Divider 4', 'soledad' ),
			'pb_vertical_line_5' => esc_attr__( 'Line Divider 5', 'soledad' ),
		);
		if ( get_theme_mod( 'penci_dms_enable' ) ) {
			$elements['pb_darkmode'] = esc_attr__( 'Dark Mode Switcher', 'soledad' );
		}
		if ( class_exists( 'WooCommerce' ) ) {
			$elements['pb_cart_icon']     = esc_attr__( 'Cart Icon', 'soledad' );
			$elements['pb_compare_icon']  = esc_attr__( 'Compare Icon', 'soledad' );
			$elements['pb_wishlist_icon'] = esc_attr__( 'WishList Icon', 'soledad' );
		}
		if ( defined( 'PENCI_BL_VERSION' ) ) {
			$elements['pb_bookmark'] = esc_attr__( 'Bookmark Button', 'soledad' );
		}

		return $elements;
	}

	public static function remain_sticky_header_element() {
		return self::remain_desktop( 'penci_hb_element_desktop_sticky' );
	}

	public static function remain_mobile_header_element() {
		$all_element = self::mobile_header_element();

		$blocks = array(
			'top' => array( 'center' ),
			'mid' => array( 'left', 'center', 'right' ),
		);

		foreach ( $blocks as $row => $columns ) {
			foreach ( $columns as $column ) {
				$setting_element = "penci_hb_element_mobile_{$row}_{$column}";
				$default_element = penci_get_builder_mod( $setting_element, array() );
				$default_element = $default_element ? explode( ',', $default_element ) : '';

				if ( is_array( $default_element ) ) {
					foreach ( $default_element as $element ) {
						unset( $all_element[ $element ] );
					}
				}
			}
		}

		return $all_element;
	}

	public static function mobile_header_element() {
		$elements = array(
			'pb_logo_mobile'            => esc_attr__( 'Mobile Logo', 'soledad' ),
			'pb_mobile_menu'            => esc_attr__( 'Mobile Menu Icon', 'soledad' ),
			'pb_login_register_mobile'  => esc_attr__( 'Mobile Login/Register', 'soledad' ),
			'pb_date_time'              => esc_attr__( 'Date Time', 'soledad' ),
			'pb_search_icon'            => esc_attr__( 'Search Icon', 'soledad' ),
			'pb_search_form'            => esc_attr__( 'Search Form', 'soledad' ),
			'pb_social_icon_mobile'     => esc_attr__( 'Social Icon', 'soledad' ),
			'pb_button_mobile'          => esc_attr__( 'Mobile Button 1', 'soledad' ),
			'pb_button_mobile_2'        => esc_attr__( 'Mobile Button 2', 'soledad' ),
			'pb_news_ticker'            => esc_attr__( 'News Ticker', 'soledad' ),
			'pb_hamburger_menu'         => esc_attr__( 'Hamburger Menu', 'soledad' ),
			'pb_block'                  => esc_attr__( 'Penci Block 1', 'soledad' ),
			'pb_block_2'                => esc_attr__( 'Penci Block 2', 'soledad' ),
			'pb_html_ad_mobile'         => esc_attr__( 'Custom HTML', 'soledad' ),
			'pb_html_ad_mobile_2'       => esc_attr__( 'Custom HTML 2', 'soledad' ),
			'pb_shortcode_mobile'       => esc_attr__( 'Shortcode', 'soledad' ),
			'pb_vertical_line_mobile_1' => esc_attr__( 'Line Divider 1', 'soledad' ),
			'pb_vertical_line_mobile_2' => esc_attr__( 'Line Divider 2', 'soledad' ),
		);
		if ( get_theme_mod( 'penci_dms_enable' ) ) {
			$elements['pb_darkmode'] = esc_attr__( 'Dark Mode Switcher', 'soledad' );
		}
		if ( class_exists( 'WooCommerce' ) ) {
			$elements['pb_cart_icon']     = esc_attr__( 'Cart Icon', 'soledad' );
			$elements['pb_compare_icon']  = esc_attr__( 'Compare Icon', 'soledad' );
			$elements['pb_wishlist_icon'] = esc_attr__( 'WishList Icon', 'soledad' );
		}
		if ( defined( 'PENCI_BL_VERSION' ) ) {
			$elements['pb_bookmark'] = esc_attr__( 'Bookmark Button', 'soledad' );
		}

		return $elements;
	}

	public static function remain_mobile_drawer_element() {
		$all_element = self::mobile_drawer_element();

		$blocks = array(
			'top'    => array( 'center' ),
			'bottom' => array( 'center' ),
		);

		foreach ( $blocks as $row => $columns ) {
			foreach ( $columns as $column ) {
				$setting_element = "penci_hb_element_mobile_drawer_{$row}_{$column}";
				$default_element = penci_get_builder_mod( $setting_element, array() );
				$default_element = $default_element ? explode( ',', $default_element ) : '';

				if ( is_array( $default_element ) ) {
					foreach ( $default_element as $element ) {
						unset( $all_element[ $element ] );
					}
				}
			}
		}

		return $all_element;
	}

	public static function mobile_drawer_element() {
		return array(
			'pb_logo_sidebar'        => esc_attr__( 'Sidebar Logo', 'soledad' ),
			'pb_dropdown_menu'       => esc_attr__( 'Mobile Sidebar Menu', 'soledad' ),
			'pb_search_form_sidebar' => esc_attr__( 'Search Form Sidebar', 'soledad' ),
			'pb_social_icon_mobile'  => esc_attr__( 'Social Icon', 'soledad' ),
			'pb_html_ad_mobile'      => esc_attr__( 'Custom HTML', 'soledad' ),
			'pb_html_ad_mobile_2'    => esc_attr__( 'Custom HTML 2', 'soledad' ),
			'pb_date_time'           => esc_attr__( 'Date Time', 'soledad' ),
			'pb_block'               => esc_attr__( 'Penci Block', 'soledad' ),
			'pb_shortcode'           => esc_attr__( 'Shortcode', 'soledad' ),
			'pb_button'              => esc_attr__( 'Button', 'soledad' ),
		);
	}

	public static function penci_dashboard_title( $post_states, $post ) {

		if ( $post->post_type !== 'penci_builder' ) {
			return $post_states;
		}

		$header_all_id     = get_theme_mod( 'pchdbd_all' );
		$header_archive_id = get_theme_mod( 'pchdbd_archive' );
		$header_page_id    = get_theme_mod( 'pchdbd_page' );
		$header_post_id    = get_theme_mod( 'pchdbd_post' );
		$header_home_id    = get_theme_mod( 'pchdbd_homepage' );
		$header_woo_id     = get_theme_mod( 'pchdbd_woocommerce' );

		if ( $header_home_id && $post->post_name == $header_home_id ) {
			$post_states[] = esc_attr__( 'Homepage Header Template', 'soledad' );
		}

		if ( $header_woo_id && $post->post_name == $header_woo_id ) {
			$post_states[] = esc_attr__( 'WooCommerce Header Template', 'soledad' );
		}

		if ( $header_all_id && $post->post_name == $header_all_id ) {
			$post_states[] = esc_attr__( 'Entire Header Template', 'soledad' );
		}

		if ( $header_archive_id && $post->post_name == $header_archive_id ) {
			$post_states[] = esc_attr__( 'Archive Header Template', 'soledad' );
		}

		if ( $header_page_id && $post->post_name == $header_page_id ) {
			$post_states[] = esc_attr__( 'Pages Header Template', 'soledad' );
		}

		if ( $header_post_id && $post->post_name == $header_post_id ) {
			$post_states[] = esc_attr__( 'Single Post Header Template', 'soledad' );
		}

		return $post_states;
	}

	public static function register_all_elements(): array {
		$elements = array(
			'pb_logo'                   => esc_attr__( 'Logo Desktop', 'soledad' ),
			'pb_logo_sticky'            => esc_attr__( 'Sticky Logo', 'soledad' ),
			'pb_logo_sidebar'           => esc_attr__( 'Logo Sidebar', 'soledad' ),
			'pb_main_menu'              => esc_attr__( 'Menu 1', 'soledad' ),
			'pb_second_menu'            => esc_attr__( 'Menu 2', 'soledad' ),
			'pb_third_menu'             => esc_attr__( 'Menu 3', 'soledad' ),
			'pb_dropdown_menu'          => esc_attr__( 'Dropdown Menu', 'soledad' ),
			'pb_mobile_menu'            => esc_attr__( 'Mobile Menu Button', 'soledad' ),
			'pb_date_time'              => esc_attr__( 'Date Time', 'soledad' ),
			'pb_search_icon'            => esc_attr__( 'Search Icon', 'soledad' ),
			'pb_search_form'            => esc_attr__( 'Search Form', 'soledad' ),
			'pb_search_form_sidebar'    => esc_attr__( 'Search Form Mobile', 'soledad' ),
			'pb_social_icon'            => esc_attr__( 'Social Icon', 'soledad' ),
			'pb_social_icon_mobile'     => esc_attr__( 'Social Icon', 'soledad' ),
			'pb_button'                 => esc_attr__( 'Button 1', 'soledad' ),
			'pb_button_2'               => esc_attr__( 'Button 2', 'soledad' ),
			'pb_button_3'               => esc_attr__( 'Button 3', 'soledad' ),
			'pb_news_ticker'            => esc_attr__( 'News Ticker', 'soledad' ),
			'pb_hamburger_menu'         => esc_attr__( 'Hamburger Menu', 'soledad' ),
			'pb_login_register'         => esc_attr__( 'Login/Register', 'soledad' ),
			'pb_login_register_mobile'  => esc_attr__( 'Mobile Login/Register', 'soledad' ),
			'pb_block'                  => esc_attr__( 'Penci Block 1', 'soledad' ),
			'pb_block_2'                => esc_attr__( 'Penci Block 2', 'soledad' ),
			'pb_html_ad'                => esc_attr__( 'Custom HTML 1', 'soledad' ),
			'pb_html_ad_2'              => esc_attr__( 'Custom HTML 2', 'soledad' ),
			'pb_html_ad_3'              => esc_attr__( 'Custom HTML 3', 'soledad' ),
			'pb_shortcode'              => esc_attr__( 'Shortcode 1', 'soledad' ),
			'pb_shortcode_2'            => esc_attr__( 'Shortcode 2', 'soledad' ),
			'pb_shortcode_3'            => esc_attr__( 'Shortcode 3', 'soledad' ),
			'pb_vertical_line_1'        => esc_attr__( 'Line Divider 1', 'soledad' ),
			'pb_vertical_line_2'        => esc_attr__( 'Line Divider 2', 'soledad' ),
			'pb_vertical_line_3'        => esc_attr__( 'Line Divider 3', 'soledad' ),
			'pb_vertical_line_4'        => esc_attr__( 'Line Divider 4', 'soledad' ),
			'pb_vertical_line_5'        => esc_attr__( 'Line Divider 5', 'soledad' ),
			'pb_logo_mobile'            => esc_attr__( 'Logo Mobile', 'soledad' ),
			'pb_mobile_nav'             => esc_attr__( 'Mobile Menu Navigation', 'soledad' ),
			'pb_button_mobile'          => esc_attr__( 'Button 1', 'soledad' ),
			'pb_button_mobile_2'        => esc_attr__( 'Button 2', 'soledad' ),
			'pb_html_ad_mobile'         => esc_attr__( 'Custom HTML', 'soledad' ),
			'pb_html_ad_mobile_2'       => esc_attr__( 'Custom HTML 2', 'soledad' ),
			'pb_shortcode_mobile'       => esc_attr__( 'Shortcode', 'soledad' ),
			'pb_vertical_line_mobile_1' => esc_attr__( 'Line Divider 1', 'soledad' ),
			'pb_vertical_line_mobile_2' => esc_attr__( 'Line Divider 2', 'soledad' ),
		);
		if ( defined( 'PENCI_BL_VERSION' ) ) {
			$elements['pb_bookmark'] = esc_attr__( 'Bookmark Button', 'soledad' );
		}
		if ( get_theme_mod( 'penci_dms_enable' ) ) {
			$elements['pb_darkmode'] = esc_attr__( 'Dark Mode Switcher', 'soledad' );
		}
		if ( class_exists( 'WooCommerce' ) ) {
			$elements['pb_cart_icon']     = esc_attr__( 'Cart Icon', 'soledad' );
			$elements['pb_compare_icon']  = esc_attr__( 'Compare Icon', 'soledad' );
			$elements['pb_wishlist_icon'] = esc_attr__( 'WishList Icon', 'soledad' );
		}

		return $elements;
	}

	public function clear_settings( WP_Customize_Manager $wp_customize ) {
		if ( isset( $_GET['layout_id'] ) ) {
			$panels   = $wp_customize->panels();
			$sections = $wp_customize->sections();

			// Optimize `in_array` by using `array_flip` for faster lookups
			$excluded_panels = array_flip( array( 'widgets', 'nav_menus', 'header_builder_config' ) );

			foreach ( $panels as $panel_id => $object ) {
				if ( ! isset( $excluded_panels[ $panel_id ] ) ) {
					$wp_customize->remove_panel( $panel_id );
				}
			}

			foreach ( $sections as $section_id => $object ) {
				// Perform `strpos()` only once for each section
				if ( strpos( $section_id, 'penci_header_' ) === false ) {
					$wp_customize->remove_section( $section_id );
				}
			}
		} else {
			// Remove only the necessary panel when layout_id is not set
			$wp_customize->remove_panel( 'header_builder_config' );
		}
	}

	public function register_customizer_settings( WP_Customize_Manager $wp_customize ) {
		require_once PENCI_SOLEDAD_DIR . '/inc/builder/customizer/select2.php';
		require_once PENCI_SOLEDAD_DIR . '/inc/builder/customizer/buider_config.php';
	}

	public function customizer_cleaner( $components ) {

		if ( isset( $_GET['layout_id'] ) && ! empty( $_GET['layout_id'] ) ) {
			$components = array();
		}

		return $components;
	}

	public function preview_customize() {
		global $wp_customize;
		$customizer_save = isset( $_GET['layout_id'] ) && ! empty( $_GET['layout_id'] ) ? $_GET['layout_id'] : '';
		if ( ! empty( $customizer_save ) ) {
			$header_data = get_post_meta( $customizer_save, 'settings_content', true );
			$header_data = wp_parse_args( $header_data, self::preview_default_data() );

			$mods         = get_theme_mods();
			$theme        = get_option( 'stylesheet' );
			$uncover_data = self::exclude_settings();

			foreach ( $mods as $theme_mod_id => $theme_mod_value ) {
				if ( strpos( $theme_mod_id, 'penci_header' ) !== false && ! in_array( $theme_mod_id, $uncover_data ) ) {
					unset( $mods[ $theme_mod_id ] );
				}
			}

			if ( is_array( $header_data ) && ! empty( $header_data ) ) {
				foreach ( $header_data as $save => $this_value ) {
					if ( ! in_array( $save, $uncover_data ) ) {
						unset( $mods[ $save ] );
						$mods[ $save ] = $this_value;
						$wp_customize->set_post_value( $save, $this_value );
					}
				}
			}
			update_option( "theme_mods_$theme", $mods );
		}
	}

	public function preview_default_data() {
		$default_element_data        = array();
		$builder_rows_desktop        = array( 'topblock', 'top', 'mid', 'bottom', 'bottomblock' );
		$builder_rows_desktop_sticky = array(
			'top'    => 'Sticky Top',
			'mid'    => 'Sticky Middle',
			'bottom' => 'Sticky Bottom',
		);
		$builder_columns_desktop     = array( 'left', 'center', 'right' );
		$builder_rows_mobile         = array( 'top', 'mid', 'bottom' );
		$builder_columns_mobile      = array( 'left', 'center', 'right' );
		$builder_row_sidebar         = array( 'top', 'bottom' );
		$builder_columns_sidebar     = array( 'center' );

		foreach ( $builder_rows_desktop as $row ) {
			foreach ( $builder_columns_desktop as $column ) {
				$default_element_data["penci_hb_element_desktop_{$row}_{$column}"] = '';
				$default_element_data["penci_hb_flex_desktop_{$row}_{$column}"] = 'row';
				$default_element_data["penci_hb_align_desktop_{$row}_{$column}"]   = $column;
			}
		}

		foreach ( $builder_rows_mobile as $row ) {
			foreach ( $builder_columns_mobile as $column ) {
				$default_element_data["penci_hb_element_mobile_{$row}_{$column}"] = '';
				$default_element_data["penci_hb_align_mobile_{$row}_{$column}"]   = $column;
			}
		}

		foreach ( $builder_rows_desktop_sticky as $row => $row_name ) {
			foreach ( $builder_columns_desktop as $column ) {
				$default_element_data["penci_hb_element_desktop_sticky_{$row}_{$column}"] = '';
				$default_element_data["penci_hb_flex_desktop_sticky_{$row}_{$column}"] = 'row';
				$default_element_data["penci_hb_align_desktop_sticky_{$row}_{$column}"]   = $column;
			}
		}

		foreach ( $builder_row_sidebar as $row ) {
			foreach ( $builder_columns_sidebar as $column ) {
				$default_element_data["penci_hb_element_mobile_drawer_{$row}_{$column}"] = '';
				$default_element_data["penci_hb_align_mobile_drawer_{$row}_{$column}"]   = $column;
			}
		}

		return $default_element_data;
	}

	public function exclude_settings() {
		return array(
			'penci_header_enable_transparent',
			'penci_header_padding',
			'penci_header_layout',
			'penci_header_menu_ani_style',
			'penci_header_ctwidth',
			'penci_header_3_banner',
			'penci_header_3_banner_url',
			'penci_header_3_adsense',
			'penci_header_social_check',
			'penci_header_social_brand',
			'penci_header_slogan_text',
			'penci_header_remove_line_slogan',
			'penci_header_menu_style',
			'penci_header_enable_padding',
			'penci_header_remove_line_hover',
			'penci_header_social_nav',
			'penci_header_hidesocial_nav',
			'penci_header_logo_mobile',
			'penci_header_logo_mobile_center',
			'penci_header_signup_padding',
			'penci_header_signup_fdesc',
			'penci_header_signup_finput',
			'penci_header_signup_fsubmit',
			'penci_header_signup_bg',
			'penci_header_signup_color',
			'penci_header_signup_input_border',
			'penci_header_signup_input_color',
			'penci_header_signup_submit_bg',
			'penci_header_signup_submit_color',
			'penci_header_signup_submit_bg_hover',
			'penci_header_signup_submit_color_hover',
			'penci_header_logo_vertical',
			'penci_header_social_vertical',
			'penci_header_social_vertical_brand',
			'penci_header_background_color',
			'penci_header_background_image',
			'penci_header_social_color',
			'penci_header_social_color_hover',
			'penci_header_slogan_color',
			'penci_header_slogan_line_color',
			'penci_header_tran_social_color',
			'penci_header_tran_social_color_hover',
			'penci_header_tran_slogan_color',
			'penci_header_tran_slogan_line_color',
			'penci_hbg_logo_max_width',
		);
	}

	public function header_builder_js() {
		if ( isset( $_GET['layout_id'] ) && ! empty( $_GET['layout_id'] ) ) {
			wp_enqueue_style( 'fontawesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css' );
			wp_enqueue_style( 'penci-header-builder', PENCI_BUILDER_URL . 'assets/css/header-builder.css' );
			wp_enqueue_script( 'selectize', PENCI_BUILDER_URL . 'assets/js/selectize.min.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
			wp_enqueue_script( 'penci-builder-saved', PENCI_BUILDER_URL . 'assets/js/control-saved.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
			wp_localize_script(
				'penci-builder-saved',
				'penci_hdbd',
				array(
					'nonce' => wp_create_nonce( 'penci-hdbd-ajax-nonce' ),
				)
			);

			wp_enqueue_script( 'penci-header-builder', PENCI_BUILDER_URL . 'assets/js/header-builder.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
			wp_enqueue_script( 'penci-customizer-builder', PENCI_BUILDER_URL . 'assets/js/customizer.js', array( 'jquery' ), PENCI_SOLEDAD_VERSION, true );
		}
	}

	public function header_builder_html() {
		$template = new Template( PENCI_BUILDER_PATH . 'customizer-template/' );
		$template->render( 'header-builder', array( 'template' => $template ), true );
	}

	public function builder_post_type() {
		$show_menu = get_theme_mod( 'penci_hide_header_builder' ) ? false : true;
		register_post_type(
			'penci_builder',
			array(
				'labels'              => array(
					'name'           => 'Header Builder',
					'name_admin_bar' => _x( 'Header Builder', 'Add New on Toolbar', 'textdomain' ),
					'edit_item'      => __( 'Edit Header', 'soledad' ),
					'view_item'      => __( 'Preview Header', 'soledad' ),
					'add_new'        => __( 'Add New Header', 'soledad' ),
					'add_new_item'   => __( 'Add New Header', 'soledad' ),
					'new_item'       => __( 'Add New Header', 'soledad' ),
				),
				'public'              => false,
				'publicly_queryable'  => false,
				'show_ui'             => $show_menu,
				'show_in_menu'        => $show_menu,
				'show_in_nav_menus'   => $show_menu,
				'query_var'           => false,
				'rewrite'             => false,
				'capability_type'     => 'post',
				'menu_icon'           => 'dashicons-buddicons-topics',
				'map_meta_cap'        => true,
				'has_archive'         => false,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'menu_position'       => null,
				'supports'            => array( 'title' ),
			)
		);
	}

	public function save_header_builder() {
		check_ajax_referer( 'penci-hdbd-ajax-nonce', 'nonce' );
		$builder_settings = $_REQUEST['setting_value'];
		$setting_id       = (int) esc_attr( $_REQUEST['setting_id'] );

		if ( ! empty( $builder_settings ) ) {

			$header_data      = get_post( $setting_id );
			$header_name      = $header_data->post_name;
			$current_settings = get_post_meta( $setting_id, 'settings_content', true );
			

			if ( ! empty( $current_settings ) ) {
				$builder_settings = wp_parse_args( wp_parse_args( $builder_settings, self::preview_default_data() ), $current_settings );
			}

			$builder_id = wp_update_post(
				array(
					'ID'          => (int) $setting_id,
					'post_status' => 'publish',
					'meta_input'  => array(
						'settings_content'  => $builder_settings,
					),
				)
			);

			if ( $builder_id ) {
				wp_send_json_success(
					array(
						'builder_id' => $builder_id,
						'message'    => 'Save builder settings successfully',
						'notice'     => 'updated',
					),
					200
				);
			} else {
				wp_send_json_error(
					array(
						'message' => esc_attr__( 'Can not saved this settings', 'soledad' ),
						'notice'  => 'error',
					),
					200
				);
			}
		} else {
			wp_send_json_error(
				array(
					'message' => esc_attr__( 'Missing setting content.', 'soledad' ),
					'notice'  => 'error',
				),
				200
			);
		}

		wp_die();
	}

	public function penci_builder_editpost_link( $post ) {
		if ( 'penci_builder' === $post->post_type ) {
			$query['autofocus[panel]'] = 'header_builder_config';
			$query['layout_id']        = $post->ID;
			$link                      = add_query_arg( $query, admin_url( 'customize.php' ) );
			?>
            <div class="penci-builder-button customize">
                <a data-id="<?php echo esc_attr( $post->ID ); ?>"
                   class="button"
                   data-href="<?php echo esc_url( $link ); ?>"
                   href="<?php echo esc_url( $link ); ?>"><?php echo esc_attr__( 'Edit with Penci Header Builder', 'soledad' ); ?></a>
            </div>
			<?php
		}
	}

	public function add_edit_link( $actions, $post ) {

		$export_posttype = array(
			'penci_builder',
		);

		if ( in_array( $post->post_type, $export_posttype ) ) {

			$query['autofocus[panel]'] = 'header_builder_config';
			$query['layout_id']        = $post->ID;
			$link                      = add_query_arg( $query, admin_url( 'customize.php' ) );

			$actions['penci-builder-edit-link'] = '<a href="' . esc_url( $link ) . '" class="penci-builder-edit-link">' . __( 'Edit with Penci Header Builder', 'soledad' ) . '</a>';
		}

		return $actions;
	}

	public function change_link( $permalink, $post ) {
		$post_link = $page_link = $archive_link = home_url();
		$posts     = get_posts(
			array(
				'posts_per_page' => 1,
			)
		);
		$pages     = get_posts(
			array(
				'posts_per_page' => 1,
				'post_type'      => 'page',
			)
		);
		if ( ! empty( $posts ) && isset( $posts[0] ) ) {
			$post_link = get_the_permalink( $posts[0]->ID );
			$post_cat  = get_the_category( $posts[0]->ID );
			if ( ! empty( $post_cat ) && isset( $post_cat[0] ) ) {
				$archive_link = get_term_link( $post_cat[0]->term_id );
			}
		}
		if ( ! empty( $pages ) && isset( $pages[0] ) ) {
			$page_link = get_page_link( $pages[0]->ID );
		}
		if ( $post->post_type == 'penci_builder' ) {

			$arg_query         = array(
				'view-header-layout' => $post->ID,
			);
			$header_archive_id = get_theme_mod( 'pchdbd_archive' );
			$header_page_id    = get_theme_mod( 'pchdbd_page' );
			$header_post_id    = get_theme_mod( 'pchdbd_post' );
			$header_home_id    = get_theme_mod( 'pchdbd_homepage' );
			$header_woo_id     = get_theme_mod( 'pchdbd_woocommerce' );
			$permalink         = add_query_arg( $arg_query, home_url() );

			if ( $header_archive_id == $post->post_name ) {
				$permalink = add_query_arg( $arg_query, $archive_link );
			}

			if ( $header_page_id == $post->post_name ) {
				$permalink = add_query_arg( $arg_query, $page_link );
			}

			if ( $header_post_id == $post->post_name ) {
				$permalink = add_query_arg( $arg_query, $post_link );
			}

			if ( $header_home_id == $post->post_name ) {
				$permalink = add_query_arg( $arg_query, home_url() );
			}

			if ( $header_woo_id == $post->post_name && function_exists( 'wc_get_page_permalink' ) ) {
				$permalink = add_query_arg( $arg_query, wc_get_page_permalink( 'shop' ) );
			}
		}

		return $permalink;
	}

	public function header_template( $template ) {
		if ( isset( $_GET['layout_id'] ) && is_customize_preview() ) {
			$template = locate_template( array( 'inc/builder/customizer-template/header.php' ) );
		}

		return $template;
	}

	public function penci_builder_admin_css() {
		global $typenow;
		if ( 'penci_builder' != $typenow ) {
			return;
		}
		wp_enqueue_style( 'penci-builder-admin', PENCI_SOLEDAD_URL . '/inc/builder/assets/css/admin.css', array(), PENCI_SOLEDAD_VERSION );
		wp_enqueue_script( 'penci-builder-admin', PENCI_SOLEDAD_URL . '/inc/builder/assets/js/admin.js', array(), PENCI_SOLEDAD_VERSION, true );
	}

	public function preview_body_class( $class ) {
		if ( is_customize_preview() && isset( $_GET['layout_id'] ) ) {
			$class[] = 'penci-header-preview-layout';
		}

		return $class;
	}

	public function live_preview() {
		wp_enqueue_script( 'web-font-loader', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', '', '1.0', true );
		wp_enqueue_script(
			'penci-builder-preview',
			PENCI_SOLEDAD_URL . '/inc/builder/assets/js/customizer-preview.js', // Define the path to the JS file
			array( 'jquery', 'customize-preview' ),
			PENCI_SOLEDAD_VERSION,
			true
		);
	}
}
