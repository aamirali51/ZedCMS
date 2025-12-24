<?php

namespace PenciSoledadElementor\Modules\PenciPopularPosts\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Modules\QueryControl\Module;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciPopularPosts extends Base_Widget {

	public function get_name() {
		return 'penci-popular-posts';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Popular Posts', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'popular-posts' );
	}


	protected function register_controls() {


		$this->register_query_section_controls();
		$this->start_controls_section(
			'section_page', array(
				'label' => esc_html__( 'Layout', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'penci_columns', array(
				'label'   => __( 'Columns', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => array(
					'4' => __( '4 Columns', 'soledad' ),
					'3' => __( '3 Columns', 'soledad' )
				)
			)
		);
		$this->add_control(
			'_title_length', array(
				'label'       => __( 'Custom Words Length for Post Titles', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => '10',
			)
		);

		$this->add_control(
			'penci_featimg_size', array(
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
			)
		);
		$this->add_responsive_control(
			'penci_featimg_ratio', array(
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
			)
		);
		$this->add_control(
			'thumb_size', array(
				'label'     => __( 'Custom Image size', 'soledad' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => $this->get_list_image_sizes( true ),
				'condition' => array( 'penci_featimg_size' => 'custom' ),
			)
		);

		$this->add_control(
			'show_navs', array(
				'label'   => __( 'Show Prev/Next Buttons', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'hide_dots', array(
				'label'   => __( 'Hide Dots', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->end_controls_section();
		$this->register_block_title_section_controls_post();

		$this->start_controls_section(
			'section_popular_posts_style',
			array(
				'label' => __( 'Popular Posts', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ptitle_style', array(
				'label' => __( 'Post Title', 'soledad' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'ptitle_color', array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .item-related h3 a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'ptitle_hcolor', array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .item-related h3 a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'ptitle_typo',
				'selector' => '{{WRAPPER}} .penci-home-popular-post .item-related h3, {{WRAPPER}} .penci-home-popular-post  .item-related h3 a'
			)
		);

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
				'{{WRAPPER}} .related-thumb' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
			],
			'condition' => [ 'featured_image_shadow_enable' => 'yes' ]
		) );

		// Post meta
		$this->add_control(
			'heading_meta_style',
			array(
				'label'     => __( 'Post Meta', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'pmeta_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .item-related span.date' => 'color: {{VALUE}};',

				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'pmeta_typo',
				'selector' => '{{WRAPPER}} .item-related span.date'
			)
		);

		$this->add_control(
			'heading_dots_style',
			array(
				'label'     => __( 'Dots', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'_dot_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .penci-owl-dots .penci-owl-dot span' => 'border-color: {{VALUE}};background-color: {{VALUE}};', ),
			)
		);
		$this->add_control(
			'dot_hcolor',
			array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .penci-owl-dots .penci-owl-dot:hover span'  => 'border-color: {{VALUE}};background-color: {{VALUE}};',
					'{{WRAPPER}} .penci-owl-dots .penci-owl-dot.active span' => 'border-color: {{VALUE}};background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		$query_args = Module::get_query_args( 'posts', $settings );

		echo \Soledad_VC_Shortcodes::popular_posts( array(
			'title'                  => $settings['heading'],
			'hide_block_heading'     => $settings['hide_block_heading'],
			'heading_title_style'    => $settings['heading_title_style'],
			'heading_title_html_tag' => $settings['heading_title_html_tag'],
			'heading_title_link'     => $settings['heading_title_link'],
			'heading_title_align'    => $settings['block_title_align'],
			'heading_icon_pos'       => $settings['heading_icon_pos'],
			'heading_icon'           => $settings['heading_icon'],

			'penci_featimg_size'  => isset( $settings['penci_featimg_size'] ) ? $settings['penci_featimg_size'] : '',
			'penci_featimg_ratio' => isset( $settings['penci_featimg_ratio'] ) ? $settings['penci_featimg_ratio'] : '',
			'thumb_size'          => isset( $settings['thumb_size'] ) ? $settings['thumb_size'] : '',
			'show_navs'           => $settings['show_navs'],
			'hide_dots'           => $settings['hide_dots'],

			'columns'         => $settings['penci_columns'],
			'_title_length'   => $settings['_title_length'],
			'elementor_query' => $query_args,
		) );
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
