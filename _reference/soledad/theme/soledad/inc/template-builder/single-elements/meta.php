<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleMeta extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Post - Meta', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-meta-data';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'meta' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcsb-meta elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-single-meta';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'meta_align', [
			'label'     => __( 'Meta Text Align', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::CHOOSE,
			'default'   => 'left',
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
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single' => 'text-align:{{VALUE}}' ],
		] );

		$this->add_control( 'hide_meta_label', [
			'label' => __( 'Hide Meta Label Text', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'justify_align', [
			'label'     => __( 'Justify Align?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single' => 'display:flex;flex-wrap:wrap;justify-content:space-between;' ],
		] );

		$this->add_control( 'remove-divider', [
			'label'     => 'Remove Divider Character',
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'selectors' => [ '{{WRAPPER}} .post-box-meta-single > span:before' => 'display:none' ],
		] );

		$this->add_control( 'meta-item-spacing', [
			'label'      => 'Spacing Between Meta',
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => array(
				'px' => array( 'max' => 100 ),
			),
			'selectors'  => [
				'{{WRAPPER}} .post-box-meta-single > span:not(:last-child)' => 'margin-right:{{SIZE}}px;',
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
			'label'     => esc_html__( 'Author Avatar Width', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::NUMBER,
			'default'   => 30,
			'condition' => [ 'penci_single_author_avatar!' => 'yes' ],
		] );

		$this->add_control( 'penci_single_meta_ava_icon_check', [
			'label'     => esc_html__( 'Hide Post Author Icon?', 'soledad' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'condition' => [ 'penci_single_meta_author!' => 'yes' ],
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
			'default'   => array( 'size' => 5 ),
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 50, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcmt-icon'          => 'margin-right: {{SIZE}}px;',
				'body.rtl {{WRAPPER}} .pcmt-icon' => 'margin-left: {{SIZE}}px;margin-right:0;',
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
				'value'   => 'far fa-comment-alt',
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

		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Color & Styles', 'soledad' ),
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

	}

	protected function render() {

		if ( penci_elementor_is_edit_mode() ) {
			$this->preview_content();
		} else {
			$this->builder_content();
		}

	}

	protected function preview_content() {
		$settings             = $this->get_settings();
		$avatar               = $settings['penci_single_author_avatar'];
		$avatarw              = $settings['penci_avatar_w'];
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
		$label_text           = $settings['hide_meta_label'];
		global $post;
		?>
        <div class="post-box-meta-single style-<?php echo esc_attr( $icon_style ); ?>">
			<?php if ( ! $settings['penci_single_meta_author'] ) : ?>
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
	            $post_author_id = get_post_field( 'post_author', $post->ID );
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
                                                echo get_the_author_meta( 'display_name', $author_id ); ?>
                                            </a>
	            <?php } ?>
                </span>
                </span>
			<?php endif; ?>
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
            $comment_text  = ! $label_text ? penci_get_setting( 'penci_trans_comment' ) : '';
            $comments_text = ! $label_text ? penci_get_setting( 'penci_trans_comments' ) : '';
            ?>
            <?php comments_number( '0 ' . $comments_text, '1 ' . $comment_text, '% ' . $comments_text ); ?></span>
			<?php endif; ?>
			<?php if ( ! $settings['penci_single_show_cview'] ) :
				?>
                <span class="pctmp-view-post">
            <?php
            if ( ! $view_icon_enable && $view_icon ) {
	            echo '<span class="pcmt-icon view-icon">';
	            \Elementor\Icons_Manager::render_icon( $view_icon );
	            echo '</span>';
            }
            ?>
                <i class="penci-post-countview-number"><?php echo penci_get_post_views( get_the_ID() ); ?></i> <?php if ( ! $label_text ) {
						echo penci_get_setting( 'penci_trans_countviews' );
					} ?></span>
			<?php endif; ?>
			<?php if ( penci_isshow_reading_time( $settings['penci_single_hreadtime'] ) ):
				?>
                <span class="single-readtime">
            <?php
            if ( ! $reading_icon_enable && $reading_icon ) {
	            echo '<span class="pcmt-icon reading -icon">';
	            \Elementor\Icons_Manager::render_icon( $reading_icon );
	            echo '</span>';
            }
            ?>
            <?php penci_reading_time(); ?></span>
			<?php endif; ?>
			<?php do_action( 'penci_single_meta_content' ); ?>
        </div>
		<?php
	}

	protected function builder_content() {
		$settings             = $this->get_settings();
		$hide_readtime        = $settings['penci_single_hreadtime'];
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
		$label_text           = $settings['hide_meta_label'];
		global $post;
		$author_ids = [];
		$author_id  = get_post_field( 'post_author', get_the_ID() );
		if ( $author_id ) {
			$author_ids[] = $author_id;
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
		?>
        <div class="post-box-meta-single style-<?php echo esc_attr( $icon_style ); ?>">
			<?php if ( ! $settings['penci_single_meta_author'] ) :
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
	                    <?php foreach ( $author_ids as $author_id ) {
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
                            echo get_the_author_meta( 'display_name', $author_id ); ?>
                        </a>
	                    <?php } ?>
                    </span>
                </span>
				<?php penci_author_update_name( $settings['penci_single_meta_author_update'], $avatar, $avatarw ); ?>
			<?php endif; ?>
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
			<?php if ( ! $settings['penci_single_show_cview'] ) : ?>
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
			<?php do_action( 'penci_single_meta_content' ); ?>
        </div>
		<?php
	}
}
