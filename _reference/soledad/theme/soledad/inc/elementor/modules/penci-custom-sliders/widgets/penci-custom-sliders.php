<?php

namespace PenciSoledadElementor\Modules\PenciCustomSliders\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciCustomSliders extends Base_Widget {

	public function get_name() {
		return 'penci-custom-sliders';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Custom Slider', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-slideshow';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'slides', 'carousel', 'image', 'title', 'slider' );
	}

	protected function register_controls() {


		$this->start_controls_section(
			'section_slides', array(
				'label' => __( 'Slides', 'soledad' )
			)
		);

		$repeater = new Repeater();
		$repeater->start_controls_tabs( 'slides_repeater' );

		$repeater->start_controls_tab( 'content', array( 'label' => __( 'Content', 'soledad' ) ) );
		$repeater->add_control(
			'heading', array(
				'label'       => __( 'Title & Description', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Slide Heading', 'soledad' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'description', array(
				'label'      => __( 'Description', 'soledad' ),
				'type'       => Controls_Manager::TEXTAREA,
				'default'    => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'soledad' ),
				'show_label' => false,
			)
		);

		$repeater->add_control(
			'button_text', array(
				'label'   => __( 'Button Text 1', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'soledad' ),
			)
		);

		$repeater->add_control(
			'button_link', array(
				'label'       => __( 'Button Link 1', 'soledad' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'soledad' ),
			)
		);
		$repeater->add_control(
			'button_text2', array(
				'label'   => __( 'Button Text 2', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'soledad' ),
			)
		);

		$repeater->add_control(
			'button_link2', array(
				'label'       => __( 'Button Link 2', 'soledad' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'soledad' ),
			)
		);

		$repeater->add_control(
			'add_url_feat_img', array(
				'label'     => __( 'Add image url', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',

			)
		);

		$repeater->add_control(
			'url_feat_img', array(
				'label'       => __( 'Image Url', 'soledad' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'soledad' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'  => 'add_url_feat_img',
							'value' => 'yes',
						)
					),
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'background', array( 'label' => __( 'Background', 'soledad' ) ) );

		$repeater->add_control(
			'background_type', array(
				'label'   => __( 'Background Type', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => [
					'image' => 'Image',
					'video' => 'Video',
				]
			)
		);

		$repeater->add_control(
			'background_color', array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#bbbbbb',
				'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} {{CURRENT_ITEM}} .penci-ctslide-bg' => 'background-color: {{VALUE}}' ),
			)
		);

		$repeater->add_control(
			'background_video',
			array(
				'label'       => __( 'Background Video URL', 'soledad' ),
				'label_block' => true,
				'separator'   => 'before',
				'condition'   => [ 'background_type' => 'video' ],
			)
		);

		$repeater->add_control(
			'background_image', array(
				'label'     => _x( 'Image', 'Background Control', 'soledad' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => [ 'background_type' => 'image' ],
				'selectors' => array( '{{WRAPPER}} {{CURRENT_ITEM}} .penci-ctslide-bg' => 'background-image: url({{URL}})' ),
			)
		);
		$repeater->add_control(
			'background_size', array(
				'label'     => _x( 'Size', 'Background Control', 'soledad' ),
				'condition' => [ 'background_type' => 'image' ],
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => _x( 'Cover', 'Background Control', 'soledad' ),
					'contain' => _x( 'Contain', 'Background Control', 'soledad' ),
					'auto'    => _x( 'Auto', 'Background Control', 'soledad' ),
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .penci-ctslide-bg' => 'background-size: {{VALUE}}'
				),
				'condition' => [ 'background_type' => 'image' ],
			)
		);

		$repeater->add_control(
			'background_ken_burns', array(
				'label'     => __( 'Ken Burns Effect', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'separator' => 'before',
				'condition' => [ 'background_type' => 'image' ],
			)
		);

		$repeater->add_control(
			'zoom_direction', array(
				'label'     => __( 'Zoom Direction', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'in',
				'options'   => array(
					'in'  => __( 'In', 'soledad' ),
					'out' => __( 'Out', 'soledad' ),
				),
				'condition' => [ 'background_ken_burns' => 'yes' ],
			)
		);

		$repeater->add_control(
			'background_overlay', array(
				'label'     => __( 'Background Overlay', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'separator' => 'before',
				'condition' => [ 'background_type' => 'image' ],
			)
		);

		$repeater->add_control(
			'background_overlay_color', array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.5)',
				'condition' => [ 'background_overlay' => 'yes' ],
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} {{CURRENT_ITEM}} .penci-ctslide-inner .penci-ctslider-bg-overlay' => 'background-color: {{VALUE}}'
				)
			)
		);
		$repeater->add_control(
			'background_overlay_blend_mode', array(
				'label'     => __( 'Blend Mode', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''            => __( 'Normal', 'soledad' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn'  => 'Color Burn',
					'hue'         => 'Hue',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'exclusion'   => 'Exclusion',
					'luminosity'  => 'Luminosity',
				),
				'condition' => [ 'background_overlay' => 'yes' ],
				'selectors' => array( '{{WRAPPER}} {{CURRENT_ITEM}} .penci-ctslide-inner .penci-ctslider-bg-overlay' => 'mix-blend-mode: {{VALUE}}' ),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'style', array( 'label' => __( 'Style', 'soledad' ) ) );

		$repeater->add_control(
			'custom_style', array(
				'label'       => __( 'Custom', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Set custom style that will only affect this specific slide.', 'soledad' ),
			)
		);

		$repeater->add_control(
			'horizontal_position', array(
				'label'                => __( 'Horizontal Position', 'soledad' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'soledad' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'soledad' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'soledad' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .penci-ctslide-inner .penci-ctslider-content' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'left'   => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right'  => 'margin-left: auto',
				),
				'conditions'           => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						)
					),
				),
			)
		);

		$repeater->add_control(
			'vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'soledad' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
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
					'{{WRAPPER}} {{CURRENT_ITEM}} .penci-ctslide-inner' => 'align-items: {{VALUE}}',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'conditions'           => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						)
					)
				),
			)
		);

		$repeater->add_control(
			'text_align', array(
				'label'       => __( 'Text Align', 'soledad' ),
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
					'{{WRAPPER}} {{CURRENT_ITEM}} .penci-ctslide-inner' => 'text-align: {{VALUE}}'
				),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						)
					)
				),
			)
		);

		$repeater->add_control(
			'content_color', array(
				'label'      => __( 'Content Color', 'soledad' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .pencislider-title'                     => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .pencislider-caption'                   => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .penci-slider_btnwrap .pencislider-btn' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						)
					)
				)
			)
		);

		$repeater->add_control(
			'bg_item_overlay', array(
				'label'      => __( 'Enable Overlay Background Color', 'soledad' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => '',
				'options'    => array(
					''    => 'Default',
					'yes' => 'Yes',
					'no'  => 'No',
				),
				'separator'  => 'before',
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						)
					)
				)
			)
		);
		$repeater->add_control(
			'bgoverlay_opacity', array(
				'label'      => __( 'Overlay Background Opacity', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array( 'px' => array( 'max' => 1, 'step' => 0.01 ) ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .pencislider-title-overlay .pslider-bgoverlay-inner:before'   => 'opacity: {{SIZE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .pencislider-caption-overlay .pslider-bgoverlay-inner:before' => 'opacity: {{SIZE}};',

				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						)
					)
				)
			)
		);
		$repeater->add_control(
			'bgoverlay_color', array(
				'label'      => __( 'Overlay Background Color', 'soledad' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => array(
					'body:not(.pcdm-enable) {{WRAPPER}} {{CURRENT_ITEM}} .pencislider-title-overlay .pslider-bgoverlay-inner:before'   => 'background-color: {{VALUE}}',
					'body:not(.pcdm-enable) {{WRAPPER}} {{CURRENT_ITEM}} .pencislider-caption-overlay .pslider-bgoverlay-inner:before' => 'background-color: {{VALUE}}',
				),
				'default'    => '',
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						)
					)
				)
			)
		);
		$repeater->add_responsive_control(
			'bgoverlay_padding', array(
				'label'      => __( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .pencislider-title-overlay .pslider-bgoverlay-inner'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .pencislider-caption-overlay .pslider-bgoverlay-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						)
					)
				)
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'penci_slides', array(
				'label'       => __( 'Slides', 'soledad' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'heading'          => __( 'Slide 1 Heading', 'soledad' ),
						'description'      => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'soledad' ),
						'button_text'      => __( 'Click Here', 'soledad' ),
						'button_text2'     => __( 'Click Here', 'soledad' ),
						'background_color' => '#833ca3',
					),
					array(
						'heading'          => __( 'Slide 2 Heading', 'soledad' ),
						'description'      => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'soledad' ),
						'button_text'      => __( 'Click Here', 'soledad' ),
						'button_text2'     => __( 'Click Here', 'soledad' ),
						'background_color' => '#4054b2',
					),
					array(
						'heading'          => __( 'Slide 3 Heading', 'soledad' ),
						'description'      => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'soledad' ),
						'button_text'      => __( 'Click Here', 'soledad' ),
						'button_text2'     => __( 'Click Here', 'soledad' ),
						'background_color' => '#1abc9c',
					)
				),
				'title_field' => '{{{ heading }}}',
			)
		);

		$this->add_control(
			'use_parallax', array(
				'label'     => __( 'Enable Parallax Effect', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',

			)
		);

		$this->add_control(
			'fullscreen', array(
				'label'     => __( 'Enable Full Screen Slider', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .penci-custom-slides' => 'width: 100%;height: 100vh',
					'{{WRAPPER}} .penci-ctslide-wrap'  => 'height: 100vh;'
				]
			)
		);

		$this->add_control(
			'use_ratio', array(
				'label'     => __( 'Use Ratio Height/Width', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [ 'fullscreen!' => 'yes' ]
			)
		);
		$this->add_responsive_control(
			'slides_img_ratio', array(
				'label'      => __( 'Ratio Height/Width', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 0.5 ),
				'range'      => array( 'px' => array( 'min' => 0.1, 'max' => 2, 'step' => 0.01 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .penci-ctslide-wrap'        => 'height: auto !important;',
					'{{WRAPPER}} .penci-ctslide-wrap:before' => 'content:"";padding-top:calc( {{SIZE}} * 100% );',
				),
				'conditions' => array(
					'relation' => 'AND',
					'terms'    => [
						[
							'name'     => 'fullscreen',
							'operator' => '!=',
							'value'    => 'yes'
						],
						[
							'name'     => 'use_ratio',
							'operator' => '=',
							'value'    => 'yes'
						],
					]
				)
			)
		);

		$this->add_responsive_control(
			'slides_height', array(
				'label'      => __( 'Height', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array( 'min' => 100, 'max' => 1500 ),
				),
				'default'    => array( 'size' => 400 ),
				'size_units' => array( 'px' ),
				'selectors'  => array( '{{WRAPPER}} .penci-ctslide-wrap' => 'height: {{SIZE}}{{UNIT}};' ),
				'conditions' => array(
					'relation' => 'AND',
					'terms'    => [
						[
							'name'     => 'fullscreen',
							'operator' => '!=',
							'value'    => 'yes'
						],
						[
							'name'     => 'use_ratio',
							'operator' => '!=',
							'value'    => 'yes'
						],
					]
				)
			)
		);

		$this->add_responsive_control(
			'slides_img_ratio_f', array(
				'label'     => __( 'Ratio Height/Width on Mobile', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'devices'   => [ 'mobile' ],
				'default'   => array( 'size' => 0.5 ),
				'range'     => array( 'px' => array( 'min' => 0.1, 'max' => 2, 'step' => 0.01 ) ),
				'selectors' => array(
					'{{WRAPPER}} .enable-fullscreen .penci-ctslide-wrap:before'                                              => 'content:"";padding-top:calc( {{SIZE}} * 100% );',
					'{{WRAPPER}} .penci-custom-slides.enable-fullscreen, {{WRAPPER}} .enable-fullscreen .penci-ctslide-wrap' => 'height: auto !important;width: 100%',
				),
				'condition' => [ 'fullscreen' => 'yes' ]
			)
		);

		$this->add_control(
			'btn_2lines', array(
				'label'       => __( 'Second button in a new line on mobile?', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Use in case you\'re using 2 buttons and this option helps you can show 2 buttons on mobile in 2 separate rows in case your buttons have long text', 'soledad' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_options',
			array(
				'label' => __( 'Slider Options', 'soledad' ),
				'type'  => Controls_Manager::SECTION,
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

		$this->add_control( 'single_slider_effect', array(
			'label'   => __( 'General Slider Effect', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => get_theme_mod( 'penci_single_slider_effect', 'creative' ),
			'options' => array(
				'slide'     => 'Slide',
				'fade'      => 'Fade',
				'coverflow' => 'Coverflow',
				'flip'      => 'Flip',
				'cards'     => 'Cards',
				'creative'  => 'Creative',
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
				'label'     => __( 'Slider Loop', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [ 'carousel_slider_effect' => 'default' ],
			)
		);
		$this->add_control(
			'auto_time', array(
				'label'   => __( 'Slider Auto Time (at x seconds)', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4000,
			)
		);
		$this->add_control(
			'speed', array(
				'label'       => __( 'Slider Speed (at x seconds)', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 800,
				'render_type' => 'template',
				'selectors'   => [ '{{WRAPPER}} .penci-owl-carousel' => '--pcfs-delay:calc({{VALUE}}s / 1000 - 0.1s)' ]
			)
		);
		$this->add_control(
			'shownav', array(
				'label'   => __( 'Show next/prev buttons', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);
		$this->add_control(
			'showdots', array(
				'label' => __( 'Show dots navigation', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'showdots_type',
			array(
				'label'     => __( 'Dots Navigation Types', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'dots',
				'condition' => [ 'showdots' => 'yes' ],
				'options'   => array(
					'dots'        => __( 'Dots', 'soledad' ),
					'number'      => __( 'Numbers', 'soledad' ),
					'number line' => __( 'Lines', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'transition',
			array(
				'label'   => __( 'Transition', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => array(
					'slide' => __( 'Slide', 'soledad' ),
					'fade'  => __( 'Fade', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'content_animation',
			array(
				'label'   => __( 'Content Animation', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fadeInUp',
				'options' => array(
					''            => __( 'None', 'soledad' ),
					'fadeInUp'    => 'Fade In Up',
					'fadeInDown'  => 'Fade In Down',
					'fadeInLeft'  => 'Fade In Left',
					'fadeInRight' => 'Fade In Right',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_slides', array(
				'label' => __( 'Slides', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'content_max_width', array(
				'label'          => __( 'Content Width', 'soledad' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'px' => array( 'min' => 0, 'max' => 1000 ),
					'%'  => array( 'min' => 0, 'max' => 100 )
				),
				'size_units'     => array( '%', 'px' ),
				'default'        => array( 'size' => '66', 'unit' => '%' ),
				'tablet_default' => array( 'unit' => '%' ),
				'mobile_default' => array( 'unit' => '%' ),
				'selectors'      => array( '{{WRAPPER}} .penci-ctslider-content' => 'max-width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'slides_padding', array(
				'label'      => __( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .penci-ctslide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'slides_horizontal_position', array(
				'label'        => __( 'Horizontal Position', 'soledad' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => 'center',
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'soledad' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'soledad' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'soledad' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'penci-h-poswrap-',
			)
		);

		$this->add_control(
			'slides_vertical_position', array(
				'label'        => __( 'Vertical Position', 'soledad' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => 'middle',
				'options'      => array(
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
				'prefix_class' => 'penci-v-poswrap-',
			)
		);

		$this->add_control(
			'slides_text_align', array(
				'label'       => __( 'Text Align', 'soledad' ),
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
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}} .penci-ctslide-inner' => 'text-align: {{VALUE}}'
				)
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title', array(
				'label' => __( 'Title', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_spacing', array(
				'label'     => __( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pencislider-title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
				)
			)
		);

		$this->add_control(
			'heading_color', array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .pencislider-title' => 'color: {{VALUE}}' )
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .pencislider-title',
			)
		);

		$this->add_control(
			'heading_overlay', array(
				'label'     => __( 'Enable Overlay Background Color', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before'
			)
		);
		$this->add_control(
			'heading_bgoverlay_opacity', array(
				'label'     => __( 'Overlay Background Opacity', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => .4 ),
				'range'     => array( 'px' => array( 'max' => 1, 'step' => 0.01 ) ),
				'selectors' => array( '{{WRAPPER}} .pencislider-title-overlay .pslider-bgoverlay-inner:before' => 'opacity: {{SIZE}};' ),
				'condition' => array( 'heading_overlay' => 'yes' )
			)
		);
		$this->add_control(
			'heading_bgoverlay_color', array(
				'label'     => __( 'Overlay Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .pencislider-title-overlay .pslider-bgoverlay-inner:before' => 'background-color: {{VALUE}}' ),
				'default'   => '#000000',
				'condition' => array( 'heading_overlay' => 'yes' )
			)
		);
		$this->add_responsive_control(
			'heading_bgoverlay_padding', array(
				'label'      => __( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'condition'  => array( 'heading_overlay' => 'yes' ),
				'selectors'  => array( '{{WRAPPER}} .pencislider-title-overlay .pslider-bgoverlay-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' )
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description', array(
				'label' => __( 'Description', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'description_spacing', array(
				'label'     => __( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pencislider-caption' => 'margin-bottom: {{SIZE}}{{UNIT}}'
				)
			)
		);

		$this->add_control(
			'description_color', array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .pencislider-caption' => 'color: {{VALUE}}' )
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .pencislider-caption',
			)
		);
		$this->add_control(
			'description_overlay', array(
				'label'     => __( 'Enable Overlay Background Color', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before'
			)
		);
		$this->add_control(
			'desc_bgoverlay_opacity', array(
				'label'     => __( 'Overlay Background Opacity', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => .4 ),
				'range'     => array( 'px' => array( 'max' => 1, 'step' => 0.01 ) ),
				'selectors' => array( '{{WRAPPER}} .pencislider-caption .pslider-bgoverlay-inner:before' => 'opacity: {{SIZE}};' ),
				'condition' => array( 'description_overlay' => 'yes' )
			)
		);
		$this->add_control(
			'desc_bgoverlay_color', array(
				'label'     => __( 'Overlay Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .pencislider-caption .pslider-bgoverlay-inner:before' => 'background-color: {{VALUE}}' ),
				'default'   => '#000000',
				'condition' => array( 'description_overlay' => 'yes' )
			)
		);
		$this->add_responsive_control(
			'desc_bgoverlay_padding', array(
				'label'      => __( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'condition'  => array( 'description_overlay' => 'yes' ),
				'selectors'  => array( '{{WRAPPER}} .pencislider-caption-overlay .pslider-bgoverlay-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' )
			)
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button', array(
				'label' => __( 'Button 1', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-1',
			)
		);
		$this->add_control(
			'button_width', array(
				'label'     => __( 'Button Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 400, ), ),
				'selectors' => array( '{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-1' => 'width: {{SIZE}}{{UNIT}};', ),
			)
		);
		$this->add_control(
			'button_height', array(
				'label'     => __( 'Button Height', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ), ),
				'selectors' => array( '{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-1' => 'height: {{SIZE}}{{UNIT}};', ),
			)
		);
		$this->add_control(
			'button_border_width',
			array(
				'label'     => __( 'Border Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-1' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'button_border_radius',
			array(
				'label'     => __( 'Border Radius', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-1' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'after',
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab( 'normal', array( 'label' => __( 'Normal', 'soledad' ) ) );

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-1' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-1' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => __( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-1' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover', array( 'label' => __( 'Hover', 'soledad' ) ) );

		$this->add_control(
			'button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-1:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-1:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .penci-slider_btnwrap .pencislider-btn-1:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Button 2
		$this->start_controls_section(
			'section2_style_button', array(
				'label' => __( 'Button 2', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button2_typography',
				'selector' => '{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-2',
			)
		);
		$this->add_control(
			'button2_width', array(
				'label'     => __( 'Button Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 400, ), ),
				'selectors' => array( '{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-2' => 'width: {{SIZE}}{{UNIT}};', ),
			)
		);
		$this->add_control(
			'button2_height', array(
				'label'     => __( 'Button Height', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ), ),
				'selectors' => array( '{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-2' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};', ),
			)
		);
		$this->add_control(
			'button2_border_width',
			array(
				'label'     => __( 'Border Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-2' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'button2_border_radius',
			array(
				'label'     => __( 'Border Radius', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-2' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'after',
			)
		);
		$this->add_control(
			'button2_heading',
			array(
				'label'     => __( 'Button 2', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->start_controls_tabs( 'button2_tabs' );

		$this->start_controls_tab( 'normal2', array( 'label' => __( 'Normal', 'soledad' ) ) );

		$this->add_control(
			'button2_text_color',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .penci-slider_btnwrap .pencislider-btn-2' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button2_background_color',
			array(
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}}  .penci-slider_btnwrap .pencislider-btn-2' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button2_border_color',
			array(
				'label'     => __( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .penci-slider_btnwrap .pencislider-btn-2' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover2', array( 'label' => __( 'Hover', 'soledad' ) ) );

		$this->add_control(
			'button2_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .penci-slider_btnwrap .pencislider-btn-2:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button2_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body:not(.pcdm-enable) {{WRAPPER}}  .penci-slider_btnwrap .pencislider-btn-2:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button2_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .penci-slider_btnwrap .pencislider-btn-2:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_pagination', array(
				'label' => __( 'Pagination', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control( 'heading_pagi_style', array(
			'label'     => __( 'Dots Pagination', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array( 'style' => array( 'style-35', 'style-38' ) )
		) );

		// number style
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'      => 'dots_number_typo',
				'label'     => __( 'Typography for Number', 'soledad' ),
				'selector'  => '{{WRAPPER}} .penci-custom-slides .penci-owl-carousel.pcdots-number .penci-owl-dot span',
				'condition' => [ 'showdots_type' => 'number' ],
			)
		);
		$this->add_control( 'dots_number_color', array(
			'label'     => __( 'Number Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'showdots_type' => 'number' ],
			'selectors' => array( '{{WRAPPER}} .penci-custom-slides .penci-owl-carousel.pcdots-number .penci-owl-dot span' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_line_bgcolor', array(
			'label'     => __( 'Line Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'showdots_type' => 'number' ],
			'selectors' => array( '{{WRAPPER}} .penci-custom-slides .penci-owl-carousel.pcdots-number .penci-owl-dot span:after' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_number_acolor', array(
			'label'     => __( 'Number Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'showdots_type' => 'number' ],
			'selectors' => array( '{{WRAPPER}} .penci-custom-slides .penci-owl-carousel.pcdots-number .penci-owl-dot.active span' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_line_bgacolor', array(
			'label'     => __( 'Line Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'showdots_type' => 'number' ],
			'selectors' => array( '{{WRAPPER}} .penci-custom-slides .penci-owl-carousel.pcdots-number .penci-owl-dot.active span:after' => 'background-color: {{VALUE}};' ),
		) );

		// dots style

		$this->add_control( 'dots_bg_color', array(
			'label'     => __( 'Dot Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'showdots_type' => 'dots' ],
			'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-custom-slides .penci-owl-carousel .penci-owl-dot span,body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_bd_color', array(
			'label'     => __( 'Dot Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'showdots_type' => 'dots' ],
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'border-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_bga_color', array(
			'label'     => __( 'Dot Borders Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'showdots_type' => 'dots' ],
			'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span,body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_bda_color', array(
			'label'     => __( 'Dot Borders Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'showdots_type' => 'dots' ],
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span' => 'border-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_cs_w', array(
			'label'     => __( 'Dot Width', 'soledad' ),
			'condition' => [ 'showdots_type' => 'dots' ],
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 5, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'width: {{SIZE}}px;height: {{SIZE}}px;' ),
		) );

		$this->add_control( 'dots_csbd_w', array(
			'label'     => __( 'Dot Borders Width', 'soledad' ),
			'condition' => [ 'showdots_type' => 'dots' ],
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'border-width: {{SIZE}}px;' ),
		) );

		$this->add_control( 'dots_csspc_w', array(
			'label'     => __( 'Dot Spacing', 'soledad' ),
			'condition' => [ 'showdots_type' => 'dots' ],
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-custom-slides .penci-owl-carousel .penci-owl-dot,{{WRAPPER}} .penci-owl-carousel .penci-owl-dot' => 'margin-left: {{SIZE}}px;margin-right: {{SIZE}}px;' ),
		) );

		$this->add_control( 'heading_prenex_style', array(
			'label'     => __( 'Previous/Next Buttons', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'dots_nxpv_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_hcolor', array(
			'label'     => __( 'Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev:hover, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next:hover' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_hbgcolor', array(
			'label'     => __( 'Hover Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev:hover, body:not(.pcdm-enable) {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next:hover' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_sizes', array(
			'label'     => __( 'Button Size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next' => 'width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px;margin-top:0;transform:translateY(-50%);' ),
		) );

		$this->add_control( 'dots_nxpv_isizes', array(
			'label'     => __( 'Icon Size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev i, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next i' => 'font-size: {{SIZE}}px;' ),
		) );

		$this->add_control( 'dots_nxpv_bdradius', array(
			'label'     => __( 'Button Border Radius', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->add_responsive_control( 'dots_nxpv_margin', array(
			'label'     => __( 'Dots Spacing Bottom', 'soledad' ),
			'condition' => [ 'showdots' => 'yes' ],
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dots' => 'bottom: {{SIZE}}px;' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {

		$settings              = $this->get_settings();
		$settings['come_from'] = 'er';
		$twolines_class        = '';
		if ( 'yes' == $settings['btn_2lines'] ) {
			$twolines_class = ' btn-twoline';
		}

		$dots_style     = $settings['showdots_type'] ? ' pcdots-' . $settings['showdots_type'] : '';
		$pcenable_style = $settings['use_parallax'] ? ' penci-jarallax' : '';
		$fullscreen     = isset( $settings['fullscreen'] ) && $settings['fullscreen'] ? ' enable-fullscreen' : '';

		$data_slider = penci_get_data_slider( $settings );

		echo '<div class="penci-block-vc penci-custom-slides' . $fullscreen . '">';
		echo '<div class="penci-block_content penci-slides-wrap swiper penci-owl-carousel penci-owl-carousel-slider' . $dots_style . '" ' . $data_slider . '>';
		echo '<div class="swiper-wrapper">';

		$slide_count = 0;

		foreach ( (array) $settings['penci_slides'] as $slide ) {

			$heading_overlay     = isset( $settings['heading_overlay'] ) && $settings['heading_overlay'] ? 'yes' : '';
			$description_overlay = isset( $settings['description_overlay'] ) && $settings['description_overlay'] ? 'yes' : '';

			$add_url_feat_img = isset( $slide['add_url_feat_img'] ) && $slide['add_url_feat_img'] ? $slide['add_url_feat_img'] : '';
			$url_feat_img     = isset( $slide['url_feat_img'] ) && $slide['url_feat_img'] ? $slide['url_feat_img'] : '';

			if ( isset( $slide['bg_item_overlay'] ) && $slide['bg_item_overlay'] ) {
				if ( 'yes' == $slide['bg_item_overlay'] ) {
					$heading_overlay = $description_overlay = 'yes';
				} elseif ( 'no' == $slide['bg_item_overlay'] ) {
					$heading_overlay = $description_overlay = '';
				}
			}

			echo '<div class="swiper-slide elementor-repeater-item-' . $slide['_id'] . ' penci-ctslide-wrap">';
			echo '<div class="penci-custom-slide">';

			$ken_class = '';
			if ( '' != $slide['background_ken_burns'] ) {
				$ken_class = ' penci-ctslider-ken-' . $slide['zoom_direction'];
			}

			$video_content = '';

			if ( 'video' == $slide['background_type'] && isset( $slide['background_video'] ) && $slide['background_video'] ) {
				$video_content = ' data-jarallax data-video-src="' . esc_url( $slide['background_video'] ) . '"';
			}

			echo '<div class="penci-ctslide-bg' . $ken_class . $pcenable_style . '"' . $video_content . '></div>';

			echo '<div class="penci-ctslide-inner' . $twolines_class . '">';

			// Add link to image
			$url_feat_img_markup = '';
			if ( 'yes' === $add_url_feat_img && $url_feat_img ) {

				if ( ! empty( $url_feat_img['url'] ) ) {
					$this->add_render_attribute( 'url_feat_img' . $slide_count, 'href', $url_feat_img['url'] );
					if ( $url_feat_img['is_external'] ) {
						$this->add_render_attribute( 'url_feat_img' . $slide_count, 'target', '_blank' );
					}

					$url_feat_img_markup = '<a class="penci-ctslider-featimg" ' . $this->get_render_attribute_string( 'url_feat_img' . $slide_count ) . '></a>';
				}

				echo $url_feat_img_markup;
			}


			if ( 'yes' === $slide['background_overlay'] ) {
				echo '<div class="penci-ctslider-bg-overlay"></div>';
			}

			echo '<div class="penci-ctslider-content penci-' . esc_attr( $settings['content_animation'] ) . '">';

			if ( isset( $slide['heading'] ) && $slide['heading'] ) {

				if ( $heading_overlay ) {
					echo '<h2 class="pencislider-title pencislider-title-overlay"><span class="pslider-bgoverlay-inner"><span>' . $slide['heading'] . '</span></span></h2>';
				} else {
					echo '<h2 class="pencislider-title">' . $slide['heading'] . '</h2>';
				}

			}

			if ( isset( $slide['description'] ) && $slide['description'] ) {
				if ( $description_overlay ) {
					echo '<div class="pencislider-caption pencislider-caption-overlay"><span class="pslider-bgoverlay-inner"><span>' . $slide['description'] . '</span></span></div>';
				} else {
					echo '<div class="pencislider-caption">' . $slide['description'] . '</div>';
				}

			}

			$html_button = '';
			if ( isset( $slide['button_text'] ) && $slide['button_text'] ) {
				$button_link_data = 'href="#" aria-label="Button"';
				if ( ! empty( $slide['button_link']['url'] ) ) {
					$this->add_render_attribute( 'button_link' . $slide_count, 'href', $slide['button_link']['url'] );
					if ( $slide['button_link']['is_external'] ) {
						$this->add_render_attribute( 'button_link' . $slide_count, 'target', '_blank' );
					}
					$button_link_data = $this->get_render_attribute_string( 'button_link' . $slide_count );
				}

				$html_button .= '<a class="pencislider-btn pencislider-btn-1 penci-button" ' . $button_link_data . '><span>' . $slide['button_text'] . '</span></a>';
			}
			if ( isset( $slide['button_text2'] ) && $slide['button_text2'] ) {
				$button_link_data = 'href="#" aria-label="Button"';
				if ( ! empty( $slide['button_link2']['url'] ) ) {
					$this->add_render_attribute( 'button_link2' . $slide_count, 'href', $slide['button_link2']['url'] );
					if ( $slide['button_link2']['is_external'] ) {
						$this->add_render_attribute( 'button_link2' . $slide_count, 'target', '_blank' );
					}
					$button_link_data = $this->get_render_attribute_string( 'button_link2' . $slide_count );
				}

				$html_button .= '<a class="pencislider-btn pencislider-btn-2 penci-button" ' . $button_link_data . '><span>' . $slide['button_text2'] . '</span></a>';
			}

			if ( $html_button ) {
				echo '<div class="penci-slider_btnwrap">' . $html_button . '</div>';
			}

			echo '</div>'; // slider content


			echo '</div>'; // penci-ctslide-inner
			echo '</div>'; // penci-custom-slide
			echo '</div>'; // penci-ctslide-wrap

			$slide_count ++;
		}

		echo '</div></div></div>';
	}
}
