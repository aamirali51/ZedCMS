<?php

namespace PenciSoledadElementor\Modules\PenciAdvancedCalculator\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class PenciAdvancedCalculator extends Base_Widget {

	public function get_name() {
		return 'penci-advanced-calculator';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( 'Advanced Calculator', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-document-file';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'custom', 'advanced', 'calculator', 'math', 'formula' ];
	}

	public function get_script_depends() {
		return [ 'penci-advanced-calculator' ];
	}

	protected function register_controls() {


		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => esc_html__( 'Calculator Fields', 'soledad' ),
			]
		);

		$repeater = new Repeater();

		$field_types = [
			'text'     => esc_html__( 'Text', 'soledad' ),
			'number'   => esc_html__( 'Number', 'soledad' ),
			'hidden'   => esc_html__( 'Hidden', 'soledad' ),
			'disabled' => esc_html__( 'Disabled', 'soledad' ),
			'select'   => esc_html__( 'Select', 'soledad' ),
			'radio'    => esc_html__( 'Radio', 'soledad' ),
		];

		$repeater->add_control(
			'field_type',
			[
				'label'   => esc_html__( 'Type', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'number',
			]
		);

		$repeater->add_control(
			'field_label',
			[
				'label'   => esc_html__( 'Label', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label'      => esc_html__( 'Placeholder', 'soledad' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => '',
				'dynamic'    => [ 'active' => true ],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'text',
								'number',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label'      => esc_html__( 'Default Value', 'soledad' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => '',
				'dynamic'    => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'text',
								'number',
								'hidden',
								'disabled',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'label'       => esc_html__( 'Options', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'description' => esc_html__( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Item|f_name', 'soledad' ),
				'dynamic'     => [ 'active' => true ],
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'inline_list',
			[
				'label'      => esc_html__( 'Inline List', 'soledad' ),
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label'      => esc_html__( 'Column Width', 'soledad' ),
				'type'       => Controls_Manager::NUMBER,
				'default'    => 100,
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => [
								'hidden',
							],
						],
					],
				],
				'selectors'  => [
					'{{WRAPPER}}  .penci-advcal-el-field-group{{CURRENT_ITEM}}' => 'width: {{VALUE}}%',
				],
			]
		);

		$this->add_control(
			'form_fields',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'field_type'  => 'number',
						'field_label' => esc_html__( 'First Value', 'soledad' ),
						'placeholder' => esc_html__( 'Enter your value', 'soledad' ),
						'width'       => '100',
					],
					[
						'field_type'  => 'number',
						'field_label' => esc_html__( 'Second Value', 'soledad' ),
						'placeholder' => esc_html__( 'Enter your value', 'soledad' ),
						'width'       => '100',
					],
				],
				'title_field' => '{{{ field_label }}}',
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'section_forms_layout',
			[
				'label' => esc_html__( 'Form Settings', 'soledad' ),
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label'   => esc_html__( 'Label', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'     => esc_html__( 'Alignment', 'soledad' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'     => [
						'title' => esc_html__( 'Left', 'soledad' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'   => [
						'title' => esc_html__( 'Center', 'soledad' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'soledad' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}.penci-advcal-el-all-field-inline--yes .penci-advcal-form' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'all_field_inline' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__( 'Text Align', 'soledad' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
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
					'{{WRAPPER}} .penci-advcal-form, {{WRAPPER}} .penci-advcal-form input, {{WRAPPER}} .penci-advcal-form textarea' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_heading',
			[
				'label'   => esc_html__( 'Submit Button', 'soledad' ),
				'type'    => Controls_Manager::HEADING,
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Text', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Submit', 'soledad' ),
			]
		);

		$this->add_responsive_control(
			'button_size',
			[
				'label'        => esc_html__( 'Button Size', 'soledad' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'small',
				'options'      => [
					'small' => __( 'Small', 'soledad' ),
					'full'  => __( 'Full Width', 'soledad' ),
				],
				'selectors_dictionary' => [
					'small' => 'auto;',
					'full' => '100%;',
				],
				'selectors'    => [
					'{{WRAPPER}} .penci-advcal-button' => 'width: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_formula',
			[
				'label' => esc_html__( 'Calculator Settings', 'soledad' ),
			]
		);

		$this->add_control(
			'form_formula',
			[
				'label'       => esc_html__( 'Formula', 'soledad' ),
				'type'        => Controls_Manager::CODE,
				'dynamic'     => [ 'active' => true ],
				'default'     => esc_html__( 'SUM(f1 + f2)', 'soledad' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'form_formula_note',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __(
					'It\'s one of the most amazing features of this widget. There are lots of math formulas available for you. The fields will automatically detect their own indexing. If you insert 2 fields and want to add them then the formula would be <b>SUM(f1+f2)</b>.
				<br/>If you want to know details about formulas, please visit <a href="https://formulajs.info/functions/" target="_blank">Here</a>.',
					'soledad'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'form_result_show',
			[
				'label'   => esc_html__( 'Result Show', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'submit',
				'options' => [
					'submit' => esc_html__( 'On Submit', 'soledad' ),
					'change' => esc_html__( 'On Change', 'soledad' ),
				],
			]
		);

		$this->add_control(
			'form_result_position',
			[
				'label'   => esc_html__( 'Result Position', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'top'    => esc_html__( 'Top', 'soledad' ),
					'bottom' => esc_html__( 'Bottom', 'soledad' ),
				],
			]
		);

		$this->add_responsive_control(
			'result_alignment',
			[
				'label'       => esc_html__( 'Alignment', 'soledad' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'left'    => [
						'title' => esc_html__( 'Left', 'soledad' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'soledad' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'soledad' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'soledad' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'     => is_rtl() ? 'right' : 'left',
				'toggle'      => false,
				'label_block' => false,
				'selectors'   => [
					'{{WRAPPER}} .penci-advcal-result' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'form_result_text',
			[
				'label'       => esc_html__( 'Result Text', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [ 'active' => true ],
				'description' => esc_html__( 'HTML also allowed.', 'soledad' ),
				'default'     => esc_html__( 'Result is: ', 'soledad' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'form_result_error',
			[
				'label'       => esc_html__( 'Error Text', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [ 'active' => true ],
				'description' => esc_html__( 'This message will appear when user will do something wrong.', 'soledad' ),
				'default'     => esc_html__( 'Error, invalid data format. please fix the data format and send again. thanks!', 'soledad' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Form Style', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'     => esc_html__( 'Field Space', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '15',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-el-field-group:not(:last-child)'               => 'margin-bottom: {{SIZE}}{{UNIT}};margin-top: 0;',
					'{{WRAPPER}} .penci-advcal-el-name-email-inline + .penci-advcal-el-name-email-inline' => 'padding-left: {{SIZE}}px',
				],
			]
		);

		$this->add_responsive_control(
			'col_gap',
			[
				'label'     => esc_html__( 'Column Space', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '12',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-field-wrap' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .penci-advcal-form'       => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 );margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_labels',
			[
				'label'     => esc_html__( 'Label', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-el-field-group > label' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .penci-advcal-label',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => esc_html__( 'Fields', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_field_style' );

		$this->start_controls_tab(
			'tab_field_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-input, {{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_placeholder_color',
			[
				'label'     => esc_html__( 'Placeholder Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-input, {{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-select' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'field_border',
				'label'       => esc_html__( 'Border', 'soledad' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-input, {{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-select',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-input, {{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'field_box_shadow',
				'selector' => '{{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-input, {{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-select',
			]
		);

		$this->add_responsive_control(
			'field_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-input, {{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'field_typography',
				'label'     => esc_html__( 'Typography', 'soledad' ),
				'selector'  => '{{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-input, {{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-select',
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_focus',
			[
				'label' => esc_html__( 'Focus', 'soledad' ),
			]
		);

		$this->add_control(
			'field_focus_background',
			[
				'label'     => esc_html__( 'Background', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-input:focus, {{WRAPPER}} .penci-advcal-el-field-group textarea:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_focus_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-el-field-group .penci-advcal-el-input:focus, {{WRAPPER}} .penci-advcal-el-field-group textarea:focus' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'field_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		$this->start_controls_section(
			'section_submit_button_style',
			[
				'label'     => esc_html__( 'Submit Button', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'form_result_show' => 'submit'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'soledad' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background_color',
				'selector' => '{{WRAPPER}} .penci-advcal-button'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .penci-advcal-button',
				'separator'   => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-advcal-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-advcal-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .penci-advcal-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'soledad' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background_hover_color',
				'selector' => '{{WRAPPER}} .penci-advcal-button:hover'
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		$this->start_controls_section(
			'section_result_style',
			[
				'label' => esc_html__( 'Result', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'result_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-result' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'result_background_color',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .penci-advcal-result'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'result_border',
				'label'    => esc_html__( 'Border', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-advcal-result',
			]
		);

		$this->add_control(
			'result_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-advcal-result' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'result_box_shadow',
				'selector' => '{{WRAPPER}} .penci-advcal-result',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'result_typography',
				'selector' => '{{WRAPPER}} .penci-advcal-result',
			]
		);

		$this->add_responsive_control(
			'result_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-advcal-result' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_error_style',
			[
				'label' => esc_html__( 'Error', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'error_color',
			[
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .penci-advcal-error div' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'error_background_color',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .penci-advcal-error div'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'error_box_shadow',
				'selector' => '{{WRAPPER}} .penci-advcal-error div',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'error_typography',
				'selector' => '{{WRAPPER}} .penci-advcal-error div',
			]
		);

		$this->add_responsive_control(
			'error_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .penci-advcal-error div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->end_controls_section();
	}

	public function form_fields_render_attributes() {
		$settings = $this->get_settings_for_display();
		$id       = $this->get_id();

		$this->add_render_attribute(
			[
				'wrapper'         => [
					'class' => [
						'elementor-form-fields-wrapper',
					],
				],
				'field-group'     => [
					'class' => [
						'penci-advcal-el-field-group',
					],
				],
				'user_name_label' => [
					'for'   => 'user_name' . $id,
					'class' => [
						'penci-advcal-label',
					]
				],
				'user_name_input' => [
					'type'  => 'text',
					'name'  => 'name',
					'id'    => 'user_name' . $id,
					'class' => [
						'penci-advcal-el-input',
						'penci-advcal-el-form-',
					],
				],

			]
		);
	}

	public function get_attribute_name( $item ) {
		return "form_fields[{$item['custom_id']}]";
	}

	public function get_attribute_id( $item ) {
		return $item['custom_id'];
	}

	protected function make_select_field( $item, $item_index ) {
		$this->add_render_attribute(
			[
				'select-wrapper' . $item_index => [
					'class' => [
						// 'elementor-field',
						// 'elementor-select-wrapper',
						// esc_attr( $item['css_classes'] ),
					],
				],
				'select' . $item_index         => [
					'name'  => $this->get_attribute_name( $item ) . ( ! empty ( $item['allow_multiple'] ) ? '[]' : '' ),
					'id'    => $this->get_attribute_id( $item ),
					'class' => [
						'penci-advcal-el-select',
					],
				],
			]
		);

		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );

		if ( ! $options ) {
			return '';
		}

		?>
		<?php if ( $this->get_settings_for_display( 'show_labels' ) ) : ?>
            <label for="<?php echo wp_kses_post( $this->get_attribute_id( $item ) ); ?>"
                   class="penci-advcal-label">
				<?php echo wp_kses_post( $item['field_label'] ); ?>
            </label>
		<?php endif; ?>
        <div <?php $this->print_render_attribute_string( 'select-wrapper' . esc_attr( $item_index ) ); ?>>
            <select <?php $this->print_render_attribute_string( 'select' . esc_attr( $item_index ) ); ?>>
				<?php
				$i = 1;
				foreach ( $options as $key => $option ) {
					$item['custom_id'] = $i ++;
					$option_id         = $item['custom_id'] . $key . $item_index;
					$option_value      = esc_attr( $option );
					$option_label      = esc_html( $option );

					if ( false !== strpos( $option, '|' ) ) {
						list( $label, $value ) = explode( '|', $option );
						$option_value = esc_attr( $value );
						$option_label = esc_html( $label );
					}

					$this->add_render_attribute( $option_id, 'value', $option_value );

					echo '<option ' . wp_kses_post( $this->get_render_attribute_string( $option_id ) ) . '>' . wp_kses_post( $option_label ) . '</option>';
				}
				?>
            </select>
        </div>
		<?php
	}


	protected function make_radio_checkbox_field( $item, $item_index, $type ) {
		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );
		$html    = '';
		if ( $this->get_settings_for_display( 'show_labels' ) ) {
			$html .= '<label for="' . $this->get_attribute_id( $item ) . '" class="penci-advcal-label">
			' . wp_kses_post( $item['field_label'] ) . '
		</label>';
		}
		if ( $options ) {
			$html .= '<div class="elementor-field-subgroup penci-advcal-el-radio-inline-' . $item['inline_list'] . '">';
			$id   = $this->get_attribute_id( $item );
			foreach ( $options as $key => $option ) {
				$element_id   = $this->get_attribute_id( $item ) . $key;
				$html_id      = $this->get_attribute_id( $item ) . $key;
				$option_label = $option;
				$option_value = $option;
				if ( false !== strpos( $option, '|' ) ) {
					list( $option_label, $option_value ) = explode( '|', $option );
				}

				$this->add_render_attribute(
					$element_id,
					[
						'type'  => $type,
						'value' => $option_value,
						'class' => 'penci-advcal-el-radio',
						'name'  => $id,
					]
				);

				if ( ! empty ( $item['field_value'] ) && $option_value === $item['field_value'] ) {
					$this->add_render_attribute( $element_id, 'checked', 'checked' );
				}

				$html .= '<label id="' . $html_id . '" class="penci-field-option"><input ' . $this->get_render_attribute_string( $element_id ) . '> <span for="' . $html_id . '">' . $option_label . '</span></label>';
			}
			$html .= '</div>';
		}

		return $html;
	}

	private function render_result() {
		$settings = $this->get_settings_for_display();
		?>
        <div class="penci-advcal-field-wrap penci-advcal-el-field-group">
            <div class="penci-advcal-result">
				<?php echo wp_kses_post( $settings['form_result_text'] ); ?> <span></span>
            </div>
        </div>
		<?php
	}

	public function render() {
		$settings   = $this->get_settings_for_display();
		$id         = $this->get_id();
		$element_id = 'penci-advcal-' . $id;
		$formula    = $settings['form_formula'];

		$this->add_render_attribute(
			[
				'button' => [
					'class' => [
						'penci-button',
						'penci-advcal-button',
					],
				],
			]
		);
		$this->add_render_attribute(
			[
				'calculator' => [
					'class'         => 'penci-advcal',
					'id'            => $element_id,
					'data-settings' => [
						wp_json_encode(
							array_filter(
								[
									'id'         => '#' . $element_id,
									"formula"    => "formula:'" . $formula . "'",
									'resultShow' => $settings['form_result_show']
								]
							)
						),
					],
				],
			]
		);

		?>
        <div <?php $this->print_render_attribute_string( 'calculator' ); ?>>
            <div class="penci-advcal-wrapper">
                <form class="penci-advcal-form">
					<?php
					if ( 'top' == $settings['form_result_position'] ) {
						$this->render_result();
					}
					?>
					<?php
					$i                      = 1;
					foreach ( $settings['form_fields'] as $item_index => $item ) :
						$item['custom_id'] = $id . '-' . $i ++;
						$disabled_class     = $item['field_type'] == 'disabled' ? 'penci-advcal-mouse-disabled' : '';


						$field_value = ( $item['field_value'] ) ? strip_tags( $item['field_value'] ) : '';
						$field_value = filter_var( $field_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
						$field_value = number_format( (float) $field_value, 2, '.', '' );

						if ( $field_value == '0.00' ) {
							$field_value = $item['field_value'];
						}

						$placeholder = ( $item['placeholder'] ) ? strip_tags( $item['placeholder'] ) : '';
						$placeholder = filter_var( $placeholder, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
						$placeholder = number_format( (float) $placeholder, 2, '.', '' );

						if ( $placeholder == '0.00' ) {
							$placeholder = $item['placeholder'];
						}

						$this->add_render_attribute(
							[
								'field_label' . $item_index => [
									'for'   => $item['custom_id'],
									'class' => [
										'penci-advcal-label',
									]
								],
								'field_input' . $item_index => [
									'type'        => $item['field_type'] != 'disabled' ? $item['field_type'] : 'text',
									'value'       => $field_value,
									'id'          => $item['custom_id'],
									'placeholder' => $placeholder,
									'class'       => [
										'penci-advcal-el-input',
										$disabled_class
									],
								],
							],
							true
						);

						?>
                        <div class="penci-advcal-field-wrap penci-advcal-el-field-group elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">

							<?php

							switch ( $item['field_type'] ) {
								case 'text':
								case 'number':
									if ( $settings['show_labels'] ) {
										echo '<label ' . wp_kses_post( $this->get_render_attribute_string( 'field_label' . $item_index ) ) . '>' . wp_kses_post( $item['field_label'] ) . '</label>';
									}
									echo '<div class="penci-advcal-el-form-controls">';
									echo '<input ' . wp_kses_post( $this->get_render_attribute_string( 'field_input' . $item_index ) ) . '>';
									echo '</div>';
									break;

								case 'hidden':
									echo '<div class="penci-advcal-el-form-controls">';
									echo '<input ' . wp_kses_post( $this->get_render_attribute_string( 'field_input' . $item_index ) ) . '>';
									echo '</div>';
									break;

								case 'disabled':
									if ( $settings['show_labels'] ) {
										echo '<label ' . wp_kses_post( $this->get_render_attribute_string( 'field_label' . $item_index ) ) . '>' . wp_kses_post( $item['field_label'] ) . '</label>';
									}
									echo '<div class="penci-advcal-el-form-controls">';
									echo '<input ' . wp_kses_post( $this->get_render_attribute_string( 'field_input' . $item_index ) ) . ' disabled="disabled">';
									echo '</div>';
									break;

								case 'select':
									$this->make_select_field( $item, $item_index );
									break;

								case 'radio':
								case 'checkbox':
									echo $this->make_radio_checkbox_field( $item, esc_attr( $item_index ), esc_html( $item['field_type'] ) );
									break;

								default:
									echo esc_html_x( 'Something wrong!', 'Frontend', 'soledad' );
									break;
							}

							?>

                        </div>
					<?php endforeach; ?>

					<?php if ( $settings['form_result_show'] == 'submit' ) : ?>

                        <div class="penci-advcal-field-wrap">
                            <div class="elementor-field-type-submit">
                                <button <?php $this->print_render_attribute_string( 'button' ); ?> type="submit">
									<?php
									echo esc_html( $settings['button_text'] );
									?>
                                </button>
                            </div>
                        </div>

					<?php endif; ?>


                    <div class="penci-advcal-field-wrap penci-advcal-el-field-group penci-advcal-error penci-advcal-hidden">
                        <div class="penci-advcal-warning" penci-advcal-el-alert>
                            <a class="penci-advcal-close" penci-advcal-el-close></a>
                            <p class="penci-advcal-p">
								<?php echo esc_html( $settings['form_result_error'] ); ?>
                            </p>
                        </div>
                    </div>

					<?php
					if ( 'bottom' == $settings['form_result_position'] ) {
						$this->render_result();
					}
					?>

                </form>
            </div>
        </div>
		<?php
	}
}