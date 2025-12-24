<?php
namespace PenciSoledadElementor\Modules\PenciPintersest\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciPintersest extends Base_Widget {

	public function get_name() {
		return 'penci-pintersest';
	}

	public function get_title() {
		return penci_get_theme_name('Penci').' '.esc_html__( ' Pinterest', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}
	
	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'pinterest' );
	}
	
	protected function register_controls() {
		

		$this->start_controls_section(
			'section_pinterest', array(
				'label' => esc_html__( 'Pinterest', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pusername', array(
				'label'       =>  __( 'Enter the <strong style="color: #ff0000;">username</strong> or <strong style="color: #ff0000;">username/board_name</strong> for load images:', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'thefirstmess/animals-cuteness',
				'label_block' => true,
				'description' => __('Example if you want to load a board has url <strong style="color: #ff0000;"><a href="https://www.pinterest.com/thefirstmess/animals-cuteness" target="_blank">https://www.pinterest.com/thefirstmess/animals-cuteness</a></strong> You need to fill <strong style="color: #ff0000;">thefirstmess/animals-cuteness</strong>','soledad' ),
			)
		);
		$this->add_control(
			'pnumbers', array(
				'label'       => __( 'Number of images to show', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 9,
			)
		);

		$this->add_control(
			'pcache', array(
				'label'       => __( 'Cache life time ( unit is seconds )', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1200,
			)
		);

		$this->add_control(
			'pfollow', array(
				'label'     => esc_html__( 'Display more link with username text?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'soledad' ),
				'label_off' => __( 'No', 'soledad' ),
				'default'   => 'yes',
			)
		);


		$this->end_controls_section();
		$this->register_block_title_section_controls();
		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		$css_class = 'penci-block-vc penci-pintersest';

		$pusername = $settings['pusername'];
		$pnumbers  = $settings['pnumbers'];
		$pcache    = $settings['pcache'];
		$pfollow   = $settings['pfollow'];
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
			<div class="penci-block_content">
				<div class="penci-pinterest-widget-container">
					<?php
					if ( ! $pusername ) {
						esc_html_e( 'Pinterest data error: pinterest data is not set, please check the ID', 'soledad' );
					}
					$pinboard = new \Penci_Pinterest();
					$pinboard->render_html( $pusername, $pnumbers, $pcache, $pfollow );
					?>
				</div>
			</div>
		</div>
		<?php
	}
}
