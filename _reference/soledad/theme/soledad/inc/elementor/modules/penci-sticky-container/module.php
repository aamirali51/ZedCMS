<?php

namespace PenciSoledadElementor\Modules\PenciStickyContainer;

use PenciSoledadElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();
		$this->add_actions();
	}

	public function get_name() {
		return 'penci-sticky-container';
	}

	public function register_section( $element ) {
		$element->start_controls_section(
			'penci_sticky_container_section',
			[
				'label' => __( 'Penci Sticky Container', 'soledad' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);
		$element->end_controls_section();
	}

	public function register_controls( $section, $args ) {

		$section->add_control(
			'sticky_container_on',
			[
				'label'        => esc_html__( 'Enable Sticky', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'description'  => esc_html__( 'Set sticky options by enable this option. Note: If you use this sticky, please avoid the Elementor Pro sticky feature. So as not to conflict.', 'soledad' ),
			]
		);

		$section->add_control(
			'sticky_container_offset',
			[
				'label'     => esc_html__( 'Offset', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
				],
				'condition' => [
					'sticky_container_on' => 'yes',
				],
			]
		);

		$section->add_control(
			'sticky_container_active_bg',
			[
				'label'     => esc_html__( 'Active Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.pencisctn-sticky.pencisctn-active' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'sticky_container_on' => 'yes',
				],
			]
		);

		$section->add_responsive_control(
			'sticky_container_active_padding',
			[
				'label'      => esc_html__( 'Active Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}.pencisctn-sticky.pencisctn-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'sticky_container_on' => 'yes',
				],
			]
		);

		$section->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'label'     => esc_html__( 'Active Box Shadow', 'soledad' ),
				'name'      => 'sticky_container_active_shadow',
				'selector'  => '{{WRAPPER}}.pencisctn-sticky.pencisctn-active',
				'condition' => [
					'sticky_container_on' => 'yes',
				],
			]
		);

		$section->add_control(
			'sticky_container_bottom',
			[
				'label'       => esc_html__( 'Scroll Until', 'soledad' ),
				'description' => esc_html__( 'If you don\'t want to scroll after specific section so set that section ID/CLASS here. for example: #section1 or .section1 it\'s support ID/CLASS', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => [
					'sticky_container_on' => 'yes',
				],
			]
		);

		$section->add_control(
			'sticky_container_on_scroll_up',
			[
				'label'        => esc_html__( 'Sticky on Scroll Up', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'description'  => esc_html__( 'Set sticky options when you scroll up your mouse.', 'soledad' ),
				'condition'    => [
					'sticky_container_on' => 'yes',
				],
			]
		);

		$section->add_control(
			'sticky_container_zindex',
			[
				'label'     => esc_html__( 'Z-Index', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => - 1000,
				'max'       => 9999,
				'condition' => [
					'sticky_container_on' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}.pencisctn-sticky.pencisctn-active' => 'z-index: {{VALUE}};',
				],
			]
		);

		$section->add_control(
			'sticky_container_disable_tablet',
			[
				'label'     => __( 'Turn Off on Tablet', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'sticky_container_on' => 'yes',
				],
				'separator' => 'before',
			]
		);


		$section->add_control(
			'sticky_container_disable_mobile',
			[
				'label'     => __( 'Turn Off on Mobile', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'sticky_container_on' => 'yes',
				],
				'separator' => 'before',
			]
		);

	}


	public function sticky_before_render( $section ) {
		$settings = $section->get_settings_for_display();
		if ( ! empty( $settings['sticky_container_on'] ) == 'yes' ) {
			$sticky_option = [];
			if ( ! empty( $settings['sticky_container_on_scroll_up'] ) ) {
				$section->add_render_attribute( '_wrapper', 'class', 'pencisctn-scrollup' );
			}

			if ( ! empty( $settings['sticky_container_offset']['size'] ) ) {
				$section->add_render_attribute( '_wrapper', 'data-pencisctn-offset', $settings['sticky_container_offset']['size'] );
			}

			if ( ! empty( $settings['sticky_container_disable_tablet'] ) ) {
				$section->add_render_attribute( '_wrapper', 'data-pencisctn-tablet', $settings['sticky_container_off_media'] );
			}
			
			if ( ! empty( $settings['sticky_container_disable_mobile'] ) ) {
				$section->add_render_attribute( '_wrapper', 'data-pencisctn-mobile', $settings['sticky_container_disable_mobile'] );
			}
			
			if ( ! empty( $settings['sticky_container_bottom'] ) ) {
				$section->add_render_attribute( '_wrapper', 'data-pencisctn-stop', $settings['sticky_container_bottom'] );
			}

			$section->add_render_attribute( '_wrapper', 'data-pencisctn-sticky', implode( ";", $sticky_option ) );
			$section->add_render_attribute( '_wrapper', 'class', 'pencisctn-sticky' );
		}
	}

	public function sticky_script_render( $section ) {

		if ( $section->get_settings( 'sticky_container_on' ) == 'yes' ) {
			wp_enqueue_script( 'penci-sticky-container' );
		}

	}

	protected function add_actions() {

		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_section' ] );
		add_action( 'elementor/element/section/penci_sticky_container_section/before_section_end', [
			$this,
			'register_controls'
		], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'sticky_before_render' ], 10, 1 );
		add_action( 'elementor/frontend/section/after_render', [ $this, 'sticky_script_render' ], 10, 1 );


		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_section' ] );
		add_action( 'elementor/element/container/penci_sticky_container_section/before_section_end', [
			$this,
			'register_controls'
		], 10, 2 );
		add_action( 'elementor/frontend/container/before_render', [ $this, 'sticky_before_render' ], 10, 1 );
		add_action( 'elementor/frontend/container/after_render', [ $this, 'sticky_script_render' ], 10, 1 );

	}
}