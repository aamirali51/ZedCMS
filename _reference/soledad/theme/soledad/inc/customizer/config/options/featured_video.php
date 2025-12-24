<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class FeaturedVideoOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_featured_video_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'          => $this->panelID,
			'title'       => esc_html__( 'Featured Video', 'soledad' ),
			'description' => __( 'You can check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/?video=video-bg&body=boxed-none">this demo</a> to know what is Featured Video Background.<br>This is a powerful WordPress theme, it supports 3 ways to help you can setup a homepage - you can check more <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#homepage">here</a>.<br>And most of all the options here apply when you use <strong>WAY 2: Config Homepage by use Customize</strong>.If you use Elementor or WPBakery to config your pages, you can use background video feature on section/rows to get it display.', 'soledad' ),
			'priority'    => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'pencidesign_section_fvideo_general_section', esc_html__( 'General', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'pencidesign_section_fvideo_colors_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
	}
}
