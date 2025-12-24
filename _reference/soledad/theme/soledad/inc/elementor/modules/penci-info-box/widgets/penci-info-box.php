<?php

namespace PenciSoledadElementor\Modules\PenciInfoBox\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciInfoBox extends Base_Widget {

	public function get_name() {
		return 'penci-info-box';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Info Box', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-image-box';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'icon', 'box', 'info', 'icon box' );
	}

	protected function register_controls() {


		$this->start_controls_section(
			'section_icon', array(
				'label' => __( 'Info Box', 'soledad' ),
			)
		);
		$this->add_control(
			'icon_type', array(
				'label'   => __( 'Icon type', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'icon'  => __( 'Icon', 'soledad' ),
					'image' => __( 'Image', 'soledad' ),
					'svg'   => __( 'SVG', 'soledad' ),
					'text'  => __( 'Text', 'soledad' ),
				),
				'default' => 'icon',
			)
		);
		$this->add_control( 'svg_tips', array(
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => '<strong style="color: #93003c;">Tips:</strong> You can download many nice free SVG icons via <a target="_blank" href="https://www.flaticon.com/">this website</a> and use them for your website.',
			'content_classes' => 'elementor-descriptor',
			'condition'       => array( 'icon_type' => 'svg' ),
		) );
		$this->add_control(
			'icon', array(
				'label'       => __( 'Icon', 'soledad' ),
				'type'        => Controls_Manager::ICON,
				'default'     => 'fas fa-star',
				'label_block' => true,
				'condition'   => array( 'icon_type' => 'icon' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Choose Image', 'soledad' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array( 'url' => Utils::get_placeholder_image_src() ),
				'condition' => array( 'icon_type' => 'image' ),
			)
		);
		$this->add_control(
			'image_hover',
			array(
				'label'     => __( 'Choose Image Hover', 'soledad' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'icon_type' => 'image' ),
			)
		);

		$this->add_control(
			'image_size', array(
				'label'     => __( 'Custom Image Size', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'thumbnail',
				'options'   => $this->get_list_image_sizes( true ),
				'condition' => array( 'icon_type' => 'image' ),
			)
		);

		$this->add_control(
			'image_bd_radius',
			array(
				'label'     => __( 'Image Border Radius', 'soledad' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'condition' => array( 'icon_type' => 'image' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-info-box.penci-shape-circle .penci-ibox-icon--image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_bd_w',
			array(
				'label'     => __( 'Image Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => array( 'icon_type' => 'image' ),
				'default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 1000, ) ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-info-box.penci-shape-circle .penci-ibox-icon--image' => 'width: 100%;max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'csvg', array(
				'label'     => __( 'Icon or SVG', 'soledad' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => array( 'icon_type' => 'svg' ),
			)
		);
		$this->add_control(
			'ctext', array(
				'label'     => __( 'Text', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '01',
				'condition' => array( 'icon_type' => 'text' ),
			)
		);

		$this->add_control(
			'icon_view', array(
				'label'     => __( 'View', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'condition' => array( 'icon_type' => array( 'icon', 'svg', 'text' ) ),
				'options'   => array(
					'default' => esc_html__( 'Default', 'soledad' ),
					'stacked' => esc_html__( 'Stacked', 'soledad' ),
					'framed'  => esc_html__( 'Framed', 'soledad' )
				)
			)
		);
		$this->add_control(
			'icon_shape', array(
				'label'     => __( 'Shape', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'circle',
				'condition' => array( 'icon_view' => array( 'stacked', 'framed' ) ),
				'options'   => array(
					'circle' => esc_html__( 'Circle', 'soledad' ),
					'square' => esc_html__( 'Square', 'soledad' )
				),
			)
		);

		$this->add_control(
			'icon_hover_animation', array(
				'label'     => __( 'Hover Animation', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'condition' => array( 'icon_view' => array( 'stacked', 'framed' ) ),
				'options'   => array(
					''             => esc_html__( 'None', 'soledad' ),
					'grow'         => esc_html__( 'Grow', 'soledad' ),
					'shrink'       => esc_html__( 'Shrink', 'soledad' ),
					'pulse'        => esc_html__( 'Pulse', 'soledad' ),
					'pulse-grow'   => esc_html__( 'Pulse Grow', 'soledad' ),
					'pulse-shrink' => esc_html__( 'Pulse Shrink', 'soledad' ),
					'push'         => esc_html__( 'Push', 'soledad' ),
					'pop'          => esc_html__( 'Pop', 'soledad' ),
					'custom-1'     => esc_html__( 'Custom Style 1', 'soledad' ),
					'custom-2'     => esc_html__( 'Custom Style 2', 'soledad' ),
					'custom-3'     => esc_html__( 'Custom Style 3', 'soledad' ),
					'custom-4'     => esc_html__( 'Custom Style 4', 'soledad' ),
					'custom-5'     => esc_html__( 'Custom Style 5', 'soledad' )
				),
			)
		);

		$this->add_control(
			'icon_position', array(
				'label'   => __( 'Icon position', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'top-left'    => __( 'Top left', 'soledad' ),
					'top-center'  => __( 'Top center', 'soledad' ),
					'top-right'   => __( 'Top right', 'soledad' ),
					'float-left'  => __( 'Float left', 'soledad' ),
					'float-right' => __( 'Float right', 'soledad' ),
				),
				'default' => 'float-left',
			)
		);

		$this->add_control(
			'content_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'soledad' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'condition'            => array( 'icon_position' => array( 'float-left', 'float-right' ) ),
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'soledad' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'soledad' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'soledad' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} .penci-ibox-float-left .penci-ibox-inner'  => 'align-items: {{VALUE}}',
					'{{WRAPPER}} .penci-ibox-float-right .penci-ibox-inner' => 'align-items: {{VALUE}}',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
			)
		);

		$this->add_responsive_control(
			'icon_width', array(
				'label'     => __( 'Width/Height for Box Contain Icon', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => array( 'icon_view!' => 'default' ),
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-ibox-icon' => 'width: {{SIZE}}px;height:{{SIZE}}px;display: flex;',
				),
			)
		);

		$this->add_control(
			'subtitle', array(
				'label'       => __( 'Sub Title', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'placeholder' => __( 'Enter your sub title', 'soledad' ),
				'label_block' => true,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'title_text', array(
				'label'       => __( 'Title', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'This is the heading', 'soledad' ),
				'placeholder' => __( 'Enter your title', 'soledad' ),
				'label_block' => true,
			)
		);
		$this->add_control(
			'_use_line', array(
				'label'     => __( 'Use Line Below The Title', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'soledad' ),
				'label_off' => __( 'No', 'soledad' ),
			)
		);
		$this->add_control(
			'pline_height', array(
				'label'     => __( 'Custom Height of Line', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'selectors' => array( '{{WRAPPER}}  .penci-ibox-line' => 'height: {{SIZE}}px' ),
				'condition' => array( '_use_line!' => '' ),
			)
		);
		$this->add_control(
			'pline_width', array(
				'label'     => __( 'Custom Width of Line', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'selectors' => array( '{{WRAPPER}}  .penci-ibox-line' => 'width: {{SIZE}}px' ),
				'condition' => array( '_use_line!' => '' ),
			)
		);

		$this->add_control(
			'description_text', array(
				'label'       => __( 'Content', 'soledad' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'soledad' ),
				'placeholder' => __( 'Enter your description', 'soledad' ),
			)
		);

		$this->add_control(
			'addrm', array(
				'label'        => __( 'Add Read More Button', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'treadmore', array(
				'label'     => __( 'Button Text', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Read More',
				'condition' => array( 'addrm' => 'yes' ),
			)
		);

		$this->add_control(
			'link', array(
				'label'       => __( 'Link', 'soledad' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'soledad' ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'wraplink', array(
				'label'        => __( 'Wrap Link for Whole Info Box?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
			)
		);


		$this->end_controls_section();

		// Design
		$this->start_controls_section(
			'section_design_spacing',
			array(
				'label' => __( 'Elements Spacing', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'stitle_marbt', array(
				'label'       => __( 'Sub Title Margin Bottom', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors'   => array( '{{WRAPPER}} .penci-info-box .penci-ibox-stit' => 'margin-bottom: {{SIZE}}px' ),
			)
		);


		$this->add_responsive_control(
			'icon_marbt', array(
				'label'       => __( 'Margin Bottom for Box Contain Icon', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors'   => array( '{{WRAPPER}} .penci-ibox-icon' => 'margin-bottom: {{SIZE}}px' ),
				'condition'   => array( 'icon_position!' => array( 'float-left', 'float-right' ) ),
			)
		);
		$this->add_responsive_control(
			'icon_marleft', array(
				'label'       => __( 'Margin Left for Box Contain Icon', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors'   => array( '{{WRAPPER}} .penci-ibox-float-right .penci-ibox-icon' => 'margin-left: {{SIZE}}px' ),
				'condition'   => array( 'icon_position' => array( 'float-right' ) ),
			)
		);
		$this->add_responsive_control(
			'icon_marright', array(
				'label'       => __( 'Margin Right for Box Contain Icon', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors'   => array( '{{WRAPPER}} .penci-ibox-float-left .penci-ibox-icon' => 'margin-right: {{SIZE}}px' ),
				'condition'   => array( 'icon_position' => array( 'float-left' ) ),
			)
		);
		$this->add_responsive_control(
			'title_paddingtop', array(
				'label'     => __( 'Title Padding Top', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array( '{{WRAPPER}} .penci-ibox-title' => 'padding-top: {{SIZE}}px' ),
			)
		);
		$this->add_responsive_control(
			'title_marbt', array(
				'label'     => __( 'Title Margin Bottom', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array( '{{WRAPPER}} .penci-ibox-title' => 'margin-bottom: {{SIZE}}px' ),
			)
		);
		$this->add_responsive_control(
			'desc_martop', array(
				'label'     => __( 'Description Margin Top', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array( '{{WRAPPER}} .penci-ibox-content' => 'margin-top: {{SIZE}}px' ),
			)
		);

		$this->add_responsive_control(
			'btn_martop', array(
				'label'     => __( 'Read More Button Margin Top', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array( '{{WRAPPER}} .penci-ibox-readmore' => 'margin-top: {{SIZE}}px' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_content',
			array(
				'label' => __( 'Typography & Colors', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'psix_style',
			array(
				'label' => __( 'Infor Box', 'soledad' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab', array(
				'label' => __( 'Normal', 'soledad' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'ib_bg',
				'label'    => __( 'Infor Box Background', 'soledad' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .penci-info-box',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'label'    => __( 'Box Shadow', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-info-box',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab', array(
				'label' => __( 'Hover', 'soledad' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'ib_hbg',
				'label'    => __( 'Infor Box Hover Background', 'soledad' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .penci-info-box:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hbox_shadow',
				'label'    => __( 'Box Shadow', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-info-box:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'ibpadding', array(
				'label'      => __( 'Infor Box Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-info-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_responsive_control(
			'ibbdradius', array(
				'label'      => __( 'Infor Box Borders Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-info-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_control(
			'pstitle_style',
			array(
				'label'     => __( 'Sub Title', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'stitle_color',
			array(
				'label'     => __( 'Sub Title Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-info-box .penci-ibox-stit' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'stitle_hcolor',
			array(
				'label'     => __( 'Sub Title Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-info-box:hover .penci-ibox-stit' => 'color: {{VALUE}}; transition: color 0.3s;' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'stitle_typo',
				'label'    => __( 'Sub Title Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-info-box .penci-ibox-stit',
			)
		);

		$this->add_control(
			'ptitle_style',
			array(
				'label'     => __( 'Title', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-ibox-title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'title_hcolor',
			array(
				'label'     => __( 'Title Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-info-box:hover .penci-ibox-title' => 'color: {{VALUE}}; transition: color 0.3s;' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'title_typo',
				'label'    => __( 'Title Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-ibox-title',
			)
		);
		$this->add_control(
			'_line_color',
			array(
				'label'     => __( 'Line Below The Title Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-ibox-line' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'_line_hcolor',
			array(
				'label'     => __( 'Line Below The Title Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-info-box:hover .penci-ibox-line' => 'background-color: {{VALUE}}; transition: background-color 0.3s;' ),
			)
		);

		$this->add_control(
			'pdesc_style',
			array(
				'label'     => __( 'Description', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'desc_color',
			array(
				'label'     => __( 'Description Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-ibox-content' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'desc_hcolor',
			array(
				'label'     => __( 'Description Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-info-box:hover .penci-ibox-content' => 'color: {{VALUE}}; transition: color 0.3s;' ),
			)
		);

		$this->add_control(
			'desc_link_color',
			array(
				'label'     => __( 'Description Links Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-ibox-content a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'desc_typo',
				'label'    => __( 'Description Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-ibox-content, {{WRAPPER}} .penci-ibox-content p',
			)
		);
		$this->add_control(
			'picon_style',
			array(
				'label'     => __( 'Icon', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'picon_color',
			array(
				'label'     => __( 'Icon Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-ibox-icon, {{WRAPPER}} .penci-ibox-icon a, {{WRAPPER}} .penci-ibox-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}}' ),
			)
		);

		$this->add_control(
			'picon_bgcolor',
			array(
				'label'     => __( 'Icon Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'icon_view' => array( 'stacked', 'framed' ) ),
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-stacked .penci-ibox-icon'                                 => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-framed .penci-ibox-icon'                                  => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-stacked .penci-animation-custom-3 .penci-ibox-icon:after' => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-stacked .penci-animation-custom-4 .penci-ibox-icon:after' => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-stacked .penci-animation-custom-5 .penci-ibox-icon:after' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'picon_border_color',
			array(
				'label'     => __( 'Icon Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'icon_view' => array( 'stacked', 'framed' ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-view-stacked .penci-icon'                                     => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-view-framed .penci-icon'                                      => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-view-stacked .penci-animation-custom-3 .penci-ibox-icon'      => 'box-shadow: inset 0 0 0 3px {{VALUE}}',
					'{{WRAPPER}} .penci-view-stacked .penci-animation-custom-4 .penci-ibox-icon'      => 'box-shadow: inset 0 0 0 3px {{VALUE}}',
					'{{WRAPPER}} .penci-view-stacked .penci-animation-custom-5 .penci-ibox-icon'      => 'box-shadow: inset 0 0 0 3px {{VALUE}}',
					'{{WRAPPER}} .penci-view-framed .penci-animation-custom-2 .penci-ibox-icon:after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .penci-view-framed .penci-animation-custom-5 .penci-ibox-icon:after' => 'border-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'picon_hcolor',
			array(
				'label'     => __( 'Icon Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-info-box:hover .penci-ibox-icon, {{WRAPPER}} .penci-ibox-inner:hover .penci-ibox-icon a, {{WRAPPER}} .penci-ibox-inner:hover .penci-ibox-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'picon_hbgcolor',
			array(
				'label'     => __( 'Icon Hover Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'icon_view' => array( 'stacked', 'framed' ) ),
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-stacked .penci-ibox-inner:hover .penci-ibox-icon'               => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-framed .penci-ibox-inner:hover .penci-ibox-icon'                => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-stacked .penci-animation-custom-3:hover .penci-ibox-icon:after' => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-stacked .penci-animation-custom-4:hover .penci-ibox-icon:after' => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-stacked .penci-animation-custom-5:hover .penci-ibox-icon:after' => 'background-color: {{VALUE}};',
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-view-framed .penci-animation-custom-1 .penci-ibox-icon:after'        => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'picon_border_hcolor',
			array(
				'label'     => __( 'Icon Hover Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array( 'icon_view' => array( 'stacked', 'framed' ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-view-stacked .penci-ibox-inner:hover .penci-ibox-icon'               => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-view-framed .penci-ibox-inner:hover .penci-ibox-icon'                => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-view-stacked .penci-animation-custom-2 .penci-ibox-icon:after'       => 'box-shadow: 0 0 0 3px {{VALUE}};',
					'{{WRAPPER}} .penci-view-stacked .penci-animation-custom-1 .penci-ibox-icon:after'       => 'box-shadow: 0 0 0 3px {{VALUE}};',
					'{{WRAPPER}} .penci-view-stacked .penci-animation-custom-3:hover .penci-ibox-icon'       => 'box-shadow: inset 0 0 0 3px {{VALUE}}',
					'{{WRAPPER}} .penci-view-stacked .penci-animation-custom-4:hover .penci-ibox-icon'       => 'box-shadow: inset 0 0 0 3px {{VALUE}}',
					'{{WRAPPER}} .penci-view-stacked .penci-animation-custom-5:hover .penci-ibox-icon'       => 'box-shadow: inset 0 0 0 3px {{VALUE}}',
					'{{WRAPPER}} .penci-view-framed .penci-animation-custom-2:hover  .penci-ibox-icon:after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .penci-view-framed .penci-animation-custom-5:hover  .penci-ibox-icon:after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .penci-view-framed .penci-animation-custom-5:hover .penci-ibox-icon'        => 'box-shadow: 0 0 0 6px {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'picon_fsize', array(
				'label'     => __( 'Font Size for Icon/SVG', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
				'condition' => array( 'icon_type!' => 'text' ),
				'selectors' => array(
					'{{WRAPPER}} .penci-ibox-icon--icon' => 'font-size: {{SIZE}}px',
					'{{WRAPPER}} .penci-ibox-icon svg'   => 'width: {{SIZE}}px; height: auto;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'      => 'pibtext_typo',
				'label'     => __( 'Icon Text Typography', 'soledad' ),
				'selector'  => '{{WRAPPER}} .penci-info-box .penci-ibox-icon, {{WRAPPER}} .penci-info-box .penci-ibox-icon a, {{WRAPPER}} .penci-info-box .penci-ibox-icon .pc-ibox-ctext',
				'condition' => array( 'icon_type' => 'text' ),
			)
		);

		$this->add_responsive_control(
			'picon_padding', array(
				'label'      => __( 'Custom Icon Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'condition'  => array( 'icon_view' => array( 'stacked', 'framed' ) ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-view-framed .penci-icon, {{WRAPPER}} .penci-view-stacked .penci-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_control(
			'rm_style',
			array(
				'label'     => __( 'Read More Button', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array( 'addrm' => 'yes' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'rm_bgcolor',
			array(
				'label'     => __( 'Button Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-info-box .penci-ibox-readmore a' => 'background-color: {{VALUE}};' ),
				'condition' => array( 'addrm' => 'yes' ),
			)
		);
		$this->add_control(
			'rm_bghcolor',
			array(
				'label'     => __( 'Button Hover Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-info-box:hover .penci-ibox-readmore a' => 'background-color: {{VALUE}};' ),
				'condition' => array( 'addrm' => 'yes' ),
			)
		);
		$this->add_control(
			'rm_color',
			array(
				'label'     => __( 'Button Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-info-box .penci-ibox-readmore a' => 'color: {{VALUE}};' ),
				'condition' => array( 'addrm' => 'yes' ),
			)
		);
		$this->add_control(
			'rm_hcolor',
			array(
				'label'     => __( 'Button Hover Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-info-box:hover .penci-ibox-readmore a' => 'color: {{VALUE}};' ),
				'condition' => array( 'addrm' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'      => 'rm_typo',
				'label'     => __( 'Button Typography', 'soledad' ),
				'selector'  => '{{WRAPPER}} .penci-info-box .penci-ibox-readmore a',
				'condition' => array( 'addrm' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'btn_padding', array(
				'label'      => __( 'Button Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array( 'addrm' => 'yes' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-info-box .penci-ibox-readmore a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_responsive_control(
			'btn_bdradius', array(
				'label'      => __( 'Button Borders Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'condition'  => array( 'addrm' => 'yes' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-info-box .penci-ibox-readmore a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_control(
			'addrmborder', array(
				'label'        => __( 'Add Button Borders', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'condition'    => array( 'addrm' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'btn_bdwidth', array(
				'label'      => __( 'Button Borders Width', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'condition'  => array( 'addrmborder' => 'yes' ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-info-box .penci-ibox-readmore a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_control(
			'rm_bcolor',
			array(
				'label'     => __( 'Button Borders Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-info-box .penci-ibox-readmore a' => 'border-color: {{VALUE}};' ),
				'condition' => array( 'addrmborder' => 'yes' ),
			)
		);
		$this->add_control(
			'rm_hbcolor',
			array(
				'label'     => __( 'Button Hover Borders Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-info-box:hover .penci-ibox-readmore a' => 'border-color: {{VALUE}};' ),
				'condition' => array( 'addrmborder' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		$block_id    = 'penci_info_box_' . rand( 1000, 100000 );
		$css_class   = 'penci-block-vc penci-info-box';
		$css_class   .= $settings['icon_position'] ? ' penci-ibox-' . $settings['icon_position'] : 'penci-ibox-float-left';
		$css_class   .= $settings['icon_view'] ? ' penci-view-' . $settings['icon_view'] : '';
		$css_class   .= $settings['icon_shape'] ? ' penci-shape-' . $settings['icon_shape'] : '';
		$inner_class = $settings['icon_hover_animation'] ? ' penci-animation-' . $settings['icon_hover_animation'] : '';


		$a_before           = '<span class="penci-ibox-icon-fa">';
		$a_after            = '</span>';
		$button_link_before = $button_link_after = $wrap_link_open = $wrap_link_close = '';

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'link', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( $settings['link']['nofollow'] ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}

			$a_before           = '<a class="penci-ibox-icon-fa-fix" ' . $this->get_render_attribute_string( 'link' ) . '></a>';
			$a_before           .= '<a class="penci-ibox-icon-fa" ' . $this->get_render_attribute_string( 'link' ) . '>';
			$a_after            = $button_link_after = $wrap_link_close = '</a>';
			$button_link_before = '<a class="penci-ibox-btn" ' . $this->get_render_attribute_string( 'link' ) . '>';
			$wrap_link_open     = '<a class="penci-iboxwrap-overlay" ' . $this->get_render_attribute_string( 'link' ) . '>';
		}
		?>
        <div id="<?php echo esc_attr( $block_id ) ?>" class="<?php echo esc_attr( $css_class ); ?>">
            <div class="penci-ibox-inner<?php echo $inner_class; ?>">
				<?php if ( 'yes' == $settings['wraplink'] ) {
					echo $wrap_link_open . $wrap_link_close;
				} ?>
				<?php
				$icon_type = $settings['icon_type'] ? $settings['icon_type'] : 'icon';
				if ( in_array( $icon_type, array( 'icon', 'svg', 'text' ) ) ) {
					echo '<div class="penci-ibox-icon penci-ibox-icon--icon penci-icon penci-tibox-' . $icon_type . '">';
					echo $a_before;
					if ( 'text' == $icon_type ) {
						$ctext = $settings['ctext'] ? $settings['ctext'] : '';
						if ( $ctext ) {
							echo '<div class="pc-ibox-ctext">' . $ctext . '</div>';
						}
					} else if ( 'svg' == $icon_type ) {
						\Elementor\Icons_Manager::render_icon( $settings['csvg'], [ 'aria-hidden' => 'true' ] );
					} else {
						$this->add_render_attribute( 'i', 'class', 'penci-ibox-icon--i ' . $settings['icon'] );
						$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
						echo '<i ' . $this->get_render_attribute_string( 'i' ) . '></i>';
					}
					echo $a_after;
					echo '</div>';
				} elseif ( $settings['image'] ) {
					$image_src   = '';
					$image_width = $image_height = $image_hwidth = $image_hheight = '';
					$image_size  = $settings['image_size'] ? $settings['image_size'] : 'thumbnail';
					if ( isset( $settings['image']['id'] ) && $settings['image']['id'] ) {
						$image_src_array = wp_get_attachment_image_src( $settings['image']['id'], $image_size );
						if ( ! empty( $image_src_array ) ) {
							$image_src    = $image_src_array[0];
							$image_width  = $image_src_array[1];
							$image_height = $image_src_array[2];
						}
					}
					if ( ! $image_src && isset( $settings['image']['url'] ) ) {
						$image_src = $settings['image']['url'];
					}

					$img_hover_html = '';

					if ( $settings['image_hover'] ) {
						$image_hover_src = '';
						if ( isset( $settings['image_hover']['id'] ) && $settings['image_hover']['id'] ) {
							$image_hover_src_array = wp_get_attachment_image_src( $settings['image_hover']['id'], $image_size );
							if ( ! empty( $image_hover_src_array ) ) {
								$image_hover_src = $image_hover_src_array[0];
								$image_hwidth    = $image_hover_src_array[1];
								$image_hheight   = $image_hover_src_array[2];
							}
						}
						if ( ! $image_hover_src && isset( $settings['image_hover']['url'] ) ) {
							$image_hover_src = $settings['image_hover']['url'];
						}

						if ( $image_hover_src ) {
							$img_hover_html = '<img class="penci-ibox-img_hover" src="' . esc_url( $image_hover_src ) . '" width="' . $image_hwidth . '" height="' . $image_hheight . '">';
						}
					}

					$img_hv_class = $img_hover_html ? 'has-hover' : 'no-hover';

					if ( $image_src || $img_hover_html ) {

						echo '<div class="penci-ibox-icon penci-ibox-icon--image '.$img_hv_class.'">';
						echo $a_before;

						if ( $image_src ) {
						
							echo '<img class="' . ( $settings['image_hover'] ? 'penci-ibox-img_active' : '' ) . '" src="' . esc_url( $image_src ) . '" width="' . $image_width . '" height="' . $image_height . '">';

						}

						echo $img_hover_html;

						echo $a_after;
						echo '</div>';
					}
				}
				?>
                <div class="penci-ibox-content-wrap">
					<?php if ( $settings['subtitle'] ): ?>
                        <div class="penci-ibox-stit"><?php echo $settings['subtitle']; ?></div><?php endif; ?>
					<?php if ( $settings['title_text'] ): ?><h3
                            class="penci-ibox-title"><?php echo $settings['title_text']; ?></h3><?php endif; ?>
					<?php if ( $settings['_use_line'] ): ?>
                        <div class="pc-ibox-wpline"><span class="penci-ibox-line"></span></div><?php endif; ?>
					<?php if ( $settings['description_text'] ): ?>
                        <div class="penci-ibox-content"><?php echo do_shortcode( wpautop( $settings['description_text'] ) ); ?></div><?php endif; ?>
					<?php
					$add_rm      = $settings['addrm'] ? $settings['addrm'] : '';
					$addrmborder = $settings['addrmborder'] ? $settings['addrmborder'] : '';
					$btn_class   = '';
					if ( 'yes' == $add_rm ) {
						if ( 'yes' == $addrmborder ) {
							$btn_class = ' pc-ibox-borders';
						}
						echo '<div class="penci-ibox-readmore' . $btn_class . '">';
						$treadmore = $settings['treadmore'] ? $settings['treadmore'] : '';
						if ( ! empty( $settings['link']['url'] ) ) {
							echo $button_link_before;
							echo do_shortcode( $treadmore );
							echo $button_link_after;
						} else {
							echo '<a class="penci-ibox-btn">';
							echo do_shortcode( $treadmore );
							echo '</a>';
						}
						echo '</div>';
					}
					?>
                </div>
            </div>
        </div>
		<?php
	}

	/**
	 * Get image sizes.
	 *
	 * Retrieve available image sizes after filtering `include` and `exclude` arguments.
	 */
	public function get_list_image_sizes( $default = false ) {
		$wp_image_sizes = $this->get_all_image_sizes();

		$image_sizes = array();

		if ( $default ) {
			$image_sizes[''] = esc_html__( 'Default', 'soledad' );
		}

		foreach ( $wp_image_sizes as $size_key => $size_attributes ) {
			$control_title = ucwords( str_replace( '_', ' ', $size_key ) );
			if ( is_array( $size_attributes ) ) {
				$control_title .= sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
			}

			$image_sizes[ $size_key ] = $control_title;
		}

		$image_sizes['full'] = esc_html__( 'Full', 'soledad' );

		return $image_sizes;
	}

	public function get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large' ];

		$image_sizes = [];

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ] = [
				'width'  => (int) get_option( $size . '_size_w' ),
				'height' => (int) get_option( $size . '_size_h' ),
				'crop'   => (bool) get_option( $size . '_crop' ),
			];
		}

		if ( $_wp_additional_image_sizes ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		return $image_sizes;
	}
}
