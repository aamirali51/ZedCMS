<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleTitle extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Post - Title', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-site-title';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'title' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcsb-ptitle elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-single-title';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading_markup', [
			'label'   => esc_html__( 'Title Markup', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'h1',
			'options' => [
				'h1' => 'H1',
				'h2' => 'H2',
				'h3' => 'H3',
				'h4' => 'H4',
				'h5' => 'H5',
				'h6' => 'H6',
			]
		] );

		$this->add_control( 'heading_align', [
			'label'   => __( 'Post Title Align', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::CHOOSE,
			'default' => 'left',
			'options' => array(
				'left'   => array(
					'title' => __( 'Left', 'soledad' ),
					'icon'  => 'eicon-text-align-left',
				),
				'center' => array(
					'title' => __( 'Center', 'soledad' ),
					'icon'  => 'eicon-text-align-center',
				),
				'right'  => array(
					'title' => __( 'Right', 'soledad' ),
					'icon'  => 'eicon-text-align-right',
				),
			),
			'toggle'  => true,
		] );

		$this->add_control( 'heading_line', [
			'label'     => __( 'Remove Heading Line', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [
				'{{WRAPPER}} .header-standard:after' => 'display:none',
				'{{WRAPPER}} .header-standard'       => 'padding:0;margin:0;',
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typo',
			'label'    => __( 'Typography for Post Title', 'soledad' ),
			'selector' => '{{WRAPPER}} .post-title',
		) );

		$this->add_control( 'main-text-color', [
			'label'     => 'Post Title Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-title' => 'color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'main-text-gcolor-enable', [
			'label' => 'Enable Gradient Color for Post Title',
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_group_control( \Elementor\Group_Control_Background::get_type(), array(
			'name'      => 'main-text-gcolor',
			'label'     => __( 'Gradient Color', 'soledad' ),
			'types'     => array( 'gradient' ),
			'selector'  => '{{WRAPPER}} .post-title.single-post-title span',
			'condition' => [ 'main-text-gcolor-enable' => 'yes' ]
		) );

		$this->add_control( 'main-text-inlinecolor-e', [
			'label'     => 'Use Inline Background Color?',
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [
				'{{WRAPPER}} .post-title' => 'background-color:var(--pcaccent-cl);display:inline;box-decoration-break: clone;padding: 3px 8px;',
				'{{WRAPPER}} .header-standard' => 'padding-top: 3px',
			],
		] );

		$this->add_control( 'main-text-inlinecolor', [
			'label'     => 'Inline Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-title' => 'background-color:{{VALUE}}' ],
			'condition' => ['main-text-inlinecolor-e'=>'yes']
		] );

		$this->add_control( 'main-text-inline-d', [
			'label'     => 'Inline Background Padding',
			'type'      => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px'],
			'selectors' => [
				'{{WRAPPER}} .post-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .header-standard' => 'padding-top: {{TOP}}{{UNIT}}',
			],
			'condition' => ['main-text-inlinecolor-e'=>'yes']
		] );

		$this->add_control( 'heading-line-color', [
			'label'     => 'Heading Line Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .header-standard:after' => 'background-color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'heading-line-s', [
			'label'      => 'Heading Line Spacing with Title',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 500 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .header-standard' => 'padding-bottom:{{SIZE}}px;',
			],
		] );

		$this->add_control( 'heading-line-w', [
			'label'      => 'Heading Line Width',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 500 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .header-standard:after'              => 'width:{{SIZE}}px !important;',
				'{{WRAPPER}} .header-standard.align-center:after' => 'margin-left:calc({{SIZE}}px / 2 * -1)',
			],
		] );

		$this->add_control( 'heading-line-h', [
			'label'      => 'Heading Line Height',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 50 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .header-standard:after' => 'height:{{SIZE}}px !important;',
			],
		] );

		$this->end_controls_section();

	}

	protected function render() {
		if ( penci_elementor_is_edit_mode() ) {
			$this->preview_content();
		} else {
			$this->builder_content();
		}
	}

	protected function preview_content() {
		$settings      = $this->get_settings();
		$class         = $settings['main-text-gcolor-enable'] ? ' gradient-enable' : '';
		$heading_tag   = $settings['heading_markup'];
		$heading_align = $settings['heading_align'];
		echo '<div class="header-standard align-' . $heading_align . $class . '"><' . $heading_tag . ' class="post-title single-post-title entry-title"><span>Demo Post Title</span></' . $heading_tag . '></div>';
	}

	protected function builder_content() {
		$settings      = $this->get_settings();
		$heading_tag   = $settings['heading_markup'];
		$heading_align = $settings['heading_align'];
		$class         = $settings['main-text-gcolor-enable'] ? ' gradient-enable' : '';
		echo '<div class="header-standard align-' . $heading_align . $class . '"><' . $heading_tag . ' class="post-title single-post-title entry-title"><span>' . get_the_title() . '</span></' . $heading_tag . '></div>';
	}
}
