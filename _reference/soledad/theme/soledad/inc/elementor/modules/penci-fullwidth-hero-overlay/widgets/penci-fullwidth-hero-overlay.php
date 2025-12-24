<?php

namespace PenciSoledadElementor\Modules\PenciFullwidthHeroOverlay\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Modules\QueryControl\Module as Query_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciFullwidthHeroOverlay extends Base_Widget {

	public function get_name() {
		return 'penci-fullwidth-hero-overlay';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Fullwidth Hero Overlay', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'list', 'post', 'small', 'slider', 'carousel' );
	}

	protected function register_controls() {


		// Section general
		$this->start_controls_section(
			'section_type', array(
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'container_width',
			[
				'label'      => __( 'Container Width', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'px' => [
						'min'  => 500,
						'max'  => 2000,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 0.1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => '1170',
				],
				'selectors'  => [
					'{{WRAPPER}} .pc-fho-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'column',
			array(
				'label'   => __( 'Small Posts Columns', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'default' => '4',
			)
		);

		$this->add_control(
			'tab_column',
			array(
				'label'   => __( 'Small Posts Columns on Tablet', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''  => 'Default',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'default' => '',
			)
		);

		$this->add_control(
			'mb_column',
			array(
				'label'   => __( 'Small Posts Columns on Mobile', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''  => 'Default',
					'1' => '1',
					'2' => '2',
				),
				'default' => '',
			)
		);

		$this->end_controls_section();

		$this->register_query_section_controls();

		$this->start_controls_section(
			'section_image', array(
				'label' => esc_html__( 'Image', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'disable_lazy', array(
				'label'        => __( 'Disable Lazyload Images?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'main_thumb_size', array(
				'label'   => __( 'Image Size for Big Post', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_list_image_sizes( true ),
			)
		);

		$this->add_control(
			'main_mthumb_size', array(
				'label'   => __( 'Image Size for Big Post on Mobile', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_list_image_sizes( true ),
			)
		);

		$this->add_responsive_control(
			'img_ratio', array(
				'label'     => __( 'Image Ratio for Small Posts', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 300, 'step' => 0.5 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pc-fho-li .penci-image-holder:before' => 'padding-top: {{SIZE}}%;',
				),
			)
		);

		$this->add_control(
			'thumb_size', array(
				'label'   => __( 'Image Size for Small Posts', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_list_image_sizes( true ),
			)
		);

		$this->add_control(
			'mthumb_size', array(
				'label'   => __( 'Image Size for Small Posts on Mobile', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_list_image_sizes( true ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_postmeta', array(
				'label' => esc_html__( 'Post Meta Data', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'post_meta', array(
				'label'    => __( 'Showing Post Meta', 'soledad' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => array( 'title', 'author', 'date' ),
				'multiple' => true,
				'options'  => array(
					'cat'     => esc_html__( 'Categories', 'soledad' ),
					'author'  => esc_html__( 'Author', 'soledad' ),
					'date'    => esc_html__( 'Date', 'soledad' ),
					'comment' => esc_html__( 'Comments', 'soledad' ),
					'views'   => esc_html__( 'Views', 'soledad' ),
					'reading' => esc_html__( 'Reading Time', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'primary_cat', array(
				'label'        => __( 'Show Primary Category Only', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __( 'If you using Yoast SEO or Rank Math plugin, this option will show only the primary category from those plugins. If you don\'t use those plugins, it will show the first category in the list categories of the posts.', 'soledad' ),
				'label_on'     => __( 'On', 'soledad' ),
				'label_off'    => __( 'Off', 'soledad' ),
				'return_value' => 'on',
				'default'      => '',
			)
		);

		$this->add_control(
			'title_length', array(
				'label'   => __( 'Custom Title Words Length', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'default' => '',
			)
		);

		$this->add_control(
			'title_blength', array(
				'label'   => __( 'Custom Title Words Length for Big Post', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'default' => '',
			)
		);

		$this->add_control(
			'show_bicon', array(
				'label'        => __( 'Hide Post Format Icon on Big Post?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_formaticon', array(
				'label'        => __( 'Show Post Format Icons on Small Posts?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_reviewpie', array(
				'label'        => __( 'Show Review Scores from Penci Review plugin', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'heading_show_mobile',
			array(
				'label'     => __( 'Showing on Mobile', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hide_cat_mobile', array(
				'label'        => __( 'Hide Post Categories on Mobile?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'hide_meta_mobile', array(
				'label'        => __( 'Hide Post Meta on Mobile', 'soledad' ),
				'description'  => __( 'Include: Author Name, Date, Comments Count, Views Count, Reading Time', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_carousel_options',
			array(
				'label' => __( 'Carousel Options', 'soledad' ),
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
			'autoplay', array(
				'label'   => __( 'Autoplay', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'loop', array(
				'label'     => __( 'Carousel Loop', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [ 'carousel_slider_effect' => 'default' ],
			)
		);
		$this->add_control(
			'auto_time', array(
				'label'   => __( 'Carousel Auto Time ( 1000 = 1s )', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4000,
			)
		);
		$this->add_control(
			'speed', array(
				'label'   => __( 'Carousel Speed ( 1000 = 1s )', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 600,
			)
		);
		$this->add_control(
			'shownav', array(
				'label' => __( 'Hide next/prev buttons', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'showdots', array(
				'label' => __( 'Show dots navigation', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'Big Post', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'item_bg',
			array(
				'label'     => __( 'Overlay Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap' => '--obg: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'item_bg_hover',
			array(
				'label'     => __( 'Overlay Background Color on Hover', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap' => '--ohbg: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'item_padding', array(
				'label'      => __( 'Element Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-bg-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_responsive_control(
			'mp_item_padding', array(
				'label'      => __( 'Big Post Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-mp' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_responsive_control(
			'mp_icon_iw', array(
				'label'     => __( 'Icon/Content Width ( % )', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( '%' => array( 'min' => 20, 'max' => 50, 'step' => 1 ) ),
				'selectors' => array(
					'(tablet+){{WRAPPER}} .pc-fho-wrap' => '--iconw: {{SIZE}}%;'
				),
			)
		);

		$this->add_responsive_control(
			'mp_icon_content_spacing', array(
				'label'     => __( 'Spacing Between Icon & Content', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 200, 'step' => 1 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pc-fho-wrap' => '--maingap: {{SIZE}}px;'
				),
			)
		);

		$this->add_responsive_control(
			'mp_lp_spacing', array(
				'label'     => __( 'Spacing Between Big Post & Small Posts', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, 'step' => 1 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-msli' => 'margin-top: {{SIZE}}px;'
				),
			)
		);

		$this->add_control(
			'mp_icon_heading_title',
			array(
				'label'     => __( 'Big Post Icon', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'mp_icon_align', array(
				'label'       => __( 'Icon Position', 'soledad' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'row'         => array(
						'title' => __( 'Left', 'soledad' ),
						'icon'  => 'eicon-text-align-left',
					),
					'row-reverse' => array(
						'title' => __( 'Right', 'soledad' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-mpct' => 'flex-direction: {{VALUE}}'
				)
			)
		);

		$this->add_responsive_control(
			'mp_icon_talign', array(
				'label'       => __( 'Icon Align', 'soledad' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'center',
				'options'     => array(
					'flex-start' => array(
						'title' => __( 'Left', 'soledad' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'soledad' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'soledad' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-mpct .pc-fho-mi' => 'justify-content: {{VALUE}}'
				)
			)
		);

		$this->add_responsive_control(
			'mp_icon_size', array(
				'label'     => __( 'Icon Wrapper Size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 300, 'step' => 1 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pc-fho-wrap' => '--iconsz: {{SIZE}}px;'
				),
			)
		);

		$this->add_responsive_control(
			'mp_icon_border_width', array(
				'label'     => __( 'Borders Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 50, 'step' => 1 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pc-fho-wrap' => '--iconbd: {{SIZE}}px;'
				),
			)
		);

		$this->add_responsive_control(
			'mp_icon_i_size', array(
				'label'     => __( 'Icon Size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, 'step' => 1 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-mi-i i' => 'font-size: {{SIZE}}px;'
				),
			)
		);

		$this->add_control(
			'mp_icon_border_color',
			array(
				'label'     => __( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-mi-i' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'mp_icon_bg_color',
			array(
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-mi-i' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'mp_icon_color',
			array(
				'label'     => __( 'Icon Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-mi-i' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'heading_pcat',
			array(
				'label'     => __( 'Big Post Categories', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'cat_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-main-cat,{{WRAPPER}} .pc-fho-wrap .pc-fho-main-cat a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'cat_hcolor',
			array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-main-cat a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'cat_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pc-fho-wrap .pc-fho-main-cat,{{WRAPPER}} .pc-fho-wrap .pc-fho-main-cat a',
			)
		);

		$this->add_control(
			'heading_ptitle',
			array(
				'label'     => __( 'Big Post Title', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-mc .pc-fho-mt a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'title_hcolor',
			array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-mc .pc-fho-mt a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'title_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pc-fho-wrap .pc-fho-mc .pc-fho-mt h3',
			)
		);

		$this->add_control(
			'heading_meta',
			array(
				'label'     => __( 'Big Post Meta', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-mc .pc-fho-mm span, {{WRAPPER}} .pc-fho-wrap .pc-fho-mc .pc-fho-mm a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'links_color',
			array(
				'label'     => __( 'Links Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-mc .pc-fho-mm span a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'links_hcolor',
			array(
				'label'     => __( 'Links Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-mc .pc-fho-mm span a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'meta_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pc-fho-wrap .pc-fho-mc .pc-fho-mm',
			)
		);

		$this->end_controls_section();

		// small list post
		$this->start_controls_section(
			'small_post_section_style_content',
			array(
				'label' => __( 'Small List Posts', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'sitem_bg',
			array(
				'label'     => __( 'Overlay Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap' => '--sobg: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'sitem_bg_hover',
			array(
				'label'     => __( 'Overlay Background Color on Hover', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap' => '--sohbg: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'small_post_item_bg',
			array(
				'label'     => __( 'Post Content Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-li-ct-inner-wrap' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'small_post_item_padding', array(
				'label'      => __( 'Post Content Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-li-ct-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_responsive_control(
			'small_post_text_align', array(
				'label'       => __( 'Content Text Align', 'soledad' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-li-ct-wrap' => 'text-align: {{VALUE}}'
				)
			)
		);

		$this->add_responsive_control(
			'small_post_heading_pcat',
			array(
				'label'     => __( 'Post Categories', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'small_post_cat_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-list-cat,{{WRAPPER}} .pc-fho-wrap .pc-fho-list-cat a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'small_post_cat_hcolor',
			array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-list-cat a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'small_post_cat_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pc-fho-wrap .pc-fho-list-cat,{{WRAPPER}} .pc-fho-wrap .pc-fho-list-cat a',
			)
		);

		$this->add_control(
			'small_post_heading_ptitle',
			array(
				'label'     => __( 'Post Title', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'small_post_title_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-lt a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'small_post_title_hcolor',
			array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-lt a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'small_post_title_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-lt h3',
			)
		);

		$this->add_control(
			'small_post_heading_meta',
			array(
				'label'     => __( 'Post Meta', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'small_post_meta_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-lm span, .pc-fho-wrap .pc-fho-li .pc-fho-lm a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'small_post_links_color',
			array(
				'label'     => __( 'Links Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-lm a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'small_post_links_hcolor',
			array(
				'label'     => __( 'Links Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-lm a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'small_post_meta_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-lm',
			)
		);

		$this->add_control(
			'small_post_heading_icon_format',
			array(
				'label'     => __( 'Post Icon Format', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'show_reviewpie' => array( 'yes' ) ),
			)
		);

		$this->add_control(
			'small_post_fm_bd_cl',
			array(
				'label'     => __( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcbg-thumb .icon-post-format' => 'border-color: {{VALUE}};' ),
				'condition' => array( 'show_reviewpie' => array( 'yes' ) ),
			)
		);

		$this->add_control(
			'small_post_fm_bg_cl',
			array(
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcbg-thumb .icon-post-format' => 'background-color: {{VALUE}};' ),
				'condition' => array( 'show_reviewpie' => array( 'yes' ) ),
			)
		);

		$this->add_control(
			'small_post_fm_cl',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcbg-thumb .icon-post-format' => 'color: {{VALUE}};' ),
				'condition' => array( 'show_reviewpie' => array( 'yes' ) ),
			)
		);

		$this->add_control(
			'small_post_heading_ele_spacing',
			array(
				'label'     => __( 'Elements Spacing', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'small_post_post_spacing', array(
				'label'     => __( 'Horizontal Spacing Between each Posts', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .pc-fho-wrap' => '--gap: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'small_post_post_vspacing', array(
				'description' => __( 'This option does not apply when small posts showing as slider', 'soledad' ),
				'label'       => __( 'Vertical Spacing Between each Posts', 'soledad' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors'   => array(
					'{{WRAPPER}} .pc-fho-wrap' => '--vgap: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'small_post_thumbnail_space', array(
				'label'     => __( 'Post Thumbnail Margin Bottom', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-list-img' => 'margin-bottom: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'small_post_cat_space', array(
				'label'     => __( 'Categories Margin Bottom', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-list-cat' => 'margin-bottom: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'small_post_meta_space', array(
				'label'     => __( 'Post Meta Margin Top', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .pc-fho-wrap .pc-fho-li .pc-fho-lm' => 'margin-top: {{SIZE}}px;',
				),
			)
		);

		$this->end_controls_section();
		// end Big Post list post

		$this->register_block_title_style_section_controls();

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

	protected function render() {
		$settings = $this->get_settings();

		$original_postype = $settings['posts_post_type'];

		if ( in_array( $original_postype, [
				'current_query',
				'related_posts'
			] ) && penci_elementor_is_edit_mode() && penci_is_builder_template() ) {
			$settings['posts_post_type'] = 'post';
		}

		//query
		$args          = Query_Control::get_query_args( 'posts', $settings );
		$args['paged'] = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );
		if ( $original_postype == 'current_query' ) {
			$paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$ppp    = $settings['posts_per_page'] ? $settings['posts_per_page'] : get_option( 'posts_per_page' );
			$ppp    = isset( $settings['arposts_per_page'] ) && $settings['arposts_per_page'] ? $settings['arposts_per_page'] : $ppp;
			$offset = 0;
			if ( $ppp ) {
				$args['posts_per_page'] = $ppp;
			}
			if ( $settings['arposts_new'] == 'yes' ) {
				$args['paged'] = 1;
			}
			if ( 0 < $settings['offset'] ) {
				$offset = $settings['offset'];
			}

			if ( ! empty( $settings['offset'] ) && $paged > 1 ) {
				$offset = $settings['offset'] + ( ( $paged - 1 ) * $ppp );
			}

			if ( $offset ) {
				$args['offset'] = $offset;
			}
		}
		$pc_fwhro_query = new \WP_Query( $args );
		$first          = 0;
		$total_post     = $pc_fwhro_query->post_count;

		// style
		$slider           = false;
		$main_thumb_size  = $settings['main_thumb_size'] ? $settings['main_thumb_size'] : 'penci-single-full';
		$main_mthumb_size = $settings['main_mthumb_size'] ? $settings['main_mthumb_size'] : 'penci-masonry-thumb';
		$thumb_size       = $settings['thumb_size'] ? $settings['thumb_size'] : 'penci-thumb';
		$mthumb_size      = $settings['mthumb_size'] ? $settings['mthumb_size'] : 'penci-thumb';
		$post_meta        = $settings['post_meta'] ? $settings['post_meta'] : array();
		$primary_cat      = $settings['primary_cat'] ? $settings['primary_cat'] : '';
		$title_length     = $settings['title_length'] ? $settings['title_length'] : '';
		$title_blength    = $settings['title_blength'] ? $settings['title_blength'] : $title_length;
		$mobile_column    = isset( $settings['mb_column'] ) && $settings['mb_column'] ? $settings['mb_column'] : 1;
		$tablet_column    = isset( $settings['tab_column'] ) && $settings['tab_column'] ? $settings['tab_column'] : 2;

		if ( $total_post - 1 > $settings['column'] ) {
			$slider = true;
		}

		$thumbnail      = $thumb_size;
		$main_thumbnail = $main_thumb_size;
		if ( penci_is_mobile() ) {
			$thumbnail      = $mthumb_size;
			$main_thumbnail = $main_mthumb_size;
		}

		$data_slider       = '';
		$data_slider_class = '';
		if ( $slider ) {
			$data_slider_class = ' penci-owl-carousel penci-owl-carousel-slider';
			$data_slider       .= $settings['showdots'] ? ' data-dots="true"' : '';
			$data_slider       .= 'yes' == $settings['shownav'] ? ' data-nav="false"' : ' data-nav="true"';
			$data_slider       .= ! $settings['loop'] ? ' data-loop="true"' : '';
			$data_slider       .= ' data-auto="' . ( 'yes' == $settings['autoplay'] ? 'true' : 'false' ) . '"';
			$data_slider       .= $settings['auto_time'] ? ' data-autotime="' . $settings['auto_time'] . '"' : ' data-autotime="4000"';
			$data_slider       .= $settings['speed'] ? ' data-speed="' . $settings['speed'] . '"' : ' data-speed="600"';

			$data_slider .= ' data-item="' . ( isset( $settings['column'] ) && $settings['column'] ? $settings['column'] : '3' ) . '"';
			$data_slider .= ' data-desktop="' . ( isset( $settings['column'] ) && $settings['column'] ? $settings['column'] : '3' ) . '" ';
			$data_slider .= ' data-tablet="' . ( isset( $settings['tab_column'] ) && $settings['tab_column'] ? $settings['tab_column'] : '2' ) . '"';
			$data_slider .= ' data-tabsmall="' . ( isset( $settings['tab_column'] ) && $settings['tab_column'] ? $settings['tab_column'] : '2' ) . '"';
			$data_slider .= ' data-mobile="' . ( isset( $settings['mb_column'] ) && $settings['mb_column'] ? $settings['mb_column'] : '1' ) . '"';
			$data_slider .= ' data-ceffect="' . $settings['carousel_slider_effect'] . '"';
		}

		if ( $pc_fwhro_query->have_posts() ) {
			?>
            <div class="pc-fho">
				<?php $this->markup_block_title( $settings, $this ); ?>
                <div class="pc-fho-wrap">
                    <div class="pc-fho-bg-wrap">
                        <div class="pc-fho-container">
							<?php while ( $pc_fwhro_query->have_posts() ) : $pc_fwhro_query->the_post(); ?>
                                <div class="pc-fho-mp pc-fho-content penci-lazy"
                                     data-bgset="<?php echo penci_get_featured_image_size( get_the_ID(), $main_thumbnail ); ?>">
                                    <div class="pc-fho-mpct">
										<?php if ( 'yes' != $settings['show_bicon'] ) { ?>
                                            <div class="pc-fho-mi">
                                                <div class="pc-fho-mi-i">
													<?php if ( has_post_format( 'video' ) ) : ?>
                                                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></a>
													<?php endif; ?>
													<?php if ( has_post_format( 'gallery' ) ) : ?>
                                                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                           aria-label="Icon"><?php penci_fawesome_icon( 'penciicon-gallery' ); ?></a>
													<?php endif; ?>
													<?php if ( has_post_format( 'audio' ) ) : ?>
                                                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-music' ); ?></a>
													<?php endif; ?>
													<?php if ( has_post_format( 'link' ) ) : ?>
                                                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-link' ); ?></a>
													<?php endif; ?>
													<?php if ( has_post_format( 'quote' ) ) : ?>
                                                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                           aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-quote-left' ); ?></a>
													<?php endif; ?>
													<?php if ( ! get_post_format() ) : ?>
                                                        <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                           aria-label="Icon"><?php penci_fawesome_icon( 'penciicon-newspaper' ); ?></a>
													<?php endif; ?>
                                                </div>
                                            </div>
										<?php } ?>
                                        <div class="pc-fho-mc<?php if ( 'yes' == $settings['show_bicon'] ) {
											echo ' bct-fullwidth';
										} ?>">
											<?php if ( in_array( 'cat', $post_meta ) ) : ?>
                                                <div class="cat pc-fho-main-cat pc-fho-cat">
													<?php penci_category( '', $primary_cat ); ?>
                                                </div>
											<?php endif; ?>
                                            <div class="pc-fho-mt">
                                                <h3>
                                                    <a href="<?php the_permalink(); ?>">
														<?php if ( ! $title_blength ) {
															the_title();
														} else {
															echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $title_blength, '...' );
														} ?>
                                                    </a>
                                                </h3>
                                            </div>
											<?php if ( count( array_intersect( array(
													'author',
													'date',
													'comment',
													'views',
													'reading'
												), $post_meta ) ) > 0 ) { ?>
                                                <div class="grid-post-box-meta pc-fho-mm">
													<?php if ( in_array( 'author', $post_meta ) ) : ?>
                                                        <span class="pfw-date-author author-italic">
												<?php echo penci_get_setting( 'penci_trans_by' ); ?> <a class="url fn n"
                                                                                                        href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
											</span>
													<?php endif; ?>
													<?php if ( in_array( 'date', $post_meta ) ) : ?>
                                                        <span class="pfw-date"><?php penci_soledad_time_link(); ?></span>
													<?php endif; ?>
													<?php if ( in_array( 'comment', $post_meta ) ) : ?>
                                                        <span class="pfw-comment">
												<a href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a>
											</span>
													<?php endif; ?>
													<?php
													if ( in_array( 'views', $post_meta ) ) {
														echo '<span class="pfw-views">';
														echo penci_get_post_views( get_the_ID() );
														echo ' ' . penci_get_setting( 'penci_trans_countviews' );
														echo '</span>';
													}
													?>
													<?php
													$hide_readtime = in_array( 'reading', $post_meta ) ? false : true;
													if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                                                        <span class="pfw-readtime"><?php penci_reading_time(); ?></span>
													<?php endif; ?>
													<?php do_action( 'penci_extra_meta' ); ?>
                                                </div>
											<?php } ?>
                                        </div>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="mp-link penci-lazy"
                                       data-bgset="<?php echo penci_get_featured_image_size( get_the_ID(), $main_thumbnail ); ?>"><?php the_title(); ?></a>
                                </div>
								<?php
								if ( $first ++ == 0 ) {
									break;
								}
							endwhile; ?>
                            <div class="pc-fho-msli-wrapper">
                                <div class="pc-fho-msli pc-fho-content tablet-columns-<?php echo $tablet_column; ?> mobile-columns-<?php echo $mobile_column; ?> columns-<?php echo $settings['column']; ?>">
                                    <div class="pc-fho-msli-inner-ct swiper <?php echo $data_slider_class; ?>" <?php echo $data_slider; ?>>
                                        <div class="swiper-wrapper">
											<?php while ( $pc_fwhro_query->have_posts() ) : $pc_fwhro_query->the_post(); ?>
                                                <div class="pc-fho-li swiper-slide">
                                                    <div class="pc-fho-li-ct-inner-wrap">
                                                        <div class="pc-fho-list-img pcbg-thumb">
															<?php
															do_action( 'penci_bookmark_post', get_the_ID(), 'small' );
															/* Display Review Piechart  */
															if ( 'yes' == $settings['show_reviewpie'] && function_exists( 'penci_display_piechart_review_html' ) ) {
																penci_display_piechart_review_html( get_the_ID(), 'small' );
															}
															?>
															<?php if ( 'yes' == $settings['show_formaticon'] ): ?>
																<?php if ( has_post_format( 'video' ) ) : ?>
                                                                    <a href="<?php the_permalink() ?>"
                                                                       class="icon-post-format"
                                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></a>
																<?php endif; ?>
																<?php if ( has_post_format( 'gallery' ) ) : ?>
                                                                    <a href="<?php the_permalink() ?>"
                                                                       class="icon-post-format"
                                                                       aria-label="Icon"><?php penci_fawesome_icon( 'penciicon-gallery' ); ?></a>
																<?php endif; ?>
																<?php if ( has_post_format( 'audio' ) ) : ?>
                                                                    <a href="<?php the_permalink() ?>"
                                                                       class="icon-post-format"
                                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-music' ); ?></a>
																<?php endif; ?>
																<?php if ( has_post_format( 'link' ) ) : ?>
                                                                    <a href="<?php the_permalink() ?>"
                                                                       class="icon-post-format"
                                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-link' ); ?></a>
																<?php endif; ?>
																<?php if ( has_post_format( 'quote' ) ) : ?>
                                                                    <a href="<?php the_permalink() ?>"
                                                                       class="icon-post-format"
                                                                       aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-quote-left' ); ?></a>
																<?php endif; ?>
															<?php endif; ?>
															<?php if ( 'yes' != $settings['disable_lazy'] ) { ?>
                                                                <a href="<?php the_permalink(); ?>"
                                                                   class="penci-image-holder penci-lazy"
                                                                   data-bgset="<?php echo penci_get_featured_image_size( get_the_ID(), $thumbnail ); ?>">
                                                                    <span class="pc-fho-slolay"></span>
                                                                </a>
															<?php } else { ?>
                                                                <a href="<?php the_permalink(); ?>"
                                                                   class="penci-image-holder"
                                                                   style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), $thumbnail ); ?>');<?php if ( 'yes' == $settings['nocrop'] ) {
																	   echo 'padding-bottom: ' . penci_get_featured_image_padding_markup( get_the_ID(), $thumbnail, true ) . '%';
																   } ?>">
                                                                    <span class="pc-fho-slolay"></span>
                                                                </a>
															<?php } ?>
                                                        </div>
                                                        <div class="pc-fho-li-ct-wrap">
															<?php if ( in_array( 'cat', $post_meta ) ) : ?>
                                                                <div class="cat pc-fho-list-cat pc-fho-cat">
																	<?php penci_category( '', $primary_cat ); ?>
                                                                </div>
															<?php endif; ?>
                                                            <div class="pc-fho-lt">
                                                                <h3>
                                                                    <a href="<?php the_permalink(); ?>"><?php if ( ! $title_length ) {
																			the_title();
																		} else {
																			echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $title_length, '...' );
																		} ?></a></h3>
                                                            </div>
															<?php if ( count( array_intersect( array(
																	'author',
																	'date',
																	'comment',
																	'views',
																	'reading'
																), $post_meta ) ) > 0 ) { ?>
                                                                <div class="grid-post-box-meta pc-fho-lm">
																	<?php if ( in_array( 'author', $post_meta ) ) : ?>
                                                                        <span class="pfw-date-author author-italic">
												<?php echo penci_get_setting( 'penci_trans_by' ); ?> <a class="url fn n"
                                                                                                        href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
											</span>
																	<?php endif; ?>
																	<?php if ( in_array( 'date', $post_meta ) ) : ?>
                                                                        <span class="pfw-date"><?php penci_soledad_time_link(); ?></span>
																	<?php endif; ?>
																	<?php if ( in_array( 'comment', $post_meta ) ) : ?>
                                                                        <span class="pfw-comment">
												<a href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a>
											</span>
																	<?php endif; ?>
																	<?php
																	if ( in_array( 'views', $post_meta ) ) {
																		echo '<span class="pfw-views">';
																		echo penci_get_post_views( get_the_ID() );
																		echo ' ' . penci_get_setting( 'penci_trans_countviews' );
																		echo '</span>';
																	}
																	?>
																	<?php
																	$hide_readtime = in_array( 'reading', $post_meta ) ? false : true;
																	if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                                                                        <span class="pfw-readtime"><?php penci_reading_time(); ?></span>
																	<?php endif; ?>
																	<?php do_action( 'penci_extra_meta' ); ?>
                                                                </div>
															<?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
											<?php endwhile;
											wp_reset_postdata(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!--.pc-fho-msli-->
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}
	}
}
