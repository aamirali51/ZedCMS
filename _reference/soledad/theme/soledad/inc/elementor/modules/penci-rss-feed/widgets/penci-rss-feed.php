<?php

namespace PenciSoledadElementor\Modules\PenciRssFeed\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Base\Base_Color;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciRssFeed extends Base_Widget {

	public function get_name() {
		return 'penci-rss-feed';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' RSS Feed', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'rss', 'feed' );
	}

	protected function register_controls() {

		// Start feed source section.
		$this->start_controls_section(
			'feed-source',
			array(
				'label' => __( 'Feed Source', 'soledad' ),
			)
		);
		$this->add_control(
			'feeds',
			array(
				'label_block' => true,
				'label'       => __( 'RSS Feed source', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => wp_sprintf( __( 'You can add multiple sources at once, by separating them with commas. <a href="%s" target="_blank">Click here</a> to check if the feed is valid. Invalid feeds may not import anything.', 'soledad' ), esc_url( 'https://validator.w3.org/feed/' ) ),
				// phpcs:ignore
			)
		);
		$this->add_control(
			'max',
			array(
				'label'   => __( 'Number of items to display', 'soledad' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
				'min'     => 1,
			)
		);
		$this->add_control(
			'offset',
			array(
				'label'       => __( 'Items Offset', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'separator'   => 'before',
				'min'         => 0,
				'default'     => 0,
			)
		);
		$this->add_control(
			'sort',
			array(
				'label'   => __( 'Order items by', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''           => __( 'Default', 'soledad' ),
					'date_desc'  => __( 'Date Descending', 'soledad' ),
					'date_asc'   => __( 'Date Ascending', 'soledad' ),
					'title_desc' => __( 'Title Descending', 'soledad' ),
					'title_asc'  => __( 'Title Ascending', 'soledad' ),
				),
			)
		);
		$this->add_control(
			'refresh',
			array(
				'label_block' => true,
				'label'       => __( 'For how long we will cache the feed results', 'soledad' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '12_hours',
				'options'     => array(
					'1_hours'  => wp_sprintf( __( '%d Hour', 'soledad' ), 1 ),
					'3_hours'  => wp_sprintf( __( '%d Hour', 'soledad' ), 3 ),
					'12_hours' => wp_sprintf( __( '%d Hour', 'soledad' ), 12 ),
					'1_days'   => wp_sprintf( __( '%d Day', 'soledad' ), 1 ),
					'3_days'   => wp_sprintf( __( '%d Days', 'soledad' ), 3 ),
					'15_days'  => wp_sprintf( __( '%d Days', 'soledad' ), 15 ),
				),
				'separator'   => 'before',
			)
		);
		$this->add_control(
			'error_empty',
			array(
				'label_block' => true,
				'label'       => __( 'Message to show when feed is empty', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'separator'   => 'before',
			)
		);
		$this->end_controls_section(); // End feed source section.

		// Start filter items section.
		$this->start_controls_section(
			'filter-items',
			array(
				'label' => wp_sprintf( __( 'Filter items%s', 'soledad' ) ),

			)
		);
		$this->add_control(
			'keywords_inc_on',
			array(
				'label'   => __( 'Display items if', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => array(
					'title'       => __( 'Title', 'soledad' ),
					'description' => __( 'Description', 'soledad' ),
					'author'      => __( 'Author', 'soledad' ),
				),

			)
		);
		$this->add_control(
			'keywords_title',
			array(
				'label_block' => true,
				'label'       => __( 'Contains:', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => __( 'You can add multiple keywords at once by separating them with comma (,) or use the plus sign (+) to bind multiple keywords.', 'soledad' ),
			)
		);
		$this->add_control(
			'keywords_exc_on',
			array(
				'label'     => __( 'Exclude items if', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'title',
				'options'   => array(
					'title'       => __( 'Title', 'soledad' ),
					'description' => __( 'Description', 'soledad' ),
					'author'      => __( 'Author', 'soledad' ),
				),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'keywords_ban',
			array(
				'label_block' => true,
				'label'       => __( 'Contains:', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => __( 'You can add multiple keywords at once by separating them with comma (,) or use the plus sign (+) to bind multiple keywords.', 'soledad' ),
			)
		);
		$this->add_control(
			'from_datetime',
			array(
				'label_block' => true,
				'label'       => __( 'Filter items by time range, from: ', 'soledad' ),
				'type'        => Controls_Manager::DATE_TIME,
				'separator'   => 'before',
			)
		);
		$this->add_control(
			'to_datetime',
			array(
				'label_block' => true,
				'label'       => __( 'To ', 'soledad' ),
				'type'        => Controls_Manager::DATE_TIME,
			)
		);
		$this->end_controls_section(); // End filter items section.

		// Start item thumbnail section.
		$this->start_controls_section(
			'size-options',
			array(
				'label' => wp_sprintf( __( 'Item Thumbnail Options', 'soledad' ) ),
			)
		);
		$this->add_control(
			'thumb',
			array(
				'label_block' => true,
				'label'       => __( 'Display first image, when available', 'soledad' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''    => __( 'Yes (without a fallback image)', 'soledad' ),
					'yes' => __( 'Yes (with a fallback image)', 'soledad' ),
					'no'  => __( 'No', 'soledad' ),
				),
			)
		);
		$this->add_control(
			'item-fallback-thumb',
			array(
				'label_block' => true,
				'label'       => __( 'Choose the Fallback Image', 'soledad' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'condition' => ['thumb'=>'yes']
			)
		);

		$this->add_control( 'imgpos', array(
			'label'   => __( 'Image Position', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'left'  => 'Left',
				'right' => 'Right',
				'top'   => 'Top',
			),
			'default' => 'left',
			'condition' => ['thumb!'=>'no']
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
			'condition' => ['thumb!'=>'no']
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
					],
					[
						'name'     => 'thumb',
						'operator' => '!=',
						'value'    => 'no'
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
			'condition' => array( 'thumb!' => 'no' ),
		) );

		$this->add_control( 'disable_lazy', array(
			'label'        => __( 'Disable Lazyload Images?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
			'condition' => array( 'thumb!' => 'no' ),
		) );

		$this->add_control( 'imgtop_mobile', array(
			'label'        => __( 'Move Image Above The Feed Meta on Mobile?', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'condition'    => array( 'imgpos' => array( 'left', 'right' ) ),
		) );
		$this->end_controls_section(); // End item thumbnail section.

		// Start layout section.
		$this->start_controls_section(
			'layout',
			array(
				'label' => wp_sprintf( __( 'Feed Layout%s', 'soledad' ) ),

			)
		);
		$this->add_control( 'type', array(
			'label'   => __( 'Type:', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'grid'  => 'Grid',
				'crs'   => 'Carousel',
				'nlist' => 'Creative List',
			),
			'default' => 'grid',
		) );

		$this->add_control( 'column', array(
			'label'     => __( 'Columns', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
			),
			'default'   => '3',
			'condition' => array( 'type!' => array( 'nlist' ) ),
		) );

		$this->add_control( 'tab_column', array(
			'label'     => __( 'Columns on Tablet', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				''  => 'Default',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
			'default'   => '',
			'condition' => array( 'type!' => array( 'nlist' ) ),
		) );

		$this->add_control( 'mb_column', array(
			'label'     => __( 'Columns on Mobile', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				''  => 'Default',
				'1' => '1',
				'2' => '2',
				'3' => '3',
			),
			'default'   => '',
			'condition' => array( 'type!' => array( 'nlist' ) ),
		) );

		$this->add_responsive_control( 'hgap', array(
			'label'     => __( 'Horizontal Space Between Feeds', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-smalllist' => '--pcsl-hgap: {{SIZE}}px;',
			),
			'condition' => array( 'type!' => array( 'nlist' ) ),
		) );

		$this->add_responsive_control( 'vgap', array(
			'label'     => __( 'Vertical Space Between Feeds', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-smalllist' => '--pcsl-bgap: {{SIZE}}px;',
			),
			'condition' => array( 'type' => array( 'grid', 'nlist' ) ),
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
		$this->add_control( 'excerpt_pos', array(
			'label'     => __( 'Excerpt & Read More Position', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'below' => 'Below of Image',
				'side'  => 'Side of Image',
			),
			'separator' => 'before',
			'default'   => 'below',
		) );
		$this->add_control(
			'target',
			array(
				'label'   => __( 'Links behavior (opened in the same window or a new tab)', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''          => __( 'Auto', 'soledad' ),
					'_blank'    => __( '_blank', 'soledad' ),
					'_self'     => __( '_self', 'soledad' ),
					'_top'      => __( '_top', 'soledad' ),
					'framename' => __( 'framename', 'soledad' ),
				),
			)
		);
		$this->add_control(
			'follow',
			array(
				'label'        => __( 'Add ”nofollow” tag to links', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'soledad' ),
				'label_off'    => esc_html__( 'No', 'soledad' ),
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'item-display-title',
			array(
				'label'        => __( 'Display item Title', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'soledad' ),
				'label_off'    => esc_html__( 'No', 'soledad' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);
		$this->add_control(
			'title',
			array(
				'label' => __( 'Max Title length (in characters)', 'soledad' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 1,
			)
		);
		$this->add_control(
			'summary',
			array(
				'label'        => __( 'Display item Description', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'soledad' ),
				'label_off'    => esc_html__( 'No', 'soledad' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);
		$this->add_control(
			'summarylength',
			array(
				'label' => __( 'Max Description length (in characters)', 'soledad' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 1,
			)
		);
		$this->end_controls_section(); // End layout section.

		// Start custom options section.
		$this->start_controls_section(
			'custom-options',
			array(
				'label' => __( 'Feed Items Custom Options', 'soledad' ),
			)
		);
		$this->add_control(
			'cus-hide-meta',
			array(
				'label'        => __( 'Hide items Meta', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'soledad' ),
				'label_off'    => esc_html__( 'No', 'soledad' ),
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'meta',
			array(
				'label'       => __( 'Display additional meta fields (author, date, time or categories)', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => array(
					'author'     => esc_html__( 'Author', 'soledad' ),
					'date'       => esc_html__( 'Date', 'soledad' ),
					'time'       => esc_html__( 'Time', 'soledad' ),
					'categories' => esc_html__( 'Categories', 'soledad' ),
				),
			)
		);
		$this->add_control(
			'cus-multiple-meta',
			array(
				'label'       => __( 'When using multiple sources, should we display additional meta fields?', 'soledad' ),
				'placeholder' => __( '(eg: source)', 'soledad' ),
				'type'        => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'source',
			array(
				'label'        => __( 'Show item source', 'soledad' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'soledad' ),
				'label_off'    => esc_html__( 'No', 'soledad' ),
				'return_value' => 'yes',
				'separator'    => 'before',
				'description'  => __( 'When using multiple sources this will append the item source to the author tag (required).', 'soledad' ),
			)
		);
		$this->end_controls_section(); // End custom options section.

		$this->register_block_title_section_controls();

		$this->start_controls_section( 'section_style_content', array(
			'label' => __( 'General', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'ver_border', array(
			'label'        => __( 'Add Vertical Border Between Feed Items', 'soledad' ),
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
			'label'     => __( 'Feed Items Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .pcsl-itemin' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'item_padding', array(
			'label'      => __( 'Feed Items Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pcsl-itemin' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );

		$this->add_control( 'item_borders', array(
			'label'     => __( 'Add Borders for Feed Items', 'soledad' ),
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
			'label'      => __( 'Feed Items Borders Width', 'soledad' ),
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
			'label'     => __( 'Feed Categories', 'soledad' ),
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
			'label'     => __( 'Feed Title', 'soledad' ),
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
			'label'     => __( 'Feed Date ( for "Creative List" layout )', 'soledad' ),
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

		$this->add_control( 'heading_meta', array(
			'label'     => __( 'Feed Meta', 'soledad' ),
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
			'label'     => __( 'Feed Excerpt', 'soledad' ),
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
		$this->add_responsive_control( 'meta_space', array(
			'label'     => __( 'Feed Meta Margin Top', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcsl-inner .grid-post-box-meta' => 'margin-top: {{SIZE}}px;',
			),
		) );

		$this->add_responsive_control( 'excerpt_space', array(
			'label'     => __( 'Feed Excerpt Margin Top', 'soledad' ),
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

		$this->register_block_title_style_section_controls();
	}

	protected function render() {
		require_once PENCI_SOLEDAD_DIR . '/inc/simple_pie.php';
		require_once PENCI_SOLEDAD_DIR . '/inc/rss_feed.php';
		$settings      = $this->get_settings();
		$fallback_img  = $settings['item-fallback-thumb'];
		$hide_meta     = $settings['cus-hide-meta'];
		$hide_title    = $settings['item-display-title'];
		$multiple_meta = $settings['cus-multiple-meta'];

		// Layout
		$display_source = $settings['source'];
		$feed_settings  = array(
			'feeds'             => $settings['feeds'],
			'max'               => $settings['max'],
			'target'            => $settings['target'],
			'title'             => $settings['title'],
			'meta'              => is_array( $settings['meta'] ) ? implode(',', $settings['meta']) : '',
			'summary'           => $settings['summary'],
			'summarylength'     => $settings['summarylength'],
			'thumb'             => $settings['thumb'],
			'default'           => ! empty( $fallback_img['url'] ) ? $fallback_img['url'] : '',
			'keywords_title'    => $settings['keywords_title'],
			'keywords_inc_on'   => $settings['keywords_inc_on'],
			'keywords_ban'      => $settings['keywords_ban'],
			'keywords_exc_on'   => $settings['keywords_exc_on'],
			'error_empty'       => $settings['error_empty'],
			'sort'              => $settings['sort'],
			'refresh'           => $settings['refresh'],
			'follow'            => $settings['follow'],
			'offset'            => $settings['offset'],
			'from_datetime'     => $settings['from_datetime'],
			'to_datetime'       => $settings['to_datetime'],
			'disable_lazy'		=> 'yes' == $settings['disable_lazy'] ? 'disable' : '',
			'multiple_meta'     => $display_source ? 'source' : '',
			'type'              => $settings['type'] ? $settings['type'] : '',
			'date_pos'          => isset( $settings['date_pos'] ) ? $settings['date_pos'] : 'left',
			'column'            => $settings['column'] ? $settings['column'] : '3',
			'tab_column'        => $settings['tab_column'] ? $settings['tab_column'] : '2',
			'mb_column'         => $settings['mb_column'] ? $settings['mb_column'] : '1',
			'imgpos'            => isset( $settings['imgpos'] ) ? $settings['imgpos'] : 'left',
			'thumb_size_imgtop' => 'top' == $settings['imgpos'] ? 'penci-thumb' : 'penci-thumb-small',
			'title_length'      => isset( $settings['title_length'] ) ? $settings['title_length'] : '',
			'excerpt_pos'       => $settings['excerpt_pos'] ? $settings['excerpt_pos'] : 'below',
		);
		$feed_settings  = apply_filters( 'penci_rss_feed_widget_shortcode_attributes_filter', $feed_settings, array() );
		// Hide item meta.
		if ( empty( $hide_meta ) ) {
			unset( $feed_settings['meta'] );
		}
		// Hide item title.
		if ( empty( $hide_title ) || 'yes' !== $hide_title ) {
			$feed_settings['title'] = 0;
		}
		// Multiple meta.
		if ( ! empty( $multiple_meta ) ) {
			$feed_settings['multiple_meta'] = $multiple_meta;
		}
		$this->markup_block_title( $settings, $this );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo \Penci_Rss_Feed::get_rss( $feed_settings );
	}
}