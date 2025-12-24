<?php

namespace PenciSoledadElementor\Modules\PenciContactForm\Widgets;

use PenciSoledadElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class PenciContactForm extends Base_Widget {

	public function get_name() {
		return 'penci-contact-form';
	}

	public function get_title() {
		return penci_get_theme_name( 'Penci' ) . ' ' . esc_html__( ' Contact Form', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return [ 'penci-elements' ];
	}

	public function get_keywords() {
		return [ 'contact', 'form', 'email' ];
	}

	public function get_script_depends() {
		return [ 'recaptcha', 'penci-contact-form' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_forms_layout',
			[
				'label' => esc_html__( 'Forms Layout', 'soledad' ),
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

		$this->add_control(
			'contact_number',
			[
				'label' => esc_html__( 'Contact Number Field', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'show_subject',
			[
				'label'   => esc_html__( 'Subject Field', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_message',
			[
				'label'   => esc_html__( 'Message Field', 'soledad' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'two_columns',
			[
				'label' => esc_html__( 'Two Columns', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'name_email_field_inline',
			[
				'label'     => esc_html__( 'Name/Email Field Inline', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'two_columns' => ''
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
					'{{WRAPPER}} .pencif-contact-form, {{WRAPPER}} .pencif-contact-form input, {{WRAPPER}} .pencif-contact-form textarea' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'soledad' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Text', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Send Message', 'soledad' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'   => esc_html__( 'Size', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''           => esc_html__( 'Default', 'soledad' ),
					'small'      => esc_html__( 'Small', 'soledad' ),
					'large'      => esc_html__( 'Large', 'soledad' ),
					'full-width' => esc_html__( 'Full Width', 'soledad' ),
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => esc_html__( 'Alignment', 'soledad' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => '',
				'options' => [
					'start'   => [
						'title' => esc_html__( 'Left', 'soledad' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'soledad' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'     => [
						'title' => esc_html__( 'Right', 'soledad' ),
						'icon'  => 'eicon-text-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Justified', 'soledad' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_forms_additional_options',
			[
				'label' => esc_html__( 'Additional Options', 'soledad' ),
			]
		);

		$this->add_control(
			'user_name_heading',
			[
				'label'     => esc_html__( 'Name Field', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'user_name_label',
			[
				'label'     => esc_html__( 'Label', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Name*', 'soledad' ),
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);


		$this->add_control(
			'user_name_placeholder',
			[
				'label'   => esc_html__( 'Placeholder', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Your Name', 'soledad' ),
			]
		);

		$this->add_control(
			'contact_heading',
			[
				'label'     => esc_html__( 'Contact Number Field', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'contact_label',
			[
				'label'     => esc_html__( 'Label', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Contact Number', 'soledad' ),
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'contact_placeholder',
			[
				'label'   => esc_html__( 'Placeholder', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Your contact number', 'soledad' ),
			]
		);

		$this->add_control(
			'subject_heading',
			[
				'label'     => esc_html__( 'Subject Field', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subject_label',
			[
				'label'     => esc_html__( 'Label', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Subject*', 'soledad' ),
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'subject_placeholder',
			[
				'label'   => esc_html__( 'Placeholder', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Your message subject', 'soledad' ),
			]
		);

		$this->add_control(
			'email_address_heading',
			[
				'label'     => esc_html__( 'Email Field', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'email_address_label',
			[
				'label'     => esc_html__( 'Label', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Email*', 'soledad' ),
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'email_placeholder',
			[
				'label'   => esc_html__( 'Placeholder', 'soledad' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'example@email.com', 'soledad' ),
			]
		);

		$this->add_control(
			'message_label_heading',
			[
				'label'     => esc_html__( 'Message Field', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'message_label',
			[
				'label'     => esc_html__( 'Label', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Your Message*', 'soledad' ),
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'message_placeholder',
			[
				'label'     => esc_html__( 'Placeholder', 'soledad' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Your Message Here', 'soledad' ),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'show_additional_message',
			[
				'label' => esc_html__( 'Additional Bottom Message', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'additional_message',
			[
				'label'       => esc_html__( 'Message', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Note: You have to fill-up above all respective field, then click below button for send your message', 'soledad' ),
				'condition'   => [
					'show_additional_message' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_recaptcha',
			[
				'label'       => esc_html__( 'reCAPTCHA Enable', 'soledad' ),
				'description' => __( 'Please follow <a target="_blank" href="https://www.google.com/recaptcha/admin">this link</a> to get the reCAPTCHA keys for your site.', 'soledad' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
			]
		);

		$this->add_control(
			'recaptcha_site_key',
			[
				'label'       => esc_html__( 'reCAPTCHA Site Key', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'condition'   => [
					'show_recaptcha' => 'yes',
				],
			]
		);

		$this->add_control(
			'recaptcha_secret_key',
			[
				'label'       => esc_html__( 'reCAPTCHA Secret Key', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'condition'   => [
					'show_recaptcha' => 'yes',
				],
			]
		);

		$this->add_control(
			'hide_recaptcha_badge',
			[
				'label'     => esc_html__( 'Hide reCAPTCHA Bagde', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_recaptcha' => 'yes',
				],
			]
		);

		$this->add_control(
			'message_rows',
			[
				'label'   => esc_html__( 'Message Rows', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '5',
				'options' => [
					'1'  => '1',
					'2'  => '2',
					'3'  => '3',
					'4'  => '4',
					'5'  => '5',
					'6'  => '6',
					'7'  => '7',
					'8'  => '8',
					'9'  => '9',
					'10' => '10',
				],
			]
		);

		$this->add_control(
			'redirect_after_submit',
			[
				'label'     => esc_html__( 'Redirect After Submit', 'soledad' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'redirect_url',
			[
				'type'          => Controls_Manager::URL,
				'show_label'    => false,
				'show_external' => false,
				'separator'     => false,
				'placeholder'   => 'http://your-link.com/',
				'description'   => esc_html__( 'Note: Because of security reasons, you can ONLY use your current domain here.', 'soledad' ),
				'condition'     => [
					'redirect_after_submit' => 'yes',
				],
				'dynamic'       => [ 'active' => true ],
			]
		);

		$this->add_control(
			'reset_after_submit',
			[
				'label' => esc_html__( 'Reset After Submit', 'soledad' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'transhelp',
			[
				'raw'             => __( 'Please follow <a target="_blank" href="' . admin_url( 'customize.php?autofocus[section]=pencidesign_new_section_transition_lang_section' ) . '">this link</a> to edit the form confirmation content.', 'soledad' ),
				'type'            => Controls_Manager::RAW_HTML,
				'separator'       => 'before',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_email_settings',
			[
				'label' => esc_html__( 'Email Settings', 'soledad' ),
			]
		);

		$this->add_control(
			'custom_email_to',
			[
				'label'       => esc_html__( 'Custom Received Email', 'soledad' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'contact_form_spam_email',
			[
				'label'       => esc_html__( 'Never Received Email from Email on This List', 'soledad' ),
				'description' => esc_html__( 'Separate email by commas', 'soledad' ),
				'type'        => Controls_Manager::TEXTAREA,
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
				'label'     => esc_html__( 'Rows Gap', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .pencif-field-group:not(:last-child)'                  => 'margin-bottom: {{SIZE}}{{UNIT}};margin-top: 0;',
					'{{WRAPPER}} form.pencif-2col .pencif-col .pencif-field-group'      => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pencif-name-email-inline + .pencif-name-email-inline' => 'padding-left: {{SIZE}}px',
				],
			]
		);
		
		$this->add_responsive_control(
			'col_gap',
			[
				'label'     => esc_html__( 'Columns Gap', 'soledad' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'condition' => [
					'two_columns' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} form.pencif-2col'                  => 'column-gap: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .pencif-field-group > label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Text Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pencif-form-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .pencif-form-label',
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
					'{{WRAPPER}} .pencif-field-group .pencif-input' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pencif-field-group textarea'      => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_placeholder_color',
			[
				'label'     => esc_html__( 'Placeholder Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pencif-field-group .pencif-input::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pencif-field-group textarea::placeholder'      => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pencif-field-group .pencif-input' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pencif-field-group textarea'      => 'background-color: {{VALUE}};',
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
				'selector'    => '{{WRAPPER}} .pencif-field-group .pencif-input, {{WRAPPER}} .pencif-field-group textarea',
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
					'{{WRAPPER}} .pencif-field-group .pencif-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .pencif-field-group textarea'      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'field_box_shadow',
				'selector' => '{{WRAPPER}} .pencif-field-group .pencif-input, {{WRAPPER}} .pencif-field-group textarea',
			]
		);

		$this->add_responsive_control(
			'field_padding',
			[
				'label'      => esc_html__( 'Padding', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pencif-field-group .pencif-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
					'{{WRAPPER}} .pencif-field-group textarea'      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'field_typography',
				'label'     => esc_html__( 'Typography', 'soledad' ),
				'selector'  => '{{WRAPPER}} .pencif-field-group .pencif-input, {{WRAPPER}} .pencif-field-group textarea',
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
					'{{WRAPPER}} .pencif-field-group .pencif-input:focus' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pencif-field-group textarea:focus'      => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_focus_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pencif-field-group .pencif-input:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pencif-field-group textarea:focus'      => 'border-color: {{VALUE}};',
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
				'label' => esc_html__( 'Submit Button', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .pencif-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .pencif-button',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_background_color',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .pencif-button'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pencif-button',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'soledad' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pencif-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .pencif-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .pencif-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_background_hover_color',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .pencif-button:hover'
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .pencif-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'soledad' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_style',
			[
				'label'     => esc_html__( 'Additional Message', 'soledad' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_additional_message!' => '',
				],
			]
		);

		$this->add_control(
			'additional_text_color',
			[
				'name'      => 'additional_text_color',
				'label'     => esc_html__( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pencif-contact-form-additional-message' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'additional_text_typography',
				'selector' => '{{WRAPPER}} .pencif-contact-form-additional-message',
			]
		);

		$this->end_controls_section();
	}

	public function form_fields_render_attributes() {
		$settings        = $this->get_settings_for_display();
		$id              = $this->get_id();

		if ( ! empty ( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'pencif-button-' . $settings['button_size'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute(
			[
				'wrapper'      => [
					'class' => [
						'penci-form-fields-wrapper',
					],
				],
				'field-group'  => [
					'class' => [
						'pencif-field-group',
						'pencif-width-1-1',
					],
				],
				'submit-group' => [
					'class' => [
						'elementor-field-type-submit',
						'pencif-field-group',
						'pencif-flex',
						'pencif-width-1-1',
					],
				],

				'button'              => [
					'class' => [
						'penci-button',
						'pencif-button',
						'pencif-button-primary',
					],
					'name'  => 'submit',
				],
				'user_name_label'     => [
					'for'   => 'user_name' . $id,
					'class' => [
						'pencif-form-label',
					]
				],
				'contact_label'       => [
					'for'   => 'contact' . $id,
					'class' => [
						'pencif-form-label',
					]
				],
				'subject_label'       => [
					'for'   => 'subject' . $id,
					'class' => [
						'pencif-form-label',
					]
				],
				'email_address_label' => [
					'for'   => 'email' . $id,
					'class' => [
						'pencif-form-label',
					]
				],
				'message_label'       => [
					'for'   => 'message' . $id,
					'class' => [
						'pencif-form-label',
					]
				],
				'user_name_input'     => [
					'type'        => 'text',
					'name'        => 'name',
					'id'          => 'user_name' . $id,
					'placeholder' => ( $settings['user_name_placeholder'] ) ? $settings['user_name_placeholder'] : esc_html__( 'Your Name', 'soledad' ),
					'class'       => [
						'pencif-input',
					],
				],
				'contact_input'       => [
					'type'        => 'tel',
					'name'        => 'contact',
					'id'          => 'contact' . $id,
					'placeholder' => ( $settings['contact_placeholder'] ) ? $settings['contact_placeholder'] : esc_html__( 'Your Contact Number', 'soledad' ),
					'class'       => [
						'pencif-input',
					],
				],
				'subject_input'       => [
					'type'        => 'text',
					'name'        => 'subject',
					'id'          => 'subject' . $id,
					'placeholder' => ( $settings['subject_placeholder'] ) ? $settings['subject_placeholder'] : esc_html__( 'Your Message Subject', 'soledad' ),
					'class'       => [
						'pencif-input',
					],
				],
				'email_address_input' => [
					'type'        => 'email',
					'name'        => 'email',
					'id'          => 'email' . $id,
					'placeholder' => ( $settings['email_placeholder'] ) ? $settings['email_placeholder'] : esc_html__( 'example@email.com', 'soledad' ),
					'class'       => [
						'pencif-input'
					],
				],
				'message_area'        => [
					'name'        => 'message',
					'id'          => 'message' . $id,
					'rows'        => $settings['message_rows'],
					'placeholder' => ( $settings['message_placeholder'] ) ? $settings['message_placeholder'] : esc_html__( 'Your Message Here', 'soledad' ),
					'class'       => [
						'pencif-textarea',
					],
				],
			]
		);

		if ( isset ( $settings['show_recaptcha'] ) && $settings['show_recaptcha'] == 'yes' ) {
			if ( ! empty ( $settings['recaptcha_site_key'] ) and ! empty ( $settings['recaptcha_secret_key'] ) ) {
				$this->add_render_attribute( 'button', 'data-sitekey', $settings['recaptcha_site_key'] );
				$this->add_render_attribute( 'button', 'data-callback', 'PenciCFormCheckGICF' );
				$this->add_render_attribute( 'button', 'class', 'g-recaptcha' );
			}
		}

		if ( ! $settings['show_labels'] ) {
			$this->add_render_attribute( 'label', 'class', 'elementor-screen-only' );
		}

		$this->add_render_attribute( 'field-group', 'class', 'elementor-field-required' )
		     ->add_render_attribute( 'input', 'required', 'required' )
		     ->add_render_attribute( 'input', 'aria-required', 'true' );
	}


	public function render() {
		$this->form_fields_render_attributes();

		?>
        <div class="pencif-contact-form">
            <div class="penci-form-fields-wrapper">
				<?php $this->contact_form_html(); ?>
            </div>
        </div>
		<?php
	}

	public function contact_form_html() {
		$settings = $this->get_settings_for_display();
		$id       = $this->get_id();
		$form_id  = ! empty ( $settings['_element_id'] ) ? 'pencif-sf-' . $settings['_element_id'] : 'pencif-sf-' . $id;

		$this->add_render_attribute( 'contact-form', 'class', [
			'pencif-contact-form',
			$settings['two_columns'] ? 'pencif-2col' : 'pencif-all-col',
			$settings['name_email_field_inline'] ? 'pencif-name-inline' : '',
		] );
		$this->add_render_attribute( 'contact-form', 'action', admin_url( 'admin-ajax.php' ) );
		$this->add_render_attribute( 'contact-form', 'method', 'post' );


		if ( isset ( $settings['show_recaptcha'] ) && $settings['show_recaptcha'] == 'yes' ) {
			if ( empty ( $settings['recaptcha_site_key'] ) and empty ( $settings['recaptcha_secret_key'] ) ) {
				$this->add_render_attribute( 'contact-form', 'class', 'without-recaptcha' );
			}
		} else {
			$this->add_render_attribute( 'contact-form', 'class', 'without-recaptcha' );
		}


		$this->add_render_attribute( 'name-email-field-group', 'class', [
			'pencif-field-group',
			'elementor-field-required'
		] );

		$this->add_render_attribute( 'name-email-field-group', 'class', 'pencif-field-normal' );


		?>
        <div class="pencif-contact-form-wrapper">
            <form <?php $this->print_render_attribute_string( 'contact-form' ); ?>>

				<?php if ( $settings['two_columns'] ) : ?>
                <div class="pencif-col">
					<?php endif; ?>

                    <div <?php $this->print_render_attribute_string( 'name-email-field-group' ); ?>>
						<?php
						if ( $settings['show_labels'] ) {
							echo '<label ' . wp_kses_post( $this->get_render_attribute_string( 'user_name_label' ) ) . '>' . wp_kses_post( $settings['user_name_label'] ) . '</label>';
						}


						echo '<div class="pencif-form-controls">';
						echo '<input ' . wp_kses_post( $this->get_render_attribute_string( 'user_name_input' ) ) . ' required ="required">';
						echo '</div>';

						?>
                    </div>

                    <div <?php $this->print_render_attribute_string( 'name-email-field-group' ); ?>>
						<?php
						if ( $settings['show_labels'] ) {
							echo '<label ' . wp_kses_post( $this->get_render_attribute_string( 'email_address_label' ) ) . '>' . wp_kses_post( $settings['email_address_label'] ) . '</label>';
						}

						echo '<div class="pencif-form-controls">';
						echo '<input ' . wp_kses_post( $this->get_render_attribute_string( 'email_address_input' ) ) . ' required="required">';
						echo '</div>';
						?>
                    </div>

					<?php if ( $settings['contact_number'] ) : ?>
                        <div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
							<?php

							if ( $settings['show_labels'] ) {
								echo '<label ' . wp_kses_post( $this->get_render_attribute_string( 'contact_label' ) ) . '>' . wp_kses_post( $settings['contact_label'] ) . '</label>';
							}
							echo '<div class="pencif-form-controls">';
							echo '<input ' . wp_kses_post( $this->get_render_attribute_string( 'contact_input' ) ) . '>';
							echo '</div>';

							?>
                        </div>
					<?php endif; ?>

					<?php if ( $settings['show_subject'] ) : ?>
                        <div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
							<?php
							if ( $settings['show_labels'] ) {
								echo '<label ' . wp_kses_post( $this->get_render_attribute_string( 'subject_label' ) ) . '>' . wp_kses_post( $settings['subject_label'] ) . '</label>';
							}
							echo '<div class="pencif-form-controls">';
							echo '<input ' . wp_kses_post( $this->get_render_attribute_string( 'subject_input' ) ) . ' required="required">';
							echo '</div>';

							?>
                        </div>
					<?php endif; ?>

					<?php if ( $settings['two_columns'] ) : ?>
                </div>
                <div class="pencif-col">
					<?php endif; ?>

					<?php if ( $settings['show_message'] ) : ?>
                        <div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
							<?php
							if ( $settings['show_labels'] ) {
								echo '<label ' . wp_kses_post( $this->get_render_attribute_string( 'message_label' ) ) . '>' . wp_kses_post( $settings['message_label'] ) . '</label>';
							}
							echo '<div class="pencif-form-controls">';
							echo '<textarea ' . wp_kses_post( $this->get_render_attribute_string( 'message_area' ) ) . ' required="required"></textarea>';
							echo '</div>';
							?>
                        </div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_additional_message'] ) : ?>
                        <div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
							<span class="pencif-contact-form-additional-message">
								<?php echo esc_html( $settings['additional_message'] ); ?>
							</span>
                        </div>
					<?php endif; ?>


					<?php if ( ( $settings['redirect_after_submit'] == 'yes' ) && ! empty ( $settings['redirect_url']['url'] ) ) :
						$redirect_url = $settings['redirect_url']['url'];
						$redirect_extarnal = ( isset ( $settings['redirect_url']['is_external'] ) && ( $settings['redirect_url']['is_external'] ) == 'on' ) ? '_blank' : '_self';
						?>
                        <input type="hidden" name="redirect-url" value="<?php
						echo esc_url( $redirect_url ) ?>"/>

                        <input type="hidden" name="is-external" value="<?php
						echo esc_html( $redirect_extarnal ) ?>"/>

					<?php endif; ?>

					<?php if ( $settings['reset_after_submit'] == 'yes' ) : ?>
                        <input type="hidden" name="reset-after-submit"
                               value="<?php echo wp_kses_post( $settings['reset_after_submit'] ); ?>"/>
					<?php endif; ?>

                    <input type="hidden" class="widget_id" name="widget_id" value="<?php
					echo esc_attr( $id ); ?>"/>
                    <input type="hidden" name="<?php echo esc_attr( $form_id ); ?>" value="true"/>
                    <input type="hidden" class="page_id" name="page_id"
                           value="<?php echo esc_attr( get_the_ID() ); ?>"/>

                    <div <?php $this->print_render_attribute_string( 'submit-group' ); ?>>
                        <button type="submit" <?php $this->print_render_attribute_string( 'button' ); ?>>
							<?php if ( ! empty ( $settings['button_text'] ) ) : ?>
                                <span>
									<?php echo esc_html( $settings['button_text'] ); ?>
								</span>
							<?php endif; ?>
                        </button>
                    </div>

					<?php if ( isset ( $settings['show_recaptcha'] ) && $settings['show_recaptcha'] == 'yes' && $settings['recaptcha_site_key'] ) : ?>

					<input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">
					<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $settings['recaptcha_site_key'];?>"></script>
					<script>
						grecaptcha.ready(function() {
							grecaptcha.execute('<?php echo $settings['recaptcha_site_key'];?>', { action: 'submit' }).then(function(token) {
								document.getElementById('recaptchaResponse').value = token;
							});
						});
					</script>

					<?php endif; ?>

                    <input name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( "simpleContactForm" ) ); ?>"
                           type="hidden">

                    <input type="hidden" name="action" value="penci_contact_form"/>

					<?php if ( $settings['two_columns'] ) : ?>
                </div>
			<?php endif; ?>

            </form>
        </div>
		<?php
	}
}