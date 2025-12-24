<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class PenciSingleToc extends \Elementor\Widget_Base {

	public function get_name() {
		return 'penci-single-toc';
	}

	public function get_title() {
		return esc_html__( 'Post - Table of Content', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	public function get_categories() {
		return [ 'penci-single-builder' ];
	}

	public function get_keywords() {
		return [ 'single', 'toc', 'table' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'General', 'soledad' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label'    => esc_html__( 'HTMl Tag', 'soledad' ),
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'default'  => 'h2',
				'options'  => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				]
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label'   => esc_html__( 'Heading Title', 'soledad' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Table of content', 'soledad' )
			]
		);

		$this->add_control(
			'marker_view',
			[
				'label'   => esc_html__( 'Marker View', 'soledad' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'number',
				'options' => [
					'none'    => 'None',
					'number'  => 'Number',
					'bullets' => 'Bullets',
				]
			]
		);

		$this->add_responsive_control(
			'table_width',
			[
				'label'     => esc_html__( 'Table of Content Width', 'soledad' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 200,
						'max' => 1200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .penci-toc-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'color_style',
			[
				'label' => esc_html__( 'Color & Styles', 'soledad' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'toc-heading-genr-style',
			[
				'label' => 'General Style',
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'toc-bg',
			[
				'label'     => 'General Background Color',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-toc-wrapper' => 'background-color:{{VALUE}}' ],
			]
		);

		$this->add_control(
			'toc-border-cl',
			[
				'label'     => 'General Borders Color',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-toc ol ol, {{WRAPPER}} .penci-toc ol li, {{WRAPPER}} .penci-toc ul li, {{WRAPPER}} .penci-toc ul ul, {{WRAPPER}} .penci-toc-wrapper .penci-toc-head' => 'border-color:{{VALUE}}' ],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(), array(
				'name'     => 'toc_typo',
				'label'    => __( 'Typography for Heading', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-toc-wrapper .penci-toc-head',
			)
		);

		$this->add_control(
			'toc-heading-color',
			[
				'label'     => 'Heading Color',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-toc-wrapper .penci-toc-head' => 'color:{{VALUE}}' ],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(), array(
				'name'     => 'toc_1st_class',
				'label'    => __( 'Typography for Parent Heading', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-toc ul a, {{WRAPPER}} .penci-toc ol a',
			)
		);

		$this->add_control(
			'toc_1st_color',
			[
				'label'     => 'Parent Color',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-toc ul > li > a, {{WRAPPER}} .penci-toc ol > li > a' => 'color:{{VALUE}}' ],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(), array(
				'name'     => 'toc_child_class',
				'label'    => __( 'Typography for Child Heading', 'soledad' ),
				'selector' => '{{WRAPPER}} .penci-toc ul ul a, {{WRAPPER}} .penci-toc ol ol a',
			)
		);

		$this->add_control(
			'toc_child_color',
			[
				'label'     => 'Child Color',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .penci-toc ul ul a, {{WRAPPER}} .penci-toc ol ol a' => 'color:{{VALUE}}' ],
			]
		);

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
		$settings = $this->get_settings();
		$marker   = $settings['marker_view'];
		?>
        <div class="penci-toc-wrapper style-<?php echo esc_attr( $marker ); ?>">
            <div class="penci-toc-head"><?php echo esc_attr( $settings['heading_title'] ); ?></div>

            <nav class="js-toc penci-toc">
                <ol class="toc-list ">
                    <li class="toc-list-item is-active-li"><a href="#"
                                                              class="toc-link node-name--H2  is-active-link">Parent
                            Title</a>
                        <ol class="toc-list  is-collapsible">
                            <li class="toc-list-item"><a href="#" class="toc-link node-name--H3 ">Child name - 1</a>
                            </li>
                            <li class="toc-list-item"><a href="#" class="toc-link node-name--H3 ">Child name - 2</a>
                            </li>
                            <li class="toc-list-item"><a href="#" class="toc-link node-name--H3 ">Child name - 3</a>
                            </li>
                        </ol>
                    </li>
                    <li class="toc-list-item"><a href="#" class="toc-link node-name--H2 ">Parent Title</a></li>
                    <li class="toc-list-item"><a href="#" class="toc-link node-name--H2 ">Parent Title</a>
                        <ol class="toc-list is-collapsible is-collapsed">
                            <li class="toc-list-item"><a href="#" class="toc-link node-name--H3 ">Child name - 1</a>
                            </li>
                        </ol>
                    </li>
                    <li class="toc-list-item"><a href="#" class="toc-link node-name--H2 ">Parent Title</a>
                        <ol class="toc-list  is-collapsible is-collapsed">
                            <li class="toc-list-item"><a href="#" class="toc-link node-name--H3 ">Child name - 1</a>
                            </li>
                            <li class="toc-list-item"><a href="#" class="toc-link node-name--H3 ">Child name - 2</a>
                                <ol class="toc-list  is-collapsible is-collapsed">
                                    <li class="toc-list-item"><a href="#" class="toc-link node-name--H4 ">Sub Child name
                                            - 1</a></li>
                                    <li class="toc-list-item"><a href="#"
                                                                 class="toc-link node-name--H4 ">Sub Child name - 2</a>
                                    </li>
                                    <li class="toc-list-item"><a href="#"
                                                                 class="toc-link node-name--H4 ">Sub Child name - 3</a>
                                    </li>
                                </ol>
                            </li>
                        </ol>
                    </li>
                    <li class="toc-list-item"><a href="#" class="toc-link node-name--H2 ">Parent Title</a>
                    </li>
                </ol>
            </nav>
        </div>
		<?php
	}

	protected function builder_content() {
		$settings      = $this->get_settings();
		$tags          = $settings['html_tag'];
		$marker        = $settings['marker_view'];
		$validate_tags = is_array( $tags ) ? implode( ',', $settings['html_tag'] ) : $settings['html_tag'];
		wp_enqueue_script( 'penci-toc', PENCI_SOLEDAD_URL . '/js/jquery.toc.js', '', PENCI_SOLEDAD_VERSION, true );
		wp_add_inline_script( 'penci-toc', 'jQuery(".js-toc").toc({content: "div.post-entry", headings: "' . $validate_tags . '"})' );
		?>
        <div class="penci-toc-wrapper style-<?php echo esc_attr( $marker ); ?>">
            <div class="penci-toc-head"><?php echo esc_attr( $settings['heading_title'] ); ?></div>
            <nav class="penci-toc">
                <ul class="js-toc"></ul>
            </nav>
        </div>
		<?php
	}
}
