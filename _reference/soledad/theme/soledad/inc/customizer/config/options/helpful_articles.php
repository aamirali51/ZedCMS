<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class ArticleFeedbackOption extends CustomizerOptionAbstract {


	public function set_option() {
		$this->set_section();
	}

	public function set_section() {
		$this->add_lazy_section( 'pencidesign_new_section_helpful_articles_section', esc_html__( 'Article Feedback', 'soledad' ), '' );
	}
}