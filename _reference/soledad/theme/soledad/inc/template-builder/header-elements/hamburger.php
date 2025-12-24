<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciHeaderHamburger extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Header - Hamburger Menu', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-logo';
	}

	public function get_categories() {
		return [ 'penci-header-builder' ];
	}

	public function get_keywords() {
		return [ 'bookmark' ];
	}

	public function get_name() {
		return 'penci-header-hamburger';
	}

	protected function register_controls() {

		// style
		$this->start_controls_section( 'content_style', [
			'label' => esc_html__( 'Style', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'btn_notice', [
			'type'    => \Elementor\Controls_Manager::ALERT,
			'alert_type' => 'warning',
			'content'    => __('You can go to "Appearance > Customize > Vertical Navigation & Menu Hamburger" to adjust the sidebar hamburger.','soledad' ),
			'render_type' => 'ui',
		] );
		
		$this->add_responsive_control( 'btn_size', [
			'label'   => esc_html__( 'Menu Size', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SLIDER,
			'selectors'    => ['{{WRAPPER}} .penci-menuhbg-toggle.builder'=>'--pcbd-menuhbg-size:{{SIZE}}px'],
		] );
		
		$this->add_control( 'btn_color', [
			'label'   => esc_html__( 'Menu Color', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::COLOR,
			'selectors'    => ['{{WRAPPER}} .pc-builder-element a.penci-menuhbg-toggle .lines-button:after, {{WRAPPER}} .pc-builder-element a.penci-menuhbg-toggle.builder .penci-lines:before,{{WRAPPER}} .pc-builder-element a.penci-menuhbg-toggle.builder .penci-lines:after'=>'background-color:{{VALUE}}'],
		] );

		$this->add_control( 'btn_hcolor', [
			'label'   => esc_html__( 'Menu Hover Color', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::COLOR,
			'selectors'    => ['{{WRAPPER}} .pc-builder-element a.penci-menuhbg-toggle:hover .lines-button:after, {{WRAPPER}} .pc-builder-element a.penci-menuhbg-toggle.builder:hover .penci-lines:before,{{WRAPPER}} .pc-builder-element a.penci-menuhbg-toggle.builder:hover .penci-lines:after'=>'background-color:{{VALUE}}'],
		] );

		$this->add_responsive_control( 'btn_bwidth', [
			'label'   => esc_html__( 'Border Width', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::SLIDER,
			'selectors'    => ['{{WRAPPER}} .penci-menuhbg-toggle.builder'=>'border-width:{{SIZE}}px'],
		] );
		
		$this->add_control( 'btn_bdcolor', [
			'label'   => esc_html__( 'Menu Border Color', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::COLOR,
			'selectors'    => ['{{WRAPPER}} .pc-builder-element a.penci-menuhbg-toggle'=>'border-color:{{VALUE}}'],
		] );

		$this->add_control( 'btn_bdhcolor', [
			'label'   => esc_html__( 'Menu Hover Border Color', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::COLOR,
			'selectors'    => ['{{WRAPPER}} .pc-builder-element a.penci-menuhbg-toggle:hover'=>'border-color:{{VALUE}}'],
		] );
		
		$this->add_control( 'btn_bgcolor', [
			'label'   => esc_html__( 'Menu Background Color', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::COLOR,
			'selectors'    => ['{{WRAPPER}} .pc-builder-element a.penci-menuhbg-toggle'=>'background-color:{{VALUE}}'],
		] );

		$this->add_control( 'btn_bghcolor', [
			'label'   => esc_html__( 'Menu Hover Background Color', 'soledad' ),
			'type'    => \Elementor\Controls_Manager::COLOR,
			'selectors'    => ['{{WRAPPER}} .pc-builder-element a.penci-menuhbg-toggle:hover'=>'background-color:{{VALUE}}'],
		] );


		$this->end_controls_section();

	}

	protected function render() {
		$settings  = $this->get_settings();
		?>
        <div class="pc-builder-element penci-menuhbg-wapper penci-menu-toggle-wapper penci-elhb-hbg">
            <a href="#" aria-label="Open Menu"
               class="penci-menuhbg-toggle builder">
				<span class="penci-menuhbg-inner">
					<i class="lines-button lines-button-double">
						<i class="penci-lines"></i>
					</i>
					<i class="lines-button lines-button-double penci-hover-effect">
						<i class="penci-lines"></i>
					</i>
				</span>
            </a>
        </div>
		<?php
		add_filter( 'theme_mod_penci_menu_hbg_show', function () {
			return true;
		} );
	}
}