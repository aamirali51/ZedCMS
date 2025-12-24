<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class TocOptions extends CustomizerOptionAbstract {

	public $panelID = 'penci_toc_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'       => $this->panelID,
			'title'    => esc_html__( 'Table of Contents', 'soledad' ),
			'priority' => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'pencidesign_toc_general_section', esc_html__( 'General', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_toc_styles_section', esc_html__( 'Font Sizes & Colors', 'soledad' ), $this->panelID );
	}
}
