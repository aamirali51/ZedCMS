<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class VerticalNavHamburgeOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_vernav_hamburger_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'          => $this->panelID,
			'title'       => esc_html__( 'Vertical Navigation & Hamburger Menu', 'soledad' ),
			'description' => __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/vertical-navigation.png">this image</a> to know what is Vertical Navigation and <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/hamburger.png">this image</a> to know what is Hamburger Menu.<br>Most of the options here applies for both Vertical Navigation & Hamburger Menu. When you enable Vertical Navigation - of course, the Hamburger Menu will does not display.', 'soledad' ),
			'priority'    => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'penci_menu_hbg_section', esc_html__( 'General', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/vertical-navigation.png">this image</a> to know what is Vertical Navigation and <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/hamburger.png">this image</a> to know what is Hamburger Menu.<br>Most of the options here applies for both Vertical Navigation & Hamburger Menu. When you enable Vertical Navigation - of course, the Hamburger Menu will does not display.', 'soledad' ) );
		$this->add_lazy_section( 'penci_menu_hbg_widgets_section', esc_html__( 'Widgets', 'soledad' ), $this->panelID, __( 'When you use Vertical Navigation or Hamburger Menu, you can add widgets display above or below the menu like on <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/hamburger-widgets.png">this image</a> via Appearance > Widgets > drag & drop widgets on the left side to "Sidebar Hamburger Widgets Above Menu" and "Sidebar Hamburger Widgets Below Menu" - check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/hamburger-widgets2.png">this image</a>.', 'soledad' ) );
		$this->add_lazy_section( 'penci_menu_hbg_colors_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
	}
}
