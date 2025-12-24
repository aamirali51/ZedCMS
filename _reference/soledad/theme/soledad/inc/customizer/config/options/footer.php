<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
class FooterOption extends CustomizerOptionAbstract {

	public $panelID = 'penci_footer_section_panel';

	public function set_option() {
		$this->set_panel();
		$this->set_section();
	}

	public function set_panel() {
		$this->customizer->add_panel( [
			'id'       => $this->panelID,
			'title'    => esc_html__( 'Footer', 'soledad' ),
			'priority' => $this->id,
		] );
	}

	public function set_section() {
		$this->add_lazy_section( 'penci_footer_builder_config_section', esc_html__( 'Footer Builder', 'soledad' ), $this->panelID, __( 'You can add new and edit a Footer Template on <a class="wp-customizer-link" target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=penci-block' ) ) . '">this page</a>.<br>Please check <a class="wp-customizer-link" href="https://www.youtube.com/watch?v=kUFqsVYyJig&list=PL1PBMejQ2VTwp9ppl8lTQ9Tq7I3FJTT04&t=809s" target="_blank">this video tutorial</a> to know how to setup your footer builder.', 'soledad' ) );
		$this->add_lazy_section( 'penci_section_footer_general_section', esc_html__( 'General', 'soledad' ), $this->panelID );
		$this->add_lazy_section( 'penci_section_footer_widgets_section', esc_html__( 'Footer Widgets Area', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-widgets-area.png">this image</a> to know where is "Footer Widgets Area".<br>To config Footer Widgets, please go to <strong>Appearance > Widgets > drag & drop widgets to "Footer Column #1", "Footer Column #2", "Footer Column #3", "Footer Column #4</strong> - check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-columns.png">this image</a>', 'soledad' ) );
		$this->add_lazy_section( 'penci_section_footer_instagram_section', esc_html__( 'Footer Instagram', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-instagram.png">this image</a> to know where is "Footer Instagram".<br>To config Footer Instagram, please go to <strong>Appearance > Widgets > drag & drop widget "Soledad Instagram Feed" to "Footer Instagram"</strong> - check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-instagram-widgets.png">this image</a>', 'soledad' ) );
		$this->add_lazy_section( 'penci_section_footer_signupform_section', esc_html__( 'Footer SignUp Form', 'soledad' ), $this->panelID, __( 'Please check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-signup-form.png">this image</a> to know where is "Footer Sign-Up Form".<br>To config Footer SignUp Form, please use the HTML markup like we said on the documentation <a class="wp-customizer-link" target="_blank" href="https://soledad.pencidesign.net/soledad-document/#setup_mailchimp">here</a>, after that go to <strong>Appearance > Widgets > drag & drop widget "Mailchimp Sign-Up Form" to "Footer Sign-Up Form"</strong> - check <a class="wp-customizer-link" target="_blank" href="https://imgresources.s3.amazonaws.com/footer-signup-form-widgets.png">this image</a>', 'soledad' ) );
		$this->add_lazy_section( 'penci_section_footer_colors_section', esc_html__( 'Colors', 'soledad' ), $this->panelID );
	}
}
