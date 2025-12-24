<?php

namespace PenciSoledadElementor\Modules\PenciTimeline\Widgets;

use Elementor\Repeater;
use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Utils;

use PenciSoledadElementor\Modules\QueryControl\Module as Query_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class PenciTimeline extends Base_Widget {

	public function get_name() {
		return 'penci-timeline';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Timeline', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-time-line';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'timeline', 'history', 'statistics' ];
	}

	public function get_style_depends() {
		return [ 'penci-timeline' ];
	}

	public function get_script_depends() {
		return [ 'penci-timeline' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'soledad' ),
			]
		);

		$this->add_control(
			'timeline_source',
			[
				'label'   => esc_html__( 'Source', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => [
					'post'   => __( 'Post', 'soledad' ),
					'custom' => __( 'Custom Content', 'soledad' ),
				],
			]
		);

		$this->add_control(
			'timeline_align',
			[
				'label'   => esc_html__( 'Layout', 'soledad' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'toggle'  => false,
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'soledad' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'soledad' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'soledad' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
			]
		);

		$this->add_control(
			'timeline_center_side',
			[
				'label'     => esc_html__( 'Center Side', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''      => __( 'Default', 'soledad' ),
					'left'  => __( 'Left', 'soledad' ),
					'right' => __( 'Right', 'soledad' ),
				],
				'condition' => [
					'timeline_align' => 'center',
				],
			]
		);

		$this->add_responsive_control( 'timeline_center_width', array(
			'label'     => __( 'Custom Content Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( '%' => array( 'min' => 10, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-tiline-align-center .penci-tiline-item:nth-child(odd)'  => 'width: {{SIZE}}%',
				'{{WRAPPER}} .penci-tiline-align-center .penci-tiline-item:nth-child(even)' => 'width: calc( 100% - {{SIZE}}% )',
			),
			'condition' => [
				'timeline_align' => 'center',
			],
		) );

		$this->end_controls_section();

		$this->register_query_section_controls( false, [ 'timeline_source' => 'post' ] );

		$this->start_controls_section(
			'section_custom_content',
			[
				'label'     => esc_html__( 'Custom Content', 'soledad' ),
				'condition' => [
					'timeline_source' => 'custom'
				]
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_timeline' );

		$repeater->start_controls_tab(
			'timeline_tab_content',
			[
				'label' => esc_html__( 'Content', 'soledad' ),
			]
		);

		$repeater->add_control(
			'timeline_title',
			[
				'label'   => esc_html__( 'Title', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'This is Timeline Item 1 Title', 'soledad' ),
			]
		);

		$repeater->add_control(
			'timeline_subtitle',
			[
				'label'   => esc_html__( 'Sub-Title', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'This is Timeline Item Sub-Title',
			]
		);
		
		$repeater->add_control(
			'timeline_date',
			[
				'label'   => esc_html__( 'Date', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '31 December 2018',
			]
		);

		$repeater->add_control(
			'timeline_image',
			[
				'label'   => esc_html__( 'Image', 'soledad' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'timeline_text',
			[
				'label'   => esc_html__( 'Content', 'soledad' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'I am timeline item content. Click edit button to change this text. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.', 'soledad' ),
			]
		);

		$repeater->add_control(
			'timeline_link',
			[
				'label'       => esc_html__( 'Button Link', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'https://example.com',
				'default'     => 'https://example.com',
			]
		);

		$repeater->add_control(
			'timeline_select_icon',
			[
				'label'            => esc_html__( 'Icon', 'soledad' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'timeline_icon',
				'default'          => [
					'value'   => 'fas fa-file-alt',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'timeline_tab_style',
			[
				'label' => esc_html__( 'Style', 'soledad' ),
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'timeline_item_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .penci-tiline .penci-tiline-item-main-container {{CURRENT_ITEM}}, {{WRAPPER}} .penci-tiline .penci-tiline-content {{CURRENT_ITEM}}',
			]
		);

		$repeater->add_control(
			'item_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-item-main-container {{CURRENT_ITEM}}, {{WRAPPER}} .penci-tiline .penci-tiline-content {{CURRENT_ITEM}}' => '--pcborder-cl: {{VALUE}};',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'timeline_items',
			[
				'label'       => esc_html__( 'Timeline Items', 'soledad' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'timeline_title'       => esc_html__( 'This is Timeline Item 1 Title', 'soledad' ),
						'timeline_text'        => esc_html__( 'I am timeline item content. Click edit button to change this text. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.', 'soledad' ),
						'timeline_select_icon' => [ 'value' => 'fas fa-file-alt', 'library' => 'fa-solid' ],
					],
					[
						'timeline_title'       => esc_html__( 'This is Timeline Item 2 Title', 'soledad' ),
						'timeline_text'        => esc_html__( 'I am timeline item content. Click edit button to change this text. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.', 'soledad' ),
						'timeline_select_icon' => [ 'value' => 'fas fa-file-alt', 'library' => 'fa-solid' ],
					],
					[
						'timeline_title'       => esc_html__( 'This is Timeline Item 3 Title', 'soledad' ),
						'timeline_text'        => esc_html__( 'I am timeline item content. Click edit button to change this text. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.', 'soledad' ),
						'timeline_select_icon' => [ 'value' => 'fas fa-file-alt', 'library' => 'fa-solid' ],
					],
					[
						'timeline_title'       => esc_html__( 'This is Timeline Item 4 Title', 'soledad' ),
						'timeline_text'        => esc_html__( 'I am timeline item content. Click edit button to change this text. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.', 'soledad' ),
						'timeline_select_icon' => [ 'value' => 'fas fa-file-alt', 'library' => 'fa-solid' ],
					],
				],
				'title_field' => '{{{ timeline_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_additional',
			[
				'label' => esc_html__( 'General Settings', 'soledad' )
			]
		);

		$this->add_control(
			'show_image',
			[
				'label'   => esc_html__( 'Show Thumbnail Image', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'thumbnail_size',
				'label'        => esc_html__( 'Image Size', 'soledad' ),
				'exclude'      => [ 'custom' ],
				'default'      => 'medium',
				'prefix_class' => 'penci-tiline-thumbnail-size-',
				'condition'    => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'dislazyload',
			[
				'label'     => esc_html__( 'Disable Image Lazyload', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_image'      => 'yes',
					'timeline_source' => 'post',
				]
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'   => esc_html__( 'Show Title', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_cat',
			[
				'label'   => esc_html__( 'Show Post Categories', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_cat_pri',
			[
				'label' => esc_html__( 'Show Primay Category Only', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'title_tags',
			[
				'label'     => __( 'Title HTML Tag', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h4',
				'options'   => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'condition' => [
					'show_title'      => 'yes',
					'timeline_source' => 'custom',
				]
			]
		);

		$this->add_control(
			'title_link',
			[
				'label'     => esc_html__( 'Title Link', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'show_title'      => 'yes',
					'timeline_source' => 'custom',
				],
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label'     => esc_html__( 'Show Post Metas', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'timeline_source' => 'post',
				],
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'   => esc_html__( 'Show Post Excerpt', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'     => esc_html__( 'Excerpt Limit', 'soledad' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 15,
				'condition' => [
					'show_excerpt'    => 'yes',
					'timeline_source' => 'post',
				],
			]
		);

		$this->add_control(
			'show_readmore',
			[
				'label'   => esc_html__( 'Read More', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label'            => esc_html__( 'Button Icon', 'soledad' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'label_block'      => false,
				'skin'             => 'inline'
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'     => esc_html__( 'Icon Position', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'right',
				'options'   => [
					'left'  => esc_html__( 'Left', 'soledad' ),
					'right' => esc_html__( 'Right', 'soledad' ),
				],
				'condition' => [
					'button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label'     => esc_html__( 'Icon Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 8,
				],
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'button_icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-button-icon-align-right, {{WRAPPER}} .pcreadmore-icon-right i, {{WRAPPER}} .pcreadmore-icon-right svg' => is_rtl() ? 'margin-right: {{SIZE}}{{UNIT}};' : 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .penci-tiline .penci-button-icon-align-left, {{WRAPPER}} .pcreadmore-icon-left i, {{WRAPPER}} .pcreadmore-icon-right svg'  => is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_item',
			[
				'label' => esc_html__( 'General Items', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline:not(.penci-tiline-posts) .penci-tiline-item-main' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline:not(.penci-tiline-posts) .penci-tiline-item-main' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .penci-biggrid-wrapper .pcbg-content-inner'     => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'arrow_background_color',
			[
				'label'     => esc_html__( 'Arrow Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_shadow',
				'selector' => '{{WRAPPER}} .penci-tiline:not(.penci-tiline-posts) .penci-tiline-item-main,{{WRAPPER}} .penci-tiline.penci-tiline-posts .penci-biggrid .penci-bgitin',
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-tiline:not(.penci-tiline-posts) .penci-tiline-item-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .penci-biggrid-wrapper .pcbg-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border', 'soledad' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-tiline:not(.penci-tiline-posts) .penci-tiline-item-main, {{WRAPPER}} .penci-tiline.penci-tiline-posts .penci-biggrid .penci-bgitin',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-tiline:not(.penci-tiline-posts) .penci-tiline-item-main' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .penci-biggrid .penci-bgitin'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_between_spacing',
			[
				'label'      => esc_html__( 'Spacing Between Items', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-item-main-wrapper .penci-tiline-item-main-container' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_lines',
			[
				'label' => esc_html__( 'Lines', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'timeline_line_color',
			[
				'label'     => esc_html__( 'Line Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-item-main-wrapper .penci-tiline-line span' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline .penci-tiline-item:first-child .penci-tiline-item-main-wrapper .penci-tiline-line:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'timeline_line_width',
			[
				'label'     => __( 'Line Size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-item-main-wrapper .penci-tiline-line span' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'timeline_line_border_type',
			[
				'label'     => __( 'Border Style', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'dashed',
				'options'     => [
					'solid'  => esc_html__( 'Solid', 'soledad' ),
					'dashed' => esc_html__( 'Dashed', 'soledad' ),
					'dotted' => esc_html__( 'Dotted', 'soledad' ),
					'double' => esc_html__( 'Double', 'soledad' ),
					'groove' => esc_html__( 'Groove', 'soledad' ),
					'ridge'  => esc_html__( 'Ridge', 'soledad' ),
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-item-main-wrapper .penci-tiline-line span' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline .penci-tiline-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline .penci-tiline-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_background',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-icon span' => 'background-color: {{VALUE}};--pcaccent-cl: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_shadow',
				'selector' => '{{WRAPPER}} .penci-tiline .penci-tiline-icon span'
			]
		);

		$this->add_responsive_control(
			'icon_width',
			[
				'label'     => __( 'Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-icon span' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'     => __( 'Icon Size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 35,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-icon span i, {{WRAPPER}} .penci-tiline .penci-tiline-icon span' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'icon_border',
				'label'       => esc_html__( 'Border', 'soledad' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-tiline .penci-tiline-icon span',
			]
		);

		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label'     => __( 'Border Radius', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 50,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-icon span' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-icon:hover, {{WRAPPER}} .penci-tiline .penci-tiline-icon span:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'icon_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-icon span:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-icon span:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-date span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'date_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-date span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'date_border',
				'label'     => esc_html__( 'Border', 'soledad' ),
				'selector'  => '{{WRAPPER}} .penci-tiline .penci-tiline-date span',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'date_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-date span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'date_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-date span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .penci-tiline .penci-tiline-date',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'date_shadow',
				'selector' => '{{WRAPPER}} .penci-tiline .penci-tiline-date span',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label'     => esc_html__( 'Thumbnail', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_ratio',
			[
				'label'     => esc_html__( 'Image Height/Ratio', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 50,
						'max'  => 500,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-thumbnail img' => 'height: {{SIZE}}px',
					'{{WRAPPER}} .penci-bgitem .penci-image-holder:before'  => 'padding-top: {{SIZE}}%;',
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label'     => esc_html__( 'Opacity', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 1,
				],
				'range'     => [
					'px' => [
						'min'  => 0.1,
						'max'  => 1,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-thumbnail img' => 'opacity: {{SIZE}};',
					'{{WRAPPER}} .penci-bgitem .penci-image-holder'         => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .pcbg-thumb'                           => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'image_border',
				'label'       => esc_html__( 'Border', 'soledad' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-tiline .penci-tiline-thumbnail,{{WRAPPER}} .pcbg-thumb',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-thumbnail, {{WRAPPER}} .pcbg-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow: hidden;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label'     => esc_html__( 'Title', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-title *'            => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-title a:hover'            => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'title_bg_color',
				'selector'       => '{{WRAPPER}} .penci-tiline .penci-tiline-title *, {{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-title a',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Type', 'soledad' ),
					],
				],
			]
		);

		$this->add_responsive_control(
			'title_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-title *, {{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => __( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-title *'          => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .penci-tiline .penci-tiline-title, {{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-title a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_meta',
			[
				'label'     => esc_html__( 'Meta', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-meta *' => 'color: {{VALUE}};',
					'{{WRAPPER}} .grid-post-box-meta span'           => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_linkcolor',
			[
				'label'     => esc_html__( 'Link Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .grid-post-box-meta span a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_hvcolor',
			[
				'label'     => esc_html__( 'Link Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .grid-post-box-meta span a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-tiline .penci-tiline-meta *, {{WRAPPER}} .grid-post-box-meta span',
			]
		);

		$this->add_responsive_control(
			'meta_spacing',
			[
				'label'     => __( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-meta, {{WRAPPER}} .grid-post-box-meta' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_category',
			[
				'label'     => esc_html__( 'Categories', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_cat' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_linkcolor',
			[
				'label'     => esc_html__( 'Link Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .cat > a.penci-cat-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_hvcolor',
			[
				'label'     => esc_html__( 'Link Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .cat > a.penci-cat-name:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'category_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .cat > a.penci-cat-name',
			]
		);

		$this->add_responsive_control(
			'category_spacing',
			[
				'label'     => __( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .pcbg-above.item-hover' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label'     => esc_html__( 'Excerpt', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-excerpt' => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-bgitem .pcbg-pexcerpt'        => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-tiline .penci-tiline-excerpt, {{WRAPPER}} .penci-bgitem .pcbg-pexcerpt',
			]
		);

		$this->add_responsive_control(
			'excerpt_spacing',
			[
				'label'     => __( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-excerpt, {{WRAPPER}} .penci-bgitem .pcbg-pexcerpt' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_readmore',
			[
				'label'     => esc_html__( 'Readmore Button', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_readmore' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_readmore_style' );

		$this->start_controls_tab(
			'tab_readmore_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'readmore_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-readmore'                                  => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline .penci-tiline-readmore svg'                              => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_background',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-readmore'                                  => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'readmore_shadow',
				'selector' => '{{WRAPPER}} .penci-tiline .penci-tiline-readmore, {{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn',
			]
		);

		$this->add_control(
			'readmore_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-readmore'                                  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'readmore_border',
				'label'       => esc_html__( 'Border', 'soledad' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-tiline .penci-tiline-readmore, {{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn',
			]
		);

		$this->add_responsive_control(
			'readmore_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-readmore'                                  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow: hidden;',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_spacing',
			[
				'label'     => __( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-readmore'                                  => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'readmore_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-tiline .penci-tiline-readmore, {{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_readmore_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'readmore_hover_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-readmore:hover'                                  => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn:hover' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .penci-tiline .penci-tiline-readmore:hover svg'                              => 'fill: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'readmore_hover_background',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-readmore:hover'                                  => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'readmore_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'readmore_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-tiline .penci-tiline-readmore:hover'                                  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .penci-tiline.penci-tiline-posts .pcbg-readmore-sec .pcbg-readmorebtn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function render_excerpt( $item = [] ) {
		
		if ( ! $this->get_settings( 'show_excerpt' ) ) {
			return;
		}
		?>
		<div class="penci-tiline-excerpt">
			<?php echo do_shortcode( $item['timeline_text'] ); ?>
		</div>
		<?php
	}

	public function render_readmore( $item = [] ) {

		if ( ! $this->get_settings( 'show_readmore' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$readmore_link = $item['timeline_link'];

		$this->add_render_attribute(
			[
				'timeline-readmore' => [
					'href'  => esc_url( $readmore_link ),
					'class' => [
						'penci-tiline-readmore'
					],
				]
			],
			'',
			'',
			true
		);

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['icon'] = 'fas fa-arrow-right';
		}

		$migrated = isset( $settings['__fa4_migrated']['button_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		?>
        <a <?php $this->print_render_attribute_string( 'timeline-readmore' ); ?>>
			<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>

			<?php if ( $settings['button_icon']['value'] ) : ?>
                <span class="penci-button-icon-align-<?php echo esc_attr( $settings['icon_align'] ); ?>">

					<?php if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $settings['button_icon'], [
							'aria-hidden' => 'true',
							'class'       => 'fa-fw'
						] );
					else : ?>
                        <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
					<?php endif; ?>

				</span>
			<?php endif; ?>
        </a>
		<?php

	}

	public function render_image( $item = [] ) {

		if ( ! $this->get_settings( 'show_image' ) ) {
			return;
		}

		$image_url = ( $item['timeline_image']['url'] ) ?: '';
		$title     = $item['timeline_title'];

		if ( $image_url ) {
			?>
            <div class="penci-tiline-thumbnail">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_html( $title ); ?>">
            </div>
			<?php
		}
	}

	public function render_title( $item = [] ) {

		if ( ! $this->get_settings( 'show_title' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$title_link = $item['timeline_link'];
		$title      = $item['timeline_title'];

		$this->add_render_attribute( 'penci-tiline-title', 'class', 'penci-tiline-title', true );

		?>
        <<?php echo esc_attr( $settings['title_tags'] ); ?>
		<?php $this->print_render_attribute_string( 'penci-tiline-title' ); ?>>
		<?php if ( $settings['title_link'] ) : ?>
            <a href="<?php echo esc_url( $title_link ); ?>" title="<?php echo esc_html( $title ); ?>">
				<?php echo esc_html( $title ); ?>
            </a>
		<?php else : ?>
            <span>
					<?php echo esc_html( $title ); ?>
				</span>
		<?php endif; ?>
        </<?php echo esc_attr( $settings['title_tags'] ); ?>>
		<?php

	}

	public function render_meta( $align, $item = [] ) {

		if ( ! $this->get_settings( 'show_meta' ) ) {
			return;
		}

		?>
        <ul class="penci-tiline-meta">
			<li>
				<?php echo esc_html( $item['timeline_date'] ); ?>
            </li>
        </ul>
		<?php

	}

	public function render_item( $align, $item = [] ) {
		?>
        <div class="penci-tiline-item-main elementor-repeater-item-<?php echo isset( $item['_id'] ) ? esc_attr( $item['_id'] ) : ''; ?>">
            <span class="penci-tiline-arrow"></span>

			<?php $this->render_image( $item ); ?>

            <div class="penci-tiline-desc">
				<?php $this->render_title( $item ); ?>
				<?php $this->render_meta( $align, $item ); ?>
				<?php $this->render_excerpt( $item ); ?>
				<?php $this->render_readmore( $item ); ?>
            </div>
        </div>
		<?php

	}

	public function render_date( $align = 'left', $item = [] ) {

		$settings = $this->get_settings_for_display();

		if ( 'post' == $settings['timeline_source'] ) {
			$timeline_date = get_the_date( 'd F Y' );
		} else {
			$timeline_date = isset( $item['timeline_date'] ) ? $item['timeline_date'] : '';
			$timeline_date = isset( $item['timeline_subtitle'] ) ? $item['timeline_subtitle'] : $timeline_date;
		}

		?>
        <div class="penci-tiline-item penci-tiline-item-date-wrap <?php echo $align; ?>">
            <div class="penci-tiline-date">
				<span>
					<?php echo esc_html( $timeline_date ); ?>
				</span>
            </div>
        </div>
		<?php

	}

	function render_post_format() {

		$this->add_render_attribute( 'timeline-icon', 'class', 'penci-tiline-icon' );

		?>
        <div <?php $this->print_render_attribute_string( 'timeline-icon' ); ?>>
			<?php if ( has_post_format( 'gallery' ) ) : ?>
                <span><?php penci_fawesome_icon( 'far fa-image' ); ?></span>
			<?php elseif ( has_post_format( 'link' ) ) : ?>
                <span><?php penci_fawesome_icon( 'fas fa-link' ); ?></span>
			<?php elseif ( has_post_format( 'quote' ) ) : ?>
                <span><?php penci_fawesome_icon( 'fas fa-quote-left' ); ?></span>
			<?php elseif ( has_post_format( 'video' ) ) : ?>
                <span><?php penci_fawesome_icon( 'fas fa-play' ); ?></span>
			<?php elseif ( has_post_format( 'audio' ) ) : ?>
                <span><?php penci_fawesome_icon( 'fas fa-music' ); ?></span>
			<?php else : ?>
                <span><?php penci_fawesome_icon( 'fas fa-file' ); ?></span>
			<?php endif; ?>
        </div>
		<?php
	}

	public function render_post() {
		$settings          = $this->get_settings();
		$id                = $this->get_id();
		$align             = $settings['timeline_align'];
		$center_side       = 'center' == $align && $settings['timeline_center_side'] ? $settings['timeline_center_side'] : '';
		$center_side_class = ( 'center' == $align && $center_side ) ? 'penci-tiline-center-' . $center_side : '';

		$query_args = Query_Control::get_query_args( 'posts', $settings );

		$wp_query = new \WP_Query( $query_args );

		if ( ! $wp_query->found_posts ) {
			return;
		}

		if ( $wp_query->have_posts() ) :

			$this->add_render_attribute(
				[
					'penci-tiline' => [
						'id'    => 'penci-tiline-' . esc_attr( $id ),
						'class' => [
							'penci-tiline',
							'penci-tiline-posts',
							'penci-tiline-icon-yes',
							'penci-tiline-' . esc_attr( $align ),
							$center_side_class
						]
					]
				]
			);

			?>
            <div <?php $this->print_render_attribute_string( 'penci-tiline' ); ?>>
                <div class="penci-tiline-grid penci-tiline-align-<?php echo $align; ?>">
					<?php
					$count             = 0;
					$showcat           = $settings['show_cat'];
					$primary_cat       = $settings['show_cat_pri'];
					$excerpt           = $settings['show_excerpt'];
					$excerpt_length    = $settings['excerpt_length'];
					$thumbnail         = $settings['thumbnail_size_size'];
					$mthumb_size       = 'medium';
					$disable_lazy      = 'yes' == $settings['dislazyload'] ? 'disable' : '';
					$readmore_icon     = $settings['button_icon'];
					$readmore_icon_pos = $settings['icon_align'];
					$readmore          = $settings['show_readmore'];
					$show_meta         = $settings['show_meta'];
					$show_title        = $settings['show_title'];
					$show_image        = $settings['show_image'];

					while ( $wp_query->have_posts() ) :
						$wp_query->the_post();

						$count ++;
						$post_format = get_post_format() ?: 'standard';
						$item_part   = ( $count % 2 === 0 ) ? 'right' : 'left';

						if ( $align == 'center' && $center_side ) {
							$item_part = $center_side;
						}


						if ( ( $count % 2 === 0 && 'center' == $align && ! $center_side ) || 'right' == $center_side ) : ?>
							<?php $this->render_date( 'right', '' ); ?>
						<?php endif; ?>

                        <div class="penci-tiline-item <?php echo esc_attr( $item_part ) . '-part'; ?> main-alt-part">

                            <div class="penci-tiline-item-main-wrapper">
                                <div class="penci-tiline-line">
                                    <span></span>
                                </div>
                                <div class="penci-tiline-item-main-container">

									<?php $this->render_post_format(); ?>

                                    <div class="penci-tiline-item-main">
                                        <span class="penci-tiline-arrow"></span>
										<?php
										include dirname( __FILE__ ) . "/post.php";
										?>
                                    </div>

                                </div>
                            </div>
                        </div>

						<?php if ( ( $count % 2 === 1 && ( 'center' == $align ) && ! $center_side ) || 'left' == $center_side ) : ?>
						<?php $this->render_date( '' ); ?>
					<?php endif; ?>

					<?php endwhile;
					wp_reset_postdata(); ?>
                </div>
            </div>

		<?php endif;
	}

	public function render_custom() {
		$id             = $this->get_id();
		$settings       = $this->get_settings_for_display();
		$timeline_items = $settings['timeline_items'];

		$align             = $settings['timeline_align'];
		$center_side       = 'center' == $align && $settings['timeline_center_side'] ? $settings['timeline_center_side'] : '';
		$center_side_class = ( 'center' == $align && $center_side ) ? 'penci-tiline-center-' . $center_side : '';

		$this->add_render_attribute( 'penci-tiline-custom', 'id', 'penci-tiline-' . esc_attr( $id ) );
		$this->add_render_attribute( 'penci-tiline-custom', 'class', 'penci-tiline' );
		$this->add_render_attribute( 'penci-tiline-custom', 'class', 'penci-tiline-' . esc_attr( $align ) );
		$this->add_render_attribute( 'penci-tiline-custom', 'class', $center_side_class );

		?>
        <div <?php $this->print_render_attribute_string( 'penci-tiline-custom' ); ?>>
            <div class="penci-tiline-grid penci-tiline-align-<?php echo $align; ?>">
				<?php
				$count = 0;
				foreach ( $timeline_items as $item ) :
					$count ++;

					if ( ! isset( $item['timeline_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
						// add old default
						$item['timeline_icon'] = 'fas fa-file-alt';
					}

					$migrated = isset( $item['__fa4_migrated']['timeline_select_icon'] );
					$is_new   = empty( $item['timeline_icon'] ) && Icons_Manager::is_migration_allowed();


					$item_part = ( $count % 2 === 0 ) ? 'right' : 'left';

					if ( $align == 'center' && $center_side ) {
						$item_part = $center_side;
					}

					if ( ( $count % 2 === 0 && 'center' == $align && ! $center_side ) || 'right' == $center_side ) : ?>
						<?php $this->render_date( 'right', $item ); ?>
					<?php endif; ?>


                    <div
                            class="penci-tiline-item <?php echo esc_attr( $item_part ) . '-part'; ?>">

                        <div class="penci-tiline-item-main-wrapper">
                            <div class="penci-tiline-line">
                                <span></span>
                            </div>
                            <div class="penci-tiline-item-main-container">


                                <div class="penci-tiline-icon">

									<span>

									<?php if ( $is_new || $migrated ) :
										Icons_Manager::render_icon( $item['timeline_select_icon'], [ 'aria-hidden' => 'true' ] );
									else : ?>
                                        <i class="<?php echo esc_attr( $item['timeline_icon'] ); ?>"
                                           aria-hidden="true"></i>
									<?php endif; ?>

									</span>

                                </div>
								<?php $this->render_item( $align, $item ); ?>
                            </div>
                        </div>
                    </div>

					<?php if ( ( $count % 2 === 1 && ( 'center' == $align ) && ! $center_side ) || 'left' == $center_side ) : ?>
					<?php $this->render_date( '', $item ); ?>
				<?php endif; ?>

				<?php endforeach; ?>

				<?php wp_reset_postdata(); ?>

            </div>
        </div>
		<?php
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		if ( 'post' === $settings['timeline_source'] ) {
			$this->render_post();
		} else if ( 'custom' === $settings['timeline_source'] ) {
			$this->render_custom();
		} else {
			return;
		}
	}
}
