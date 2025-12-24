<?php

namespace PenciSoledadElementor\Modules\PenciTeamMember\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciTeamMember extends Base_Widget {

	public function get_name() {
		return 'penci-team-member';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Team Members', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'team memeber' );
	}

	public function get_marker_img_el( $image, $thumbnail_size = 'thumbnail' ) {
		if ( empty( $image['url'] ) ) {
			return '';
		}
		if ( ! empty( $image['id'] ) ) {
			$attr = wp_get_attachment_image_src( $image['id'], $thumbnail_size );
			if ( isset( $attr['url'] ) && $attr['url'] ) {
				$image['url'] = $attr['url'];
			}
		}

		return $image['url'];
	}

	protected function register_controls() {


		$this->start_controls_section( 'section_temmembers', array(
			'label' => esc_html__( 'Team memebers', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$repeater = new Repeater();
		$repeater->add_control( 'image', array(
			'label'   => __( 'Choose Image', 'soledad' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => array( 'url' => Utils::get_placeholder_image_src() ),
		) );
		$repeater->add_control( 'name', array(
			'label' => __( 'Name', 'soledad' ),
			'type'  => Controls_Manager::TEXT,
		) );
		$repeater->add_control( 'position', array(
			'label' => __( 'Position', 'soledad' ),
			'type'  => Controls_Manager::TEXT,
		) );
		$repeater->add_control( 'desc', array(
			'label' => __( 'Description', 'soledad' ),
			'type'  => Controls_Manager::TEXTAREA,
		) );
		$repeater->add_control( 'link_avatar', array(
			'label'       => __( 'Avatar URL', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://your-link.com/',
		) );
		$repeater->add_control( 'link_website', array(
			'label'       => __( 'Website URL', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://your-link.com/',
		) );
		$repeater->add_control( 'link_facebook', array(
			'label'       => __( 'Facebook URL', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://your-link.com/',
		) );
		$repeater->add_control( 'link_twitter', array(
			'label'       => __( 'Twitter URL', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://your-link.com/',
		) );
		$repeater->add_control( 'link_linkedin', array(
			'label'       => __( 'Linkedin URL', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://your-link.com/',
		) );
		$repeater->add_control( 'link_instagram', array(
			'label'       => __( 'Instagram URL', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://your-link.com/',
		) );
		$repeater->add_control( 'link_youtube', array(
			'label'       => __( 'Youtube URL', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://your-link.com/',
		) );
		$repeater->add_control( 'link_vimeo', array(
			'label'       => __( 'Vimeo URL', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://your-link.com/',
		) );
		$repeater->add_control( 'link_pinterest', array(
			'label'       => __( 'Pinterest URL', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://your-link.com/',
		) );
		$repeater->add_control( 'link_dribbble', array(
			'label'       => __( 'Dribbble URL', 'soledad' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => 'https://your-link.com/',
		) );

		$this->add_control( 'teammembers', array(
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => array(
				array(
					'name'          => __( 'Team member #1', 'soledad' ),
					'desc'          => 'I am text block. Click edit button to change this text.',
					'link'          => __( 'https://your-link.com', 'soledad' ),
					'image'         => array( 'url' => Utils::get_placeholder_image_src() ),
					'link_website'  => '#',
					'link_facebook' => '#',
					'link_twitter'  => '#',
				),
				array(
					'name'          => __( 'Team member #1', 'soledad' ),
					'desc'          => 'I am text block. Click edit button to change this text.',
					'link'          => __( 'https://your-link.com', 'soledad' ),
					'image'         => array( 'url' => Utils::get_placeholder_image_src() ),
					'link_website'  => '#',
					'link_facebook' => '#',
					'link_twitter'  => '#',
				),
				array(
					'name'          => __( 'Team member #1', 'soledad' ),
					'desc'          => 'I am text block. Click edit button to change this text.',
					'link'          => __( 'https://your-link.com', 'soledad' ),
					'image'         => array( 'url' => Utils::get_placeholder_image_src() ),
					'link_website'  => '#',
					'link_facebook' => '#',
					'link_twitter'  => '#',
				)
			),
			'title_field' => '{{{ name }}}',
		) );


		$this->end_controls_section();

		$this->start_controls_section( 'section_layout', array(
			'label' => esc_html__( 'Layout', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'style', array(
			'label'   => __( 'Choose Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 's1',
			'options' => array(
				's1' => esc_html__( 'Bordered', 'soledad' ),
				's2' => esc_html__( 'Background', 'soledad' ),
				's3' => esc_html__( 'Extended', 'soledad' ),
				's4' => esc_html__( 'Overlay Slide Up', 'soledad' ),
				's5' => esc_html__( 'Overlay', 'soledad' ),
			)
		) );
		$this->add_responsive_control( 'columns', array(
			'label'          => __( 'Columns', 'soledad' ),
			'type'           => Controls_Manager::SELECT,
			'default'        => '3',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'options'        => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
			)
		) );

		$this->add_group_control( Group_Control_Image_Size::get_type(), array(
			'name'      => 'penci_img',
			'default'   => 'penci-masonry-thumb',
			'separator' => 'none',
		) );

		$this->add_responsive_control( 'imageratio', array(
			'label'     => __( 'Custom Image Ratio Height/Width (%)', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 300, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-teammb-bsc .penci-teammb-img:before' => 'padding-top: {{SIZE}}%; height: auto;',
			),
		) );

		$this->add_responsive_control( 'width_img', array(
			'label'     => __( 'Custom Image Width', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'selectors' => array( '{{WRAPPER}} .penci-teammb-item .penci-teammb-img' => 'max-width: {{SIZE}}px;width: 100%; height: auto;' ),
		) );

		$this->add_responsive_control( 'imgradius', array(
			'label'      => __( 'Image Border Radius', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-teammb-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array( 'style' => array( 's1', 's2', 's3' ) ),
		) );

		$this->add_control( 'titpos', array(
			'label'   => __( 'Title Position', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'default',
			'options' => array(
				'default' => __( 'Default', 'soledad' ),
				'above'   => __( 'Above Position Text', 'soledad' ),
				'below'   => __( 'Below Position Text', 'soledad' ),
			)
		) );

		$this->add_control( 'social_shape', array(
			'label'   => __( 'Social Icons Shape', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'default',
			'options' => array(
				'default' => __( 'Default', 'soledad' ),
				'circle'  => __( 'Circle', 'soledad' ),
				'square'  => __( 'Square', 'soledad' ),
				'round'   => __( 'Round', 'soledad' ),
			)
		) );

		$this->add_control( 'social_style', array(
			'label'   => __( 'Social Icons Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'default',
			'options' => array(
				'default'  => __( 'Default', 'soledad' ),
				'filled'   => __( 'Filled', 'soledad' ),
				'bordered' => __( 'Bordered', 'soledad' ),
			)
		) );

		$this->add_control( 'social_colors', array(
			'label'   => __( 'Social Icons Colors Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'default',
			'options' => array(
				'default'   => __( 'Custom Colors', 'soledad' ),
				'brandbg'   => __( 'Brands Color', 'soledad' ),
				'brandtext' => __( 'Brands Text Color', 'soledad' ),
			)
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_spacing', array(
			'label' => esc_html__( 'Elements Spacing', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_responsive_control( 'row_gap', array(
			'label'     => __( 'Member Items Rows Gap', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}}  .pencisc-grid' => 'grid-row-gap: {{SIZE}}px' ),
		) );

		$this->add_responsive_control( 'col_gap', array(
			'label'     => __( 'Member Items Columns Gap', 'soledad' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}}  .pencisc-grid' => 'grid-column-gap: {{SIZE}}px' ),
		) );

		$this->add_control( 'team_image_martop', array(
			'label'     => __( 'Image Bottom Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-teammb-s2 .penci-teammb-img' => 'margin-bottom: {{SIZE}}px',
				'{{WRAPPER}} .penci-teammb-s1 .penci-teammb-img' => 'margin-bottom: {{SIZE}}px',
			),
			'condition' => array( 'style' => array( 's1', 's2' ) ),
		) );

		$this->add_control( 'team_name_martop', array(
			'label'     => __( 'Name Top Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-teammb-bsc .penci-team_member_name' => 'margin-top: {{SIZE}}px',
			),
		) );

		$this->add_control( 'team_pos_martop', array(
			'label'     => __( 'Position Text Top Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-team_member_pos, {{WRAPPER}} .penci-team_member_name + .penci-team_member_pos' => 'margin-top: {{SIZE}}px' ),
		) );

		$this->add_control( 'team_desc_martop', array(
			'label'     => __( 'Description Top Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-team_member_desc' => 'margin-top: {{SIZE}}px' ),
		) );

		$this->add_responsive_control( 'social_space', array(
			'label'     => __( 'Spacing Between Icons', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-team_member_socails .penci-social-item' => 'margin-left: calc( {{SIZE}}px / 2 );margin-right: calc( {{SIZE}}px / 2 );' ),
		) );

		$this->add_control( 'team_social_martop', array(
			'label'     => __( 'Social Icons Top Spacing', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 100, ) ),
			'selectors' => array( '{{WRAPPER}} .penci-team_member_socails' => 'margin-top: {{SIZE}}px' ),
		) );

		$this->end_controls_section();

		$this->register_block_title_section_controls();

		$this->start_controls_section( 'section_style_content', array(
			'label' => __( 'Team Members', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'heading_all', array(
			'label' => __( 'Team Members', 'soledad' ),
			'type'  => Controls_Manager::HEADING,
		) );

		$this->add_responsive_control( 'team_padding', array(
			'label'      => __( 'Content Padding', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-teammb-s1 .penci-teammb-inner'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .penci-teammb-s2 .penci-teammb-inner'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .penci-teammb-s3 .penci-team_item__info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array( 'style' => array( 's1', 's2', 's3' ) ),
		) );

		$this->add_control( 'team_bghcolor', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-teammb-inner' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'team_borderhcolor', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci-teammb-inner' => 'border:1px solid {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'team_bdw', array(
			'label'      => __( 'Borders Width', 'soledad' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array(
				'{{WRAPPER}} .penci-teammb-s1 .penci-teammb-inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .penci-teammb-s2 .penci-teammb-inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'  => array( 'style' => array( 's1', 's2' ) ),
		) );

		$this->add_control( 'heading_team_name', array(
			'label' => __( 'Name', 'soledad' ),
			'type'  => Controls_Manager::HEADING,
		) );

		$this->add_control( 'team_name_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci-team_member_name' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'team_name_typo',
			'selector' => '{{WRAPPER}} .penci-team_member_name',
		) );

		$this->add_control( 'heading_team_pos', array(
			'label'     => __( 'Position', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'team_pos_color', array(
			'label'     => __( 'Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci-team_member_pos' => 'color: {{VALUE}};' ),
		) );
		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'team_pos_typo',
			'selector' => '{{WRAPPER}} .penci-team_member_pos',
		) );

		$this->add_control( 'heading_team_desc', array(
			'label'     => __( 'Description', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );

		$this->add_control( 'team_desc_hcolor', array(
			'label'     => __( 'Description Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci-team_member_desc' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'     => 'team_desc_typo',
			'selector' => '{{WRAPPER}} .penci-team_member_desc',
		) );

		$this->add_control( 'heading_team_social', array(
			'label'     => __( 'Social Icons', 'soledad' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		) );
		$this->add_control( 'team_social_color', array(
			'label'     => __( 'Social Icons Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci-social-wrap .penci-social-item, {{WRAPPER}} .penci-social-wrap .penci-social-item i' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'team_social_hcolor', array(
			'label'     => __( 'Social Icons Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci-social-wrap .penci-social-item:hover, {{WRAPPER}} .penci-social-wrap .penci-social-item:hover i' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'social_bgcolor', array(
			'label'     => __( 'Social Icons Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-social-wrap .penci-social-item i' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'social_bghcolor', array(
			'label'     => __( 'Social Icons Hover Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( 'body:not(.pcdm-enable) {{WRAPPER}} .penci-social-wrap .penci-social-item:hover i' => 'background-color: {{VALUE}};' ),
		) );

		$this->add_control( 'social_bdcolor', array(
			'label'     => __( 'Social Icons Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci-social-wrap .penci-social-item i' => 'border-color: {{VALUE}};' ),
		) );

		$this->add_control( 'social_bdhcolor', array(
			'label'     => __( 'Social Icons Hover Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array( '{{WRAPPER}} .penci-social-wrap .penci-social-item:hover i' => 'border-color: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'social_size', array(
			'label'     => __( 'Icons Size', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-social-wrap .penci-social-item i' => 'font-size: {{SIZE}}px;'
			),
		) );

		$this->add_responsive_control( 'social_width', array(
			'label'     => __( 'Social Icons Width/Height', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .penci-teammb-bsc' => '--pcteammb-w: {{SIZE}}px;'
			),
		) );

		$this->add_responsive_control( 'social_bdw', array(
			'label'     => __( 'Social Icons Borders Width', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcteam-shape .penci-social-item i' => 'border-width: {{SIZE}}px;'
			),
		) );

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		if ( ! $settings['teammembers'] ) {
			return;
		}
		$social_colorcss = '';
		$team_members    = (array) $settings['teammembers'];
		$style           = isset( $settings['style'] ) ? $settings['style'] : 's1';
		$image_size      = isset( $settings['penci_img_size'] ) ? $settings['penci_img_size'] : 'penci-masonry-thumb';
		$social_shape    = isset( $settings['social_shape'] ) ? $settings['social_shape'] : 'default';
		$social_style    = isset( $settings['social_style'] ) ? $settings['social_style'] : 'default';
		$social_colors   = isset( $settings['social_colors'] ) ? $settings['social_colors'] : 'default';
		if ( 'default' != $social_shape && 'default' == $social_style ) {
			$social_style = 'filled';
		}
		$titpos = isset( $settings['titpos'] ) ? $settings['titpos'] : 'default';
		if ( 'brandbg' == $social_colors ) {
			$social_colorcss = ' penci-social-colored';
		} else if ( 'brandtext' == $social_colors ) {
			$social_colorcss = ' penci-social-textcolored';
		}

		$css_class = 'penci-block-vc penci-teammb-bsc';
		if ( 's5' == $style ) {
			$css_class .= ' penci-teammb-s4';
		}
		$css_class     .= ' penci-teammb-' . $style;
		$css_class     .= ' pencisc-grid-' . $settings['columns'];
		$column_tablet = isset( $settings['columns_tablet'] ) ? $settings['columns_tablet'] : '2';
		$column_mobile = isset( $settings['columns_mobile'] ) ? $settings['columns_mobile'] : '1';
		$css_class     .= ' pencisc-grid-tablet-' . $column_tablet;
		$css_class     .= ' pencisc-grid-mobile-' . $column_mobile;
		if ( 'default' != $social_shape || 'default' != $social_style ) {
			$css_class .= ' pcteam-shape';
		}
		$css_class .= ' pcsc-shape-' . $social_shape;
		$css_class .= ' pcsc-st-' . $social_style;
		$title_pos = 'below';
		if ( 's1' == $style ) {
			$title_pos = 'above';
		}
		if ( 'above' == $titpos || 'below' == $titpos ) {
			$title_pos = $titpos;
		}
		?>
        <div class="<?php echo esc_attr( $css_class ); ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
            <div class="penci-block_content pencisc-grid">
				<?php
				$link_target = 'target="_blank"';

				if ( ! get_theme_mod( 'penci_dis_noopener' ) ) {
					$link_target .= ' rel="noopener"';
				}
				foreach ( (array) $team_members as $item ) {

					$name_item     = isset( $item['name'] ) ? $item['name'] : '';
					$desc_item     = isset( $item['desc'] ) ? $item['desc'] : '';
					$position_item = isset( $item['position'] ) ? $item['position'] : '';

					$link_avatar_item    = isset( $item['link_website'] ) ? $item['link_avatar'] : '';
					$link_website_item   = isset( $item['link_website'] ) ? $item['link_website'] : '';
					$link_facebook_item  = isset( $item['link_facebook'] ) ? $item['link_facebook'] : '';
					$link_twitter_item   = isset( $item['link_twitter'] ) ? $item['link_twitter'] : '';
					$link_linkedin_item  = isset( $item['link_linkedin'] ) ? $item['link_linkedin'] : '';
					$link_instagram_item = isset( $item['link_instagram'] ) ? $item['link_instagram'] : '';
					$link_dribbble_item  = isset( $item['link_dribbble'] ) ? $item['link_dribbble'] : '';

					$link_youtube_item   = isset( $item['link_youtube'] ) ? $item['link_youtube'] : '';
					$link_vimeo_item     = isset( $item['link_vimeo'] ) ? $item['link_vimeo'] : '';
					$link_pinterest_item = isset( $item['link_pinterest'] ) ? $item['link_pinterest'] : '';
					$image_id            = isset( $item['image']['id'] ) ? $item['image']['id'] : '';
					$url_img_item        = Utils::get_placeholder_image_src();
					if ( $image_id ) {
						$url_img_item_array = wp_get_attachment_image_src( $image_id, $image_size );
						if ( isset( $url_img_item_array[0] ) && $url_img_item_array[0] ) {
							$url_img_item = $url_img_item_array[0];
						} else {
							$url_img_item = $item['image']['url'];
						}
					}
					?>
                    <div class="penci-teammb-item pencisc-grid-item">
                        <div class="penci-teammb-inner">
							<?php
							if ( $url_img_item ) {

								$link_before = $link_after = 'span';

								if ( $link_avatar_item ) {
									$link_before = 'a target="_blank" href="' . esc_url( $link_avatar_item ) . '"';
									$link_after  = 'a';
								}

								echo '<' . $link_before . ' '.penci_layout_bg( $url_img_item ).' class="penci-image-holder penci-teammb-img '.penci_layout_bg_class().'">' . penci_layout_img( $url_img_item ) . '</' . $link_after . '>';

							}
							?>
                            <div class="penci-team_item__info">
								<?php if ( $position_item && ( 'below' == $title_pos ) ): ?>
                                    <div class="penci-team_member_pos"><?php echo $position_item; ?></div>
								<?php endif; ?>
								<?php if ( $name_item ): ?>
                                    <h5 class="penci-team_member_name"><?php echo $name_item; ?></h5>
								<?php endif; ?>
								<?php if ( $position_item && ( 'above' == $title_pos ) ): ?>
                                    <div class="penci-team_member_pos"><?php echo $position_item; ?></div>
								<?php endif; ?>
								<?php if ( $desc_item ): ?>
                                    <div class="penci-team_member_desc"><?php echo $desc_item; ?></div>
								<?php endif; ?>
                                <div class="penci-team_member_socails penci-social-wrap<?php echo $social_colorcss; ?>">
									<?php if ( $link_website_item ): ?>
                                        <a <?php echo $link_target ?>class="penci-social-item penci-social-item website"
                                           href="<?php echo esc_url( $link_website_item ); ?>"><?php penci_fawesome_icon( 'fas fa-globe' ); ?></a>
									<?php endif; ?>
									<?php if ( $link_facebook_item ): ?>
                                        <a <?php echo $link_target ?>
                                                class="penci-social-item penci-social-item facebook-f"
                                                href="<?php echo esc_url( $link_facebook_item ); ?>"><?php penci_fawesome_icon( 'fab fa-facebook-f' ); ?></a>
									<?php endif; ?>
									<?php if ( $link_twitter_item ): ?>
                                        <a <?php echo $link_target ?>class="penci-social-item penci-social-item twitter"
                                           href="<?php echo esc_url( $link_twitter_item ); ?>"><?php penci_fawesome_icon( 'penciicon-x-twitter' ); ?></a>
									<?php endif; ?>

									<?php if ( $link_linkedin_item ): ?>
                                        <a <?php echo $link_target ?>
                                                class="penci-social-item penci-social-item linkedin"
                                                href="<?php echo esc_url( $link_linkedin_item ); ?>"><?php penci_fawesome_icon( 'fab fa-linkedin-in' ); ?></a>
									<?php endif; ?>
									<?php if ( $link_instagram_item ): ?>
                                        <a <?php echo $link_target ?>
                                                class="penci-social-item penci-social-item instagram"
                                                href="<?php echo esc_url( $link_instagram_item ); ?>"><?php penci_fawesome_icon( 'fab fa-instagram' ); ?></a>
									<?php endif; ?>
									<?php if ( $link_youtube_item ): ?>
                                        <a <?php echo $link_target ?>class="penci-social-item penci-social-item youtube"
                                           href="<?php echo esc_url( $link_youtube_item ); ?>"><?php penci_fawesome_icon( 'fab fa-youtube' ); ?></a>
									<?php endif; ?>
									<?php if ( $link_vimeo_item ): ?>
                                        <a <?php echo $link_target ?> class="penci-social-item penci-social-item vimeo"
                                                                      href="<?php echo esc_url( $link_vimeo_item ); ?>"><?php penci_fawesome_icon( 'fab fa-vimeo-v' ); ?></a>
									<?php endif; ?>
									<?php if ( $link_pinterest_item ): ?>
                                        <a <?php echo $link_target ?>
                                                class="penci-social-item penci-social-item pinterest"
                                                href="<?php echo esc_url( $link_pinterest_item ); ?>"><?php penci_fawesome_icon( 'fab fa-pinterest' ); ?></a>
									<?php endif; ?>
									<?php if ( $link_dribbble_item ): ?>
                                        <a <?php echo $link_target ?>
                                                class="penci-social-item penci-social-item dribbble"
                                                href="<?php echo esc_url( $link_dribbble_item ); ?>"><?php penci_fawesome_icon( 'fab fa-dribbble' ); ?></a>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>
        </div>
		<?php
	}
}
