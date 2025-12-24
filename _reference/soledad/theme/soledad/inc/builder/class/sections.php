<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW;

/**
 * Class Theme Soledad Customizer
 */
class HeaderBuilderCustomizer {
	/**
	 * @var Customizer
	 */
	private static $instance;

	/**
	 * @var Customizer
	 */
	private $customizer = null;

	/**
	 * Section of Customizer
	 */
	private $list_elements = array(
		'pb_block',
		'pb_block_2',
		'pb_button',
		'pb_button_2',
		'pb_button_3',
		'pb_bookmark',
		'pb_darkmode',
		'pb_button_mobile',
		'pb_button_mobile_2',
		'pb_cart_icon',
		'pb_compare_icon',
		'pb_date_time',
		'pb_dropdown_menu',
		'pb_hamburger_menu',
		'pb_html_ad',
		'pb_html_ad_2',
		'pb_html_ad_3',
		'pb_html_ad_mobile',
		'pb_html_ad_mobile_2',
		'pb_login_register',
		'pb_login_register_mobile',
		'pb_logo',
		'pb_logo_mobile',
		'pb_logo_sidebar',
		'pb_logo_sticky',
		'pb_main_menu',
		'pb_mobile_menu',
		'pb_news_ticker',
		'pb_search_form',
		'pb_search_form_sidebar',
		'pb_search_icon',
		'pb_second_menu',
		'pb_shortcode',
		'pb_shortcode_2',
		'pb_shortcode_3',
		'pb_shortcode_mobile',
		'pb_social_icon',
		'pb_social_icon_mobile',
		'pb_third_menu',
		'pb_vertical_line_1',
		'pb_vertical_line_2',
		'pb_vertical_line_3',
		'pb_vertical_line_4',
		'pb_vertical_line_5',
		'pb_vertical_line_mobile_1',
		'pb_vertical_line_mobile_2',
		'pb_wishlist_icon',
	);

	private $list_settings = array(
		'penci_header_bottombar_setting_section',
		'penci_header_bottomblockbar_setting_section',
		'penci_header_desktop_option_section',
		'penci_header_desktop_sticky_bottom_section',
		'penci_header_desktop_sticky_mid_section',
		'penci_header_desktop_sticky_section',
		'penci_header_desktop_sticky_top_section',
		'penci_header_drawer_container_section',
		'penci_header_midbar_setting_section',
		'penci_header_mobile_bottombar_setting_section',
		'penci_header_mobile_midbar_setting_section',
		'penci_header_mobile_option_section',
		'penci_header_mobile_topbar_setting_section',
		'penci_header_topbar_setting_section',
		'penci_header_topblockbar_setting_section',
	);

	/**
	 * Construct
	 */
	private function __construct() {
		// Need to load Customizer early for this kind of request.
		if ( isset( $_REQUEST['action'] ) && 'penci_fw_customizer_disable_panel' === $_REQUEST['action'] ) {
			$this->customizer = \SoledadFW\Customizer\Customizer::get_instance();
		}

		if ( ! is_admin() || is_customize_preview() ) {
			add_filter( 'soledad_fw_register_lazy_section', array( $this, 'register_lazy_section' ) );
			add_action( 'soledad_fw_register_customizer_option', array( $this, 'load_customizer' ), 95 );
		}
	}

	/**
	 * @return Customizer
	 */
	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function register_lazy_section( $result ) {

		global $wp;
		if ( isset( $wp->query_vars['search'] ) ) {
			return $result;
		}

		$elements = $this->list_elements;
		$settings = $this->list_settings;

		$elements_path = PENCI_SOLEDAD_DIR . '/inc/builder/elements/';
		$settings_path = PENCI_SOLEDAD_DIR . '/inc/builder/customizer/sections/';

		foreach ( $elements as $eid ) {
			$el_id              = 'penci_header_' . $eid . '_section';
			$result[ $el_id ][] = "{$elements_path}/{$eid}/settings.php";
		}

		foreach ( $settings as $sid ) {
			$result[ $sid ][] = "{$settings_path}/{$sid}.php";
		}

		return $result;
	}

	public function load_customizer() {
		$this->customizer = Customizer\Customizer::get_instance();
		new Customizer\HeaderBuilderOption( $this->customizer, 1 );
	}
}
