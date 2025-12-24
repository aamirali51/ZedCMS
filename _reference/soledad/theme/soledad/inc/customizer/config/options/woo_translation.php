<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class WooTranslationOption extends CustomizerOptionAbstract {

	public function set_option() {
		$this->set_section();
	}

	public function set_section() {
		$this->add_lazy_section( 'pencidesign_woo_section_transition_lang_section', esc_html__( 'WooCommerce Text Translation', 'soledad' ), '', __("If you are using WPML or Polylang - Use shortcode [pencilang] inside fields below with multiple languages - Example: <strong>[pencilang en_US='Share' fr_FR='Partager' language_code='Your language text' /]</strong><br>Make sure plugin Penci Shortcodes are activated. You can check languages code <a class='wp-customizer-link' href='https://make.wordpress.org/polyglots/teams/' target='_blank'>here</a>","soledad"));
	}
}
