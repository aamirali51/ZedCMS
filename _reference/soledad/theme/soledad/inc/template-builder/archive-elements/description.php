<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciArchiveDescription extends \Elementor\Widget_Base {

	public function get_title() {
		return esc_html__( 'Archive - Description', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-text';
	}

	public function get_categories() {
		return [ 'penci-custom-archive-builder' ];
	}

	public function get_keywords() {
		return [ 'archive', 'description' ];
	}

	protected function get_html_wrapper_class() {
		return 'pcab-adesc elementor-widget-' . $this->get_name();
	}

	public function get_name() {
		return 'penci-archive-description';
	}

	protected function register_controls() {

		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'General', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'desc_align', [
			'label'     => __( 'Text Align', 'soledad' ),
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
			'selectors' => [ '{{WRAPPER}} .penci-category-description.post-entry' => 'text-align:{{VALUE}}' ],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'color_style', [
			'label' => esc_html__( 'Color & Styles', 'soledad' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'desc_typo',
			'label'    => __( 'Typography for Archive Description', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-archive-description.post-entry, {{WRAPPER}} .penci-archive-description.post-entry p',
		) );

		$this->add_control( 'text-color', [
			'label'     => 'Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-archive-description.post-entry' => 'color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'main-text-color', [
			'label'     => 'Link Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-archive-description.post-entry a' => 'color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'main-text-hcolor', [
			'label'     => 'Link Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-archive-description.post-entry a:hover' => 'color:{{VALUE}} !important' ],
		] );

		$this->add_control( 'readmore-heading', [
			'label'     => 'Read More',
			'type'      => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_control( 'readmore-bg-color', [
			'label'     => 'Read More Background Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-category-description-button:before' => 'background: linear-gradient(to bottom, transparent 0px, {{VALUE}} 40px);' ],
		] );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'readmore_btn_typo',
			'label'    => __( 'Typography for Read more', 'soledad' ),
			'selector' => '{{WRAPPER}} .penci-category-description-button a',
		) );

		$this->add_control( 'readmore_btn_color', [
			'label'     => 'Read More Text Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-category-description-button a' => 'color:{{VALUE}}' ],
		] );

		$this->add_control( 'readmore_btn_hcolor', [
			'label'     => 'Read More Text Hover Color',
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .penci-category-description-button a:hover' => 'color:{{VALUE}}' ],
		] );

		$this->add_responsive_control( 'desc_maxheight', [
			'label'     => 'Max Height for Description Text',
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => array( 'px' => array( 'min' => 0, 'max' => 1000, ) ),
			'selectors' => [ 'body:not(.penci-disable-desc-collapse) {{WRAPPER}} .penci-category-description' => 'max-height:{{SIZE}}px' ],
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
		?>
        <div class="post-entry penci-category-description penci-archive-description">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus consequuntur eius iste mollitia quo
                veritatis, voluptatum. Atque culpa deleniti eligendi est explicabo modi officia optio porro reiciendis
                tempore, ut voluptas!</p>
        </div>
		<?php
	}

	protected function builder_content() {

		$desc_content = $button_desc = '';

		if ( ! get_theme_mod( 'penci_archive_disable_desc_collapse', true ) ) {

			$button_desc = '<div class="penci-category-description-button"><a aria-label="' . penci_get_setting( 'penci_trans_read_more' ) . '" title="' . penci_get_setting( 'penci_trans_read_more' ) . '" href="#">' . penci_get_setting( 'penci_trans_read_more' ) . '</a></div>';
		}

		if ( is_tag() && tag_description() ) {
			$desc_content = '<div class="post-entry penci-archive-description penci-tag-description"><div class="penci-category-description-inner">' . do_shortcode( tag_description() ) . '</div>' . $button_desc . '</div>';
		} elseif ( is_category() && category_description() ) {
			$desc_content = '<div class="post-entry penci-archive-description penci-category-description"><div class="penci-category-description-inner">' . do_shortcode( category_description() ) . '</div>' . $button_desc . '</div>';
		} elseif ( is_archive() && get_the_archive_description() ) {
			$desc_content = '<div class="post-entry penci-category-description penci-archive-description"><div class="penci-category-description-inner">' . do_shortcode( get_the_archive_description() ) . '</div>' . $button_desc . '</div>';
		}
		echo $desc_content;
	}
}
