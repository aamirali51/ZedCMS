<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class TopbarOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_topbar_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'          => $this->panelID,
			'title'       => esc_html__( 'TopBar', 'soledad' ),
			'description' => __( 'Check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/topbar.png">this image</a> to know what is TopBar', 'soledad' ),
			'priority'    => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'pencidesign_new_section_topbar_section', esc_html__( 'General Settings', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/topbar.png">this image</a> to know what is TopBar', 'soledad' ));
		$this->add_lazy_section( 'pencidesign_topbar_section_fontsize_section', esc_html__( 'Font Size', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_topbar_section_colors_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
	}
}
