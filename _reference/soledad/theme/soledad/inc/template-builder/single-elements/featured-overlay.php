<?php

use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleFeaturedOverlay extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Post - Featured Image Overlay', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-parallax';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'title' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcsb-ft-o elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-single-featured-overlay';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'penci_enable_jarallax_single', [
			'label' => esc_html__( 'Enable Parallax on Featured Image', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'penci_post_thumb', [
			'label' => esc_html__( 'Hide Featured Image on Top', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::HIDDEN,
		] );

		$this->add_control( 'img_size', [
			'label'   => esc_html__( 'Image Size', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => $this->get_list_image_sizes( true ),
		] );

		$this->add_control( 'img_msize', [
			'label'   => esc_html__( 'Image Mobile Size', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => $this->get_list_image_sizes( true ),
		] );

		$this->add_control( 'img_ratio', [
			'label'     => esc_html__( 'Image Ratio (in %)', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( '%' => array( 'min' => 0, 'max' => 300, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-single-featured-img,{{WRAPPER}} .penci-jarallax' => 'padding-top: {{SIZE}}% !important;',
			),
		] );


		$this->end_controls_section();

		$this->start_controls_section( 'content_elspacing', [
			'label' => esc_html__( 'Elements Spacing', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_responsive_control( 'element_bspacing', [
			'label'     => esc_html__( 'Breadcrumb Spacing', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => [
				'{{WRAPPER}} .container.penci-breadcrumb' => 'margin-bottom:{{SIZE}}px'
			],
		] );

		$this->add_responsive_control( 'element_catspacing', [
			'label'     => esc_html__( 'Categories Spacing', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => [
				'{{WRAPPER}} .penci-standard-cat' => 'margin-bottom:{{SIZE}}px'
			],
		] );

		$this->add_responsive_control( 'element_titlespacing', [
			'label'     => esc_html__( 'Post Title Spacing', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => [
				'{{WRAPPER}} .header-standard .post-title' => 'margin-bottom:{{SIZE}}px'
			],
		] );

		$this->add_responsive_control( 'element_subtitlespacing', [
			'label'     => esc_html__( 'Post Sub Title Spacing', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => [
				'{{WRAPPER}} .header-standard .penci-psub-title' => 'margin-bottom:{{SIZE}}px'
			],
		] );

		$this->add_responsive_control( 'element_metaspacing', [
			'label'     => esc_html__( 'Meta Spacing', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => [
				'{{WRAPPER}} .post-box-meta-single' => 'margin-top:{{SIZE}}px'
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'content_overlay', [
			'label' => esc_html__( 'Overlay Content', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'content_overlay_align', [
			'label'     => esc_html__( 'Text Align', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::CHOOSE,
			'options'   => array(
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
			'toggle'    => true,
			'selectors' => [ '{{WRAPPER}} .standard-post-special_wrapper,{{WRAPPER}} .standard-post-special_wrapper .penci-fto-ct,{{WRAPPER}} .standard-post-special_wrapper *,{{WRAPPER}} .post-box-meta-single' => 'text-align:{{VALUE}}' ],
		] );

		$this->add_control( 'content_overlay_calign', [
			'label'     => esc_html__( 'Content Align', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::CHOOSE,
			'options'   => array(
				'start'  => array(
					'title' => __( 'Top', 'soledad' ),
					'icon'  => 'eicon-v-align-top',
				),
				'center' => array(
					'title' => __( 'Center', 'soledad' ),
					'icon'  => 'eicon-v-align-middle',
				),
				'end'    => array(
					'title' => __( 'Bottom', 'soledad' ),
					'icon'  => 'eicon-v-align-bottom',
				),
			),
			'default'   => 'end',
			'toggle'    => true,
			'selectors' => [ '{{WRAPPER}} .standard-post-special_wrapper' => 'bottom:30px;top:30px;display:flex;flex-wrap: wrap;flex-direction: column;justify-content:{{VALUE}}' ],
		] );

		$this->add_control( 'hide_breadcrumb', [
			'label'     => esc_html__( 'Hide Breadcrumb', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [ '{{WRAPPER}} .penci-breadcrumb' => 'display:none' ],
		] );

		$this->add_control( 'hide_title', [
			'label'     => esc_html__( 'Hide Post Title', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [ '{{WRAPPER}} .single-post-title' => 'display:none' ],
		] );

		$this->add_control( 'hide_subtitle', [
			'label' => esc_html__( 'Hide Post Sub Title', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'penci_post_cat', [
			'label' => esc_html__( 'Hide Post Categories', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'hide_info', [
			'label'     => esc_html__( 'Hide Post Meta', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single' => 'display:none' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'post_meta', [
			'label'     => esc_html__( 'Post Meta', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
			'condition' => [ 'hide_info!' => 'yes' ],
		] );

		$this->add_control( 'hide_meta_label', [
			'label'     => esc_html__( 'Hide Meta Label?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
		] );

		$this->add_control( 'meta_divider', [
			'label'     => 'Remove Meta Divider Character',
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => array(
				'{{WRAPPER}} .post-box-meta-single > span:before' => 'display:none !important;'
			),
		] );

		$this->add_control( 'meta-item-spacing', [
			'label'      => 'Spacing Between Meta',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 100 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .post-box-meta-single > span:not(:last-child)'          => 'margin-right:{{SIZE}}px;',
				'body.rtl {{WRAPPER}} .post-box-meta-single > span:not(:last-child)' => 'margin-left:{{SIZE}}px;margin-right:0',
			],
		] );

		$this->add_control( 'penci_single_meta_author', [
			'label'     => esc_html__( 'Hide Author?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
		] );

		$this->add_control( 'penci_single_meta_author_update', [
			'label'     => esc_html__( 'Show Latest Update Author?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
		] );

		$this->add_control( 'penci_single_author_avatar', [
			'label'     => esc_html__( 'Hide Author Avatar?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'condition' => [ 'penci_single_meta_author!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_meta_ava_icon_check', [
			'label'     => esc_html__( 'Hide Post Author Icon?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'condition' => [ 'penci_single_meta_author!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_author_avatar_br', [
			'label'     => esc_html__( 'Author Borders Radius', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single .avatar' => 'border-radius:{{SIZE}}px' ],
			'condition' => [ 'penci_single_author_avatar!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_author_avatar_sp', [
			'label'     => esc_html__( 'Author Spacing', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single .avatar' => 'margin-left:{{SIZE}}px;margin-right:{{SIZE}}px' ],
			'condition' => [ 'penci_single_author_avatar!' => 'yes' ],
		] );

		$this->add_control( 'penci_avatar_w', [
			'default'   => 30,
			'label'     => esc_html__( 'Author Avatar Width', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::NUMBER,
			'condition' => [ 'penci_single_author_avatar!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_meta_date', [
			'label'     => esc_html__( 'Hide Post Date?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
		] );

		$this->add_control( 'penci_single_meta_date_icon_check', [
			'label'     => esc_html__( 'Hide Post Date Icon?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'condition' => [ 'penci_single_meta_date!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_meta_comment', [
			'label'     => esc_html__( 'Hide Post Comments?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
		] );

		$this->add_control( 'penci_single_meta_comment_icon_check', [
			'label'     => esc_html__( 'Hide Post Comment Icon?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'condition' => [ 'penci_single_meta_comment!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_show_cview', [
			'label'     => esc_html__( 'Hide Post View?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
		] );

		$this->add_control( 'penci_single_meta_view_icon_check', [
			'label'     => esc_html__( 'Hide Post View Icon?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'condition' => [ 'penci_single_show_cview!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_hreadtime', [
			'label'     => esc_html__( 'Hide Post Reading Time ?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
		] );

		$this->add_control( 'penci_single_meta_reading_icon_check', [
			'label'     => esc_html__( 'Hide Post Reading Icon?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'condition' => [ 'penci_single_hreadtime!' => 'yes' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'icon_settings', [
			'label' => esc_html__( 'Icon Settings', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'meta_icon_style', [
			'label'   => esc_html__( 'Icon Style', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'default',
			'options' => [
				'default' => 'Default',
				's1'      => 'Style 1',
				's2'      => 'Style 2',
				's3'      => 'Style 3',
				's4'      => 'Style 4',
			],
		] );

		$this->add_control( 'meta_icon_size', [
			'label'     => esc_html__( 'Icon Width', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcmt-icon' => 'width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px;',
			),
		] );

		$this->add_control( 'meta_icon_fsize', [
			'label'     => esc_html__( 'Icon Font Size', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcmt-icon' => 'font-size: {{SIZE}}px;',
			),
		] );

		$this->add_control( 'meta_icon_border', [
			'label'     => esc_html__( 'Icon Borders Radius', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcmt-icon' => 'border-radius: {{SIZE}}px;',
			),
		] );

		$this->add_control( 'meta_icon_borderw', [
			'label'     => esc_html__( 'Icon Borders Width', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 10, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcmt-icon' => 'border-width: {{SIZE}}px;',
			),
		] );

		$this->add_control( 'meta_icon_spacing', [
			'label'     => esc_html__( 'Icon Spacing', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 10, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcmt-icon' => 'margin-left: {{SIZE}}px;margin-right: {{SIZE}}px;',
			),
		] );

		$this->add_control( 'penci_single_meta_gnr_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pcmt-icon' => 'color:{{VALUE}}' ]
		] );

		$this->add_control( 'penci_single_meta_gnr_bg_color', [
			'label'     => esc_html__( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pcmt-icon'                                       => 'background-color:{{VALUE}}',
				'{{WRAPPER}} .post-box-meta-single.style-s3 .pcmt-icon:after'  => 'border-left-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .pcmt-icon:before' => 'border-left-color:{{VALUE}} !important',

			]
		] );

		$this->add_control( 'penci_single_meta_gnr_bd_color', [
			'label'     => esc_html__( 'Borders Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .pcmt-icon'                                      => 'border-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .pcmt-icon:after' => 'border-left-color:{{VALUE}} !important',
			]
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'author_icon_settings', [
			'label'     => esc_html__( 'Author Icon', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
			'condition' => [ 'penci_single_meta_ava_icon_check!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_meta_ava_icon', [
			'label'            => esc_html__( 'Post Author Icon', 'soledad' ),
			'type'             => \Elementor\Controls_Manager::ICONS,
			'fa4compatibility' => 'icon',
			'default'          => [
				'value'   => 'far fa-user',
				'library' => 'fa-regular',
			]
		] );

		$this->add_control( 'penci_single_meta_author_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .ava-icon' => 'color:{{VALUE}}' ]
		] );

		$this->add_control( 'penci_single_meta_author_bg_color', [
			'label'     => esc_html__( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .ava-icon'                                       => 'background-color:{{VALUE}}',
				'{{WRAPPER}} .post-box-meta-single.style-s3 .ava-icon:after'  => 'border-left-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .ava-icon:before' => 'border-left-color:{{VALUE}} !important',

			]
		] );

		$this->add_control( 'penci_single_meta_author_bd_color', [
			'label'     => esc_html__( 'Borders Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .ava-icon'                                      => 'border-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .ava-icon:after' => 'border-left-color:{{VALUE}} !important',
			]
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'date_icon_settings', [
			'label'     => esc_html__( 'Date Icon', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
			'condition' => [ 'penci_single_meta_date_icon_check!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_meta_date_icon', [
			'label'            => esc_html__( 'Post Date Icon', 'soledad' ),
			'type'             => \Elementor\Controls_Manager::ICONS,
			'fa4compatibility' => 'icon',
			'default'          => [
				'value'   => 'far fa-clock',
				'library' => 'fa-regular',
			]
		] );

		$this->add_control( 'penci_single_meta_date_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .date-icon' => 'color:{{VALUE}}' ]
		] );

		$this->add_control( 'penci_single_meta_date_bg_color', [
			'label'     => esc_html__( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .date-icon'                                       => 'background-color:{{VALUE}}',
				'{{WRAPPER}} .post-box-meta-single.style-s3 .date-icon:after'  => 'border-left-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .date-icon:before' => 'border-left-color:{{VALUE}} !important',

			]
		] );

		$this->add_control( 'penci_single_meta_date_bd_color', [
			'label'     => esc_html__( 'Borders Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .date-icon'                                      => 'border-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .date-icon:after' => 'border-left-color:{{VALUE}} !important',

			]
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'comment_icon_settings', [
			'label'     => esc_html__( 'Comment Icon', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
			'condition' => [ 'penci_single_meta_comment_icon_check!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_meta_comment_icon', [
			'label'            => esc_html__( 'Post Comment Icon', 'soledad' ),
			'type'             => \Elementor\Controls_Manager::ICONS,
			'fa4compatibility' => 'icon',
			'default'          => [
				'value'   => 'far fa-comment-dots',
				'library' => 'fa-regular',
			]
		] );

		$this->add_control( 'penci_single_meta_comment_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .comment-icon' => 'color:{{VALUE}}' ]
		] );

		$this->add_control( 'penci_single_meta_comment_bg_color', [
			'label'     => esc_html__( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .comment-icon'                                       => 'background-color:{{VALUE}}',
				'{{WRAPPER}} .post-box-meta-single.style-s3 .comment-icon:after'  => 'border-left-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .comment-icon:before' => 'border-left-color:{{VALUE}} !important',

			]
		] );

		$this->add_control( 'penci_single_meta_comment_bd_color', [
			'label'     => esc_html__( 'Borders Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .comment-icon'                                      => 'border-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .comment-icon:after' => 'border-left-color:{{VALUE}} !important',

			]
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'view_icon_settings', [
			'label'     => esc_html__( 'Post View Icon', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
			'condition' => [ 'penci_single_meta_view_icon_check!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_meta_view_icon', [
			'label'            => esc_html__( 'Post View Icon', 'soledad' ),
			'type'             => \Elementor\Controls_Manager::ICONS,
			'fa4compatibility' => 'icon',
			'default'          => [
				'value'   => 'far fa-eye',
				'library' => 'fa-regular',
			]
		] );

		$this->add_control( 'penci_single_meta_view_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .view-icon' => 'color:{{VALUE}}' ]
		] );

		$this->add_control( 'penci_single_meta_view_bg_color', [
			'label'     => esc_html__( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .view-icon'                                       => 'background-color:{{VALUE}}',
				'{{WRAPPER}} .post-box-meta-single.style-s3 .view-icon:after'  => 'border-left-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .view-icon:before' => 'border-left-color:{{VALUE}} !important',

			]
		] );

		$this->add_control( 'penci_single_meta_view_bd_color', [
			'label'     => esc_html__( 'Borders Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .view-icon'                                      => 'border-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .view-icon:after' => 'border-left-color:{{VALUE}} !important',

			]
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'reading_icon_settings', [
			'label'     => esc_html__( 'Reading Icon', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
			'condition' => [ 'penci_single_meta_reading_icon_check!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_meta_reading_icon', [
			'label'            => esc_html__( 'Post Reading Icon', 'soledad' ),
			'type'             => \Elementor\Controls_Manager::ICONS,
			'fa4compatibility' => 'icon',
			'default'          => [
				'value'   => 'far fa-book',
				'library' => 'fa-regular',
			]
		] );

		$this->add_control( 'penci_single_meta_reading_icon_color', [
			'label'     => esc_html__( 'Icon Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .reading-icon' => 'color:{{VALUE}}' ]
		] );

		$this->add_control( 'penci_single_meta_reading_bg_color', [
			'label'     => esc_html__( 'Background Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .reading-icon'                                       => 'background-color:{{VALUE}}',
				'{{WRAPPER}} .post-box-meta-single.style-s3 .reading-icon:after'  => 'border-left-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .reading-icon:before' => 'border-left-color:{{VALUE}} !important',
			]

		] );

		$this->add_control( 'penci_single_meta_reading_bd_color', [
			'label'     => esc_html__( 'Borders Color', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .reading-icon'                                      => 'border-color:{{VALUE}} !important',
				'{{WRAPPER}} .post-box-meta-single.style-s4 .reading-icon:after' => 'border-left-color:{{VALUE}} !important',
			]
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'color_style_settings', [
			'label' => esc_html__( 'General Style ', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'overlay_color', [
			'label' => 'Background Style',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), array(
			'name'     => 'overlay_bgcolor',
			'label'    => __( 'Overlay Background', 'soledad' ),
			'types'    => array( 'classic', 'gradient' ),
			'selector' => '{{WRAPPER}} .penci-move-title-above:after',
		) );

		$this->add_control( 'overlay_ctbgcolor', [
			'label'     => 'Content Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-fto-ct' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'overlay_ctw', [
			'label'     => 'Content Width',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 1170 ) ),
			'selectors' => [ '{{WRAPPER}} .penci-fto-ct' => 'max-width:{{SIZE}}px' ],
		] );

		$this->add_control( 'overlay_align', [
			'label'                => esc_html__( 'Content Width Align', 'soledad' ),
			'type'                 => \Elementor\Controls_Manager::CHOOSE,
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
			'selectors_dictionary' => array(
				'left'   => 'margin-right: auto !important',
				'center' => 'margin-left: auto !important; margin-right: auto !important;',
				'right'  => 'margin-left: auto !important',
			),
			'selectors'            => [ '{{WRAPPER}} .penci-fto-ct' => '{{VALUE}}' ],
		] );

		$this->add_responsive_control( 'overlay_ctpd', array(
			'label'      => __( 'Content Padding', 'soledad' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array( '{{WRAPPER}} .penci-fto-ct' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->add_control( 'overlay_ctbdr', [
			'label'     => 'Content Borders Radius',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
			'selectors' => [ '{{WRAPPER}} .penci-fto-ct' => 'border-radius:{{SIZE}}px' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'style_breadcrumb', [
			'label'     => esc_html__( 'Breadcrumb Style', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
			'condition' => [ 'hide_breadcrumb!' => 'yes' ],
		] );

		$this->add_control( 'breadcrumb_head', [
			'label' => 'Breadcrumb Style',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'breadcrumb_typo',
			'label'    => __( 'Typography for Breadcrumb', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-breadcrumb.single-breadcrumb',
		) );

		$this->add_control( 'breadcrumb_color', [
			'label'     => 'Breadcrumb Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-breadcrumb.single-breadcrumb span, {{WRAPPER}} .penci-breadcrumb.single-breadcrumb i,{{WRAPPER}} .penci-breadcrumb.single-breadcrumb a' => 'color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'breadcrumb_lhcolor', [
			'label'     => 'Breadcrumb Text Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-breadcrumb.single-breadcrumb a:hover' => 'color:{{VALUE}}' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'style_categories', [
			'label'     => esc_html__( 'Category Style', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
			'condition' => [ 'penci_post_cat!' => 'yes' ],
		] );

		$this->add_control( 'cat_pre_style', [
			'label'   => 'Category Style',
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				''   => 'Default Theme Style',
				's1' => 'Style 1',
				's2' => 'Style 2',
				's3' => 'Style 3',
				's4' => 'Style 4',
			],
		] );

		$this->add_control( 'cat_head', [
			'label' => 'Category Custom Style',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'cat_typo',
			'label'    => __( 'Typography for Categories', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-breadcrumb.single-breadcrumb',
		) );

		$this->add_control( 'cat_color', [
			'label'     => 'Category Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .cat' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'cat_lcolor', [
			'label'     => 'Category Link Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .cat > a.penci-cat-name' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'cat_lhcolor', [
			'label'     => 'Category Link Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .cat > a.penci-cat-name:hover' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'cat_bgcolor', [
			'label'     => 'Category Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .cat > a.penci-cat-name' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'cat_bghcolor', [
			'label'     => 'Category Background Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .cat > a.penci-cat-name:hover' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'cat_bcolor', [
			'label'     => 'Category Borders Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .cat > a.penci-cat-name' => 'border-color:{{VALUE}}' ],
		] );

		$this->add_control( 'cat_bhcolor', [
			'label'     => 'Category Borders Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .cat > a.penci-cat-name:hover' => 'border-color:{{VALUE}}' ],
		] );

		$this->add_control( 'cat_padding', [
			'label'      => 'Category Item Padding',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .cat > a.penci-cat-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->add_control( 'cat_border', [
			'label'      => 'Category Item Border',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .cat > a.penci-cat-name' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->add_control( 'cat_border_style', [
			'label'     => 'Category Borders Style',
			'type'      => \Elementor\Controls_Manager::SELECT,
			'options'   => [
				'dotted' => 'Dotted',
				'dashed' => 'Dashed',
				'solid'  => 'Solid',
				'double' => 'Double',
				'groove' => 'Groove',
				'ridge'  => 'Ridge',
				'inset'  => 'Inset',
				'outset' => 'Outset',
			],
			'selectors' => [ '{{WRAPPER}} .cat > a.penci-cat-name' => 'border-style:{{VALUE}}' ],
		] );

		$this->add_control( 'cat_border_radius', [
			'label'      => 'Category Borders Radius',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .cat > a.penci-cat-name' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->add_control( 'cat_divider', [
			'label'     => 'Remove Category Divider Character',
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => array(
				'{{WRAPPER}} .cat > a.penci-cat-name:after' => 'display:none !important;'
			),
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'meta_color_style', [
			'label' => esc_html__( 'General Meta Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'heading_typo',
			'label'    => __( 'Typography for Post Meta', 'soledad' ),
			'selector' => '{{WRAPPER}} .post-box-meta-single',
		) );

		$this->add_control( 'meta-color', [
			'label'     => 'Meta Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single, {{WRAPPER}} .post-box-meta-single span' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'meta-link-color', [
			'label'     => 'Meta Link Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single a' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'meta-link-hcolor', [
			'label'     => 'Meta Link Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single a:hover' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'remove-divider', [
			'label'     => 'Remove Divider Character',
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single > span:before' => 'display:none' ],
		] );

		$this->add_control( 'meta-color-heading', [
			'label' => 'Meta Color',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_control( 'meta-author-color', [
			'label'     => 'Author Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .author-post,{{WRAPPER}} .author-post .author' => 'color:{{VALUE}}' ],
			'condition' => [ 'penci_single_meta_author!' => 'yes' ],
		] );

		$this->add_control( 'meta-author-lcolor', [
			'label'     => 'Author Link Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .author-post a' => 'color:{{VALUE}}' ],
			'condition' => [ 'penci_single_meta_author!' => 'yes' ],
		] );

		$this->add_control( 'meta-author-hcolor', [
			'label'     => 'Author Link Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .author-post a:hover' => 'color:{{VALUE}}' ],
			'condition' => [ 'penci_single_meta_author!' => 'yes' ],
		] );

		$this->add_control( 'meta-pdate-color', [
			'label'     => 'Post Date Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pctmp-date-post' => 'color:{{VALUE}} !important' ],
			'condition' => [ 'penci_single_meta_date!' => 'yes' ],
		] );

		$this->add_control( 'meta-pcomment-color', [
			'label'     => 'Post Comment Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pctmp-comment-post' => 'color:{{VALUE}} !important' ],
			'condition' => [ 'penci_single_meta_comment!' => 'yes' ],
		] );

		$this->add_control( 'meta-pview-color', [
			'label'     => 'Post View Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .pctmp-view-post' => 'color:{{VALUE}} !important' ],
			'condition' => [ 'penci_single_show_cview!' => 'yes' ],
		] );

		$this->add_control( 'meta-preading-color', [
			'label'     => 'Post Reading Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .single-readtime' => 'color:{{VALUE}} !important' ],
			'condition' => [ 'penci_single_hreadtime!' => 'yes' ],
		] );

		$this->end_controls_section();

		/* Post Title */
		$this->start_controls_section( 'style_title', [
			'label'     => esc_html__( 'Title Style', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
			'condition' => [ 'hide_title!' => 'yes' ],
		] );

		$this->add_control( 'title_head', [
			'label' => 'Title Style',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'title_typo',
			'label'    => __( 'Typography for Post Title', 'soledad' ),
			'selector' => '{{WRAPPER}} .header-standard .post-title',
		) );

		$this->add_control( 'title_color', [
			'label'     => 'Post Title Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .header-standard .post-title' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'main-text-gcolor-enable', [
			'label' => 'Enable Gradient Color for Post Title',
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_group_control( \Elementor\Group_Control_Background::get_type(), array(
			'name'      => 'main-text-gcolor',
			'label'     => __( 'Gradient Color', 'soledad' ),
			'types'     => array( 'gradient' ),
			'selector'  => '{{WRAPPER}} .post-title.single-post-title span',
			'condition' => [ 'main-text-gcolor-enable' => 'yes' ]
		) );

		$this->add_control( 'main-text-inlinecolor-e', [
			'label'     => 'Use Inline Background Color?',
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [
				'{{WRAPPER}} .post-title'      => 'background-color:var(--pcaccent-cl);display:inline;box-decoration-break: clone;padding: 3px 8px;',
				'{{WRAPPER}} .header-standard' => 'padding-top: 3px',
			],
		] );

		$this->add_control( 'main-text-inlinecolor', [
			'label'     => 'Inline Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-title' => 'background-color:{{VALUE}}' ],
			'condition' => [ 'main-text-inlinecolor-e' => 'yes' ]
		] );

		$this->add_control( 'main-text-inline-d', [
			'label'      => 'Inline Background Padding',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .post-title'      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .header-standard' => 'padding-top: {{TOP}}{{UNIT}}',
			],
			'condition'  => [ 'main-text-inlinecolor-e' => 'yes' ]
		] );

		$this->add_control( 'title_bgcolor', [
			'label'     => 'Title Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .header-standard .post-title' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'title_bcolor', [
			'label'     => 'Title Borders Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .header-standard .post-title' => 'border-color:{{VALUE}}' ],
		] );

		$this->add_control( 'title_padding', [
			'label'      => 'Title Padding',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .header-standard .post-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->add_control( 'title_border', [
			'label'      => 'Title Border',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .header-standard .post-title' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->add_control( 'title_border_style', [
			'label'     => 'Title Borders Style',
			'type'      => \Elementor\Controls_Manager::SELECT,
			'options'   => [
				'dotted' => 'Dotted',
				'dashed' => 'Dashed',
				'solid'  => 'Solid',
				'double' => 'Double',
				'groove' => 'Groove',
				'ridge'  => 'Ridge',
				'inset'  => 'Inset',
				'outset' => 'Outset',
			],
			'selectors' => [ '{{WRAPPER}} .header-standard .post-title' => 'border-style:{{VALUE}}' ],
		] );

		$this->add_control( 'title_border_radius', [
			'label'      => 'Title Borders Radius',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .header-standard .post-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->end_controls_section();

		/* Post Title */
		$this->start_controls_section( 'style_subtitle', [
			'label'     => esc_html__( 'Sub Title Style', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
			'condition' => [ 'hide_subtitle!' => 'yes' ],
		] );

		$this->add_control( 'subtitle_head', [
			'label' => 'Sub Title Style',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'subtitle_typo',
			'label'    => __( 'Typography for Post Sub Title', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-psub-title',
		) );

		$this->add_control( 'subtitle_color', [
			'label'     => 'Post Sub Title Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-psub-title' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'subtitle_bgcolor', [
			'label'     => 'Sub Title Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-psub-title' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'subtitle_bcolor', [
			'label'     => 'Sub Title Borders Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-psub-title' => 'border-color:{{VALUE}}' ],
		] );

		$this->add_control( 'subtitle_padding', [
			'label'      => 'Sub Title Padding',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-psub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->add_control( 'subtitle_border', [
			'label'      => 'Sub Title Border',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-psub-title' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->add_control( 'subtitle_border_style', [
			'label'     => 'Sub Title Borders Style',
			'type'      => \Elementor\Controls_Manager::SELECT,
			'options'   => [
				'dotted' => 'Dotted',
				'dashed' => 'Dashed',
				'solid'  => 'Solid',
				'double' => 'Double',
				'groove' => 'Groove',
				'ridge'  => 'Ridge',
				'inset'  => 'Inset',
				'outset' => 'Outset',
			],
			'selectors' => [ '{{WRAPPER}} .penci-psub-title' => 'border-style:{{VALUE}}' ],
		] );

		$this->add_control( 'subtitle_border_radius', [
			'label'      => 'Sub Title Borders Radius',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-psub-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->end_controls_section();

		/* Post Meta */
		$this->start_controls_section( 'style_postmeta', [
			'label'     => esc_html__( 'Post Meta Style', 'soledad' ),
			'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
			'condition' => [ 'hide_info!' => 'yes' ],
		] );

		$this->add_control( 'postinfo_head', [
			'label' => 'Post Meta Style',
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'postinfo_typo',
			'label'    => __( 'Typography for Post Metarmations', 'soledad' ),
			'selector' => '{{WRAPPER}} .header-standard .post-box-meta-single span',
		) );

		$this->add_control( 'postinfo_color', [
			'label'     => 'Post Meta Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .header-standard .post-box-meta-single span' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'postinfo_lcolor', [
			'label'     => 'Post Meta Link Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .header-standard .post-box-meta-single span a' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'postinfo_lhcolor', [
			'label'     => 'Post Meta Link Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .header-standard .post-box-meta-single span a:hover' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'postinfo_bgcolor', [
			'label'     => 'Meta Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single > span' => 'background-color:{{VALUE}}' ],
		] );

		$this->add_control( 'postinfo_bcolor', [
			'label'     => 'Meta Borders Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single > span' => 'border-color:{{VALUE}}' ],
		] );

		$this->add_control( 'postinfo_padding', [
			'label'      => 'Meta Padding',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .post-box-meta-single > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->add_control( 'postinfo_border', [
			'label'      => 'Meta Border',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .post-box-meta-single > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->add_control( 'meta_border_style', [
			'label'     => 'Meta Borders Style',
			'type'      => \Elementor\Controls_Manager::SELECT,
			'options'   => [
				'dotted' => 'Dotted',
				'dashed' => 'Dashed',
				'solid'  => 'Solid',
				'double' => 'Double',
				'groove' => 'Groove',
				'ridge'  => 'Ridge',
				'inset'  => 'Inset',
				'outset' => 'Outset',
			],
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single > span' => 'border-style:{{VALUE}}' ],
		] );

		$this->add_control( 'meta_border_radius', [
			'label'      => 'Title Borders Radius',
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .post-box-meta-single > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			),
		] );

		$this->end_controls_section();

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
		global $post;
		$settings             = $this->get_settings();
		$avatar               = $settings['penci_single_author_avatar'];
		$avatarw              = isset( $settings['penci_avatar_w'] ) && $settings['penci_avatar_w'] ? $settings['penci_avatar_w'] : 32;
		$ava_icon_enable      = $settings['penci_single_meta_ava_icon_check'];
		$ava_icon             = $settings['penci_single_meta_ava_icon'];
		$date_icon_enable     = $settings['penci_single_meta_date_icon_check'];
		$date_icon            = $settings['penci_single_meta_date_icon'];
		$commment_icon_enable = $settings['penci_single_meta_comment_icon_check'];
		$commment_icon        = $settings['penci_single_meta_comment_icon'];
		$view_icon_enable     = $settings['penci_single_meta_view_icon_check'];
		$view_icon            = $settings['penci_single_meta_view_icon'];
		$reading_icon_enable  = $settings['penci_single_meta_reading_icon_check'];
		$reading_icon         = $settings['penci_single_meta_reading_icon'];
		$icon_style           = $settings['meta_icon_style'];
		$thumb_alt            = '';
		$label_text           = $settings['hide_meta_label'];
		$gradient_class       = $settings['main-text-gcolor-enable'] ? ' gradient-enable' : '';

		if ( has_post_thumbnail() ) {
			$thumb_id  = get_post_thumbnail_id( get_the_ID() );
			$thumb_alt = penci_get_image_alt( $thumb_id, get_the_ID() );
		}

		if ( penci_elementor_is_edit_mode() ) {
			$attachments = get_posts( [ 'post_type' => 'attachment', 'numberposts' => 1 ] );
			if ( ! empty( $attachments ) ) {
				$thumb_id  = $attachments[0]->ID;
				$thumb_alt = penci_get_image_alt( $thumb_id, get_the_ID() );
			}
			add_filter( 'has_post_thumbnail', function () {
				return true;
			} );
		}


		$move_title_bellow   = false;
		$enable_jarallax     = $settings['penci_enable_jarallax_single'];
		$pmt_enable_jarallax = get_post_meta( get_the_ID(), 'penci_enable_jarallax_single', true );

		if ( $pmt_enable_jarallax ) {
			$enable_jarallax = $pmt_enable_jarallax;
		}

		$simage_size = get_theme_mod( 'penci_single_custom_thumbnail_size' ) ? get_theme_mod( 'penci_single_custom_thumbnail_size' ) : 'penci-full-thumb';
		$image_size  = $settings['img_size'] ? $settings['img_size'] : $simage_size;

		if ( penci_is_mobile() ) {
			$image_size = $settings['img_msize'] ? $settings['img_msize'] : 'penci-masonry-thumb';
		}


		$div_special_wrapper = '';
		if ( ! $move_title_bellow ) {
			$div_special_wrapper .= '<div class="';
			$div_special_wrapper .= 'standard-post-special_wrapper' . $gradient_class;
			$div_special_wrapper .= '">';
		}

		$image_html = penci_get_featured_single_image_size( get_the_ID(), $image_size, $enable_jarallax, $thumb_alt );

		if ( penci_elementor_is_edit_mode() ) {
			$class       = 'attachment-penci-full-thumb size-penci-full-thumb penci-single-featured-img wp-post-image';
			$src         = PENCI_SOLEDAD_URL . '/inc/template-builder/placeholder.php?w=1200&h=800';
			$style_ratio = 'padding-top: 67%;';
			if ( $enable_jarallax ) {
				$image_html = '<img class="jarallax-img" src="' . $src . '" alt="' . $thumb_alt . '">';
			} elseif ( ! get_theme_mod( 'penci_speed_disable_first_screen' ) || get_theme_mod( 'penci_disable_lazyload_fsingle' ) ) {
				$image_html = '<span class="' . $class . ' penci-disable-lazy" style="background-image: url(' . $src . ');' . $style_ratio . '"></span>';
			} else {
				$image_html = '<span class="' . $class . ' penci-lazy" data-bgset="' . $src . '" style="' . $style_ratio . '"></span>';
			}
		}

		?>
		<?php if ( penci_get_post_format( 'link' ) || penci_get_post_format( 'quote' ) ) : ?>
			<?php
			$class_pimage_linkquote = 'standard-post-special post-image';
			if ( penci_get_post_format( 'quote' ) ) {
				$class_pimage_linkquote .= ' penci-special-format-quote';
			}
			if ( ! has_post_thumbnail() || get_theme_mod( 'penci_post_thumb' ) ) {
				$class_pimage_linkquote .= ' no-thumbnail';
			}

			if ( ! $move_title_bellow ) {
				$class_pimage_linkquote .= ' penci-move-title-above';
			}

			if ( $enable_jarallax ) {
				$class_pimage_linkquote .= ' penci-jarallax';
			}
			?>
        <div class="<?php echo( $class_pimage_linkquote ); ?>">
			<?php
			if ( has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
				echo $image_html;
			}
			?>
			<?php echo $div_special_wrapper; ?>
            <div class="standard-content-special">
                <div class="format-post-box<?php if ( penci_get_post_format( 'quote' ) ) {
					echo ' penci-format-quote';
				} else {
					echo ' penci-format-link';
				} ?>">
                    <span class="post-format-icon"><?php penci_fawesome_icon( 'fas fa-' . ( penci_get_post_format( 'quote' ) ? 'quote-left' : 'link' ) ); ?></span>
                    <p class="dt-special">
						<?php
						if ( penci_get_post_format( 'quote' ) ) {
							$dt_content = get_post_meta( $post->ID, '_format_quote_source_name', true );
							if ( ! empty( $dt_content ) ): echo sanitize_text_field( $dt_content ); endif;
						} else {
							$dt_content = get_post_meta( $post->ID, '_format_link_url', true );
							if ( ! empty( $dt_content ) ):
								echo '<a href="' . esc_url( $dt_content ) . '" target="_blank">' . sanitize_text_field( $dt_content ) . '</a>';
							endif;
						}
						?>
                    </p>
					<?php
					if ( penci_get_post_format( 'quote' ) ):
						$quote_author = get_post_meta( $post->ID, '_format_quote_source_url', true );
						if ( ! empty( $quote_author ) ):
							echo '<div class="author-quote"><span>' . sanitize_text_field( $quote_author ) . '</span></div>';
						endif;
					endif; ?>
                </div>
            </div>
            <div class="penci-fto-ct">
				<?php
				if ( ! $move_title_bellow ) {
					get_template_part( 'template-parts/single', 'breadcrumb' );
				}
				if ( ! $move_title_bellow && has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
					get_template_part( 'inc/template-builder/single-elements/entry', 'header', $settings );
				}
				?>
            </div>
			<?php if ( ! $move_title_bellow ): ?></div><?php endif; ?>
            </div>

		<?php elseif ( penci_get_post_format( 'gallery' ) ) : ?>

			<?php $images = get_post_meta( $post->ID, '_format_gallery_images', true ); ?>

			<?php if ( ! $move_title_bellow && has_post_thumbnail() ) : ?>
				<?php if ( ! get_theme_mod( 'penci_post_thumb' ) ) : ?>
                    <div class="post-image penci-move-title-above <?php echo( $enable_jarallax ? ' penci-jarallax' : '' ); ?>">
						<?php
						if ( ! get_theme_mod( 'penci_disable_lightbox_single' ) && ! $enable_jarallax ) {
							$thumb_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
							echo '<a href="' . esc_url( $thumb_url ) . '" data-rel="penci-gallery-image-content">';
							echo $image_html;
							echo '</a>';
						} else {
							echo $image_html;
						}

						echo $div_special_wrapper;

						echo '<div class="penci-fto-ct">';

						get_template_part( 'template-parts/single', 'breadcrumb' );
						if ( has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
							get_template_part( 'inc/template-builder/single-elements/entry', 'header', $settings );
						}
						echo '</div>';
						echo '</div>';
						?>
                    </div>
				<?php endif; ?>
			<?php elseif ( $images ) :
				$autoplay = ! get_theme_mod( 'penci_disable_autoplay_single_slider' ) ? 'true' : 'false';
				?>
                <div class="post-image">
                    <div class="swiper penci-owl-carousel penci-owl-carousel-slider penci-nav-visible"
                         data-auto="<?php echo $autoplay; ?>" data-lazy="true">
                        <div class="swiper-wrapper">
							<?php foreach ( $images as $image ) : ?>

								<?php $the_image = wp_get_attachment_image_src( $image, $image_size ); ?>
								<?php $the_caption = get_post_field( 'post_excerpt', $image );
								$image_alt         = penci_get_image_alt( $image, get_the_ID() );
								$image_title_html  = penci_get_image_title( $image );
								?>
                                <div class="swiper-slide swiper-mark-item">
                                    <figure class="penci-swiper-mask">
										<?php if ( get_theme_mod( 'penci_speed_disable_first_screen' ) || ! get_theme_mod( 'penci_disable_lazyload_fsingle' ) ) { ?>
                                            <img class="penci-lazy" data-src="<?php echo esc_url( $the_image[0] ); ?>"
                                                 alt="<?php echo $image_alt; ?>"<?php echo $image_title_html; ?> />
										<?php } else { ?>
                                            <img src="<?php echo esc_url( $the_image[0] ); ?>"
                                                 alt="<?php echo $image_alt; ?>"<?php echo $image_title_html; ?> />
										<?php } ?>
										<?php if ( get_theme_mod( 'penci_post_gallery_caption' ) && $the_caption ): ?>
                                            <p class="penci-single-gallery-captions penci-single-gaformat-caption"><?php echo $the_caption; ?></p>
										<?php endif; ?>
                                    </figure>
                                </div>

							<?php endforeach; ?>
                        </div>
                    </div>
                </div>
			<?php endif; ?>

		<?php elseif ( penci_get_post_format( 'video' ) ) : ?>
			<?php
			Penci_Sodedad_Video_Format::show_builder_video_embed( array(
				'post_id'             => $post->ID,
				'parallax'            => $enable_jarallax,
				'args'                => array( 'width' => '1920', 'height' => '1080' ),
				'show_title_inner'    => true,
				'move_title_bellow'   => $move_title_bellow,
				'div_special_wrapper' => $div_special_wrapper,
				'single_style'        => 'style-1'
			), $settings );
			?>
		<?php elseif ( penci_get_post_format( 'audio' ) ) : ?>
			<?php
			$class_pimage_audio = 'standard-post-image post-image audio';

			if ( ! has_post_thumbnail() || get_theme_mod( 'penci_post_thumb' ) ) {
				$class_pimage_audio .= ' no-thumbnail';
			}

			if ( $enable_jarallax ) {
				$class_pimage_audio .= ' penci-jarallax';
			}

			if ( ! $move_title_bellow ) {
				$class_pimage_audio .= ' penci-move-title-above';
			}

			?>
        <div class="<?php echo $class_pimage_audio; ?>">
			<?php
			if ( has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
				echo $image_html;
			}
			?>
			<?php echo $div_special_wrapper; ?>
            <div class="audio-iframe">
				<?php $penci_audio = get_post_meta( $post->ID, '_format_audio_embed', true );
				$penci_audio_str   = substr( $penci_audio, - 4 ); ?>
				<?php if ( wp_oembed_get( $penci_audio ) ) : ?>
					<?php echo wp_oembed_get( $penci_audio ); ?>
				<?php elseif ( $penci_audio_str == '.mp3' ) : ?>
					<?php echo do_shortcode( '[audio src="' . esc_url( $penci_audio ) . '"]' ); ?>
				<?php else : ?>
					<?php echo $penci_audio; ?>
				<?php endif; ?>
            </div>
            <div class="penci-fto-ct">
				<?php
				if ( ! $move_title_bellow ) {
					get_template_part( 'template-parts/single', 'breadcrumb' );
				}
				if ( ! $move_title_bellow && has_post_thumbnail() && ! get_theme_mod( 'penci_post_thumb' ) ) {
					get_template_part( 'inc/template-builder/single-elements/entry', 'header', $settings );
				}
				?>
            </div>
			<?php if ( ! $move_title_bellow ): ?></div><?php endif; ?>
            </div>

		<?php else : ?>

			<?php if ( has_post_thumbnail() && ! $settings['penci_post_thumb'] ) : ?>
                <div class="post-image <?php echo( ! $move_title_bellow ? ' penci-move-title-above' : '' ); ?>">
					<?php
					if ( ! get_theme_mod( 'penci_disable_lightbox_single' ) && ! $enable_jarallax ) {
						$thumb_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
						echo '<a href="' . esc_url( $thumb_url ) . '" data-rel="penci-gallery-bground-content">';
						echo $image_html;
						echo '</a>';
					} else {

						echo '<div class="' . ( $enable_jarallax ? 'penci-jarallax' : '' ) . '">';
						echo $image_html;
						echo '</div>';
					}

					echo $div_special_wrapper;
					echo '<div class="penci-fto-ct">';
					if ( ! $move_title_bellow ) {
						get_template_part( 'template-parts/single', 'breadcrumb' );
					}

					if ( ! $move_title_bellow && has_post_thumbnail() ) {
						?>
                        <div class="header-standard header-classic single-header">
						<?php if ( ! $settings['penci_post_cat'] ) : ?>
                            <div class="penci-standard-cat penci-single-cat <?php echo $settings['cat_pre_style']; ?>"><span
                                        class="cat">
                                    <?php if ( penci_elementor_is_edit_mode() ) {
	                                    echo '<a class="penci-cat-name" href="#">Category Name 1</a> <a class="penci-cat-name" href="#">Category Name 2</a> <a class="penci-cat-name" href="#">Category Name 3</a>';
                                    } else {
	                                    penci_category( '' );
                                    } ?>
                                </span></div>
						<?php endif; ?>

						<?php if ( empty( $settings['hide_title'] ) ): ?>
                            <h1 class="post-title single-post-title entry-title"><span><?php the_title(); ?></span></h1>
						<?php endif; ?>
						<?php
						if ( ! $settings['hide_subtitle'] ) {
							penci_display_post_subtitle();
						}
						?>
						<?php penci_soledad_meta_schema(); ?>
						<?php $hide_readtime = get_theme_mod( 'penci_single_hreadtime' ); ?>
						<?php if ( ! $settings['penci_single_meta_author'] || ! $settings['penci_single_meta_date'] || ! $settings['penci_single_meta_comment'] || $settings['penci_single_show_cview'] || $settings[ $hide_readtime ] ) : ?>

                            <div class="post-box-meta-single style-<?php echo esc_attr( $icon_style ); ?>">
								<?php if ( ! $settings['penci_single_meta_author'] ) :
									global $post;
									?>
                                    <span class="author-post byline">
                                        <span class="author vcard">
                                            <?php
                                            if ( ! $ava_icon_enable && $ava_icon ) {
	                                            echo '<span class="pcmt-icon ava-icon">';
	                                            \Elementor\Icons_Manager::render_icon( $ava_icon );
	                                            echo '</span>';
                                            }
                                            ?>
	                                        <?php if ( ! $label_text ) {
		                                        echo penci_get_setting( 'penci_trans_written_by' );
	                                        } ?>

	                                        <?php
	                                        $author_ids     = [];
	                                        $post_author_id = get_post_field( 'post_author', get_the_ID() );
	                                        if ( $post_author_id ) {
		                                        $author_ids[] = $post_author_id;
	                                        }
	                                        if ( function_exists( 'coauthors__echo' ) ) {
		                                        $author_list = coauthors__echo( 'ID', 'field', array(
			                                        'between'     => ',',
			                                        'betweenLast' => ',',
			                                        'before'      => '',
			                                        'after'       => '',
		                                        ), null, false );
		                                        if ( $author_list ) {
			                                        $author_ids = explode( ',', $author_list );
		                                        }
	                                        }
	                                        $total   = count( $author_ids );
	                                        $current = 0;
	                                        foreach ( $author_ids as $author_id ) {
		                                        $current ++;
		                                        ?>

                                                <a class="author-url url fn n"
                                                   href="<?php echo get_author_posts_url( $author_id ); ?>">
                                                <?php
                                                if ( ! $avatar ) {
	                                                echo get_avatar( $author_id, $avatarw );
                                                } else {
	                                                echo $current == 2 ? ', ' : ( ( $current == $total && $total > 1 ) ? ' & ' : '' );
                                                }
                                                echo get_the_author_meta( 'display_name', $author_id );
                                                ?>
                                            </a>
	                                        <?php } ?>
                                        </span>
                                    </span>
								<?php endif; ?>
								<?php penci_author_update_name( $settings['penci_single_meta_author_update'], $avatar, $avatarw ); ?>
								<?php if ( ! $settings['penci_single_meta_date'] ) : ?>
                                    <span class="pctmp-date-post">
                                <?php
                                if ( ! $date_icon_enable && $date_icon ) {
	                                echo '<span class="pcmt-icon date-icon">';
	                                \Elementor\Icons_Manager::render_icon( $date_icon );
	                                echo '</span>';
                                }
                                ?>
                                <?php penci_soledad_time_link( 'single' ); ?></span>
								<?php endif; ?>
								<?php if ( ! $settings['penci_single_meta_comment'] ) :
									?>
                                    <span class="pctmp-comment-post">
                                    <?php
                                    if ( ! $commment_icon_enable && $commment_icon ) {
	                                    echo '<span class="pcmt-icon comment-icon">';
	                                    \Elementor\Icons_Manager::render_icon( $commment_icon );
	                                    echo '</span>';
                                    }
                                    $comment_text  = ! $label_text ? ' ' . penci_get_setting( 'penci_trans_comment' ) : '';
                                    $comments_text = ! $label_text ? ' ' . penci_get_setting( 'penci_trans_comments' ) : '';
                                    ?>
                                    <?php comments_number( '0' . $comments_text, '1' . $comment_text, '%' . $comments_text ); ?></span>
								<?php endif; ?>
								<?php if ( 'yes' != $settings['penci_single_show_cview'] ) : ?>
                                    <span class="pctmp-view-post">
                                    <?php
                                    if ( ! $view_icon_enable && $view_icon ) {
	                                    echo '<span class="pcmt-icon view-icon">';
	                                    \Elementor\Icons_Manager::render_icon( $view_icon );
	                                    echo '</span>';
                                    }
                                    ?>
                                        <i class="penci-post-countview-number"><?php echo penci_get_post_views( get_the_ID() ); ?></i><?php if ( ! $label_text ) {
											echo ' ' . penci_get_setting( 'penci_trans_countviews' );
										} ?></span>
								<?php endif; ?>
								<?php if ( penci_isshow_reading_time( $hide_readtime ) ):
									?>
                                    <span class="single-readtime">
                                    <?php
                                    if ( ! $reading_icon_enable && $reading_icon ) {
	                                    echo '<span class="pcmt-icon reading-icon">';
	                                    \Elementor\Icons_Manager::render_icon( $reading_icon );
	                                    echo '</span>';
                                    }
                                    ?>
                                    <?php penci_reading_time(); ?></span>
								<?php endif; ?>
								<?php
								if ( get_the_post_thumbnail_caption() && get_theme_mod( 'penci_post_thumb_caption' ) && ! $move_title_bellow ) {
									echo '<span class="penci-featured-caption penci-fixed-caption penci-caption-relative">' . get_the_post_thumbnail_caption() . '</span>';
								}
								?>
								<?php do_action( 'penci_single_meta_content' ); ?>
                            </div>
						<?php endif; ?>
						<?php
						$recipe_title = get_post_meta( get_the_ID(), 'penci_recipe_title', true );
						if ( has_shortcode( get_the_content(), 'penci_recipe' ) || $recipe_title ) {
							do_action( 'penci_recipes_action_hook' );
						} ?>
                        </div><?php
					}
					echo '</div>';
					if ( ! $move_title_bellow ) {
						echo '</div>';
					}

					if ( get_the_post_thumbnail_caption() && get_theme_mod( 'penci_post_thumb_caption' ) && $move_title_bellow ) {
						echo '<span class="penci-featured-caption penci-fixed-caption">' . get_the_post_thumbnail_caption() . '</span>';
					}
					?>
                </div>
			<?php endif; ?>

		<?php endif;
	}
}
