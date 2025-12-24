<?php

namespace PenciSoledadElementor\Modules\PenciCustomCarousel\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Plugin;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class PenciCustomCarousel extends Base_Widget {


	private $slide_prints_count = 0;

	public function get_name() {
		return 'penci-custom-carousel';
	}

	public function get_title() {
		return esc_html__( 'Custom Carousel', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-carousel';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'custom', 'carousel', 'navigation' ];
	}

	protected function register_controls() {


		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Slides', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'slides_per_view',
			[
				'label'          => esc_html__( 'Slides Per View', 'soledad' ),
				'type'           => Controls_Manager::SELECT,
				'options'        => [
					'auto' => 'Auto',
					'1' => '1 Column',
					'2' => '2 Columns',
					'3' => '3 Columns',
					'4' => '4 Columns',
					'5' => '5 Columns',
					'6' => '6 Columns',
				],
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
			]
		);

		$this->add_control(
			'content_type',
			[
				'label'   => esc_html__( 'Content Type', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''                   => esc_html__( 'Default', 'soledad' ),
					'penci-ccarousel-custom-content' => esc_html__( 'Custom Content', 'soledad' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'type',
			[
				'type'        => Controls_Manager::CHOOSE,
				'label'       => esc_html__( 'Type', 'soledad' ),
				'default'     => 'image',
				'options'     => [
					'image' => [
						'title' => esc_html__( 'Image', 'soledad' ),
						'icon'  => 'eicon-image',
					],
					'video' => [
						'title' => esc_html__( 'Video', 'soledad' ),
						'icon'  => 'eicon-video-camera',
					],
				],
				'label_block' => false,
				'toggle'      => false,
			]
		);

		$repeater->add_control(
			'image',
			[
				'label'   => esc_html__( 'Image', 'soledad' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'image_link_to_type',
			[
				'label'     => esc_html__( 'Link to', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''       => esc_html__( 'None', 'soledad' ),
					'file'   => esc_html__( 'Media File', 'soledad' ),
					'custom' => esc_html__( 'Custom URL', 'soledad' ),
				],
				'condition' => [
					'type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'image_link_to',
			[
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'dynamic'     => [ 'active' => true ],
				'condition'   => [
					'type'               => 'image',
					'image_link_to_type' => 'custom',
				],
				'separator'   => 'none',
				'show_label'  => false,
			]
		);

		$repeater->add_control(
			'video',
			[
				'label'         => esc_html__( 'Video Link', 'soledad' ),
				'type'          => Controls_Manager::URL,
				'dynamic'       => [ 'active' => true ],
				'placeholder'   => esc_html__( 'Enter your video link', 'soledad' ),
				'description'   => esc_html__( 'Insert YouTube or Vimeo link', 'soledad' ),
				'show_external' => false,
				'condition'     => [
					'type' => 'video',
				],
			]
		);

		$this->add_control(
			'slides',
			[
				'label'     => esc_html__( 'Slides', 'soledad' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'default'   => $this->get_repeater_defaults(),
				'condition' => [
					'content_type' => ''
				],
			]
		);


		$repeater_2 = new Repeater();

		$repeater_2->add_control(
			'source',
			[
				'type'        => Controls_Manager::CHOOSE,
				'label'       => esc_html__( 'Source', 'soledad' ),
				'default'     => 'editor',
				'options'     => [
					'editor'   => [
						'title' => esc_html__( 'Editor', 'soledad' ),
						'icon'  => 'eicon-edit',
					],
					'template' => [
						'title' => esc_html__( 'Template', 'soledad' ),
						'icon'  => 'eicon-section',
					],
				],
				'label_block' => false,
				'toggle'      => false,
			]
		);


		$repeater_2->add_control(
			'template_source',
			[
				'label'     => esc_html__( 'Select Source', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'elementor',
				'options'   => [
					"elementor" => esc_html__( 'Elementor Template', 'soledad' ),
					'anywhere'  => esc_html__( 'Penci Block Template', 'soledad' ),
				],
				'condition' => [
					'source' => 'template',
				],
			]
		);
		$repeater_2->add_control(
			'elementor_template',
			[
				'label'       => __( 'Select Template', 'soledad' ),
				'label_block' => true,
				'placeholder' => __( 'Type and select template', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_posts_by_query',
				'render'      => 'penci_get_posts_title_by_id',
				'post_type'   => 'elementor_library',
				'condition'   => [
					'source'          => 'template',
					'template_source' => "elementor"
				],
			]
		);
		$repeater_2->add_control(
			'anywhere_template',
			[
				'label'       => __( 'Select Template', 'soledad' ),
				'label_block' => true,
				'placeholder' => __( 'Type and select template', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_posts_by_query',
				'render'      => 'penci_get_posts_title_by_id',
				'post_type'   => 'penci-block',
				'condition'   => [
					'source'          => 'template',
					'template_source' => "anywhere"
				],
			]
		);

		$repeater_2->add_control(
			'editor_content',
			[
				'type'       => Controls_Manager::TEXTAREA,
				'dynamic'    => [ 'active' => true ],
				'default'    => __( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', 'soledad' ),
				'show_label' => false,
				'condition'  => [
					'source' => 'editor',
				],
			]
		);

		$repeater_2->add_responsive_control(
			'custom_slide_width',
			[
				'label'     => __( 'Custom Slide Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => '' ),
				'size_units'   => array( '%','px' ),
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 1000, ) ),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}'  => 'width: {{SIZE}}{{UNIT}};'
				),
			]
		);

		$this->add_control(
			'skin_template_slides',
			[
				'label'     => esc_html__( 'Slides', 'soledad' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater_2->get_controls(),
				'default'   => $this->get_repeater_defaults(),
				'condition' => [
					'content_type' => 'penci-ccarousel-custom-content'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image_size',
				'default'   => 'medium',
				'separator' => 'before',
				'condition' => [
					'content_type!' => 'penci-ccarousel-custom-content',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => esc_html__( 'Carousel Settings', 'soledad' ),
			]
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

		$this->add_control( 'autoplay', array(
			'label'   => __( 'Autoplay', 'soledad' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );

		$this->add_control( 'loop', array(
			'label'     => __( 'Carousel Loop', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'condition' => array( 'carousel_slider_effect' => 'default' ),
		) );
		$this->add_control( 'auto_time', array(
			'label'   => __( 'Carousel Auto Time ( 1000 = 1s )', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 4000,
		) );
		$this->add_control( 'speed', array(
			'label'   => __( 'Carousel Speed ( 1000 = 1s )', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 600,
		) );
		$this->add_control( 'shownav', array(
			'label'   => __( 'Show next/prev buttons', 'soledad' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );
		$this->add_control( 'showdots', array(
			'label' => __( 'Show dots navigation', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			[
				'label' => esc_html__( 'Slides', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'space_between',
			[
				'label'   => esc_html__( 'Space Between', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'max' => 50,
					],
				],
				'default' => [
					'size' => 10,
				],
				'condition' => ['carousel_slider_effect'=>'default']
			]
		);

		$this->add_control(
			'slide_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-carousel .swiper-slide' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slide_border_size',
			[
				'label'     => esc_html__( 'Border Size', 'soledad' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .swiper-carousel .swiper-slide' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'slide_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-carousel .swiper-slide' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'slide_padding',
			[
				'label'     => esc_html__( 'Padding', 'soledad' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .swiper-carousel .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slide_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'%' => [
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .swiper-carousel .swiper-slide, {{WRAPPER}} .penci-ccarousel-ep-custom-carousel .swiper-carousel' => 'border-radius: {{SIZE}}{{UNIT}};overflow:hidden;',
				],
			]
		);

		$this->add_control(
			'shadow_mode',
			[
				'label'        => esc_html__( 'Shadow Mode', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'penci-ccarousel-ep-shadow-mode-',
			]
		);

		$this->add_control(
			'shadow_color',
			[
				'label'     => esc_html__( 'Shadow Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'shadow_mode' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container:before' => is_rtl() ? 'background: linear-gradient(to left, {{VALUE}} 5%,rgba(255,255,255,0) 100%);' : 'background: linear-gradient(to right, {{VALUE}} 5%,rgba(255,255,255,0) 100%);',
					'{{WRAPPER}} .elementor-widget-container:after'  => is_rtl() ? 'background: linear-gradient(to left, rgba(255,255,255,0) 0%, {{VALUE}} 95%);' : 'background: linear-gradient(to right, rgba(255,255,255,0) 0%, {{VALUE}} 95%);',
				],
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
		) );

		$this->add_control( 'dots_bg_color', array(
			'label'     => __( 'Dot Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-custom-slides .penci-owl-carousel .penci-owl-dot span,{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span,{{WRAPPER}} .swiper-container .progress' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_bd_color', array(
			'label'     => __( 'Dot Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'border-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_bga_color', array(
			'label'     => __( 'Dot Borders Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span,{{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_bda_color', array(
			'label'     => __( 'Dot Borders Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span' => 'border-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_cs_w', array(
			'label'     => __( 'Dot Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 5, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'width: {{SIZE}}px;height: {{SIZE}}px;' ),
		) );

		$this->add_control( 'dots_csbd_w', array(
			'label'     => __( 'Dot Borders Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'border-width: {{SIZE}}px;' ),
		) );

		$this->add_control( 'heading_prenex_style', array(
			'label'     => __( 'Previous/Next Buttons', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'dots_nxpv_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next, {{WRAPPER}} .slider-40-wrapper .nav-slider-button' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_hcolor', array(
			'label'     => __( 'Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev:hover, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next:hover, {{WRAPPER}} .slider-40-wrapper .nav-slider-button:hover' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next, {{WRAPPER}} .slider-40-wrapper .nav-slider-button' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_hbgcolor', array(
			'label'     => __( 'Hover Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev:hover, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next:hover, {{WRAPPER}} .slider-40-wrapper .nav-slider-button:hover' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_nxpv_sizes', array(
			'label'     => __( 'Button Padding', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next, {{WRAPPER}} .slider-40-wrapper .nav-slider-button' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: auto;height: auto;line-height: normal;margin-top:0;transform:translateY(-50%);' ),
		) );

		$this->add_control( 'dots_nxpv_isizes', array(
			'label'     => __( 'Icon Size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev i, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next i, {{WRAPPER}} .slider-40-wrapper .nav-slider-button i' => 'font-size: {{SIZE}}px;' ),
		) );

		$this->add_control( 'dots_nxpv_bdradius', array(
			'label'     => __( 'Button Border Radius', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-prev, {{WRAPPER}} .penci-owl-carousel .penci-owl-nav .owl-next, {{WRAPPER}} .slider-40-wrapper .nav-slider-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->end_controls_section();
	}

	protected function get_default_slides_count() {
		return 5;
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return array_fill( 0, $this->get_default_slides_count(), [
			'image' => [
				'url' => $placeholder_image_src,
			],
		] );
	}

	protected function get_image_caption( $slide ) {
		$caption_type = $this->get_settings_for_display( 'caption' );

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $slide['image']['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}
	}

	protected function get_image_link_to( $slide ) {
		if ( isset( $slide['video']['url'] ) ) {
			return $slide['image']['url'];
		}

		if ( ! isset( $slide['image_link_to_type'] ) ) {
			return '';
		}
		if ( 'custom' === $slide['image_link_to_type'] ) {
			return $slide['image_link_to']['url'];
		}

		return $slide['image']['url'];
	}

	protected function print_slide( array $slide, array $settings, $element_key ) {

		if ( 'penci-ccarousel-custom-content' === $settings['content_type'] ) {
			$this->render_slide_template( $slide, $element_key, $settings );
		} else {
			if ( ! empty( $settings['thumbs_slider'] ) ) {

				$settings['video_play_icon'] = false;
			}

			$this->add_render_attribute( $element_key . '-image', [
				'class' => 'penci-image-holder penci-lazy',
				'data-bgset' => $this->get_slide_image_url( $slide, $settings ),
			] );

			$image_link_to = $this->get_image_link_to( $slide );


			if ( $image_link_to ) {

				if ( ( 'video' !== $slide['type'] ) && ( '' !== isset( $slide['video']['url'] ) ) && 'custom' !== $slide['image_link_to_type'] ) {
					$this->add_link_attributes( $element_key . '_link', $slide['image'] );
				}

				if ( 'custom' === $slide['image_link_to_type'] ) {

					$this->add_link_attributes( $element_key . '_link', $slide['image_link_to'] );
				} else {
					$this->add_render_attribute( $element_key . '_link', [
						'data-elementor-open-lightbox' => 'no',
						'data-caption'                 => $this->get_image_caption( $slide ),
					] );
				}

				if ( 'video' === $slide['type'] && $slide['video']['url'] ) {
					$this->add_link_attributes( $element_key . '_link', $slide['video'] );
				}

				echo '<a ' . $this->get_render_attribute_string( $element_key . '_link' ) . '>';
			}

			echo '<span ' . $this->get_render_attribute_string( $element_key . '-image' ) . '></span>';

			if ( $image_link_to ) {
				echo '</a>';
			}
		}
	}

	protected function template_edit_link( $template_id ) {
		if ( Plugin::$instance->editor->is_edit_mode() ) {

			$final_url = add_query_arg( [ 'elementor' => '' ], get_permalink( $template_id ) );
	
			$output = sprintf( '<a class="penci-elementor-template-edit-link" href="%1$s" title="%2$s" target="_blank"><i class="eicon-edit"></i></a>', esc_url( $final_url ), esc_html__( 'Edit Template', 'soledad' ) );
	
			return $output;
		}
	
		return false;
	}

	protected function render_slide_template( array $slide, $element_key, array $settings ) {

		?>
        <div <?php $this->print_render_attribute_string( $element_key . '-template' ); ?>>
			<?php

			if ( 'template' == $slide['source'] ) {
				if ( 'elementor' == $slide['template_source'] and ! empty( $slide['elementor_template'] ) ) {
					echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $slide['elementor_template'] );
					echo $this->template_edit_link( $slide['elementor_template'] );
				} elseif ( 'anywhere' == $slide['template_source'] and ! empty( $slide['anywhere_template'] ) ) {
					echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $slide['anywhere_template'] );
					echo $this->template_edit_link( $slide['anywhere_template'] );
				}
			} else {
				echo wp_kses_post( $slide['editor_content'] );
			}

			?>
        </div>
		<?php
	}

	protected function render_header() {
		$id         = 'penci-ccarousel-ep-custom-carousel-' . $this->get_id();
		$settings   = $this->get_settings_for_display();
		$skin_class = ( 'penci-ccarousel-custom-content' == $settings['content_type'] ) ? 'custom-content' : 'default';

		$this->add_render_attribute( 'custom-carousel', 'id', $id );
		$this->add_render_attribute( 'custom-carousel', 'class', [
			'penci-ccarousel-ep-custom-carousel',
			'elementor-swiper',
			'penci-ccarousel-skin-' . $skin_class
		] );
		$this->add_render_attribute( 'custom-carousel', 'data-penci-ccarousel-lightbox', 'toggle: .penci-ccarousel-ep-custom-carousel-lightbox-item; animation: slide;' );

		$data_slider = '';
		$data_slider .= $settings['showdots'] ? ' data-dots="true"' : '';
		$data_slider .= $settings['shownav'] ? ' data-nav="true"' : '';
		$data_slider .= ! $settings['loop'] ? ' data-loop="true"' : '';
		$data_slider .= ' data-auto="' . ( 'yes' == $settings['autoplay'] ? 'true' : 'false' ) . '"';
		$data_slider .= $settings['auto_time'] ? ' data-autotime="' . $settings['auto_time'] . '"' : ' data-autotime="4000"';
		$data_slider .= $settings['speed'] ? ' data-speed="' . $settings['speed'] . '"' : ' data-speed="600"';

		$data_slider .= ' data-item="' . ( isset( $settings['slides_per_view'] ) && $settings['slides_per_view'] ? $settings['slides_per_view'] : '3' ) . '"';
		$data_slider .= ' data-desktop="' . ( isset( $settings['slides_per_view'] ) && $settings['slides_per_view'] ? $settings['slides_per_view'] : '3' ) . '" ';
		$data_slider .= ' data-tablet="' . ( isset( $settings['slides_per_view_tablet'] ) && $settings['slides_per_view_tablet'] ? $settings['slides_per_view_tablet'] : '2' ) . '"';
		$data_slider .= ' data-tabsmall="' . ( isset( $settings['slides_per_view_tablet'] ) && $settings['slides_per_view_tablet'] ? $settings['slides_per_view_tablet'] : '2' ) . '"';
		$data_slider .= ' data-mobile="' . ( isset( $settings['slides_per_view_mobile'] ) && $settings['slides_per_view_mobile'] ? $settings['slides_per_view_mobile'] : '1' ) . '"';
		$data_slider .= ' data-margin="' . ( isset( $settings['space_between']['size'] ) && $settings['space_between']['size'] ? $settings['space_between']['size'] : '30' ) . '"';
		$data_slider .= ' data-ceffect="' . $settings['carousel_slider_effect'] . '"';

		$this->add_render_attribute( 'swiper', 'class', 'swiper-carousel swiper penci-owl-carousel penci-owl-carousel-slider');

		?>
        <div <?php $this->print_render_attribute_string( 'swiper' ); ?> <?php echo $data_slider;?>>
        <div class="swiper-wrapper">
		<?php
	}

	protected function render_loop_slides( array $settings = null ) {

		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$default_settings = [ 'video_play_icon' => true ];
		$settings         = array_merge( $default_settings, $settings );

		$slides = array();

		if ( 'penci-ccarousel-custom-content' == $settings['content_type'] ) {
			$slides = $settings['skin_template_slides'];
		} else {
			$slides = $settings['slides'];
		}

		foreach ( $slides as $index => $slide ) :
			$this->slide_prints_count ++;


			$this->add_render_attribute( 'slide', 'class', 'swiper-slide penci-custom-slide-'.$index .' elementor-repeater-item-' . esc_attr( $slide['_id'] ), true );

			?>
            <div <?php $this->print_render_attribute_string( 'slide' ); ?>>
				<?php $this->print_slide( $slide, $settings, 'slide-' . $index . '-' . $this->slide_prints_count ); ?>
            </div>
		<?php
		endforeach;
	}

	protected function get_slide_image_url( $slide, array $settings ) {
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['image']['id'], 'image_size', $settings );

		if ( ! $image_url ) {
			$image_url = $slide['image']['url'];
		}

		return $image_url;
	}

	protected function render_slider( array $settings = null ) {
		$this->render_loop_slides( $settings );
	}

	public function render() {
		$this->render_header();
		$this->render_slider();
		echo '</div></div>';
	}
}