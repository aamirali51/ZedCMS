<?php

namespace PenciSoledadElementor\Modules\PenciMultiTaxonomiesPostsFilter\Widgets;

use Elementor\Controls_Manager;
use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Group_Control_Typography;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciMultiTaxonomiesPostsFilter extends Base_Widget {

	public function get_name() {
		return 'penci-multi-taxonomies-posts-filter';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Multi Taxonomies Posts Filter', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'posts', 'filter', 'penci', 'taxonomies' );
	}

	public function get_script_depends() {
		return [ 'mtp-filters' ];
	}

	protected function register_controls() {

		$this->start_controls_section( 'section_general_content', array(
			'label' => __( 'General Layout', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'tax_position', array(
			'label'   => __( 'Taxonomy Posistion', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'left',
			'options' => [
				'left'  => __( 'Left', 'soledad' ),
				'right' => __( 'Right', 'soledad' ),
			],
		) );

		$this->add_control( 'tax_sticky', array(
			'label'     => __( 'Enable Sticky', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
		) );

		$this->add_responsive_control( 'tax_sizes', array(
			'label'      => __( 'Taxonomy List Column Size', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( '%' ),
			'range'      => array( '%' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-mtp-filters-wrapper' => '--sidebar: {{SIZE}}%;'
			)
		) );

		$this->add_responsive_control( 'tax_spacing', array(
			'label'     => __( 'Spacing Between Taxonomy List & Content', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-wrapper' => '--spacing: {{SIZE}}px;'
			)
		) );

		$this->end_controls_section();


		$this->start_controls_section( 'section_general', array(
			'label' => esc_html__( 'Taxonomies', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$tax_options = [];

		foreach ( get_object_taxonomies( 'post', 'object' ) as $tax_name => $tax_info ) {
			if ( ! in_array( $tax_name, [ 'post_format', 'elementor_library_type', 'penci_block_category' ] ) ) {
				$tax_options[ $tax_name ] = $tax_info->label;
			}
		}

		$this->add_control( 'tax', array(
			'label'   => __( 'Taxonomy', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $tax_options,
			'default' => 'category',
		) );

		$this->add_control( 'taxonomies_ex', [
			'label'       => esc_html__( 'Select the Excluded Taxonomies Terms.', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_taxonomies_by_query',
			'render'      => 'penci_get_taxonomies_title_by_id',
			'taxonomy'    => get_object_taxonomies( 'post' ),
			'multiple'    => true,
			'label_block' => true,
		] );

		$this->add_control( 'taxonomies_in', [
			'label'       => esc_html__( 'Select the Included Taxonomies Terms.', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_taxonomies_by_query',
			'render'      => 'penci_get_taxonomies_title_by_id',
			'taxonomy'    => get_object_taxonomies( 'post' ),
			'multiple'    => true,
			'label_block' => true,
		] );

		$this->add_control( 'orderby', array(
			'label'   => __( 'Order By', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'term_id',
			'options' => [
				'term_id' => __( 'ID', 'soledad' ),
				'name'    => __( 'Name', 'soledad' ),
				'slug'    => __( 'Slug', 'soledad' ),
				'count'   => __( 'Count', 'soledad' ),
			],
		) );

		$this->add_control( 'order', array(
			'label'   => __( 'Order', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'DESC',
			'options' => [
				'ASC'  => __( 'ASC', 'soledad' ),
				'DESC' => __( 'DESC', 'soledad' ),
			],
		) );

		$this->add_control( 'count', array(
			'label' => __( 'Show posts count?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_control( 'maxitems', array(
			'label'   => __( 'Limit Terms to Show', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 10,
		) );

		$this->add_control( 'ajax_loading_style', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__( 'Loading Icon Style', 'soledad' ),
			'default' => 'df',
			'options' => [
				'df' => __( 'Follow Customize', 'soledad' ),
				's9' => __( 'Style 1', 'soledad' ),
				's2' => __( 'Style 2', 'soledad' ),
				's3' => __( 'Style 3', 'soledad' ),
				's4' => __( 'Style 4', 'soledad' ),
				's5' => __( 'Style 5', 'soledad' ),
				's6' => __( 'Style 6', 'soledad' ),
				's1' => __( 'Style 7', 'soledad' ),
			],
			//'label_block' => true,
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_post_listing', array(
			'label' => esc_html__( 'Post Settings', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'post_orderby', array(
			'label'   => __( 'Order By', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'date',
			'options' => [
				'date'          => __( 'Published Date', 'soledad' ),
				'ID'            => __( 'Post ID', 'soledad' ),
				'modified'      => __( 'Modified Date', 'soledad' ),
				'title'         => __( 'Post Title', 'soledad' ),
				'rand'          => __( 'Random Posts', 'soledad' ),
				'comment_count' => __( 'Comment Count', 'soledad' ),
				'most_liked' 	=> __( 'Most Liked', 'soledad' ),
				'popular'       => __( 'Most Viewed Posts All Time', 'soledad' ),
				'popular_day'   => __( 'Most Viewed Posts Daily', 'soledad' ),
				'popular7'      => __( 'Most Viewed Posts Once Weekly', 'soledad' ),
				'popular_month' => __( 'Most Viewed Posts Once a Month', 'soledad' ),
			],
		) );

		$this->add_control( 'post_order', array(
			'label'   => __( 'Order', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'DESC',
			'options' => [
				'ASC'  => __( 'ASC', 'soledad' ),
				'DESC' => __( 'DESC', 'soledad' ),
			],
		) );

		$this->add_control( 'posts_per_page', array(
			'label'   => __( 'Posts Per Page', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 10,
		) );

		$this->add_control( 'column', array(
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
			'default' => '1',
		) );

		$this->add_control( 'tab_column', array(
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
		) );

		$this->add_control( 'mb_column', array(
			'label'   => __( 'Columns on Mobile', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				''  => 'Default',
				'1' => '1',
				'2' => '2',
				'3' => '3',
			),
			'default' => '',
		) );

		$this->add_responsive_control( 'hgap', array(
			'label'     => __( 'Horizontal Space Between Posts', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-smalllist' => '--pcsl-hgap: {{SIZE}}px;',
			),
		) );

		$this->add_responsive_control( 'vgap', array(
			'label'     => __( 'Vertical Space Between Posts', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-smalllist' => '--pcsl-bgap: {{SIZE}}px;',
			),
		) );

		$this->add_responsive_control( 'imggap', array(
			'label'     => __( 'Space Between Image & Content', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-smalllist' => '--pcsl-between: {{SIZE}}px;',
			),
		) );

		$this->add_control( 'vertical_position', array(
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
			'render_type'          => 'template',
			'selectors'            => array(
				'{{WRAPPER}} .pcsl-inner .pcsl-iteminer' => 'align-items: {{VALUE}}',
			),
			'selectors_dictionary' => array(
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			),
		) );

		$this->add_control( 'text_align', array(
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
		) );

		$this->add_control( 'paging', array(
			'label'       => __( 'Page Navigation Style', 'soledad' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'none',
			'options'     => array(
				'none'     => esc_html__( 'None', 'soledad' ),
				'loadmore' => esc_html__( 'Load More Posts Button', 'soledad' ),
				'scroll'   => esc_html__( 'Infinite Scroll', 'soledad' ),
			),
			'description' => __( 'Load More Posts Button & Infinite Scroll just works on frontend only.', 'soledad' ),
		) );

		$this->add_control( 'paging_align', array(
			'label'     => __( 'Page Navigation Align', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'align-center',
			'options'   => array(
				'align-center' => esc_html__( 'Center', 'soledad' ),
				'align-left'   => esc_html__( 'Left', 'soledad' ),
				'align-right'  => esc_html__( 'Right', 'soledad' ),
			),
			'condition' => array( 'paging!' => [ 'none', 'nextprev' ] ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_image', array(
			'label' => esc_html__( 'Image', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'hide_thumb', array(
			'label'        => __( 'Hide Featured Image?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'imgpos', array(
			'label'   => __( 'Image Position', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'left'  => 'Left',
				'right' => 'Right',
				'top'   => 'Top',
			),
			'default' => 'left',
		) );

		$this->add_responsive_control( 'imgwidth', [
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
				'{{WRAPPER}} .pcsl-inner .pcsl-thumb'                                                                             => 'width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .pcsl-imgpos-left .pcsl-content, {{WRAPPER}} .pcsl-imgpos-right .pcsl-content'                       => 'width: calc( 100% - {{SIZE}}{{UNIT}} );',
				'{{WRAPPER}} .pcsl-imgpos-left.pcsl-hdate .pcsl-content, {{WRAPPER}} .pcsl-imgpos-right.pcsl-hdate .pcsl-content' => 'width: calc( 100% - var(--pcsl-dwidth) - {{SIZE}}{{UNIT}} );',
			],
		] );

		$this->add_control( 'image_align', array(
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
		) );

		$this->add_responsive_control( 'img_ratio', array(
			'label'     => __( 'Image Ratio', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 300, 'step' => 0.5 ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcsl-inner .penci-image-holder:before' => 'padding-top: {{SIZE}}%;',
			),
			'condition' => array( 'nocrop!' => 'yes' ),
		) );

		$this->add_control( 'disable_lazy', array(
			'label'        => __( 'Disable Lazyload Images?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'thumb_size', array(
			'label'   => __( 'Image Size', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => $this->get_list_image_sizes( true ),
		) );

		$this->add_control( 'mthumb_size', array(
			'label'   => __( 'Image Size for Mobile', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => $this->get_list_image_sizes( true ),
		) );

		$this->add_control( 'nocrop', array(
			'label'        => __( 'No Crop Image?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'description'  => __( 'To make it works, you need to select Image Size above is "Penci Masonry Thumb" or "Penci Full Thumb" or "Full"', 'soledad' ),
		) );

		$this->add_control( 'imgtop_mobile', array(
			'label'        => __( 'Move Image Above The Post Meta on Mobile?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'condition'    => array( 'imgpos' => array( 'left', 'right' ) ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_postmeta', array(
			'label' => esc_html__( 'Post Meta Data', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'post_meta', array(
			'label'    => __( 'Showing Post Meta', 'soledad' ),
			'type'     => Controls_Manager::SELECT2,
			'default'  => array( 'title', 'author', 'date' ),
			'multiple' => true,
			'options'  => array(
				'cat'     => esc_html__( 'Categories', 'soledad' ),
				'title'   => esc_html__( 'Title', 'soledad' ),
				'author'  => esc_html__( 'Author', 'soledad' ),
				'date'    => esc_html__( 'Date', 'soledad' ),
				'comment' => esc_html__( 'Comments', 'soledad' ),
				'views'   => esc_html__( 'Views', 'soledad' ),
				'reading' => esc_html__( 'Reading Time', 'soledad' ),
			),
		) );

		$this->add_control( 'primary_cat', array(
			'label'        => __( 'Show Primary Category Only', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'description'  => __( 'If you using Yoast SEO or Rank Math plugin, this option will show only the primary category from those plugins. If you don\'t use those plugins, it will show the first category in the list categories of the posts.', 'soledad' ),
			'label_on'     => __( 'On', 'soledad' ),
			'label_off'    => __( 'Off', 'soledad' ),
			'return_value' => 'on',
			'default'      => '',
		) );

		$this->add_control( 'title_length', array(
			'label'   => __( 'Custom Title Words Length', 'soledad' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
			'default' => '',
		) );

		$this->add_control( 'dformat', array(
			'label'   => __( 'Date Format', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				''        => 'Default',
				'timeago' => 'Time Ago',
				'normal'  => 'Normal',
			),
			'default' => 'timeago',
		) );

		$this->add_control( 'show_formaticon', array(
			'label'        => __( 'Show Post Format Icons', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'show_reviewpie', array(
			'label'        => __( 'Show Review Scores from Penci Review plugin', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'show_excerpt', array(
			'label'        => __( 'Show The Post Excerpt?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'separator'    => 'before',
		) );

		$this->add_control( 'excerpt_length', array(
			'label'     => __( 'Custom Excerpt Length', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 1,
			'max'       => 500,
			'step'      => 1,
			'default'   => 15,
			'condition' => array( 'show_excerpt' => 'yes' ),
		) );

		$this->add_control( 'excerpt_align', array(
			'label'       => __( 'Custom Excerpt Align', 'soledad' ),
			'type'        => Controls_Manager::CHOOSE,
			'condition'   => array( 'show_excerpt' => 'yes' ),
			'label_block' => false,
			'options'     => array(
				'left'    => array(
					'title' => __( 'Left', 'soledad' ),
					'icon'  => 'eicon-text-align-left',
				),
				'center'  => array(
					'title' => __( 'Center', 'soledad' ),
					'icon'  => 'eicon-text-align-center',
				),
				'right'   => array(
					'title' => __( 'Right', 'soledad' ),
					'icon'  => 'eicon-text-align-right',
				),
				'justify' => array(
					'title' => __( 'Justify', 'soledad' ),
					'icon'  => 'eicon-text-align-justify',
				),
			),
			'selectors'   => array(
				'{{WRAPPER}} .pcsl-pexcerpt' => 'text-align: {{VALUE}}'
			)
		) );

		$this->add_control( 'show_readmore', array(
			'label'        => __( 'Show Read More Button?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'separator'    => 'before',
		) );

		$this->add_control( 'rmstyle', array(
			'label'     => __( 'Read More Button Style', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'filled'    => 'Default',
				'bordered'  => 'Bordered',
				'underline' => 'Underline',
				'text'      => 'Text Only',
			),
			'default'   => 'filled',
			'condition' => array( 'show_readmore' => 'yes' ),
		) );

		$this->add_control( 'rm_align', array(
			'label'       => __( 'Custom Read More Align', 'soledad' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'condition'   => array( 'show_readmore' => 'yes' ),
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
				'{{WRAPPER}} .pcsl-readmore' => 'text-align: {{VALUE}}'
			)
		) );

		$this->add_control( 'excerpt_pos', array(
			'label'      => __( 'Excerpt & Read More Position', 'soledad' ),
			'type'       => Controls_Manager::SELECT,
			'options'    => array(
				'below' => 'Below of Image',
				'side'  => 'Side of Image',
			),
			'separator'  => 'before',
			'default'    => 'below',
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'show_excerpt',
						'operator' => '==',
						'value'    => 'yes'
					],
					[
						'name'     => 'show_readmore',
						'operator' => '==',
						'value'    => 'yes'
					]
				]
			]
		) );

		$this->add_control( 'heading_show_mobile', array(
			'label'     => __( 'Showing on Mobile', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'hide_cat_mobile', array(
			'label'        => __( 'Hide Post Categories on Mobile?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'hide_meta_mobile', array(
			'label'        => __( 'Hide Post Meta on Mobile', 'soledad' ),
			'description'  => __( 'Include: Author Name, Date, Comments Count, Views Count, Reading Time', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'hide_excerpt_mobile', array(
			'label'        => __( 'Hide Post Excerpt on Mobile', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array( 'show_excerpt' => 'yes' ),
		) );

		$this->add_control( 'hide_rm_mobile', array(
			'label'        => __( 'Hide Post Read More Button on Mobile', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => array( 'show_readmore' => 'yes' ),
		) );

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		$this->start_controls_section( 'section_style_tax', array(
			'label' => __( 'Taxonomy Layout', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'tax_listing_bgcl', array(
			'label'     => __( 'Taxonomy List/Content Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'tax_listing_scl', array(
			'label'     => __( 'Taxonomy List/Content Seperate Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-posts' => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'tax_listing_heading', array(
			'label'     => __( 'Taxonomy Text', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => [ 'count!' => '' ],
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'tax_listing_typo',
			'label'    => __( 'Taxonomy Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-mtp-filters-terms ul li a',
		) );

		$this->add_responsive_control( 'tax_listing_spacing', array(
			'label'     => __( 'Spacing Between Items', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-mtp-filters-wrapper' => '--listspc: {{SIZE}}px' ),
		) );

		$this->add_responsive_control( 'tax_listing_bdw', array(
			'label'     => __( 'Taxonomy Term Border Width', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-mtp-filters-terms ul li a' => 'border-style: solid;border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->add_responsive_control( 'tax_listing_bdrd', array(
			'label'     => __( 'Taxonomy Term Border Radius', 'soledad' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-mtp-filters-terms ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->start_controls_tabs( 'tabs_tax_style' );

		$this->start_controls_tab(
			'tax_listing_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control( 'tax_listing_t_bg', array(
			'label'     => __( 'Taxonomy Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'tax_listing_bg', array(
			'label'     => __( 'Taxonomy Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a' => 'background: {{VALUE}};',
			),
		) );

		$this->add_control( 'tax_listing_bdcl', array(
			'label'     => __( 'Taxonomy Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a' => 'border-color: {{VALUE}};',
			),
		) );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tax_listing_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control( 'tax_listing_thbg', array(
			'label'     => __( 'Taxonomy Text Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a:hover' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'tax_listing_hbg', array(
			'label'     => __( 'Taxonomy Background Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a:hover' => 'background: {{VALUE}};',
			),
		) );

		$this->add_control( 'tax_listing_bbg', array(
			'label'     => __( 'Taxonomy Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a:hover' => 'border-color: {{VALUE}};',
			),
		) );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tax_listing_active',
			[
				'label' => esc_html__( 'Active', 'soledad' ),
			]
		);

		$this->add_control( 'tax_listing_tabg', array(
			'label'     => __( 'Taxonomy Active Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a.added, {{WRAPPER}} .penci-mtp-filters-wrapper[data-filter-terms=""] ul li a[data-id="all"]' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'tax_listing_abg', array(
			'label'     => __( 'Taxonomy Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a.added, {{WRAPPER}} .penci-mtp-filters-wrapper[data-filter-terms=""] ul li a[data-id="all"]' => 'background: {{VALUE}};',
			),
		) );

		$this->add_control( 'tax_listing_abbg', array(
			'label'     => __( 'Taxonomy Active Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a.added, {{WRAPPER}} .penci-mtp-filters-wrapper[data-filter-terms=""] ul li a[data-id="all"]' => 'border-color: {{VALUE}};',
			),
		) );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Count

		$this->add_control( 'tax_listing_number_heading', array(
			'label'     => __( 'Taxonomy Count Number', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => [ 'count!' => '' ],
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'tax_count_typo',
			'label'     => __( 'Taxonomy Count Number Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .penci-mtp-filters-terms ul li a .count',
			'condition' => [ 'count!' => '' ],
		) );

		$this->add_control( 'tax_listing_count_thbg', array(
			'label'     => __( 'Taxonomy Hover Count Number Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => [ 'count!' => '' ],
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a:hover .count' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'tax_listing_count_tabg', array(
			'label'     => __( 'Taxonomy Active Count Number Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => [ 'count!' => '' ],
			'selectors' => array(
				'{{WRAPPER}} .penci-mtp-filters-terms ul li a.added .count, {{WRAPPER}} .penci-mtp-filters-wrapper[data-filter-terms=""] ul li a[data-id="all"] .count' => 'color: {{VALUE}};',
			),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_content', array(
			'label' => __( 'Posts Layout', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'ver_border', array(
			'label'        => __( 'Add Vertical Border Between Post Items', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
		) );

		$this->add_control( 'ver_bordercl', array(
			'label'     => __( 'Custom Vertical Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsl-verbd .pcsl-item' => 'border-right-color: {{VALUE}};',
			),
			'condition' => array( 'ver_border' => 'yes' ),
		) );

		$this->add_responsive_control( 'ver_borderw', array(
			'label'     => __( 'Custom Vertical Border Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcsl-verbd .pcsl-item' => 'border-right-width: {{SIZE}}px;',
			),
			'condition' => array( 'ver_border' => 'yes' ),
		) );

		$this->add_control( 'item_bg', array(
			'label'     => __( 'Post Items Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pcsl-itemin' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'item_padding', array(
			'label'      => __( 'Post Items Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcsl-itemin' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_control( 'item_borders', array(
			'label'     => __( 'Add Borders for Post Items', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pcsl-itemin' => 'border: 1px solid {{VALUE}};' ),
		) );

		$this->add_control( 'remove_border_last', array(
			'label'     => __( 'Remove Border Bottom On Last Item', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'selectors' => array(
				'{{WRAPPER}} .pcsl-col-1 .pcsl-item:last-child .pcsl-itemin' => 'padding-bottom: 0; border-bottom: none;'
			),
			'condition' => array( 'column' => '1', 'item_borders!' => '' ),
		) );

		$this->add_responsive_control( 'item_bordersw', array(
			'label'      => __( 'Post Items Borders Width', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcsl-itemin' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_responsive_control( 'side_padding', array(
			'label'      => __( 'Padding for Side Content of Image', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcsl-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'
			),
		) );

		// Box Shadow
		$this->add_control( 'heading_featured_image_shadow', array(
			'label'     => __( 'Featured Image Shadow', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'featured_image_shadow_enable', array(
			'label' => __( 'Enable Shadow?', 'soledad' ),
			'type'  => Controls_Manager::SWITCHER,
		) );

		$this->add_responsive_control( 'featured_image_shadow', array(
			'label'     => __( 'Image Shadow', 'soledad' ),
			'type'      => Controls_Manager::BOX_SHADOW,
			'selectors' => [
				'{{WRAPPER}} .pcsl-thumb' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
			],
			'condition' => [ 'featured_image_shadow_enable' => 'yes' ]
		) );

		$this->add_control( 'heading_pcat', array(
			'label'     => __( 'Post Categories', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'cat_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .cat > a.penci-cat-name' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'cat_hcolor', array(
			'label'     => __( 'Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .cat > a.penci-cat-name:hover' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'cat_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .cat > a.penci-cat-name',
		) );

		$this->add_control( 'heading_ptitle', array(
			'label'     => __( 'Post Title', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'title_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pcsl-content .pcsl-title a' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'title_hcolor', array(
			'label'     => __( 'Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pcsl-content .pcsl-title a:hover' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'title_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcsl-content .pcsl-title',
		) );

		$this->add_control( 'heading_date', array(
			'label'     => __( 'Post Date ( for "Creative List" layout )', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array( 'type' => array( 'nlist' ) ),
		) );

		$this->add_control( 'date_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pcsl-hdate .pcsl-date span' => 'color: {{VALUE}};' ),
			'condition' => array( 'type' => array( 'nlist' ) ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'date_typo',
			'label'     => __( 'Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .pcsl-hdate .pcsl-date span',
			'condition' => array( 'type' => array( 'nlist' ) ),
		) );

		// alt meta
		$this->add_control( 'heading_alt_meta', array(
			'label'     => __( 'Post Date', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'column' => '1' ],
		) );

		$this->add_control( 'meta_alt_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .grid-post-box-meta.pcmtf-mt-alt span' => 'color: {{VALUE}};' ),
			'condition' => [ 'column' => '1' ],
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'meta_alt_typo',
			'label'     => __( 'Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .grid-post-box-meta.pcmtf-mt-alt',
			'condition' => [ 'column' => '1' ],
		) );
		// end alt meta

		$this->add_control( 'heading_meta', array(
			'label'     => __( 'Post Meta', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'meta_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .grid-post-box-meta span' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'links_color', array(
			'label'     => __( 'Links Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .grid-post-box-meta span a' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'links_hcolor', array(
			'label'     => __( 'Links Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .grid-post-box-meta span a:hover' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'meta_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .grid-post-box-meta',
		) );

		$this->add_control( 'heading_excerpt', array(
			'label'     => __( 'Post Excerpt', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array( 'show_excerpt' => 'yes' ),
		) );

		$this->add_control( 'excerpt_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => array( 'show_excerpt' => 'yes' ),
			'selectors' => array( '{{WRAPPER}} .pcbg-pexcerpt, {{WRAPPER}} .pcbg-pexcerpt p' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'excerpt_typo',
			'label'     => __( 'Typography', 'soledad' ),
			'condition' => array( 'show_excerpt' => 'yes' ),
			'selector'  => '{{WRAPPER}} .pcbg-pexcerpt p',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_rm', array(
			'label'     => __( 'Read More Button', 'soledad' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => array( 'show_readmore' => 'yes' ),
		) );

		$this->add_responsive_control( 'rm_padding', array(
			'label'      => __( 'Button Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'condition'  => array( 'rmstyle!' => 'text' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcsl-readmore .pcsl-readmorebtn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_responsive_control( 'rm_borders', array(
			'label'      => __( 'Button Borders Width', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'condition'  => array( 'rmstyle' => array( 'bordered', 'underline' ) ),
			'selectors'  => array(
				'{{WRAPPER}} .pcsl-readmore .pcsl-readmorebtn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_responsive_control( 'rm_radius', array(
			'label'      => __( 'Button Borders Radius', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'condition'  => array( 'rmstyle' => array( 'bordered', 'filled' ) ),
			'selectors'  => array(
				'{{WRAPPER}} .pcsl-readmore .pcsl-readmorebtn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'rm_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .pcsl-readmore .pcsl-readmorebtn',
		) );

		$this->add_control( 'rm_color', array(
			'label'     => __( 'Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pcsl-readmore .pcsl-readmorebtn' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'rm_hcolor', array(
			'label'     => __( 'Text Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pcsl-readmore .pcsl-readmorebtn:hover' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'rm_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => array( 'rmstyle' => array( 'bordered', 'filled' ) ),
			'selectors' => array( '{{WRAPPER}} .pcsl-readmore .pcsl-readmorebtn' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'rm_hbgcolor', array(
			'label'     => __( 'Hover Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => array( 'rmstyle' => array( 'bordered', 'filled' ) ),
			'selectors' => array( '{{WRAPPER}} .pcsl-readmore .pcsl-readmorebtn:hover' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'rm_bdcolor', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => array( 'rmstyle' => array( 'bordered', 'underline' ) ),
			'selectors' => array( '{{WRAPPER}} .pcsl-readmore .pcsl-readmorebtn' => 'border-color: {{VALUE}};' ),
		) );

		$this->add_control( 'rm_hbdcolor', array(
			'label'     => __( 'Hover Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => array( 'rmstyle' => array( 'bordered', 'underline' ) ),
			'selectors' => array( '{{WRAPPER}} .pcsl-readmore .pcsl-readmorebtn:hover' => 'border-color: {{VALUE}};' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_spacing', array(
			'label' => __( 'Elements Spacing', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_responsive_control( 'cat_space', array(
			'label'     => __( 'Categories Margin Bottom', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcsl-inner .pcsl-content .cat' => 'margin-bottom: {{SIZE}}px;',
			),
		) );

		$this->add_responsive_control( 'meta_space', array(
			'label'     => __( 'Post Meta Margin Top', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcsl-inner .grid-post-box-meta' => 'margin-top: {{SIZE}}px;',
			),
		) );

		$this->add_responsive_control( 'excerpt_space', array(
			'label'     => __( 'Post Excerpt Margin Top', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'condition' => array( 'show_excerpt' => 'yes' ),
			'selectors' => array(
				'{{WRAPPER}} .pcsl-pexcerpt' => 'margin-top: {{SIZE}}px;',
			),
		) );

		$this->add_responsive_control( 'rm_space', array(
			'label'     => __( 'Read More Button Margin Top', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'condition' => array( 'show_readmore' => 'yes' ),
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcsl-readmore' => 'margin-top: {{SIZE}}px;',
			),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'pagi_design', array(
			'label'     => __( 'Page Navigation', 'soledad' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => array(
				'paging!' => 'none',
				'type!'   => 'crs',
			),
		) );

		$this->add_responsive_control( 'pagi_mwidth', array(
			'label'      => __( 'Load More Posts Button Max Width', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( '%', 'px' ),
			'range'      => array(
				'%'  => array( 'min' => 0, 'max' => 100, ),
				'px' => array( 'min' => 0, 'max' => 2000, 'step' => 1 ),
			),
			'condition'  => array(
				'paging' => array( 'loadmore', 'scroll' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button' => 'max-width: {{SIZE}}{{UNIT}};',
			),
		) );

		$this->add_control( 'pagi_color', array(
			'label'     => __( 'Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a'                    => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a' => 'color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_control( 'pagi_hcolor', array(
			'label'     => __( 'Text Hover & Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a:hover'                         => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a:hover'      => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li span.current' => 'color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_control( 'bgpagi_color', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a'                    => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a' => 'border-color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_control( 'bgpagi_hcolor', array(
			'label'     => __( 'Borders Hover & Active Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a:hover'                         => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a:hover'      => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li span.current' => 'border-color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_control( 'bgpagi_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a'                    => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a' => 'background-color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_control( 'bgpagi_hbgcolor', array(
			'label'     => __( 'Hover & Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination a:hover'                         => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li a:hover'      => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination ul.page-numbers li span.current' => 'background-color: {{VALUE}};',
			),
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'bgpagi_typo',
			'label'     => __( 'Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .penci-pagination a, {{WRAPPER}} .penci-pagination span.current',
			'condition' => array(
				'paging!' => 'none',
			),
		) );

		$this->add_responsive_control( 'bgpagi_borderwidth', array(
			'label'      => __( 'Borders Width', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} ul.page-numbers li a'         => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} ul.page-numbers span.current' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .penci-pagination a'          => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array(
				'paging!' => 'none',
			),
		) );

		$this->add_responsive_control( 'bgpagi_borderradius', array(
			'label'      => __( 'Borders Radius', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} ul.page-numbers li a'         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} ul.page-numbers span.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .penci-pagination a'          => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array(
				'paging!' => 'none',
			),
		) );

		$this->add_responsive_control( 'bgpagi_padding', array(
			'label'      => __( 'Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} ul.page-numbers li a'         => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} ul.page-numbers span.current' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .penci-pagination a'          => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array(
				'paging!' => 'none',
			),
		) );

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

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

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tax      = $settings['tax'];
		$order    = $settings['order'];
		$orderby  = $settings['orderby'];
		$maxitems = $settings['maxitems'];

		$dformat           = $settings['dformat'] ? $settings['dformat'] : '';
		$column            = $settings['column'] ? $settings['column'] : '3';
		$tab_column        = $settings['tab_column'] ? $settings['tab_column'] : '2';
		$mb_column         = $settings['mb_column'] ? $settings['mb_column'] : '1';
		$imgpos            = $settings['imgpos'] ? $settings['imgpos'] : 'left';
		$thumb_size_imgtop = 'top' == $imgpos ? 'penci-thumb' : 'penci-thumb-small';
		if ( get_theme_mod( 'penci_featured_image_size' ) == 'vertical' ) {
			$thumb_size_imgtop = 'penci-thumb-vertical';
		} else if ( get_theme_mod( 'penci_featured_image_size' ) == 'square' ) {
			$thumb_size_imgtop = 'penci-thumb-square';
		}
		$thumb_size   = $settings['thumb_size'] ? $settings['thumb_size'] : $thumb_size_imgtop;
		$mthumb_size  = $settings['mthumb_size'] ? $settings['mthumb_size'] : $thumb_size_imgtop;
		$post_meta    = $settings['post_meta'] ? $settings['post_meta'] : array();
		$primary_cat  = $settings['primary_cat'] ? $settings['primary_cat'] : '';
		$title_length = $settings['title_length'] ? $settings['title_length'] : '';
		$excerpt_pos  = $settings['excerpt_pos'] ? $settings['excerpt_pos'] : 'below';
		$paging       = $settings['paging'] ? $settings['paging'] : 'none';
		$paging_align = $settings['paging_align'] ? $settings['paging_align'] : 'align-center';
		if ( 'top' == $imgpos ) {
			$excerpt_pos = 'side';
		}
		$rmstyle        = $settings['rmstyle'] ? $settings['rmstyle'] : 'filled';
		$excerpt_length = $settings['excerpt_length'] ? $settings['excerpt_length'] : 15;

		$thumbnail = $thumb_size;
		if ( penci_is_mobile() ) {
			$thumbnail = $mthumb_size;
		}

		$inner_wrapper_class = 'pcsl-inner penci-clearfix';
		$inner_wrapper_class .= ' pcsl-grid';
		$item_class          = 'normal-item';

		if ( isset( $settings['paywall_heading_text_style'] ) ) {
			$inner_wrapper_class .= ' pencipw-hd-' . $settings['paywall_heading_text_style'];
		}

		$inner_wrapper_class .= ' pcsl-imgpos-' . $imgpos;
		$inner_wrapper_class .= ' pcsl-col-' . $column;
		$inner_wrapper_class .= ' pcsl-tabcol-' . $tab_column;
		$inner_wrapper_class .= ' pcsl-mobcol-' . $mb_column;
		if ( 'yes' == $settings['nocrop'] ) {
			$inner_wrapper_class .= ' pcsl-nocrop';
		}
		if ( 'yes' == $settings['hide_cat_mobile'] ) {
			$inner_wrapper_class .= ' pcsl-cat-mhide';
		}
		if ( 'yes' == $settings['hide_meta_mobile'] ) {
			$inner_wrapper_class .= ' pcsl-meta-mhide';
		}
		if ( 'yes' == $settings['hide_excerpt_mobile'] ) {
			$inner_wrapper_class .= ' pcsl-excerpt-mhide';
		}
		if ( 'yes' == $settings['hide_rm_mobile'] ) {
			$inner_wrapper_class .= ' pcsl-rm-mhide';
		}
		if ( 'yes' == $settings['imgtop_mobile'] && in_array( $imgpos, array( 'left', 'right' ) ) ) {
			$inner_wrapper_class .= ' pcsl-imgtopmobile';
		}
		if ( 'yes' == $settings['ver_border'] ) {
			$inner_wrapper_class .= ' pcsl-verbd';
		}

		if ( ! in_array( 'date', $post_meta ) ) {
			$inner_wrapper_class .= ' no-date';
		}

		$inner_wrapper_class .= ' pcmtp-ct-' . $settings['vertical_position'];

		$term_args = [
			'taxonomy' => $tax,
			'order'    => $order,
			'orderby'  => $orderby,
			'number'   => $maxitems,
		];

		if ( $settings['taxonomies_ex'] ) {
			$term_args['exclude'] = $settings['taxonomies_ex'];
		}

		if ( $settings['taxonomies_in'] ) {
			$term_args['include'] = $settings['taxonomies_in'];
		}

		// posts args

		$post_args = [
			'orderby'        => $settings['post_orderby'],
			'order'          => $settings['post_order'],
			'posts_per_page' => $settings['posts_per_page'],
		];

		if ( $settings['taxonomies_ex'] ) {
			$post_args['tax_query'][] = [
				'operator' => 'NOT IN',
				'taxonomy' => $tax,
				'terms'    => $settings['taxonomies_ex'],
			];
		}

		if ( $settings['taxonomies_in'] ) {
			$post_args['tax_query'][] = [
				'operator' => 'IN',
				'taxonomy' => $tax,
				'terms'    => $settings['taxonomies_in'],
			];
		}

		if ( $settings['taxonomies_ex'] || $settings['taxonomies_in'] ) {
			$post_args['tax_query']['relation'] = 'AND';
		}

		$post_list = new WP_Query( $post_args );

		$disable_lazy = $settings['disable_lazy'] == 'yes' ? 'false' : 'true';

		$data_settings = [
			'hide_thumb'      => $settings['hide_thumb'],
			'show_reviewpie'  => $settings['show_reviewpie'],
			'show_formaticon' => $settings['show_formaticon'],
			'disable_lazy'    => $disable_lazy,
			'nocrop'          => $settings['nocrop'],
			'post_meta'       => $post_meta,
			'excerpt_length'  => $excerpt_length,
			'title_length'    => $title_length,
			'rmstyle'         => $rmstyle,
			'dformat'         => $dformat,
			'excerpt_pos'     => $excerpt_pos,
			'primary_cat'     => $primary_cat,
			'thumbnail'       => $thumbnail,
			'column'          => $column,
			'show_excerpt'    => $settings['show_excerpt'],
			'show_readmore'   => $settings['show_readmore'],
		];

		$terms_list   = get_terms( $term_args );
		$count_html   = '';
		$sticky_class = $settings['tax_sticky'] ? 'penci-mtp-sticky' : 'penci-mtp-non-sticky';
		$this->markup_block_title( $settings, $this );
		?>
        <div class="penci-mtp-filters-wrapper <?php echo $sticky_class; ?> pctp-<?php echo $settings['tax_position']; ?>"
             data-settings='<?php echo json_encode( $data_settings ); ?>'
             data-tax="<?php echo $tax; ?>" data-request-id="<?php echo wp_create_nonce( 'penci-mtp-filters' ); ?>"
             data-paged="1" data-filter-terms=""
             data-query='<?php echo json_encode( $post_args ); ?>'>
            <div class="penci-mtp-filters-terms">
                <div class="theiaStickySidebar">
					<a href="#" aria-label="<?php echo penci_get_setting( 'penci_trans_all' );?>" class="penci-mtp-filters-mobile">
						<span><?php echo penci_get_setting( 'penci_trans_all' );?></span>
					</a>
					<a href="#" aria-label="<?php echo penci_get_setting( 'penci_trans_close' );?>" class="penci-mtp-filters-close">
						<span><?php echo penci_get_setting( 'penci_trans_close' );?></span>
					</a>
                    <ul class="penci-mtp-filters-main">
                        <li>
                            <a class="pcmtp-f-term" data-id="all" href="#">
								<?php
								echo penci_get_setting( 'penci_trans_all' );
								if ( $settings['count'] ) {
									echo '<span class="count">' . $post_list->found_posts . '</span>';
								}
								?>
                            </a>
                        </li>
						<?php
						foreach ( $terms_list as $order => $term_data ) {
							if ( $settings['count'] ) {
								$count_html = '<span class="count">' . $term_data->count . '</span>';
							}
							echo '<li><a class="pcmtp-f-term" data-id="' . $term_data->term_id . '" href="#">' . $term_data->name . $count_html . '</a></li>';
						}
						?>
                    </ul>
                </div>
            </div>
            <div class="penci-mtp-filters-posts">
                <div class="theiaStickySidebar">
					<?php
					if ( $post_list->have_posts() ) {
						?>
                        <div class="penci-smalllist">
                            <div class="<?php echo $inner_wrapper_class; ?>">
								<?php
								while ( $post_list->have_posts() ) {
									$post_list->the_post();
									?>
                                    <div class="pcsl-item<?php if ( 'yes' == $settings['hide_thumb'] || ! has_post_thumbnail() ) {
										echo ' pcsl-nothumb';
									} ?>">
                                        <div class="pcsl-itemin">
                                            <div class="pcsl-iteminer">


												<?php if ( 'yes' != $settings['hide_thumb'] && has_post_thumbnail() ) { ?>
                                                    <div class="pcsl-thumb">
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
                                                                   aria-label="Icon"><?php penci_fawesome_icon( 'far fa-image' ); ?></a>
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
                                                        <a <?php echo penci_layout_bg( penci_get_featured_image_size( get_the_ID(), $thumbnail ), $disable_lazy ); ?>
                                                                href="<?php the_permalink(); ?>"
                                                                title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                                                class="<?php echo penci_layout_bg_class( $disable_lazy ); ?> penci-image-holder"<?php if ( 'yes' == $settings['nocrop'] ) {
															echo ' style="padding-bottom: ' . penci_get_featured_image_padding_markup( get_the_ID(), $thumbnail, true ) . '%"';
														} ?>>
															<?php echo penci_layout_img( penci_get_featured_image_size( get_the_ID(), $thumbnail ), get_the_title(), $disable_lazy ); ?>
                                                        </a>

                                                    </div>
												<?php } ?>
                                                <div class="pcsl-content">
													<?php if ( in_array( 'cat', $post_meta ) ) : ?>
                                                        <div class="cat pcsl-cat">
															<?php penci_category( '', $primary_cat ); ?>
                                                        </div>
													<?php endif; ?>

													<?php if ( in_array( 'title', $post_meta ) ) : ?>
                                                        <div class="pcsl-title">
                                                            <a href="<?php the_permalink(); ?>"<?php if ( $title_length ): echo ' title="' . wp_strip_all_tags( get_the_title() ) . '"'; endif; ?>><?php

																if ( ! $title_length ) {
																	the_title();
																} else {
																	echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $title_length, '...' );
																} ?></a>
                                                        </div>
													<?php endif; ?>

													<?php if ( isset( $settings['cspost_enable'] ) && $settings['cspost_enable'] || ( count( array_intersect( array(
																'author',
																'date',
																'comment',
																'views',
																'reading'
															), $post_meta ) ) > 0 ) || ( count( array_intersect( array(
																'author',
																'comment',
																'views',
																'reading'
															), $post_meta ) ) > 0 ) ) { ?>

														<?php if ( $column == 1 ) { ?>
                                                            <div class="grid-post-box-meta pcsl-meta pcmtf-mt-alt">
																<?php if ( in_array( 'date', $post_meta ) ) : ?>
                                                                    <span class="sl-date"><?php penci_soledad_time_link( null, $dformat ); ?></span>
																<?php endif; ?>
                                                            </div>
														<?php } ?>


                                                        <div class="grid-post-box-meta pcsl-meta">
															<?php if ( in_array( 'date', $post_meta ) ) : ?>
                                                                <span class="sl-date"><?php penci_soledad_time_link( null, $dformat ); ?></span>
															<?php endif; ?>

															<?php if ( in_array( 'author', $post_meta ) ) : ?>
                                                                <span class="sl-date-author author-italic">
													<?php echo penci_get_setting( 'penci_trans_by' ); ?> <?php if ( function_exists( 'coauthors_posts_links' ) ) :
																		penci_coauthors_posts_links();
																	else: ?>
                                                                        <a class="author-url url fn n"
                                                                           href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
																	<?php endif; ?>
													</span>
															<?php endif; ?>
															
															<?php if ( in_array( 'comment', $post_meta ) ) : ?>
                                                                <span class="sl-comment">
												<a href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a>
											</span>
															<?php endif; ?>
															<?php
															if ( in_array( 'views', $post_meta ) ) {
																echo '<span class="sl-views">';
																echo penci_get_post_views( get_the_ID() );
																echo ' ' . penci_get_setting( 'penci_trans_countviews' );
																echo '</span>';
															}
															?>
															<?php
															$hide_readtime = in_array( 'reading', $post_meta ) ? false : true;
															if ( penci_isshow_reading_time( $hide_readtime ) ): ?>
                                                                <span class="sl-readtime"><?php penci_reading_time(); ?></span>
															<?php endif; ?>
															<?php echo penci_show_custom_meta_fields( [
																'validator' => isset( $settings['cspost_enable'] ) ? $settings['cspost_enable'] : '',
																'keys'      => isset( $settings['cspost_cpost_meta'] ) ? $settings['cspost_cpost_meta'] : '',
																'acf'       => isset( $settings['cspost_cpost_acf_meta'] ) ? $settings['cspost_cpost_acf_meta'] : '',
																'label'     => isset( $settings['cspost_cpost_meta_label'] ) ? $settings['cspost_cpost_meta_label'] : '',
																'divider'   => isset( $settings['cspost_cpost_meta_divider'] ) ? $settings['cspost_cpost_meta_divider'] : '',
															] ); ?>
                                                        </div>
													<?php } ?>

													<?php if ( 'yes' == $settings['show_excerpt'] && 'side' == $excerpt_pos ) { ?>
                                                        <div class="pcbg-pexcerpt pcsl-pexcerpt">
															<?php penci_the_excerpt( $excerpt_length ); ?>
                                                        </div>
													<?php } ?>
													<?php if ( 'yes' == $settings['show_readmore'] && 'side' == $excerpt_pos ) { ?>
                                                        <div class="pcsl-readmore">
                                                            <a href="<?php the_permalink(); ?>"
                                                               class="pcsl-readmorebtn pcsl-btns-<?php echo $rmstyle; ?>">
																<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>
                                                            </a>
                                                        </div>
													<?php } ?>

                                                </div>

												<?php if ( ( 'yes' == $settings['show_excerpt'] || 'yes' == $settings['show_readmore'] ) && 'below' == $excerpt_pos ) { ?>
                                                    <div class="pcsl-flex-full">
														<?php if ( 'yes' == $settings['show_excerpt'] ) { ?>
                                                            <div class="pcbg-pexcerpt pcsl-pexcerpt">
																<?php penci_the_excerpt( $excerpt_length ); ?>
                                                            </div>
														<?php } ?>
														<?php if ( 'yes' == $settings['show_readmore'] ) { ?>
                                                            <div class="pcsl-readmore">
                                                                <a href="<?php the_permalink(); ?>"
                                                                   class="pcsl-readmorebtn pcsl-btns-<?php echo $rmstyle; ?>">
																	<?php echo penci_get_setting( 'penci_trans_read_more' ); ?>
                                                                </a>
                                                            </div>
														<?php } ?>
                                                    </div>
												<?php } ?>
                                            </div>
                                        </div>
                                    </div>
									<?php
								}
								wp_reset_postdata();
								?>
                            </div>
							<?php echo penci_get_html_animation_loading( $settings['ajax_loading_style'] ); ?>
                        </div>
						<?php

					}

					if ( 'loadmore' == $paging || 'scroll' == $paging ) {
						$button_class = ' penci-ajax-more penci-mtpf-more-click';
						if ( 'scroll' == $paging ):
							$button_class = ' penci-ajax-more penci-mtpf-more-scroll';
						endif;
						?>
                        <div class="pcbg-paging penci-pagination <?php echo 'pcbg-paging-' . $paging_align . $button_class; ?>">
                            <a class="penci-ajax-more-button" href="#" aria-label="More Posts"
                               data-more="<?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?>"
                               data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_posts' ); ?>">
                                <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?></span>
                                <span class="ajaxdot"></span>
								<?php penci_fawesome_icon( 'fas fa-sync' ); ?>
                            </a>
                        </div>
					<?php } ?>
                </div>
            </div>
        </div>
		<?php
	}
}
