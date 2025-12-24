<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class FeaturedSliderOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_featured_slider_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'          => $this->panelID,
			'title'       => esc_html__( 'Featured Slider', 'soledad' ),
			'description' => __( 'This is a powerful WordPress theme, it supports 3 ways to help you can setup a homepage - you can check more <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#homepage">here</a>.<br>And most of all the options here apply when you use <strong>WAY 2: Config Homepage by use Customize</strong>.<br>If you use Elementor or WPBakery to config your pages, you can use element "Penci Featured Slider" to get the slider display.', 'soledad' ),
			'priority'    => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'penci_section_fslider_general_section', esc_html__( 'General', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_section_fslider_fsize_section', esc_html__( 'Font Sizes', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_section_fslider_colors_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
	}
}
