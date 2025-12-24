<?php

namespace PenciSoledadElementor\Modules\PenciFeaturedCat\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciFeaturedCat extends Base_Widget {

	public function get_name() {
		return 'penci-featured-cat';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Featured Cat', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'facebook', 'social', 'embed', 'page' );
	}

	public function get_script_depends() {
		return [ 'penci_ajax_filter_fcat' ];
	}

	protected function register_controls() {


		$this->register_query_section_controls( true );
		$this->start_controls_section( 'section_layout', array(
			'label' => esc_html__( 'Layout', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'penci_style', array(
			'label'   => __( 'Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'style-1',
			'options' => array(
				'style-1'  => 'Style 1 - 1st Post Grid Featured on Left',
				'style-2'  => 'Style 2 - 1st Post Grid Featured on Top',
				'style-3'  => 'Style 3 - Text Overlay',
				'style-4'  => 'Style 4 - Single Slider',
				'style-5'  => 'Style 5 - Slider 2 Columns',
				'style-6'  => 'Style 6 - 1st Post List Featured on Top',
				'style-7'  => 'Style 7 - Grid 2 Columns',
				'style-8'  => 'Style 8 - List Layout',
				'style-9'  => 'Style 9 - Small List Layout',
				'style-10' => 'Style 10 - 2 First Posts Featured and List',
				'style-11' => 'Style 11 - Text Overlay Center',
				'style-12' => 'Style 12 - Slider 3 Columns',
				'style-13' => 'Style 13 - Grid 3 Columns',
				'style-14' => 'Style 14 - 1st Post Overlay Featured on Top',
				'style-15' => 'Style 15 - Overlay Left then List on Right',
			)
		) );
		$this->add_control( 'thumb15', array(
			'label'     => __( 'Show Thumbnail on Small Posts', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => array( 'penci_style' => array( 'style-15' ) ),
		) );

		$this->add_responsive_control( 'spacing_item', array(
			'label'       => __( 'Rows Gap Between Post Items', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .home-featured-cat-content:not(.style-6) .mag-post-box:not(:last-child)'                                                                                                                                                                                                      => 'padding-bottom: {{SIZE}}{{UNIT}};margin-bottom: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .home-featured-cat-content:not(.style-6) .mag-photo'                                                                                                                                                                                                                          => 'margin-bottom: {{SIZE}}{{UNIT}};margin-bottom: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .home-featured-cat-content.style-6 .cat-left,{{WRAPPER}} .home-featured-cat-content.style-7 .penci-grid > li,{{WRAPPER}} .home-featured-cat-content.style-13 .penci-grid.penci-fea-cat-style-13 > li,{{WRAPPER}} .home-featured-cat-content.style-6 .cat-right .mag-post-box' => 'margin-bottom : {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .home-featured-cat-content.style-7 .penci-grid,{{WRAPPER}} .home-featured-cat-content.style-13 .penci-grid.penci-fea-cat-style-13'                                                                                                                                            => 'row-gap : {{SIZE}}{{UNIT}}',
			),
			'label_block' => true,
			'condition'   => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-5',
					'style-8',
					'style-11',
					'style-12'
				]
			],
		) );

		$this->add_responsive_control( 'horizontal_spacing', array(
			'label'          => __( 'Horizontal Spacing Between Post Items', 'soledad' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => array( 'size' => '' ),
			'mobile_default' => [
				'size' => 0
			],
			'devices'        => [ 'desktop', 'tablet' ],
			'range'          => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'      => array(
				'(tablet+) body:not(.rtl) {{WRAPPER}} .home-featured-cat-content:not(.style-6) .cat-left'                                                                             => 'padding-right: calc({{SIZE}}{{UNIT}} / 2)',
				'(tablet+) body:not(.rtl) {{WRAPPER}} .home-featured-cat-content:not(.style-6) .cat-right'                                                                            => 'padding-left: calc({{SIZE}}{{UNIT}} / 2)',
				'(tablet+) body.rtl {{WRAPPER}} .home-featured-cat-content:not(.style-6) .cat-left'                                                                                   => 'padding-left: calc({{SIZE}}{{UNIT}} / 2)',
				'(tablet+) body.rtl {{WRAPPER}} .home-featured-cat-content:not(.style-6) .cat-right'                                                                                  => 'padding-right: calc({{SIZE}}{{UNIT}} / 2)',
				'(tablet+){{WRAPPER}} .home-featured-cat-content.style-3'                                                                                                             => 'width:calc(100% + {{SIZE}}{{UNIT}});margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}}',
				'(tablet+){{WRAPPER}} .home-featured-cat-content.style-5 .owl-prev, {{WRAPPER}} .home-featured-cat-content.style-12 .owl-prev'                                        => 'left: calc({{SIZE}}{{UNIT}} / 2 + 20px);',
				'(tablet+){{WRAPPER}} .home-featured-cat-content.style-5 .owl-next, {{WRAPPER}} .home-featured-cat-content.style-12 .owl-next'                                        => 'right: calc({{SIZE}}{{UNIT}} / 2 + 20px);',
				'(tablet+){{WRAPPER}} .penci-magcat-carousel-wrapper'                                                                                                                 => 'margin-left: calc({{SIZE}}{{UNIT}} * -1 / 2);margin-right: calc({{SIZE}}{{UNIT}} * -1 / 2);',
				'(tablet+){{WRAPPER}} .home-featured-cat-content .mag-photo,{{WRAPPER}} .penci-magcat-carousel .magcat-carousel'                                                      => 'padding-left: calc({{SIZE}}{{UNIT}} / 2);padding-right: calc({{SIZE}}{{UNIT}} / 2);',
				'(tablet+){{WRAPPER}} .home-featured-cat-content.style-9 .mag-post-box,{{WRAPPER}} .home-featured-cat-content.style-10 .mag-post-box'                                 => 'width:calc(50% - calc({{SIZE}}{{UNIT}}/2) );margin-right:{{SIZE}}{{UNIT}}',
				'(tablet+){{WRAPPER}} .home-featured-cat-content.style-9 .mag-post-box:nth-child(2n+2),{{WRAPPER}} .home-featured-cat-content.style-10 .mag-post-box:nth-child(2n+2)' => 'margin-right:0',
				'(tablet+){{WRAPPER}} .home-featured-cat-content.style-13 .penci-grid.penci-fea-cat-style-13'                                                                         => '--pcrgap: {{SIZE}}{{UNIT}}',
				'(tablet+){{WRAPPER}} .home-featured-cat-content.style-7 .penci-grid.penci-fea-cat-style-7'                                                                           => '--pcrgap: {{SIZE}}{{UNIT}}',
				'(tablet+){{WRAPPER}} .home-featured-cat-content.style-6 .cat-right .mag-post-box'                                                                                    => 'width:calc(50% - calc({{SIZE}}{{UNIT}} / 2) );margin-right:{{SIZE}}{{UNIT}}',
				'(tablet+){{WRAPPER}} .home-featured-cat-content.style-6 .cat-right .mag-post-box:nth-child(2n+2)'                                                                    => 'margin-right:0',
			),
			'label_block'    => true,
			'condition'      => [
				'penci_style!' => [
					'style-2',
					'style-3',
					'style-4',
					'style-8',
					'style-11',
					'style-14',
				]
			],
		) );

		$this->add_responsive_control( 'simgwidth', array(
			'label'     => __( 'Custom Image Width for Small Posts', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 300, 'step' => 1 ) ),
			'selectors' => array(
				'{{WRAPPER}} .home-featured-cat-content .penci-image-holder.small-fix-size' => 'width: {{SIZE}}px;',
			),
			'condition' => array(
				'penci_style' => array(
					'style-1',
					'style-2',
					'style-6',
					'style-9',
					'style-10',
					'style-15'
				)
			),
		) );

		$this->add_responsive_control( 'penci_columns', array(
			'label'          => __( 'Columns', 'soledad' ),
			'type'           => Controls_Manager::SELECT,
			'default'        => '',
			'tablet_default' => '1',
			'mobile_default' => '1',
			'options'        => array(
				''  => 'Default',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
			),
			'condition'      => array( 'penci_style' => array( 'style-3', 'style-11' ) ),
		) );

		$this->add_control( 'penci_column_gap', array(
			'label'     => __( 'Columns Gap', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-featured-cat-sc:not( .penci-featured-cat-ctcol ) .home-featured-cat-content'            => 'width: calc(100% + {{SIZE}}{{UNIT}});margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2)',
				'{{WRAPPER}} .penci-featured-cat-sc:not( .penci-featured-cat-ctcol ) .home-featured-cat-content .mag-photo' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2)',
				'{{WRAPPER}} .penci-featured-cat-ctcol .home-featured-cat-content'                                          => 'grid-column-gap: {{SIZE}}{{UNIT}}'
			),
			'condition' => array( 'penci_style' => array( 'style-3', 'style-11' ) ),
		) );

		$this->add_control( 'penci_row_gap', array(
			'label'              => __( 'Rows Gap', 'soledad' ),
			'type'               => Controls_Manager::SLIDER,
			'range'              => array( 'px' => array( 'min' => 0, 'max' => 200 ) ),
			'frontend_available' => true,
			'selectors'          => array(
				'{{WRAPPER}} .mag-cat-style-8 .penci-grid'                                                                  => 'row-gap: 0',
				'{{WRAPPER}} .penci-featured-cat-sc:not( .penci-featured-cat-ctcol ) .home-featured-cat-content .mag-photo' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .penci-featured-cat-ctcol .home-featured-cat-content'                                          => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .mag-cat-style-8 .penci-grid li.list-post:not(:last-child)'                                    => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2); margin-bottom: calc({{SIZE}}{{UNIT}}/2)',
			),
			'condition'          => array(
				'penci_style' => array(
					'style-3',
					'style-8',
					'style-11',
				)
			),
		) );

