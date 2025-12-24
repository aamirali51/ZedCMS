<?php

namespace PenciSoledadElementor\Modules\PenciTableOfContents\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class PenciTableOfContents extends Base_Widget {
	public function get_name() {
		return 'penci-table-of-contents';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Table of Contents', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'table', 'content', 'index' ];
	}

	public function get_script_depends() {
		return [ 'jquery-ui-widget', 'penci-el-toc' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content_table_of_content',
			[
				'label' => esc_html__( 'Table of Content', 'soledad' ),
			]
		);

		$this->add_control(
			'selectors',
			[
				'label'       => __( 'Index Tags', 'soledad' ),
				'description' => __( 'If you want to exclude headings within a specific widget or element, edit that widget, go to the Advanced tab, and add the class <strong>ignore-this-tag</strong> to the CSS Classes field.', 'soledad' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'default'     => [ 'h2', 'h3', 'h4' ],
				'options'     => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label'       => __( 'Scroll to Indexed Top Offset', 'soledad' ),
				'type'        => Controls_Manager::SLIDER,
				'separator'   => 'before',
				'description' => __( 'Scroll will stop after this offset from top', 'soledad' ),
				'default'     => [
					'size' => 10,
				],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 250,
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_table_of_content',
			[
				'label' => esc_html__( 'General Options', 'soledad' ),
			]
		);

		$this->add_control(
			'context',
			[
				'label'       => __( 'Index Area (any class/id selector)', 'soledad' ),
				'description' => __( 'Any class or ID selector accept here for your table of content.', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '.elementor',
				'placeholder' => '.elementor / #container',
			]
		);

		$this->add_control(
			'auto_collapse',
			[
				'label'     => esc_html__( 'Auto Collapse Sub Index', 'soledad' ),
				'separator' => 'before',
				'type'      => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'history',
			[
				'label'       => esc_html__( 'Click History', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Click History is the list of web pages a user has visited recently of Table Of Content. This feature also responsible for Hash Generator.', 'soledad' ),
			]
		);


		$this->add_control(
			'extend_page',
			[
				'label'       => esc_html__( 'Extend Page', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'If a user scrolls to the bottom of the page and the page is not tall enough to scroll to the last table of contents item, then the page height is increased', 'soledad' ),
			]
		);

		$this->add_control(
			'toc_index_header',
			[
				'label'       => __( 'Index Header Text', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' 	  => 'Table of Contents',
				'separator'   => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_offcanvas',
			[
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'index_background',
			[
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-toc-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'index_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-toc-wrapper'
			]
		);

		$this->add_control(
			'index_radius',
			[
				'label'      => __( 'Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-toc-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'index_padding',
			[
				'label'      => __( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-toc-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'index_min_height',
			[
				'label'              => __( 'Min Height', 'soledad' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', 'vh' ],
				'range'              => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .penci-toc-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'index_box_shadow',
				'label'    => __( 'Box Shadow', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-toc-wrapper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'header_style',
			[
				'label'     => __( 'TOC Heading', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'toc_index_header!' => '',
				],
			]
		);

		$this->add_control(
			'header_text_color',
			[
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-el-toc-table-of-content-header h4' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'header_background_color',
				'selector' => '{{WRAPPER}} .penci-el-toc-table-of-content-header',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'header_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-el-toc-table-of-content-header'
			]
		);

		$this->add_control(
			'header_radius',
			[
				'label'      => __( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-el-toc-table-of-content-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label'      => __( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-el-toc-table-of-content-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'header_margin',
			[
				'label'      => __( 'Margin', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-el-toc-table-of-content-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'header_box_shadow',
				'label'    => __( 'Box Shadow', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-el-toc-table-of-content-header',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .penci-el-toc-table-of-content-header, {{WRAPPER}} .penci-el-toc-table-of-content-header h4',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_list',
			[
				'label' => esc_html__( 'List of Contents', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'index_spacing',
			[
				'label'      => esc_html__( 'Title Spacing', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-toc-wrapper .penci-el-toc-table-of-content ul li' => 'padding: {{SIZE}}{{UNIT}} 0;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'index_typography',
				'selector' => '{{WRAPPER}} .nav > li > a',
			]
		);

		$this->start_controls_tabs( 'index_title_style' );

		$this->start_controls_tab(
			'normal',
			[
				'label' => __( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nav > li > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'index_title_underline_normal',
			[
				'label'     => __( 'Underline', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .nav > li > a' => 'text-decoration: underline',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			[
				'label' => __( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'index_title_color_hover',
			[
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nav > li > a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'index_title_underline_hover',
			[
				'label'     => __( 'Underline', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .nav > li > a:hover' => 'text-decoration: underline',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'active',
			[
				'label' => __( 'Active', 'soledad' ),
			]
		);

		$this->add_control(
			'title_active_color',
			[
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nav > li.penci-el-toc-active > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'index_title_underline_active',
			[
				'label'     => __( 'Underline', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .nav > li.penci-el-toc-active > a' => 'text-decoration: underline',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_ofc_btn',
			[
				'label'     => esc_html__( 'Button', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout!' => [ 'fixed', 'regular' ]
				]
			]
		);

		$this->start_controls_tabs( 'tabs_ofc_btn_style' );

		$this->start_controls_tab(
			'tab_ofc_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background',
			[
				'label'     => __( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button'
			]
		);

		$this->add_responsive_control(
			'button_radius',
			[
				'label'      => __( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_shadow',
				'selector' => '{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_ofc_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'ofc_btn_hover_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button:hover'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ofc_btn_hover_bg',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ofc_btn_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'ofc_btn_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-el-toc-toggle-button-wrapper a.penci-el-toc-toggle-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$id       = 'penci-el-toc-toc-' . $this->get_id();

		$this->add_render_attribute( 'toc-wrapper', 'class', 'penci-el-toc-wrapper penci-toc-wrapper penci-toc-default' );
		$this->add_render_attribute( 'toc-wrapper', 'id', esc_attr( $id ) );

		?>
        <div <?php $this->print_render_attribute_string( 'toc-wrapper' ); ?>>
            <div>
				<?php $this->table_of_content_header(); ?>
				<?php $this->table_of_content(); ?>
            </div>
        </div>
		<?php
	}

	private function table_of_content_header() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['toc_index_header'] ) ) {
			return;
		}
		?>
        <div class="penci-el-toc-table-of-content-header">
            <h4><?php echo esc_html( $settings['toc_index_header'] ); ?></h4>
        </div>
		<?php
	}

	private function table_of_content() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			[
				'table-of-content' => [
					'data-settings' => [
						wp_json_encode( [
							'context'        => $settings['context'],
							'selectors'      => implode( ',', $settings['selectors'] ),
							'ignoreSelector' => '.ignore-this-tag [class*="-heading-title"]',
							'showAndHide'    => $settings['auto_collapse'] ? true : false,
							'scrollTo'       => $settings['offset']['size'],
							'history'        => $settings['history'] ? true : false,
							'extendPage'     => $settings['extend_page'] ? true : false,
							'hashGenerator'  => 'pretty',
						] )
					]
				]
			]
		);

		?>
        <div class="penci-el-toc-table-of-content" <?php $this->print_render_attribute_string( 'table-of-content' ); ?>></div>
		<?php
	}
}