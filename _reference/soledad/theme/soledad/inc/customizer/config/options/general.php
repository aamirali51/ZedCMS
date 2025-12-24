<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class GeneralOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_general_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'       => $this->panelID,
			'title'    => esc_html__( 'General', 'soledad' ),
			'priority' => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'pencidesign_new_section_general_section', esc_html__( 'General Settings', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_body_boxed_section', esc_html__( 'Body Boxed', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_archive_page_section', esc_html__( 'Category, Tags, Search, Archive Pages', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_posts_page_settings_section', esc_html__( 'Posts Page Settings', 'soledad' ), $this->panelID, __( 'You need to set a blog page by go to dashboard > Settings > Reading > Posts page' ) );
		$this->add_lazy_section( 'pencidesign_general_search_page_section', esc_html__( 'Search Settings', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_gdpr_section', esc_html__( 'GDPR Policy', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_social_sharing_section', esc_html__( 'Like Posts & Social Sharing', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_image_sizes_section', esc_html__( 'Manage Image Sizes', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_schema_markup_section', esc_html__( 'Schema Markup', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_typography_section', esc_html__( 'Typography', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_colors_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_colors_dark_section', esc_html__( 'Dark Mode', 'soledad' ), $this->panelID, __( 'You need to use the default theme in light mode to get it works perfectly.', 'soledad' ) );
		$this->add_lazy_section( 'pencidesign_general_cursor_section', esc_html__( 'Cursor Effect', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_lvn_section', esc_html__( 'Live Viewer Notification', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_ageverify_section', esc_html__( 'Age Verify', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_userprofile_section', esc_html__( 'User Profile', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_linkmanager_section', esc_html__( 'Links Manager', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_maintenance_mode_section', esc_html__( 'Maintenance Mode', 'soledad' ), $this->panelID, __( 'Set your entire website as MAINTENANCE MODE, meaning the site is offline temporarily for maintenance, or set it as COMING SOON mode, meaning the site is offline until it is ready to be launched.', 'soledad' ) );
		$this->add_lazy_section( 'pencidesign_general_accessibility_section', esc_html__( 'Accessibility', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_general_extra_section', esc_html__( 'Extra Options', 'soledad' ), $this->panelID );
	}
}
