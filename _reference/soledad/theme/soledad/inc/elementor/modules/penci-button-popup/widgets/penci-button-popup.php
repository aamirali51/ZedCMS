<?php

namespace PenciSoledadElementor\Modules\PenciButtonPopup\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciButtonPopup extends Base_Widget {

	public function get_name() {
		return 'penci-button-popup';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Button Popup', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'button', 'click', 'penci', 'soledad' );
	}

	public function get_script_depends() {
		return array( 'penci-button-popup' );
	}

	protected function register_controls() {


		$this->start_controls_section( 'section_general', array(
			'label' => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Button', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'text', array(
			'label'       => __( 'Button Text', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => __( 'Click Here', 'soledad' ),
			'placeholder' => __( 'Add Button Text Here', 'soledad' ),
		) );

		$this->add_responsive_control( 'align', array(
			'label'   => __( 'Alignment', 'elementor' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => array(
				'left'    => array(
					'title' => __( 'Left', 'elementor' ),
					'icon'  => 'eicon-text-align-left',
				),
				'center'  => array(
					'title' => __( 'Center', 'elementor' ),
					'icon'  => 'eicon-text-align-center',
				),
				'right'   => array(
					'title' => __( 'Right', 'elementor' ),
					'icon'  => 'eicon-text-align-right',
				),
				'justify' => array(
					'title' => __( 'Full', 'elementor' ),
					'icon'  => 'eicon-text-align-justify',
				),
			),
			'default' => '',
		) );

		$this->add_responsive_control( 'button_padding', array(
			'label'      => __( 'Button Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcbtn-wrapperin' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_responsive_control( 'button_radius', array(
			'label'      => __( 'Button Border Radius', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcbtn-wrapperin, {{WRAPPER}} .pcbtn-wrapper a.pcbtn:before, {{WRAPPER}} .pcbtn-wrapper a.pcbtn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;'
			),
			'condition'  => array( 'border_style!' => 'gradient' ),
		) );

		$this->add_control( 'add_borders', array(
			'label'     => __( 'Add Borders to Button', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'separator' => 'before',
		) );

		$this->add_control( 'border_style', array(
			'label'     => __( 'Borders Style', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				''         => 'Solid',
				'dotted'   => 'Dotted',
				'dashed'   => 'Dashed',
				'double'   => 'Double',
				'groove'   => 'Groove',
				'ridge'    => 'Ridge',
				'inset'    => 'Inset',
				'outset'   => 'Outset',
				'gradient' => 'Gradient',
			),
			'default'   => '',
			'condition' => array( 'add_borders' => 'yes' ),
		) );

		$this->add_responsive_control( 'border_width', array(
			'label'      => __( 'Custom Button Borders Width', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcbtn-bordered .pcbtn-wrapperin, {{WRAPPER}} .pcbtn-wrapper a.pcbtn:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
			'condition'  => array( 'add_borders' => 'yes' ),
		) );

		$this->add_control( 'add_icon', array(
			'label'     => __( 'Add Icon to Button', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'separator' => 'before',
		) );

		$this->add_control( 'button_icon', array(
			'label'     => esc_html__( 'Icon', 'soledad' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => array(
				'value'   => 'fas fa-star',
				'library' => 'solid',
			),
			'condition' => array( 'add_icon' => 'yes' ),
		) );

		$this->add_control( 'icon_pos', array(
			'label'     => __( 'Icon Position', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'left'  => 'Before',
				'right' => 'After',
			),
			'default'   => 'left',
			'condition' => array( 'add_icon' => 'yes' ),
		) );

		$this->add_responsive_control( 'icon_space', array(
			'label'     => __( 'Icon Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcbtn-icon-left span span i, {{WRAPPER}} .pcbtn-icon-left span span svg'   => 'margin-right: {{SIZE}}px;',
				'{{WRAPPER}} .pcbtn-icon-right span span i, {{WRAPPER}} .pcbtn-icon-right span span svg' => 'margin-left: {{SIZE}}px;',
			),
			'condition' => array( 'add_icon' => 'yes' ),
		) );

		// Subtext
		$this->add_control( 'add_subtext', array(
			'label'     => __( 'Add Subtext?', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'separator' => 'before',
		) );
		$this->add_control( 'subtext', array(
			'label'       => __( 'SubText', 'soledad' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => __( 'Example Subtext', 'soledad' ),
			'placeholder' => __( 'Add Your SubText Here', 'soledad' ),
			'label_block' => true,
			'condition'   => array( 'add_subtext' => 'yes' ),
		) );

		$this->add_responsive_control( 'subtext_align', array(
			'label'     => __( 'Text & Subtext Alignment', 'elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => array(
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
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcbtn-wrapperin' => 'text-align: {{VALUE}};',
			),
			'condition' => array( 'add_subtext' => 'yes' ),
		) );

		$this->add_responsive_control( 'subtext_margin', array(
			'label'      => __( 'Subtext Margin', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'default'    => array(
				'top'      => '',
				'right'    => '',
				'bottom'   => '',
				'left'     => '',
				'unit'     => 'px',
				'isLinked' => false,
			),
			'selectors'  => array(
				'{{WRAPPER}} span.pcbtn-subtext' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array( 'add_subtext' => 'yes' ),
		) );

		$this->add_control( 'button_id', array(
			'label'       => __( 'Button ID', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'title'       => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'soledad' ),
			'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'soledad' ),
			'separator'   => 'before',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_popup_content', array(
			'label' => esc_html__( 'Popup Content', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'popup_cstyle', array(
			'label'   => __( 'Popup Content Style:', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'text',
			'options' => [
				'text'  => 'Custom Text/HTML',
				'block' => 'Penci Block',
			]
		) );

		$this->add_control( 'popup_content', array(
			'label'     => __( 'Popup Content:', 'soledad' ),
			'type'      => Controls_Manager::WYSIWYG,
			'default'   => '',
			'condition' => array( 'popup_cstyle' => array( 'text' ) ),
		) );

		$this->add_control( 'popup_block', array(
			'label'     => __( 'Popup Block:', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => penci_builder_block_list(),
			'condition' => array( 'popup_cstyle' => array( 'block' ) ),
		) );

		$this->add_responsive_control( 'popup_width', array(
			'label'     => __( 'Popup Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 200, 'max' => 2000, ) ),
			'selectors' => [
				'#penci-btn-popup-{{ID}}.button-popup-content' => 'max-width: {{SIZE}}px'
			]
		) );

		$this->add_responsive_control( 'popup_height', array(
			'label'     => __( 'Popup Height', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 200, 'max' => 2000, ) ),
			'selectors' => [
				'#penci-btn-popup-{{ID}}.button-popup-content' => 'min-height: {{SIZE}}px'
			]
		) );

		$this->add_control( 'popup_position', array(
			'label'   => __( 'Popup Postion:', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'middle-center',
			'options' => [
				'top-left'      => 'Top Left',
				'top-center'    => 'Top Center',
				'top-right'     => 'Top Right',
				'middle-left'   => 'Middle Left',
				'middle-center' => 'Middle Center',
				'middle-right'  => 'Middle Right',
				'bottom-left'   => 'Bottom Left',
				'bottom-center' => 'Bottom Center',
				'bottom-right'  => 'Bottom Right',
			]
		) );

		$this->add_control( 'popup_anityle', array(
			'label'   => __( 'Popup Open Animation Style:', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'move-to-top',
			'options' => [
				'move-to-left'   => 'Move To Left',
				'move-to-right'  => 'Move To Right',
				'move-to-top'    => 'Move To Top',
				'move-to-bottom' => 'Move To Bottom',
				'fadein'         => 'Fade In',
				'zoomin'         => 'Zoom In',
			]
		) );

		$this->end_controls_section();

		// Design
		$this->start_controls_section( 'section_design_content', array(
			'label' => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Button', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'button_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} span.pcbtn-content',
		) );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), array(
			'name'     => 'button_tshadow',
			'label'    => __( 'Text Shadow', 'soledad' ),
			'selector' => '{{WRAPPER}} span.pcbtn-content',
		) );

		$this->add_responsive_control( 'icon_size', array(
			'label'     => __( 'Icon Font Size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
			'selectors' => array(
				'{{WRAPPER}}  span.pcbtn-content i'   => 'font-size: {{SIZE}}px;',
				'{{WRAPPER}}  span.pcbtn-content svg' => 'width: {{SIZE}}px;',
			),
			'condition' => array( 'add_icon' => 'yes' ),
		) );

		$this->add_control( 'subtext_heading', array(
			'label'     => __( 'Subtext', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array( 'add_subtext' => 'yes' ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'subtext_typo',
			'label'     => __( 'SubText Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} span.pcbtn-subtext',
			'condition' => array( 'add_subtext' => 'yes' ),
		) );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), array(
			'name'      => 'button_subtshadow',
			'label'     => __( 'Text Shadow', 'soledad' ),
			'selector'  => '{{WRAPPER}} span.pcbtn-subtext',
			'condition' => array( 'add_subtext' => 'yes' ),
		) );

		$this->start_controls_tabs( 'style_tabs' );

		$this->start_controls_tab( 'style_normal_tab', array(
			'label' => __( 'Normal', 'soledad' ),
		) );

		$this->add_control( 'btcolor', array(
			'label'     => __( 'Button Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} span.pcbtn-content' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'iconcolor', array(
			'label'     => __( 'Icon Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} span.pcbtn-content i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} span.pcbtn-content svg' => 'fill: {{VALUE}};',
			),
			'condition' => array( 'add_icon' => 'yes' ),
		) );

		$this->add_control( 'subcolor', array(
			'label'     => __( 'Subtext Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} span.pcbtn-subtext' => 'color: {{VALUE}};' ),
			'condition' => array( 'add_subtext' => 'yes' ),
		) );

		$this->add_group_control( Group_Control_Background::get_type(), array(
			'name'        => 'btbg',
			'label'       => __( 'Button Background', 'soledad' ),
			'types'       => array( 'classic', 'gradient' ),
			'selector'    => '{{WRAPPER}} .pcbtn-wrapperin, {{WRAPPER}} .pcbtn-wrapper a.pcbtn',
			'label_block' => true,
			'separator'   => 'before'
		) );

		$this->add_control( 'border_cl', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .pcbtn-bordered .pcbtn-wrapperin' => 'border-color: {{VALUE}};' ),
			'condition' => array( 'add_borders' => 'yes' ),
			'separator' => 'before'
		) );

		$this->add_control( 'sborder_cl', array(
			'label'     => __( 'Second Borders Color( Gradient Borders )', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .pcbtn-bordered .pcbtn-wrapperin' => 'border-image: linear-gradient( 360deg , {{border_cl.VALUE}}, {{VALUE}} ); border-image: -webkit-linear-gradient( 360deg , {{border_cl.VALUE}}, {{VALUE}} ); border-image-slice: 1;' ),
			'condition' => array( 'add_borders' => 'yes', 'border_style' => 'gradient' ),
		) );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
			'name'      => 'box_shadow',
			'label'     => __( 'Box Shadow', 'soledad' ),
			'selector'  => '{{WRAPPER}} a.pcbtn',
			'separator' => 'before'
		) );

		$this->end_controls_tab();

		$this->start_controls_tab( 'style_hover_tab', array(
			'label' => __( 'Hover', 'soledad' ),
		) );

		$this->add_control( 'bthcolor', array(
			'label'     => __( 'Button Text Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} a.pcbtn:hover span.pcbtn-content' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'iconhcolor', array(
			'label'     => __( 'Icon Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} a.pcbtn:hover span.pcbtn-content i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} a.pcbtn:hover span.pcbtn-content svg' => 'fill: {{VALUE}};',
			),
			'condition' => array( 'add_icon' => 'yes' ),
		) );

		$this->add_control( 'subhcolor', array(
			'label'     => __( 'Subtext Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} a.pcbtn:hover span.pcbtn-subtext' => 'color: {{VALUE}};' ),
			'condition' => array( 'add_subtext' => 'yes' ),
		) );

		$this->add_group_control( Group_Control_Background::get_type(), array(
			'name'        => 'bthbg',
			'label'       => __( 'Button Hover Background', 'soledad' ),
			'types'       => array( 'classic', 'gradient' ),
			'selector'    => '{{WRAPPER}} .pcbtn-wrapper a.pcbtn:before',
			'label_block' => true,
			'separator'   => 'before'
		) );

		$this->add_control( 'border_hcl', array(
			'label'     => __( 'Borders Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .pcbtn-wrapper a.pcbtn:before' => 'border-color: {{VALUE}};' ),
			'condition' => array( 'add_borders' => 'yes' ),
			'separator' => 'before'
		) );

		$this->add_control( 'sborder_hcl', array(
			'label'     => __( 'Second Borders Hover Color( Gradient Borders )', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .pcbtn-wrapper a.pcbtn:before' => 'border-image: linear-gradient( 360deg , {{border_hcl.VALUE}}, {{VALUE}} ); border-image: -webkit-linear-gradient( 360deg , {{border_hcl.VALUE}}, {{VALUE}} ); border-image-slice: 1;' ),
			'condition' => array( 'add_borders' => 'yes', 'border_style' => 'gradient' ),
		) );

		$this->add_control( 'hover_animation', array(
			'label' => esc_html__( 'Hover Animation', 'soledad' ),
			'type'  => Controls_Manager::HOVER_ANIMATION,
		) );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
			'name'      => 'hbox_shadow',
			'label'     => __( 'Box Shadow on Hover', 'soledad' ),
			'selector'  => '{{WRAPPER}} a.pcbtn:hover',
			'separator' => 'before'
		) );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section( 'section_design_popup', array(
			'label' => __( 'Popup Design', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'btn_popup_de', array(
			'label'      => __( 'Popup Content Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'#penci-btn-popup-{{ID}}.button-popup-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			]
		) );

		$this->add_group_control( \Elementor\Group_Control_Background::get_type(), array(
			'name'     => 'btn_popup_bg',
			'label'    => __( 'Background Settings', 'soledad' ),
			'types'    => [ 'classic', 'gradient', 'video' ],
			'selector' => '#penci-btn-popup-{{ID}}.button-popup-content'
		) );

		$this->add_control( 'btn_popup_cl', array(
			'label'     => __( 'Content Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'#penci-btn-popup-{{ID}}.button-popup-content' => 'color:{{VALUE}}'
			]
		) );

		$this->add_control( 'btn_popup_close_cl', array(
			'label'     => __( 'Close Button Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'#penci-btn-popup-{{ID}}.button-popup-content .mfp-close' => 'color:{{VALUE}}'
			]
		) );

		$this->add_responsive_control( 'btn_popup_close_close_size', array(
			'label'     => __( 'Close Button Size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 10, 'max' => 2000, ) ),
			'selectors' => [
				'#penci-btn-popup-{{ID}}.button-popup-content .mfp-close' => 'font-size:{{SIZE}}px'
			]
		) );

		$this->add_control( 'overlay_popup_bg', array(
			'label'     => __( 'Overlay Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'.mfp-bg.penci-pps-{{ID}}' => 'background-color:{{VALUE}}'
			]
		) );

		$this->add_control( 'overlay_opacity', array(
			'label'      => __( 'Overlay Background Opacity', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'step' => 0.01,
					'max'  => 1,
				],
			],
			'selectors'  => [
				'.mfp-ani-wrap.mfp-ready.mfp-bg.penci-pps-{{ID}}' => 'opacity:{{SIZE}}'
			]
		) );

		$this->end_controls_section();

	}

	protected function render() {
		$settings       = $this->get_settings_for_display();
		$text           = $settings['text'] ? $settings['text'] : '';
		$link           = '';
		$button_id_attr = '';
		$button_id      = $settings['button_id'] ? $settings['button_id'] : '';
		if ( $button_id ) {
			$button_id      = str_replace( ' ', '', $button_id );
			$button_id_attr = ' id="' . $button_id . '"';
		}
		$add_icon     = $settings['add_icon'] ? $settings['add_icon'] : '';
		$add_borders  = $settings['add_borders'] ? $settings['add_borders'] : '';
		$border_style = $settings['border_style'] ? $settings['border_style'] : 'solid';
		$icon_pos     = $settings['icon_pos'] ? $settings['icon_pos'] : 'left';
		$align        = $settings['align'] ? $settings['align'] : 'none';
		$align_class  = 'pcbtn-align-' . $align;
		if ( isset( $settings['align_tablet'] ) && $settings['align_tablet'] ) {
			$align_class .= ' pcbtn-talign-' . $settings['align_tablet'];
		}
		if ( isset( $settings['align_mobile'] ) && $settings['align_mobile'] ) {
			$align_class .= ' pcbtn-malign-' . $settings['align_mobile'];
		}
		$add_subtext    = $settings['add_subtext'] ? $settings['add_subtext'] : '';
		$subtext        = $settings['subtext'] ? $settings['subtext'] : '';
		$button_icon    = $settings['button_icon'] ? $settings['button_icon'] : array();
		$icon_html      = '';
		$button_classes = 'pc-popup-btn pcbtn pcbtn-icon-' . $icon_pos;
		if ( $add_borders ) {
			$button_classes .= ' pcbtn-bordered pcbtn-bstyle-' . $border_style;
		}
		if ( 'yes' == $add_icon && ! empty( $button_icon ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $button_icon );
			$icon_html = ob_get_clean();
		}
		if ( $settings['hover_animation'] ) {
			$button_classes .= ' elementor-animation-' . $settings['hover_animation'];
		}
		$id             = 'penci-btn-popup-' . $this->get_id();
		$id_style       = 'penci-pps-' . $this->get_id();
		$anistyle       = isset( $settings['popup_anityle'] ) && $settings['popup_anityle'] ? $settings['popup_anityle'] : 'move-to-top';
		$popup_position = isset( $settings['popup_position'] ) && $settings['popup_position'] ? $settings['popup_position'] : 'middle-center';
		$popup_cstyle   = isset( $settings['popup_cstyle'] ) && $settings['popup_cstyle'] ? $settings['popup_cstyle'] : 'text';
		?>
        <div class="pcbtn-wrapper penci-button-popup <?php echo $align_class; ?>">
            <a data-popup="<?php echo $id; ?>"
               data-position="<?php echo esc_attr( $popup_position . ' ' . $id_style ); ?>"
               class="<?php echo $button_classes; ?>"
               href="#<?php echo $id; ?>"<?php
			echo $button_id_attr; ?>>
				<span class="pcbtn-wrapperin">
					<span class="pcbtn-content"><?php if ( 'left' == $icon_pos ): echo $icon_html; endif; ?><?php echo do_shortcode( $text ); ?><?php if ( 'right' == $icon_pos ): echo $icon_html; endif; ?></span>
					<?php if ( 'yes' == $add_subtext && $subtext ) { ?>
                        <span class="pcbtn-subtext"><?php echo do_shortcode( $subtext ); ?></span>
					<?php } ?>
				</span>
            </a>
        </div>
        <div id="<?php echo $id; ?>"
             class="<?php echo esc_attr( $anistyle ); ?> <?php echo esc_attr( $popup_position . ' ' . $id_style ); ?> mfp-with-anim button-popup-content">
			<?php if ( 'text' == $popup_cstyle ) {
				echo do_shortcode( $settings['popup_content'] );
			} else if ( 'block' == $popup_cstyle && $settings['popup_block'] ) {
				$popup_render_content = '';
				$popup_block_id       = get_page_by_path( $settings['popup_block'], OBJECT, 'penci-block' )->ID;
				if ( $popup_block_id && did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->documents->get( $popup_block_id )->is_built_with_elementor() ) {
					$popup_render_content .= penci_get_elementor_content( $popup_block_id );
				} else {
					$popup_render_content .= do_shortcode( get_post( $popup_block_id )->post_content );

					$shortcodes_custom_css = get_post_meta( $popup_block_id, '_wpb_shortcodes_custom_css', true );

					$popup_render_content .= '<style data-type="vc_shortcodes-custom-css">';
					if ( ! empty( $shortcodes_custom_css ) ) {
						$popup_render_content .= $shortcodes_custom_css;
					}
					$popup_render_content .= '</style>';
				}
				echo $popup_render_content;
			} ?>
        </div>
		<?php
	}
}
