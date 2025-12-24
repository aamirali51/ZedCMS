<?php

namespace PenciSoledadElementor\Modules\PenciFeaturedSliders\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciFeaturedSliders extends Base_Widget {

	public function get_name() {
		return 'penci-featured-sliders';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Featured Slider', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'post', 'slider' );
	}

	public function get_script_depends() {
		return array( 'ff40', 'gsap' );
	}

	protected function register_controls() {


		$this->register_query_section_controls( true );
		// Section layout
		$this->start_controls_section( 'section_layout', array(
			'label' => esc_html__( 'Layout', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'style', array(
			'label'   => __( 'Choose Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'style-1',
			'options' => array(
				'style-1'  => 'Style 1',
				'style-2'  => 'Style 2',
				'style-4'  => 'Style 4',
				'style-6'  => 'Style 6',
				'style-7'  => 'Style 7',
				'style-8'  => 'Style 8',
				'style-9'  => 'Style 9',
				'style-10' => 'Style 10',
				'style-11' => 'Style 11',
				'style-13' => 'Style 13',
				'style-15' => 'Style 15',
				'style-17' => 'Style 17',
				'style-19' => 'Style 19',
				'style-20' => 'Style 20',
				'style-21' => 'Style 21',
				'style-22' => 'Style 22',
				'style-23' => 'Style 23',
				'style-24' => 'Style 24',
				'style-25' => 'Style 25',
				'style-26' => 'Style 26',
				'style-27' => 'Style 27',
				'style-28' => 'Style 28',
				'style-29' => 'Style 29',
				'style-35' => 'Style 35',
				'style-37' => 'Style 37',
				'style-38' => 'Style 38',
				'style-40' => 'Style 39',
				'style-41' => 'Style 40',
				'style-42' => 'Style 41',
				'style-44' => 'Style 42',
				'style-45' => 'Style 43',
			)
		) );


		$list_switchers = array(
			'disable_lazyload_slider' => array(
				'label' => __( 'Disable Lazy Load Images on The Slider', 'soledad' ),
			),
			'enable_flat_overlay'     => array(
				'label'     => __( 'Enable Flat Overlay Replace with Gradient Overlay', 'soledad' ),
				'condition' => array(
					'style!' => array(
						'style-1',
						'style-2',
						'style-3',
						'style-4',
						'style-5',
						'style-29',
						'style-30',
						'style-35',
						'style-36',
						'style-37',
						'style-38'
					)
				),
			),
			'center_box'              => array(
				'label' => __( 'Hide Center Box', 'soledad' ),
			),
			'meta_date_hide'          => array(
				'label' => __( 'Hide Post Date', 'soledad' ),
			),
			'show_viewscount'         => array(
				'label' => __( 'Show Count View', 'soledad' ),
			),
			'hide_categories'         => array(
				'label' => __( 'Hide Categories Of Post', 'soledad' ),
			),
			'show_cat'                => array(
				'label' => __( 'Display Categories for all Posts on Slider', 'soledad' ),
				'desc'  => __( 'By default, we disabled categories for some slider styles & some small posts - this option will help you show it if you want.', 'soledad' ),
			),
			'hide_meta_comment'       => array(
				'label' => __( 'Hide Post Number Comments', 'soledad' ),
			),
			'show_meta_author'        => array(
				'label' => __( 'Show Post Author', 'soledad' ),
			),
			'hide_meta_excerpt'       => array(
				'label'     => __( 'Hide Post Excerpt', 'soledad' ),
				'condition' => array(
					'style' => array(
						'style-35',
						'style-38',
						'style-44',
						'style-42',
						'style-41',
						'style-40'
					)
				),
			),
			'hide_format_icons'       => array(
				'label' => __( 'Hide Post Format Icons', 'soledad' ),
			)
		);

		foreach ( $list_switchers as $list_switcher_key => $list_switcher_info ) {
			$this->add_control( $list_switcher_key, array(
				'label'       => $list_switcher_info['label'],
				'type'        => Controls_Manager::SWITCHER,
				'description' => isset( $list_switcher_info['desc'] ) ? $list_switcher_info['desc'] : '',
				'condition'   => isset( $list_switcher_info['condition'] ) ? $list_switcher_info['condition'] : '',
			) );
		}
		$this->add_control( 'title_length', array(
			'label'     => __( 'Post Title Length', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => '12',
			'separator' => 'before',
		) );
		$this->add_control( 'spacing_settings_section', array(
			'label'     => __( 'Content Align', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'cs_vertical_align', array(
			'label'       => __( 'Content Vertical Align', 'soledad' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'options'     => array(
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
		) );
		$this->add_control( 'cs_horizontal_align', array(
			'label'       => __( 'Horizontal Align', 'soledad' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'options'     => array(
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
			'condition'   => [ 'style' => [ 'style-1', 'style-2' ] ],
		) );
		$this->add_control( 'cs_text_align', array(
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
		) );
		$this->add_responsive_control( 'cs_container_w', array(
			'label'     => __( 'Center Box Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 1500, ) ),
			'selectors' => [ '{{WRAPPER}} .featured-style-1 .penci-featured-content .feat-text,{{WRAPPER}} .featured-style-2 .penci-featured-content .feat-text' => 'max-width:{{SIZE}}px' ],
			'condition' => [ 'style' => [ 'style-1', 'style-2' ] ]
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'spacingitems_settings_section', array(
			'label' => __( 'Content Spacing', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT
		) );
		$this->add_responsive_control( 'csct_spacing', array(
			'label'     => __( 'Content Padding', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-slide-overlay .penci-mag-featured-content,{{WRAPPER}} .penci-featured-content .feat-text,{{WRAPPER}} .feat-text-right' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'csct_cmargin', array(
			'label'              => __( 'Content Margin', 'soledad' ),
			'type'               => Controls_Manager::DIMENSIONS,
			'allowed_dimensions' => 'vertical',
			'placeholder'        => [
				'top'      => '',
				'right'    => 'auto',
				'bottom'   => '',
				'left'     => 'auto',
				'isLinked' => false,
			],
			'range'              => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'          => array( '{{WRAPPER}} .penci-featured-content-right .feat-text-right,{{WRAPPER}} .penci-slide-overlay .penci-mag-featured-content,{{WRAPPER}} .penci-featured-content' => 'margin-top: {{TOP}}{{UNIT}};margin-bottom: {{BOTTOM}}{{UNIT}};' ),
		) );
		$big_post_lists = array(
			'style-6',
			'style-13',
			'style-15',
			'style-17',
			'style-20',
			'style-21',
			'style-22',
			'style-23',
			'style-24',
			'style-25',
			'style-26',
			'style-27',
			'style-28',
			'style-37'
		);
		$this->add_responsive_control( 'csct_bspacing', array(
			'label'     => __( 'Content Padding for Big Posts', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'condition' => array( 'style' => $big_post_lists ),
			'selectors' => array( '{{WRAPPER}} .penci-pitem-big .penci-featured-content .feat-text,{{WRAPPER}} .penci-pitem-big .penci-mag-featured-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'cscat_spacing', array(
			'label'     => __( 'Categories', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-mag-featured-content .cat,{{WRAPPER}} .penci-featured-content .feat-text .featured-cat,{{WRAPPER}} .featured-cat' => 'margin-bottom: {{SIZE}}px' ),
		) );
		$this->add_responsive_control( 'cstitle_spacing', array(
			'label'     => __( 'Title', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-featured-content .feat-text h3,{{WRAPPER}} .penci-owl-featured-area .feat-text h3,{{WRAPPER}} .penci-mag-featured-content h3,{{WRAPPER}} .featured-style-35 .feat-text-right h3,{{WRAPPER}} .penci-widget-slider h4, {{WRAPPER}} h3.title-part' => 'margin-bottom: {{SIZE}}px' ),
		) );
		$this->add_responsive_control( 'csmeta_spacing', array(
			'label'     => __( 'Meta', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-mag-featured-content .feat-meta,{{WRAPPER}} .penci-featured-content .feat-text .feat-meta,{{WRAPPER}} .penci-fslider-fmeta' => 'margin-top: {{SIZE}}px' ),
		) );
		$this->add_control( 'excerpt_spacing', array(
			'label'     => __( 'Excerpt Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'max' => 100 ) ),
			'selectors' => array(
				'{{WRAPPER}} .featured-content-excerpt p,{{WRAPPER}} .featured-slider-excerpt p' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .featured-slider-excerpt'                                           => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
			'condition' => array(
				'style' => array(
					'style-35',
					'style-38',
					'style-40',
					'style-44',
					'style-42',
					'style-41'
				)
			)
		) );
		$this->add_responsive_control( 'csbtn_spacing', array(
			'label'     => __( 'Button', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-featured-slider-button' => 'margin-top: {{SIZE}}px' ),
			'condition' => array( 'style' => array( 'style-35', 'style-38', 'style-40' ) )
		) );
		$this->add_control( 'cspd_bigpost', array(
			'label'     => __( 'Spacing for Big Posts', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array( 'style' => $big_post_lists ),
		) );
		$this->add_responsive_control( 'cscat_bspacing', array(
			'label'     => __( 'Categories', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-pitem-big .penci-mag-featured-content .cat' => 'margin-bottom: {{SIZE}}px' ),
			'condition' => array( 'style' => $big_post_lists ),
		) );
		$this->add_responsive_control( 'cstitle_bspacing', array(
			'label'     => __( 'Title', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'condition' => array( 'style' => $big_post_lists ),
			'selectors' => array( '{{WRAPPER}} .penci-pitem-big .penci-featured-content .feat-text h3,{{WRAPPER}} .penci-pitem-big .penci-mag-featured-content h3' => 'margin-bottom: {{SIZE}}px' ),
		) );
		$this->add_responsive_control( 'csmeta_bspacing', array(
			'label'     => __( 'Meta', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'condition' => array( 'style' => $big_post_lists ),
			'selectors' => array( '{{WRAPPER}} .penci-pitem-big .penci-mag-featured-content .feat-meta' => 'margin-top: {{SIZE}}px' ),
		) );
		$this->add_control( 'excerpt_bspacing', array(
			'label'     => __( 'Excerpt Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'max' => 100 ) ),
			'selectors' => array( '{{WRAPPER}} .penci-pitem-big .featured-content-excerpt p' => 'margin-bottom: {{SIZE}}{{UNIT}};' ),
			'condition' => array( 'style' => array( 'style-35', 'style-38' ) )
		) );
		$this->add_responsive_control( 'csprvnxt_bspacing', array(
			'label'     => __( 'Slider Prev/Next Buttons Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'separator' => 'before',
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev' => 'left: {{SIZE}}px;',
				'{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next' => 'right: {{SIZE}}px;',
			),
		) );
		$this->add_control( 'dots_csspc_w', array(
			'label'     => __( 'Slider Dots Spacing', 'soledad' ),
			'separator' => 'before',
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'condition' => [ 'showdots!' => '' ],
			'selectors' => array( '{{WRAPPER}} .penci-custom-slides .penci-owl-carousel .penci-owl-dot,{{WRAPPER}} .penci-owl-carousel .penci-owl-dot' => 'margin-left: {{SIZE}}px;margin-right: {{SIZE}}px;' ),
		) );

		$this->end_controls_section();

		// Options slider
		$this->start_controls_section( 'section_slider_options', array(
			'label' => __( 'Slider Options', 'soledad' ),
		) );
		$this->add_control( 'carousel_slider_effect', array(
			'label'       => __( 'Carousel Effect', 'soledad' ),
			'type'        => Controls_Manager::SELECT,
			'description' => __( 'The "Swing" effect does not support the loop option.', 'soledad' ),
			'default'     => get_theme_mod( 'penci_fslider_carousel_slider_effect', 'swing' ),
			'options'     => array(
				'default' => 'Default ( Slide )',
				'swing'   => 'Swing',
			),
			'condition'   => array( 'style' => array( 'style-7', 'style-8', 'style-9', 'style-10', 'style-38' ) )
		) );
		$this->add_control( 'single_slider_effect', array(
			'label'       => __( 'Slider Effect', 'soledad' ),
			'description' => __( 'Some sliders do not support all effects listed below. The Creative effect is functioning correctly on styles 1, 3, 29, 30, 35, and 36. Style 39 only supports the "Fade" effect.', 'soledad' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => get_theme_mod( 'penci_fslider_single_slider_effect', 'creative' ),
			'options'     => array(
				'slide'     => 'Slide',
				'fade'      => 'Fade',
				'coverflow' => 'Coverflow',
				'flip'      => 'Flip',
				'cards'     => 'Cards',
				'creative'  => 'Creative',
			),
			'condition'   => array(
				'style!' => array(
					'style-7',
					'style-8',
					'style-9',
					'style-10',
					'style-38',
					'style-41',
					'style-42',
					'style-44'
				)
			)
		) );
		$this->add_control( 'autoplay', array(
			'label'   => __( 'Autoplay', 'soledad' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );
		$this->add_control( 'loop', array(
			'label'     => __( 'Slider Loop', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'condition' => [ 'carousel_slider_effect' => 'default' ],
		) );
		$this->add_control( 'auto_time', array(
			'label'   => __( 'Slider Auto Time (at x seconds)', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 4000,
		) );
		$this->add_control( 'speed', array(
			'label'       => __( 'Slider Speed (at x seconds)', 'soledad' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => 600,
			'render_type' => 'template',
			'selectors'   => [ '{{WRAPPER}} .penci-owl-carousel' => '--pcfs-delay:calc({{VALUE}}s / 1000 - 0.1s)' ]
		) );
		$this->add_control( 'shownav', array(
			'label'   => __( 'Show Next/Prev Buttons', 'soledad' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
			//'condition' => array( 'style' => array( 'style-35', 'style-37' ) ),
		) );
		$this->add_control( 'showdots', array(
			'label' => __( 'Show Dots Navigation', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
			//'condition' => array( 'style' => array( 'style-35', 'style-37' ) ),
		) );
		$this->add_control( 'slideTo', array(
			'label' => __( 'Start the slider from the slide Number', 'soledad' ),
			'type'  => Controls_Manager::NUMBER,
			//'condition' => array( 'style' => array( 'style-35', 'style-37' ) ),
		) );
		$this->add_control( 'slidespg', array(
			'label' => __( 'Slides Per Group', 'soledad' ),
			'type'  => Controls_Manager::NUMBER,
			//'condition' => array( 'style' => array( 'style-35', 'style-37' ) ),
		) );
		$this->end_controls_section();

		$style_big_post = array(
			'style-6',
			'style-13',
			'style-15',
			'style-17',
			'style-18',
			'style-19',
			'style-20',
			'style-21',
			'style-22',
			'style-23',
			'style-24',
			'style-25',
			'style-26',
			'style-27',
			'style-28',
			'style-37'
		);
		// Design
		$this->start_controls_section( 'section_design_image', array(
			'label' => __( 'Image', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE
		) );

		$this->add_control( 'post_thumb_size', array(
			'label'   => __( 'Image size', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => $this->get_list_image_sizes( true ),
		) );
		$this->add_control( 'bpost_thumb_size', array(
			'label'     => __( 'Image size for Big Post', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => $this->get_list_image_sizes( true ),
			'condition' => array( 'style' => $style_big_post ),
		) );
		$this->add_control( 'post_thumb_size_mobile', array(
			'label'   => __( 'Image size for Mobile', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => $this->get_list_image_sizes( true ),
		) );

		$this->add_control( 'img_border_radius', array(
			'label'       => __( 'Border Radius', 'elementor' ),
			'description' => 'You can use pixel or percent. E.g:  <strong>10px</strong>  or  <strong>10%</strong>',
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => array( '%', 'px' ),
			'default'     => array( 'unit' => '%', 'size' => 0 ),
			'range'       => array( '%' => array( 'max' => 50 ), 'px' => array( 'min' => 0, 'max' => 200 ) ),
			'selectors'   => array(
				'{{WRAPPER}} .featured-area.featured-style-42 .item-inner-content, {{WRAPPER}} .featured-style-41 .swiper-slide, {{WRAPPER}} .slider-40-wrapper .nav-thumb-creative .thumb-container:after, {{WRAPPER}} .penci-slider44-t-item:before,.penci-slider44-main-wrapper .item' => 'border-radius: {{SIZE}}{{UNIT}};-webkit-border-radius: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .featured-area .penci-image-holder,{{WRAPPER}} .featured-area .penci-slider4-overlay'                                                                                                                                                                        => 'border-radius: {{SIZE}}{{UNIT}};-webkit-border-radius: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .featured-area .penci-slide-overlay .overlay-link'                                                                                                                                                                                                           => 'border-radius: {{SIZE}}{{UNIT}};-webkit-border-radius: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .featured-style-29 .featured-slider-overlay,{{WRAPPER}} .penci-slider38-overlay'                                                                                                                                                                             => 'border-radius: {{SIZE}}{{UNIT}};-webkit-border-radius: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .penci-featured-content-right:before'                                                                                                                                                                                                                        => 'border-top-right-radius: {{SIZE}}{{UNIT}};border-bottom-right-radius: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .penci-flat-overlay .penci-slide-overlay .penci-mag-featured-content:before'                                                                                                                                                                                 => 'border-bottom-left-radius: {{SIZE}}{{UNIT}};border-bottom-right-radius: {{SIZE}}{{UNIT}};',
			)
		) );
		$this->add_responsive_control( 'img_ratio', array(
			'label'       => __( 'Ratio Height/Width of Images', 'soledad' ),
			'description' => 'This option does not apply for <strong>Slider Styles 19 & 27</strong>',
			'type'        => Controls_Manager::SLIDER,
			'range'       => array( 'px' => array( 'min' => 0.1, 'max' => 2, 'step' => 0.01 ) ),
			'selectors'   => array(
				'{{WRAPPER}} .penci-owl-carousel:not(.elsl-style-19):not(.elsl-style-27):not(.penci-slider44-main) .penci-image-holder'        => 'height: auto !important;',
				'{{WRAPPER}} .penci-owl-carousel:not(.elsl-style-19):not(.elsl-style-27):not(.penci-slider44-main) .penci-image-holder:before' => 'content:"";padding-top:calc( {{SIZE}} * 100% );height: auto;',
				'{{WRAPPER}} .featured-style-13 .penci-owl-carousel .penci-item-1 .penci-image-holder:before'        => 'padding-top:calc( {{SIZE}} * 50% );',
				'{{WRAPPER}} .featured-style-15 .penci-owl-carousel .penci-item-2 .penci-image-holder:before'        => 'padding-top:calc( {{SIZE}} * 50% );',
				'{{WRAPPER}} .featured-style-25 .penci-owl-carousel .penci-item-1 .penci-image-holder:before'        => 'padding-top:calc( {{SIZE}} * 150% );',
				'{{WRAPPER}} .penci-slick-carousel .penci-image-holder:before'                                       => 'padding-top:calc( {{SIZE}} * 150% );',
				'{{WRAPPER}} .featured-style-43 .img-container'                                                      => 'padding-top:calc( {{SIZE}} * 150% );',
			),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_design_content', array(
			'label' => __( 'Content', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE
		) );

		$this->add_control( 'heading_title_style', array(
			'label' => __( 'Title', 'soledad' ),
			'type'  => Controls_Manager::HEADING
		) );

		$this->add_control( 'title_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .feat-text h3, {{WRAPPER}} .feat-text h3 a'             => 'color: {{VALUE}};',
				'{{WRAPPER}} .feat-text-right h3, {{WRAPPER}} .feat-text-right h3 a' => 'color: {{VALUE}};',
				'{{WRAPPER}} h3.title-part'                                          => 'color: {{VALUE}};',
			)
		) );
		$this->add_control( 'title_hcolor', array(
			'label'     => __( 'Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .feat-text h3 a:hover'       => 'color: {{VALUE}};',
				'{{WRAPPER}} .feat-text-right h3 a:hover' => 'color: {{VALUE}};',
				'{{WRAPPER}} h3.title-part a:hover'       => 'color: {{VALUE}};',
			)
		) );

		$this->add_control( 'title_hcolor_effect', array(
			'label'     => __( 'Enable Hover Border Color', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'selectors' => array(
				'{{WRAPPER}} .feat-text h3 a,{{WRAPPER}} .feat-text-right h3 a,{{WRAPPER}} h3.title-part' 		 	   => 'background-image: linear-gradient(var(--pcaccent-cl) 0%, var(--pcaccent-cl) 98%);background-size: 0 1px;background-repeat: no-repeat;background-position: left 100%;word-break: break-word;transition: 0.3s all ease-in-out;',
				'{{WRAPPER}} .feat-text h3 a:hover,{{WRAPPER}} .feat-text-right h3 a:hover,{{WRAPPER}} h3.title-part'  => 'background-size: 100% 1px;',
			),
		) );
		
		$this->add_control( 'title_hcolor_effect_color', array(
			'label'     => __( 'Hover Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .feat-text h3 a,{{WRAPPER}} .feat-text-right h3 a,{{WRAPPER}} h3.title-part' => 'background-image: linear-gradient({{VALUE}} 0%, {{VALUE}} 98%)' ),
			'condition' => array( 'title_hcolor_effect' => 'yes' ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'title_typography',
			'selector' => '{{WRAPPER}} .feat-text h3, {{WRAPPER}} .feat-text h3 a,{{WRAPPER}} .feat-text-right h3, {{WRAPPER}} .feat-text-right h3 a, {{WRAPPER}} h3.title-part'
		) );
		$this->add_responsive_control( 'bptitle_size', array(
			'label'     => __( 'Font size for Big Post', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .featured-area .penci-pitem-big .feat-text h3,{{WRAPPER}} .featured-area .penci-pitem-big .feat-text h3 a' => 'font-size: {{SIZE}}px'
			),
			'condition' => array( 'style' => $style_big_post ),
		) );

		$this->add_control( 'heading_pcat_style', array(
			'label' => __( 'Category', 'soledad' ),
			'type'  => Controls_Manager::HEADING
		) );

		$this->add_control( 'pcat_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .feat-text .featured-cat a,{{WRAPPER}} .featured-style-35 .featured-cat a,{{WRAPPER}} .featured-cat a' => 'color: {{VALUE}};' )
		) );
		$this->add_control( 'pcat_hcolor', array(
			'label'     => __( 'Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .feat-text .featured-cat a:hover,{{WRAPPER}} .featured-style-35 .featured-cat a:hover, {{WRAPPER}} .featured-cat a:hover' => 'color: {{VALUE}};' )
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'pcat_typo',
			'selector' => '{{WRAPPER}} .feat-text .featured-cat a,{{WRAPPER}} {{WRAPPER}} .featured-style-35 .featured-cat a, {{WRAPPER}} .featured-cat a'
		) );

		$this->add_control( 'heading_meta_style', array(
			'label'     => __( 'Meta', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before'
		) );

		$this->add_control( 'meta_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .feat-text .feat-meta span,{{WRAPPER}} .feat-text .feat-meta a'                                                                                                         => 'color: {{VALUE}};',
				'{{WRAPPER}} .featured-content-excerpt .feat-meta span,{{WRAPPER}} .featured-content-excerpt .feat-meta span a,{{WRAPPER}} .penci-fslider-fmeta, {{WRAPPER}} .penci-fslider-fmeta a' => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-fslider-fmeta, {{WRAPPER}} .penci-featured-content .feat-text .feat-meta span, {{WRAPPER}} .penci-featured-content .feat-text .feat-meta span a'                 => 'color: {{VALUE}};',
			)
		) );
		$this->add_control( 'meta_hcolor', array(
			'label'     => __( 'Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .feat-text .feat-meta a:hover,{{WRAPPER}} .penci-fslider-fmeta a:hover' => 'color: {{VALUE}};',
				'{{WRAPPER}} .featured-content-excerpt .feat-meta span a:hover'                      => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-featured-content .feat-text .feat-meta span a:hover'             => 'color: {{VALUE}};',
			)
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'meta_typography',
			'selector' => '{{WRAPPER}} .feat-text .feat-meta span,{{WRAPPER}} .feat-text .feat-meta a,{{WRAPPER}} .featured-content-excerpt .feat-meta span,{{WRAPPER}} .featured-content-excerpt .feat-meta span a, {{WRAPPER}} .penci-featured-content .feat-text .feat-meta span a'
		) );

		$this->add_control( 'heading_excerpt_style', array(
			'label'     => __( 'Excerpt', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array(
				'style' => array(
					'style-35',
					'style-38',
					'style-40',
					'style-44',
					'style-42',
					'style-41'
				)
			)
		) );

		$this->add_control( 'excerpt_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .featured-content-excerpt p,{{WRAPPER}} .featured-slider-excerpt p' => 'color: {{VALUE}};' ),
			'condition' => array(
				'style' => array(
					'style-35',
					'style-38',
					'style-40',
					'style-44',
					'style-42',
					'style-41'
				)
			)
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'excerpt_typography',
			'selector'  => '{{WRAPPER}} .featured-content-excerpt p,{{WRAPPER}} .featured-slider-excerpt p',
			'condition' => array(
				'style' => array(
					'style-35',
					'style-38',
					'style-40',
					'style-44',
					'style-42',
					'style-41'
				)
			)
		) );

		$this->add_control( 'heading_readmore_style', array(
			'label'     => __( 'Read More', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array(
				'style' => array(
					'style-35',
					'style-38',
					'style-40',
					'style-44',
					'style-42',
					'style-41'
				)
			)
		) );

		$this->add_control( 'read_more_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-featured-slider-button a' => 'color: {{VALUE}};border-color:{{VALUE}};' ),
			'condition' => array(
				'style' => array(
					'style-35',
					'style-38',
					'style-40',
					'style-44',
					'style-42',
					'style-41'
				)
			)
		) );

		$this->add_control( 'read_more_hcolor', array(
			'label'     => __( 'Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-featured-slider-button a:hover' => 'color: {{VALUE}};' ),
			'condition' => array(
				'style' => array(
					'style-35',
					'style-38',
					'style-40',
					'style-44',
					'style-42',
					'style-41'
				)
			)
		) );
		$this->add_control( 'read_more_hbgcolor', array(
			'label'     => __( 'Hover Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-featured-slider-button a:hover' => 'border-color: {{VALUE}};background-color: {{VALUE}};' ),
			'condition' => array(
				'style' => array(
					'style-35',
					'style-38',
					'style-40',
					'style-44',
					'style-42',
					'style-41'
				)
			)
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'read_more_typography',
			'selector'  => '{{WRAPPER}} .penci-featured-slider-button a',
			'condition' => array(
				'style' => array(
					'style-35',
					'style-38',
					'style-40',
					'style-44',
					'style-42',
					'style-41'
				)
			)
		) );

		/* Small Title */

		$this->add_control( 'heading_stitle_style', array(
			'label'     => __( 'Small Thumbnail', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array( 'style' => array( 'style-40' ) )
		) );

		$this->add_control( 'stitle_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .slider-40-wrapper .nav-thumb-creative .thumb-container:after' => 'background: {{VALUE}};',
			),
			'condition' => array( 'style' => array( 'style-40' ) )
		) );

		// Small Posts

		$this->add_control( 'smallpost_heading', array(
			'label'     => __( 'Thumb Posts', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array( 'style' => array( 'style-41', 'style-42', 'style-44' ) )
		) );

		$this->add_responsive_control( 'smallpost_thumbsize', array(
			'label'     => __( 'Thumbnail Size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => array( 'size' => '' ),
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-slider41-t-item .pcslider-41-img' => 'flex: 0 0 {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .penci-slider44-t-item .pcslider-44-img' => 'flex: 0 0 {{SIZE}}{{UNIT}}',
			),
			'condition' => array( 'style' => array( 'style-41', 'style-44' ) )
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'smallpost_typo',
			'selector'  => '{{WRAPPER}} .penci-slider41-t-item .pcslider-41-ct h3, {{WRAPPER}} .pcslider-42-ct h3, {{WRAPPER}} .penci-slider44-t-item h3',
			'condition' => array( 'style' => array( 'style-41', 'style-42', 'style-44' ) )
		) );

		$this->add_control( 'smallpost_color', array(
			'label'     => __( 'Title Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .penci-slider41-t-item .pcslider-41-ct h3' => 'color: {{VALUE}}',
				'{{WRAPPER}} .pcslider-42-ct h3'                        => 'color: {{VALUE}}',
				'{{WRAPPER}} .penci-slider44-t-item h3'                 => 'color: {{VALUE}}',
			),
			'condition' => array( 'style' => array( 'style-41', 'style-42' ) )
		) );

		$this->add_control( 'smallpost_acolor', array(
			'label'     => __( 'Title Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .swiper-slide-thumb-active .penci-slider41-t-item h3' => 'color: {{VALUE}}',
				'{{WRAPPER}} .swiper-slide-thumb-active .pcslider-42-ct h3'        => 'color: {{VALUE}}',
				'{{WRAPPER}} .swiper-slide-thumb-active .penci-slider44-t-item h3' => 'color: {{VALUE}}',
			),
			'condition' => array( 'style' => array( 'style-41', 'style-42', 'style-44' ) )
		) );

		$this->add_control( 'smallpost_bgcolor', array(
			'label'     => __( 'General Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .penci-slider41-thumb-wrapper' => 'background-color: {{VALUE}}',
			),
			'condition' => array( 'style' => array( 'style-41' ) )
		) );

		$this->add_control( 'smallpost_bdcolor', array(
			'label'     => __( 'General Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .penci-slider41-thumb-wrapper' => '--pcborder-cl: {{VALUE}}',
			),
			'condition' => array( 'style' => array( 'style-41' ) )
		) );

		$this->add_control( 'smallpost_ibgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .pcslider-42-ct'               => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .penci-slider44-t-item:before' => 'background-color: {{VALUE}}',
			),
			'condition' => array( 'style' => array( 'style-42', 'style-44' ) )
		) );

		$this->add_control( 'smallpost_ibgacolor', array(
			'label'     => __( 'Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .swiper-slide-thumb-active .pcslider-42-ct'               => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .swiper-slide-thumb-active .penci-slider44-t-item:before' => 'background-color: {{VALUE}}',
			),
			'condition' => array( 'style' => array( 'style-42', 'style-44' ) )
		) );

		$this->add_responsive_control( 'smallpost_ibpadding', array(
			'label'     => __( 'Custom Padding', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'selectors' => array(
				'{{WRAPPER}} .pcslider-42-ct'        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .penci-slider44-t-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition' => array( 'style' => array( 'style-42', 'style-44' ) )
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_design_overlay', array(
			'label' => __( 'Overlay Background', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE
		) );

		$this->add_control( 'overlay_bgcolor', array(
			'label'     => __( 'Overlay Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .featured-style-1 .penci-featured-content .featured-slider-overlay' => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .featured-style-2 .penci-featured-content .featured-slider-overlay' => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .penci-slider44-main-wrapper .penci-featured-content'               => 'background-color: {{VALUE}}',
			),
			'condition' => array( 'style' => array( 'style-1', 'style-2', 'style-44' ) ),
		) );

		$this->add_group_control( Group_Control_Background::get_type(), array(
			'name'        => 'ft_o_bgcolor',
			'label'       => __( 'Overlay Background Color', 'soledad' ),
			'types'       => array( 'classic', 'gradient' ),
			'selector'    => '{{WRAPPER}} .penci-featured-content .featured-slider-overlay,{{WRAPPER}} .penci-featured-content-right:before,{{WRAPPER}} .penci-slide-overlay .overlay-link, {{WRAPPER}} .slider-40-wrapper .list-slider-creative .item-slider-creative .img-container:before, {{WRAPPER}} .penci-slider44-main-wrapper .penci-featured-content',
			'label_block' => true,
			'separator'   => 'before'
		) );

		$this->add_control( 'ft_o_bgopacity', array(
			'label'     => __( 'Overlay Background Opacity', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( '%' => array( 'min' => 0, 'max' => 1, 'step' => 0.1 ) ),
			'condition' => array( 'style!' => 'style-44' ),
			'selectors' => array( '{{WRAPPER}} .penci-featured-content .featured-slider-overlay,{{WRAPPER}} .penci-featured-content-right:before,{{WRAPPER}} .penci-slide-overlay .overlay-link, {{WRAPPER}} .slider-44-item .thumbnail .dark-overlay, {{WRAPPER}} .slider-40-wrapper .list-slider-creative .item-slider-creative .img-container:before' => 'opacity: calc( {{SIZE}} / 100 )' ),
		) );

		$this->add_control( 'ft_o_bghopacity', array(
			'label'     => __( 'Hover Overlay Background Opacity', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( '%' => array( 'min' => 0, 'max' => 1, 'step' => 0.1 ) ),
			'condition' => array( 'style!' => 'style-44' ),
			'selectors' => array( '{{WRAPPER}} .penci-item-mag:hover .penci-slide-overlay .overlay-link, {{WRAPPER}} .featured-style-38 .item:hover .penci-slider38-overlay, {{WRAPPER}} .penci-flat-overlay .penci-item-mag:hover .penci-slide-overlay .penci-mag-featured-content:before, {{WRAPPER}} .slider-40-wrapper .list-slider-creative .item-slider-creative:hover .img-container:before' => 'opacity: calc( {{SIZE}} / 100 )' ),
		) );

		$this->add_responsive_control( 'ft_o_bdradius', array(
			'label'      => __( 'Border Radius', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-featured-content .featured-slider-overlay,{{WRAPPER}} .penci-featured-content-right:before,{{WRAPPER}} .penci-slide-overlay .overlay-link, {{WRAPPER}} .slider-40-wrapper .list-slider-creative .item-slider-creative .img-container:before, {{WRAPPER}} .penci-slider44-main-wrapper .penci-featured-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};-webkit-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array( 'style!' => 'style-44' )
		));

		$this->add_control( 'ft_o_border', array(
			'label'     => __( 'Border', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-featured-content .featured-slider-overlay,{{WRAPPER}} .penci-featured-content-right:before,{{WRAPPER}} .penci-slide-overlay .overlay-link, {{WRAPPER}} .slider-40-wrapper .list-slider-creative .item-slider-creative .img-container:before, {{WRAPPER}} .penci-slider44-main-wrapper .penci-featured-content' => 'border: 1px solid {{VALUE}};' ),
			'condition' => array( 'style!' => 'style-44' )
		));

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'ft_o_boxshadow',
				'selector'  => '{{WRAPPER}} .penci-featured-content .featured-slider-overlay,{{WRAPPER}} .penci-featured-content-right:before,{{WRAPPER}} .penci-slide-overlay .overlay-link, {{WRAPPER}} .slider-40-wrapper .list-slider-creative .item-slider-creative .img-container:before, {{WRAPPER}} .penci-slider44-main-wrapper .penci-featured-content',
				'condition' => array( 'style!' => 'style-44' )
			]
		);

		$this->end_controls_section();

		$this->start_controls_section( 'section_design_sliderdosnav', array(
			'label' => __( 'Slider Controls', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE
		) );

		$this->add_control( 'heading_pagi_style', array(
			'label'     => __( 'Dots Pagination', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'showdots!' => '' ]
		) );

		$this->add_control( 'dots_bg_color', array(
			'label'     => __( 'Dot Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-custom-slides .penci-owl-carousel .penci-owl-dot span,{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span,{{WRAPPER}} .swiper-container .progress' => 'background-color: {{VALUE}};' ),
			'condition' => [ 'showdots!' => '' ]
		) );

		$this->add_control( 'dots_bd_color', array(
			'label'     => __( 'Dot Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'border-color: {{VALUE}};' ),
			'condition' => [ 'showdots!' => '' ]
		) );

		$this->add_control( 'dots_bga_color', array(
			'label'     => __( 'Dot Borders Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'showdots!' => '' ],
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span,{{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_bda_color', array(
			'label'     => __( 'Dot Borders Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'showdots!' => '' ],
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span' => 'border-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_cs_w', array(
			'label'     => __( 'Dot Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 5, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'width: {{SIZE}}px;height: {{SIZE}}px;' ),
			'condition' => [ 'showdots!' => '' ],
		) );

		$this->add_control( 'dots_csbd_w', array(
			'label'     => __( 'Dot Borders Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'border-width: {{SIZE}}px;' ),
			'condition' => [ 'showdots!' => '' ],
		) );

		$this->add_control( 'heading_prenex_style', array(
			'label'     => __( 'Previous/Next Buttons', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'shownav!' => '' ],
		) );

		$this->add_control( 'dots_nxpv_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'shownav!' => '' ],
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next, {{WRAPPER}} .slider-40-wrapper .nav-slider-button' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_hcolor', array(
			'label'     => __( 'Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'shownav!' => '' ],
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev:hover, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next:hover, {{WRAPPER}} .slider-40-wrapper .nav-slider-button:hover' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'shownav!' => '' ],
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next, {{WRAPPER}} .slider-40-wrapper .nav-slider-button' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_hbgcolor', array(
			'label'     => __( 'Hover Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'shownav!' => '' ],
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev:hover, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next:hover, {{WRAPPER}} .slider-40-wrapper .nav-slider-button:hover' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_sizes', array(
			'label'     => __( 'Button Padding', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'condition' => [ 'shownav!' => '' ],
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next, {{WRAPPER}} .slider-40-wrapper .nav-slider-button' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: auto;height: auto;line-height: normal;margin-top:0;transform:translateY(-50%);' ),
		) );

		$this->add_control( 'dots_nxpv_isizes', array(
			'label'     => __( 'Icon Size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'condition' => [ 'shownav!' => '' ],
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev i, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next i, {{WRAPPER}} .slider-40-wrapper .nav-slider-button i' => 'font-size: {{SIZE}}px;' ),
		) );

		$this->add_control( 'dots_nxpv_bdradius', array(
			'label'     => __( 'Button Border Radius', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'condition' => [ 'shownav!' => '' ],
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next, {{WRAPPER}} .slider-40-wrapper .nav-slider-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->end_controls_section();

		$this->register_penci_bookmark_style_groups();

		$this->register_paywall_premium_heading_style_groups();

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

		$query_args = Module::get_query_args( 'posts', $settings );

		if ( in_array( $original_postype, [ 'current_query', 'related_posts' ] ) ) {
			$paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$ppp    = $settings['posts_per_page'] ? $settings['posts_per_page'] : get_option( 'posts_per_page' );
			$ppp    = isset( $settings['arposts_per_page'] ) && $settings['arposts_per_page'] ? $settings['arposts_per_page'] : $ppp;
			$offset = 0;
			if ( $ppp ) {
				$query_args['posts_per_page'] = $ppp;
			}

			if ( $settings['arposts_new'] == 'yes' ) {
				$query_args['paged'] = 1;
			}
			if ( 0 < $settings['offset'] ) {
				$offset = $settings['offset'];
			}

			if ( ! empty( $settings['offset'] ) && $paged > 1 ) {
				$offset = $settings['offset'] + ( ( $paged - 1 ) * $ppp );
			}

			if ( $offset ) {
				$query_args['offset'] = $offset;
			}
		}

		$feat_query = new \WP_Query( $query_args );

		if ( ! $feat_query->have_posts() ) {
			echo self::show_missing_settings( 'Featured Slider', penci_get_setting( 'penci_ajaxsearch_no_post' ) );
		}

		$slider_style = $settings['style'] ? $settings['style'] : 'style-1';

		$slider_class = $this->get_class_slider( $settings );

		$disable_lazyload    = $settings['disable_lazyload_slider'];
		$slider_title_length = $settings['title_length'] ? $settings['title_length'] : 12;
		$center_box          = $settings['center_box'];
		$meta_date_hide      = $settings['meta_date_hide'];
		$show_viewscount     = $settings['show_viewscount'];
		$hide_categories     = $settings['hide_categories'];
		$show_cat            = $settings['show_cat'];
		$hide_meta_comment   = $settings['hide_meta_comment'];
		$show_meta_author    = $settings['show_meta_author'];
		$hide_meta_excerpt   = $settings['hide_meta_excerpt'];
		$hide_format_icons   = $settings['hide_format_icons'];

		$post_thumb_size        = $settings['post_thumb_size'];
		$bpost_thumb_size       = $settings['bpost_thumb_size'];
		$post_thumb_size_mobile = $settings['post_thumb_size_mobile'];
		$cs_vertical_align      = $settings['cs_vertical_align'] && $settings['cs_vertical_align'] ? ' vertical-align-' . $settings['cs_vertical_align'] : '';
		$cs_horizontal_align    = $settings['cs_horizontal_align'] && $settings['cs_horizontal_align'] ? ' horizontal-align-' . $settings['cs_horizontal_align'] : '';
		$cs_text_align          = $settings['cs_text_align'] && $settings['cs_text_align'] ? ' text-align-' . $settings['cs_text_align'] : '';
		$wrapper_css            = isset( $settings['paywall_heading_text_style'] ) ? ' pencipw-hd-' . $settings['paywall_heading_text_style'] : '';

		$slider_lib = 'penci-owl-featured-area';

		$slider_lib .= ' elsl-' . $slider_style;

		$swiper = true;

		if ( $slider_style == 'style-40' ) {
			wp_enqueue_script( 'ff40' );
			wp_enqueue_script( 'gsap' );
			$slider_lib .= ' no-df-swiper';
			$swiper     = false;
		}

		if ( $slider_style == 'style-41' || $slider_style == 'style-42' || $slider_style == 'style-44' ) {
			$slider_lib .= ' no-df-swiper';
			$swiper     = false;
		}

		if ( $swiper ) {
			$slider_lib .= ' swiper penci-owl-carousel';
		}


		echo '<div class="penci-block-el featured-area featured-' . $slider_class . $cs_vertical_align . $cs_horizontal_align . $cs_text_align . $wrapper_css . '">';
		if ( $slider_style == 'style-37' ):
			echo '<div class="penci-featured-items-left">';
		endif;
		echo '<div class="' . $slider_lib . ' pcfg-slide-' . $this->get_id() . ' elsl-' . $slider_class . '"' . $this->get_slider_data( $settings ) . '>';
		if ( $swiper ) {
			if ( $settings['shownav'] ) {
				echo '<div class="penci-owl-nav"><div class="owl-prev"><i class="penciicon-left-chevron"></i></div><div class="owl-next"><i class="penciicon-right-chevron"></i></div></div>';
			}
			echo '<div class="swiper-wrapper">';
		}
		include dirname( __FILE__ ) . "/{$slider_style}.php";
		if ( $swiper && $slider_style != 'style-37' ) {
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
	}

	public static function show_missing_settings( $label, $mess ) {
		$output = '';
		if ( is_user_logged_in() ) {
			$output .= '<div class="penci-missing-settings">';
			$output .= '<span>' . $label . '</span>';
			$output .= $mess;
			$output .= '</div>';
		}

		return $output;
	}

	public function get_class_slider( $settings ) {
		$slider_style = $settings['style'] ? $settings['style'] : 'style-1';

		$slider_class = $slider_style;
		if ( $slider_style == 'style-5' ) {
			$slider_class = 'style-4 style-5';
		} elseif ( $slider_style == 'style-30' ) {
			$slider_class = 'style-29 style-30';
		} elseif ( $slider_style == 'style-36' ) {
			$slider_class = 'style-35 style-36';
		}

		if ( $settings['enable_flat_overlay'] && in_array( $slider_style, array(
				'style-6',
				'style-7',
				'style-8',
				'style-9',
				'style-10',
				'style-11',
				'style-12',
				'style-13',
				'style-14',
				'style-15',
				'style-16',
				'style-17',
				'style-18',
				'style-19',
				'style-20',
				'style-21',
				'style-22',
				'style-23',
				'style-24',
				'style-25',
				'style-26',
				'style-27',
				'style-28'
			) ) ) {
			$slider_class .= ' penci-flat-overlay';
		}

		return $slider_class;
	}

	public function get_slider_data( $settings ) {
		$slider_style = $settings['style'] ? $settings['style'] : 'style-1';

		$output = '';

		if ( $slider_style == 'style-7' || $slider_style == 'style-8' ) {
			$output .= ' data-item="4" data-desktop="4" data-tablet="2" data-tabsmall="1"';
		} elseif ( $slider_style == 'style-9' || $slider_style == 'style-10' ) {
			$output .= ' data-item="3" data-desktop="3" data-tablet="2" data-tabsmall="1"';
		} elseif ( $slider_style == 'style-11' || $slider_style == 'style-12' ) {
			$output .= ' data-item="2" data-desktop="2" data-tablet="2" data-tabsmall="1"';
		}

		$data_next_prev = 'yes' == $settings['shownav'] ? 'true' : 'false';
		$data_dots      = 'yes' == $settings['showdots'] ? 'true' : 'false';
		$output         .= ' data-dots="' . $data_dots . '" data-nav="' . $data_next_prev . '"';

		$output .= ' data-ceffect="' . $settings['carousel_slider_effect'] . '"';
		$output .= ' data-seffect="' . $settings['single_slider_effect'] . '"';
		$output .= ' data-style="' . $slider_style . '"';
		$output .= ' data-auto="' . ( 'yes' == $settings['autoplay'] ? 'true' : 'false' ) . '"';
		$output .= ' data-autotime="' . ( $settings['auto_time'] ? intval( $settings['auto_time'] ) : '4000' ) . '"';
		$output .= ' data-speed="' . ( $settings['speed'] ? intval( $settings['speed'] ) : '600' ) . '"';
		$output .= ' data-loop="' . ( 'yes' == $settings['loop'] ? 'true' : 'false' ) . '"';
		$output .= $settings['slidespg'] ? ' data-slidespg="' . $settings['slidespg'] . '"' : '';
		$output .= $settings['slideTo'] ? ' data-slideTo="' . $settings['slideTo'] . '"' : '';

		return $output;
	}
}
