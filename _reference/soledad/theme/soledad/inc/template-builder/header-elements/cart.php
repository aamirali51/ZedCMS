<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciHeaderCart extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Header - Cart', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-logo';
	}

	public function get_categories() {
		return [ 'penci-header-builder' ];
	}

	public function get_keywords() {
		return [ 'cart' ];
	}

	public function get_name() {
		return 'penci-header-cart';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_responsive_control( 'btn_size', [
			'label'   => esc_html__( 'Button Size', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SLIDER,
			'selectors' => [
				'{{WRAPPER}} .top-search-classes' => 'width:{{SIZE}}px;',
				'{{WRAPPER}} .top-search-classes a.cart-contents' => 'width:{{SIZE}}px;height:{{SIZE}}px;line-height:{{SIZE}}px',
			]
		] );

		$this->end_controls_section();

		// style
		$this->start_controls_section( 'content_style', [
			'label' => esc_html__( 'Style', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'btn_heading_01', [
			'label' => esc_html__( 'Cart Icon', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_control( 'btn_bgcolor', [
			'label' => esc_html__( 'Background Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents' => 'background:{{VALUE}}']
		] );
		
		$this->add_control( 'btn_bghvcolor', [
			'label' => esc_html__( 'Background Hover Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents:hover' => 'background:{{VALUE}}']
		] );
		
		$this->add_control( 'btn_txtcolor', [
			'label' => esc_html__( 'Text Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents' => 'color:{{VALUE}}']
		] );
		
		$this->add_control( 'btn_txthvcolor', [
			'label' => esc_html__( 'Text Hover Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents:hover' => 'color:{{VALUE}}']
		] );

		$this->add_control( 'btn_bdradius', [
			'label' => esc_html__( 'Border Radius', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::DIMENSIONS,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
		] );

		$this->add_control( 'btn_bdcolor', [
			'label' => esc_html__( 'Border Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents' => 'border:1px solid {{VALUE}};']
		] );

		$this->add_control( 'btn_bdwidth', [
			'label' => esc_html__( 'Border Width', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::DIMENSIONS,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents' => 'border-width:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
		] );

		$this->add_responsive_control( 'btn_iconsize', [
			'label' => esc_html__( 'Icon Size', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::SLIDER,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents > i' => 'font-size:{{SIZE}}px;']
		] );

		$this->add_control( 'btn_heading_02', [
			'label' => esc_html__( 'Counter Style', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );
		
		$this->add_control( 'btn_icolor', [
			'label' => esc_html__( 'Icon Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents > span' => 'color:{{VALUE}}']
		] );
		
		$this->add_control( 'btn_ihvcolor', [
			'label' => esc_html__( 'Icon Hover Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents:hover > span' => 'color:{{VALUE}}']
		] );
		
		$this->add_control( 'btn_ibgcolor', [
			'label' => esc_html__( 'Icon Background Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents > span' => 'background-color:{{VALUE}}']
		] );
		
		$this->add_control( 'btn_ihvbgcolor', [
			'label' => esc_html__( 'Icon Hover Background Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .top-search-classes a.cart-contents > span' => 'background-color:{{VALUE}}']
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'btn_icon_typo',
			'label'    => __( 'Cart Number Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .top-search-classes a.cart-contents > span',
		) );

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();
		?>
		<div class="pchd-elcart">
			<div class="pb-header-builder cart-icon">
				<?php
				get_template_part( 'template-parts/header/cart-icon' );
				?>
			</div>
		</div>	
		<?php
	}
}