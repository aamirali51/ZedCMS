<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciHeaderLogo extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Header - Logo', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-logo';
	}

	public function get_categories() {
		return [ 'penci-header-builder' ];
	}

	public function get_keywords() {
		return [ 'logo' ];
	}

	public function get_name() {
		return 'penci-header-logo';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->start_controls_tabs( 'logo_settings' );

		$this->start_controls_tab(
			'logo_normal', array(
				'label' => __( 'Normal', 'soledad' )
			)
		);

		$this->add_control( 'logo_img', [
			'label'   => esc_html__( 'Logo Image', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::MEDIA,
			'default' => array( 'url' => PENCI_SOLEDAD_URL . '/images/logo.png' ),
		] );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'logo_darkmode', array(
				'label' => __( 'Dark Mode', 'soledad' )
			)
		);

		$this->add_control( 'logo_img_dark', [
			'label'   => esc_html__( 'Logo Image for Dark Mode', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::MEDIA,
			'default' => array( 'url' => PENCI_SOLEDAD_URL . '/images/logo.png' ),
		] );
		
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'    => 'image_size',
				'default' => 'large',
			]
		);

		$this->add_control( 'logo_slogan', [
			'label'   => esc_html__( 'Logo Slogan', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => get_option( 'blogdescription' ),
		] );


		$this->add_control( 'link_to', [
			'label'   => esc_html__( 'Link To', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'site_url',
			'options' => [
				'none'     => esc_html__( 'None', 'soledad' ),
				'site_url' => esc_html__( 'Site URL', 'soledad' ),
				'custom'   => esc_html__( 'Custom URL', 'soledad' ),
			],
		] );

		$this->add_control(
			'url',
			[
				'label'              => esc_html__( 'URL', 'soledad' ),
				'type'               => \Elementor\Controls_Manager::URL,
				'condition'          => [
					'link_to' => 'custom',
				],
				'frontend_available' => true,
				'dynamic'            => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		// style
		$this->start_controls_section( 'content_style', [
			'label' => esc_html__( 'Style', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'soledad' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'soledad' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'soledad' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'soledad' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'          => esc_html__( 'Width', 'soledad' ),
				'type'           => \Elementor\Controls_Manager::SLIDER,
				'default'        => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'          => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .penci-mainlogo' => 'max-width:100%;width: {{SIZE}}{{UNIT}};height:auto',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'      => esc_html__( 'Height', 'soledad' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .penci-mainlogo' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label'     => esc_html__( 'Opacity', 'soledad' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-mainlogo' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .penci-mainlogo',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label'     => esc_html__( 'Opacity', 'soledad' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover .penci-mainlogo' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}}:hover .penci-mainlogo',
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label'     => esc_html__( 'Transition Duration', 'soledad' ) . ' (s)',
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-mainlogo' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'soledad' ),
				'type'  => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .penci-mainlogo',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-mainlogo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_box_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .penci-mainlogo',
			]
		);

		$this->end_controls_section();

	}

	protected function get_link_url( $settings ) {
		switch ( $settings['link_to'] ) {
			case 'none':
				return [ 'url' => '#' ];

			case 'custom':
				return ( ! empty( $settings['link']['url'] ) ) ? $settings['link'] : false;

			case 'site_url':
				return [ 'url' => home_url( '/' ) ];

			default:
				return [ 'url' => $settings['logo_img']['url'] ];
		}
	}

	protected function render() {
		$settings       = $this->get_settings();
		$logo_src       = isset( $settings['logo_img']['url'] ) ? $settings['logo_img']['url'] : '';
		$dark_logo      = isset( $settings['logo_img_dark']['url'] ) ? $settings['logo_img_dark']['url'] : '';
		$data_dark_logo = '';
		$logo_slogan    = $settings['logo_slogan'];
		$logo_url       = $this->get_link_url( $settings );
		if ( $dark_logo && get_theme_mod( 'penci_dms_enable' ) ) {
			$data_dark_logo .= 'data-lightlogo="' . esc_url( $logo_src ) . '"';
			$data_dark_logo .= ' data-darklogo="' . esc_url( $dark_logo ) . '"';
		}
		?>
        <div class="pc-builder-element pc-logo pc-logo-desktop penci-header-image-logo">
            <a rel="home" href="<?php echo esc_url( $logo_url['url'] ); ?>">
                <img class="penci-mainlogo penci-limg pclogo-cls" <?php echo $data_dark_logo; ?>
                     src="<?php echo esc_url( $logo_src ); ?>"
                     alt="<?php bloginfo( 'name' ); ?>"
                     width="<?php echo penci_get_image_data_basedurl( $logo_src, 'w' ); ?>"
                     height="<?php echo penci_get_image_data_basedurl( $logo_src, 'h' ); ?>">
				<?php if ( ! empty( $logo_slogan ) ): ?>
                    <div class="site-slogan"><span><?php echo esc_attr( $logo_slogan ); ?></span></div>
				<?php endif; ?>
            </a>
        </div>
		<?php
	}
}