<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciHeaderBookmark extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Header - Bookmark', 'soledad' );
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
		return 'penci-header-bookmark';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'btn_text', [
			'label' => esc_html__( 'Button Text', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::TEXT,
		] );

		$this->add_control( 'btn_target', [
			'label'   => esc_html__( 'Link Target', 'soledad' ),
			'default' => '_self',
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'_blank'  => __( 'Blank', 'soledad' ),
				'_self'   => __( 'Self', 'soledad' ),
				'_parent' => __( 'Parent', 'soledad' ),
				'_top'    => __( 'Top', 'soledad' ),
			]
		] );

		$this->end_controls_section();

		// style
		$this->start_controls_section( 'content_style', [
			'label' => esc_html__( 'Style', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'btn_text_color', [
			'label' => esc_html__( 'Button Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .penci-builder-element.top-search-classes a' => 'color:{{VALUE}}']
		] );
		
		$this->add_control( 'btn_text_hvcolor', [
			'label' => esc_html__( 'Button Hover Color', 'soledad' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .penci-builder-element.top-search-classes a:hover' => 'color:{{VALUE}}']
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'btn_text_typo',
			'label'    => __( 'Typography', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-builder-element.top-search-classes a',
		) );

		$this->end_controls_section();

	}

	protected function render() {
		$settings        = $this->get_settings();
		$btn_link_target = $settings['btn_target'];
		$btn_title       = $settings['btn_text'];
		$btn_title = ! empty( $btn_title ) ? $btn_title : '';

		$pages = get_option( 'penci_bl_set_pages' );
		if ( isset( $pages['subscribe_manage_page'] ) && $pages['subscribe_manage_page'] ) {
			echo '<div id="penci-header-bookmark" class="penci-header-bookmark-element penci-builder-element top-search-classes"><a title="' . penci_get_setting( 'penci_trans_bookmark' ) . '" target="' . $btn_link_target . '" href="' . esc_url( get_page_link( $pages['subscribe_manage_page'] ) ) . '">' . penci_icon_by_ver( 'fa fa-bookmark-o' ) . $btn_title . '</a></div>';
		}

	}
}