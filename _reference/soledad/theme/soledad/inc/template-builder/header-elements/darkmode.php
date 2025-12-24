<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciHeaderDarkmode extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Header - Dark Mode', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-logo';
	}

	public function get_categories() {
		return [ 'penci-header-builder' ];
	}

	public function get_keywords() {
		return [ 'darkmode' ];
	}

	public function get_name() {
		return 'penci-header-darkmode';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'btn_notice', [
			'type'    => \Elementor\Controls_Manager::ALERT,
			'alert_type' => 'warning',
			'content'    => __( 'Please go to "Appearance > Customize > Dark Mode/Dark Theme": turn on the "Enable Dark Mode Switcher" option before using this feature.', 'soledad' ),
			'render_type' => 'ui',
		] );
		
		$this->add_control( 'btn_style', [
			'label'   => esc_html__( 'Style', 'soledad' ),
			'default' => '3',
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'1' => __( 'Style 1', 'soledad' ),
				'2' => __( 'Style 2', 'soledad' ),
				'3' => __( 'Style 3', 'soledad' ),
				'4' => __( 'Style 4', 'soledad' ),
			]
		] );

		$this->end_controls_section();

		// style
		$this->start_controls_section( 'content_style', [
			'label' => esc_html__( 'Style', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'bgcolor', [
			'label' => esc_html__( 'Background Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['body {{WRAPPER}} .pc-dmswitcher-element' => '--pcdm_btnbg:{{VALUE}}']
		] );

		$this->add_control( 'dcolor', [
			'label' => esc_html__( 'Day Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['body {{WRAPPER}} .pc-dmswitcher-element' => '--pcdm_btnd:{{VALUE}}']
		] );

		$this->add_control( 'dbgcolor', [
			'label' => esc_html__( 'Day Background Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['body {{WRAPPER}} .pc-dmswitcher-element' => '--pcdm_btndbg:{{VALUE}}']
		] );

		$this->add_control( 'ncolor', [
			'label' => esc_html__( 'Night Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['body {{WRAPPER}} .pc-dmswitcher-element' => '--pcdm_btnn:{{VALUE}}']
		] );

		$this->add_control( 'nbgcolor', [
			'label' => esc_html__( 'Night Background Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['body {{WRAPPER}} .pc-dmswitcher-element' => '--pcdm_btnnbg:{{VALUE}}']
		] );


		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();
		$style = $settings['btn_style'];
		?>
		<div class="pb-header-builder pc-dmswitcher-element">
			<div class="pc_dm_mode style_<?php echo esc_attr( $style ); ?>">
				<label class="pc_dm_switch">
					<input type="checkbox" class="pc_dark_mode_toggle" aria-label="Darkmode Switcher">
					<span class="slider round"></span>
				</label>
			</div>
		</div>

		<?php
	}
}