		$this->add_control( 'penci_featimg_size', array(
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
			)
		) );
		$this->add_responsive_control( 'penci_featimg_ratio', array(
			'label'          => __( 'Image Ratio', 'soledad' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => array( 'size' => 0.66 ),
			'tablet_default' => array( 'size' => '' ),
			'mobile_default' => array( 'size' => 0.5 ),
			'range'          => array( 'px' => array( 'min' => 0.1, 'max' => 2, 'step' => 0.01 ) ),
			'selectors'      => array(
				'{{WRAPPER}} .penci-image-holder:before' => 'padding-top: calc( {{SIZE}} * 100% );',
			),
			'condition'      => array( 'penci_featimg_size' => 'custom' ),
		) );
		$this->add_control( 'thumb_size', array(
			'label'     => __( 'Custom Image size', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => $this->get_list_image_sizes( true ),
			'condition' => array( 'penci_featimg_size' => 'custom' ),
		) );

		$this->add_control( 'big_title_length', array(
			'label'       => __( 'Custom Words Length for Post Titles for Big Posts', 'soledad' ),
			'type'        => Controls_Manager::NUMBER,
			'label_block' => true,
			'default'     => '',
		) );
		$this->add_control( '_title_length', array(
			'label'       => __( 'Custom Words Length for Post Titles', 'soledad' ),
			'type'        => Controls_Manager::NUMBER,
			'label_block' => true,
			'default'     => '',
		) );

		$this->add_control( 'remove_dot', array(
			'label'     => __( 'Remove Dot Before The Post Title', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => array( 'penci_style' => array( 'style-14' ) ),
			'selectors' => array(
				'{{WRAPPER}} .home-featured-cat-content.style-14 .magcat-padding'        => 'padding: 0;',
				'{{WRAPPER}} .home-featured-cat-content.style-14 .magcat-padding:before' => 'content: none; display: none;',
			),
		) );

		$featured_cat_opts = array(
			'enable_meta_overlay' => array(
				'label' => 'Enable Post Meta Overlay Featured Image',
				'desc'  => 'This option just apply for or Featured Category Style 7'
			),
			'hide_author'         => array( 'label' => 'Hide Post Author', 'desc' => '' ),
			'show_author_sposts'  => array( 'label' => 'Show Post Author on Small Posts', 'desc' => '' ),
			'hide_cat'            => array(
				'label' => 'Hide Category',
				'desc'  => 'This option just apply for or Featured Category Style 8'
			),
			'hide_icon_format'    => array( 'label' => 'Hide Icon Post Format', 'desc' => '' ),
			'hide_date'           => array( 'label' => 'Hide Post Date', 'desc' => '' ),
			'show_commentcount'   => array( 'label' => 'Show Comment Count', 'desc' => '' ),
			'show_viewscount'     => array( 'label' => 'Show Views Count', 'desc' => '' ),
			'hide_readtime'       => array( 'label' => 'Hide Reading Time', 'desc' => '' ),
			'hide_excerpt'        => array( 'label' => 'Hide Post Excerpt', 'desc' => '' ),
			'hide_excerpt_line'   => array( 'label' => 'Remove Line Above Post Excerpt', 'desc' => '' ),
		);

		foreach ( $featured_cat_opts as $featured_cat_key => $featured_cat_opt ) {
			$this->add_control( $featured_cat_key, array(
				'label'       => $featured_cat_opt['label'],
				'type'        => Controls_Manager::SWITCHER,
				'description' => $featured_cat_opt['desc'],
			) );
		}

		$this->add_control( 'cat_rmborder_bottom', array(
			'label'     => __( 'Remove Borders on Post Items', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'selectors' => array(
				'{{WRAPPER}} .home-featured-cat-content .mag-post-box, {{WRAPPER}} .home-featured-cat-content.style-8 .penci-grid li.list-post'                     => 'border: none !important;',
				'{{WRAPPER}} .home-featured-cat-content .mag-post-box:not(:last-child)'                                                                             => 'margin-bottom: 20px; padding-bottom: 0;',
				'{{WRAPPER}} .home-featured-cat-content.style-2 .mag-post-box.first-post, {{WRAPPER}} .home-featured-cat-content.style-10 .mag-post-box.first-post' => 'padding-bottom: 0;',
				'{{WRAPPER}} .home-featured-cat-content.style-14 .mag-post-box, {{WRAPPER}} .home-featured-cat-content.style-14 .mag-post-box'                      => 'padding-bottom: 0; margin-bottom: 20px;',
			),
		) );

		$this->add_control( '_excerpt_length', array(
			'label'     => __( 'Custom Excerpt Length', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'condition' => array(
				'hide_excerpt!' => 'yes',
				'penci_style'   => array(
					'style-1',
					'style-2',
					'style-6',
					'style-7',
					'style-8',
					'style-10'
				)
			),
		) );

		$this->add_control( 'cat_autoplay', array(
			'label'     => __( 'Disable Autoplay on the Slider', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => array( 'penci_style' => array( 'style-4', 'style-5', 'style-12' ) ),
		) );

		// Enable view all button
		$this->add_control( 'cat_seemore', array(
			'label'     => __( 'Enable "View All" Button', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'separator' => 'before',
		) );
		$this->add_control( 'cat_view_link', array(
			'label'       => __( 'Custom Link for "View All" Button', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => __( 'https://your-link.com', 'soledad' ),
			'condition'   => array( 'cat_seemore' => 'yes' ),
			'label_block' => true,
		) );

		$this->add_control( 'cat_remove_arrow', array(
			'label'     => __( 'Remove arrow on "View All"', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => array( 'cat_seemore' => 'yes' ),
		) );
		$this->add_control( 'cat_readmore_button', array(
			'label'     => __( 'Make "View All" is A Button', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => array( 'cat_seemore' => 'yes' ),
		) );
		$this->add_control( 'cat_readmore_align', array(
			'label'       => __( 'Align "View All" Button', 'soledad' ),
			'type'        => Controls_Manager::CHOOSE,
			'options'     => array(
				'left'   => array(
					'title' => __( 'Left', 'soledad' ),
					'icon'  => 'eicon-text-align-left'
				),
				'center' => array(
					'title' => __( 'Center', 'soledad' ),
					'icon'  => 'eicon-text-align-center'
				),
				'right'  => array(
					'title' => __( 'Right', 'soledad' ),
					'icon'  => 'eicon-text-align-right'
				),
			),
			'default'     => 'left',
			'label_block' => true,
			'condition'   => array( 'cat_seemore' => 'yes' ),
		) );
		$this->add_responsive_control( 'cat_readmore_martop', array(
			'label'       => __( 'Custom Margin Top for "View All" Button', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .penci-featured-cat-seemore' => 'margin-top: {{SIZE}}{{UNIT}} !important'
			),
			'condition'   => array( 'cat_seemore' => 'yes' ),
			'label_block' => true,
		) );


		$this->end_controls_section();

		// Post Spacing
		$this->start_controls_section( 'section_design_spacing', array(
			'label' => __( 'Elements Spacing', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_responsive_control( 'spacing_thumb', array(
			'label'       => __( 'Thumbnail Spacing', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'body:not(.rtl) {{WRAPPER}} .home-featured-cat-content .mag-post-box:not(.first-post) .magcat-thumb'   => 'margin-right: {{SIZE}}{{UNIT}}',
				'body.rtl {{WRAPPER}} .home-featured-cat-content .mag-post-box:not(.first-post) .magcat-thumb'         => 'margin-left: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .penci-grid li .item > .thumbnail, {{WRAPPER}} .penci-masonry .item-masonry > .thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
			),
			'label_block' => true,
		) );
		$this->add_responsive_control( 'spacing_title', array(
			'label'       => __( 'Title Spacing', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .home-featured-cat-content .magcat-titlte.entry-title,{{WRAPPER}} .grid-title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
			),
			'label_block' => true,
		) );
		$this->add_responsive_control( 'spacing_gheader', array(
			'label'       => __( 'Header Group Spacing', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .grid-header-box' => 'padding-bottom: {{SIZE}}{{UNIT}}'
			),
			'label_block' => true,
		) );
		$this->add_responsive_control( 'spacing_meta', array(
			'label'       => __( 'Meta Spacing', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .home-featured-cat-content .magcat-detail .mag-meta,{{WRAPPER}} .grid-post-box-meta,{{WRAPPER}} .grid-post-box-meta' => 'margin-top: {{SIZE}}{{UNIT}}'
			),
			'label_block' => true,
		) );
		$this->add_responsive_control( 'spacing_content', array(
			'label'       => __( 'Content/Excerpt Spacing', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .item-content.entry-content' => 'margin-top: {{SIZE}}{{UNIT}}'
			),
			'condition'   => [ 'penci_style' => [ 'style-7' ] ],
			'label_block' => true,
		) );

		$this->add_responsive_control( 'bspacing_heading_text', array(
			'label'     => __( 'Spacing for Big Post', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-5',
					'style-7',
					'style-8',
					'style-9',
					'style-11',
					'style-12',
					'style-13',
				]
			],
		) );

		$this->add_responsive_control( 'bspacing_btom', array(
			'label'       => __( 'Big Post Spacing to Bottom', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .home-featured-cat-content .cat-left,{{WRAPPER}} .home-featured-cat-content.style-6 .cat-left,{{WRAPPER}} .home-featured-cat-content.style-2 .mag-post-box.first-post,{{WRAPPER}} .home-featured-cat-content.style-10 .mag-post-box.first-post,{{WRAPPER}} .home-featured-cat-content.style-14 .mag-post-box.first-post' => 'margin-bottom: {{SIZE}}{{UNIT}}',
			),
			'label_block' => true,
			'condition'   => [
				'penci_style' => [
					'style-1',
					'style-2',
					'style-6',
					'style-10',
					'style-14',
				]
			],
		) );

		$this->add_responsive_control( 'bspacing_thumb', array(
			'label'       => __( 'Thumbnail Spacing', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .home-featured-cat-content:not(.style-6) .mag-post-box.first-post .magcat-thumb'                   => 'margin-bottom: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .penci-featured-cat-sc .home-featured-cat-content.style-6 .mag-post-box.first-post .magcat-detail' => 'padding-left: {{SIZE}}{{UNIT}}'
			),
			'label_block' => true,
			'condition'   => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-5',
					'style-7',
					'style-8',
					'style-9',
					'style-11',
					'style-12',
					'style-13',
				]
			],
		) );
		$this->add_responsive_control( 'bspacing_title', array(
			'label'       => __( 'Title Spacing', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .home-featured-cat-content .first-post .magcat-detail .mag-header .magcat-titlte.entry-title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
			),
			'label_block' => true,
			'condition'   => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-5',
					'style-7',
					'style-8',
					'style-9',
					'style-11',
					'style-12',
					'style-13',
				]
			],
		) );
		$this->add_responsive_control( 'bspacing_meta', array(
			'label'       => __( 'Meta Spacing', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .home-featured-cat-content .first-post .magcat-detail .mag-meta' => 'margin-top: {{SIZE}}{{UNIT}}'
			),
			'label_block' => true,
			'condition'   => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-5',
					'style-7',
					'style-8',
					'style-9',
					'style-11',
					'style-12',
					'style-13',
				]
			],
		) );
		$this->add_responsive_control( 'bspacing_hgroup', array(
			'label'       => __( 'Header Group Spacing Top', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .home-featured-cat-content .first-post .magcat-detail .mag-header' => 'padding-bottom: {{SIZE}}{{UNIT}};'
			),
			'label_block' => true,
			'condition'   => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-5',
					'style-7',
					'style-8',
					'style-9',
					'style-11',
					'style-12',
					'style-13',
				]
			],
		) );
		$this->add_responsive_control( 'bspacing_hgroup_b', array(
			'label'       => __( 'Header Group Spacing Bottom', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .home-featured-cat-content .first-post .magcat-detail .mag-header' => 'margin-bottom: {{SIZE}}{{UNIT}};'
			),
			'label_block' => true,
			'condition'   => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-5',
					'style-7',
					'style-8',
					'style-9',
					'style-11',
					'style-12',
					'style-13',
				]
			],
		) );
		$this->add_responsive_control( 'bspacing_content', array(
			'label'       => __( 'Post Excerpt/Content Spacing', 'soledad' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => array( 'size' => '' ),
			'range'       => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors'   => array(
				'{{WRAPPER}} .home-featured-cat-content .first-post .magcat-detail .mag-excerpt' => 'margin-top: {{SIZE}}{{UNIT}};'
			),
			'label_block' => true,
			'condition'   => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-5',
					'style-7',
					'style-8',
					'style-9',
					'style-11',
					'style-12',
					'style-13',
				]
			],
		) );

		$this->end_controls_section();

		$this->register_block_title_ajax_filter( true );

		$this->register_block_title_section_controls_post();

		// Design
		$this->start_controls_section( 'section_design_general', array(
			'label' => __( 'Featured Cat', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );
		$this->add_control( 'wrapborder_color', array(
			'label'     => __( 'Wrapper Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .home-featured-cat-content.style-15' => 'border-color: {{VALUE}};' ),
			'condition' => array( 'penci_style' => array( 'style-15' ) ),
		) );

		$this->add_responsive_control( 'padding_around', array(
			'label'      => __( 'Add Padding Around Post Items', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .home-featured-cat-content .mag-post-box, {{WRAPPER}} .style-5 .magcat-thumb, {{WRAPPER}} .style-12 .magcat-thumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important; border: 1px solid var(--pcborder-cl);'
			),
			'condition'  => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-7',
					'style-8',
					'style-11',
					'style-13',
				]
			],
		) );

		$this->add_responsive_control( 'borders_around', array(
			'label'      => __( 'Borders Width Around Post Items', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .home-featured-cat-content .mag-post-box, {{WRAPPER}} .style-5 .magcat-thumb, {{WRAPPER}} .style-12 .magcat-thumb' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'
			),
			'condition'  => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-7',
					'style-8',
					'style-11',
					'style-13',
				]
			],
		) );

		$this->add_control( 'pbg_around', array(
			'label'     => __( 'Add Background Color for Post Items?', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .home-featured-cat-content .mag-post-box, body:not(.pcdm-enable) {{WRAPPER}} .style-5 .magcat-thumb, {{WRAPPER}} .style-12 .magcat-thumb' => 'background-color: {{VALUE}};' ),
			'condition' => [
				'penci_style!' => [
					'style-3',
					'style-4',
					'style-7',
					'style-8',
					'style-11',
					'style-13',
				]
			],
		) );

		$this->add_control( 'pborder_color', array(
			'label'     => __( 'Post Items Borders Color ', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .home-featured-cat-content .mag-post-box,{{WRAPPER}} .penci-grid li.list-post, {{WRAPPER}} .style-5 .magcat-thumb, {{WRAPPER}} .style-12 .magcat-thumb' => 'border-color: {{VALUE}};' ),
		) );

		// Post title
		$this->add_control( 'heading_ptittle_settings', array(
			'label'     => __( 'Posts Title', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'ptitle_color', array(
			'label'     => __( 'Post Title Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .penci-grid li .item h2 a'                                  => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-masonry .item-masonry h2 a'                          => 'color: {{VALUE}};',
				'{{WRAPPER}} .home-featured-cat-content .magcat-detail h3 a'             => 'color: {{VALUE}};',
				'{{WRAPPER}} .home-featured-cat-content.style-14 .magcat-padding:before' => 'border-color: {{VALUE}};',
			),
		) );
		$this->add_control( 'ptitle_hcolor', array(
			'label'     => __( 'Post Title Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .penci-grid li .item h2 a:hover'                      => 'color: {{VALUE}};',
				'{{WRAPPER}} .penci-masonry .item-masonry h2 a:hover'              => 'color: {{VALUE}};',
				'{{WRAPPER}} .home-featured-cat-content .magcat-detail h3 a:hover' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'bptitle_color', array(
			'label'     => __( 'Post Title Color of Big Post', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .home-featured-cat-content .first-post .magcat-detail h3 a' => 'color: {{VALUE}} !important;' ),
			'condition' => array( 'penci_style' => array( 'style-14', 'style-15' ) ),
		) );
		$this->add_control( 'bptitle_hcolor', array(
			'label'     => __( 'Post Title Hover Color of Big Post', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .home-featured-cat-content .first-post .magcat-detail h3 a:hover' => 'color: {{VALUE}} !important;' ),
			'condition' => array( 'penci_style' => array( 'style-14', 'style-15' ) ),
		) );
		$this->add_control( 'title_hcolor_effect', array(
			'label'     => __( 'Enable Hover Border Color', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'selectors' => array(
				'{{WRAPPER}} .entry-title a' 		=> 'background-image: linear-gradient(var(--pcaccent-cl) 0%, var(--pcaccent-cl) 98%);background-size: 0 1px;background-repeat: no-repeat;background-position: left 100%;word-break: break-word;transition: 0.3s all ease-in-out;',
				'{{WRAPPER}} .entry-title a:hover'  => 'background-size: 100% 1px;',
			),
		) );
		$this->add_control( 'title_hcolor_effect_color', array(
			'label'     => __( 'Hover Border Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .entry-title a' => 'background-image: linear-gradient({{VALUE}} 0%, {{VALUE}} 98%)' ),
			'condition' => array( 'title_hcolor_effect' => 'yes' ),
		) );
		$this->add_responsive_control( 'bptitle_fsize', array(
			'label'     => __( 'Font Size for Title of Big Post', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .home-featured-cat-content .first-post .magcat-detail h3 a' => 'font-size: {{SIZE}}px' ),
			'condition' => array(
				'penci_style' => array(
					'style-1',
					'style-2',
					'style-6',
					'style-10',
					'style-14',
					'style-15'
				)
			),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'ptitle_typo',
			'selector' => '{{WRAPPER}} .home-featured-cat-content .magcat-detail h3 a,{{WRAPPER}} .penci-grid li .item h2 a,{{WRAPPER}} .penci-masonry .item-masonry h2 a',
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
				'{{WRAPPER}} .magcat-thumb' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
			],
			'condition' => [ 'featured_image_shadow_enable' => 'yes' ]
		) );

		// Post meta
		$this->add_control( 'heading_pmeta_settings', array(
			'label'     => __( 'Posts Meta', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'pmeta_color', array(
			'label'     => __( 'Post Meta Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .home-featured-cat-content .grid-post-box-meta span a'                => 'color: {{VALUE}};',
				'{{WRAPPER}} .home-featured-cat-content .grid-post-box-meta span'                  => 'color: {{VALUE}};',
				'{{WRAPPER}} .home-featured-cat-content .mag-photo .grid-post-box-meta span:after' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'pmeta_hcolor', array(
			'label'     => __( 'Post Meta Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .grid-post-box-meta span a.comment-link:hover' => 'color: {{VALUE}};',
				'{{WRAPPER}} .grid-post-box-meta span a:hover'              => 'color: {{VALUE}};',
			),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'pmeta_typo',
			'selector' => '{{WRAPPER}} .home-featured-cat-content .grid-post-box-meta',
		) );
		// Post excrept
		$this->add_control( 'heading_pexcrept_settings', array(
			'label'     => __( 'Posts Excerpt', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'pexcerpt_color', array(
			'label'     => __( 'Post Excerpt Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .entry-content' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'pexcerpt_typo',
			'selector' => '{{WRAPPER}} .entry-content,{{WRAPPER}} .entry-content p',
		) );

		// Post category
		$this->add_control( 'heading_pcat_settings', array(
			'label'     => __( 'Categories', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'pcat_color', array(
			'label'     => __( 'Categories Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .cat > a.penci-cat-name'       => 'color: {{VALUE}};',
				'{{WRAPPER}} .cat > a.penci-cat-name:after' => 'color: {{VALUE}};',
			),
		) );
		$this->add_control( 'pcat_hcolor', array(
			'label'     => __( 'Categories Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .cat > a.penci-cat-name:hover' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'pcat_typo',
			'selector' => '{{WRAPPER}} .cat > a.penci-cat-name',
		) );

		// Button
		$this->add_control( 'heading_pbutton_settings', array(
			'label'     => __( 'View all" Button', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'cat_viewall_color', array(
			'label'     => __( 'Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .penci-featured-cat-seemore a,{{WRAPPER}} .penci-featured-cat-seemore.penci-btn-make-button a' => 'color: {{VALUE}};',
			),
			'condition' => array( 'cat_seemore' => 'yes' ),
		) );
		$this->add_control( 'cat_viewall_bgcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .penci-featured-cat-seemore.penci-btn-make-button a' => 'background-color: {{VALUE}};',
			),
			'condition' => array( 'cat_seemore' => 'yes' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'pviewall_typo',
			'selector' => '{{WRAPPER}} .penci-featured-cat-seemore a',
		) );

		// Dots Navigations

		$this->add_control( 'heading_pagi_style', array(
			'label'     => __( 'Dots Pagination', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => array( 'penci_style' => array( 'style-4', 'style-5', 'style-12' ) ),
		) );

		$this->add_control( 'dots_bg_color', array(
			'label'     => __( 'Dot Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span,{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span,{{WRAPPER}} .swiper-container .progress' => 'background-color: {{VALUE}};' ),
			'condition' => array( 'penci_style' => array( 'style-4', 'style-5', 'style-12' ) ),
		) );

		$this->add_control( 'dots_bd_color', array(
			'label'     => __( 'Dot Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'border-color: {{VALUE}};' ),
			'condition' => array( 'penci_style' => array( 'style-4', 'style-5', 'style-12' ) ),
		) );

		$this->add_control( 'dots_bga_color', array(
			'label'     => __( 'Dot Borders Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'penci_style' => array( 'style-4', 'style-5', 'style-12' ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span,{{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_bda_color', array(
			'label'     => __( 'Dot Borders Active Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array( 'penci_style' => array( 'style-4', 'style-5', 'style-12' ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot.active span' => 'border-color: {{VALUE}};' ),
		) );

		$this->add_control( 'dots_cs_w', array(
			'label'     => __( 'Dot Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 5, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'width: {{SIZE}}px;height: {{SIZE}}px;' ),
			'condition' => array( 'penci_style' => array( 'style-4', 'style-5', 'style-12' ) ),
		) );

		$this->add_control( 'dots_csbd_w', array(
			'label'     => __( 'Dot Borders Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 1, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-owl-carousel .penci-owl-dot span' => 'border-width: {{SIZE}}px;' ),
			'condition' => array( 'penci_style' => array( 'style-4', 'style-5', 'style-12' ) ),
		) );

		$this->end_controls_section();

		$this->register_block_heading_link_section_style();
		$this->register_penci_bookmark_style_groups();
		$this->register_paywall_premium_heading_style_groups();
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
		$settings             = $this->get_settings();
		$penci_columns        = isset( $settings['penci_columns'] ) ? $settings['penci_columns'] : '';
		$penci_columns_tablet = isset( $settings['penci_columns_tablet'] ) ? $settings['penci_columns_tablet'] : $penci_columns;
		$penci_columns_mobile = isset( $settings['penci_columns_mobile'] ) ? $settings['penci_columns_mobile'] : $penci_columns;

		$query_args = Module::get_query_args( 'posts', $settings );

		echo \Soledad_VC_Shortcodes::featured_cat( array(
			'heading'                => $settings['heading'],
			'hide_block_heading'     => $settings['hide_block_heading'],
			'heading_title_style'    => $settings['heading_title_style'],
			'heading_title_html_tag' => $settings['heading_title_html_tag'],
			'heading_title_link'     => $settings['heading_title_link'],
			'heading_title_align'    => $settings['block_title_align'],
			'heading_icon_pos'       => $settings['heading_icon_pos'],
			'heading_icon'           => $settings['heading_icon'],
			'cat_seemore'            => $settings['cat_seemore'],
			'cat_view_link'          => $settings['cat_view_link'],
			'cat_remove_arrow'       => $settings['cat_remove_arrow'],
			'cat_readmore_button'    => $settings['cat_readmore_button'],
			'cat_readmore_align'     => $settings['cat_readmore_align'],

			'penci_featimg_size'  => isset( $settings['penci_featimg_size'] ) ? $settings['penci_featimg_size'] : '',
			'penci_featimg_ratio' => isset( $settings['penci_featimg_ratio'] ) ? $settings['penci_featimg_ratio'] : '',
			'thumb_size'          => $settings['thumb_size'],
			'thumb15'             => $settings['thumb15'],

			'enable_meta_overlay' => $settings['enable_meta_overlay'],
			'hide_author'         => $settings['hide_author'],
			'show_author_sposts'  => $settings['show_author_sposts'],
			'hide_readtime'       => $settings['hide_readtime'],
			'hide_cat'            => $settings['hide_cat'],
			'hide_icon_format'    => $settings['hide_icon_format'],
			'hide_date'           => $settings['hide_date'],
			'hide_excerpt'        => $settings['hide_excerpt'],
			'hide_excerpt_line'   => $settings['hide_excerpt_line'],
			'cat_autoplay'        => $settings['cat_autoplay'],
			'_excerpt_length'     => $settings['_excerpt_length'],
			'big_title_length'    => $settings['big_title_length'],
			'_title_length'       => $settings['_title_length'],

			'penci_columns'        => $settings['penci_columns'],
			'penci_columns_tablet' => $penci_columns_tablet,
			'penci_columns_mobile' => $penci_columns_mobile,
			'penci_column_gap'     => isset( $settings['penci_column_gap'] ) ? $settings['penci_column_gap'] : '',
			'penci_row_gap'        => $settings['penci_row_gap'],
			'show_viewscount'      => $settings['show_viewscount'],
			'show_commentcount'    => $settings['show_commentcount'],

			'style'                      => $settings['penci_style'],
			'elementor_query'            => $query_args,
			'biggrid_ajaxfilter_cat'     => $settings['biggrid_ajaxfilter_cat'],
			'biggrid_ajaxfilter_tag'     => $settings['biggrid_ajaxfilter_tag'],
			'biggrid_ajaxfilter_author'  => $settings['biggrid_ajaxfilter_author'],
			'group_more_link_text'       => $settings['group_more_link_text'],
			'group_more_defaultab_text'  => $settings['group_more_defaultab_text'],
			'biggrid_ajax_loading_style' => $settings['biggrid_ajax_loading_style'],
			'paging'                     => $settings['paging'],

			'validator' => $settings['cspost_enable'],
			'keys'      => $settings['cspost_cpost_meta'],
			'acf'       => $settings['cspost_cpost_acf_meta'],
			'label'     => $settings['cspost_cpost_meta_label'],
			'divider'   => $settings['cspost_cpost_meta_divider'],

			'wrapper_css' => isset( $settings['paywall_heading_text_style'] ) ? ' pencipw-hd-' . $settings['paywall_heading_text_style'] : '',
		) );
	}
}
