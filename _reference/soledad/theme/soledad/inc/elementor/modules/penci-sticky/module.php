<?php

namespace PenciSoledadElementor\Modules\PenciSticky;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use PenciSoledadElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'penci-sticky';
	}

	public function register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'section_penci_extra_options', array(
				'label' => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Extra Options', 'soledad' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'penci_enable_sticky',
			array(
				'label'       => __( 'Enable Sticky sidebar and content', 'soledad' ),
				'description' => __( 'Check on front end to see it works.', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => ''
			)
		);

		$element->add_control(
			'penci_enable_repons_section',
			array(
				'label'       => __( 'Flexible Responsive?', 'soledad' ),
				'description' => __( 'This option applies for Structure "25, 50, 25" only', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => ''
			)
		);

		$element->add_control(
			'penci_ctsidebar_mb',
			array(
				'label'       => __( 'Custom Position of Content and Sidebar on Mobile with Structure "25, 50, 25"', 'soledad' ),
				'description' => __( 'Check on front end to see it works.', 'soledad' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'con_sb2_sb1',
				'options'     => array(
					'con_sb2_sb1' => 'Content + Sidebar left + Sidebar right',
					'con_sb1_sb2' => 'Content + Sidebar right + Sidebar left',
					'sb2_con_sb1' => 'Sidebar left + Content + Sidebar right',
					'sb2_sb1_con' => 'Sidebar left + Sidebar right + Content',
					'sb1_con_sb2' => 'Sidebar right + Content + Sidebar left',
					'sb1_sb2_con' => 'Sidebar right + Sidebar left + Content',
				),
				'condition'   => array( 'penci_enable_repons_section' => 'yes' ),
			)
		);

		$element->end_controls_section();
	}

	public function register_container_controls( Controls_Stack $element ) {

		$element->start_controls_section(
			'section_penci_extra_options', array(
				'label' => penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Extra Options', 'soledad' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'penci_enable_sticky',
			array(
				'label'       => __( 'Enable Sticky Content', 'soledad' ),
				'description' => __( 'Check on front end to see it works. This option only works for the parent container.', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => ''
			)
		);

		$element->end_controls_section();
	}

	private function add_actions() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/container/section_layout/after_section_end', [
			$this,
			'register_container_controls'
		] );
	}
}
