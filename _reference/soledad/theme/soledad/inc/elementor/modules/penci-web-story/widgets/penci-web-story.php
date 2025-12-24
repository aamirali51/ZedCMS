<?php

namespace PenciSoledadElementor\Modules\PenciWebStory\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciWebStory extends Base_Widget {

	public function get_name() {
		return 'penci-web-story';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Web Story', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-posts-carousel';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'story', 'web' );
	}

	public function get_script_depends() {
		return array( 'penci_web_stories' );
	}

	protected function register_controls() {


		$this->start_controls_section( 'section_general', array(
			'label' => esc_html__( 'Stories Query', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );
		$this->add_control( 'query_type', array(
			'label'   => __( 'Query Type', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'all',
			'options' => array(
				'all'    => esc_html__( 'All Stories', 'soledad' ),
				'custom' => esc_html__( 'Custom Stories', 'soledad' ),
			),
		) );
		$this->add_control( 'story_ids', array(
			'label'       => __( 'Custom Story', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_posts_by_query',
			'render'      => 'penci_get_posts_title_by_id',
			'post_type'   => 'web-story',
			'multiple'    => true,
			'label_block' => true,
			'condition'   => [ 'query_type' => 'custom' ]
		) );
		$this->add_control( 'story_cat', array(
			'label'       => __( 'Categories', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_taxonomies_by_query',
			'render'      => 'penci_get_taxonomies_title_by_id',
			'taxonomy'    => 'web_story_category',
			'multiple'    => true,
			'label_block' => true,
			'condition'   => [ 'query_type' => 'all' ]
		) );
		$this->add_control( 'story_tag', array(
			'label'       => __( 'Tags', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_taxonomies_by_query',
			'render'      => 'penci_get_taxonomies_title_by_id',
			'taxonomy'    => 'web_story_tag',
			'multiple'    => true,
			'label_block' => true,
			'condition'   => [ 'query_type' => 'all' ]
		) );
		$this->add_control( 'ex_story_cat', array(
			'label'       => __( 'Exclude Categories', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_taxonomies_by_query',
			'render'      => 'penci_get_taxonomies_title_by_id',
			'taxonomy'    => 'web_story_category',
			'multiple'    => true,
			'label_block' => true,
			'condition'   => [ 'query_type' => 'all' ]
		) );
		$this->add_control( 'ex_story_tag', array(
			'label'       => __( 'Exclude Tags', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_taxonomies_by_query',
			'render'      => 'penci_get_taxonomies_title_by_id',
			'taxonomy'    => 'web_story_tag',
			'multiple'    => true,
			'label_block' => true,
			'condition'   => [ 'query_type' => 'all' ]
		) );
		$this->add_control( 'ex_story_ids', array(
			'label'       => __( 'Exlcude Custom Stories', 'soledad' ),
			'type'        => 'penci_el_autocomplete',
			'search'      => 'penci_get_posts_by_query',
			'render'      => 'penci_get_posts_title_by_id',
			'post_type'   => 'web-story',
			'multiple'    => true,
			'label_block' => true,
			'condition'   => [ 'query_type' => 'all' ]
		) );
		$this->add_control( 'orderby', array(
			'label'     => __( 'Order By', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'date',
			'options'   => array(
				'date'          => __( 'Published Date', 'soledad' ),
				'ID'            => __('Post ID','soledad' ),
				'modified'      => __('Modified Date','soledad' ),
				'title'         => __('Post Title','soledad' ),
				'rand'          => __('Random Posts','soledad' ),
				'comment_count' => __('Comment Count','soledad' ),
				'popular'       => __('Most Viewed Posts All Time','soledad' ),
				'popular_day'   => __('Most Viewed Posts Daily','soledad' ),
				'popular7'      => __('Most Viewed Posts Once Weekly','soledad' ),
				'popular_month' => __('Most Viewed Posts Once a Month','soledad' ),
			),
			'condition' => [ 'query_type' => 'all' ]
		) );
		$this->add_control( 'order', array(
			'label'     => __( 'Order', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'desc',
			'options'   => array(
				'asc'  => __( 'ASC', 'soledad' ),
				'desc' => __( 'DESC', 'soledad' )
			),
			'condition' => [ 'query_type' => 'all' ]
		) );
		$this->add_control( 'number', array(
			'label'     => __( 'Number of Stories', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'condition' => [ 'query_type' => 'all' ],
			'default'   => get_option( 'posts_per_page' ),
		) );
		$this->add_control( 'offset', array(
			'label'     => __( 'Offset Stories', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 0,
			'condition' => [ 'query_type' => 'all' ]
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_layout', array(
			'label' => esc_html__( 'Layout', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );
		$this->add_control( 'layout', array(
			'label'   => __( 'Layout', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'onerow',
			'options' => array(
				'onerow' => __( 'One Row', 'soledad' ),
				'grid'   => __( 'Grid', 'soledad' ),
				'slider' => __( 'Slider', 'soledad' ),
			),
		) );
		$this->add_control( 'layout_algin', array(
			'label'     => __( 'Content Align', 'soledad' ),
			'type'      => Controls_Manager::CHOOSE,
			'default'   => 'center',
			'options'   => array(
				'start'  => array(
					'title' => __( 'Left', 'soledad' ),
					'icon'  => 'eicon-text-align-left',
				),
				'center' => array(
					'title' => __( 'Center', 'soledad' ),
					'icon'  => 'eicon-text-align-center',
				),
				'end'    => array(
					'title' => __( 'Right', 'soledad' ),
					'icon'  => 'eicon-text-align-right',
				),
			),
			'toggle'    => true,
			'condition' => [ 'layout' => 'grid' ],
			'selectors' => [ '{{WRAPPER}} .pc-wstories-wrapper .pc-wstories-list' => 'justify-content:{{VALUE}}' ]
		) );
		$this->add_responsive_control( 'columns', array(
			'label'          => __( 'Columns', 'soledad' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => array(
				'size' => '12',
			),
			'tablet_default' => array(
				'size' => '6'
			),
			'mobile_default' => array(
				'size' => '4'
			),
			'condition'      => [ 'layout!' => 'onerow' ],
			'render_type'    => 'template',
			'range'          => array( 'px' => array( 'min' => 2, 'max' => 12 ) ),
			'selectors'      => [
				'{{WRAPPER}} .pc-wstories-wrapper .pc-wstories-list.grid .pc-webstory-item'                    => 'width:calc(100% / {{SIZE}})',
				'{{WRAPPER}} .pc-wstories-wrapper .pc-wstories-list.slider:not(.penci-owl-loaded) .pc-webstory-item' => 'width:calc(100% / {{SIZE}})',
			],
		) );
		$this->add_responsive_control( 'onerow_cw', array(
			'label'      => __( 'Custom Item Width', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'condition'      => [ 'layout' => 'onerow' ],
			'range'      => array(
				'px' => array( 'min' => 50, 'max' => 2000 )
			),
			'size_units' => array( 'px' ),
			'selectors'  => [ '{{WRAPPER}} .pc-wstories-wrapper .pc-wstories-list.one-row .pc-webstory-item' => 'flex: 0 0 {{SIZE}}px;width:{{SIZE}}px;', ]
		) );
		$this->add_control( 'imgsize', array(
			'label'   => __( 'Thumbnail Image Size', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_list_image_sizes( true ),
		) );
		$this->add_control( 'lazyload', array(
			'label'        => __( 'Disable Lazyload on Thumbnail Images', 'soledad' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'soledad' ),
			'label_off'    => __( 'No', 'soledad' ),
			'return_value' => 'yes',
			'default'      => '',
		) );
		$this->add_responsive_control( 'v_spacing', array(
			'label'     => __( 'Vertical Spacing Between Items', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 600 ) ),
			'condition' => array( 'layout' => 'grid' ),
			'selectors' => [
				'{{WRAPPER}} .pc-wstories-wrapper .pc-webstory-item' => 'margin-bottom:{{SIZE}}px',
				'{{WRAPPER}} .pc-wstories'                           => 'margin-bottom:calc({{SIZE}}px * -1)',
			],
		) );
		$this->add_responsive_control( 'h_spacing', array(
			'label'     => __( 'Horizontal Spacing Between Items', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
			'selectors' => [ '{{WRAPPER}} .pc-wstories' => '--gap:calc({{SIZE}}px / 2)' ],
		) );
		$this->add_control( 'showtitle', array(
			'label'   => __( 'Show Story Title', 'soledad' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );
		$this->add_control( 'popup_ct_heading', array(
			'label'     => __( 'Popup Content', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'nextprev', array(
			'label'   => __( 'Show Next/Prev Buttons', 'soledad' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );
		$this->add_control( 'pos', array(
			'label'   => __( 'Show Positon Number of Current Story', 'soledad' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_carousel_options', array(
			'label'     => __( 'Carousel Options', 'soledad' ),
			'condition' => array( 'layout' => 'slider' ),
		) );

		$this->add_control( 'carousel_slider_effect', array(
			'label'   => __( 'Carousel Slider Effect', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => get_theme_mod( 'penci_carousel_slider_effect', 'swing' ),
			'options' => array(
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
			'label'   => __( 'Carousel Loop', 'soledad' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'condition' => [ 'carousel_slider_effect' => 'default' ],
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
		$this->register_block_title_section_controls();

		$this->start_controls_section( 'section_style', array(
			'label' => esc_html__( 'Stories', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );
		$this->add_responsive_control( 'border_radius', array(
			'label'      => __( 'Border Radius', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .pc-wstories-wrapper .pc-webstory-thumb-wrapper, {{WRAPPER}} .pc-wstories-wrapper .pc-webstory-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		) );
		$this->add_responsive_control( 'bdwidth', array(
			'label'     => __( 'Borders Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => [ '{{WRAPPER}} .pc-wstories-wrapper .pc-webstory-thumb-wrapper' => 'padding:{{SIZE}}px' ]
		) );
		$this->add_control( 'bdcolor_title', array(
			'label'     => __( 'Story Borders Color', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_group_control( \Elementor\Group_Control_Background::get_type(), array(
			'name'     => 'bdcolor',
			'label'    => __( 'Story Borders Color', 'soledad' ),
			'types'    => array( 'classic', 'gradient' ),
			'selector' => '{{WRAPPER}} .pc-wstories-wrapper .pc-webstory-item .pc-webstory-thumb-wrapper',
		) );
		$this->add_control( 'bd_seencolor_title', array(
			'label'     => __( 'Story Seen Borders Color', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_group_control( \Elementor\Group_Control_Background::get_type(), array(
			'name'     => 'bd_seencolor',
			'label'    => __( 'Story Seen Borders Color', 'soledad' ),
			'types'    => array( 'classic', 'gradient' ),
			'selector' => '{{WRAPPER}} .pc-wstories-wrapper .pc-webstory-item.seen .pc-webstory-thumb-wrapper',
		) );
		$this->add_responsive_control( 'inner_bdwidth', array(
			'label'     => __( 'Inner Borders Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'separator' => 'before',
			'selectors' => [ '{{WRAPPER}} .pc-wstories-wrapper .pc-webstory-thumb' => 'border-width:{{SIZE}}px' ]
		) );
		$this->add_control( 'inner_bdcolor', array(
			'label'     => __( 'Inner Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pc-wstories-wrapper .pc-webstory-thumb' => 'border-color:{{VALUE}}' ]
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_title_style', array(
			'label'     => esc_html__( 'Title', 'soledad' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'showtitle' => 'yes' ]
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'title_typo',
			'label'    => __( 'Typography for Story Title', 'soledad' ),
			'selector' => '{{WRAPPER}} .pc-wstories-wrapper .pc-webstory-item-title h4',
		) );
		$this->add_control( 'title_color', array(
			'label'     => __( 'Story Title Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pc-wstories-wrapper .pc-webstory-item-title h4' => 'color:{{VALUE}}' ]
		) );
		$this->add_responsive_control( 'title_spacing', array(
			'label'     => __( 'Spacing Between Title & Thumbnail', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array(
				'px' => array( 'min' => 0, 'max' => 200 )
			),
			'selectors' => [ '{{WRAPPER}} .pc-wstories-wrapper .pc-webstory-item-title' => 'margin-top:{{SIZE}}px' ]
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'popup_style', array(
			'label' => esc_html__( 'Popup', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );
		$this->add_control( 'ajax_loading_style', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__( 'Loading Icon Style', 'soledad' ),
			'default' => 'df',
			'options' => [
				'df' => 'Follow Customize',
				's9' => 'Style 1',
				's2' => 'Style 2',
				's3' => 'Style 3',
				's4' => 'Style 4',
				's5' => 'Style 5',
				's6' => 'Style 6',
				's1' => 'Style 7',
			],
		) );
		$this->add_control( 'loading_icon_color', array(
			'label'     => __( 'Loading Icon Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .penci-loading-animation-1 .penci-loading-animation,{{WRAPPER}} .penci-loading-animation-1 .penci-loading-animation:before,{{WRAPPER}} .penci-loading-animation-1 .penci-loading-animation:after,{{WRAPPER}} .penci-loading-animation-5 .penci-loading-animation,{{WRAPPER}} .penci-loading-animation-6 .penci-loading-animation:before,{{WRAPPER}} .penci-loading-animation-7 .penci-loading-animation,{{WRAPPER}} .penci-loading-animation-8 .penci-loading-animation,{{WRAPPER}} .penci-loading-animation-9 .penci-loading-circle-inner:before,{{WRAPPER}} .penci-loading-animation-1>div,{{WRAPPER}} .penci-three-bounce .one,{{WRAPPER}} .penci-three-bounce .two,.penci-three-bounce .three' => 'background-color: {{VALUE}}',
				'{{WRAPPER}}'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   => '--pc-loader-2:{{VALUE}}'
			),
		) );
		$this->add_control( 'loading_bgcolor', array(
			'label'     => __( 'Loading Overlay Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pc-wstories-popup-wrapper' => 'background-color:{{VALUE}}'
			),
		) );
		$this->add_control( 'navigation_btn_heading', array(
			'label'     => __( 'Next/Prev Buttons', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'navigation_btn_color', array(
			'label'     => __( 'Next/Prev Buttons Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pc-wstories-popup-wrapper .pc-ws-btn' => 'color:{{VALUE}}'
			),
		) );
		$this->add_control( 'navigation_btn_hcolor', array(
			'label'     => __( 'Next/Prev Buttons Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pc-wstories-popup-wrapper .pc-ws-btn:hover' => 'color:{{VALUE}}'
			),
		) );
		$this->add_responsive_control( 'navigation_btn_size', array(
			'label'     => __( 'Next/Prev Buttons Size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pc-wstories-popup-wrapper .pc-ws-btn' => 'font-size:{{SIZE}}px'
			),
		) );
		$this->end_controls_section();

		$this->register_block_title_style_section_controls();
	}

	protected function render() {
		$settings = $this->get_settings();
		$this->markup_block_title( $settings, $this );
		\penci_webstories( $settings );
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
}
