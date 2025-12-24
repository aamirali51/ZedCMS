<?php

namespace PenciSoledadElementor\Modules\PenciSnapchat\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Base\Base_Color;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciSnapchat extends Base_Widget {

	public function get_name() {
		return 'penci-snapchat';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Snapchat', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-icon-box';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'category' );
	}

	protected function register_controls() {


		// Section layout
		$this->start_controls_section( 'section_settings', array(
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'username', array(
			'label' => __( 'Account Name:', 'soledad' ),
			'type'  => Controls_Manager::TEXT,
		) );

		$this->add_control( 'userid', array(
			'label' => __( 'Account ID:', 'soledad' ),
			'type'  => Controls_Manager::TEXT,
		) );

		$this->add_control( 'avatar', array(
			'label' => __( 'Avatar', 'soledad' ),
			'type'  => Controls_Manager::MEDIA,
		) );

		$this->add_responsive_control( 'avatar_size', array(
			'label'      => __( 'Avatar Size', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => array(
				'%'  => array( 'min' => 0, 'max' => 100 ),
				'px' => array( 'min' => 0, 'max' => 2000 )
			),
			'size_units' => array( '%', 'px' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci_snapchat_widget .pc-snapchat-avatar' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};'
			)
		) );

		$this->add_control( 'style', array(
			'label'   => __( 'Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'rounded',
			'options' => [
				'rounded' => 'Rounded',
				'circle'  => 'Circle',
			]
		) );

		$this->add_control( 'bgstyle', array(
			'label'   => __( 'Background Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'flat',
			'options' => [
				'flat' => 'Flat',
				'dots' => 'Dots',
			]
		) );

		$this->add_responsive_control( 'badge_size', array(
			'label'      => __( 'Custom Badge Size', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => array(
				'%'  => array( 'min' => 0, 'max' => 100 ),
				'px' => array( 'min' => 0, 'max' => 2000 )
			),
			'size_units' => array( '%', 'px' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci_snapchat_widget .pc-snapchat-badge' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};'
			)
		) );

		$this->add_control( 'text_align', array(
			'label'                => __( 'Alignment', 'soledad' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => array(
				'left'   => array(
					'title' => __( 'Left', 'elementor' ),
					'icon'  => 'eicon-text-align-left',
				),
				'center' => array(
					'title' => __( 'Center', 'elementor' ),
					'icon'  => 'eicon-text-align-center',
				),
				'right'  => array(
					'title' => __( 'Right', 'elementor' ),
					'icon'  => 'eicon-text-align-right',
				)
			),
		) );

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		$this->start_controls_section( 'section_style_image', array(
			'label' => __( 'Color & Style', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );


		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'author_name_typo',
			'label'    => __( 'Account Name Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pc-snapchat-wrapper .pc-snapchat-name',
		) );

		$this->add_control( 'name_color', array(
			'label'     => __( 'Account Name Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pc-snapchat-wrapper .pc-snapchat-name' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'author_id_typo',
			'label'    => __( 'Account ID Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pc-snapchat-wrapper .pc-snapchat-id',
		) );

		$this->add_control( 'id_color', array(
			'label'     => __( 'Account ID Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pc-snapchat-wrapper .pc-snapchat-id' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'badge_bcolor', array(
			'label'     => __( 'Badge Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci_snapchat_widget .pc-snapchat-badge' => 'border-color: {{VALUE}};' ),
		) );

		$this->end_controls_section();
		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$instance   = $this->get_settings();
		$ptext_align = isset( $instance['text_align'] ) ? $instance['text_align'] : 'center';
		if ( ! empty( $instance['username'] ) && ! empty( $instance['userid'] ) && ! empty( $instance['avatar'] ) ) {
			$img_class = ! empty( $instance['style'] ) ? 'is-' . $instance['style'] : 'is-rounded';
			$imgstyle  = ! empty( $instance['bgstyle'] ) ? $instance['bgstyle'] : 'flat';
			$userid    = str_replace( '@', '', $instance['userid'] );

			$bghtmlbg_src = 'data-bgset="' . PENCI_SOLEDAD_URL . '/images/snapchat_' . $imgstyle . '.png"';
			$bghtml_src   = 'data-bgset="' . $instance['avatar']['url'] . '"';
			$this->markup_block_title( $instance, $this );
			?>
            <div class="penci_snapchat_widget <?php echo 'pcsnapal-' . $ptext_align; ?>">
                <div class="pc-snapchat-wrapper">
                    <a href="https://snapchat.com/add/<?php echo $userid ?>" rel="external noopener nofollow">
						<span class="pc-snapchat-badge <?php echo $img_class; ?>">
                            <span class="pc-snapchat-badge-over penci-image-holder penci-lazy" <?php echo $bghtmlbg_src; ?>></span>
                            <span class="pc-snapchat-avatar penci-image-holder penci-lazy" <?php echo $bghtml_src; ?>></span>
						</span>
                        <span class="pc-snapchat-name"><?php echo $instance['username'] ?></span>
                        <span class="pc-snapchat-id">@<?php echo $userid ?></span>
                    </a>
                </div>
            </div>
			<?php
		} else {
			echo __( 'Enter the required account info.', 'soledad' );
		}

	}
}
