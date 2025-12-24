<?php
namespace PenciSoledadElementor\Modules\PenciFacebookPage\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciFacebookPage extends Base_Widget {

	public function get_name() {
		return 'penci-facebook-page';
	}

	public function get_title() {
		return penci_get_theme_name('Penci').' '.esc_html__( ' Facebook Page', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-fb-feed';
	}
	
	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'facebook', 'social', 'embed', 'page' );
	}

	protected function register_controls() {
		

		// Section layout
		$this->start_controls_section(
			'section_page', array(
				'label' => esc_html__( 'Page', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'page_url', array(
				'label'       => __( 'Facebook Page URL', 'soledad' ),
				'placeholder' => 'https://www.facebook.com/your-page/',
				'default'     => 'https://www.facebook.com/PenciDesign',
				'label_block' => true,
				'description' => __( 'Paste the URL of the Facebook page.', 'soledad' ),
			)
		);

		$this->add_responsive_control(
			'page_height', array(
				'label'   => __( 'Facebook Page Height', 'soledad' ),
				'description' => __( 'This option is only applied when "Hide Stream" option is not checked', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array( 'size' => '290' ),
				'range'   => array( 'px' => array( 'min' => 100, 'max' => 500, ) ),
			)
		);
		
		$this->add_control(
			'hide_cover', array(
				'label'     => __( 'Hide Cover Photo?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
			)
		);
		
		$this->add_control(
			'hide_faces', array(
				'label'     => __( 'Hide Faces', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
			)
		);
		$this->add_control(
			'hide_stream', array(
				'label'     => __( 'Hide Stream', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
			)
		);
		
		$this->add_control(
			'language', array(
				'label'       => __( 'Custom Language', 'soledad' ),
				'placeholder' => 'en_GB',
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Fill the language code to use on Facebook Page Box here. By default, the language will follow the site language. See more <a href="https://developers.facebook.com/docs/internationalization/" target="_blank">here</a>', 'soledad' ),
			)
		);

		$this->end_controls_section();
		$this->register_block_title_section_controls();
		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['page_url'] ) ) {
			echo $this->get_title() . ': ' . esc_html__( 'Please enter a valid URL', 'soledad' );

			return;
		}

		$css_class = 'penci-block-vc penci_facebook_widget';

		$height = $settings['page_height']['size'] ? $settings['page_height']['size'] : '';
		$title_page = $settings['heading'] ? $settings['page_height'] : '';
		$page_url = $settings['page_url'] ? $settings['page_url'] : '';
		$faces = $settings['hide_faces'] ? $settings['hide_faces'] : '';
		$stream = $settings['hide_stream'] ? $settings['hide_stream'] : '';
		$cover = $settings['hide_cover'] ? $settings['hide_cover'] : '';
		$language = $settings['language'] ? $settings['language'] : '';
		
		$lang = $language ? $language : get_locale();
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
			<div class="penci-block_content">
				<div id="fb-root"></div>
				<script data-cfasync="false">(function(d, s, id){
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/<?php echo esc_attr($lang)?>/sdk.js#xfbml=1&version=v9.0";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
				<div class="fb-page" data-href="<?php echo esc_url( $page_url ); ?>"<?php if( ! $stream && is_numeric( $height ) && $height > 69 ): ?> data-height="<?php echo absint( $height ); ?>"<?php endif;?> data-small-header="false" data-hide-cover="<?php if($cover) { echo 'true'; } else { echo 'false'; } ?>" data-show-facepile="<?php if($faces) { echo 'false'; } else { echo 'true'; } ?>" data-show-posts="<?php if($stream) { echo 'false'; } else { echo 'true'; } ?>" data-adapt-container-width="true">
					<div class="fb-xfbml-parse-ignore"><a href="<?php echo esc_attr( $page_url ); ?>"><?php if($title_page) { echo sanitize_text_field( $title_page ); } else { echo 'Facebook'; } ?></a></div>
				</div>
			</div>
		</div>
		<?php
	}
}
