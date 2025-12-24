<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class PostLayoutOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_post_layout_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'          => $this->panelID,
			'title'       => esc_html__( 'Posts Layouts', 'soledad' ),
			'description' => __( 'All options here apply for Standard Layout, Classic Layout and 1st Posts of 1st Standard & 1st Classic Layout. For other layouts, check on "Other Layouts" section below.', 'soledad' ),
			'priority'    => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'penci_section_standard_classic_section', esc_html__( 'Standard & Classic', 'soledad' ), $this->panelID, __('All options here apply for Standard Layout, Classic Layout and 1st Posts of 1st Standard & 1st Classic Layout. For other layouts, check on "Other Layouts" section below.','soledad') );
		$this->add_lazy_section( 'penci_section_other_layouts_section', esc_html__( 'Other Layouts', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_section_layout_rowsgap_section', esc_html__( 'Row Gap', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_section_layout_fontsize_section', esc_html__( 'Font Size', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_section_layout_colors_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
	}
}
