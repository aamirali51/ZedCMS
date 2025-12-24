<?php

namespace PenciSoledadElementor\Modules\PenciProductList\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciProductList extends Base_Widget {

	public function get_name() {
		return 'penci-product-list';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Product List', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'list', 'post', 'small', 'slider', 'carousel', 'product' );
	}

	protected function register_controls() {


		// Section general
		$this->start_controls_section(
			'section_type', array(
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'type',
			array(
				'label'   => __( 'Type:', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'grid' => 'Grid',
					'crs'  => 'Carousel',
				),
				'default' => 'grid',
			)
		);

		$this->add_control(
			'column',
			array(
				'label'   => __( 'Columns', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'default' => '3',
			)
		);

		$this->add_control(
			'tab_column',
			array(
				'label'   => __( 'Columns on Tablet', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''  => 'Default',
					'1' => '1',
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
				'label'   => __( 'Columns on Mobile', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''  => 'Default',
					'1' => '1',
					'2' => '2',
					'3' => '3',
				),
				'default' => '',
			)
		);

		$this->add_responsive_control(
			'hgap', array(
				'label'     => __( 'Horizontal Space Between Products', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-smalllist' => '--pcsl-hgap: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'vgap', array(
				'label'     => __( 'Vertical Space Between Products', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-smalllist' => '--pcsl-bgap: {{SIZE}}px;',
				),
				'condition' => array( 'type' => array( 'grid' ) ),
			)
		);

		$this->add_responsive_control(
			'imggap', array(
				'label'     => __( 'Space Between Image & Content', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .penci-smalllist' => '--pcsl-between: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'vertical_position',
			array(
				'label'                => __( 'Vertical Align', 'soledad' ),
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
					'{{WRAPPER}} .pcsl-inner .pcsl-iteminer' => 'align-items: {{VALUE}}',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
			)
		);

		$this->add_control(
			'text_align', array(
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
					'{{WRAPPER}} .pcsl-content, {{WRAPPER}} .pcsl-flex-full' => 'text-align: {{VALUE}}'
				)
			)
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'product_query_section',
			[
				'label' => esc_html__( 'Product Query', 'soledad' ),
			]
		);

		$this->add_control(
			'post_type',
			[
				'label'       => esc_html__( 'Data source', 'soledad' ),
				'description' => esc_html__( 'Select content type for your grid.', 'soledad' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'product',
				'options'     => array(
					'product'            => esc_html__( 'All Products', 'soledad' ),
					'featured'           => esc_html__( 'Featured Products', 'soledad' ),
					'sale'               => esc_html__( 'Sale Products', 'soledad' ),
					'new'                => esc_html__( 'Products with NEW label', 'soledad' ),
					'bestselling'        => esc_html__( 'Bestsellers', 'soledad' ),
					'ids'                => esc_html__( 'List of IDs', 'soledad' ),
					'top_rated_products' => esc_html__( 'Top Rated Products', 'soledad' ),
					'recent_viewed'      => esc_html__( 'Recent Viewed Products', 'soledad' ),
				),
			]
		);

		$this->add_control(
			'include',
			[
				'label'       => esc_html__( 'Include only', 'soledad' ),
				'description' => esc_html__( 'Add products by title.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_posts_by_query',
				'render'      => 'penci_get_posts_title_by_id',
				'post_type'   => 'product',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'post_type' => 'ids',
				],
			]
		);

		$this->add_control(
			'taxonomies',
			[
				'label'       => esc_html__( 'Categories or tags', 'soledad' ),
				'description' => esc_html__( 'List of product categories.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_taxonomies_by_query',
				'render'      => 'penci_get_taxonomies_title_by_id',
				'taxonomy'    => array_merge( [ 'product_cat', 'product_tag' ], $this->get_product_attributes_array() ),
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'post_type!' => 'ids',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'       => esc_html__( 'Order by', 'soledad' ),
				'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'soledad' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''               => '',
					'date'           => esc_html__( 'Date', 'soledad' ),
					'id'             => esc_html__( 'ID', 'soledad' ),
					'author'         => esc_html__( 'Author', 'soledad' ),
					'title'          => esc_html__( 'Title', 'soledad' ),
					'modified'       => esc_html__( 'Last modified date', 'soledad' ),
					'comment_count'  => esc_html__( 'Number of comments', 'soledad' ),
					'menu_order'     => esc_html__( 'Menu order', 'soledad' ),
					'meta_value'     => esc_html__( 'Meta value', 'soledad' ),
					'meta_value_num' => esc_html__( 'Meta value number', 'soledad' ),
					'rand'           => esc_html__( 'Random order', 'soledad' ),
					'price'          => esc_html__( 'Price', 'soledad' ),
				),
			]
		);

		$this->add_control(
			'items_per_page',
			[
				'label'       => esc_html__( 'Products Per Page', 'soledad' ),
				'description' => esc_html__( 'Number of products you want to show in this list.', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
			]
		);

		$this->add_control(
			'offset',
			[
				'label'       => esc_html__( 'Offset', 'soledad' ),
				'description' => esc_html__( 'Number of grid elements to displace or pass over.', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'condition'   => [
					'post_type!' => 'ids',
				],
			]
		);

		$this->add_control(
			'query_type',
			[
				'label'   => esc_html__( 'Query type', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'OR',
				'options' => array(
					'OR'  => esc_html__( 'OR', 'soledad' ),
					'AND' => esc_html__( 'AND', 'soledad' ),
				),
			]
		);

		$this->add_control(
			'order',
			[
				'label'       => esc_html__( 'Sort order', 'soledad' ),
				'description' => 'Designates the ascending or descending order. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.',
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''     => esc_html__( 'Inherit', 'soledad' ),
					'DESC' => esc_html__( 'Descending', 'soledad' ),
					'ASC'  => esc_html__( 'Ascending', 'soledad' ),
				),
				'condition'   => [
					'post_type!' => 'ids',
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label'       => esc_html__( 'Meta key', 'soledad' ),
				'description' => esc_html__( 'Input meta key for grid ordering.', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'condition'   => [
					'orderby' => [ 'meta_value', 'meta_value_num' ],
				],
			]
		);

		$this->add_control(
			'exclude',
			[
				'label'       => esc_html__( 'Exclude', 'soledad' ),
				'description' => esc_html__( 'Exclude posts, pages, etc. by title.', 'soledad' ),
				'type'        => 'penci_el_autocomplete',
				'search'      => 'penci_get_posts_by_query',
				'render'      => 'penci_get_posts_title_by_id',
				'post_type'   => 'product',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'post_type!' => 'ids',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_image', array(
				'label' => esc_html__( 'Image', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'hide_thumb', array(
				'label'        => __( 'Hide Product Image?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'imgpos',
			array(
				'label'   => __( 'Image Position', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'left'  => 'Left',
					'right' => 'Right',
					'top'   => 'Top',
				),
				'default' => 'left',
			)
		);

		$this->add_responsive_control(
			'imgwidth',
			[
				'label'      => __( 'Image Width', 'soledad' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 600,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 95,
						'step' => 0.1,
					],
				],
				'default'    => [
					'unit' => '%',
				],
				'selectors'  => [
					'{{WRAPPER}} .pcsl-inner .pcsl-thumb'                                                       => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pcsl-imgpos-left .pcsl-content, {{WRAPPER}} .pcsl-imgpos-right .pcsl-content' => 'width: calc( 100% - {{SIZE}}{{UNIT}} );',
				],
			]
		);

		$this->add_control(
			'image_align',
			array(
				'label'                => __( 'Image Align', 'soledad' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
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
				'selectors'            => array(
					'{{WRAPPER}} .pcsl-inner.pcsl-imgpos-top .pcsl-thumb' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'left'   => 'marin-right: auto;',
					'center' => 'margin-left: auto; margin-right: auto;',
					'right'  => 'margin-left: auto;',
				),
				'conditions'           => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'imgpos',
							'operator' => '==',
							'value'    => 'top'
						],
						[
							'name'     => 'imgwidth[size]',
							'operator' => '!=',
							'value'    => ''
						]
					]
				]
			)
		);

		$this->add_responsive_control(
			'img_ratio', array(
				'label'     => __( 'Image Ratio', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 300, 'step' => 0.5 ) ),
				'selectors' => array(
					'{{WRAPPER}} .pcsl-inner .penci-image-holder:before' => 'padding-top: {{SIZE}}%;',
				),
				'condition' => array( 'nocrop!' => 'yes' ),
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
			'thumb_size', array(
				'label'   => __( 'Image Size', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_list_image_sizes( true ),
			)
		);

		$this->add_control(
			'mthumb_size', array(
				'label'   => __( 'Image Size for Mobile', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_list_image_sizes( true ),
			)
		);

		$this->add_control(
			'nocrop', array(
				'label'        => __( 'No Crop Image?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'To make it works, you need to select Image Size above is "Penci Masonry Thumb" or "Penci Full Thumb" or "Full"', 'soledad' ),
			)
		);

		$this->add_control(
			'imgtop_mobile', array(
				'label'        => __( 'Move Image Above The Post Meta on Mobile?', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soledad' ),
				'label_off'    => __( 'No', 'soledad' ),
				'return_value' => 'yes',
				'condition'    => array( 'imgpos' => array( 'left', 'right' ) ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_postmeta', array(
				'label' => esc_html__( 'Product Meta Data', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'product_price', array(
				'label'        => __( 'Hide Product Price', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'soledad' ),
				'label_off'    => __( 'Off', 'soledad' ),
				'return_value' => 'on',
				'default'      => '',
			)
		);

		$this->add_control(
			'product_cat', array(
				'label'        => __( 'Hide Product Categories', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'soledad' ),
				'label_off'    => __( 'Off', 'soledad' ),
				'return_value' => 'on',
				'default'      => '',
			)
		);

		$this->add_control(
			'product_rating', array(
				'label'        => __( 'Hide Product Rating', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'soledad' ),
				'label_off'    => __( 'Off', 'soledad' ),
				'return_value' => 'on',
				'default'      => '',
			)
		);

		$this->add_control(
			'title_length', array(
				'label'   => __( 'Custom Product Title Words Length', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'default' => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_carousel_options',
			array(
				'label'     => __( 'Carousel Options', 'soledad' ),
				'condition' => array( 'type' => 'crs' ),
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

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'General', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'item_bg',
			array(
				'label'     => __( 'Product Items Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcsl-itemin' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'item_padding', array(
				'label'      => __( 'Product Items Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcsl-itemin' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_control(
			'item_borders',
			array(
				'label'     => __( 'Add Borders for Product Items', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcsl-itemin' => 'border: 1px solid {{VALUE}};' ),
			)
		);

		$this->add_control(
			'remove_border_last', array(
				'label'     => __( 'Remove Border Bottom On Last Item', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'soledad' ),
				'label_off' => __( 'No', 'soledad' ),
				'selectors' => array(
					'{{WRAPPER}} .pcsl-col-1 .pcsl-item:last-child .pcsl-itemin' => 'padding-bottom: 0; border-bottom: none;'
				),
				'condition' => array( 'column' => '1', 'item_borders!' => '' ),
			)
		);

		$this->add_responsive_control(
			'item_bordersw', array(
				'label'      => __( 'Products Items Borders Width', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcsl-itemin' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				),
			)
		);

		$this->add_responsive_control(
			'side_padding', array(
				'label'      => __( 'Padding for Side Content of Image', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcsl-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'
				),
			)
		);

		$this->add_control(
			'heading_pcat',
			array(
				'label'     => __( 'Product Categories', 'soledad' ),
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
				'selectors' => array( '{{WRAPPER}} .penci-product-cats,{{WRAPPER}} .penci-product-cats > a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'cat_hcolor',
			array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-product-cats > a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'cat_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-product-cats,{{WRAPPER}} .penci-product-cats > a',
			)
		);

		$this->add_control(
			'heading_ptitle',
			array(
				'label'     => __( 'Product Title', 'soledad' ),
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
				'selectors' => array( '{{WRAPPER}} .pcsl-content .pcsl-title a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'title_hcolor',
			array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcsl-content .pcsl-title a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'title_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pcsl-content .pcsl-title',
			)
		);

		$this->add_control(
			'price_ptitle',
			array(
				'label'     => __( 'Product Price', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcsl-content .pcsl-price span' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'price_hcolor',
			array(
				'label'     => __( 'Sale Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .pcsl-content .pcsl-price span del,{{WRAPPER}} .pcsl-content .pcsl-price span del span' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'price_typo',
				'label'    => __( 'Typography', 'soledad' ),
				'selector' => '{{WRAPPER}} .pcsl-content .pcsl-price',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_spacing',
			array(
				'label' => __( 'Elements Spacing', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'cat_space', array(
				'label'     => __( 'Categories Margin Bottom', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .pcsl-inner .pcsl-content .cat' => 'margin-bottom: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'meta_space', array(
				'label'     => __( 'Product Price Margin Top', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
				'selectors' => array(
					'{{WRAPPER}} .pcsl-inner .pcsl-price' => 'margin-top: {{SIZE}}px;',
				),
			)
		);

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

	}

	/**
	 * Get attribute taxonomies
	 *
	 * @since 1.0.0
	 */
	public function get_product_attributes_array() {
		$attributes = [];

		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			foreach ( wc_get_attribute_taxonomies() as $attribute ) {
				$attributes[] = 'pa_' . $attribute->attribute_name;
			}
		}

		return $attributes;
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

		$type              = $settings['type'] ? $settings['type'] : '';
		$column            = $settings['column'] ? $settings['column'] : '3';
		$tab_column        = $settings['tab_column'] ? $settings['tab_column'] : '2';
		$mb_column         = $settings['mb_column'] ? $settings['mb_column'] : '1';
		$imgpos            = $settings['imgpos'] ? $settings['imgpos'] : 'left';
		$thumb_size_imgtop = 'top' == $imgpos ? 'penci-thumb' : 'penci-thumb-small';
		$thumb_size        = $settings['thumb_size'] ? $settings['thumb_size'] : $thumb_size_imgtop;
		$mthumb_size       = $settings['mthumb_size'] ? $settings['mthumb_size'] : $thumb_size_imgtop;
		$title_length      = $settings['title_length'] ? $settings['title_length'] : '';

		$lazyload =  $settings['disable_lazy'] == 'yes' ? 'false' : 'true';

		$thumbnail = $thumb_size;
		if ( penci_is_mobile() ) {
			$thumbnail = $mthumb_size;
		}

		$item_class = 'normal-item';

		$inner_wrapper_class = 'pcsl-inner penci-clearfix';
		$inner_wrapper_class .= ' pcsl-' . $type;
		if ( 'crs' == $type ) {
			$item_class          = 'swiper-slide';
			$inner_wrapper_class .= ' swiper penci-owl-carousel penci-owl-carousel-slider';
		}
		$inner_wrapper_class .= ' pcsl-imgpos-' . $imgpos;
		$inner_wrapper_class .= ' pcsl-col-' . $column;
		$inner_wrapper_class .= ' pcsl-tabcol-' . $tab_column;
		$inner_wrapper_class .= ' pcsl-mobcol-' . $mb_column;
		if ( 'yes' == $settings['nocrop'] ) {
			$inner_wrapper_class .= ' pcsl-nocrop';
		}
		$data_slider = '';
		if ( 'crs' == $type ) {
			$data_slider .= $settings['showdots'] ? ' data-dots="true"' : ' data-dots="false"';
			$data_slider .= $settings['shownav'] ? ' data-nav="true"' : ' data-nav="false"';
			$data_slider .= ! $settings['loop'] ? ' data-loop="true"' : '';
			$data_slider .= ' data-auto="' . ( 'yes' == $settings['autoplay'] ? 'true' : 'false' ) . '"';
			$data_slider .= $settings['auto_time'] ? ' data-autotime="' . $settings['auto_time'] . '"' : ' data-autotime="4000"';
			$data_slider .= $settings['speed'] ? ' data-speed="' . $settings['speed'] . '"' : ' data-speed="600"';

			$data_slider .= ' data-item="' . ( isset( $settings['column'] ) && $settings['column'] ? $settings['column'] : '3' ) . '"';
			$data_slider .= ' data-desktop="' . ( isset( $settings['column'] ) && $settings['column'] ? $settings['column'] : '3' ) . '" ';
			$data_slider .= ' data-tablet="' . ( isset( $settings['tab_column'] ) && $settings['tab_column'] ? $settings['tab_column'] : '2' ) . '"';
			$data_slider .= ' data-tabsmall="' . ( isset( $settings['tab_column'] ) && $settings['tab_column'] ? $settings['tab_column'] : '2' ) . '"';
			$data_slider .= ' data-mobile="' . ( isset( $settings['mb_column'] ) && $settings['mb_column'] ? $settings['mb_column'] : '1' ) . '"';
			$data_slider .= ' data-ceffect="' . $settings['carousel_slider_effect'] . '"';
		}

		// product query

		$settings = wp_parse_args( $settings, penci_custom_product_query_default_args() );

		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		if ( isset( $_GET['product-page'] ) ) { // phpcs:ignore
			$paged = wc_clean( wp_unslash( $_GET['product-page'] ) ); // phpcs:ignore
		}

		// Query settings.
		$ordering_args = WC()->query->get_catalog_ordering_args( $settings['orderby'], $settings['order'] );

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'paged'               => $paged,
			'orderby'             => $ordering_args['orderby'],
			'order'               => $ordering_args['order'],
			'posts_per_page'      => $settings['items_per_page'],
			'meta_query'          => WC()->query->get_meta_query(), // phpcs:ignore
			'tax_query'           => WC()->query->get_tax_query(), // phpcs:ignore
		);

		if ( 'new' === $settings['post_type'] ) {
			$new_label = get_theme_mod( 'penci_woo_label_new_product', true );
			$days      = get_theme_mod( 'penci_woo_label_new_product_period', 7 );
			if ( $new_label && $days ) {
				$query_args['date_query'] = array(
					'after' => date( 'Y-m-d', strtotime( '-' . $days . ' days' ) ),
				);
			} else {
				$query_args['meta_query'][] = array(
					array(
						'key'     => 'penci_pmeta_product_extra_options',
						'value'   => '"permanent_new_label";s:1:"1"',
						'compare' => 'LIKE',
					),
				);
			}
		}

		if ( isset( $settings['search_query'] ) && ! empty( $settings['search_query'] ) ) {
			$query_args['s'] = esc_attr( $settings['search_query'] );
		}

		if ( $ordering_args['meta_key'] ) {
			$query_args['meta_key'] = $ordering_args['meta_key']; // phpcs:ignore
		}
		if ( $settings['meta_key'] ) {
			$query_args['meta_key'] = $settings['meta_key']; // phpcs:ignore
		}
		if ( 'ids' === $settings['post_type'] && $settings['include'] ) {
			$query_args['post__in'] = $settings['include'];
		}
		if ( $settings['exclude'] ) {
			$query_args['post__not_in'] = $settings['exclude'];
		}
		if ( $settings['taxonomies'] ) {
			$taxonomy_names = get_object_taxonomies( 'product' );
			$terms          = get_terms(
				$taxonomy_names,
				array(
					'orderby'    => 'name',
					'include'    => $settings['taxonomies'],
					'hide_empty' => false,
				)
			);

			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				if ( 'featured' === $settings['post_type'] ) {
					$query_args['tax_query'] = [ 'relation' => 'AND' ]; // phpcs:ignore
				}

				$relation = $settings['query_type'] ? $settings['query_type'] : 'OR';
				if ( count( $terms ) > 1 ) {
					$query_args['tax_query']['categories'] = array( 'relation' => $relation );
				}

				foreach ( $terms as $term ) {
					$query_args['tax_query']['categories'][] = array(
						'taxonomy'         => $term->taxonomy,
						'field'            => 'slug',
						'terms'            => array( $term->slug ),
						'include_children' => true,
						'operator'         => 'IN',
					);
				}
			}
		}
		if ( 'featured' === $settings['post_type'] ) {
			$query_args['tax_query'][] = array(
				'taxonomy'         => 'product_visibility',
				'field'            => 'name',
				'terms'            => 'featured',
				'operator'         => 'IN',
				'include_children' => false,
			);
		}
		if ( apply_filters( 'penci_hide_out_of_stock_items', false ) && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$query_args['meta_query'][] = array(
				'key'     => '_stock_status',
				'value'   => 'outofstock',
				'compare' => 'NOT IN',
			);
		}
		if ( $settings['order'] ) {
			$query_args['order'] = $settings['order'];
		}
		if ( $settings['offset'] ) {
			$query_args['offset'] = $settings['offset'];
		}
		if ( 'sale' === $settings['post_type'] ) {
			$query_args['post__in'] = array_merge( array( 0 ), penci_get_product_ids_on_sale() );
		}
		if ( 'bestselling' === $settings['post_type'] ) {
			$query_args['orderby']  = 'meta_value_num';
			$query_args['meta_key'] = 'total_sales'; // phpcs:ignore
			$query_args['order']    = 'DESC';
		}

		WC()->query->remove_ordering_args();

		if ( isset( $_GET['orderby'] ) && $settings['header_tools'] ) { // phpcs:ignore
			$element_orderby = wc_clean( wp_unslash( $_GET['orderby'] ) ); // phpcs:ignore

			if ( 'date' === $element_orderby ) {
				$query_args['orderby'] = 'date';
				$query_args['order']   = 'DESC';
			} elseif ( 'price-desc' === $element_orderby ) {
				$query_args['orderby'] = 'price';
				$query_args['order']   = 'DESC';
			} else {
				$query_args['orderby'] = $element_orderby;
				$query_args['order']   = 'ASC';
			}
		}

		if ( 'price' === $query_args['orderby'] ) {
			$query_args['orderby']  = 'meta_value_num';
			$query_args['meta_key'] = '_price'; // phpcs:ignore
		}

		if ( isset( $_GET['per_page'] ) && $settings['header_tools'] ) { // phpcs:ignore
			$query_args['posts_per_page'] = wc_clean( wp_unslash( $_GET['per_page'] ) ); // phpcs:ignore
		}

		if ( isset( $settings['product_type'] ) && in_array(
				$settings['product_type'],
				array(
					'day',
					'week',
					'month',
				)
			) ) {
			$date = '+1 day';
			if ( $settings['product_type'] == 'week' ) {
				$date = '+7 day';
			} elseif ( $settings['product_type'] == 'month' ) {
				$date = '+1 month';
			}
			$query_args['meta_query'] = apply_filters(
				'penci_product_deals_meta_query',
				array_merge(
					WC()->query->get_meta_query(),
					array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => strtotime( $date ),
							'compare' => '<=',
						),
					)
				)
			);
		} elseif ( isset( $settings['product_type'] ) && $settings['product_type'] == 'deals' ) {
			$query_args['meta_query'] = apply_filters(
				'penci_product_deals_meta_query',
				array_merge(
					WC()->query->get_meta_query(),
					array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						),
					)
				)
			);
		}

		if ( 'top_rated_products' === $settings['post_type'] ) {
			add_filter( 'posts_clauses', 'penci_order_by_rating_post_clauses' );
			$products = new \WP_Query( apply_filters( 'penci_product_element_query_args', $query_args ) );
			remove_filter( 'posts_clauses', 'penci_order_by_rating_post_clauses' );
		} else {
			$products = new \WP_Query( apply_filters( 'penci_product_element_query_args', $query_args ) );
		}
		?>
        <div class="penci-wrapper-smalllist">
			<?php $this->markup_block_title( $settings, $this ); ?>
			<?php
			if ( ! $products->have_posts() ) {
				echo $this->show_missing_settings( 'Product List', penci_get_setting( 'penci_ajaxsearch_no_post' ) );
			}

			if ( $products->have_posts() ) {
				?>
                <div class="penci-smalllist pcsl-wrapper">
                    <div class="<?php echo $inner_wrapper_class; ?>"<?php echo $data_slider; ?>>
						<?php if ( 'crs' == $type ) : ?>
                        <div class="swiper-wrapper">
							<?php endif; ?>
							<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                                <div class="pcsl-item<?php if ( 'yes' == $settings['hide_thumb'] || ! has_post_thumbnail() ) {
									echo ' pcsl-nothumb';
								}
								echo ' ' . $item_class; ?>">
                                    <div class="pcsl-itemin">
                                        <div class="pcsl-iteminer">
											<?php if ( 'yes' != $settings['hide_thumb'] && has_post_thumbnail() ) { ?>
                                                <div class="pcsl-thumb">

                                                    <a <?php echo penci_layout_bg( penci_get_image_size_url( penci_image_srcset( get_the_ID(), $thumbnail ) ), $lazyload ); ?> href="<?php the_permalink(); ?>"
                                                       class="<?php echo penci_layout_bg_class( $lazyload );?> penci-image-holder"<?php if ( 'yes' == $settings['nocrop'] ) {
														echo ' style="padding-bottom: ' . penci_get_featured_image_padding_markup( get_the_ID(), $thumbnail, true ) . '%"';
													} ?>>
														<?php echo penci_layout_img( penci_get_image_size_url( penci_image_srcset( get_the_ID(), $thumbnail ) ), get_the_title(), $lazyload ); ?>
                                                    </a>

                                                </div>
											<?php } ?>
                                            <div class="pcsl-content">

												<?php if ( $settings['product_cat'] !== 'on' ): ?>
                                                    <div class="cat pcsl-cat">
														<?php $this->product_categories_link(); ?>
                                                    </div>
												<?php endif; ?>

                                                <div class="pcsl-title">
                                                    <a href="<?php the_permalink(); ?>"><?php

														if ( ! $title_length ) {
															the_title();
														} else {
															echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $title_length, '...' );
														} ?></a>
                                                </div>

												<?php if ( $settings['product_price'] !== 'on' ): ?>

                                                    <div class="pcsl-price">
														<?php woocommerce_template_loop_price(); ?>
                                                    </div>
												<?php endif; ?>

												<?php if ( $settings['product_rating'] !== 'on' ): ?>
                                                    <div class="pcsl-rating">
														<?php woocommerce_template_loop_rating(); ?>
                                                    </div>
												<?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							<?php endwhile; ?>
							<?php if ( 'crs' == $type ) : ?>
                        </div>
					<?php endif; ?>
                    </div>
                </div>
				<?php
			} /* End check if query exists posts */
			?>
        </div>
		<?php
		wp_reset_postdata();
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

	public function product_categories_link() {
		global $post;

		$terms = get_the_terms( $post->ID, 'product_cat' );

		if ( ! $terms ) {
			return;
		}

		$terms_array = array();
		$parent      = array();
		$child       = array();
		$links       = array();

		foreach ( $terms as $term ) {
			$terms_array[ $term->term_id ] = $term;

			if ( ! $term->parent ) {
				$parent[ $term->term_id ] = $term->name;
			}
		}

		foreach ( $terms as $term ) {
			if ( $term->parent ) {
				unset( $parent[ $term->parent ] );

				if ( array_key_exists( $term->parent, $terms_array ) ) {
					$child[ $term->parent ] = get_term( $term->parent )->name;
				}

				$child[ $term->term_id ] = $term->name;
			}
		}

		$terms = $child + $parent;

		foreach ( $terms as $key => $value ) {
			$links[] = '<a href="' . esc_url( get_term_link( $key ) ) . '" rel="tag">' . esc_html( $value ) . '</a>';
		}

		?>
        <div class="penci-product-cats">
			<?php echo implode( ', ', $links ); // phpcs:ignore ?>
        </div>
		<?php
	}
}
