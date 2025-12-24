<?php

namespace PenciSoledadElementor\Modules\PenciMarquee\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Css_Filter;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if accessed directly

class PenciMarquee extends Base_Widget {

	public function get_name() {
		return 'penci-marquee';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Marquee', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-slider-video';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'marquee', 'marquee text', 'marquee-list', 'news', 'ticker' ];
	}

	public function get_script_depends() {
		return [ 'penci-marquee-el' ];
	}

	protected function register_controls() {
		$this->register_controls_layout_items();
		$this->register_controls_marquee_options();
		$this->register_controls_style_text();
		$this->register_controls_style_images();
	}

	protected function register_controls_marquee_options() {
		$this->start_controls_section(
			'section_controls_marquee',
			[
				'label' => esc_html__( 'General Options', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'marquee_item_spacing',
			[
				'label'              => esc_html__( 'Item Spacing', 'soledad' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .penci-marquee-el .marquee-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'frontend_available' => true,
				'render_type'        => 'template',
			]
		);

		$this->add_control(
			'marquee_speed',
			[
				'label'              => esc_html__( 'Scroll Speed', 'soledad' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 10000,
				'step'               => 1,
				'default'            => 50,
				'frontend_available' => true,
				'render_type'        => 'none',
				'separator'          => 'after',

			]
		);
		$this->add_control(
			'marquee_direction',
			[
				'label'              => esc_html__( 'Direction', 'soledad' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'left'  => [
						'title' => esc_html__( 'Left', 'soledad' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'soledad' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'            => 'left',
				'frontend_available' => true,
				'render_type'        => 'template',
				'toggle'             => false,
				'separator'          => 'before',
			]
		);
		$this->add_control(
			'marquee_pause_on_hover',
			[
				'label'              => esc_html__( 'Pause On Hover', 'soledad' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'soledad' ),
				'label_off'          => esc_html__( 'No', 'soledad' ),
				'return_value'       => 'yes',
				'frontend_available' => true,

			]
		);

		$this->add_control(
			'marquee_draggable',
			[
				'label'              => esc_html__( 'Draggable', 'soledad' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'soledad' ),
				'label_off'          => esc_html__( 'No', 'soledad' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'marquee_clickable',
			[
				'label'              => esc_html__( 'Clickable', 'soledad' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'soledad' ),
				'label_off'          => esc_html__( 'No', 'soledad' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'marquee_advanced',
			[
				'label'              => esc_html__( 'Advanced Options', 'soledad' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'soledad' ),
				'label_off'          => esc_html__( 'No', 'soledad' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
			]
		);

		$this->add_responsive_control(
			'marquee_rotate',
			[
				'label'     => esc_html__( 'Rotate', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => - 100,
						'max'  => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--penci-marquee-el-rotate: {{SIZE}}deg;',
				],
				'separator' => 'before',
				'condition' => [
					'marquee_advanced' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'marquee_rotate_offset',
			[
				'label' => esc_html__( 'Offset left', 'soledad' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => - 500,
						'max'  => 500,
						'step' => 1,
					]
				],

				'selectors' => [
					'{{WRAPPER}} ' => '--penci-marquee-el-offset: -{{SIZE}}px;',
				],
				'condition' => [
					'marquee_advanced' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'marquee_rotate_adjustment',
			[
				'label'     => esc_html__( 'Offset Right', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => - 500,
						'max'  => 500,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} ' => '--penci-marquee-el-adjustment: {{SIZE}}px;',
				],
				'condition' => [
					'marquee_advanced' => 'yes'
				]
			]
		);

		$this->add_control(
			'skin_shadow_mode',
			[
				'label'        => esc_html__( 'Shadow Mode', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'penci-shadow-mode-',
				'render_type'  => 'template',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'skin_shadow_color',
			[
				'label'     => esc_html__( 'Shadow Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'skin_shadow_mode' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}.penci-shadow-mode-yes:before' => is_rtl() ? 'background: linear-gradient(to left, {{VALUE}} 5%,rgba(255,255,255,0) 100%);' : 'background: linear-gradient(to right, {{VALUE}} 5%,rgba(255,255,255,0) 100%);',
					'{{WRAPPER}}.penci-shadow-mode-yes:after'  => is_rtl() ? 'background: linear-gradient(to left, rgba(255,255,255,0) 0%, {{VALUE}} 95%);' : 'background: linear-gradient(to right, rgba(255,255,255,0) 0%, {{VALUE}} 95%);',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls_layout_items() {
		$this->start_controls_section(
			'section_layout_text',
			[
				'label' => esc_html__( 'Marquee', 'soledad' ),
			]
		);
		$this->add_control(
			'marquee_motice',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Please switch to "Preview Mode" to fully experience the Marquee Widget\'s functionality and make any needed adjustments.', 'soledad' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'marquee_type',
			[
				'label'              => esc_html__( 'Marquee Type', 'soledad' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'image' => esc_html__( 'Image', 'soledad' ),
					'text'  => esc_html__( 'Text', 'soledad' ),
				],
				'default'            => 'image',
				'frontend_available' => true,
				'render_type'        => 'template',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'label'     => esc_html__( 'Image Size', 'soledad' ),
				'exclude'   => [ 'custom' ],
				'default'   => 'medium',
				'condition' => [
					'marquee_type' => 'image'
				]
			]
		);

		$this->add_responsive_control(
			'marquee_image_height',
			[
				'label'       => esc_html__( 'Height', 'soledad' ),
				'description' => esc_html__( 'Set image size in pixel. Default is 250px', 'soledad' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', 'vh', '%' ],
				'range'       => [
					'px' => [
						'min'  => 50,
						'max'  => 450,
						'step' => 1,
					]
				],
				'default'     => [
					'size' => 250,
					'unit' => 'px',
				],
				'selectors'   => [
					'{{WRAPPER}} .penci-marquee-el .marquee-content img' => 'object-fit: contain;width: auto; height: {{SIZE}}{{UNIT}};',
				],
				'render_type'        => 'template',
				'condition' => [
					'marquee_type' => 'image'
				]
			]
		);

		$this->add_control(
			'marquee_image_caption',
			[
				'label'       => esc_html__( 'Show Image Caption', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition' => [
					'marquee_type' => 'image'
				]
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'marquee_content',
			[
				'label'       => esc_html__( 'Content', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
			]
		);
		$repeater->add_control(
			'marquee_link',
			[
				'label'         => __( 'Link', 'soledad' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://example.com', 'soledad' ),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);
		$repeater->add_control(
			'marquee_color',
			[
				'label'       => esc_html__( 'Color', 'soledad' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'render_type' => 'template',
				'separator'   => 'before'
			]
		);

		$repeater->add_control(
			'marquee_bg_color',
			[
				'label'       => esc_html__( 'Background Color', 'soledad' ),
				'type'        => Controls_Manager::COLOR,
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'marquee_type_text',
			[
				'label'              => esc_html__( 'Marquee Items', 'soledad' ),
				'type'               => Controls_Manager::REPEATER,
				'fields'             => $repeater->get_controls(),
				'title_field'        => '{{{ marquee_content }}}',
				'condition'          => [
					'marquee_type' => 'text'
				],
				'frontend_available' => true,
				'render_type'        => 'none',
				'prevent_empty'      => false,
				'default'            => [
					[
						'marquee_content' => esc_html__( "PenciDesign", 'soledad' )
					],
					[
						'marquee_content' => esc_html__( "Soledad", 'soledad' )
					],
					[
						'marquee_content' => esc_html__( "WordPress", 'soledad' )
					],
					[
						'marquee_content' => esc_html__( "WooCommerce", 'soledad' )
					],
					[
						'marquee_content' => esc_html__( "Elementor", 'soledad' )
					],
					[
						'marquee_content' => esc_html__( "ThemeForest", 'soledad' )
					],
				]
			]
		);

		$image_slides = new Repeater();
		$image_slides->add_control(
			'marquee_image',
			[
				'label' => esc_html__( 'Image', 'soledad' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);
		$image_slides->add_control(
			'marquee_image_link',
			[
				'label'         => __( 'Link', 'soledad' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://example.com', 'soledad' ),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);
		$this->add_control(
			'marquee_type_images',
			[
				'label'         => esc_html__( 'Maruqee Items', 'soledad' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $image_slides->get_controls(),
				'condition'     => [
					'marquee_type' => 'image'
				],
				'prevent_empty' => false,
				'default'       => [
					[
						'marquee_image' => [
							'url' => Utils::get_placeholder_image_src()

						]
					],
					[
						'marquee_image' => [
							'url' => Utils::get_placeholder_image_src()
						]
					],
					[
						'marquee_image' => [
							'url' => Utils::get_placeholder_image_src()
						]
					],
					[
						'marquee_image' => [
							'url' => Utils::get_placeholder_image_src()
						]
					],
					[
						'marquee_image' => [
							'url' => Utils::get_placeholder_image_src()
						]
					],
					[
						'marquee_image' => [
							'url' => Utils::get_placeholder_image_src()
						]
					],
					[
						'marquee_image' => [
							'url' => Utils::get_placeholder_image_src()
						]
					],
					[
						'marquee_image' => [
							'url' => Utils::get_placeholder_image_src()
						]
					]

				]
			]
		);
		$this->end_controls_section();
	}

	protected function register_controls_style_text() {

		$this->start_controls_section(
			'section_style_title',
			[
				'label'     => esc_html__( 'Marquee', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'marquee_type' => 'text'
				]
			]
		);

		$this->start_controls_tabs(
			'marquee_title_style_tabs'
		);
		$this->start_controls_tab(
			'marquee_title_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);
		$this->add_control(
			'marquee_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-marquee-el .marquee-content .marquee-title' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'marquee_title_background',
				'label'    => esc_html__( 'Background', 'soledad' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .penci-marquee-el .marquee-content',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'marquee_title_border',
				'label'     => esc_html__( 'Border', 'soledad' ),
				'selector'  => '{{WRAPPER}} .penci-marquee-el .marquee-content',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'marquee_title_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-marquee-el .marquee-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'marquee_title_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-marquee-el .marquee-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'marquee_title_typogrphy',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-marquee-el .marquee-content',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'     => 'title_text_stroke',
				'label'    => esc_html__( 'Text Stroke', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-marquee-el .marquee-content',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'marquee_title_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-marquee-el .marquee-content',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'marquee_title_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'marquee_title_h_color',
			[
				'label'     => esc_html__( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-marquee-el .marquee-content:hover .marquee-title' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'marquee_title_h_background',
				'label'    => esc_html__( 'Background', 'soledad' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .penci-marquee-el .marquee-content:hover',
			]
		);
		$this->add_control(
			'marquee_title_border_h_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-marquee-el .marquee-content:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'marquee_title_border_border!' => '',
				]
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	public function get_calculated_image_width( $attachment_id, $desired_height ) {
		// Get image metadata
		$image_meta = wp_get_attachment_metadata($attachment_id);
		
		if (!$image_meta || !isset($image_meta['width']) || !isset($image_meta['height'])) {
			return 200;
		}
		
		$original_width = $image_meta['width'];
		$original_height = $image_meta['height'];
		
		// Calculate aspect ratio and new width
		$aspect_ratio = $original_width / $original_height;
		$calculated_width = round($desired_height * $aspect_ratio);
		
		return $calculated_width;
	}

	protected function register_controls_style_images() {
		$this->start_controls_section(
			'section_style_controls_image',
			[
				'label'     => esc_html__( 'Marquee Images', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'marquee_type' => 'image'
				]
			]
		);

		$this->start_controls_tabs(
			'marquee_image_style_tabs'
		);
		$this->start_controls_tab(
			'marquee_image_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'marquee_image_background',
				'label'    => esc_html__( 'Background', 'soledad' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .penci-marquee-el .marquee-content',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'marquee_image_border',
				'label'    => esc_html__( 'Border', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-marquee-el .marquee-content',
			]
		);
		$this->add_responsive_control(
			'marquee_image_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-marquee-el .marquee-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'marquee_image_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-marquee-el .marquee-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .penci-marquee-el .marquee-image img',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'marquee_image_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'marquee_image_background_hover',
				'label'    => esc_html__( 'Background', 'soledad' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .penci-marquee-el .marquee-content:hover',
			]
		);
		$this->add_control(
			'marquee_border_hover_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-marquee-el .marquee-content:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'marquee_image_border_border!' => '',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .penci-marquee-el .marquee-image:hover img',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}


	public function render_thumbnail( $item, $show_caption = '' ) {
		$settings  = $this->get_settings_for_display();
		$image     = $item['marquee_image'];
		$thumb_url = Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'thumbnail', $settings );
		$link      = $item['marquee_image_link'];

		$width = $this->get_calculated_image_width( $image['id'], $settings['marquee_image_height']['size'] );
		$style     = 'width:' . $width . 'px;';
		$caption = wp_get_attachment_caption( $image['id'] );
		$class = $caption_html = '';
		if ( $show_caption && $caption ) {
			$class = ' wp-caption';
			$caption_html = '<p class="wp-caption-text">' . esc_html( $caption ) . '</p>';
		}
		// Define image content based on available URL
		$content = $thumb_url
			? wp_get_attachment_image( $image['id'], $settings['thumbnail_size'] )
			: '<img  src="' . esc_url( $image['url'] ) . '" alt="">';

		// Check if link exists and wrap content with link attributes
		if ( ! empty( $link['url'] ) ) {
			$this->add_link_attributes( 'marquee-link', $link, true );
			$link_attributes = 'class="marquee-link marquee-content marquee-image'.$class.'" ' . $this->get_render_attribute_string( 'marquee-link' );

			echo wp_kses_post( '<a ' . $link_attributes . ' style="' . esc_attr( $style ) . '">' . $content . $caption_html . '</a>' );
		} else {
			echo '<div class="marquee-content marquee-image'.$class.'" style="' . esc_attr( $style ) . '">' . wp_kses_post( $content ) . $caption_html . '</div>';
		}
	}


	function marquee_rolling() {
		$settings                     = $this->get_settings_for_display();
		$contentText                  = $settings['marquee_type_text'];
		$contentImages                = $settings['marquee_type_images'];
		?>
		<?php if ( $settings['marquee_type'] === 'text' ) : ?>
			<?php if ( $contentText ) :
				$count = 0;
				foreach ( $contentText as $index => $list ) :
					$single_color = 'link_' . $index;
					$marquee_bg_color = 'marquee_bg_color_' . $index;
					if ( ! empty( $list['marquee_bg_color'] ) ) {
						$this->add_render_attribute( $marquee_bg_color, 'style', 'background-color: ' . $list['marquee_bg_color'] . ';' );
					}
					if ( ! empty( $list['marquee_color'] ) ) {
						$this->add_render_attribute( $single_color, 'style', 'color: ' . $list['marquee_color'] . ';' );
					}
					?>

                    <div class="marquee-content marquee-text" <?php $this->print_render_attribute_string( $marquee_bg_color ); ?>>


						<?php
						if ( ! empty( $list['marquee_link']['url'] ) ) {
							$this->add_link_attributes( 'marquee-link', $list['marquee_link'], true );
							$link_attributes = 'class="marquee-title"';
							$link_attributes .= ' ' . $this->get_render_attribute_string( 'marquee-link' );
							printf( '<a %1$s %3$s>%2$s</a>', wp_kses_post( $link_attributes ), esc_html( $list['marquee_content'] ), wp_kses_post( $this->get_render_attribute_string( $single_color ) ) );
						} else {
							printf( '<span class="marquee-title" %1$s>%2$s</span>', wp_kses_post( $this->get_render_attribute_string( $single_color ) ), esc_html( $list['marquee_content'] ) );
						}
						?>
                    </div>

					<?php
					$count ++;
				endforeach;
			endif; ?>
		<?php endif; ?>

		<?php if ( $settings['marquee_type'] === 'image' ) : ?>
			<?php if ( $contentImages ) :
				foreach ( $contentImages as $key => $image ) :
					$this->render_thumbnail( $image, $settings['marquee_image_caption'] === 'yes' );
					?>
				<?php endforeach;
			endif; ?>
		<?php endif; ?>

		<?php
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'penci-marquee-el', [
			'id'    => 'penci-marque-' . $this->get_id() . '',
			'class' => [ 'penci-marquee-el', 'marquee-type-' . $settings['marquee_type'] . '' ],
		], null, true ); ?>


        <div <?php $this->print_render_attribute_string( 'penci-marquee-el' ); ?>>
			<?php $this->marquee_rolling(); ?>
        </div>
		<?php
	}
}
