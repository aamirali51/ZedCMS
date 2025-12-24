<?php

namespace PenciSoledadElementor\Modules\PenciTiktokEmbedFeed\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciTiktokEmbedFeed extends Base_Widget {

	public function get_name() {
		return 'penci-tiktok-embed-feed';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' TikTok Feed', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'tiktok', 'social' );
	}

	public function get_script_depends() {
		return array( 'penci_tiktok_embed' );
	}

	protected function register_controls() {


		// Section layout
		$this->start_controls_section( 'section_page', array(
			'label' => esc_html__( 'TikTok Data', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );
		$this->add_control( 'username', array(
			'label'   => __( 'Username ( Without @ )', 'soledad' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_responsive_control( 'width', array(
			'label'      => __( 'Width', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 2000 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .tiktok-embed' => 'width:100%; max-width:{{SIZE}}px'
			]
		) );

		$this->add_control( 'content_horizontal_position', array(
			'label'                => __( 'Content Text Horizontal Position', 'soledad' ),
			'type'                 => Controls_Manager::CHOOSE,
			'label_block'          => false,
			'options'              => array(
				'left'   => array(
					'title' => __( 'Left', 'soledad' ),
					'icon'  => 'eicon-h-align-left',
				),
				'center' => array(
					'title' => __( 'Center', 'soledad' ),
					'icon'  => 'eicon-h-align-center',
				),
				'right'  => array(
					'title' => __( 'Right', 'soledad' ),
					'icon'  => 'eicon-h-align-right',
				),
			),
			'selectors'            => array(
				'{{WRAPPER}} .tiktok-embed' => '{{VALUE}}',
			),
			'selectors_dictionary' => array(
				'left'   => 'margin-right: auto',
				'center' => 'margin-left: auto; margin-right: auto;',
				'right'  => 'margin-left: auto',
			),
		) );

		$this->end_controls_section();
		$this->register_block_title_section_controls();
		$this->start_controls_section( 'section_latest_tiktok_style', array(
			'label' => __( 'TikTok', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'tiktok_text_typo',
			'label'    => __( 'Username Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} section a',
		) );
		$this->add_control( 'tiktok_u_color', array(
			'label'     => __( 'Username Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} section a' => 'color: {{VALUE}};',
			)
		) );
		$this->add_control( 'tiktok_u_hcolor', array(
			'label'     => __( 'Username Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} section a:hover' => 'color: {{VALUE}};',
			)
		) );

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();
		$username = $settings['username'] ? $settings['username'] : '';
		?>
        <div class="pc-tiktok-embed-feed-el">
			<?php
			$this->markup_block_title( $settings, $this );
			if ( $username ) {
				?>
                <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@<?php echo esc_attr( $username ); ?>"
                            data-unique-id="<?php echo esc_attr( $username ); ?>"
                            data-embed-type="creator"
                            style="width: 100%;">
                    <section><a target="_blank"
                                href="https://www.tiktok.com/@<?php echo esc_attr( $username ); ?>">@<?php echo esc_attr( $username ); ?></a>
                    </section>
                </blockquote>
				<?php
			} else {
				_e( 'Please enter your Tiktok username', 'soledad' );
			}
			?>
        </div>
		<?php
	}
}
