<?php

namespace PenciSoledadElementor\Modules\PenciCircleMenu\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if accessed directly

class PenciCircleMenu extends Base_Widget {
	public function get_name() {
		return 'penci-circle-menu';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Circle Menu', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-plus-circle-o';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'circle', 'menu', 'rounded' ];
	}

	public function get_style_depends() {
		return [ 'penci-circle-menu' ];
	}

	public function get_script_depends() {
		return [ 'penci-circle-menu' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content_iconnav',
			[
				'label' => esc_html__( 'Circle Menu', 'soledad' ),
			]
		);

		$this->add_control(
			'toggle_icon',
			[
				'label'       => esc_html__( 'Choose Toggle Icon', 'soledad' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options'     => [
					'plus'        => [
						'title' => esc_html__( 'Plus', 'soledad' ),
						'icon'  => 'fas fa-plus',
					],
					'plus-circle' => [
						'title' => esc_html__( 'Plus Circle', 'soledad' ),
						'icon'  => 'fas fa-plus-circle',
					],
					'close'       => [
						'title' => esc_html__( 'Close', 'soledad' ),
						'icon'  => 'fas fa-times',
					],
					'cog'         => [
						'title' => esc_html__( 'Settings', 'soledad' ),
						'icon'  => 'fas fa-cog',
					],
					'menu'        => [
						'title' => esc_html__( 'Bars', 'soledad' ),
						'icon'  => 'fas fa-bars',
					],
					'custom'      => [
						'title' => esc_html__( 'Custom', 'soledad' ),
						'icon'  => 'fas fa-edit',
					],
				],
				'default'     => 'plus',
			]
		);

		$this->add_control(
			'custom_icon',
			[
				'label'       => esc_html__( 'Custom Icon', 'soledad' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'far fa-times-circle',
					'library' => 'fa-regular',
				],
				'condition'   => [
					'toggle_icon' => 'custom',
				],
				'label_block' => false,
				'skin'        => 'inline',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label'   => esc_html__( 'Menu Title', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
				'default' => 'Home',
			]
		);

		$repeater->add_control(
			'circle_menu_icon',
			[
				'label'            => esc_html__( 'Icon', 'soledad' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'fas fa-home',
					'library' => 'fa-solid',
				],
				'label_block'      => false,
				'skin'             => 'inline',
			]
		);

		$repeater->add_control(
			'iconnav_link',
			[
				'label'       => esc_html__( 'Link', 'soledad' ),
				'type'        => Controls_Manager::URL,
				'default'     => [ 'url' => '#' ],
				'dynamic'     => [ 'active' => true ],
				'description' => 'Add your section id WITH the # key. e.g: #my-id also you can add internal/external URL',
			]
		);

		$repeater->add_control(
			'custom_style_popover',
			[
				'label'        => esc_html__( 'Custom Style', 'soledad' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'render_type'  => 'ui',
				'return_value' => 'yes',
			]
		);

		$repeater->start_popover();

		$repeater->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu {{CURRENT_ITEM}}.penci-cmenui a'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-circle-menu {{CURRENT_ITEM}}.penci-cmenui a svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'icon_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu {{CURRENT_ITEM}}.penci-cmenui:hover a'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-circle-menu {{CURRENT_ITEM}}.penci-cmenui:hover a svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'icon_background_color',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu {{CURRENT_ITEM}}.penci-cmenui' => 'background: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'icon_hover_background_color',
			[
				'label'     => esc_html__( 'Hover Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu {{CURRENT_ITEM}}.penci-cmenui:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$repeater->end_popover();

		$this->add_control(
			'circle_menu',
			[
				'label'       => esc_html__( 'Circle Menu Items', 'soledad' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'separator'   => 'before',
				'default'     => [
					[
						'circle_menu_icon' => [ 'value' => 'fas fa-home', 'library' => 'fa-solid' ],
						'iconnav_link'     => [
							'url' => '#',
						],
						'title'            => esc_html__( 'Home', 'soledad' ),
					],
					[
						'circle_menu_icon' => [ 'value' => 'fas fa-shopping-bag', 'library' => 'fa-solid' ],
						'iconnav_link'     => [
							'url' => '#',
						],
						'title'            => esc_html__( 'Products', 'soledad' ),
					],
					[
						'circle_menu_icon' => [ 'value' => 'fas fa-wrench', 'library' => 'fa-solid' ],
						'iconnav_link'     => [
							'url' => '#',
						],
						'title'            => esc_html__( 'Settings', 'soledad' ),
					],
					[
						'circle_menu_icon' => [ 'value' => 'fas fa-book', 'library' => 'fa-solid' ],
						'iconnav_link'     => [
							'url' => '#',
						],
						'title'            => esc_html__( 'Documentation', 'soledad' ),
					],
					[
						'circle_menu_icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ],
						'iconnav_link'     => [
							'url' => '#',
						],
						'title'            => esc_html__( 'Contact Us', 'soledad' ),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'General Settings', 'soledad' ),
			]
		);

		$this->add_control(
			'toggle_icon_position',
			[
				'label'   => esc_html__( 'Toggle Icon Position', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [ 
					''              => esc_html__( 'Default', 'soledad' ),
					'top-left'      => esc_html__( 'Top Left', 'soledad' ),
					'top-center'    => esc_html__( 'Top Center', 'soledad' ),
					'top-right'     => esc_html__( 'Top Right', 'soledad' ),
					'center'        => esc_html__( 'Center', 'soledad' ),
					'center-left'   => esc_html__( 'Center Left', 'soledad' ),
					'center-right'  => esc_html__( 'Center Right', 'soledad' ),
					'bottom-left'   => esc_html__( 'Bottom Left', 'soledad' ),
					'bottom-center' => esc_html__( 'Bottom Center', 'soledad' ),
					'bottom-right'  => esc_html__( 'Bottom Right', 'soledad' ),
				],
			]
		);

		$this->add_control(
			'toggle_icon_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'soledad' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'soledad' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'soledad' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'soledad' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-penci-circle-menu' => 'justify-content: {{VALUE}}; display: flex;',
				],
				'condition' => [
					'toggle_icon_position' => '',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_icon_x_position',
			[
				'label'     => esc_html__( 'Horizontal Offset', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
				],
				'range'     => [
					'px' => [
						'min'  => - 500,
						'step' => 10,
						'max'  => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ep-circle-menu-h-offset: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_icon_y_position',
			[
				'label'     => esc_html__( 'Vertical Offset', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
				],
				'range'     => [
					'px' => [
						'min'  => - 500,
						'step' => 10,
						'max'  => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ep-circle-menu-v-offset: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'trigger',
			[
				'label'     => esc_html__( 'Trigger', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hover',
				'options'   => [
					'hover' => esc_html__( 'Hover', 'soledad' ),
					'click' => esc_html__( 'Click', 'soledad' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_on_trigger',
			[
				'label'   => esc_html__( 'Show Tooltip', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_additional_settings',
			[
				'label' => esc_html__( 'Circle Menu Settings', 'soledad' ),
			]
		);

		$this->add_control(
			'direction',
			[
				'label'   => esc_html__( 'Menu Direction', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom-right',
				'options' => [
					'top'          => esc_html__( 'Top', 'soledad' ),
					'right'        => esc_html__( 'Right', 'soledad' ),
					'bottom'       => esc_html__( 'Bottom', 'soledad' ),
					'left'         => esc_html__( 'Left', 'soledad' ),
					'full'         => esc_html__( 'Full', 'soledad' ),
					'top-left'     => esc_html__( 'Top-Left', 'soledad' ),
					'top-right'    => esc_html__( 'Top-Right', 'soledad' ),
					'top-half'     => esc_html__( 'Top-Half', 'soledad' ),
					'bottom-left'  => esc_html__( 'Bottom-Left', 'soledad' ),
					'bottom-right' => esc_html__( 'Bottom-Right', 'soledad' ),
					'bottom-half'  => esc_html__( 'Bottom-Half', 'soledad' ),
					'left-half'    => esc_html__( 'Left-Half', 'soledad' ),
					'right-half'   => esc_html__( 'Right-Half', 'soledad' ),
				],
			]
		);

		$this->add_control(
			'item_diameter',
			[
				'label'   => esc_html__( 'Circle Menu Size', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range'   => [
					'px' => [
						'min'  => 20,
						'step' => 1,
						'max'  => 50,
					],
				],
			]
		);

		$this->add_control(
			'circle_radius',
			[
				'label'   => esc_html__( 'Circle Menu Distance', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
				],
				'range'   => [
					'px' => [
						'min'  => 20,
						'step' => 5,
						'max'  => 500,
					],
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label'   => esc_html__( 'Speed', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 500,
				],
				'range'   => [
					'px' => [
						'min'  => 100,
						'step' => 10,
						'max'  => 1000,
					],
				],
			]
		);

		$this->add_control(
			'delay',
			[
				'label'   => esc_html__( 'Delay', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1000,
				],
				'range'   => [
					'px' => [
						'min'  => 100,
						'step' => 10,
						'max'  => 2000,
					],
				],
			]
		);

		$this->add_control(
			'step_out',
			[
				'label'   => esc_html__( 'Step Out', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range'   => [
					'px' => [
						'min'  => - 200,
						'step' => 5,
						'max'  => 200,
					],
				],
			]
		);

		$this->add_control(
			'step_in',
			[
				'label'   => esc_html__( 'Step In', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => - 20,
				],
				'range'   => [
					'px' => [
						'min'  => - 200,
						'step' => 5,
						'max'  => 200,
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tooltip_settings',
			[
				'label'     => esc_html__( 'Tooltip Settings', 'soledad' ),
				'condition' => [
					'tooltip_on_trigger' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_text',
			[
				'label'       => esc_html__( 'Tooltip Text', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Click me to show menus', 'soledad' ),
			]
		);

		$this->add_control(
			'tooltip_animation',
			[
				'label'   => esc_html__( 'Animation', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'shift-toward',
				'options' => [
					'shift-away'   => esc_html__( 'Shift-Away', 'soledad' ),
					'shift-toward' => esc_html__( 'Shift-Toward', 'soledad' ),
					'fade'         => esc_html__( 'Fade', 'soledad' ),
					'scale'        => esc_html__( 'Scale', 'soledad' ),
					'perspective'  => esc_html__( 'Perspective', 'soledad' ),
				],
			]
		);

		$this->add_control(
			'tooltip_x_offset',
			[
				'label'   => esc_html__( 'X Offset', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
			]
		);

		$this->add_control(
			'tooltip_y_offset',
			[
				'label'   => esc_html__( 'Y Offset', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
			]
		);

		$this->add_control(
			'tooltip_arrow',
			[
				'label' => esc_html__( 'Arrow', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'tooltip_trigger',
			[
				'label'       => esc_html__( 'Trigger on Click', 'soledad' ),
				'description' => esc_html__( 'Don\'t set yes when you set lightbox image with marker.', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

		//Style
		$this->start_controls_section(
			'section_style_toggle_icon',
			[
				'label' => esc_html__( 'Toggle Icon', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_toggle_icon_style' );

		$this->start_controls_tab(
			'tab_toggle_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'toggle_icon_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_icon_background',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'toggle_icon_border',
				'label'       => esc_html__( 'Border', 'soledad' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-circle-menu li.penci-cmenuti',
			]
		);

		$this->add_responsive_control(
			'toggle_icon_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_icon_padding',
			[
				'label'     => esc_html__( 'Padding', 'soledad' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toggle_icon_shadow',
				'selector' => '{{WRAPPER}} .penci-circle-menu li.penci-cmenuti',
			]
		);

		$this->add_responsive_control(
			'toggle_icon_size',
			[
				'label'     => esc_html__( 'Icon Size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 48,
					],
				],
				'default'   => [
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti a svg' => 'height: {{SIZE}}px; width: {{SIZE}}px;',
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti a i'   => 'font-size: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'transition_function',
			[
				'label'   => esc_html__( 'Transition', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ease',
				'options' => [
					'ease'        => esc_html__( 'Ease', 'soledad' ),
					'linear'      => esc_html__( 'Linear', 'soledad' ),
					'ease-in'     => esc_html__( 'Ease-In', 'soledad' ),
					'ease-out'    => esc_html__( 'Ease-Out', 'soledad' ),
					'ease-in-out' => esc_html__( 'Ease-In-Out', 'soledad' ),
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'toggle_icon_hover_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti:hover'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_icon_hover_background',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_icon_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'toggle_icon_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenuti:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toggle_icon_shadow_hover',
				'label'    => esc_html__( 'Box Shadow', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-circle-menu li.penci-cmenuti:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_circle_menu_icon',
			[
				'label' => esc_html__( 'Circle Icon', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_circle_menu_icon_style' );

		$this->start_controls_tab(
			'tab_circle_menu_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_menu_icon_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu-container .penci-cmenui a'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-circle-menu-container .penci-cmenui svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'circle_menu_icon_background',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenui' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'circle_menu_icon_border',
				'label'       => esc_html__( 'Border', 'soledad' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-circle-menu li.penci-cmenui',
			]
		);

		$this->add_responsive_control(
			'circle_menu_icon_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenui' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'circle_menu_icon_shadow',
				'selector' => '{{WRAPPER}} .penci-circle-menu li.penci-cmenui',
			]
		);

		$this->add_responsive_control(
			'circle_menu_icon_size',
			[
				'label'     => esc_html__( 'Icon Size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'step' => 1,
						'max'  => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li.penci-cmenui' => 'font-size: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'circle_menu_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_menu_icon_hover_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu-container .penci-cmenui:hover a'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-circle-menu-container .penci-cmenui:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'circle_menu_icon_hover_background',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'circle_menu_icon_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'circle_menu_icon_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-circle-menu li:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_tooltip',
			[
				'label'     => esc_html__( 'Tooltip', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tooltip_on_trigger' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'tooltip_width',
			[
				'label'       => esc_html__( 'Width', 'soledad' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [
					'px',
					'em',
				],
				'range'       => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'selectors'   => [
					'.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"]' => 'max-width: calc({{SIZE}}{{UNIT}} - 10px) !important;',
				],
				'render_type' => 'template',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tooltip_typography',
				'selector' => '.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"]',
			]
		);

		$this->add_control(
			'tooltip_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"] .penci-cmenu-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tooltip_color',
			[
				'label'     => esc_html__( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tooltip_text_align',
			[
				'label'     => esc_html__( 'Text Alignment', 'soledad' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
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
				'selectors' => [
					'.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"]' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'tooltip_background',
				'selector' => '.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"], .tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"] .tippy-backdrop',
			]
		);

		$this->add_control(
			'tooltip_arrow_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"] .tippy-arrow' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'tooltip_padding',
			[
				'label'       => esc_html__( 'Padding', 'soledad' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'selectors'   => [
					'.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"] .tippy-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'tooltip_border',
				'label'       => esc_html__( 'Border', 'soledad' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"]',
			]
		);

		$this->add_responsive_control(
			'tooltip_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tooltip_box_shadow',
				'selector' => '.tippy-box[data-theme="penci-cmenu-tippy-{{ID}}"]',
			]
		);

		$this->end_controls_section();
	}

	public function render_loop_iconnav_list() {
		$settings = $this->get_settings_for_display();

		foreach ( $settings['circle_menu'] as $index => $list ) :

			$link_key = 'link_' . $index;
			if ( ! empty( $list['iconnav_link']['url'] ) ) {
				$this->add_link_attributes( $link_key, $list['iconnav_link'] );
			}

			$this->add_render_attribute( $link_key, 'class', 'penci-cmenu-position-center', true );

			if ( isset( $settings['tooltip_on_trigger'] ) && $settings['tooltip_on_trigger'] == 'yes' ) {
				$this->add_render_attribute(
					[
						$link_key => [
							'class'              => 'penci-cmenu-tippy-tooltip penci-cmenu-item',
							'data-tippy'         => '',
							'data-tippy-content' => htmlspecialchars( $list['title'], ENT_QUOTES, 'UTF-8' ),
						],
					],
					'',
					'',
					true
				);
				if ( $settings['tooltip_x_offset']['size'] or $settings['tooltip_y_offset']['size'] ) {
					$this->add_render_attribute( $link_key, 'data-tippy-offset', '[' . $settings['tooltip_x_offset']['size'] . ',' . $settings['tooltip_y_offset']['size'] . ']', true );
				}
				if ( 'yes' == $settings['tooltip_arrow'] ) {
					$this->add_render_attribute( $link_key, 'data-tippy-arrow', 'true', true );
				} else {
					$this->add_render_attribute( $link_key, 'data-tippy-arrow', 'false', true );
				}
				if ( $settings['tooltip_animation'] ) {
					$this->add_render_attribute( $link_key, 'data-tippy-animation', $settings['tooltip_animation'], true );
				}
				if ( 'yes' == $settings['tooltip_trigger'] ) {
					$this->add_render_attribute( $link_key, 'data-tippy-trigger', 'click', true );
				}
			}

			if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
				// add old default
				$settings['icon'] = 'fas fa-arrow-right';
			}

			$migrated = isset( $list['__fa4_migrated']['circle_menu_icon'] );
			$is_new   = empty( $list['icon'] ) && Icons_Manager::is_migration_allowed();

			$this->add_render_attribute( 'menu-item', 'class', 'penci-cmenui elementor-repeater-item-' . esc_attr( $list['_id'] ), true );

			?>
            <li <?php $this->print_render_attribute_string( 'menu-item' ); ?>>
                <a <?php $this->print_render_attribute_string( $link_key ); ?>>
					<?php if ( $list['circle_menu_icon']['value'] ) : ?>
                        <span>

							<?php if ( $is_new || $migrated ) :
								Icons_Manager::render_icon( $list['circle_menu_icon'], [
									'aria-hidden' => 'true',
									'class'       => 'fa-fw'
								] );
							else : ?>
                                <i class="<?php echo esc_attr( $list['icon'] ); ?>" aria-hidden="true"></i>
							<?php endif; ?>

						</span>

					<?php endif; ?>
                </a>
            </li>
		<?php
		endforeach;
	}

	protected function render() {
		$settings    = $this->get_settings_for_display();
		$id          = 'penci-circle-menu-' . $this->get_id();
		$toggle_icon = ( $settings['toggle_icon'] ) ?: 'plus';

		$this->add_render_attribute(
			[
				'circle-menu-container' => [
					'id'    => [
						esc_attr( $id ),
					],
					'class' => [
						'penci-circle-menu-container',
						$settings['toggle_icon_position'] ? 'penci-cmenu-position-fixed penci-cmenu-position-' . $settings['toggle_icon_position'] : '',
					],
				],
			]
		);

		if ( 'custom' == $settings['toggle_icon'] ) {
			$this->add_render_attribute(
				[
					'toggle-icon' => [
						'href'  => [
							'javascript:void(0)',
						],
						'class' => [
							'penci-cmenu-icon',
						],
					],
				]
			);
		} else {
			$this->add_render_attribute(
				[
					'toggle-icon' => [
						'href'  => [
							'javascript:void(0)',
						],
						'class' => [
							'penci-cmenu-icon',
						],
					],
				]
			);
		}

		if ( isset( $settings['tooltip_on_trigger'] ) && ! empty( $settings['tooltip_text'] ) && $settings['tooltip_on_trigger'] == 'yes' ) {
			$this->add_render_attribute(
				[
					'toggle-icon' => [
						'class'              => 'penci-cmenu-tippy-tooltip',
						'data-tippy'         => '',
						'data-tippy-content' => htmlspecialchars( $settings['tooltip_text'], ENT_QUOTES, 'UTF-8' ),
					],
				]
			);
			if ( $settings['tooltip_x_offset']['size'] or $settings['tooltip_y_offset']['size'] ) {
				$this->add_render_attribute( 'toggle-icon', 'data-tippy-offset', '[' . $settings['tooltip_x_offset']['size'] . ',' . $settings['tooltip_y_offset']['size'] . ']', true );
			}
			if ( 'yes' == $settings['tooltip_arrow'] ) {
				$this->add_render_attribute( 'toggle-icon', 'data-tippy-arrow', 'true', true );
			} else {
				$this->add_render_attribute( 'toggle-icon', 'data-tippy-arrow', 'false', true );
			}
			if ( $settings['tooltip_animation'] ) {
				$this->add_render_attribute( 'toggle-icon', 'data-tippy-animation', $settings['tooltip_animation'], true );
			}
			if ( 'yes' == $settings['tooltip_trigger'] ) {
				$this->add_render_attribute( 'toggle-icon', 'data-tippy-trigger', 'click', true );
			}
		}

		$circle_menu_settings = wp_json_encode(
			array_filter( [
				"direction"           => $settings["direction"],
				"item_diameter"       => $settings["item_diameter"]["size"],
				"circle_radius"       => $settings["circle_radius"]["size"],
				"speed"               => $settings["speed"]["size"],
				"delay"               => $settings["delay"]["size"],
				"step_out"            => $settings["step_out"]["size"],
				"step_in"             => $settings["step_in"]["size"],
				"trigger"             => $settings["trigger"],
				"transition_function" => $settings["transition_function"],
			] )
		);

		$this->add_render_attribute( 'circle-menu-settings', 'data-settings', $circle_menu_settings );

		?>
        <div <?php $this->print_render_attribute_string( 'circle-menu-container' ); ?>>
            <ul class="penci-circle-menu" <?php $this->print_render_attribute_string( 'circle-menu-settings' ); ?>>
                <li class="penci-cmenuti">

					<?php if ( 'custom' == $settings['toggle_icon'] ) { ?>
                        <a <?php $this->print_render_attribute_string( 'toggle-icon' ); ?>>
							<?php Icons_Manager::render_icon( $settings['custom_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </a>
					<?php } else { ?>
                        <a <?php $this->print_render_attribute_string( 'toggle-icon' ); ?>>
                            <i class="fas fa-<?php echo esc_attr( $toggle_icon ); ?>" aria-hidden="true"></i>
                        </a>
					<?php } ?>

                </li>
				<?php $this->render_loop_iconnav_list(); ?>
            </ul>
        </div>
		<?php
	}
}