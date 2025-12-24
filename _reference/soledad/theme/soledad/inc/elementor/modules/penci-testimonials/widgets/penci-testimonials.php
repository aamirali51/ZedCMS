<?php

namespace PenciSoledadElementor\Modules\PenciTestimonials\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciTestimonials extends Base_Widget {

	public function get_name() {
		return 'penci-testimonials';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Testimonials', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-review';
	}

	public function get_categories() {
		return array( 'penci-elements' );
	}

	public function get_keywords() {
		return array( 'testimonials' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_testimonails',
			array(
				'label' => esc_html__( 'Testimonials', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'testitype',
			array(
				'label'   => __( 'Type', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'crs',
				'options' => array(
					'crs'  => esc_html__( 'Slider', 'soledad' ),
					'grid' => esc_html__( 'Grid', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'Select Style', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 's1',
				'options' => array(
					's1' => esc_html__( 'Style 1', 'soledad' ),
					's2' => esc_html__( 'Style 2', 'soledad' ),
					's3' => esc_html__( 'Style 3', 'soledad' ),
					's4' => esc_html__( 'Style 4', 'soledad' ),
					's5' => esc_html__( 'Style 5', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'slider_item',
			array(
				'label'   => __( 'Columns', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'' => 'Default',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
			)
		);

		$this->add_control(
			'slider_titem',
			array(
				'label'   => __( 'Columns on Tablet', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'' => 'Default',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
			)
		);

		$this->add_control(
			'slider_mitem',
			array(
				'label'   => __( 'Columns on Mobile', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
			)
		);

		$this->add_control(
			'imagepos',
			array(
				'label'     => __( 'Image Position', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''      => __( 'Top', 'soledad' ),
					'left'  => __( 'Left', 'soledad' ),
					'right' => __( 'Right', 'soledad' ),
				),
				'condition' => array( 'style' => 's4' ),
			)
		);

		$this->add_control(
			'column_gap',
			array(
				'label'     => __( 'Column Gap', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .penci-testimonails' => '--pcsl-hgap: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'row_gap',
			array(
				'label'     => __( 'Row Gap', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .penci-testimonails' => '--pcsl-bgap: {{SIZE}}px;',
				),
				'condition' => array( 'testitype' => 'grid' ),
			)
		);

		$this->add_responsive_control(
			'_desc_width',
			array(
				'label'     => __( 'Description Width', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-testi-blockquote' => 'max-width: {{SIZE}}px;margin-left:auto;margin-right:auto;' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_reviews_items',
			array(
				'label' => esc_html__( 'Reviews Items', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'testi_name',
			array(
				'label'   => __( 'Custom Name', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Testimonail #1', 'soledad' ),
			)
		);
		$repeater->add_control(
			'testi_image',
			array(
				'label'   => __( 'Choose Avatar', 'soledad' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);
		$repeater->add_control(
			'testi_company',
			array(
				'label' => __( 'Company/Position', 'soledad' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$repeater->add_control(
			'testi_desc',
			array(
				'label'   => __( 'Description', 'soledad' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'I am text block. Click edit button to change this text.', 'soledad' ),
			)
		);
		$repeater->add_control(
			'testi_rating',
			array(
				'label'   => __( 'Rating', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
				),
				'default' => '5',
			)
		);

		$this->add_control(
			'testimonails',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'testi_name'    => __( 'Customer #1', 'soledad' ),
						'testi_image'   => array( 'url' => Utils::get_placeholder_image_src() ),
						'testi_desc'    => __( 'I am text block. Click edit button to change this text.', 'soledad' ),
						'testi_company' => __( 'Company Name', 'soledad' ),
						'testi_link'    => '#',
					),
					array(
						'testi_name'    => __( 'Customer #2', 'soledad' ),
						'testi_image'   => array( 'url' => Utils::get_placeholder_image_src() ),
						'testi_desc'    => __( 'I am text block. Click edit button to change this text.', 'soledad' ),
						'testi_company' => __( 'Company Name', 'soledad' ),
						'testi_link'    => '#',
					),
					array(
						'testi_name'    => __( 'Customer #3', 'soledad' ),
						'testi_image'   => array( 'url' => Utils::get_placeholder_image_src() ),
						'testi_desc'    => __( 'I am text block. Click edit button to change this text.', 'soledad' ),
						'testi_company' => __( 'Company Name', 'soledad' ),
						'testi_link'    => '#',
					),
					array(
						'testi_name'    => __( 'Customer #4', 'soledad' ),
						'testi_image'   => array( 'url' => Utils::get_placeholder_image_src() ),
						'testi_desc'    => __( 'I am text block. Click edit button to change this text.', 'soledad' ),
						'testi_company' => __( 'Company Name', 'soledad' ),
						'testi_link'    => '#',
					),
				),
				'title_field' => '{{{ name }}}',
			)
		);

		$this->end_controls_section();

		// Options slider
		$this->start_controls_section(
			'section_slider_options',
			array(
				'label'     => __( 'Slider Options', 'soledad' ),
				'condition' => array( 'testitype' => 'crs' ),
			)
		);

		$this->add_control( 'carousel_slider_effect', array(
			'label'       => __( 'Carousel Slider Effect', 'soledad' ),
			'description' => __( 'The "Swing" effect does not support the loop option.', 'soledad' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => get_theme_mod( 'penci_carousel_slider_effect', 'swing' ),
			'options'     => array(
				'default' => 'Default',
				'swing'   => 'Swing',
			),
		) );

		$this->add_control(
			'autoplay',
			array(
				'label'   => __( 'Autoplay', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);
		$this->add_control(
			'loop',
			array(
				'label'     => __( 'Slider Loop', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [ 'carousel_slider_effect' => 'default' ],
			)
		);
		$this->add_control(
			'auto_time',
			array(
				'label'   => __( 'Slider Auto Time (at x seconds)', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4000,
			)
		);
		$this->add_control(
			'speed',
			array(
				'label'   => __( 'Slider Speed (at x seconds)', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 800,
			)
		);
		$this->add_control(
			'shownav',
			array(
				'label'   => __( 'Show next/prev buttons', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);
		$this->add_control(
			'showdots',
			array(
				'label' => __( 'Show dots navigation', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_spacing',
			array(
				'label' => esc_html__( 'Elements Spacing', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'p_name_marbottom',
			array(
				'label'     => __( 'Name Spacing Top', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-testi-name' => 'margin-top: {{SIZE}}px' ),
			)
		);
		$this->add_responsive_control(
			'p_company_marbottom',
			array(
				'label'     => __( 'Company/Position Spacing Top', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-testi-company' => 'margin-top: {{SIZE}}px' ),
			)
		);
		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'     => __( 'Image Spacing Top', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-testi-avatar' => 'margin-top: {{SIZE}}px' ),
			)
		);
		$this->add_responsive_control(
			'p_rating_marbottom',
			array(
				'label'     => __( 'Rating Spacing Top', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-testi-rating' => 'margin-top: {{SIZE}}px' ),
			)
		);
		$this->add_responsive_control(
			'p_desc_marbottom',
			array(
				'label'     => __( 'Description Spacing Top', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-testi-blockquote' => 'margin-top: {{SIZE}}px' ),
			)
		);

		$this->add_responsive_control(
			'desc_marbot',
			array(
				'label'     => __( 'Description Spacing Bottom', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-testi-blockquote' => 'margin-bottom: {{SIZE}}px' ),
			)
		);

		$this->add_responsive_control(
			'p_desc_padding',
			array(
				'label'     => __( 'Description Padding', 'soledad' ),
				'type'      => Controls_Manager::HIDDEN,
				'selectors' => array( '{{WRAPPER}} .penci-testi-blockquote' => 'padding: {{SIZE}}px' ),
			)
		);

		$this->add_responsive_control(
			'description_padding',
			array(
				'label'      => __( 'Content Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pc-testiinner .penci-testi-blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Options colors
		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'Content', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'team4_bg',
			array(
				'label'     => __( 'Member Items Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-testi-s4 .pc-testiinner' => 'background-color: {{VALUE}};' ),
				'condition' => array( 'style' => array( 's4' ) ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'team4_shadow',
				'label'     => __( 'Adjust Box Shadow', 'soledad' ),
				'selector'  => '{{WRAPPER}} .penci-testi-s4 .pc-testiinner',
				'condition' => array( 'style' => array( 's4' ) ),
			)
		);

		$this->add_responsive_control(
			'team4_padding',
			array(
				'label'      => __( 'Member Items Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-testi-s4 .pc-testiinner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array( 'style' => array( 's4' ) ),
			)
		);

		$this->add_control(
			'icon_quote_color',
			array(
				'label'     => __( 'Quote Icon Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-testimonail .penci-testi-bq-icon:before' => 'color: {{VALUE}}; border-color:{{VALUE}};' ),
			)
		);
		$this->add_control(
			'icon_quote_bgcolor',
			array(
				'label'     => __( 'Quote Icon Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-testimonail .penci-testi-bq-icon:before' => 'background-color: {{VALUE}};' ),
				'condition' => array( 'style' => array( 's1' ) ),
			)
		);

		$this->add_responsive_control(
			'quote_size',
			array(
				'label'     => __( 'Quote Icon size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .penci-testimonail .penci-testi-bq-icon:before' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .penci-testi-s2 .penci-testi-bq-icon:before'    => 'width: auto; height: auto; line-height: {{SIZE}}px;',
				),
			)
		);

		// Slider
		$this->add_control(
			'heading_image',
			array(
				'label'     => __( 'Image', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'_image_width_height',
			array(
				'label'     => __( 'Image With/Height', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array( '{{WRAPPER}} .penci-testi-avatar' => 'width: {{SIZE}}px;height: {{SIZE}}px' ),
			)
		);

		$this->add_responsive_control(
			'image_borderdius',
			array(
				'label'      => __( 'Image Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-testi-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_cname',
			array(
				'label'     => __( 'Customers Name', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Name Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-testi-name, {{WRAPPER}} .testiname' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typo',
				'label'    => __( 'Typography for Name', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-testi-name, {{WRAPPER}} .testiname',
			)
		);

		$this->add_control(
			'heading_company',
			array(
				'label'     => __( 'Company Name', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'company_color',
			array(
				'label'     => __( 'Company Name Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-testi-company, {{WRAPPER}} .testicom' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'company_typo',
				'label'    => __( 'Typography for Company Name', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-testi-company, {{WRAPPER}} .testicom',
			)
		);

		$this->add_control(
			'heading_desc',
			array(
				'label'     => __( 'Description Text', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => __( 'Description Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-testi-blockquote' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'desc_bgcolor',
			array(
				'label'     => __( 'Description Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .penci-testi-s5 .penci-testi-blockquote:after' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .penci-testi-blockquote'                       => 'background-color: {{VALUE}};border-color: {{VALUE}};',
				),
				'condition' => array( 'style' => array( 's1', 's2', 's5' ) ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typo',
				'label'    => __( 'Typography for Description', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-testi-blockquote',
			)
		);

		$this->add_responsive_control(
			'desc_borderdius',
			array(
				'label'      => __( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-testimonail .penci-testi-blockquote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array( 'style' => array( 's1', 's2', 's5' ) ),
			)
		);

		$this->add_control(
			'heading_rating',
			array(
				'label'     => __( 'Stars Rating', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rating_color',
			array(
				'label'     => __( 'Rating Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .penci-testi-rating' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_responsive_control(
			'rating_size',
			array(
				'label'     => __( 'Rating size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array( '{{WRAPPER}} .penci-testi-rating' => 'font-size: {{SIZE}}px' ),
			)
		);

		// Slider
		$this->add_control(
			'heading_slider_style',
			array(
				'label'     => __( 'Slider', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array( 'testitype' => 'crs' ),
			)
		);
		$this->add_control(
			'slider_dot_color',
			array(
				'label'     => __( 'Dots Navigation Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'testitype' => 'crs' ),
				'selectors' => array(
					'{{WRAPPER}} .penci-owl-dot span' => 'background-color: {{VALUE}}; opacity: 1;',
				),
			)
		);
		$this->add_control(
			'slider_dot_bordercl',
			array(
				'label'     => __( 'Dots Navigation Borders Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'testitype' => 'crs' ),
				'selectors' => array(
					'{{WRAPPER}} .penci-owl-dot span' => 'border-color: {{VALUE}};opacity: 1;',
				),
			)
		);
		$this->add_control(
			'slider_dot_hcolor',
			array(
				'label'     => __( 'Dots Navigation Active Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'testitype' => 'crs' ),
				'selectors' => array( '{{WRAPPER}} .penci-owl-dot.hover span,{{WRAPPER}} .penci-owl-dot.active span' => 'background-color: {{VALUE}};border-color: {{VALUE}};opacity: 1;' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( ! $settings['testimonails'] ) {
			return;
		}

		$style        = isset( $settings['style'] ) && $settings['style'] ? $settings['style'] : 's1';

		$default_item_desktop = '3';
		$default_item_tablet = '2';
		if( $style == 's1' || $style == 's3' ) { $default_item_desktop = '1'; $default_item_tablet = '1'; }

		$slider_item  = isset( $settings['slider_item'] ) && $settings['slider_item'] ? $settings['slider_item'] : $default_item_desktop;
		$slider_titem = isset( $settings['slider_titem'] ) && $settings['slider_titem'] ? $settings['slider_titem'] : $default_item_tablet;
		$slider_mitem = isset( $settings['slider_mitem'] ) && $settings['slider_mitem'] ? $settings['slider_mitem'] : '1';
		$testitype    = isset( $settings['testitype'] ) && $settings['testitype'] ? $settings['testitype'] : 'crs';
		$imagepos     = isset( $settings['imagepos'] ) && $settings['imagepos'] ? $settings['imagepos'] : '';



		$wrapper_css_class = 'penci-block-vc penci-smalllist penci-testimonails';
		$wrapper_css_class .= ' penci-testi-' . $style;
		if ( 's4' == $style && $imagepos ) {
			$wrapper_css_class .= ' pcimgpos-' . $imagepos;
		}

		$inner_wrapper_class = 'penci-block_content pcsl-inner penci-clearfix';
		$inner_wrapper_class .= ' pcsl-' . $testitype;
		if ( 'crs' == $testitype ) {
			$inner_wrapper_class .= ' swiper penci-owl-carousel penci-owl-carousel-slider';
		}

		$inner_wrapper_class .= ' pcsl-col-' . $slider_item;
		$inner_wrapper_class .= ' pcsl-tabcol-' . $slider_titem;
		$inner_wrapper_class .= ' pcsl-mobcol-' . $slider_mitem;

		$data_slider = '';
		if ( 'crs' == $testitype ) {
			$data_slider = $settings['showdots'] ? ' data-dots="true"' : '';
			$data_slider .= ! $settings['shownav'] ? ' data-nav="true"' : '';
			$data_slider .= ! $settings['loop'] ? ' data-loop="true"' : '';
			$data_slider .= ' data-ceffect="' . $settings['carousel_slider_effect'] . '"';
			$data_slider .= ' data-auto="' . ( 'yes' == $settings['autoplay'] ? 'true' : 'false' ) . '"';
			$data_slider .= ' data-autotime="' . ( $settings['auto_time'] ? intval( $settings['auto_time'] ) : '4000' ) . '"';
			$data_slider .= ' data-speed="' . ( $settings['speed'] ? intval( $settings['speed'] ) : '800' ) . '"';
			$data_slider .= ' data-item="' . $slider_item . '" data-desktop="' . $slider_item . '" data-tablet="' . $slider_titem . '" data-tabsmall="' . $slider_titem . '" data-mobile="' . $slider_mitem . '"';
		}
		?>
        <div class="<?php echo esc_attr( $wrapper_css_class ); ?>">
            <div class="<?php echo $inner_wrapper_class; ?>"<?php echo $data_slider; ?>>
				<?php
				$item_class = 'normal-item';
				if ( 'crs' == $testitype ) {
					echo '<div class="swiper-wrapper">';
				}
				foreach ( (array) $settings['testimonails'] as $_testi ) {
					$_testi_image   = isset( $_testi['testi_image'] ) ? $_testi['testi_image'] : '';
					$_testi_name    = isset( $_testi['testi_name'] ) ? $_testi['testi_name'] : '';
					$_testi_company = isset( $_testi['testi_company'] ) ? $_testi['testi_company'] : '';
					$_testi_desc    = isset( $_testi['testi_desc'] ) ? $_testi['testi_desc'] : '';
					$_testi_link    = isset( $_testi['testi_link'] ) ? $_testi['testi_link'] : '';
					$_testi_rating  = isset( $_testi['testi_rating'] ) ? $_testi['testi_rating'] : '';

					if ( $_testi_name || $_testi_company || $_testi_desc ) {
						if ( 'crs' == $testitype ) {
							echo '<div class="swiper-slide">';
						}
						?>
                        <div class="pcsl-item penci-testimonail <?php echo $item_class; ?>">
                            <div class="pcsl-itemin pc-testiinner">
								<?php
								if ( in_array( $style, array( 's1', 's2', 's3' ) ) ) {
									if ( 's2' == $style ) {
										if ( $_testi_desc ) {
											echo '<div class="penci-testi-blockquote">';
											echo '<div class="penci-testi-bq-inner"><span class="penci-testi-bq-icon"></span><span>' . $_testi_desc . '</span></div>';

											if ( $_testi_rating ) {
												$rating_item = '';
												for ( $i = 1; $i <= $_testi_rating; $i ++ ) {
													$rating_item .= penci_icon_by_ver( 'fas fa-star' );
												}
												if ( $rating_item ) {
													echo '<div class="penci-testi-rating">' . $rating_item . '</div>';
												}
											}

											echo '</div>';
										}
									} else {
										if ( $_testi_desc ) {
											echo '<div class="penci-testi-blockquote"><div class="penci-testi-bq-inner"><span class="penci-testi-bq-icon"></span><span>' . $_testi_desc . '</span></div></div>';
										}

										if ( $_testi_rating ) {
											$rating_item = '';
											for ( $i = 1; $i <= $_testi_rating; $i ++ ) {
												$rating_item .= penci_icon_by_ver( 'fas fa-star' );
											}

											if ( $rating_item ) {
												echo '<div class="penci-testi-rating">' . $rating_item . '</div>';
											}
										}
									}

									$url_img_item = $this->get_marker_img_el( $_testi_image );
									if ( $url_img_item ) {
										$thumbnailsize = penci_get_image_size_url( $url_img_item, 'thumbnail' );
										echo '<div class="penci-testi-avatar">';
										echo '<img src="' . esc_url( $thumbnailsize ) . '" alt="' . esc_attr( $_testi_name ) . '"/>';
										echo '</div>';
									}
									if ( $_testi_name ) {
										echo '<h3 class="penci-testi-name">' . $_testi_name . '</h3>';
									}
									if ( $_testi_company ) {
										echo '<div class="penci-testi-company">' . $_testi_company . '</div>';
									}
								} elseif ( in_array( $style, array( 's4' ) ) ) {
									$url_img_item = $this->get_marker_img_el( $_testi_image );
									if ( $url_img_item ) {
										$thumbnailsize = penci_get_image_size_url( $url_img_item, 'thumbnail' );
										echo '<div class="pc-testiava"><div class="penci-testi-avatar">';
										echo '<img src="' . esc_url( $thumbnailsize ) . '" alt="' . esc_attr( $_testi_name ) . '"/>';
										echo '</div></div>';
									}

									if ( $_testi_rating || $_testi_desc || $_testi_name || $_testi_company ) {
										echo '<div class="pctesticont">';
										if ( $_testi_rating ) {
											$rating_item = '';
											for ( $i = 1; $i <= $_testi_rating; $i ++ ) {
												$rating_item .= penci_icon_by_ver( 'fas fa-star' );
											}
											if ( $rating_item ) {
												echo '<div class="penci-testi-rating">' . $rating_item . '</div>';
											}
										}

										if ( $_testi_desc ) {
											echo '<div class="penci-testi-blockquote"><div class="penci-testi-bq-inner"><span class="penci-testi-bq-icon"></span><span>' . $_testi_desc . '</span></div></div>';
										}

										if ( $_testi_name || $_testi_company ) {
											echo '<div class="penci-testi-company"><span class="testiname">' . $_testi_name . '</span><span class="testicom">' . $_testi_company . '</span></div>';
										}
										echo '</div>';
									}
								} else {

									if ( $_testi_desc ) {
										echo '<div class="penci-testi-blockquote"><div class="penci-testi-bq-inner"><span class="penci-testi-bq-icon"></span><span>' . $_testi_desc . '</span></div></div>';
									}
									$url_img_item = $this->get_marker_img_el( $_testi_image );

									if ( $url_img_item || $_testi_rating || $_testi_name || $_testi_company ) {
										echo '<div class="pctesti-head">';

										$url_img_item = $this->get_marker_img_el( $_testi_image );
										if ( $url_img_item ) {
											$thumbnailsize = penci_get_image_size_url( $url_img_item, 'thumbnail' );
											echo '<div class="pc-testiava"><div class="penci-testi-avatar">';
											echo '<img src="' . esc_url( $thumbnailsize ) . '" alt="' . esc_attr( $_testi_name ) . '"/>';
											echo '</div></div>';
										}

										if ( $_testi_rating || $_testi_name || $_testi_company ) {
											echo '<div class="pctesticont">';
											if ( $_testi_name ) {
												echo '<h3 class="penci-testi-name">' . $_testi_name . '</h3>';
											}
											if ( $_testi_company ) {
												echo '<div class="penci-testi-company">' . $_testi_company . '</div>';
											}

											if ( $_testi_rating ) {
												$rating_item = '';
												for ( $i = 1; $i <= $_testi_rating; $i ++ ) {
													$rating_item .= penci_icon_by_ver( 'fas fa-star' );
												}
												if ( $rating_item ) {
													echo '<div class="penci-testi-rating">' . $rating_item . '</div>';
												}
											}
											echo '</div>';
										}

										echo '</div>';

									}
								}
								?>
                            </div>
                        </div>

						<?php
						if ( 'crs' == $testitype ) {
							echo '</div>';
						}
					}

				}
				if ( 'crs' == $testitype ) {
					echo '</div>';
				}
				?>
            </div>
        </div>
		<?php
	}

	public function get_marker_img_el( $image, $thumbnail_size = 'thumbnail' ) {
		if ( empty( $image['url'] ) ) {
			return '';
		}
		if ( ! empty( $image['id'] ) ) {
			$attr = wp_get_attachment_image_src( $image['id'], $thumbnail_size );
			if ( isset( $attr['url'] ) && $attr['url'] ) {
				$image['url'] = $attr['url'];
			}
		}

		return $image['url'];
	}
}
