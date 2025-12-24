<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class HeaderBuilderOption extends CustomizerOptionAbstract {

	public $panelID = 'header_builder_config';

	public function set_option() {
		$this->set_section();
	}

	public function set_section() {
		$this->add_lazy_section( 'penci_header_pb_block_section', esc_html__( 'Block 1', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_block_2_section', esc_html__( 'Block 2', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_button_section', esc_html__( 'Button 1', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_bookmark_section', esc_html__( 'Bookmark Button', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_button_2_section', esc_html__( 'Button 2', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_button_3_section', esc_html__( 'Button 3', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_button_mobile_section', esc_html__( 'Mobile Button 1', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_button_mobile_2_section', esc_html__( 'Mobile Button 2', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_cart_icon_section', esc_html__( 'Cart Icon', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_compare_icon_section', esc_html__( 'Compare Icon', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_dropdown_menu_section', esc_html__( 'Mobile Sidebar Menu', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_hamburger_menu_section', esc_html__( 'Hamburger Menu', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_html_ad_section', esc_html__( 'Custom HTML 1', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_html_ad_2_section', esc_html__( 'Custom HTML 2', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_html_ad_3_section', esc_html__( 'Custom HTML 3', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_html_ad_mobile_section', esc_html__( 'Mobile Custom HTML', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_html_ad_mobile_2_section', esc_html__( 'Mobile Custom HTML 2', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_login_register_section', esc_html__( 'Login/Register', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_login_register_mobile_section', esc_html__( 'Mobile Login/Register', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_logo_section', esc_html__( 'Logo', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_logo_mobile_section', esc_html__( 'Logo Mobile', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_logo_sidebar_section', esc_html__( 'Logo Sidebar', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_logo_sticky_section', esc_html__( 'Logo Sticky', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_main_menu_section', esc_html__( 'Main Menu', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_mobile_menu_section', esc_html__( 'Mobile Menu', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_news_ticker_section', esc_html__( 'News Ticker', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_search_form_section', esc_html__( 'Search Form', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_search_form_sidebar_section', esc_html__( 'Sidebar Search Form', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_search_icon_section', esc_html__( 'Search Icon', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_darkmode_section', esc_html__( 'Dark Mode Switcher', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_second_menu_section', esc_html__( 'Second Navigation Menu', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_shortcode_section', esc_html__( 'Shortcode 1', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_shortcode_2_section', esc_html__( 'Shortcode 2', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_shortcode_3_section', esc_html__( 'Shortcode 3', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_shortcode_mobile_section', esc_html__( 'Shortcode Mobile 1', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_social_icon_section', esc_html__( 'Social Icons', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_social_icon_mobile_section', esc_html__( 'Mobile Social Icons', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_third_menu_section', esc_html__( 'Third Navigation', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_vertical_line_1_section', esc_html__( 'Vertical Line 1', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_vertical_line_2_section', esc_html__( 'Vertical Line 2', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_vertical_line_3_section', esc_html__( 'Vertical Line 3', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_vertical_line_4_section', esc_html__( 'Vertical Line 4', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_vertical_line_5_section', esc_html__( 'Vertical Line 5', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_vertical_line_mobile_1_section', esc_html__( 'Mobile Vertical Line 1', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_vertical_line_mobile_2_section', esc_html__( 'Mobile Vertical Line 2', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_wishlist_icon_section', esc_html__( 'Wishlist Icon', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_pb_date_time_section', esc_html__( 'Date/Time', 'soledad' ), $this->panelID );

		$this->add_lazy_section( 'penci_header_bottombar_setting_section', esc_html__( 'Header Bottom Bar', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_bottomblockbar_setting_section', esc_html__( 'Header Bottom Block', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_desktop_option_section', esc_html__( 'Header Desktop Options', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_desktop_sticky_bottom_section', esc_html__( 'Header Sticky Bottom', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_desktop_sticky_mid_section', esc_html__( 'Header Sticky Middle', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_desktop_sticky_section', esc_html__( 'Header Sticky Options', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_desktop_sticky_top_section', esc_html__( 'Header Sticky Top', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_drawer_container_section', esc_html__( 'Mobile Sidebar', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_midbar_setting_section', esc_html__( 'Header Middle Bar', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_mobile_bottombar_setting_section', esc_html__( 'Mobile Bottom Bar', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_mobile_midbar_setting_section', esc_html__( 'Mobile Middle Bar', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_mobile_option_section', esc_html__( 'Mobile Options', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_mobile_topbar_setting_section', esc_html__( 'Mobile Top Bar', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_topbar_setting_section', esc_html__( 'Header Top Bar', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_header_topblockbar_setting_section', esc_html__( 'Header Top Block', 'soledad' ), $this->panelID );
	}
}
