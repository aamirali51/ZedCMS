<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class PopupOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_popup_section_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'       => $this->panelID,
			'title'    => esc_html__( 'Promo Popup', 'soledad' ),
			'priority' => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'penci_popup_section_general_section', esc_html__( 'General', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_popup_section_display_section', esc_html__( 'Popup Display', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_popup_section_styles_section', esc_html__( 'Styles & Colors', 'soledad' ), $this->panelID );
	}
}
