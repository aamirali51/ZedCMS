<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class PagesOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_single_page_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'       => $this->panelID,
			'title'    => esc_html__( 'Pages', 'soledad' ),
			'priority' => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'penci_section_spage_general_section', esc_html__( 'General', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_section_spage_header_section', esc_html__( 'Page Header', 'soledad' ), $this->panelID, __('Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/page-header.png">this image</a> to know what is "Page Header"','soledad') );
		$this->add_lazy_section( 'penci_section_spage_404_section', esc_html__( '404 Page', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_section_spage_colors_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
	}
}
