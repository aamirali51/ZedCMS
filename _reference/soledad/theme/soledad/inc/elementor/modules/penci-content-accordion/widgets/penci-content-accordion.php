<?php

namespace PenciSoledadElementor\Modules\PenciContentAccordion\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Repeater;
use PenciSoledadElementor\Modules\QueryControl\Controls\Penci_Group_Control_Posts;
use PenciSoledadElementor\Modules\QueryControl\Module as Query_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciContentAccordion extends Base_Widget {

	public function get_name() {
		return 'penci-content-accordion';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Content Accordion', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'content', 'posts', 'custom', 'accordion' ];
	}

	public function get_script_depends() {
		return [ 'penci-content-accordion' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content_settings',
			[
				'label' => __( 'Content Settings', 'soledad' ),
			]
		);

		$this->add_control(
			'content_settings',
			[
				'label'          => __( 'Query Type:', 'soledad' ),
				'type'           => Controls_Manager::SELECT,
				'options'        => [
					'post'   => esc_html__( 'Based Posts', 'soledad' ),
					'custom' => esc_html__( 'Custom', 'soledad' ),
				],
				'default'        => 'post',
				'render_type'    => 'template',
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();

		$this->register_query_section_controls( false, array( 'content_settings' => 'post' ) );

		$this->start_controls_section(
			'section_custom_item',
			[
				'label'     => esc_html__( 'Custom Items', 'soledad' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ 'content_settings' => 'custom' ]
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'items_tabs_controls' );

		$repeater->start_controls_tab(
			'tab_item_content',
			[
				'label' => __( 'Content', 'soledad' ),
			]
		);

		$repeater->add_control(
			'image_accordion_title',
			[
				'label'       => __( 'Title', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => __( 'Tab Title', 'soledad' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'image_accordion_sub_title',
			[
				'label'       => __( 'Sub Title', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'image_accordion_button',
			[
				'label'       => esc_html__( 'Button Text', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Read More', 'soledad' ),
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label'         => esc_html__( 'Button Link', 'soledad' ),
				'type'          => Controls_Manager::URL,
				'default'       => [ 'url' => '#' ],
				'show_external' => false,
				'dynamic'       => [ 'active' => true ],
				'condition'     => [
					'image_accordion_button!' => ''
				]
			]
		);

		$repeater->add_control(
			'slide_image',
			[
				'label'   => esc_html__( 'Background Image', 'soledad' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [ 'active' => true ],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_item_content_optional',
			[
				'label' => __( 'Optional', 'soledad' ),
			]
		);

		$repeater->add_control(
			'title_link',
			[
				'label'         => esc_html__( 'Title Link', 'soledad' ),
				'type'          => Controls_Manager::URL,
				'default'       => [ 'url' => '' ],
				'show_external' => false,
				'dynamic'       => [ 'active' => true ],
				'condition'     => [
					'image_accordion_title!' => ''
				]
			]
		);

		$repeater->add_control(
			'image_accordion_text',
			[
				'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => [ 'active' => true ],
				'default' => __( 'Box Content', 'soledad' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'image_accordion_items',
			[
				'label'       => esc_html__( 'Items', 'soledad' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'image_accordion_sub_title' => __( 'This is a label', 'soledad' ),
						'image_accordion_title'     => __( 'Content Accordion #1', 'soledad' ),
						'image_accordion_text'      => __( 'Lorem ipsum dolor sit amet consect voluptate repell endus kilo gram magni illo ea animi.', 'soledad' ),
						'slide_image'               => [ 'url' => Utils::get_placeholder_image_src() ]
					],
					[
						'image_accordion_sub_title' => __( 'This is a label', 'soledad' ),
						'image_accordion_title'     => __( 'Content Accordion #2', 'soledad' ),
						'image_accordion_text'      => __( 'Lorem ipsum dolor sit amet consect voluptate repell endus kilo gram magni illo ea animi.', 'soledad' ),
						'slide_image'               => [ 'url' => Utils::get_placeholder_image_src() ]
					],
					[
						'image_accordion_sub_title' => __( 'This is a label', 'soledad' ),
						'image_accordion_title'     => __( 'Content Accordion #3', 'soledad' ),
						'image_accordion_text'      => __( 'Lorem ipsum dolor sit amet consect voluptate repell endus kilo gram magni illo ea animi.', 'soledad' ),
						'slide_image'               => [ 'url' => Utils::get_placeholder_image_src() ]
					],
					[
						'image_accordion_sub_title' => __( 'This is a label', 'soledad' ),
						'image_accordion_title'     => __( 'Content Accordion #4', 'soledad' ),
						'image_accordion_text'      => __( 'Lorem ipsum dolor sit amet consect voluptate repell endus kilo gram magni illo ea animi.', 'soledad' ),
						'slide_image'               => [ 'url' => Utils::get_placeholder_image_src() ]
					],
				],
				'title_field' => '{{{ image_accordion_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_hover_box',
			[
				'label' => esc_html__( 'General Settings', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'skin_type',
			[
				'label'                => __( 'Style', 'soledad' ),
				'type'                 => Controls_Manager::SELECT,
				'options'              => [
					'default'     => __( 'Horizontal', 'soledad' ),
					'vertical'    => __( 'Vertical', 'soledad' ),
				],
				'default'              => 'default',
				'tablet_default'       => 'default',
				'mobile_default'       => 'default',
				'prefix_class'         => 'pcct-ac-%s-',
				'selectors_dictionary' => [
					'default'     => 'flex-direction: unset;',
					'vertical'    => 'flex-direction: column;',
				],
				'selectors'            => [
					'{{WRAPPER}} .penci-ct-accordion' => '{{VALUE}};',
				],
				'render_type'          => 'template',
				'style_transfer'       => true,
			]
		);

		$this->add_responsive_control(
			'image_accordion_min_height',
			[
				'label'      => esc_html__( 'Height', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 1200,
					],
					'em' => [
						'min' => 10,
						'max' => 100,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .penci-ct-accordion' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_accordion_width',
			[
				'label'     => esc_html__( 'Content Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 100,
						'max' => 1200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion-content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail_size',
				'label'     => esc_html__( 'Image Size', 'soledad' ),
				'exclude'   => [ 'custom' ],
				'default'   => 'full',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'image_accordion_event',
			[
				'label'   => __( 'Select Event', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'mouseover',
				'options' => [
					'click'     => __( 'Click', 'soledad' ),
					'mouseover' => __( 'Hover', 'soledad' ),
				],
			]
		);

		$this->add_control(
			'divider_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'items_content_align',
			[
				'label'     => __( 'Text Alignment', 'soledad' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'soledad' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'soledad' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'soledad' ),
						'icon'  => 'eicon-h-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'soledad' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion-item' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'items_content_vertical_align',
			[
				'label'     => __( 'Vertical Alignment', 'soledad' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Top', 'soledad' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'     => [
						'title' => __( 'Center', 'soledad' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'   => [
						'title' => __( 'Bottom', 'soledad' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion-content' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'active_item',
			[
				'label'     => esc_html__( 'Active Item', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'active_item_number',
			[
				'label'       => __( 'Item Number', 'soledad' ),
				'description' => __( 'Type your item number', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'condition'   => [
					'active_item' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'active_item_expand',
			[
				'label'          => esc_html__( 'Active Item Column Expand', 'soledad' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default'        => [
					'size' => 6
				],
				'tablet_default' => [
					'size' => 6,
				],
				'mobile_default' => [
					'size' => 10,
				],
				'selectors'      => [
					'{{WRAPPER}} .penci-ct-accordion-item.active' => 'flex: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'     => esc_html__( 'Show Title', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'show_sub_title',
			[
				'label'   => esc_html__( 'Show Sub Title/Categories', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_text',
			[
				'label'   => esc_html__( 'Show Text/Post Excerpt', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_button',
			[
				'label'   => esc_html__( 'Show Button/Read More', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'hide_on_mobile_title',
			[
				'label'     => esc_html__( 'Hide Title on Mobile', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'hide_on_mobile_sub_title',
			[
				'label'     => esc_html__( 'Hide Sub Title on Mobile', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_sub_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'hide_on_mobile_text',
			[
				'label'     => esc_html__( 'Hide Text on Mobile', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_text' => 'yes'
				]
			]
		);

		$this->add_control(
			'hide_on_mobile_button',
			[
				'label'     => esc_html__( 'Hide Button on Mobile', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_button' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		//Style
		$this->start_controls_section(
			'section_image_accordion_style',
			[
				'label' => __( 'Content Accordion', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_accordion_overlay_color',
			[
				'label'     => __( 'Overlay Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion-item .penci-ct-accordion-content:before' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'tabs_content_padding',
			[
				'label'      => __( 'Content Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-ct-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_accordion_divider_heading',
			[
				'label'     => __( 'Divider', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_accordion_divider_color',
			[
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion-item:after' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_accordion_divider_width',
			[
				'label'     => esc_html__( 'Width', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion-item:after' => 'width: {{SIZE}}{{UNIT}}; right: calc(-{{SIZE}}{{UNIT}} / 2);',
				],
				'condition' => [
					'skin_type'         => [ 'default' ],
					'enable_item_style' => ''
				],
			]
		);

		$this->add_responsive_control(
			'image_accordion_divider_width_skin',
			[
				'label'       => esc_html__( 'Width', 'soledad' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'condition'   => [
					'skin_type'         => [ 'vertical' ],
					'enable_item_style' => ''
				],
				'render_type' => 'ui',
				'selectors'   => [
					'{{WRAPPER}} .penci-ct-accordion-item:after' => '--divider-width: {{SIZE}}{{UNIT}}; --divider-bottom: -{{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'enable_item_style',
			[
				'label'     => esc_html__( 'Enable Item Style', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'item_column_gap',
			[
				'label'     => esc_html__( 'Item Gap', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion'            => 'grid-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .penci-ct-accordion-item:after' => 'width: 0; right: 0; --divider-width: 0; --divider-bottom: -0;',
				],
				'condition' => [
					'enable_item_style' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'item_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-ct-accordion-item',
				'condition'   => [
					'enable_item_style' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'item_radius',
			[
				'label'      => __( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-ct-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'enable_item_style' => 'yes',
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
					'show_title' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion .penci-ct-accordion-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion-title' => 'padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-ct-accordion-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'     => 'title_text_stroke',
				'label'    => __( 'Text Stroke', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-ct-accordion-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_sub_title',
			[
				'label'     => esc_html__( 'Sub Title', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_sub_title' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'sub_title_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion .penci-ct-accordion-sub-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_title_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion-sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_title_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-ct-accordion-sub-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_post_meta',
			[
				'label'     => esc_html__( 'Post Meta', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_settings' => [ 'post' ],
				],
			]
		);

		$this->add_control(
			'post_meta_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-meta span, {{WRAPPER}} .penci-ct-meta span a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_meta_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-meta' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_meta_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-ct-meta span,{{WRAPPER}} .penci-ct-meta a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			[
				'label'     => esc_html__( 'Text', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_text' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion .penci-ct-accordion-text, {{WRAPPER}} .penci-ct-accordion .penci-ct-accordion-text *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion-text' => 'padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-ct-accordion-text',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label'     => esc_html__( 'Button', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion .penci-ct-accordion-button a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background',
				'selector' => '{{WRAPPER}} .penci-ct-accordion-button a',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				'label'     => esc_html__( 'Border', 'soledad' ),
				'selector'  => '{{WRAPPER}} .penci-ct-accordion-button a',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-ct-accordion-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'border_radius_advanced_show!' => 'yes',
				],
			]
		);

		$this->add_control(
			'border_radius_advanced_show',
			[
				'label' => __( 'Advanced Radius', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_responsive_control(
			'border_radius_advanced',
			[
				'label'       => esc_html__( 'Radius', 'soledad' ),
				'description' => sprintf( __( 'For example: <b>%1s</b> or Go <a href="%2s" target="_blank">this link</a> and copy and paste the radius value.', 'soledad' ), '30% 70% 82% 18% / 46% 62% 38% 54%', 'https://9elements.github.io/fancy-border-radius/' ),
				'type'        => Controls_Manager::TEXT,
				'size_units'  => [ 'px', '%' ],
				'separator'   => 'after',
				'default'     => '30% 70% 82% 18% / 46% 62% 38% 54%',
				'selectors'   => [
					'{{WRAPPER}} .penci-ct-accordion-button a' => 'border-radius: {{VALUE}}; overflow: hidden;',
				],
				'condition'   => [
					'border_radius_advanced_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-ct-accordion-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-ct-accordion-button a',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .penci-ct-accordion-button a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion .penci-ct-accordion-button a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_hover_background',
				'selector' => '{{WRAPPER}} .penci-ct-accordion-button a:hover',
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-ct-accordion-button a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function render_accordion_content( $item, $title_key, $button_key ) {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $item['title_link']['url'] ) ) {
			$this->add_link_attributes( $title_key, $item['title_link'] );
		}

		if ( ! empty( $item['button_link']['url'] ) ) {
			$this->add_link_attributes( $button_key, $item['button_link'] );
		}
		?>
        <div class="penci-ct-accordion-content">
			<?php if ( $item['image_accordion_sub_title'] && ( 'yes' == $settings['show_sub_title'] ) ) : ?>
                <div <?php $this->print_render_attribute_string( 'penci-ct-accordion-sub-title' ); ?>>
					<?php echo esc_html( $item['image_accordion_sub_title'] ); ?>
                </div>
			<?php endif; ?>

			<?php if ( $item['image_accordion_title'] && ( 'yes' == $settings['show_title'] ) ) : ?>
				<?php if ( '' !== $item['title_link']['url'] ) : ?>
                    <a <?php $this->print_render_attribute_string( $title_key ); ?>>
				<?php endif; ?>
                <h3 <?php $this->print_render_attribute_string( 'penci-ct-accordion-title' ); ?>>
					<?php echo esc_html( $item['image_accordion_title'] ); ?>
                </h3>
				<?php if ( '' !== $item['title_link']['url'] ) : ?>
                    </a>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $item['image_accordion_text'] && ( 'yes' == $settings['show_text'] ) ) : ?>
                <div <?php $this->print_render_attribute_string( 'penci-ct-accordion-text' ); ?>>
					<?php echo wp_kses_post( $this->parse_text_editor( $item['image_accordion_text'] ) ); ?>
                </div>
			<?php endif; ?>

			<?php if ( $item['image_accordion_button'] && ( 'yes' == $settings['show_button'] ) ) : ?>
                <div <?php $this->print_render_attribute_string( 'penci-ct-accordion-button' ); ?>>
					<?php if ( '' !== $item['button_link']['url'] ) : ?>
                    <a <?php $this->print_render_attribute_string( $button_key ); ?>>
						<?php endif; ?>
						<?php echo wp_kses_post( $item['image_accordion_button'] ); ?>
						<?php if ( '' !== $item['button_link']['url'] ) : ?>
                    </a>
				<?php endif; ?>
                </div>
			<?php endif; ?>
        </div>
		<?php
	}

	public function render_posts() {
		$settings         = $this->get_settings_for_display();
		$original_postype = $settings['posts_post_type'];

		if ( in_array( $original_postype, [
				'current_query',
				'related_posts'
			] ) && penci_elementor_is_edit_mode() && penci_is_builder_template() ) {
			$settings['posts_post_type'] = 'post';
		}
		$archive_buider_check = $settings['posts_post_type'];

		$args = Query_Control::get_query_args( 'posts', $settings );

		$ppp = $settings['posts_per_page'] ? $settings['posts_per_page'] : get_option( 'posts_per_page' );
		if ( in_array( $original_postype, [ 'current_query', 'related_posts' ] ) ) {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			if ( 'current_query' == $original_postype ) {
				$ppp = isset( $settings['arposts_per_page'] ) && $settings['arposts_per_page'] ? $settings['arposts_per_page'] : $ppp;
			}
			$offset = 0;

			if ( $ppp ) {
				$args['posts_per_page'] = $ppp;
			}
			if ( isset( $settings['arposts_new'] ) && $settings['arposts_new'] == 'yes' ) {
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

		$query_custom = new \WP_Query( $args );

		if ( ! $query_custom->have_posts() ) {
			echo $this->show_missing_settings( 'Content Accordion', penci_get_setting( 'penci_ajaxsearch_no_post' ) );
		}

		?>
        <div <?php $this->print_render_attribute_string( 'content-accordion' ); ?>>
			<?php
			while ( $query_custom->have_posts() ) : $query_custom->the_post();
				$slide_image = get_the_post_thumbnail_url();
				$this->add_render_attribute( 'content-accordion-item', 'class', 'penci-ct-accordion-item penci-lazy penci-image-holder', true );
				?>
				<div <?php $this->print_render_attribute_string( 'content-accordion-item' ); ?>
                            data-bgset="<?php echo esc_url( $slide_image ); ?>">
					<?php $this->show_post_content( $settings ); ?>
                </div>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
        </div>
		<?php
	}

	public function show_post_content( $settings ) {
		$primary_cat = true;
		$post_meta   = $settings['postmeta'];
		?>
        <div class="penci-ct-accordion-content">
			<?php if ( 'yes' == $settings['show_sub_title'] ) : ?>
                <div <?php $this->print_render_attribute_string( 'penci-ct-accordion-sub-title' ); ?>>
					<?php penci_category( '', $primary_cat ); ?>
                </div>
			<?php endif; ?>

			<?php if ( 'yes' == $settings['show_title'] ) : ?>
                <h3 <?php $this->print_render_attribute_string( 'penci-ct-accordion-title' ); ?>>
                    <a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
                    </a>
                </h3>
			<?php endif;
			if ( ( isset( $settings['cspost_enable'] ) && $settings['cspost_enable'] ) || ( count( array_intersect( array(
						'author',
						'date',
						'comment',
						'views',
						'reading',
					), $post_meta ) ) > 0 ) ) { ?>
                <div class="grid-post-box-meta penci-ct-meta">
                    <div class="pcbg-meta-desc">
						<?php if ( in_array( 'author', $post_meta ) ) : ?>
                            <span class="penci-ct-date-author author-italic author vcard">
						<?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
									penci_coauthors_posts_links();
								else: ?>
                                    <a class="author-url url fn n"
                                       href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
								<?php endif; ?>
					</span>
						<?php endif; ?>
						<?php if ( in_array( 'date', $post_meta ) ) : ?>
                            <span class="penci-ct-date"><?php penci_soledad_time_link(); ?></span>
						<?php endif; ?>
						<?php if ( in_array( 'comment', $post_meta ) ) : ?>
                            <span class="penci-ct-comment">
						<a href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a>
					</span>
						<?php endif; ?>
						<?php
						if ( in_array( 'views', $post_meta ) ) {
							echo '<span>';
							echo penci_get_post_views( get_the_ID() );
							echo ' ' . penci_get_setting( 'penci_trans_countviews' );
							echo '</span>';
						}
						?>
						<?php
						$hide_readtime = in_array( 'reading', $post_meta ) ? false : true;
						if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                            <span class="penci-ct-readtime"><?php penci_reading_time(); ?></span>
						<?php endif; ?>
						<?php echo penci_show_custom_meta_fields( [
							'validator' => isset( $settings['cspost_enable'] ) ? $settings['cspost_enable'] : '',
							'keys'      => isset( $settings['cspost_cpost_meta'] ) ? $settings['cspost_cpost_meta'] : '',
							'acf'       => isset( $settings['cspost_cpost_acf_meta'] ) ? $settings['cspost_cpost_acf_meta'] : '',
							'label'     => isset( $settings['cspost_cpost_meta_label'] ) ? $settings['cspost_cpost_meta_label'] : '',
							'divider'   => isset( $settings['cspost_cpost_meta_divider'] ) ? $settings['cspost_cpost_meta_divider'] : '',
						] ); ?>
						<?php do_action( 'penci_extra_meta' ); ?>
                    </div>
                </div>
			<?php }
			$excerpt_length = '20';
			if ( 'yes' == $settings['show_text'] ) : ?>
                <div <?php $this->print_render_attribute_string( 'penci-ct-accordion-text' ); ?>>
					<?php penci_the_excerpt( $excerpt_length ); ?>
                </div>
			<?php endif; ?>

			<?php if ( 'yes' == $settings['show_button'] ) : ?>
                <div <?php $this->print_render_attribute_string( 'penci-ct-accordion-button' ); ?>>
                    <a href="<?php the_permalink(); ?>">
						<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>
                    </a>
                </div>
			<?php endif; ?>
        </div>
		<?php
	}

	public static function show_missing_settings( $label, $mess ) {
		$output = '';
		if ( current_user_can( 'manage_options' ) ) {
			$output .= '<div class="penci-missing-settings">';
			$output .= '<p style="margin-bottom: 4px;">This message appears for administrator users only</p>';
			$output .= '<span>' . $label . '</span>';
			$output .= $mess;
			$output .= '</div>';
		}

		return $output;
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		$id       = $this->get_id();

		if ( $settings['image_accordion_event'] ) {
			$imageAccordionEvent = $settings['image_accordion_event'];
		} else {
			$imageAccordionEvent = false;
		}

		$this->add_render_attribute(
			[
				'content-accordion' => [
					'id'            => 'penci-ct-accordion-' . $this->get_id(),
					'class'         => 'penci-ct-accordion',
					'data-settings' => [
						wp_json_encode( array_filter( [
							'tabs_id'          => 'penci-ct-accordion-' . $this->get_id(),
							'mouse_event'      => $imageAccordionEvent,
							'activeItem'       => $settings['active_item'] == 'yes' ? true : false,
							'activeItemNumber' => $settings['active_item_number']
						] ) )
					]
				]
			]
		);

		$this->add_render_attribute( 'penci-ct-accordion-title', 'class', 'penci-ct-accordion-title', true );
		$this->add_render_attribute( 'penci-ct-accordion-sub-title', 'class', 'penci-ct-accordion-sub-title', true );
		$this->add_render_attribute( 'penci-ct-accordion-text', 'class', 'penci-ct-accordion-text', true );
		$this->add_render_attribute( 'penci-ct-accordion-button', 'class', 'penci-ct-accordion-button', true );

		if ( 'yes' == $settings['hide_on_mobile_title'] ) {
			$this->add_render_attribute( 'penci-ct-accordion-title', 'class', 'penci-ct-accordion-title pccthide-m', true );
		}
		if ( 'yes' == $settings['hide_on_mobile_sub_title'] ) {
			$this->add_render_attribute( 'penci-ct-accordion-sub-title', 'class', 'penci-ct-accordion-sub-title pccthide-m', true );
		}
		if ( 'yes' == $settings['hide_on_mobile_text'] ) {
			$this->add_render_attribute( 'penci-ct-accordion-text', 'class', 'penci-ct-accordion-text pccthide-m', true );
		}
		if ( 'yes' == $settings['hide_on_mobile_button'] ) {
			$this->add_render_attribute( 'penci-ct-accordion-button', 'class', 'penci-ct-accordion-button pccthide-m', true );
		}

		if ( $settings['content_settings'] == 'post' ) {
			$this->render_posts();
		} else {
			$this->render_custom();
		}
	}

	public function render_custom() {
		$settings = $this->get_settings_for_display();
		if ( empty( $settings['image_accordion_items'] ) ) {
			return;
		}
		?>

        <div <?php $this->print_render_attribute_string( 'content-accordion' ); ?>>
			<?php foreach ( $settings['image_accordion_items'] as $index => $item ) :

				$title_key = 'title_to_' . $index;
				$button_key = 'button_to_' . $index;

				$slide_image = Group_Control_Image_Size::get_attachment_image_src( $item['slide_image']['id'], 'thumbnail_size', $settings );
				if ( ! $slide_image ) {
					$slide_image = $item['slide_image']['url'];
				}
				$this->add_render_attribute( 'content-accordion-item', 'class', 'penci-ct-accordion-item penci-lazy penci-image-holder', true );
				?>

				
                <div <?php $this->print_render_attribute_string( 'content-accordion-item' ); ?>
                        data-bgset="<?php echo esc_url( $slide_image ); ?>">
					<?php $this->render_accordion_content( $item, $title_key, $button_key ); ?>
                </div>
			

			<?php endforeach; ?>
        </div>
		<?php
	}
}
