<?php

namespace PenciSoledadElementor\Modules\PenciCollapsibleControls;

use Elementor\Controls_Manager;
use PenciSoledadElementor\Base\Module_Base;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function __construct() {
		parent::__construct();
		$this->add_actions();
		$this->wp_enqueue_scripts();
	}

	public function get_name() {
		return 'penci-collapsible-controls';
	}

	public function wp_enqueue_scripts() {
		wp_enqueue_style( 'penci-folding' );
		wp_enqueue_script( 'penci-folding' );
	}

	public function register_section( $widget ) {
		$widget->start_controls_section(
			'section_penci_collapsible_controls',
			[
				'tab'   => Controls_Manager::TAB_ADVANCED,
				'label' => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Collapsible', 'soledad' ),
			]
		);

		$widget->end_controls_section();
	}

	public function register_controls( $widget, $args ) {

		// Enable folding control
		$widget->add_control(
			'pencifolding_enable_folding',
			[
				'label'        => esc_html__( 'Enable Folding', 'soledad' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'soledad' ),
				'label_off'    => esc_html__( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'penci-widget-folded-',
			]
		);

		// Folded height control
		$widget->add_responsive_control(
			'pencifolding_folded_height',
			[
				'label'      => esc_html__( 'Folded Height', 'soledad' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'em' ],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 1000,
						'step' => 10,
					],
					'vh' => [
						'min'  => 10,
						'max'  => 100,
						'step' => 1,
					],
					'em' => [
						'min'  => 3,
						'max'  => 50,
						'step' => 0.5,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 200,
				],
				'condition'  => [
					'pencifolding_enable_folding' => 'yes',
				],
				'selectors'  => [
					'{{WRAPPER}}.penci-widget-folded-yes' => 'max-height: {{SIZE}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		// Button alignment
		$widget->add_control(
			'pencifolding_button_alignment',
			[
				'label'     => esc_html__( 'Button Alignment', 'soledad' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'soledad' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'soledad' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'soledad' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'condition' => [
					'pencifolding_enable_folding' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .widget-fold-button-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Button styling
		$widget->add_control(
			'pencifolding_button_style_heading',
			[
				'label'     => esc_html__( 'Button Style', 'soledad' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'pencifolding_enable_folding' => 'yes',
				],
			]
		);

		$widget->add_control(
			'pencifolding_button_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#007cba',
				'condition' => [
					'pencifolding_enable_folding' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .widget-fold-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget->add_control(
			'pencifolding_button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'soledad' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'condition' => [
					'pencifolding_enable_folding' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .widget-fold-button' => 'color: {{VALUE}};',
				],
			]
		);

		$widget->add_control(
			'pencifolding_button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 4,
				],
				'condition'  => [
					'pencifolding_enable_folding' => 'yes',
				],
				'selectors'  => [
					'{{WRAPPER}} .widget-fold-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_control(
			'pencifolding_button_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => 10,
					'right'    => 20,
					'bottom'   => 10,
					'left'     => 20,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'condition'  => [
					'pencifolding_enable_folding' => 'yes',
				],
				'selectors'  => [
					'{{WRAPPER}} .widget-fold-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$widget->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'pencifolding_button_typo',
			'label'     => __( 'Button Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .widget-fold-button',
		) );

	}

	protected function add_actions() {

		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_section' ] );
		add_action( 'elementor/element/common/section_penci_collapsible_controls/before_section_end', [
			$this,
			'register_controls'
		], 10, 2 );

	}
}