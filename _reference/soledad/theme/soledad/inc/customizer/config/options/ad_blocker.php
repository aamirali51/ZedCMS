<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class AdBlockerOption extends CustomizerOptionAbstract {


	public function set_option() {
		$this->set_section();
	}

	public function set_section() {
		$this->add_lazy_section( 'pencidesign_new_section_adblocker_section', esc_html__( 'Ad Blocker Detector', 'soledad' ), '' );
	}
}
