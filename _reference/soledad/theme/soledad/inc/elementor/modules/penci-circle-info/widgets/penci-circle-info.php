<?php

namespace PenciSoledadElementor\Modules\PenciCircleInfo\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

// Exit if accessed directly
class PenciCircleInfo extends Base_Widget {

	public function get_name() {
		return 'penci-circle-info';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Circle Info', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-info-circle-o';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'circle', 'info' ];
	}

	public function get_style_depends() {
		return [ 'penci-circle-info' ];
	}

	public function get_script_depends() {
		return [ 'penci-circle-info' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_layouts',
			[
				'label' => esc_html__( 'Circle Items', 'soledad' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs(
			'item_info_tab'
		);

		$repeater->start_controls_tab(
			'item_info_tab_content',
			[
				'label' => esc_html__( 'Content', 'soledad' ),
			]
		);

		$repeater->add_control(
			'circle_info_item_title',
			[
				'label'       => esc_html__( 'Title', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Title Item', 'soledad' ),
				'default'     => esc_html__( 'Title Item', 'soledad' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'circle_info_item_details',
			[
				'label'       => esc_html__( 'Details', 'soledad' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( "Default description. Lorem Ipsum is simply dummy text of the printing and typesetting industry.   ", 'soledad' ),
				'placeholder' => esc_html__( 'Type your description here', 'soledad' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'circle_info_item_icon',
			[
				'label'       => esc_html__( 'Icon', 'soledad' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				],
				'skin'        => 'inline'
			]
		);

		$repeater->add_control(
			'circle_info_title_link',
			[
				'label'       => esc_html__( 'Link', 'soledad' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true,
				'placeholder' => esc_html__( 'https://your-link.com', 'soledad' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'item_info_tab_bg',
			[
				'label' => esc_html__( 'Background', 'soledad' ),
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'item_background',
				'types'    => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'circle_info_icon_list',
			[
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'separator'   => 'before',
				'default'     => [
					[
						'circle_info_item_title'   => esc_html__( 'List Item #1', 'soledad' ),
						'circle_info_item_details' => '@1 Click edit button to change this text. Lorem agaca ipsum dolor sit amet. Ut elit tellus, luctus nec ullam corper mattis, pulvinar dapibus leo.',
						'soledad', // No need to translate
						'circle_info_item_icon'    => [
							'value'   => 'far fa-smile',
							'library' => 'fa-regular'
						],
					],
					[
						'circle_info_item_title'   => esc_html__( 'List Item #2', 'soledad' ),
						'circle_info_item_details' => '@2 Click edit button to change this text. Lorem agaca ipsum dolor sit amet. Ut elit tellus, luctus nec ullam corper mattis, pulvinar dapibus meo.',
						'soledad', // No need to translate
						'circle_info_item_icon'    => [
							'value'   => 'fas fa-brain',
							'library' => 'fa-solid'
						],
					],
					[
						'circle_info_item_title'   => esc_html__( 'List Item #3', 'soledad' ),
						'circle_info_item_details' => '@3 Click edit button to change this text. Lorem agaca ipsum dolor sit amet. Ut elit tellus, luctus nec ullam corper mattis, pulvinar dapibus leo.',
						'soledad', // No need to translate
						'circle_info_item_icon'    => [
							'value'   => 'far fa-check-circle',
							'library' => 'fa-regular'
						],
					],
					[
						'circle_info_item_title'   => esc_html__( 'List Item #4', 'soledad' ),
						'circle_info_item_details' => '@4 Click edit button to change this text. Lorem agaca ipsum dolor sit amet. Ut elit tellus, luctus nec ullam corper mattis, pulvinar dapibus meo.',
						'soledad', // No need to translate
						'circle_info_item_icon'    => [
							'value'   => 'fas fa-cog',
							'library' => 'fa-solid'
						],
					],
					[
						'circle_info_item_title'   => esc_html__( 'List Item #5', 'soledad' ),
						'circle_info_item_details' => '@5 Click edit button to change this text. Lorem agaca ipsum dolor sit amet. Ut elit tellus, luctus nec ullam corper mattis, pulvinar dapibus leo.',
						'soledad', // No need to translate
						'circle_info_item_icon'    => [
							'value'   => 'fas fa-dice-d6',
							'library' => 'fa-solid'
						],
					],
					[
						'circle_info_item_title'   => esc_html__( 'List Item #6', 'soledad' ),
						'circle_info_item_details' => '@6 Click edit button to change this text. Lorem agaca ipsum dolor sit amet. Ut elit tellus, luctus nec ullam corper mattis, pulvinar dapibus meo.',
						'soledad', // No need to translate
						'circle_info_item_icon'    => [
							'value'   => 'fas fa-asterisk',
							'library' => 'fa-solid'
						],
					],
				],
				'title_field' => '{{{ circle_info_item_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layouts1',
			[
				'label' => esc_html__( 'General Settings', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_info_layout',
			[
				'label'          => esc_html__( 'Layout on Mobile', 'soledad' ),
				'type'           => Controls_Manager::CHOOSE,
				'label_block'    => false,
				'options'        => [
					'circle' => [
						'title' => esc_html__( 'Circle', 'soledad' ),
						'icon'  => 'eicon-circle-o',
					],
					'list'   => [
						'title' => esc_html__( 'List', 'soledad' ),
						'icon'  => 'eicon-editor-list-ul',
					],
				],
				'toggle'         => false,
				'default'        => 'list',
				'prefix_class'   => 'penci-cinfo-style-',
				'style_transfer' => true,
				'render_type'    => 'template',
			]
		);

		$this->add_responsive_control(
			'circle_info_size',
			[
				'label'     => esc_html__( 'Circle Size ', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 400,
				],
				'range'     => [
					'px' => [
						'min' => 200,
						'max' => 1500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-cinfowrap' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'hide_content',
			[
				'label'        => esc_html__( 'Hide Content', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'penci-cinfo-hide-',
				'condition'    => [ 'circle_info_layout' => 'default' ]
			]
		);

		$this->add_control(
			'title_tags',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'span',
				'options'   => [ 
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                ],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'circle_info_custom_margin',
			[
				'label' => esc_html__( 'Custom Margin', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_responsive_control(
			'circle_info_margin',
			[
				'label'     => esc_html__( 'Margin', 'soledad' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfowrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'condition' => [
					'circle_info_custom_margin' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'circle_info_icon_area_size',
			[
				'label'     => esc_html__( 'Icon Area Size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle, {{WRAPPER}} .penci-cinfoicon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}'                                                                      => '--ep-icon-info-margin: calc({{SIZE}}px + 20px);'
				],
			]
		);

		$this->add_responsive_control(
			'circle_info_icon_size',
			[
				'label'     => esc_html__( 'Icon Size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle i, {{WRAPPER}} .penci-cinfoicon i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .penci-cinfosub-circle, {{WRAPPER}} .penci-cinfoicon'     => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'circle_info_event',
			[
				'label'   => esc_html__( 'Select Event ', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'mouseover',
				'options' => [
					'click'     => esc_html__( 'Click', 'soledad' ),
					'mouseover' => esc_html__( 'Hover', 'soledad' ),
				],
			]
		);

		$this->add_control(
			'link_on_icon',
			[
				'label' => esc_html__( 'Link On Icon', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'circle_info_auto_mode',
			[
				'label'       => esc_html__( 'Auto Mode', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'circle_info_auto_time',
			[
				'label'     => esc_html__( 'Time (ms)', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 3000,
				],
				'range'     => [
					'px' => [
						'min' => 1000,
						'max' => 10000,
					],
				],
				'condition' => [
					'circle_info_auto_mode' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'circle_info_content_style',
			[
				'label' => esc_html__( 'Content', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_mode_content_style' );

		$this->start_controls_tab(
			'circle_info_tab_content_normal',
			[
				'label' => esc_html__( 'Normal  ', 'soledad' ),
			]
		);

		$this->add_control(
			'content_glassmorphism_effect',
			[
				'label'       => esc_html__( 'Enable Backdrop Filter Effect', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'content_glassmorphism_blur_level',
			[
				'label'     => esc_html__( 'Blur Level', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'step' => 1,
						'max'  => 50,
					]
				],
				'default'   => [
					'size' => 5
				],
				'selectors' => [
					'{{WRAPPER}} .penci-cinfoitem' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);'
				],
				'condition' => [
					'content_glassmorphism_effect' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'circle_info_content_background_normal',
				'selector' => '{{WRAPPER}} .penci-cinfoitem',
			]
		);

		$this->add_responsive_control(
			'circle_info_content_padding_normal',
			[
				'label'     => esc_html__( 'Padding', 'soledad' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfoitem' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'circle_item_border',
				'selector' => '{{WRAPPER}} .penci-cinfoitem',
			]
		);

		$this->add_responsive_control(
			'circle_item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-cinfoitem' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'circle_info_tab_content_hover',
			[
				'label' => esc_html__( 'Hover  ', 'soledad' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'circle_info_content_background_hover',
				'selector' => '{{WRAPPER}} .penci-cinfoitem:hover',
			]
		);

		$this->add_control(
			'circle_item_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'circle_item_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-cinfoitem:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'circle_info_icon_style',
			[
				'label' => esc_html__( 'Icon', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_mode_style' );

		$this->start_controls_tab(
			'circle_info_tab_icon_normal',
			[
				'label' => esc_html__( 'Normal  ', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_info_icon_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle, {{WRAPPER}} .penci-cinfoicon'         => 'color: {{VALUE}}',
					'{{WRAPPER}} .penci-cinfosub-circle svg, {{WRAPPER}} .penci-cinfoicon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'circle_info_icon_background',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle, {{WRAPPER}} .penci-cinfoicon' => 'background-color: {{VALUE}} ',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'circle_icon_border',
				'selector' => '{{WRAPPER}} .penci-cinfosub-circle, {{WRAPPER}} .penci-cinfoicon',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'circle_info_icon_box_shadow',
				'label'    => esc_html__( 'Shadow', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-cinfosub-circle, {{WRAPPER}} .penci-cinfoicon',
			]
		);

		$this->add_control(
			'glassmorphism_effect',
			[
				'label'       => esc_html__( 'Enable Backdrop Filter Effect', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'glassmorphism_blur_level',
			[
				'label'     => esc_html__( 'Blur Level', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'step' => 1,
						'max'  => 50,
					]
				],
				'default'   => [
					'size' => 5
				],
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle, {{WRAPPER}} .penci-cinfoicon' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);'
				],
				'condition' => [
					'glassmorphism_effect' => 'yes',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'circle_info_tab_icon_hover',
			[
				'label' => esc_html__( 'Hover  ', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_info_icon_color_hover',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle:hover, {{WRAPPER}} .penci-cinfoicon:hover'         => 'color: {{VALUE}} ',
					'{{WRAPPER}} .penci-cinfosub-circle:hover svg, {{WRAPPER}} .penci-cinfoicon:hover svg' => 'fill: {{VALUE}} ',
				],
			]
		);

		$this->add_control(
			'circle_info_icon_background_hover',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle:hover, {{WRAPPER}} .penci-cinfoicon:hover' => 'background-color: {{VALUE}} ',
				],
			]
		);

		$this->add_control(
			'circle_info_icon_border_hover',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle:hover, {{WRAPPER}} .penci-cinfoicon:hover' => 'border-color: {{VALUE}} ',
				],
				'condition' => [
					'circle_icon_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'circle_info_icon_box_shadow_hover',
				'label'    => esc_html__( 'Shadow', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-cinfosub-circle:hover, {{WRAPPER}} .penci-cinfoicon:hover',

			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'circle_info_tab_icon_active',
			[
				'label' => esc_html__( 'Active  ', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_info_icon_color_active',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle.active, {{WRAPPER}} .penci-cinfoicon.active'         => 'color: {{VALUE}} ',
					'{{WRAPPER}} .penci-cinfosub-circle.active svg, {{WRAPPER}} .penci-cinfoicon.active svg' => 'fill: {{VALUE}} ',
				],
			]
		);

		$this->add_control(
			'circle_info_icon_background_active',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle.active, {{WRAPPER}} .penci-cinfoicon.active' => 'background-color: {{VALUE}} ',
				],
			]
		);

		$this->add_control(
			'circle_info_icon_border_active',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfosub-circle:active, {{WRAPPER}} .penci-cinfoicon:active' => 'border-color: {{VALUE}} ',
				],
				'condition' => [
					'circle_icon_border_border!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'circle_info_icon_box_shadow_active',
				'label'    => esc_html__( 'Shadow', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-cinfosub-circle.active, {{WRAPPER}} .penci-cinfosub-circle.active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'circle_info_title_style',
			[
				'label' => esc_html__( 'Title', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_mode_style0' );

		$this->start_controls_tab(
			'circle_info_tab_title_normal',
			[
				'label' => esc_html__( 'Normal  ', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_info_title_color_normal',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .penci-cinfotitle, {{WRAPPER}} .penci-cinfoitem a ' => 'color: {{VALUE}} ',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'circle_info_title_typography',
				'selector' => '{{WRAPPER}} .penci-cinfotitle, {{WRAPPER}} .penci-cinfoitem a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'circle_info_tab_title_hover',
			[
				'label' => esc_html__( 'Hover  ', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_info_title_color_hover',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .penci-cinfotitle:hover, {{WRAPPER}} .penci-cinfoitem a:hover ' => 'color: {{VALUE}} ',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'circle_info_description_style',
			[
				'label' => esc_html__( 'Text', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_description_style' );

		$this->start_controls_tab(
			'circle_info_tab_description_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_info_description_color_normal',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .penci-cinfodesc' => 'color: {{VALUE}} ',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'circle_info_description_typography',
				'selector' => '{{WRAPPER}} .penci-cinfodesc',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'circle_info_tab_description_hover',
			[
				'label' => esc_html__( 'Hover  ', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_info_description_color_hover',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfodesc:hover' => 'color: {{VALUE}} ',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		$this->start_controls_section(
			'circle_info_additional_style',
			[
				'label' => esc_html__( 'Additional', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->start_controls_tabs( 'circle_info_border_style' );

		$this->start_controls_tab(
			'circle_info_border_style_1',
			[
				'label' => esc_html__( 'Border 1', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_info_border_style1',
			[
				'label'     => esc_html__( 'Border Style', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none'   => esc_html__( 'None', 'soledad' ),
					'solid'  => esc_html__( 'Solid', 'soledad' ),
					'dotted' => esc_html__( 'Dotted', 'soledad' ),
					'dashed' => esc_html__( 'Dashed', 'soledad' ),
					'double' => esc_html__( 'Double', 'soledad' ),
					'groove' => esc_html__( 'Groove', 'soledad' ),
				],
				'default'   => 'solid',
				'selectors' => [
					'{{WRAPPER}} .penci-cinfoinner:before' => 'border-style: {{VALUE}} ',
				],
			]
		);

		$this->add_responsive_control(
			'circle_info_border_width1',
			[
				'label'     => esc_html__( 'Border Width', 'soledad' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfoinner:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'circle_info_border_color1',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfoinner:before' => 'border-color: {{VALUE}} ',
				],
			]
		);

		$this->end_controls_tab();


		$this->start_controls_tab(
			'circle_info_border_style_2',
			[
				'label' => esc_html__( 'Border 2', 'soledad' ),
			]
		);

		$this->add_control(
			'circle_info_border_style2',
			[
				'label'     => esc_html__( 'Border Style', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none'   => esc_html__( 'None', 'soledad' ),
					'solid'  => esc_html__( 'Solid', 'soledad' ),
					'dotted' => esc_html__( 'Dotted', 'soledad' ),
					'dashed' => esc_html__( 'Dashed', 'soledad' ),
					'double' => esc_html__( 'Double', 'soledad' ),
					'groove' => esc_html__( 'Groove', 'soledad' ),
				],
				'default'   => 'solid',
				'selectors' => [
					'{{WRAPPER}} .penci-cinfoinner:after' => 'border-style: {{VALUE}} ',
				],
			]
		);

		$this->add_responsive_control(
			'circle_info_border_width2',
			[
				'label'     => esc_html__( 'Border Width', 'soledad' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfoinner:after' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'circle_info_border_color2',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-cinfoinner:after' => 'border-color: {{VALUE}} ',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( $settings['circle_info_auto_mode'] == 'yes' ) {
			$autoMode = true;
			if ( $settings['circle_info_auto_time']['size'] ) {
				$autoModeTime = $settings['circle_info_auto_time']['size'];
			} else {
				$autoModeTime = 0;
			}
		} else {
			$autoMode     = false;
			$autoModeTime = 0;
		}

		if ( $settings['circle_info_event'] ) {
			$circleInfoEvent = $settings['circle_info_event'];
		} else {
			$circleInfoEvent = false;
		}

		$this->add_render_attribute(
			[
				'circle_info' => [
					'class'         => 'penci-cinfo ',
					'data-settings' => [
						wp_json_encode( [
							"id"           => 'penci-cinfo' . $this->get_id(),
							"circleMoving" => $autoMode,
							"movingTime"   => $autoModeTime,
							"mouseEvent"   => $circleInfoEvent,
						] ),
					],
				],
			]
		);

		?>

        <div <?php $this->print_render_attribute_string( 'circle_info' ); ?>>
            <div class="penci-cinfowrap" id="penci-cinfo<?php echo esc_attr( $this->get_id() ); ?>">

                <div class="penci-cinfoinner">
					<?php
					$i = 1;
					foreach ( $settings['circle_info_icon_list'] as $index => $item ) :

						$this->add_render_attribute( 'sub_circle', 'class', 'penci-cinfosub-circle', true );
						if ( $i == 1 ) {
							$this->add_render_attribute( 'sub_circle', 'class', 'active' );
						}
						$this->add_render_attribute( 'sub_circle', 'data-circle-index', $i ++, true );

						$link_key_lg = 'link_' . $index;
						if ( ! empty( $item['circle_info_title_link']['url'] ) ) {
							$this->add_link_attributes( $link_key_lg, $item['circle_info_title_link'] );
						} else {
							$this->add_render_attribute( $link_key_lg, 'href', '#', true );
						}

						?>
                        <div <?php $this->print_render_attribute_string( 'sub_circle' ); ?>>
							<?php if ( $settings['link_on_icon'] == 'yes' ) : ?>
                            <a <?php $this->print_render_attribute_string( $link_key_lg ); ?>>
								<?php endif; ?>
								<?php if ( ! empty( $item['circle_info_item_icon']['value'] ) ) : ?>
									<?php
									Icons_Manager::render_icon( $item['circle_info_item_icon'], [ 'aria-hidden' => 'true' ] );
									?>
								<?php endif; ?>

								<?php if ( $settings['link_on_icon'] == 'yes' ) : ?>
                            </a>
						<?php endif; ?>

                        </div>
					<?php endforeach; ?>
                </div>

                <div class="penci-cinfocontent">
					<?php
					$i = 1;
					foreach ( $settings['circle_info_icon_list'] as $index => $item ) :

						$this->add_render_attribute( 'circle_content', 'class', 'penci-cinfoitem icci' . $i ++ . ' elementor-repeater-item-' . esc_attr( $item['_id'] ), true );

						if ( $i == 2 ) {
							$this->add_render_attribute( 'circle_content', 'class', 'active' );
						}
						$this->add_render_attribute( 'circle_title_tags', 'class', 'penci-cinfotitle' );


						$link_key = 'link_to_' . $index;
						if ( ! empty( $item['circle_info_title_link']['url'] ) ) {
							$this->add_link_attributes( $link_key, $item['circle_info_title_link'] );
						} else {
							$this->add_render_attribute( $link_key, 'href', '#', true );
						}


						?>

                        <div <?php $this->print_render_attribute_string( 'circle_content' ); ?>>

							<?php if ( ! empty( $item['circle_info_item_icon']['value'] ) ) : ?>
                                <div class="penci-cinfoicon d-md-none">
									<?php if ( $settings['link_on_icon'] == 'yes' ) : ?>
                                    <a <?php $this->print_render_attribute_string( $link_key ); ?>>
										<?php endif; ?>
										<?php
										Icons_Manager::render_icon( $item['circle_info_item_icon'], [ 'aria-hidden' => 'true' ] );
										?>
										<?php if ( $settings['link_on_icon'] == 'yes' ) : ?>
                                    </a>
								<?php endif; ?>
                                </div>
							<?php endif; ?>

                            <div class="penci-cinfocontent-inner">
                                <div>
                                    <a <?php $this->print_render_attribute_string( $link_key ); ?>>
                                        <<?php echo esc_attr( $settings['title_tags'] ); ?> <?php $this->print_render_attribute_string( 'circle_title_tags' ); ?>
                                        >
										<?php echo wp_kses_post( $item['circle_info_item_title'] ); ?>
                                    </<?php echo esc_attr( $settings['title_tags'] ); ?>>
                                    </a>
                                </div>

                                <div class="penci-cinfodesc">
									<?php echo wp_kses_post( $item['circle_info_item_details'] ); ?>
                                </div>
                            </div>

                        </div>

					<?php endforeach; ?>

                </div>

            </div>
        </div>

		<?php
	}
}
