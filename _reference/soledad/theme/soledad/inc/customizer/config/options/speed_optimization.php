<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class SpeedOptimizationOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_speed_section_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'       => $this->panelID,
			'title'    => esc_html__( 'Speed Optimization', 'soledad' ),
			'priority' => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'penci_section_speed_general_section', esc_html__( 'General & Lazyload', 'soledad' ), $this->panelID, __( 'To use options here in the right way - please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#speed-optimization">this guide</a> first - on <strong>Speed Optimization</strong> section', 'soledad' ) );

		if ( function_exists( 'penci_speed_optimizer_get_stat' ) ) {
			$this->add_lazy_section( 'penci_section_speed_css_section', esc_html__( 'Optimize CSS', 'soledad' ), $this->panelID, __( 'To use options here in the right way - please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#speed-optimization">this guide</a> first - on <strong>Speed Optimization</strong> section', 'soledad' ) );
			$this->add_lazy_section( 'penci_section_speed_javascript_section', esc_html__( 'Optimize JavaScript', 'soledad' ), $this->panelID, __( 'To use options here in the right way - please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#speed-optimization">this guide</a> first - on <strong>Speed Optimization</strong> section', 'soledad' ) );
			$this->add_lazy_section( 'penci_section_speed_html_section', esc_html__( 'Optimize HTML', 'soledad' ), $this->panelID, __( 'To use options here in the right way - please check <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#speed-optimization">this guide</a> first - on <strong>Speed Optimization</strong> section', 'soledad' ) );

		}
	}
}
