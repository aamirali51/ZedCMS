<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class SidebarOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_sidebar_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'       => $this->panelID,
			'title'    => esc_html__( 'Sidebar', 'soledad' ),
			'priority' => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'penci_section_sidebar_general_section', esc_html__( 'General', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/widget-heading-title.png">this image</a> to know what is "Sidebar Widget Heading"', 'soledad' ) );
		$this->add_lazy_section( 'penci_section_sidebar_fsize_section', esc_html__( 'Font Size', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_section_sidebar_colors_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
	}
}
