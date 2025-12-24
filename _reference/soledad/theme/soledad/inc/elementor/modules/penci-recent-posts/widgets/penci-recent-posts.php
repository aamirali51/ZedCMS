<?php

namespace PenciSoledadElementor\Modules\PenciRecentPosts\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Modules\QueryControl\Module as Query_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciRecentPosts extends Base_Widget {

	public function get_name() {
		return 'penci-recent-posts';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Widget Recent/Popular Posts', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'posts' );
	}

	public function get_script_depends() {
		return [ 'penci_widgets_ajax' ];
	}

	protected function register_controls() {


		// Section layout
		$this->start_controls_section(
			'section_page_layout', array(
				'label' => esc_html__( 'Layout', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'penci_size', array(
				'label'                => __( 'Image Size Type', 'soledad' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => '',
				'options'              => array(
					''           => esc_html__( 'Default', 'soledad' ),
					'horizontal' => esc_html__( 'Horizontal Size', 'soledad' ),
					'square'     => esc_html__( 'Square Size', 'soledad' ),
					'vertical'   => esc_html__( 'Vertical Size', 'soledad' ),
					'custom'     => esc_html__( 'Custom', 'soledad' ),
				),
				'selectors'            => array( '{{WRAPPER}} .penci-image-holder:before' => '{{VALUE}}', ),
				'selectors_dictionary' => array(
					'horizontal' => 'padding-top: 66.6667%;',
					'square'     => 'padding-top: 100%;',
					'vertical'   => 'padding-top: 135.4%;',
				),
			)
		);
		$this->add_responsive_control(
			'penci_img_ratio', array(
				'label'          => __( 'Image Ratio', 'soledad' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array( 'size' => 0.66 ),
				'tablet_default' => array( 'size' => '' ),
				'mobile_default' => array( 'size' => 0.5 ),
				'range'          => array( 'px' => array( 'min' => 0.1, 'max' => 2, 'step' => 0.01 ) ),
				'selectors'      => array(
					'{{WRAPPER}} .penci-image-holder:before' => 'padding-top: calc( {{SIZE}} * 100% );',
				),
				'condition'      => array( 'penci_size' => 'custom' ),
			)
		);
		$this->add_control(
			'thumb_size', array(
				'label'     => __( 'Custom Image size', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => $this->get_list_image_sizes( true ),
				'condition' => array( 'penci_size' => 'custom' ),
			)
		);

		$this->add_control(
			'thumb_bigsize', array(
				'label'     => __( 'Custom Image size for Featured Posts', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => $this->get_list_image_sizes( true ),
				'condition' => array( 'penci_size' => 'custom' ),
			)
		);

		$this->add_control(
			'title_length', array(
				'label'       => __( 'Custom words length for post titles', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'If your post titles is too long - You can use this option for trim it. Fill number value here.', 'soledad' ),
			)
		);

		$this->add_control(
			'dotstyle', array(
				'label'   => __( 'Show Timeline Dots?', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''   => esc_html__( "Don't Show", 'soledad' ),
					's1' => esc_html__( 'Show with Color Style', 'soledad' ),
					's2' => esc_html__( 'Show with Hover Style', 'soledad' ),
					's3' => esc_html__( 'Show with Animation Style', 'soledad' ),
				),
			)
		);

		$this->add_control(
			'movemeta', array(
				'label'   => __( 'Move post meta to display above post title?', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'hide_thumb', array(
				'label'   => __( 'Hide thumbnail', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);
		$this->add_control(
			'thumbright', array(
				'label'     => __( 'Display thumbnail on right?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array( 'hide_thumb!' => 'yes' ),
			)
		);
		$this->add_control(
			'twocolumn', array(
				'label'       => __( 'Display on 2 columns?', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'If you use 2 columns option, it will ignore option display thumbnail on right.', 'soledad' ),
			)
		);
		$this->add_control(
			'featured', array(
				'label' => __( 'Display 1st post featured?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'featured2', array(
				'label' => __( 'Display featured post style 2?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'allfeatured', array(
				'label'       => __( 'Display all post featured?', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'If you use all post featured option, it will ignore option display thumbnail on right & 2 columns.', 'soledad' ),
			)
		);
		$this->add_control(
			'show_author', array(
				'label' => __( 'Show Author Name?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'hide_postdate', array(
				'label' => __( 'Hide post date?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'show_comment', array(
				'label' => __( 'Show Comment Count?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'show_postviews', array(
				'label' => __( 'Show Post Views?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'icon_format', array(
				'label' => __( 'Enable icon post format?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'ordernum', array(
				'label' => __( 'Show the order numbers?', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'ordernum_style', array(
				'label'     => __( 'Order Number Style', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => [ 'ordernum' => 'yes' ],
				'default'   => '1',
				'options'   => array(
					'1' => esc_html__( 'Style 1', 'soledad' ),
					'2' => esc_html__( 'Style 2', 'soledad' ),
					'3' => esc_html__( 'Style 3', 'soledad' ),
					'4' => esc_html__( 'Style 4', 'soledad' ),
				),
			)
		);

		$this->add_responsive_control(
			'imgwidth', array(
				'label'       => __( 'Custom Image Width', 'soledad' ),
				'description' => __( 'This option doesn\'t apply for featured posts.', 'soledad' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
				'selectors'   => array(
					'{{WRAPPER}} ul.side-newsfeed li .penci-image-holder.small-fix-size' => 'width: {{SIZE}}px'
				),
				'condition'   => array( 'hide_thumb!' => 'yes' ),
			)
		);

		$this->add_control(
			'showborder', array(
				'label'     => __( 'Remove Border at The Bottom?', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} ul.side-newsfeed:not(.penci-feed-2columns) li' 				=> 'border-bottom: none',
					'{{WRAPPER}} .side-newsfeed.penci-vscroll .js-marquee-wrapper .js-marquee'  => 'border-bottom: none'
				)
			)
		);

		$this->add_control(
			'rp_rows_gap', array(
				'label'              => __( 'Rows Gap', 'soledad' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => array( 'px' => array( 'min' => 0, 'max' => 200 ) ),
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}} .side-newsfeed.penci-vscroll .js-marquee-wrapper .js-marquee' => 'margin-bottom: calc({{SIZE}}{{UNIT}}/2); padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} ul.side-newsfeed:not(.penci-feed-2columns) li' 			   => 'margin-bottom: calc({{SIZE}}{{UNIT}}/2); padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} ul.penci-feed-2columns li'                     			   => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control( 'ajaxnav', array(
			'label'   => __( 'Ajax Posts Navigation?', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''    => 'Disable',
				'nav' => 'Next/Previous Buttons',
				'btn' => 'Load More Posts Button',
			]
		) );
		
		$this->add_control( 'vertical_scroll', array(
			'label'   	  => __( 'Enable Vertical Scroll?', 'soledad' ),
			'description' => __( 'This option only affects the front end.', 'soledad' ),
			'type'    	  => Controls_Manager::SWITCHER,
		) );
		
		$this->add_responsive_control( 'vertical_scroll_height', array(
			'label'   	=> __( 'Scroll Height', 'soledad' ),
			'type'    	=> Controls_Manager::SLIDER,
			'condition' => [ 'vertical_scroll' => 'yes' ],
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 2000, ) ),
			'selectors' => array( '{{WRAPPER}} ul.side-newsfeed' => 'max-height: {{SIZE}}px' ),
		) );
		
		$this->add_control( 'vertical_scroll_speed', array(
			'label'   	=> __( 'Scroll Speed', 'soledad' ),
			'type'    	=> Controls_Manager::NUMBER,
			'condition' => [ 'vertical_scroll' => 'yes' ],
		) );
		
		$this->add_control( 'vertical_scroll_spacing', array(
			'label'   	=> __( 'Scroll Spacing', 'soledad' ),
			'type'    	=> Controls_Manager::NUMBER,
			'default' 	=> 20,
			'condition' => [ 'vertical_scroll' => 'yes' ],
		) );

		$this->end_controls_section();
		$this->register_query_section_controls();
		$this->register_block_title_section_controls();
		$this->start_controls_section(
			'section_post_style',
			array(
				'label' => __( 'Post', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'pborder_color', array(
				'label'     => __( 'Borders Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .side-newsfeed.penci-vscroll .js-marquee-wrapper .js-marquee'                                                   => 'border-bottom: 1px solid {{VALUE}};',
					'{{WRAPPER}} ul.side-newsfeed li, {{WRAPPER}} .side-newsfeed.pctlst'                                                         => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .side-newsfeed.pctlst.pctl-s2 .penci-feed:before, {{WRAPPER}} .side-newsfeed.pctlst.pctl-s3 .penci-feed:before' => 'background-color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'pborder_timeline', array(
				'label'     => __( 'Border Colors for Timeline Dots', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'dotstyle!' => '' ),
				'selectors' => array(
					'{{WRAPPER}} .side-newsfeed.pctlst' => 'border-color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'dotbg', array(
				'label'     => __( 'Dots Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'dotstyle!' => '' ),
				'selectors' => array(
					'{{WRAPPER}} .side-newsfeed.pctlst .penci-feed:before' => 'background-color: {{VALUE}};',
				)
			)
		);

		$this->add_control(
			'dothbg', array(
				'label'     => __( 'Dots Hover/Animation Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'dotstyle' => array( 's2', 's3' ) ),
				'selectors' => array(
					'{{WRAPPER}} .side-newsfeed.pctlst.pctl-s2 .penci-feed:hover:before,{{WRAPPER}} .side-newsfeed.pctlst.pctl-s3 .penci-feed:after' => 'background-color: {{VALUE}};',
				)
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'ordernum_typo',
				'label'    => __( 'Order Number Typo', 'soledad' ),
				'selector' => '{{WRAPPER}} ul.order-numbers-3 li .number-post,{{WRAPPER}} .order-numbers-4.display-order-numbers li .side-image:before,{{WRAPPER}} ul.side-newsfeed li .number-post,{{WRAPPER}} .order-numbers-2.display-order-numbers li .side-item-text:before',
			)
		);
		$this->add_control(
			'ordernum_bgcolor', array(
				'label'     => __( 'Order Number Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [ 'ordernum_style!' => '2' ],
				'selectors' => array(
					'{{WRAPPER}} ul.order-numbers-3 li .number-post'                           => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .order-numbers-4.display-order-numbers li .side-image:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} ul.side-newsfeed li .number-post'                             => 'background-color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'ordernum_color', array(
				'label'     => __( 'Order Number Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul.order-numbers-3 li .number-post'                               => 'color: {{VALUE}};',
					'{{WRAPPER}} .order-numbers-4.display-order-numbers li .side-image:before'     => 'color: {{VALUE}};',
					'{{WRAPPER}} ul.side-newsfeed li .number-post'                                 => 'color: {{VALUE}};',
					'{{WRAPPER}} .order-numbers-2.display-order-numbers li .side-item-text:before' => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'ordernum_bdcolor', array(
				'label'     => __( 'Order Number Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [ 'ordernum_style!' => '2' ],
				'selectors' => array(
					'{{WRAPPER}} ul.order-numbers-3 li .number-post'                           => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .order-numbers-4.display-order-numbers li .side-image:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} ul.side-newsfeed li .number-post'                             => 'border-color: {{VALUE}};',
				)
			)
		);
		$this->add_responsive_control(
			'ordernum_size', array(
				'label'     => __( 'Order Number Box Size', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => '' ),
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'condition' => [ 'ordernum_style!' => '2' ],
				'selectors' => array(
					'{{WRAPPER}} ul.side-newsfeed'                                                                                                      => '--oboxsize: {{SIZE}}px;',
					'{{WRAPPER}} ul.side-newsfeed.order-numbers-1 li .order-border-number,{{WRAPPER}} ul.side-newsfeed.order-numbers-1 li .number-post' => 'width: {{SIZE}}px;height: {{SIZE}}px;line-height:calc({{SIZE}}px - 2px);',
				)
			)
		);
		$this->add_control(
			'heading_ptitle_style', array(
				'label' => __( 'Post Title', 'soledad' ),
				'type'  => Controls_Manager::HEADING
			)
		);

		$this->add_control(
			'ptitle_color', array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul.side-newsfeed li .side-item .side-item-text h4 a' => 'color: {{VALUE}};',
					'{{WRAPPER}} ul.side-newsfeed li .side-item .side-item-text h4'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .popularpost_item .pcpopular_new_post_title'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .popularpost_item .pcpopular_new_post_title a'       => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'ptitle_hcolor', array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul.side-newsfeed li .side-item .side-item-text h4 a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .popularpost_item .pcpopular_new_post_title a:hover'       => 'color: {{VALUE}};',
				)
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'ptitle_typo',
				'selector' => '{{WRAPPER}} ul.side-newsfeed li .side-item .side-item-text h4 a,{{WRAPPER}}  ul.side-newsfeed li .side-item .side-item-text h4, {{WRAPPER}} .popularpost_item .pcpopular_new_post_title a, {{WRAPPER}} .popularpost_item .pcpopular_new_post_title'
			)
		);

		$this->add_responsive_control(
			'featitle_size', array(
				'label'     => __( 'Font size for Featured/Big Post', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
				'selectors' => array(
					'{{WRAPPER}} ul.side-newsfeed li.featured-news .side-item .side-item-text h4 a' => 'font-size: {{SIZE}}px'
				),
			)
		);

		$this->add_control(
			'heading_pmeta_style', array(
				'label' => __( 'Post Meta', 'soledad' ),
				'type'  => Controls_Manager::HEADING
			)
		);

		$this->add_control(
			'pmeta_color', array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul.side-newsfeed li .side-item .side-item-text .side-item-meta' => 'color: {{VALUE}};',
					'{{WRAPPER}} .popularpost_meta'                                              => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'pmeta_acolor', array(
				'label'     => __( 'Color for Author Name & Comment Count', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul.side-newsfeed li .side-item .side-item-text .side-item-meta a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .popularpost_meta a'                                              => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'pmeta_ahcolor', array(
				'label'     => __( 'Hover Color for Author Name & Comment Count', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul.side-newsfeed li .side-item .side-item-text .side-item-meta a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .popularpost_meta a:hover'                                              => 'color: {{VALUE}};',
				)
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'pmeta_typo',
				'selector' => '{{WRAPPER}} ul.side-newsfeed li .side-item .side-item-text .side-item-meta, {{WRAPPER}} .popularpost_meta',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'post_navigation_style', array(
			'label'     => __( 'Post Navigation', 'soledad' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'ajaxnav!' => '' ]
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'post_nav_btn_typo',
			'label'    => __( 'Button Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn .pcnav-title, {{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button',
		) );

		$this->add_control( 'post_nav_btn_color', array(
			'label'     => __( 'Button Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn .pcnav-title' => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button'                                                       => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'post_nav_btn_bdcolor', array(
			'label'     => __( 'Button Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn .pcnav-title' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button'                                                       => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'post_nav_btn_bgcolor', array(
			'label'     => __( 'Button Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn .pcnav-title' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button'                                                       => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'post_nav_btn_hcolor', array(
			'label'     => __( 'Button Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover .pcnav-title' => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button:hover'                                                             => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'post_nav_btn_bdhcolor', array(
			'label'     => __( 'Button Border Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover .pcnav-title' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button:hover'                                                             => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'post_nav_btn_bghcolor', array(
			'label'     => __( 'Button Background Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover,{{WRAPPER}} .penci-pagination.penci-ajax-nav .pcajx-btn:hover .pcnav-title' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .penci-pagination.penci-ajax-more a.penci-ajax-more-button:hover'                                                             => 'background-color: {{VALUE}};',
			),
		) );

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

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
		$settings         = $this->get_settings();
		$penci_size       = isset( $settings['penci_size'] ) ? $settings['penci_size'] : '';
		$dotstyle         = $settings['dotstyle'] ? $settings['dotstyle'] : '';
		$thumb_size       = $settings['thumb_size'] ? $settings['thumb_size'] : '';
		$thumb_bigsize    = $settings['thumb_bigsize'] ? $settings['thumb_bigsize'] : '';
		$original_postype = $settings['posts_post_type'];

		if ( in_array( $original_postype, [
				'current_query',
				'related_posts'
			] ) && penci_elementor_is_edit_mode() && penci_is_builder_template() ) {
			$settings['posts_post_type'] = 'post';
		}

		$query_args = Query_Control::get_query_args( 'posts', $settings );
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
		$loop = new \WP_Query( $query_args );

		if ( ! $loop->have_posts() ) {
			return;
		}

		$title_length = intval( $settings['title_length'] );
		$featured     = 'yes' == $settings['featured'] ? true : false;
		$movemeta     = 'yes' == $settings['movemeta'] ? true : false;
		$twocolumn    = 'yes' == $settings['twocolumn'] ? true : false;
		$featured2    = 'yes' == $settings['featured2'] ? true : false;
		$allfeatured  = 'yes' == $settings['allfeatured'] ? true : false;
		$thumbright   = 'yes' == $settings['thumbright'] ? true : false;
		$postdate     = 'yes' == $settings['hide_postdate'] ? true : false;
		$showauthor   = 'yes' == $settings['show_author'] ? true : false;
		$showcomment  = 'yes' == $settings['show_comment'] ? true : false;
		$showviews    = 'yes' == $settings['show_postviews'] ? true : false;
		$icon         = 'yes' == $settings['icon_format'] ? true : false;
		$ordernum     = 'yes' == $settings['ordernum'] ? true : false;
		$vscroll      = 'yes' == $settings['vertical_scroll'] ? true : false;

		if ( $vscroll ) {
			wp_enqueue_script( 'penci-marquee-widget' );
		}

		$ajax_tab_var = [
			'title_length'   => intval( $settings['title_length'] ),
			'featured'       => $settings['featured'],
			'movemeta'       => $settings['movemeta'],
			'twocolumn'      => $settings['twocolumn'],
			'featured2'      => $settings['featured2'],
			'allfeatured'    => $settings['allfeatured'],
			'thumbright'     => $settings['thumbright'],
			'hide_postdate'  => $settings['hide_postdate'],
			'show_author'    => $settings['show_author'],
			'show_comment'   => $settings['show_comment'],
			'show_postviews' => $settings['show_postviews'],
			'icon_format'    => $settings['icon_format'],
			'ordernum'       => $settings['ordernum'],
			'custom_query'   => $query_args,
		];


		$css_class = 'penci-block-vc penci_recent-posts-sc widget';
		$rand      = rand( 1000, 10000 );
		?>
        <div class="<?php echo esc_attr( $css_class ); ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
            <div class="penci-block_content">

                <ul id="penci-latestwg-<?php echo sanitize_text_field( $rand ); ?>"
					<?php if ( $vscroll ): ?>
						data-vscroll-speed="<?php echo esc_attr( $settings['vertical_scroll_speed'] ); ?>"
						data-vscroll-spacing="<?php echo esc_attr( $settings['vertical_scroll_spacing'] ); ?>"
					<?php endif; ?>
					<?php if ( isset( $settings['ajaxnav'] ) && $settings['ajaxnav'] ) { ?>
                        data-settings='<?php echo json_encode( $ajax_tab_var ); ?>'
                        data-paged="1"
                        data-type="elementor"
                        data-action="penci_latest_news_widget_ajax"
                        data-mes="<?php echo penci_get_setting( 'penci_trans_no_more_posts' ); ?>"
                        data-max="<?php echo esc_attr( $loop->max_num_pages ); ?>"
					<?php } ?>
                    class="side-newsfeed<?php if ( $twocolumn && ! $allfeatured ): echo ' penci-feed-2columns';
						if ( $featured ) {
							echo ' penci-2columns-featured';
						} else {
							echo ' penci-2columns-feed';
						} endif;
					if ( $dotstyle ) {
						echo ' pctlst pctl-' . $dotstyle;
					}
					if ( $ordernum ) {
						echo ' display-order-numbers order-numbers-' . $settings['ordernum_style'];
					}
					if ( $vscroll ) {
						echo ' penci-vscroll';
					}
					?>">
					<?php $num = 1;
					while ( $loop->have_posts() ) : $loop->the_post(); ?>
                        <li class="penci-feed<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ): echo ' featured-news';
							if ( $featured2 ): echo ' featured-news2'; endif; endif; ?><?php if ( $allfeatured ): echo ' all-featured-news'; endif; ?>">
							<?php if ( $ordernum && has_post_thumbnail() && ! $settings['hide_thumb'] ): ?>
                                <span class="order-border-number<?php if ( $thumbright && ! $twocolumn ): echo ' right-side'; endif; ?>">
									<span class="number-post"><?php echo sanitize_text_field( $num ); ?></span>
								</span>
							<?php endif; ?>
                            <div class="side-item">
								<?php if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) && ! $settings['hide_thumb'] ) : ?>
                                    <div class="side-image<?php if ( $thumbright ): echo ' thumbnail-right'; endif; ?>">
										<?php
										$size_pie = 'small';
										if ( ( ( $num == 1 ) && $featured ) || $allfeatured ): $size_pie = 'normal'; endif;
										do_action( 'penci_bookmark_post', get_the_ID(), $size_pie );
										/* Display Review Piechart  */
										if ( function_exists( 'penci_display_piechart_review_html' ) ) {
											penci_display_piechart_review_html( get_the_ID(), $size_pie );
										}

										$thumb = penci_featured_images_size( 'small' );
										if ( 'horizontal' == $penci_size ) {
											$thumb = 'penci-thumb-small';
										} else if ( 'square' == $penci_size ) {
											$thumb = 'penci-thumb-square';
										} else if ( 'vertical' == $penci_size ) {
											$thumb = 'penci-thumb-vertical';
										} else if ( 'custom' == $penci_size ) {
											if ( $thumb_size ) {
												$thumb = $thumb_size;
											}
										}
										if ( ( ( $num == 1 ) && $featured ) || $allfeatured ) {
											$thumb = penci_featured_images_size();
											if ( 'horizontal' == $penci_size ) {
												$thumb = 'penci-thumb';
											} else if ( 'square' == $penci_size ) {
												$thumb = 'penci-thumb-square';
											} else if ( 'vertical' == $penci_size ) {
												$thumb = 'penci-thumb-vertical';
											} else if ( 'custom' == $penci_size ) {
												if ( $thumb_bigsize ) {
													$thumb = $thumb_bigsize;
												}
											}
										}
										?>

                                        <a <?php echo penci_layout_bg( penci_image_srcset( get_the_ID(), $thumb ) ); ?>
                                                class="<?php echo penci_layout_bg_class(); ?> penci-image-holder<?php if ( ( ( $num == 1 ) && $featured ) || $allfeatured ) {
													echo '';
												} else {
													echo ' small-fix-size';
												} ?>" rel="bookmark"
                                                href="<?php the_permalink(); ?>"
                                                title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
											<?php echo penci_layout_img( penci_image_srcset( get_the_ID(), $thumb ), get_the_title() ); ?>
                                        </a>


										<?php if ( $icon ): ?>
											<?php if ( has_post_format( 'video' ) ) : ?>
                                                <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                   aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-play' ); ?></a>
											<?php endif; ?>
											<?php if ( has_post_format( 'audio' ) ) : ?>
                                                <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                   aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-music' ); ?></a>
											<?php endif; ?>
											<?php if ( has_post_format( 'link' ) ) : ?>
                                                <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                   aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-link' ); ?></a>
											<?php endif; ?>
											<?php if ( has_post_format( 'quote' ) ) : ?>
                                                <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                   aria-label="Icon"><?php penci_fawesome_icon( 'fas fa-quote-left' ); ?></a>
											<?php endif; ?>
											<?php if ( has_post_format( 'gallery' ) ) : ?>
                                                <a href="<?php the_permalink() ?>" class="icon-post-format"
                                                   aria-label="Icon"><?php penci_fawesome_icon( 'far fa-image' ); ?></a>
											<?php endif; ?>
										<?php endif; ?>
                                    </div>
								<?php endif; ?>
                                <div class="side-item-text">
									<?php if ( $movemeta && ( ! $postdate || $showauthor || $showcomment || $showviews ) ): ?>
                                        <div class="grid-post-box-meta penci-side-item-meta pcsnmt-above">
											<?php if ( $showauthor ): ?>
                                                <span class="side-item-meta side-wauthor"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <a
                                                            class="url fn n"
                                                            href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></span>
											<?php endif; ?>
											<?php if ( ! $postdate ): ?>
                                                <span class="side-item-meta side-wdate"><?php penci_soledad_time_link(); ?></span>
											<?php endif; ?>
											<?php if ( $showcomment ): ?>
                                                <span class="side-item-meta side-wcomments"><a
                                                            href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
											<?php endif; ?>
											<?php if ( $showviews ): ?>
                                                <span class="side-item-meta side-wviews"><?php echo penci_get_post_views( get_the_ID() ) . ' ' . penci_get_setting( 'penci_trans_countviews' ); ?></span>
											<?php endif; ?>
											<?php do_action( 'penci_extra_meta' ); ?>
                                        </div>
									<?php endif; ?>

                                    <h4 class="side-title-post">
                                        <a href="<?php the_permalink() ?>" rel="bookmark"
                                           title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
											<?php
											if ( ! $title_length || ! is_numeric( $title_length ) ) {
												if ( $featured2 && ( ( ( $num == 1 ) && $featured ) || $allfeatured ) ) {
													echo wp_trim_words( wp_strip_all_tags( get_the_title() ), 12, '...' );
												} else {
													the_title();
												}
											} else {
												echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $title_length, '...' );
											}
											?>
                                        </a>
                                    </h4>
									<?php if ( ! $movemeta && ( ! $postdate || $showauthor || $showcomment || $showviews ) ): ?>
                                        <div class="grid-post-box-meta penci-side-item-meta pcsnmt-below">
											<?php if ( $showauthor ): ?>
                                                <span class="side-item-meta side-wauthor"><?php echo penci_get_setting( 'penci_trans_by' ); ?> <a
                                                            class="url fn n"
                                                            href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></span>
											<?php endif; ?>
											<?php if ( ! $postdate ): ?>
                                                <span class="side-item-meta side-wdate"><?php penci_soledad_time_link(); ?></span>
											<?php endif; ?>
											<?php if ( $showcomment ): ?>
                                                <span class="side-item-meta side-wcomments"><a
                                                            href="<?php comments_link(); ?> "><?php comments_number( '0 ' . penci_get_setting( 'penci_trans_comments' ), '1 ' . penci_get_setting( 'penci_trans_comment' ), '% ' . penci_get_setting( 'penci_trans_comments' ) ); ?></a></span>
											<?php endif; ?>
											<?php if ( $showviews ): ?>
                                                <span class="side-item-meta side-wviews"><?php echo penci_get_post_views( get_the_ID() ) . ' ' . penci_get_setting( 'penci_trans_countviews' ); ?></span>
											<?php endif; ?>
											<?php do_action( 'penci_extra_meta' ); ?>
                                        </div>
									<?php endif; ?>
                                </div>
                            </div>
                        </li>
						<?php $num ++; endwhile; ?>
                </ul>
				<?php

				if ( isset( $settings['ajaxnav'] ) && $settings['ajaxnav'] == 'btn' ) {
					?>
                    <div class="penci-pagination penci-ajax-more pcwg-lposts">
                        <a class="penci-ajax-more-button penci-wgajx-btn"
                           href="#" aria-label="More Posts">
                            <span class="ajax-more-text"><?php echo penci_get_setting( 'penci_trans_load_more_posts' ); ?></span><span
                                    class="ajaxdot"></span><i
                                    class="penci-faicon fa fa-refresh"></i> </a>
                    </div>
					<?php
				} else if ( isset( $settings['ajaxnav'] ) && $settings['ajaxnav'] == 'nav' ) { ?>
                    <div class="penci-pagination penci-ajax-nav pcwg-lposts">
                        <span class="pcajx-btn penci-wgajx-btn prev disable"><?php echo penci_icon_by_ver( 'penciicon-left-chevron' ) . ' <span class="pcnav-title">' . penci_get_setting( 'penci_trans_back' ); ?></span></span>
                        <span class="pcajx-btn penci-wgajx-btn next"><?php echo '<span class="pcnav-title">' . penci_get_setting( 'penci_trans_next' ) . '</span>' . penci_icon_by_ver( 'penciicon-right-chevron' ); ?></span>
                    </div>
					<?php
				}
				if ( isset( $settings['ajaxnav'] ) && $settings['ajaxnav'] ) {
					?>
                    <div class="pcwgajx-ld-wrapper">
						<?php echo penci_get_html_animation_loading( 'df' ); ?>
                    </div>
					<?php
				}
				wp_reset_postdata();
				?>
            </div>
        </div>
		<?php
	}
}
