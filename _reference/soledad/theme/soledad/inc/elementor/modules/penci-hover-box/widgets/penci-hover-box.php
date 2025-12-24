<?php

namespace PenciSoledadElementor\Modules\PenciHoverBox\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use PenciSoledadElementor\Modules\PenciHoverBox\Skins;
use PenciSoledadElementor\Modules\QueryControl\Module as Query_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class PenciHoverBox extends Base_Widget {

	public function get_name() {
		return 'penci-hover-box';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Hover Box', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-image-rollover';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'fancy', 'effects', 'toggle', 'accordion', 'hover', 'slideshow', 'slider', 'box', 'animated boxs' ];
	}

	public function is_reload_preview_required() {
		return false;
	}

	public function get_style_depends() {
		return [ 'penci-hover-box' ];
	}

	public function get_script_depends() {
		return [ 'penci-hover-box' ];
	}

	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Envelope( $this ) );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_tabs_item',
			[
				'label' => esc_html__( 'Hover Style', 'soledad' ),
			]
		);

		$this->add_control(
			'layout_style',
			[
				'label'     => esc_html__( 'Layout Style', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'style-1',
				'options'   => [
					'style-1' => esc_html__( 'Style 1', 'soledad' ),
					'style-2' => esc_html__( 'Style 2', 'soledad' ),
				],
				'condition' => [
					'_skin' => '',
				],
			]
		);

		$this->add_control(
			'item_type',
			[
				'label'   => esc_html__( 'Item Type', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom' => esc_html__( 'Custom Items', 'soledad' ),
					'query'  => esc_html__( 'Post Query', 'soledad' ),
				],
			]
		);

		$this->end_controls_section();

		$this->register_query_section_controls( false, array( 'item_type' => 'query' ) );

		$this->start_controls_section(
			'section_hover_box_items',
			[
				'label'     => esc_html__( 'Hover Box Items', 'soledad' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'item_type' => 'custom'
				],
			]
		);


		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'items_tabs_controls' );

		$repeater->start_controls_tab(
			'tab_item_content',
			[
				'label' => esc_html__( 'Content', 'soledad' ),
			]
		);

		$repeater->add_control(
			'selected_icon',
			[
				'label'       => esc_html__( 'Icon', 'soledad' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'skin'        => 'inline',
				'label_block' => false
			]
		);

		$repeater->add_control(
			'hover_box_title',
			[
				'label'       => esc_html__( 'Title', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => esc_html__( 'Tab Title', 'soledad' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'hover_box_sub_title',
			[
				'label'       => esc_html__( 'Sub Title', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'hover_box_button',
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
				'label'     => esc_html__( 'Button Link', 'soledad' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => [ 'active' => true ],
				'default'   => [ 'url' => '#' ],
				'condition' => [
					'hover_box_button!' => ''
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
				'label' => esc_html__( 'Optional', 'soledad' ),
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
					'hover_box_title!' => ''
				]
			]
		);

		$repeater->add_control(
			'hover_box_content',
			[

				'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => [ 'active' => true ],
				'default' => esc_html__( 'Box Content', 'soledad' ),
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'hover_box_content_background',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .penci-hover-box-content {{CURRENT_ITEM}}',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Type', 'soledad' ),
					],
				],
				'separator'      => 'before',
			]
		);
		$repeater->add_control(
			'ignore_element_notes',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Note: This option will work if the background image is empty.', 'soledad' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',

			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'hover_box',
			[
				'label'       => esc_html__( 'Items', 'soledad' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'separator'   => 'before',
				'default'     => [
					[
						'hover_box_sub_title' => esc_html__( 'This is label', 'soledad' ),
						'hover_box_title'     => esc_html__( 'Hover Box One', 'soledad' ),
						'hover_box_content'   => esc_html__( 'Lorem ipsum dolor sit amet consect voluptate repell endus kilo gram magni illo ea animi.', 'soledad' ),
						'selected_icon'       => [ 'value' => 'far fa-laugh', 'library' => 'fa-regular' ],
						'slide_image'         => [ 'url' => Utils::get_placeholder_image_src() ]
					],
					[
						'hover_box_sub_title' => esc_html__( 'This is label', 'soledad' ),
						'hover_box_title'     => esc_html__( 'Hover Box Two', 'soledad' ),
						'hover_box_content'   => esc_html__( 'Lorem ipsum dolor sit amet consect voluptate repell endus kilo gram magni illo ea animi.', 'soledad' ),
						'selected_icon'       => [ 'value' => 'fas fa-cog', 'library' => 'fa-solid' ],
						'slide_image'         => [ 'url' => Utils::get_placeholder_image_src() ]
					],
					[
						'hover_box_sub_title' => esc_html__( 'This is label', 'soledad' ),
						'hover_box_title'     => esc_html__( 'Hover Box Three', 'soledad' ),
						'hover_box_content'   => esc_html__( 'Lorem ipsum dolor sit amet consect voluptate repell endus kilo gram magni illo ea animi.', 'soledad' ),
						'selected_icon'       => [ 'value' => 'fas fa-dice-d6', 'library' => 'fa-solid' ],
						'slide_image'         => [ 'url' => Utils::get_placeholder_image_src() ]
					],
					[
						'hover_box_sub_title' => esc_html__( 'This is label', 'soledad' ),
						'hover_box_title'     => esc_html__( 'Hover Box Four', 'soledad' ),
						'hover_box_content'   => esc_html__( 'Lorem ipsum dolor sit amet consect voluptate repell endus kilo gram magni illo ea animi.', 'soledad' ),
						'selected_icon'       => [ 'value' => 'fas fa-ring', 'library' => 'fa-solid' ],
						'slide_image'         => [ 'url' => Utils::get_placeholder_image_src() ]
					],
					[
						'hover_box_sub_title' => esc_html__( 'This is label', 'soledad' ),
						'hover_box_title'     => esc_html__( 'Hover Box Five', 'soledad' ),
						'hover_box_content'   => esc_html__( 'Lorem ipsum dolor sit amet consect voluptate repell endus kilo gram magni illo ea animi.', 'soledad' ),
						'selected_icon'       => [ 'value' => 'fas fa-adjust', 'library' => 'fa-solid' ],
						'slide_image'         => [ 'url' => Utils::get_placeholder_image_src() ]
					],
					[
						'hover_box_sub_title' => esc_html__( 'This is label', 'soledad' ),
						'hover_box_title'     => esc_html__( 'Hover Box Six', 'soledad' ),
						'hover_box_content'   => esc_html__( 'Lorem ipsum dolor sit amet consect voluptate repell endus kilo gram magni illo ea animi.', 'soledad' ),
						'selected_icon'       => [ 'value' => 'fas fa-cog', 'library' => 'fa-solid' ],
						'slide_image'         => [ 'url' => Utils::get_placeholder_image_src() ]
					],
				],
				'title_field' => '{{{ elementor.helpers.renderIcon( this, selected_icon, {}, "i", "panel" ) }}} {{{ hover_box_title }}}',
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
			'hover_box_min_height',
			[
				'label'     => esc_html__( 'Height', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 100,
						'max' => 20000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_skin!' => 'penci-hvb-envelope',
				]
			]
		);

		$this->add_responsive_control(
			'skin_hover_box_min_height',
			[
				'label'     => esc_html__( 'Height', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 100,
						'max' => 20000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box, {{WRAPPER}} .penci-hover-box-item' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_skin' => 'penci-hvb-envelope',
				]
			]
		);

		$this->add_responsive_control(
			'hover_box_width',
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
					'{{WRAPPER}} .penci-hover-box-item-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_skin!' => 'penci-hvb-envelope',
				]
			]
		);

		$this->add_control(
			'default_content_position',
			[
				'label'     => esc_html__( 'Content Position', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => [
					''              => esc_html__( 'Default', 'soledad' ),
					'top-left'      => esc_html__( 'Top Left', 'soledad' ),
					'top-center'    => esc_html__( 'Top Center', 'soledad' ),
					'top-right'     => esc_html__( 'Top Right', 'soledad' ),
					'center'        => esc_html__( 'Center', 'soledad' ),
					'center-left'   => esc_html__( 'Center Left', 'soledad' ),
					'center-right'  => esc_html__( 'Center Right', 'soledad' ),
					'bottom-left'   => esc_html__( 'Bottom Left', 'soledad' ),
					'bottom-center' => esc_html__( 'Bottom Center', 'soledad' ),
					'bottom-right'  => esc_html__( 'Bottom Right', 'soledad' ),
				],
				'condition' => [
					'_skin!' => 'penci-hvb-envelope',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'content_gap',
			[
				'label'     => esc_html__( 'Content Gap', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'medium',
				'options'   => [
					'small'  => esc_html__( 'Small', 'soledad' ),
					'medium' => esc_html__( 'Medium', 'soledad' ),
					'large'  => esc_html__( 'Large', 'soledad' ),
				],
				'condition' => [
					'_skin!' => 'penci-hvb-envelope',
				]
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns', 'soledad' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '2',
				'options'        => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'condition' => [
					'_skin' => '',
				],
				'separator'      => 'before',
				'selectors'      => [
					'{{WRAPPER}} .penci-hover-box-grid' => '--columns: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'ct_columns',
			[
				'label'          => esc_html__( 'Columns', 'soledad' ),
				'type'           => Controls_Manager::SLIDER,
				'condition' => [
					'_skin' => 'penci-hvb-envelope',
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 6,
					],
				],
				'default'        => [
					'size' => 4,
				],
			]
		);

		$this->add_control(
			'content_position',
			[
				'label'     => esc_html__( 'Position', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bottom',
				'options'   => [
					'top'    => 'Top',
					'center' => 'Center',
					'bottom' => 'Bottom',
				],
				'condition' => [
					'_skin' => 'penci-hvb-envelope',
				]
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'      => esc_html__( 'Gap', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .penci-hover-box-grid' => '--gap: -{{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'_skin' => '',
				]
			]
		);

		$this->add_control(
			'hover_box_event',
			[
				'label'     => esc_html__( 'Select Event ', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'mouseover',
				'options'   => [
					'click'     => esc_html__( 'Click', 'soledad' ),
					'mouseover' => esc_html__( 'Hover', 'soledad' ),
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'hover_box_active_item',
			[
				'label'       => esc_html__( 'Active Item', 'soledad' ),
				'description' => esc_html__( 'Set default item by inserting the item\'s numeric position (i.e. 1 or 2 or 3 or ...) The numeric position reads from the top-left corner as 1st and continues to the right side.', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '1',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'thumbnail_size',
				'label'   => esc_html__( 'Image Size', 'soledad' ),
				'exclude' => [ 'custom' ],
				'default' => 'full',
			]
		);

		$this->add_responsive_control(
			'tabs_content_align',
			[
				'label'     => esc_html__( 'Alignment', 'soledad' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'soledad' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'soledad' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'soledad' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'soledad' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .pchvb-meta .pcbg-meta-desc' => 'justify-content: {{VALUE}};',
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
			'title_tags',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'condition' => [
					'show_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label'     => esc_html__( 'Show Icon', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'show_sub_title',
			[
				'label'   => esc_html__( 'Show Sub Title', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'item_type' => 'custom'
				],
			]
		);
		
		$this->add_control(
			'show_post_meta',
			[
				'label'   => esc_html__( 'Show Post Meta', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'item_type' => 'query'
				],
			]
		);

		$this->add_control( 'postmeta', array(
			'label'     => __( 'Showing Post Data', 'soledad' ),
			'type'      => Controls_Manager::SELECT2,
			'default'   => array( 'cat', 'title', 'author', 'date' ),
			'multiple'  => true,
			'options'   => array(
				'cat'     => esc_html__( 'Categories', 'soledad' ),
				'author'  => esc_html__( 'Author', 'soledad' ),
				'date'    => esc_html__( 'Date', 'soledad' ),
				'comment' => esc_html__( 'Comments', 'soledad' ),
				'views'   => esc_html__( 'Views', 'soledad' ),
				'reading' => esc_html__( 'Reading Time', 'soledad' ),
			),
			'condition' => array( 
				'show_post_meta' => 'yes',
				'item_type' => 'query' 
			),
		) );

		$this->add_control(
			'primary_cat',
			[
				'label'     => esc_html__( 'Show Primary Category', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_post_meta' => 'yes',
					'item_type'      => 'query',
				],
			]
		);
		
		$this->add_control(
			'show_content',
			[
				'label'     => esc_html__( 'Show Text/Post Excerpt', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$this->add_control( 'post_content_length', array(
			'label'     => __( 'Custom Post Excerpt Words Length', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 1,
			'max'       => 100,
			'step'      => 1,
			'default'   => 20,
			'condition' => array( 
				'show_content' 	=> 'yes',
				'item_type' 	=> 'query'
			),
		) );

		$this->add_control(
			'text_hide_on',
			[
				'label'              => esc_html__( 'Text Hide On', 'soledad' ),
				'type'               => Controls_Manager::SELECT2,
				'multiple'           => true,
				'label_block'        => false,
				'options'            => [
					'desktop' => esc_html__( 'Desktop', 'soledad' ),
					'tablet'  => esc_html__( 'Tablet', 'soledad' ),
					'mobile'  => esc_html__( 'Mobile', 'soledad' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'item_type'    => 'custom',
					'show_content' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_button',
			[
				'label'     => esc_html__( 'Show Button/Read More Button', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'box_image_effect',
			[
				'label'     => esc_html__( 'Image Effect?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default' 	=> 'yes',
			]
		);

		$this->add_control(
			'box_image_effect_select',
			[
				'label'     => esc_html__( 'Effect', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'effect-1',
				'options'   => [
					'effect-1' => 'Style 1',
					'effect-2' => 'Style 2',
					'effect-3' => 'Style 3',
				],
				'condition' => [
					'box_image_effect' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		//Style
		$this->start_controls_section(
			'section_hover_box_style',
			[
				'label' => esc_html__( 'Hover Box', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover_box_overlay_color',
			[
				'label'     => esc_html__( 'Overlay Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box:before' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_box_divider_size',
			[
				'label'      => esc_html__( 'Divider Size', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .penci-hover-box-skin-envelope .penci-hover-box-item-wrap>.penci-hvb-active:before' => 'width: {{SIZE}}{{UNIT}}; left: calc(-{{SIZE}}{{UNIT}} / 2);',
				],
				'condition'  => [
					'_skin' => 'penci-hvb-envelope'
				]
			]
		);

		$this->add_control(
			'hover_box_divider_color',
			[
				'label'     => esc_html__( 'Divider Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-skin-envelope .penci-hover-box-item-wrap>.penci-hvb-active:before' => 'background: {{VALUE}};',
				],
				'condition' => [
					'_skin' => 'penci-hvb-envelope'
				]
			]
		);

		$this->add_control(
			'box_item_heading',
			[
				'label'     => esc_html__( 'Item', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'box_item_style' );

		$this->start_controls_tab(
			'box_item_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_item_background',
				'selector' => '{{WRAPPER}} .penci-hover-box-item',
			]
		);

		$this->add_responsive_control(
			'box_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-hover-box-default .penci-hover-box-item, {{WRAPPER}} .penci-hover-box-skin-envelope .penci-hover-box-description, {{WRAPPER}} .penci-hover-box-skin-flexure .penci-hover-box-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'box_item_border',
				'selector' => '{{WRAPPER}} .penci-hover-box .penci-hover-box-item',
			]
		);

		$this->add_responsive_control(
			'box_item_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-hover-box-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_item_shadow',
				'selector' => '{{WRAPPER}} .penci-hover-box-item'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'box_item_hover',
			[
				'label' => esc_html__( 'hover', 'soledad' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_item_hover_background',
				'selector' => '{{WRAPPER}} .penci-hover-box-item:hover',
			]
		);

		$this->add_control(
			'box_item_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'box_item_border_border!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_item_hover_shadow',
				'selector' => '{{WRAPPER}} .penci-hover-box-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'box_item_active',
			[
				'label' => esc_html__( 'Active', 'soledad' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_item_active_background',
				'selector' => '{{WRAPPER}} .penci-hover-box-item.active',
			]
		);

		$this->add_control(
			'box_item_active_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item.active' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'box_item_border_border!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_item_active_shadow',
				'selector' => '{{WRAPPER}} .penci-hover-box-item.active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon_box',
			[
				'label'     => esc_html__( 'Icon', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icon' => 'yes',
				]
			]
		);

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-icon-wrap'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-hover-box-icon-wrap svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'icon_background',
				'selector'       => '{{WRAPPER}} .penci-hover-box-icon-wrap',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Type', 'soledad' ),
					],
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'vh', 'vw' ],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .penci-hover-box-icon-wrap' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'rotate',
			[
				'label'     => esc_html__( 'Rotate', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
					'unit' => 'deg',
				],
				'range'     => [
					'deg' => [
						'max' => 360,
						'min' => - 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-icon-wrap i, {{WRAPPER}} .penci-hover-box-icon-wrap svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-icon-wrap  ' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} .penci-hover-box-icon-wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'icon_border',
				'label'     => esc_html__( 'Border', 'soledad' ),
				'selector'  => '{{WRAPPER}} .penci-hover-box-icon-wrap',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-hover-box-icon-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-hover-box-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => esc_html__( 'Icon Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-icon-wrap'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-icon-wrap svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'icon_button_hover_background',
				'selector'       => '{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-icon-wrap',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Type', 'soledad' ),
					],
				],
			]
		);

		$this->add_control(
			'icon_button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-icon-wrap' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_rotate',
			[
				'label'     => esc_html__( 'Rotate', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'deg',
					'size' => 90
				],
				'range'     => [
					'deg' => [
						'max' => 360,
						'min' => - 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-icon-wrap i, {{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-icon-wrap svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_active',
			[
				'label' => esc_html__( 'Active', 'soledad' ),
			]
		);

		$this->add_control(
			'icon_active_color',
			[
				'label'     => esc_html__( 'Icon Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-icon-wrap'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-icon-wrap svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'icon_button_active_background_color',
				'selector'       => '{{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-icon-wrap',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Type', 'soledad' ),
					],
				],
			]
		);

		$this->add_control(
			'icon_button_active_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-icon-wrap' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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
					'{{WRAPPER}} .penci-hover-box-title, {{WRAPPER}} .penci-hover-box-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-title, {{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_active_color',
			[
				'label'     => esc_html__( 'Active Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-title, {{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-hover-box-title, {{WRAPPER}} .penci-hover-box-title a',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_post_meta',
			[
				'label'     => esc_html__( 'Post Meta', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_post_meta' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'post_meta_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pcbg-meta-desc span, {{WRAPPER}} .pcbg-meta-desc span a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'post_meta_lcolor',
			[
				'label'     => esc_html__( 'Link Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pcbg-meta-desc span a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_meta_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pcbg-meta-desc span a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_meta_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pcbg-meta-desc span, {{WRAPPER}} .pcbg-meta-desc span a',
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
					'{{WRAPPER}} .penci-hover-box-sub-title'                                      => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-hover-box-skin-flexure .penci-hover-box-sub-title:before' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_title_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-sub-title'                                      => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-hover-box-skin-flexure .penci-hover-box-item:hover .penci-hover-box-sub-title:before' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_title_active_color',
			[
				'label'     => esc_html__( 'Active Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-sub-title'                                      => 'color: {{VALUE}};',
					'{{WRAPPER}} .penci-hover-box-skin-flexure .penci-hover-box-item.active .penci-hover-box-sub-title:before' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_title_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-sub-title'                               => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .penci-hover-box-skin-flexure .penci-hover-box-sub-title' => 'margin-left: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_title_typography',
				'selector' => '{{WRAPPER}} .penci-hover-box-sub-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			[
				'label'     => esc_html__( 'Text', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_content' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'description_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'description_active_color',
			[
				'label'     => esc_html__( 'Active Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-text' => 'padding-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .penci-hover-box-text',
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

		$this->start_controls_tabs( 'hover_box_button_style' );

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
					'{{WRAPPER}} .penci-hover-box-button a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background',
				'selector' => '{{WRAPPER}} .penci-hover-box-button a',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .penci-hover-box-button a',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				'label'     => esc_html__( 'Border', 'soledad' ),
				'selector'  => '{{WRAPPER}} .penci-hover-box-button a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-hover-box-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .penci-hover-box-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-hover-box-button a',
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
					'{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-button a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_hover_background',
				'selector' => '{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-button a',
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
					'{{WRAPPER}} .penci-hover-box-item:hover .penci-hover-box-button a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_active',
			[
				'label' => esc_html__( 'Active', 'soledad' ),
			]
		);

		$this->add_control(
			'button_active_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-button a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_active_background_color',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-button a' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_active_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-item.active .penci-hover-box-button a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .penci-hover-box-button' => 'padding-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section( 'section_design_sliderdosnav', array(
			'label' => __( 'Slider Controls', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition' => array(
				'_skin' => 'penci-hvb-envelope',
			),
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

		$this->register_block_title_style_section_controls();
	}

	public function activeItem( $active_item, $totalItem ) {
		$active_item = (int) $active_item;

		return $active_item = ( $active_item <= 0 || $active_item > $totalItem ? 1 : $active_item );
	}

	protected function render() {
		$settings = $this->get_settings();

		$this->markup_block_title( $settings, $this );

		if ( $settings['hover_box_event'] ) {
			$hoverBoxEvent = $settings['hover_box_event'];
		} else {
			$hoverBoxEvent = false;
		}

		if ( $settings['box_image_effect'] ) {
			$this->add_render_attribute( 'hover_box', 'class', 'penci-hover-box-img-effect penci-hvb-' . $settings['box_image_effect_select'] );
		}

		$this->add_render_attribute(
			[
				'hover_box' => [
					'id'    => 'penci-hover-box-' . $this->get_id(),
					'class' => 'penci-hover-box penci-hover-box-default penci-hover-box-' . $settings['layout_style'],

					'data-settings' => [
						wp_json_encode( array_filter( [
							'box_id'      => 'penci-hover-box-' . $this->get_id(),
							'mouse_event' => $hoverBoxEvent,
						] ) )
					]
				]
			]
		);

		?>
        <div <?php $this->print_render_attribute_string( 'hover_box' ); ?>>

		<?php
		if ( 'custom' == $settings['item_type'] ) {
			$this->box_content();
			$this->box_items();
		} else {
			$this->post_content();
			$this->post_items();
		}
		?>

        </div>

		<?php
	}

	public function box_content() {
		$settings   = $this->get_settings_for_display();
		$id         = $this->get_id();

		?>

		<?php foreach ( $settings['hover_box'] as $index => $item ) :
			$tab_count = $index + 1;
			$tab_id = 'penci-hvb-box-' . $tab_count . esc_attr( $id );

			$slide_image = Group_Control_Image_Size::get_attachment_image_src( $item['slide_image']['id'], 'thumbnail_size', $settings );
			if ( ! $slide_image ) {
				$slide_image = $item['slide_image']['url'];
			}
			
			$active_item = $this->activeItem( $settings['hover_box_active_item'], count( $settings['hover_box'] ) );

			if ( $tab_id == 'penci-hvb-box-' . $active_item . esc_attr( $id ) ) {
				$this->add_render_attribute( 'hover-box-content', 'class', 'penci-hover-box-content active', true );
			} else {
				$this->add_render_attribute( 'hover-box-content', 'class', 'penci-hover-box-content', true );
			}

			$this->add_render_attribute( 'hover-box-content-img', 'class', 'penci-hover-box-img elementor-repeater-item-' . esc_attr( $item['_id'] ), true );

			?>

            <div id="<?php echo esc_attr( $tab_id ); ?>" <?php $this->print_render_attribute_string( 'hover-box-content' ); ?>>

				<?php if ( $slide_image ) : ?>
                    <div class="penci-hover-box-img"
                         style="background-image: url('<?php echo esc_url( $slide_image ); ?>');"></div>
				<?php else : ?>
                    <div <?php $this->print_render_attribute_string( 'hover-box-content-img' ); ?>></div>
				<?php endif; ?>

            </div>
		<?php endforeach; ?>

		<?php
	}

	public function box_items() {
		$settings = $this->get_settings_for_display();
		$id       = $this->get_id();

		$this->add_render_attribute( 'box-settings', 'data-penci-hover-box-items', 'connect: #penci-hvb-box-content-' . esc_attr( $id ) . ';' );
		$this->add_render_attribute( 'box-settings', 'class', [
			'penci-hover-box-item-wrap',
			'penci-hvb-position-' . $settings['content_gap'],
			'penci-hvb-position-' . $settings['default_content_position']
		] );

		$text_hide_on_setup = '';

		if ( ! empty ( $settings['text_hide_on'] ) ) {
			foreach ( $settings['text_hide_on'] as $element ) {

				if ( $element == 'desktop' ) {
					$text_hide_on_setup .= ' penci-hvb-desktop';
				}
				if ( $element == 'tablet' ) {
					$text_hide_on_setup .= ' penci-hvb-tablet';
				}
				if ( $element == 'mobile' ) {
					$text_hide_on_setup .= ' penci-hvb-mobile';
				}
			}
		}


		?>
        <div <?php $this->print_render_attribute_string( 'box-settings' ); ?>>
            <div class="penci-hover-box-grid">

				<?php foreach ( $settings['hover_box'] as $index => $item ) :

				$tab_count = $index + 1;
				$tab_id    = 'penci-hvb-box-' . $tab_count . esc_attr( $id );


				$active_item = $this->activeItem( $settings['hover_box_active_item'], count( $settings['hover_box'] ) );

				if ( $tab_id == 'penci-hvb-box-' . $active_item . esc_attr( $id ) ) {
					$this->add_render_attribute( 'box-item', 'class', 'penci-hover-box-item active', true );
				} else {
					$this->add_render_attribute( 'box-item', 'class', 'penci-hover-box-item', true );
				}

				$this->add_render_attribute( 'penci-hover-box-title', 'class', 'penci-hover-box-title', true );

				$title_key  = 'title_' . $index;
				$button_key = 'button_' . $index;
				$this->add_render_attribute( $title_key, 'class', 'penci-hover-box-title-link', true );
				$this->add_link_attributes( $title_key, isset ( $item['title_link'] ) ? $item['title_link'] : [] );
				$this->add_link_attributes( $button_key, isset ( $item['button_link'] ) ? $item['button_link'] : [] );

				?>
                <div>
                    <div <?php $this->print_render_attribute_string( 'box-item' ); ?>
                            data-id="<?php echo esc_attr( $tab_id ); ?>">

						<?php if ( 'yes' == $settings['show_icon'] ) : ?>
                            <a class="penci-hover-box-icon-box" href="javascript:void(0);"
                               data-tab-index="<?php echo esc_attr( $index ); ?>">
									<span class="penci-hover-box-icon-wrap">
										<?php Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</span>
                            </a>
						<?php endif; ?>

						<?php if ( $item['hover_box_sub_title'] && ( 'yes' == $settings['show_sub_title'] ) ) : ?>
                            <div class="penci-hover-box-sub-title">
								<?php echo wp_kses( $item['hover_box_sub_title'], wp_kses_allowed_html( 'post' ) ); ?>
                            </div>
						<?php endif; ?>

						<?php if ( $item['hover_box_title'] && ( 'yes' == $settings['show_title'] ) ) : ?>
                        <<?php echo esc_attr( $settings['title_tags'] ); ?>
						<?php $this->print_render_attribute_string( 'penci-hover-box-title' ); ?>>

						<?php if ( '' !== $item['title_link']['url'] ) : ?>
                        <a <?php $this->print_render_attribute_string( $title_key ); ?>>
							<?php endif; ?>
							<?php echo wp_kses( $item['hover_box_title'], wp_kses_allowed_html( 'post' ) ); ?>
							<?php if ( '' !== $item['title_link']['url'] ) : ?>
                        </a>
					<?php endif; ?>

                    </<?php echo esc_attr( $settings['title_tags'] ); ?>>
					<?php endif; ?>

					<?php if ( $item['hover_box_content'] && ( 'yes' == $settings['show_content'] ) ) : ?>
                        <div class="penci-hover-box-text <?php echo esc_attr( $text_hide_on_setup ); ?>">
							<?php echo wp_kses_post( $this->parse_text_editor( $item['hover_box_content'] ) ); ?>
                        </div>
					<?php endif; ?>

					<?php if ( $item['hover_box_button'] && ( 'yes' == $settings['show_button'] ) ) : ?>
                        <div class="penci-hover-box-button">
                            <a <?php $this->print_render_attribute_string( $button_key ); ?>>
								<?php echo wp_kses_post( $item['hover_box_button'] ); ?>
                            </a>
                        </div>
					<?php endif; ?>

                </div>
            </div>
			<?php endforeach; ?>

        </div>
        </div>
		<?php
	}

	public function post_content() {
		$settings = $this->get_settings_for_display();
		$id       = $this->get_id();
		$args     = Query_Control::get_query_args( 'posts', $settings );;
		$query_custom = new \WP_Query( $args );
		$thumbnail_size = isset( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : 'full';
		?>

		<?php
		if ( $query_custom->have_posts() ):
			$post_count = 0;
			while ( $query_custom->have_posts() ) : $query_custom->the_post();
				$post_count++;
				$tab_id = 'penci-hvb-box-' . $post_count . esc_attr( $id );

				$slide_image = get_the_post_thumbnail_url( get_the_ID(), $thumbnail_size );
				if ( ! $slide_image ) {
					$slide_image = penci_get_default_thumbnail_url();
				}
				
				$active_item = $this->activeItem( $settings['hover_box_active_item'], $query_custom->found_posts );

				if ( $tab_id == 'penci-hvb-box-' . $active_item . esc_attr( $id ) ) {
					$this->add_render_attribute( 'hover-box-content', 'class', 'penci-hover-box-content active', true );
				} else {
					$this->add_render_attribute( 'hover-box-content', 'class', 'penci-hover-box-content', true );
				}

				$this->add_render_attribute( 'hover-box-content-img', 'class', 'penci-hover-box-img elementor-repeater-item-' . esc_attr( get_the_ID() ), true );

				?>

                <div id="<?php echo esc_attr( $tab_id ); ?>" <?php $this->print_render_attribute_string( 'hover-box-content' ); ?>>

					<?php if ( $slide_image ) : ?>
                        <div class="penci-hover-box-img"
                             style="background-image: url('<?php echo esc_url( $slide_image ); ?>');"></div>
					<?php else : ?>
                        <div <?php $this->print_render_attribute_string( 'hover-box-content-img' ); ?>></div>
					<?php endif; ?>

                </div>
			<?php
			endwhile;
		endif; ?>
		<?php
	}

	public function post_items() {
		$settings = $this->get_settings();
		$id   = $this->get_id();
		$args = Query_Control::get_query_args( 'posts', $settings );;
		$query_custom = new \WP_Query( $args );
		$post_meta    	= $settings['postmeta'] ? $settings['postmeta'] : [];
		$primary_cat    = $settings['primary_cat'];
		$this->add_render_attribute( 'box-settings', 'data-penci-hover-box-items', 'connect: #penci-hvb-box-content-' . esc_attr( $id ) . ';' );
		$this->add_render_attribute( 'box-settings', 'class', [
			'penci-hover-box-item-wrap',
			'penci-hvb-position-' . $settings['content_gap'],
			'penci-hvb-position-' . $settings['default_content_position']
		] );

		$text_hide_on_setup = '';

		if ( ! empty ( $settings['text_hide_on'] ) ) {
			foreach ( $settings['text_hide_on'] as $element ) {

				if ( $element == 'desktop' ) {
					$text_hide_on_setup .= ' penci-hvb-desktop';
				}
				if ( $element == 'tablet' ) {
					$text_hide_on_setup .= ' penci-hvb-tablet';
				}
				if ( $element == 'mobile' ) {
					$text_hide_on_setup .= ' penci-hvb-mobile';
				}
			}
		}
		?>
		<div <?php $this->print_render_attribute_string( 'box-settings' ); ?>>
		<div class="penci-hover-box-grid">
		<?php
		if ( $query_custom->have_posts() ) {
			$post_count = 0;
			while ( $query_custom->have_posts() ) : $query_custom->the_post();
				$post_count++;
				$tab_id = 'penci-hvb-box-' . $post_count . esc_attr( $id );
				$active_item = $this->activeItem( $settings['hover_box_active_item'], $query_custom->found_posts );

				if ( $tab_id == 'penci-hvb-box-' . $active_item . esc_attr( $id ) ) {
					$this->add_render_attribute( 'box-item', 'class', 'penci-hover-box-item active', true );
				} else {
					$this->add_render_attribute( 'box-item', 'class', 'penci-hover-box-item', true );
				}

				$this->add_render_attribute( 'penci-hover-box-title', 'class', 'penci-hover-box-title', true );
				?>
                <div>
                    <div <?php $this->print_render_attribute_string( 'box-item' ); ?>
                            data-id="<?php echo esc_attr( $tab_id ); ?>">
						<?php if ( 'yes' == $settings['show_icon'] ) : ?>
                            <a class="penci-hover-box-icon-box" href="javascript:void(0);"
                               data-tab-index="<?php echo esc_attr( get_the_ID() ); ?>">
								<span class="penci-hover-box-icon-wrap">
								<?php if ( has_post_format( 'gallery' ) ) : ?>
									<?php penci_fawesome_icon( 'far fa-image' ); ?>
								<?php elseif ( has_post_format( 'link' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-link' ); ?>
								<?php elseif ( has_post_format( 'quote' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-quote-left' ); ?>
								<?php elseif ( has_post_format( 'video' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-play' ); ?>
								<?php elseif ( has_post_format( 'audio' ) ) : ?>
									<?php penci_fawesome_icon( 'fas fa-music' ); ?>
								<?php else : ?>
									<?php penci_fawesome_icon( 'fas fa-file' ); ?>
								<?php endif; ?>
								</span>
                            </a>
						<?php endif; ?>

						<?php if ( 'yes' == $settings['show_post_meta'] ) : ?>
							<?php if ( ( isset( $settings['cspost_enable'] ) && $settings['cspost_enable'] ) || ( count( array_intersect( array(
										'author',
										'date',
										'comment',
										'views',
										'reading',
										'excerpt'
									), $post_meta ) ) > 0 ) ) { ?>
                                <div class="pchvb-meta">
                                    <div class="pcbg-meta-desc">
										<?php if ( in_array( 'cat', $post_meta ) ) : ?>
											<span class="bg-cat-meta">
												<?php penci_category( '', $primary_cat ); ?>
											</span>
										<?php endif; ?>
										<?php if ( in_array( 'author', $post_meta ) ) : ?>
                                            <span class="bg-date-author author-italic author vcard">
												<?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
													penci_coauthors_posts_links();
												else: ?>
                                                    <a class="author-url url fn n"
                                                       href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
												<?php endif; ?>
											</span>
										<?php endif; ?>
										<?php if ( in_array( 'date', $post_meta ) ) : ?>
                                            <span class="bg-date"><?php penci_soledad_time_link(); ?></span>
										<?php endif; ?>
										<?php if ( in_array( 'comment', $post_meta ) ) : ?>
                                            <span class="bg-comment">
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
                                            <span class="bg-readtime"><?php penci_reading_time(); ?></span>
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
							<?php } ?>
						<?php endif; ?>

						<?php if ( 'yes' == $settings['show_title'] ) : ?>
							<<?php echo esc_attr( $settings['title_tags'] ); ?>
							<?php $this->print_render_attribute_string( 'penci-hover-box-title' ); ?>>


							<a class="penci-hover-box-title-link" href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>


							</<?php echo esc_attr( $settings['title_tags'] ); ?>>
						<?php endif; ?>

					<?php if ( 'yes' == $settings['show_content'] ) : ?>
                        <div class="penci-hover-box-text">
							<?php penci_the_excerpt( $settings['post_content_length'] ); ?>
                        </div>
					<?php endif; ?>

					<?php if ( 'yes' == $settings['show_button'] ) : ?>
                        <div class="penci-hover-box-button">
                            <a href="<?php the_permalink(); ?>">
								<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>
                            </a>
                        </div>
					<?php endif; ?>
                </div>
                </div>
			<?php
			endwhile;
			wp_reset_postdata();
		} else {
			echo '<div class="penci-no-posts-found">' . esc_html__( 'No posts found', 'soledad' ) . '</div>';
		}
		?>
		</div></div>
		<?php
	}
}