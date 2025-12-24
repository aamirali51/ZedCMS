<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class LogoHeaderOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_logoheader_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'       => $this->panelID,
			'title'    => esc_html__( 'Logo & Header', 'soledad' ),
			'priority' => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'penci_builder_general_section', esc_html__( 'Header Builder', 'soledad' ), $this->panelID, __( 'You can add new and edit a header builder on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=penci_builder' ) ) . '">this page</a>.<br>Please check <a class="wp-customizer-link" href="https://www.youtube.com/watch?v=kUFqsVYyJig&list=PL1PBMejQ2VTwp9ppl8lTQ9Tq7I3FJTT04&index=4" target="_blank">this video tutorial</a> to know how to setup your header builder.', 'soledad' ) );
		$this->add_lazy_section( 'pencidesign_logo_header_general_section', esc_html__( 'General', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_logo_header_logo_section', esc_html__( 'Logo', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_logo_header_slogan_section', esc_html__( 'Slogan Text', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/slogan-text.png">this image</a> to know what is Slogan text. Note that slogan text does not supports on some header styles', 'soledad' ) );
		$this->add_lazy_section( 'pencidesign_logo_header_primary_menu_section', esc_html__( 'Main Bar & Primary Menu', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/mainbar-primary.png">this image</a> to know what is "Main Bar" and "Primary Menu"', 'soledad' ) );
		$this->add_lazy_section( 'pencidesign_logo_header_category_megamenu_section', esc_html__( 'Category Mega Menu', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/category-mega-menu.png">this image</a> to know what is Category Mega Menu. To config a category mega menu, please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#menu">this guide</a>', 'soledad' ) );
		$this->add_lazy_section( 'penci_section_header_signupform_section', esc_html__( 'Header SignUp Form', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/header-signup-form.png">this image</a> to know where is "Header Sign-Up Form".<br>To config Header SignUp Form, please use the HTML markup like we said on the documentation <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#setup_mailchimp">here</a>, after that go to <strong>Appearance > Widgets > drag & drop widget "Mailchimp Sign-Up Form" to "Header Sign-Up Form"</strong> - check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/header-signup-form-widget.png">this image</a>.<br>If you use Elementor or WPBakery Page Builder to config your page, you can use element "Penci Mailchimp" to get it display.', 'soledad' ) );
		$this->add_lazy_section( 'pencidesign_logo_header_verticalnav_section', esc_html__( 'Vertical Mobile Navigation', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/vertical-mobile-navigation.png">this image</a> to know what is Vertical Mobile Navigation.', 'soledad' ) );
		$this->add_lazy_section( 'pencidesign_logo_header_colors_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_logo_header_colors_trans_section', esc_html__( 'Colors for Transparent Header', 'soledad' ), $this->panelID );
	}
}
