<?php

namespace PenciSoledadElementor\Modules\PenciAnimatedHeadline\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class PenciAnimatedHeadline extends Base_Widget {

	public function get_name() {
		return 'penci-animated-headline';
	}

	public function get_title() {
		return esc_html__( 'Penci Animated Headline', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-animated-headline';
	}

	public function get_keywords() {
		return [ 'headline', 'heading', 'animation', 'title', 'text' ];
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'text_elements',
			[
				'label' => esc_html__( 'Headline', 'soledad' ),
			]
		);

		$this->add_control(
			'text_style',
			array(
				'label'   => __( 'Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'rotating',
				'options' => array(
					'none'        => __( 'None', 'soledad' ),
					'highlighted' => __( 'Highlighted', 'soledad' ),
					'rotating'    => __( 'Rotating', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'text_shape',
			array(
				'label'     => __( 'Shape', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'circle',
				'options'   => array(
					'circle'           => __( 'Circle', 'soledad' ),
					'curly'            => __( 'Curly', 'soledad' ),
					'underline'        => __( 'Underline', 'soledad' ),
					'double'           => __( 'Double', 'soledad' ),
					'double-underline' => __( 'Double Underline', 'soledad' ),
					'underline-zigzag' => __( 'Underline Zigzag', 'soledad' ),
					'diagonal'         => __( 'Diagonal', 'soledad' ),
					'strikethrough'    => __( 'Strikethrough', 'soledad' ),
					'x'                => __( 'X', 'soledad' ),
					'check'            => __( 'Check', 'soledad' ),
					'pan'              => __( 'Pan', 'soledad' ),
					'click'            => __( 'Click', 'soledad' ),
					'heart'            => __( 'Heart', 'soledad' ),
					'bolt'             => __( 'Bolt', 'soledad' ),
					'sparkle'          => __( 'Sparkle', 'soledad' ),
					'line'             => __( 'Line', 'soledad' ),
					'line-1'           => __( 'Line 1', 'soledad' ),
					'line-2'           => __( 'Line 2', 'soledad' ),
					'underline-1'      => __( 'Underline 1', 'soledad' ),
					'underline-2'      => __( 'Underline 2', 'soledad' ),
				),
				'condition' => [ 'text_style' => 'highlighted' ]
			)
		);

		$this->add_responsive_control(
			'highlight_width',
			array(
				'label'     => __( 'Shape Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 'size' => 8 ],
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'condition' => [ 'text_style' => 'highlighted' ],
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text svg path' => 'stroke-width:{{SIZE}}px' ],
			)
		);

		$this->add_control(
			'highlight_animation_duration',
			array(
				'label'       => __( 'Animation Duration', 'soledad' ),
				'description' => __( 'Enter the value in second(s)', 'soledad' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [ 'size' => 10 ],
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 0.1,
					),
				),
				'condition'   => [ 'text_style' => 'highlighted' ],
				'selectors'   => array(
					'{{WRAPPER}} .penci-animated-headline .penci-animated-text svg path' => '-moz-animation-duration: {{SIZE}}s; -webkit-animation-duration: {{SIZE}}s; -o-animation-duration: {{SIZE}}s; -ms-animation-duration: {{SIZE}}s; animation-duration: {{SIZE}}s;',
				),
			)
		);

		$this->add_control(
			'highlight_animation_delay',
			array(
				'label'       => __( 'Animation Delay', 'soledad' ),
				'description' => __( 'Enter the value in second(s)', 'soledad' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [ 'size' => 3 ],
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 0.1,
					),
				),
				'condition'   => [ 'text_style' => 'highlighted' ],
				'selectors'   => array(
					'{{WRAPPER}} .penci-animated-headline .penci-animated-text svg path' => '-moz-animation-delay: {{SIZE}}s; -webkit-animation-delay: {{SIZE}}s; -o-animation-delay: {{SIZE}}s; -ms-animation-delay: {{SIZE}}s; animation-delay: {{SIZE}}s;',
				),
			)
		);

		$this->add_control(
			'text_rotating',
			array(
				'label'     => __( 'Rotating', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'typing',
				'options'   => array(
					'typing'      => __( 'Typing', 'soledad' ),
					'clip'        => __( 'Clip', 'soledad' ),
					'flip'        => __( 'Flip', 'soledad' ),
					'swirl'       => __( 'Swirl', 'soledad' ),
					'blinds'      => __( 'Blinds', 'soledad' ),
					'bounce'      => __( 'Bounce', 'soledad' ),
					'swing'       => __( 'Swing', 'soledad' ),
					'rubber-band' => __( 'Rubber Band', 'soledad' ),
					'drop-in'     => __( 'Drop In', 'soledad' ),
					'wave'        => __( 'Wave', 'soledad' ),
					'slide-left'  => __( 'Slide Left', 'soledad' ),
					'slide-right' => __( 'Slide Right', 'soledad' ),
					'slide-up'    => __( 'Slide Up', 'soledad' ),
					'slide-down'  => __( 'Slide Down', 'soledad' ),
				),
				'condition' => [ 'text_style' => 'rotating' ]
			)
		);

		$this->add_control(
			'text_letter_speed',
			array(
				'label'      => __( 'Letters Speed Rotate', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => 100 ],
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'conditions' => array(
					'relation' => 'AND',
					'terms'    => [
						[
							'name'     => 'text_style',
							'operator' => '=',
							'value'    => 'rotating'
						],
						[
							'name'     => 'text_rotating',
							'operator' => 'in',
							'value'    => array( 'typing', 'swirl', 'blinds', 'wave' ),
						],
					]
				)
			)
		);

		$this->add_control(
			'text_delay_change',
			array(
				'label'     => __( 'Delay on Change Words', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 'size' => 2500 ],
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'condition' => [ 'text_style' => 'rotating' ]
			)
		);

		$this->add_control(
			'text_clip_duration',
			array(
				'label'      => __( 'Clip Duration', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => 2000 ],
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'conditions' => array(
					'relation' => 'AND',
					'terms'    => [
						[
							'name'     => 'text_style',
							'operator' => '=',
							'value'    => 'rotating'
						],
						[
							'name'     => 'text_rotating',
							'operator' => '=',
							'value'    => 'clip',
						],
					]
				)
			)
		);

		$this->add_control(
			'text_delay_delete',
			array(
				'label'      => __( 'Delay on Delete Letters', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => 500 ],
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'conditions' => array(
					'relation' => 'AND',
					'terms'    => [
						[
							'name'     => 'text_style',
							'operator' => '=',
							'value'    => 'rotating'
						],
						[
							'name'     => 'text_rotating',
							'operator' => '=',
							'value'    => 'typing',
						],
					]
				)
			)
		);

		$this->add_control(
			'text_before',
			array(
				'label'   => __( 'Before Text', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'This is ',
			)
		);

		$this->add_control(
			'text_animated',
			array(
				'label'     => __( 'Animated Text', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => ' animate ',
				'condition' => [ 'text_style!' => 'rotating' ]
			)
		);

		$this->add_control(
			'text_after',
			array(
				'label'   => __( 'After Text', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => ' text',
			)
		);

		$this->add_control(
			'text_link',
			array(
				'label' => __( 'Link', 'soledad' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'text_html_tag',
			array(
				'label'   => __( 'HTML Tag', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'p',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
			)
		);

		$this->add_control(
			'text_alignment',
			array(
				'label'     => __( 'Alignment', 'soledad' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left'
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center'
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right'
					),
				),
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline' => 'text-align:{{VALUE}}' ]
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'text_rotating_list_heading', array(
				'label'     => esc_html__( 'Rotating Text', 'soledad' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ 'text_style' => 'rotating' ]
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text_rotating_list_item',
			array(
				'label' => __( 'Rotating Text', 'soledad' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'text_rotating_list', array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'text_rotating_list_item' => __( 'Animate', 'soledad' ),
					),
					array(
						'text_rotating_list_item' => __( 'Beauty', 'soledad' ),
					),
					array(
						'text_rotating_list_item' => __( 'Effects', 'soledad' ),
					),
				),
				'title_field' => '{{{ text_rotating_list_item }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'normal_text_style_heading', array(
				'label' => esc_html__( 'Normal Text', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'text_normal_typography',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-animated-headline .penci-animated-text .normal-text',
			)
		);

		$this->add_control(
			'text_normal_color_style',
			array(
				'label'   => __( 'Normal Color Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'color',
				'options' => array(
					'color'    => __( 'Color', 'soledad' ),
					'gradient' => __( 'Gradient', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'text_normal_color',
			array(
				'label'     => __( 'Normal Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [ 'text_normal_color_style' => 'color' ],
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text .normal-text' => 'color:{{VALUE}}' ],
			)
		);

		$this->add_control(
			'text_normal_hcolor',
			array(
				'label'     => __( 'Normal Text Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [ 'text_normal_color_style' => 'color' ],
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text:hover .normal-text' => 'color:{{VALUE}}' ],
			)
		);

		$this->add_control(
			'text_normal_gradient_heading',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [ 'text_normal_color_style' => 'gradient' ],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'text_normal_gradient',
				'label'     => __( 'Text Gradient Color', 'soledad' ),
				'types'     => array( 'gradient' ),
				'selector'  => '{{WRAPPER}} .penci-animated-headline .penci-animated-text .normal-text',
				'condition' => [ 'text_normal_color_style' => 'gradient' ],

			)
		);

		$this->add_control(
			'text_normal_hgradient_heading',
			array(
				'label'     => __( 'Text Hover Color', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [ 'text_normal_color_style' => 'gradient' ],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'text_normal_hgradient',
				'label'     => __( 'Text Gradient Hover Color', 'soledad' ),
				'types'     => array( 'gradient' ),
				'selector'  => '{{WRAPPER}} .penci-animated-headline .penci-animated-text:hover .normal-text',
				'condition' => [ 'text_normal_color_style' => 'gradient' ],

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ani_text_style_heading', array(
				'label' => esc_html__( 'Animated Text', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'text_ani_typography',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-animated-headline .penci-animated-text .dynamic-text',
			)
		);

		$this->add_control(
			'text_animated_margin',
			array(
				'label'    => __( 'Animated Text Margin', 'soledad' ),
				'type'     => Controls_Manager::DIMENSIONS,
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text .dynamic-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
			)
		);
		
		$this->add_control(
			'text_animated_spacing',
			array(
				'label'    => __( 'Animated Text Padding', 'soledad' ),
				'type'     => Controls_Manager::DIMENSIONS,
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text .dynamic-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
			)
		);
		
		$this->add_control(
			'text_animated_color_style',
			array(
				'label'   => __( 'Animated Text Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'gradient',
				'options' => array(
					'color'    => __( 'Color', 'soledad' ),
					'gradient' => __( 'Gradient', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'text_ani_color',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [ 'text_animated_color_style' => 'color' ],
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text .dynamic-wrapper.style-color .dynamic-text' => 'color:{{VALUE}}' ],
			)
		);

		$this->add_control(
			'text_animated_hover_color',
			array(
				'label'     => __( 'Text Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [ 'text_animated_color_style' => 'color' ],
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text:hover .dynamic-wrapper.style-color .dynamic-text' => 'color:{{VALUE}}' ],
			)
		);

		$this->add_control(
			'text_animated_normal_gradient_heading',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [ 'text_animated_color_style' => 'gradient' ],
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text:hover .dynamic-wrapper.style-color .dynamic-text' => 'color:{{VALUE}}' ],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'text_animated_normal_gradient',
				'label'     => __( 'Text Gradient Color', 'soledad' ),
				'types'     => array( 'gradient' ),
				'selector'  => '{{WRAPPER}} .penci-animated-headline .penci-animated-text .dynamic-wrapper.style-gradient:not(.typing-delete) .dynamic-text, {{WRAPPER}} .penci-animated-headline .penci-animated-text .dynamic-wrapper.style-gradient:not(.typing-delete) .dynamic-text .dynamic-text-letter',
				'condition' => [ 'text_animated_color_style' => 'gradient' ],

			)
		);

		$this->add_control(
			'text_animated_normal_hgradient_heading',
			array(
				'label'     => __( 'Text Hover Color', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [ 'text_animated_color_style' => 'gradient' ],
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text:hover .dynamic-wrapper.style-color .dynamic-text' => 'color:{{VALUE}}' ],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'text_animated_hover_gradient',
				'label'     => __( 'Text Hover Gradient Color', 'soledad' ),
				'types'     => array( 'gradient' ),
				'selector'  => '{{WRAPPER}} .penci-animated-headline .penci-animated-text:hover .dynamic-wrapper.style-gradient:not(.typing-delete) .dynamic-text, {{WRAPPER}} .penci-animated-headline .penci-animated-text:hover .dynamic-wrapper.style-gradient:not(.typing-delete) .dynamic-text .dynamic-text-letter',
				'condition' => [ 'text_animated_color_style' => 'gradient' ],

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'highlight_style_heading', array(
				'label'     => esc_html__( 'Highlight', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'text_style' => 'highlighted' ]
			)
		);

		$this->add_control(
			'highlight_color_style',
			array(
				'label'   => __( 'Animated Color Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'color',
				'options' => array(
					'color'    => __( 'Color', 'soledad' ),
					'gradient' => __( 'Gradient', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'highlight_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text svg path.style-color' => 'stroke:{{VALUE}}' ],
				'condition' => [ 'highlight_color_style' => 'color' ]
			)
		);

		$this->add_control(
			'highlight_gradient_color1',
			array(
				'label'     => __( 'Gradient Color 1', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text svg linearGradient stop:nth-of-type(1)' => 'stop-color:{{VALUE}}' ],
				'condition' => [ 'highlight_color_style' => 'gradient' ]
			)
		);

		$this->add_control(
			'highlight_gradient_color2',
			array(
				'label'     => __( 'Gradient Color 2', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text svg linearGradient stop:nth-of-type(2)' => 'stop-color:{{VALUE}}' ],
				'condition' => [ 'highlight_color_style' => 'gradient' ]
			)
		);

		$this->add_control(
			'highlight_rounded',
			array(
				'label'     => __( 'Rounded Edges', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} .penci-animated-headline .penci-animated-text svg path' => 'stroke-linecap: round; stroke-linejoin: round',
				)
			)
		);

		$this->add_control(
			'highlight_front',
			array(
				'label'     => __( 'Override to Text', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} .penci-animated-headline .penci-animated-text svg'           => 'z-index: 2',
					'{{WRAPPER}} .penci-animated-headline .penci-animated-text .dynamic-text' => 'z-index: auto',
				)
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cursor_style_heading', array(
				'label'      => esc_html__( 'Typing', 'soledad' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'AND',
					'terms'    => [
						[
							'name'     => 'text_style',
							'operator' => '=',
							'value'    => 'rotating'
						],
						[
							'name'     => 'text_rotating',
							'operator' => '=',
							'value'    => 'typing',
						],
					]
				)
			)
		);

		$this->add_control(
			'cursor_color',
			array(
				'label'     => __( 'Cursor Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline[data-style=rotating][data-rotate=typing] .penci-animated-text .dynamic-wrapper:after' => 'background-color:{{VALUE}}' ],
			)
		);

		$this->add_control(
			'typing_delete_color',
			array(
				'label'     => __( 'Delete Block Font Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline[data-style=rotating][data-rotate=typing] .penci-animated-text .dynamic-wrapper.typing-delete .dynamic-text .dynamic-text-letter' => 'color:{{VALUE}}' ],
			)
		);

		$this->add_control(
			'typing_background',
			array(
				'label'     => __( 'Delete Block Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-animated-headline[data-style=rotating][data-rotate=typing] .penci-animated-text .dynamic-wrapper.typing-delete' => 'background-color:{{VALUE}}' ],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cursor_clip_heading', array(
				'label'      => esc_html__( 'Clip', 'soledad' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'AND',
					'terms'    => [
						[
							'name'     => 'text_style',
							'operator' => '=',
							'value'    => 'rotating'
						],
						[
							'name'     => 'text_rotating',
							'operator' => '=',
							'value'    => 'clip',
						],
					]
				)
			)
		);

		$this->add_control(
			'clip_width',
			array(
				'label'     => __( 'Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 'size' => 10 ],
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .penci-animated-headline[data-style=rotating][data-rotate=clip] .penci-animated-text .dynamic-wrapper:after' => 'width: {{SIZE}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'        => 'clip_background',
				'label'       => __( 'Clip Color', 'soledad' ),
				'types'       => array( 'classic', 'gradient' ),
				'selector'    => '{{WRAPPER}} .penci-animated-headline[data-style=rotating][data-rotate=clip] .penci-animated-text .dynamic-wrapper:after',
				'label_block' => true,
				'separator'   => 'before'
			)
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings            = $this->get_settings_for_display();
		$tag                 = esc_attr( $settings['text_html_tag'] );
		$normal_color_style  = esc_attr( $settings['text_normal_color_style'] );
		$dynamic_color_style = esc_attr( $settings['text_animated_color_style'] );
		$style               = $settings['text_style'];
		$link                = '';

		wp_enqueue_script( 'penci-animate-headline' );

		if ( isset( $settings['text_link']['url'] ) && ! empty( $settings['text_link']['url'] ) ) {
			$this->add_render_attribute( 'text_link', 'href', $settings['text_link']['url'] );
			$this->add_render_attribute( 'text_link', 'class', 'pc-textlink' );
			if ( isset( $settings['text_link']['is_external'] ) && ! empty( $settings['text_link']['is_external'] ) ) {
				$this->add_render_attribute( 'text_link', 'target', '_blank' );
			}
			if ( isset( $settings['text_link']['nofollow'] ) && $settings['text_link']['nofollow'] ) {
				$this->add_render_attribute( 'text_link', 'rel', 'nofollow' );
			}
			$link = '<a ' . $this->get_render_attribute_string( 'text_link' ) . '></a>';
		}

		if ( 'rotating' === $style ) {
			$text   = array();
			$lists  = $settings['text_rotating_list'];
			$rotate = $settings['text_rotating'];
			$delay  = $settings['text_delay_change']['size'];

			foreach ( $lists as $list ) {
				array_push( $text, $list['text_rotating_list_item'] );
			}

			$text    = implode( ',', $text );
			$options = array(
				'style'  => esc_attr( $style ),
				'text'   => esc_attr( $text ),
				'rotate' => esc_attr( $rotate ),
				'delay'  => esc_attr( $delay ),
			);

			if ( in_array( $rotate, array( 'typing', 'swirl', 'blinds', 'wave' ), true ) ) {
				$options['letter-speed'] = esc_attr( $settings['text_letter_speed']['size'] );
			}

			if ( 'clip' === $rotate ) {
				$options['clip-duration'] = esc_attr( $settings['text_clip_duration']['size'] );
			}

			if ( 'typing' === $rotate ) {
				$options['delay-delete'] = esc_attr( $settings['text_delay_delete']['size'] );
			}
		} elseif ( 'highlighted' === $style ) {
			$options = array(
				'style' => esc_attr( $style ),
				'text'  => esc_attr( $settings['text_animated'] ),
				'shape' => esc_attr( $settings['text_shape'] ),
			);
		} else {
			$options = array( 'style' => esc_attr( $style ) );
		}

		foreach ( $options as $option => $value ) {
			$this->add_render_attribute( 'headline', 'data-' . $option, $value );
		}

		$this->add_render_attribute( 'headline', 'class', 'penci-animated-headline' );

		$text = '<' . $tag . ' class="penci-animated-text">';

		$inner_text = '<span class="normal-text style-' . $normal_color_style . '">' . esc_attr( $settings['text_before'] ) . '</span>';

		if ( 'rotating' === $style ) {
			$inner_text = $inner_text . '<span class="dynamic-wrapper style-' . $dynamic_color_style . '">' . $this->render_rotating_list( $settings['text_rotating_list'], $settings['text_rotating'] ) . '</span>';
		} elseif ( 'highlighted' === $style ) {
			$inner_text = $inner_text . '<span class="dynamic-wrapper style-' . $dynamic_color_style . '"><span class="dynamic-text">' . esc_attr( $settings['text_animated'] ) . '</span>' . penci_animated_heading_stroke( $settings['text_shape'], $settings['highlight_color_style'] ) . '</span>';
		} else {
			$inner_text = $inner_text . '<span class="dynamic-wrapper style-' . $dynamic_color_style . '"><span class="dynamic-text">' . esc_attr( $settings['text_animated'] ) . '</span></span>';
		}

		$inner_text = $inner_text . '<span class="normal-text style-' . $normal_color_style . '">' . esc_attr( $settings['text_after'] ) . '</span>';

		$text .= $inner_text;

		$text .= '</' . $tag . '>';

		echo '<div ' . $this->get_render_attribute_string( 'headline' ) . '>' . $text . $link . '</div>';
	}

	private function render_rotating_list( $lists, $rotate_style ) {
		$text_list = '';

		if ( in_array( $rotate_style, array( 'typing', 'swirl', 'blinds', 'wave' ), true ) ) {
			foreach ( $lists as $list ) {
				$text_string = $list['text_rotating_list_item'];
				$text_length = mb_strlen( $text_string, 'UTF-8' );
				$text_list   = $text_list . '<span class="dynamic-text">';

				for ( $i = 0; $i < $text_length; $i ++ ) {
					$text_list = $text_list . '<span class="dynamic-text-letter">' . mb_substr( $text_string, $i, 1, 'UTF-8' ) . '</span>';
				}

				$text_list = $text_list . '</span>';
			}
		} else {
			foreach ( $lists as $list ) {
				$text_list = $text_list . '<span class="dynamic-text">' . $list['text_rotating_list_item'] . '</span>';
			}
		}

		return $text_list;
	}
}
