<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleRelatedpost extends \Elementor\Widget_Base {

	public function get_name() {
		return 'penci-single-related-posts';
	}

	public function get_title() {
		return esc_html__( 'Post - Related Posts', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'post', 'related' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'penci_post_related_text',
			[
				'label' => esc_html__( 'Related Heading Text', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
				'default'  => penci_get_setting('penci_post_related_text'),
			]
		);

        $this->add_control(
			'penci_hide_heading',
			[
				'label' => esc_html__( 'Use Custom Heading Style?', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'penci_post_related_autoplay',
			[
				'label' => esc_html__( 'Related Posts Carousel Auto Play', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'penci_numbers_related_post',
			[
				'label' => esc_html__( 'Number of Related Posts', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::NUMBER,
				 'default' => 4,
			]
		);

		$this->add_control(
			'penci_related_style',
			[
				'label'   => esc_html__( 'Related Posts Style', 'soledad' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'style-1',
				'options' => [
					'style-1' => 'Style 1',
					'style-2' => 'Style 2',
					'style-3' => 'Style 3',
					'style-4' => 'Style 4',
					'style-5' => 'Style 5',
					'style-5' => 'Style 6',
				]
			]
		);

		$this->add_control(
			'penci_related_orderby',
			[
				'label'   => esc_html__( 'Order Related Posts By', 'soledad' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
				'options' => [
					'rand'          => 'Random Posts',
					'date'          => 'Published Date',
					'ID'            => 'Post ID',
					'modified'      => 'Modified Date',
					'title'         => 'Post Title',
					'comment_count' => 'Comment Count',
					'popular'       => 'Most Viewed Posts All Time',
					'popular_day'   => 'Most Viewed Posts Daily',
					'popular7'      => 'Most Viewed Posts Once Weekly',
					'popular_month' => 'Most Viewed Posts Once a Month',
				]
			]
		);

		$this->add_control(
			'penci_related_sort_order',
			[
				'label'   => esc_html__( 'Sort Order Related Posts', 'soledad' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
				'options' => [
					'DESC' => 'Descending',
					'ASC'  => 'Ascending '
				]
			]
		);

		$this->add_control(
			'penci_related_posts_title_length',
			[
				'label' => esc_html__( 'Title Length', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::NUMBER,
			]
		);

		$this->add_control(
			'penci_related_by',
			[
				'label'   => esc_html__( 'Related Posts By', 'soledad' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'categories',
				'options' => array(
					'categories'  => 'Categories',
					'tags'        => 'Tags',
					'primary_cat' => 'Primary Category from "Yoast SEO" or "Rank Math" plugin'
				)
			]
		);

		$this->add_control(
			'penci_post_related_grid',
			[
				'label' => esc_html__( 'Make Related Posts Display in a Grid Layout ( not Slider )', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'penci_post_related_dots',
			[
				'label' => esc_html__( 'Hide Dots On Carousel Related Posts', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'penci_post_related_arrows',
			[
				'label' => esc_html__( 'Enable Next/Prev Button On Carousel Related Posts', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'penci_post_related_icons',
			[
				'label' => esc_html__( 'Enable Posts Format Icons in Related Posts', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'penci_hide_date_related',
			[
				'label' => esc_html__( 'Hide Post Date on Related Posts', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

        // heading
		$this->start_controls_section( 'section_title_block', array(
			'label'     => __( 'Heading Title', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
			'condition' => [ 'penci_hide_heading' => 'yes' ]
		) );
		$this->add_control( 'hide_block_heading', array(
			'label' => __( 'Hide Heading Title', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::HIDDEN,
		) );
		$this->add_control( 'heading_title_style', array(
			'label'   => __( 'Choose Style', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => array(
				''                  => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'style-1'           => esc_html__( 'Style 1', 'soledad' ),
				'style-2'           => esc_html__( 'Style 2', 'soledad' ),
				'style-3'           => esc_html__( 'Style 3', 'soledad' ),
				'style-4'           => esc_html__( 'Style 4', 'soledad' ),
				'style-5'           => esc_html__( 'Style 5', 'soledad' ),
				'style-6'           => esc_html__( 'Style 6 - Only Text', 'soledad' ),
				'style-7'           => esc_html__( 'Style 7', 'soledad' ),
				'style-9'           => esc_html__( 'Style 8', 'soledad' ),
				'style-8'           => esc_html__( 'Style 9 - Custom Background Image', 'soledad' ),
				'style-10'          => esc_html__( 'Style 10', 'soledad' ),
				'style-11'          => esc_html__( 'Style 11', 'soledad' ),
				'style-12'          => esc_html__( 'Style 12', 'soledad' ),
				'style-13'          => esc_html__( 'Style 13', 'soledad' ),
				'style-14'          => esc_html__( 'Style 14', 'soledad' ),
				'style-15'          => esc_html__( 'Style 15', 'soledad' ),
				'style-16'          => esc_html__( 'Style 16', 'soledad' ),
				'style-2 style-17'  => esc_html__( 'Style 17', 'soledad' ),
				'style-18'          => esc_html__( 'Style 18', 'soledad' ),
				'style-18 style-19' => esc_html__( 'Style 19', 'soledad' ),
				'style-18 style-20' => esc_html__( 'Style 20', 'soledad' ),
				'style-21'          => __( 'Style 21', 'soledad' ),
				'style-22'          => __( 'Style 22', 'soledad' ),
				'style-23'          => __( 'Style 23', 'soledad' ),
				'style-24'          => __( 'Style 24', 'soledad' ),
				'style-25'          => __( 'Style 25', 'soledad' ),
				'style-26'          => __( 'Style 26', 'soledad' ),
				'style-27'          => __( 'Style 27', 'soledad' ),
				'style-28'          => __( 'Style 28', 'soledad' ),
			)
		) );
		$this->add_control( 'heading', array(
			'label'   => __( 'Heading Title', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::HIDDEN,
			'default' => __( 'Heading Title', 'soledad' ),
		) );
		$this->add_control( 'heading_title_link', array(
			'label'       => __( 'Title url', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::URL,
			'placeholder' => __( 'https://your-link.com', 'soledad' ),
		) );
		$this->add_control( 'block_title_align', array(
			'label'   => __( 'Heading Align', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => array(
				''               => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'pcalign-left'   => esc_html__( 'Left', 'soledad' ),
				'pcalign-center' => esc_html__( 'Center', 'soledad' ),
				'pcalign-right'  => esc_html__( 'Right', 'soledad' )
			)
		) );
		$this->add_control( 'heading_icon_pos', array(
			'label'     => __( 'Align Icon on Style 15', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''              => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'pciconp-right' => esc_html__( 'Right', 'soledad' ),
				'pciconp-left'  => esc_html__( 'Left', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->add_control( 'heading_icon', array(
			'label'     => __( 'Custom Icon on Style 15', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''             => esc_html__( 'Default ( follow Customize )', 'soledad' ),
				'pcicon-right' => esc_html__( 'Arrow Right', 'soledad' ),
				'pcicon-left'  => esc_html__( 'Arrow Left', 'soledad' ),
				'pcicon-down'  => esc_html__( 'Arrow Down', 'soledad' ),
				'pcicon-up'    => esc_html__( 'Arrow Up', 'soledad' ),
				'pcicon-star'  => esc_html__( 'Star', 'soledad' ),
				'pcicon-bars'  => esc_html__( 'Bars', 'soledad' ),
				'pcicon-file'  => esc_html__( 'File', 'soledad' ),
				'pcicon-fire'  => esc_html__( 'Fire', 'soledad' ),
				'pcicon-book'  => esc_html__( 'Book', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->end_controls_section();
		// endheading

		$this->start_controls_section(
			'color_style',
			[
				'label' => esc_html__( 'Color & Styles', 'soledad' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control( 'heading_align', [
			'label'   => __( 'Content Align', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::CHOOSE,
			'default' => 'center',
			'options' => array(
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
			'toggle'  => true,
			'selectors' => ['{{WRAPPER}} .item-related' =>'text-align:{{VALUE}}']
		] );

        $this->add_responsive_control(
			'post_thumbnail_ratio',
			[
				'label' => 'Post Thumbnail Ratio',
				'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER }} .penci-image-holder::before' => 'padding-top: {{SIZE}}%',
				],
			]
		);

        $this->add_responsive_control(
			'post_thumbnail_spacing',
			[
				'label' => 'Spacing Between Thumbnail & Title',
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER }} .item-related > a' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

        $this->add_responsive_control(
			'post_header_spacing',
			[
				'label' => 'Spacing Between Post Title & Meta',
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER }} .item-related span.date' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'post_heading_settings',
			[
				'label' => 'Heading Title',
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(), array(
				'name'     => 'heading_title_typo',
				'label'    => __( 'Typography for Heading Title', 'soledad' ),
				'selector' => '{{WRAPPER}} .post-title-box h4',
			)
		);

		$this->add_control(
			'heading_title_color',
			[
				'label'     => 'Heading Title Color',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .post-title-box h4' => 'color:{{VALUE}}' ],
			]
		);

        $this->add_control(
			'heading_title_border',
			[
				'label'     => 'Heading Title Borders Color',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .post-title-box h4:before,{{WRAPPER}} .post-title-box h4:after' => 'background-color:{{VALUE}}' ],
			]
		);

		$this->add_control(
			'post_title_settings',
			[
				'label' => 'Post Title',
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(), array(
				'name'     => 'post_title_typo',
				'label'    => __( 'Typography for Post Title', 'soledad' ),
				'selector' => '{{WRAPPER}} .post-related .item-related > h3 a',
			)
		);

		$this->add_control(
			'post_title_color',
			[
				'label'     => 'Post Title Color',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .post-related .item-related > h3 a' => 'color:{{VALUE}}' ],
			]
		);

		$this->add_control(
			'post_title_hcolor',
			[
				'label'     => 'Post Title Hover Color',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .post-related .item-related > h3 a:hover' => 'color:{{VALUE}}' ],
			]
		);

		$this->add_control(
			'post_date_settings',
			[
				'label' => 'Post Date',
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(), array(
				'name'     => 'post_date_typo',
				'label'    => __( 'Typography for Post Date', 'soledad' ),
				'selector' => '{{WRAPPER}} .post-related .item-related .date',
			)
		);

		$this->add_control(
			'post_date_color',
			[
				'label'     => 'Post Date Color',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .post-related .item-related .date' => 'color:{{VALUE}}' ],
			]
		);

		$this->end_controls_section();

        /* custom heading style*/
		$this->start_controls_section( 'section_title_block_style', array(
			'label'     => __( 'Block Heading Title', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
			'condition' => [ 'penci_hide_heading' => 'yes' ]
		) );

		$this->add_control( 'block_title_color', array(
			'label'     => __( 'Title Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-border-arrow .inner-arrow'                                   => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-border-arrow .inner-arrow a'                                 => 'color: {{VALUE}};',
				'{{WRAPPER}} .home-pupular-posts-title, {{WRAPPER}} .home-pupular-posts-title a' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'block_title_hcolor', array(
			'label'     => __( 'Title Hover Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-border-arrow .inner-arrow a:hover' => 'color: {{VALUE}} !important;',
				'{{WRAPPER}} .home-pupular-posts-title a:hover'        => 'color: {{VALUE}} !important;',
			),
		) );
		$this->add_control( 'block_title_bcolor', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-border-arrow .inner-arrow,' . '{{WRAPPER}} .style-4.penci-border-arrow .inner-arrow:before,' . '{{WRAPPER}} .style-4.penci-border-arrow .inner-arrow:after,' . '{{WRAPPER}} .style-5.penci-border-arrow,' . '{{WRAPPER}} .style-7.penci-border-arrow,' . '{{WRAPPER}} .style-9.penci-border-arrow' => 'border-color: {{VALUE}}',
				'{{WRAPPER}} .penci-border-arrow:before'                                                                                                                                                                                                                                                                               => 'border-top-color: {{VALUE}}',
				'{{WRAPPER}} .style-16.penci-border-arrow:after'                                                                                                                                                                                                                                                                       => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .penci-home-popular-posts'                                                                                                                                                                                                                                                                                => 'border-top-color: {{VALUE}}',
			)
		) );
		$this->add_control( 'btitle_outer_bcolor', array(
			'label'     => __( 'Borders Outer Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}}  .penci-border-arrow:after' => 'border-color: {{VALUE}};'
			)
		) );
		$this->add_control( 'btitle_style10_btopcolor', array(
			'label'     => __( 'Borders Top', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-homepage-title.style-10' => 'border-top-color: {{VALUE}};'
			),
			'condition' => array( 'heading_title_style' => 'style-10' ),
		) );

		$this->add_control( 'btitle_style5_bcolor', array(
			'label'     => __( 'Borders Bottom', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .style-5.penci-border-arrow'              => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .penci-homepage-title.style-10'           => 'border-bottom-color: {{VALUE}};',
				'{{WRAPPER}} .style-12.penci-border-arrow'             => 'border-bottom-color: {{VALUE}};',
				'{{WRAPPER}} .style-11.penci-border-arrow'             => 'border-bottom-color: {{VALUE}};',
				'{{WRAPPER}} .style-5.penci-border-arrow .inner-arrow' => 'border-bottom-color: {{VALUE}};',
			),
			'condition' => array( 'heading_title_style' => array( 'style-5', 'style-10', 'style-11', 'style-12' ) ),
		) );
		$this->add_control( 'btitle_style78_bcolor', array(
			'label'     => __( 'Small Borders Bottom on Style 7 & 8', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .style-7.penci-border-arrow .inner-arrow:before' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .style-9.penci-border-arrow .inner-arrow:before' => 'background-color: {{VALUE}};'
			),
			'condition' => array( 'heading_title_style' => array( 'style-7', 'style-9' ) ),
		) );
		$this->add_control( 'btitle_shapes_color', array(
			'label'     => __( 'Background Color for Shapes', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .style-13.pcalign-center .inner-arrow:before,{{WRAPPER}} .style-13.pcalign-right .inner-arrow:before'                                         => 'border-left-color: {{VALUE}};',
				'{{WRAPPER}} .style-13.pcalign-center .inner-arrow:after,{{WRAPPER}} .style-13.pcalign-left .inner-arrow:after'                                            => ' border-right-color: {{VALUE}};',
				'{{WRAPPER}} .style-12 .inner-arrow:before,{{WRAPPER}} .style-12.pcalign-right .inner-arrow:after,{{WRAPPER}} .style-12.pcalign-center .inner-arrow:after' => ' border-bottom-color: {{VALUE}};',
				'{{WRAPPER}} .style-11 .inner-arrow:after,{{WRAPPER}} .style-11 .inner-arrow:before'                                                                       => ' border-top-color: {{VALUE}};'
			),
			'condition' => array( 'heading_title_style' => array( 'style-13', 'style-11', 'style-12' ) ),
		) );

		$this->add_control( 'btitle_inshapes_color', array(
			'label'     => __( 'Background Color for Shapes Inside', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .style-11 .inner-arrow:after,{{WRAPPER}} .style-11 .inner-arrow:before' => ' border-right-color: {{VALUE}};'
			),
			'condition' => array( 'heading_title_style' => array( 'style-11' ) ),
		) );

		$this->add_control( 'bgstyle15_color', array(
			'label'       => __( 'Background Color for Icon', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::COLOR,
			'default'     => '',
			'description' => __( 'For Icon on Style 15', 'soledad' ),
			'selectors'   => array(
				'{{WRAPPER}} .style-15.penci-border-arrow:before' => 'background-color: {{VALUE}};',
			),
			'condition'   => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->add_control( 'iconstyle15_color', array(
			'label'       => __( 'Icon Color', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::COLOR,
			'default'     => '',
			'description' => __( 'For Icon on Style 15', 'soledad' ),
			'selectors'   => array(
				'{{WRAPPER}} .style-15.penci-border-arrow:after' => 'color: {{VALUE}};',
			),
			'condition'   => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->add_responsive_control( 'iconstyle15_size', array(
			'label'       => __( 'Custom Font Size for Icon', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::SLIDER,
			'description' => __( 'For Icon on Style 15', 'soledad' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .style-15.penci-border-arrow:after' => 'font-size: {{SIZE}}px;',
			),
			'condition'   => array( 'heading_title_style' => array( 'style-15' ) ),
		) );
		$this->add_control( 'lines_color', array(
			'label'       => __( 'Color for Lines', 'soledad' ),
			'type'        => \Elementor\Controls_Manager::COLOR,
			'default'     => '',
			'description' => __( 'For Lines on Styles 18, 19, 20', 'soledad' ),
			'selectors'   => array(
				'{{WRAPPER}} .style-18.penci-border-arrow:after' => 'color: {{VALUE}}; background-image: linear-gradient( -45deg, transparent, transparent 30%, {{VALUE}} 30%, {{VALUE}} 50%, transparent 50%, transparent 80%, {{VALUE}} 80%);',
				'{{WRAPPER}} .style-19.penci-border-arrow:after' => 'background-image: linear-gradient( -90deg, transparent, transparent 30%, {{VALUE}} 30%, {{VALUE}} 50%, transparent 50%, transparent 80%, {{VALUE}} 80%);',
				'{{WRAPPER}} .style-20.penci-border-arrow:after' => 'background-image: linear-gradient( 0deg, transparent, transparent 30%, {{VALUE}} 30%, {{VALUE}} 50%, transparent 50%, transparent 80%, {{VALUE}} 80%);',
			),
			'condition'   => array(
				'heading_title_style' => array(
					'style-18',
					'style-18 style-19',
					'style-18 style-20'
				)
			),
		) );

		$this->add_control( 'btitle_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .style-2.penci-border-arrow:after'                                                                                                                                                                                                => 'border-color: transparent;border-top-color: {{VALUE}};',
				'{{WRAPPER}} .style-14 .inner-arrow:before,{{WRAPPER}} .style-11 .inner-arrow,' . '{{WRAPPER}} .style-12 .inner-arrow,{{WRAPPER}} .style-13 .inner-arrow,{{WRAPPER}} .style-15 .inner-arrow,' . '{{WRAPPER}} .penci-border-arrow .inner-arrow' => 'background-color: {{VALUE}};',
			)
		) );
		$this->add_control( 'btitle_outer_bgcolor', array(
			'label'     => __( 'Background Outer Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-border-arrow:after' => 'background-color: {{VALUE}};'
			)
		) );

		$this->add_control( 'btitle_style9_bgimg', array(
			'label'     => __( 'Select Background Image for Style 9', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::MEDIA,
			//'dynamic'     => array( 'active' => true ),
			//'responsive'  => true,
			//'render_type' => 'template',
			'default'   => array( 'id' => '', 'url' => '' ),
			'selectors' => array( '{{WRAPPER}} .style-8.penci-border-arrow .inner-arrow' => 'background-image: url("{{URL}}");' ),
			'condition' => array( 'heading_title_style' => 'style-8' ),
		) );

		$this->add_control( 'btitle_style9_repeat', array(
			'label'     => esc_html__( 'Background Image Repeat', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'options'   => array(
				'no-repeat' => esc_html__( 'No Repeat', 'soledad' ),
				'repeat'    => esc_html__( 'Repeat', 'soledad' ),
				'repeat-x'  => esc_html__( 'Repeat X', 'soledad' ),
				'repeat-y'  => esc_html__( 'Repeat Y', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => 'style-8' ),
			'default'   => 'no-repeat',
			'selectors' => array( '{{WRAPPER}} .style-8.penci-border-arrow .inner-arrow' => 'background-repeat: {{VALUE}};' ),
		) );

		$this->add_control( 'btitle_style9_size', array(
			'label'     => esc_html__( 'Background Image Size', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'options'   => array(
				'auto 100%' => esc_html__( 'With Auto - Height 100%', 'soledad' ),
				'100% auto' => esc_html__( 'Width 100% - Height Auto', 'soledad' ),
				'cover'     => esc_html__( 'Cover', 'soledad' ),
				'contain'   => esc_html__( 'Contain', 'soledad' ),
				'auto'      => esc_html__( 'Orininal Size', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => 'style-8' ),
			'default'   => 'auto 100%',
			'selectors' => array( '{{WRAPPER}} .style-8.penci-border-arrow .inner-arrow' => 'background-size: {{VALUE}};' ),
		) );

		$this->add_control( 'btitle_style9_pos', array(
			'label'     => esc_html__( 'Background Image Position', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'options'   => array(
				'left top'      => esc_html__( 'Left Top', 'soledad' ),
				'left center'   => esc_html__( 'Left Center', 'soledad' ),
				'left bottom'   => esc_html__( 'Left Bottom', 'soledad' ),
				'right top'     => esc_html__( 'Right Top', 'soledad' ),
				'right center'  => esc_html__( 'Right Center', 'soledad' ),
				'right bottom'  => esc_html__( 'Right Bottom', 'soledad' ),
				'center top'    => esc_html__( 'Center Top', 'soledad' ),
				'center center' => esc_html__( 'Center', 'soledad' ),
				'center bottom' => esc_html__( 'Center Bottom', 'soledad' ),
			),
			'condition' => array( 'heading_title_style' => 'style-8' ),
			'default'   => 'left top',
			'selectors' => array( '{{WRAPPER}} .style-8.penci-border-arrow .inner-arrow' => 'background-position: {{VALUE}};' ),
		) );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'btitle_typo',
			'label'    => __( 'Block Title Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-border-arrow .inner-arrow',
		) );
		$this->end_controls_section();
		/* end custom heading*/

	}

	protected function render() {

		if ( penci_elementor_is_edit_mode() ) {
			$this->preview_content();
		} else {
			$this->builder_content();
		}

	}

	protected function preview_content() {
        $settings = $this->get_settings();
		$data_auto = 'true';
        $auto      = $settings[ 'penci_post_related_autoplay' ];
        $related_style = $settings['penci_related_style'];
        if ( $auto == false ) {
            $data_auto = 'false';
        }
        $numbers_related = $settings[ 'penci_numbers_related_post' ];
        if ( ! isset( $numbers_related ) || $numbers_related < 1 ) {
            $numbers_related = 10;
        }
        $related_title_length = $settings[ 'penci_related_posts_title_length' ] ? $settings[ 'penci_related_posts_title_length' ] : 8;
        $data_loop            = '';
        $settings['heading'] = $settings['penci_post_related_text'];
        if ( $settings['penci_hide_heading'] == 'yes' ) {
			$this->markup_block_title( $settings );
		}
?>
<div class="pcrlt-<?php echo $related_style; ?> post-related<?php if ( $settings[ 'penci_post_related_grid' ] ): echo ' penci-posts-related-grid'; endif; ?>">
<?php if ($settings[ 'penci_post_related_text' ] && $settings['penci_hide_heading'] != 'yes' ): ?>
    <div class="post-title-box"><h4
                class="post-box-title"><?php echo $settings[ 'penci_post_related_text' ]; ?></h4></div>
                <?php endif;?>
	<?php if ( ! $settings[ 'penci_post_related_grid' ]) {
	$dcol_slides = $related_style == 'style-4' ? 2 : 3;
	$mcol_slides = $related_style == 'style-4' ? 1 : 2;
	$lazy_class = 'penci-lazy'; ?>
    <div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-related-carousel"
         data-lazy="true"<?php echo $data_loop; ?> data-item="<?php echo $dcol_slides;?>" data-desktop="<?php echo $dcol_slides;?>" data-tablet="<?php echo $mcol_slides;?>" data-tabsmall="<?php echo $mcol_slides;?>"
         data-auto="<?php echo $data_auto; ?>"
         data-speed="300"<?php if ( ! $settings[ 'penci_post_related_dots' ] ) {
		echo ' data-dots="true"';
	}
	if ( ! $settings[ 'penci_post_related_arrows' ] ) {
		echo ' data-nav="false"';
	} ?>>
	<div class="swiper-wrapper">
		<?php } else {
		$lazy_class = 'penci-lazy'; ?>
        <div class="penci-related-carousel penci-related-grid-display">
			<?php } ?>
			<?php for ($i=1;$i<$numbers_related;$i++): ?>
                <div class="item-related swiper-slide">
                	<div class="item-related-inner">
                		<?php 
                    	if ( 'style-2' == $related_style ) {
                    		echo '<a style="" class="penci-cat-name" href="#" rel="category tag"><span style="">Category Name</span></a>';
                    	}
                    	?>
	                <?php do_action('penci_bookmark_post',get_the_ID()); ?>
	                    <a class="related-thumb penci-image-holder <?php echo $lazy_class; ?>"
	                       data-bgset="<?php echo PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=200&h=300' ;?>"
	                       href="#" title="This is a post Title"></a>
	                    <div class="related-content">
	                    	<?php 
	                    	if ( 'style-4' == $related_style || 'style-5' == $related_style ) {
	                    		?>
	                    		<a class="penci-cat-name" href="#" rel="category tag"><span>Category Name</span></a>
	                    		<?php
	                    	}
	                    	?>
	                        <h3>
	                            <a href="#"><?php echo wp_trim_words( 'This is a post Title', $related_title_length, '...' ); ?></a>
	                        </h3>
							<?php if ( ! $settings[ 'penci_hide_date_related' ] ): ?>
	                            <span class="date"><?php penci_soledad_time_link(); ?></span>
							<?php endif; ?>
						</div>
					</div>
                </div>
			<?php
			endfor;
			if ( ! $settings[ 'penci_post_related_grid' ]) {
				echo '<div class="penci-owl-dots"></div></div>';
			}
			echo '</div></div>';
	}

    public static function markup_block_title( $args, $self = null ) {
		$defaults = array(
			'heading_title_style'  => 'style-1',
			'heading'              => '',
			'heading_title_link'   => '',
			'add_title_icon'       => '',
			'block_title_icon'     => '',
			'block_title_ialign'   => '',
			'block_title_align'    => '',
			'heading_icon_pos'     => '',
			'heading_icon'         => '',
			'block_title_marginbt' => '',
		);

		$r = wp_parse_args( $args, $defaults );

		if ( ! $r['heading'] ) {
			return;
		}

		if ( 'video_list' == $r['heading_title_style'] ) {
			return;
		}

		$heading_title = get_theme_mod( 'penci_sidebar_heading_style' ) ? get_theme_mod( 'penci_sidebar_heading_style' ) : 'style-1';
		$heading_align = get_theme_mod( 'penci_sidebar_heading_align' ) ? get_theme_mod( 'penci_sidebar_heading_align' ) : 'pcalign-center';


		if ( $r['heading_title_style'] ) {
			$heading_title = $r['heading_title_style'];
		}

		if ( $r['block_title_align'] ) {
			$heading_align = 'pcalign-' . $r['block_title_align'];
		}

		$heading_icon_pos    = get_theme_mod( 'penci_sidebar_icon_align' ) ? get_theme_mod( 'penci_sidebar_icon_align' ) : 'pciconp-right';
		$heading_icon_design = get_theme_mod( 'penci_sidebar_icon_design' ) ? get_theme_mod( 'penci_sidebar_icon_design' ) : 'pcicon-right';

		if ( $r['heading_icon_pos'] ) {
			$heading_icon_pos = $r['heading_icon_pos'];
		}

		if ( $r['heading_icon'] ) {
			$heading_icon_design = $r['heading_icon'];
		}

		$classes = 'penci-border-arrow penci-homepage-title penci-home-latest-posts';
		$classes .= ' ' . $heading_title;
		$classes .= ' ' . $heading_align;
		$classes .= ' ' . $heading_icon_pos;
		$classes .= ' ' . $heading_icon_design;
		$classes .= $r['block_title_ialign'] ? ' block-title-icon-' . $r['block_title_ialign'] : ' block-title-icon-left';
		?>
        <div class="<?php echo esc_attr( $classes ); ?>">
            <h3 class="inner-arrow">
				<?php
				if ( $r['heading_title_link']['url'] ) {
					$self->add_render_attribute( 'link', 'href', $r['heading_title_link']['url'] );
					if ( $r['heading_title_link']['is_external'] ) {
						$self->add_render_attribute( 'link', 'target', '_blank' );
					}

					if ( $r['heading_title_link']['nofollow'] ) {
						$self->add_render_attribute( 'link', 'rel', 'nofollow' );
					}

					echo '<a ' . $self->get_render_attribute_string( 'link' ) . '>';
				} else {
					echo '<span>';
				}

				if ( $r['add_title_icon'] && $r['block_title_icon'] && 'left' == $r['block_title_ialign'] ) {
					\Elementor\Icons_Manager::render_icon( $r['block_title_icon'] );
				}
				echo do_shortcode( $r['heading'] );
				if ( $r['add_title_icon'] && $r['block_title_icon'] && 'right' == $r['block_title_ialign'] ) {
					\Elementor\Icons_Manager::render_icon( $r['block_title_icon'] );
				}
				if ( $r['heading_title_link'] ) {
					echo '</a>';
				} else {
					echo '</span>';
				}
				?>
            </h3>
        </div>
		<?php
	}

	protected function builder_content() {
		$this->overwrite_mods();
        $settings = $this->get_settings();
        $settings['heading'] = $settings['penci_post_related_text'];
        if ( $settings['penci_hide_heading'] == 'yes' ) {
			$this->markup_block_title( $settings );
		}
		get_template_part( 'inc/templates/related_posts' );
	}

	protected function overwrite_mods() {
		$settings = $this->get_settings();
		$mods     = [
            'penci_related_style',
			'penci_post_related_autoplay',
			'penci_numbers_related_post',
			'penci_related_orderby',
			'penci_related_sort_order',
			'penci_related_posts_title_length',
			'penci_related_by',
			'penci_post_related_grid',
			'penci_post_related_dots',
			'penci_post_related_arrows',
			'penci_post_related_icons',
			'penci_hide_date_related',
			'penci_post_related_text'
		];
		foreach ( $mods as $mod ) {
			$value = $settings[ $mod ];
            if ( $settings['penci_hide_heading'] == 'yes' && 'penci_post_related_text' == $mod ) {
			    $value = '';
		    }
			add_filter( 'theme_mod_' . $mod, function () use ( $value ) {
				return $value;
			} );
		}
	}
}
