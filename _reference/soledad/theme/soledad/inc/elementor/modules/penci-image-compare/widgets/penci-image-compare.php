<?php

namespace PenciSoledadElementor\Modules\PenciImageCompare\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class PenciImageCompare extends Base_Widget {

	public function get_name() {
		return 'penci-image-compare';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Compare Before/After', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-image';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'image', 'compare', 'comparison', 'difference' ];
	}

	public function get_script_depends() {
		return [ 'penci-image-compare' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'soledad' ),
			]
		);

		$this->add_control(
			'before_image',
			[
				'label'       => esc_html__( 'Before Image', 'soledad' ),
				'description' => esc_html__( 'Use same size image for before and after for better preview.', 'soledad' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'after_image',
			[
				'label'       => esc_html__( 'After Image', 'soledad' ),
				'description' => esc_html__( 'Use same size image for before and after for better preview.', 'soledad' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'dynamic'     => [ 'active' => true ],
			]
		);


		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'thumbnail_size',
				'label'   => __( 'Image Size', 'soledad' ),
				'exclude' => [ 'custom' ],
				'default' => 'full',
			]
		);

		$this->add_responsive_control(
			'img_ratio',
			[
				'label'       => esc_html__( 'Customize Compare Area Height', 'soledad' ),
				'description' => esc_html__( 'The image is dynamically cropped using the CSS background "cover" property.', 'soledad' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'render_type' => 'template',
				'size_units'  => [ 'px', 'em', 'rem', 'vh' ],
				'default'     => array( 'size' => '' ),
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 2000, ) ),
				'selectors'   => array(
					'{{WRAPPER}} .image-compare' => 'width: 100%; height: {{SIZE}}{{UNIT}};'
				)
			]
		);

		$this->add_control(
			'before_label',
			[
				'label'       => esc_html__( 'Before Label', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Before Label', 'soledad' ),
				'default'     => esc_html__( 'Before', 'soledad' ),
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'after_label',
			[
				'label'       => esc_html__( 'After Label', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'After Label', 'soledad' ),
				'default'     => esc_html__( 'After', 'soledad' ),
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_additional_settings',
			[
				'label' => esc_html__( 'General Settings', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'orientation',
			[
				'label'   => esc_html__( 'Orientation', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'soledad' ),
					'vertical'   => esc_html__( 'Vertical', 'soledad' ),
				],
			]
		);

		$this->add_control(
			'default_offset_pct',
			[
				'label'   => esc_html__( 'Before Image Visiblity', 'soledad' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 70,
				],
				'range'   => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
			]
		);

		$this->add_control(
			'no_overlay',
			[
				'label'       => esc_html__( 'Overlay', 'soledad' ),
				'description' => esc_html__( 'Do not show the overlay with before and after.', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'separator'   => 'before'
			]
		);

		$this->add_control(
			'on_hover',
			[
				'label'     => esc_html__( 'On Hover?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'no_overlay' => 'yes'
				]
			]
		);

		$this->add_control(
			'move_slider_on_hover',
			[
				'label'       => esc_html__( 'Slide on Hover', 'soledad' ),
				'description' => esc_html__( 'Move slider on mouse hover?', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'separator'   => 'before'
			]
		);

		$this->add_control(
			'add_circle',
			[
				'label'     => esc_html__( 'Enable Circle In Bar', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'add_circle_blur',
			[
				'label'     => esc_html__( 'Enable Circle Blur', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'add_circle' => 'yes'
				],
			]
		);

		$this->add_control(
			'add_circle_shadow',
			[
				'label'     => esc_html__( 'Enable Circle Shadow', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'add_circle' => 'yes'
				],
			]
		);

		$this->add_control(
			'smoothing',
			[
				'label'     => esc_html__( 'Enable Smoothing', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'smoothing_amount',
			[
				'label'     => esc_html__( 'Smoothing Amount', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 400,
				],
				'range'     => [
					'px' => [
						'max'  => 1000,
						'min'  => 100,
						'step' => 10,
					],
				],
				'condition' => [
					'smoothing' => 'yes'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_style',
			[
				'label' => esc_html__( 'Style', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label'     => esc_html__( 'Overlay Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penciimg-compare .penciimg-compare-overlay:before' => 'background: {{VALUE}};',
				],
				'condition' => [
					'no_overlay' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_image_compare_style' );

		$this->start_controls_tab(
			'tab_image_compare_before_style',
			[
				'label' => esc_html__( 'Before', 'soledad' ),
			]
		);

		$this->add_control(
			'before_background',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penciimg-compare .icv__label.icv__label-before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'before_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penciimg-compare .icv__label.icv__label-before' => 'color: {{VALUE}};',
				],
			]
		);


		$this->end_controls_tab();


		$this->start_controls_tab(
			'tab_image_compare_after_style',
			[
				'label' => esc_html__( 'After', 'soledad' ),
			]
		);

		$this->add_control(
			'after_background',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penciimg-compare .icv__label.icv__label-after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'after_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penciimg-compare .icv__label.icv__label-after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_image_compare_bar_style',
			[
				'label' => esc_html__( 'Bar', 'soledad' ),
			]
		);

		$this->add_control(
			'bar_color',
			[
				'label'   => esc_html__( 'Bar Color', 'soledad' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#fff',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_responsive_control(
			'after_before_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penciimg-compare .icv__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'after_before_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penciimg-compare .icv__label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'after_before_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penciimg-compare .icv__label',
			]
		);

		$this->end_controls_section();

	}

	public function render() {
		$settings = $this->get_settings_for_display();

		if ( $settings['default_offset_pct']['size'] < 1 ) {
			$settings['default_offset_pct']['size'] = $settings['default_offset_pct']['size'] * 100;
		}

		$settings_arg = [
			'id'                   => 'image-compare-' . $this->get_id(),
			'default_offset_pct'   => $settings['default_offset_pct']['size'],
			'orientation'          => ( $settings['orientation'] == 'horizontal' ) ? false : true,
			'before_label'         => $settings['before_label'],
			'after_label'          => $settings['after_label'],
			'no_overlay'           => ( 'yes' == $settings['no_overlay'] ) ? true : false,
			'on_hover'             => ( 'yes' == $settings['on_hover'] ) ? true : false,
			'move_slider_on_hover' => ( 'yes' == $settings['move_slider_on_hover'] ) ? true : false,
			'add_circle'           => ( 'yes' == $settings['add_circle'] ) ? true : false,
			'add_circle_blur'      => ( 'yes' == $settings['add_circle_blur'] ) ? true : false,
			'add_circle_shadow'    => ( 'yes' == $settings['add_circle_shadow'] ) ? true : false,
			'smoothing'            => ( 'yes' == $settings['smoothing'] ) ? true : false,
			'smoothing_amount'     => $settings['smoothing_amount']['size'],
			'bar_color'            => $settings['bar_color'],
			'fluidMode'            => isset( $settings['img_ratio']['size'] ) && $settings['img_ratio']['size'] ? true : false,
		];

		$this->add_render_attribute(
			[
				'image-compare' => [
					'id'            => 'image-compare-' . $this->get_id(),
					'class'         => [ 'image-compare' ],
					'data-settings' => [
						wp_json_encode( $settings_arg ),
					],
				],
			]
		);

		if ( 'yes' == $settings['no_overlay'] ) {
			$this->add_render_attribute( 'image-compare', 'class', 'penciimg-compare-overlay' );
		}

		?>
        <div class="penciimg-compare">
            <div <?php $this->print_render_attribute_string( 'image-compare' ); ?>>
				<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail_size', 'before_image' ) ); ?>
				<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail_size', 'after_image' ) ); ?>
            </div>
        </div>

		<?php
	}
}