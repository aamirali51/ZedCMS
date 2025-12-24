<?php

namespace PenciSoledadElementor\Modules\PenciSocialCounter\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use PenciSoledadElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PenciSocialCounter extends Base_Widget {

	public function get_name() {
		return 'penci-social-counter';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Social Counter', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-share';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return array( 'social counter' );
	}


	protected function register_controls() {


		$this->start_controls_section( 'section_general', array(
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'source', array(
			'label'   => __( 'Select Data Source', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'counter',
			'options' => array(
				'counter'    => esc_html__( 'Social Counter', 'soledad' ),
				'customizer' => esc_html__( 'Social Customizer (no counter)', 'soledad' ),
			)
		) );

		$this->add_control( 'app_id', array(
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => '<span style="color: red;font-weight: bold;">Note</span>: You need to setup data for socials sharing via <strong>Dashboard > Soledad > Social Counter</strong> to get the counter number work.',
			'content_classes' => 'elementor-descriptor',
			'condition'       => [ 'source' => 'counter' ]
		) );

		$this->add_control( 'app_id_2', array(
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => '<span style="color: red;font-weight: bold;">Note</span>: You need to fill out social media URL <strong>Apperance > Customize > Social Media</strong>.',
			'content_classes' => 'elementor-descriptor',
			'condition'       => [ 'source' => 'customizer' ]
		) );

		$this->add_control( 'hide_count', array(
			'label'     => __( 'Hide Counter Data & Show Social Name?', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'condition' => [ 'source' => 'counter' ]
		) );

		$this->add_control( 'social_style', array(
			'label'   => __( 'Select Pre-Build Design', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 's1',
			'options' => array(
				's1' => esc_html__( 'Style 1', 'soledad' ),
				's2' => esc_html__( 'Style 2', 'soledad' ),
				's3' => esc_html__( 'Style 3', 'soledad' ),
				's4' => esc_html__( 'Style 4', 'soledad' ),
			)
		) );

		$this->add_control( 'fill', array(
			'label'   => __( 'Filled or Bordered Style?', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'border',
			'options' => array(
				'fill'   => esc_html__( 'Filled', 'soledad' ),
				'border' => esc_html__( 'Bordered', 'soledad' ),
			)
		) );

		$this->add_control( 'shape', array(
			'label'   => __( 'Shape', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'rectangle',
			'options' => array(
				'rectangle' => esc_html__( 'Rectangle', 'soledad' ),
				'round'     => esc_html__( 'Round', 'soledad' ),
				'circle'    => esc_html__( 'Circle', 'soledad' ),
			)
		) );

		$this->add_control( 'column', array(
			'label'     => __( 'Select Columns', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''  => esc_html__( 'Default', 'soledad' ),
				'1' => esc_html__( '1 Column', 'soledad' ),
				'2' => esc_html__( '2 Columns', 'soledad' ),
				'3' => esc_html__( '3 Columns', 'soledad' ),
				'4' => esc_html__( '4 Columns', 'soledad' ),
			),
			'condition' => array( 'social_style!' => array( 's4' ) ),
		) );

		$this->add_control( 'tab_column', array(
			'label'     => __( 'Select Columns for Tablet', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''  => esc_html__( 'Default', 'soledad' ),
				'1' => esc_html__( '1 Column', 'soledad' ),
				'2' => esc_html__( '2 Columns', 'soledad' ),
				'3' => esc_html__( '3 Columns', 'soledad' ),
				'4' => esc_html__( '4 Columns', 'soledad' ),
			),
			'condition' => array( 'social_style!' => array( 's4' ) ),
		) );

		$this->add_control( 'mobile_column', array(
			'label'     => __( 'Select Columns for Mobile', 'soledad' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''  => esc_html__( 'Default', 'soledad' ),
				'1' => esc_html__( '1 Column', 'soledad' ),
				'2' => esc_html__( '2 Columns', 'soledad' ),
				'3' => esc_html__( '3 Columns', 'soledad' ),
			),
			'condition' => array( 'social_style!' => array( 's4' ) ),
		) );

		$this->add_control( 'color_style', array(
			'label'   => __( 'Colors Style', 'soledad' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'custom',
			'options' => array(
				'custom'    => esc_html__( 'Custom Color', 'soledad' ),
				'brandbg'   => esc_html__( 'Brands Background Color', 'soledad' ),
				'brandtext' => esc_html__( 'Brands Icons Color', 'soledad' ),
			),
		) );

		$this->add_responsive_control( 'hospace', array(
			'label'     => __( 'Horizontal Spacing Between Social Icons', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-wrapper' => '--pcsoc-space: {{SIZE}}px;',
			),
		) );

		$this->add_responsive_control( 'verspace', array(
			'label'     => __( 'Vertical Spacing Between Social Icons', 'soledad' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 200, ) ),
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-wrapper' => '--pcsoc-bspace: {{SIZE}}px;',
			),
		) );

		$this->add_responsive_control( 'customizer_s3_style', array(
			'label'      => __( 'Custom Height for Social Icon', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => array( 'px' => array( 'min' => 0, 'max' => 500, ) ),
			'conditions' => array(
				'terms' => array(
					array(
						'name'  => 'source',
						'value' => 'customizer',
					),
					array(
						'name'  => 'social_style',
						'value' => 's3',
					)
				)
			),
			'selectors'  => array(
				'{{WRAPPER}} .pcsoc-wrapper.pcsocs-s3 .pcsoc-item' => 'padding-top: {{SIZE}}px;padding-bottom: {{SIZE}}px;',
			),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_showhide', array(
			'label'     => esc_html__( 'Show/Hide Social Icons', 'soledad' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'source' => 'counter' ]
		) );

		$pmetas = array(
			'facebook'   => array( 'label' => __( 'Facebook', 'soledad' ), 'default' => 'yes' ),
			'twitter'    => array( 'label' => __( 'Twitter', 'soledad' ), 'default' => 'yes' ),
			'youtube'    => array( 'label' => __( 'Youtube', 'soledad' ), 'default' => 'yes' ),
			'instagram'  => array( 'label' => __( 'Instagram', 'soledad' ), 'default' => '' ),
			'pinterest'  => array( 'label' => __( 'Pinterest', 'soledad' ), 'default' => '' ),
			'flickr'     => array( 'label' => __( 'Flickr', 'soledad' ), 'default' => '' ),
			'vimeo'      => array( 'label' => __( 'Vimeo', 'soledad' ), 'default' => '' ),
			'soundcloud' => array( 'label' => __( 'SoundCloud', 'soledad' ), 'default' => '' ),
			'behance '   => array( 'label' => __( 'Behance', 'soledad' ), 'default' => '' ),
			'vk'         => array( 'label' => __( 'VK', 'soledad' ), 'default' => '' ),
			'tiktok'     => array( 'label' => __( 'Tiktok', 'soledad' ), 'default' => '' ),
			'twitch'     => array( 'label' => __( 'Twitch', 'soledad' ), 'default' => '' ),
			'rss'        => array( 'label' => __( 'RSS', 'soledad' ), 'default' => '' ),
		);

		foreach ( $pmetas as $key => $pmeta ) {
			$this->add_control( 'social_' . $key, array(
				'label'       => $pmeta['label'],
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => __( 'Show', 'soledad' ),
				'label_off'   => __( 'Hide', 'soledad' ),
				'default'     => $pmeta['default'],
				'separator'   => '',
				'description' => __( 'Setup ' . esc_attr( $pmeta['label'] ) . ' counter data <a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=penci_social_counter_settings#tab-' . $key ) ) . '">here</a>.' ),
			) );
		}

		$this->end_controls_section();

		$this->start_controls_section( 'section_showhide_customizer', array(
			'label'     => esc_html__( 'Show/Hide Social Icons', 'soledad' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'source' => 'customizer' ]
		) );

		$socials_profile = penci_social_media_array();
		$custom_social_icons = get_option( 'penci_custom_socials', array() );
		foreach ( $socials_profile as $name => $data ) {
			$default = in_array( $name, [ 'facebook', 'twitter', 'youtube', 'instagram' ] ) ? 'yes' : '';
			$this->add_control( 'social_profile_' . $name, array(
				'label'     => ucwords( isset( $custom_social_icons[ $name ]['name'] ) ? $custom_social_icons[ $name ]['name'] : $name ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'soledad' ),
				'label_off' => __( 'Hide', 'soledad' ),
				'default'   => $default,
				'separator' => '',
			) );
		}

		$this->end_controls_section();

		$this->register_block_title_section_controls();
		$this->start_controls_section( 'section_content_style', array(
			'label' => __( 'Social Items Settings', 'soledad' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'style_des', array(
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'Please note that: If you selected to use Brands Background Color or Brands Icons Color, some colors options below can not apply.', 'soledad' ),
			'content_classes' => 'elementor-descriptor',
		) );

		$this->add_responsive_control( 'counter_item_icon_size', [
			'label'      => __( 'Icon Size', 'soledad' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 500,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .pcsoc-icon i' => 'font-size: {{SIZE}}px;',
				'{{WRAPPER}} .pcsocs-s3 i'  => 'line-height: {{SIZE}}px;',
			],
		] );

		$this->add_control( 'bgcl', array(
			'label'     => __( 'Background Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsocs-s1 .pcsoc-item' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s2 .pcsoc-icon' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s3 .pcsoc-item' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s4 .pcsoc-icon' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'hbgcl', array(
			'label'     => __( 'Background Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsocs-s1 .pcsoc-item:hover'             => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s2 .pcsoc-item:hover .pcsoc-icon' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s3 .pcsoc-item:hover'             => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s4 .pcsoc-item:hover .pcsoc-icon' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'bordercl', array(
			'label'     => __( 'Borders Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => array( 'fill' => 'border' ),
			'selectors' => array(
				'{{WRAPPER}} .pcsocs-s1 .pcsoc-item' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s2 .pcsoc-icon' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s3 .pcsoc-item' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s4 .pcsoc-icon' => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'borderhcl', array(
			'label'     => __( 'Borders Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'condition' => array( 'fill' => 'border' ),
			'selectors' => array(
				'{{WRAPPER}} .pcsocs-s1 .pcsoc-item:hover'             => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s2 .pcsoc-item:hover .pcsoc-icon' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s3 .pcsoc-item:hover'             => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .pcsocs-s4 .pcsoc-item:hover .pcsoc-icon' => 'border-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'textcl', array(
			'label'     => __( 'Icons Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-item i' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'texthcl', array(
			'label'     => __( 'Icons Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-item:hover i' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'countcl', array(
			'label'     => __( 'Counter Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-counter' => 'color: {{VALUE}};',
			),
			'condition' => [ 'source' => 'counter' ]
		) );

		$this->add_control( 'counthcl', array(
			'label'     => __( 'Counter Text Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-item:hover .pcsoc-counter' => 'color: {{VALUE}};',
			),
			'condition' => [ 'source' => 'counter' ]
		) );

		$this->add_control( 'fanscl', array(
			'label'     => __( '"Fans" Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-item .pcsoc-fan' => 'color: {{VALUE}};',
			),
			'condition' => [ 'source' => 'counter' ]
		) );

		$this->add_control( 'fanshcl', array(
			'label'     => __( '"Fans" Text Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-item:hover .pcsoc-fan' => 'color: {{VALUE}};',
			),
			'condition' => [ 'source' => 'counter' ]
		) );

		$this->add_control( 'followcl', array(
			'label'     => __( '"Follow" Text Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-item .pcsoc-like' => 'color: {{VALUE}};',
			),
			'condition' => [ 'social_style' => 's2' ]
		) );

		$this->add_control( 'followhcl', array(
			'label'     => __( '"Follow" Text Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-item:hover .pcsoc-like' => 'color: {{VALUE}};',
			),
			'condition' => [ 'social_style' => 's2' ]
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'counter_item_typo_number',
			'label'     => __( 'Number Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .pcsoc-counter',
			'condition' => [ 'source' => 'counter' ]
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'counter_item_typo_infotext',
			'label'     => __( 'Info Text Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .pcsoc-fan, {{WRAPPER}} .pcsoc-like',
		) );

		$this->add_group_control( Group_Control_Typography::get_type(), array(
			'name'      => 'counter_item_typo_name',
			'label'     => __( 'Social Name Typography', 'soledad' ),
			'selector'  => '{{WRAPPER}} .pcsoc-counter.pcsoc-socname,{{WRAPPER}} .pcsoc-socname',
		) );

		$this->add_control( 'socialname_cl', array(
			'label'     => __( 'Social Name Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-counter.pcsoc-socname,{{WRAPPER}} .pcsoc-socname' => 'color: {{VALUE}};',
			),
		) );
		
		$this->add_control( 'socialname_hvcl', array(
			'label'     => __( 'Social Name Hover Color', 'soledad' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{WRAPPER}} .pcsoc-item:hover .pcsoc-counter.pcsoc-socname,{{WRAPPER}} .pcsoc-item:hover .pcsoc-socname' => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'use_shadow', array(
			'label'     => __( 'Use Shadow?', 'soledad' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => __( 'Yes', 'soledad' ),
			'label_off' => __( 'No', 'soledad' ),
			'separator' => 'before'
		) );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
			'name'      => 'shadow',
			'label'     => __( 'Box Shadow', 'soledad' ),
			'selector'  => '{{WRAPPER}} .pcsocshadow .pcsc-brandflag',
			'condition' => array( 'use_shadow' => 'yes' ),
		) );

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		$css_class     = 'penci-block-vc penci-social-counter';
		$wrapper_class = 'pcsoc-wrapper';
		$social_style  = isset( $settings['social_style'] ) && $settings['social_style'] && in_array( $settings['social_style'], array(
			's1',
			's2',
			's3',
			's4'
		) ) ? $settings['social_style'] : 's1';
		$fill          = $settings['fill'] ? $settings['fill'] : 'border';
		$shape         = $settings['shape'] ? $settings['shape'] : 'rectangle';
		$color_style   = $settings['color_style'] ? $settings['color_style'] : 'custom';
		$brand_class   = $brand_class_icon = '';
		if ( in_array( $social_style, array( 's2', 's4' ) ) ) {
			$brand_class_icon = ' pcsc-brandflag';
		} else if ( in_array( $social_style, array( 's1', 's3' ) ) ) {
			$brand_class = ' pcsc-brandflag';
		}

		$wrapper_class .= ' pcsocs-' . $social_style;
		$wrapper_class .= ' pcsocf-' . $fill;
		$wrapper_class .= ' pcsocs-' . $shape;
		$wrapper_class .= ' pcsoccl-' . $color_style;

		if ( 's4' != $social_style ) {
			$column_default        = in_array( $social_style, array( 's3' ) ) ? '3' : '1';
			$column                = $settings['column'] ? $settings['column'] : $column_default;
			$tab_column_default    = in_array( $social_style, array( 's3' ) ) ? 'default' : '1';
			$mobile_column_default = in_array( $social_style, array( 's3' ) ) ? '2' : '1';
			$tab_column            = $settings['tab_column'] ? $settings['tab_column'] : $tab_column_default;
			$mobile_column         = $settings['mobile_column'] ? $settings['mobile_column'] : $mobile_column_default;
			$wrapper_class         .= ' pcsocc-' . $column;
			$wrapper_class         .= ' pcsocc-tabcol-' . $tab_column;
			$wrapper_class         .= ' pcsocc-mocol-' . $mobile_column;
		}

		if ( 'yes' == $settings['use_shadow'] ) {
			$wrapper_class .= ' pcsocshadow';
		}
		?>
        <div class="pcsoc-wrapper-outside source-<?php echo esc_attr( $settings['source'] ); ?>">
			<?php $this->markup_block_title( $settings, $this ); ?>
            <div class="<?php echo $wrapper_class; ?>">
				<?php
				if ( 'counter' == $settings['source'] ) {
					$socials = array(
						'facebook',
						'twitter',
						'youtube',
						'instagram',
						'pinterest',
						'flickr',
						'vimeo',
						'soundcloud',
						'behance ',
						'vk',
						'tiktok',
						'twitch',
						'rss',
					);


					foreach ( $socials as $social ) {


						if ( empty( $settings[ 'social_' . $social ] ) ) {
							continue;
						}

						$social_info = \PENCI_FW_Social_Counter::get_social_counter( $social );

						$social_info_name = $social_info['name'] ?? '';

						if ( ! $social_info || ! $social_info_name ) {
							continue;
						}
						if ( 'tiktok' === $social ) {
							$count = $social_info['count'];
						} else {
							$count = \PENCI_FW_Social_Counter::format_followers( $social_info['count'] );
						}

						$count = $count ? $count : '';

						$social_icon     = $social_info['icon'];
						$social_follower = isset( $social_info['text_below'] ) && $social_info['text_below'] ? $social_info['text_below'] : '';
						$social_follow   = isset( $social_info['text_btn'] ) && $social_info['text_btn'] ? $social_info['text_btn'] : '';
						$social_url      = isset( $social_info['url'] ) && $social_info['url'] ? $social_info['url'] : '';
						?>
                        <div class="pcsoc-item-wrap">
                            <a class="pcsoc-item pcsoci-<?php echo $social . $brand_class; ?><?php if ( ! $count ) {
								echo ' empty-count';
							} ?>" href="<?php echo esc_url( $social_url ); ?>"
                               target="_blank" <?php echo penci_reltag_social_icons(); ?>>
                                <span class="pcsoc-icon pcsoci-<?php echo $social . $brand_class_icon; ?>"><?php echo $social_icon; ?></span>
								<?php if ( $count && 'yes' != $settings['hide_count'] ) { ?>
                                    <span class="pcsoc-counter"><?php echo $count; ?></span>
                                    <span class="pcsoc-fan"><?php echo $social_follower; ?></span>
								<?php } else { ?>
                                    <span class="pcsoc-counter pcsoc-socname"><?php echo $social; ?></span>
								<?php } ?>
								<?php if ( in_array( $social_style, array( 's1', 's2' ) ) ) { ?>
                                    <span class="pcsoc-like"><?php echo $social_follow; ?></span>
								<?php } ?>
                            </a>
                        </div>
						<?php
					}
				} else {
					$socials = penci_social_media_array();
					$custom_social_icons = get_option( 'penci_custom_socials', array() );
					foreach ( $socials as $social => $sdata ) {

						if ( empty( $settings[ 'social_profile_' . $social ] ) ) {
							continue;
						}

						$social_icon   = penci_icon_by_ver( $sdata[1] );
						$social_follow = penci_get_setting( 'penci_trans_follow' );
						$social_url    = esc_url( do_shortcode( $sdata[0] ) );
						$name = isset( $custom_social_icons[ $social ]['name'] ) ? $custom_social_icons[ $social ]['name'] : $social;
						?>
                        <div class="pcsoc-item-wrap">
                            <a class="pcsoc-item pcsoci-<?php echo $social . $brand_class; ?> empty-count"
                               href="<?php echo esc_url( $social_url ); ?>"
                               target="_blank" <?php echo penci_reltag_social_icons(); ?>>
                                <span class="pcsoc-icon pcsoci-<?php echo $social . $brand_class_icon; ?>"><?php echo $social_icon; ?></span>
                                <span class="pcsoc-counter pcsoc-socname"><?php echo $name; ?></span>
								<?php if ( in_array( $social_style, array( 's1', 's2' ) ) ) { ?>
                                    <span class="pcsoc-like"><?php echo $social_follow; ?></span>
								<?php } ?>
                            </a>
                        </div>
						<?php
					}
				}
				?>
            </div>
        </div>
		<?php
	}
}
